<?php
// Articles.php
require_once "Database.php";

class Articles {
    private $conn;
    private $view_name = "ArticleDetailsView"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create an article
    public function createArticle($authorID, $bannerImage, $title, $innerText) {
        $query = "INSERT INTO Articles 
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
        $query = "SELECT * FROM " . $this->view_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get article by ID
    public function getArticleByID($articleID) {
        $query = "SELECT * FROM " . $this->view_name . " WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update article (only updates the Articles table)
    public function updateArticle($articleID, $title, $innerText, $bannerPath) {
        $query = "UPDATE Articles SET Title = :title, InnerText = :innerText, BannerImage = :imgPath WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':innerText', $innerText);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':imgPath', $bannerPath);
        return $stmt->execute();
    }

    // Delete article (deletes from Articles table)
    public function deleteArticle($articleID) {
        $query = "DELETE FROM Articles WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        return $stmt->execute();
    }

    // Search articles by title or inner text
    public function searchArticles($searchTerm) {
        $query = "SELECT * FROM " . $this->view_name . " 
                  WHERE Title LIKE :term OR InnerText LIKE :term";
        $stmt = $this->conn->prepare($query);
        $term = "%" . $searchTerm . "%"; // Adding wildcards for partial matching
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all matching articles
    }
}