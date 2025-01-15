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

?>
