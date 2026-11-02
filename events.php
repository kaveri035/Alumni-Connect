<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['ad_fullname'];
$success = "";
$error = "";

// Add event to the NEW events table
if (isset($_POST['add_event'])) {
    $name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $desc = mysqli_real_escape_string($conn, $_POST['event_desc']);
    $venue = mysqli_real_escape_string($conn, $_POST['event_venue']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    
    // Insert into events table (the one students use)
    $query = "INSERT INTO events (event_name, event_description, event_type, event_date, event_time, venue, created_by, created_at, is_active) 
              VALUES ('$name', '$desc', '$event_type', '$date', '$time', '$venue', 1, NOW(), 1)";
    
    if (mysqli_query($conn, $query)) {
        $success = "Event added successfully and will be visible to all students and alumni!";
    } else {
        $error = "Failed to add event: " . mysqli_error($conn);
    }
}

// Delete event
if (isset($_GET['delete'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['delete']);
    if (mysqli_query($conn, "DELETE FROM events WHERE event_id = '$event_id'")) {
        $success = "Event deleted!";
    }
}

// Fetch all events from the new events table
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events Management - SGBIT Admin</title>
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
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            color: #667eea;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            background: #6c757d;
            color: white;
            display: inline-block;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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

        .add-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .events-grid {
            display: grid;
            gap: 1.5rem;
        }

        .event-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            border-left: 4px solid #667eea;
            position: relative;
        }

        .event-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }

        .event-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
            color: #666;
            flex-wrap: wrap;
        }

        .event-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: #667eea;
            color: white;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .btn-delete {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
        }

        .registration-count {
            margin-top: 1rem;
            padding: 0.5rem;
            background: #e3f2fd;
            border-radius: 5px;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Events Management</h1>
            <a href="adminpage.php" class="btn">← Back</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Event Form -->
        <div class="add-form">
            <h2 style="color: #667eea; margin-bottom: 1rem;">Add New Event</h2>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Event Name: <span style="color:red;">*</span></label>
                        <input type="text" name="event_name" required>
                    </div>
                    <div class="form-group">
                        <label>Event Type: <span style="color:red;">*</span></label>
                        <select name="event_type" required>
                            <option value="">Select Type</option>
                            <option value="reunion">Reunion</option>
                            <option value="workshop">Workshop</option>
                            <option value="seminar">Seminar</option>
                            <option value="sports">Sports</option>
                            <option value="cultural">Cultural</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date: <span style="color:red;">*</span></label>
                        <input type="date" name="event_date" required>
                    </div>
                    <div class="form-group">
                        <label>Time: <span style="color:red;">*</span></label>
                        <input type="time" name="event_time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Venue: <span style="color:red;">*</span></label>
                    <input type="text" name="event_venue" placeholder="e.g., Main Auditorium, Seminar Hall" required>
                </div>

                <div class="form-group">
                    <label>Description: <span style="color:red;">*</span></label>
                    <textarea name="event_desc" placeholder="Provide detailed information about the event..." required></textarea>
                </div>

                <button type="submit" name="add_event" class="btn-primary">✨ Create Event</button>
            </form>
        </div>

        <!-- Events List -->
        <h2 style="color: #667eea; margin-bottom: 1.5rem;">All Events</h2>
        <div class="events-grid">
            <?php if (mysqli_num_rows($events) > 0): ?>
                <?php while ($event = mysqli_fetch_assoc($events)): ?>
                    <?php
                    // Get registration count
                    $event_id = $event['event_id'];
                    $reg_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM event_registrations WHERE event_id = $event_id"))['count'];
                    ?>
                    <div class="event-card">
                        <a href="?delete=<?php echo $event['event_id']; ?>" class="btn-delete" 
                           onclick="return confirm('Delete this event? This will also delete all registrations.')">🗑 Delete</a>
                        
                        <span class="event-type"><?php echo ucfirst($event['event_type']); ?></span>
                        <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <div class="event-meta">
                            <span>📅 <?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                            <span>⏰ <?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                            <span>📍 <?php echo htmlspecialchars($event['venue']); ?></span>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($event['event_description'])); ?></p>
                        
                        <div class="registration-count">
                            <strong>👥 <?php echo $reg_count; ?></strong> student(s) registered
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 3rem;">No events yet. Create your first event!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>