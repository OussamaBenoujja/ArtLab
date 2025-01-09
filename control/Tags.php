<?php

class Tags {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTags() {
        $query = "SELECT * FROM Tags";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagByID($tagID) {
        $query = "SELECT * FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTag($name, $description = null) {
        $query = "INSERT INTO Tags (TagName, Description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function updateTag($tagID, $name, $description) {
        $query = "UPDATE Tags SET TagName = :name, Description = :description WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    public function deleteTag($tagID) {
        $query = "DELETE FROM ArticleTags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();

        $query = "DELETE FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }
}