<?php

require_once 'User.php';
require_once 'Donation.php';
require_once 'Campaign.php';
require_once 'IPaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IObserver.php';
require_once 'IEvent.php';

class Donor extends UserModel implements IObserver {
    private int $donorID;
    private array $donationsHistory;
    private float $totalDonations;
    private array $campaignsJoined = [];
    private IPaymentStrategy $paymentMethod;
    private ISubject $eventData;
    private Event $eventStrategy;

    public function __construct(
        int $userID,
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        IPaymentStrategy $paymentMethod,
        Event $eventStrategy = null, //defaultevent
        ISubject $eventData =null//defaulttttttttt
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donorID = $this->getUserID();
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        $this->campaignsJoined = [];
        $this->paymentMethod = $paymentMethod;
        $this->eventData = $eventData;
        $this->eventStrategy = $eventStrategy;
        $this->eventData->registerObserver($this);
    }


    public static function create($donor): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
        // 1. Insert into `user` table first
        $userSql = "INSERT IGNORE INTO users (userID, username, firstname, lastname, email, password, location, phoneNumber)
                    VALUES (:userID, :username, :firstname, :lastname, :email, :password, :location, :phoneNumber)";
        
        $userParams = [
            ':userID' => $donor->getDonorID(),
            ':username' => $donor->getUsername(),
            ':firstname' => $donor->getFirstname(),
            ':lastname' => $donor->getLastname(),
            ':email' => $donor->getEmail(),
            ':password' => password_hash($donor->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($donor->getLocation()),
            ':phoneNumber' => $donor->getPhoneNumber()
        ];
    
        $userInserted = $dbConnection->execute($userSql, $userParams);
    
        // 2. Insert into `donors` table
        $donorSql = "INSERT INTO donors (userID, totalDonations)
                     VALUES (:userID, :totalDonations)
                     ON DUPLICATE KEY UPDATE totalDonations = VALUES(totalDonations)";
    
        $donorParams = [
            ':userID' => $donor->getDonorID(),
            ':totalDonations' => $donor->getTotalDonations()
        ];
    
        $donorInserted = $dbConnection->execute($donorSql, $donorParams);
    
        // 3. Return whether both inserts were successful
        return $userInserted && $donorInserted;
    }
    

    // public static function retrieve($donorID): ?Donor {
    //     $dbConnection = UserModel::getDatabaseConnection();
    //     $sql = "SELECT * FROM donors WHERE userID = :donorID";
    //     $params = [':donorID' => $donorID];
    
    //     $result = $dbConnection->query($sql, $params);
    
    //     if ($result && !empty($result)) {
    //         $location = json_decode($result['location'], true);
    //         $paymentMethod = new IPaymentStrategy();
    //         $eventStrategy = new CampaignStrategy(
    //             $result['eventBudget'] ?? 200,
    //             new DateTime($result['eventStartDate'] ?? '2024-11-20 10:00:00'),
    //             $result['eventName'] ?? "",
    //             $result['eventParticipants'] ?? 40,
    //             $result['eventMaxParticipants'] ?? 100,
    //             $result['eventDescription'] ?? "",
    //             $result['eventCost'] ?? 50.0,
    //             $result['eventLocation'] ?? "",
    //             $result['eventDuration'] ?? 300
    //         ); 
    //         $eventData = new ISubject();
    //         return new Donor(
    //             $result['userID'],
    //             $result['username'],
    //             $result['firstname'],
    //             $result['lastname'],
    //             $result['email'],
    //             $result['password'],
    //             $location,
    //             $result['phoneNumber'],
    //             $paymentMethod,
    //             $eventStrategy,
    //             $eventData
    //         );
    //     }
    
    //     return null;
    // }
    
    public static function update($donor): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "UPDATE donors SET 
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
        $sql = "DELETE FROM donors WHERE userID = :donorID";
        $params = [':donorID' => $donorID];

        return $dbConnection->execute($sql, $params);
    }


    public function getDonationHistory(): array {
        return $this->donationsHistory;
    }

    public function addDonation(Donation $donation): bool {
        $this->donationsHistory[] = $donation;
        $this->totalDonations += $donation->getAmount();
        return true;
    }
    
    //StrategyMethods

    public function joinEvent() {
        $this->eventStrategy->signUp($this->donorID);
    } 

    public function getAllEventsStrategy(){
        $this->eventStrategy->getAllEvents();
    }


    public function checkEventStatusStrategy(){

        return $this->eventStrategy->checkEventStatus($this->donorID);
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
