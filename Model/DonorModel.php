<?php

require_once 'User.php';
require_once 'Donation.php';
require_once 'Campaign.php';
require_once 'PaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IEvent.php';

class Donor extends UserModel {
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
        ISubject $eventData,
        ISubject $newsData
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donorID = $userID;
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        $this->campaignsJoined = [];
        $this->paymentMethod = $paymentMethod;
        $this->eventData = $eventData;
        $this->newsData = $newsData;
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
        //lesaaaaaaa lamaaa n3ml interface ll event
        return true;
    }

    public function getTotalDonations(): float {
        return $this->totalDonations;
    }

    public function setPaymentMethod(PaymentStrategy $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function updateStatus(): void {
        $this->totalDonations = 0.0;
        foreach ($this->donationsHistory as $donation) {
            $this->totalDonations += $donation->getAmount();
        }

        foreach ($this->campaignsJoined as $campaign) {
            if ($campaign->isCompleted()) {
                $campaign->notifyDonor($this);
            }
        }

        // 3. Synchronize donor data (optional: if donor data needs to be fetched/updated from an external source)
        // Optionally, fetch the latest data for the donor from a database, API, etc.
        // $this->fetchLatestDataFromDatabase();

        $this->newsData->notify($this);  // Assuming ISubject has a method notify() to update the donor
    }

    public function joinCampaign(CampaignModel $campaign): void {
        $this->campaignsJoined[] = $campaign;
    }

    public function getCampaignsJoined(): array {
        return $this->campaignsJoined;
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

