<?php
session_start();

// Include necessary files
require_once '../control/Database.php';
require_once '../control/Users.php';

$database = new Database();
$pdo = $database->getConnection();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; // Accessing user details from session
    $userID = $user['UserID']; // Assuming UserID is part of the user session

    // Create an instance of the Users class
    $users = new Users($pdo);

    // Fetch user details
    $userDetails = $users->getUserByID($userID);

    if (!$userDetails) {
        echo "User not found.";
        exit; // Stop further execution
    }

    // Fetch the articles authored by the user
    $query = "SELECT * FROM Articles WHERE AuthorID = :authorID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':authorID', $userID);
    $stmt->execute();
    $userArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch liked articles
    $likedQuery = "SELECT Articles.ArticleID, Articles.Title 
                   FROM LikedArticles 
                   JOIN Articles ON LikedArticles.ArticleID = Articles.ArticleID 
                   WHERE LikedArticles.UserID = :userID";
    $likedStmt = $pdo->prepare($likedQuery);
    $likedStmt->bindParam(':userID', $userID);
    $likedStmt->execute();
    $likedArticles = $likedStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect to login page if not authenticated
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($userDetails['Username']) ?>'s Profile</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-lg">
        <div class="flex flex-col items-center mb-4">
            <img src="<?= htmlspecialchars($userDetails['ProfileImage'] ?? 'default.jpg') ?>" alt="Profile Image" class="w-24 h-24 rounded-full shadow-md mb-4">
            <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($userDetails['Username']) ?></h1>
            <p class="text-gray-600 text-center">Bio: <?= htmlspecialchars($userDetails['Bio']) ?></p>
            <p class="text-gray-500 text-sm">Joined on: <span class="font-medium"><?= htmlspecialchars($userDetails['DateOfJoining']) ?></span></p>
        </div>

        <button id="editProfile" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Profile</button>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">My Articles</h2>
            <ul class="list-disc pl-5">
                <?php foreach ($userArticles as $article): ?>
                    <li><a href="article.php?id=<?= htmlspecialchars($article['ArticleID']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($article['Title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Liked Articles</h2>
            <ul class="list-disc pl-5">
                <?php foreach ($likedArticles as $likedArticle): ?>
                    <li><a href="article.php?id=<?= htmlspecialchars($likedArticle['ArticleID']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($likedArticle['Title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>
            <form id="editProfileForm">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($userDetails['Username']) ?>" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 rounded-md"><?= htmlspecialchars($userDetails['Bio']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="profileImage" class="block text-sm font-medium text-gray-700">Profile Image</label>
                    <input type="file" id="profileImage" name="profileImage" class="mt-1 block w-full border-gray-300 rounded-md" accept="image/*">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#editProfile').on('click', function() {
                $('#editModal').removeClass('hidden');
            });

            $('#closeModal').on('click', function() {
                $('#editModal').addClass('hidden');
            });

            $('#editProfileForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: 'update_profile.php', // URL to the update script
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        if (response.success) {
                            location.reload(); // Reload to see updated info
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>