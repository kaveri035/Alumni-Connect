<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$success = "";
$error = "";

// Get current student data
$query = "SELECT * FROM studentinfo WHERE st_register = '$st_register'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $mobile = mysqli_real_escape_string($conn, trim($_POST['mobile']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    
    $update = "UPDATE studentinfo SET 
               st_email = '$email', 
               st_mobile = '$mobile', 
               st_address = '$address' 
               WHERE st_register = '$st_register'";
    
    if (mysqli_query($conn, $update)) {
        $success = "Profile updated successfully!";
        // Refresh data
        $result = mysqli_query($conn, $query);
        $student = mysqli_fetch_assoc($result);
    } else {
        $error = "Failed to update profile: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - SGBIT Alumni Connect</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 28px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
            display: inline-block;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            gap: 10px;
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

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input:disabled {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .readonly-badge {
            display: inline-block;
            background: #e0e0e0;
            color: #666;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 8px;
            font-weight: normal;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            width: 100%;
            padding: 15px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .info-box {
            background: #e8eaf6;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #333;
            font-size: 14px;
        }

        .info-box strong {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Update Profile</h1>
            <div class="btn-group">
                <a href="studentpage.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>

        <div class="form-card">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span style="font-size: 20px;">✅</span>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <span style="font-size: 20px;">❌</span>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <strong>ℹ️ Note:</strong> You can update your contact information below. Name and Registration Number cannot be changed.
            </div>

            <form method="POST" action="">
                <div class="form-section">
                    <h3>📋 Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Full Name 
                                <span class="readonly-badge">Read-only</span>
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars($student['st_name']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                Registration Number 
                                <span class="readonly-badge">Read-only</span>
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars($student['st_register']); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Branch 
                                <span class="readonly-badge">Read-only</span>
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars($student['st_branch']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                Current Year 
                                <span class="readonly-badge">Read-only</span>
                            </label>
                            <input type="text" value="Year <?php echo htmlspecialchars($student['st_year']); ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>📞 Contact Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span style="color: red;">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($student['st_email']); ?>" 
                                   placeholder="your.email@example.com"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Mobile Number <span style="color: red;">*</span></label>
                            <input type="text" 
                                   name="mobile" 
                                   value="<?php echo htmlspecialchars($student['st_mobile']); ?>" 
                                   pattern="[0-9]{10}"
                                   placeholder="10 digit mobile number"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address <span style="color: red;">*</span></label>
                        <textarea name="address" 
                                  placeholder="Enter your complete address"
                                  required><?php echo htmlspecialchars($student['st_address']); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit">💾 Save Changes</button>
                <a href="studentpage.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>