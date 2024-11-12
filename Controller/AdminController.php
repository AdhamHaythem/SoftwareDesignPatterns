<?php
require_once '../Model/AdminModel.php';
require_once '../View/AdminView.php';

class AdminController {
    
    public function manageUsers(int $userID): void {
        Admin::manageUsers($userID);
    }

    public function generateReports(): string {
        $view = new AdminView();
        $reports = Admin::generateReports();
        $view->displayReports($reports);
        return $reports;
    }

    public function sendNotification(int $userID): void {
        Admin::sendNotification($userID);
        //  display isA 
    }

    public function viewDonationStatistics(): void {
        $view = new AdminView();
        $statistics = Admin::viewDonationStatistics();
        $view->displayStatistics($statistics);
    }
}

$adminController = new AdminController();

if (isset($_POST['manageUsers'])) {
    if (!empty($_POST['userID'])) {
        $adminController->manageUsers((int)$_POST['userID']);
    }
}

if (isset($_POST['generateReports'])) {
    $adminController->generateReports();
}

if (isset($_POST['sendNotification'])) {
    if (!empty($_POST['userID'])) {
        $adminController->sendNotification((int)$_POST['userID']);
    }
}

if (isset($_POST['viewDonationStatistics'])) {
    $adminController->viewDonationStatistics();
}

?>
