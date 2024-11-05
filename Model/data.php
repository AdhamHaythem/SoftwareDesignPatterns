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
    `adminID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userID` INT NOT NULL,
    `donation_manager` VARCHAR(255),
    FOREIGN KEY (`userID`) REFERENCES `user`(`userID`) ON DELETE CASCADE
)";
if ($conn->query($sql_admin) === TRUE) {
    echo "Table 'admin' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
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
    ('janedoe', 'Jane', 'Doe', 'janedoe@example.com', MD5('password456'), '[\"San Francisco\"]', '0987654321', TRUE),
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

