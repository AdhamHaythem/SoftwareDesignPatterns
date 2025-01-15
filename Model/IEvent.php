<?php
require_once "IMaintainable.php";
require_once "ISubject.php";

abstract class Event implements IMaintainable, ISubject {
    private string $name;
    private array $Eventobservers = [];
    private DateTime $time;

    private static int $counter =1 ;
    private string $location;
    private int $volunteersNeeded;
    private int $eventID;
    private array $volunteersList = [];
    private static ?DatabaseConnection $dbConnection = null;
    private string $status;

    public function __construct(
        DateTime $time,
        string $location,
        int $volunteersNeeded,
        int $eventID,
        string $name
    ) {
        $this->time = $time;
        $this->location = $location;
        $this->volunteersNeeded = $volunteersNeeded;
        $this->eventID = self::$counter;
        $this->name = $name;
        self::$counter++;
    }

    abstract public function signUp(int $donorID): bool;
    abstract public function getAllEvents(): array;
    abstract public function checkEventStatus(): string;
    abstract public function generateEventReport(): string;


    public function setTime(DateTime $time): void {
        $this->time = $time;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function setLocation(string $location): void {
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

    public function notifyObservers($EventStatus): void {
        foreach ($this->Eventobservers as $observer) {
            $observer->UpdateStatus($EventStatus);
        }
    }

    public function setStatus(string $EventStatus): void {
        $this->status = $EventStatus;
        $this->notifyObservers($EventStatus);
    }

    public static function create($object): bool {
        if (!$object instanceof Event) {
            throw new InvalidArgumentException("Expected instance of Event");
        }
    
        $dbConnection = Event::getDatabaseConnection();
    
        try {
            $Eventsql = "INSERT INTO event (eventID, name, time, location, volunteers_needed, volunteersList) 
                         VALUES (?, ?, ?, ?, ?, ?)";
    
            $Eventparams = [
                $object->getEventID(),
                $object->getName(),
                $object->getTime()->format('Y-m-d H:i:s'),
                $object->getLocation(),
                $object->getVolunteersNeeded(),
                json_encode($object->getVolunteersList())
            ];
    
            $Eresult = $dbConnection->execute($Eventsql, $Eventparams);
    
            return $Eresult;
        } catch (Exception $e) {
            error_log("Error creating event: " . $e->getMessage());
            return false;
        }
    }


    public static function delete($eventID): bool {
        $dbConnection = Event::getDatabaseConnection();
    
        try {
            $Eventsql = "DELETE FROM events WHERE eventID = :eventID";
            $Eventparams = [$eventID];
    
            return $dbConnection->execute($Eventsql, $Eventparams);
        } catch (Exception $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

 public static function update($object): bool {
        if (!$object instanceof Event) {
            throw new InvalidArgumentException("Expected instance of Event");
        }
    
        $dbConnection = Event::getDatabaseConnection();
    
        try {
            $Eventsql = "UPDATE events SET 
                            name = :name, 
                            time = :time, 
                            location = :location, 
                            volunteers_needed = :volunteers_needed, 
                            volunteersList = :volunteersList
                        WHERE eventID = :eventID";
    
            $Eventparams = [
                $object->getEventID(),
                $object->getName(),
                $object->getTime()->format('Y-m-d H:i:s'),
                $object->getLocation(),
                $object->getVolunteersNeeded(),
                json_encode($object->getVolunteersList())
            ];
    
            return $dbConnection->execute($Eventsql, $Eventparams);
        } catch (Exception $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }
    public static function retrieve($eventID): ?Event {
        $dbConnection = Event::getDatabaseConnection();
    
        try {
            $Eventsql = "SELECT * FROM events WHERE eventID = :eventID";
            $Eventparams = [$eventID];
            $result = $dbConnection->query($Eventsql, $Eventparams);
    
            if ($result) {
                return new VolunteeringEventStrategy(
                    $result['name'],    
                    new DateTime($result['time']),       
                    $result['location'],                     
                    $result['volunteers_needed'],             
                    $result['eventID']  
                );
            }
            return null; 
        } catch (Exception $e) {
            error_log("Error retrieving event: " . $e->getMessage());
            return null;
        }
    }
}
?>


   