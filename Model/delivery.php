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
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
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
            $password,
            $location,
            $phoneNumber,
            $title,
            $salary,
            $workingHours
        );
        $this->vehicleType = $vehicleType;
    }

    // CRUD Methods
    public static function create($personnel): bool {
        $dbConnection = DatabaseConnection::getInstance();
        if (!$personnel instanceof DeliveryPersonnel) {
            throw new InvalidArgumentException("Expected instance of DeliveryPersonnel");
        }
        $sql = "INSERT INTO delivery_personnel (userID, username, firstname, lastname, email, password, location, phoneNumber, title, salary, workingHours, vehicleType, deliveriesCompleted)
                VALUES (:userID, :username, :firstname, :lastname, :email, :password, :location, :phoneNumber, :title, :salary, :workingHours, :vehicleType, :deliveriesCompleted)";
        
        $params = [
            ':userID' => $personnel->getUserID(),
            ':username' => $personnel->getUsername(),
            ':firstname' => $personnel->getFirstname(),
            ':lastname' => $personnel->getLastname(),
            ':email' => $personnel->getEmail(),
            ':password' => password_hash($personnel->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($personnel->getLocation()),
            ':phoneNumber' => $personnel->getPhoneNumber(),
            ':title' => $personnel->getTitle(),
            ':salary' => $personnel->getSalary(),
            ':workingHours' => $personnel->getHoursWorked(),
            ':vehicleType' => $personnel->getVehicleType(),
            ':deliveriesCompleted' => $personnel->getDeliveriesCompleted()
        ];
        return $dbConnection->execute($sql, $params);
    }

    public static function retrieve($userID): ?DeliveryPersonnel {
        $sql = "SELECT * FROM delivery_personnel WHERE userID = :userID";
        $params = [':userID' => $userID];
    
        // Get the database connection
        $dbConnection = UserModel::getDatabaseConnection();
    
        // Use query instead of execute to fetch data
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            return new DeliveryPersonnel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['password'],
                json_decode($result['location'], true),
                $result['phoneNumber'],
                $result['title'],
                $result['salary'],
                $result['workingHours'],
                $result['vehicleType']
            );
        }
    
        return null;
    }
    

    public static function update($personnel): bool {
        $sql = "UPDATE delivery_personnel SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber,
                    title = :title,
                    salary = :salary,
                    workingHours = :workingHours,
                    vehicleType = :vehicleType,
                    deliveriesCompleted = :deliveriesCompleted
                WHERE userID = :userID";

        $params = [
            ':userID' => $personnel->getUserID(),
            ':username' => $personnel->getUsername(),
            ':firstname' => $personnel->getFirstname(),
            ':lastname' => $personnel->getLastname(),
            ':email' => $personnel->getEmail(),
            ':password' => password_hash($personnel->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($personnel->getLocation()),
            ':phoneNumber' => $personnel->getPhoneNumber(),
            ':title' => $personnel->getTitle(),
            ':salary' => $personnel->getSalary(),
            ':workingHours' => $personnel->getHoursWorked(),
            ':vehicleType' => $personnel->getVehicleType(),
            ':deliveriesCompleted' => $personnel->getDeliveriesCompleted()
        ];
        $dbConnection=UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function delete($userID): bool {
        $sql = "DELETE FROM delivery_personnel WHERE userID = :userID";
        $params = [':userID' => $userID];

        $dbConnection=UserModel::getDatabaseConnection();
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
