<?php

require_once 'userModel.php'; 

class EmployeeModel extends UserModel {
    private string $title;
    private int $salary;
    private int $workingHours;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
        int $salary,
        int $workingHours,
        int $userID=0
    ) {
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            $userID
        );

        $this->title = $title;
        $this->salary = $salary;
        $this->workingHours = $workingHours;
    }

    public function requestLeave(DateTime $time, string $reason): bool {
        return true;
    }

    public function reportHoursWorked(int $hours): void {
        $this->workingHours += $hours;
    }

    public function getHoursWorked(): int {
        return $this->workingHours;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getSalary() {
        return $this->salary;
    }

    public function setSalary($salary) {

        $this->salary = $salary;

    }

    public static function create($employee): bool {
        if (!$employee instanceof EmployeeModel) {
            throw new InvalidArgumentException("Expected instance of EmployeeModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Insert into `user` table
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $userParams = [
                $employee->getUserID(),
                $employee->getUsername(),
                $employee->getFirstname(),
                $employee->getLastname(),
                $employee->getEmail(),
                password_hash($employee->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($employee->getLocation()), // Serialize location as JSON
                $employee->getPhoneNumber(),
                1 // isActive (true)
            ];
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert into `user` table.");
            }
    
            // Insert into `employee` table
            $employeeSql = "INSERT INTO employee (userID, title, salary, workingHours)
                            VALUES (?, ?, ?, ?)";
            $employeeParams = [
                $employee->getUserID(),
                $employee->getTitle(),
                $employee->getSalary(),
                $employee->getHoursWorked()
            ];
            if (!$dbConnection->execute($employeeSql, $employeeParams)) {
                throw new Exception("Failed to insert into `employee` table.");
            }
    
            // If both insertions succeed
            return true;
        } catch (Exception $e) {
            error_log("Error creating employee: " . $e->getMessage());
            return false;
        }
    }
    
    public static function retrieve($userID): ?EmployeeModel {
        $dbConnection = DatabaseConnection::getInstance();
    
        // Query to retrieve employee details joined with user
        $sql = "SELECT * FROM employee e
                JOIN user u ON e.userID = u.userID
                WHERE u.userID = ?";
        $params = [$userID];
    
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0]; // Fetch the first result
    
            // Create a new EmployeeModel instance
            return new EmployeeModel(
                $row['username'],                             // username
                $row['firstName'],                            // firstName
                $row['lastName'],                             // lastName
                $row['email'],                                // email
                $row['password'],                             // password
                json_decode($row['locationList'], true),      // location (JSON)
                $row['phoneNumber'],                          // phoneNumber
                $row['title'],                                // title
                $row['salary'],                               // salary
                $row['workingHours'],                         // workingHours
                $row['userID']                                // userID
            );
        }
    
        return null; // Return null if no result is found
    }
    
    public static function update($employee): bool {
        if (!$employee instanceof EmployeeModel) {
            throw new InvalidArgumentException("Expected instance of EmployeeModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update `user` table
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
                $employee->getUsername(),
                $employee->getFirstname(),
                $employee->getLastname(),
                $employee->getEmail(),
                password_hash($employee->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($employee->getLocation()), // Serialize location as JSON
                $employee->getPhoneNumber(),
                $employee->getUserID()
            ];
            $userUpdated = $dbConnection->execute($userSql, $userParams);
    
            // Update `employee` table
            $employeeSql = "UPDATE employee SET 
                                title = ?, 
                                salary = ?, 
                                workingHours = ?
                            WHERE userID = ?";
            $employeeParams = [
                $employee->getTitle(),
                $employee->getSalary(),
                $employee->getHoursWorked(),
                $employee->getUserID()
            ];
            $employeeUpdated = $dbConnection->execute($employeeSql, $employeeParams);
    
            return $userUpdated && $employeeUpdated;
        } catch (Exception $e) {
            error_log("Error updating employee: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>
