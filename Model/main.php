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
require_once 'student.php';

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


// function main() {
//     $config = require 'configurations.php';

//     $db = new DatabaseConnection($config);


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


//     // Add the donor to the database
//     if (Donor::create($donor1)) {
//         echo "Donor 1 created and added to the database successfully.\n";
//     } else {
//         echo "Failed to create Donor 1.\n";
//         return; // Exit if creation fails
//     }

//     // Retrieve the donor from the database
//     $retrievedDonor = Donor::retrieve(1);
//     if ($retrievedDonor) {
//         echo "\nRetrieved Donor 1:\n";
//         echo "Donor ID: " . $retrievedDonor->getDonorID() . "\n";
//         echo "Username: " . $retrievedDonor->getUsername() . "\n";
//         echo "First Name: " . $retrievedDonor->getFirstname() . "\n";
//         echo "Last Name: " . $retrievedDonor->getLastname() . "\n";
//         echo "Email: " . $retrievedDonor->getEmail() . "\n";
//         echo "Location: " . implode(", ", $retrievedDonor->getLocation()) . "\n";
//         echo "Phone Number: " . $retrievedDonor->getPhoneNumber() . "\n";
//     } else {
//         echo "Failed to retrieve Donor 1.\n";
//         return; // Exit if retrieval fails
//     }

//     // Update the donor's details
//     $retrievedDonor->setUsername("updatedUsername");
//     $retrievedDonor->setFirstname("updatedFirstName");
//     $retrievedDonor->setLastname("updatedLastName");
//     $retrievedDonor->setEmail("updatedEmail@gmail.com");
//     $retrievedDonor->setLocation(["New York", "London"]);
//     $retrievedDonor->setPhoneNumber("01111111111");




//     if (Donor::update($retrievedDonor)) {
//         echo "\nDonor 1 updated successfully.\n";
//     } else {
//         echo "\nFailed to update Donor 1.\n";
//         return; // Exit if update fails
//     }

//     // Retrieve the updated donor from the database
//     $updatedDonor = Donor::retrieve($retrievedDonor->getDonorID());
//     if ($updatedDonor) {
//         echo "\nUpdated Donor 1:\n";
//         echo "Donor ID: " . $updatedDonor->getDonorID() . "\n";
//         echo "Username: " . $updatedDonor->getUsername() . "\n";
//         echo "First Name: " . $updatedDonor->getFirstname() . "\n";
//         echo "Last Name: " . $updatedDonor->getLastname() . "\n";
//         echo "Email: " . $updatedDonor->getEmail() . "\n";
//         echo "Location: " . implode(", ", $updatedDonor->getLocation()) . "\n";
//         echo "Phone Number: " . $updatedDonor->getPhoneNumber() . "\n";
//     } else {
// //         echo "Failed to retrieve updated Donor 1.\n";
//     }

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
// }
// main();


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
//Donation::delete(1);
// function main(){
// // $donation = Donation::retrieve(2);

// // if ($donation) {
// //     echo "Before Update:\n";
// //     echo "Amount: " . $donation->getAmount() . "\n";
// //     echo "Donor ID: " . $donation->getDonorID() . "\n";
// //     echo "Date: " . $donation->getDate()->format('Y-m-d') . "\n";
// //     echo "Donation ID: " . $donation->getDonationID() . "\n";

// //     // Make changes to the donation object
// //     $donation->setAmount(250.00); // Update amount    // Change donor ID
// //     $donation->setDate(new DateTime('2025-02-01')); // Change date

// //     // Update the donation in the database
// //     if (Donation::update($donation)) {
// //         echo "Donation updated successfully.\n";

// //         // Fetch the updated donation to confirm changes
// //         $updatedDonation = Donation::retrieve(2);
// //         echo "After Update:\n";
// //         echo "Amount: " . $updatedDonation->getAmount() . "\n";
// //         echo "Donor ID: " . $updatedDonation->getDonorID() . "\n";
// //         echo "Date: " . $updatedDonation->getDate()->format('Y-m-d') . "\n";
// //         echo "Donation ID: " . $updatedDonation->getDonationID() . "\n";
// //     } else {
// //         echo "Failed to update the donation.\n";
// //     }
// // } else {
// //     echo "No donation found with the given ID.\n";
// // }

