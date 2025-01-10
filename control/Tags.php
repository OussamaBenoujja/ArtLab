<?php

class Tags {
    private $conn;

    // Properties
    private $tagID;
    private $name;
    private $description;

    public function __construct($db, $tagID = null, $name = null, $description = null) {
        $this->conn = $db;
        $this->tagID = $tagID;
        $this->name = $name;
        $this->description = $description;
    }

    // Getters and Setters
    public function getTagID() {
        return $this->tagID;
    }

    public function setTagID($tagID) {
        $this->tagID = $tagID;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    // Get all tags
    public function getAllTags() {
        $query = "SELECT * FROM Tags";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a tag by its ID
    public function getTagByID($tagID = null) {
        $tagID = $tagID ?? $this->tagID;

        $query = "SELECT * FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new tag
    public function createTag($name = null, $description = null) {
        $name = $name ?? $this->name;
        $description = $description ?? $this->description;

        $query = "INSERT INTO Tags (TagName, Description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Update an existing tag
    public function updateTag($tagID = null, $name = null, $description = null) {
        $tagID = $tagID ?? $this->tagID;
        $name = $name ?? $this->name;
        $description = $description ?? $this->description;

        $query = "UPDATE Tags SET TagName = :name, Description = :description WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    // Delete a tag
    public function deleteTag($tagID = null) {
        $tagID = $tagID ?? $this->tagID;

        // First, delete all associations in the ArticleTags table
        $query = "DELETE FROM ArticleTags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();

        // Then, delete the tag from the Tags table
        $query = "DELETE FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    // Remove all tags associated with an article
    public function removeAllTagsForArticle($articleID) {
        $query = "DELETE FROM ArticleTags WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        return $stmt->execute();
    }

    // Add a tag to an article
    public function addTag($articleID, $tagID = null) {
        $tagID = $tagID ?? $this->tagID;

        $query = "INSERT INTO ArticleTags (ArticleID, TagID) VALUES (:articleID, :tagID)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    // Get all tags associated with an article
    public function getTagsForArticle($articleID) {
        $query = "SELECT Tags.TagID, Tags.TagName 
                  FROM Tags 
                  JOIN ArticleTags ON Tags.TagID = ArticleTags.TagID 
                  WHERE ArticleTags.ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}