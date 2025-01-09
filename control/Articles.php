<?php
require_once "Database.php";

class Articles {
    private $conn;
    private $view_name = "ArticleDetailsView"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createArticle($authorID, $bannerImage, $title, $content, $categoryID) {
        $query = "INSERT INTO Articles (AuthorID, BannerImage, Title, InnerText, CreatedAt, CategoryID) 
                  VALUES (:authorID, :bannerImage, :title, :content, NOW(), :categoryID)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':authorID', $authorID);
        $stmt->bindParam(':bannerImage', $bannerImage);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':categoryID', $categoryID);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        throw new Exception("Failed to create article.");
    }

    // Get all articles
    public function getAllArticles() {
        $query = "
            SELECT 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt AS ArticleCreatedAt,
                users.UserID AS AuthorID,
                CONCAT(users.FirstName, ' ', users.LastName) AS AuthorName,
                users.Email AS AuthorEmail,
                GROUP_CONCAT(DISTINCT votes.VoteType) AS VoteTypes,
                GROUP_CONCAT(DISTINCT comments.CommentText) AS Comments,
                GROUP_CONCAT(DISTINCT categories.CategoryName) AS Categories,
                GROUP_CONCAT(DISTINCT tags.TagName) AS Tags
            FROM 
                articles
                LEFT JOIN users ON articles.AuthorID = users.UserID
                LEFT JOIN votes ON articles.ArticleID = votes.ArticleID
                LEFT JOIN comments ON articles.ArticleID = comments.ArticleID
                LEFT JOIN articlecategories ON articles.ArticleID = articlecategories.ArticleID
                LEFT JOIN categories ON articlecategories.CategoryID = categories.CategoryID
                LEFT JOIN articletags ON articles.ArticleID = articletags.ArticleID
                LEFT JOIN tags ON articletags.TagID = tags.TagID
            GROUP BY 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt,
                users.UserID,
                users.FirstName,
                users.LastName,
                users.Email
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get article by ID
    public function getArticleByID($articleID) {
        $query = "
            SELECT 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt AS ArticleCreatedAt,
                users.UserID AS AuthorID,
                CONCAT(users.FirstName, ' ', users.LastName) AS AuthorName,
                users.Email AS AuthorEmail,
                users.ProfileImage,
                categories.CategoryName,
                GROUP_CONCAT(DISTINCT tags.TagName) AS Tags
            FROM 
                articles
                LEFT JOIN users ON articles.AuthorID = users.UserID
                LEFT JOIN categories ON articles.CategoryID = categories.CategoryID
                LEFT JOIN articletags ON articles.ArticleID = articletags.ArticleID
                LEFT JOIN tags ON articletags.TagID = tags.TagID
            WHERE 
                articles.ArticleID = :articleID
            GROUP BY 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt,
                users.UserID,
                users.FirstName,
                users.LastName,
                users.Email,
                users.ProfileImage,
                categories.CategoryName
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get articles by AuthorID
    public function getArticlesByAuthorID($authorID) {
        $query = "SELECT * FROM Articles WHERE AuthorID = :authorID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':authorID', $authorID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update article
    public function updateArticle($articleID, $title, $innerText, $bannerPath) {
        $query = "UPDATE Articles SET Title = :title, InnerText = :innerText, BannerImage = :imgPath WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':innerText', $innerText);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':imgPath', $bannerPath);
        return $stmt->execute();
    }

    // Delete article
    public function deleteArticle($articleID) {
        // Start a transaction to ensure atomicity
        $this->conn->beginTransaction();

        try {
            // Delete from Votes
            $query = "DELETE FROM Votes WHERE ArticleID = :articleID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':articleID', $articleID);
            $stmt->execute();

            // Delete from Comments
            $query = "DELETE FROM Comments WHERE ArticleID = :articleID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':articleID', $articleID);
            $stmt->execute();

            // Delete from LikedArticles
            $query = "DELETE FROM LikedArticles WHERE ArticleID = :articleID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':articleID', $articleID);
            $stmt->execute();

            // Delete from ArticleTags
            $query = "DELETE FROM ArticleTags WHERE ArticleID = :articleID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':articleID', $articleID);
            $stmt->execute();

            // Finally, delete from Articles
            $query = "DELETE FROM Articles WHERE ArticleID = :articleID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':articleID', $articleID);
            $stmt->execute();

            // Commit the transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->conn->rollBack();
            throw new Exception("Failed to delete article: " . $e->getMessage());
        }
    }

    // Search articles by title or inner text
    public function searchArticles($searchTerm) {
        $query = "SELECT * FROM " . $this->view_name . " 
                  WHERE Title LIKE :term OR InnerText LIKE :term";
        $stmt = $this->conn->prepare($query);
        $term = "%" . $searchTerm . "%"; 
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Get articles by category
    public function getArticlesByCategory($categoryID) {
        $query = "
            SELECT 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt AS ArticleCreatedAt,
                users.UserID AS AuthorID,
                CONCAT(users.FirstName, ' ', users.LastName) AS AuthorName,
                users.Email AS AuthorEmail,
                GROUP_CONCAT(DISTINCT votes.VoteType) AS VoteTypes,
                GROUP_CONCAT(DISTINCT comments.CommentText) AS Comments,
                GROUP_CONCAT(DISTINCT categories.CategoryName) AS Categories,
                GROUP_CONCAT(DISTINCT tags.TagName) AS Tags
            FROM 
                articles
                LEFT JOIN users ON articles.AuthorID = users.UserID
                LEFT JOIN votes ON articles.ArticleID = votes.ArticleID
                LEFT JOIN comments ON articles.ArticleID = comments.ArticleID
                LEFT JOIN articlecategories ON articles.ArticleID = articlecategories.ArticleID
                LEFT JOIN categories ON articlecategories.CategoryID = categories.CategoryID
                LEFT JOIN articletags ON articles.ArticleID = articletags.ArticleID
                LEFT JOIN tags ON articletags.TagID = tags.TagID
            WHERE 
                categories.CategoryID = :categoryID
            GROUP BY 
                articles.ArticleID,
                articles.Title,
                articles.InnerText,
                articles.BannerImage,
                articles.CreatedAt,
                users.UserID,
                users.FirstName,
                users.LastName,
                users.Email
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $categoryID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a tag to an article
    public function addTag($articleID, $tagID) {
        // Check if the ArticleID exists
        $query = "SELECT ArticleID FROM Articles WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            throw new Exception("ArticleID $articleID does not exist.");
        }
    
        // Check if the TagID exists
        $query = "SELECT TagID FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            throw new Exception("TagID $tagID does not exist.");
        }
    
        // Insert into ArticleTags
        $query = "INSERT INTO ArticleTags (ArticleID, TagID) VALUES (:articleID, :tagID)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    // Remove a tag from an article
    public function removeTag($articleID, $tagID) {
        $query = "DELETE FROM ArticleTags WHERE ArticleID = :articleID AND TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }

    // Get all tags for an article
    public function getTagsForArticle($articleID) {
        $query = "SELECT Tags.TagID, Tags.TagName, Tags.Description 
                  FROM Tags 
                  JOIN ArticleTags ON Tags.TagID = ArticleTags.TagID 
                  WHERE ArticleTags.ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function setArticleCategory($articleID, $categoryID) {
        // Check if the ArticleID exists
        $query = "SELECT ArticleID FROM Articles WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            throw new Exception("ArticleID $articleID does not exist.");
        }
    
        // Check if the CategoryID exists
        $query = "SELECT CategoryID FROM Categories WHERE CategoryID = :categoryID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $categoryID);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            throw new Exception("CategoryID $categoryID does not exist.");
        }
    
        // Update the Articles table with the CategoryID
        $query = "UPDATE Articles SET CategoryID = :categoryID WHERE ArticleID = :articleID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $categoryID);
        $stmt->bindParam(':articleID', $articleID);
        return $stmt->execute();
    }
}