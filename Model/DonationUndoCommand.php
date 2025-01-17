<?php
require_once 'ICommand.php';
require_once 'DonationModel.php';

// class DonationUndoCommand implements ICommand {
//     private Donation $donation;
//     private float $amount;

//     public function __construct(Donation $donation, float $amount) {
//         $this->donation = $donation;
//         $this->amount = $amount;
//     }

//     public function execute(): void {
//         echo "Executing DonationUndoCommand\n";

//         $currentAmount = $this->donation->getAmount();
//         $newAmount = $currentAmount - $this->amount;

//         if ($newAmount >= 0) {
//             $this->donation->setAmount($newAmount);
//             Donation::update($this->donation);
//             echo "Undo successful: Donation reduced by {$this->amount}, new total: {$newAmount}.\n";
//         } else {
//             echo "Undo failed: Insufficient amount in donation.\n";
//         }
//     }

//     public function getDonation(): Donation {
//         return $this->donation;
//     }
//     public function getAmount(): float {
//         return $this->amount;
//     }
// }




class DonationUndoCommand implements ICommand {
    private ?Donation $donation = null;
    private ?float $previousAmount = null;

    public function __construct() {}

    public function execute(): void {
       // echo "Executing DonationUndoCommand\n";

        if ($this->donation === null || $this->previousAmount === null) {
          //  echo "Undo failed: No donation or previous amount set.\n";
            return;
        }

        // Revert the donation amount to the previous state
        $this->donation->setAmount($this->previousAmount);
        Donation::update($this->donation);
       // echo "Undo successful: Donation reverted to \${$this->previousAmount}.\n";
    }

    public function setDonation(Donation $donation): void {
        $this->donation = $donation;
    }

    public function setPreviousAmount(float $amount): void {
        $this->previousAmount = $amount;
    }
}
//............................................................

// class DonationUndoCommand implements ICommand {
//     private Donation $donation;  

//     public function __construct() {}

//     public function execute(): void {
//         echo "Executing DonationUndoCommand\n";


//         $currentAmount = $this->donation->getAmount();

//         $previousAmount = $this->donation->getPreviousAmount();  // Access the previous amount

//         if ($previousAmount !== null) {
//             $this->donation->setAmount($previousAmount);

//             echo "Undo successful: Donation reverted from \${$currentAmount} to \${$previousAmount}.\n";
//         } else {
//             echo "No previous donation amount found. Cannot undo.\n";
//         }
//     }

//     public function setDonation(Donation $donation): void {
//         $this->donation = $donation;
//     }
// }



// class DonationUndoCommand implements ICommand {
//     private Donation $donation;
//     private float $previousAmount; // Store the state before the change

//     public function __construct(Donation $donation) {
//         $this->donation = $donation;
//         $this->previousAmount = $donation->getAmount(); // Save current amount
//     }

//     public function execute(): void {
//         echo "Executing DonationUndoCommand\n";

//         // Restore the previous state
//         $this->donation->setAmount($this->previousAmount);
//         Donation::update($this->donation);

//         echo "Undo successful: Donation restored to {$this->previousAmount}.\n";
//     }

//     // Getter for the donation amount
//     public function getPreviousAmount(): float {
//         return $this->previousAmount;
//     }

//     // Getter for the Donation object
//     public function getDonation(): Donation {
//         return $this->donation;
//     }
// }


?>
