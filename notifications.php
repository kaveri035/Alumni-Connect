<?php
include_once 'db_config.php';
session_start();

// Determine user type and register
$user_type = null;
$user_register = null;

if (isset($_SESSION['st_register'])) {
    $user_type = 'student';
    $user_register = $_SESSION['st_register'];
} elseif (isset($_SESSION['pi_register'])) {
    $user_type = 'alumni';
    $user_register = $_SESSION['pi_register'];
} else {
    header("Location: login.php");
    exit();
}

// Handle mark as read
if (isset($_POST['mark_read'])) {
    $notification_id = mysqli_real_escape_string($conn, $_POST['notification_id']);
    mysqli_query($conn, "UPDATE notifications SET is_read=1, read_at=NOW() 
                         WHERE notification_id='$notification_id'");
    exit(json_encode(['success' => true]));
}

// Handle mark all as read
if (isset($_POST['mark_all_read'])) {
    mysqli_query($conn, "UPDATE notifications SET is_read=1, read_at=NOW() 
                         WHERE user_register='$user_register' AND is_read=0");
    header("Location: notifications.php");
    exit();
}

// Handle delete notification
if (isset($_POST['delete_notification'])) {
    $notification_id = mysqli_real_escape_string($conn, $_POST['notification_id']);
    mysqli_query($conn, "DELETE FROM notifications WHERE notification_id='$notification_id'");
    exit(json_encode(['success' => true]));
}

// Fetch notifications
$notifications_query = "SELECT * FROM notifications 
                        WHERE user_register='$user_register' AND user_type='$user_type'
                        ORDER BY created_at DESC
                        LIMIT 50";
$notifications = mysqli_query($conn, $notifications_query);

// Count unread
$unread_count = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM notifications 
     WHERE user_register='$user_register' AND is_read=0"))['count'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications - Alumni Connect</title>
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

.container {
    max-width: 900px;
    margin: 40px auto;
}

.header {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 {
    color: #667eea;
    font-size: 2rem;
    margin-bottom: 5px;
}

.header-content p {
    color: #666;
}

.unread-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 1.1rem;
}

.actions-bar {
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 10px;
}

.action-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-mark-all {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-back {
    background: #6c757d;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.notifications-list {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.notification-item {
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-left: 4px solid transparent;
    transition: all 0.3s;
    display: flex;
    gap: 15px;
    align-items: start;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.notification-item.unread {
    background: #e7f0ff;
    border-left-color: #667eea;
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.icon-connection {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.icon-message {
    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
}

.icon-mentorship {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
}

.icon-other {
    background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 8px;
}

.notification-title {
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
}

.notification-time {
    font-size: 0.85rem;
    color: #999;
    white-space: nowrap;
}

.notification-message {
    color: #666;
    line-height: 1.6;
    margin-bottom: 10px;
}

.notification-actions {
    display: flex;
    gap: 10px;
}

.notif-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-view {
    background: #667eea;
    color: white;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.notif-btn:hover {
    transform: scale(1.05);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .actions-bar {
        flex-direction: column;
    }
    
    .notification-item {
        flex-direction: column;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-content">
            <h1>🔔 Notifications</h1>
            <p>Stay updated with all your activities</p>
        </div>
        <?php if ($unread_count > 0) { ?>
            <div class="unread-badge">
                <?php echo $unread_count; ?> Unread
            </div>
        <?php } ?>
    </div>
    
    <div class="actions-bar">
        <?php if ($unread_count > 0) { ?>
            <form method="POST" style="display: inline;">
                <button type="submit" name="mark_all_read" class="action-btn btn-mark-all">
                    ✓ Mark All as Read
                </button>
            </form>
        <?php } ?>
        <a href="<?php echo ($user_type == 'student') ? 'studentpage.php' : 'alumnipage.php'; ?>" 
           class="action-btn btn-back">
            ← Back to Dashboard
        </a>
    </div>
    
    <div class="notifications-list">
        <?php if (mysqli_num_rows($notifications) > 0) { ?>
            <?php while ($notif = mysqli_fetch_assoc($notifications)) { 
                $unread_class = $notif['is_read'] == 0 ? 'unread' : '';
                
                // Determine icon based on notification type
                $icon_class = 'icon-other';
                $icon = '🔔';
                
                if ($notif['notification_type'] == 'connection_request') {
                    $icon_class = 'icon-connection';
                    $icon = '🤝';
                } elseif ($notif['notification_type'] == 'message') {
                    $icon_class = 'icon-message';
                    $icon = '💬';
                } elseif (strpos($notif['notification_type'], 'mentorship') !== false) {
                    $icon_class = 'icon-mentorship';
                    $icon = '🎓';
                }
            ?>
                <div class="notification-item <?php echo $unread_class; ?>">
                    <div class="notification-icon <?php echo $icon_class; ?>">
                        <?php echo $icon; ?>
                    </div>
                    <div class="notification-content">
                        <div class="notification-header">
                            <div class="notification-title">
                                <?php echo htmlspecialchars($notif['title']); ?>
                            </div>
                            <div class="notification-time">
                                <?php 
                                $time = new DateTime($notif['created_at']);
                                $now = new DateTime();
                                $diff = $now->diff($time);
                                
                                if ($diff->days == 0) {
                                    if ($diff->h == 0) {
                                        echo $diff->i . ' min ago';
                                    } else {
                                        echo $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                                    }
                                } elseif ($diff->days == 1) {
                                    echo 'Yesterday';
                                } else {
                                    echo $time->format('M d, Y');
                                }
                                ?>
                            </div>
                        </div>
                        <div class="notification-message">
                            <?php echo htmlspecialchars($notif['message']); ?>
                        </div>
                        <div class="notification-actions">
                            <?php if (!empty($notif['link'])) { ?>
                                <button class="notif-btn btn-view" 
                                        onclick="window.location.href='<?php echo htmlspecialchars($notif['link']); ?>'">
                                    View
                                </button>
                            <?php } ?>
                            <?php if ($notif['is_read'] == 0) { ?>
                                <button class="notif-btn btn-view" 
                                        onclick="markAsRead(<?php echo $notif['notification_id']; ?>)">
                                    Mark as Read
                                </button>
                            <?php } ?>
                            <button class="notif-btn btn-delete" 
                                    onclick="deleteNotification(<?php echo $notif['notification_id']; ?>)">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="empty-state">
                <div class="empty-icon">🔔</div>
                <h3>No notifications yet</h3>
                <p>You're all caught up! Notifications will appear here when you have activity.</p>
            </div>
        <?php } ?>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch('notifications.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'mark_read=1&notification_id=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        fetch('notifications.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'delete_notification=1&notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
</body>
</html>