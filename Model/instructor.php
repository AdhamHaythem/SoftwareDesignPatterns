<?php
require_once 'employee.php';
require_once 'Lesson.php';

class InstructorModel extends EmployeeModel {
    private array $lessons = []; // Array to hold LessonModel instances
    private static DatabaseConnection $dbConnection; // Static database connection variable

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
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
            $password,
            $location,
            $phoneNumber,
            "Instructor",
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
        // Check if the provided object is an instance of the expected model
        $dbConnection = DatabaseConnection::getInstance();
        if (!$instructor instanceof InstructorModel) {
            throw new InvalidArgumentException("Expected instance of InstructorModel");
        }
    
        try {
            // 1. Insert into the `user` table
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $userParams = [
                $instructor->getUserID(),
                $instructor->getUsername(),
                $instructor->getFirstname(),
                $instructor->getLastname(),
                $instructor->getEmail(),
                password_hash($instructor->getPassword(), PASSWORD_DEFAULT), // Hash the password
                json_encode($instructor->getLocation()), // Serialize location as JSON
                $instructor->getPhoneNumber(),
                1 // isActive (true)
            ];
    
            // Execute the insertion into the `user` table
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert into `user` table.");
            }
    
            // 2. Insert into the `employee` table
            $employeeSql = "INSERT INTO employee (userID, title, salary, workingHours)
                            VALUES (?, ?, ?, ?)";
            
            $employeeParams = [
                $instructor->getUserID(),
                $instructor->getTitle(),
                $instructor->getSalary(),
                $instructor->getHoursWorked()
            ];
    
            // Execute the insertion into the `employee` table
            if (!$dbConnection->execute($employeeSql, $employeeParams)) {
                throw new Exception("Failed to insert into `employee` table.");
            }
    
            // 3. Insert into the `instructor` table
            $instructorSql = "INSERT INTO instructor (userID, lessons)
                              VALUES (?, ?)";
            
            $instructorParams = [
                $instructor->getUserID(),
                json_encode($instructor->getLessons()) // Serialize managedEmployees as JSON
            ];
    
            // Execute the insertion into the `instructor` table
            if (!$dbConnection->execute($instructorSql, $instructorParams)) {
                throw new Exception("Failed to insert into `instructor` table.");
            }
    
            // If all insertions are successful, return true
            return true;
        } catch (Exception $e) {
            // Log any errors and return false
            error_log("Error creating instructor: " . $e->getMessage());
            return false;
        }
    }
    

    // Retrieve an Instructor record from the database by userID
    public static function retrieve($userID): ?InstructorModel {
        $sql = "SELECT * FROM instructors WHERE userID = :userID";
        $params = [':userID' => $userID];

        $result = UserModel::getDatabaseConnection()->query($sql, $params);
        if ($result && !empty($result)) {
            return new InstructorModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
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

        return UserModel::getDatabaseConnection()->execute($sql, $params);
    }

    // Delete an Instructor record from the database by userID
    public static function delete($userID): bool {
        $sql = "DELETE FROM instructors WHERE userID = :userID";
        $params = [':userID' => $userID];

        return UserModel::getDatabaseConnection()->execute($sql, $params);
    }

    // Additional Methods for Lessons

    // Add a lesson to the instructor's managed lessons
    public function createLesson(LessonModel $lesson): bool {
        $this->lessons[] = $lesson;
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "INSERT INTO lesson (lessonID,lessonName,lessonSubject,duration,instructorID,views)
                        VALUES (?, ?, ?, ?, ?, ?)";

        $params = [
             $lesson->getLessonId(),
            $lesson->getLessonName(),
            $lesson->getLessonSubject(),
            $lesson->getDuration(),
            $this->getUserID(),
            $lesson->getStudentView(),
    ];
    return $dbConnection->execute($sql, $params);
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
