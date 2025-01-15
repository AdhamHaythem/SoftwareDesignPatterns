<?php
require_once 'DonationDecorator.php';
require_once 'DonationModel.php';

class Food {
    private $weight;
    public function __construct(float $weight) {
        $this->weight = $weight;
    }

    public function getWeight(): float {
        return $this->$this->weight;
    }
}
?>
