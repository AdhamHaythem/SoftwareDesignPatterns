<?php

require_once 'userModel.php';
require_once 'DonationModel.php';
require_once 'CampaignStrategy.php';
require_once 'IPaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IObserver.php';
require_once 'IEvent.php';

class Donor extends UserModel implements IObserver {
    private static int $counter = 1;
    private int $donorID;
    private array $donationsHistory;
    private float $totalDonations;
    private array $campaignsJoined = [];
    private ?IPaymentStrategy $paymentMethod;
    private ?ISubject $eventData;
    private ?Event $eventStrategy;

    private ?ICommand $currentCommand = null;  
    private array $undoStack = [];  // Stack -> undo commands
    private array $redoStack = [];   // Stack -> redo commands
 
    public function __construct(
        int $userID,
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        IPaymentStrategy $paymentMethod=null,
        Event $event=null
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donorID =self::$counter;
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        if ($event !== null) {
            $this->campaignsJoined[] = $event;
        }
        $this->paymentMethod = $paymentMethod;
        $this->donorID = self::$counter++;
    }

    public function setCommand(ICommand $command): void {
        $this->currentCommand = $command;
        $this->undoStack[] = $command;
    }

    public function undo(): void {
        if (count($this->undoStack) > 0) {
            $command = array_pop($this->undoStack);
            $command->execute();
            $this->redoStack[] = $command;
            echo "Undo executed.\n";
        } else {
            echo "Nothing to undo.\n";
        }
    }

    public function redo(): void {
        if (count($this->redoStack) > 0) {
            $command = array_pop($this->redoStack);
            $command->execute();
            $this->undoStack[] = $command;
            echo "Redo executed.\n";
        } else {
            echo "Nothing to redo.\n";
        }
    }



    public static function create($donor): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            // 1. Insert into `user` table
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE username = VALUES(username), email = VALUES(email)";
    
            $userParams = [
                $donor->getUserID(), // userID
                $donor->getUsername(), // username
                $donor->getFirstname(), // firstName
                $donor->getLastname(), // lastName
                $donor->getEmail(), // email
                password_hash($donor->getPassword(), PASSWORD_DEFAULT), // hashed password
                json_encode($donor->getLocation()), // location as JSON
                $donor->getPhoneNumber(), // phoneNumber
                true // isActive
            ];
    
            // Execute user table insertion
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert or update user record.");
            }
    
            // 2. Insert into `donor` table
            $donorSql = "INSERT INTO donor (donorID,userID, donationHistory, totalDonations, goalAmount)
                         VALUES (?, ?, ?, ?,?)
                         ON DUPLICATE KEY UPDATE totalDonations = VALUES(totalDonations), goalAmount = VALUES(goalAmount)";
    
            $donorParams = [
                $donor->getUserID(),
                $donor->getUserID(), // userID
                json_encode($donor->getDonationHistory()), // donationHistory as JSON
                $donor->getTotalDonationsStrategy(), // totalDonations
                0// goalAmount
            ];
    
            // Execute donor table insertion
            if (!$dbConnection->execute($donorSql, $donorParams)) {
                throw new Exception("Failed to insert or update donor record.");
            }
    
            return true;
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error creating donor: " . $e->getMessage());
            return false;
        }
    }
    
    
    
    
    

    public static function retrieve($donorID): ?Donor {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "SELECT * FROM donor WHERE userID = :donorID";
        $params = [':donorID' => $donorID];

        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new Donor(
                $result['userID'],
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['email'],
                $result['password'],
                json_decode($result['location'], true),
                $result['phoneNumber'],
                null, 
                null,    
            );
        }
        return null;
    }
    public static function update($donor): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "UPDATE donor SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber, 
                    totalDonations = :totalDonations 
                WHERE userID = :userID";

        $params = [
            ':username' => $donor->getUsername(),
            ':firstname' => $donor->getFirstname(),
            ':lastname' => $donor->getLastname(),
            ':email' => $donor->getEmail(),
            ':password' => password_hash($donor->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($donor->getLocation()), // Assuming location is an array
            ':phoneNumber' => $donor->getPhoneNumber(),
            ':totalDonations' => $donor->getTotalDonations(),
            ':userID' => $donor->getDonorID()
        ];

        return $dbConnection->execute($sql, $params);
    }

    public static function delete($donorID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "DELETE FROM donor WHERE userID = :donorID";
        $params = [':donorID' => $donorID];

        return $dbConnection->execute($sql, $params);
    }


    public function getDonationHistory(): array {
        return $this->donationsHistory;
    }

    // public function addDonation(Donation $donation): bool {
    //     $this->donationsHistory[] = $donation;
    //     $this->totalDonations += $donation->getAmount();
    //     return true;
    // }

    public function addEvent(Event $event): void {
        $this->campaignsJoined[] = $event;
    }    

    public function getEvents(): array {
        return $this->campaignsJoined;
    }
    //StrategyMethods

    public function joinEvent() {
        $this->eventStrategy->signUp($this->donorID);
    } 

    public function getAllEventsStrategy(){
        $this->eventStrategy->getAllEvents();
    }


    public function checkEventStatusStrategy(){

        return $this->eventStrategy->checkEventStatus();
    }

    public function generateEventReportStrategy(){
        $this->eventStrategy->generateEventReport();
    }

    public function getTotalDonationsStrategy(): float {
        return $this->totalDonations;
    }

    // Switching between strategies
    public function setPaymentMethod(IPaymentStrategy $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function setEventMethod(Event $eventStrategy): void {
        $this->eventStrategy = $eventStrategy;
    }

    // Observer status update
    public function UpdateStatus(string $status): void {
        echo "Donor {$this->donorID} has been notified about the event update: $status\n";
    }

    public function getDonorID(): int {
        return $this->donorID;
    }

    public function getPaymentMethod(): IPaymentStrategy {
        return $this->paymentMethod;
    }



    public function getEventMethod(): Event{
        return $this->eventStrategy;
    }

    public function getEventData(): ISubject {
        return $this->eventData;
    }
}
?>
