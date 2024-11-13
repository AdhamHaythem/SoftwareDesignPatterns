<?php
require_once 'Event.php'; 
require_once 'IObserver.php';
require_once 'db_connection.php';
require_once 'Donor.php';

class VolunteeringEvent extends Event {
    private string $name;
    private array $observers = [];


    public function __construct(string $name, DateTime $time, string $location, int $volunteersNeeded, int $eventID, DatabaseConnection $dbConnection) {
        parent::__construct($time, $location, $volunteersNeeded, $eventID, $dbConnection);
        $this->name = $name;
    }


    public function getVolunteerInfo(Donor $volunteer): array {
        return [
            'id' => $volunteer->getDonorID(),
            'name' => $volunteer->getName(),

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
        $result = self::$dbConnection->query($sql, $params);

        if ($result) {
            return new VolunteeringEvent(
                $result['name'],
                new DateTime($result['eventTime']),
                $result['location'],
                $result['volunteersNeeded'],
                $result['eventID'],
                self::$dbConnection
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
        return self::$dbConnection->execute($sql, $params);
    }

    public function registerObserver(IObserver $observer): void {
        $this->observers[] = $observer;
    }

    public function removeObserver(int $observerID): void {
        unset($this->observers[$observerID]);
    }


    public function notifyObservers(): void {
        foreach ($this->observers as $observer) {
            $observer->update(); 
        }
    }
}
