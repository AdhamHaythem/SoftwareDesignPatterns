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
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
        DatabaseConnection $dbConnection,
        string $title,
        int $employeeId,
        int $salary,
        int $workingHours
    ) {
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
            $dbConnection,
            $title,
            $employeeId,
            $salary,
            $workingHours
        );
    }


    public function addEmployee(EmployeeModel $employee){
        $this->managedEmployees[] = $employee;
    }

    public function getManagedEmployees(): array {
        return $this->managedEmployees;
    }


    public function recruitVolunteer(Donor $donor){
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
