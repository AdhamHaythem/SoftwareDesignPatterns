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

    // Create tables and handle other operations here
    createTables($db);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

if ($db->conn->connect_error) {
    die("Connection failed: " . $db->conn->connect_error);
} else {
    echo "Connected to the database successfully.<br>";
}

function createTables($db){
    $sql_user = "CREATE TABLE IF NOT EXISTS `user` (
        `userID` INT NOT NULL PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `firstName` VARCHAR(50) NOT NULL,
        `lastName` VARCHAR(50) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `locationList` TEXT,
        `phoneNumber` VARCHAR(15),
        `isActive` BOOLEAN DEFAULT TRUE
    )";

    if ($db->execute($sql_user)) {
        echo "Table 'user' created successfully.<br>";
    } else {
        echo "Error creating table: " . $db->getError() . "<br>";
    }
}

$sql_admin = "CREATE TABLE IF NOT EXISTS `admin` (
    `userID` INT NOT NULL PRIMARY KEY,
    `donation_manager` VARCHAR(255),
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE ON UPDATE CASCADE
)";
if ($db->execute($sql_admin) === TRUE) {
    echo "Table 'admin' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_employee = "CREATE TABLE IF NOT EXISTS `employee`(
    `userID` INT NOT NULL PRIMARY KEY,
    `title` VARCHAR(50) NOT NULL,
    `salary` INT NOT NULL,
    `workingHours` INT NOT NULL,
    FOREIGN KEY (`userID`) 
        REFERENCES `user`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

if ($db->execute($sql_employee) === TRUE) {
    echo "Table 'Employee' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_hr = "CREATE TABLE IF NOT EXISTS `HR` (
    `userID` INT NOT NULL PRIMARY KEY,
    `managedEmployees` TEXT, -- JSON or serialized list of Employee IDs
    
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

$sql_technical = "CREATE TABLE IF NOT EXISTS `technical` (
    `userID` INT NOT NULL PRIMARY KEY,
    `skills` TEXT,            -- JSON or serialized list of skills
    `certifications` TEXT,    -- JSON or serialized list of certifications
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

$sql_delivery_personnel = "CREATE TABLE IF NOT EXISTS `deliverypersonnel` (
    `userID` INT NOT NULL PRIMARY KEY,
    `vehicleType` VARCHAR(50) NOT NULL,
    `driverLicense` VARCHAR(50) NOT NULL,
    `deliveriesCompleted` INT NOT NULL,
    `currentLoad` TEXT,       -- JSON or serialized list of current items
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";
// Execute queries
if ($db->execute($sql_hr) === TRUE) {
    echo "Table 'HR' created successfully.<br>";
} else {
    echo "Error creating table 'HR': " . $conn->error . "<br>";
}

if ($db->execute($sql_technical) === TRUE) {
    echo "Table 'Technical' created successfully.<br>";
} else {
    echo "Error creating table 'Technical': " . $conn->error . "<br>";
}

if ($db->execute($sql_delivery_personnel) === TRUE) {
    echo "Table 'DeliveryPersonnel' created successfully.<br>";
} else {
    echo "Error creating table 'DeliveryPersonnel': " . $conn->error . "<br>";
}

$sql_instructor = "CREATE TABLE IF NOT EXISTS `instructor` (
    `userID` INT NOT NULL PRIMARY KEY,
    `lessons` TEXT, -- JSON or serialized list of lessons
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

// Execute query
if ($db->execute($sql_instructor) === TRUE) {
    echo "Table 'Instructor' created successfully.<br>";
} else {
    echo "Error creating table 'Instructor': " . $conn->error . "<br>";
}

$sql_donor = "CREATE TABLE IF NOT EXISTS donor (
    donorID INT NOT NULL PRIMARY KEY,
    userID INT NOT NULL UNIQUE, 
    donationHistory TEXT,
    totalDonations DOUBLE,
    goalAmount DOUBLE,
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE
)";

if ($db->execute($sql_donor) === TRUE) {
    echo "Table 'donor' created successfully.<br>";

    $alter_donor_sql = "ALTER TABLE donor ADD CONSTRAINT unique_user_id UNIQUE (userID)";
    if ($db->execute($alter_donor_sql) === TRUE) {
        echo "Unique constraint added to 'donor' table successfully.<br>";
    } else {
        echo "Error adding unique constraint to 'donor' table: " . $conn->error . "<br>";
    }
} else {
    echo "Error creating table 'donor': " . $conn->error . "<br>";
}


$sql_donation = "CREATE TABLE IF NOT EXISTS `donation` (
    `donationID` INT NOT NULL PRIMARY KEY,
    `amount` DOUBLE NOT NULL,
    `donorID` INT NOT NULL,
    UNIQUE (`donorID`, `amount`),  -- Prevent duplicate donations for the same donor
    FOREIGN KEY (`donorID`) REFERENCES `donor`(`donorID`) ON DELETE CASCADE ON UPDATE CASCADE
)";
if ($db->execute($sql_donation) === TRUE) {
    echo "Table 'Donation' created successfully.<br>";
} else {
    echo "Error creating table 'Donation': " . $conn->error . "<br>";
}

$sql_donation_manager = "CREATE TABLE IF NOT EXISTS donationManager (
    donations TEXT NOT NULL,                     
    totalDonations DOUBLE DEFAULT 0.0,           
    goalAmount DOUBLE DEFAULT 0.0,               
    campaigns TEXT NOT NULL,                     -- JSON or serialized list of campaigns
    UNIQUE KEY unique_donations (donations(255)) -- Ensure unique donations
)";

if ($db->execute($sql_donation_manager) === TRUE) {
    echo "Table 'DonationManager' created successfully.<br>";
} else {
    echo "Error creating table 'DonationManager': " . $conn->error . "<br>";
}


$sql_lesson = "CREATE TABLE IF NOT EXISTS `lesson` (
    `lessonID` INT NOT NULL PRIMARY KEY,
    `lessonName` VARCHAR(255) NOT NULL,
    `lessonSubject` VARCHAR(255) NOT NULL,
    `duration` INT NOT NULL, -- Duration in minutes
    `instructorID` INT NOT NULL, -- References the Instructor table
    `views` INT DEFAULT 0, -- Number of student views
    FOREIGN KEY (`instructorID`) 
        REFERENCES `Instructor`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

// Execute query
if ($db->execute($sql_lesson) === TRUE) {
    echo "Table 'Lesson' created successfully.<br>";
} else {
    echo "Error creating table 'Lesson': " . $conn->error . "<br>";
}


$sql_student = "CREATE TABLE IF NOT EXISTS `student` (
    `userID` INT NOT NULL,
    `lessonDetails` VARCHAR(255),
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE
)";
if ($db->execute($sql_student) === TRUE) {
    echo "Table 'student' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}




$sql_event = "CREATE TABLE IF NOT EXISTS event (
    eventID INT NOT NULL PRIMARY KEY, 
    name VARCHAR(255) NOT NULL,                  
    time DATETIME NOT NULL,                         
    location VARCHAR(255) NOT NULL,                  
    volunteers_needed INT NOT NULL,                
    volunteersList TEXT                          
)";

