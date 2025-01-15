<?php

require_once 'employee.php';
require_once 'hr.php';
require_once 'technical.php';
require_once 'delivery.php';

class EmployeeFactory {
    public function createEmployee(
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
        int $workingHours,
        string $vehicleType = null,
        array $skills = null,
        array $certifications = null,
        string $EmployeeType
    ): EmployeeModel {
        $employee = null;

        switch ($EmployeeType) {
            case "Delivery":
                $employee = new DeliveryPersonnel(
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
                    $workingHours,
                    $vehicleType
                );
                break;

            case "Technical":
                $employee = new technicalModel(
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
                    $workingHours,
                    $skills,
                    $certifications
                );
                break;

            case "HR":
                $employee = new hrModel(
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
                break;

                case "Instructor":
                    $employee = new InstructorModel(
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
                    break;

            default:
                throw new InvalidArgumentException("Invalid Employee Type: $EmployeeType");
        }

        return $employee;
    }
}
?>
