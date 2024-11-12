<?php
require_once 'DonationDecorator.php';
require_once 'DonationModel.php';

class Food extends DonationDecorator {
    private Donation $donation;

    public function __construct(float $amount, int $donorID,Donation $donation) {
        $this->donation = $donation;
        parent::__construct($amount, $donorID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount() + $amount);
        return $this->getAmount()+ $this->donation->getAmount();
    }
}
?>
