<?php
require_once 'User.php';
require_once 'Donation.php';
require_once 'Campaign.php';
require_once 'PaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IObserver.php';
require_once 'IEvent.php';

class Donor extends UserModel implements IObserver {
    private int $donorID;
    private array $donationsHistory;
    private float $totalDonations;
    private array $campaignsJoined;
    private PaymentStrategy $paymentMethod;
    private ISubject $eventData;
    private ISubject $newsData;

    public function __construct(
        int $userID,
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        PaymentStrategy $paymentMethod,
        ISubject $eventData
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donorID = $userID;
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        $this->campaignsJoined = [];
        $this->paymentMethod = $paymentMethod;
        $this->eventData = $eventData;

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

    public function joinEvent(IEvent $event): bool {
        return true;
    }

    public function getTotalDonations(): float {
        return $this->totalDonations;
    }

    public function setPaymentMethod(PaymentStrategy $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function Update(): void {
        echo "Donor {$this->donorID} has been notified about the event update.\n";
        
        $this->totalDonations = 0.0;
        foreach ($this->donationsHistory as $donation) {
            $this->totalDonations += $donation->getAmount();
        }
    }

    public function getDonorID(): int {
        return $this->donorID;
    }

    public function getPaymentMethod(): PaymentStrategy {
        return $this->paymentMethod;
    }

    public function getEventData(): ISubject {
        return $this->eventData;
    }

    public function getNewsData(): ISubject {
        return $this->newsData;
    }
}
?>