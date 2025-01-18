<?php
require_once 'DonationModel.php';

abstract class DonationDecorator extends Donation {
    protected Donation $donation;

    public function __construct(float $amount, int $donorID, Donation $donation) {
        parent::__construct($amount, $donorID);
        $this->donation = $donation;
    }
    abstract public function amountPaid(float $amount): float;
}

?>