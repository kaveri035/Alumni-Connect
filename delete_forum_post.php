<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";

// Delete forum post
if (isset($_GET['delete'])) {
    $post_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Get post title before deleting
    $title_query = "SELECT eforum_title FROM forumdata WHERE eforum_id = $post_id";
    $title_result = mysqli_query($conn, $title_query);
    $post_title = mysqli_fetch_assoc($title_result)['eforum_title'];
    
    // Delete replies first
    mysqli_query($conn, "DELETE FROM forum_reply WHERE forum_topic = '$post_title'");
    
    // Delete post
    if (mysqli_query($conn, "DELETE FROM forumdata WHERE eforum_id = $post_id")) {
        $success = "Forum post and all replies deleted successfully!";
    }
}

// Get all forum posts
$posts = mysqli_query($conn, "SELECT fd.*, 
         (SELECT COUNT(*) FROM forum_reply WHERE forum_topic = fd.eforum_title) as reply_count
          FROM forumdata fd ORDER BY fd.eforum_time DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Forum Posts - SGBIT Admin</title>
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
            background: #6c757d;
            color: white;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .post-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-content h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .post-meta {
            color: #666;
            font-size: 0.9rem;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗑️ Delete Forum Posts</h1>
            <a href="adminpage.php" class="btn">← Back</a>
        </div>

        <?php if ($success): ?>
            <div class="alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php while ($post = mysqli_fetch_assoc($posts)): ?>
            <div class="post-item">
                <div class="post-content">
                    <h3><?php echo $post['eforum_title']; ?></h3>
                    <div class="post-meta">
                        By <?php echo $post['eforum_author']; ?> • 
                        <?php echo date('M d, Y', strtotime($post['eforum_time'])); ?> • 
                        <?php echo $post['reply_count']; ?> replies
                    </div>
                </div>
                <a href="?delete=<?php echo $post['eforum_id']; ?>" class="btn-delete" 
                   onclick="return confirm('⚠️ Delete this post and all its replies?')">
                    Delete
                </a>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($posts) == 0): ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No forum posts to delete.</p>
        <?php endif; ?>
    </div>
</body>
</html>

