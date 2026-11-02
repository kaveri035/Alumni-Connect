<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBIT Alumni Connect</title>
    <?php
include_once 'db_config.php';
?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-x: hidden;
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

        /* Hero Section */
        .hero {
            margin-top: 80px;
            height: 90vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
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

        .hero-content {
            text-align: center;
            color: white;
            z-index: 1;
            padding: 2rem;
            animation: fadeInUp 1s ease;
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

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
flex-wrap: wrap;
        }

        .hero-button {
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid white;
        }

        .hero-button.primary {
            background: white;
            color: #667eea;
        }

        .hero-button.secondary {
            background: transparent;
            color: white;
        }

        .hero-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Slideshow Section */
        .slideshow-section {
            padding: 5rem 5%;
            background: white;
        }

        .slideshow-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0 0, 0, 0.2);
        }

        .slide {
            display: none;
            position: relative;
        }

        .slide.active {
            display: block;
            animation: fadeIn 0.8s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .slide img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .slide-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 3rem;
            color: white;
        }

        .slide-text {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

 .slide-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            color: white;
            padding: 1rem;
            border: none;
            cursor: pointer;
            font-size: 2rem;
            transition: all 0.3s;
            z-index: 10;
        }

        .slide-nav:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .slide-nav.prev {
            left: 20px;
            border-radius: 0 10px 10px 0;
        }

        .slide-nav.next {
            right: 20px;
            border-radius: 10px 0 0 10px;
        }

        .slide-dots {
            text-align: center;
            margin-top: 2rem;
        }

        .dot {
            display: inline-block;
  width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #ddd;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active {
            background: #667eea;
            transform: scale(1.3);
        }

        /* Welcome Section */
        .welcome-section {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .welcome-content {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .welcome-content h2 {
            font-size: 3rem;
            color: #333;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s;
        }

        .welcome-content .subtitle {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 2rem;
            font-style: italic;
        }

.welcome-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 2rem;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 5%;
            background: white;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 15px;
            color: white;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

 .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Registration Guide */
        .registration-guide {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .guide-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .guide-content h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .step {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: transform 0.3s;
        }
 .step:hover {
            transform: translateX(10px);
        }

        .step-number {
            background: white;
            color: #667eea;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-content strong {
            color: #ffd700;
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 2rem;
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
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .nav-links {
                display: none;
            }

 .slide img {
                height: 300px;
            }

            .welcome-content h2 {
                font-size: 2rem;
            }
        }
.auth-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
}

.auth-buttons .login-btn {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.auth-buttons .login-btn:hover {
    background: #667eea;
    color: white;
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
        <a href="login.php" class="cta-button">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Welcome to SGBIT Alumni Connect</h1>
            <p>Reconnect. Inspire. Grow Together.</p>
            <div class="hero-buttons">
                <a href="register.php" class="hero-button primary">Join Our Network</a>
                <a href="index.php" class="hero-button secondary">Learn More</a>
            </div>
        </div>
    </section>
<!-- Slideshow Section -->
    <section class="slideshow-section" id="about">
        <div class="slideshow-container">
            <div class="slide active">
                <img src="pictures/sgbit-img-1.jpg" alt="Campus">
                <div class="slide-overlay">
                    <div class="slide-text">Linking to Your Roots</div>
                </div>
            </div>
            <div class="slide">
                <img src="pictures/SGBIT2.jpg" alt="Networking">
                <div class="slide-overlay">
                    <div class="slide-text">Connect with Old Friends</div>
                </div>
            </div>
            <div class="slide">
                <img src="pictures/SGBIT3.jpg" alt="Collaboration">
                <div class="slide-overlay">
                    <div class="slide-text">Your Journey Starts Here</div>
                </div>
            </div>
            <div class="slide">
                <img src="pictures/SGBIT4.jpg" alt="Success">
                <div class="slide-overlay">
                    <div class="slide-text">Build a Brighter Future</div>
                </div>
            </div>
            <button class="slide-nav prev">&#10094;</button>
            <button class="slide-nav next">&#10095;</button>
        </div>
        <div class="slide-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>
<!-- Welcome Section -->
    <section class="welcome-section">
        <div class="welcome-content">
            <h2>Welcome to SGBIT Alumni Connect</h2>
            <p class="subtitle">Let's keep in touch...</p>
            <p>Welcome to the Alumni Connect Portal! Join our network to reconnect with your batchmates and contribute to student growth. Your experience can guide current students through mentorship and opportunities. Stay updated with campus events, reunions, and success stories. Together, let's build a stronger bridge between alumni and students for a brighter future.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <h3>Network & Reconnect</h3>
                <p>Find and connect with your batchmates and build lasting professional relationships.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎓</div>
                <h3>Mentor Students</h3>
                <p>Share your expertise and guide current students toward successful careers.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Campus Events</h3>
                <p>Stay updated with reunions, workshops, and exclusive alumni gatherings.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💼</div>
                <h3>Career Opportunities</h3>
                <p>Access job postings and career resources shared within our alumni network.</p>
            </div>
</div>
    </section>

    <!-- Registration Guide -->
    <section class="registration-guide" id="register">
        <div class="guide-content">
            <h2>How to Register as an Alumni</h2>
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    Click on the <strong>"Register"</strong> button at the top of the page.
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    Fill in your <strong>Full Name</strong>, <strong>Gender</strong>, and <strong>Registration Number</strong>.
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    Select your <strong>Branch</strong> and <strong>Session</strong> from the dropdown menus.
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    Enter your valid <strong>Email ID</strong> and <strong>Mobile Number</strong>.
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    Create a strong <strong>Password</strong> and click <strong>Register</strong> to complete your signup.
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 SGBIT Alumni Connect. All rights reserved.</p>
        <p>Building bridges between alumni and students for a brighter future.</p>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-top" id="scrollTop">↑</div>

    <script>
        // Slideshow functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = (n + slides.length) % slides.length;
            
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

document.querySelector('.slide-nav.next').addEventListener('click', nextSlide);
        document.querySelector('.slide-nav.prev').addEventListener('click', prevSlide);

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showSlide(index));
        });

        // Auto-play slideshow
        setInterval(nextSlide, 5000);

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

        // Animate feature cards on scroll
        const observerOptions = {
            threshold: 0.1,
rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>