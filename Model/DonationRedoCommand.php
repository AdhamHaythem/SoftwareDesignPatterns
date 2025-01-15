<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';

class DonationRedoCommand implements ICommand {
    private Donation $donation;
    private float $amount;

    public function __construct(Donation $donation, float $amount) {
        $this->donation = $donation;
        $this->amount = $amount;
    }

    public function execute(): void {
        echo "Executing DonationRedoCommand\n"; 
        $newAmount = $this->donation->getAmount() + $this->amount;
        $this->donation->setAmount($newAmount);
        Donation::update($this->donation);
        echo "Redo: Donation increased by {$this->amount}, new total is {$newAmount}.\n";
    }
}
?>
