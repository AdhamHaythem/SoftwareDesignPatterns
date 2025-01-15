<?php
require_once 'DonationDecorator.php';
require_once 'DonationModel.php';

class FoodAdapter extends DonationDecorator{
    private Donation $donation;
    private Food $food;

    public function __construct(int $donorID,Donation $donation,Food $food) {
        $this->donation = $donation;
        $this->food = $food;
        $amount = $this->food->getWeight() *10;
        parent::__construct($amount, $donorID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount() + $amount);
        return $this->getAmount()+ $this->donation->getAmount();
    }
}

?>