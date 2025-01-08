<?php
require_once "../control/Database.php";
require_once "../control/Users.php";
require_once "../control/Articles.php";
require_once "../control/Catagories.php";

$db = new Database();
$users = new Users($db->getConnection());
$articles = new Articles($db->getConnection());
$categories = new Categories($db->getConnection());

$categoryMessage = '';
$allUsers = $users->getAllUsers(); 
$allArticles = $articles->getAllArticles(); 
$existingCategories = $categories->getAllCategories(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (isset($_POST['delete_article'])) {
        header('Content-Type: application/json');
    
        if (!isset($_POST['article_id']) || !is_numeric($_POST['article_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid article ID.']);
            exit();
        }
    
        $articleId = intval($_POST['article_id']);
    
        if ($articles->deleteArticle($articleId)) {
            echo json_encode(['status' => 'success', 'message' => 'Article deleted successfully.']);
        } else {
            error_log("Failed to delete article with ID: $articleId");
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete article.']);
        }
        exit();
    }

    if (isset($_POST['delete_user'])) {
        header('Content-Type: application/json');
    
        if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
            exit();
        }
    
        $user_Id = intval($_POST['user_id']);
    
        if ($users->deleteUser($user_Id)) {
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
        } else {
            error_log("Failed to delete user with ID: $user_Id");
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <div class="bg-gray-800 text-white w-1/4 p-5">
        <h2 class="text-xl font-bold mb-5">Admin Dashboard</h2>
        <ul>
            <li><a href="#members" class="side-link block py-2 hover:bg-gray-700 rounded">Members</a></li>
            <li><a href="#articles" class="side-link block py-2 hover:bg-gray-700 rounded">Articles</a></li>
            <li><a href="#categories" class="side-link block py-2 hover:bg-gray-700 rounded">Categories</a></li>
            <li><a href="logout.php" class="side-link block py-2 hover:bg-gray-700 rounded">Logout</a></li>
        </ul>
    </div>

    <div class="flex-1 p-6">
        <div id="members" class="dashboard-section hidden">
            <h2 class="text-2xl font-bold mb-4">Members</h2>
            <table class="min-w-full bg-white rounded shadow mb-4">
                <thead>
                <tr class="w-full bg-blue-600 text-white">
                    <th class="py-2">Username</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($allUsers as $user): ?>
                    <tr data-user-id="<?php echo htmlspecialchars($user['UserID']); ?>">
                        <td id="<?php echo htmlspecialchars($user['UserID']); ?>" class="border px-4 py-2"><?php echo htmlspecialchars($user['Username']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Email']); ?></td>
                        <td class="border px-4 py-2">
                            <button class="bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700 delete-user">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="articles" class="dashboard-section hidden">
            <h2 class="text-2xl font-bold mb-4">Articles</h2>
            <table class="min-w-full bg-white rounded shadow mb-4">
                <thead>
                <tr class="w-full bg-blue-600 text-white">
                    <th class="py-2">Title</th>
                    <th class="py-2">Author</th>
                    <th class="py-2">Actions</th>
                </tr>
                </thead>
                <tbody id="articlesTable">
                <?php foreach ($allArticles as $article): ?>
                    <tr data-article-id="<?php echo htmlspecialchars($article['ArticleID']); ?>">
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($article['Title']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($article['AuthorName']); ?></td>
                        <td class="border px-4 py-2">
                            <button class="delete-article bg-red-500 text-white py-1 px-3 rounded hover:bg-red-700">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

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
    </div>
</div>

<script>
    const links = document.querySelectorAll('.side-link');
    const sections = document.querySelectorAll('.dashboard-section');

    sections.forEach(section => section.classList.add('hidden'));

    links.forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            sections.forEach(section => section.classList.add('hidden'));
            const targetId = event.target.getAttribute('href').substring(1);
            document.getElementById(targetId).classList.remove('hidden');
        });
    });

    $(document).on('click', '.delete-article', function() {
        const row = $(this).closest('tr');
        const articleId = row.data('article-id');

        if (confirm("Are you sure you want to delete this article?")) {
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: { delete_article: true, article_id: articleId },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });

    $(document).on('click', '.delete-user', function() {
        const row = $(this).closest('tr');
        const userId = row.data('user-id');

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: { delete_user: true, user_id: userId },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });

    document.getElementById('members').classList.remove('hidden');

    <?php foreach ($allUsers as $user): ?>
        document.getElementById('<?php echo htmlspecialchars($user['UserID']); ?>').addEventListener('mouseover', function(event) {
            let card = document.createElement('div');
            card.id = 'usercard';
            card.style.position = 'absolute';
            card.style.top = `${event.clientY + 10}px`;
            card.style.left = `${event.clientX + 10}px`;
            card.style.backgroundColor = 'white';
            card.style.border = '1px solid #ccc';
            card.style.borderRadius = '5px';
            card.style.padding = '10px';
            card.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
            card.style.zIndex = '1000';

            card.innerHTML = `
                <div class="flex flex-row gap-6 p-6 bg-white rounded-lg shadow-md">
                    <img class="rounded-lg w-32 h-32 object-cover" src="<?php echo htmlspecialchars($user['ProfileImage']); ?>" alt="Profile Image">
                    <div class="flex-1">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-700 font-medium">Username: <span class="font-normal"><?php echo htmlspecialchars($user['Username']); ?></span></p>
                                <p class="text-gray-700 font-medium">Email: <span class="font-normal"><?php echo htmlspecialchars($user['Email']); ?></span></p>
                                <p class="text-gray-700 font-medium">Role: <span class="font-normal"><?php echo htmlspecialchars($user['UserType']); ?></span></p>
                                <p class="text-gray-700 font-medium">First Name: <span class="font-normal"><?php echo htmlspecialchars($user['FirstName']); ?></span></p>
                                <p class="text-gray-700 font-medium">Last Name: <span class="font-normal"><?php echo htmlspecialchars($user['LastName']); ?></span></p>
                            </div>
                            <div>
                                <p class="text-gray-700 font-medium">Date of Birth: <span class="font-normal"><?php echo htmlspecialchars($user['Birthday']); ?></span></p>
                                <p class="text-gray-700 font-medium">Date of Joining: <span class="font-normal"><?php echo htmlspecialchars($user['DateOfJoining']); ?></span></p>
                                <p class="text-gray-700 font-medium">Bio: <span class="font-normal"><?php echo htmlspecialchars($user['Bio']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(card);
        });

        document.getElementById('<?php echo htmlspecialchars($user['UserID']); ?>').addEventListener('mouseout', function() {
            let card = document.getElementById('usercard');
            if (card) {
                card.remove();
            }
        });
    <?php endforeach; ?>
</script>

</body>
</html>