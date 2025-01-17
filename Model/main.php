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
//require_once 'PaymentAdmin.php';
require_once 'cash.php';
require_once 'visa.php';
require_once 'statisticsGenerator.php';
require_once 'AdminModel.php';
require_once 'DonationManager.php';
require_once 'CampaignStrategy.php';

//.............Main to test strategiessssssssssssss


//     function main(){

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


// $creditCardPayment = new Cash();
// $paypalPayment = new Visa();

// // Create event strategies
// $fundraisingEvent = new CampaignStrategy(
//         new DateTime('2023-1-15 10:00:00'),
//         'New York',
//         10,
//         1,
//         'Charity Run',
//         1000.0,
//         'Annual Charity Run',
//         'descriptionnnnnn',
//         500.0);

// $volunteerEvent = new VolunteeringEventStrategy(
//        'Food Drive',
//         new DateTime('2023-1-11 10:00:00'),
//         'Los Angeles',
//         200,
//         3); 


// $donor->setPaymentMethod($creditCardPayment);
// $donor->setEventMethod($fundraisingEvent);


// echo "Testing Credit Card Payment with Fundraising Event:\n";
// $donor->setPaymentMethod($creditCardPayment);
// $donor->setEventMethod($fundraisingEvent);   
// $donor->joinEvent();

// // Test Paypal payment and volunteer event
// echo "\nTesting Paypal Payment with Volunteer Event:\n";
// $donor->setPaymentMethod($paypalPayment);  
// echo($donor->checkEventStatusStrategy());  
// $donor->setEventMethod($volunteerEvent);     
// $donor->joinEvent();
// echo "\nChecking event status and generating event report:\n";
// echo($donor->checkEventStatusStrategy());
// $donor->generateEventReportStrategy();

// }
//  main();



//.............Main to test campain&volunteeringEvents

// function main(){
    
//     $campaign1 = new CampaignStrategy(
//         new DateTime('2023-1-15 10:00:00'),
//         'New York',
//         10,
//         1,
//         'Charity Run',
//         1000.0,
//         'Annual Charity Run',
//         'descriptionnnnnn',
//         500.0
//     );

//    $campaign2 = new CampaignStrategy(
//         new DateTime('2023-1-11 10:00:00'),
//         'Los Angeles',
//         15,
//         2,
//         'Food Drive',
//         2000.0,
//         'Winter Food Drive',
//         'descriptionnnnnn',
//         1200.0
//     );
   
//     $volunteering = new VolunteeringEventStrategy(
//        'Food Drive',
//         new DateTime('2023-1-11 10:00:00'),
//         'Los Angeles',
//         200,
//         3
       
//     );

//     if (CampaignStrategy::create($campaign1)) {
//             echo "campaign1 created and added to the database successfully.\n";
//                 } else {
//                     echo "Failed to create campaign1.\n";
//         }

//     if (CampaignStrategy::create($campaign2)) {
//             echo "campaign2 created and added to the database successfully.\n";
//             } else {
//             echo "Failed to create campaign2.\n";
//     }

//     if (VolunteeringEventStrategy::create($volunteering)) {
//         echo "volunteeringcreated and added to the database successfully.\n";
//         } else {
//         echo "Failed to create volunteering.\n";
// }

// }
// main();




//.............Main to test DManager with db


