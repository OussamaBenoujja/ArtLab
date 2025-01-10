<?php
session_start();

require_once "../control/Database.php";
require_once "../control/Tags.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user']['UserType'] !== 'Author') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$articleID = $input['articleID'] ?? null;
$tags = $input['tags'] ?? [];

if (!$articleID) {
    echo json_encode(['success' => false, 'message' => 'Article ID is required']);
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$tagsHandler = new Tags($conn);

try {
    $tagsHandler->removeAllTagsForArticle($articleID);
    foreach ($tags as $tagID) {
        $tagsHandler->addTag($articleID, $tagID);
    }

    echo json_encode(['success' => true, 'message' => 'Tags updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}