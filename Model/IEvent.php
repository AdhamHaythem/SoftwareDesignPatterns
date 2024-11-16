<?php
require "IMaintainable.php";
require "ISubject.php.php";

abstract class Event implements IMaintainable, ISubject {
    private string $name;
    private array $Eventobservers = [];
    private DateTime $time;
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
        $this->eventID = $eventID;
        $this->name = $name;
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

    public function notifyObservers(): void {
        foreach ($this->Eventobservers as $observer) {
            $observer->UpdateStatus();
        }
    }

    public function setStatus(string $EventStatus): void {
        $this->status = $EventStatus;
        $this->notifyObservers();
    }
}


?>