function main() {
    $config = require 'configurations.php';

    $db = new DatabaseConnection($config);


    $donor1 = new Donor(
        1, // userID
        'mariam', // username
        'mariaam', // firstName
        'badawy', // lastName
        'mariambadawy@gmail.com', // email
        '123456', // password
        ['Cairo', 'Dubai'], // location
        '01001449338' // phoneNumber
    );


    // Add the donor to the database
    if (Donor::create($donor1)) {
        echo "Donor 1 created and added to the database successfully.\n";
    } else {
        echo "Failed to create Donor 1.\n";
        return; // Exit if creation fails
    }

    // Retrieve the donor from the database
    $retrievedDonor = Donor::retrieve(1);
    if ($retrievedDonor) {
        echo "\nRetrieved Donor 1:\n";
        echo "Donor ID: " . $retrievedDonor->getDonorID() . "\n";
        echo "Username: " . $retrievedDonor->getUsername() . "\n";
        echo "First Name: " . $retrievedDonor->getFirstname() . "\n";
        echo "Last Name: " . $retrievedDonor->getLastname() . "\n";
        echo "Email: " . $retrievedDonor->getEmail() . "\n";
        echo "Location: " . implode(", ", $retrievedDonor->getLocation()) . "\n";
        echo "Phone Number: " . $retrievedDonor->getPhoneNumber() . "\n";
    } else {
        echo "Failed to retrieve Donor 1.\n";
        return; // Exit if retrieval fails
    }

    // Update the donor's details
    $retrievedDonor->setUsername("updatedUsername");
    $retrievedDonor->setFirstname("updatedFirstName");
    $retrievedDonor->setLastname("updatedLastName");
    $retrievedDonor->setEmail("updatedEmail@gmail.com");
    $retrievedDonor->setLocation(["New York", "London"]);
    $retrievedDonor->setPhoneNumber("01111111111");




    if (Donor::update($retrievedDonor)) {
        echo "\nDonor 1 updated successfully.\n";
    } else {
        echo "\nFailed to update Donor 1.\n";
        return; // Exit if update fails
    }

    // Retrieve the updated donor from the database
    $updatedDonor = Donor::retrieve($retrievedDonor->getDonorID());
    if ($updatedDonor) {
        echo "\nUpdated Donor 1:\n";
        echo "Donor ID: " . $updatedDonor->getDonorID() . "\n";
        echo "Username: " . $updatedDonor->getUsername() . "\n";
        echo "First Name: " . $updatedDonor->getFirstname() . "\n";
        echo "Last Name: " . $updatedDonor->getLastname() . "\n";
        echo "Email: " . $updatedDonor->getEmail() . "\n";
        echo "Location: " . implode(", ", $updatedDonor->getLocation()) . "\n";
        echo "Phone Number: " . $updatedDonor->getPhoneNumber() . "\n";
    } else {
        echo "Failed to retrieve updated Donor 1.\n";
    }

    // $donor2 = new Donor(
    //     2, // userID
    //     'mariam2', // username
    //     'mariaam2', // firstName
    //     'badawy2', // lastName
    //     'mariambadawy2@gmail.com', // email
    //     '123456', // password
    //     ['Cairo', 'Dubai'], // location
    //     '01001449338' // phoneNumber
    // );

    // if (Donor::create($donor2)) {
    //         echo "Donor 2 created and added to the database successfully.\n";
    //     } else {
    //         echo "Failed to create Donor 2.\n";
    //     }

    // $campaign1 = new CampaignStrategy(
    //     new DateTime('2023-1-15 10:00:00'),
    //     'New York',
    //     10,
    //     1,
    //     'Charity Run',
    //     1000.0,
    //     'Annual Charity Run',
    //     'descriptionnnnnn',
    //     500.0
    // );

    // $campaign2 = new CampaignStrategy(
    //     new DateTime('2023-1-11 10:00:00'),
    //     'Los Angeles',
    //     15,
    //     2,
    //     'Food Drive',
    //     2000.0,
    //     'Winter Food Drive',
    //     'descriptionnnnnn',
    //     1200.0
    // );
    // $campaigns = [$campaign1, $campaign2];

    // $donation1 = new Donation(1000.0, 1,new DateTime(),1);
    // $donation2 = new Donation(2000.0, 1,new DateTime(), 2);
    // $donation3 =new Donation(3000.0, 2,new DateTime(), 3);

    // $donations = [
    //     1 => [$donation1, $donation2], 
    //     2 => [$donation3],             
    // ];

    // $donationManager = new DonationManager(1000.0, $donations, $campaigns);

    // // Insert the donation manager into the database
    // if (DonationManager::create($donationManager)) {
    //     echo "DonationManager created successfully!\n";
    // } else {
    //     echo "Failed to create DonationManager.\n";
    // }

    // $donation = new Donation(100.0, 1, new DateTime('2023-10-15 10:00:00'),1);

    // // Insert the donation into the database
    // if (Donation::create($donation)) {
    //     echo "Donation created successfully!\n";
    // } else {
    //     echo "Failed to create donation.\n";
    //     return;
    
    // }
    // $donation2 = new Donation(300.0, 2, new DateTime('2023-10-15 10:00:00'),2);

    // // Insert the donation into the database
    // if (Donation::create($donation2)) {
    //     echo "Donation created successfully!\n";
    // } else {
    //     echo "Failed to create donation.\n";
    //     return;
    
    // }
}
main();


