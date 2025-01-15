<?php

require_once 'DonationModel.php';
require_once 'DonorModel.php';
require_once 'DonationUndoCommand.php';
require_once 'DonationRedoCommand.php';

// function main() {
//     $donor = new Donor(
//         1, // userID
//         'mariam', // username
//         'mariaam', // firstName
//         'badawy', // lastName
//         'mariambadawy@gmail.com', // email
//         '123456', // password
//         ['Cairo', 'Dubai'], // location
//         '01001449338' // phoneNumber
//     );

//     $donation = new Donation(200.0, 0, 1); 

//     echo "Initial Donation: \${$donation->getAmount()}\n";

//     $undoCommand = new DonationUndoCommand($donation,100);
//     $donor->setCommand($undoCommand);


//     echo "Performing Undo...\n";
//     $donor->undo();


//     echo "Performing Redo...\n";
//     $donor->redo();

//     echo "Final Donation: \${$donation->getAmount()}\n";
// }

// main();


//.................................................................................

function main() {
    $donor = new Donor(
        1, // userID
        'mariam', // username
        'mariaam', // firstName
        'badawy', // lastName
        'mariambadawy@gmail.com', // email
        '123456', // password
        ['Cairo', 'Dubai'], // location
        '01001449338' // phoneNumber
    );

    $donation = new Donation(500.0, 0, 1); // Initial donation
    $donor->setDonation($donation); // Set the donation in the donor

    echo "Initial Donation: \${$donation->getAmount()}\n";

    // undoooooooooo
    $undoCommand = new DonationUndoCommand();
    $donor->setCommand($undoCommand);

    echo "Performing Undo...\n";
    $donor->undo();
    
    //redoooooooooooo
    echo "Performing Redo...\n";
    $donor->redo();

    echo "Final Donation: \${$donation->getAmount()}\n";
}

main();
// function main() {
//     $donor = new Donor(
//         1, // userID
//         'mariam', // username
//         'mariaam', // firstName
//         'badawy', // lastName
//         'mariambadawy@gmail.com', // email
//         '123456', // password
//         ['Cairo', 'Dubai'], // location
//         '01001449338' // phoneNumber
//     );


// $donation = new Donation(200.0, 1, 1);
// echo "Initial Donation: \${$donation->getAmount()}\n";

// $donation->amountPaid(100.0); 
// echo "Donation after update: \${$donation->getAmount()}\n";  // Should print 250

// // Now, we create the undo command and execute it
// $undoCommand = new DonationUndoCommand();
// $undoCommand->setDonation($donation);  // Set the donation reference
// $undoCommand->execute();  // Should revert to 200
// echo "Donation after Undo: \${$donation->getAmount()}\n";  // Should print 200

// // Create the redo command and execute it
// $redoCommand = new DonationRedoCommand();
// $redoCommand->setDonation($donation);  // Set the donation reference
// $redoCommand->execute();  // Should go back to 250
// echo "Donation after Redo: \${$donation->getAmount()}\n";  // Should print 250
// }

//main();


