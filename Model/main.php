<?php

require_once 'DonationModel.php';
require_once 'DonorModel.php';
require_once 'DonationUndoCommand.php';
require_once 'DonationRedoCommand.php';
require_once 'db_connection.php';
require_once 'ReportGenerator.php';
require_once 'instructor.php';
require_once 'EventUndoCommand.php';
require_once 'EventRedoCommand.php';
require_once 'VolunteeringEventModel.php';
require_once 'hr.php';
require_once 'Technical.php';
require_once 'delivery.php';

// ............Main to test Report Generator for Instructor

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

    $hr = new HRModel(
        'nog_doe',       
        'Nog',              
        'Doe',               
        254678,                   
        'nog@example.com',  
        'nog111',     
        ['San Nogcisco', 'Nogland'], 
        9876543210,          
        'HR',              
        600000,              
        35                   
    );
    
    if (HRModel::create($hr)) {
        echo "HR created and added to the database successfully.\n";
    } else {
        echo "Failed to create HR.\n";
    }
    $technical = new TechnicalModel(
        'noga_lcd',          
        'Noga',             
        'LCD',          
        33213,                   
        'noga@lcd.com',   
        'pass@789',        
        ['Mansoura', 'UK'],  
        5551234567,         
        'Technical',         
        750000,               
        80,
        ["Tea with Milk"],
        []                   
    );
    
    if (TechnicalModel::create($technical)) {
        echo "Technical created and added to the database successfully.\n";
    } else {
        echo "Failed to create technical.\n";
    }
    
    $delivery = new DeliveryPersonnel(
        "DeliveryMan",
        "Delivery",
        "Man",
        "132801273",
        "deliv@gmail.com",
        "password",
        ['Dubai', 'UAE'],     
        1234568890,            
        "Delivery",
        123123123,
        33,
        "Tuk-tuk"
    );
    if (DeliveryPersonnel::create($delivery)) {
        echo "DeliveryPersonnel created and added to the database successfully.\n";
    } else {
        echo "Failed to create DeliveryPersonnel.\n";
    }
    
    $donor = new Donor(
        '12345688878',     
        'Motabare3',             
        'Motabare3',            
        "Tabaro3at",                     
        'motabare3@example.com',   
        '1234bnbb',    
        ['Dubai', 'UFC'],     
        1234567890,            
    );
    if (Donor::create($donor)) {
        echo "Donor created and added to the database successfully.\n";
    } else {
        echo "Failed to create Donor.\n";
    }

    $instructor1 = new InstructorModel(
        'MorganAhmedMorgan',       // username
        'Morgan',           // firstname
        'Morgan',            // lastname
        111112,                // userID (ensure this is unique)
        'morgan@example.com', // email
        'pass123',    // password
        ['Cairo', 'Egypt'], // location (array)
        12345123810,       // phoneNumber
        'Instructor',     // title
        5000000,            // salary
        40                // workingHours
    );
    if (InstructorModel::create($instructor1)) {
        echo "Instructor 1 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Instructor 1.\n";
    }
    
    $instructor2 = new InstructorModel(
        'vini_jr',       // username
        'Vinicius',             // firstname
        'JR',            // lastname
        2890,                  // userID
        'vini@example.com', // email
        'vini2025',      // password
        ['Rio de Janeiro', 'Brazil'], // location
        99876543210,         // phoneNumber
        'Instructor',       // title
        5500000,              // salary
        89                 // workingHours
    );
    if (InstructorModel::create($instructor2)) {
        echo "Instructor 2 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Instructor 2.\n";
    }
    $instructor3 = new InstructorModel(
        'doctorbaseer',       // username
        'Doctor',             // firstname
        'Baseer',            // lastname
        28690,                  // userID
        'baseer@example.com', // email
        'baseer',      // password
        ['Imbaba', 'Egypt'], // location
        49876543210,         // phoneNumber
        'Instructor',       // title
        550,              // salary
        9                 // workingHours
    );
    if (InstructorModel::create($instructor3)) {
        echo "Instructor 3 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Instructor 3.\n";
    }
    $hr1 = new HRModel(
        'elnognog',       // username
        'ElNog',           // firstname
        'Nog',           // lastname
        254678,          // userID
        'nognognog@example.com', // email
        'nog111',        // password
        ['Nogchester', 'UK'], // location
        9576543210,      // phoneNumber
        'HR',            // title
        600000,          // salary
        39               // workingHours
    );
    if (HRModel::create($hr1)) {
        echo "HR 1 created and added to the database successfully.\n";
    } else {
        echo "Failed to create HR 1.\n";
    }
    
    $hr2 = new HRModel(
        'hdaboor',      // username
        'Haytham',         // firstname
        'Dabour',         // lastname
        768432,          // userID
        'hdaboor@example.com', // email
        'hdaboooor',  // password
        ['Cool', 'Beautiful'], // location
        5554443333,      // phoneNumber
        'HR',            // title
        70000,           // salary
        40               // workingHours
    );
    if (HRModel::create($hr2)) {
        echo "HR 2 created and added to the database successfully.\n";
    } else {
        echo "Failed to create HR 2.\n";
    }

    $hr3 = new HRModel(
        'sayedelbadawy',      // username
        'Sayed',         // firstname
        'Elbadawy',         // lastname
        768432,          // userID
        'badawy@example.com', // email
        'badawyyy',  // password
        ['Tanta', 'Egypt'], // location
        5554441233,      // phoneNumber
        'HR',            // title
        710000,           // salary
        10               // workingHours
    );
    if (HRModel::create($hr3)) {
        echo "HR 3 created and added to the database successfully.\n";
    } else {
        echo "Failed to create HR 3.\n";
    }

    $technical1 = new TechnicalModel(
        'noga_diode',        // username
        'Noga',            // firstname
        'Diode',             // lastname
        33213,             // userID
        'noga@diode.com',    // email
        'pass@789',        // password
        ['Big Ben', 'UK'], // location
        5551234567,        // phoneNumber
        'Technical',       // title
        750000,            // salary
        80,                // workingHours
        ['Singing','C++','Embedded Systems'], // skills
        ['Certified']                 // certifications
    );
    if (TechnicalModel::create($technical1)) {
        echo "Technical 1 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Technical 1.\n";
    }
    
    $technical2 = new TechnicalModel(
        'tech_guru',         // username
        'Mark',              // firstname
        'Taylor',            // lastname
        7453212,               // userID
        'mark@example.com',  // email
        'fixIt2025',         // password
        ['London', 'UK'],    // location
        1234567890,          // phoneNumber
        'Technical',         // title
        80000,               // salary
        40,                  // workingHours
        ['Java', 'OOP'], // skills
        ['Cisco Certified Network Demolisher']  // certifications
    );
    if (TechnicalModel::create($technical2)) {
        echo "Technical 2 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Technical 2.\n";
    }

    $technical3 = new TechnicalModel(
        'hamdahelal',         // username
        'Hamada',              // firstname
        'Helal',            // lastname
        745365652,               // userID
        'hamada@example.com',  // email
        'hamada111',         // password
        ['Alexandria', 'USA'],    // location
        123456780090,          // phoneNumber
        'Technical',         // title
        800000,               // salary
        20,                  // workingHours
        ['Tea with milk', 'Tea without Milk','Tea without Mint'], // skills
        ['IBM Certified']  // certifications
    );
    if (TechnicalModel::create($technical3)) {
        echo "Technical 3 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Technical 3.\n";
    }
    $technical4 = new TechnicalModel(
        'ramadan7aree2a',         // username
        'Ramadan',              // firstname
        '7aree2a',            // lastname
        745365652,               // userID
        'ramadan7aree2a@example.com',  // email
        'ramadanfire',         // password
        ['Shoubra', 'Egypt'],    // location
        112945780090,          // phoneNumber
        'Technical',         // title
        100,               // salary
        0,                  // workingHours
        ['Banana with milk', 'Last order'], // skills
        ['IBM Certified Closed', 'IELTS']  // certifications
    );
    if (TechnicalModel::create($technical4)) {
        echo "Technical 4 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Technical 4.\n";
    }

    $delivery1 = new DeliveryPersonnel(
        'bond007',       // username
        'James',              // firstname
        'Bond',               // lastname
        1234596,               // userID
        'james@example.com',  // email
        'bond007',        // password
        ['Dubai', 'UAE'],     // location
        1234568890,           // phoneNumber
        'Delivery',           // title
        123123,               // salary
        33,                   // workingHours
        'Yacht'             // vehicleType
    );
    if (DeliveryPersonnel::create($delivery1)) {
        echo "DeliveryPersonnel 1 created and added to the database successfully.\n";
    } else {
        echo "Failed to create DeliveryPersonnel 1.\n";
    }
    
    $delivery2 = new DeliveryPersonnel(
        'shedeedelsor3a',         // username
        'Shedeed',               // firstname
        'Elsor3a',              // lastname
        2233644,               // userID
        'shedeed@example.com',   // email
        'shedeed2025',       // password
        ['Qatar', 'Qatar'], // location
        98765434411,           // phoneNumber
        'Delivery',           // title
        45000,                // salary
        30,                   // workingHours
        'Beach Buggy'           // vehicleType
    );
    if (DeliveryPersonnel::create($delivery2)) {
        echo "DeliveryPersonnel 2 created and added to the database successfully.\n";
    } else {
        echo "Failed to create DeliveryPersonnel 2.\n";
    }

    $delivery3 = new DeliveryPersonnel(
        'summerwatermelon',         // username
        'Summer',               // firstname
        'Watermelon',              // lastname
        2299944,               // userID
        'watermelon@example.com',   // email
        'watermelon2025',       // password
        ['Fayoum', 'Egypt'], // location
        98765434411,           // phoneNumber
        'Delivery',           // title
        4000,                // salary
        39,                   // workingHours
        'Scooter'           // vehicleType
    );
    if (DeliveryPersonnel::create($delivery3)) {
        echo "DeliveryPersonnel 3 created and added to the database successfully.\n";
    } else {
        echo "Failed to create DeliveryPersonnel 3.\n";
    }
    
    $Generator = new ReportGenerator();
    $Generator->getData('HR');
    $Generator->getData('Technical');
    $Generator->getData('Delivery');
    $Generator->getData('Donor');

    $result = $Generator->getData('Instructor');

    echo "Instructor Report:\n";
    $user = $Generator->filterData(1, $result);
    print_r($user);
}

