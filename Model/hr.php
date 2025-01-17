<?php
require_once 'employee.php';
require_once 'DonorModel.php';

class hrModel extends EmployeeModel {
    private array $managedEmployees = [];
    private array $Donors = [];

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
        array $managedEmployees = [],
        $userID=0
    ) {
        parent::__construct(    
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            "HR",
            $salary,
            $workingHours,
            $userID
        );
        $this->managedEmployees = $managedEmployees;
    }

    // CRUD Methods
    public static function create($hr): bool {
        // Check if the provided object is an instance of the expected model
        $dbConnection = DatabaseConnection::getInstance();
        if (!$hr instanceof hrModel) {
            throw new InvalidArgumentException("Expected instance of hrModel");
        }
    
        try {
            // 1. Insert into the `user` table
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $userParams = [
                $hr->getUserID(),
                $hr->getUsername(),
                $hr->getFirstname(),
                $hr->getLastname(),
                $hr->getEmail(),
                password_hash($hr->getPassword(), PASSWORD_DEFAULT), // Hash the password
                json_encode($hr->getLocation()), // Serialize location as JSON
                $hr->getPhoneNumber(),
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
                $hr->getUserID(),
                $hr->getTitle(),
                $hr->getSalary(),
                $hr->getHoursWorked()
            ];
    
            // Execute the insertion into the `employee` table
            if (!$dbConnection->execute($employeeSql, $employeeParams)) {
                throw new Exception("Failed to insert into `employee` table.");
            }
    
            // 3. Insert into the `hr` table
            $hrSql = "INSERT INTO hr (userID, managedEmployees)
                      VALUES (?, ?)";
            
            $hrParams = [
                $hr->getUserID(),
                json_encode($hr->getManagedEmployees()) // Serialize managedEmployees as JSON
            ];
    
            // Execute the insertion into the `hr` table
            if (!$dbConnection->execute($hrSql, $hrParams)) {
                throw new Exception("Failed to insert into `hr` table.");
            }
    
            // If all insertions are successful, return true
            return true;
        } catch (Exception $e) {
            // Log any errors and return false
            error_log("Error creating HR: " . $e->getMessage());
            return false;
        }
    }
    
    

    public static function retrieve($userID): ?hrModel {
        $sql = "SELECT * 
                FROM user u
                JOIN employee e ON u.userID = e.userID
                JOIN hr h ON u.userID = h.userID
                WHERE u.userID = ?";
        
        $params = [$userID];
        $dbConnection = DatabaseConnection::getInstance();
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0];
    
            return new hrModel(
                $row['username'],
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['password'],
                json_decode($row['locationList'], true),
                (int) $row['phoneNumber'],
                (int) $row['salary'],
                (int) $row['workingHours'],
                json_decode($row['managedEmployees'], true),
                (int) $row['userID']
            );
        }
    
        return null;
    }
    
    public static function update($hr): bool {
        if (!$hr instanceof hrModel) {
            throw new InvalidArgumentException("Expected instance of hrModel");
        }
    
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
                            phoneNumber = ?, 
                            isActive = ?
                        WHERE userID = ?";
            
            $userParams = [
                $hr->getUsername(),
                $hr->getFirstname(),
                $hr->getLastname(),
                $hr->getEmail(),
                password_hash($hr->getPassword(), PASSWORD_DEFAULT),
                json_encode($hr->getLocation()),
                $hr->getPhoneNumber(),
                1, // isActive
                $hr->getUserID()
            ];
    
            $userUpdated = $dbConnection->execute($userSql, $userParams);
    
            // Update the `employee` table
            $employeeSql = "UPDATE employee SET 
                                title = ?, 
                                salary = ?, 
                                workingHours = ?
                            WHERE userID = ?";
            
            $employeeParams = [
                $hr->getTitle(),
                $hr->getSalary(),
                $hr->getHoursWorked(),
                $hr->getUserID()
            ];
    
            $employeeUpdated = $dbConnection->execute($employeeSql, $employeeParams);
    
            // Update the `hr` table
            $hrSql = "UPDATE hr SET 
                          managedEmployees = ?
                      WHERE userID = ?";
            
            $hrParams = [
                json_encode($hr->getManagedEmployees()),
                $hr->getUserID()
            ];
    
            $hrUpdated = $dbConnection->execute($hrSql, $hrParams);
            return $userUpdated && $employeeUpdated && $hrUpdated;
        } catch (Exception $e) {
            error_log("Error updating HR: " . $e->getMessage());
            return false;
        }
    }


    public function addEmployees(array $employeeIDs) {

        foreach ($employeeIDs as $employeeID) {

            if (!in_array($employeeID, $this->managedEmployees)) {

                $this->managedEmployees[] = $employeeID;

            }

        }

    }

    
    
    
    

    public function addEmployee(EmployeeModel $employee) {
        $this->managedEmployees[] = $employee;
    }

    public function getManagedEmployees(): array {
        return $this->managedEmployees;
    }

    public function recruitVolunteer(Donor $donor) {
        $this->Donors[] = $donor;
    }

    public function getVolunteers(): array {
        return $this->Donors;
    }

    public function processPayment(EmployeeModel $employee): int {
        $title = $employee->getTitle();
        $multiplier = 0.0;
    
        if (strcasecmp($title, "HR") === 0) {
            $multiplier = 1.3;
        } elseif (strcasecmp($title, "Accountant") === 0) {
            $multiplier = 1.4;
        } elseif (strcasecmp($title, "Technical") === 0) {
            $multiplier = 1.6;
        } elseif (strcasecmp($title, "Delivery") === 0) {
            $multiplier = 1.0;
        }
    
        $payment = $employee->getHoursWorked() * $multiplier;
        return (int) $payment;
    }
}

?>
