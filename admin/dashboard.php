<?php 

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include ('../includes/connection.php');
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
        <h3 style="padding-bottom: 30px; padding-top: 10px;">Dashboard</h3>
        
        </section>
      </section>
    </section>

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
 






  