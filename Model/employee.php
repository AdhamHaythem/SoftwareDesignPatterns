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
    public function getHoursWorked():int{
        return $this->workingHours;
    }
    function getTitle():string{
        return $this->title;
    }
    
    function getSalary(){
        return $this->salary;
    }

}

