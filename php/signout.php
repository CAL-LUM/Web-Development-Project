<?php
// Start or resume session
session_start();

// Check if session is active
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect the user to the home page page on sign out.
header("Location: ../index.html");
exit();
?>