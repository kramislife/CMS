<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");


$successmsg = '';
$errormsg = '';

if(isset($_POST['submit']))
{
$contactNumber=$_POST['contactNumber'];
$userEmail=$_POST['userEmail'];
$address=$_POST['address'];
$department = $_POST['department'];

$query=mysqli_query($conn, "UPDATE faculty_login SET ContactNo='$contactNumber',  Email='$userEmail', Address='$address', Department ='$department' WHERE UserID='".$_SESSION['UserID']."'");

if ($query) {
  $_SESSION['profileUpdated'] = true;
  header("Location: profile");
  exit();
} else {
  $errormsg = "It looks like we have a problem in the Database. Please Try Again Later!";
}
} 
if (isset($_SESSION['profileUpdated']) && $_SESSION['profileUpdated'] === true) {
$successmsg = "Profile Updated Successfully!";
unset($_SESSION['profileUpdated']);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Profile</title>
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
    
  <?php include("../osas/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

  <section id="container">
  <section id="main-content">
    <section class="wrapper">
    <h4 style=" padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">PERSONAL INFORMATION</h4>
      <div class="row mt">
        <div class="col-lg-12">
          <div class="form-panel">

          <?php if($successmsg)
            {?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($successmsg);?></div>
            <?php }?>

          <?php if($errormsg)
            {?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($errormsg);?></div>
            <?php }?>

            <?php $query=mysqli_query($conn, "SELECT * FROM faculty_login WHERE UserID='".$_SESSION['UserID']."'");
                while($row=mysqli_fetch_array($query)) 
            {?> 

<h4 class="mb"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo htmlentities($row['FullName']);?>'s Profile</h4>

            <form class="form-horizontal style-form" method="post" name="profile">
              <div class="form-group">
              <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Name</strong></label>
                <div class="col-sm-4">
                  <input type="text" name="fullName" required="required" value="<?php echo htmlentities($row['FullName']);?>" class="form-control" disabled>                 
                </div>
                <label class="col-sm-2 control-label"><strong style="color: black">Department</strong></label>
                  <div class="col-sm-4">
                    <select name="department" id="department" class="form-control" required="" disabled>
                      <option disabled selected value="">Department</option>
                      <option value="Administrator">Administrator</option>
                      <option value="HR Department">HR Department</option>
                      <option value="IT Department">IT Department</option>
                    </select> 
                  </div>
              </div>

              <div class="form-group">   
                <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Place of Birth</strong></label>
                <div class="col-sm-4">
                  <input type="text" name="placeOfBirth" required="required" value="<?php echo htmlentities($row['PlaceofBirth']);?>" class="form-control" disabled>
                </div>
                <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Date of Birth</strong></label>
                <div class="col-sm-4">
                  <input type="text" name="dateOfBirth" required="required" value="<?php echo htmlentities($row['DateofBirth']);?>" class="form-control" disabled>
                </div>
              </div>

              <div class="form-group">
              <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Gender</strong></label>
                <div class="col-sm-4">
                 <input type="text" name="gender" required="required" value="<?php echo htmlentities($row['Gender']);?>"class="form-control" disabled> 
                </div>
                <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Email Address</strong></label>
                <div class="col-sm-4">
                  <input type="email" name="userEmail" required="required" value="<?php echo htmlentities($row['Email']);?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
              <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Contact Number</strong></label>
              <div class="col-sm-4">
                <input type="text"  name="contactNumber" required="required" maxlength="11" pattern="\d{11}" value="<?php echo htmlentities($row['ContactNo']);?>" class="form-control">
              </div>

                <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Address</strong> </label>
                <div class="col-sm-4">
                <textarea  name="address" required="required" class="form-control"><?php echo htmlentities($row['Address']);?></textarea>
               </div>              
              </div>

              <div class="form-group">
                <div class="col-sm-10 text-center">
                  <div class="pt-3 pl-4">
                  <span><i class="font-weight-bold"> <strong style="color: black">I hereby Certify that all the information provided are true and correct to the best of my knowledge.</strong></i></span>
                  </div>
                
                  <div class="col-sm-12 d-flex justify-content-center"  style="padding-top: 15px;">
                    <button type="submit" name="submit" class="btn btn-success ">Save Profile</button>
                  </div>
                </div>
              </div>   
            </form>
          </div>
        </div>
      </div>
    </section>
  </section>
</section>

<script>
  const dropdown = document.getElementById('department');

  const selectedOption = localStorage.getItem('selectedOption');
  if (selectedOption) {
    dropdown.value = selectedOption;
  }
  dropdown.addEventListener('change', function() {
    localStorage.setItem('selectedOption', this.value);
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
  <script>
      $(function(){
          $('select.styled').customSelect();
      });
  </script>
  </body>
</html>
<?php } ?>
