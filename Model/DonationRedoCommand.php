<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';

// class DonationRedoCommand implements ICommand {
//     private Donation $donation;
//     private float $amount;

//     public function __construct(Donation $donation, float $amount) {
//         $this->donation = $donation;
//         $this->amount = $amount;
//     }

//     public function execute(): void {
//         echo "Executing DonationRedoCommand\n";

//         $newAmount = $this->donation->getAmount() + $this->amount;
//         $this->donation->setAmount($newAmount);
//         Donation::update($this->donation);

//         echo "Redo successful: Donation increased by {$this->amount}, new total: {$newAmount}.\n";
//     }
//     public function getDonation(): Donation {
//         return $this->donation;
//     }

//     // Getter for amount
//     public function getAmount(): float {
//         return $this->amount;
//     }
// }




//.................................................

class DonationRedoCommand implements ICommand {
    private ?Donation $donation = null;
    private float $nextAmount = 0.0;

    public function __construct() {}

    public function execute(): void {
        echo "Executing DonationRedoCommand\n";

        if ($this->donation === null) {
            echo "Redo failed: No donation set.\n";
            return;
        }

        // Restore the donation amount to the next state
        $this->donation->setAmount($this->nextAmount);
        Donation::update($this->donation);
        echo "Redo successful: Donation updated to \${$this->nextAmount}.\n";
    }

    public function setDonation(Donation $donation): void {
        $this->donation = $donation;
    }

    public function setNextAmount(float $amount): void {
        $this->nextAmount = $amount;
    }
}
//.........................................................................
// class DonationRedoCommand implements ICommand {
//     private Donation $donation;

//     public function __construct() {
//         // No constructor parameters
//     }

//     public function execute(): void {
//         echo "Executing DonationRedoCommand\n";

//         // Get the current donation amount
//         $currentAmount = $this->donation->getAmount();

//         // Access the previous amount stored in Donor (from undo)
//         $previousAmount = $this->donation->getPreviousAmount();  // Access the previous amount after undo

//         if ($previousAmount !== null) {
//             // Set the donation amount back to the previous state after undo (reverting the undo)
//             $this->donation->setAmount($previousAmount);

//             echo "Redo successful: Donation reverted from \${$currentAmount} to \${$previousAmount}.\n";
//         } else {
//             echo "No previous donation amount found for redo. Cannot redo.\n";
//         }
//     }

//     public function setDonation(Donation $donation): void {
//         $this->donation = $donation;
//     }
// }




// class DonationRedoCommand implements ICommand {
//     private Donation $donation;
//     private float $nextAmount;

//     public function __construct(Donation $donation, float $nextAmount) {
//         $this->donation = $donation;
//         $this->nextAmount = $nextAmount;
//     }

//     public function execute(): void {
//         echo "Executing DonationRedoCommand\n";
//         $currentAmount = $this->donation->getAmount();

//         // Restore the next amount
//         $this->donation->setAmount($this->nextAmount);
//         Donation::update($this->donation);

//         echo "Redo successful: Donation reverted from {$currentAmount} to {$this->nextAmount}.\n";
//     }

//     // Getter for Donation
//     public function getDonation(): Donation {
//         return $this->donation;
//     }

//     // Getter for Next Amount
//     public function getNextAmount(): float {
//         return $this->nextAmount;
//     }
// }

?>
