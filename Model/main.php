<?php
require_once 'configurations.php';
require_once 'db_connection.php';
require_once 'data.php';
require_once 'DonorModel.php';
require_once 'Donor.php'; // Ensure the Donor class is included

function main() {
    $config = require 'configurations.php';
    $db = new DatabaseConnection($config);

    try {
        // Create database tables
        createTables($db);
    } catch (Exception $e) {
        echo "Error creating tables: " . $e->getMessage() . "<br>";
    }

    // Create a Donor object
    $donor = new Donor(
        0, // userID
        "AdhamHaythem", // Username
        "Adham", // First name
        "Haythem", // Last name
        "adhamhaythem285@gmail.com", // Email
        "123456", // Password
        [], // Location (empty array as default)
        "01001449338", // Phone number as a string
        null, // Payment method (null for default behavior)
        null, // Event strategy (null for default)
        null  // Event data (null for default)
    );

    // Use DonorModel to insert into the database
    $result = Donor::create($donor);

    if ($result) {
        echo "Donor created successfully!<br>";
    } else {
        echo "Error creating donor.<br>";
    }

    // Close the database connection
    $db->close();
}

main();
