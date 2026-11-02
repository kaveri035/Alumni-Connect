<?php
// Start or resume the session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optional: clear cookies if you used any custom cookies
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, '/');
}

// Redirect to home or login page
header("Location: index.php");
exit();
?>
