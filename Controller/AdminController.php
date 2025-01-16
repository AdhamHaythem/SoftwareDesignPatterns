<?php
require_once '../Model/AdminModel.php';
require_once '../View/AdminView.php';

class AdminController {
    
    public function manageUsers(int $userID, Admin $admin): void {
        $admin -> manageUsers($userID);
    }

    public function generateReports(Admin $admin,$id): string {
        $view = new AdminView();
        $proxy= new ReportsGenerationProxy("admin",new ReportGenerator());
        $reports = $admin->generateReports();
        $results= [];
        $finalizedReports= $proxy->finalizeReport($id,$results);
        $view->displayReports($reports);
        return $reports;
    }

    public function viewDonationStatistics(Admin $admin,$id): void {
        $view = new AdminView();
        $statistics = $admin->viewDonationStatistics();
        $proxy= new ReportsGenerationProxy("admin",new statisticsGenerator());
        $reports = $admin->generateReports();
        $results= [];
        $finalizedReports= $proxy->finalizeReport($id,$results);
        $view->displayDonationStatistics($statistics);
    }

    public function changeState($donationId)
    {
        $donation = Donation::retrieve($donationId);
        $donation->handleChange();
    }
}

$adminController = new AdminController();

if (isset($_POST['manageUsers'])) {
    if (!empty($_POST['userID'])) {
        $admin = Admin::retrieve($_POST['AdminId']);
        $adminController->manageUsers((int)$_POST['userID'],$admin);
    }
}

if (isset($_POST['generateReports'])) {
    $admin = Admin::retrieve($_POST['AdminId']);
    $adminController->generateReports($admin,$_POST['id']);
}

if (isset($_POST['viewDonationStatistics'])) {
    $admin = Admin::retrieve($_POST['AdminId']);
    $adminController->viewDonationStatistics($admin,$_POST['id']);
}

if(isset($_POST['DonationState']))
{
    if(!empty($_POST['donationID']))
    {
    $adminController->changeState($_POST['donationID']);
    }
}


?>
