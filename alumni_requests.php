<?php
include_once 'db_config.php';
session_start();

// Verify alumni login
if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];

// Handle accept/reject
if (isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    // Update mentorship_requests table
    mysqli_query($conn, "UPDATE mentorship_requests SET status='$status', response_date=NOW() WHERE request_id='$request_id'");

    // If accepted → insert into mentorship table
    if ($status == 'Accepted') {
        $getRequest = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st_register, mentor_pi_register FROM mentorship_requests WHERE request_id='$request_id'"));
        $st_register = $getRequest['st_register'];
        $mentor_pi_register = $getRequest['mentor_pi_register'];

        mysqli_query($conn, "INSERT INTO mentorship (mentor_pi_register, mentee_st_register, status, start_date) VALUES ('$mentor_pi_register', '$st_register', 'active', NOW())");
    }

    header("Location: alumni_requests.php");
    exit();
}

// Fetch mentorship requests
$sql = "SELECT r.request_id, si.st_name, si.st_email, si.st_branch, si.st_year, r.status, r.request_date
        FROM mentorship_requests r
        JOIN studentinfo si ON r.st_register = si.st_register
        WHERE r.mentor_pi_register = '$pi_register'
        ORDER BY 
            CASE r.status 
                WHEN 'Pending' THEN 1 
                WHEN 'Accepted' THEN 2 
                WHEN 'Rejected' THEN 3 
            END,
            r.request_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Mentorship Requests</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 900px;
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

.card {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    border-left: 5px solid #667eea;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.card.pending {
    border-left-color: #ff9800;
}

.card.accepted {
    border-left-color: #4CAF50;
}

.card.rejected {
    border-left-color: #f44336;
}

.card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.card-info {
    margin: 8px 0;
    font-size: 0.95rem;
}

.info-label {
    font-weight: 600;
    color: #555;
}

.status-badge {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 10px 5px 10px 0;
}

.badge-pending {
    background: #ff9800;
    color: white;
}

.badge-accepted {
    background: #4CAF50;
    color: white;
}

.badge-rejected {
    background: #f44336;
    color: white;
}

.btn-group {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn {
    flex: 1;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.accept {
    background: #4CAF50;
}

.accept:hover {
    background: #45a049;
    transform: scale(1.02);
}

.reject {
    background: #f44336;
}

.reject:hover {
    background: #da190b;
    transform: scale(1.02);
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
    margin-top: 20px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.no-requests {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 1.1rem;
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
    <h2>📩 Mentorship Requests</h2>
    
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card <?php echo strtolower($row['status']); ?>">
                <h3>👨‍🎓 <?php echo htmlspecialchars($row['st_name']); ?></h3>
                <div class="card-info">
                    <span class="info-label">📧 Email:</span> <?php echo htmlspecialchars($row['st_email']); ?>
                </div>
                <div class="card-info">
                    <span class="info-label">🎓 Branch:</span> <?php echo htmlspecialchars($row['st_branch']); ?>
                </div>
                <div class="card-info">
                    <span class="info-label">📅 Year:</span> <?php echo htmlspecialchars($row['st_year']); ?>
                </div>
                <div class="card-info">
                    <span class="info-label">📆 Requested:</span> <?php echo date('d M Y, h:i A', strtotime($row['request_date'])); ?>
                </div>
                
                <span class="status-badge badge-<?php echo strtolower($row['status']); ?>">
                    <?php echo $row['status']; ?>
                </span>

                <?php if ($row['status'] == 'Pending') { ?>
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                    <div class="btn-group">
                        <button type="submit" name="status" value="Accepted" class="btn accept">
                            ✅ Accept
                        </button>
                        <button type="submit" name="status" value="Rejected" class="btn reject">
                            ❌ Reject
                        </button>
                    </div>
                    <input type="hidden" name="update_status" value="1">
                </form>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="no-requests">
            <p>📭 No mentorship requests found.</p>
            <p style="font-size: 0.9rem; margin-top: 10px; color: #999;">Students will see your profile when they search for mentors.</p>
        </div>
    <?php } ?>
    
    <a href="alumnipage.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>