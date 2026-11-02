<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['ad_fullname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SGBIT Alumni Connect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
        }

        /* Header Image */
        .header-banner {
            width: 100%;
            height: auto;
            display: block;
            margin-top: 80px;
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

        /* Logo */
        .nav-logo {
            width: 55px;
            height: 55px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            flex: 1;
            align-items: center;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: block;
            padding: 0.9rem 1.5rem;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 8px;
            background: transparent;
        }

        .nav-link:hover,
        .nav-item.active .nav-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Dropdown */
        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 220px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .nav-item:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            display: block;
            padding: 1rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .dropdown-content a:hover {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-left-color: #764ba2;
            padding-left: 2rem;
        }

        /* User Section */
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

        .welcome-text {
            font-weight: 600;
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
            transform: scale(1.05);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-link {
                padding: 0.8rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
                margin-left: auto;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 80%;
                max-width: 300px;
                background: white;
                flex-direction: column;
                padding: 1rem;
                box-shadow: 5px 0 20px rgba(0, 0, 0, 0.2);
                transition: left 0.3s;
                height: calc(100vh - 70px);
                overflow-y: auto;
                gap: 0.5rem;
            }

            .nav-links.active {
                left: 0;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                width: 100%;
            }

            .dropdown-content {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                margin-top: 0.5rem;
                background: #f8f9fa;
            }

            .user-section {
                flex-direction: column;
                padding: 1rem;
                gap: 0.5rem;
            }

            .header-banner {
                height: 150px;
            }
        }

        /* --- Mentorship Menu Style --- */
.nav-links .nav-item .nav-link[href="#"],
.nav-links .nav-item .nav-link:contains("Mentorship") {
    color: #333;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.nav-links .nav-item .nav-link[href="#"]:hover {
    background: linear-gradient(135deg, #42a5f5 0%, #5c6bc0 100%);
    color: #fff;
    box-shadow: 0 5px 15px rgba(66,165,245,0.3);
}

/* Dropdown links for mentorship */
.nav-links .nav-item .dropdown-content a[href*="requests"],
.nav-links .nav-item .dropdown-content a[href*="pairs"] {
    border-left-color: #42a5f5;
}

.nav-links .nav-item .dropdown-content a[href*="requests"]:hover,
.nav-links .nav-item .dropdown-content a[href*="pairs"]:hover {
    background: linear-gradient(90deg, #42a5f5 0%, #5c6bc0 100%);
    color: #fff;
    border-left-color: #5c6bc0;
    padding-left: 2rem;
}

    </style>
</head>
<body>
    <!-- Header Image -->
    <img src="image/sgbit-img-1.jpg" alt="SGBIT Banner" class="header-banner">

    <!-- Admin Navigation -->
    <nav>
        <div class="nav-container">
            <!-- Logo -->
            <img src="image/logo.jpg" alt="SGBIT Logo" class="nav-logo">

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

            <!-- Navigation Links -->
            <ul class="nav-links" id="navLinks">
                <li class="nav-item active">
                    <a href="adminpage.php" class="nav-link">Home</a>
                </li>

                <li class="nav-item">
                    <a href="" class="nav-link">News</a>
                    <div class="dropdown-content">
                        <a href="announcement.php">Announcement Board</a>
                        <a href="events.php">Events</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="forum_list.php" class="nav-link">E-Forum</a>
                    <div class="dropdown-content">
                        <a href="add_forum_post.php">Add New Forum Post</a>
                        <a href="delete_forum_post.php">Delete Forum Post</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="search_alumni3.php" class="nav-link">Search Alumni</a>
                </li>

                <li class="nav-item">
                    <a href="Financial_Record.php" class="nav-link">Financial</a>
                    <div class="dropdown-content">
                        <a href="NewPayment.php">New Payment</a>
                    </div>
                </li>

                

                <li class="nav-item">
                    <a href="manage_alumni.php" class="nav-link">Alumni</a>
                </li>
                 <li class="nav-item">
                    <a href="" class="nav-link">🤝 Mentorship</a>
                <div class="dropdown-content">
                        <a href="admin_mentorship_requests.php">Mentorship requests</a>
                        <a href="admin_mentorship_pairs.php">Mentorship pair</a>
                    </div>
                </li>
            </ul>
            
            <!-- User Section -->
            <div class="user-section">
                <span class="welcome-text">Welcome, Admin</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            
            
        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.getElementById('navLinks');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (navLinks && mobileToggle && 
                !navLinks.contains(event.target) && 
                !mobileToggle.contains(event.target)) {
                navLinks.classList.remove('active');
            }
        });
    </script>
</body>
</html>