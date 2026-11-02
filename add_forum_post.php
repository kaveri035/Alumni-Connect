<?php
session_start();
require_once 'db_config.php';

// Check if user is logged in (Student OR Alumni OR Admin)
$is_student = isset($_SESSION['st_register']);
$is_alumni = isset($_SESSION['pi_register']);
$is_admin = isset($_SESSION['ad_id']);

if (!$is_student && !$is_alumni && !$is_admin) {
    header("Location: login.php");
    exit();
}

// Get user details based on who's logged in
if ($is_student) {
    $user_register = $_SESSION['st_register'];
    $user_name = $_SESSION['st_name'] ?? 'Student';
    $user_type = 'student';
    $redirect_page = 'studentpage.php';
    $user_badge = '📚 Student';
} elseif ($is_alumni) {
    $user_register = $_SESSION['pi_register'];
    $user_name = $_SESSION['pi_name'] ?? 'Alumni';
    $user_type = 'alumni';
    $redirect_page = 'alumnipage.php';
    $user_badge = '🎓 Alumni';
} else {
    $user_register = $_SESSION['ad_id'];
    $user_name = $_SESSION['ad_fullname'] ?? 'Admin';
    $user_type = 'admin';
    $redirect_page = 'adminpage.php';
    $user_badge = '👨‍💼 Admin';
}

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_post'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));

    if (empty($title) || empty($content) || empty($category)) {
        $error_message = "All fields are required!";
    } else {
        // Insert into forumdata table (works for all users)
        $insert_query = "INSERT INTO forumdata (eforum_title, eforum_author, eforum_content, eforum_tags) 
                        VALUES ('$title', '$user_name', '$content', '$category')";
        
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "✅ Post created successfully!";
            // Clear form
            $_POST = array();
        } else {
            $error_message = "Failed to create post: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Forum Post - Alumni Connect</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; padding: 20px;
        }
        .container { max-width: 800px; margin: 0 auto; }
        .header {
            background: white; padding: 20px 30px; border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        .header h1 { color: #667eea; font-size: 28px; }
        .user-badge {
            display: inline-block;
            padding: 5px 12px;
            background: #e8eaf6;
            color: #667eea;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .btn-group { display: flex; gap: 10px; }
        .btn {
            padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;
            font-size: 14px; text-decoration: none; transition: all 0.3s; font-weight: 500;
        }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .form-card {
            background: white; border-radius: 15px; padding: 35px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h2 { color: #333; font-size: 24px; margin-bottom: 10px; }
        .form-header p { color: #666; font-size: 14px; }
        .alert { 
            padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;
            animation: slideDown 0.5s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block; color: #333; font-weight: 600; margin-bottom: 8px; font-size: 14px;
        }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0;
            border-radius: 8px; font-size: 15px; transition: all 0.3s;
        }
        .form-control:focus {
            outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        textarea.form-control { min-height: 200px; resize: vertical; font-family: inherit; }
        select.form-control {
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 15px center;
            padding-right: 40px;
        }
        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; border-radius: 8px; font-size: 16px;
            font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
        .character-count { text-align: right; font-size: 12px; color: #999; margin-top: 5px; }
        .tips-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .tips-section h3 { color: #667eea; font-size: 16px; margin-bottom: 10px; }
        .tips-section ul { margin-left: 20px; color: #666; font-size: 14px; line-height: 1.8; }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .btn-group {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>✏️ Create New Post</h1>
            <span class="user-badge"><?php echo $user_badge; ?></span>
        </div>
        <div class="btn-group">
            <a href="forum_list.php" class="btn btn-secondary">View All Posts</a>
            <a href="<?php echo $redirect_page; ?>" class="btn btn-secondary">← Back</a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h2>Share Your Thoughts</h2>
            <p>Connect with students, alumni, and community by sharing experiences, asking questions, or starting discussions</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">⚠️ <?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
                <a href="forum_list.php" style="color: #155724; font-weight: 600; margin-left: 10px;">View your post →</a>
            </div>
        <?php endif; ?>

        <div class="tips-section">
            <h3>💡 Tips for Great Posts</h3>
            <ul>
                <li>Be clear and descriptive in your title</li>
                <li>Provide context and details in your content</li>
                <li>Choose the most relevant category</li>
                <li>Be respectful and professional</li>
                <?php if ($is_admin): ?>
                <li><strong>As an Admin:</strong> Your posts are visible to all users and can be used for announcements</li>
                <?php endif; ?>
            </ul>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="category">Category <span style="color:red">*</span></label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Select a category</option>
                    <option value="General Discussion">💬 General Discussion</option>
                    <option value="Career & Jobs">💼 Career & Jobs</option>
                    <option value="Networking">🤝 Networking</option>
                    <option value="Events">🎉 Events</option>
                    <option value="Alumni Stories">📚 Alumni Stories</option>
                    <option value="Mentorship">👨‍🏫 Mentorship</option>
                    <option value="College Memories">🎓 College Memories</option>
                    <option value="Questions">❓ Questions</option>
                    <?php if ($is_admin): ?>
                    <option value="Official Announcements">📢 Official Announcements</option>
                    <?php endif; ?>
                    <option value="Announcements">📣 Announcements</option>
                    <option value="Other">📌 Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Post Title <span style="color:red">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                       placeholder="Enter a catchy title for your post"
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                       required maxlength="200" oninput="updateCharCount('title','title-count',200)">
                <div class="character-count"><span id="title-count">0</span>/200 characters</div>
            </div>

            <div class="form-group">
                <label for="content">Post Content <span style="color:red">*</span></label>
                <textarea name="content" id="content" class="form-control"
                          placeholder="Share your thoughts, experiences, or questions in detail..."
                          required maxlength="2000"
                          oninput="updateCharCount('content','content-count',2000)"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                <div class="character-count"><span id="content-count">0</span>/2000 characters</div>
            </div>

            <button type="submit" name="submit_post" class="btn-submit">📝 Publish Post</button>
        </form>
    </div>
</div>

<script>
function updateCharCount(inputId, countId, maxLength) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(countId);
    const len = input.value.length;
    counter.textContent = len;
    counter.style.color = len > maxLength * 0.9 ? '#dc3545' : '#999';
}
window.onload = function() {
    updateCharCount('title','title-count',200);
    updateCharCount('content','content-count',2000);
};
</script>
</body>
</html>