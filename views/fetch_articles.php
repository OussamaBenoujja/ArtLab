<?php
session_start();
require_once "../control/Database.php"; 
require_once "../control/Articles.php"; 

$db = new Database(); 
$articles = new Articles($db->getConnection()); 

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($selectedCategory) {
    $allArticles = $articles->getArticlesByCategory($selectedCategory); 
} else {
    $allArticles = $articles->getAllArticles();
}

$totalArticles = count($allArticles); 
$articlesPerPage = 9;
$totalPages = ceil($totalArticles / $articlesPerPage);
$currentPage = max(1, min($totalPages, $currentPage)); 
$offset = ($currentPage - 1) * $articlesPerPage;

$currentArticles = array_slice($allArticles, $offset, $articlesPerPage);

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
<?php endforeach; ?>