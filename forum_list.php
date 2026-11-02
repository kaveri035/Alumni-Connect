<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$alumni_name = $_SESSION['pi_name'];

// Get all forum posts with reply count
$query = "SELECT fd.*, 
          (SELECT COUNT(*) FROM forum_reply WHERE forum_topic = fd.eforum_title) as reply_count
          FROM forumdata fd 
          ORDER BY fd.eforum_time DESC";
$posts = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum - SGBIT Alumni</title>
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

        h1 {
            color: #667eea;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .forum-post {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
            transition: transform 0.3s;
        }

        .forum-post:hover {
            transform: translateX(5px);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .post-title {
            color: #667eea;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .post-meta {
            color: #666;
            font-size: 0.9rem;
        }

        .post-content {
            color: #333;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .post-footer {
            display: flex;
            gap: 1rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Forum</h1>
            <div>
                <a href="add_forum_post.php" class="btn btn-primary">➕ Add New Post</a>
                <a href="alumni_mypostreply.php" class="btn btn-secondary">My Posts</a>
                <a href="alumnipage.php" class="btn btn-secondary">← Back</a>
            </div>
        </div>

        <?php while ($post = mysqli_fetch_assoc($posts)): ?>
            <div class="forum-post">
                <div class="post-header">
                    <div>
                        <div class="post-title"><?php echo $post['eforum_title']; ?></div>
                        <div class="post-meta">
                            Posted by <strong><?php echo $post['eforum_author']; ?></strong> 
                            on <?php echo date('M d, Y', strtotime($post['eforum_time'])); ?>
                        </div>
                    </div>
                </div>
                
                <div class="post-content">
                    <?php echo nl2br($post['eforum_content']); ?>
                </div>
                
                <div class="post-footer">
                    <a href="forum_reply.php?id=<?php echo $post['eforum_id']; ?>" class="btn btn-primary btn-small">
                        💬 Reply (<?php echo $post['reply_count']; ?>)
                    </a>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($posts) == 0): ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No forum posts yet. Be the first to post!</p>
        <?php endif; ?>
    </div>
</body>
</html>

