<!-- ===================================================== -->
<!-- FILE: manage_alumni.php (Complete with Database) -->
<!-- ===================================================== -->
<?php
include_once 'db_config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// Handle Approve/Reject/Delete actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = mysqli_real_escape_string($conn, $_GET['action']);
    $pi_register = mysqli_real_escape_string($conn, $_GET['id']);
    
    if ($action == 'approve') {
        $query = "UPDATE alumnimember SET al_status = 'Approve' WHERE pi_register = '$pi_register'";
        if (mysqli_query($conn, $query)) {
            $success = "Alumni approved successfully! They can now login.";
        } else {
            $error = "Failed to approve alumni!";
        }
    } 
    elseif ($action == 'reject') {
        $query = "UPDATE alumnimember SET al_status = 'Rejected' WHERE pi_register = '$pi_register'";
        if (mysqli_query($conn, $query)) {
            $success = "Alumni status changed to Rejected!";
        } else {
            $error = "Failed to reject alumni!";
        }
    } 
    elseif ($action == 'delete') {
        // Delete from both tables (foreign key will handle it)
        $query1 = "DELETE FROM alumniinfo WHERE pi_register = '$pi_register'";
        $query2 = "DELETE FROM alumnimember WHERE pi_register = '$pi_register'";
        
        if (mysqli_query($conn, $query1) && mysqli_query($conn, $query2)) {
            $success = "Alumni deleted successfully!";
        } else {
            $error = "Failed to delete alumni!";
        }
    }
}

// Get all alumni with their status
$query = "SELECT ai.*, am.al_status, am.al_password 
          FROM alumniinfo ai 
          JOIN alumnimember am ON ai.pi_register = am.pi_register 
          ORDER BY 
            CASE am.al_status 
                WHEN 'Pending' THEN 1 
                WHEN 'Approve' THEN 2 
                WHEN 'Rejected' THEN 3 
            END, ai.pi_name ASC";
$alumni_result = mysqli_query($conn, $query);

// Count statistics
$pending_query = "SELECT COUNT(*) as count FROM alumnimember WHERE al_status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_count = mysqli_fetch_assoc($pending_result)['count'];

$approved_query = "SELECT COUNT(*) as count FROM alumnimember WHERE al_status = 'Approve'";
$approved_result = mysqli_query($conn, $approved_query);
$approved_count = mysqli_fetch_assoc($approved_result)['count'];

$rejected_query = "SELECT COUNT(*) as count FROM alumnimember WHERE al_status = 'Rejected'";
$rejected_result = mysqli_query($conn, $rejected_query);
$rejected_count = mysqli_fetch_assoc($rejected_result)['count'];

$total_count = mysqli_num_rows($alumni_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Alumni - SGBIT Alumni Connect</title>
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
            padding-top: 2rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        h1 {
            color: #667eea;
            font-size: 2.5rem;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            opacity: 0.95;
            font-size: 1.1rem;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.8rem 1.8rem;
            border: 2px solid #667eea;
            border-radius: 25px;
            background: white;
            color: #667eea;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table th {
            padding: 1.2rem;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        table td {
            padding: 1.2rem;
            border-bottom: 1px solid #e0e0e0;
        }

        table tbody tr {
            transition: background 0.3s;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approve {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-reject {
            background: #ffc107;
            color: #333;
        }

        .btn-reject:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            table {
                font-size: 0.85rem;
            }

            table th,
            table td {
                padding: 0.8rem;
            }

            .stat-card h3 {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>👥 Manage Alumni</h1>
            <a href="adminpage.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $pending_count; ?></h3>
                <p>⏳ Pending Approvals</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $approved_count; ?></h3>
                <p>✅ Approved Alumni</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $rejected_count; ?></h3>
                <p>❌ Rejected</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_count; ?></h3>
                <p>📊 Total Alumni</p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterTable('all')">All Alumni</button>
            <button class="filter-tab" onclick="filterTable('Pending')">Pending (<?php echo $pending_count; ?>)</button>
            <button class="filter-tab" onclick="filterTable('Approve')">Approved (<?php echo $approved_count; ?>)</button>
            <button class="filter-tab" onclick="filterTable('Rejected')">Rejected (<?php echo $rejected_count; ?>)</button>
        </div>

        <!-- Alumni Table -->
        <div class="table-container">
            <table id="alumniTable">
                <thead>
                    <tr>
                        <th>Reg. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Branch</th>
                        <th>Session</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($alumni_result) > 0):
                        while ($alumni = mysqli_fetch_assoc($alumni_result)): 
                    ?>
                        <tr data-status="<?php echo $alumni['al_status']; ?>">
                            <td><strong><?php echo $alumni['pi_register']; ?></strong></td>
                            <td><?php echo $alumni['pi_name']; ?></td>
                            <td><?php echo $alumni['pi_email']; ?></td>
                            <td><?php echo $alumni['pi_mobile']; ?></td>
                            <td><?php echo $alumni['pi_branch']; ?></td>
                            <td><?php echo $alumni['pi_session']; ?></td>
                            <td><?php echo $alumni['pi_company'] ? $alumni['pi_company'] : '-'; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($alumni['al_status']); ?>">
                                    <?php echo $alumni['al_status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($alumni['al_status'] == 'Pending'): ?>
                                        <a href="?action=approve&id=<?php echo $alumni['pi_register']; ?>" 
                                           class="btn btn-approve" 
                                           onclick="return confirm('Approve this alumni? They will be able to login.')">
                                            ✓ Approve
                                        </a>
                                        <a href="?action=reject&id=<?php echo $alumni['pi_register']; ?>" 
                                           class="btn btn-reject" 
                                           onclick="return confirm('Reject this alumni?')">
                                            ✗ Reject
                                        </a>
                                    <?php elseif ($alumni['al_status'] == 'Approve'): ?>
                                        <a href="?action=reject&id=<?php echo $alumni['pi_register']; ?>" 
                                           class="btn btn-reject" 
                                           onclick="return confirm('Revoke approval for this alumni?')">
                                            Revoke
                                        </a>
                                    <?php elseif ($alumni['al_status'] == 'Rejected'): ?>
                                        <a href="?action=approve&id=<?php echo $alumni['pi_register']; ?>" 
                                           class="btn btn-approve" 
                                           onclick="return confirm('Approve this alumni?')">
                                            Approve
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="?action=delete&id=<?php echo $alumni['pi_register']; ?>" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('⚠️ Delete this alumni permanently? This cannot be undone!')">
                                        🗑 Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <h3>No Alumni Found</h3>
                                    <p>No alumni have registered yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Filter table by status
        function filterTable(status) {
            const rows = document.querySelectorAll('#alumniTable tbody tr');
            const tabs = document.querySelectorAll('.filter-tab');
            
            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter rows
            let visibleCount = 0;
            rows.forEach(row => {
                if (row.dataset.status) {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show empty state if no results
            if (visibleCount === 0 && status !== 'all') {
                alert('No alumni found with status: ' + status);
            }
        }
    </script>
</body>
</html>