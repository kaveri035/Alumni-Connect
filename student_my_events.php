<?php
session_start();
require_once 'db_config.php';

// Redirect if not logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];

// Make sure st_register column exists in event_registrations
$alter_table = "ALTER TABLE event_registrations 
                ADD COLUMN IF NOT EXISTS st_register VARCHAR(50) NOT NULL AFTER user_id";
mysqli_query($conn, $alter_table);

// Handle cancellation
if (isset($_POST['cancel_registration'])) {
    $registration_id = mysqli_real_escape_string($conn, $_POST['registration_id']);
    
    $delete_query = "DELETE FROM event_registrations 
                     WHERE registration_id = '$registration_id' AND st_register = '$st_register'";
    
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['event_success'] = "Event registration cancelled successfully!";
    } else {
        $_SESSION['event_error'] = "Failed to cancel registration!";
    }
    header("Location: my_events.php");
    exit();
}

// Check for messages
$success_message = '';
if (isset($_SESSION['event_success'])) {
    $success_message = $_SESSION['event_success'];
    unset($_SESSION['event_success']);
}

$error_message = '';
if (isset($_SESSION['event_error'])) {
    $error_message = $_SESSION['event_error'];
    unset($_SESSION['event_error']);
}

// Fetch registered events
$query = "SELECT e.*, er.registration_date, er.registration_id, er.attendance_status
          FROM event_registrations er
          JOIN events e ON er.event_id = e.event_id
          WHERE er.st_register = '$st_register'
          ORDER BY e.event_date ASC";
$result = mysqli_query($conn, $query);

// Debug: Check if query is working
if (!$result) {
    $error_message = "Database error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Alumni Connect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        h1 {
            color: #667eea;
            font-size: 2rem;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: #667eea;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .event-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .event-title {
            font-size: 24px;
            color: #333;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .event-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
        }

        .event-desc {
            color: #555;
            line-height: 1.6;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }

        .no-events {
            background: white;
            padding: 60px 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .no-events h2 {
            color: #667eea;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .event-info {
                grid-template-columns: 1fr;
            }

            .event-footer {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📋 My Registered Events</h1>
        <div>
            <a href="student_upcoming_events.php" class="btn" style="margin-right: 10px;">Browse Events</a>
            <a href="studentpage.php" class="btn">← Dashboard</a>
        </div>
    </div>

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <span style="font-size: 20px;">✅</span>
            <span><?php echo htmlspecialchars($success_message); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-error">
            <span style="font-size: 20px;">❌</span>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($event = mysqli_fetch_assoc($result)): ?>
            <?php 
            $is_past = strtotime($event['event_date']) < strtotime(date('Y-m-d'));
            ?>
            <div class="event-card">
                <div class="event-title">
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </div>

                <div class="event-info">
                    <div class="info-item">
                        <span>📅</span>
                        <strong><?php echo date('F j, Y', strtotime($event['event_date'])); ?></strong>
                    </div>
                    
                    <?php if (!empty($event['venue'])): ?>
                    <div class="info-item">
                        <span>📍</span>
                        <span><?php echo htmlspecialchars($event['venue']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-item">
                        <span>⏰</span>
                        <span><?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span>✅</span>
                        <span>Registered on: <?php echo date('M j, Y', strtotime($event['registration_date'])); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span>🎫</span>
                        <span>Status: <strong><?php echo htmlspecialchars($event['attendance_status']); ?></strong></span>
                    </div>
                </div>

                <?php if (!empty($event['event_description'])): ?>
                <div class="event-desc">
                    <?php echo nl2br(htmlspecialchars($event['event_description'])); ?>
                </div>
                <?php endif; ?>

                <div class="event-footer">
                    <?php if ($is_past): ?>
                        <span class="status-badge" style="background: #d1ecf1; color: #0c5460;">
                            Event Completed
                        </span>
                    <?php else: ?>
                        <span class="status-badge">Registered</span>
                    <?php endif; ?>
                    
                    <?php if (!$is_past): ?>
                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                        <input type="hidden" name="registration_id" value="<?php echo $event['registration_id']; ?>">
                        <button type="submit" name="cancel_registration" class="btn btn-danger">
                            Cancel Registration
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-events">
            <h2>📅 No Registered Events</h2>
            <p>You haven't registered for any events yet.</p>
            <a href="student_upcoming_events.php" class="btn" style="margin-top: 20px;">Browse Upcoming Events</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>