// $donation = new Donation(
//     300,                            // Amount of the donation
//     1,                              // Donor ID (reference to donorID = 1)
//     new DateTime('2025-01-20'),      // Date of the donation
//     3
// );

// // Insert the new donation into the database
// if (Donation::create($donation)) {
//     echo "Donation with donorID 1 and donationID 3 created successfully.\n";
// } else {
//     echo "Failed to create the donation.\n";
// }


// }
// main();


    

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

    // Technical Object 1
// $technical1 = new technicalModel(
//     'tech_guru',           // username
//     'John',                // firstname
//     'Smith',               // lastname
//     'johnsmith@example.com', // email
//     'password123',         // password
//     ['New York', 'USA'],   // location
//     1234567890,            // phoneNumber
//     8000,                  // salary
//     40,                    // workingHours
//     ['PHP', 'MySQL'],      // skills
//     ['AWS Certification'], // certifications 
// );

// if (technicalModel::create($technical1)) {
//     echo "Technical 1 created successfully.\n";
// } else {
//     echo "Failed to create Technical 1.\n";
// }

// // Technical Object 2
// $technical2 = new technicalModel(
//     'code_master',         // username
//     'Jane',                // firstname
//     'Doe',                 // lastname
//     'janedoe@example.com', // email
//     'securePass',          // password
//     ['San Francisco', 'USA'], // location
//     9876543210,            // phoneNumber
//     10000,                 // salary
//     45,                    // workingHours
//     ['Python', 'Django'],  // skills
//     ['Azure Certification'], // certifications
// );

// if (technicalModel::create($technical2)) {
//     echo "Technical 2 created successfully.\n";
// } else {
//     echo "Failed to create Technical 2.\n";
// }

// // Technical Object 3
// $technical3 = new technicalModel(
//     'data_wizard',         // username
//     'Alice',               // firstname
//     'Johnson',             // lastname
//     'alicej@example.com',  // email
//     'dataRocks!',          // password
//     ['Seattle', 'USA'],    // location
//     4561237890,            // phoneNumber
//     9000,                  // salary
//     35,                    // workingHours
//     ['Data Science', 'ML'], // skills
//     ['GCP Certification'], // certifications
// );

// if (technicalModel::create($technical3)) {
//     echo "Technical 3 created successfully.\n";
// } else {
//     echo "Failed to create Technical 3.\n";
// }

// // Technical Object 4
// $technical4 = new technicalModel(
//     'dev_ops',             // username
//     'Bob',                 // firstname
//     'Brown',               // lastname
//     'bobbrown@example.com', // email
//     'ops1234',             // password
//     ['Austin', 'USA'],     // location
//     3216549870,            // phoneNumber
//     11000,                 // salary
//     50,                    // workingHours
//     ['DevOps', 'Kubernetes'], // skills
//     ['Red Hat Certification'], // certifications
// );

// if (technicalModel::create($technical4)) {
//     echo "Technical 4 created successfully.\n";
// } else {
//     echo "Failed to create Technical 4.\n";
// }

// try {
//     // Step 1: Retrieve the technicalModel with userID = 2
//     $technical = technicalModel::retrieve(2);

//     if ($technical) {
//         echo "Before Update:\n";
//         echo "Username: " . $technical->getUsername() . "\n";
//         echo "First Name: " . $technical->getFirstname() . "\n";
//         echo "Last Name: " . $technical->getLastname() . "\n";
//         echo "Email: " . $technical->getEmail() . "\n";
//         echo "Location: " . json_encode($technical->getLocation()) . "\n";
//         echo "Skills: " . json_encode($technical->getSkills()) . "\n";
//         echo "Certifications: " . json_encode($technical->getCertifications()) . "\n";
//         echo "UserId: " . $technical->getUserID() . "\n";

