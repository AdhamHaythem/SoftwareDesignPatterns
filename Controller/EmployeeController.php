<?php
require_once '../Model/EmployeeModel.php';
require_once './UserController.php';
require_once '../Model/UserModel.php';
require_once '../Model/hr.php';
require_once '../Model/technical.php';
require_once '../Model/delivery.php';

class EmployeeController {
    public function requestLeave($time, $reason,$type,$employeeId): bool {
         if($type=="HR") $employee= hrModel::retrieve($employeeId);
         elseif($type=="Delivery") $employee= DeliveryPersonnel::retrieve($employeeId);
         elseif($type=="Technical") $employee= technicalModel::retrieve($employeeId);
        if($employee){
            return $employee->requestLeave($time, $reason);
        }
        return false;
    }

    public function reportHoursWorked(int $employeeId,int $hours,string $type): void {
        if($type=="HR") $employee= hrModel::retrieve($employeeId);
        elseif($type=="Delivery") $employee= DeliveryPersonnel::retrieve($employeeId);
        elseif($type=="Technical") $employee= technicalModel::retrieve($employeeId);
       
        if ($employee) {
            $employee->reportHoursWorked($hours);
        }
    }
}

$employeeController = new EmployeeController();

if (isset($_POST['requestLeave'])) {
    if (isset($_POST['time'], $_POST['reason'])) {
        $employeeController->requestLeave($_POST['time'], $_POST['reason'],$_POST['type'], $_POST['employeeId']);
    }
}

if (isset($_POST['reportHoursWorked'])) {
    if (isset($_POST['userId'], $_POST['hours'])) {
        $employeeController->reportHoursWorked($_POST['employeeId'], (int)$_POST['hours'],$_POST['type']);
    }
}

?>
