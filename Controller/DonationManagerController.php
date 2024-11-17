<?php
require_once '../Model/DonationModel.php';
require_once '../Model/CampaignStrategy.php';
require_once '../Model/DonorModel.php';
require_once '../View/DonationManagerView.php';
require_once '../Model/DonationmanagerAdham.php';
require_once 'EventController.php';


class DonationManagerController {
   
    public function createCampaign($campaignId,$target,$title,$moneyEarned){
        $campaignModel = new CampaignStrategy($campaignId,$target,$title,$moneyEarned);
        DonationManager::create($campaignModel);
    
}

    public function createDonation($amount,$DonationID,$donorId){
            $donationModel = new Donation($amount,$DonationID,$donorId);
            DonationManager::create($donationModel);
        
    }

    // Retrieves a specific donation or campaign by key
    public function retrieve(string $key){
        $donationModel = Donation::retrieve($key);
        if ($donationModel) {
            return $donationModel;
        }
        $view= new CampaignView;
        $campaignModel = CampaignStrategy::retrieve($key);
        $view->displayCampaignDetails($campaignModel);
    }

    // Updates a specific donation or campaign
    public function update(string $key, array $data): bool {
        $donationModel = Donation::retrieve($key);
        if ($donationModel) {
            return $donationModel->update($data);
        }

        $campaignModel = CampaignStrategy::retrieve($key);
        if ($campaignModel) {
            return $campaignModel->update($data);
        }
        
        return false;
    }

    // Deletes a donation or campaign by key
    public function delete(string $key): bool {
        return Donation::delete($key) || CampaignStrategy::delete($key);
    }

    // Retrieves donation details by Donation ID
    public function getDonationDetails(int $donationId): ?Donation {
        return Donation::retrieve($donationId);
    }

    // Calculates the total amount of all donations
    public function calculateTotalDonations(){
        $model= new DonationManager();
        $view= new DonationManagerView();
        $total = $model->calculateTotalDonations();
        // $view->totalDonations($total);
    }

    // Gets statistics for a specific donation
    public function getDonationStatistics($managerId) {
        $manager =  DonationManager::retrieve($managerId);
        $statistics=$manager->getDonationStatistics();
        $view=new DonationManagerView();
        $view->displayDonationStatistics($statistics);
    }

    // Edits an existing campaign
    public function editCampaign($campaignId){
        $campaign = CampaignStrategy::retrieve($campaignId);
        CampaignStrategy::update($campaign);
    }

    // Retrieves campaign details by Campaign ID
    public function getCampaignDetails(int $campaignId): ?CampaignStrategy {
        return CampaignStrategy::retrieve($campaignId);
    }


    // Generates a report of all donations
    public function generateDonationReport($managerId) {
        $manager =  DonationManager::retrieve($managerId);
        $report = $manager->generateDonationReport();
        $view=new DonationManagerView();
        $view->displayDonationReport($report); //needs parameter
    }

    // Adds a new campaign with specific time and location
    public function addCampaign($campaignID,$target,$title,$time,$location,$volunteersNeeded,$eventID) {
        $campaignModel = new CampaignStrategy($campaignID,$target,$title,$time,$location,$volunteersNeeded,$eventID);
        CampaignStrategy::create($campaignModel);
    }
}

// Handling form submissions and requests
$donationManagerController = new DonationManagerController();

if (isset($_POST['createDonation'])) {

    $donationManagerController->createDonation($_POST['amount'],$_post['DonationID'],$_post['donorId']);
    echo json_encode(['success' => $result]);
    exit;
}


if (isset($_POST['createCampaign'])) {

    $donationManagerController->createCampaign($_POST['campaignId'],$_post['target'],$_post['title'],0);
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
    $donationManagerController->calculateTotalDonations();
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
    if (!empty($_post['campaignId'])) {
        $donationManagerController->editCampaign($_post['campaignId']);
    }
    exit;
}

if (isset($_POST['generateDonationReport'])) {
    $donationManagerController->generateDonationReport($_POST['managerId']);
    echo json_encode(['success' => true, 'report' => $report]);
    exit;
}

if (isset($_POST['addCampaign'])) {
    $donationManagerController->addCampaign($_POST['campaignID'],$_POST['target'],$_POST['title'],$_POST['time'], $_POST['location'],$_POST['volunteersNeeded'],$_POST['eventID']);
    echo json_encode(['success' => $result]);
    exit;
}
?>
