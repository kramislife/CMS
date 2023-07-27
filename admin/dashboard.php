<?php 

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include ('../includes/connection.php');
include("../includes/check_session.php");


// Retrieve the data for the pie chart
$data_query = "SELECT ComplaintType, COUNT(*) AS complaint_count FROM complaints GROUP BY ComplaintType";
$data_query_run = mysqli_query($conn, $data_query);
$complaint_data = array();
$background_colors = array();
$border_colors = array();

while ($row = mysqli_fetch_assoc($data_query_run)) {
  $complaint_data[] = $row['complaint_count'];
  $background_colors[] = 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.6)';
  $border_colors[] = 'rgb(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ')';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

<?php include("../admin/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

                  <section id="main-content">
                    <section class="wrapper">
                    <h2 style="padding-bottom: 30px; padding-top: 10px;  font-weight:bolder; font-family: 'Times New Roman', Times, serif;">DASHBOARD</h2>

                    <div class="row">
            <div class="col-md-3">
              <div class="card text-white bg-danger mb-3" style="height: 170px; font-size: larger;">
                <div class="card-body">Pending Complaints
                <?php
                  $pending_query = "SELECT * FROM complaints WHERE (Status = 'Pending' OR Status IS NULL) AND isDeleted = '0'";
                  $pending_query_run = mysqli_query($conn, $pending_query);
                  $pending_count = mysqli_num_rows($pending_query_run);

                  if ($pending_count > 0) {
                    echo '<h1 class="mb-3 pt-3" style="font-size: 30px;">'.$pending_count.'</h1>';
                  } else {
                    echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
                  }
                ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="pending-complaint">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card text-white bg-success mb-3" style="height: 170px; font-size: larger;">
                <div class="card-body">Confirmed Complaints
                <?php
                  $confirmed_query = "SELECT * FROM complaints WHERE (Status = 'In Process' OR Status = 'Closed') AND Flag = '0'";
                  $confirmed_query_run = mysqli_query($conn, $confirmed_query);
                  $confirmed_count = mysqli_num_rows($confirmed_query_run);

                  if ($confirmed_count > 0) {
                    echo '<h1 class="mb-3 pt-3" style="font-size: 30px;">'.$confirmed_count.'</h1>';
                  } else {
                    echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
                  }
                ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="confirmed-complaint">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3" style="height: 170px; font-size:larger">
                  <div class="card-body">
                  <span class="opacity-icon">
                              Total Complaints
                              <?php
                                $total_query = ("SELECT COUNT(*) AS complaint_count FROM complaints");

                                $total_query_run = mysqli_query($conn, $total_query);
                                $total_complaints = 0;

                                while ($complaint = mysqli_fetch_assoc($total_query_run)) {
                                  $total_complaints += $complaint['complaint_count'];
                                }

                                if ($total_complaints > 0) {
                                  echo '<h1 class="mb-0 pt-3" style="font-size: 30px;">'.$total_complaints.'</h1>';
                                } else {
                                  echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
                                }
                              ?>
                            </span>
                    <div class="text-right" style="opacity: 0.5; font-size: 50px; align-items:center;">
                <i class="fas fa-user-plus"></i>
              </div>

                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="complaint-summary">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
                </div>
            </div>

            <div class="col-md-3">
              <div class="card text-white bg-secondary mb-3" style="height: 170px; font-size: larger;">
                <div class="card-body">Total Faculty Members
                <?php
                  $total_query = "SELECT COUNT(*) AS faculty_count FROM faculty_login";
                  $total_query_run = mysqli_query($conn, $total_query);
                  $faculty = mysqli_fetch_assoc($total_query_run);

                  if ($faculty['faculty_count'] > 0) {
                    echo '<h1 class="mb-3 pt-3" style="font-size: 30px;">'.$faculty['faculty_count'].'</h1>';
                  } else {
                    echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
                  }
                ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="faculty-list">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>
          </div>    
      
          <div class="graphBox">
            <div class="box">    
              <canvas id="myChart"></canvas>        
            </div>

            <div class="box">
            <canvas id="myGraph"></canvas>
            </div> 
          </div>
        </section>
      </section>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
  
<script>
  <?php

  $complaints_query = "SELECT ComplaintType, COUNT(*) AS count FROM complaints GROUP BY ComplaintType";
  $complaints_result = mysqli_query($conn, $complaints_query);

  $labels = array();
  $data = array();
  $total = 0;

  while ($row = mysqli_fetch_assoc($complaints_result)) {
    $labels[] = $row['ComplaintType'];
    $data[] = $row['count'];
    $total += $row['count'];
  }


  for ($i = 0; $i < count($data); $i++) {
    $data[$i] = round($data[$i] / $total * 100, 2);
  }
  

  echo json_encode($labels);
  echo json_encode($data);

?>

  /* PIE GRAPH */
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'polarArea',
    data: {
      labels: <?php echo json_encode($labels); ?>,
      datasets: [{
        label: 'Percentage',
        data: <?php echo json_encode($data); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.3)',
          'rgba(255, 159, 64, 0.3)',
          'rgba(255, 205, 86, 0.3)',
          'rgba(75, 192, 192, 0.3)',
          'rgba(54, 162, 235, 0.3)',
          'rgba(153, 102, 255, 0.3)',
          'rgba(201, 203, 207, 0.3)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });

  /* BAR GRAPH */
  const Graph = document.getElementById('myGraph');
  new Chart(Graph, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($labels); ?>,
      datasets: [{
        label: 'Complaint Summary Percentage',
        data: <?php echo json_encode($data); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.3)',
          'rgba(255, 159, 64, 0.3)',
          'rgba(255, 205, 86, 0.3)',
          'rgba(75, 192, 192, 0.3)',
          'rgba(54, 162, 235, 0.3)',
          'rgba(153, 102, 255, 0.3)',
          'rgba(201, 203, 207, 0.3)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
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
      <script src="../assets/js/jquery-1.8.3.min.js"></script>
      <script src="../assets/js/bootstrap.min.js"></script>
      <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
      <script src="../assets/js/chart-area-demo.js"></script>
      <script src="../assets/js/chart-bar-demo.js"></script>
      <script src="../assets/js/chart-pie-demo.js"></script>
      <script src="../assets/js/datatables-demo.js"></script>
      <script src="../assets/js/datatables-simple-demo.js"></script>
      <script src="../assets/js/scripts.js"></script>
    </body>
  </html>
 