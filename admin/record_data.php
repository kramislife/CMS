<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");


$successmsg = '';
$errormsg = '';

// VIEW FUNCTION IN MODAL
if (isset($_POST['checking_view_btn'])) {
  $complaintNumber = $_POST['complaintNumber'];

  $query = "SELECT * FROM complaints WHERE ComplaintNumber = '$complaintNumber'";
  $query_run = mysqli_query($conn, $query);

  if (mysqli_num_rows($query_run) > 0) {
    $row = mysqli_fetch_assoc($query_run);

    $data = array(
      'success' => true,
      'complaintNumber' => $row['ComplaintNumber'],
      'name' => $row['ComplainantName'],
      'email' => $row['Email'],
      'complaintType' => $row['ComplaintType'],
      'others' => $row['Others'],
      'ComplaintDetails' => $row['ComplaintDetails'],
      'complaintFile' => $row['ComplaintFile'],
      'status' => $row['Status']
    );

    echo json_encode($data);
  } else {
    $data = array('success' => false);
    echo json_encode($data);
  }
}

// DELETE FUNCTION IN MODAL
if (isset($_POST['deleteData'])) {
  $id = $_POST['deleteID'];

  $id = mysqli_real_escape_string($conn, $id);

  $query = "DELETE FROM complaints WHERE ComplaintNumber = '$id'";
  $query_run = mysqli_query($conn, $query);

  if ($query_run) {
    $_SESSION['successmsg'] = 'Complaint Successfully Deleted.';
    header("Location: complaint-records");
    exit;
  } else {
    $_SESSION['errormsg'] = 'Failed to Delete Complaint.';
    header("Location: complaint-records");
    exit;
  }
}

?>

