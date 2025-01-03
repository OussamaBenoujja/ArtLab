<?php
session_start();

require_once '../control/Database.php';
require_once '../control/Users.php';

$database = new Database();
$pdo = $database->getConnection();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userID = $user['UserID'];

    $username = $_POST['username'];
    $bio = $_POST['bio'];

    // Handle file upload
    $profileImagePath = null;

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $targetDir = '../assets/img/';
        $fileName = basename($_FILES['profileImage']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Allow specific file types (you may customize this)
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowedTypes)) {
            // Move the uploaded file to the designated directory
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFilePath)) {
                $profileImagePath = $targetFilePath;
            } else {
                echo json_encode(['success' => false, 'message' => 'File upload failed']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }
    }

    // Prepare to update user data
    $users = new Users($pdo);
    $updateSuccess = $users->updateUser($userID, $username, $bio, $profileImagePath);

    if ($updateSuccess) {
        // Update session data if username changes
        $_SESSION['user']['Username'] = $username;
        if ($profileImagePath) {
            $_SESSION['user']['ProfileImage'] = $profileImagePath;
        }
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Profile update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
}