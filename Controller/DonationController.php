<?php
require_once '../Model/DonationModel.php';
require_once '../View/DonationView.php';

class DonationController {

    public function processDonationPayment(float $amount, int $donationId): bool {
        // $donation =  hanretrieve somehow
        if ($donation) {
            $donation->amountPaid($amount);
            return true;
        }
        return false;
    }

    public function displayDonationDetails(int $donationId): void {
        $donationView = new DonationView();
        $donationModel = new DonationModel();

        $donation = $donationModel->getDonationDetails($donationId);
        $donationView->displayDonationDetails($donation);
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

if (isset($_POST['displayDonationDetails'])) {
    if (!empty($_POST['donationId'])) {
        $donationController->displayDonationDetails((int)$_POST['donationId']);
        echo json_encode(['success' => true]);
    }
    exit;
}


?>
