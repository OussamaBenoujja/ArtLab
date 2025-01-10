<?php
// Votes.php
require_once "Database.php";

class Votes {
    private $conn;
    private $table_name = "Votes";

    // Properties
    private $userID;
    private $articleID;
    private $voteType;

    public function __construct($db, $userID = null, $articleID = null, $voteType = null) {
        $this->conn = $db;
        $this->userID = $userID;
        $this->articleID = $articleID;
        $this->voteType = $voteType;
    }

    // Getters and Setters
    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function getArticleID() {
        return $this->articleID;
    }

    public function setArticleID($articleID) {
        $this->articleID = $articleID;
    }

    public function getVoteType() {
        return $this->voteType;
    }

    public function setVoteType($voteType) {
        $this->voteType = $voteType;
    }

    
    public function addVote($userID = null, $articleID = null, $voteType = null) {
        // Use parameters if provided, otherwise use object properties
        $userID = $userID ?? $this->userID;
        $articleID = $articleID ?? $this->articleID;
        $voteType = $voteType ?? $this->voteType;

        // Validate required fields
        if (empty($userID) || empty($articleID) || empty($voteType)) {
            throw new Exception("Missing required fields: userID, articleID, or voteType.");
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (UserID, ArticleID, VoteType) 
                  VALUES (:userID, :articleID, :voteType)
                  ON DUPLICATE KEY UPDATE VoteType = :voteType";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':voteType', $voteType);

        return $stmt->execute();
    }

    
    public function removeVote($userID = null, $articleID = null) {
        // Use parameters if provided, otherwise use object properties
        $userID = $userID ?? $this->userID;
        $articleID = $articleID ?? $this->articleID;

        // Validate required fields
        if (empty($userID) || empty($articleID)) {
            throw new Exception("Missing required fields: userID or articleID.");
        }

        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE UserID = :userID AND ArticleID = :articleID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':articleID', $articleID);

        return $stmt->execute();
    }

  
    public function countVotes($articleID) {
        $query = "SELECT 
                    SUM(CASE WHEN VoteType = 'Upvote' THEN 1 ELSE 0 END) AS Upvotes,
                    SUM(CASE WHEN VoteType = 'Downvote' THEN 1 ELSE 0 END) AS Downvotes
                  FROM " . $this->table_name . " 
                  WHERE ArticleID = :articleID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':articleID', $articleID);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function hasUserVoted($userID, $articleID) {
        $query = "SELECT VoteType FROM " . $this->table_name . " 
                  WHERE UserID = :userID AND ArticleID = :articleID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':articleID', $articleID);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}