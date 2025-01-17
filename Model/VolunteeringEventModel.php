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

    public static function retrieve($key): ?VolunteeringEventStrategy {
        $sql = "SELECT * FROM volunteering_events WHERE eventID = :eventID";
        $params = [':eventID' => $key];
        $dbConnection = DatabaseConnection::getInstance();
        $result = $dbConnection->query($sql, $params);

        if ($result) {
            return new VolunteeringEventStrategy(
                $result['name'],
                new DateTime($result['eventTime']),
                $result['location'],
                $result['volunteersNeeded'],
                $result['eventID'],
            );
        }
        return null;
    }

    public static function update($object): bool {
        $sql = "UPDATE volunteering_events SET name = :name, eventTime = :eventTime, location = :location, volunteersNeeded = :volunteersNeeded WHERE eventID = :eventID";
        $params = [
            ':name' => $object->name,
            ':eventTime' => $object->getTime()->format('Y-m-d H:i:s'),
            ':location' => $object->getLocation(),
            ':volunteersNeeded' => $object->getVolunteersNeeded(),
            ':eventID' => $object->getEventID()
        ];
        return $object->dbConnection->execute($sql, $params);
    }

    public static function delete($eventID): bool {
        $sql = "DELETE FROM volunteering_events WHERE eventID = :eventID";
        $params = [':eventID' => $eventID];
        
        $dbConnection= Event::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
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
