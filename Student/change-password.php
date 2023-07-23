<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

$successmsg = '';
$errormsg = '';

if (isset($_POST['submit'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Define the password requirements using regular expressions
    $uppercaseRegex = '/[A-Z]/';        // At least one uppercase letter
    $lowercaseRegex = '/[a-z]/';        // At least one lowercase letter
    $numericRegex = '/[0-9]/';           // At least one numeric digit
    $specialCharRegex = '/[^A-Za-z0-9]/'; // At least one special character

    $query = "SELECT Password FROM student_login WHERE UserID = '" . $_SESSION['UserID'] . "'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($oldPassword, $row['Password'])) {
            if ($newPassword != $oldPassword) { // Check if the new password is different from the old password
                if ($newPassword == $confirmPassword) {
                    if (
                        preg_match($uppercaseRegex, $newPassword) &&
                        preg_match($lowercaseRegex, $newPassword) &&
                        preg_match($numericRegex, $newPassword) &&
                        preg_match($specialCharRegex, $newPassword)
                    ) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updateQuery = "UPDATE student_login SET Password = '$hashedPassword' WHERE UserID = '" . $_SESSION['UserID'] . "'";
                        mysqli_query($conn, $updateQuery);

                        $successmsg = "Password Changed Successfully!";
                    } else {
                        $errormsg = "Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
                    }
                } else {
                    $errormsg = "New and Confirm Password do not match!";
                }
            } else {
                $errormsg = "You entered your old password. Please try another password.";
            }
        } else {
            $errormsg = "Old Password is Incorrect!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Change Password</title>
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

<?php include("../Student/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

      <section id="main-content">
          <section class="wrapper">
          	<h4 style=" padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">CHANGE PASSWORD</h4>
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

                      <form class="form-horizontal style-form" method="post" name="chngpwd" onSubmit="return valid();">
                          <div class="form-group">
                              <label style="padding-top: 20px;" class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Current Password</strong></label>
                              <div style="padding-top: 20px;" class="col-sm-7">
                                  <input type="password" name="oldPassword" required="required" class="form-control">
                              </div>
                          </div>

                           <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">New Password</strong></label>
                              <div class="col-sm-7">
                                  <input type="password" name="newPassword" required="required" class="form-control" id="newPassword">
                                  <medium style="color:red" id="newPasswordRequirements" class="form-text text-muted"></medium>
                              </div>
                           </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Confirm Password</strong></label>
                              <div class="col-sm-7">
                                  <input type="password" name="confirmPassword" required="required" class="form-control" id="confirmPassword">
                                  <medium style="color:red" id="confirmPasswordRequirements" class="form-text text-muted"></medium>
                              </div>
                          </div>
                          <div class="form-group text-center">
                            <div class="col-sm-12 d-flex justify-content-center"  style="padding-top: 15px;">
                            <button type="submit" name="submit" class="btn btn-success ">Save Profile</button>
                        </div>
                      </div>
                     </form>
                 </div>
             </div>
          </div>     	
		</section>
      </section>

      <script>
    // Get the input fields and the password requirements message elements
    var newPasswordInput = document.getElementById('newPassword');
    var newPasswordRequirements = document.getElementById('newPasswordRequirements');

    // Function to display the password requirements message for newPassword
    function showNewPasswordRequirements() {
        newPasswordRequirements.textContent = "Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
    }

    // Function to hide the password requirements message for newPassword
    function hideNewPasswordRequirements() {
        newPasswordRequirements.textContent = "";
    }
    
    // Add event listeners to show/hide the password requirements message for newPassword
    newPasswordInput.addEventListener('focus', showNewPasswordRequirements);
    newPasswordInput.addEventListener('blur', hideNewPasswordRequirements);

    // Add event listeners to show/hide the password requirements message for confirmPassword
    confirmPasswordInput.addEventListener('focus', showConfirmPasswordRequirements);
    confirmPasswordInput.addEventListener('blur', hideConfirmPasswordRequirements);
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
 




