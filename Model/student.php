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
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        array $enrolledLessons=[],
        int $userID = 0 
    ) {
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $email,
            $location,
            $password,
            $phoneNumber,
            $userID);
            $this->studentID = $userID;
            $this->enrolledLessons = $enrolledLessons;
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

     // Observer status update

    public function UpdateStatus(string $status): string {
        return $status;
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
        // 1. Insert into the `user` table
        $userSql = "INSERT INTO user (username, firstname, lastname, userID, email, password, locationList, phoneNumber, isActive)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $userParams = [
            $student->getUsername(),
            $student->getFirstname(),
            $student->getLastname(),
            $student->getUserID(),
            $student->getEmail(),
            password_hash($student->getPassword(), PASSWORD_DEFAULT), // Securely hash the password
            json_encode($student->getLocation()), // Serialize location list as JSON
            $student->getPhoneNumber(),
            1 // isActive (true)
        ];

        if (!$dbConnection->execute($userSql, $userParams)) {
            throw new Exception("Failed to insert into `user` table.");
        }

        // 2. Insert into the `student` table
        $studentSql = "INSERT INTO student (userID, studentID, lessonSubject)
                       VALUES (?, ?, ?)";

        $studentParams = [
            $student->getUserID(),
            $student->getStudentID(),
            json_encode($student->getLessonSubject()) // Serialize lessonSubject as JSON
        ];

        if (!$dbConnection->execute($studentSql, $studentParams)) {
            throw new Exception("Failed to insert into `student` table.");
        }

        // If all insertions are successful, return true
        return true;

    } catch (Exception $e) {
        // Log the error and return false
        error_log("Error creating student: " . $e->getMessage());
        return false;
    }
}

// Retrieve a Student record from the database by userID
public static function retrieve($userID): ?StudentModel {
    $dbConnection = DatabaseConnection::getInstance();

    // Query to retrieve student details joined with user
    $sql = "SELECT * FROM student s
            JOIN user u ON s.userID = u.userID
            WHERE s.userID = ?";
    $params = [$userID];

    // Execute the query
    $result = $dbConnection->query($sql, $params);

    if ($result && !empty($result)) {
        $row = $result[0];

        // Validate required fields
        if (
            isset(
                $row['userID'], $row['username'], $row['firstName'], $row['lastName'], 
                $row['email'], $row['password'], $row['locationList'], $row['phoneNumber'],
                $row['lessonSubject']
            )
        ) {
            // Create a new StudentModel instance
            $student = new StudentModel(
                $row['username'],                              // username
                $row['firstName'],                             // firstname
                $row['lastName'],                              // lastname
                $row['email'],                                 // email
                $row['password'],                              // password
                json_decode($row['locationList'], true),       // location
                (int)$row['phoneNumber'],                      // phoneNumber
                json_decode($row['lessonSubject'], true),      // lessonSubject (JSON decoded)
                (int)$row['userID']                            // userID
            );

            return $student;
        } else {
            throw new Exception("Missing required fields in the query result.");
        }
    }

    // Return null if no result is found
    return null;
}

// Update a Student record in the database
public static function update($student): bool {
    if (!$student instanceof StudentModel) {
        throw new InvalidArgumentException("Expected instance of StudentModel");
    }

    // SQL query to update the student table and relevant fields
    $sql = "UPDATE student s
            JOIN user u ON s.userID = u.userID
            SET 
                u.username = ?, 
                u.firstName = ?, 
                u.lastName = ?, 
                u.email = ?, 
                u.password = ?, 
                u.locationList = ?, 
                u.phoneNumber = ?,
                s.lessonSubject = ?
            WHERE s.userID = ?";

    // Bind parameters
    $params = [
        $student->getUsername(),                              // username
        $student->getFirstname(),                             // firstname
        $student->getLastname(),                              // lastname
        $student->getEmail(),                                 // email
        password_hash($student->getPassword(), PASSWORD_DEFAULT), // password (hashed)
        json_encode($student->getLocation()),                // location (JSON encoded)
        $student->getPhoneNumber(),                          // phoneNumber
        json_encode($student->getLessonSubject()),           // lessonSubject (JSON encoded)
        $student->getUserID()                                // userID (where condition)
    ];

    // Get the database connection
    $dbConnection = DatabaseConnection::getInstance();

    // Execute the query
    try {
        return $dbConnection->execute($sql, $params);
    } catch (Exception $e) {
        // Log the error for debugging
        error_log("Error updating student record: " . $e->getMessage());
        return false;
    }
}

    
}

?>
