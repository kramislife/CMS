<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Complaint Details</title>
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
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">  
</head>
<body>
  <section id="container">
    <?php include('../Faculty/sidebar.php'); ?>
    <?php include ('../includes/header.php');?>
    <section id="main-content">
      <section class="wrapper site-min-height">
      <h4 style=" padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">COMPLAINT DETAILS</h4>

      <?php
        $query = mysqli_query($conn, "SELECT c.*, f.FullName, f.Email
        FROM complaints AS c
        JOIN faculty_login AS f ON c.ComplaintID = f.UserID
        WHERE c.ComplaintID = '".$_SESSION['UserID']."' AND c.ComplaintNumber ='".$_GET['cid']."'");

              while ($row = mysqli_fetch_array($query)) {
              ?>
                <div class="content-panel">
                  <form class="form-horizontal style-form">

                  <div class="form-group">
                      <label class="col-sm-2 control-label"><strong style="color: black">Name: </strong></label>
                      <div class="col-sm-2 control-label">
                        <p><?php echo htmlentities($row['FullName']);?></p>
                      </div>           
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Email : </b></label>
                      <div class="col-sm-2 control-label">
                        <p><?php echo htmlentities($row['Email']);?></p>
                      </div>           
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Complaint Number : </b></label>
                      <div class="col-sm-4 control-label">
                        <p><?php echo htmlentities($row['ComplaintNumber']);?></p>
                      </div>           
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Complaint Type : </b></label>
                      <div class="col-sm-4 control-label">
                        <p><?php echo htmlentities($row['ComplaintType']);?></p>
                      </div>           
                    </div>

                    <?php if ($row['ComplaintType'] === 'Others'): ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Others:</b></label>
                      <div class="col-sm-4 control-label">
                        <p><?php echo htmlentities($row['Others']);?></p>
                      </div>
                    </div>
                    <?php endif; ?>


                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Complaint Details :</b></label>
                      <div class="col-sm-4 control-label" style=" width: 80%; text-align:justify; word-wrap: break-word;">
                        <p><?php echo htmlentities($row['ComplaintDetails']);?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"><b style="color: black">Complaint File :</b></label>
                      <div class="col-sm-4 control-label">
                        <p id="reviewCompFile">
                          <?php
                          $cfile = $row['ComplaintFile'];
                          if ($cfile == "" || $cfile == "NULL") {
                            echo htmlentities("None");
                          } else {
                          ?>
                            <a href="../complaintDocs/<?php echo htmlentities($row['ComplaintFile']); ?>" target="_blank">View File</a>
                          <?php } ?>
                        </p>                      
                      </div>
                    </div>

                    <?php
                    $queryStatus = mysqli_query($conn, "SELECT Status FROM complaints WHERE ComplaintID = '".$_SESSION['UserID']."'AND ComplaintNumber ='".$_GET['cid']."'");
                    while ($statusRow = mysqli_fetch_array($queryStatus)) {
                    ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><b style="color: black">Status: </b></label>  
                        <div class="col-sm-2 control-label">
                          <?php
                          $cStatus = $statusRow['Status'];
                          if ($cStatus == "" || $cStatus == NULL) {
                            echo htmlentities("Pending");
                          } else {
                            echo htmlentities($statusRow['Status']);
                          }
                          ?>
                        </div>         
                      </div> 
                    <?php
                    }
                    ?>          
                  </form>   
                </div>
              <?php
              }
              ?>
           </div>
      </section>
    </section>
  </section>

  <script>
  if (data.complaintFile) {
    $('#reviewCompFile').html('<a href="../complaintDocs/' + encodeURIComponent(data.complaintFile) + '" target="_blank">View File</a>');
  } else {
    $('#reviewCompFile').text("No file Available");
  }
</script>
  
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="../assets/js/jquery.scrollTo.min.js"></script>
<script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="../assets/js/common-scripts.js"></script>
<script src="../assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="../assets/js/bootstrap-switch.js"></script>
<script src="../assets/js/jquery.tagsinput.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-daterangepicker/date.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script src="../assets/js/form-component.js"></script>    
</body>
</html>


