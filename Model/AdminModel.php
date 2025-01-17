<?php

require_once 'userModel.php';
require_once 'DonationManager.php';

class Admin extends UserModel {
    private DonationManager $donationManager;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        float $goalAmount = 0.0,
        int $userID=0
        ) {
        parent::__construct($username, $firstname, $lastname, $email, $password, $location, $phoneNumber, $userID);
        $this->donationManager = new DonationManager($userID,$goalAmount, [], []);
       // DonationManager :: create($this->donationManager);
    }

    // CRUD Methods
    public static function create($admin): bool {
        if (!$admin instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE username = VALUES(username), email = VALUES(email)";
    
            $userParams = [
                $admin->getUserID(), // userID
                $admin->getUsername(), // username
                $admin->getFirstname(), // firstName
                $admin->getLastname(), // lastName
                $admin->getEmail(), // email
                password_hash($admin->getPassword(), PASSWORD_DEFAULT), // hashed password
                json_encode($admin->getLocation()), // location as JSON
                $admin->getPhoneNumber(), // phoneNumber
                true // isActive
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert or update user record.");
            }
    
            $adminSql = "INSERT INTO admin (userID, donation_manager)
                         VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE donation_manager = VALUES(donation_manager)";
    
            $adminParams = [
                $admin->getUserID(), 
                json_encode($admin->donationManager)
            ];
    
            if (!$dbConnection->execute($adminSql, $adminParams)) {
                throw new Exception("Failed to insert or update admin record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error creating admin: " . $e->getMessage());
            return false;
        }
    }
    public static function retrieve($userID): ?Admin {
        $dbConnection = DatabaseConnection::getInstance();
    
        $sql = "SELECT * FROM admin a
                JOIN user u ON a.userID = u.userID
                WHERE a.userID = ?";
        $params = [$userID];
    
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0];

            $location = json_decode($row['locationList'], true);
    
            if (
                isset($row['userID'], $row['username'], $row['firstName'], $row['lastName'], $row['email'], $row['password'], $location, $row['phoneNumber'], $row['donation_manager'])
            ) {

                // string $username,
                // string $firstname,
                // string $lastname,
                // string $email,
                // string $password,
                // array $location,
                // int $phoneNumber,
                // float $goalAmount = 0.0,
                // int $userID=0
                $goalAmount = (float)$row['goalAmount'];
                $admin = new Admin(
                    $row['username'],
                    $row['firstName'],
                    $row['lastName'],
                    $row['email'],
                    $row['password'],
                    $location,
                    $row['phoneNumber'],
                    $goalAmount,
                    $row['userID']
                );
    
                return $admin;
            } else {
                throw new Exception("Missing required fields in the query result.");
            }
        }
    
        return null; // Return null if no record is found
    }

    public static function update($admin): bool {
        if (!$admin instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update the user table
            $userSql = "UPDATE user SET 
                        username = ?, 
                        firstName = ?, 
                        lastName = ?, 
                        email = ?, 
                        password = ?, 
                        locationList = ?, 
                        phoneNumber = ? 
                    WHERE userID = ?";
    
            $userParams = [
                $admin->getUsername(),
                $admin->getFirstname(),
                $admin->getLastname(),
                $admin->getEmail(),
                password_hash($admin->getPassword(), PASSWORD_DEFAULT), // Hash the password
                json_encode($admin->getLocation()), // Encode locationList as JSON
                $admin->getPhoneNumber(),
                $admin->getUserID()
            ];   
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to update user record.");
            }
    
           
            $adminSql = "UPDATE admin SET 
                        donation_manager = ? 
                    WHERE userID = ?";
    
            $adminParams = [
                json_encode($admin->getDonationManager()), 
                $admin->getUserID()
            ];
            if (!$dbConnection->execute($adminSql, $adminParams)) {
                throw new Exception("Failed to update admin record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating admin: " . $e->getMessage());
            return false;
        }
    }
 
    public static function delete($userID): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $adminSql = "DELETE FROM admin WHERE userID = ?";
            $adminParams = [$userID];
    
            if (!$dbConnection->execute($adminSql, $adminParams)) {
                throw new Exception("Failed to delete admin record.");
            }
    
            $userSql = "DELETE FROM user WHERE userID = ?";
            $userParams = [$userID];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to delete user record.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error deleting admin: " . $e->getMessage());
            return false;
        }
    }

    public function getDonationManager(): DonationManager{
        return $this->donationManager;
    }

    public function setDonationManager(DonationManager $donationManager): void {
        $this->donationManager = $donationManager;
    }

    public function manageUsers(int $userID): void {
        $user = $this->getUserByID($userID); 
        if ($user) {
            $user->update($user);
        } else {
            throw new Exception("User not found.");
        }
    }
    
    public function getUserByID(int $userID): ?UserModel {
        return UserModel::retrieve($userID);
    }

    public function generateReports(): string {
        $totalDonations = $this->donationManager->calculateTotalDonations();
        return "Report generated. Total Donations: $totalDonations";
    }
    public function viewDonationStatistics(): string {
        $statistics = [];
        foreach ($this->donationManager->generateDonationReport() as $donation) {
            $statistics[] = $this->donationManager->getDonationStatistics($donation);
        }
        return "Donation statistics:\n" . implode("\n", $statistics);
    }
}

?>
