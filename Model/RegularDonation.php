<?php
require_once 'DonationModel.php';

class RegularDonation extends Donation {

    public function __construct(float $amount, int $donorID,DateTime $date, int $donationID = 0) {
        parent::__construct($amount, $donorID, $date, $donationID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount());
        return $this->getAmount();
    }
}
?>