if ($db->execute($sql_event) === TRUE) {
    echo "Table 'Event' created successfully.<br>";
} else {
    echo "Error creating table 'Event': " . $conn->error . "<br>";
}

// VolunteeringEventStrategy Table
$sql_volunteering_event_strategy = "CREATE TABLE IF NOT EXISTS `volunteeringeventstrategy` (
    `strategyID` INT NOT NULL PRIMARY KEY, -- Unique identifier for each strategy
    `eventID` INT NOT NULL,                               -- Foreign key referencing Event
    FOREIGN KEY (`eventID`) REFERENCES `Event`(`eventID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)";

if ($db->execute($sql_volunteering_event_strategy) === TRUE) {
    echo "Table 'VolunteeringEventStrategy' created successfully.<br>";
} else {
    echo "Error creating table 'VolunteeringEventStrategy': " . $conn->error . "<br>";
}

// CampaignStrategy Table
$sql_campaign_strategy = "CREATE TABLE IF NOT EXISTS `campaignstrategy` (
    `campaignID` INT NOT NULL PRIMARY KEY,
    `target` DOUBLE NOT NULL,                           
    `title` VARCHAR(255) NOT NULL,                      
    `donations` TEXT,                                   
    `moneyEarned` FLOAT DEFAULT 0.0                   
)";

if ($db->execute($sql_campaign_strategy) === TRUE) {
    echo "Table 'CampaignStrategy' created successfully.<br>";
} else {
    echo "Error creating table 'CampaignStrategy': " . $conn->error . "<br>";
}

// EventVolunteer Bridge Table
$sql_event_volunteer = "CREATE TABLE IF NOT EXISTS `eventvolunteer` (
    `eventID` INT NOT NULL,
    `donorID` INT NOT NULL,
    PRIMARY KEY (`eventID`, `donorID`),
    FOREIGN KEY (`eventID`) REFERENCES `Event`(`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`donorID`) REFERENCES `Donor`(`donorID`) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($db->execute($sql_event_volunteer) === TRUE) {
    echo "Table 'EventVolunteer' created successfully.<br>";
} else {
    echo "Error creating table 'EventVolunteer': " . $conn->error . "<br>";
}

$insert_user_sql = "INSERT INTO `user` (username, firstName, lastName, email, password, locationList, phoneNumber, isActive) VALUES
    ('johndoe', 'John', 'Doe', 'johndoe@example.com', MD5('password123'), '[\"New York\", \"Los Angeles\"]', '1234567890', TRUE),
    ('alice', 'Alice', 'Smith', 'alice@example.com', MD5('password789'), '[\"Chicago\"]', '1122334455', FALSE)";

if ($conn->query($insert_user_sql) === TRUE) {
    echo "Sample data inserted into 'user' table successfully.<br>";
} else {
    echo "Error inserting data into 'user': " . $conn->error . "<br>";
}


$insert_donor_sql = "INSERT INTO `donor` (userID, donationHistory, totalDonations, goalAmount) 
VALUES 
(1, '[{\"donationID\": 1, \"amount\": 100}]', 100, 500), 
(2, '[{\"donationID\": 2, \"amount\": 200}]', 200, 1000)";

if ($conn->query($insert_donor_sql) === TRUE) {
    echo "Sample data inserted into 'donor' table successfully.<br>";
} else {
    echo "Error inserting data into 'donor': " . $conn->error . "<br>";
}


// Close the connection
$conn->close();

?>