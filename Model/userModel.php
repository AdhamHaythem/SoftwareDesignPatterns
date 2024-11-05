<?php

class User {
    // Properties
    private string $username;
    private string $firstname;
    private string $lastname;
    private int $userID;
    private string $email;
    private string $usernameID;
    private string $password;
    private array $location; // List of Location objects
    private int $phoneNumber;
    private DatabaseConnection $dbConnection;

    // Constructor
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
        DatabaseConnection $dbConnection
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
        $this->dbConnection = $dbConnection;
    }

    // Methods
    public function create(object $object): bool {
        // Code to create a new user in the database
        return true;
    }

    public function retrieve(string $key): object {
        // Code to retrieve user information from the database
        return new stdClass(); // Placeholder for returned object
    }

    public function update(string $key): bool {
        // Code to update user information in the database
        return true;
    }

    public function delete(string $key): bool {
        // Code to delete a user from the database
        return true;
    }

    public function getFullName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getContactInfo(): string {
        return $this->email . ', ' . $this->phoneNumber;
    }

    public function isActive(): bool {
        // Code to check if user is active
        return true;
    }

    public function changePassword(string $oldPassword, string $newPassword): bool {
        if ($this->password === $oldPassword) {
            $this->password = $newPassword;
            // Code to update password in the database
            return true;
        }
        return false;
    }
}

