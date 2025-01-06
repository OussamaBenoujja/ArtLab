<?php
// Authentication.php
require_once "Database.php";

class Authentication {
    private $conn;
    private $table_name = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register($username, $firstName, $lastName, $email, $password, $userType, $birthday, $bio = null, $profileImage = '../assets/img/default.jpg') {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            // Include ProfileImage in the SQL query
            $query = "INSERT INTO " . $this->table_name . " 
                      (Username, FirstName, LastName, Email, Password, UserType, Birthday, Bio, ProfileImage) 
                      VALUES (:username, :firstName, :lastName, :email, :password, :userType, :birthday, :bio, :profileImage)";
    
            $stmt = $this->conn->prepare($query);
    
            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':userType', $userType);
            $stmt->bindParam(':birthday', $birthday);
            $stmt->bindParam(':bio', $bio);
            $stmt->bindParam(':profileImage', $profileImage); // Bind the profile image path
    
            // Execute the query
            if ($stmt->execute()) {
                return true;
            } else {
                // Log the error or handle it appropriately
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Database error: " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            // Log the exception or handle it appropriately
            throw new Exception("Registration failed: " . $e->getMessage());
        }
    }

    // User login
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($user && password_verify($password, $user['Password'])) {
            
            $token = bin2hex(random_bytes(16));
            $this->updateToken($user['UserID'], $token);
            return [
                "success" => true,
                "token" => $token,
                "user" => $user,
                "user_id" => $user['UserID']
            ];
        }

        return ["success" => false, "message" => "Invalid email or password."];
    }

    
    private function updateToken($userID, $token) {
        $query = "UPDATE " . $this->table_name . " 
                  SET token_auth = :token WHERE UserID = :userID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':userID', $userID);

        $stmt->execute();
    }

   
    public function validateToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE token_auth = :token";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    // Logout
    public function logout($userID) {
        $query = "UPDATE " . $this->table_name . " 
                  SET token_auth = NULL WHERE UserID = :userID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':userID', $userID);

        return $stmt->execute();
    }
}
