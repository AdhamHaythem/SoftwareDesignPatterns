<?php
require_once '../Model/DonationModel.php';
require_once '../Model/DonationManager.php';
require_once '../View/DonationView.php';

class DonationController {

    public function processDonationPayment(float $amount, int $donationId): bool {
        $donation = DonationManager::retrieve($donationId);    
        if ($donation) {
            $donation->amountPaid($amount);
            return true;
        }
        return false;
    }

}

$donationController = new DonationController();

if (isset($_POST['processDonationPayment'])) {
    if (!empty($_POST['amount']) && !empty($_POST['donationId'])) {
        $result = $donationController->processDonationPayment((float)$_POST['amount'], (int)$_POST['donationId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}


?>
