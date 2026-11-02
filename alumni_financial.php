<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];

// Get all payments for this alumni
$query = "SELECT * FROM financialdata WHERE pi_register = '$pi_register' ORDER BY payment_date DESC";
$payments = mysqli_query($conn, $query);

// Calculate total
$total_query = "SELECT SUM(total_payment) as total FROM financialdata WHERE pi_register = '$pi_register'";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_paid = $total_data['total'] ? $total_data['total'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Records - SGBIT Alumni</title>
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

        .total-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .total-card h2 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
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
            <a href="alumni_home.php" class="btn">← Back</a>
        </div>

        <div class="total-card">
            <p style="opacity: 0.9; margin-bottom: 0.5rem;">Total Amount Paid</p>
            <h2>₹<?php echo number_format($total_paid, 2); ?></h2>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = mysqli_fetch_assoc($payments)): ?>
                    <tr>
                        <td><?php echo $payment['payment_id']; ?></td>
                        <td><?php echo $payment['payment_purpose']; ?></td>
                        <td><strong>₹<?php echo number_format($payment['total_payment'], 2); ?></strong></td>
                        <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>

                <?php if (mysqli_num_rows($payments) == 0): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem; color: #666;">
                            No payment records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>