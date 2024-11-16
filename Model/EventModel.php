<?php
require_once 'db_connection.php'; 
require_once 'db_connection.php'; 
require_once 'IMaintainable.php';
require_once 'IEvent.php';
require_once 'IObserver.php';
require_once 'ISubject.php';

class Event implements IMaintainable, ISubject {
    private array $Eventobservers = [];
    private DateTime $time;
    private string $location;
    private int $volunteersNeeded;
    private int $eventID;
    private array $volunteersList;
    private static DatabaseConnection $dbConnection; 
    private IEvent $eventStrategy;
    private String $status;

    public function __construct(DateTime $time, string $location, int $volunteersNeeded, int $eventID,IEvent $eventStrategy) {
        $this->time = $time;
        $this->location = $location;
        $this->volunteersNeeded = $volunteersNeeded;
        $this->eventID = $eventID;
        $this->volunteersList = [];
        $this->eventStrategy = $eventStrategy; // Default strategy
    }

    public function getTime(): DateTime {
        return $this->time;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getVolunteersNeeded(): int {
        return $this->volunteersNeeded;
    }

    public function getEventID(): int {
        return $this->eventID;
    }

    public function getVolunteersList(): array {
        return $this->volunteersList;
    }

    public function addVolunteer(int $donorID): bool {
        if (count($this->volunteersList) < $this->volunteersNeeded) {
            $this->volunteersList[] = $donorID;
            return true;
        }
        return false;
    }

    public function registerObserver(IObserver $observer): void {
        $this->Eventobservers[] = $observer;
    }

    public function removeObserver(IObserver $observer): void {
        $index = array_search($observer, $this->Eventobservers, true);
        if ($index !== false) {
            unset($this->Eventobservers[$index]);
        }
    }

    public function notifyObservers(): void {
        foreach ($this->Eventobservers as $observer) {
            $observer->UpdateStatus();  // Notify each donor
        }
    }
    //notifyyyy el oberverss
    
    public function SetStatus(string $EventStatus): void {
        $this->status = $EventStatus;
        $this->notifyObservers();
    }

    // public function setStrategy(IEvent $eventStrategy): void {
    //     $this->eventStrategy = $eventStrategy;
    // }

    // public function signUpBasedOnStrategy(int $donorID): bool {
    //     return $this->eventStrategy->signUp($this, $donorID);
    // }


        // Set the database connection
        public static function setDatabaseConnection(DatabaseConnection $dbConnection) {
            self::$dbConnection = $dbConnection;
        }
        public static function getDatabaseConnection() {
           return self::$dbConnection;
        }

    public static function create($object): bool {
        $sql = "INSERT INTO events (eventID, eventTime, location, volunteersNeeded) VALUES (:eventID, :eventTime, :location, :volunteersNeeded)";
        $params = [
            ':eventID' => $object->eventID,
            ':eventTime' => $object->time->format('Y-m-d H:i:s'),
            ':location' => $object->location,
            ':volunteersNeeded' => $object->volunteersNeeded
        ];
        return $object->dbConnection->execute($sql, $params);
    }

    public static function retrieve($key) {
        $dbConnection = Event::getDatabaseConnection();
        $sql = "SELECT * FROM events WHERE eventID = :eventID";
        $params = [':eventID' => $key];
        $result = self::$dbConnection->query($sql, $params);
        if ($result) {
            return new Event(
                new DateTime($result['eventTime']),
                $result['location'],
                $result['volunteersNeeded'],
                $result['eventID'],
                new CampaignStrategy() // Default strategy
            );
        }
        return null;
    }

    public static function update($object): bool {
        $sql = "UPDATE events SET eventTime = :eventTime, location = :location, volunteersNeeded = :volunteersNeeded WHERE eventID = :eventID";
        $params = [
            ':eventTime' => $object->time->format('Y-m-d H:i:s'),
            ':location' => $object->location,
            ':volunteersNeeded' => $object->volunteersNeeded,
            ':eventID' => $object->eventID
        ];
        return $object->dbConnection->execute($sql, $params);
    }

    public static function delete($eventID): bool {
        $sql = "DELETE FROM events WHERE eventID = :eventID";
        $params = [':eventID' => $eventID];
        return self::$dbConnection->execute($sql, $params);
    }

    public static function getAll(): array {
        $sql = "SELECT * FROM events";
        $result = self::$dbConnection->query($sql);
        $events = [];
        if ($result) {
            foreach ($result as $row) {
                $events[] = new Event(
                    new DateTime($row['eventTime']),
                    $row['location'],
                    $row['volunteersNeeded'],
                    $row['eventID'],
                    new CampaignStrategy() // Default strategy
                );
            }
        }
        return $events;
    }
}
?>