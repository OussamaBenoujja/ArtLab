

<?php

if(isset($_POST['articleID'])) {
    require_once "../control/Database.php";
    require_once "../control/Articles.php";

    $db = new Database();
    $articles = new Articles($db->getConnection());

    $articleID = $_POST['articleID'];
    $articles->deleteArticle($articleID);
    echo json_encode(['status' => 'deleted']);
}



?>