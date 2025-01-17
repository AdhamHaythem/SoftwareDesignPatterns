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
    private int $adminID;

    public function __construct(int $adminID, float $goalAmount = 0.0, array $donations = [], array $campaigns = []) {
        $this->adminID = $adminID;
        $this->donationsByDonor = $donations;
        $this->totalDonations = 0.0;
        $this->campaigns = $campaigns;

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
                'volunteers_needed' => $campaign->getVolunteersNeeded(),
                'description' => $campaign->getDescription(),
                'time' => $campaign->getTime()->format('Y-m-d H:i:s'),
                'target' => $campaign->getTarget(),
                'moneyEarned' => $campaign->getMoneyEarned(),
                
            ];
        }, $manager->campaigns);

        $sql = "INSERT INTO donationmanager (adminID, goalAmount, totalDonations, campaigns) VALUES (?, ?, ?, ?)";
        $params = [
             $manager->adminID,
             $manager->goalAmount,
             $manager->totalDonations,
             json_encode($campaignsArray) 
        ];

        if (!$dbConnection->execute($sql, $params)) {
            throw new Exception("Failed to insert donation manager record.");
        }

        return true;
    }

    public static function retrieve($adminID): ?DonationManager {
        $dbConnection = DatabaseConnection::getInstance();
    
        $sql = "SELECT * FROM donationmanager WHERE adminID = ?";
        $params = [$adminID];
    
        $result = $dbConnection->query($sql, $params);
    
        if (!$result || empty($result)) {
            return null; // dManager not found
        }
    
        $row = $result[0];
    

        $campaignsArray = json_decode($row['campaigns'], true);

        $campaigns = array_map(function ($campaignData) {
            return new CampaignStrategy(
                new DateTime($campaignData['time']),
                $campaignData['location'],
                $campaignData['volunteers_needed'],
                $campaignData['eventID'],
                $campaignData['name'],
                $campaignData['target'],
                $campaignData['title'],
                $campaignData['description'],
                $campaignData['moneyEarned']
            );
        }, $campaignsArray);
    
        return new DonationManager(
            $row['adminID'],
            $row['goalAmount'],
            $row['totalDonations'],
            $campaigns
        );
    }

  
    public static function update($manager): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
    try {
    
        
            $campaignsArray = array_map(function ($campaign) {
                return [
                    'time' => $campaign->getTime()->format('Y-m-d H:i:s'),
                    'location' => $campaign->getLocation(),
                    'volunteers_needed' => $campaign->getVolunteersNeeded(),
                    'eventID' => $campaign->getCampaignID(),
                    'name' => $campaign->getName(),
                    'target' => $campaign->getTarget(),
                    'title' => $campaign->getTitle(),
                    'description' => $campaign->getDescription(),                    
                    'moneyEarned' => $campaign->getMoneyEarned()
                ];
            }, $manager->campaigns);
    
            $sql = "UPDATE donationmanager SET 
                        goalAmount = ?, 
                        totalDonations = ?, 
                        campaigns = ?
                    WHERE adminID = ?";
            $params = [
                $manager->goalAmount,
                $manager->totalDonations,
                json_encode($campaignsArray),
                $manager->adminID
            ];
    
            echo "Donation Manager SQL Query: $sql\n";
            echo "Donation Manager Parameters: " . print_r($params, true) . "\n";
    
            if (!$dbConnection->execute($sql, $params)) {
                throw new Exception("Failed to update donation manager record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating donation manager: " . $e->getMessage());
            return false;
        }
    }
 
    public static function delete($adminID): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $sql = "DELETE FROM donationmanager WHERE userID = ?";
            $params = [$adminID];
            // echo "Donation Manager SQL Query: $sql\n";
            // echo "Donation Manager Parameters: " . print_r($params, true) . "\n";
    
            if (!$dbConnection->execute($sql, $params)) {
                throw new Exception("Failed to delete donation manager record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error deleting donation manager: " . $e->getMessage());
            return false;
        }
    }


    public function addDonationForDonor(int $donorID, Donation $donation): bool {
        if (!isset($this->donationsByDonor[$donorID])) {
            $this->donationsByDonor[$donorID] = [];
        }
        $this->donationsByDonor[$donorID][] = $donation;
        $this->totalDonations += $donation->getAmount();
        return true;
    }


    public function getAdminID(): int {
        return $this->adminID;
    }

    public function setAdminID(int $adminID): void {
        $this->adminID = $adminID;
    }

    public function getDonationsByDonor(int $donorID): array {
        return $this->donationsByDonor[$donorID] ?? [];
    }

    public function calculateTotalDonations(): float {
        return $this->totalDonations;
    }

    public function getGoalAmount(): float {
        return $this->goalAmount;
    }

    public function setGoalAmount(float $goalAmount): void {
        $this->goalAmount = $goalAmount;
    }

    public function getTotalDonations(): float {
        return $this->totalDonations;
    }
    public function setTotalDonations(float $totalDonations): void {
        $this->totalDonations = $totalDonations;
    }
    public function getCampaigns(): array {
        return $this->campaigns;
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