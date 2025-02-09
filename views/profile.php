<?php
session_start();

require_once '../control/Database.php';
require_once '../control/Users.php';
require_once '../control/Articles.php'; 

$database = new Database();
$pdo = $database->getConnection();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; 
    $userID = $user['UserID']; 

    
    $users = new Users($pdo);
    $articles = new Articles($pdo); 

    
    $userDetails = $users->getUserByID($userID);

    if (!$userDetails) {
        echo "User not found.";
        exit; 
    }

    $userArticles = $articles->getArticlesByAuthorID($userID); 
    $likedQuery = "SELECT Articles.ArticleID, Articles.Title 
                   FROM LikedArticles 
                   JOIN Articles ON LikedArticles.ArticleID = Articles.ArticleID 
                   WHERE LikedArticles.UserID = :userID";
    $likedStmt = $pdo->prepare($likedQuery);
    $likedStmt->bindParam(':userID', $userID);
    $likedStmt->execute();
    $likedArticles = $likedStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
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
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
            <nav class="mt-4 md:mt-0">
                <ul class="flex flex-wrap justify-center gap-4">
                    <li><a href="home.php" class="text-gray-600 hover:text-blue-600">Home</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="profile.php" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                        <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                            <li><a href="dashboard.php" class="text-gray-600 hover:text-blue-600">Dashboard</a></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['user']['UserType'] === 'Author'): ?>
                            <li><a href="create_article.php" class="text-gray-600 hover:text-blue-600">Create Article</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php" class="text-gray-600 hover:text-blue-600">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="text-gray-600 hover:text-blue-600">Login</a></li>
                        <li><a href="signup.php" class="text-gray-600 hover:text-blue-600">Signup</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-lg">
            <!-- Profile Section -->
            <div class="flex flex-col items-center mb-4">
                <img src="<?= htmlspecialchars($userDetails['ProfileImage'] ?? 'default.jpg') ?>" 
                     alt="Profile Image" class="w-24 h-24 rounded-full shadow-md mb-4">
                <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($userDetails['Username']) ?></h1>
                <p class="text-gray-600 text-center">Bio: <?= htmlspecialchars($userDetails['Bio']) ?></p>
                <p class="text-gray-500 text-sm">Joined on: <span class="font-medium"><?= htmlspecialchars($userDetails['DateOfJoining']) ?></span></p>
            </div>

            <!-- Edit Profile Button -->
            <button id="editProfile" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mb-6">
                Edit Profile
            </button>

            <!-- My Articles -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">My Articles</h2>
                <ul class="list-disc pl-5">
                    <?php foreach ($userArticles as $article): ?>
                        <li>
                            <a href="article.php?articleID=<?= htmlspecialchars($article['ArticleID']) ?>" 
                               class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($article['Title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Liked Articles -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Liked Articles</h2>
                <ul class="list-disc pl-5">
                    <?php foreach ($likedArticles as $likedArticle): ?>
                        <li>
                            <a href="article.php?articleID=<?= htmlspecialchars($likedArticle['ArticleID']) ?>" 
                               class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($likedArticle['Title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Articles Table for Authors -->
            <?php if ($userDetails['UserType'] === 'Author'): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">My Articles</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Title</th>
                                    <th class="py-2 px-4 border-b">Created At</th>
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userArticles as $article): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($article['Title']) ?></td>
                                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($article['CreatedAt']) ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="update_article.php?id=<?= htmlspecialchars($article['ArticleID']) ?>" 
                                               class="px-2 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                                Update
                                            </a>
                                            <button onclick="deleteArticle(<?= htmlspecialchars($article['ArticleID']) ?>)" 
                                                    class="px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md mx-4">
            <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>
            <form id="editProfileForm">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($userDetails['Username']) ?>" 
                           class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 rounded-md"><?= htmlspecialchars($userDetails['Bio']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="profileImage" class="block text-sm font-medium text-gray-700">Profile Image</label>
                    <input type="file" id="profileImage" name="profileImage" 
                           class="mt-1 block w-full border-gray-300 rounded-md" accept="image/*">
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
                    url: 'update_profile.php', 
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        if (response.success) {
                            location.reload(); 
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });

        function deleteArticle(articleID) {
            if (confirm('Are you sure you want to delete this article?')) {
                $.ajax({
                    url: 'delete_article.php',
                    type: 'POST',
                    data: { articleID: articleID },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'deleted') {
                            alert('Article deleted successfully!');
                            location.reload(); 
                        } else {
                            alert('Failed to delete the article.');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the article.');
                    }
                });
            }
        }
    </script>
</body>
</html>