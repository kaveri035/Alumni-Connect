<?php
include_once 'db_config.php';
session_start();

// If already logged in, redirect
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: adminpage.php");
    } elseif ($_SESSION['user_type'] == 'alumni') {
        header("Location: alumnipage.php");
    } elseif ($_SESSION['user_type'] == 'student') {
        header("Location: studentpage.php");
    }
    exit();
}

$error = "";
$success = "";

if (isset($_GET['registered'])) {
    $success = "Registration successful! You can now login.";
}

if (isset($_GET['alumni_registered'])) {
    $success = "Alumni registration successful! Please wait for admin approval to login.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    
    if ($user_type == 'admin') {
        // Admin Login
        $query = "SELECT * FROM adminmember WHERE ad_id = '$username' AND ad_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['ad_id'] = $admin['ad_id'];
            $_SESSION['ad_fullname'] = $admin['ad_fullname'];
            $_SESSION['user_type'] = 'admin';
            header("Location: adminpage.php");
            exit();
        } else {
            $error = "Invalid admin credentials!";
        }
        
    } elseif ($user_type == 'alumni') {
        // Alumni Login
        $query = "SELECT am.*, ai.pi_name FROM alumnimember am 
                  JOIN alumniinfo ai ON am.pi_register = ai.pi_register 
                  WHERE am.pi_register = '$username' AND am.al_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $alumni = mysqli_fetch_assoc($result);
            
            if ($alumni['al_status'] == 'Approve') {
                $_SESSION['pi_register'] = $alumni['pi_register'];
                $_SESSION['pi_name'] = $alumni['pi_name'];
                $_SESSION['user_type'] = 'alumni';
                header("Location: alumnipage.php");
                exit();
            } else {
                $error = "Your account is pending admin approval. Please wait.";
            }
        } else {
            $error = "Invalid registration number or password!";
        }
        
    } elseif ($user_type == 'student') {
        // Student Login
        $query = "SELECT sm.*, si.st_name FROM studentmember sm 
                  JOIN studentinfo si ON sm.st_register = si.st_register 
                  WHERE sm.st_register = '$username' AND sm.st_password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $student = mysqli_fetch_assoc($result);
            
            if ($student['st_status'] == 'Active') {
                $_SESSION['st_register'] = $student['st_register'];
                $_SESSION['st_name'] = $student['st_name'];
                $_SESSION['user_type'] = 'student';
                header("Location: studentpage.php");
                exit();
            } else {
                $error = "Your account is inactive. Please contact admin.";
            }
        } else {
            $error = "Invalid registration number or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SGBIT Alumni Connect</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-wrapper {
            max-width: 950px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-side {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-side h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .login-side p {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .illustration {
            font-size: 8rem;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .form-side {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #666;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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

        .alert-error {
            background: #fee;
            color: #c00;
            border-left: 4px solid #c00;
        }

        .alert-success {
            background: #efe;
            color: #0a0;
            border-left: 4px solid #0a0;
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

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: #667eea;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.9rem 0.9rem 0.9rem 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group select {
            cursor: pointer;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            padding: 0 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .login-side {
                padding: 2rem;
            }

            .login-side h1 {
                font-size: 2rem;
            }

            .illustration {
                font-size: 5rem;
            }

            .form-side {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-side">
            <div class="illustration">🎓</div>
            <h1>SGBIT Alumni Connect</h1>
            <p>Welcome back! Connect with your fellow alumni, share experiences, and build lasting relationships.</p>
        </div>

        <div class="form-side">
            <div class="form-header">
                <h2>Login to Your Account</h2>
                <p>Enter your credentials to continue</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">❌ <?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="user_type">Login As:</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <select name="user_type" id="user_type" required>
                            <option value="">-- Select User Type --</option>
                            <option value="admin">Admin</option>
                            <option value="alumni">Alumni</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username / Registration Number:</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="text" name="username" id="username" placeholder="Enter your ID" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Login Now</button>
            </form>
            <div style="text-align: center; margin-top: 1rem;">
    <a href="forgot_password.php" style="color: #667eea; text-decoration: none;">
        Forgot Password?
    </a>
</div>

            
            <div class="form-footer">
                <p>Don't have an account? <a href="register.php">Register Now</a></p>
                <p style="margin-top: 0.5rem;"><a href="index.php">← Back to Home</a></p>
                
            </div>
        </div>
    </div>
</body>
</html>
