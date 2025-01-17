<?php
require_once 'UserModel.php';
require_once 'Lesson.php';

class StudentModel extends UserModel implements IObserver { 
    private array $enrolledLessons = [];
    private array $lessonCompletionStatus = []; 
    private ISubject $lessonSubject;
    private int $studentID;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        array $enrolledLessons=[],
        array $completedLessons=[],
        int $userID = 0 
    ) {
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            $userID);
            $this->studentID = $userID;
            $this->enrolledLessons = $enrolledLessons;
            $this->lessonCompletionStatus = $completedLessons;
    }

    public function enrollInLesson(LessonModel $lesson): void {
        $this->enrolledLessons[] = $lesson;
        $this->lessonCompletionStatus[$lesson->getLessonId()] = false; 
        $lesson->registerObserver($this);
    }

    public function setLessons(array $lessons): void {
        $this->enrolledLessons = $lessons;
        foreach ($lessons as $lesson) {
            $this->lessonCompletionStatus[$lesson->getLessonId()] = false;
            $lesson->registerObserver($this);
        }
    }

    public function completeLesson(LessonModel $lesson): void {
        $lessonId = $lesson->getLessonId();
        if (isset($this->lessonCompletionStatus[$lessonId])) {
            $this->lessonCompletionStatus[$lessonId] = true;
        } else {
            throw new Exception("Lesson not found in enrolled lessons.");
        }
    }

    public function isLessonCompleted(LessonModel $lesson): bool {
        $lessonId = $lesson->getLessonId();
        return $this->lessonCompletionStatus[$lessonId] ?? false;
    }

    public function getCompletedLessons(): array {
        $completedLessons = [];
        foreach ($this->enrolledLessons as $lesson) {
            if ($this->isLessonCompleted($lesson)) {
                $completedLessons[] = $lesson;
            }
        }
        return $completedLessons;
    }

    public function incrementLessonViews(LessonModel $lesson): void {
        $lesson->incrementViews();
    }

    public function getEnrolledLessons(): array {
        return $this->enrolledLessons;
    }

    public function getLessonProgress(LessonModel $lesson): string {
        if ($this->isLessonCompleted($lesson)) {
            return "Completed";
        }
        return "In Progress";
    }

    public function UpdateStatus(string $status): void {
        echo "Student {$this->studentID} has been notified about the lesson update: $status\n";
    }

    public function getStudentID(): int {
        return $this->studentID;
    }

    public function getLessonSubject(): ISubject {
        return $this->lessonSubject;
    }

    // Create a new Student record in the database

    public static function create($student): bool {
        if (!$student instanceof StudentModel) {
            throw new InvalidArgumentException("Expected instance of StudentModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Insert into the `user` table
            $userSql = "INSERT INTO user (username, firstname, lastname, userID, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $userParams = [
                $student->getUsername(),
                $student->getFirstname(),
                $student->getLastname(),
                $student->getUserID(),
                $student->getEmail(),
                password_hash($student->getPassword(), PASSWORD_DEFAULT),
                json_encode($student->getLocation()),
                $student->getPhoneNumber(),
                1
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert into `user` table.");
            }
    
            // Insert into the `student` table
            $studentSql = "INSERT INTO student (userID, enrolledLessons, completedLessons)
                           VALUES (?, ?, ?)";
            $studentParams = [
                $student->getUserID(),
                json_encode(array_map(fn($lesson) => $lesson->getLessonId(), $student->getEnrolledLessons())),
                json_encode(array_map(fn($lesson) => $lesson->getLessonId(), $student->getCompletedLessons()))
            ];
    
            if (!$dbConnection->execute($studentSql, $studentParams)) {
                throw new Exception("Failed to insert into `student` table.");
            }
    
            return true;
    
        } catch (Exception $e) {
            error_log("Error creating student: " . $e->getMessage());
            return false;
        }
    }
    
    public static function retrieve($userID): ?StudentModel {
        $dbConnection = DatabaseConnection::getInstance();
    
        // Query to retrieve student details joined with user
        $sql = "SELECT * FROM student s
                JOIN user u ON s.userID = u.userID
                WHERE s.userID = ?";
        $params = [$userID];
    
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0];
    
            // Fetch enrolled and completed lessons
            $enrolledLessonIDs = json_decode($row['enrolledLessons'], true) ?? [];
            $completedLessonIDs = json_decode($row['completedLessons'], true) ?? [];
    
            $enrolledLessons = array_map(fn($lessonID) => LessonModel::retrieve($lessonID), $enrolledLessonIDs);
            $completedLessons = array_map(fn($lessonID) => LessonModel::retrieve($lessonID), $completedLessonIDs);
    
            return new StudentModel(
                $row['username'],
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['password'],
                json_decode($row['locationList'], true),
                (int)$row['phoneNumber'],
                $enrolledLessons,
                $completedLessons,
                (int)$row['userID']
            );
        }
    
        return null;
    }
    
    public static function update($student): bool {
        if (!$student instanceof StudentModel) {
            throw new InvalidArgumentException("Expected instance of StudentModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update the `user` table
            $userSql = "UPDATE user SET 
                            username = ?, 
                            firstname = ?, 
                            lastname = ?, 
                            email = ?, 
                            password = ?, 
                            locationList = ?, 
                            phoneNumber = ?
                        WHERE userID = ?";
            $userParams = [
                $student->getUsername(),
                $student->getFirstname(),
                $student->getLastname(),
                $student->getEmail(),
                password_hash($student->getPassword(), PASSWORD_DEFAULT),
                json_encode($student->getLocation()),
                $student->getPhoneNumber(),
                $student->getUserID()
            ];
            $userUpdated = $dbConnection->execute($userSql, $userParams);
    
            // Update the `student` table
            $studentSql = "UPDATE student SET 
                               enrolledLessons = ?, 
                               completedLessons = ?
                           WHERE userID = ?";
            $studentParams = [
                json_encode(array_map(fn($lesson) => $lesson->getLessonId(), $student->getEnrolledLessons())),
                json_encode(array_map(fn($lesson) => $lesson->getLessonId(), $student->getCompletedLessons())),
                $student->getUserID()
            ];
            $studentUpdated = $dbConnection->execute($studentSql, $studentParams);
    
            return $userUpdated && $studentUpdated;
    
        } catch (Exception $e) {
            error_log("Error updating student: " . $e->getMessage());
            return false;
        }
    }
    

    
}

?>
