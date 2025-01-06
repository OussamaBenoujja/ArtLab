<?php

require_once "Database.php";

class Users {
    private $conn;
    private $table_name = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function createUser($username, $firstName, $lastName, $email, $password, $userType, $birthday, $bio = null, $pfp) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Username, FirstName, LastName, Email, Password, UserType, Birthday, Bio, IsBanned, ProfileImage) 
                  VALUES (:username, :firstName, :lastName, :email, :password, :userType, :birthday, :bio, 'no', :pfp)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(':userType', $userType);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':pfp', $pfp);
        
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

    // Get all users
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user
    public function updateUser($userID, $username = null, $bio = null, $profileImagePath = null) {
        $query = "UPDATE " . $this->table_name . " SET Bio = :bio" . 
                 ($profileImagePath ? ", ProfileImage = :profileImage" : "") . 
                 " WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bio', $bio);
        if ($profileImagePath) {
            $stmt->bindParam(':profileImage', $profileImagePath);
        }
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

    // Search users by username, first name, or last name
    public function searchUsers($searchTerm) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE Username LIKE :term OR FirstName LIKE :term OR LastName LIKE :term";
        $stmt = $this->conn->prepare($query);
        $term = "%" . $searchTerm . "%"; // Adding wildcards for partial matching
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ban user
    public function banUser($userID) {
        $query = "UPDATE " . $this->table_name . " SET IsBanned = 'yes' WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        return $stmt->execute();
    }

    // Unban user
    public function unbanUser($userID) {
        $query = "UPDATE " . $this->table_name . " SET IsBanned = 'no' WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        return $stmt->execute();
    }
}