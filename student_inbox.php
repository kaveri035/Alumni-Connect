<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - SGBIT Alumni Connect</title>
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
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            flex: 1;
            align-items: center;
        }

        .nav-link {
            padding: 0.9rem 1.5rem;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 3rem 5%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .compose-btn {
            background: white;
            color: #667eea;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }

        .compose-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.4);
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.7rem 1.5rem;
            border: 2px solid white;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: white;
            color: #667eea;
        }

        /* Messages Container */
        .messages-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        /* Message Item */
        .message-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s;
            gap: 1rem;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .message-item.unread {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
        }

        .message-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .message-content {
            flex: 1;
            min-width: 0;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .message-sender {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .message-item.unread .message-sender {
            color: #667eea;
        }

        .message-time {
            color: #666;
            font-size: 0.9rem;
        }

        .message-subject {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.3rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .message-preview {
            color: #666;
            font-size: 0.95rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .message-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .action-icon:hover {
            background: #667eea;
            color: white;
            transform: scale(1.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: #666;
        }

        .empty-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        /* Message Detail Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 700px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #667eea;
            font-weight: bold;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #333;
        }

        .message-detail {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            color: #333;
            font-size: 1.1rem;
        }

        .message-body {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            line-height: 1.8;
            color: #333;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-reply {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-delete {
            background: #ff4444;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .nav-links {
                display: none;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-buttons {
                justify-content: center;
            }

            .message-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .message-actions {
                width: 100%;
                justify-content: flex-end;
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
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="student_inbox.php" class="nav-link">Messages</a></li>
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
            <h1>📬 Inbox</h1>
            <p>You have 3 unread messages</p>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <a href="student_send_message.php" class="compose-btn">✍️ Compose New Message</a>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterMessages('all')">All</button>
                <button class="filter-btn" onclick="filterMessages('unread')">Unread</button>
                <button class="filter-btn" onclick="filterMessages('read')">Read</button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container">
            <!-- Unread Message 1 -->
            <div class="message-item unread" data-status="unread" onclick="openMessage(1)">
                <div class="message-avatar">JD</div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-sender">John Doe</span>
                        <span class="message-time">2 hours ago</span>
                    </div>
                    <div class="message-subject">Career Guidance Request</div>
                    <div class="message-preview">Hi! I'm interested in learning more about software development career paths...</div>
                </div>
                <div class="message-actions">
                    <div class="action-icon" onclick="event.stopPropagation(); deleteMessage(1)" title="Delete">🗑️</div>
                </div>
            </div>

            <!-- Unread Message 2 -->
            <div class="message-item unread" data-status="unread" onclick="openMessage(2)">
                <div class="message-avatar">JS</div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-sender">Jane Smith</span>
                        <span class="message-time">5 hours ago</span>
                    </div>
                    <div class="message-subject">Internship Opportunity</div>
                    <div class="message-preview">Hello! Our company is looking for interns in the ECE department...</div>
                </div>
                <div class="message-actions">
                    <div class="action-icon" onclick="event.stopPropagation(); deleteMessage(2)" title="Delete">🗑️</div>
                </div>
            </div>

            <!-- Unread Message 3 -->
            <div class="message-item unread" data-status="unread" onclick="openMessage(3)">
                <div class="message-avatar">AA</div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-sender">Alumni Admin</span>
                        <span class="message-time">1 day ago</span>
                    </div>
                    <div class="message-subject">Upcoming Alumni Meet</div>
                    <div class="message-preview">We're organizing an alumni meet on December 15th. Don't miss it!</div>
                </div>
                <div class="message-actions">
                    <div class="action-icon" onclick="event.stopPropagation(); deleteMessage(3)" title="Delete">🗑️</div>
                </div>
            </div>

            <!-- Read Message 1 -->
            <div class="message-item" data-status="read" onclick="openMessage(4)">
                <div class="message-avatar">MJ</div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-sender">Mike Johnson</span>
                        <span class="message-time">2 days ago</span>
                    </div>
                    <div class="message-subject">Re: Project Collaboration</div>
                    <div class="message-preview">Thanks for reaching out! I'd be happy to help with your project...</div>
                </div>
                <div class="message-actions">
                    <div class="action-icon" onclick="event.stopPropagation(); deleteMessage(4)" title="Delete">🗑️</div>
                </div>
            </div>

            <!-- Read Message 2 -->
            <div class="message-item" data-status="read" onclick="openMessage(5)">
                <div class="message-avatar">SW</div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-sender">Sarah Williams</span>
                        <span class="message-time">3 days ago</span>
                    </div>
                    <div class="message-subject">Mentorship Program</div>
                    <div class="message-preview">I'm glad to be your mentor! Let's schedule our first meeting...</div>
                </div>
                <div class="message-actions">
                    <div class="action-icon" onclick="event.stopPropagation(); deleteMessage(5)" title="Delete">🗑️</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Detail Modal -->
    <div class="modal" id="messageModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Message Details</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="message-detail">
                <div class="detail-label">From:</div>
                <div class="detail-value" id="modalFrom">John Doe</div>
            </div>
            <div class="message-detail">
                <div class="detail-label">Subject:</div>
                <div class="detail-value" id="modalSubject">Career Guidance Request</div>
            </div>
            <div class="message-detail">
                <div class="detail-label">Date:</div>
                <div class="detail-value" id="modalDate">November 1, 2025 - 2:30 PM</div>
            </div>
            <div class="message-detail">
                <div class="detail-label">Message:</div>
                <div class="message-body" id="modalBody">
                    Hi! I'm interested in learning more about software development career paths. Could you share your experience and advice?
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-reply" onclick="replyMessage()">Reply</button>
                <button class="btn btn-delete" onclick="deleteCurrentMessage()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Filter Messages
        function filterMessages(filter) {
            const messages = document.querySelectorAll('.message-item');
            const buttons = document.querySelectorAll('.filter-btn');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            messages.forEach(msg => {
                if (filter === 'all') {
                    msg.style.display = 'flex';
                } else {
                    msg.style.display = msg.dataset.status === filter ? 'flex' : 'none';
                }
            });
        }

        // Open Message Modal
        function openMessage(id) {
            const modal = document.getElementById('messageModal');
            modal.classList.add('show');
            
            // Mark as read
            event.currentTarget.classList.remove('unread');
            event.currentTarget.dataset.status = 'read';
        }

        // Close Modal
        function closeModal() {
            const modal = document.getElementById('messageModal');
            modal.classList.remove('show');
        }

        // Reply to Message
        function replyMessage() {
            window.location.href = 'student_send_message.php';
        }

        // Delete Message
        function deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                event.target.closest('.message-item').remove();
            }
        }

        // Delete Current Message from Modal
        function deleteCurrentMessage() {
            if (confirm('Are you sure you want to delete this message?')) {
                closeModal();
                // In real implementation, delete from database
                alert('Message deleted successfully!');
            }
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>