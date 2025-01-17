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
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        int $salary,
        int $workingHours,
        array $lessons = [],
        int $userID
    ) {
        // Call parent constructor to initialize User properties
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            "Instructor",
            $salary,
            $workingHours,
            $userID
        );

        $this->lessons = $lessons;
    }

    public function addLessons(array $lessons) {

        // Assuming $this->lessons is an array property that holds the lessons

        $this->lessons = array_merge($this->lessons, $lessons);

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
        $sql = "SELECT *
                FROM user u
                LEFT JOIN employee e ON u.userID = e.userID
                LEFT JOIN instructor i ON u.userID = i.userID
                WHERE u.userID = ?";
        
        $params = [$userID];
        $dbConnection = DatabaseConnection::getInstance();
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0]; // Assuming only one record is fetched
    
            return new InstructorModel(
                $row['username'],
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['password'],
                json_decode($row['locationList'], true),
                $row['phoneNumber'],
                $row['salary'],
                $row['workingHours'],
                json_decode($row['lessons'], true),
                $row['userID']
            );
        }
    
        return null;
    }
    
    

    // Update an Instructor record in the database
    public static function update($instructor): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update the `user` table
            $userSql = "UPDATE user SET 
                            username = ?, 
                            firstName = ?, 
                            lastName = ?, 
                            email = ?, 
                            password = ?, 
                            locationList = ?, 
                            phoneNumber = ?
                        WHERE userID = ?";
            
            $userParams = [
                $instructor->getUsername(),
                $instructor->getFirstname(),
                $instructor->getLastname(),
                $instructor->getEmail(),
                password_hash($instructor->getPassword(), PASSWORD_DEFAULT),
                json_encode($instructor->getLocation()),
                $instructor->getPhoneNumber(),
                $instructor->getUserID()
            ];
    
            $userUpdated = $dbConnection->execute($userSql, $userParams);
    
            // Update the `employee` table
            $employeeSql = "UPDATE employee SET 
                                title = ?, 
                                salary = ?, 
                                workingHours = ?
                            WHERE userID = ?";
            
            $employeeParams = [
                $instructor->getTitle(),
                $instructor->getSalary(),
                $instructor->getHoursWorked(),
                $instructor->getUserID()
            ];
    
            $employeeUpdated = $dbConnection->execute($employeeSql, $employeeParams);
    
            // Update the `instructor` table
            $instructorSql = "UPDATE instructor SET 
                                  lessons = ?
                              WHERE userID = ?";
            
            $instructorParams = [
                json_encode($instructor->getLessons()),
                $instructor->getUserID()
            ];
    
            $instructorUpdated = $dbConnection->execute($instructorSql, $instructorParams);
    
            return $userUpdated && $employeeUpdated && $instructorUpdated;
        } catch (Exception $e) {
            error_log("Error updating Instructor: " . $e->getMessage());
            return false;
        }
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
