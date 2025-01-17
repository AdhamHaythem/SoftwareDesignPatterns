<?php
//require_once 'db_connection.php';
require_once 'IMaintainable.php';
require_once 'configurations.php';
//require_once 'data.php';

class UserModel implements IMaintainable {
    private static int $counter=1;
    private string $username;
    private string $firstname;
    private string $lastname;
    private int $userID;
    private string $email;
    private string $password;
    private array $location;
    private int $phoneNumber;
    private static DatabaseConnection $dbConnection;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        int $userID=0
    ) {
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->userID = $userID === 0 ? UserModel::useCounter() : $userID;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
        $this->phoneNumber = $phoneNumber;
    }

    private static function useCounter(): int {
        $ID = self::$counter;
        self::$counter++;
        $db_connection = DatabaseConnection::getInstance();
        $sql = "UPDATE counters SET UserID = ? where CounterID = 1";
        $params = [self::$counter];
        $db_connection->execute($sql, $params);
        return $ID;
    }

    public static function setCounter(int $counter): void {
        self::$counter = $counter;
    }


    // Create a new user in the database
    public static function create($user): bool {
        if (!$user instanceof UserModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Insert or update into `user` table
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                            username = VALUES(username),
                            firstName = VALUES(firstName),
                            lastName = VALUES(lastName),
                            email = VALUES(email),
                            password = VALUES(password),
                            locationList = VALUES(locationList),
                            phoneNumber = VALUES(phoneNumber),
                            isActive = VALUES(isActive)";
    
            $userParams = [
                $user->getUserID(),
                $user->getUsername(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                password_hash($user->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($user->getLocation()), // Serialize location as JSON
                $user->getPhoneNumber(),
                1 // isActive (true)
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert or update into `user` table.");
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    public static function retrieve($userID): ?UserModel {
        $dbConnection = DatabaseConnection::getInstance();
    
        // Query to retrieve user details
        $sql = "SELECT * FROM user WHERE userID = ?";
        $params = [$userID];
    
        $result = $dbConnection->query($sql, $params);
    
        if ($result && !empty($result)) {
            $row = $result[0]; // Assuming only one record is fetched
    
            // Create a new UserModel instance
            return new UserModel(
                $row['username'],                              // username
                $row['firstName'],                             // firstname
                $row['lastName'],                              // lastname
                $row['email'],                                 // email
                $row['password'],                              // password
                json_decode($row['locationList'], true) ?? [], // location (JSON decoded)
                $row['phoneNumber'],                           // phoneNumber
                $row['userID']                                 // userID
            );
        }
    
        return null; // Return null if no result is found
    }
    
    public static function update($user): bool {
        if (!$user instanceof UserModel) {
            throw new InvalidArgumentException("Expected instance of UserModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // Update user details
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
                $user->getUsername(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                password_hash($user->getPassword(), PASSWORD_DEFAULT), // Hash password
                json_encode($user->getLocation()), // Serialize location as JSON
                $user->getPhoneNumber(),
                $user->getUserID()
            ];
    
            return $dbConnection->execute($userSql, $userParams);
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    

    public static function delete($userID): bool {
        $sql = "DELETE FROM user WHERE userID = ?";
        $params = [$userID];

        $dbConnection = DatabaseConnection::getInstance();
        return $dbConnection->execute($sql, $params);
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
