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
        $dbConnection = UserModel::getDatabaseConnection(); 
        $sql = "INSERT INTO students (studentID, username, firstname, lastname, email, password, location, phoneNumber)
                VALUES (:studentID, :username, :firstname, :lastname, :email, :password, :location, :phoneNumber)";
        $params = [
            ':studentID' => $student->getStudentID(),
            ':username' => $student->username,
            ':firstname' => $student->firstname,
            ':lastname' => $student->lastname,
            ':email' => $student->email,
            ':password' => $student->password,
            ':location' => $student->location, 
            ':phoneNumber' => $student->phoneNumber
        ];
        return $dbConnection->execute($sql, $params);
    }

    // Read
    public static function retrieve($studentID): ?StudentModel {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "SELECT * FROM students WHERE studentID = :studentID";
        $params = [':studentID' => $studentID];
        $result = $dbConnection->query($sql, $params);
        if ($result) {
            return new StudentModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['studentID'],
                $result['email'],
                $result['password'],
                $result['location'],
                $result['phoneNumber'],
                new ISubject()
            );
        }
        return null;
    }

    // Update
    public static function update($student): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "UPDATE students SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber 
                WHERE studentID = :studentID";
        $params = [
            ':studentID' => $student->getStudentID(),
            ':username' => $student->username,
            ':firstname' => $student->firstname,
            ':lastname' => $student->lastname,
            ':email' => $student->email,
            ':password' => $student->password,
            ':location' => $student->location,
            ':phoneNumber' => $student->phoneNumber
        ];
        return $dbConnection->execute($sql, $params);
    }

    // Delete
    public static function delete($studentID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "DELETE FROM students WHERE studentID = :studentID";
        $params = [':studentID' => $studentID];
        return $dbConnection->execute($sql, $params);
    }
}

?>
