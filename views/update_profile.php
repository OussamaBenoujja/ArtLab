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
        // Generate unique filename to prevent overwriting
        $fileType = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileType;
        $targetFilePath = $targetDir . $fileName;
        
        // Allow specific file types
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            // Move the uploaded file
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFilePath)) {
                // Store relative path in database
                $profileImagePath = '../assets/img/' . $fileName;
                
                // Delete old profile image if it exists and isn't the default
                if (isset($user['ProfileImage']) && 
                    $user['ProfileImage'] !== '../assets/img/default.jpg' && 
                    file_exists($user['ProfileImage'])) {
                    unlink($user['ProfileImage']);
                }
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'File upload failed. Please check directory permissions.'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Invalid file type. Allowed types: JPG, JPEG, PNG, GIF'
            ]);
            exit;
        }
    }
    
    // Prepare to update user data
    $users = new Users($pdo);
    
    try {
        $updateSuccess = $users->updateUser($userID, $username, $bio, $profileImagePath);
        
        if ($updateSuccess) {
            // Update session data
            $_SESSION['user']['Username'] = $username;
            if ($profileImagePath) {
                $_SESSION['user']['ProfileImage'] = $profileImagePath;
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Profile updated successfully',
                'newImage' => $profileImagePath ?? $_SESSION['user']['ProfileImage']
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Profile update failed. Please try again.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'User is not logged in'
    ]);
}