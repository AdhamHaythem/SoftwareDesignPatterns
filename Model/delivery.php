<?php
class Delivery {
    private static int $nextId = 1;
    private int $deliveryId;
    private string $status;
    private string $item;
    private string $destination;
    private static DatabaseConnection $dbConnection;  // Assuming this exists

    public function __construct(string $status, string $item, string $destination) {
        $this->deliveryId = self::$nextId++;
        $this->status = $status;
        $this->item = $item;
        $this->destination = $destination;
    }

    // Set Database Connection
    public static function setDatabaseConnection(DatabaseConnection $dbConnection) {
        self::$dbConnection = $dbConnection;
    }

    // CRUD Methods
    public static function create(Delivery $delivery): bool {
        $sql = "INSERT INTO deliveries (deliveryId, status, item, destination) VALUES (:deliveryId, :status, :item, :destination)";
        
        $params = [
            ':deliveryId' => $delivery->getDeliveryId(),
            ':status' => $delivery->getStatus(),
            ':item' => $delivery->getItem(),
            ':destination' => $delivery->getDestination(),
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    public static function retrieve(int $deliveryId): ?Delivery {
        $sql = "SELECT * FROM deliveries WHERE deliveryId = :deliveryId";
        $params = [':deliveryId' => $deliveryId];
        
        $result = self::$dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new Delivery(
                $result['status'],
                $result['item'],
                $result['destination']
            );
        }
        return null;
    }

    public static function update(Delivery $delivery): bool {
        $sql = "UPDATE deliveries SET status = :status, item = :item, destination = :destination WHERE deliveryId = :deliveryId";

        $params = [
            ':deliveryId' => $delivery->getDeliveryId(),
            ':status' => $delivery->getStatus(),
            ':item' => $delivery->getItem(),
            ':destination' => $delivery->getDestination()
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    public static function delete(int $deliveryId): bool {
        $sql = "DELETE FROM deliveries WHERE deliveryId = :deliveryId";
        $params = [':deliveryId' => $deliveryId];

        return self::$dbConnection->execute($sql, $params);
    }

    // Getters
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
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        int $salary,
        int $workingHours,
        string $vehicleType,
        int $userID = 0 
    ) {
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            "Delivery",
            $salary,
            $workingHours,
            $userID
        );
        $this->vehicleType = $vehicleType;
    }

    public function setVehicleType($vehicleType) {

        $this->vehicleType = $vehicleType;

    }

    public function setDeliveriesCompleted($deliveriesCompleted) {

        $this->deliveriesCompleted = $deliveriesCompleted;

    }

    // CRUD Methods
    public static function create($personnel): bool {
        // Check if the provided object is an instance of DeliveryPersonnel
        if (!$personnel instanceof DeliveryPersonnel) {
            throw new InvalidArgumentException("Expected instance of DeliveryPersonnel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // 1. Insert into the `user` table
            $userSql = "INSERT INTO user (userID, username, firstname, lastname, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $userParams = [
                $personnel->getUserID(),
                $personnel->getUsername(),
                $personnel->getFirstname(),
                $personnel->getLastname(),
                $personnel->getEmail(),
                password_hash($personnel->getPassword(), PASSWORD_DEFAULT), // Hash the password securely
                json_encode($personnel->getLocation()), // Serialize location as JSON
                $personnel->getPhoneNumber(),
                1 // isActive (true)
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert into `user` table.");
            }
    
            // 2. Insert into the `employee` table
            $employeeSql = "INSERT INTO employee (userID, title, salary, workingHours)
                            VALUES (?, ?, ?, ?)";
    
            $employeeParams = [
                $personnel->getUserID(),
                $personnel->getTitle(),
                $personnel->getSalary(),
                $personnel->getHoursWorked()
            ];
    
            if (!$dbConnection->execute($employeeSql, $employeeParams)) {
                throw new Exception("Failed to insert into `employee` table.");
            }
    
            // 3. Insert into the `delivery_personnel` table
            $deliverySql = "INSERT INTO deliverypersonnel (userID, vehicleType, driverLicense, deliveriesCompleted, currentLoad)
                            VALUES (?, ?, ?, ?, ?)";
    
            $deliveryParams = [
                $personnel->getUserID(),
                $personnel->getVehicleType(),
                1,
                $personnel->getDeliveriesCompleted(),
                $personnel->getCurrentLoad()
            ];
    
            if (!$dbConnection->execute($deliverySql, $deliveryParams)) {
                throw new Exception("Failed to insert into `delivery_personnel` table.");
            }
    
            // If all insertions are successful, return true
            return true;
    
        } catch (Exception $e) {
            // Log the error and return false
            error_log("Error creating delivery personnel: " . $e->getMessage());
            return false;
        }
    }

    
    
    

    public static function retrieve($userID): ?DeliveryPersonnel {
        $sql = "SELECT * FROM deliverypersonnel dp
                JOIN employee e ON dp.userID = e.userID
                JOIN user u ON dp.userID = u.userID
                WHERE dp.userID = ?";
        $params = [$userID];
    
        $dbConnection = DatabaseConnection::getInstance();
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0];
    
            return new DeliveryPersonnel(
                $row['username'],
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['password'],
                json_decode($row['locationList'], true),
                $row['phoneNumber'],
                $row['salary'],
                $row['workingHours'],
                $row['vehicleType'],
                (int)$row['userID']
            );
        }
    
        return null;
    }
    
    

    public static function update($personnel): bool {
        if (!$personnel instanceof DeliveryPersonnel) {
            throw new InvalidArgumentException("Expected instance of DeliveryPersonnel");
        }
    
        $sql = "UPDATE user u
                JOIN employee e ON u.userID = e.userID
                JOIN deliverypersonnel dp ON e.userID = dp.userID
                SET 
                    u.username = ?, 
                    u.firstName = ?, 
                    u.lastName = ?, 
                    u.email = ?, 
                    u.password = ?, 
                    u.locationList = ?, 
                    u.phoneNumber = ?, 
                    e.title = ?, 
                    e.salary = ?, 
                    e.workingHours = ?, 
                    dp.vehicleType = ?, 
                    dp.deliveriesCompleted = ?
                WHERE u.userID = ?";
    
        $params = [
            $personnel->getUsername(),
            $personnel->getFirstname(),
            $personnel->getLastname(),
            $personnel->getEmail(),
            password_hash($personnel->getPassword(), PASSWORD_DEFAULT),
            json_encode($personnel->getLocation()),
            $personnel->getPhoneNumber(),
            $personnel->getTitle(),
            $personnel->getSalary(),
            $personnel->getHoursWorked(),
            $personnel->getVehicleType(),
            $personnel->getDeliveriesCompleted(),
            $personnel->getUserID()
        ];
    
        $dbConnection = DatabaseConnection::getInstance();
        return $dbConnection->execute($sql, $params);
    }
    

    public function addDelivery(Delivery $delivery): void {
        $this->currentLoad[] = $delivery;
    }

    public function completeDelivery(int $index): bool {
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
