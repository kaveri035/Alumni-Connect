<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];
$pi_name = $_SESSION['pi_name'];

// Handle connection request response
if (isset($_POST['respond_request'])) {
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
        
        // Notify student
        mysqli_query($conn, "INSERT INTO notifications (user_type, user_register, notification_type, title, message, link) 
                             VALUES ('{$req['from_type']}', '{$req['from_register']}', 'connection_request', 
                             'Connection Accepted!', 'Alumni {$pi_name} accepted your connection request', 'student_connections.php')");
        
        $success = "Connection request accepted!";
    } else {
        $error = "Connection request rejected.";
    }
}

// Fetch pending connection requests
$pending_query = "SELECT cr.*, si.st_name, si.st_email, si.st_branch, si.st_year
                  FROM connection_requests cr
                  JOIN studentinfo si ON cr.from_register = si.st_register
                  WHERE cr.to_register='$pi_register' AND cr.status='pending'
                  ORDER BY cr.request_date DESC";
$pending_requests = mysqli_query($conn, $pending_query);

// Fetch my connections (students)
$connections_query = "SELECT uc.*, si.st_name, si.st_email, si.st_branch, si.st_year, 
                      uc.last_interaction, uc.interaction_count
                      FROM user_connections uc
                      JOIN studentinfo si ON uc.user1_register = si.st_register
                      WHERE uc.user2_register='$pi_register' AND uc.user2_type='alumni'
                      ORDER BY uc.connection_date DESC";
$connections = mysqli_query($conn, $connections_query);

// Stats
$total_connections = mysqli_num_rows($connections);
$total_pending = mysqli_num_rows($pending_requests);
$total_interactions = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(interaction_count) as total FROM user_connections WHERE user2_register='$pi_register'"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Student Network - Alumni Connect</title>
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
}

.header {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.header h1 {
    color: #667eea;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.section h2 {
    color: #667eea;
    margin-bottom: 20px;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.request-card, .connection-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.request-card:hover, .connection-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    font-weight: bold;
    margin-right: 15px;
}

.card-info h4 {
    color: #333;
    font-size: 1.1rem;
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

.request-message {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin: 10px 0;
    font-style: italic;
    color: #666;
    border-left: 3px solid #667eea;
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

.btn-accept {
    background: #28a745;
    color: white;
}

.btn-reject {
    background: #dc3545;
    color: white;
}

.btn-message {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

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

.stat-label-small {
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

.back-btn {
    display: inline-block;
    padding: 12px 25px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    margin-top: 20px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👥 My Student Network</h1>
        <p>Connect with students, guide them, and make an impact</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_connections; ?></div>
                <div class="stat-label">Total Connections</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                <div class="stat-number"><?php echo $total_pending; ?></div>
                <div class="stat-label">Pending Requests</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);">
                <div class="stat-number"><?php echo $total_interactions; ?></div>
                <div class="stat-label">Total Interactions</div>
            </div>
        </div>
    </div>
    
    <?php if (isset($success)) { ?>
        <div class="message success">✅ <?php echo $success; ?></div>
    <?php } ?>
    
    <?php if (isset($error)) { ?>
        <div class="message error">❌ <?php echo $error; ?></div>
    <?php } ?>
    
    <div class="content-grid">
        <!-- Pending Requests -->
        <div class="section">
            <h2>⏳ Pending Connection Requests</h2>
            
            <?php if (mysqli_num_rows($pending_requests) > 0) { ?>
                <?php while ($req = mysqli_fetch_assoc($pending_requests)) { 
                    $initials = strtoupper(substr($req['st_name'], 0, 1));
                ?>
                    <div class="request-card">
                        <div class="card-header">
                            <div class="avatar"><?php echo $initials; ?></div>
                            <div class="card-info">
                                <h4><?php echo htmlspecialchars($req['st_name']); ?></h4>
                                <p><?php echo htmlspecialchars($req['st_branch']); ?> - Year <?php echo $req['st_year']; ?></p>
                            </div>
                        </div>
                        
                        <div class="card-details">
                            <div class="detail-row">
                                <i>📧</i> <?php echo htmlspecialchars($req['st_email']); ?>
                            </div>
                            <div class="detail-row">
                                <i>📅</i> Requested on <?php echo date('M d, Y', strtotime($req['request_date'])); ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($req['message'])) { ?>
                            <div class="request-message">
                                💬 "<?php echo htmlspecialchars($req['message']); ?>"
                            </div>
                        <?php } ?>
                        
                        <form method="POST">
                            <input type="hidden" name="request_id" value="<?php echo $req['request_id']; ?>">
                            <div class="card-actions">
                                <button type="submit" name="action" value="accept" class="btn btn-accept">
                                    ✅ Accept
                                </button>
                                <button type="submit" name="action" value="reject" class="btn btn-reject">
                                    ❌ Decline
                                </button>
                            </div>
                            <input type="hidden" name="respond_request" value="1">
                        </form>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">⏳</div>
                    <p>No pending requests</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- My Connections -->
        <div class="section">
            <h2>🤝 My Student Connections</h2>
            
            <?php 
            mysqli_data_seek($connections, 0);
            if (mysqli_num_rows($connections) > 0) { ?>
                <?php while ($conn_data = mysqli_fetch_assoc($connections)) { 
                    $initials = strtoupper(substr($conn_data['st_name'], 0, 1));
                ?>
                    <div class="connection-card">
                        <div class="card-header">
                            <div class="avatar"><?php echo $initials; ?></div>
                            <div class="card-info">
                                <h4><?php echo htmlspecialchars($conn_data['st_name']); ?></h4>
                                <p><?php echo htmlspecialchars($conn_data['st_branch']); ?> - Year <?php echo $conn_data['st_year']; ?></p>
                            </div>
                        </div>
                        
                        <div class="card-details">
                            <div class="detail-row">
                                <i>📧</i> <?php echo htmlspecialchars($conn_data['st_email']); ?>
                            </div>
                            <div class="detail-row">
                                <i>🔗</i> Connected since <?php echo date('M Y', strtotime($conn_data['connection_date'])); ?>
                            </div>
                        </div>
                        
                        <div class="interaction-stats">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $conn_data['interaction_count'] ?? 0; ?></div>
                                <div class="stat-label-small">Interactions</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">
                                    <?php 
                                    if ($conn_data['last_interaction']) {
                                        $date = new DateTime($conn_data['last_interaction']);
                                        echo $date->format('M d');
                                    } else {
                                        echo 'Never';
                                    }
                                    ?>
                                </div>
                                <div class="stat-label-small">Last Contact</div>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <button class="btn btn-message" onclick="window.location.href='alumni_chat.php?user=<?php echo $conn_data['user1_register']; ?>'">
                                💬 Message
                            </button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">🤝</div>
                    <p>No connections yet</p>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <a href="alumnipage.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>