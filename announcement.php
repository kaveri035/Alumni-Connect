<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['ad_id'];
$admin_name = $_SESSION['ad_fullname'];
$success = "";
$error = "";

// Add announcement
if (isset($_POST['add_announcement'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $time = date('Y/m/d h:i:sa');
    
    // Generate announcement ID
    $count_query = "SELECT COUNT(*) as count FROM announcement";
    $count = mysqli_fetch_assoc(mysqli_query($conn, $count_query))['count'];
    $ann_id = 'ANN' . ($count + 1);
    
    $query = "INSERT INTO announcement (ann_id, ann_name, ann_desc, ann_time) 
              VALUES ('$ann_id', '$title', '$description', '$time')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Announcement added successfully!";
    } else {
        $error = "Failed to add announcement!";
    }
}

// Delete announcement
if (isset($_GET['delete'])) {
    $ann_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM announcement WHERE ann_id = '$ann_id'";
    
    if (mysqli_query($conn, $query)) {
        $success = "Announcement deleted!";
    }
}

// Get all announcements
$announcements = mysqli_query($conn, "SELECT * FROM announcement ORDER BY ann_time DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - SGBIT Admin</title>
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

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .add-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .announcement-list {
            display: grid;
            gap: 1.5rem;
        }

        .announcement-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            position: relative;
        }

        .announcement-card h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .announcement-card p {
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .announcement-meta {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .btn-delete {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Announcement Board</h1>
            <a href="admin_homepage.php" class="btn">← Back</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Announcement Form -->
        <div class="add-form">
            <h2 style="color: #667eea; margin-bottom: 1rem;">Add New Announcement</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required></textarea>
                </div>

                <button type="submit" name="add_announcement" class="btn-primary">Post Announcement</button>
            </form>
        </div>

        <!-- Announcements List -->
        <h2 style="color: #667eea; margin-bottom: 1.5rem;">All Announcements</h2>
        <div class="announcement-list">
            <?php while ($ann = mysqli_fetch_assoc($announcements)): ?>
                <div class="announcement-card">
                    <a href="?delete=<?php echo $ann['ann_id']; ?>" class="btn-delete" 
                       onclick="return confirm('Delete this announcement?')">🗑 Delete</a>
                    
                    <h3><?php echo $ann['ann_name']; ?></h3>
                    <p><?php echo nl2br($ann['ann_desc']); ?></p>
                    <div class="announcement-meta">
                        Posted by Admin on <?php echo $ann['ann_time']; ?>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($announcements) == 0): ?>
                <p style="text-align: center; color: #666; padding: 3rem;">No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
