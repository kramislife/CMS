<?php 

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

?>

<!DOCTYPE html>
<html plang="en">
<head>
  <meta charset="utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS | Complaint History</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css"/>
  <link rel="stylesheet" type="text/css" href="../assets/css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="../assets/js/gritter/css/jquery.gritter.css" />
  <link rel="stylesheet" type="text/css" href="../assets/lineicons/style.css">    
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/style-responsive.css">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
  <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
  <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">  
</head>
<body>
  <section id="container">

  <?php include("../Faculty/sidebar.php"); ?>
  <?php include("../includes/header.php"); ?>  

    <section id="main-content">
      <section class="wrapper">
      <h4 style=" padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">COMPLAINT HISTORY</h4>
        <div class="row mt">
          <div class="col-lg-12">

          <?php if (isset($_SESSION['successmsg'])) { ?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($_SESSION['successmsg']); ?>
              </div>
            <?php unset($_SESSION['successmsg']);
            } ?>

            <?php if (isset($_SESSION['errormsg'])) { ?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($_SESSION['errormsg']); ?>
              </div>
            <?php unset($_SESSION['errormsg']);
            } ?>

            <div class="content-panel">
              <section id="unseen">
              <table id="records" class="table table-bordered table-striped table-condensed">
  <thead>
    <tr>
      <th>Complaint Number</th>
      <th>Status</th>
      <th>Complaint Type</th>
      <th>Date Submitted</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    
  <?php
  $userId = $_SESSION['UserID'];
  $query = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID='$userId'AND Flag = '0' ORDER BY 
  CASE 
  WHEN status = 'in process' THEN 1
  WHEN status = 'in process' THEN 2
  WHEN status = 'closed' THEN 3
  END, RegDate DESC");

    while ($row = mysqli_fetch_array($query)) {
      ?>
      <tr>
        <td data-label="Complaint Number:"  class="complaintNumber"><?php echo htmlentities($row['ComplaintNumber']);?></td>

        <td data-label="Status:">
          <?php
          $cStatus = $row['Status'];
          $statusClass = "";
          $statusText = "";


          if ($cStatus == "" || $cStatus == NULL) {
            $statusClass = "badge";
            $statusStyle = "background-color: #eb3e4f; font-size:13px;";
            $statusText = "Pending";
          } else if ($cStatus == "In Process") {
            $statusClass = "badge";
            $statusStyle = "background-color: #2eb44d; font-size:13px;";
            $statusText = htmlentities($row['Status']);
          } else if ($cStatus == "Closed") {
            $statusClass = "badge";
            $statusStyle = "background-color: #488eda; font-size:13px;";
            $statusText = htmlentities($row['Status']);
          } else {
            $statusClass = "badge";
            $statusStyle = "background-color: gray; color: white;";
            $statusText = htmlentities($row['Status']);
          }
          ?>

          <span class="<?php echo $statusClass; ?>" style="<?php echo $statusStyle; ?>"><?php echo $statusText; ?></span>
        </td>

        <td data-label="Complaint Type:"><?php echo htmlentities($row['ComplaintType']);?></td>
        <td data-label="Registered Date:"><?php echo htmlentities($row['RegDate']);?></td>

        <td data-label="Action:">
          <a href="complaint-details?cid=<?php echo htmlentities($row['ComplaintNumber']);?>">
            <button type="button" class="btn btn-primary fa fa-eye fa-lg edit_btn" name="editData"></button>
          </a>
          <button type="button" class="btn btn-danger fa fa-trash-o fa-lg delete_btn" data-toggle="modal" data-target="#deleteModal"></button>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
              </section>
            </div>
          </div>
        </div>
          <!-- Delete Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div style="background-color: #8f0303;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Complaint</h4>
              </div>
              <form id="deleteForm" action="delete_data.php" method="POST">
                <div class="modal-body">
                  <input type="hidden" name="deleteID" id="delete_id">
                  <p style="color:black">Are you sure you want to <strong>Delete</strong> this complaint?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="deleteData" class="btn btn-danger">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </section>
    </section>
  </section>

    
        
<!-- DELETE MODAL SCRIPT -->
<script>
  $(document).ready(function () {
    // Set delete_id value when the delete button is clicked
    $('.delete_btn').click(function(e) {
      e.preventDefault();

      var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
      var status = $(this).closest('tr').find('td:nth-child(2)').text().trim();
    
      if (status === "In Process") {
        // Show the message in the delete modal body
        $('#deleteModal .modal-body').html("<p style='color: black;'> Deletion for your complaints is not allowed because it is now <b>" + status +"</b></p>");
        $('#deleteModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
        $('#deleteModal').modal('show');
      } else {
        // Proceed with deletion
        $('#delete_id').val(complaintNumber);
        $('#deleteModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="deleteData" class="btn btn-danger">Delete</button>');
        $('#deleteModal').modal('show');
      }
    });
  });
</script>
                                                 
  <script>
$(document).ready(function() {
  var recordsTable = new DataTable('#records');
});
</script>

  <script src="../assets/js/jquery.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../assets/js/jquery.scrollTo.min.js"></script>
  <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="../assets/js/common-scripts.js"></script>
</body>
</html>

