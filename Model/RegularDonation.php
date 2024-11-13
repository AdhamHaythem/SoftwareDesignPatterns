<?php
require_once 'DonationModel.php';

class RegularDonation extends Donation {

    public function __construct(float $amount, int $donorID) {
        parent::__construct($amount, $donorID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount());
        return $this->getAmount();
    }
}
?>
