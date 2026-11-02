<?php
session_start();
require_once 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$student_name = $_SESSION['st_name'] ?? 'Student';

// Fetch posts from BOTH tables (forum_posts for students AND forumdata for alumni/admin)
// We'll use UNION to combine both tables
$query = "
    (SELECT 
        fp.post_id as id,
        fp.title,
        fp.content,
        fp.category,
        fp.likes,
        fp.created_at,
        si.st_name as author_name,
        CONCAT(si.st_branch, ' • Year ', si.st_year) as author_info,
        'student' as post_type,
        fp.post_id as post_reference
    FROM forum_posts fp 
    JOIN studentinfo si ON fp.st_register = si.st_register)
    
    UNION ALL
    
    (SELECT 
        fd.eforum_id as id,
        fd.eforum_title as title,
        fd.eforum_content as content,
        fd.eforum_tags as category,
        0 as likes,
        fd.eforum_time as created_at,
        fd.eforum_author as author_name,
        'Alumni/Admin' as author_info,
        'alumni' as post_type,
        fd.eforum_id as post_reference
    FROM forumdata fd)
    
    ORDER BY created_at DESC
";

$result = mysqli_query($conn, $query);

// Handle like/unlike (only for student posts)
if (isset($_POST['like_post'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $post_type = mysqli_real_escape_string($conn, $_POST['post_type']);
    
    if ($post_type == 'student') {
        // Check if already liked
        $check_like = "SELECT * FROM forum_likes WHERE post_id = '$post_id' AND st_register = '$st_register'";
        $like_result = mysqli_query($conn, $check_like);
        
        if (mysqli_num_rows($like_result) > 0) {
            // Unlike
            mysqli_query($conn, "DELETE FROM forum_likes WHERE post_id = '$post_id' AND st_register = '$st_register'");
            mysqli_query($conn, "UPDATE forum_posts SET likes = likes - 1 WHERE post_id = '$post_id'");
        } else {
            // Like
            mysqli_query($conn, "INSERT INTO forum_likes (post_id, st_register) VALUES ('$post_id', '$st_register')");
            mysqli_query($conn, "UPDATE forum_posts SET likes = likes + 1 WHERE post_id = '$post_id'");
        }
    }
    
    header("Location: view_forum_post.php");
    exit();
}

// Handle comment submission (only for student posts)
if (isset($_POST['add_comment'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $post_type = mysqli_real_escape_string($conn, $_POST['post_type']);
    $comment_text = mysqli_real_escape_string($conn, trim($_POST['comment_text']));
    
    if (!empty($comment_text) && $post_type == 'student') {
        mysqli_query($conn, "INSERT INTO forum_comments (post_id, st_register, comment_text) 
                             VALUES ('$post_id', '$st_register', '$comment_text')");
    }
    
    header("Location: view_forum_post.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Forum Posts - Alumni Connect</title>
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
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

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

        .header h1 {
            color: #667eea;
            font-size: 28px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

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

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

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

        .post-card.alumni-post {
            border-left: 4px solid #764ba2;
        }

        .post-card.student-post {
            border-left: 4px solid #667eea;
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }

        .author-avatar.alumni {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .author-details h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 3px;
        }

        .author-details p {
            color: #666;
            font-size: 13px;
        }

        .post-date {
            color: #999;
            font-size: 13px;
        }

        .post-category {
            display: inline-block;
            padding: 5px 12px;
            background: #e8eaf6;
            color: #667eea;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .post-type-badge {
            display: inline-block;
            padding: 3px 10px;
            background: #f0f0f0;
            color: #666;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 8px;
        }

        .post-type-badge.alumni {
            background: #ede7f6;
            color: #764ba2;
        }

        .post-title {
            color: #333;
            font-size: 22px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .post-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .post-actions {
            display: flex;
            gap: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .action-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        .action-btn.liked {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .comments-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .comment {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: 600;
            color: #667eea;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .comment-text {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }

        .comment-form {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .comment-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .comment-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .no-posts {
            background: white;
            padding: 60px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .no-posts h2 {
            color: #667eea;
            margin-bottom: 15px;
        }

        .no-posts p {
            color: #666;
            margin-bottom: 25px;
        }

        .info-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #856404;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .btn-group {
                flex-wrap: wrap;
                justify-content: center;
            }

            .post-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .comment-form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Forum Posts</h1>
            <div class="btn-group">
                <a href="add_forum_post.php" class="btn btn-primary">+ New Post</a>
                <a href="my_forum_post.php" class="btn btn-secondary">My Posts</a>
                <a href="studentpage.php" class="btn btn-secondary">← Back</a>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
                <?php
                $is_student_post = ($post['post_type'] == 'student');
                $post_id = $post['post_reference'];
                
                // For student posts, check likes and get comments
                $is_liked = false;
                $comments_count = 0;
                $comments_result = null;
                
                if ($is_student_post) {
                    $check_like = "SELECT * FROM forum_likes WHERE post_id = '$post_id' AND st_register = '$st_register'";
                    $like_check = mysqli_query($conn, $check_like);
                    $is_liked = mysqli_num_rows($like_check) > 0;
                    
                    $comments_query = "SELECT fc.*, si.st_name as commenter_name 
                                      FROM forum_comments fc 
                                      JOIN studentinfo si ON fc.st_register = si.st_register 
                                      WHERE fc.post_id = '$post_id' 
                                      ORDER BY fc.created_at ASC";
                    $comments_result = mysqli_query($conn, $comments_query);
                    $comments_count = mysqli_num_rows($comments_result);
                }
                ?>
                
                <div class="post-card <?php echo $is_student_post ? 'student-post' : 'alumni-post'; ?>">
                    <div class="post-header">
                        <div class="author-info">
                            <div class="author-avatar <?php echo $is_student_post ? '' : 'alumni'; ?>">
                                <?php echo strtoupper(substr($post['author_name'], 0, 1)); ?>
                            </div>
                            <div class="author-details">
                                <h3>
                                    <?php echo htmlspecialchars($post['author_name']); ?>
                                    <span class="post-type-badge <?php echo $is_student_post ? '' : 'alumni'; ?>">
                                        <?php echo $is_student_post ? '📚 Student' : '🎓 ' . $post['author_info']; ?>
                                    </span>
                                </h3>
                                <p><?php echo htmlspecialchars($post['author_info']); ?></p>
                            </div>
                        </div>
                        <div class="post-date">
                            <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                        </div>
                    </div>
                    
                    <span class="post-category"><?php echo htmlspecialchars($post['category']); ?></span>
                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    
                    <div class="post-actions">
                        <?php if ($is_student_post): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <input type="hidden" name="post_type" value="student">
                                <button type="submit" name="like_post" class="action-btn <?php echo $is_liked ? 'liked' : ''; ?>">
                                    👍 <?php echo $post['likes']; ?> Likes
                                </button>
                            </form>
                            <button class="action-btn" onclick="toggleComments(<?php echo $post_id; ?>)">
                                💬 <?php echo $comments_count; ?> Comments
                            </button>
                        <?php else: ?>
                            <button class="action-btn" disabled>
                                👍 View Only
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($is_student_post): ?>
                    <div class="comments-section" id="comments-<?php echo $post_id; ?>" style="display: none;">
                        <?php if ($comments_count > 0): ?>
                            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                                <div class="comment">
                                    <div class="comment-author"><?php echo htmlspecialchars($comment['commenter_name']); ?></div>
                                    <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="color: #999; font-size: 14px; margin-bottom: 15px;">No comments yet. Be the first to comment!</p>
                        <?php endif; ?>
                        
                        <form method="POST" class="comment-form">
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <input type="hidden" name="post_type" value="student">
                            <input type="text" name="comment_text" class="comment-input" placeholder="Write a comment..." required>
                            <button type="submit" name="add_comment" class="btn btn-primary">Post</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">
                <h2>No Posts Yet</h2>
                <p>Be the first to start a discussion in the forum!</p>
                <a href="add_forum_post.php" class="btn btn-primary">Create First Post</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>