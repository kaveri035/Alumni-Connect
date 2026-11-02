<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register']) || !isset($_GET['id'])) {
    header("Location: forum_list.php");
    exit();
}

$alumni_name = $_SESSION['pi_name'];
$post_id = mysqli_real_escape_string($conn, $_GET['id']);

// Get forum post details
$post_query = "SELECT * FROM forumdata WHERE eforum_id = $post_id";
$post_result = mysqli_query($conn, $post_query);
$post = mysqli_fetch_assoc($post_result);

if (!$post) {
    header("Location: forum_list.php");
    exit();
}

// Get all replies for this post
$replies_query = "SELECT * FROM forum_reply WHERE forum_topic = '{$post['eforum_title']}' ORDER BY forum_reply_time DESC";
$replies = mysqli_query($conn, $replies_query);

// Add reply
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_message = mysqli_real_escape_string($conn, $_POST['reply_message']);
    $reply_time = date('Y/m/d h:i:sa');
    
    $query = "INSERT INTO forum_reply (forum_topic, forum_message, forum_reply_time, forum_reply_name) 
              VALUES ('{$post['eforum_title']}', '$reply_message', '$reply_time', '$alumni_name')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: forum_reply.php?id=$post_id&success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum Replies - SGBIT Alumni</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .post-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .post-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .post-meta {
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .post-content {
            line-height: 1.8;
        }

        .replies-section {
            margin-top: 2rem;
        }

        h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
        }

        .reply-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 3px solid #667eea;
        }

        .reply-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .reply-author {
            font-weight: bold;
            color: #667eea;
        }

        .reply-message {
            color: #333;
            line-height: 1.6;
        }

        .reply-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            min-height: 120px;
            font-size: 1rem;
            resize: vertical;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Original Post -->
        <div class="post-detail">
            <div class="post-title"><?php echo $post['eforum_title']; ?></div>
            <div class="post-meta">
                Posted by <strong><?php echo $post['eforum_author']; ?></strong> 
                on <?php echo date('M d, Y h:i A', strtotime($post['eforum_time'])); ?>
            </div>
            <div class="post-content"><?php echo nl2br($post['eforum_content']); ?></div>
        </div>

        <!-- Replies Section -->
        <div class="replies-section">
            <h2>💬 Replies (<?php echo mysqli_num_rows($replies); ?>)</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">✅ Reply posted successfully!</div>
            <?php endif; ?>

            <?php while ($reply = mysqli_fetch_assoc($replies)): ?>
                <div class="reply-item">
                    <div class="reply-header">
                        <span class="reply-author"><?php echo $reply['forum_reply_name']; ?></span>
                        <span><?php echo $reply['forum_reply_time']; ?></span>
                    </div>
                    <div class="reply-message"><?php echo nl2br($reply['forum_message']); ?></div>
                </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($replies) == 0): ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No replies yet. Be the first to reply!</p>
            <?php endif; ?>
        </div>

        <!-- Reply Form -->
        <div class="reply-form">
            <h3 style="color: #667eea; margin-bottom: 1rem;">Add Your Reply</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Your Reply:</label>
                    <textarea name="reply_message" placeholder="Write your reply here..." required></textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Post Reply</button>
                    <a href="forum_list.php" class="btn btn-secondary">Back to Forum</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>