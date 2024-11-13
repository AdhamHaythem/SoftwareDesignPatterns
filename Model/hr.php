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
            $title,
            $salary,
            $workingHours
        );
    }

    // CRUD Methods
    public static function create($hr): bool {
        if (!$hr instanceof hrModel) {
            throw new InvalidArgumentException("Expected instance of HR");
        }
        $sql = "INSERT INTO hr (username, firstname, lastname, userID, email, password, location, phoneNumber, title, salary, workingHours)
                VALUES (:username, :firstname, :lastname, :userID, :email, :password, :location, :phoneNumber, :title, :salary, :workingHours)";
        
        $params = [
            ':username' => $hr->getUsername(),
            ':firstname' => $hr->getFirstname(),
            ':lastname' => $hr->getLastname(),
            ':userID' => $hr->getUserID(),
            ':email' => $hr->getEmail(),
            ':password' => password_hash($hr->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($hr->getLocation()), // Assuming location is an array
            ':phoneNumber' => $hr->getPhoneNumber(),
            ':title' => $hr->getTitle(),
            ':salary' => $hr->getSalary(),
            ':workingHours' => $hr->getHoursWorked()
        ];
        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function retrieve($userID): ?hrModel {
        $sql = "SELECT * FROM hr WHERE userID = :userID";
        $params = [':userID' => $userID];
        
        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new hrModel(
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

    public static function update($hr): bool {
        if (!$hr instanceof hrModel) {
            throw new InvalidArgumentException("Expected instance of HR");
        }
        $sql = "UPDATE hr SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    usernameID = :usernameID, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber,
                    title = :title,
                    salary = :salary,
                    workingHours = :workingHours
                WHERE userID = :userID";

        $params = [
            ':username' => $hr->getUsername(),
            ':firstname' => $hr->getFirstname(),
            ':lastname' => $hr->getLastname(),
            ':email' => $hr->getEmail(),
            ':password' => password_hash($hr->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($hr->getLocation()),
            ':phoneNumber' => $hr->getPhoneNumber(),
            ':title' => $hr->getTitle(),
            ':salary' => $hr->getSalary(),
            ':workingHours' => $hr->getHoursWorked()
        ];
        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function delete($userID): bool {
        $sql = "DELETE FROM hr WHERE userID = :userID";
        $params = [':userID' => $userID];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    // Methods for managing employees and volunteers
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
