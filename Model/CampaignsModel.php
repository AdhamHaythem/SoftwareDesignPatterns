<?php

require_once 'EventModel.php';
require_once 'DonationModel.php';

class CampaignModel {
    private int $campaignID;
    private float $target;
    private string $title;
    private array $totalEvents;
    private array $donations;
    private float $moneyEarned;
    private DatabaseConnection $dbConnection;

    public function __construct(int $campaignID, float $target, string $title, array $totalEvents, array $donations, float $moneyEarned) {
        $this->campaignID = $campaignID;
        $this->target = $target;
        $this->title = $title;
        $this->totalEvents = $totalEvents;
        $this->donations = $donations;
        $this->moneyEarned = $moneyEarned;
    }



    public function addVolunteer(int $donorID, int $eventID): bool {
        foreach ($this->totalEvents as $event) {
            if ($event->getEventID() == $eventID) {
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

        return $this->dbConnection->execute($sql, $params);
    }

    public static function create(CampaignModel $campaign): bool {
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

        return $campaign->dbConnection->execute($sql, $params);
    }

    public static function retrieve(int $campaignID, DatabaseConnection $dbConnection): ?CampaignModel {
        $sql = "SELECT * FROM campaigns WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        
        $result = $dbConnection->query($sql, $params);
        if ($result) {
            return new CampaignModel(
                $result['campaignID'],
                $result['targetAmount'],
                $result['campaignName'],
                [], // actual events here
                [], //actual donations here
                $result['raisedAmount'],
                $dbConnection
            );
        }
        return null;
    }

    public static function update(CampaignModel $campaign): bool {
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


    public static function delete(int $campaignID, DatabaseConnection $dbConnection): bool {
        $sql = "DELETE FROM campaigns WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];

        return $dbConnection->execute($sql, $params);
    }


    public function registerObserver(IObserver $observer): void {
    }

    public function removeObserver(int $observerID): void {
    }

    public function notifyObserver(): void {
    }
}

?>