//.............Main to test DManager without db

// function main() {

       
//     $campaign1 = new CampaignStrategy(
//         101,
//         new DateTime('2023-10-15 10:00:00'),
//         'New York',
//         10,
//         1,
//         'Charity Run',
//         1000.0,
//         'Annual Charity Run',
//         500.0
//     );

//     $campaign2 = new CampaignStrategy(
//         102,
//         new DateTime('2023-11-20 14:00:00'),
//         'Los Angeles',
//         15,
//         2,
//         'Food Drive',
//         2000.0,
//         'Winter Food Drive',
//         1200.0
//     );
//     $campaigns = [$campaign1, $campaign2];

//     $donation1 = new Donation(1000.0, 1,new DateTime(),1);
//     $donation2 = new Donation(2000.0, 1,new DateTime(), 2);
//     $donation3 =new Donation(3000.0, 2,new DateTime(), 3);

//     $donations = [
//         1 => [$donation1, $donation2], 
//         2 => [$donation3],             
//     ];

//     $donationManager = new DonationManager(1000.0, $donations, $campaigns);

//     // $donationManager->addDonationForDonor(1, new Donation(1000.0, 1,new DateTime(),1));
//     // $donationManager->addDonationForDonor(1, new Donation(2000.0, 1,new DateTime(), 2));
//     // $donationManager->addDonationForDonor(2, new Donation(3000.0, 2,new DateTime(), 3));

//     echo "Total Donations: " . $donationManager->calculateTotalDonations() . "\n";

//     echo "Donations for Donor 1:\n";
//     foreach ($donationManager->getDonationsByDonor(1) as $donation) {
//         echo "Donation ID: " . $donation->getDonationID() . ", Amount: " . $donation->getAmount() . "\n";
//     }


//     echo "Campaign Details for ID 101:\n";
//     $campaignDetails = $donationManager->getCampaignDetails(101);
//     if ($campaignDetails) {
//         echo "Campaign ID: " . $campaignDetails->getCampaignID() . "\n";
//         echo "Title: " . $campaignDetails->getTitle() . "\n";
//         echo "Target: " . $campaignDetails->getTarget() . "\n";
//         echo "Money Earned: " . $campaignDetails->getMoneyEarned() . "\n";
//     } else {
//         echo "Campaign not found.\n";
//     }

//     echo "\nDonation Details for ID 2:\n";
//     $donationDetails = $donationManager->getDonationDetails(2);
//     if ($donationDetails) {
//         echo " Amount: " . $donationDetails->getAmount() . "\n";
//     } else {
//         echo "Donation not found.\n";
//     }
// }

// main();


//..............Main to test statisticsGenerator

//function main() {
//     $config = require 'configurations.php';

//     $db = new DatabaseConnection($config);


// // Donor 1
// $donor1 = new Donor(
//     10001,                    // userID
//     'Motabare3',              // username
//     'Motabare3',              // firstname
//     'Tabaro3at',              // lastname
//     'motabare3@example.com',  // email
//     '1234bnbb',               // password
//     ['Dubai', 'UFC'],         // location
//     1234567890                // phoneNumber
// );
// if (Donor::create($donor1)) {
//     echo "Donor 1 created and added to the database successfully.\n";
// } else {
//     echo "Failed to create Donor 1.\n";
// }

// // Donor 2
// $donor2 = new Donor(
//     10002,                    // userID
//     'KindHeart',              // username
//     'Kind',                   // firstname
//     'Heart',                  // lastname
//     'kindheart@example.com',  // email
//     'mypassword',             // password
//     ['Abu Dhabi', 'UAE'],     // location
//     9876543210                // phoneNumber
// );
// if (Donor::create($donor2)) {
//     echo "Donor 2 created and added to the database successfully.\n";
// } else {
//     echo "Failed to create Donor 2.\n";
// }

