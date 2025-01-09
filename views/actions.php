<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../control/Database.php';
require_once __DIR__ . '/../control/Votes.php';
require_once __DIR__ . '/../control/Comments.php';

$db = new Database();
$conn = $db->getConnection();
$votes = new Votes($conn);
$comments = new Comments($conn);

header('Content-Type: application/json');

// CSRF token verification
$csrfToken = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    die(json_encode(['success' => false, 'message' => 'Invalid CSRF token.']));
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'vote':
        $articleID = $_POST['articleID'];
        $userID = $_POST['userID'];
        $voteType = $_POST['voteType'];
        try {
            $votes->addVote($userID, $articleID, $voteType);
            $voteCounts = $votes->countVotes($articleID);
            $userVote = $votes->hasUserVoted($userID, $articleID);
            echo json_encode([
                'success' => true,
                'netVotes' => ($voteCounts['Upvotes'] ?? 0) - ($voteCounts['Downvotes'] ?? 0),
                'userVote' => $userVote ? $userVote['VoteType'] : null
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'add_comment':
        $articleID = $_POST['articleID'];
        $userID = $_POST['userID'];
        $commentText = $_POST['commentText'];
        try {
            $comments->addComment($articleID, $userID, $commentText);
            // Fetch the new comment and return HTML
            $newComment = $comments->getCommentsByArticle($articleID)[0];
            ob_start();
            ?>
            <li class="bg-white p-4 rounded shadow mb-2" id="comment-<?php echo $newComment['CommentID']; ?>">
                <div id="comment-view-<?php echo $newComment['CommentID']; ?>">
                    <div class="flex justify-between items-start">
                        <strong><?php echo htmlspecialchars($newComment['Username']); ?>:</strong>
                        <span class="text-sm text-gray-500">
                            <?php echo date('M d, Y H:i', strtotime($newComment['CreatedAt'])); ?>
                        </span>
                    </div>
                    <p class="mt-2"><?php echo htmlspecialchars($newComment['CommentText']); ?></p>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $newComment['UserID'] || $_SESSION['user']['UserType'] === 'Admin')): ?>
                        <div class="mt-2 flex gap-2">
                            <?php if ($_SESSION['user_id'] == $newComment['UserID']): ?>
                                <button onclick="toggleEditForm(<?php echo $newComment['CommentID']; ?>)" class="text-blue-500 text-sm hover:underline">
                                    Edit
                                </button>
                            <?php endif; ?>
                            <button type="button" class="delete-comment text-red-500 text-sm hover:underline" data-comment-id="<?php echo $newComment['CommentID']; ?>">
                                Delete
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $newComment['UserID']): ?>
                    <div id="comment-edit-<?php echo $newComment['CommentID']; ?>" class="hidden">
                        <form class="edit-comment-form">
                            <textarea name="edited_comment" rows="3" class="w-full p-2 border rounded resize-none focus:border-blue-500 focus:outline-none" required><?php echo htmlspecialchars($newComment['CommentText']); ?></textarea>
                            <div class="mt-2 flex gap-2">
                                <button type="submit" class="text-green-500 text-sm hover:underline">Save</button>
                                <button type="button" onclick="toggleEditForm(<?php echo $newComment['CommentID']; ?>)" class="text-gray-500 text-sm hover:underline">Cancel</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </li>
            <?php
            $html = ob_get_clean();
            echo json_encode(['success' => true, 'html' => $html]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

        case 'edit_comment':
            $commentID = $_POST['commentID'];
            $commentText = $_POST['commentText'];
            $userID = $_POST['userID'];
            try {
                $comments->updateComment($commentID, $userID, $commentText);
                $updatedComment = $comments->getCommentByID($commentID);
                echo json_encode(['success' => true, 'commentText' => $updatedComment['CommentText']]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;

    case 'delete_comment':
        $commentID = $_POST['commentID'];
        try {
            $comments->deleteComment($commentID, $userID);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        break;
}
?>