<?php
require_once 'UserModel.php';
require_once 'LessonModel.php';

class StudentModel extends UserModel implements IObserver { 
    private array $enrolledLessons = [];
    private array $lessonCompletionStatus = []; 
    private ISubject $lessonSubject;
    private int $studentID;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        ISubject $lessonSubject
    ) {
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $password,
            $location,
            $phoneNumber);
            $this->studentID = $userID;
            $this->lessonSubject = $lessonSubject;
            $this->lessonSubject->registerObserver($this);
    }

    public function enrollInLesson(LessonModel $lesson): void {
        $this->enrolledLessons[] = $lesson;
        $this->lessonCompletionStatus[$lesson->getLessonId()] = false; 
        $lesson->registerObserver($this);
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

    public static function create($student): bool {
        if (!$student instanceof StudentModel) {
            throw new InvalidArgumentException("Expected instance of Student");
        }   
    
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            $userSql = "INSERT IGNORE INTO user (username, firstname, lastname, email, password, location, phoneNumber)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $userParams = [
                $student->getUsername(),
                $student->getFirstname(),
                $student->getLastname(),
                $student->getEmail(),
                password_hash($student->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($student->getLocation()), // Serialize location as JSON
                $student->getPhoneNumber()
            ];
    
            $userInserted = $dbConnection->execute($userSql, $userParams);
    
         
            $studentSql = "INSERT INTO student (studentID, userID, username)
                           VALUES (? , ? , ?)
                           ON DUPLICATE KEY UPDATE 
                           username = VALUES(username)";
    
            $studentParams = [
                $student->getStudentID(),
                $student->getUserID(),
                $student->getUsername()
            ];
    
            $studentInserted = $dbConnection->execute($studentSql, $studentParams);
    
            
            return $userInserted && $studentInserted;
        } catch (Exception $e) {
            error_log("Error creating student: " . $e->getMessage());
            return false;
        }
    }
    
    public static function retrieve($studentID): ?StudentModel {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "SELECT * FROM student WHERE studentID = :studentID";
        $params = [$studentID];
        $result = $dbConnection->query($sql, $params);
        
        if ($result) {
            $userID = $result['userID'];
            $userSql = "SELECT * FROM user WHERE userID = :userID";
            $userParams = [$userID];
            $userResult = $dbConnection->query($userSql, $userParams);
    
            if ($userResult) {
                return new StudentModel(
                    $userResult['username'],
                    $userResult['firstname'],
                    $userResult['lastname'],
                    $studentID, 
                    $userResult['email'],
                    '', 
                    json_decode($userResult['location'], true),  // Assuming location is a JSON
                    $userResult['phoneNumber'],
                    new ISubject()  // Assuming you have an instance of ISubject
                );
            }
        }
        return null;
    }
    
    public static function update($student): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
        $userSql = "UPDATE user SET 
                        username = :username, 
                        firstname = :firstname, 
                        lastname = :lastname, 
                        email = :email, 
                        password = :password, 
                        location = :location, 
                        phoneNumber = :phoneNumber 
                    WHERE userID = :userID";
    
        $userParams = [
            $student->getUserID(),
            $student->getUsername(),
            $student->getFirstname(),
            $student->getLastname(),
            $student->getEmail(),
            password_hash($student->getPassword(), PASSWORD_DEFAULT),
            json_encode($student->getLocation()),
            $student->getPhoneNumber()
        ];
    
        $userUpdated = $dbConnection->execute($userSql, $userParams);
    
        $studentSql = "UPDATE student SET 
                           username = :username 
                       WHERE studentID = :studentID";
    
        $studentParams = [
            $student->getStudentID(),
            $student->getUsername()
        ];
    
        $studentUpdated = $dbConnection->execute($studentSql, $studentParams);
    
        return $userUpdated && $studentUpdated;
    }
    

    public static function delete($studentID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        
        $studentSql = "DELETE FROM student WHERE studentID = :studentID";
        $studentParams = [$studentID];
        $studentDeleted = $dbConnection->execute($studentSql, $studentParams);
    
        $sql = "SELECT userID FROM student WHERE studentID = :studentID";
        $params = [$studentID];
        $result = $dbConnection->query($sql, $params);
        
        if ($result) {
            $userID = $result['userID'];
            $userSql = "DELETE FROM user WHERE userID = :userID";
            $userParams = [$userID];
            $userDeleted = $dbConnection->execute($userSql, $userParams);
    
            return $studentDeleted && $userDeleted;
        }
        return $studentDeleted;
    }
    
}

?>
