<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");


$successmsg = '';
$errormsg = '';

// EDIT FUNCTION IN MODAL TODAY'S COMPLAINTS
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

<<<<<<< HEAD
// EDIT FUNCTION IN MODAL CONFIRMED COMPLAINTS
// EDIT FUNCTION IN MODAL CONFIRMED COMPLAINTS
if (isset($_POST['checking_update_btn'])) { // Removed space before checking_update_btn
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


=======
>>>>>>> e439a6ac6efdf9a5b410b18b65cde96983d2fcb2

// DELETE FUNCTION IN MODAL
if (isset($_POST['deleteData'])) {
  $id = $_POST['deleteID'];
  $id = mysqli_real_escape_string($conn, $id);

<<<<<<< HEAD
  $selectQuery = "SELECT * FROM complaints WHERE ComplaintNumber = '$id'";
  $complaintData = mysqli_query($conn, $selectQuery);
  $complaint = mysqli_fetch_assoc($complaintData);

  $insertQuery = "INSERT INTO deleted_Complaints (ComplainantName, Email, ComplaintID, ComplaintNumber, ComplaintType, Others, ComplaintDetails, ComplaintFile, Status, Updated_Time)
                  SELECT '{$complaint['ComplainantName']}', '{$complaint['Email']}', '{$complaint['ComplaintID']}', 
                         '{$complaint['ComplaintNumber']}', '{$complaint['ComplaintType']}', '{$complaint['Others']}', '{$complaint['ComplaintDetails']}', '{$complaint['ComplaintFile']}', 
                         '{$complaint['Status']}', '{$complaint['Updated_Time']}'";
  $insertResult = mysqli_query($conn, $insertQuery);

  if ($insertResult) {
    $deleteQuery = "DELETE FROM complaints WHERE ComplaintNumber = '$id'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
      $_SESSION['successmsg'] = 'Complaint Deleted Successfully!';
      header("Location: confirmed-complaint");
      exit;
    }
  } else {
    $_SESSION['errormsg'] = 'Failed to Delete Complaint.';
    header("Location: confirmed-complaint");
    exit;
  }
}
=======
  $deleteQuery = "UPDATE complaints SET Flag = 1 WHERE ComplaintNumber = '$id'";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
      $_SESSION['successmsg'] = 'Complaint Deleted Successfully!';
  } else {
      $_SESSION['errormsg'] = 'Failed to Delete Complaint.';
  }

  header("Location: confirmed-complaint");
  exit;
}
  
>>>>>>> e439a6ac6efdf9a5b410b18b65cde96983d2fcb2

?>