// // Donor 3
// $donor3 = new Donor(
//     10003,                    // userID
//     'HelpfulHero',            // username
//     'Helpful',                // firstname
//     'Hero',                   // lastname
//     'helpfulhero@example.com',// email
//     'secure123',              // password
//     ['Sharjah', 'UAE'],       // location
//     8765432109                // phoneNumber
// );
// if (Donor::create($donor3)) {
//     echo "Donor 3 created and added to the database successfully.\n";
// } else {
//     echo "Failed to create Donor 3.\n";
// }

// // Donor 4
// $donor4 = new Donor(
//     10004,                    // userID
//     'CharityChamp',           // username
//     'Charity',                // firstname
//     'Champ',                  // lastname
//     'charitychamp@example.com', // email
//     'charitypass',            // password
//     ['Cairo', 'Egypt'],       // location
//     7654321098                // phoneNumber
// );
// if (Donor::create($donor4)) {
//     echo "Donor 4 created and added to the database successfully.\n";
// } else {
//     echo "Failed to create Donor 4.\n";
// }

// // Donations for Donor 1 (donorID = $donor1->getUserID())
// $donations1 = [
//     new Donation(100.00, $donor1->getUserID(), new DateTime('2025-01-16 10:00:00')),
//     new Donation(150.00, $donor1->getUserID(), new DateTime('2025-01-17 11:00:00')),
//     new Donation(200.00, $donor1->getUserID(), new DateTime('2025-01-18 12:00:00')),
//     new Donation(250.00, $donor1->getUserID(), new DateTime('2025-01-19 13:00:00'))
// ];

// // Donations for Donor 2 (donorID = $donor2->getUserID())
// $donations2 = [
//     new Donation(120.00, $donor2->getUserID(), new DateTime('2025-01-16 14:00:00')),
//     new Donation(130.00, $donor2->getUserID(), new DateTime('2025-01-17 15:00:00')),
//     new Donation(140.00, $donor2->getUserID(), new DateTime('2025-01-18 16:00:00')),
//     new Donation(150.00, $donor2->getUserID(), new DateTime('2025-01-19 17:00:00'))
// ];

// // Donations for Donor 3 (donorID = $donor3->getUserID())
// $donations3 = [
//     new Donation(180.00, $donor3->getUserID(), new DateTime('2025-01-16 18:00:00')),
//     new Donation(190.00, $donor3->getUserID(), new DateTime('2025-01-17 19:00:00')),
//     new Donation(200.00, $donor3->getUserID(), new DateTime('2025-01-18 20:00:00')),
//     new Donation(210.00, $donor3->getUserID(), new DateTime('2025-01-19 21:00:00'))
// ];

// // Donations for Donor 4 (donorID = $donor4->getUserID())
// $donations4 = [
//     new Donation(50.00, $donor4->getUserID(), new DateTime('2025-01-16 22:00:00')),
//     new Donation(60.00, $donor4->getUserID(), new DateTime('2025-01-17 23:00:00')),
//     new Donation(70.00, $donor4->getUserID(), new DateTime('2025-01-18 08:00:00')),
//     new Donation(80.00, $donor4->getUserID(), new DateTime('2025-01-19 09:00:00'))
// ];

// // Create Donations in Database
// foreach ($donations1 as $donation) {
//     if (Donation::create($donation)) echo "Donation for Donor 1 created successfully.\n";
// }
// foreach ($donations2 as $donation) {
//     if (Donation::create($donation)) echo "Donation for Donor 2 created successfully.\n";
// }
// foreach ($donations3 as $donation) {
//     if (Donation::create($donation)) echo "Donation for Donor 3 created successfully.\n";
// }
// foreach ($donations4 as $donation) {
//     if (Donation::create($donation)) echo "Donation for Donor 4 created successfully.\n";
// }



