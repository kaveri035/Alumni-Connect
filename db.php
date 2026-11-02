<?php
// db.php - central DB connection using mysqli
$host = '127.0.0.1';
$user = 'root';
$pass = ''; // set your mysql password
$db   = 'sgbit_alumni_connect'; // change to your DB name if different

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
