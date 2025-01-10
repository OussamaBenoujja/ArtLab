<?php
require_once "Database.php";

class Categories {
    private $conn;
    private $table_name = "Categories";

    // Properties
    private $categoryID;
    private $categoryName;

    public function __construct($db, $categoryID = null, $categoryName = null) {
        $this->conn = $db;
        $this->categoryID = $categoryID;
        $this->categoryName = $categoryName;
    }

    // Getters and Setters
    public function getCategoryID() {
        return $this->categoryID;
    }

    public function setCategoryID($categoryID) {
        $this->categoryID = $categoryID;
    }

    public function getCategoryName() {
        return $this->categoryName;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

    // Add a new category
    public function addCategory($categoryName = null) {
        $categoryName = $categoryName ?? $this->categoryName;

        $query = "INSERT INTO " . $this->table_name . " (CategoryName) VALUES (:categoryName)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryName', $categoryName);
        return $stmt->execute();
    }

    // Delete a category by ID
    public function deleteCategory($categoryID = null) {
        $categoryID = $categoryID ?? $this->categoryID;

        $query = "DELETE FROM " . $this->table_name . " WHERE CategoryID = :categoryID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $categoryID);
        return $stmt->execute();
    }

    // Get all categories
    public function getAllCategories() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}