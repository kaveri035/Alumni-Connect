<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];

// Handle form submission
if (isset($_POST['update_social_profile'])) {
    $st_linkedin = mysqli_real_escape_string($conn, $_POST['st_linkedin']);
    $st_instagram = mysqli_real_escape_string($conn, $_POST['st_instagram']);
    $st_whatsapp = mysqli_real_escape_string($conn, $_POST['st_whatsapp']);
    $st_facebook = mysqli_real_escape_string($conn, $_POST['st_facebook']);
    $st_twitter = mysqli_real_escape_string($conn, $_POST['st_twitter']);
    $st_github = mysqli_real_escape_string($conn, $_POST['st_github']);
    $st_portfolio = mysqli_real_escape_string($conn, $_POST['st_portfolio']);
    
    $updateQuery = "UPDATE studentinfo SET 
        st_linkedin = '$st_linkedin',
        st_instagram = '$st_instagram',
        st_whatsapp = '$st_whatsapp',
        st_facebook = '$st_facebook',
        st_twitter = '$st_twitter',
        st_github = '$st_github',
        st_portfolio = '$st_portfolio'
        WHERE st_register = '$st_register'";
    
    if (mysqli_query($conn, $updateQuery)) {
        $message = "Social profiles updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// Fetch current profile data
$profileQuery = "SELECT * FROM studentinfo WHERE st_register = '$st_register'";
$profileResult = mysqli_query($conn, $profileQuery);
$profile = mysqli_fetch_assoc($profileResult);
?>
<!DOCTYPE html>
<html>
<head>
<title>Update Social Profile</title>
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
    max-width: 800px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
    font-size: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 1rem;
}

.message {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
}

.message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.form-section {
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.3rem;
    color: #667eea;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
}

.social-icon {
    font-size: 1.2rem;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 0.85rem;
}

.btn-group {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f5f7fa;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-secondary:hover {
    background: #667eea;
    color: white;
}

.info-box {
    background: #e3f2fd;
    border-left: 4px solid #2196F3;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.info-box p {
    color: #0d47a1;
    margin: 5px 0;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
    
    .btn-group {
        flex-direction: column;
    }
}
</style>
</head>
<body>
<div class="container">
    <h2>🔗 Update Social Profile</h2>
    <p class="subtitle">Connect with alumni and fellow students</p>

    <?php if (isset($message)) { ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <div class="info-box">
        <p>💡 <strong>Tip:</strong> Add your social profiles to network better with alumni!</p>
        <p>🎯 Alumni can find and connect with you through these links.</p>
    </div>

    <form method="POST">
        <!-- Professional Links -->
        <div class="form-section">
            <div class="section-title">
                💼 Professional Links
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">🔗</span>
                    LinkedIn Profile URL
                </label>
                <input type="url" name="st_linkedin" value="<?php echo htmlspecialchars($profile['st_linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/in/your-profile">
                <small>Your LinkedIn profile link (e.g., https://linkedin.com/in/yourname)</small>
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">🐙</span>
                    GitHub Profile URL
                </label>
                <input type="url" name="st_github" value="<?php echo htmlspecialchars($profile['st_github'] ?? ''); ?>" placeholder="https://github.com/your-username">
                <small>Your GitHub profile (showcase your projects)</small>
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">🌐</span>
                    Portfolio/Website URL
                </label>
                <input type="url" name="st_portfolio" value="<?php echo htmlspecialchars($profile['st_portfolio'] ?? ''); ?>" placeholder="https://your-website.com">
                <small>Your personal website or portfolio</small>
            </div>
        </div>

        <!-- Social Media -->
        <div class="form-section">
            <div class="section-title">
                📱 Social Media
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">💬</span>
                    WhatsApp Number
                </label>
                <input type="tel" name="st_whatsapp" value="<?php echo htmlspecialchars($profile['st_whatsapp'] ?? ''); ?>" placeholder="919876543210">
                <small>WhatsApp number with country code (e.g., 919876543210 for India)</small>
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">📸</span>
                    Instagram Profile URL
                </label>
                <input type="url" name="st_instagram" value="<?php echo htmlspecialchars($profile['st_instagram'] ?? ''); ?>" placeholder="https://instagram.com/your-username">
                <small>Your Instagram profile link</small>
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">📘</span>
                    Facebook Profile URL
                </label>
                <input type="url" name="st_facebook" value="<?php echo htmlspecialchars($profile['st_facebook'] ?? ''); ?>" placeholder="https://facebook.com/your-profile">
                <small>Your Facebook profile link</small>
            </div>

            <div class="form-group">
                <label>
                    <span class="social-icon">🐦</span>
                    Twitter/X Profile URL
                </label>
                <input type="url" name="st_twitter" value="<?php echo htmlspecialchars($profile['st_twitter'] ?? ''); ?>" placeholder="https://twitter.com/your-username">
                <small>Your Twitter/X profile link</small>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" name="update_social_profile" class="btn btn-primary">
                💾 Save Changes
            </button>
            <a href="studentpage.php" class="btn btn-secondary" style="text-align:center; text-decoration:none; line-height:1.5;">
                ← Back to Dashboard
            </a>
        </div>
    </form>
</div>
</body>
</html>