//setterrrr w getterrrrrrrr 3shann om el dbbbbb ya adhaaaaaaaaaaam

<?php

require_once 'EventModel.php';
require_once 'DonationModel.php';

class CampaignModel extends Event {
    private int $campaignID;
    private float $target;
    private string $title;
    private array $totalEvents;
    private array $donations;
    private float $moneyEarned;

    
    public function __construct(int $campaignID, float $target, string $title,float $moneyEarned) {
        $this->campaignID = $campaignID;
        $this->target = $target;
        $this->title = $title;
        $this->moneyEarned = $moneyEarned;
    }

    public function addVolunteer(int $donorID): bool {
        foreach ($this->totalEvents as $event) {
            if ($event->getDonorID() == $donorID) {
                return $event->addVolunteer($donorID);
            }
        }
        return false;
    }

    public function updateTarget(int $newTarget): bool {
        $this->target = $newTarget;
        return $this->updateCampaignInDB(); 
    }

    public function getDetails(): array {
        return [
            'CampaignID' => $this->campaignID,
            'Title' => $this->title,
            'Target' => $this->target,
            'MoneyEarned' => $this->moneyEarned,
            'TotalEvents' => $this->totalEvents,
            'Donations' => $this->donations
        ];
    }

    public function getCampaignID(): int {
         return $this ->campaignID;

    }
    public function addFunds(float $amount): bool {
        $this->moneyEarned += $amount;
        return $this->updateCampaignInDB();
    }

    private function updateCampaignInDB(): bool {
        $sql = "UPDATE campaigns SET 
                    targetAmount = :targetAmount, 
                    raisedAmount = :raisedAmount
                WHERE campaignID = :campaignID";
        
        $params = [
            ':targetAmount' => $this->target,
            ':raisedAmount' => $this->moneyEarned,
            ':campaignID' => $this->campaignID
        ];

        $dbConnection= Event::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function create($campaign): bool {

        if (!$campaign instanceof CampaignModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
        $sql = "INSERT INTO campaigns (campaignID, campaignName, description, startDate, endDate, targetAmount, raisedAmount)
                VALUES (:campaignID, :campaignName, :description, :startDate, :endDate, :targetAmount, :raisedAmount)";
        
        $params = [
            ':campaignID' => $campaign->campaignID,
            ':campaignName' => $campaign->title,
            ':startDate' => $campaign->totalEvents[0]->getStartDate()->format('Y-m-d H:i:s'),
            ':endDate' => $campaign->totalEvents[0]->getEndDate()->format('Y-m-d H:i:s'),
            ':targetAmount' => $campaign->target,
            ':raisedAmount' => $campaign->moneyEarned
        ];
        $dbConnection= Event::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function retrieve($campaignID): ?CampaignModel {
        $dbConnection = CampaignModel::getDatabaseConnection();
        $sql = "SELECT * FROM campaigns WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        
        $result = $dbConnection->query($sql, $params);
        if ($result) {
            return new CampaignModel(
                $result['campaignID'],
                $result['targetAmount'],
                $result['campaignName'],
                $result['raisedAmount'],
            );
        }
        return null;
    }

    public static function update($campaign): bool {
        $sql = "UPDATE campaigns SET 
                    campaignName = :campaignName, 
                    description = :description, 
                    targetAmount = :targetAmount, 
                    raisedAmount = :raisedAmount
                WHERE campaignID = :campaignID";
        
        $params = [
            ':campaignName' => $campaign->title,
            ':targetAmount' => $campaign->target,
            ':raisedAmount' => $campaign->moneyEarned,
            ':campaignID' => $campaign->campaignID
        ];

        return $campaign->dbConnection->execute($sql, $params);
    }


    public static function delete($campaignID): bool {
        $sql = "DELETE FROM campaigns WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        $dbConnection= Event::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }
}

?>