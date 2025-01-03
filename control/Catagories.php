<?php
require_once "Database.php";

class Categories {
    private $conn;
    private $table_name = "Categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a new category
    public function addCategory($categoryName) {
        $query = "INSERT INTO " . $this->table_name . " (CategoryName) VALUES (:categoryName)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryName', $categoryName);
        return $stmt->execute();
    }

    // Delete a category by ID
    public function deleteCategory($categoryID) {
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