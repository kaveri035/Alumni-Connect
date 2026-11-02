<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - SGBIT Alumni Connect</title>
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
            padding: 4rem 5%;
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

        /* Events Container */
        .events-container {
            padding: 2rem 5% 5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        /* Event Card */
        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .event-card:nth-child(1) { animation-delay: 0.1s; }
        .event-card:nth-child(2) { animation-delay: 0.2s; }
        .event-card:nth-child(3) { animation-delay: 0.3s; }
        .event-card:nth-child(4) { animation-delay: 0.4s; }
        .event-card:nth-child(5) { animation-delay: 0.5s; }
        .event-card:nth-child(6) { animation-delay: 0.6s; }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }

        .event-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .event-badge.upcoming {
            background: #4ade80;
            color: white;
        }

        .event-badge.past {
            background: #94a3b8;
            color: white;
        }

        .event-content {
            padding: 1.5rem;
        }

        .event-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .event-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .event-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .event-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .event-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .event-button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .event-attendees {
            color: #667eea;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: white;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
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

            .events-grid {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                gap: 0.5rem;
            }

            .filter-tab {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
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
        <h1>Alumni Events</h1>
        <p>Connect, Network, and Celebrate Together</p>
    </div>

    <!-- Events Container -->
    <div class="events-container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active" onclick="filterEvents('all')">All Events</div>
            <div class="filter-tab" onclick="filterEvents('upcoming')">Upcoming</div>
            <div class="filter-tab" onclick="filterEvents('past')">Past Events</div>
            <div class="filter-tab" onclick="filterEvents('reunion')">Reunions</div>
            <div class="filter-tab" onclick="filterEvents('workshop')">Workshops</div>
        </div>

        <!-- Events Grid -->
        <div class="events-grid" id="eventsGrid">
            <!-- Event Card 1 -->
            <div class="event-card" data-category="upcoming reunion">
                <div class="event-image">
                    <div class="event-badge upcoming">Upcoming</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 December 15, 2025</div>
                    <h3 class="event-title">Annual Alumni Reunion 2025</h3>
                    <p class="event-description">Join us for our grand annual reunion! Reconnect with your batchmates, share memories, and celebrate our journey together.</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 SGBIT Campus</div>
                        <div class="meta-item">⏰ 10:00 AM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">125 Attending</span>
                        <button class="event-button">Register Now</button>
                    </div>
                </div>
            </div>

            <!-- Event Card 2 -->
            <div class="event-card" data-category="upcoming workshop">
                <div class="event-image">
                    <div class="event-badge upcoming">Upcoming</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 November 20, 2025</div>
                    <h3 class="event-title">Tech Talk: AI & Machine Learning</h3>
                    <p class="event-description">Industry experts share insights on AI trends, career opportunities, and practical applications in today's tech landscape.</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 Virtual Event</div>
                        <div class="meta-item">⏰ 6:00 PM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">89 Attending</span>
                        <button class="event-button">Register Now</button>
                    </div>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="event-card" data-category="upcoming workshop">
                <div class="event-image">
                    <div class="event-badge upcoming">Upcoming</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 November 30, 2025</div>
                    <h3 class="event-title">Career Mentorship Program</h3>
                    <p class="event-description">Alumni-Student mentorship session focusing on career guidance, interview preparation, and industry insights.</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 Seminar Hall</div>
                        <div class="meta-item">⏰ 2:00 PM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">56 Attending</span>
                        <button class="event-button">Register Now</button>
                    </div>
                </div>
            </div>

            <!-- Event Card 4 -->
            <div class="event-card" data-category="past reunion">
                <div class="event-image">
                    <div class="event-badge past">Past Event</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 October 5, 2025</div>
                    <h3 class="event-title">Batch 2020 Reunion</h3>
                    <p class="event-description">A memorable evening celebrating 5 years since graduation. Great food, music, and nostalgia!</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 Hotel Grand Plaza</div>
                        <div class="meta-item">⏰ 7:00 PM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">142 Attended</span>
                        <button class="event-button">View Photos</button>
                    </div>
                </div>
            </div>

            <!-- Event Card 5 -->
            <div class="event-card" data-category="past workshop">
                <div class="event-image">
                    <div class="event-badge past">Past Event</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 September 15, 2025</div>
                    <h3 class="event-title">Entrepreneurship Workshop</h3>
                    <p class="event-description">Successful alumni entrepreneurs shared their startup journey, challenges, and key learnings.</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 Auditorium</div>
                        <div class="meta-item">⏰ 3:00 PM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">78 Attended</span>
                        <button class="event-button">View Highlights</button>
                    </div>
                </div>
            </div>

            <!-- Event Card 6 -->
            <div class="event-card" data-category="upcoming workshop">
                <div class="event-image">
                    <div class="event-badge upcoming">Upcoming</div>
                </div>
                <div class="event-content">
                    <div class="event-date">📅 December 10, 2025</div>
                    <h3 class="event-title">Alumni Sports Tournament</h3>
                    <p class="event-description">Cricket, football, and basketball matches. Come cheer for your batch and relive the glory days!</p>
                    <div class="event-meta">
                        <div class="meta-item">📍 Sports Complex</div>
                        <div class="meta-item">⏰ 9:00 AM</div>
                    </div>
                    <div class="event-footer">
                        <span class="event-attendees">95 Attending</span>
                        <button class="event-button">Register Team</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State (hidden by default) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-state-icon">📭</div>
            <h3>No Events Found</h3>
            <p>Check back soon for upcoming events!</p>
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
        // Filter Events
        function filterEvents(category) {
            const cards = document.querySelectorAll('.event-card');
            const tabs = document.querySelectorAll('.filter-tab');
            const emptyState = document.getElementById('emptyState');
            let visibleCount = 0;

            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            // Filter cards
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category.includes(category)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }

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