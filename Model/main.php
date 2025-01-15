<?php

require_once 'DonationModel.php';
require_once 'DonorModel.php';
require_once 'DonationUndoCommand.php';
require_once 'DonationRedoCommand.php';

function main() {

    $donor = new Donor(
        1, // userID
        'AdhamHaythem', // username
        'Adham', // firstName
        'Haythem', // lastName
        'adhamhaythem285@gmail.com', // email
        '123456', // password
        ['Cairo', 'Dubai'], // location
        '01001449338' // phoneNumber
    );


    $donation = new Donation(200.0, 0, 1);


    echo "Initial Donation: \${$donation->getAmount()}\n";

  
    $undoCommand = new DonationUndoCommand($donation, 50.0); 
    $redoCommand = new DonationRedoCommand($donation, 50.0); 

  
    $donor->setCommand($undoCommand);
    echo "Performing Undo...\n";
    $donor->undo();  // Perform Undo


    $donor->setCommand($redoCommand);
    echo "Performing Redo...\n";
    $donor->redo(); 

    echo "Final Donation: \${$donation->getAmount()}\n";
}

main();

?>
