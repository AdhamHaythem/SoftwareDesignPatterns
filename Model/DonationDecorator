<?php
require_once 'DonationModel.php';

abstract class DonationDecorator extends Donation{
    public function __construct(float $amount, int $donorID) {
        parent::__construct($amount,$donorID);
    }

    public abstract function amountPaid(float $amount): float;
}
?>