// $Generator = new statisticsGenerator();
// $result = $Generator->getData('Donations'); // Only pass the required argument
// $finalReport = $Generator->finalizeReport(results: $result); // Skip userID, let it use its default value (0)

// echo "Final Report:\n";
// echo "Mean: " . $finalReport['mean'] . "\n";


    

// }

//main();

//..............Main to test PaymentAdmin

// function main() {
  
//     $donor1 = new Donor(
//         1, // userID
//         'mariam', // username
//         'mariaam', // firstName
//         'badawy', // lastName
//         'mariambadawy@gmail.com', // email
//         '123456', // password
//         ['Cairo', 'Dubai'], // location
//         '01001449338' // phoneNumber
//     );

//     $donor2 = new Donor(
//         2, // userID
//         'mariam2', // username
//         'mariaam2', // firstName
//         'badawy2', // lastName
//         'mariambadawy2@gmail.com', // email
//         '123456', // password
//         ['Cairo', 'Dubai'], // location
//         '01001449338' // phoneNumber
//     );


//     $admin = new PaymentAdmin();
//     $donor1->setPaymentMethod(new Cash());

//     $admin->processPayment($donor1, 100); 

//     $donor2->setPaymentMethod(new Visa());
//     $admin->processPayment($donor2, 200); 

//     $transactions = $admin->getTransactions();
//     echo "Transactions:\n";
//     print_r($transactions);

//     $totalFees = $admin->calculateTotalFees();
//     echo "Total Fees: $" . $totalFees . "\n";
// }

// main();


// ............Main to test Report Generator for Instructor

// function main() {

//     $config = require 'configurations.php';

//     $db = new DatabaseConnection($config);



//     $instructor = new InstructorModel(
//         'john_doe',       // username
//         'John',           // firstname
//         'Doe',            // lastname
//         1,                // userID (ensure this is unique)
//         'john@example.com', // email
//         'password123',    // password
//         ['New York', 'USA'], // location (array)
//         1234567890,       // phoneNumber
//         'Instructor',     // title
//         50000,            // salary
//         40                // workingHours
//     );
    
//     // Add the instructor to the database
//     if (InstructorModel::create($instructor)) {
//         echo "Instructor created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create instructor.\n";
//     }

//     $hr = new HRModel(
//         'nog_doe',       
//         'Nog',              
//         'Doe',               
//         254678,                   
//         'nog@example.com',  
//         'nog111',     
//         ['San Nogcisco', 'Nogland'], 
//         9876543210,          
//         'HR',              
//         600000,              
//         35                   
//     );
    
//     if (HRModel::create($hr)) {
//         echo "HR created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create HR.\n";
//     }
//     $technical = new TechnicalModel(
//         'noga_lcd',          
//         'Noga',             
//         'LCD',          
//         33213,                   
//         'noga@lcd.com',   
//         'pass@789',        
//         ['Mansoura', 'UK'],  
//         5551234567,         
//         'Technical',         
//         750000,               
//         80,
//         ["Tea with Milk"],
//         []                   
//     );
    
//     if (TechnicalModel::create($technical)) {
//         echo "Technical created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create technical.\n";
//     }
    
//     $delivery = new DeliveryPersonnel(
//         "DeliveryMan",
//         "Delivery",
//         "Man",
//         "132801273",
//         "deliv@gmail.com",
//         "password",
//         ['Dubai', 'UAE'],     
//         1234568890,            
//         "Delivery",
//         123123123,
//         33,
//         "Tuk-tuk"
//     );
//     if (DeliveryPersonnel::create($delivery)) {
//         echo "DeliveryPersonnel created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create DeliveryPersonnel.\n";
//     }
    
//     $donor = new Donor(
//         '12345688878',     
//         'Motabare3',             
//         'Motabare3',            
//         "Tabaro3at",                     
//         'motabare3@example.com',   
//         '1234bnbb',    
//         ['Dubai', 'UFC'],     
//         1234567890,            
//     );
//     if (Donor::create($donor)) {
//         echo "Donor created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Donor.\n";
//     }

