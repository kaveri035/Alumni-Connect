<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$st_name = $_SESSION['st_name'];

// Handle connection request
if (isset($_POST['send_request'])) {
    $to_register = mysqli_real_escape_string($conn, $_POST['to_register']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Check if request already exists
    $check = mysqli_query($conn, "SELECT * FROM connection_requests 
                                   WHERE from_register='$st_register' AND to_register='$to_register'");
    
    if (mysqli_num_rows($check) == 0) {
        $insert = "INSERT INTO connection_requests (from_type, from_register, to_type, to_register, message, status) 
                   VALUES ('student', '$st_register', 'alumni', '$to_register', '$message', 'pending')";
        
        if (mysqli_query($conn, $insert)) {
            // Create notification for alumni
            mysqli_query($conn, "INSERT INTO notifications (user_type, user_register, notification_type, title, message, link) 
                                 VALUES ('alumni', '$to_register', 'connection_request', 'New Connection Request', 
                                 'Student $st_name wants to connect with you', 'alumni_connections.php')");
            $success = "Connection request sent successfully!";
        }
    } else {
        $error = "Connection request already exists!";
    }
}

// Handle accept/reject (for alumni viewing)
if (isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $status = ($action == 'accept') ? 'accepted' : 'rejected';
    
    mysqli_query($conn, "UPDATE connection_requests SET status='$status', response_date=NOW() 
                         WHERE request_id='$request_id'");
    
    if ($status == 'accepted') {
        $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM connection_requests WHERE request_id='$request_id'"));
        
        // Create connection
        mysqli_query($conn, "INSERT INTO user_connections (user1_type, user1_register, user2_type, user2_register) 
                             VALUES ('{$req['from_type']}', '{$req['from_register']}', '{$req['to_type']}', '{$req['to_register']}')");
        
        // Notify sender
        mysqli_query($conn, "INSERT INTO notifications (user_type, user_register, notification_type, title, message, link) 
                             VALUES ('{$req['from_type']}', '{$req['from_register']}', 'connection_request', 
                             'Connection Accepted', 'Your connection request was accepted!', 'student_connections.php')");
    }
}

// Fetch my connections
$connections_query = "SELECT uc.*, ai.pi_name, ai.pi_email, ai.pi_company, ai.pi_designation, 
                      ai.pi_branch, ai.pi_linkedin, ai.pi_whatsapp, uc.last_interaction, uc.interaction_count
                      FROM user_connections uc
                      JOIN alumniinfo ai ON uc.user2_register = ai.pi_register
                      WHERE uc.user1_register='$st_register' AND uc.user1_type='student'
                      ORDER BY uc.connection_date DESC";
$connections = mysqli_query($conn, $connections_query);

// Fetch pending requests
$pending_query = "SELECT cr.*, ai.pi_name, ai.pi_email, ai.pi_company, ai.pi_designation, ai.pi_branch
                  FROM connection_requests cr
                  JOIN alumniinfo ai ON cr.to_register = ai.pi_register
                  WHERE cr.from_register='$st_register' AND cr.status='pending'
                  ORDER BY cr.request_date DESC";
$pending_requests = mysqli_query($conn, $pending_query);

// Fetch suggested connections (alumni not connected)
$suggestions_query = "SELECT ai.*, 
                      (SELECT COUNT(*) FROM user_connections WHERE user2_register=ai.pi_register) as connection_count
                      FROM alumniinfo ai
                      JOIN alumnimember am ON ai.pi_register = am.pi_register
                      WHERE am.al_status='Approve'
                      AND ai.pi_register NOT IN (
                          SELECT user2_register FROM user_connections WHERE user1_register='$st_register'
                      )
                      AND ai.pi_register NOT IN (
                          SELECT to_register FROM connection_requests WHERE from_register='$st_register'
                      )
                      ORDER BY connection_count DESC, ai.pi_yop DESC
                      LIMIT 8";
$suggestions = mysqli_query($conn, $suggestions_query);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Connections - Alumni Connect</title>
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
    max-width: 1400px;
    margin: 40px auto;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 20px;
}

.sidebar {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.sidebar h3 {
    color: #667eea;
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    text-align: center;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.nav-item {
    padding: 12px 15px;
    margin: 8px 0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
}

.nav-item:hover, .nav-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.main-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header h2 {
    color: #667eea;
    font-size: 2rem;
}

.tab-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e0e0e0;
}

.tab-btn {
    padding: 12px 24px;
    border: none;
    background: transparent;
    color: #666;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border-bottom: 3px solid transparent;
}

.tab-btn:hover, .tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.connection-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.connection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.avatar {
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

.card-info h4 {
    color: #333;
    font-size: 1.2rem;
    margin-bottom: 5px;
}

.card-info p {
    color: #666;
    font-size: 0.9rem;
}

.card-details {
    margin: 15px 0;
}

.detail-row {
    display: flex;
    align-items: center;
    margin: 8px 0;
    font-size: 0.9rem;
    color: #555;
}

.detail-row i {
    margin-right: 8px;
    color: #667eea;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    flex: 1;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.social-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.social-btn {
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-linkedin { background: #0077b5; color: white; }
.btn-whatsapp { background: #25D366; color: white; }
.btn-email { background: #EA4335; color: white; }

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 8px;
}

.badge-pending { background: #ffc107; color: #000; }
.badge-connected { background: #28a745; color: white; }

.interaction-stats {
    display: flex;
    gap: 15px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-value {
    font-weight: bold;
    color: #667eea;
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #999;
}

.message {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
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

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    margin-bottom: 20px;
}

.modal-header h3 {
    color: #667eea;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
}

.close-btn {
    float: right;
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h3>🤝 My Network</h3>
        
        <div class="stats-card">
            <div class="stats-number"><?php echo mysqli_num_rows($connections); ?></div>
            <div class="stats-label">Connections</div>
        </div>
        
        <div class="stats-card">
            <div class="stats-number"><?php echo mysqli_num_rows($pending_requests); ?></div>
            <div class="stats-label">Pending Requests</div>
        </div>
        
        <div class="nav-item active" onclick="showTab('connections')">
            👥 My Connections
        </div>
        <div class="nav-item" onclick="showTab('pending')">
            ⏳ Pending Requests
        </div>
        <div class="nav-item" onclick="showTab('suggestions')">
            ✨ Suggested Connections
        </div>
        
        <a href="studentpage.php" style="text-decoration: none;">
            <div class="nav-item">← Back to Dashboard</div>
        </a>
    </div>
    
    <div class="main-content">
        <?php if (isset($success)) { ?>
            <div class="message success">✅ <?php echo $success; ?></div>
        <?php } ?>
        
        <?php if (isset($error)) { ?>
            <div class="message error">❌ <?php echo $error; ?></div>
        <?php } ?>
        
        <div class="header">
            <h2>🌐 Alumni Network</h2>
        </div>
        
        <!-- My Connections Tab -->
        <div class="tab-content active" id="connections">
            <h3 style="margin-bottom: 20px; color: #333;">Your Connections</h3>
            
            <?php if (mysqli_num_rows($connections) > 0) { ?>
                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($connections)) { 
                        $initials = strtoupper(substr($row['pi_name'], 0, 1));
                    ?>
                        <div class="connection-card">
                            <div class="card-header">
                                <div class="avatar"><?php echo $initials; ?></div>
                                <div class="card-info">
                                    <h4><?php echo htmlspecialchars($row['pi_name']); ?>
                                        <span class="badge badge-connected">Connected</span>
                                    </h4>
                                    <p><?php echo htmlspecialchars($row['pi_designation']); ?></p>
                                    <p><?php echo htmlspecialchars($row['pi_company']); ?></p>
                                </div>
                            </div>
                            
                            <div class="card-details">
                                <div class="detail-row">
                                    <i>🎓</i> <?php echo htmlspecialchars($row['pi_branch']); ?>
                                </div>
                                <div class="detail-row">
                                    <i>📧</i> <?php echo htmlspecialchars($row['pi_email']); ?>
                                </div>
                            </div>
                            
                            <div class="social-buttons">
                                <?php if (!empty($row['pi_linkedin'])) { ?>
                                    <a href="<?php echo htmlspecialchars($row['pi_linkedin']); ?>" target="_blank" class="social-btn btn-linkedin">
                                        🔗 LinkedIn
                                    </a>
                                <?php } ?>
                                
                                <?php if (!empty($row['pi_whatsapp'])) { ?>
                                    <a href="https://wa.me/<?php echo htmlspecialchars($row['pi_whatsapp']); ?>" target="_blank" class="social-btn btn-whatsapp">
                                        💬 WhatsApp
                                    </a>
                                <?php } ?>
                                
                                <a href="mailto:<?php echo htmlspecialchars($row['pi_email']); ?>" class="social-btn btn-email">
                                    📧 Email
                                </a>
                            </div>
                            
                            <div class="interaction-stats">
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $row['interaction_count'] ?? 0; ?></div>
                                    <div class="stat-label">Interactions</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?php 
                                        if ($row['last_interaction']) {
                                            $date = new DateTime($row['last_interaction']);
                                            echo $date->format('M d');
                                        } else {
                                            echo 'Never';
                                        }
                                        ?>
                                    </div>
                                    <div class="stat-label">Last Contact</div>
                                </div>
                            </div>
                            
                            <div class="card-actions">
                                <button class="btn btn-primary" onclick="window.location.href='student_chat.php?user=<?php echo $row['user2_register']; ?>'">
                                    💬 Message
                                </button>
                                <button class="btn btn-secondary" onclick="viewProfile('<?php echo $row['user2_register']; ?>')">
                                    👁️ View Profile
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">🤝</div>
                    <h3>No connections yet</h3>
                    <p>Start connecting with alumni to build your network!</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- Pending Requests Tab -->
        <div class="tab-content" id="pending">
            <h3 style="margin-bottom: 20px; color: #333;">Pending Connection Requests</h3>
            
            <?php if (mysqli_num_rows($pending_requests) > 0) { ?>
                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($pending_requests)) { 
                        $initials = strtoupper(substr($row['pi_name'], 0, 1));
                    ?>
                        <div class="connection-card">
                            <div class="card-header">
                                <div class="avatar"><?php echo $initials; ?></div>
                                <div class="card-info">
                                    <h4><?php echo htmlspecialchars($row['pi_name']); ?>
                                        <span class="badge badge-pending">Pending</span>
                                    </h4>
                                    <p><?php echo htmlspecialchars($row['pi_designation']); ?></p>
                                    <p><?php echo htmlspecialchars($row['pi_company']); ?></p>
                                </div>
                            </div>
                            
                            <div class="card-details">
                                <div class="detail-row">
                                    <i>🎓</i> <?php echo htmlspecialchars($row['pi_branch']); ?>
                                </div>
                                <div class="detail-row">
                                    <i>📧</i> <?php echo htmlspecialchars($row['pi_email']); ?>
                                </div>
                            </div>
                            
                            <p style="margin-top: 10px; color: #666; font-size: 0.9rem;">
                                Sent on <?php echo date('M d, Y', strtotime($row['request_date'])); ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">⏳</div>
                    <h3>No pending requests</h3>
                    <p>All your connection requests have been processed</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- Suggestions Tab -->
        <div class="tab-content" id="suggestions">
            <h3 style="margin-bottom: 20px; color: #333;">Suggested Alumni to Connect</h3>
            
            <?php if (mysqli_num_rows($suggestions) > 0) { ?>
                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($suggestions)) { 
                        $initials = strtoupper(substr($row['pi_name'], 0, 1));
                    ?>
                        <div class="connection-card">
                            <div class="card-header">
                                <div class="avatar"><?php echo $initials; ?></div>
                                <div class="card-info">
                                    <h4><?php echo htmlspecialchars($row['pi_name']); ?></h4>
                                    <p><?php echo htmlspecialchars($row['pi_designation']); ?></p>
                                    <p><?php echo htmlspecialchars($row['pi_company']); ?></p>
                                </div>
                            </div>
                            
                            <div class="card-details">
                                <div class="detail-row">
                                    <i>🎓</i> <?php echo htmlspecialchars($row['pi_branch']); ?>
                                </div>
                                <div class="detail-row">
                                    <i>👥</i> <?php echo $row['connection_count']; ?> connections
                                </div>
                            </div>
                            
                            <div class="card-actions">
                                <button class="btn btn-primary" onclick="openConnectModal('<?php echo $row['pi_register']; ?>', '<?php echo htmlspecialchars($row['pi_name']); ?>')">
                                    ➕ Connect
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">✨</div>
                    <h3>No suggestions available</h3>
                    <p>Check back later for new connection suggestions</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Connect Modal -->
<div class="modal" id="connectModal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3>Send Connection Request</h3>
        </div>
        <form method="POST">
            <input type="hidden" name="to_register" id="to_register">
            <div class="form-group">
                <label>To: <span id="recipient_name"></span></label>
            </div>
            <div class="form-group">
                <label>Message (Optional):</label>
                <textarea name="message" placeholder="Introduce yourself and why you'd like to connect..."></textarea>
            </div>
            <div class="card-actions">
                <button type="submit" name="send_request" class="btn btn-success">Send Request</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active from nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    
    // Add active to clicked nav item
    event.target.classList.add('active');
}

function openConnectModal(register, name) {
    document.getElementById('to_register').value = register;
    document.getElementById('recipient_name').textContent = name;
    document.getElementById('connectModal').classList.add('show');
}

function closeModal() {
    document.getElementById('connectModal').classList.remove('show');
}

function viewProfile(register) {
    window.location.href = 'view_alumni_profile.php?pi_register=' + register;
}

// Close modal when clicking outside
document.getElementById('connectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
</body>
</html>