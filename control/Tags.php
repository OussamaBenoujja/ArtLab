


<?php



class tags{

    private $name;
    private $description;
    private $tagID;
    private $conn;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAlltags(){
        $query = "SELECT * FROM Tags";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagByID($tagID){
        $query = "SELECT * FROM Tags WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagID', $tagID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTag($name, $description){
        $query = "INSERT INTO Tags (Name, Description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function updateTag($tagID, $name = null, $description = null){
        $query = "UPDATE Tags SET Name = :name, Description = :description WHERE TagID = :tagID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':tagID', $tagID);
        return $stmt->execute();
    }
    

}


?>