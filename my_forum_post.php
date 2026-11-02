<?php
session_start();
require_once 'db_config.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$student_name = $_SESSION['st_name'] ?? '';

// ✅ Handle post deletion
if (isset($_POST['delete_post'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    // Delete related likes and comments first (if any)
    mysqli_query($conn, "DELETE FROM forum_comments WHERE post_id = '$post_id'");
    mysqli_query($conn, "DELETE FROM forum_likes WHERE post_id = '$post_id'");
    mysqli_query($conn, "DELETE FROM forumdata WHERE eforum_id = '$post_id' AND eforum_author = '$student_name'");

    header("Location: my_forum_post.php");
    exit();
}

// ✅ Fetch posts created by the logged-in student
$query = "
    SELECT f.*, 
        (SELECT COUNT(*) FROM forum_comments WHERE post_id = f.eforum_id) AS comment_count,
        (SELECT COUNT(*) FROM forum_likes WHERE post_id = f.eforum_id) AS like_count
    FROM forumdata f
    WHERE f.eforum_author = '$student_name'
    ORDER BY f.eforum_id DESC
";
$result = mysqli_query($conn, $query);

// ✅ Get user post stats
$stats_query = "
    SELECT 
        COUNT(*) AS total_posts,
        COALESCE(SUM((SELECT COUNT(*) FROM forum_likes WHERE post_id = f.eforum_id)), 0) AS total_likes,
        COALESCE(SUM((SELECT COUNT(*) FROM forum_comments WHERE post_id = f.eforum_id)), 0) AS total_comments
    FROM forumdata f
    WHERE f.eforum_author = '$student_name'
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Forum Posts - Alumni Connect</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container { max-width: 1000px; margin: 0 auto; }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 { color: #01579b; font-size: 28px; }

        .btn-group { display: flex; gap: 10px; }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
            display: inline-block;
        }

        .btn-primary { background: #01579b; color: white; }
        .btn-primary:hover { background: #013f70; transform: translateY(-2px); }

        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }

        .btn-danger {
            background: #dc3545; color: white;
            padding: 6px 12px; font-size: 13px;
        }
        .btn-danger:hover { background: #c82333; }

        .btn-edit {
            background: #28a745; color: white;
            padding: 6px 12px; font-size: 13px;
        }
        .btn-edit:hover { background: #218838; }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-icon { font-size: 40px; margin-bottom: 10px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #01579b; margin-bottom: 5px; }
        .stat-label { color: #666; font-size: 14px; }

        .post-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .post-category {
            display: inline-block;
            padding: 5px 12px;
            background: #e1f5fe;
            color: #0277bd;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .post-date { color: #999; font-size: 13px; }
        .post-title { color: #333; font-size: 22px; margin-bottom: 12px; font-weight: 600; }
        .post-content { color: #555; line-height: 1.6; margin-bottom: 15px; font-size: 15px; }

        .post-stats {
            display: flex;
            gap: 20px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
            margin-bottom: 15px;
        }

        .stat-item { display: flex; align-items: center; gap: 5px; color: #666; font-size: 14px; }

        .post-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .no-posts {
            background: white;
            padding: 60px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .no-posts h2 { color: #01579b; margin-bottom: 15px; }
        .no-posts p { color: #666; margin-bottom: 25px; }
        .delete-form { display: inline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 My Forum Posts</h1>
            <div class="btn-group">
                <a href="add_forum_post.php" class="btn btn-primary">+ New Post</a>
                <a href="view_forum_post.php" class="btn btn-secondary">All Posts</a>
                <a href="studentpage.php" class="btn btn-secondary">← Back</a>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📄</div>
                <div class="stat-number"><?= $stats['total_posts'] ?? 0; ?></div>
                <div class="stat-label">Total Posts</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👍</div>
                <div class="stat-number"><?= $stats['total_likes'] ?? 0; ?></div>
                <div class="stat-label">Total Likes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💬</div>
                <div class="stat-number"><?= $stats['total_comments'] ?? 0; ?></div>
                <div class="stat-label">Total Comments</div>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
                <div class="post-card">
                    <div class="post-header">
                        <div>
                            <span class="post-category"><?= htmlspecialchars($post['eforum_tags']); ?></span>
                        </div>
                        <div class="post-date">
                            <?php
                                if (!empty($post['eforum_date']) && strtotime($post['eforum_date']) > 0) {
                                    echo date('M d, Y', strtotime($post['eforum_date']));
                                } else {
                                    echo '—';
                                }
                            ?>
                        </div>
                    </div>

                    <h2 class="post-title"><?= htmlspecialchars($post['eforum_title']); ?></h2>
                    <p class="post-content"><?= nl2br(htmlspecialchars($post['eforum_content'])); ?></p>

                    <div class="post-stats">
                        <div class="stat-item">👍 <?= $post['like_count']; ?> Likes</div>
                        <div class="stat-item">💬 <?= $post['comment_count']; ?> Comments</div>
                    </div>

                    <div class="post-actions">
                        <a href="edit_forum_post.php?id=<?= $post['eforum_id']; ?>" class="btn btn-edit">✏️ Edit</a>
                        <form method="POST" class="delete-form" onsubmit="return confirm('Delete this post?');">
                            <input type="hidden" name="post_id" value="<?= $post['eforum_id']; ?>">
                            <button type="submit" name="delete_post" class="btn btn-danger">🗑️ Delete</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">
                <h2>No Posts Yet</h2>
                <p>Start sharing your thoughts and experiences with the community!</p>
                <a href="add_forum_post.php" class="btn btn-primary">Create Your First Post</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
