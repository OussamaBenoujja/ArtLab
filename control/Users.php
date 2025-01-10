<?php

require_once "Database.php";

class Users {
    private $conn;
    private $table_name = "Users";

    // User properties
    private $userID;
    private $username;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $userType;
    private $birthday;
    private $bio;
    private $isBanned;
    private $profileImage;

    public function __construct($db, $userID = null, $username = null, $firstName = null, $lastName = null, $email = null, $password = null, $userType = null, $birthday = null, $bio = null, $isBanned = 'no', $profileImage = null) {
        $this->conn = $db;
        $this->userID = $userID;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
        $this->birthday = $birthday;
        $this->bio = $bio;
        $this->isBanned = $isBanned;
        $this->profileImage = $profileImage;
    }

    // Getters and Setters
    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUserType() {
        return $this->userType;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    public function getIsBanned() {
        return $this->isBanned;
    }

    public function setIsBanned($isBanned) {
        $this->isBanned = $isBanned;
    }

    public function getProfileImage() {
        return $this->profileImage;
    }

    public function setProfileImage($profileImage) {
        $this->profileImage = $profileImage;
    }

    // Create a new user
    public function createUser() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Username, FirstName, LastName, Email, Password, UserType, Birthday, Bio, IsBanned, ProfileImage) 
                  VALUES (:username, :firstName, :lastName, :email, :password, :userType, :birthday, :bio, 'no', :pfp)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));
        $stmt->bindParam(':userType', $this->userType);
        $stmt->bindParam(':birthday', $this->birthday);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':pfp', $this->profileImage);
        
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

    public function banUser($userID) {
        $query = "UPDATE users SET IsBanned = 'yes' WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        if ($stmt->execute()) {
            // Ban all articles by this author
            $articles = new Articles($this->conn);
            $articles->banArticlesByAuthor($userID);
            return true;
        }
        return false;
    }

    public function unbanUser($userID) {
        $query = "UPDATE users SET IsBanned = 'no' WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        if ($stmt->execute()) {
            // Unban all articles by this author
            $articles = new Articles($this->conn);
            $articles->unbanArticlesByAuthor($userID);
            return true;
        }
        return false;
    }
}