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
?>
