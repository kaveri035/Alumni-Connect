<?php
session_start();
require_once 'db_config.php';

// Redirect if not logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $st_register = mysqli_real_escape_string($conn, $_SESSION['st_register']);
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
    
    // Verify event exists and is upcoming
    $event_check = mysqli_query($conn, "SELECT * FROM events WHERE event_id = '$event_id' AND event_date >= CURDATE()");
    
    if (mysqli_num_rows($event_check) == 0) {
        $_SESSION['event_error'] = "Event not found or has already passed!";
        header("Location: student_upcoming_events.php");
        exit();
    }
    
    $event = mysqli_fetch_assoc($event_check);
    
    // Check if already registered
    $check_query = "SELECT * FROM event_registrations WHERE event_id = '$event_id' AND st_register = '$st_register'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['event_error'] = "You are already registered for this event!";
        header("Location: student_upcoming_events.php");
        exit();
    }
    
    // Register for the event
    $registration_date = date('Y-m-d H:i:s');
    
    $insert_query = "INSERT INTO event_registrations (event_id, user_id, st_register, registration_date, attendance_status) 
                     VALUES ('$event_id', 0, '$st_register', '$registration_date', 'registered')";
    
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['event_success'] = "Successfully registered for '" . htmlspecialchars($event['event_name']) . "'!";
        header("Location: student_upcoming_events.php");
    } else {
        $_SESSION['event_error'] = "Registration failed. Please try again. Error: " . mysqli_error($conn);
        header("Location: student_upcoming_events.php");
    }
    exit();
    
} else {
    $_SESSION['event_error'] = "Invalid request!";
    header("Location: student_upcoming_events.php");
    exit();
}
?>