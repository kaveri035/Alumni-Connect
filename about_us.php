<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About Us</title>

</head>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SGBIT Alumni Connect</title>
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
            padding: 1rem 5%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #667eea;
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .cta-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .auth-buttons .login-btn {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .auth-buttons .login-btn:hover {
            background: #667eea;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 3rem 5%;
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

        /* Hero Section */
        .about-hero {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .about-hero p {
            font-size: 1.2rem;
            line-height: 1.8;
            opacity: 0.95;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Content Container */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Function Section */
        .function-section {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .function-section h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Feature Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .feature-box:nth-child(1) { animation-delay: 0.1s; }
        .feature-box:nth-child(2) { animation-delay: 0.2s; }
        .feature-box:nth-child(3) { animation-delay: 0.3s; }
        .feature-box:nth-child(4) { animation-delay: 0.4s; }
        .feature-box:nth-child(5) { animation-delay: 0.5s; }
        .feature-box:nth-child(6) { animation-delay: 0.6s; }
        .feature-box:nth-child(7) { animation-delay: 0.7s; }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .feature-box h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .feature-box p {
            line-height: 1.6;
            opacity: 0.9;
            text-align: center;
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        /* Scroll to Top Button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
            z-index: 999;
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .scroll-top.show {
            display: flex;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .about-hero h1 {
                font-size: 2rem;
            }

            .about-hero p {
                font-size: 1rem;
            }

            .nav-links {
                display: none;
            }

            .function-section {
                padding: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">SGBIT Alumni</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about_us.php">About</a></li>
            <li><a href="eventss.php">Events</a></li>
            <li><a href="contact_us.php">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="register.php" class="cta-button">Register Now</a>
            <a href="login.php" class="cta-button login-btn">Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <div class="about-hero">
            <h1>SGBIT Alumni Management Association</h1>
            <p>The SGBIT Alumni Association supervises the graduate programs in SGBIT. This includes administering the admission, appointment of Alumni, Members reviews, provision of financial assistance and assessment.</p>
        </div>

        <!-- Content Container -->
        <div class="content-container">
            <!-- Function Section -->
            <div class="function-section">
                <h2>Functions of Alumni Management System</h2>
                
                <!-- Features Grid -->
                <div class="features-grid">
                    <div class="feature-box">
                        <div class="feature-icon">📢</div>
                        <h3>Promote Programs</h3>
                        <p>Promoting SGBIT's graduate programs and building awareness in the community</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">💼</div>
                        <h3>Career Placement</h3>
                        <p>Facilitate the appointment of Alumni after graduation through networking opportunities</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">💰</div>
                        <h3>Financial Support</h3>
                        <p>Oversees the provision of financial assistance to deserving students and alumni</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">🎉</div>
                        <h3>Alumni Events</h3>
                        <p>Organises events for alumni to reconnect and strengthen the community bond</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">💬</div>
                        <h3>Communication Hub</h3>
                        <p>Facilitate communication between alumni after graduation across all batches</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">📊</div>
                        <h3>Assessment Coordination</h3>
                        <p>Coordinate the assessment for graduation purposes and quality assurance</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">🔬</div>
                        <h3>Research Enhancement</h3>
                        <p>Organise activities to enhance graduate students research capabilities and innovation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 SGBIT Alumni Connect. All rights reserved.</p>
        <p>Building bridges between alumni and students for a brighter future.</p>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-top" id="scrollTop">↑</div>

    <script>
        // Scroll to top button
        const scrollTopBtn = document.getElementById('scrollTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>