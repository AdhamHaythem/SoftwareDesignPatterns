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
    private array $campaignsJoined =[];
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
        Event $eventStrategy = new CampaignStrategy(200 ,"","",400.0), //defaultevent
        ISubject $eventData //defaulttttttttt
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donorID = $userID;
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        $this->campaignsJoined = [];
        $this->paymentMethod = $paymentMethod;
        $this->eventData = $eventData;
        $this->eventStrategy = $eventStrategy;
        $this->eventData->registerObserver($this);
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
    
    //Switch ma ben el strategies henaaaa
    public function setPaymentMethod(IPaymentStrategy $paymentMethod): void {
       $this->paymentMethod = $paymentMethod;
   }

    public function setEventMethod(Event $eventStrategy): void {
        $this->eventStrategy = $eventStrategy;
}

    //observerrrr status updateeeee
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