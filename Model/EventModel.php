<?php

class Event {
    private DateTime $time;
    private string $location;
    private int $volunteersNeeded;
    private int $eventID;
    private array $volunteersList;
    private DatabaseConnection $dbConnection;

    public function __construct(DateTime $time, string $location, int $volunteersNeeded, int $eventID) {
        $this->time = $time;
        $this->location = $location;
        $this->volunteersNeeded = $volunteersNeeded;
        $this->eventID = $eventID;
        $this->volunteersList = [];
    }
}    
?>    