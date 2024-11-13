<?php
require_once 'Event.php'; 
require_once 'IObserver.php';
require_once 'db_connection.php';
require_once 'Donor.php';

class VolunteeringEvent extends Event {
    private string $name;
    private array $observers = [];


    public function __construct(string $name, DateTime $time, string $location, int $volunteersNeeded, int $eventID) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID);
        $this->name = $name;
    }


    public function getVolunteerInfo(Donor $volunteer): array {
        return [
            'id' => $volunteer->getDonorID(),
            'name' => $volunteer->getUsername(),

        ];
    }

    public function assignToEvent(Donor $volunteer): bool {
        if (count($this->getVolunteersList()) < $this->getVolunteersNeeded()){
            $this->getVolunteersList()[] = $volunteer->getDonorID();
            $this->notifyObservers();
            return true;
        }
        return false;
    }

    
    public static function create($object): bool {
        $sql = "INSERT INTO volunteering_events (name, eventTime, location, volunteersNeeded, eventID) VALUES (:name, :eventTime, :location, :volunteersNeeded, :eventID)";
        $params = [
            ':name' => $object->name,
            ':eventTime' => $object->getTime()->format('Y-m-d H:i:s'),
            ':location' => $object->getLocation(),
            ':volunteersNeeded' => $object->getVolunteersNeeded(),
            ':eventID' => $object->getEventID()
        ];
        return $object->dbConnection->execute($sql, $params);
    }

    public static function retrieve($key): ?VolunteeringEvent {
        $sql = "SELECT * FROM volunteering_events WHERE eventID = :eventID";
        $params = [':eventID' => $key];
        $dbConnection= Event::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);

        if ($result) {
            return new VolunteeringEvent(
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
    


    public function notifyObservers(): void {
        foreach ($this->observers as $observer) {
            $observer->update(); 
        }
    }
}