//     $instructor1 = new InstructorModel(
//         'MorganAhmedMorgan',       // username
//         'Morgan',           // firstname
//         'Morgan',            // lastname
//         111112,                // userID (ensure this is unique)
//         'morgan@example.com', // email
//         'pass123',    // password
//         ['Cairo', 'Egypt'], // location (array)
//         12345123810,       // phoneNumber
//         'Instructor',     // title
//         5000000,            // salary
//         40                // workingHours
//     );
//     if (InstructorModel::create($instructor1)) {
//         echo "Instructor 1 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Instructor 1.\n";
//     }
    
//     $instructor2 = new InstructorModel(
//         'vini_jr',       // username
//         'Vinicius',             // firstname
//         'JR',            // lastname
//         2890,                  // userID
//         'vini@example.com', // email
//         'vini2025',      // password
//         ['Rio de Janeiro', 'Brazil'], // location
//         99876543210,         // phoneNumber
//         'Instructor',       // title
//         5500000,              // salary
//         89                 // workingHours
//     );
//     if (InstructorModel::create($instructor2)) {
//         echo "Instructor 2 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Instructor 2.\n";
//     }
//     $instructor3 = new InstructorModel(
//         'doctorbaseer',       // username
//         'Doctor',             // firstname
//         'Baseer',            // lastname
//         28690,                  // userID
//         'baseer@example.com', // email
//         'baseer',      // password
//         ['Imbaba', 'Egypt'], // location
//         49876543210,         // phoneNumber
//         'Instructor',       // title
//         550,              // salary
//         9                 // workingHours
//     );
//     if (InstructorModel::create($instructor3)) {
//         echo "Instructor 3 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Instructor 3.\n";
//     }
//     $hr1 = new HRModel(
//         'elnognog',       // username
//         'ElNog',           // firstname
//         'Nog',           // lastname
//         254678,          // userID
//         'nognognog@example.com', // email
//         'nog111',        // password
//         ['Nogchester', 'UK'], // location
//         9576543210,      // phoneNumber
//         'HR',            // title
//         600000,          // salary
//         39               // workingHours
//     );
//     if (HRModel::create($hr1)) {
//         echo "HR 1 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create HR 1.\n";
//     }
    
//     $hr2 = new HRModel(
//         'hdaboor',      // username
//         'Haytham',         // firstname
//         'Dabour',         // lastname
//         768432,          // userID
//         'hdaboor@example.com', // email
//         'hdaboooor',  // password
//         ['Cool', 'Beautiful'], // location
//         5554443333,      // phoneNumber
//         'HR',            // title
//         70000,           // salary
//         40               // workingHours
//     );
//     if (HRModel::create($hr2)) {
//         echo "HR 2 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create HR 2.\n";
//     }

//     $hr3 = new HRModel(
//         'sayedelbadawy',      // username
//         'Sayed',         // firstname
//         'Elbadawy',         // lastname
//         768432,          // userID
//         'badawy@example.com', // email
//         'badawyyy',  // password
//         ['Tanta', 'Egypt'], // location
//         5554441233,      // phoneNumber
//         'HR',            // title
//         710000,           // salary
//         10               // workingHours
//     );
//     if (HRModel::create($hr3)) {
//         echo "HR 3 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create HR 3.\n";
//     }

//     $technical1 = new TechnicalModel(
//         'noga_diode',        // username
//         'Noga',            // firstname
//         'Diode',             // lastname
//         33213,             // userID
//         'noga@diode.com',    // email
//         'pass@789',        // password
//         ['Big Ben', 'UK'], // location
//         5551234567,        // phoneNumber
//         'Technical',       // title
//         750000,            // salary
//         80,                // workingHours
//         ['Singing','C++','Embedded Systems'], // skills
//         ['Certified']                 // certifications
//     );
//     if (TechnicalModel::create($technical1)) {
//         echo "Technical 1 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Technical 1.\n";
//     }
    
