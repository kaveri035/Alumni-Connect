<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];

// Track interaction (view profile)
if (isset($_GET['track']) && isset($_GET['mentor'])) {
    $mentor_pi_register = mysqli_real_escape_string($conn, $_GET['mentor']);
    $interaction_type = mysqli_real_escape_string($conn, $_GET['track']);
    
    $trackQuery = "INSERT INTO user_interactions (from_type, from_register, to_type, to_register, interaction_type) 
                   VALUES ('student', '$st_register', 'alumni', '$mentor_pi_register', '$interaction_type')";
    mysqli_query($conn, $trackQuery);
    exit();
}

// Handle mentor request submission
if (isset($_POST['request_mentor'])) {
    $mentor_pi_register = $_POST['mentor_pi_register'];
    
    // Check if request already exists
    $checkRequest = mysqli_query($conn, "SELECT * FROM mentorship_requests WHERE st_register='$st_register' AND mentor_pi_register='$mentor_pi_register'");
    
    if (mysqli_num_rows($checkRequest) > 0) {
        $message = "You have already sent a request to this mentor!";
        $messageType = "error";
    } else {
        // Insert new mentorship request
        $insertRequest = mysqli_query($conn, "INSERT INTO mentorship_requests (st_register, mentor_pi_register, status, request_date) VALUES ('$st_register', '$mentor_pi_register', 'Pending', NOW())");
        
        if ($insertRequest) {
            // Create notification for alumni
            $notifQuery = "INSERT INTO notifications (user_type, user_register, notification_type, title, message, link) 
                           VALUES ('alumni', '$mentor_pi_register', 'mentorship_request', 'New Mentorship Request', 'A student has requested you as a mentor', 'alumni_requests.php')";
            mysqli_query($conn, $notifQuery);
            
            $message = "Mentorship request sent successfully!";
            $messageType = "success";
        } else {
            $message = "Failed to send request. Please try again.";
            $messageType = "error";
        }
    }
}

// Fetch available mentors (alumni who are approved and available)
$sql = "SELECT ai.pi_register, ai.pi_name, ai.pi_email, ai.pi_mobile, ai.pi_company, 
        ai.pi_designation, ai.pi_yop, ai.pi_branch, ai.pi_session,
        ai.pi_linkedin, ai.pi_instagram, ai.pi_whatsapp, ai.pi_facebook, 
        ai.pi_twitter, ai.pi_github, ai.pi_portfolio,
        ai.show_contact, ai.available_for_mentorship
        FROM alumniinfo ai
        JOIN alumnimember am ON ai.pi_register = am.pi_register
        WHERE am.al_status = 'Approve'
        AND (ai.available_for_mentorship IS NULL OR ai.available_for_mentorship = 1)
        AND ai.pi_register NOT IN (
            SELECT mentor_pi_register FROM mentorship_requests 
            WHERE st_register='$st_register' AND status='Pending'
        )
        AND ai.pi_register NOT IN (
            SELECT mentor_pi_register FROM mentorship 
            WHERE mentee_st_register='$st_register' AND status='active'
        )
        ORDER BY ai.pi_yop DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Find a Mentor</title>
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
    max-width: 1200px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
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

.mentor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.mentor-card {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.mentor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.mentor-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #667eea;
}

.mentor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    margin-right: 15px;
}

.mentor-name {
    font-size: 1.3rem;
    font-weight: bold;
    color: #333;
}

.mentor-info {
    margin: 10px 0;
}

.info-row {
    display: flex;
    align-items: center;
    margin: 8px 0;
    font-size: 0.95rem;
}

.info-label {
    font-weight: 600;
    color: #555;
    min-width: 100px;
}

.info-value {
    color: #333;
}

/* Social Connect Buttons */
.social-connect {
    margin: 15px 0;
    padding: 15px;
    background: white;
    border-radius: 8px;
    border-top: 2px solid #667eea;
}

.social-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 10px;
}

.social-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.social-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-linkedin {
    background: #0077b5;
    color: white;
    border-color: #0077b5;
}

.btn-whatsapp {
    background: #25D366;
    color: white;
    border-color: #25D366;
}

.btn-instagram {
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    color: white;
    border-color: #dc2743;
}

.btn-github {
    background: #333;
    color: white;
    border-color: #333;
}

.btn-portfolio {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.btn-facebook {
    background: #1877f2;
    color: white;
    border-color: #1877f2;
}

.btn-twitter {
    background: #1DA1F2;
    color: white;
    border-color: #1DA1F2;
}

.btn-email {
    background: #EA4335;
    color: white;
    border-color: #EA4335;
}

.btn-phone {
    background: #34A853;
    color: white;
    border-color: #34A853;
}

.request-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 15px;
}

