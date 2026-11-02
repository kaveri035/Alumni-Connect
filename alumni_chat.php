<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];
$pi_name = $_SESSION['pi_name'];

// Get selected conversation
$selected_user = isset($_GET['user']) ? $_GET['user'] : null;

// Fetch all conversations (connected students)
$conversations_query = "SELECT DISTINCT uc.user1_register, si.st_name, si.st_branch, si.st_year,
                        (SELECT COUNT(*) FROM chat_messages 
                         WHERE sender_register=uc.user1_register AND receiver_register='$pi_register' AND is_read=0) as unread_count,
                        (SELECT message_text FROM chat_messages 
                         WHERE (sender_register='$pi_register' AND receiver_register=uc.user1_register) 
                         OR (sender_register=uc.user1_register AND receiver_register='$pi_register')
                         ORDER BY sent_at DESC LIMIT 1) as last_message,
                        (SELECT sent_at FROM chat_messages 
                         WHERE (sender_register='$pi_register' AND receiver_register=uc.user1_register) 
                         OR (sender_register=uc.user1_register AND receiver_register='$pi_register')
                         ORDER BY sent_at DESC LIMIT 1) as last_message_time
                        FROM user_connections uc
                        JOIN studentinfo si ON uc.user1_register = si.st_register
                        WHERE uc.user2_register='$pi_register' AND uc.user2_type='alumni'
                        ORDER BY last_message_time DESC";
$conversations = mysqli_query($conn, $conversations_query);