//         // Step 2: Update some fields in the technicalModel
//         $technical->setUsername('updated_tech_master');
//         $technical->setFirstname('Updated Jane');
//         $technical->setLastname('Doe Updated');
//         $technical->setEmail('updatedjanedoe@example.com');
//         $technical->setLocation(['San Francisco', 'USA', 'Updated']);
//         $technical->setSkills(['Python', 'Django', 'Flask']);
//         $technical->setCertifications(['Updated Azure Certification', 'AWS Certification']);

//         // Step 3: Call the update method
//         if (technicalModel::update($technical)) {
//             echo "TechnicalModel with userID 2 updated successfully.\n";

//             // Step 4: Retrieve the technicalModel again to confirm the update
//             $updatedTechnical = technicalModel::retrieve(2);

//             if ($updatedTechnical) {
//                 echo "After Update:\n";
//                 echo "Username: " . $updatedTechnical->getUsername() . "\n";
//                 echo "First Name: " . $updatedTechnical->getFirstname() . "\n";
//                 echo "Last Name: " . $updatedTechnical->getLastname() . "\n";
//                 echo "Email: " . $updatedTechnical->getEmail() . "\n";
//                 echo "Location: " . json_encode($updatedTechnical->getLocation()) . "\n";
//                 echo "Skills: " . json_encode($updatedTechnical->getSkills()) . "\n";
//                 echo "Certifications: " . json_encode($updatedTechnical->getCertifications()) . "\n";
//             } else {
//                 echo "Failed to retrieve the updated technicalModel.\n";
//             }
//         } else {
//             echo "Failed to update technicalModel with userID 2.\n";
//         }
//     } else {
//         echo "No technicalModel found with userID 2.\n";
//     }
// } catch (Exception $e) {
//     echo "An error occurred: " . $e->getMessage();
// }

// Dummy Employees to be managed by HR
// $employee1 = [
//     'username' => 'john_employee',
//     'firstname' => 'John',
//     'lastname' => 'Doe',
//     'email' => 'john.employee@example.com',
//     'password' => password_hash('password123', PASSWORD_DEFAULT),
//     'location' => ['New York', 'USA'],
//     'phoneNumber' => 1234567890,
//     'title' => 'Developer',
//     'salary' => 6000,
//     'workingHours' => 40,
// ];

// $employee2 = [
//     'username' => 'jane_employee',
//     'firstname' => 'Jane',
//     'lastname' => 'Smith',
//     'email' => 'jane.employee@example.com',
//     'password' => password_hash('password123', PASSWORD_DEFAULT),
//     'location' => ['Los Angeles', 'USA'],
//     'phoneNumber' => 9876543210,
//     'title' => 'Designer',
//     'salary' => 5500,
//     'workingHours' => 35,
// ];
// $managedEmployees = [$employee1, $employee2];

// // Create HR instances
// $hr1 = new HRModel(
//     'hr_guru',
//     'Sarah',
//     'Johnson',
//     'sarah.hr@example.com',
//     'passwordHR',
//     ['San Francisco', 'USA'],
//     7894561230,
//     8000, // Salary
//     40,   // Working hours
//     $managedEmployees, // Managed employees
// );

// $hr2 = new HRModel(
//     'hr_master',
//     'David',
//     'Williams',
//     'david.hr@example.com',
//     'passwordHR',
//     ['Chicago', 'USA'],
//     4561237890,
//     9000, // Salary
//     45,   // Working hours
//     [$employee2], // Managed employees
// );

// // Insert employees into the database
// $employeesToInsert = [$employee1, $employee2];

// foreach ($employeesToInsert as $employeeData) {
//     $employee = new EmployeeModel(
//         $employeeData['username'],
//         $employeeData['firstname'],
//         $employeeData['lastname'],
//         $employeeData['email'],
//         $employeeData['password'],
//         $employeeData['location'],
//         $employeeData['phoneNumber'],
//         $employeeData['title'],
//         $employeeData['salary'],
//         $employeeData['workingHours'],
//     );
// }

// Insert HR into the database
// $hrInstances = [$hr1, $hr2];
// foreach ($hrInstances as $hr) {
//     if (HRModel::create($hr)) {
//         echo "HR with userID {$hr->getUserID()} created successfully.\n";
//     } else {
//         echo "Failed to create HR with userID {$hr->getUserID()}.\n";
//     }
// }

