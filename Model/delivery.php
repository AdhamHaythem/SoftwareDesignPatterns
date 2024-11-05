<?php
class Delivery {
    private static int $nextId = 1;  // Static property to keep track of the next unique ID
    private int $deliveryId;         // Unique ID for each Delivery instance
    private string $status;          // Represents the delivery status (e.g., 'pending', 'in_transit', 'delivered')
    private string $item;            // Represents the item being delivered
    private string $destination;     // Represents the destination of the delivery

    public function __construct(string $status, string $item, string $destination) {
        $this->deliveryId = self::$nextId++; // Assign and increment the unique ID
        $this->status = $status;
        $this->item = $item;
        $this->destination = $destination;
    }
    public function getDeliveryId(): int {
        return $this->deliveryId;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function setStatus(string $status): void {
        $this->status = $status;
    }

    // Getter for item
    public function getItem(): string {
        return $this->item;
    }

    // Getter for destination
    public function getDestination(): string {
        return $this->destination;
    }
}

?>

<?php 
require_once 'employee.php';

class DeliveryPersonnel extends Employee {   
    private string $vehicleType;
    private int $deliveriesCompleted = 0;
    private array $currentLoad = []; // Array to hold Delivery objects

    public function __construct( 
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
        DatabaseConnection $dbConnection,
        string $title,
        int $employeeId,
        int $salary,
        int $workingHours,
        string $vehicleType
    ) {
        // Call parent constructor to initialize Employee properties
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $usernameID,
            $password,
            $location,
            $phoneNumber,
            $dbConnection,
            $title,
            $employeeId,
            $salary,
            $workingHours
        );
        $this->vehicleType = $vehicleType;
    }

    // Method to add a delivery to the current load
    public function addDelivery(Delivery $delivery): void {
        $this->currentLoad[] = $delivery;
    }

    // Method to mark a delivery as completed
    public function completeDelivery(int $index): bool {
        if (isset($this->currentLoad[$index])) {
            $this->currentLoad[$index]->setStatus('delivered');
            $this->deliveriesCompleted++;
            return true;
        }
        return false;
    }

    // Getter for deliveriesCompleted
    public function getDeliveriesCompleted(): int {
        return $this->deliveriesCompleted;
    }

    // Getter for currentLoad
    public function getCurrentLoad(): array {
        return $this->currentLoad;
    }

    // Getter for vehicleType
    public function getVehicleType(): string {
        return $this->vehicleType;
    }
}

?>