main();

//...................Main to test EventUndo/RedoCommand (volunteeringggg orrrr Campaign)

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

//     // $event = new VolunteeringEventStrategy(
//     //     "Charity Run", // name
//     //     new DateTime('2023-12-01'), // time
//     //     "Central Park", // location
//     //     10, // volunteersNeeded
//     //     1 // eventID
//     // );
 
//     $event = new CampaignStrategy(
//         1,
//         new DateTime('2023-12-01'), // time
//         "Central Park", // location
//         10, // volunteersNeeded
//         1, // eventID
//         "Charity Run", // name
//         1.0,
//         "Campaignnn",
//         90000.0
//     );

//     $donor->setEvent($event);

//     $eventJoinCommand = new EventUndoCommand();
//     $donor->setCommand($eventJoinCommand);

//     echo "Performing Undo...\n";
//     $donor->undo();

//     echo "Performing Redo...\n";
//     $donor->redo();

//     echo "Final Events: " . implode(", ", array_map(fn($e) => $e->getName(), $donor->getEvents())) . "\n";
// }

// main();

//..................Main to test DonationUndo/RedoCommand
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

 //.......................Main To test stateeeeee
//  function main() {
  
//     $donation = new Donation(100.0, 0, 1);
//     echo "Donation created with ID: {$donation->getDonationID()}\n";
//     $donation->handleChange();  // Output: Donation is under review.
//     $donation->handleChange();  // Output: Donation is in progress.
//     $donation->handleChange();  // Output: Donation is paid.

//     $donation->amountPaid(50.0);
//     $donation->setState(new UnderReviewState());
//     $donation->handleChange();

    //databaseeeeeeeeeeeeee
    // Save the donation to the database
    // if (Donation::create($donation)) {
    //     echo "Donation saved to the database.\n";
    // } else {
    //     echo "Failed to save donation to the database.\n";
    // }

    // // Retrieve the donation from the database
    // $retrievedDonation = Donation::retrieve($donation->getDonationID());
    // if ($retrievedDonation) {
    //     echo "Retrieved donation amount: {$retrievedDonation->getAmount()}\n";
    // } else {
    //     echo "Failed to retrieve donation.\n";
    // }

    // // Delete the donation from the database
    // if (Donation::delete($donation->getDonationID())) {
    //     echo "Donation deleted from the database.\n";
    // } else {
    //     echo "Failed to delete donation.\n";
    // }
// }

// main();


