<?php
session_start();
require_once "../control/Database.php"; 
require_once "../control/Articles.php"; 

$db = new Database(); 
$articles = new Articles($db->getConnection()); 

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;

if ($selectedCategory) {
    $allArticles = $articles->getArticlesByCategory($selectedCategory); 
} else {
    $allArticles = $articles->getAllArticles();
}

$totalArticles = count($allArticles); 

$articlesPerPage = 9;
$totalPages = ceil($totalArticles / $articlesPerPage);
$currentArticlePage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentArticlePage = max(1, min($totalPages, $currentArticlePage)); 
$offset = ($currentArticlePage - 1) * $articlesPerPage;

$currentArticles = array_slice($allArticles, $offset, $articlesPerPage);
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
        <form id="categoryFilterForm" class="mb-6">
            <label for="category" class="block text-sm font-medium text-gray-700">Filter by Category</label>
            <select id="category" name="category" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                <option value="">All Categories</option>
                <?php
                $query = "SELECT * FROM Categories";
                $stmt = $db->getConnection()->prepare($query);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['CategoryID']) ?>" <?= (isset($_GET['category']) && $_GET['category'] == $category['CategoryID']) ? 'selected' : '' ?>>
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
            <button id="prevPage" data-page="<?= $currentArticlePage - 1 ?>" class="px-4 py-2 border rounded <?= ($currentArticlePage == 1 ? 'opacity-50 cursor-not-allowed' : '') ?>">Previous</button>
            <span class="mx-2">Page <?= $currentArticlePage ?> of <?= $totalPages ?></span>
            <button id="nextPage" data-page="<?= $currentArticlePage + 1 ?>" class="px-4 py-2 border rounded <?= ($currentArticlePage == $totalPages ? 'opacity-50 cursor-not-allowed' : '') ?>">Next</button>
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
            $('.article-text').each(function() {
                const originalText = $(this).text();
                if (originalText.length > 50) {
                    const truncatedText = originalText.substring(0, 50) + " ... ";
                    $(this).text(truncatedText);
                    $(this).append('<a href="#" class="text-blue-600 hover:underline see-more">See More</a>');
                    $(this).data('full-text', originalText);
                }
            });

            $(document).on('click', '.see-more', function(e) {
                e.preventDefault();
                const fullText = $(this).parent().data('full-text');
                $(this).parent().text(fullText);
            });

            $('#category').change(function() {
                const categoryID = $(this).val();
                $.ajax({
                    url: 'fetch_articles.php',
                    type: 'GET',
                    data: { category: categoryID },
                    success: function(response) {
                        $('#articlesGrid').html(response);
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching articles.');
                    }
                });
            });

            $('#prevPage, #nextPage').click(function() {
                const page = $(this).data('page');
                const categoryID = $('#category').val();
                $.ajax({
                    url: 'fetch_articles.php',
                    type: 'GET',
                    data: { category: categoryID, page: page },
                    success: function(response) {
                        $('#articlesGrid').html(response);
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching articles.');
                    }
                });
            });
        });
    </script>

</body>
</html>