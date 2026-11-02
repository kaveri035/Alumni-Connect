<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$alumni_name = $_SESSION['pi_name'];

// Get my posts
$my_posts_query = "SELECT fd.*, 
                   (SELECT COUNT(*) FROM forum_reply WHERE forum_topic = fd.eforum_title) as reply_count
                   FROM forumdata fd 
                   WHERE fd.eforum_author = '$alumni_name'
                   ORDER BY fd.eforum_time DESC";
$my_posts = mysqli_query($conn, $my_posts_query);

// Get my replies
$my_replies_query = "SELECT * FROM forum_reply WHERE forum_reply_name = '$alumni_name' ORDER BY forum_reply_time DESC";
$my_replies = mysqli_query($conn, $my_replies_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Posts & Replies - SGBIT Alumni</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1, h2 {
            color: #667eea;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            background: #6c757d;
            color: white;
        }

        .section {
            margin-bottom: 3rem;
        }

        .post-item, .reply-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }

        .post-title {
            color: #667eea;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .post-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .post-content {
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 My Posts & Replies</h1>
            <a href="forum_list.php" class="btn">← Back to Forum</a>
        </div>

        <!-- My Posts Section -->
        <div class="section">
            <h2>My Forum Posts (<?php echo mysqli_num_rows($my_posts); ?>)</h2>
            <br>
            <?php while ($post = mysqli_fetch_assoc($my_posts)): ?>
                <div class="post-item">
                    <div class="post-title"><?php echo $post['eforum_title']; ?></div>
                    <div class="post-meta">
                        Posted on <?php echo date('M d, Y', strtotime($post['eforum_time'])); ?>
                        • <?php echo $post['reply_count']; ?> replies
                    </div>
                    <div class="post-content"><?php echo substr(nl2br($post['eforum_content']), 0, 200) . '...'; ?></div>
                    <a href="forum_reply.php?id=<?php echo $post['eforum_id']; ?>" style="color: #667eea; font-weight: 600;">View Post →</a>
                </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($my_posts) == 0): ?>
                <p style="text-align: center; color: #666; padding: 2rem;">You haven't created any posts yet.</p>
            <?php endif; ?>
        </div>

        <!-- My Replies Section -->
        <div class="section">
            <h2>My Replies (<?php echo mysqli_num_rows($my_replies); ?>)</h2>
            <br>
            <?php while ($reply = mysqli_fetch_assoc($my_replies)): ?>
                <div class="reply-item">
                    <div class="post-meta">
                        Reply to: <strong><?php echo $reply['forum_topic']; ?></strong> 
                        • <?php echo $reply['forum_reply_time']; ?>
                    </div>
                    <div class="post-content"><?php echo nl2br($reply['forum_message']); ?></div>
                </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($my_replies) == 0): ?>
                <p style="text-align: center; color: #666; padding: 2rem;">You haven't posted any replies yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