// // Retrieve HR with userID = 1

// Test retrieving HR with userID = 7
// $hr = HRModel::retrieve(1);

// if ($hr) {
//     echo "HR Retrieved Successfully:\n";
//     echo "User ID: " . $hr->getUserID() . "\n";
//     echo "Username: " . $hr->getUsername() . "\n";
//     echo "First Name: " . $hr->getFirstname() . "\n";
//     echo "Last Name: " . $hr->getLastname() . "\n";
//     echo "Email: " . $hr->getEmail() . "\n";
//     echo "Salary: " . $hr->getSalary() . "\n";
//     echo "Working Hours: " . $hr->getHoursWorked() . "\n";
//     echo "Managed Employees: " . implode(', ', $hr->getManagedEmployees()) . "\n";
//     echo "Location: " . implode(', ', $hr->getLocation()) . "\n";
// } else {
//     echo "No HR found with the given User ID.\n";
// }


// $newHR = new hrModel(
//     'hr_manager',             // username
//     'John',                   // firstname
//     'Doe',                    // lastname
//     'johndoe@example.com',    // email
//     'securepassword123',      // password
//     ['New York', 'USA'],      // location
//     9876543210,               // phoneNumber
//     90000,                    // salary
//     40,                       // workingHours
//     [101, 102, 103],          // managedEmployees (dummy employee IDs)
//     10                        // userID
// );

// // Insert the HR data into the database
// if (HRModel::create($newHR)) {
//     echo "HR record created successfully:\n";
//     print_r($newHR);

//     // Retrieve the HR data
//     $retrievedHR = HRModel::retrieve($newHR->getUserID());

//     if ($retrievedHR) {
//         echo "\nRetrieved HR data:\n";
//         print_r($retrievedHR);

//         // Update the HR data
//         $retrievedHR->setFirstname('Jane');
//         $retrievedHR->setLastname('Smith');
//         $retrievedHR->setSalary(95000);
//         $retrievedHR->addEmployees([104, 105]);

//         if (HRModel::update($retrievedHR)) {
//             echo "\nHR record updated successfully:\n";
//             $updatedHR = HRModel::retrieve($retrievedHR->getUserID());
//             print_r($updatedHR);

//             // Clean up: delete the test HR data
//             if (UserModel::delete($retrievedHR->getUserID())) {
//                 echo "\nHR record deleted successfully.\n";
//             } else {
//                 echo "\nFailed to delete HR record.\n";
//             }
//         } else {
//             echo "\nFailed to update HR record.\n";
//         }
//     } else {
//         echo "\nFailed to retrieve HR record.\n";
//     }
// } else {
//     echo "Failed to create HR record.\n";
// }


// $instructor1 = new InstructorModel(
//     'math_master',   // username
//     'Alan',          // firstname
//     'Turing',        // lastname
//     'alan.turing@example.com', // email
//     'securepass1',   // password
//     ['London', 'UK'], // location
//     987654321,       // phoneNumber
//     75000,           // salary
//     40,              // workingHours
//     ['Calculus', 'Algebra'], // lessons
//     100         // userID
// );

// $instructor2 = new InstructorModel(
//     'science_genius', // username
//     'Marie',          // firstname
//     'Curie',          // lastname
//     'marie.curie@example.com', // email
//     'securepass2',    // password
//     ['Paris', 'France'], // location
//     123456789,        // phoneNumber
//     80000,            // salary
//     35,               // workingHours
//     ['Physics', 'Chemistry'], // lessons
//     200               // userID
// );

// $instructor3 = new InstructorModel(
//     'history_pro',    // username
//     'Isaac',          // firstname
//     'Newton',         // lastname
//     'isaac.newton@example.com', // email
//     'securepass3',    // password
//     ['Cambridge', 'UK'], // location
//     456789123,        // phoneNumber
//     90000,            // salary
//     30,               // workingHours
//     ['History of Science', 'Philosophy'], // lessons
//     300              // userID
// );

