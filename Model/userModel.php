<?php
require_once 'DatabaseConnection.php';
require_once 'IMaintainable.php';

class UserModel implements IMaintainable {
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
        DatabaseConnection $dbConnection
    ) {
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->userID = $userID;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
        $this->dbConnection = $dbConnection;
    }

    public static function create($object): bool {
        if (!$object instanceof UserModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }

        $sql = "INSERT INTO users (username, firstname, lastname, userID, email, password, phoneNumber)
                VALUES (:username, :firstname, :lastname, :userID, :email, :password, :phoneNumber)";
        
        $params = [ //bn-map with actual parameters
            ':username' => $object->username,
            ':firstname' => $object->firstname,
            ':lastname' => $object->lastname,
            ':userID' => $object->userID,
            ':email' => $object->email,
            ':password' => password_hash($object->password, PASSWORD_DEFAULT),
            ':phoneNumber' => $object->phoneNumber
        ];

        return $this->dbConnection->execute($sql, $params);
    }

    public static function retrieve($key): ?UserModel {
        $sql = "SELECT * FROM users WHERE userID = :userID";
        $params = [':userID' => $key];
        
        $result = $this->dbConnection->query($sql, $params);
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
                $this->dbConnection
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

        return $this->dbConnection->execute($sql, $params);
    }

    public static function delete($key): bool {
        $sql = "DELETE FROM users WHERE userID = :userID";
        $params = [':userID' => $key];

        return $this->dbConnection->execute($sql, $params);
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
            return $this->dbConnection->execute($sql, $params);
        }
        return false;
    }
}

?>
