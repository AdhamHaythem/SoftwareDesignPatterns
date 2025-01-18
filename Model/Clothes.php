<?php

include_once 'DonationDecorator.php';
class Clothes extends DonationDecorator {
    public function __construct(float $amount, int $donorID, Donation $donation) {
        parent::__construct($amount, $donorID, $donation);
    }

    public function amountPaid(float $amount): float {
        // Add the amount to both this donation and the decorated donation
        $this->setAmount($this->getAmount() + $amount);
        return $this->getAmount() + $this->donation->getAmount();
    }
}
?>
