<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../control/Database.php";
require_once "../control/Articles.php";
require_once "../control/Catagories.php";
require_once "../control/Tags.php";

session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['UserType'] !== 'Author') {
//     header('Location: login.php');
//     exit();
// }

$db = new Database();
$conn = $db->getConnection();
$articles = new Articles($conn);
$categories = new Categories($conn);
$tags = new Tags($conn);

$availableCategories = $categories->getAllCategories();
$availableTags = $tags->getAllTags();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Validate inputs
        if (empty($_POST['title']) || empty($_POST['InnerText']) || empty($_POST['category'])) {
            throw new Exception('All fields are required');
        }

        // Validate file upload
        if (!isset($_FILES['bannerImage']) || $_FILES['bannerImage']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Banner image is required');
        }

        // Process file upload
        $uploadDir = '../assets/img/';
        $fileExtension = strtolower(pathinfo($_FILES['bannerImage']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
        }

        // Ensure the upload directory exists and is writable
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Upload directory is not writable');
        }

        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['bannerImage']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload image');
        }

        // Create article
        $articleID = $articles->createArticle(
            $_SESSION['user_id'],
            $uploadPath,
            $_POST['title'],
            $_POST['InnerText'],
            $_POST['category']
        );

        if (!$articleID) {
            throw new Exception('Failed to create article');
        }

        // Debugging: Print the ArticleID
        echo "Created ArticleID: " . $articleID;

        // Add tags to the article (if tags are selected)
        if (!empty($_POST['tags'])) {
            foreach ($_POST['tags'] as $tagID) {
                $articles->addTag($tagID, $articleID); // Correct order: $tagID first, then $articleID
            }
        }

        $response = ['success' => true, 'message' => 'Article created successfully'];

    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }

    // Return JSON response for AJAX requests
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
    <title>Create Article</title>
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
            <textarea name="InnerText" class="article-content w-full border rounded p-2 focus:outline-none focus:ring focus:ring-blue-200" placeholder="Write your article here..." required></textarea>
        </div>
        <div class="right-column w-full md:w-1/3 bg-gray-300 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Other Details</h2>
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Article Title</label>
                <input type="text" id="title" name="title" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" placeholder="Enter the title of your article" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
                <select id="category" name="category" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" required>
                    <option value="">Select a category</option>
                    <?php foreach ($availableCategories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['CategoryID']); ?>">
                            <?php echo htmlspecialchars($category['CategoryName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-medium mb-2">Tags</label>
                <select id="tags" name="tags[]" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" multiple>
                    <?php foreach ($availableTags as $tag): ?>
                        <option value="<?php echo htmlspecialchars($tag['TagID']); ?>">
                            <?php echo htmlspecialchars($tag['TagName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="bannerImage" class="block text-gray-700 font-medium mb-2">Upload Banner Image</label>
                <input type="file" id="bannerImage" name="bannerImage" accept="image/*" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Article</button>
        </div>
    </form>

    <footer class="bg-white p-4 shadow-md mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-600">Copyright &copy; <?php echo date('Y'); ?> Article Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>