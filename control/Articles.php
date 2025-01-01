<?php
// Articles.php
require_once "Database.php";

class Articles {
    private $conn;
    private $table_name = "Articles";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create an article
    public function createArticle($authorID, $bannerImage, $title, $innerText) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (AuthorID, BannerImage, Title, InnerText) 
                  VALUES (:authorID, :bannerImage, :title, :innerText)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':authorID', $authorID);
        $stmt->bindParam(':bannerImage', $bannerImage);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':innerText', $innerText);

        return $stmt->execute();
    }

    // Get all articles
    public function getAllArticles() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get article by ID
    public function getArticleByID($articleID) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update article
    public function updateArticle($articleID, $title, $innerText) {
        $query = "UPDATE " . $this->table_name . " SET Title = :title, InnerText = :innerText WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':innerText', $innerText);
        $stmt->bindParam(':articleID', $articleID);
        return $stmt->execute();
    }

    // Delete article
    public function deleteArticle($articleID) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        return $stmt->execute();
    }
}
