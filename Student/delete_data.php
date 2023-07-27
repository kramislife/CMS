<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");


$successmsg = '';
$errormsg = '';


// DELETE FUNCTION IN MODAL
if (isset($_POST['deleteData'])) {
  $id = $_POST['deleteID'];
  $id = mysqli_real_escape_string($conn, $id);

  $statusQuery = "SELECT Status FROM complaints WHERE ComplaintNumber = '$id'";
  $statusResult = mysqli_query($conn, $statusQuery);
  $statusRow = mysqli_fetch_assoc($statusResult);
  $status = $statusRow['Status'];

  if (is_null($status)) {
      $deleteQuery = "DELETE FROM complaints WHERE ComplaintNumber = '$id'";
  } else {
      $deleteQuery = "UPDATE complaints SET isDeleted = 1 WHERE ComplaintNumber = '$id'";
  }

  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
      $_SESSION['successmsg'] = 'Complaint Deleted Successfully!';
  } else {
      $_SESSION['errormsg'] = 'Failed to Delete Complaint.';
  }

  header("Location: complaint-history");
  exit;
}

  
?>