// $instructor4 = new InstructorModel(
//     'bio_teacher',    // username
//     'Charles',        // firstname
//     'Darwin',         // lastname
//     'charles.darwin@example.com', // email
//     'securepass4',    // password
//     ['Kent', 'UK'],   // location
//     789123456,        // phoneNumber
//     85000,            // salary
//     32,               // workingHours
//     ['Biology', 'Evolution'], // lessons
//     400             // userID
// );

// // Insert the instructors into the database
// $instructors = [$instructor1, $instructor2, $instructor3, $instructor4];
// foreach ($instructors as $instructor) {
//     if (InstructorModel::create($instructor)) {
//         echo "Instructor {$instructor->getUsername()} created successfully.\n";
//     } else {
//         echo "Failed to create Instructor {$instructor->getUsername()}.\n";
//     }
// }

// Test retrieval and update on one instructor
// $retrievedInstructor = InstructorModel::retrieve(200);
// if ($retrievedInstructor) {
//     echo "\nRetrieved Instructor:\n";
//     print_r($retrievedInstructor);

//     // Update the retrieved instructor
//     $retrievedInstructor->setFirstname('Marie Updated');
//     $retrievedInstructor->addLessons(['Physics Updated', 'Chemistry Updated']);
//     if (InstructorModel::update($retrievedInstructor)) {
//         echo "\nInstructor updated successfully.\n";

//         // Verify the update
//         $updatedInstructor = InstructorModel::retrieve(2);
//         echo "\nUpdated Instructor:\n";
//         print_r($updatedInstructor);
//     } else {
//         echo "\nFailed to update Instructor.\n";
//     }
// }

// Clean up by deleting all test instructors

// foreach ($instructors as $instructor) {
//     if (InstructorModel::delete($instructor->getUserID())) {
//         echo "Instructor {$instructor->getUsername()} deleted successfully.\n";
//     } else {
//         echo "Failed to delete Instructor {$instructor->getUsername()}.\n";
//     }
// }


// Include necessary files or autoloaders
// $personnel1 = new DeliveryPersonnel(
//     'delivery_guy1',
//     'John',
//     'Doe',
//     'example@example.com',
//     'securepassword',
//     ['New York', 'USA'],
//     1234567890,
//     4000,
//     40,
//     'Motorbike',
//     99
// );

// $personnel2 = new DeliveryPersonnel(
//     'delivery_guy2',
//     'Jane',
//     'Smith',
//     'john.doe@example.com',
//     'securepassword123',
//     ['San Francisco', 'USA'],
//     9876543210,
//     4500,
//     35,
//     'Bicycle',
//     100
// );

// echo "Creating delivery personnel...\n";
// if (DeliveryPersonnel::create($personnel1)) {
//     echo "Delivery Personnel 1 created successfully.\n";
// } else {
//     echo "Failed to create Delivery Personnel 1.\n";
// }

// if (DeliveryPersonnel::create($personnel2)) {
//     echo "Delivery Personnel 2 created successfully.\n";
// } else {
//     echo "Failed to create Delivery Personnel 2.\n";
// }

// // Step 2: Retrieve and Print
// echo "Retrieving Delivery Personnel 1...\n";
// $retrievedPersonnel = DeliveryPersonnel::retrieve(99);
// if ($retrievedPersonnel) {
//     echo "Retrieved Delivery Personnel 1:\n";
//     print_r($retrievedPersonnel);
// } else {
//     echo "Failed to retrieve Delivery Personnel 1.\n";
// }

// // Step 3: Update Data
// if ($retrievedPersonnel) {
//     echo "Updating Delivery Personnel 1...\n";
//     $retrievedPersonnel->setFirstname('Updated John');
//     $retrievedPersonnel->setLastname('Updated Doe');
//     $retrievedPersonnel->setVehicleType('Truck');
//     $retrievedPersonnel->setDeliveriesCompleted(50);

//     if (DeliveryPersonnel::update($retrievedPersonnel)) {
//         echo "Delivery Personnel 1 updated successfully.\n";

