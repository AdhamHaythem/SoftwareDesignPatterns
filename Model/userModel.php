<?php

require_once 'db_connection.php';
require_once 'IMaintainable.php';
require_once 'configurations.php';
require_once 'data.php';

class UserModel implements IMaintainable {
    private string $username;
    private string $firstname;
    private string $lastname;
    private int $userID;
    private string $email;
    private string $password;
    private array $location;
    private int $phoneNumber;
    private static DatabaseConnection $dbConnection;

    // Constructor to initialize the properties
    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber
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

    // Set the database connection
    public static function setDatabaseConnection(DatabaseConnection $dbConnection) {
        self::$dbConnection = $dbConnection;
    }
    public static function getDatabaseConnection() {
       return self::$dbConnection;
    }


    // Create a new user in the database
    public static function create($object): bool {
        if (!$object instanceof UserModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }

        $sql = "INSERT INTO users (username, firstname, lastname, userID, email, password, phoneNumber)
                VALUES (:username, :firstname, :lastname, :userID, :email, :password, :phoneNumber)";
        
        $params = [
            ':username' => $object->username,
            ':firstname' => $object->firstname,
            ':lastname' => $object->lastname,
            ':userID' => $object->userID,
            ':email' => $object->email,
            ':password' => password_hash($object->password, PASSWORD_DEFAULT),
            ':phoneNumber' => $object->phoneNumber
        ];

        return self::$dbConnection->execute($sql, $params);
    }

    public static function retrieve($key): ?UserModel {
        $sql = "SELECT * FROM users WHERE userID = :userID";
        $params = [':userID' => $key];
        
        $result = self::$dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new UserModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['password'],
                [],
                $result['phoneNumber']
            );
        }
        return null;
    }

    public static function update($object): bool {
        if (!$object instanceof UserModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
    
        $sql = "UPDATE users SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    phoneNumber = :phoneNumber 
                WHERE userID = :userID";

        $params = [
            ':username' => $object->username,
            ':firstname' => $object->firstname,
            ':lastname' => $object->lastname,
            ':email' => $object->email,
            ':password' => password_hash($object->password, PASSWORD_DEFAULT),
            ':phoneNumber' => $object->phoneNumber,
            ':userID' => $object->userID
        ];
    
        return self::$dbConnection->execute($sql, $params);
    }

    public static function delete($key): bool {
        $sql = "DELETE FROM users WHERE userID = :userID";
        $params = [':userID' => $key];

        return self::$dbConnection->execute($sql, $params);
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
        if (password_verify($oldPassword, $this->password)) { 
            $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password WHERE userID = :userID";
            $params = [
                ':password' => $this->password,
                ':userID' => $this->userID
            ];
            return self::$dbConnection->execute($sql, $params);
        }
        return false;
    }

        // Getters
        public function getUsername(): string {
            return $this->username;
        }
    
        public function getFirstname(): string {
            return $this->firstname;
        }
    
        public function getLastname(): string {
            return $this->lastname;
        }
    
        public function getUserID(): int {
            return $this->userID;
        }
    
        public function getEmail(): string {
            return $this->email;
        }
    
        public function getPassword(): string {
            return $this->password;
        }
    
        public function getLocation(): array {
            return $this->location;
        }
    
        public function getPhoneNumber(): int {
            return $this->phoneNumber;
        }
    
        // Setters
        public function setUsername(string $username): void {
            $this->username = $username;
        }
    
        public function setFirstname(string $firstname): void {
            $this->firstname = $firstname;
        }
    
        public function setLastname(string $lastname): void {
            $this->lastname = $lastname;
        }
    
        public function setUserID(int $userID): void {
            $this->userID = $userID;
        }
    
        public function setEmail(string $email): void {
            $this->email = $email;
        }
    
        public function setPassword(string $password): void {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
    
        public function setLocation(array $location): void {
            $this->location = $location;
        }
    
        public function setPhoneNumber(int $phoneNumber): void {
            $this->phoneNumber = $phoneNumber;
        }
}

?>
