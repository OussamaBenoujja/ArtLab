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

if($article['isban'] == 'yes'){
    header("Location: nocon.php");
    exit();
}

if (!$article) {
    header("Location: index.php");
    exit();
}

$voteCounts = $votes->countVotes($articleID);
$netVotes = ($voteCounts['Upvotes'] ?? 0) - ($voteCounts['Downvotes'] ?? 0);

$userVote = null;
if (isset($_SESSION['user_id'])) {
    $userVote = $votes->hasUserVoted($_SESSION['user_id'], $articleID);
}

$articleComments = $comments->getCommentsByArticle($articleID);

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['Title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
            <nav class="mt-4 md:mt-0">
                <ul class="flex flex-wrap justify-center gap-4">
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

    <!-- Main Content -->
    <div class="flex flex-col md:flex-row p-4 gap-4">
        <!-- Main Article Content -->
        <main class="flex-1 bg-white p-6 rounded-lg shadow">
            <!-- Article Banner -->
            <header class="relative">
                <img src="<?php echo htmlspecialchars($article['BannerImage']); ?>" alt="Article Banner" class="w-full h-48 md:h-64 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-25 flex flex-col md:flex-row items-center justify-between p-4">
                    <div class="text-center md:text-left">
                        <h1 class="text-white text-2xl md:text-4xl font-bold"><?php echo htmlspecialchars($article['Title']); ?></h1>
                        <?php if (!empty($article['CategoryName'])): ?>
                            <div class="mt-2">
                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                    Category: <?php echo htmlspecialchars($article['CategoryName']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($article['Tags'])): ?>
                            <div class="mt-2">
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">
                                    Tags: <?php echo htmlspecialchars($article['Tags']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center space-x-2 mt-4 md:mt-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="flex space-x-2">
                                <button type="button" name="vote" value="upvote" class="vote-button bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 <?php echo ($userVote && $userVote['VoteType'] === 'Upvote') ? 'opacity-50' : ''; ?>" data-vote-type="upvote">
                                    👍
                                </button>
                                <span id="vote-count" class="bg-white px-4 py-2 rounded"><?php echo $netVotes; ?></span>
                                <button type="button" name="vote" value="downvote" class="vote-button bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 <?php echo ($userVote && $userVote['VoteType'] === 'Downvote') ? 'opacity-50' : ''; ?>" data-vote-type="downvote">
                                    👎
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="bg-white px-4 py-2 rounded">
                                Votes: <?php echo $netVotes; ?>
                            </div>
                        <?php endif; ?>
                        <a href="download.php?articleID=<?php echo $articleID; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Download PDF
                        </a>
                    </div>
                </div>
            </header>

            <!-- Article Content -->
            <section class="mt-6 prose lg:prose-xl">
                <?php echo $article['InnerText']; ?>
            </section>

            <!-- Comments Section -->
            <h2 class="text-2xl font-semibold mt-12">Comments</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mt-4">
                    <form name="commentForm" class="bg-white p-4 rounded shadow">
                        <textarea name="comment" rows="4" class="resize-none w-full p-2 border-2 border-gray-300 rounded focus:border-blue-500 focus:outline-none" placeholder="Type your comment here..." required></textarea>
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

            <!-- Existing Comments -->
            <h3 class="text-xl font-semibold mt-6">Existing Comments</h3>
            <ul id="comments-list" class="mt-4">
                <?php foreach ($articleComments as $comment): ?>
                    <li class="bg-white p-4 rounded shadow mb-2" id="comment-<?php echo $comment['CommentID']; ?>">
                        <div id="comment-view-<?php echo $comment['CommentID']; ?>">
                            <div class="flex justify-between items-start">
                                <strong><?php echo htmlspecialchars($comment['Username']); ?>:</strong>
                                <span class="text-sm text-gray-500">
                                    <?php echo date('M d, Y H:i', strtotime($comment['CreatedAt'])); ?>
                                </span>
                            </div>
                            <p class="mt-2"><?php echo htmlspecialchars($comment['CommentText']); ?></p>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $comment['UserID'] || $_SESSION['user']['UserType'] === 'Admin')): ?>
                                <div class="mt-2 flex gap-2">
                                    <?php if ($_SESSION['user_id'] == $comment['UserID']): ?>
                                        <button onclick="toggleEditForm(<?php echo $comment['CommentID']; ?>)" class="text-blue-500 text-sm hover:underline">
                                            Edit
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="delete-comment text-red-500 text-sm hover:underline" data-comment-id="<?php echo $comment['CommentID']; ?>">
                                        Delete
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['UserID']): ?>
                            <div id="comment-edit-<?php echo $comment['CommentID']; ?>" class="hidden">
                                <form class="edit-comment-form">
                                    <textarea name="edited_comment" rows="3" class="w-full p-2 border rounded resize-none focus:border-blue-500 focus:outline-none" required><?php echo htmlspecialchars($comment['CommentText']); ?></textarea>
                                    <div class="mt-2 flex gap-2">
                                        <button type="submit" class="text-green-500 text-sm hover:underline">Save</button>
                                        <button type="button" onclick="toggleEditForm(<?php echo $comment['CommentID']; ?>)" class="text-gray-500 text-sm hover:underline">Cancel</button>
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
        <aside class="w-full md:w-1/4 bg-white p-4 shadow mt-4 md:mt-0">
            <h2 class="text-xl font-semibold mb-4">Author</h2>
            <div class="flex flex-col items-center">
                <img src="<?php echo htmlspecialchars($article['ProfileImage'] ?? '../assets/img/default.jpg'); ?>" alt="Profile Picture" class="rounded-full w-20 h-20 object-cover">
                <div class="mt-4 text-center">
                    <h3 class="font-bold"><?php echo htmlspecialchars($article['AuthorName']); ?></h3>
                    <p class="text-gray-500">Email: <?php echo htmlspecialchars($article['AuthorEmail']); ?></p>
                </div>
            </div>
        </aside>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 text-center mt-4">
        <p>© <?php echo date('Y'); ?> Art and Physics. All rights reserved.</p>
    </footer>

    <!-- JavaScript for AJAX -->
    <script>
        $(document).ready(function() {

            $(document).on('submit', '.edit-comment-form', function(e) {
        e.preventDefault();
        var commentID = $(this).closest('li').attr('id').replace('comment-', '');
        var commentText = $(this).find('textarea[name="edited_comment"]').val().trim();
        if (commentText === '') {
            alert('Comment text cannot be empty.');
            return;
        }
        $.ajax({
            url: '/ARTLAB/views/actions.php',
            type: 'POST',
            data: {
                action: 'edit_comment',
                commentID: commentID,
                commentText: commentText,
                userID: userID,
                csrf_token: getCsrfToken()
            },
            success: function(response) {
                if (response.success) {
                    $('#comment-view-' + commentID).find('p').text(response.commentText);
                    $('#comment-edit-' + commentID).hide();
                    $('#comment-view-' + commentID).show();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + error);
                console.error('Response Text: ' + xhr.responseText);
                alert('An error occurred while editing the comment.');
            }
        });
    });

            // CSRF token
            function getCsrfToken() {
                return '<?php echo $_SESSION['csrf_token']; ?>';
            }

            // Voting
            $('.vote-button').click(function() {
                var voteType = $(this).data('vote-type');
                $.ajax({
                    url: 'actions.php',
                    type: 'POST',
                    data: {
                        action: 'vote',
                        articleID: <?php echo $articleID; ?>,
                        userID: <?php echo $_SESSION['user_id']; ?>,
                        voteType: voteType,
                        csrf_token: getCsrfToken()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#vote-count').text(response.netVotes);
                            $('.vote-button').removeClass('opacity-50');
                            if (response.userVote === 'Upvote') {
                                $('.vote-button[data-vote-type="upvote"]').addClass('opacity-50');
                            } else if (response.userVote === 'Downvote') {
                                $('.vote-button[data-vote-type="downvote"]').addClass('opacity-50');
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + error);
                        alert('An error occurred while voting.');
                    }
                });
            });

            // Comment submission
            $('form[name="commentForm"]').on('submit', function(e) {
                e.preventDefault();
                var commentText = $(this).find('textarea[name="comment"]').val().trim();
                if (commentText === '') {
                    alert('Please enter a comment.');
                    return;
                }
                $.ajax({
                    url: 'actions.php',
                    type: 'POST',
                    data: {
                        action: 'add_comment',
                        articleID: <?php echo $articleID; ?>,
                        userID: <?php echo $_SESSION['user_id']; ?>,
                        commentText: commentText,
                        csrf_token: getCsrfToken()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#comments-list').prepend(response.html);
                            $('form[name="commentForm"]').find('textarea[name="comment"]').val('');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + error);
                        alert('An error occurred while submitting the comment.');
                    }
                });
            });

            var userID = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

            // In the edit comment form submission
            $.ajax({
                url: 'actions.php',
                type: 'POST',
                data: {
                    action: 'edit_comment',
                    commentID: commentID,
                    commentText: commentText,
                    userID: userID,
                    csrf_token: getCsrfToken()
                },
                success: function(response) {
                    if (response.success) {
                        $('#comment-view-' + commentID).find('p').text(response.commentText);
                        $('#comment-edit-' + commentID).hide();
                        $('#comment-view-' + commentID).show();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                    alert('An error occurred while editing the comment.');
                }
            });

            // Comment deletion
            $(document).on('click', '.delete-comment', function() {
                if (!confirm('Are you sure you want to delete this comment?')) {
                    return;
                }
                var commentID = $(this).data('comment-id');
                $.ajax({
                    url: 'actions.php',
                    type: 'POST',
                    data: {
                        action: 'delete_comment',
                        commentID: commentID,
                        csrf_token: getCsrfToken()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#comment-' + commentID).remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + error);
                        alert('An error occurred while deleting the comment.');
                    }
                });
            });
        });

        // Toggle edit form
        function toggleEditForm(commentID) {
            $('#comment-view-' + commentID).toggle();
            $('#comment-edit-' + commentID).toggle();
        }
    </script>
</body>
</html>