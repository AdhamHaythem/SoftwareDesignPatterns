<?php

abstract class Donation {
    private float $amount;
    private int $donationID;
    private static int $donationIDincremental = 0;
    private int $donorID;

    public function __construct(float $amount, int $donorID) {
        $this->amount = $amount;
        $this->donationID = self::$donationIDincremental; // Assign current counter value
        $this->donorID = $donorID;
        self::$donationIDincremental++; // Increment for the next instance
    }

    public abstract function amountPaid(float $amount): float;

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

?>
