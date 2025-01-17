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
        array $location,
        int $volunteersNeeded,
        int $eventID,
        string $name,
        float $target,
        string $title,
        string $description,
        float $moneyEarned
    ) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID, $name, $description);
        $this->campaignID = $eventID;
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
        return true;
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
            json_encode($campaign->getLocation()),
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
             $campaign->getTarget(),
             $campaign->getTitle(),
            //  $campaign->getLocation(),
            //  $campaign->getVolunteersNeeded(),
             $campaign->getdescription(),
            //  $campaign->getTime()->format('Y-m-d H:i:s'),
            
             $campaign->getMoneyEarned()
        ];
        if (!$dbConnection->execute($sql, $params)) {
            throw new Exception("Failed to insert or update CAMPAIGN record.");
        }

        return true;
    }

    public static function retrieve($eventID): ?CampaignStrategy {
        $dbConnection = DatabaseConnection::getInstance();
    
        $sql = "SELECT * FROM campaignstrategy c JOIN event e ON c.eventID = c.eventID WHERE c.eventID = ?";
        $params = [$eventID];
    
        $result = $dbConnection->query($sql, $params);
    
        if (!$result || empty($result)) {
            return null; // Campaign not found
        }
    
        $row = $result[0];
        $location = json_decode($row['location'], true);
  
        $time = new DateTime($row['time']);
    
        return new CampaignStrategy(
            $time, // time
            $location, // location
            $row['volunteers_needed'], // volunteersNeeded
            $row['eventID'], // eventID
            $row['name'], // name
            $row['target'], // target
            $row['title'], // title
            $row['description'], // description
            $row['moneyEarned'] // moneyEarned
        );
    }


    public static function update($campaign): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update the event table first
            $eventSql = "UPDATE event SET 
                            name = ?, 
                            time = ?, 
                            location = ?, 
                            volunteers_needed = ?, 
                            volunteersList = ?
                        WHERE eventID = ?";
    
            $eventParams = [
                $campaign->getName(),
                $campaign->getTime()->format('Y-m-d H:i:s'),
                json_encode($campaign->getLocation()),
                $campaign->getVolunteersNeeded(),
                json_encode($campaign->getVolunteersList()),
                $campaign->getEventID()
            ];
    
            // echo "Event SQL Query: $eventSql\n";
            // echo "Event Parameters: " . print_r($eventParams, true) . "\n";
    
            if (!$dbConnection->execute($eventSql, $eventParams)) {
                throw new Exception("Failed to update event record.");
            }
    
            // Update the campaignstrategy table
            $campaignSql = "UPDATE campaignstrategy SET 
                                target = ?, 
                                title = ?, 
                                description = ?, 
                                moneyEarned = ?
                            WHERE eventID = ?";
    
            $campaignParams = [
                $campaign->getTarget(),
                $campaign->getTitle(),
                $campaign->getDescription(),
                $campaign->getMoneyEarned(),
                $campaign->getEventID()
            ];
    
            echo "Campaign SQL Query: $campaignSql\n";
            echo "Campaign Parameters: " . print_r($campaignParams, true) . "\n";
    
            if (!$dbConnection->execute($campaignSql, $campaignParams)) {
                throw new Exception("Failed to update campaign record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating campaign: " . $e->getMessage());
            return false;
        }
    }

public static function delete($eventID): bool {
    $dbConnection = DatabaseConnection::getInstance();

    try {
        $sql = "DELETE FROM event WHERE eventID = ?";
        $params = [$eventID];

        // echo "Campaign SQL Query: $sql\n";
        // echo "Campaign Parameters: " . print_r($params, true) . "\n";

        if (!$dbConnection->execute($sql, $params)) {
            throw new Exception("Failed to delete campaign record.");
        }

        return true;
    } catch (Exception $e) {
        error_log("Error deleting campaign: " . $e->getMessage());
        return false;
    }
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