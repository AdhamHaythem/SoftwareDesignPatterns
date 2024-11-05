<?php

class User {
    
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
        if ($this->password === $oldPassword) {
            $this->password = $newPassword;
            return true;
        }
        return false;
    }
}

