<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['UserID'])) {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect the user to the login page or any other desired page
    header("Location: http://localhost/CMS/");
    exit();
}
?>
