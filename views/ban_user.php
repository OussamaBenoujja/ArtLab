
<?php
// ban_user.php


require_once "../control/Database.php";
require_once "../control/Users.php";

$db = new Database();
$users = new Users($db->getConnection());

if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $currentUser = $users->getUserByID($userID); 

    if ($currentUser['IsBanned'] === 'yes') {
        // If user is currently banned, we unban them
        $users->unbanUser($userID);
        echo json_encode(['status' => 'unbanned']);
    } else {
        // If user is not banned, we ban them
        $users->banUser($userID);
        echo json_encode(['status' => 'banned']);
    }
}

?>