<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$pi_register = isset($_GET['pi_register']) ? mysqli_real_escape_string($conn, $_GET['pi_register']) : null;

if (!$pi_register) {
    header("Location: student_connections.php");
    exit();
}

// Track profile view
mysqli_query($conn, "INSERT INTO user_interactions (from_type, from_register, to_type, to_register, interaction_type) 
                     VALUES ('student', '$st_register', 'alumni', '$pi_register', 'view_profile')");

// Get alumni details
$alumni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM alumniinfo WHERE pi_register='$pi_register'"));

if (!$alumni) {
    header("Location: student_connections.php");
    exit();
}

// Check connection status
$connection = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM user_connections 
     WHERE user1_register='$st_register' AND user2_register='$pi_register'"));

$pending_request = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM connection_requests 
     WHERE from_register='$st_register' AND to_register='$pi_register' AND status='pending'"));

// Get interaction stats
$interaction_stats = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total_interactions,
            SUM(CASE WHEN interaction_type='view_profile' THEN 1 ELSE 0 END) as profile_views,
            SUM(CASE WHEN interaction_type='click_linkedin' THEN 1 ELSE 0 END) as linkedin_clicks,
            SUM(CASE WHEN interaction_type='click_whatsapp' THEN 1 ELSE 0 END) as whatsapp_clicks,
            SUM(CASE WHEN interaction_type='send_message' THEN 1 ELSE 0 END) as messages_sent
     FROM user_interactions 
     WHERE from_register='$st_register' AND to_register='$pi_register'"));

// Get mentorship status
$mentorship = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM mentorship 
     WHERE mentor_pi_register='$pi_register' AND mentee_st_register='$st_register'"));

// Connection count
$connection_count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as count FROM user_connections WHERE user2_register='$pi_register'"))['count'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($alumni['pi_name']); ?> - Profile</title>
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
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 20px;
}

.main-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.profile-header {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
    font-weight: bold;
    flex-shrink: 0;
}

.profile-info h1 {
    color: #333;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.profile-title {
    color: #667eea;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.profile-company {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.profile-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-connected {
    background: #d4edda;
    color: #155724;
}

.badge-mentor {
    background: #fff3cd;
    color: #856404;
}

.badge-connections {
    background: #e7f0ff;
    color: #004085;
}

.section {
    margin-top: 30px;
}

.section h2 {
    color: #667eea;
    margin-bottom: 20px;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #555;
}

.info-icon {
    font-size: 1.5rem;
    color: #667eea;
}