.request-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.back-btn {
    display: inline-block;
    padding: 12px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.no-mentors {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 1.1rem;
}

.privacy-note {
    font-size: 0.75rem;
    color: #999;
    font-style: italic;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .mentor-grid {
        grid-template-columns: 1fr;
    }
    
    .container {
        padding: 20px;
    }
    
    .social-buttons {
        flex-direction: column;
    }
    
    .social-btn {
        justify-content: center;
    }
}
</style>
</head>
<body>
<div class="container">
    <h2>🎓 Find Your Mentor</h2>

    <?php if (isset($message)) { ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <div class="mentor-grid">
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $initials = strtoupper(substr($row['pi_name'], 0, 1));
                $show_contact = $row['show_contact'] ?? 1;
            ?>
                <div class="mentor-card">
                    <div class="mentor-header">
                        <div class="mentor-avatar"><?php echo $initials; ?></div>
                        <div class="mentor-name"><?php echo htmlspecialchars($row['pi_name']); ?></div>
                    </div>
                    
                    <div class="mentor-info">
                        <?php if ($show_contact) { ?>
                        <div class="info-row">
                            <span class="info-label">📧 Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_email']); ?></span>
                        </div>
                        <?php if (!empty($row['pi_mobile'])) { ?>
                        <div class="info-row">
                            <span class="info-label">📱 Phone:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_mobile']); ?></span>
                        </div>
                        <?php } } ?>
                        <?php if (!empty($row['pi_company'])) { ?>
                        <div class="info-row">
                            <span class="info-label">🏢 Company:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_company']); ?></span>
                        </div>
                        <?php } ?>
                        <?php if (!empty($row['pi_designation'])) { ?>
                        <div class="info-row">
                            <span class="info-label">💼 Position:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_designation']); ?></span>
                        </div>
                        <?php } ?>
                        <div class="info-row">
                            <span class="info-label">🎓 Branch:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_branch']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">📅 Session:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['pi_session']); ?></span>
                        </div>
                    </div>

                    <!-- Social Connect Section -->
                    <?php if ($show_contact && (!empty($row['pi_linkedin']) || !empty($row['pi_whatsapp']) || !empty($row['pi_instagram']) || !empty($row['pi_github']) || !empty($row['pi_portfolio']) || !empty($row['pi_facebook']) || !empty($row['pi_twitter']))) { ?>
                    <div class="social-connect">
                        <div class="social-title">🔗 Connect with Me:</div>
                        <div class="social-buttons">
                            <?php if (!empty($row['pi_linkedin'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_linkedin']); ?>" target="_blank" class="social-btn btn-linkedin" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_linkedin')">
                                🔗 LinkedIn
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_whatsapp'])) { ?>
                            <a href="https://wa.me/<?php echo htmlspecialchars($row['pi_whatsapp']); ?>" target="_blank" class="social-btn btn-whatsapp" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_whatsapp')">
                                💬 WhatsApp
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_instagram'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_instagram']); ?>" target="_blank" class="social-btn btn-instagram" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_instagram')">
                                📸 Instagram
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_github'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_github']); ?>" target="_blank" class="social-btn btn-github" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_github')">
                                🐙 GitHub
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_portfolio'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_portfolio']); ?>" target="_blank" class="social-btn btn-portfolio" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_portfolio')">
                                🌐 Portfolio
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_facebook'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_facebook']); ?>" target="_blank" class="social-btn btn-facebook" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_facebook')">
                                📘 Facebook
                            </a>
                            <?php } ?>
                            
                            <?php if (!empty($row['pi_twitter'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['pi_twitter']); ?>" target="_blank" class="social-btn btn-twitter" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_twitter')">
                                🐦 Twitter
                            </a>
                            <?php } ?>
                            
                            <?php if ($show_contact) { ?>
                            <a href="mailto:<?php echo htmlspecialchars($row['pi_email']); ?>" class="social-btn btn-email" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_email')">
                                📧 Email
                            </a>
                            
                            <?php if (!empty($row['pi_mobile'])) { ?>
                            <a href="tel:<?php echo htmlspecialchars($row['pi_mobile']); ?>" class="social-btn btn-phone" onclick="trackInteraction('<?php echo $row['pi_register']; ?>', 'click_phone')">
                                📞 Call
                            </a>
                            <?php } } ?>
                        </div>
                        <p class="privacy-note">Interactions are tracked for analytics</p>
                    </div>
                    <?php } ?>

                    <form method="POST">
                        <input type="hidden" name="mentor_pi_register" value="<?php echo $row['pi_register']; ?>">
                        <button type="submit" name="request_mentor" class="request-btn">
                            📨 Request Mentorship
                        </button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-mentors">
                <p>No mentors available at the moment. Please check back later!</p>
            </div>
        <?php } ?>
    </div>

    <a href="studentpage.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<script>
function trackInteraction(mentorRegister, interactionType) {
    // Track interaction using AJAX
    fetch('?track=' + interactionType + '&mentor=' + mentorRegister);
}
</script>
</body>
</html>