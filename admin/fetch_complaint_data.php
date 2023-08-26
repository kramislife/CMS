<?php
// fetch_complaint_data.php

// Connect to your database
include('../includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the UserID from the AJAX request
  $userID = $_POST['userID'];

  // Prepare and execute the SQL query to fetch complaint data
  $query = mysqli_prepare($conn, "SELECT ComplaintID, ComplainantName, Email, ComplaintType, Status, Updated_Time, ComplaintResponse FROM complaints WHERE UserID = ?");
  mysqli_stmt_bind_param($query, 's', $userID);
  mysqli_stmt_execute($query);
  $result = mysqli_stmt_get_result($query);

  // Fetch the complaint data as an associative array
  $complaints = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $complaints[] = $row;
  }

  // Return the complaint data as JSON
  header('Content-Type: application/json');
  echo json_encode($complaints);
} else {
  // Handle invalid requests
  echo 'Invalid request.';
}
?>
