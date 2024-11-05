<?php
require_once 'employee.php';
class technicalModel extends EmployeeModel
{
    private array $skills;
    private array $certifications;


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
    int $workingHours,
    array $skills,
    array $certifications)
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

        $this->skills=$skills;
        $this->certifications=$certifications;
    }
}




?>