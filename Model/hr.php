<?php
require_once 'employee.php';
class hr extends Employee {
private array $managedEmployees =[];

function __construct( 
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
int $workingHours)
{
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
        $workingHours);
}

public function addEmployee( Employee $employee){
    $this->managedEmployees[]=$employee;
}

public function getManagedEmployees():array{
    return $this->managedEmployees;
}
}

?>