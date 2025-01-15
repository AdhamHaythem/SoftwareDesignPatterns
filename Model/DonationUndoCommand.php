<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';

class DonationUndoCommand implements ICommand {
    private Donation $donation;
    private float $amount;

    public function __construct(Donation $donation, float $amount) {
        $this->donation = $donation;
        $this->amount = $amount;
    }

    public function execute(): void {
        echo "Executing DonationUndoCommand\n"; 
        $currentAmount = $this->donation->getAmount();
        $newAmount = $currentAmount - $this->amount;

        if ($newAmount >= 0) {
            $this->donation->setAmount($newAmount);
            Donation::update($this->donation);
            echo "Undo: Donation reduced by {$this->amount}, new total is {$newAmount}.\n";
        } else {
            echo "Undo failed: Insufficient amount in donation.\n";
        }
    }
}
?>
