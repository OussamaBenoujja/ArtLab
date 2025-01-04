<?php
session_start();

require_once '../control/Database.php';
require_once "../control/Articles.php";
require_once "../control/Catagories.php";
require_once "../control/Comments.php";
require_once "../control/Votes.php";

$db = new Database();
$conn = $db->getConnection();
$articles = new Articles($conn);
$categories = new Categories($conn);
$comments = new Comments($conn);
$votes = new Votes($conn);


if (!isset($_GET['articleID'])) {
    header("Location: index.php");
    exit();
}

$articleID = $_GET['articleID'];
$article = $articles->getArticleByID($articleID);

if (!$article) {
    header("Location: index.php");
    exit();
}


if (isset($_POST['vote']) && isset($_SESSION['user_id'])) {
    $voteType = $_POST['vote'] === 'upvote' ? 'Upvote' : 'Downvote';
    $votes->addVote($_SESSION['user_id'], $articleID, $voteType);
    header("Location: article.php?articleID=" . $articleID);
    exit();
}


if (isset($_POST['action']) && isset($_SESSION['user_id'])) {
    $commentID = $_POST['comment_id'] ?? null;
    
    if ($_POST['action'] === 'delete') {
       
        if ($_SESSION['user']['UserType'] === 'Admin' || $_POST['comment_user_id'] == $_SESSION['user_id']) {
            $comments->deleteComment($commentID, $_POST['comment_user_id']);
        }
    } elseif ($_POST['action'] === 'edit' && $_POST['comment_user_id'] == $_SESSION['user_id']) {
        
        $newText = trim($_POST['edited_comment']);
        if (!empty($newText)) {
            $comments->updateComment($commentID, $_SESSION['user_id'], $newText);
        }
    }
    header("Location: article.php?articleID=" . $articleID);
    exit();
}


if (isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $commentText = trim($_POST['comment']);
    if (!empty($commentText)) {
        $comments->addComment($articleID, $_SESSION['user_id'], $commentText);
    }
    header("Location: article.php?articleID=" . $articleID);
    exit();
}


$voteCounts = $votes->countVotes($articleID);
$netVotes = ($voteCounts['Upvotes'] ?? 0) - ($voteCounts['Downvotes'] ?? 0);


$userVote = null;
if (isset($_SESSION['user_id'])) {
    $userVote = $votes->hasUserVoted($_SESSION['user_id'], $articleID);
}

