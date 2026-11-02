<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// Add new payment
if (isset($_POST['add_payment'])) {
    $pi_register = mysqli_real_escape_string($conn, $_POST['pi_register']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    
    // Generate payment ID
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM financialdata"))['count'];
    $payment_id = 'P' . ($count + 1);
    
    $query = "INSERT INTO financialdata (payment_id, total_payment, payment_purpose, pi_register, payment_date) 
              VALUES ('$payment_id', '$amount', '$purpose', '$pi_register', '$date')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Payment added successfully!";
    } else {
        $error = "Failed to add payment!";
    }
}

// Get all approved alumni for dropdown
$alumni_query = "SELECT ai.pi_register, ai.pi_name 
                 FROM alumniinfo ai 
                 JOIN alumnimember am ON ai.pi_register = am.pi_register 
                 WHERE am.al_status = 'Approve' 
                 ORDER BY ai.pi_name";
$alumni = mysqli_query($conn, $alumni_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Payment - SGBIT Admin</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #667eea;
            margin-bottom: 2rem;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💳 Add New Payment</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Select Alumni:</label>
                <select name="pi_register" required>
                    <option value="">-- Select Alumni --</option>
                    <?php while ($al = mysqli_fetch_assoc($alumni)): ?>
                        <option value="<?php echo $al['pi_register']; ?>">
                            <?php echo $al['pi_name']; ?> (<?php echo $al['pi_register']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Payment Purpose:</label>
                <select name="purpose" required>
                    <option value="">-- Select Purpose --</option>
                    <option value="Yearly Membership">Yearly Membership (₹1000)</option>
                    <option value="Life-time Membership">Life-time Membership (₹2000)</option>
                    <option value="Registration Fee">Registration Fee (₹250)</option>
                    <option value="Cash Donation">Cash Donation</option>
                </select>
            </div>

            <div class="form-group">
                <label>Amount (₹):</label>
                <input type="number" name="amount" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label>Payment Date:</label>
                <input type="date" name="payment_date" required>
            </div>

            <div class="btn-group">
                <button type="submit" name="add_payment" class="btn btn-primary">Add Payment</button>
                <a href="Financial_Record.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>