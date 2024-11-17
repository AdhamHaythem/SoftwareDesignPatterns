<?php

require_once 'Donation.php';
require_once 'Campaign.php';
require_once 'DonorModel.php';
require_once 'IMaintainable.php';

class DonationManager implements IMaintainable {
    private array $donationsByDonor;
    private float $totalDonations;
    private float $goalAmount;
    private array $campaigns;
    private static DatabaseConnection $dbConnection; // Static variable for database connection

    public function __construct(float $goalAmount = 0.0, array $donations = [], array $campaigns = []) {
        $this->donationsByDonor = $donations;
        $this->totalDonations = 0.0;

        foreach ($donations as $donorID => $donationList) {
            foreach ($donationList as $donation) {
                $this->totalDonations += $donation->getAmount();
            }
        }

        $this->goalAmount = $goalAmount;
        $this->campaigns = $campaigns;
    }

    // Setter for database connection
    public static function setDatabaseConnection(DatabaseConnection $dbConnection): void {
        self::$dbConnection = $dbConnection;
    }

    // Getter for database connection
    public static function getDatabaseConnection(): DatabaseConnection {
        return self::$dbConnection;
    }

    // CRUD Methods for DonationManager

    // Create a new DonationManager record in the database
    public static function create($manager): bool {
        $sql = "INSERT INTO donation_managers (goalAmount, totalDonations) VALUES (:goalAmount, :totalDonations)";
        $params = [
            ':goalAmount' => $manager->goalAmount,
            ':totalDonations' => $manager->totalDonations
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    // Retrieve a DonationManager record from the database by ID
    public static function retrieve($managerID): ?DonationManager {
        $sql = "SELECT * FROM donation_managers WHERE id = :managerID";
        $params = [':managerID' => $managerID];

        $result = self::$dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new DonationManager(
                $result['goalAmount'],
                [], // Empty donations, you might want to populate these separately
                []  // Empty campaigns, you might want to populate these separately
            );
        }

        return null;
    }

    // Update a DonationManager record in the database
    public static function update($manager): bool {
        $sql = "UPDATE donation_managers SET goalAmount = :goalAmount, totalDonations = :totalDonations WHERE id = :managerID";
        $params = [
            ':goalAmount' => $manager->goalAmount,
            ':totalDonations' => $manager->totalDonations,
            ':managerID' => $manager->managerID
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    // Delete a DonationManager record from the database by ID
    public static function delete($managerID): bool {
        $sql = "DELETE FROM donation_managers WHERE id = :managerID";
        $params = [':managerID' => $managerID];

        return self::$dbConnection->execute($sql, $params);
    }

    // Additional Methods for DonationManager
    public function addDonationForDonor(int $donorID, Donation $donation): bool {
        if (!isset($this->donationsByDonor[$donorID])) {
            $this->donationsByDonor[$donorID] = [];
        }
        $this->donationsByDonor[$donorID][] = $donation;
        $this->totalDonations += $donation->getAmount();
        return true;
    }

    public function getDonationsByDonor(int $donorID): array {
        return $this->donationsByDonor[$donorID] ?? [];
    }

    public function calculateTotalDonations(): float {
        return $this->totalDonations;
    }

    public function getDonationDetails(int $donationID): ?Donation {
        foreach ($this->donationsByDonor as $donations) {
            foreach ($donations as $donation) {
                if ($donation->getId() == $donationID) {
                    return $donation;
                }
            }
        }
        return null;
    }

    public function getCampaignDetails(int $campaignID): ?CampaignModel {
        foreach ($this->campaigns as $campaign) {
            if ($campaign->getCampaignID() == $campaignID) {
                return $campaign;
            }
        }
        return null;
    }

    public function generateDonationReport(): array {
        $allDonations = [];
        foreach ($this->donationsByDonor as $donations) {
            $allDonations = array_merge($allDonations, $donations);
        }
        return $allDonations;
    }

    public function getDonationStatistics(): array {
        $statistics = [];
        foreach ($this->donationsByDonor as $donorID => $donations) {
            foreach ($donations as $donation) {
                $statistics[] = [
                    'DonationID' => $donation->getId(),
                    'Amount' => $donation->getAmount(),
                    'DonorID' => $donorID
                ];
            }
        }
        return $statistics;
    }
}

?>
