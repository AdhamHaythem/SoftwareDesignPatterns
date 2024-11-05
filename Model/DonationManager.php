<?php

require_once 'Donation.php'; 

class DonationManager {
    private array $donations = [];
    private float $totalDonations = 0.0;
    private float $goalAmount;
    private array $campaigns = [];

    public function __construct(array $donations = [], float $totalDonations = 0.0, float $goalAmount = 0.0, array $campaigns = []) {
        $this->donations = $donations;
        $this->totalDonations = $totalDonations;
        $this->goalAmount = $goalAmount;
        $this->campaigns = $campaigns;
    }
    public function create(object $object): bool {
        if ($object instanceof Donation) {
            $this->donations[] = $object;
            $this->totalDonations += $object->getAmount();
            return true;
        } elseif ($object instanceof Campaign) {
            $this->campaigns[] = $object;
            return true;
        }
        return false;
    }

    public function retrieve(int $key): ?object {
        foreach ($this->donations as $donation) {
            if ($donation->getId() == $key) {
                return $donation;
            }
        }
        foreach ($this->campaigns as $campaign) {
            if ($campaign->getCampaignID() == $key) {
                return $campaign;
            }
        }
        return null;
    }

    public function update(int $key): bool {
        return true; 
    }

    public function delete(int $key): bool {
        foreach ($this->donations as $index => $donation) {
            if ($donation->getId() == $key) {
                unset($this->donations[$index]);
                return true;
            }
        }
        foreach ($this->campaigns as $index => $campaign) {
            if ($campaign->getCampaignID() == $key) {
                unset($this->campaigns[$index]);
                return true;
            }
        }
        return false;
    }

    public function getDonationDetails(int $donationID): ?Donation {
        foreach ($this->donations as $donation) {
            if ($donation->getId() == $donationID) {
                return $donation;
            }
        }
        return null;
    }

    public function calculateTotalDonations(): float {
        return $this->totalDonations;
    }

    public function getDonationStatistics(Donation $donation): string {
        return "Donation ID: {$donation->getId()}, Amount: {$donation->getAmount()}";
    }

    public function editCampaigns(Campaign $campaign): bool {
        return true; // Placeholder return value
    }

    public function getCampaignDetails(int $campaignID): ?Campaign {
        foreach ($this->campaigns as $campaign) {
            if ($campaign->getCampaignID() == $campaignID) {
                return $campaign;
            }
        }
        return null;
    }

    public function generateDonationReport(): array {
        return $this->donations; // Returns a list of donations
    }

    public function addCampaign(DateTime $time, string $location): bool {
        $campaignID = count($this->campaigns) + 1; // Generate a new campaign ID
        $campaign = new Campaign($campaignID, $location, $time);
        return $this->create($campaign);
    }
}
