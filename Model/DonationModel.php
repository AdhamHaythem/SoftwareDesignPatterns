<?php

class Donation {
    private float $amount;
    private int $donationID;
    private int $donorID;

    public function __construct(float $amount, int $donationID, int $donorID) {
        $this->amount = $amount;
        $this->donationID = $donationID;
        $this->donorID = $donorID;
    }

    public function amountPaid(float $amount): float {
        $this->amount += $amount;
        return $this->amount;
    }

    public function getDonorID(): int { 
        return $this->donorID;
    }

    public function getDonationID(): int {
        return $this->donationID;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }
}

// Example usage
$donation = new Donation(100.0, 1, 123); 

echo "Initial Donation Amount: " . $donation->getAmount() . "\n"; // Output: 100.0
echo "Donor ID: " . $donation->getDonorID() . "\n"; // Output: 123
$newAmount = $donation->amountPaid(50.0); // Update the amount paid
echo "Updated Donation Amount: " . $newAmount . "\n"; // Output: 150.0
?>
