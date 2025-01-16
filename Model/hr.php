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
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
        int $salary,
        int $workingHours
    ) {
        parent::__construct(    
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $password,
            $location,
            $phoneNumber,
            "HR",
            $salary,
            $workingHours
        );
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
        $sql = "SELECT u.userID, u.username, u.firstName, u.lastName, u.email, u.password, u.locationList, u.phoneNumber, u.isActive, 
                       e.title, e.salary, e.workingHours,
                       h.managedEmployees
                FROM user u
                LEFT JOIN employee e ON u.userID = e.userID
                LEFT JOIN hr h ON u.userID = h.userID
                WHERE u.userID = :userID";
        
        $params = [':userID' => $userID];
        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        
        if ($result && !empty($result)) {
            return new hrModel(
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
                $result['workingHours'],
                $result['managedEmployees'],          
            );
        }
        
        return null;
    }
    public static function update($hr): bool {
        if (!$hr instanceof hrModel) {
            throw new InvalidArgumentException("Expected instance of HR");
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
                            phoneNumber = :phoneNumber, 
                            isActive = :isActive
                        WHERE userID = :userID";
    
            $userParams = [
                $hr->getUserID(),
                $hr->getUsername(),
                $hr->getFirstname(),
                $hr->getLastname(),
                $hr->getEmail(),
                password_hash($hr->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($hr->getLocation()),
                $hr->getPhoneNumber(),
                1 
            ];
    
            $userUpdated = $dbConnection->execute($userSql, $userParams);
    
           
            if ($userUpdated && $hr->getTitle() && $hr->getSalary()) {
                $employeeSql = "UPDATE employee SET 
                                    title = :title, 
                                    salary = :salary, 
                                    workingHours = :workingHours
                                WHERE userID = :userID";
    
                $employeeParams = [
                    $hr->getUserID(),
                    $hr->getTitle(),
                    $hr->getSalary(),
                    $hr->getHoursWorked()
                ];
    
                $employeeUpdated = $dbConnection->execute($employeeSql, $employeeParams);
            }
            if ($userUpdated) {
                $hrSql = "UPDATE hr SET 
                            managedEmployees = :managedEmployees
                        WHERE userID = :userID";
    
                $hrParams = [
                    $hr->getUserID(),
                    $hr->getManagedEmployees(),
                ];
    
                $hrUpdated = $dbConnection->execute($hrSql, $hrParams);
    
                return $hrUpdated;
            }
    
            return false;
        } catch (Exception $e) {
            error_log("Error updating HR: " . $e->getMessage());
            return false;
        }
    }
    
    public static function delete($userID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
           
            $hrSql = "DELETE FROM hr WHERE userID = :userID";
            $hrParams = [$userID];
            $hrDeleted = $dbConnection->execute($hrSql, $hrParams);

            $employeeSql = "DELETE FROM employee WHERE userID = :userID";
            $employeeDeleted = $dbConnection->execute($employeeSql, $hrParams);
    
            $userSql = "DELETE FROM user WHERE userID = :userID";
            $userDeleted = $dbConnection->execute($userSql, $hrParams);
    
            return $hrDeleted && $employeeDeleted && $userDeleted;
        } catch (Exception $e) {
            error_log("Error deleting HR: " . $e->getMessage());
            return false;
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