// Get selected user details
if ($selected_user) {
    $user_details = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT * FROM studentinfo WHERE st_register='$selected_user'"));
    
    // Mark messages as read
    mysqli_query($conn, "UPDATE chat_messages SET is_read=1, read_at=NOW() 
                         WHERE sender_register='$selected_user' AND receiver_register='$pi_register' AND is_read=0");
    
    // Fetch messages
    $messages_query = "SELECT * FROM chat_messages 
                       WHERE (sender_register='$pi_register' AND receiver_register='$selected_user')
                       OR (sender_register='$selected_user' AND receiver_register='$pi_register')
                       ORDER BY sent_at ASC";
    $messages = mysqli_query($conn, $messages_query);
}

// Handle sending message
if (isset($_POST['send_message'])) {
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    if (!empty($message)) {
        $insert = "INSERT INTO chat_messages (sender_type, sender_register, receiver_type, receiver_register, message_text) 
                   VALUES ('alumni', '$pi_register', 'student', '$receiver', '$message')";
        
        if (mysqli_query($conn, $insert)) {
            // Update interaction count
            mysqli_query($conn, "UPDATE user_connections 
                                SET last_interaction=NOW(), interaction_count=interaction_count+1 
                                WHERE user1_register='$receiver' AND user2_register='$pi_register'");
            
            // Create notification
            mysqli_query($conn, "INSERT INTO notifications (user_type, user_register, notification_type, title, message, link) 
                                VALUES ('student', '$receiver', 'message', 'New Message', 
                                'Alumni $pi_name sent you a message', 'student_chat.php?user=$pi_register')");
            
            header("Location: alumni_chat.php?user=$receiver");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages - Alumni Connect</title>
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

.chat-container {
    max-width: 1400px;
    margin: 20px auto;
    display: grid;
    grid-template-columns: 350px 1fr;
    height: calc(100vh - 40px);
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* Sidebar */
.sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e0e0e0;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.sidebar-header h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.user-status {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    opacity: 0.9;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #28a745;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.search-box {
    margin-top: 10px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.search-box input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.7);
}

.conversations {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 12px;
}

.conversation-item:hover {
    background: #e9ecef;
}

.conversation-item.active {
    background: #667eea;
    color: white;
}

.conversation-avatar {
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

.conversation-item.active .conversation-avatar {
    background: white;
    color: #667eea;
}

.conversation-info {
    flex: 1;
    min-width: 0;
}

.conversation-name {
    font-weight: 600;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-preview {
    font-size: 0.85rem;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-item.active .conversation-preview {
    color: rgba(255, 255, 255, 0.8);
}

.conversation-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}

.conversation-time {
    font-size: 0.75rem;
    color: #999;
}

.conversation-item.active .conversation-time {
    color: rgba(255, 255, 255, 0.8);
}

.unread-badge {
    background: #667eea;
    color: white;
    font-size: 0.75rem;
    padding: 3px 8px;
    border-radius: 10px;
    font-weight: 600;
}

.conversation-item.active .unread-badge {
    background: white;
    color: #667eea;
}

/* Chat Area */
.chat-area {
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.chat-avatar {
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
}

.chat-user-details h3 {
    color: #333;
    font-size: 1.2rem;
}

.chat-user-details p {
    color: #666;
    font-size: 0.9rem;
}

.chat-actions {
    display: flex;
    gap: 10px;
}

.action-btn {
    padding: 8px 15px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
    text-decoration: none;
    color: #333;
}

.action-btn:hover {
    background: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.message {
    margin-bottom: 15px;
    display: flex;
    gap: 10px;
}

.message.sent {
    justify-content: flex-end;
}

.message-bubble {
    max-width: 60%;
    padding: 12px 16px;
    border-radius: 12px;
    position: relative;
    animation: messageSlide 0.3s ease;
}

@keyframes messageSlide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message.received .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.message.sent .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.message-text {
    line-height: 1.5;
    word-wrap: break-word;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 5px;
}

.message-input-area {
    padding: 20px;
    border-top: 1px solid #e0e0e0;
    background: white;
}

.message-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.message-input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s;
    font-family: inherit;
}

.message-input:focus {
    border-color: #667eea;
}

.send-btn {
    padding: 12px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.send-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.send-btn:active {
    transform: translateY(0);
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #999;
    text-align: center;
    padding: 40px;
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 20px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-state p {
    color: #999;
    margin-bottom: 20px;
}

.empty-state a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    padding: 10px 20px;
    border: 2px solid #667eea;
    border-radius: 8px;
    transition: all 0.3s;
}

.empty-state a:hover {
    background: #667eea;
    color: white;
}

.no-conversations {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.no-conversations-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

/* Scrollbar Styling */
.conversations::-webkit-scrollbar,
.messages-container::-webkit-scrollbar {
    width: 8px;
}

.conversations::-webkit-scrollbar-track,
.messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.conversations::-webkit-scrollbar-thumb,
.messages-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.conversations::-webkit-scrollbar-thumb:hover,
.messages-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

@media (max-width: 768px) {
    .chat-container {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        display: none;
    }
    
    .sidebar.show {
        display: flex;
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
    
    .message-bubble {
        max-width: 80%;
    }
    
    .chat-header {
        padding: 15px;
    }
    
    .message-form {
        flex-wrap: nowrap;
    }
    
    .send-btn {
        padding: 12px 20px;
    }
}
</style>
</head>
<body>
<div class="chat-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>💬 Messages</h2>
            <div class="user-status">
                <span class="status-dot"></span>
                <span>Online as <?php echo htmlspecialchars($pi_name); ?></span>
            </div>
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search conversations..." id="searchInput">
            </div>
        </div>
        
        <div class="conversations">
            <?php 
            if (mysqli_num_rows($conversations) > 0) {
                mysqli_data_seek($conversations, 0);
                while ($conv = mysqli_fetch_assoc($conversations)) { 
                    $initials = strtoupper(substr($conv['st_name'], 0, 1));
                    $active = ($selected_user == $conv['user1_register']) ? 'active' : '';
            ?>
                <div class="conversation-item <?php echo $active; ?>" 
                     onclick="window.location.href='alumni_chat.php?user=<?php echo $conv['user1_register']; ?>'">
                    <div class="conversation-avatar"><?php echo $initials; ?></div>
                    <div class="conversation-info">
                        <div class="conversation-name"><?php echo htmlspecialchars($conv['st_name']); ?></div>
                        <div class="conversation-preview">
                            <?php echo htmlspecialchars(substr($conv['last_message'] ?? 'No messages yet', 0, 30)); ?>
                            <?php if (strlen($conv['last_message'] ?? '') > 30) echo '...'; ?>
                        </div>
                    </div>
                    <div class="conversation-meta">
                        <?php if ($conv['last_message_time']) { ?>
                            <div class="conversation-time">
                                <?php 
                                $time = new DateTime($conv['last_message_time']);
                                $now = new DateTime();
                                $diff = $now->diff($time);
                                
                                if ($diff->days == 0) {
                                    echo $time->format('h:i A');
                                } elseif ($diff->days == 1) {
                                    echo 'Yesterday';
                                } else {
                                    echo $time->format('M d');
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <?php if ($conv['unread_count'] > 0) { ?>
                            <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                        <?php } ?>
                    </div>
                </div>
            <?php 
                }
            } else { ?>
                <div class="no-conversations">
                    <div class="no-conversations-icon">👥</div>
                    <p>No conversations yet</p>
                    <p style="font-size: 0.85rem; margin-top: 10px;">
                        Students you connect with will appear here
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <!-- Chat Area -->
    <div class="chat-area">
        <?php if ($selected_user && $user_details) { ?>
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-user-info">
                    <div class="chat-avatar">
                        <?php echo strtoupper(substr($user_details['st_name'], 0, 1)); ?>
                    </div>
                    <div class="chat-user-details">
                        <h3><?php echo htmlspecialchars($user_details['st_name']); ?></h3>
                        <p><?php echo htmlspecialchars($user_details['st_branch']); ?> - Year <?php echo $user_details['st_year']; ?></p>
                    </div>
                </div>
                <div class="chat-actions">
                    <a href="alumni_connections.php" class="action-btn">
                        👁 View Connections
                    </a>
                </div>
            </div>
            
            <!-- Messages -->
            <div class="messages-container" id="messagesContainer">
                <?php 
                if (mysqli_num_rows($messages) > 0) {
                    while ($msg = mysqli_fetch_assoc($messages)) { 
                        $is_sent = ($msg['sender_register'] == $pi_register);
                        $msg_class = $is_sent ? 'sent' : 'received';
                ?>
                    <div class="message <?php echo $msg_class; ?>">
                        <div class="message-bubble">
                            <div class="message-text"><?php echo nl2br(htmlspecialchars($msg['message_text'])); ?></div>
                            <div class="message-time">
                                <?php 
                                $time = new DateTime($msg['sent_at']);
                                echo $time->format('M d, h:i A');
                                ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                } else { ?>
                    <div class="empty-state">
                        <div class="empty-icon">💬</div>
                        <h3>Start a conversation</h3>
                        <p>Send your first message to <?php echo htmlspecialchars($user_details['st_name']); ?></p>
                    </div>
                <?php } ?>
            </div>
            
            <!-- Message Input -->
            <div class="message-input-area">
                <form method="POST" class="message-form">
                    <input type="hidden" name="receiver" value="<?php echo $selected_user; ?>">
                    <input type="text" name="message" class="message-input" 
                           placeholder="Type your message..." required autofocus>
                    <button type="submit" name="send_message" class="send-btn">Send 📤</button>
                </form>
            </div>
        <?php } else { ?>
            <div class="empty-state">
                <div class="empty-icon">💬</div>
                <h3>Select a conversation</h3>
                <p>Choose a student from the left to start messaging</p>
                <br>
                <a href="alumni_connections.php">
                    ← Back to Student Network
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<script>
// Auto-scroll to bottom of messages
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
});

// Search conversations
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.conversation-item').forEach(item => {
        const name = item.querySelector('.conversation-name').textContent.toLowerCase();
        item.style.display = name.includes(search) ? 'flex' : 'none';
    });
});

// Auto-refresh messages every 30 seconds
<?php if ($selected_user) { ?>
setInterval(function() {
    // Check for new messages without full page reload
    fetch('check_new_messages.php?from=<?php echo $selected_user; ?>')
        .then(response => response.json())
        .then(data => {
            if (data.has_new) {
                location.reload();
            }
        });
}, 30000); // 30 seconds
<?php } ?>

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Add enter key to send message
document.querySelector('.message-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>
</body>
</html>