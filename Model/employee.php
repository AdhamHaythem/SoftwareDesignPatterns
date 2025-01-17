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

    public static function create($employee): bool {
        if (!$employee instanceof EmployeeModel) {
            throw new InvalidArgumentException("Expected instance of Employee");
        }
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            $userSql = "INSERT IGNORE INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $userParams = [
                 $employee->getUserID(),
                 $employee->getUsername(),
                 $employee->getFirstname(),
                 $employee->getLastname(),
                 $employee->getEmail(),
                 password_hash($employee->getPassword(), PASSWORD_DEFAULT), // Hash password
                 json_encode($employee->getLocation()),
                 $employee->getPhoneNumber(),
                 1 // Assuming new employees are active by default
            ];
    
            $userInserted = $dbConnection->execute($userSql, $userParams);
    
            $employeeSql = "INSERT INTO employee (userID, title, salary, workingHours)
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE 
                            title = VALUES(title), 
                            salary = VALUES(salary), 
                            workingHours = VALUES(workingHours)";
    
            $employeeParams = [
                $employee->getUserID(),
                $employee->getTitle(),
                $employee->getSalary(),
                $employee->getHoursWorked()
            ];
    
            $employeeInserted = $dbConnection->execute($employeeSql, $employeeParams);

            return $userInserted && $employeeInserted;
        } catch (Exception $e) {
            error_log("Error creating employee: " . $e->getMessage());
            return false;
        }
    }
    

    public static function retrieve($userID): ?EmployeeModel {
        $dbConnection = UserModel::getDatabaseConnection();
    
        $sql = "SELECT u.userID, u.username, u.firstName, u.lastName, u.email, u.password, u.locationList, u.phoneNumber, u.isActive, 
                       e.title, e.salary, e.workingHours
                FROM user u
                JOIN employee e ON u.userID = e.userID
                WHERE u.userID = :userID";
    
        $params = [$userID];
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            return new EmployeeModel(
                $result['username'],
                $result['firstName'],
                $result['lastName'],
                $result['userID'],
                $result['email'],
                $result['password'],
                json_decode($result['locationList'], true),
                $result['phoneNumber'],
                $result['title'],
                $result['salary'],
                $result['workingHours']
            );
        }
    
        return null; 
    }
    public static function update($employee): bool {
        if (!$employee instanceof EmployeeModel) {
            throw new InvalidArgumentException("Expected instance of EmployeeModel");
        }
    
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            $userSql = "UPDATE user SET 
                            username = :username, 
                            firstName = :firstName, 
                            lastName = :lastName, 
                            email = :email, 
                            password = :password, 
                            locationList = :locationList, 
                            phoneNumber = :phoneNumber
                        WHERE userID = :userID";
    
            $userParams = [
                $employee->getUsername(),
                $employee->getFirstname(),
                $employee->getLastname(),
                $employee->getEmail(),
                password_hash($employee->getPassword(), PASSWORD_DEFAULT),
                json_encode($employee->getLocation()),
                $employee->getPhoneNumber(),
                $employee->getUserID()
            ];
    
            $userUpdated = $dbConnection->execute($userSql, $userParams);
            $employeeSql = "UPDATE employee SET 
                                title = :title, 
                                salary = :salary, 
                                workingHours = :workingHours
                            WHERE userID = :userID";
    
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
    
    public static function delete($userID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            $employeeSql = "DELETE FROM employee WHERE userID = :userID";
            $employeeParams = [$userID];
            $employeeDeleted = $dbConnection->execute($employeeSql, $employeeParams);
    
            $userSql = "DELETE FROM user WHERE userID = :userID";
            $userParams = [$userID];
            $userDeleted = $dbConnection->execute($userSql, $userParams);
    
            return $employeeDeleted && $userDeleted;
        } catch (Exception $e) {
            error_log("Error deleting employee: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
