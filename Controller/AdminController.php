<?php
require_once '../Model/AdminModel.php';
require_once '../View/AdminView.php';

class AdminController {
    
    public function manageUsers(int $userID, Admin $admin): void {
        $admin -> manageUsers($userID);
    }

    public function generateReports(Admin $admin): string {
        $view = new AdminView();
        $reports = $admin->generateReports();
        $view->displayReports($reports);
        return $reports;
    }

    public function viewDonationStatistics(Admin $admin): void {
        $view = new AdminView();
        $statistics = $admin->viewDonationStatistics();
        $view->displayDonationStatistics($statistics);
    }
}

$adminController = new AdminController();

if (isset($_POST['manageUsers'])) {
    if (!empty($_POST['userID'])) {
        $adminController->manageUsers((int)$_POST['userID'],$_post['Admin']);
    }
}

if (isset($_POST['generateReports'])) {
    $adminController->generateReports($_post['Admin']);
}


if (isset($_POST['viewDonationStatistics'])) {
    $adminController->viewDonationStatistics($_post['Admin']);
}

?>
