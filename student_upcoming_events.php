<?php
session_start();
require_once 'db_config.php';

// Redirect if not logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];
$today = date('Y-m-d');

// Check for success message
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

// First, make sure the event_registrations table has the st_register column
$alter_table = "ALTER TABLE event_registrations 
                ADD COLUMN IF NOT EXISTS st_register VARCHAR(50) NOT NULL AFTER user_id";
mysqli_query($conn, $alter_table);

// Fetch upcoming events
$query = "SELECT * FROM events WHERE event_date >= '$today' ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);

// Store events in array to check registration status
$events = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $event_id = $row['event_id'];
        
        // Check if registered
        $check_reg = mysqli_query($conn, "SELECT COUNT(*) as count FROM event_registrations WHERE event_id = '$event_id' AND st_register = '$st_register'");
        $reg_data = mysqli_fetch_assoc($check_reg);
        $row['is_registered'] = ($reg_data['count'] > 0) ? 1 : 0;
        
        // Get total registrations
        $check_total = mysqli_query($conn, "SELECT COUNT(*) as count FROM event_registrations WHERE event_id = '$event_id'");
        $total_data = mysqli_fetch_assoc($check_total);
        $row['total_registrations'] = $total_data['count'];
        
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events - Alumni Connect</title>
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
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-registered {
            background: #6c757d;
            cursor: not-allowed;
        }

        .btn-registered:hover {
            background: #6c757d;
            transform: none;
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .event-title {
            font-size: 24px;
            color: #333;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .event-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-registered {
            background: #d4edda;
            color: #155724;
        }

        .badge-available {
            background: #cce5ff;
            color: #004085;
        }

        .event-date {
            color: #666;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .event-location {
            color: #666;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .event-desc {
            color: #555;
            margin-top: 12px;
            line-height: 1.6;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .registration-count {
            color: #666;
            font-size: 14px;
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

        .no-events p {
            color: #666;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            h1 {
                font-size: 1.5rem;
            }

            .event-header {
                flex-direction: column;
                gap: 10px;
            }

            .event-footer {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Upcoming Events</h1>
        <a href="studentpage.php" class="btn">← Back to Dashboard</a>
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

    <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <div class="event-header">
                    <div>
                        <div class="event-title">
                            <?php echo htmlspecialchars($event['event_name']); ?>
                        </div>
                    </div>
                    <?php if ($event['is_registered'] > 0): ?>
                        <span class="event-badge badge-registered">✓ Registered</span>
                    <?php else: ?>
                        <span class="event-badge badge-available">Available</span>
                    <?php endif; ?>
                </div>

                <div class="event-date">
                    <span>📅</span>
                    <strong><?php echo date('l, F j, Y', strtotime($event['event_date'])); ?></strong>
                </div>

                <?php if (!empty($event['venue'])): ?>
                <div class="event-location">
                    <span>📍</span>
                    <span><?php echo htmlspecialchars($event['venue']); ?></span>
                </div>
                <?php endif; ?>

                <div class="event-date">
                    <span>⏰</span>
                    <span><?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                </div>

                <?php if (!empty($event['event_description'])): ?>
                <div class="event-desc">
                    <?php echo nl2br(htmlspecialchars($event['event_description'])); ?>
                </div>
                <?php endif; ?>

                <div class="event-footer">
                    <div class="registration-count">
                        <strong><?php echo $event['total_registrations']; ?></strong> student(s) registered
                    </div>
                    
                    <?php if ($event['is_registered'] > 0): ?>
                        <button class="btn btn-registered" disabled>
                            ✓ Already Registered
                        </button>
                    <?php else: ?>
                        <form action="register_event.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Do you want to register for this event?');">
                                Register Now
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-events">
            <h2>📅 No Upcoming Events</h2>
            <p>There are no events scheduled at the moment. Check back later for new opportunities!</p>
            <a href="studentpage.php" class="btn" style="margin-top: 20px;">Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>