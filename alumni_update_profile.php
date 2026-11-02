<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];
$success = "";
$error = "";

// Get current alumni data
$query = "SELECT * FROM alumniinfo WHERE pi_register = '$pi_register'";
$result = mysqli_query($conn, $query);
$alumni = mysqli_fetch_assoc($result);

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    
    $update = "UPDATE alumniinfo SET 
               pi_email = '$email', 
               pi_mobile = '$mobile', 
               pi_address = '$address',
               pi_company = '$company'
               WHERE pi_register = '$pi_register'";
    
    if (mysqli_query($conn, $update)) {
        $success = "Profile updated successfully!";
        // Refresh data
        $result = mysqli_query($conn, $query);
        $alumni = mysqli_fetch_assoc($result);
    } else {
        $error = "Failed to update profile!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile - SGBIT Alumni</title>
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group input:disabled {
            background: #f0f0f0;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Update Profile</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" value="<?php echo $alumni['pi_name']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Registration Number:</label>
                <input type="text" value="<?php echo $alumni['pi_register']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $alumni['pi_email']; ?>" required>
            </div>

            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile" value="<?php echo $alumni['pi_mobile']; ?>" pattern="[0-9]{10}" required>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" required><?php echo $alumni['pi_address']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Current Company:</label>
                <input type="text" name="company" value="<?php echo $alumni['pi_company']; ?>">
            </div>

            <div class="form-group">
                <label>Branch:</label>
                <input type="text" value="<?php echo $alumni['pi_branch']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Session:</label>
                <input type="text" value="<?php echo $alumni['pi_session']; ?>" disabled>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="alumni_home.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

