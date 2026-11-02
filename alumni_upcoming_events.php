<?php
session_start();
require_once 'db_config.php';

// Redirect if not logged in
if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$pi_register = $_SESSION['pi_register'];
$today = date('Y-m-d');

// Fetch upcoming events
$query = "SELECT * FROM events WHERE event_date >= '$today' ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);
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
            margin-bottom: 8px;
        }

        .event-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: #28a745;
            color: white;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-bottom: 10px;
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
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Upcoming Events</h1>
        <a href="alumnipage.php" class="btn">← Back to Dashboard</a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($event = mysqli_fetch_assoc($result)): ?>
            <div class="event-card">
                <span class="event-type"><?php echo ucfirst($event['event_type']); ?></span>
                
                <div class="event-title">
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </div>

                <div class="event-date">
                    <span>📅</span>
                    <strong><?php echo date('l, F j, Y', strtotime($event['event_date'])); ?></strong>
                </div>

                <div class="event-date">
                    <span>⏰</span>
                    <span><?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                </div>

                <?php if (!empty($event['venue'])): ?>
                <div class="event-location">
                    <span>📍</span>
                    <span><?php echo htmlspecialchars($event['venue']); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($event['event_description'])): ?>
                <div class="event-desc">
                    <?php echo nl2br(htmlspecialchars($event['event_description'])); ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-events">
            <h2>📅 No Upcoming Events</h2>
            <p>There are no events scheduled at the moment. Check back later!</p>
            <a href="alumnipage.php" class="btn" style="margin-top: 20px;">Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>