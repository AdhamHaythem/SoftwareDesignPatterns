<?php

require_once 'userModel.php';
require_once 'DonationManager.php';

class Admin extends UserModel {
    private DonationManager $donationManager;

    public function __construct(
        int $userID,
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        float $goalAmount = 0.0
    ) {
        parent::__construct($username, $firstname, $lastname, $userID, $email, $password, $location, $phoneNumber);
        $this->donationManager = new DonationManager($goalAmount, [], []);
        DonationManager :: create($this->donationManager);
    }

    // CRUD Methods
    public static function create($adminObject): bool {
        if (!$adminObject instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }
    
        $dbConnection = UserModel::getDatabaseConnection();
    
        try {
            $userSql = "INSERT IGNORE INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $userParams = [
                $adminObject->getUserID(),
                $adminObject->getUsername(),
                $adminObject->getFirstname(),
                $adminObject->getLastname(),
                $adminObject->getEmail(),
                password_hash($adminObject->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($adminObject->getLocation()),
                $adminObject->getPhoneNumber(),
                1 // Assuming new users are active by default
            ];
    
            $userInserted = $dbConnection->execute($userSql, $userParams);
    
            $adminSql = "INSERT INTO admin (userID, donation_manager)
                         VALUES (? , ?)
                         ON DUPLICATE KEY UPDATE 
                         donation_manager = VALUES(donation_manager)";
    
            $adminParams = [
                $adminObject->getUserID(),
            json_encode($adminObject->donationManager) // Serialize donation_manager as JSON
            ];
    
            $adminInserted = $dbConnection->execute($adminSql, $adminParams);
            return $userInserted && $adminInserted;
        } catch (Exception $e) {
            error_log("Error creating admin: " . $e->getMessage());
            return false;
        }
    }

    public static function retrieve($userID): ?Admin {
        $sql = "SELECT * FROM admin WHERE userID = :userID";
        $params = [$userID];

        $dbConnection = UserModel::getDatabaseConnection();
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
    
        $dbConnection = UserModel::getDatabaseConnection();
    
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
    

    public static function delete($userID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
    
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
        echo "Managing user with ID: $userID\n";
        $user = $this->getUserByID($userID); 
        if ($user) {
            $user->update($user);
        } else {
            echo "User with ID $userID not found.\n";
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
