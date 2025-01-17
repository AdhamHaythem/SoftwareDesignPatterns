<?php
require_once 'db_connection.php';

$config = [
    'DB_HOST' => 'localhost',     
    'DB_USER' => 'root',           
    'DB_PASS' => '',               
    'DB_NAME' => 'sdp',            
];

try {
    // Create a new DatabaseConnection object
    $db = new DatabaseConnection($config);

    // Create the travel_plans table
    createTravelPlanTable($db);
} catch (Exception $e) {
    // echo "Error: " . $e->getMessage();
}

if ($db->conn->connect_error) {
    die("Connection failed: " . $db->conn->connect_error);
} else {
    // echo "Connected to the database successfully.<br>";
}

function createTravelPlanTable($db) {
    $sql_travel_plans = "CREATE TABLE IF NOT EXISTS `travel_plans` (
        `travelPlanID` INT AUTO_INCREMENT PRIMARY KEY,
        `userID` INT NOT NULL,
        `eventID` INT NOT NULL,
        `destination` VARCHAR(255) NOT NULL,
        `startDate` DATE NOT NULL,
        `endDate` DATE NOT NULL,
        `transportMode` VARCHAR(50) NOT NULL,
        `cost` FLOAT NOT NULL,
        FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`eventID`) REFERENCES `event`(`eventID`) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    // if ($db->execute($sql_travel_plans) === TRUE) {
    //     echo "Table 'travel_plans' created successfully.<br>";
    // } else {
    //     echo "Error creating table 'travel_plans': " . $db->getError() . "<br>";
    // }
}

// Close the connection
$conn->close();

?>
