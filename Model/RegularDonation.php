<?php
require_once 'DonationModel.php';

class Clothes extends Donation {

    public function __construct(float $amount, int $donorID) {
        parent::__construct($amount, $donorID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount() + $amount);
        return $this->getAmount();
    }
}
?>
