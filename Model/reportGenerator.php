<?php
require_once 'reportGeneratingTemplate.php';
class ReportGenerator extends ReportsGenerationTemplate {

    public function __construct() {
        $this->db = DatabaseConnection::getInstance();

    }

    public function getData(String $dataType): array {
        if ($dataType == 'Instructor') {
            $sql = "
                SELECT 
                user.userID,
                user.username,
                user.firstName,
                user.lastName,
                user.email,
                user.locationList,
                user.phoneNumber,
                employee.title, 
                employee.salary, 
                employee.workingHours,
                instructor.lessons
                FROM user
                INNER JOIN employee ON user.userID = employee.userID
                INNER JOIN instructor ON user.userID = instructor.userID
                WHERE employee.title = 'Instructor'
            ";
        }
        else if ($dataType == 'HR') {
            $sql = "
            SELECT 
            user.userID,
            user.username,
            user.firstName,
            user.lastName,
            user.email,
            user.locationList,
            user.phoneNumber,
            employee.title, 
            employee.salary, 
            employee.workingHours,
            hr.managedEmployees
            FROM user
            INNER JOIN employee ON user.userID = employee.userID
            INNER JOIN hr ON user.userID = hr.userID
            WHERE employee.title = 'HR'
        ";
        }
        else if ($dataType == 'Technical') {
            $sql = "
                SELECT 
                    user.userID,
                    user.username,
                    user.firstName,
                    user.lastName,
                    user.email,
                    user.locationList,
                    user.phoneNumber,
                    employee.title, 
                    employee.salary, 
                    employee.workingHours,
                    technical.skills,
                    technical.certifications
                FROM 
                    user
                INNER JOIN 
                    employee ON user.userID = employee.userID
                INNER JOIN 
                    technical ON user.userID = technical.userID
                WHERE 
                    employee.title = 'Technical'
            ";
        }
        else if ($dataType == 'Delivery') {
            $sql = "
            SELECT 
            user.userID,
            user.username,
            user.firstName,
            user.lastName,
            user.email,
            user.locationList,
            user.phoneNumber,
            employee.title, 
            employee.salary, 
            employee.workingHours,
            deliverypersonnel.vehicleType,
            deliverypersonnel.driverLicense,
            deliverypersonnel.deliveriesCompleted,
            deliverypersonnel.currentLoad
            FROM user
            INNER JOIN employee ON user.userID = employee.userID
            INNER JOIN deliverypersonnel ON user.userID = deliverypersonnel.userID
            WHERE employee.title = 'Delivery'
        ";
        }

        else if ($dataType == 'Donor') {
            $sql = "
           SELECT 
            donor.donorID,
            user.userID,
            user.username,
            user.firstName,
            user.lastName,
            user.email,
            donor.donationHistory,
            donor.totalDonations,
            donor.goalAmount
        FROM 
            donor
        INNER JOIN 
            user 
        ON 
            donor.userID = user.userID
        ";
        }
        
            // Execute the query using the custom query function
            try {
                $results = $this->db->query($sql); // No need for `fetch_assoc`
                // if (!empty($results)) {
                //     foreach ($results as $row) {
                //         print_r($row); // Output each row of data
                //     }
                // } else {
                //     echo "No $dataType found.";
                // }
            } catch (Exception $e) {
                echo "Query error: " . $e->getMessage();
            }
            return $results;
    }

    public function generate(Object $object): void {

    }
    public function filterData(int $userID , array $results): array{
        foreach ($results as $result) {
            if ($result['userID'] == $userID) {
            return $result;
            }
        }
        return [];

    }

    public function formatData(array $result): Object {
        if (!empty($result)) {
            $object = new stdClass();
            foreach ($result as $key => $value) {
                $object->$key = $value;
            }
            return $object;
        }
        return new stdClass();
    }
}
?>