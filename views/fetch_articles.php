<?php
session_start();
require_once "../control/Database.php";
require_once "../control/Articles.php";

$db = new Database();
$articles = new Articles($db->getConnection());

$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Fetch articles based on search term and category
if (!empty($searchTerm)) {
    $allArticles = $articles->searchArticles($searchTerm);
} elseif ($selectedCategory) {
    $allArticles = $articles->getArticlesByCategory($selectedCategory);
} else {
    $allArticles = $articles->getAllArticles();
}

// Pagination logic
$totalArticles = count($allArticles);
$articlesPerPage = 9;
$totalPages = ceil($totalArticles / $articlesPerPage);
$page = max(1, min($totalPages, $page));
$offset = ($page - 1) * $articlesPerPage;
$currentArticles = array_slice($allArticles, $offset, $articlesPerPage);

// Generate articles grid HTML
$articlesHTML = '';
foreach ($currentArticles as $article) {
    $articlesHTML .= '
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="' . htmlspecialchars($article['BannerImage']) . '" alt="Article Image" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <a href="article.php?articleID=' . htmlspecialchars($article['ArticleID']) . '" class="hover:underline">
                        ' . htmlspecialchars($article['Title']) . '
                    </a>
                </h2>
                <p class="text-gray-600 mt-2 article-text">' . htmlspecialchars($article['InnerText']) . '</p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-gray-500 text-sm">By ' . htmlspecialchars($article['AuthorName']) . ' (@' . htmlspecialchars($article['AuthorUsername']) . ')</span>
                    <span class="text-gray-500 text-sm">' . htmlspecialchars(substr($article['ArticleCreatedAt'], 0, 10)) . '</span>
                </div>
            </div>
        </div>
    ';
}

// Generate pagination HTML
$paginationHTML = '';
if ($totalArticles > $articlesPerPage) {
    $paginationHTML .= '<div id="pagination" class="flex justify-center mt-6">';
    if ($page > 1) {
        $paginationHTML .= '<button id="prevPage" data-page="' . ($page - 1) . '" class="px-4 py-2 border rounded">Previous</button>';
    }
    $paginationHTML .= '<span class="mx-2">Page ' . $page . ' of ' . $totalPages . '</span>';
    if ($page < $totalPages) {
        $paginationHTML .= '<button id="nextPage" data-page="' . ($page + 1) . '" class="px-4 py-2 border rounded">Next</button>';
    }
    $paginationHTML .= '</div>';
}

// Return JSON response
echo json_encode([
    'articles' => $articlesHTML,
    'pagination' => $paginationHTML
]);
?>