<?php
require_once 'db_connecttion.php';
require_once 'UserModel.php';

$config = require 'configurations.php';
$dbConnection = new DatabaseConnection($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);

// Creating a new user
$user = new UserModel(
    "johndoe",
    "John",
    "Doe",
    1,
    "johndoe@example.com",
    "secure_password",
    ["New York"],
    1234567890,
    $dbConnection
);

if (UserModel::create($user)) {
    echo "User created successfully.<br>";
} else {
    echo "Failed to create user.<br>";
}

// Retrieving user by ID
$retrievedUser = UserModel::retrieve(1);
if ($retrievedUser) {
    echo "User found: " . $retrievedUser->getFullName() . "<br>";
} else {
    echo "User not found.<br>";
}

// Updating user details
$user->username = "john_doe_updated";
if (UserModel::update($user)) {
    echo "User updated successfully.<br>";
}

// Deleting user
if (UserModel::delete(1)) {
    echo "User deleted successfully.<br>";
}

?>