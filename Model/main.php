<?php
require_once 'configurations.php';
require_once 'db_connection.php';
require_once 'data.php';

function main() {
    // Initialize database connection
    $config = require 'configurations.php';
    $db = new DatabaseConnection($config);

    // Create tables
    try {
        createTables($db); // Calls the createTables function from data.php
    } catch (Exception $e) {
        echo "Error creating tables: " . $e->getMessage() . "<br>";
    }

    // Insert sample data for testing
    try {
        $sampleDataSQL = "INSERT INTO `user` (username, firstName, lastName, email, password, locationList, phoneNumber, isActive) VALUES
            ('testuser1', 'Test', 'User1', 'testuser1@example.com', MD5('password1'), '[\"New York\", \"Los Angeles\"]', '1234567890', TRUE),
            ('testuser2', 'Test', 'User2', 'testuser2@example.com', MD5('password2'), '[\"Chicago\"]', '9876543210', FALSE)";
        $db->execute($sampleDataSQL);
        echo "Sample data inserted successfully.<br>";
    } catch (Exception $e) {
        echo "Error inserting sample data: " . $e->getMessage() . "<br>";
    }

    // Fetch and display data for testing
    try {
        $users = $db->query("SELECT * FROM `user`");
        if ($users) {
            foreach ($users as $user) {
                echo "UserID: {$user['userID']}, Username: {$user['username']}, Email: {$user['email']}<br>";
            }
        } else {
            echo "No users found.<br>";
        }
    } catch (Exception $e) {
        echo "Error fetching data: " . $e->getMessage() . "<br>";
    }

    // Close the database connection
    $db->close();
}

// Run the main function
main();
?>
