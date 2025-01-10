<?php
require_once "../control/Database.php";
require_once "../control/Articles.php";
require_once "../control/Catagories.php";
require_once "../control/Tags.php";

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user']['UserType'] !== 'Author') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: profile.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$articles = new Articles($conn);
$categories = new Categories($conn);
$tags = new Tags($conn);

$articleID = $_GET['id'];
$article = $articles->getArticleByID($articleID);

if (!$article || $article['AuthorID'] !== $_SESSION['user_id']) {
    header('Location: profile.php');
    exit();
}

$availableCategories = $categories->getAllCategories();
$availableTags = $tags->getAllTags();
$currentTags = $tags->getTagsForArticle($articleID); // Fetch current tags for the article

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Validate inputs
        if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['category'])) {
            throw new Exception('All fields are required');
        }

        $newBannerPath = null;
        
        // Handle new banner image if uploaded
        if (isset($_FILES['bannerImage']) && $_FILES['bannerImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/img/';
            $fileExtension = strtolower(pathinfo($_FILES['bannerImage']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
            }

            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['bannerImage']['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to upload image');
            }

            $newBannerPath = $uploadPath;
        }

        // Update the article
        $result = $articles->updateArticle(
            $articleID,
            $_POST['title'],
            $_POST['content'],
            $newBannerPath
        );

        if ($result) {
            $response = ['success' => true, 'message' => 'Article updated successfully'];
        } else {
            throw new Exception('Failed to update article');
        }

    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Article</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .article-content {
            height: 80vh;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php if (isset($response) && !$response['success']): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?php echo htmlspecialchars($response['message']); ?></span>
    </div>
    <?php endif; ?>

    <form id="articleForm" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row h-screen">
        <div class="left-column w-full md:w-2/3 bg-gray-200 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Article Content</h2>
            <textarea name="content" class="article-content w-full border rounded p-2 focus:outline-none focus:ring focus:ring-blue-200" required><?php echo htmlspecialchars($article['InnerText']); ?></textarea>
        </div>
        <div class="right-column w-full md:w-1/3 bg-gray-300 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Other Details</h2>
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Article Title</label>
                <input type="text" id="title" name="title" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" value="<?php echo htmlspecialchars($article['Title']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
                <select id="category" name="category" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" required>
                    <option value="">Select a category</option>
                    <?php foreach ($availableCategories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['CategoryID']); ?>" <?php echo ($category['CategoryID'] == $article['CategoryID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['CategoryName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-medium mb-2">Tags</label>
                <select id="tags" name="tags[]" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" multiple>
                    <?php foreach ($availableTags as $tag): ?>
                        <option value="<?php echo htmlspecialchars($tag['TagID']); ?>" <?php echo in_array($tag['TagID'], array_column($currentTags, 'TagID')) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tag['TagName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 mb-2">Current Banner Image:</p>
                <img src="<?php echo htmlspecialchars($article['BannerImage']); ?>" alt="Current banner" class="max-w-full h-auto mb-2">
                <label for="bannerImage" class="block text-gray-700 font-medium mb-2">Upload New Banner Image (optional)</label>
                <input type="file" id="bannerImage" name="bannerImage" accept="image/*" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Article</button>
            <a href="profile.php" class="inline-block mt-2 text-blue-600 hover:text-blue-800">Back to Profile</a>
        </div>
    </form>

    <footer class="bg-white p-4 shadow-md mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-600">Copyright &copy; <?php echo date('Y'); ?> Article Hub. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Handle tag updates dynamically
        const tagsSelect = document.getElementById('tags');
        const articleID = <?php echo $articleID; ?>;

        tagsSelect.addEventListener('change', async (e) => {
            const selectedTags = Array.from(tagsSelect.selectedOptions).map(option => option.value);

            try {
                const response = await fetch('update_tags.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        articleID: articleID,
                        tags: selectedTags
                    }),
                });

                const result = await response.json();

                if (!result.success) {
                    alert('Error updating tags: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating tags. Please try again.');
            }
        });
    </script>
</body>
</html>