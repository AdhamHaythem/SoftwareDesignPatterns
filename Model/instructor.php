<?php
require_once 'employee.php';
require_once 'LessonModel.php';

class InstructorModel extends EmployeeModel {
    private array $lessons = []; // Array to hold LessonModel instances
    private static DatabaseConnection $dbConnection; // Static database connection variable

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
        int $salary,
        int $workingHours
    ) {
        // Call parent constructor to initialize User properties
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $usernameID,
            $password,
            $location,
            $phoneNumber,
            $title,
            $salary,
            $workingHours
        );
    }

    // Setter for database connection
    public static function setDatabaseConnection(DatabaseConnection $dbConnection): void {
        self::$dbConnection = $dbConnection;
    }

    // Getter for database connection
    public static function getDatabaseConnection(): DatabaseConnection {
        return self::$dbConnection;
    }

    // CRUD Methods for InstructorModel

    // Create a new Instructor record in the database
    public static function create($instructor): bool {
        $sql = "INSERT INTO instructors (userID, username, firstname, lastname, email, password, location, phoneNumber, title, salary, workingHours)
                VALUES (:userID, :username, :firstname, :lastname, :email, :password, :location, :phoneNumber, :title, :salary, :workingHours)";

        $params = [
            ':userID' => $instructor->getUserID(),
            ':username' => $instructor->getUsername(),
            ':firstname' => $instructor->getFirstname(),
            ':lastname' => $instructor->getLastname(),
            ':email' => $instructor->getEmail(),
            ':password' => password_hash($instructor->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($instructor->getLocation()), // Assuming location is an array
            ':phoneNumber' => $instructor->getPhoneNumber(),
            ':title' => $instructor->getTitle(),
            ':salary' => $instructor->getSalary(),
            ':workingHours' => $instructor->getHoursWorked()
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    // Retrieve an Instructor record from the database by userID
    public static function retrieve($userID): ?InstructorModel {
        $sql = "SELECT * FROM instructors WHERE userID = :userID";
        $params = [':userID' => $userID];

        $result = self::$dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new InstructorModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['usernameID'],
                $result['password'],
                json_decode($result['location'], true),
                $result['phoneNumber'],
                $result['title'],
                $result['salary'],
                $result['workingHours']
            );
        }

        return null;
    }

    // Update an Instructor record in the database
    public static function update($instructor): bool {
        $sql = "UPDATE instructors SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber, 
                    title = :title, 
                    salary = :salary, 
                    workingHours = :workingHours 
                WHERE userID = :userID";

        $params = [
            ':username' => $instructor->getUsername(),
            ':firstname' => $instructor->getFirstname(),
            ':lastname' => $instructor->getLastname(),
            ':email' => $instructor->getEmail(),
            ':password' => password_hash($instructor->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($instructor->getLocation()), // Assuming location is an array
            ':phoneNumber' => $instructor->getPhoneNumber(),
            ':title' => $instructor->getTitle(),
            ':salary' => $instructor->getSalary(),
            ':workingHours' => $instructor->getWorkingHours(),
            ':userID' => $instructor->getUserID()
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    // Delete an Instructor record from the database by userID
    public static function delete($userID): bool {
        $sql = "DELETE FROM instructors WHERE userID = :userID";
        $params = [':userID' => $userID];

        return self::$dbConnection->execute($sql, $params);
    }

    // Additional Methods for Lessons

    // Add a lesson to the instructor's managed lessons
    public function createLesson(LessonModel $lesson): bool {
        $this->lessons[] = $lesson;
        return true;
    }

    // Retrieve a lesson by its ID
    public function retrieveLesson(int $lessonId): ?LessonModel {
        foreach ($this->lessons as $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                return $lesson;
            }
        }
        return null;
    }

    // Update an existing lesson
    public function updateLesson(int $lessonId, array $newData): bool {
        foreach ($this->lessons as $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                if (isset($newData['lessonName'])) {
                    $lesson->setLessonName($newData['lessonName']);
                }
                if (isset($newData['lessonSubject'])) {
                    $lesson->setLessonSubject($newData['lessonSubject']);
                }
                if (isset($newData['duration'])) {
                    $lesson->setDuration($newData['duration']);
                }
                return true;
            }
        }
        return false;
    }

    // Delete a lesson by its ID
    public function deleteLesson(int $lessonId): bool {
        foreach ($this->lessons as $index => $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                unset($this->lessons[$index]);
                $this->lessons = array_values($this->lessons);
                return true;
            }
        }
        return false;
    }

    // Retrieve all lessons managed by the instructor
    public function getLessons(): array {
        return $this->lessons;
    }
}
?>
