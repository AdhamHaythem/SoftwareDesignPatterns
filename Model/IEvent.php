<?php
require_once "IMaintainable.php";
require_once "ISubject.php";

abstract class Event implements IMaintainable, ISubject {
    private string $name;
    private array $Eventobservers = [];
    private DateTime $time;

    private static int $counter =1 ;
    private array $location= [];
    private int $volunteersNeeded;
    private array $volunteersList = [];
    private static ?DatabaseConnection $dbConnection = null;
    private string $status;
    private int $eventID=0;

    public function __construct(
        DateTime $time,
        array $location,
        int $volunteersNeeded,
        int $eventID,
        string $name
    ) {
        $this->time = $time;
        $this->location = $location;
        $this->volunteersNeeded = $volunteersNeeded;
        $this->eventID = $eventID === 0 ? Event::useCounter() : $eventID;
        $this->name = $name;
    }

    abstract public function signUp(int $donorID): bool;
    abstract public function getAllEvents(): array;
    abstract public function checkEventStatus(): string;
    abstract public function generateEventReport(): string;

    private static function useCounter(): int {
        $ID = self::$counter;
        self::$counter++;
        $db_connection = DatabaseConnection::getInstance();
        $sql = "UPDATE counters SET EventID = ? where CounterID = 1";
        $params = [self::$counter];
        $db_connection->execute($sql, $params);
        return $ID;
    }

    public static function setCounter(int $counter): void {
        self::$counter = $counter;
    }


    public function setTime(DateTime $time): void {
        $this->time = $time;
    }

    public function getLocation(): array {
        return $this->location;
    }

    public function setLocation(array $location): void {
        $this->location = $location;
    }

    public function getVolunteersNeeded(): int {
        return $this->volunteersNeeded;
    }

    public function setVolunteersNeeded(int $volunteersNeeded): void {
        $this->volunteersNeeded = $volunteersNeeded;
    }

    public function setEventID(int $eventID): void {
        $this->eventID = $eventID;
    }

    public function setVolunteersList(array $volunteersList): void {
        $this->volunteersList = $volunteersList;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getTime(): DateTime {
        return $this->time;
    }

    public function getEventID(): int {
        return $this->eventID;
    }

    public function getVolunteersList(): array {
        return $this->volunteersList;
    }

    public static function setDatabaseConnection(DatabaseConnection $connection): void {
        self::$dbConnection = $connection;
    }

    public static function getDatabaseConnection(): ?DatabaseConnection {
        return self::$dbConnection;
    }

    public function addVolunteer($donorID): bool {
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
        $this->Eventobservers = array_filter($this->Eventobservers, function($o) use ($observer) {
            return $o !== $observer;
        });
    }

    public function notifyObservers(): void {
        foreach ($this->Eventobservers as $observer) {
            $observer->UpdateStatus($this->status);
        }
    }

    public function setStatus(string $EventStatus): void {
        $this->status = $EventStatus;
        $this->notifyObservers();
    }

    public static function create($object): bool {
        if (!$object instanceof Event) {
            throw new InvalidArgumentException("Expected instance of Event");
        }
    
        $dbConnection = Event::getDatabaseConnection();
    
        try {
            $Eventsql = "INSERT INTO event (eventID,name,time,location,volunteers_needed,volunteersList) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
            $Eventparams = [
                $object->getEventID(),
                $object->getName(),
                $object->getTime()->format('Y-m-d H:i:s'),
                json_encode($object->getLocation()),
                $object->getVolunteersNeeded(),
                json_encode($object->getVolunteersList()),
            ];
    
            $Eresult = $dbConnection->execute($Eventsql, $Eventparams);
    
            return $Eresult;
        } catch (Exception $e) {
            error_log("Error creating event: " . $e->getMessage());
            return false;
        }
    }


    public static function delete($eventID): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $eventSql = "DELETE FROM event WHERE eventID = ?";
            $eventParams = [$eventID];
    
            if (!$dbConnection->execute($eventSql, $eventParams)) {
                throw new Exception("Failed to delete event record.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

    public static function update($event): bool {
        if (!$event instanceof Event) {
            throw new InvalidArgumentException("Expected instance of Event");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $eventSql = "UPDATE events SET 
                            name = ?, 
                            time = ?, 
                            location = ?, 
                            volunteers_needed = ?, 
                            volunteersList = ?
                        WHERE eventID = ?";
    
            $eventParams = [
                $event->getName(),
                $event->getTime()->format('Y-m-d H:i:s'),
                json_encode($event->getLocation()),
                $event->getVolunteersNeeded(),
                json_encode($event->getVolunteersList()),
                $event->getEventID()
            ];
    
            if (!$dbConnection->execute($eventSql, $eventParams)) {
                throw new Exception("Failed to update event record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }
    public static function retrieve($eventID): ?Event {
        $dbConnection = DatabaseConnection::getInstance();
        
        try {
            $sql = "SELECT * FROM events WHERE eventID = ?";
            $params = [$eventID];
            $result = $dbConnection->query($sql, $params);
        
            if ($result && !empty($result)) {
                $row = $result[0];
    
                if (
                    isset($row['eventID'], $row['name'], $row['time'], $row['location'], $row['volunteers_needed'], $row['volunteersList'])
                ) {


                    $location = json_decode($row['location'], true);
                    $volunteersList = json_decode($row['volunteersList'], true) ?? [];

                    $event = new Event(
                        new DateTime($row['time']), // time
                        $location,// location
                        (int)$row['volunteers_needed'], // volunteersNeeded
                        (int)$row['eventID'], // eventID
                        $row['name'] // name
                    );
                    $event->setVolunteersList(json_decode($row['volunteersList'], true) ?? []);
    
                    return $event;
                } else {
                    throw new Exception("Missing required fields in the query result.");
                }
            }
            return null;
        } catch (Exception $e) {
            error_log("Error retrieving event: " . $e->getMessage());
            return null;
        }
    }
}
?>


   