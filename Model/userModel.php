<?php
require_once 'DatabaseConnection.php';

<<<<<<< HEAD
class User {
    
=======
class UserModel {
    // Properties
>>>>>>> 9a997fcbece7bae91aa3f7a21de3f396b1aad8c0
    private string $username;
    private string $firstname;
    private string $lastname;
    private int $userID;
    private string $email;
    private string $usernameID;
    private string $password;
    private array $location; 
    private int $phoneNumber;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
      //  DatabaseConnection $dbConnection
    ) {
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->userID = $userID;
        $this->email = $email;
        $this->usernameID = $usernameID;
        $this->password = $password;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
       // $this->dbConnection = $dbConnection;
    }

<<<<<<< HEAD
    public function create(object $object): bool {
        return true;
    }

    public function retrieve(string $key): object {
        return new stdClass();
    }

    public function update(string $key): bool {
        return true;
    }

    public function delete(string $key): bool {
        return true;
=======
    // Create a new user in the database
    public static function create(UserModel $user): bool {
        $sql = "INSERT INTO users (username, firstname, lastname, userID, email, usernameID, password, phoneNumber)
                VALUES (:username, :firstname, :lastname, :userID, :email, :usernameID, :password, :phoneNumber)";
        
        $params = [
            ':username' => $user->username,
            ':firstname' => $user->firstname,
            ':lastname' => $user->lastname,
            ':userID' => $user->userID,
            ':email' => $user->email,
            ':usernameID' => $user->usernameID,
            ':password' => password_hash($user->password, PASSWORD_DEFAULT), // Password hashing for security
            ':phoneNumber' => $user->phoneNumber
        ];

        return $user->dbConnection->execute($sql, $params);
    }

    // Retrieve user information from the database by userID
    public static function retrieve(string $userID): ?UserModel {
        $sql = "SELECT * FROM users WHERE userID = :userID";
        $params = [':userID' => $userID];
        
        $result = $dbConnection->query($sql, $params); // Assuming query returns a single row as an associative array
        if ($result) {
            return new UserModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['usernameID'],
                $result['password'],
                [], // Populate with actual location data if needed
                $result['phoneNumber'],
                $dbConnection
            );
        }
        return null; // Return null if user not found
    }

    // Update user information in the database
    public static function update(UserModel $user): bool {
        $sql = "UPDATE users SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    usernameID = :usernameID, 
                    password = :password, 
                    phoneNumber = :phoneNumber 
                WHERE userID = :userID";
        
        $params = [
            ':username' => $user->username,
            ':firstname' => $user->firstname,
            ':lastname' => $user->lastname,
            ':email' => $user->email,
            ':usernameID' => $user->usernameID,
            ':password' => password_hash($user->password, PASSWORD_DEFAULT),
            ':phoneNumber' => $user->phoneNumber,
            ':userID' => $user->userID
        ];

        return $dbConnection->execute($sql, $params);
    }

    // Delete a user from the database by userID
    public static function delete(string $userID): bool {
        $sql = "DELETE FROM users WHERE userID = :userID";
        $params = [':userID' => $userID];

        return $dbConnection->execute($sql, $params);
>>>>>>> 9a997fcbece7bae91aa3f7a21de3f396b1aad8c0
    }

    // Other instance methods
    public function getFullName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getContactInfo(): string {
        return $this->email . ', ' . $this->phoneNumber;
    }

    public function isActive(): bool {
<<<<<<< HEAD

=======
        // Code to check if user is active, e.g., checking a status field in the database
>>>>>>> 9a997fcbece7bae91aa3f7a21de3f396b1aad8c0
        return true;
    }

    public function changePassword(string $oldPassword, string $newPassword): bool {
<<<<<<< HEAD
        if ($this->password === $oldPassword) {
            $this->password = $newPassword;
            return true;
=======
        if (password_verify($oldPassword, $this->password)) { // Verifying the old password
            $this->password = password_hash($newPassword, PASSWORD_DEFAULT); // Hashing the new password
            // Update password in the database
            $sql = "UPDATE users SET password = :password WHERE userID = :userID";
            $params = [
                ':password' => $this->password,
                ':userID' => $this->userID
            ];
            return $this->dbConnection->execute($sql, $params);
>>>>>>> 9a997fcbece7bae91aa3f7a21de3f396b1aad8c0
        }
        return false;
    }
}

?>
