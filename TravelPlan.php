<?php

require_once 'db_connection.php';

class TravelPlan {
    private int $travelPlanID;
    private int $userID;
    private int $eventID;
    private string $destination;
    private string $startDate;
    private string $endDate;
    private string $transportMode;
    private float $cost;

    public function __construct(
        int $userID,
        int $eventID,
        string $destination,
        string $startDate,
        string $endDate,
        string $transportMode,
        float $cost
    ) {
        $this->userID = $userID;
        $this->eventID = $eventID;
        $this->destination = $destination;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->transportMode = $transportMode;
        $this->cost = $cost;
    }

    public function save(): bool {
        $db = DatabaseConnection::getInstance();
        $sql = "INSERT INTO travel_plans (userID, eventID, destination, startDate, endDate, transportMode, cost) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$this->userID, $this->eventID, $this->destination, $this->startDate, $this->endDate, $this->transportMode, $this->cost];
        return $db->execute($sql, $params);
    }

    public static function retrieve(int $travelPlanID): ?self {
        $db = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM travel_plans WHERE travelPlanID = ?";
        $result = $db->query($sql, [$travelPlanID]);
        if ($result) {
            return new self(
                $result['userID'],
                $result['eventID'],
                $result['destination'],
                $result['startDate'],
                $result['endDate'],
                $result['transportMode'],
                $result['cost']
            );
        }
        return null;
    }

    public static function delete(int $travelPlanID): bool {
        $db = DatabaseConnection::getInstance();
        $sql = "DELETE FROM travel_plans WHERE travelPlanID = ?";
        return $db->execute($sql, [$travelPlanID]);
    }
}
?>
