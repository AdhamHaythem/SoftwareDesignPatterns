<?php
require_once 'IEvent.php'; 
require_once 'IObserver.php';
require_once 'db_connection.php';
require_once 'DonorModel.php';

class VolunteeringEventStrategy extends Event {
    private array $observers = [];


    public function __construct(string $name, DateTime $time, string $location, int $volunteersNeeded, int $eventID) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID, $name);
    }
    public function getVolunteerInfo(Donor $volunteer): array {
        return [
            'id' => $volunteer->getDonorID(),
            'name' => $volunteer->getUsername(),

        ];
    }
    public function getListObservers(): array {
        return $this->observers;
    }

    public function setListObservers(array $observers): void {
        $this->observers = $observers;
    }

    // public function assignToEvent(Donor $volunteer): bool {
    //     if (count($this->getVolunteersList()) < $this->getVolunteersNeeded()){
    //         $this->getVolunteersList()[] = $volunteer->getDonorID();
    //         $this->notifyObservers();
    //         return true;
    //     }
    //     return false;
    // }

    
    public static function create($object): bool {

        $dbConnection = DatabaseConnection::getInstance();
        $Eventsql = "INSERT INTO event (eventID,name,time,location,volunteers_needed,volunteersList) 
        VALUES (?, ?, ?, ?, ?, ?)";
        $Eventparams = [
            $object->getEventID(),
            $object->getName(),
            $object->getTime()->format('Y-m-d H:i:s'),
            $object->getLocation(),
            $object->getVolunteersNeeded(),
            json_encode($object->getVolunteersList())
        ];
        $Eresult=$dbConnection->execute($Eventsql, $Eventparams);

        $VolunteerSql = "INSERT INTO volunteeringeventstrategy (eventID) 
        VALUES (?)";
        $VolunteerParams = [
            $object->getEventID()
        ];
        $Vresult= $dbConnection->execute($VolunteerSql, $VolunteerParams);

        return $Vresult && $Eresult ;
    }

    public static function retrieve($eventID): ?VolunteeringEventStrategy {
        $dbConnection = DatabaseConnection::getInstance();
    
        $sql = "SELECT e.*, v.eventID 
                FROM event e 
                JOIN volunteeringeventstrategy v ON e.eventID = v.eventID 
                WHERE e.eventID = ?";
        $params = [$eventID];
    
        $result = $dbConnection->query($sql, $params);
    
        if (!$result || empty($result)) {
            return null; // Event not found
        }
    
        $row = $result[0];
    
        return new VolunteeringEventStrategy(
            $row['name'],
            new DateTime($row['time']),
            $row['location'],
            $row['volunteers_needed'],
            $row['eventID']
        );
    }

    public static function update($volunteeringEvent): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update the event table
            $eventSql = "UPDATE event SET 
                            name = ?, 
                            time = ?, 
                            location = ?, 
                            volunteers_needed = ?, 
                            volunteersList = ?
                        WHERE eventID = ?";
            $eventParams = [
                $volunteeringEvent->getName(),
                $volunteeringEvent->getTime()->format('Y-m-d H:i:s'),
                $volunteeringEvent->getLocation(),
                $volunteeringEvent->getVolunteersNeeded(),
                json_encode($volunteeringEvent->getVolunteersList()),
                $volunteeringEvent->getEventID()
            ];
    
            echo "Event SQL Query: $eventSql\n";
            echo "Event Parameters: " . print_r($eventParams, true) . "\n";
    
            if (!$dbConnection->execute($eventSql, $eventParams)) {
                throw new Exception("Failed to update event record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating volunteering event: " . $e->getMessage());
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
      

    public function registerObserver(IObserver $observer): void {
        $this->observers[] = $observer;
    }

    public function removeObserver($observerID): void {
        unset($this->observers[$observerID]);
    }
    
    public function notifyObservers($EventStatus): void {
        foreach ($this->observers as $observer) {
            $observer->UpdateStatus($EventStatus); 
        }
    }


   //for strategiesss

   public function signUp(int $donorID): bool {
    if (!isset($donorID)) {
    //    echo "Volunteering SignUp: Donor ID is not provided.\n";
        return false;
    }
    
    if (count($this->getVolunteersList()) < $this->getVolunteersNeeded()) {
     //   echo "Volunteering SignUp: Donor $donorID successfully signed up to volunteer for the event.\n";
        return $this->addVolunteer($donorID);
    }
    // echo "Volunteering SignUp: No more spots available for volunteers.\n";
    return false;
}

public function getAllEvents(): array {
    $sql = "SELECT * FROM volunteering_events";
    $dbConnection = Event::getDatabaseConnection();
    $result = $dbConnection->query($sql);

    $events = [];
    if ($result) {
        foreach ($result as $eventData) {
            $events[] = new VolunteeringEventStrategy(
                $eventData['name'],
                new DateTime($eventData['eventTime']),
                $eventData['location'],
                $eventData['volunteersNeeded'],
                $eventData['eventID']
            );
        }
    }
    return $events;
}


public function checkEventStatus(): string {
    if ($this->getVolunteersNeeded() > count($this->getVolunteersList())) {
        return 'Volunteering Event is open for more volunteers';
    }
    return 'Volunteering Event is fully booked';
}

public function generateEventReport(): string {
    $report = "Volunteering Event Report for Event ID: " . $this->getEventID() . "\n";
    $report .= "Location: " . $this->getLocation() . "\n";
    $report .= "Volunteers Needed: " . $this->getVolunteersNeeded() . "\n";
    $report .= "Current Volunteers: " . count($this->getVolunteersList()) . "\n";
    $report .= "Volunteering Event Status: " . $this->checkEventStatus() . "\n";
    return $report;
}
}
