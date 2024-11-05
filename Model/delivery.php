<?php
class Delivery {
    private static int $nextId = 1;  // Static property to keep track of the next unique ID
    private int $deliveryId;         
    private string $status;         
    private string $item;            
    private string $destination;    

    public function __construct(string $status, string $item, string $destination) {
        $this->deliveryId = self::$nextId++;
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

    public function getItem(): string {
        return $this->item;
    }

    public function getDestination(): string {
        return $this->destination;
    }
}

?>

<?php 
require_once 'employee.php';

class DeliveryPersonnel extends EmployeeModel {   
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

    public function addDelivery(Delivery $delivery): void {
        $this->currentLoad[] = $delivery;
    }

    public function completeDelivery(int $index): bool { //M7tag a-call b ID
        if (isset($this->currentLoad[$index])) {
            $this->currentLoad[$index]->setStatus('delivered');
            $this->deliveriesCompleted++;
            return true;
        }
        return false;
    }

    public function getDeliveriesCompleted(): int {
        return $this->deliveriesCompleted;
    }

    public function getCurrentLoad(): array {
        return $this->currentLoad;
    }

    public function getVehicleType(): string {
        return $this->vehicleType;
    }
}

?>
