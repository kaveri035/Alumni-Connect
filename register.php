<?php
include_once 'db_config.php';
session_start();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        
        if ($user_type == 'admin') {
            // Admin Registration
            $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
            $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
            
            // Validate admin fields
            if (empty($admin_id) || empty($fullname) || empty($password)) {
                $error = "All fields are required for admin registration!";
            } else {
                // Check if admin ID exists
                $check = mysqli_query($conn, "SELECT * FROM adminmember WHERE ad_id = '$admin_id'");
                if (mysqli_num_rows($check) > 0) {
                    $error = "Admin ID already exists!";
                } else {
                    $query = "INSERT INTO adminmember (ad_id, ad_password, ad_fullname) 
                              VALUES ('$admin_id', '$password', '$fullname')";
                    if (mysqli_query($conn, $query)) {
                        header("Location: login.php?registered=1");
                        exit();
                    } else {
                        $error = "Registration failed: " . mysqli_error($conn);
                    }
                }
            }
            
        } elseif ($user_type == 'alumni') {
            // Alumni Registration
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $register = mysqli_real_escape_string($conn, $_POST['register_number']);
            $branch = mysqli_real_escape_string($conn, $_POST['branch']);
            $session = mysqli_real_escape_string($conn, $_POST['session']);
            $company = mysqli_real_escape_string($conn, $_POST['company']);
            
            // Check if registration number exists
            $check = mysqli_query($conn, "SELECT * FROM alumnimember WHERE pi_register = '$register'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Registration number already exists!";
            } else {
                // Insert into alumnimember
                $query1 = "INSERT INTO alumnimember (pi_register, al_password, al_status) 
                           VALUES ('$register', '$password', 'Pending')";
                
                if (mysqli_query($conn, $query1)) {
                    // Insert into alumniinfo
                    $query2 = "INSERT INTO alumniinfo (pi_name, pi_gender, pi_address, pi_email, 
                              pi_session, pi_branch, pi_mobile, pi_company, pi_register) 
                              VALUES ('$name', '$gender', '$address', '$email', '$session', 
                              '$branch', '$mobile', '$company', '$register')";
                    
                    if (mysqli_query($conn, $query2)) {
                        header("Location: login.php?alumni_registered=1");
                        exit();
                    }
                }
            }
            
        } elseif ($user_type == 'student') {
            // Student Registration
            $name = mysqli_real_escape_string($conn, $_POST['st_name']);
            $gender = mysqli_real_escape_string($conn, $_POST['st_gender']);
            $email = mysqli_real_escape_string($conn, $_POST['st_email']);
            $mobile = mysqli_real_escape_string($conn, $_POST['st_mobile']);
            $address = mysqli_real_escape_string($conn, $_POST['st_address']);
            $register = mysqli_real_escape_string($conn, $_POST['st_register']);
            $branch = mysqli_real_escape_string($conn, $_POST['st_branch']);
            $year = mysqli_real_escape_string($conn, $_POST['st_year']);
            
            // Check if registration number exists
            $check = mysqli_query($conn, "SELECT * FROM studentmember WHERE st_register = '$register'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Registration number already exists!";
            } else {
                // Insert into studentmember
                $query1 = "INSERT INTO studentmember (st_register, st_password, st_status) 
                           VALUES ('$register', '$password', 'Active')";
                
                if (mysqli_query($conn, $query1)) {
                    // Insert into studentinfo
                    $query2 = "INSERT INTO studentinfo (st_name, st_gender, st_address, st_email, 
                              st_branch, st_year, st_mobile, st_register) 
                              VALUES ('$name', '$gender', '$address', '$email', 
                              '$branch', '$year', '$mobile', '$register')";
                    
                    if (mysqli_query($conn, $query2)) {
                        header("Location: login.php?registered=1");
                        exit();
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SGBIT Alumni Connect</title>
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
            position: relative;
            overflow-x: hidden;
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

        .register-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 25px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
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

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            text-align: center;
        }

        .register-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .register-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .register-body {
            padding: 3rem;
        }

        .alert {
            padding: 1rem;
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

        .alert-error {
            background: #fee;
            color: #c00;
            border-left: 4px solid #c00;
        }

        .user-type-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .type-card {
            padding: 1.5rem;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .type-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .type-card .icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .type-card h3 {
            font-size: 1.2rem;
        }

        .type-card input[type="radio"] {
            display: none;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group label span {
            color: #ff4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .register-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .form-content {
            display: none;
        }

        .form-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .user-type-selector {
                grid-template-columns: 1fr;
            }

            .register-header {
                padding: 2rem;
            }

            .register-header h1 {
                font-size: 2rem;
            }

            .register-body {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>🎓 Create Your Account</h1>
            <p>Join the SGBIT Community Today!</p>
        </div>

        <div class="register-body">
            <?php if ($error): ?>
                <div class="alert alert-error">❌ <?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="registerForm">
                <!-- User Type Selection -->
                <div class="user-type-selector">
                    <label class="type-card" onclick="selectUserType('admin')">
                        <input type="radio" name="user_type" value="admin" required>
                        <div class="icon">👨‍💼</div>
                        <h3>Admin</h3>
                    </label>

                    <label class="type-card" onclick="selectUserType('alumni')">
                        <input type="radio" name="user_type" value="alumni" required>
                        <div class="icon">🎓</div>
                        <h3>Alumni</h3>
                    </label>

                    <label class="type-card" onclick="selectUserType('student')">
                        <input type="radio" name="user_type" value="student" required>
                        <div class="icon">📚</div>
                        <h3>Student</h3>
                    </label>
                </div>

                <!-- Admin Registration Form -->
                <div id="adminForm" class="form-content">
                    <div class="form-section">
                        <h3>👨‍💼 Admin Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Admin ID: <span>*</span></label>
                                <input type="text" name="admin_id" id="admin_id" placeholder="e.g., AD1" required>
                            </div>
                            <div class="form-group">
                                <label>Full Name: <span>*</span></label>
                                <input type="text" name="fullname" id="fullname" placeholder="Enter full name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>🔒 Security</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Password: <span>*</span></label>
                                <input type="password" name="password" id="admin_password" minlength="6" placeholder="At least 6 characters" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password: <span>*</span></label>
                                <input type="password" name="confirm_password" id="admin_confirm" minlength="6" placeholder="Re-enter password" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alumni Registration Form -->
                <div id="alumniForm" class="form-content">
                    <div class="form-section">
                        <h3>📋 Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name: <span>*</span></label>
                                <input type="text" name="name">
                            </div>
                            <div class="form-group">
                                <label>Gender: <span>*</span></label>
                                <select name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email: <span>*</span></label>
                                <input type="email" name="email">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number: <span>*</span></label>
                                <input type="text" name="mobile" pattern="[0-9]{10}" placeholder="10 digits">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Address: <span>*</span></label>
                            <textarea name="address"></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>🎓 Academic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Registration Number: <span>*</span></label>
                                <input type="text" name="register_number" placeholder="e.g., R1">
                            </div>
                            <div class="form-group">
                                <label>Branch: <span>*</span></label>
                                <select name="branch">
                                    <option value="">Select Branch</option>
                                    <option value="CSE">Computer Science (CSE)</option>
                                    <option value="ECE">Electronics (ECE)</option>
                                    <option value="MECH">Mechanical (MECH)</option>
                                    <option value="IT">Information Technology (IT)</option>
                                    <option value="EE">Electrical (EE)</option>
                                    <option value="CIVIL">Civil</option>
                                    <option value="AIEI">AI & ML</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Session: <span>*</span></label>
                                <input type="text" name="session" placeholder="e.g., 2016-2020">
                            </div>
                            <div class="form-group">
                                <label>Current Company:</label>
                                <input type="text" name="company" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>🔒 Security</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Password: <span>*</span></label>
                                <input type="password" name="password" minlength="6">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password: <span>*</span></label>
                                <input type="password" name="confirm_password" minlength="6">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Registration Form -->
                <div id="studentForm" class="form-content">
                    <div class="form-section">
                        <h3>📋 Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name: <span>*</span></label>
                                <input type="text" name="st_name">
                            </div>
                            <div class="form-group">
                                <label>Gender: <span>*</span></label>
                                <select name="st_gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email: <span>*</span></label>
                                <input type="email" name="st_email">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number: <span>*</span></label>
                                <input type="text" name="st_mobile" pattern="[0-9]{10}" placeholder="10 digits">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Address: <span>*</span></label>
                            <textarea name="st_address"></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>🎓 Academic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Registration Number: <span>*</span></label>
                                <input type="text" name="st_register" placeholder="e.g., S1">
                            </div>
                            <div class="form-group">
                                <label>Branch: <span>*</span></label>
                                <select name="st_branch">
                                    <option value="">Select Branch</option>
                                    <option value="CSE">Computer Science (CSE)</option>
                                    <option value="ECE">Electronics (ECE)</option>
                                    <option value="MECH">Mechanical (MECH)</option>
                                    <option value="IT">Information Technology (IT)</option>
                                    <option value="EE">Electrical (EE)</option>
                                    <option value="CIVIL">Civil</option>
                                    <option value="AIEI">AI & ML</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Current Year: <span>*</span></label>
                                <select name="st_year">
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><!-- Empty for spacing --></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>🔒 Security</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Password: <span>*</span></label>
                                <input type="password" name="password" minlength="6">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password: <span>*</span></label>
                                <input type="password" name="confirm_password" minlength="6">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">Register Now</button>
            </form>

            <div class="register-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p style="margin-top: 0.5rem;"><a href="index.php">← Back to Home</a></p>
            </div>
        </div>
    </div>

    <script>
        function selectUserType(type) {
            // Remove active class from all cards
            document.querySelectorAll('.type-card').forEach(card => {
                card.classList.remove('active');
            });

            // Add active class to selected card
            event.currentTarget.classList.add('active');

            // Check the radio button
            document.querySelector(`input[value="${type}"]`).checked = true;

            // Hide all forms
            document.querySelectorAll('.form-content').forEach(form => {
                form.classList.remove('active');
            });

            // Show selected form
            if (type === 'admin') {
                document.getElementById('adminForm').classList.add('active');
                // Make admin fields required
                document.getElementById('admin_id').required = true;
                document.getElementById('fullname').required = true;
                document.getElementById('admin_password').required = true;
                document.getElementById('admin_confirm').required = true;
            } else if (type === 'alumni') {
                document.getElementById('alumniForm').classList.add('active');
                // Remove admin required
                document.getElementById('admin_id').required = false;
                document.getElementById('fullname').required = false;
                document.getElementById('admin_password').required = false;
                document.getElementById('admin_confirm').required = false;
            } else if (type === 'student') {
                document.getElementById('studentForm').classList.add('active');
                // Remove admin required
                document.getElementById('admin_id').required = false;
                document.getElementById('fullname').required = false;
                document.getElementById('admin_password').required = false;
                document.getElementById('admin_confirm').required = false;
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const userType = document.querySelector('input[name="user_type"]:checked');
            
            if (!userType) {
                e.preventDefault();
                alert('Please select a user type!');
                return false;
            }

            // Disable fields in inactive forms to prevent them from being submitted
            document.querySelectorAll('.form-content:not(.active) input, .form-content:not(.active) select, .form-content:not(.active) textarea').forEach(field => {
                field.disabled = true;
            });

            // Get password from the active form
            let password, confirmPassword;
            if (userType.value === 'admin') {
                password = document.getElementById('admin_password').value;
                confirmPassword = document.getElementById('admin_confirm').value;
            } else {
                const activeForm = document.querySelector('.form-content.active');
                password = activeForm.querySelector('input[name="password"]').value;
                confirmPassword = activeForm.querySelector('input[name="confirm_password"]').value;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                // Re-enable all fields
                document.querySelectorAll('input, select, textarea').forEach(field => {
                    field.disabled = false;
                });
                alert('Passwords do not match!');
                return false;
            }

            // Additional validation for admin
            if (userType.value === 'admin') {
                const adminId = document.getElementById('admin_id').value.trim();
                const fullname = document.getElementById('fullname').value.trim();
                
                if (!adminId || !fullname || !password) {
                    e.preventDefault();
                    // Re-enable all fields
                    document.querySelectorAll('input, select, textarea').forEach(field => {
                        field.disabled = false;
                    });
                    alert('Please fill all required fields for admin registration!\nAdmin ID: ' + (adminId ? 'OK' : 'MISSING') + '\nName: ' + (fullname ? 'OK' : 'MISSING') + '\nPassword: ' + (password ? 'OK' : 'MISSING'));
                    return false;
                }
            }
        });
    </script>
</body>
</html>