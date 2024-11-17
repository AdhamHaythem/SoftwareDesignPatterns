<?php
require_once 'db_connection.php';


//USER TABLEEEEEEE
$sql_user = "CREATE TABLE IF NOT EXISTS `user` (
    `userID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,         
    `username` VARCHAR(50) NOT NULL UNIQUE,                    
    `firstName` VARCHAR(50) NOT NULL,                          
    `lastName` VARCHAR(50) NOT NULL,                          
    `email` VARCHAR(100) NOT NULL UNIQUE,                      
    `password` VARCHAR(255) NOT NULL,                          
    `locationList` TEXT,                                      
    `phoneNumber` VARCHAR(15),                                 
    `isActive` BOOLEAN DEFAULT TRUE                           
)";
if ($conn->query($sql_user) === TRUE) {
    echo "Table 'user' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_admin = "CREATE TABLE IF NOT EXISTS `admin` (
    `userID` INT NOT NULL,
    `donation_manager` VARCHAR(255),
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE ON UPDATE CASCADE
)";
if ($conn->query($sql_admin) === TRUE) {
    echo "Table 'admin' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_employee = "CREATE TABLE IF NOT EXISTS `Employee`(
    `userID` INT NOT NULL PRIMARY KEY,
    `title` VARCHAR(50) NOT NULL,
    `salary` INT NOT NULL,
    `workingHours` INT NOT NULL,
    FOREIGN KEY (`userID`) 
        REFERENCES `user`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

if ($conn->query($sql_employee) === TRUE) {
    echo "Table 'Employee' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_hr = "CREATE TABLE IF NOT EXISTS `HR` (
    `userID` INT NOT NULL PRIMARY KEY,
    `managedEmployees` TEXT, -- JSON or serialized list of Employee IDs
    `recruits` TEXT,         -- JSON or serialized list of Donors
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

$sql_technical = "CREATE TABLE IF NOT EXISTS `Technical` (
    `userID` INT NOT NULL PRIMARY KEY,
    `skills` TEXT,            -- JSON or serialized list of skills
    `certifications` TEXT,    -- JSON or serialized list of certifications
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

$sql_delivery_personnel = "CREATE TABLE IF NOT EXISTS `DeliveryPersonnel` (
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
if ($conn->query($sql_hr) === TRUE) {
    echo "Table 'HR' created successfully.<br>";
} else {
    echo "Error creating table 'HR': " . $conn->error . "<br>";
}

if ($conn->query($sql_technical) === TRUE) {
    echo "Table 'Technical' created successfully.<br>";
} else {
    echo "Error creating table 'Technical': " . $conn->error . "<br>";
}

if ($conn->query($sql_delivery_personnel) === TRUE) {
    echo "Table 'DeliveryPersonnel' created successfully.<br>";
} else {
    echo "Error creating table 'DeliveryPersonnel': " . $conn->error . "<br>";
}

$sql_instructor = "CREATE TABLE IF NOT EXISTS `Instructor` (
    `userID` INT NOT NULL PRIMARY KEY,
    `lessons` TEXT, -- JSON or serialized list of lessons
    FOREIGN KEY (`userID`) 
        REFERENCES `Employee`(`userID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

// Execute query
if ($conn->query($sql_instructor) === TRUE) {
    echo "Table 'Instructor' created successfully.<br>";
} else {
    echo "Error creating table 'Instructor': " . $conn->error . "<br>";
}

$sql_donor = "CREATE TABLE IF NOT EXISTS `donor` (
    `donorID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userID` INT NOT NULL,
    `donationHistory` TEXT,
    `totalDonations` DOUBLE,
    `goalAmount` DOUBLE,
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE
)";
if ($conn->query($sql_donor) === TRUE) {
    echo "Table 'donor' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql_lesson = "CREATE TABLE IF NOT EXISTS `Lesson` (
    `lessonID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
if ($conn->query($sql_lesson) === TRUE) {
    echo "Table 'Lesson' created successfully.<br>";
} else {
    echo "Error creating table 'Lesson': " . $conn->error . "<br>";
}


$sql_student = "CREATE TABLE IF NOT EXISTS `student` (
    `studentID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userID` INT NOT NULL,
    `lessonDetails` VARCHAR(255),
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE
)";
if ($conn->query($sql_student) === TRUE) {
    echo "Table 'student' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$insert_user_sql = "INSERT INTO `user` (username, firstName, lastName, email, password, locationList, phoneNumber, isActive) VALUES
    ('johndoe', 'John', 'Doe', 'johndoe@example.com', MD5('password123'), '[\"New York\", \"Los Angeles\"]', '1234567890', TRUE),
    ('alice', 'Alice', 'Smith', 'alice@example.com', MD5('password789'), '[\"Chicago\"]', '1122334455', FALSE)
";
if ($conn->query($insert_user_sql) === TRUE) {
    echo "Sample data inserted into 'user' table successfully.<br>";
} else {
    echo "Error inserting data: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>