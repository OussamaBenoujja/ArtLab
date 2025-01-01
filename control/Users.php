<?php

require_once "Database.php";

class Users {
    private $conn;
    private $table_name = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function createUser($username, $firstName, $lastName, $email, $password, $userType, $birthday, $bio = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Username, FirstName, LastName, Email, Password, UserType, Birthday, Bio) 
                  VALUES (:username, :firstName, :lastName, :email, :password, :userType, :birthday, :bio)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(':userType', $userType);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':bio', $bio);

        return $stmt->execute();
    }

    // Get user by ID
    public function getUserByID($userID) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user
    public function updateUser($userID, $bio) {
        $query = "UPDATE " . $this->table_name . " SET Bio = :bio WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':userID', $userID);
        return $stmt->execute();
    }

    // Delete user
    public function deleteUser($userID) {
        $query = "DELETE FROM " . $this->table_name . " WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        return $stmt->execute();
    }
}
