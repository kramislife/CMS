<?php
session_start();

// If the user is not logged in
if (!isset($_SESSION['UserID'])) {
    
    // Redirect to the home page
    header("Location: http://localhost/ComplaintManagementSystem/");
    exit;
}
?>


