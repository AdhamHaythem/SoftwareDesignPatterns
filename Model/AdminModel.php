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
        DonationManager :: create($this->donationManager);
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
        $sql = "SELECT * FROM admin WHERE userID = :userID";
        $params = [$userID];

        $dbConnection = DatabaseConnection::getInstance();        
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new Admin(
                $result['userID'],
                $result['username'],
                $result['firstName'],
                $result['lastName'],
                $result['email'],
                $result['password'],
                json_decode($result['locationList'], true), 
                $result['phoneNumber'],
                json_decode($result['donation_manager'], true)
            );
        }
        return null;
    }

    public static function update($adminObject): bool {
        if (!$adminObject instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }
    
        $dbConnection = DatabaseConnection::getInstance();    
        try {
            $userSql = "UPDATE user SET 
                            username = :username, 
                            firstName = :firstName, 
                            lastName = :lastName, 
                            email = :email, 
                            password = :password, 
                            locationList = :locationList, 
                            phoneNumber = :phoneNumber 
                        WHERE userID = :userID";
    
            $userParams = [
                $adminObject->getUsername(),
                $adminObject->getFirstname(),
                $adminObject->getLastname(),
                $adminObject->getEmail(),
                password_hash($adminObject->getPassword(), PASSWORD_DEFAULT),
                json_encode($adminObject->getLocation()), // Serialize locationList as JSON
                $adminObject->getPhoneNumber(),
                $adminObject->getUserID()
            ];
    
            $userUpdated = $dbConnection->execute($userSql, $userParams);
            $adminSql = "UPDATE admin SET 
                            donation_manager = :donation_manager
                         WHERE userID = :userID";
    
            $adminParams = [
                json_encode($adminObject->getDonationManager()), // Serialize donation_manager as JSON
                $adminObject->getUserID()
            ];
    
            $adminUpdated = $dbConnection->execute($adminSql, $adminParams);
    
            return $userUpdated && $adminUpdated;
        } catch (Exception $e) {
            error_log("Error updating admin: " . $e->getMessage());
            return false;
        }
    }
    
    public function getDonationManager(): DonationManager{
        return $this->donationManager;
    }
    public static function delete($userID): bool {
        $dbConnection = DatabaseConnection::getInstance();    
        try {
            $adminSql = "DELETE FROM admin WHERE userID = :userID";
            $adminParams = [$userID];
            $adminDeleted = $dbConnection->execute($adminSql, $adminParams);
    
            $userSql = "DELETE FROM user WHERE userID = :userID";
            $userParams = [$userID];
            $userDeleted = $dbConnection->execute($userSql, $userParams);
    
            return $adminDeleted && $userDeleted;
        } catch (Exception $e) {
            error_log("Error deleting admin: " . $e->getMessage());
            return false;
        }
    }
    

    public function manageUsers(int $userID): void {
        // echo "Managing user with ID: $userID\n";
        $user = $this->getUserByID($userID); 
        if ($user) {
            $user->update($user);
        } else {
            // echo "User with ID $userID not found.\n";
        }
    }
    
    public function getUserByID(int $userID): ?UserModel {
        return UserModel::retrieve($userID);
    }

    public function generateReports(): string {
        $totalDonations = $this->donationManager->calculateTotalDonations();
        return "Report generated. Total Donations: $totalDonations";
    }

    public function sendNotification(int $userID): void {
        echo "Notification sent to user with ID: $userID\n";
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
