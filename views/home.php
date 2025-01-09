<?php
session_start();
require_once "../control/Database.php"; 
require_once "../control/Articles.php"; 
require_once "../control/Catagories.php";

$db = new Database(); 
$articles = new Articles($db->getConnection()); 
$categories = new Categories($db->getConnection());

$allCategories = $categories->getAllCategories();

$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';
$categoryID = isset($_GET['category']) ? $_GET['category'] : null;

if ($categoryID) {
    $allArticles = $articles->getArticlesByCategory($categoryID);
} elseif (!empty($searchTerm)) {
    $allArticles = $articles->searchArticles($searchTerm);
} else {
    $allArticles = $articles->getAllArticles();
}

$articlesPerPage = 9;
$totalArticles = count($allArticles);
$totalPages = ceil($totalArticles / $articlesPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));
$offset = ($currentPage - 1) * $articlesPerPage;
$currentArticles = array_slice($allArticles, $offset, $articlesPerPage);

// Check if the request is AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjax) {
    // Output only the articles grid for AJAX requests
    foreach ($currentArticles as $article): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="<?= htmlspecialchars($article['BannerImage']) ?>" alt="Article Image" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <a href="article.php?articleID=<?= htmlspecialchars($article['ArticleID']) ?>" class="hover:underline">
                        <?= htmlspecialchars($article['Title']) ?>
                    </a>
                </h2>
                <p class="text-gray-600 mt-2 article-text"><?= htmlspecialchars($article['InnerText']) ?></p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-gray-500 text-sm">By <?= htmlspecialchars($article['AuthorName']) ?></span>
                    <span class="text-gray-500 text-sm"><?= htmlspecialchars(substr($article['ArticleCreatedAt'], 0, 10)) ?></span>
                </div>
            </div>
        </div>
    <?php endforeach;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">

<header class="bg-white shadow-md p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="home.php" class="text-gray-600 hover:text-blue-600">Home</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="profile.php" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                    <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                        <li><a href="dashboard.php" class="text-gray-600 hover:text-blue-600">Dashboard</a></li>
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

<div class="max-w-7xl mx-auto p-6">
    <!-- Search Form -->
    <form id="searchForm" class="mb-6">
        <label for="searchTerm" class="block text-sm font-medium text-gray-700">Search Articles</label>
        <input type="text" id="searchTerm" name="searchTerm" placeholder="Search by title or author name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= htmlspecialchars($searchTerm) ?>">
    </form>

    <!-- Category Filter Form -->
    <form id="categoryFilterForm" class="mb-6">
        <label for="category" class="block text-sm font-medium text-gray-700">Filter by Category</label>
        <select id="category" name="category" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            <option value="">All Categories</option>
            <?php foreach ($allCategories as $category): ?>
                <option value="<?= htmlspecialchars($category['CategoryID']) ?>" <?= ($category['CategoryID'] == $categoryID) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['CategoryName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<main class="max-w-7xl mx-auto p-6">
    <div id="articlesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($currentArticles as $article): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="<?= htmlspecialchars($article['BannerImage']) ?>" alt="Article Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <a href="article.php?articleID=<?= htmlspecialchars($article['ArticleID']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['Title']) ?>
                        </a>
                    </h2>
                    <p class="text-gray-600 mt-2 article-text"><?= htmlspecialchars($article['InnerText']) ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">By <?= htmlspecialchars($article['AuthorName']) ?></span>
                        <span class="text-gray-500 text-sm"><?= htmlspecialchars(substr($article['ArticleCreatedAt'], 0, 10)) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalArticles > $articlesPerPage): ?>
        <div id="pagination" class="flex justify-center mt-6">
            <button id="prevPage" data-page="<?= $currentPage - 1 ?>" class="px-4 py-2 border rounded <?= ($currentPage == 1 ? 'opacity-50 cursor-not-allowed' : '') ?>">Previous</button>
            <span class="mx-2">Page <?= $currentPage ?> of <?= $totalPages ?></span>
            <button id="nextPage" data-page="<?= $currentPage + 1 ?>" class="px-4 py-2 border rounded <?= ($currentPage == $totalPages ? 'opacity-50 cursor-not-allowed' : '') ?>">Next</button>
        </div>
    <?php endif; ?>
</main>

<footer class="bg-white p-4 shadow-md mt-6">
    <div class="max-w-7xl mx-auto text-center">
        <p class="text-gray-600">© 2023 Article Hub. All rights reserved.</p>
    </div>
</footer>

<script>
$(document).ready(function() {
    function fetchArticles(searchTerm, categoryID) {
        $('#articlesGrid').html('<div class="text-center"><img src="loading.gif" alt="Loading..."></div>'); // Loading spinner

        $.ajax({
            url: 'home.php',
            type: 'GET',
            data: {
                searchTerm: searchTerm,
                category: categoryID
            },
            success: function(response) {
                $('#articlesGrid').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + error);
                $('#articlesGrid').html('<p class="text-center text-red-500">An error occurred while fetching articles.</p>');
            }
        });
    }

    // Fetch articles on page load
    var initialSearchTerm = '<?php echo htmlspecialchars($searchTerm); ?>';
    var initialCategoryID = '<?php echo ($categoryID !== null ? $categoryID : ''); ?>';
    fetchArticles(initialSearchTerm, initialCategoryID);

    // Fetch articles on search input change
    $('#searchTerm').on('input', function() {
        var searchTerm = $(this).val();
        var categoryID = $('#category').val();
        fetchArticles(searchTerm, categoryID);
    });

    // Fetch articles on category change
    $('#category').on('change', function() {
        var searchTerm = $('#searchTerm').val();
        var categoryID = $(this).val();
        fetchArticles(searchTerm, categoryID);
    });
});
</script>

</body>
</html>