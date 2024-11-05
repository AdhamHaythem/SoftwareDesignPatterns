<?php
require_once 'EventModel.php';
require_once 'DatabaseConnection.php';
require_once 'DonorModel.php';
require_once 'IObserver.php';

class VolunteeringEvent extends Event {
    private string $name;
    private array $volunteers = []; // Array to store assigned volunteers
    private array $observers = [];  // Array to store observers

    private DatabaseConnection $dbConnection;

    public function __construct(string $name, DatabaseConnection $dbConnection) {
        $this->name = $name;
        $this->dbConnection = $dbConnection;
    }

    // Assign a volunteer to the event
    public function assignToEvent(Donor $volunteer): void {
        $this->volunteers[$volunteer->getId()] = $volunteer;
    }

    // Retrieve information about a specific volunteer in the event
    public function getVolunteerInfo(Donor $volunteer): ?Donor {
        return $this->volunteers[$volunteer->getId()] ?? null;
    }

    // CRUD operations

    // Create a new event in the database
    public function create(object $object): bool {
        $sql = "INSERT INTO volunteering_events (name) VALUES (:name)";
        $params = [
            ':name' => $this->name
        ];
        return $this->dbConnection->execute($sql, $params);
    }

    // Retrieve an event from the database by name
    public function retrieve(string $name): ?self {
        $sql = "SELECT * FROM volunteering_events WHERE name = :name";
        $params = [':name' => $name];
        $result = $this->dbConnection->query($sql, $params);

        if ($result) {
            $event = new self($result['name'], $this->dbConnection);
            return $event;
        }
        return null;
    }

    // Update the event details in the database
    public function update(string $name): bool {
        $sql = "UPDATE volunteering_events SET name = :name WHERE name = :old_name";
        $params = [
            ':name' => $this->name,
            ':old_name' => $name
        ];
        return $this->dbConnection->execute($sql, $params);
    }

    // Delete the event from the database
    public function delete(string $name): bool {
        $sql = "DELETE FROM volunteering_events WHERE name = :name";
        $params = [':name' => $name];
        return $this->dbConnection->execute($sql, $params);
    }

    // Observer pattern methods

    // Register an observer
    public function registerObserver(IObserver $observer): void {
        $this->observers[] = $observer;
    }

    // Remove an observer by index
    public function removeObserver(int $index): void {
        if (isset($this->observers[$index])) {
            unset($this->observers[$index]);
            $this->observers = array_values($this->observers); // Re-index the array
        }
    }

    // Notify all registered observers
    public function notifyObserver(): void {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