.social-links {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.social-btn {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-linkedin { background: #0077b5; color: white; }
.btn-whatsapp { background: #25D366; color: white; }
.btn-github { background: #333; color: white; }
.btn-portfolio { background: #667eea; color: white; }
.btn-email { background: #EA4335; color: white; }

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.action-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.action-card h3 {
    color: #667eea;
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.action-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 10px;
    font-size: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
}

.back-link {
    display: inline-block;
    padding: 12px 25px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    margin-top: 20px;
}

.back-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .profile-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="main-content">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($alumni['pi_name'], 0, 1)); ?>
                </div>
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($alumni['pi_name']); ?></h1>
                    <?php if (!empty($alumni['pi_designation'])) { ?>
                        <div class="profile-title"><?php echo htmlspecialchars($alumni['pi_designation']); ?></div>
                    <?php } ?>
                    <?php if (!empty($alumni['pi_company'])) { ?>
                        <div class="profile-company">@ <?php echo htmlspecialchars($alumni['pi_company']); ?></div>
                    <?php } ?>
                    <div class="profile-badges">
                        <?php if ($connection) { ?>
                            <span class="badge badge-connected">✓ Connected</span>
                        <?php } ?>
                        <?php if ($mentorship) { ?>
                            <span class="badge badge-mentor">🎓 Your Mentor</span>
                        <?php } ?>
                        <span class="badge badge-connections">
                            👥 <?php echo $connection_count; ?> Connections
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Basic Info -->
            <div class="section">
                <h2>📋 Information</h2>
                <div class="info-grid">
                    <?php if ($alumni['show_contact']) { ?>
                        <div class="info-item">
                            <div class="info-icon">📧</div>
                            <div><?php echo htmlspecialchars($alumni['pi_email']); ?></div>
                        </div>
                        <?php if (!empty($alumni['pi_mobile'])) { ?>
                            <div class="info-item">
                                <div class="info-icon">📱</div>
                                <div><?php echo htmlspecialchars($alumni['pi_mobile']); ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="info-item">
                        <div class="info-icon">🎓</div>
                        <div><?php echo htmlspecialchars($alumni['pi_branch']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📅</div>
                        <div><?php echo htmlspecialchars($alumni['pi_session']); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Social Links -->
            <?php if ($alumni['show_contact']) { ?>
                <div class="section">
                    <h2>🔗 Connect</h2>
                    <div class="social-links">
                        <?php if (!empty($alumni['pi_linkedin'])) { ?>
                            <a href="<?php echo htmlspecialchars($alumni['pi_linkedin']); ?>" 
                               target="_blank" class="social-btn btn-linkedin"
                               onclick="trackClick('linkedin')">
                                LinkedIn
                            </a>
                        <?php } ?>
                        <?php if (!empty($alumni['pi_whatsapp'])) { ?>
                            <a href="https://wa.me/<?php echo htmlspecialchars($alumni['pi_whatsapp']); ?>" 
                               target="_blank" class="social-btn btn-whatsapp"
                               onclick="trackClick('whatsapp')">
                                WhatsApp
                            </a>
                        <?php } ?>
                        <?php if (!empty($alumni['pi_github'])) { ?>
                            <a href="<?php echo htmlspecialchars($alumni['pi_github']); ?>" 
                               target="_blank" class="social-btn btn-github"
                               onclick="trackClick('github')">
                                GitHub
                            </a>
                        <?php } ?>
                        <?php if (!empty($alumni['pi_portfolio'])) { ?>
                            <a href="<?php echo htmlspecialchars($alumni['pi_portfolio']); ?>" 
                               target="_blank" class="social-btn btn-portfolio"
                               onclick="trackClick('portfolio')">
                                Portfolio
                            </a>
                        <?php } ?>
                        <a href="mailto:<?php echo htmlspecialchars($alumni['pi_email']); ?>" 
                           class="social-btn btn-email"
                           onclick="trackClick('email')">
                            Email
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <a href="student_connections.php" class="back-link">← Back to Connections</a>
    </div>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Actions -->
        <div class="action-card">
            <h3>Quick Actions</h3>
            <?php if ($connection) { ?>
                <button class="action-btn btn-primary" 
                        onclick="window.location.href='student_chat.php?user=<?php echo $pi_register; ?>'">
                    💬 Send Message
                </button>
            <?php } elseif ($pending_request) { ?>
                <button class="action-btn btn-secondary" disabled>
                    ⏳ Request Pending
                </button>
            <?php } else { ?>
                <button class="action-btn btn-success" 
                        onclick="window.location.href='student_connections.php'">
                    ➕ Send Connection Request
                </button>
            <?php } ?>
            
            <?php if (!$mentorship && $connection) { ?>
                <button class="action-btn btn-primary"
                        onclick="window.location.href='student_find_mentor.php'">
                    🎓 Request as Mentor
                </button>
            <?php } ?>
        </div>
        
        <!-- Interaction Stats -->
        <div class="stats-card">
            <h3>Your Interactions</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $interaction_stats['total_interactions'] ?? 0; ?></div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $interaction_stats['profile_views'] ?? 0; ?></div>
                    <div class="stat-label">Profile Views</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $interaction_stats['linkedin_clicks'] ?? 0; ?></div>
                    <div class="stat-label">LinkedIn</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $interaction_stats['whatsapp_clicks'] ?? 0; ?></div>
                    <div class="stat-label">WhatsApp</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function trackClick(platform) {
    fetch('student_connections.php?track=click_' + platform + '&user=<?php echo $pi_register; ?>');
}
</script>
</body>
</html>