// Get comments
$articleComments = $comments->getCommentsByArticle($articleID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['Title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleEditForm(commentId) {
            const viewDiv = document.getElementById(`comment-view-${commentId}`);
            const editDiv = document.getElementById(`comment-edit-${commentId}`);
            viewDiv.classList.toggle('hidden');
            editDiv.classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Main content area -->
        <main class="flex-1 p-6">
            <header class="relative">
                <img src="<?php echo htmlspecialchars($article['BannerImage']); ?>" alt="Article Banner" class="w-full h-64 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-25 flex items-center justify-between p-4">
                    <h1 class="text-white text-4xl font-bold"><?php echo htmlspecialchars($article['Title']); ?></h1>
                    <div class="flex items-center space-x-2">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" class="flex space-x-2">
                                <button type="submit" name="vote" value="upvote" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 <?php echo ($userVote && $userVote['VoteType'] === 'Upvote') ? 'opacity-50' : ''; ?>">
                                    👍 
                                </button>
                                <span class="bg-white px-4 py-2 rounded"><?php echo $netVotes; ?></span>
                                <button type="submit" name="vote" value="downvote" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 <?php echo ($userVote && $userVote['VoteType'] === 'Downvote') ? 'opacity-50' : ''; ?>">
                                    👎
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="bg-white px-4 py-2 rounded">
                                Votes: <?php echo $netVotes; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <section class="mt-6 prose lg:prose-xl bg-white p-6 rounded-lg shadow">
                <?php echo $article['InnerText']; ?>
            </section>

            <h2 class="text-2xl font-semibold mt-12">Comments</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mt-4">
                    <form method="POST" class="bg-white p-4 rounded shadow">
                        <textarea name="comment" rows="4" class="resize-none w-full p-2 border-2 border-gray-300 rounded focus:border-blue-500 focus:outline-none" 
                            placeholder="Type your comment here..." required></textarea>
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Submit Comment
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="mt-4 bg-yellow-100 p-4 rounded">
                    <p>Please <a href="login.php" class="text-blue-500 hover:underline">login</a> to comment.</p>
                </div>
            <?php endif; ?>

            <h3 class="text-xl font-semibold mt-6">Existing Comments</h3>
            <ul class="mt-4">
                <?php foreach ($articleComments as $comment): ?>
                    <li class="bg-white p-4 rounded shadow mb-2" id="comment-<?php echo $comment['CommentID']; ?>">
                        <!-- Comment View Mode -->
                        <div id="comment-view-<?php echo $comment['CommentID']; ?>">
                            <div class="flex justify-between items-start">
                                <strong><?php echo htmlspecialchars($comment['Username']); ?>:</strong>
                                <span class="text-sm text-gray-500">
                                    <?php echo date('M d, Y H:i', strtotime($comment['CreatedAt'])); ?>
                                </span>
                            </div>
                            <p class="mt-2"><?php echo htmlspecialchars($comment['CommentText']); ?></p>
                            
                            <?php if (isset($_SESSION['user_id']) && 
                                    ($_SESSION['user_id'] == $comment['UserID'] || 
                                     $_SESSION['user']['UserType'] === 'Admin')): ?>
                                <div class="mt-2 flex gap-2">
                                    <?php if ($_SESSION['user_id'] == $comment['UserID']): ?>
                                        <button onclick="toggleEditForm(<?php echo $comment['CommentID']; ?>)"
                                                class="text-blue-500 text-sm hover:underline">
                                            Edit
                                        </button>
                                    <?php endif; ?>
                                    
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                                        <input type="hidden" name="comment_user_id" value="<?php echo $comment['UserID']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="text-red-500 text-sm hover:underline"
                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Comment Edit Mode -->
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['UserID']): ?>
                            <div id="comment-edit-<?php echo $comment['CommentID']; ?>" class="hidden">
                                <form method="POST">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                                    <input type="hidden" name="comment_user_id" value="<?php echo $comment['UserID']; ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <textarea name="edited_comment" rows="3" 
                                            class="w-full p-2 border rounded resize-none focus:border-blue-500 focus:outline-none"
                                            required><?php echo htmlspecialchars($comment['CommentText']); ?></textarea>
                                    <div class="mt-2 flex gap-2">
                                        <button type="submit" class="text-green-500 text-sm hover:underline">Save</button>
                                        <button type="button" onclick="toggleEditForm(<?php echo $comment['CommentID']; ?>)"
                                                class="text-gray-500 text-sm hover:underline">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($articleComments)): ?>
                    <li class="bg-white p-4 rounded shadow mb-2">No comments yet.</li>
                <?php endif; ?>
            </ul>
        </main>

        <!-- Sidebar -->
        <aside class="w-1/4 bg-white p-4 shadow">
            <h2 class="text-xl font-semibold mb-4">Author</h2>
            <div class="flex items-center mb-4">
                <img src="<?php echo htmlspecialchars($article['AuthorProfileImage'] ?? '../assets/img/default.jpg'); ?>" 
                     alt="Profile Picture" class="rounded-full w-20 h-20 mr-4 object-cover">
                <div>
                    <h3 class="font-bold"><?php echo htmlspecialchars($article['AuthorName']); ?></h3>
                    <p class="text-gray-500">Email: <?php echo htmlspecialchars($article['AuthorEmail']); ?></p>
                </div>
            </div>
        </aside>
    </div>

    <footer class="bg-gray-800 text-white p-4 text-center mt-4">
        <p>© <?php echo date('Y'); ?> Art and Physics. All rights reserved.</p>
    </footer>
</body>
</html>