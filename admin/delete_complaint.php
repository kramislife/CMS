<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include ("../includes/check_session.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $complaintId = $_POST['complaintId'];

  // Perform the deletion
  $query = mysqli_query($conn, "DELETE FROM complaints WHERE ComplaintID = '$complaintId'");
  if ($query) {
    // Deletion successful
    $response = array('success' => true);
    echo json_encode($response);
    exit;
  } else {
    // Deletion failed
    $response = array('success' => false, 'error' => 'Failed to delete the complaint: ' . mysqli_error($conn));
    echo json_encode($response);
    exit;
  }
}

// Invalid request
$response = array('success' => false, 'error' => 'Invalid request');
echo json_encode($response);
?>
