<?php
require_once 'DonationModel.php';
require_once 'CampaignStrategy.php';
require_once 'db_connection.php';
require_once 'IMaintainable.php';

class DonationManager implements IMaintainable {
    private array $donationsByDonor;
    private float $totalDonations;
    private float $goalAmount;
    private array $campaigns;

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

    public static function create($manager): bool {
        $dbConnection = DatabaseConnection::getInstance();

        $campaignsArray = array_map(function ($campaign) {
            return [
                'eventID' => $campaign->getCampaignID(),
                'title' => $campaign->getTitle(),
                'location' => $campaign->getLocation(),
                'volunteersNeeded' => $campaign->getVolunteersNeeded(),
                'description' => $campaign->getDescription(),
                'time' => $campaign->getTime()->format('Y-m-d H:i:s'),
                'target' => $campaign->getTarget(),
                'moneyEarned' => $campaign->getMoneyEarned(),
                
            ];
        }, $manager->campaigns);

        $sql = "INSERT INTO donationmanager (goalAmount, totalDonations, campaigns) VALUES (?, ?, ?)";
        $params = [
             $manager->goalAmount,
             $manager->totalDonations,
             json_encode($campaignsArray) 
        ];

        if (!$dbConnection->execute($sql, $params)) {
            throw new Exception("Failed to insert donation manager record.");
        }

        return true;
    }

    public static function retrieve($managerID): ?DonationManager {
        $dbConnection = DatabaseConnection::getInstance();

        $sql = "SELECT * FROM donationmanager WHERE id = :managerID";
        $params = [':managerID' => $managerID];

        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new DonationManager(
                $result['goalAmount'],
                [], // Empty donationss
                []  // Empty campaignss
            );
        }

        return null;
    }

  
    public static function update($manager): bool {
        $dbConnection = DatabaseConnection::getInstance();

        $sql = "UPDATE donationmanager SET goalAmount = :goalAmount, totalDonations = :totalDonations WHERE id = :managerID";
        $params = [
            ':goalAmount' => $manager->goalAmount,
            ':totalDonations' => $manager->totalDonations,
            ':managerID' => $manager->managerID
        ];

        return $dbConnection->execute($sql, $params);
    }

 
    public static function delete($managerID): bool {
        $dbConnection = DatabaseConnection::getInstance();

        $sql = "DELETE FROM donationmanager WHERE id = :managerID";
        $params = [':managerID' => $managerID];

        return $dbConnection->execute($sql, $params);
    }


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
                if ($donation->getDonationID() == $donationID) {
                    return $donation;
                }
            }
        }
        return null;
    }

    public function getCampaignDetails(int $campaignID): ?CampaignStrategy {
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