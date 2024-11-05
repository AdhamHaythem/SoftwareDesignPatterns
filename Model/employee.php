<?php

require_once 'User.php'; // Assuming the User class is defined in User.php

class EmployeeModel extends UserModel {
    // Properties specific to Employee
    private string $title;
    private int $employeeId;
    private int $salary;
    private int $workingHours;

    // Constructor
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
        // Call parent constructor to initialize User properties
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
            $dbConnection
        );

        // Initialize Employee-specific properties
        $this->title = $title;
        $this->employeeId = $employeeId;
        $this->salary = $salary;
        $this->workingHours = $workingHours;
    }

    // Methods specific to Employee
    public function requestLeave(DateTime $time, string $reason): bool {
        return true;
    }

    public function reportHoursWorked(int $hours): void {
        $this->workingHours += $hours;
    }
    public function getHoursWorked():int{
        return $this->workingHours;
    }
    function getTitle():string{
        return $this->title;
    }
}

