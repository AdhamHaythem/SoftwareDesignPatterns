<?php

require_once '../Model/DonorModel.php';
require_once '../Model/VolunteeringEventStrategy.php';
require_once '../Model/CampaignStrategy.php';
require_once '../Model/RegularDonation.php';
require_once '../Model/Clothes.php';
require_once '../Model/CashDonation.php';
require_once '../Model/Food.php';
require_once '../Model/MedicalSupplies.php';
require_once '../Model/Cash.php';
require_once '../Model/Visa.php';
require_once '../View/DonorView.php';
class DonorController{

    public function getDonationHistory($donorId){
        $donorModel = Donor::retrieve($donorId);
        $donationHistory=$donorModel->getDonationHistory();
        $view = new DonorView();
        $view->displayDonationHistory($donationHistory);
    }    

    public function getTotalDonations($donorId){
        $donorModel = Donor::retrieve($donorId);
        return $donorModel->getTotalDonations();
    }
    
    public function joinEvent($strategy,$donorId){
        $donor= Donor::retrieve($donorId);
        $donor->joinEvent($strategy);
        return true;
    }

    public function setPaymentMethod($strategy,$donorId): bool{
        $donor= Donor::retrieve($donorId);
        $donor->setPaymentMethod($strategy);
        return true;
    }

}

    $donorController= new DonorController();

    if (isset($_POST['getDonationHistory'])) {
        if(!empty($_POST['donorId'])){
            $donorController->getDonationHistory($_POST['donorId']);
        }
    }

    if (isset($_POST['getTotalDonations'])) {
        if(!empty($_POST['donorId'])){
            $data = $donorController->getTotalDonations($_POST['donorId']);
            $view = new DonorView();
            $view->displayTotalDonations($data);

        }
    }

    if (isset($_POST['addDonation'])) {
        $donation= new RegularDonation($_post['amount'],$_post['donorId']);
        
        if (isset($_POST['Clothes'])) {
            $donation= new Clothes($_post['amount'],$_post['donorId'],$donation);
        }

        if (isset($_POST['Food'])) {
            $donation= new Food($_post['amount'],$_post['donorId'],$donation);
        }

        if (isset($_POST['MedicalSupplies'])) {
            $donation= new MedicalSupplies($_post['amount'],$_post['donorId'],$donation);
        }

        if (isset($_POST['CashDonation'])) {
            $donation= new CashDonation($_post['amount'],$_post['donorId'],$donation);
        }

        $donation->amountPaid($_POST['amount']);
    }

    if (isset($_POST['setPayment'])) {
        
        if (isset($_POST['Cash'])) {
            $strategy= new Cash();
        }

        elseif (isset($_POST['Visa'])) {
            $strategy= new Visa();
        }

        $donorController->setPaymentMethod($strategy,$_POST['donorId']);
    }

    if (isset($_POST['joinEvent'])) {
        
        if (isset($_POST['CampaignStrategy'])) {
            $strategy= new CampaignStrategy();
        }

        elseif (isset($_POST['VolunteeringEventStrategy'])) {
            $strategy= new VolunteeringEventStrategy();
        }

        $donorController->joinEvent($strategy,$_POST['donorId']);
    }


?>