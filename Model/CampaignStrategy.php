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
    private string $description;

    public function __construct(
        DateTime $time, 
        string $location,
        int $volunteersNeeded,
        int $eventID,
        string $name,
        float $target,
        string $title,
        string $description,
        float $moneyEarned
    ) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID, $name, $description);
        $this->campaignID = $eventID; //w henaaaaaaa
        $this->target = $target;
        $this->title = $title;
        $this->moneyEarned = $moneyEarned;
        $this->description = $description;
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
    public function getdescription(): string {
        return $this->description;
    }
    
    public function setDescription(string $description): void {
        $this->description = $description;
    }
 

    public function setMoneyEarned(float $moneyEarned): void {
        $this->moneyEarned = $moneyEarned;
    }
    
    // public function addVolunteer($donorID): bool {
    //     foreach ($this->totalEvents as $event) {
    //         if ($event->getDonorID() == $donorID) {
    //             return $event->addVolunteer($donorID);
    //         }
    //     }
    //     return false;
    // }

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
        $dbConnection = DatabaseConnection::getInstance();

        if (!$campaign instanceof CampaignStrategy) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
        
        $Eventsql = "INSERT INTO event (eventID,name,time,location,volunteers_needed,volunteersList) 
        VALUES (?, ?, ?, ?, ?, ?)";
        $Eventparams = [
            $campaign->getEventID(),
            $campaign->getName(),
            $campaign->getTime()->format('Y-m-d H:i:s'),
            $campaign->getLocation(),
            $campaign->getVolunteersNeeded(),
            json_encode($campaign->getVolunteersList())
        
        ];
        if (!$dbConnection->execute($Eventsql, $Eventparams)) {
            throw new Exception("Failed to insert or update eventforcampaign record.");
        }

        $sql = "INSERT INTO campaignstrategy (eventID,target,title,description,moneyEarned)
                VALUES (?, ?, ?, ?, ?)";
        
        $params = [
             $campaign->getCampaignID(),
             $campaign->getTitle(),
            //  $campaign->getLocation(),
            //  $campaign->getVolunteersNeeded(),
             $campaign->getdescription(),
            //  $campaign->getTime()->format('Y-m-d H:i:s'),
             $campaign->getTarget(),
             $campaign->getMoneyEarned()
        ];
        if (!$dbConnection->execute($sql, $params)) {
            throw new Exception("Failed to insert or update CAMPAIGN record.");
        }

        return true;
    }

    public static function retrieve($campaignID): ?CampaignStrategy {
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM campaignstrategy WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
    
        $result = $dbConnection->query($sql, $params);
    
        if (!$result) {
            return null; // Campaign not found
        }
    
        $time = new DateTime($result['time']);
      
    
        return new CampaignStrategy(
            $time,
            $result['location'],
            $result['volunteersNeeded'],
            $result['eventID'],
            $result['name'],
            $result['target'],
            $result['title'],
            $result['description'],
            $result['moneyEarned']
        );
    }


   ///update 8alaaaaaaaaaattt w feh redundancyyyyyyyyyy

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

        return DatabaseConnection::getInstance() ->execute($sql, $params);
    }


    public static function delete($campaignID): bool {
        $sql = "DELETE FROM campaignstrategy WHERE campaignID = :campaignID";
        $params = [':campaignID' => $campaignID];
        $dbConnection = DatabaseConnection::getInstance();
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
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM campaignstrategy";
        
        $result = $dbConnection->query($sql);
        $campaigns = [];
        
        if ($result) {
            foreach ($result as $campaignData) {
                $time = new DateTime($campaignData['time']);
                $campaigns[] = new CampaignStrategy(
                    $time,
                    $campaignData['location'],
                    $campaignData['volunteersNeeded'],
                    $campaignData['eventID'],
                    $campaignData['name'],
                    $campaignData['target'],
                    $campaignData['title'],
                    $campaignData['description'],
                    $campaignData['moneyEarned']

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