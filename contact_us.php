<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SGBIT Alumni Connect</title>

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

        /* Page Header */
        .page-header {
            margin-top: 80px;
            padding: 4rem 5% 2rem;
            text-align: center;
            color: white;
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
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-header p {
            font-size: 1.3rem;
            opacity: 0.95;
        }

        /* Main Container */
        .main-container {
            padding: 2rem 5% 5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .contact-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .contact-card:nth-child(1) { animation-delay: 0.1s; }
        .contact-card:nth-child(2) { animation-delay: 0.2s; }
        .contact-card:nth-child(3) { animation-delay: 0.3s; }

        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .contact-card h3 {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 1rem;
            text-align: center;
        }

        .contact-info {
            color: #555;
            line-height: 2;
            text-align: center;
        }

        .contact-info a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .contact-info a:hover {
            color: #764ba2;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .social-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* FAQ Section */
        .faq-section {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .faq-section h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .faq-item {
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
            padding-left: 1.5rem;
            transition: all 0.3s;
        }

        .faq-item:hover {
            border-left-color: #764ba2;
            padding-left: 2rem;
        }

        .faq-question {
            font-size: 1.3rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.8rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-answer {
            color: #555;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .faq-toggle {
            font-size: 1.5rem;
            color: #667eea;
            transition: transform 0.3s;
        }

        .faq-item.active .faq-toggle {
            transform: rotate(180deg);
        }

        /* Map Container */
        .map-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .map-container h3 {
            font-size: 1.8rem;
            color: #667eea;
            margin-bottom: 1rem;
            text-align: center;
        }

        .map-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        /* Scroll to Top */
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
            .page-header h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .faq-section {
                padding: 2rem;
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

    <!-- Page Header -->
    <div class="page-header">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you!</p>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Contact Cards Grid -->
        <div class="contact-grid">
            <!-- Location Card -->
            <div class="contact-card">
                <div class="card-icon">📍</div>
                <h3>Our Location</h3>
                <div class="contact-info">
                    <strong>SGBIT Belagavi</strong><br>
                    Belagavi, Karnataka<br>
                    India
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="contact-card">
                <div class="card-icon">📞</div>
                <h3>Get in Touch</h3>
                <div class="contact-info">
                    <strong>Phone:</strong> +XXXX XXX XXX<br>
                    <strong>Fax:</strong> +XXX XXX XXX<br>
                    <strong>Email:</strong> <a href="mailto:emailus@sgbit.edu.in">emailus@sgbit.edu.in</a>
                </div>
            </div>

            <!-- Social Media Card -->
            <div class="contact-card">
                <div class="card-icon">🌐</div>
                <h3>Follow Us</h3>
                <div class="contact-info">
                    <p>Stay connected through our social media</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/SGBIT/" class="social-icon" target="_blank">f</a>
                        <a href="https://www.instagram.com/SGBIT/" class="social-icon" target="_blank">📷</a>
                        <a href="https://twitter.com/SGBIT" class="social-icon" target="_blank">🐦</a>
                    </div>
                </div>
            </div>
        </div>

       

        <!-- FAQ Section -->
        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <div class="faq-question">
                    Who are our Alumni?
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    All individuals who have followed Undergraduate, Postgraduate or PhD programmes of SGBIT can register as our alumni.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do I become a member of the SGBIT Alumni?
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    All undergraduates and postgraduates of SGBIT can register as our alumni. It is important for alumni to update their particulars for the SGBIT Alumni Directory (Database) by registering as a member online so that we can keep in touch with you for life.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    As an alumni, how can I contribute towards the University?
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    You can help by participating in the various activities organized by the University or by contributing ideas or programmes towards enriching learning experience of all students. Other assistance include donation, sponsorship, expertise and volunteer services. Whatever your contribution, they are most welcome.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    I am still a student of the University, can I take part in alumni activities?
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes, of course. We believe that the bond between alumni and students should begin as soon as the student enters the University because you will become our future alumni later on. The Alumni Advancement is also committed to organizing training especially in soft skills and career development programmes to raise your employability in the job market upon graduation.
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 SGBIT Alumni Connect. All rights reserved.</p>
        <p>Building bridges between alumni and students for a brighter future.</p>
    </footer>

    <!-- Scroll to Top -->
    <div class="scroll-top" id="scrollTop">↑</div>

    <script>
        // FAQ Toggle
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

        // Scroll to top
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

        // Smooth scrolling
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