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
    }

    // CRUD Methods similar to UserModel
    
    public static function createAdmin($adminObject): bool {
        if (!$adminObject instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }

        $sql = "INSERT INTO admins (username, firstname, lastname, userID, email, password, phoneNumber)
                VALUES (:username, :firstname, :lastname, :userID, :email, :password, :phoneNumber)";
        
        $params = [
            ':username' => $adminObject->getUsername(),
            ':firstname' => $adminObject->getFirstname(),
            ':lastname' => $adminObject->getLastname(),
            ':userID' => $adminObject->getUserID(),
            ':email' => $adminObject->getEmail(),
            ':password' => password_hash($adminObject->getPassword(), PASSWORD_DEFAULT),
            ':phoneNumber' => $adminObject->getPhoneNumber()
        ];
        
        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function retrieveAdmin($userID): ?Admin {
        $sql = "SELECT * FROM admins WHERE userID = :userID";
        $params = [':userID' => $userID];

        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new Admin(
                $result['userID'],
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['email'],
                $result['password'],
                [],  // Assuming location data will be handled separately
                $result['phoneNumber']
            );
        }
        return null;
    }

    public static function updateAdmin($adminObject): bool {
        if (!$adminObject instanceof Admin) {
            throw new InvalidArgumentException("Expected instance of Admin");
        }
    
        $sql = "UPDATE admins SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    phoneNumber = :phoneNumber 
                WHERE userID = :userID";

        $params = [
            ':username' => $adminObject->getUsername(),
            ':firstname' => $adminObject->getFirstname(),
            ':lastname' => $adminObject->getLastname(),
            ':email' => $adminObject->getEmail(),
            ':password' => password_hash($adminObject->getPassword(), PASSWORD_DEFAULT),
            ':phoneNumber' => $adminObject->getPhoneNumber(),
            ':userID' => $adminObject->getUserID()
        ];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function deleteAdmin($userID): bool {
        $sql = "DELETE FROM admins WHERE userID = :userID";
        $params = [':userID' => $userID];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    // Additional methods for admin-specific functionalities
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
