<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';



class DonationUndoCommand implements ICommand {
    private ?Donation $donation = null;
    private ?float $previousAmount = null;

    public function __construct() {}

    public function execute(): void {
        if ($this->donation === null || $this->previousAmount === null) {
          throw new Exception("Donation or previous amount not set. Cannot execute undo command.");
            return;
        }

        // Revert the donation amount to the previous state
        $this->donation->setAmount($this->previousAmount);
        Donation::update($this->donation);
    }

    public function setDonation(Donation $donation): void {
        $this->donation = $donation;
    }

    public function setPreviousAmount(float $amount): void {
        $this->previousAmount = $amount;
    }
}
//............................................................


?>
