<?php
require_once 'configurations.php';
require_once 'db_connection.php';
require_once 'data.php';
require_once 'DonorModel.php'; // Ensure the DonorModel is included
require_once 'instructor.php';
require_once 'VolunteeringEventModel.php';

function main() {
    // Load configurations and create a database connection
    $config = require 'configurations.php';
    $db = new DatabaseConnection($config);
    // try {
    //     createTables($db);
    // } catch (Exception $e) {
    //     echo "Error creating tables: " . $e->getMessage() . "<br>";
    // }
    UserModel::setDatabaseConnection($db);
    Event::setDatabaseConnection($db);
    // Create a new donor object
    try {
        // Manually assigned userID
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

        $user1 = new UserModel(
            "johndoe",
            "John",
            "Doe",
            1, // User ID
            "johndoe@example.com",
            "password123",
            ["New York", "Los Angeles"], // Location
            1234567890 // Phone Number
        );
        
        $user2 = new UserModel(
            "janedoe",
            "Jane",
            "Doe",
            2, // User ID
            "janedoe@example.com",
            "password456",
            ["Chicago"], // Location
            9876543210 // Phone Number
        );
        

        // Create donor
        if (Donor::create($donor)) {
            echo "Donor created successfully!\n";
        } else {
            echo "Failed to create donor.\n";
        }


        if (UserModel::create($user1)) {
            echo "User created successfully!\n";
        } else {
            echo "Failed to create User.\n";
        }


        // if (UserModel::delete(3)) {
        //     echo "User Deleted successfully!\n";
        // } else {
        //     echo "Failed to Delete User.\n";
        // }

        $instructor = new InstructorModel(
            "john_doe",        // Username
            "John",            // First Name
            "Doe",             // Last Name
            1,                 // User ID
            "john.doe@example.com", // Email
            "securePassword123",    // Password
            ["New York", "Los Angeles"], // Location (as an array)
            1234567890,        // Phone Number
            "Senior Instructor", // Title
            60000,             // Salary
            40                 // Working Hours
        );
        
        // if (InstructorModel::create($instructor)) {
        //     echo "Instructor created successfully.\n";
        // } else {
        //     echo "Failed to create instructor.\n";
        // }
        
        // $lesson = new LessonModel(
        //     1,                     // lessonID
        //     "Introduction to PHP", // lessonName
        //     "Programming",         // lessonSubject
        //     60,                    // duration (in minutes)
        //     $instructor                    // views (initially zero)
        // );

        // if ($instructor->createLesson($lesson)) {
        //     echo "Lesson created successfully.\n";
        // } else {
        //     echo "Failed to create lesson.\n";
        // }


        $event = new VolunteeringEventStrategy(
            "PHP Basics Workshop",  // Name of the event
            new DateTime("2024-11-20 10:00:00"), // Date and time of the event
            "Online (Zoom)",        // Location
            10,                     // Volunteers needed
            101                     // Event ID
        );

        if (VolunteeringEventStrategy::create($event)) {
            echo "Event created successfully.\n";
        } else {
            echo "Failed to create Event.\n";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $db->close();
}

main();
