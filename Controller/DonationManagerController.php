<?php
require_once '../Model/DonationModel.php';
require_once '../Model/CampaignModel.php';
require_once '../Model/DonorModel.php';
require_once '../View/DonationManagerView.php';
require_once '../Model/DonationManager.php';

class DonationManagerController {
   
    // Creates a new donation or campaign object
    public function create(array $object): bool {
        if (isset($object['type']) && $object['type'] === 'campaign') {
            $campaignModel = new CampaignModel();
            return $campaignModel->create($object);
        } else {
            $donationModel = new DonationModel();
            return $donationModel->create($object);
        }
    }

    // Retrieves a specific donation or campaign by key
    public function retrieve(string $key){
        $donationModel = DonationModel::retrieve($key);
        if ($donationModel) {
            return $donationModel;
        }

        $campaignModel = CampaignModel::retrieve($key);
        CampaignView::displayCampaignDetails($campaignModel);
        
    }

    // Updates a specific donation or campaign
    public function update(string $key, array $data): bool {
        $donationModel = DonationModel::retrieve($key);
        if ($donationModel) {
            return $donationModel->update($data);
        }

        $campaignModel = CampaignModel::retrieve($key);
        if ($campaignModel) {
            return $campaignModel->update($data);
        }
        
        return false;
    }

    // Deletes a donation or campaign by key
    public function delete(string $key): bool {
        return DonationModel::delete($key) || CampaignModel::delete($key);
    }

    // Retrieves donation details by Donation ID
    public function getDonationDetails(int $donationId): ?Donation {
        return DonationModel::retrieve($donationId);
    }

    // Calculates the total amount of all donations
    public function calculateTotalDonations(){

        $total = DonationManager::calculateTotalDonations();
        DonationManagerView::totalDonations($total);
    }

    // Gets statistics for a specific donation
    public function getDonationStatistics(Donation $donation) {
        
        $statistics =  DonationManager::getDonationStatistics();
        DonationManagerView::displayDonationStatistics($statistics);
    }

    // Edits an existing campaign
    public function editCampaign(Campaign $campaign): bool {
        return $campaign->update();
    }

    // Retrieves campaign details by Campaign ID
    public function getCampaignDetails(int $campaignId): ?Campaign {
        return CampaignModel::retrieve($campaignId);
    }


    // Generates a report of all donations
    public function generateDonationReport() {
        $report = DonationManger::generateDonationReport();
        DonationManagerView::displayDonationReport($report);
    }

    // Adds a new campaign with specific time and location
    public function addCampaign($campaignID,$target,$title,$time,$location,$volunteersNeeded,$eventID) {
        $campaignModel = new CampaignModel($campaignID,$target,$title,$time,$location,$volunteersNeeded,$eventID);
        CampaignModel::create($campaignModel);
    }
}

// Handling form submissions and requests
$donationManagerController = new DonationManagerController();

if (isset($_POST['create'])) {
    $result = $donationManagerController->create($_POST['object']);
    echo json_encode(['success' => $result]);
    exit;
}

if (isset($_POST['retrieve'])) {
    $result = $donationManagerController->retrieve($_POST['key']);
    echo json_encode(['success' => !empty($result), 'data' => $result]);
    exit;
}

if (isset($_POST['update'])) {
    $result = $donationManagerController->update($_POST['key'], $_POST['data']);
    echo json_encode(['success' => $result]);
    exit;
}

if (isset($_POST['delete'])) {
    $result = $donationManagerController->delete($_POST['key']);
    echo json_encode(['success' => $result]);
    exit;
}

if (isset($_POST['getDonationDetails'])) {
    $result = $donationManagerController->getDonationDetails((int)$_POST['DonationID']);
    echo json_encode(['success' => !empty($result), 'data' => $result]);
    exit;
}

if (isset($_POST['calculateTotalDonations'])) {
    $total = $donationManagerController->calculateTotalDonations();
    echo json_encode(['success' => true, 'total' => $total]);
    exit;
}

if (isset($_POST['getDonationStatistics'])) {
    $donation = $donationManagerController->retrieve($_POST['key']);
    if ($donation) {
        $donationManagerController->getDonationStatistics($donation);
        
    }
    exit;
}

if (isset($_POST['editCampaign'])) {
    $campaign = $donationManagerController->getCampaignDetails((int)$_POST['campaignID']);
    if ($campaign) {
        $result = $donationManagerController->editCampaign($campaign);
        echo json_encode(['success' => $result]);
    }
    exit;
}

if (isset($_POST['sendDonationConfirmation'])) {
    $donor = DonorModel::retrieve($_POST['donorId']);
    $donation = DonationModel::retrieve((int)$_POST['DonationID']);
    if ($donor && $donation) {
        $result = $donationManagerController->sendDonationConfirmation($donor, $donation);
        echo json_encode(['success' => $result]);
    }
    exit;
}

if (isset($_POST['generateDonationReport'])) {
    $report = $donationManagerController->generateDonationReport();
    echo json_encode(['success' => true, 'report' => $report]);
    exit;
}

if (isset($_POST['addCampaign'])) {
    $result = $donationManagerController->addCampaign($_POST['time'], $_POST['location']);
    echo json_encode(['success' => $result]);
    exit;
}
?>
