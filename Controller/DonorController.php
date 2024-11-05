<?php

// + getDonationHistory(): List <Donation> 
// + addDonation(amount:double): bool 
// + joinEvent(): bool 
// + getTotalDonations(): double
// +setPaymentMethod(type:String):void
class DonorController{

    public function getDonationHistory($donorId){
        $donorModel = DonorModel::retrieve($donorId);
        $donationHistory=$donorModel->getDonationHistory();
        DonorView::displayDonationHistory($donationHistory);
    }    

    public function getTotalDonations($donationId){}
    
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
            $donorController->getDonationHistory($donorId);
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