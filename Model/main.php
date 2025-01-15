<?php

require_once 'DonationModel.php';
require_once 'DonorModel.php';
require_once 'DonationUndoCommand.php';
require_once 'DonationRedoCommand.php';
require_once 'db_connection.php';
require_once 'ReportGenerator.php';
require_once 'InstructorModel.php';
require_once 'DatabaseConnection.php';

function main() {

    $config = require 'configurations.php';

    $db = new DatabaseConnection($config);



    $instructor = new InstructorModel(
        'john_doe',       // username
        'John',           // firstname
        'Doe',            // lastname
        1,                // userID (ensure this is unique)
        'john@example.com', // email
        'password123',    // password
        ['New York', 'USA'], // location (array)
        1234567890,       // phoneNumber
        'Instructor',     // title
        50000,            // salary
        40                // workingHours
    );
    
    // Add the instructor to the database
    if (InstructorModel::create($instructor)) {
        echo "Instructor created and added to the database successfully.\n";
    } else {
        echo "Failed to create instructor.\n";
    }


    $Generator = new ReportGenerator();
    $Generator->finalizeReport('Instructor');
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


