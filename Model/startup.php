<?php

require_once 'db_connection.php';
require_once 'userModel.php';
require_once 'Lesson.php';
require_once 'DonationModel.php';
require_once 'IEvent.php';

class StartUp {
    public static function initialize() {
        $dbConnection = DatabaseConnection::getInstance();
        
        // Fetch the counter row from the database
        $sql = "SELECT * FROM counters WHERE CounterID = 1";
        $result = $dbConnection->query($sql);

        // Check if the result has any rows
        if ($result && !empty($result)) {
            $row = $result[0];
            // Set counters for different models
            UserModel::setCounter((int)$row['UserID']);
            Event::setCounter((int)$row['EventID']);
            LessonModel::setCounter((int)$row['LessonID']);
            Donation::setCounter((int)$row['DonationID']);
        } else {
            // Handle the case where the row is not found
            throw new Exception("Counter row with CounterID = 1 not found.");
        }
    }
}
?>