//         // Verify the update
//         echo "Verifying update for Delivery Personnel 1...\n";
//         $updatedPersonnel = DeliveryPersonnel::retrieve(1);
//         if ($updatedPersonnel) {
//             echo "Updated Delivery Personnel 1:\n";
//             print_r($updatedPersonnel);
//         } else {
//             echo "Failed to retrieve updated Delivery Personnel 1.\n";
//         }
//     } else {
//         echo "Failed to update Delivery Personnel 1.\n";
//     }

//     echo "Deleting Delivery Personnel...\n";
// if (DeliveryPersonnel::delete(100)) {
//     echo "Delivery Personnel deleted successfully.\n";
// } else {
//     echo "Failed to delete Delivery Personnel.\n";
// }
// }


// Step 5: Clean up (optional, for testing purposes only)
// You can delete test data after testing if required.






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


function main(){
    // Create an instructor
// $instructor = new InstructorModel(
//     "instructor_username",
//     "Instructor",
//     "Lastname",
//     "instructor@example.com",
//     "securepassword",
//     ["City", "Country"],
//     1234567890,
//     50000,
//     40,
//     [],
//     999 // userID
// );

// // Ensure the instructor exists in the database
// InstructorModel::create($instructor);

// // Create a lesson
// $lesson = new LessonModel(
//     "PHP Basics",
//     "Programming",
//     120,
//     $instructor // Pass the instructor object
// );

// if (LessonModel::create($lesson)) {
//     echo "Lesson created successfully.\n";
// } else {
//     echo "Failed to create lesson.\n";
// }

// // Retrieve the lesson
// $retrievedLesson = LessonModel::retrieve(1);
// if ($retrievedLesson) {
//     echo "Lesson retrieved: " . $retrievedLesson->getLessonName() . "\n";
//     echo "Instructor: " . $retrievedLesson->getInstructor()->getFirstname() . "\n";
// } else {
//     echo "Failed to retrieve lesson.\n";
// }

// // Update the lesson
// $retrievedLesson ->setLessonName("Advanced PHP");
// if (LessonModel::update($retrievedLesson)) {
//     echo "Lesson updated successfully.\n";
// } else {
//     echo "Failed to update lesson.\n";
// }

// // Delete the lesson
// if (LessonModel::delete($retrievedLesson->getLessonId())) {
//     echo "Lesson deleted successfully.\n";
// } else {
//     echo "Failed to delete lesson.\n";
// }

$instructor1 = new InstructorModel(
    "instructor1",
    "Alice",
    "Johnson",
    "alice.johnson@example.com",
    "securepassword1",
    ["City1", "Country1"],
    9876543210,
    5000,
    40,
    [],
    66
);

$instructor2 = new InstructorModel(
    "instructor2",
    "Bob",
    "Smith",
    "bob.smith@example.com",
    "securepassword2",
    ["City2", "Country2"],
    8765432109,
    6000,
    35,
    [],
    77
);

// Create Instructors in the Database
InstructorModel::create($instructor1);
InstructorModel::create($instructor2);

$lesson1 = new LessonModel(
    "Math 101",
    "Mathematics",
    60,
    $instructor1, // Pass InstructorModel object
    111
);

$lesson2 = new LessonModel(
    "Science Basics",
    "Science",
    45,
    $instructor2, // Pass InstructorModel object
    222
);

// Create Lessons in the Database
LessonModel::create($lesson1);
LessonModel::create($lesson2);

$student = new StudentModel(
    "student1",
    "John",
    "Doe",
    "john.doe@example.com",
    "studentpassword",
    ["City", "Country"],
    1234567890,
    [$lesson1, $lesson2],
    [],
    1000
);

// Create Student in the Database
StudentModel::create($student);

echo "Retrieving Student...\n";
$retrievedStudent = StudentModel::retrieve(1000); // Retrieve by userID
print_r($retrievedStudent);

echo "Updating Student...\n";
// Update enrolled lessons
$retrievedStudent->setLessons([$lesson1]); // Remove one lesson
StudentModel::update($retrievedStudent);

// Retrieve again to verify
$updatedStudent = StudentModel::retrieve(1000);
print_r($updatedStudent);

echo "Deleting Student...\n";
StudentModel::delete(1000);






}

main();
