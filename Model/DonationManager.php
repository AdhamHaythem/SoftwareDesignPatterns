<?php

require_once 'Donation.php';
require_once 'Campaign.php';

class DonationManager {
    private array $donationsByDonor;
    private float $totalDonations;
    private float $goalAmount;
    private array $campaigns;

    public function __construct(float $goalAmount = 0.0, array $donations = [], array $campaigns = []) {
        $this->donationsByDonor = $donations;
        $this->totalDonations = 0.0;

        foreach ($donations as $donorID => $donationList) {
            foreach ($donationList as $donation) {
                $this->totalDonations += $donation->getAmount();
            }
        }

        $this->goalAmount = $goalAmount;
        $this->campaigns = $campaigns;
    }

    public static function create(object $object): bool {
        if ($object instanceof Donation) {
            $donorID = $object->getDonorID();
            if (!isset(self::$donationsByDonor[$donorID])) {
                self::$donationsByDonor[$donorID] = [];
            }
            self::$donationsByDonor[$donorID][] = $object;
            return true;
        } elseif ($object instanceof CampaignModel) {
            self::$campaigns[] = $object;
            return true;
        }
        return false;
    }

    public static function retrieve(int $key): ?object {
        foreach (self::$donationsByDonor as $donorID => $donations) {
            foreach ($donations as $donation) {
                if ($donation->getId() == $key) {
                    return $donation;
                }
            }
        }
        foreach (self::$campaigns as $campaign) {
            if ($campaign->getCampaignID() == $key) {
                return $campaign;
            }
        }
        return null;
    }


    public static function update(object $object): bool {
        if ($object instanceof Donation) {
            foreach (self::$donationsByDonor as $donorID => &$donations) {
                foreach ($donations as &$donation) {
                    if ($donation->getDonationID() == $object->getDonationID()) {
                        $donation = $object;
                        return true;
                    }
                }
            }
        } elseif ($object instanceof CampaignModel) {
            foreach (self::$campaigns as &$campaign) {
                if ($campaign->getCampaignID() == $object->getCampaignID()) {
                    $campaign = $object;
                    return true;
                }
            }
        }
        return false;
    }


    public static function delete(int $key): bool {
        foreach (self::$donationsByDonor as $donorID => &$donations) {
            foreach ($donations as $index => $donation) {
                if ($donation->getId() == $key) {
                    unset($donations[$index]);
                    return true;
                }
            }
        }
        foreach (self::$campaigns as $index => $campaign) {
            if ($campaign->getCampaignID() == $key) {
                unset(self::$campaigns[$index]);
                return true;
            }
        }
        return false;
    }

    public function addDonationForDonor(int $donorID, Donation $donation): bool {
        if (!isset($this->donationsByDonor[$donorID])) {
            $this->donationsByDonor[$donorID] = [];
        }
        $this->donationsByDonor[$donorID][] = $donation;
        $this->totalDonations += $donation->getAmount();
        return true;
    }

    public function getDonationsByDonor(int $donorID): array {
        return $this->donationsByDonor[$donorID] ?? [];
    }


    public function calculateTotalDonations(): float {
        return $this->totalDonations;
    }

    public function getDonationDetails(int $donationID): ?Donation {
        foreach ($this->donationsByDonor as $donations) {
            foreach ($donations as $donation) {
                if ($donation->getId() == $donationID) {
                    return $donation;
                }
            }
        }
        return null;
    }

    public function getCampaignDetails(int $campaignID): ?CampaignModel {
        foreach ($this->campaigns as $campaign) {
            if ($campaign->getCampaignID() == $campaignID) {
                return $campaign;
            }
        }
        return null;
    }

    public function generateDonationReport(): array {
        $allDonations = [];
        foreach ($this->donationsByDonor as $donations) {
            $allDonations = array_merge($allDonations, $donations);
        }
        return $allDonations;
    }
}
?>
