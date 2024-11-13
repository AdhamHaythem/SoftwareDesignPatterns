<?php
require_once '../Model/EmployeeModel.php';
require_once './UserController.php';
require_once '../Model/UserModel.php';

class EmployeeController {
    public function requestLeave($time, $reason): bool {
        return EmployeeModel::requestLeave($time, $reason); 
    }

    public function reportHoursWorked(int $userId, int $hours): void {
        $employee = EmployeeModel::retrieve($userId);
        if ($employee) {
            $employee->reportHoursWorked($hours);
        }
    }
}

$employeeController = new EmployeeController();

if (isset($_POST['requestLeave'])) {
    if (isset($_POST['time'], $_POST['reason'])) {
        $employeeController->requestLeave($_POST['time'], $_POST['reason']);
    }
}

if (isset($_POST['reportHoursWorked'])) {
    if (isset($_POST['userId'], $_POST['hours'])) {
        $employeeController->reportHoursWorked($_POST['userId'], (int)$_POST['hours']);
    }
}

?>
