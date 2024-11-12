<?php

require_once 'userModel.php';
require_once 'DonationManager.php';

class Admin extends UserModel {
    private DonationManager $donationManager;

    public function __construct(
        int $userID,
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        float $goalAmount = 0.0
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donationManager = new DonationManager($goalAmount, [], []);
    }

    public function manageUsers(int $userID): void {
        echo "Managing user with ID: $userID\n";
        $user = $this->getUserByID($userID); 
        if ($user) {
            $user->update($user);
        } else {
            echo "User with ID $userID not found.\n";
        }
    }
    
    public function getUserByID(int $userID): ?UserModel {
        return UserModel::retrieve($userID);
    }

    public function generateReports(): string {
        $totalDonations = $this->donationManager->calculateTotalDonations();
        return "Report generated. Total Donations: $totalDonations";
    }

    //implementation lesaaa henaaaaaaaa
    public function sendNotification(int $userID): void {
        echo "Notification sent to user with ID: $userID\n";
    }

    public function viewDonationStatistics(): string {
        $statistics = [];
        foreach ($this->donationManager->generateDonationReport() as $donation) {
            $statistics[] = $this->donationManager->getDonationStatistics($donation);
        }
        return "Donation statistics:\n" . implode("\n", $statistics);
    }
}

?>
