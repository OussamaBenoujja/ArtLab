<?php
require_once "../control/Database.php"; 
require_once "../control/Articles.php"; 

$db = new Database(); 
$articles = new Articles($db->getConnection()); 


$allArticles = $articles->getAllArticles();
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
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Home</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($currentArticles as $article): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="<?= htmlspecialchars($article['BannerImage']) ?>" alt="Article Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <a href="article.php?articleID=<?= htmlspecialchars($article['ArticleID']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['Title']) ?>
                        </a>
                    </h2>
                    <p class="text-gray-600 mt-2"><?= htmlspecialchars($article['InnerText']) ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">By <?= htmlspecialchars($article['AuthorName']) ?></span>
                        <span class="text-gray-500 text-sm"><?= htmlspecialchars($article['CreatedAt']) ?></span>
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
            $('#prevPage').click(function() {
                const page = $(this).data('page');
                if (page < 1) return;
                window.location.href = '?page=' + page;
            });

            $('#nextPage').click(function() {
                const page = $(this).data('page');
                if (page > <?= $totalPages ?>) return;
                window.location.href = '?page=' + page;
            });
        });
    </script>

</body>
</html>