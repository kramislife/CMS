<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");


$successmsg = '';
$errormsg = '';

// EDIT FUNCTION IN MODAL
if (isset($_POST['checking_edit_btn'])) {
  $complaintNumber = $_POST['complaintNumber'];

  $query = "SELECT * FROM complaints WHERE ComplaintNumber = '$complaintNumber'";
  $query_run = mysqli_query($conn, $query);

  if (mysqli_num_rows($query_run) > 0) {
    $row = mysqli_fetch_assoc($query_run);

    $status = $row['Status'];
    if ($status === NULL) {
      $status = "Pending";
    }

    $data = array(
      'success' => true,
      'complaintNumber' => $row['ComplaintNumber'],
      'name' => $row['ComplainantName'],
      'email' => $row['Email'],
      'complaintType' => $row['ComplaintType'],
      'others' => $row['Others'],
      'ComplaintDetails' => $row['ComplaintDetails'],
      'complaintFile' => $row['ComplaintFile'],
      'status' => $status
    );

    echo json_encode($data);
  } else {
    $data = array('success' => false);
    echo json_encode($data);
  }
} elseif (isset($_POST['complaintNumber']) && isset($_POST['status'])) {
  $complaintNumber = $_POST['complaintNumber'];
  $status = $_POST['status'];

  // Update the complaint status in the database
  $query = "UPDATE complaints SET Status = '$status' WHERE ComplaintNumber = '$complaintNumber'";
  $query_run = mysqli_query($conn, $query);

  if ($query_run) {
    $_SESSION['successmsg'] = 'Complaint Status Updated Successfully!';
    echo json_encode(array('success' => true));
  } else {
    echo json_encode(array('success' => false));
  }
  exit;
}

// DELETE FUNCTION IN MODAL
if (isset($_POST['deleteData'])) {
  $id = $_POST['deleteID'];
  $id = mysqli_real_escape_string($conn, $id);

  $deleteQuery = "UPDATE complaints SET Flag = 1 WHERE ComplaintNumber = '$id'";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
      $_SESSION['successmsg'] = 'Complaint Deleted Successfully!';
  } else {
      $_SESSION['errormsg'] = 'Failed to Delete Complaint.';
  }

  header("Location: complaint-records");
  exit;
}
  
?>