//     $technical2 = new TechnicalModel(
//         'tech_guru',         // username
//         'Mark',              // firstname
//         'Taylor',            // lastname
//         7453212,               // userID
//         'mark@example.com',  // email
//         'fixIt2025',         // password
//         ['London', 'UK'],    // location
//         1234567890,          // phoneNumber
//         'Technical',         // title
//         80000,               // salary
//         40,                  // workingHours
//         ['Java', 'OOP'], // skills
//         ['Cisco Certified Network Demolisher']  // certifications
//     );
//     if (TechnicalModel::create($technical2)) {
//         echo "Technical 2 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Technical 2.\n";
//     }

//     $technical3 = new TechnicalModel(
//         'hamdahelal',         // username
//         'Hamada',              // firstname
//         'Helal',            // lastname
//         745365652,               // userID
//         'hamada@example.com',  // email
//         'hamada111',         // password
//         ['Alexandria', 'USA'],    // location
//         123456780090,          // phoneNumber
//         'Technical',         // title
//         800000,               // salary
//         20,                  // workingHours
//         ['Tea with milk', 'Tea without Milk','Tea without Mint'], // skills
//         ['IBM Certified']  // certifications
//     );
//     if (TechnicalModel::create($technical3)) {
//         echo "Technical 3 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Technical 3.\n";
//     }
//     $technical4 = new TechnicalModel(
//         'ramadan7aree2a',         // username
//         'Ramadan',              // firstname
//         '7aree2a',            // lastname
//         745365652,               // userID
//         'ramadan7aree2a@example.com',  // email
//         'ramadanfire',         // password
//         ['Shoubra', 'Egypt'],    // location
//         112945780090,          // phoneNumber
//         'Technical',         // title
//         100,               // salary
//         0,                  // workingHours
//         ['Banana with milk', 'Last order'], // skills
//         ['IBM Certified Closed', 'IELTS']  // certifications
//     );
//     if (TechnicalModel::create($technical4)) {
//         echo "Technical 4 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Technical 4.\n";
//     }

//     $delivery1 = new DeliveryPersonnel(
//         'bond007',       // username
//         'James',              // firstname
//         'Bond',               // lastname
//         1234596,               // userID
//         'james@example.com',  // email
//         'bond007',        // password
//         ['Dubai', 'UAE'],     // location
//         1234568890,           // phoneNumber
//         'Delivery',           // title
//         123123,               // salary
//         33,                   // workingHours
//         'Yacht'             // vehicleType
//     );
//     if (DeliveryPersonnel::create($delivery1)) {
//         echo "DeliveryPersonnel 1 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create DeliveryPersonnel 1.\n";
//     }
    
//     $delivery2 = new DeliveryPersonnel(
//         'shedeedelsor3a',         // username
//         'Shedeed',               // firstname
//         'Elsor3a',              // lastname
//         2233644,               // userID
//         'shedeed@example.com',   // email
//         'shedeed2025',       // password
//         ['Qatar', 'Qatar'], // location
//         98765434411,           // phoneNumber
//         'Delivery',           // title
//         45000,                // salary
//         30,                   // workingHours
//         'Beach Buggy'           // vehicleType
//     );
//     if (DeliveryPersonnel::create($delivery2)) {
//         echo "DeliveryPersonnel 2 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create DeliveryPersonnel 2.\n";
//     }

//     $delivery3 = new DeliveryPersonnel(
//         'summerwatermelon',         // username
//         'Summer',               // firstname
//         'Watermelon',              // lastname
//         2299944,               // userID
//         'watermelon@example.com',   // email
//         'watermelon2025',       // password
//         ['Fayoum', 'Egypt'], // location
//         98765434411,           // phoneNumber
//         'Delivery',           // title
//         4000,                // salary
//         39,                   // workingHours
//         'Scooter'           // vehicleType
//     );
//     if (DeliveryPersonnel::create($delivery3)) {
//         echo "DeliveryPersonnel 3 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create DeliveryPersonnel 3.\n";
//     }
    
//     $Generator = new ReportGenerator();
//     $Generator->getData('HR');
//     $Generator->getData('Technical');
//     $Generator->getData('Delivery');
//     $Generator->getData('Donor');

//     $result = $Generator->getData('Instructor');

//     echo "Instructor Report:\n";
//     $user = $Generator->filterData(1, $result);
//     print(gettype($user));
// }

// main();

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


