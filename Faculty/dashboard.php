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

<?php include("../Faculty/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>

      <section id="main-content">
        <section class="wrapper">
        <h2 style="padding-bottom: 30px; padding-top: 10px;  font-weight:bolder; font-family: 'Times New Roman', Times, serif;">DASHBOARD</h2>
        
        <div class="col-md-3">
          <div class="card text-white bg-danger mb-3" style="height: 170px; font-size: larger;">
              <div class="card-body">
                  Pending Complaints
                  <?php
                  $rt = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID = '".$_SESSION['UserID']."' AND isDeleted = '0' AND Status IS NULL");
                  $num1 = mysqli_num_rows($rt);
                  if ($num1 > 0) {
                      echo '<h1 class="mb-0 pt-3" style="font-size: 30px;">'.htmlentities($num1).'</h1>'; 
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
        <div class="card-body">
            Approved Complaints
            <?php
            $status = "in Process";
            $rt = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID='".$_SESSION['UserID']."' AND isDeleted = '0' AND Status='$status'");
            $num1 = mysqli_num_rows($rt);
            if ($num1 > 0) {
                echo '<h1 class="mb-0 pt-3" style="font-size: 30px;">'.htmlentities($num1).'</h1>';
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
    <div class="card text-white bg-primary mb-3" style="height: 170px; font-size: larger;">
        <div class="card-body">
            Closed Complaints
            <?php
            $status = "Closed";
            $rt = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID='".$_SESSION['UserID']."' AND Status='$status' AND isDeleted ='0'");
            $num1 = mysqli_num_rows($rt);
            if ($num1 > 0) {
                echo '<h1 class="mb-0 pt-3" style="font-size: 30px;">'.htmlentities($num1).'</h1>';
            } else {
                echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
            }
            ?>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
         <a class="small text-white stretched-link" href="closed-complaint">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div> 
        </div>
    </div>
</div>


<div class="col-md-3">
    <div class="card text-white bg-secondary mb-3" style="height: 170px; font-size: larger;">
        <div class="card-body">
            Total Complaints
            <?php
            $rt = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID='".$_SESSION['UserID']."' AND isDeleted = '0'");
            $num1 = mysqli_num_rows($rt);
            if ($num1 > 0) {
                echo '<h1 class="mb-0 pt-3" style="font-size: 30px;">'.htmlentities($num1).'</h1>';
            } else {
                echo '<h4 class="mb-3 pt-3" style="font-size: 20px;">No Data!</h4>';
            }
            ?>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <?php
            if ($num1 > 0) {
                echo '<a class="small text-white stretched-link" href="complaint-history">View Details</a>';
                echo '<div class="small text-white"><i class="fas fa-angle-right"></i></div>';
            }
            ?>
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
// Fetch data from the database
$complaints_query = "SELECT Status, COUNT(*) AS count FROM complaints WHERE ComplaintID = '".$_SESSION['UserID']."' AND isDeleted = '0' GROUP BY Status";
$complaints_result = mysqli_query($conn, $complaints_query);

// Initialize arrays to store the labels and data for the graph
$labels = array();
$data = array();
$total = 0;

// Loop through the fetched data and populate the arrays
while ($row = mysqli_fetch_assoc($complaints_result)) {
    if ($row['Status'] === null) {
        $labels[] = "Pending";
    } else {
        $labels[] = $row['Status'];
    }
    $data[] = $row['count'];
    $total += $row['count'];
}

// Calculate the percentage of each complaint status
for ($i = 0; $i < count($data); $i++) {
    $data[$i] = round($data[$i] / $total * 100, 2);
}

 // Output the JSON data for the graph
  echo json_encode($labels);
  echo json_encode($data);
?>

  /* PIE GRAPH */
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode($labels); ?>,
      datasets: [{
        label: 'Percentage',
        data: <?php echo json_encode($data); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.9)',
          'rgba(255, 159, 64, 0.9)',
          'rgba(255, 205, 86, 0.9)',
          'rgba(75, 192, 192, 0.9)',
          'rgba(54, 162, 235, 0.9)',
          'rgba(153, 102, 255, 0.9)',
          'rgba(201, 203, 207, 0.9)'
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
        label: 'Complaint Status',
        data: <?php echo json_encode($data); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.9)',
          'rgba(255, 159, 64, 0.9)',
          'rgba(255, 205, 86, 0.9)',
          'rgba(75, 192, 192, 0.9)',
          'rgba(54, 162, 235, 0.9)',
          'rgba(153, 102, 255, 0.9)',
          'rgba(201, 203, 207, 0.9)'
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
      <script src="../assets/js/jquery-1.8.3.min.js"></script>
      <script src="../assets/js/bootstrap.min.js"></script>
      <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
      <script src="../assets/js/jquery.scrollTo.min.js"></script>
      <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
      <script src="../assets/js/jquery.sparkline.js"></script>
      <script src="../assets/js/common-scripts.js"></script>
      <script src="../assets/js/chart-master/Chart.js"></script>  
      <script type="text/javascript" src="../assets/js/gritter/js/jquery.gritter.js"></script>
      <script type="text/javascript" src="../assets/js/gritter-conf.js"></script>
      <script src="../assets/js/sparkline-chart.js"></script>    
    <script src="../assets/js/zabuto_calendar.js"></script>	
    </body>
  </html>
 




