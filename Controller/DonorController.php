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
        $donorModel = DonorModel::retrieve($donorId);
        $donationHistory=$donorModel->getDonationHistory();
        DonorView::displayDonationHistory($donationHistory);
    }    

    public function getTotalDonations($donorId){
        $donorModel = DonorModel::retrieve($donationId);
        return $donorModel->getTotalDonations();
    }
    
    public function joinEvent($strategy,$donorId){
        $donor= DonorModel::retrieve($donorId);
        $donor->joinEvent($strategy);
        return true;
    }

    public function setPaymentMethod($strategy,$donorId): bool{
        $donor= DonorModel::retrieve($donorId);
        $donor->setPayment($strategy);
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
            $donorController->getTotalDonations($_POST['donorId']);
        }
    }

    if (isset($_POST['addDonation'])) {
        $donation= new RegularDonation();
        
        if (isset($_POST['Clothes'])) {
            $donation= new Clothes($donation);
        }

        if (isset($_POST['Food'])) {
            $donation= new Food($donation);
        }

        if (isset($_POST['MedicalSupplies'])) {
            $donation= new MedicalSupplies($donation);
        }

        if (isset($_POST['CashDonation'])) {
            $donation= new CashDonation($donation);
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