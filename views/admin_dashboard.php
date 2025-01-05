<?php
require_once "../control/Database.php";
require_once "../control/Users.php";
require_once "../control/Articles.php";
require_once "../control/Catagories.php"; // Ensure the spelling is correct

$db = new Database();
$users = new Users($db->getConnection());
$articles = new Articles($db->getConnection());
$categories = new Categories($db->getConnection());

$searchResults = [];
$categoryMessage = '';
$allUsers = $users->getAllUsers(); // Fetch all users
$allArticles = $articles->getAllArticles(); // Fetch all articles

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $searchTerm = htmlspecialchars($_POST['search_term']);
        
        // Search members
        $searchResults['members'] = $users->searchUsers($searchTerm);
        
        // Search articles
        $searchResults['articles'] = $articles->searchArticles($searchTerm);
    }

    if (isset($_POST['add_category'])) {
        $newCategoryName = htmlspecialchars($_POST['new_category_name']);
        if ($categories->addCategory($newCategoryName)) {
            $categoryMessage = "Category '$newCategoryName' added successfully.";
        } else {
            $categoryMessage = "Failed to add category.";
        }
    }

    if (isset($_POST['delete_category'])) {
        $categoryId = intval($_POST['category_id']);
        if ($categories->deleteCategory($categoryId)) {
            $categoryMessage = "Category deleted successfully.";
        } else {
            $categoryMessage = "Failed to delete category.";
        }
    }
}

// Fetch categories
$existingCategories = $categories->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-1/4 p-5">
        <h2 class="text-xl font-bold mb-5">Admin Dashboard</h2>
        <ul>
            <li><a href="#members" class="side-link block py-2 hover:bg-gray-700 rounded">Members</a></li>
            <li><a href="#authors" class="side-link block py-2 hover:bg-gray-700 rounded">Authors</a></li>
            <li><a href="#articles" class="side-link block py-2 hover:bg-gray-700 rounded">Articles</a></li>
            <li><a href="#categories" class="side-link block py-2 hover:bg-gray-700 rounded">Categories</a></li>
            <li><a href="logout.php" class="side-link block py-2 hover:bg-gray-700 rounded">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">

        <!-- Search Bar -->
        <form method="POST" class="mb-4">
            <input type="text" name="search_term" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" placeholder="Search for a member or article...">
            <button type="submit" name="search" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 mt-2">Search</button>
        </form>

        <!-- Display Search Results -->
        <?php if (!empty($searchResults)): ?>
            <div>
                <h3 class="font-bold mb-2">Search Results:</h3>
                <div>
                    <h4>Members:</h4>
                    <ul>
                        <?php foreach ($searchResults['members'] as $member): ?>
                            <li><?php echo htmlspecialchars($member['Username']); ?> (<?php echo htmlspecialchars($member['Email']); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                    <h4>Articles:</h4>
                    <ul>
                        <?php foreach ($searchResults['articles'] as $article): ?>
                            <li><?php echo htmlspecialchars($article['Title']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- If no search results, show default lists -->
            <div>
                <h3 class="font-bold mb-2">All Members:</h3>
                <ul>
                    <?php foreach ($allUsers as $user): ?>
                        <li><?php echo htmlspecialchars($user['Username']); ?> (<?php echo htmlspecialchars($user['Email']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
                <h3 class="font-bold mb-2">All Articles:</h3>
                <ul>
                    <?php foreach ($allArticles as $article): ?>
                        <li><?php echo htmlspecialchars($article['Title']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Categories Section -->
        <div id="categories" class="dashboard-section hidden">
            <h2 class="text-2xl font-bold mb-4">Categories</h2>
            <p class="text-green-600"><?php echo $categoryMessage; ?></p>
            <table class="min-w-full bg-white rounded shadow mb-4">
                <thead>
                <tr class="w-full bg-blue-600 text-white">
                    <th class="py-2">Category Name</th>
                    <th class="py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($existingCategories as $category): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($category['CategoryName']); ?></td>
                        <td class="border px-4 py-2">
                            <form method="POST" class="inline">
                                <input type="hidden" name="category_id" value="<?php echo $category['CategoryID']; ?>">
                                <button type="submit" name="delete_category" class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <form method="POST">
                <input type="text" name="new_category_name" class="border rounded p-2 w-1/3 mb-4" placeholder="New Category Name" required>
                <button type="submit" name="add_category" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Add Category</button>
            </form>
        </div>

        <!-- Other sections would follow (Members, Authors, Articles) -->

    </div>
</div>

<script>
    const links = document.querySelectorAll('.side-link');
    const sections = document.querySelectorAll('.dashboard-section');

    sections.forEach(section => section.classList.add('hidden'));

    function showSection(event) {
        sections.forEach(section => section.classList.add('hidden')); // Hide all
        const targetId = event.currentTarget.getAttribute('href').substring(1); // Get the ID from the href
        const targetSection = document.getElementById(targetId); // Find the target section by ID
        if (targetSection) {
            targetSection.classList.remove('hidden'); // Show the target section
        }
    }

    links.forEach(link => {
        link.addEventListener('click', showSection);
    });
</script>

</body>
</html>