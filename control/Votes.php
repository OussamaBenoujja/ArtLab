<?php
// Votes.php
require_once "Database.php";

class Votes {
    private $conn;
    private $table_name = "Votes";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a vote
    public function addVote($userID, $articleID, $voteType) {
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

    // Remove a vote
    public function removeVote($userID, $articleID) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE UserID = :userID AND ArticleID = :articleID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':articleID', $articleID);

        return $stmt->execute();
    }

    // Count upvotes and downvotes for an article
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

    // Check if a user has voted on a specific article
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
