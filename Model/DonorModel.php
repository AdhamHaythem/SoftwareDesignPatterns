<?php

require_once 'User.php'; 
require_once 'Donation.php'; 
require_once 'Campaign.php';
require_once 'PaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IEvent.php'; 

class Donor extends User {
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
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
        PaymentStrategy $paymentMethod,
        ISubject $eventData,
        ISubject $newsData
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $usernameID, $password, $location, $phoneNumber);
        $this->donorID = $userID;
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        $this->campaignsJoined = [];
        $this->paymentMethod = $paymentMethod;
        $this->eventData = $eventData;
        $this->newsData = $newsData;
    }

    public function getDonationHistory(): array {
        global $donationManager;
        return $donationManager->getDonationsByDonor($this->donorID);
    }

    public function addDonation(Donation $donation): bool {
        $this->donationsHistory[] = $donation;
        $this->totalDonations += $donation->getAmount();
        global $donationManager;
        return $donationManager->addDonationForDonor($this->donorID, $donation);
    }

    public function joinEvent(IEvent $event): bool {
        // Example implementation for joining an event
        // You may add more functionality as needed
        return true;
    }

    public function getTotalDonations(): float {
        return $this->totalDonations;
    }

    public function setPaymentMethod(PaymentStrategy $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function update(): void {
       
    }

    public function joinCampaign(Campaign $campaign): void {
        $this->campaignsJoined[] = $campaign;
    }

    public function getCampaignsJoined(): array {
        return $this->campaignsJoined;
    }
}
?>
