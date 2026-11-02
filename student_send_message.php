<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message - SGBIT Alumni Connect</title>
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
        }

        /* Navigation Bar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .nav-container {
            display: flex;
            align-items: center;
            padding: 0.5rem 3%;
            gap: 1rem;
        }

        .nav-logo {
            width: 55px;
            height: 55px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            flex: 1;
            align-items: center;
        }

        .nav-link {
            display: block;
            padding: 0.9rem 1.5rem;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .user-section {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.7rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 25px;
            color: white;
        }

        .logout-btn {
            background: white;
            color: #667eea;
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid white;
        }

        .logout-btn:hover {
            background: transparent;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 3rem 5%;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Message Form Container */
        .message-form-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .form-group label span {
            color: #ff4444;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            min-height: 200px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
            transform: translateY(-3px);
        }

        /* Character Counter */
        .char-counter {
            text-align: right;
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Success Message */
        .success-message {
            background: #4ade80;
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: none;
            animation: slideDown 0.5s ease;
        }

        .success-message.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .message-form-container {
                padding: 2rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <img src="image/logo.jpg" alt="SGBIT Logo" class="nav-logo">
            <ul class="nav-links">
                <li><a href="studentpage.php" class="nav-link">Home</a></li>
                <li><a href="student_messages.php" class="nav-link">Messages</a></li>
                <li><a href="student_forum.php" class="nav-link">E-Forum</a></li>
                <li><a href="search_alumni.php" class="nav-link">Search Alumni</a></li>
            </ul>
            <div class="user-section">
                <span>Welcome, Student</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>📧 Send Message</h1>
            <p>Connect with alumni and fellow students</p>
        </div>

        <!-- Success Message -->
        <div class="success-message" id="successMessage">
            ✓ Message sent successfully!
        </div>

        <!-- Message Form -->
        <div class="message-form-container">
            <form id="messageForm" method="POST" action="process_send_message.php">
                <!-- Recipient -->
                <div class="form-group">
                    <label for="recipient">To <span>*</span></label>
                    <select id="recipient" name="recipient" class="form-control" required>
                        <option value="">Select Recipient</option>
                        <optgroup label="Alumni">
                            <option value="alumni_1">John Doe (Batch 2020 - CSE)</option>
                            <option value="alumni_2">Jane Smith (Batch 2019 - ECE)</option>
                            <option value="alumni_3">Mike Johnson (Batch 2021 - ME)</option>
                            <option value="alumni_4">Sarah Williams (Batch 2018 - Civil)</option>
                        </optgroup>
                        <optgroup label="Students">
                            <option value="student_1">Alex Kumar (CSE - 3rd Year)</option>
                            <option value="student_2">Priya Sharma (ECE - 2nd Year)</option>
                        </optgroup>
                        <optgroup label="Admin">
                            <option value="admin_1">Alumni Admin</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Subject -->
                <div class="form-group">
                    <label for="subject">Subject <span>*</span></label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        class="form-control" 
                        placeholder="Enter message subject"
                        maxlength="200"
                        required
                    >
                    <div class="char-counter">
                        <span id="subjectCounter">0</span>/200 characters
                    </div>
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label for="message">Message <span>*</span></label>
                    <textarea 
                        id="message" 
                        name="message" 
                        class="form-control" 
                        placeholder="Type your message here..."
                        maxlength="2000"
                        required
                    ></textarea>
                    <div class="char-counter">
                        <span id="messageCounter">0</span>/2000 characters
                    </div>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                    <a href="student_messages.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character Counter for Subject
        const subjectInput = document.getElementById('subject');
        const subjectCounter = document.getElementById('subjectCounter');
        
        subjectInput.addEventListener('input', function() {
            subjectCounter.textContent = this.value.length;
        });

        // Character Counter for Message
        const messageInput = document.getElementById('message');
        const messageCounter = document.getElementById('messageCounter');
        
        messageInput.addEventListener('input', function() {
            messageCounter.textContent = this.value.length;
        });

        // Form Submission
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');
            
            // Reset form
            this.reset();
            subjectCounter.textContent = '0';
            messageCounter.textContent = '0';
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
            
            // In real implementation, this would submit to PHP
            // this.submit();
        });
    </script>
</body>
</html>