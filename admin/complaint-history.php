<?php 

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include ("../includes/check_session.php");


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
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">  
</head>
<body>
  <section id="container">

  <?php include("../Admin/sidebar.php"); ?>
  <?php include("../includes/header.php"); ?>  

    <section id="main-content">
      <section class="wrapper">
        <h3 style="padding-top: 10px;">Complaint History</h3>
        <div class="row mt">
          <div class="col-lg-12">
            <div class="content-panel">
              <section id="unseen">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th style="text-align: center;">Complaint Number</th>
                      <th style="text-align: center;">Registration Date</th>
                      <th style="text-align: center;">Complaint Update</th>
                      <th style="text-align: center;">Status</th>
                      <th style="text-align: center;">Action</th>                   
                    </tr>
                  </thead>
                  <tbody>
                    
                    <?php 
                      $query = mysqli_query($conn, "SELECT * FROM facultycomplaints WHERE ComplaintID='".$_SESSION['UserID']."'");
                      while ($row = mysqli_fetch_array($query)) {
                    ?>

                    <tr>
                      <td style="text-align:center;"><?php echo htmlentities($row['ComplaintNumber']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['RegDate']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['Updated_Time']);?></td>
                      <td style="text-align:center;">

                        <?php 
                          $status = $row['Status'];
                          if ($status == "" || $status == "NULL") { 
                        ?>
                          <button type="button" class="btn btn-theme04">Pending</button>

                        <?php 
                          }
                          elseif ($status == "In Process") { 
                        ?>
                          <button type="button" class="btn btn-warning">In Process</button>
                          
                        <?php 
                          }
                          elseif ($status == "Closed") {
                        ?>
                          <button type="button" class="btn btn-success">Closed</button>
                        <?php 
                          } 
                        ?>
                        
                      </td>
                      <td style="text-align:center;">
                        <a href="complaint-details?cid=<?php echo htmlentities($row['ComplaintNumber']);?>">
                          <button type="button" class="btn btn-primary">View Details</button>
                        </a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </section>
            </div>
          </div>
        </div>
      </section>
    </section>
  </section>

  <script src="../assets/js/jquery.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../assets/js/jquery.scrollTo.min.js"></script>
  <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="../assets/js/common-scripts.js"></script>
</body>
</html>
