<!-- setterrrr w getterrrrrrrr 3shann om el dbbbbb ya adhaaaaaaaaaaam -->

<?php

require_once 'IEvent.php';
require_once 'DonationModel.php';

class CampaignStrategy extends Event {
    private int $campaignID;
    private float $target;
    private string $title;
    private array $totalEvents;
    private array $donations;
    private float $moneyEarned;

    public function __construct(
        int $campaignID,
        DateTime $time,
        string $location,
        int $volunteersNeeded,
        int $eventID,
        string $name,
        float $target,
        string $title,
        float $moneyEarned
    ) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID, $name);
        $this->campaignID = $campaignID;
        $this->target = $target;
        $this->title = $title;
        $this->moneyEarned = $moneyEarned;
    }


    public function getCampaignID(): int {
        return $this->campaignID;
    }

    public function setCampaignID(int $campaignID): void {
        $this->campaignID = $campaignID;
    }

    public function getTarget(): float {
        return $this->target;
    }

    public function setTarget(float $target): void {
        $this->target = $target;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getTotalEvents(): array {
        return $this->totalEvents;
    }

    public function setTotalEvents(array $totalEvents): void {
        $this->totalEvents = $totalEvents;
    }

    public function getDonations(): array {
        return $this->donations;
    }

    public function setDonations(array $donations): void {
        $this->donations = $donations;
    }

    public function getMoneyEarned(): float {
        return $this->moneyEarned;
    }

    public function setMoneyEarned(float $moneyEarned): void {
        $this->moneyEarned = $moneyEarned;
    }
    
    public function addVolunteer($donorID): bool {
        foreach ($this->totalEvents as $event) {
            if ($event->getDonorID() == $donorID) {
                return $event->addVolunteer($donorID);
            }
        }
        return false;
    }

    // public function assignToEvent(Donor $volunteer): bool {
    //     if (count($this->getVolunteersList()) < $this->getVolunteersNeeded()){
    //         $this->getVolunteersList()[] = $volunteer->getDonorID();
    //         $this->notifyObservers();
    //         return true;
    //     }
    //     return false;
    // }

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

    public function addFunds(float $amount): bool {
        $this->moneyEarned += $amount;
        return $this->updateCampaignInDB();
    }

    private function updateCampaignInDB(): bool {
        $sql = "UPDATE campaignstrategy SET 
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

        if (!$campaign instanceof CampaignStrategy) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
        $sql = "INSERT INTO campaignstrategy (campaignID, campaignName, description, startDate, endDate, targetAmount, raisedAmount)
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

    public static function retrieve($campaignID): ?CampaignStrategy {
        $dbConnection = Event::getDatabaseConnection();
        $sql = "SELECT * FROM campaignstrategy WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        
        $result = $dbConnection->query($sql, $params);
        if ($result) {
            $startDate = new DateTime($result['startDate']);
            $endDate = new DateTime($result['endDate']);
            
            return new CampaignStrategy(
                $result['campaignID'],
                $startDate,
                $result['location'],
                $result['volunteersNeeded'],
                $result['eventID'],
                $result['campaignName'],
                $result['targetAmount'],
                $result['campaignTitle'],
                $result['raisedAmount']
            );
        }
        return null;
    }
    

    public static function update($campaign): bool {
        $sql = "UPDATE campaignstrategy SET 
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

        return Event::getDatabaseConnection()->execute($sql, $params);
    }


    public static function delete($campaignID): bool {
        $sql = "DELETE FROM campaignstrategy WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        $dbConnection= Event::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

  

    // for strategiesssssssssssssssss
    public function signUp(int $donorID): bool {

        if (!isset($donorID)) {
            return false;
        }
        if (count($this->getVolunteersList()) < $this->getVolunteersNeeded()) {
            echo "Campaign SignUp: Donor $donorID is successfully signed up for the campaign event!\n";
            return $this->addVolunteer($donorID);
        }
        echo "Campaign SignUp: No more spots available for the campaign event.\n";
        return false;
    }
    
    public function getAllEvents(): array {
        $dbConnection = Event::getDatabaseConnection();
        $sql = "SELECT * FROM campaignstrategy";
        
        $result = $dbConnection->query($sql);
        $campaigns = [];
        
        if ($result) {
            foreach ($result as $campaignData) {
                $startDate = new DateTime($campaignData['startDate']);
                $endDate = new DateTime($campaignData['endDate']);
                $campaigns[] = new CampaignStrategy(
                    $campaignData['campaignID'],
                    $startDate,
                    $campaignData['location'],
                    $campaignData['volunteersNeeded'],
                    $campaignData['eventID'], 
                    $campaignData['campaignName'],
                    $campaignData['targetAmount'],
                    $campaignData['campaignTitle'],
                    $campaignData['raisedAmount']
                );
            }
        }
        
        return $campaigns;
    }
    


    public function checkEventStatus(): string {
        if ($this->getVolunteersNeeded() > count($this->getVolunteersList())) {
            return 'Campaign Event is still open for more volunteers';
        }
        return 'Campaign Event is fully booked';
    }

    public function generateEventReport(): string {
        $report = "Campaign Event Report for Event ID: " . $this->getEventID() . "\n";
        $report .= "Location: " . $this->getLocation() . "\n";
        $report .= "Volunteers Needed: " . $this->getVolunteersNeeded() . "\n";
        $report .= "Current Volunteers: " . count($this->getVolunteersList()) . "\n";
        $report .= "Campaign Event Status: " . $this->checkEventStatus() . "\n";
        return $report;
    }

}

?>