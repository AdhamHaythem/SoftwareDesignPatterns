<?php
require_once 'DatabaseConnection.php';

class UserModel{
    // Properties
    private string $username;
    private string $firstname;
    private string $lastname;
    private int $userID;
    private string $email;
    private string $password;
    private array $location;
    private int $phoneNumber;
    private DatabaseConnection $dbConnection;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
    ) {
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->userID = $userID;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
    }

    public static function create(UserModel $user): bool {
        $sql = "INSERT INTO users (username, firstname, lastname, userID, email, password, phoneNumber)
                VALUES (:username, :firstname, :lastname, :userID, :email, :password, :phoneNumber)";
        
        $params = [
            ':username' => $user->username,
            ':firstname' => $user->firstname,
            ':lastname' => $user->lastname,
            ':userID' => $user->userID,
            ':email' => $user->email,
            ':password' => password_hash($user->password, PASSWORD_DEFAULT), // Password hashing for security
            ':phoneNumber' => $user->phoneNumber
        ];

        return $user->dbConnection->execute($sql, $params); // Use $user->dbConnection
    }

    // Retrieve user information from the database by userID
    public static function retrieve(int $userID, DatabaseConnection $dbConnection): ?UserModel {
        $sql = "SELECT * FROM users WHERE userID = :userID";
        $params = [':userID' => $userID];
        
        $result = $dbConnection->query($sql, $params); // Use $dbConnection
        if ($result) {
            return new UserModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['password'],
                [], // Populate with actual location data if needed
                $result['phoneNumber'],
                $dbConnection // Pass dbConnection to the model
            );
        }
        return null;
    }

    public static function update(UserModel $user): bool {
        $sql = "UPDATE users SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    phoneNumber = :phoneNumber 
                WHERE userID = :userID";
        
        $params = [
            ':username' => $user->username,
            ':firstname' => $user->firstname,
            ':lastname' => $user->lastname,
            ':email' => $user->email,
            ':password' => password_hash($user->password, PASSWORD_DEFAULT),
            ':phoneNumber' => $user->phoneNumber,
            ':userID' => $user->userID
        ];

        return $user->dbConnection->execute($sql, $params); 
    }

    public static function delete(int $userID, DatabaseConnection $dbConnection): bool {
        $sql = "DELETE FROM users WHERE userID = :userID";
        $params = [':userID' => $userID];

        return $dbConnection->execute($sql, $params); // Use $dbConnection
    }

    public function getFullName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getContactInfo(): string {
        return $this->email . ', ' . $this->phoneNumber;
    }

    public function isActive(): bool {
        return true;
    }

    public function changePassword(string $oldPassword, string $newPassword): bool {
        if (password_verify($oldPassword, $this->password)) { // Verifying the old password
            $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password WHERE userID = :userID";
            $params = [
                ':password' => $this->password,
                ':userID' => $this->userID
            ];
            return $this->dbConnection->execute($sql, $params); // Use $this->dbConnection
        }
        return false; // Return false if old password does not match
    }
}

?>
