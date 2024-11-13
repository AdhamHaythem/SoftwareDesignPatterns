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
    private array $campaignsJoined;
    private IPaymentStrategy $paymentMethod;
    private ISubject $eventData;
    private ISubject $newsData;
    private IEvent $eventStrategy;

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
        IEvent $eventStrategy, //defaultevent
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
    public function joinEvent(Event $event) {
        $this->eventStrategy->signUp($event, $this->donorID);
    }

    public function getAllEvents(){
        $this->eventStrategy->getAllEvents();
    }

    public function processEvents(){
        $this->eventStrategy->processEvents();
    }

    public function checkEventStatus(Event $event){
        $this->eventStrategy->checkEventStatus($event);
    }

    public function generateEventReport(Event $event){
        $this->eventStrategy->generateEventReport($event);
    }
    public function sendReminderToVolunteers(Event $event){
        $this->eventStrategy->sendReminderToVolunteers($event);
    }

    public function getTotalDonations(): float {
        return $this->totalDonations;
    }
    
    //Switch ma ben el strategies henaaaa
    public function setPaymentMethod(IPaymentStrategy $paymentMethod): void {
       $this->paymentMethod = $paymentMethod;
   }

   public function setEventMethod(IEvent $eventStrategy): void {
    $this->eventStrategy = $eventStrategy;
}

    public function UpdateStatus(): void {
        echo "Donor {$this->donorID} has been notified about the event update.\n";
        
        $this->totalDonations = 0.0;
        foreach ($this->donationsHistory as $donation) {
            $this->totalDonations += $donation->getAmount();
        }
    }

    public function getDonorID(): int {
        return $this->donorID;
    }

   public function getPaymentMethod(): IPaymentStrategy {
       return $this->paymentMethod;
    }


    public function getEventMethod(): IEvent{
        return $this->eventStrategy;
    }

    public function getEventData(): ISubject {
        return $this->eventData;
    }

    public function getNewsData(): ISubject {
        return $this->newsData;
    }
}
?>