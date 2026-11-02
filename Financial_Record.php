<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

// Get all payments with alumni details
$query = "SELECT f.*, a.pi_name 
          FROM financialdata f 
          JOIN alumniinfo a ON f.pi_register = a.pi_register 
          ORDER BY f.payment_date DESC";
$payments = mysqli_query($conn, $query);

// Calculate totals
$total_query = "SELECT SUM(total_payment) as total FROM financialdata";
$total_result = mysqli_query($conn, $total_query);
$total_revenue = mysqli_fetch_assoc($total_result)['total'];

// Count by purpose
$yearly_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM financialdata WHERE payment_purpose LIKE '%Yearly%'"))['count'];
$lifetime_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM financialdata WHERE payment_purpose LIKE '%Life%'"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Records - SGBIT Admin</title>
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
            max-width: 1400px;
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
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f8f9fa;
        }

        table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        table tr:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Financial Records</h1>
            <div>
                <a href="admin_homepage.php" class="btn btn-back">← Back</a>
                <a href="NewPayment.php" class="btn btn-add">➕ Add Payment</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>₹<?php echo number_format($total_revenue, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
            <div class="stat-card">
                <h3><?php echo mysqli_num_rows($payments); ?></h3>
                <p>Total Payments</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $yearly_count; ?></h3>
                <p>Yearly Memberships</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $lifetime_count; ?></h3>
                <p>Lifetime Memberships</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Alumni Name</th>
                    <th>Reg. No.</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = mysqli_fetch_assoc($payments)): ?>
                    <tr>
                        <td><?php echo $payment['payment_id']; ?></td>
                        <td><?php echo $payment['pi_name']; ?></td>
                        <td><?php echo $payment['pi_register']; ?></td>
                        <td><?php echo $payment['payment_purpose']; ?></td>
                        <td><strong>₹<?php echo number_format($payment['total_payment'], 2); ?></strong></td>
                        <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (mysqli_num_rows($payments) == 0): ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No payment records yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

