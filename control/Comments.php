<?php

// Comments.php
require_once "Database.php";

class Comments {
    private $conn;
    private $table_name = "Comments";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a new comment
    public function addComment($articleID, $userID, $commentText) {
        $query = "INSERT INTO " . $this->table_name . " (ArticleID, UserID, CommentText, CreatedAt) 
                  VALUES (:articleID, :userID, :commentText, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':commentText', $commentText);

        return $stmt->execute();
    }

    // Retrieve all comments for a specific article
    public function getCommentsByArticle($articleID) {
        $query = "SELECT c.CommentID, c.CommentText, c.CreatedAt, u.Username, c.UserID
                  FROM " . $this->table_name . " c 
                  JOIN Users u ON c.UserID = u.UserID 
                  WHERE c.ArticleID = :articleID 
                  ORDER BY c.CreatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all comments by a specific user
    public function getCommentsByUser($userID) {
        $query = "SELECT c.CommentID, c.CommentText, c.CreatedAt, a.Title AS ArticleTitle 
                  FROM " . $this->table_name . " c 
                  JOIN Articles a ON c.ArticleID = a.ArticleID 
                  WHERE c.UserID = :userID 
                  ORDER BY c.CreatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a comment
    public function updateComment($commentID, $userID, $newCommentText) {
        $query = "UPDATE " . $this->table_name . " 
                  SET CommentText = :newCommentText, CreatedAt = NOW() 
                  WHERE CommentID = :commentID AND UserID = :userID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':newCommentText', $newCommentText);
        $stmt->bindParam(':commentID', $commentID);
        $stmt->bindParam(':userID', $userID);

        return $stmt->execute();
    }

    // Delete a comment
    public function deleteComment($commentID, $userID) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE CommentID = :commentID AND UserID = :userID";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':commentID', $commentID);
        $stmt->bindParam(':userID', $userID);

        return $stmt->execute();
    }
}
