<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';
//.................................................

class DonationRedoCommand implements ICommand {
    private ?Donation $donation = null;
    private float $nextAmount = 0.0;

    public function __construct() {}

    public function execute(): void {

        if ($this->donation === null) {
            throw new Exception("Donation not set. Cannot execute redo command.");
            return;
        }

        // Restore the donation amount to the next state
        $this->donation->setAmount($this->nextAmount);
        Donation::update($this->donation);
    }

    public function setDonation(Donation $donation): void {
        $this->donation = $donation;
    }

    public function setNextAmount(float $amount): void {
        $this->nextAmount = $amount;
    }
  }
?>
