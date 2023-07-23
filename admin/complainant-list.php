<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

// Initialize variables for success and error messages
$successMessage = "";
$errorMessage = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the form data
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $contact = $_POST['contact'];
  $department = $_POST['department'];

  // Check if user already exists in the database
  $query = "SELECT * FROM faculty_login WHERE FullName = '$fullname' AND Email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // User already exists
    $errorMessage = "User Already Created!";
  } else {
    // Insert the new user into the database
    $insertQuery = "INSERT INTO faculty_login (FullName, Email, ContactNo, Department) VALUES ('$fullname', '$email', '$contact', '$department')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
      // User creation successful
      $successMessage = "User Created Successfully!";
    } else {
      // User creation failed
      $errorMessage = "Error creating user: " . mysqli_error($conn);
    }
  }
}
?>


<!DOCTYPE html>
<html plang="en">
<head>
  <meta charset="utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS | Complainant</title>
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
        <h3>Complainant</h3>
        <div class="row mt">
          <div class="col-lg-12">      

          <?php if (!empty($errorMessage)): ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
    <?php echo $errorMessage; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
  <div class="alert alert-success alert-dismissible" role="alert">
    <?php echo $successMessage; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif; ?>

            <div class="content-panel">
              <section id="unseen">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                    <th style="text-align: center;">Complaint Number</th>
                      <th style="text-align: center;">Fullname</th>
                      <th style="text-align: center;">Email</th>
                      <th style="text-align: center;">Contact Number</th>
                      <th style="text-align: center;">Department</th>
                      <th style="text-align: center;">Updated Time</th>                   
                    </tr>
                  </thead>
                  <tbody>
                    
                    <?php 
                      $query = mysqli_query($conn, "SELECT * FROM faculty_login");
                      while ($row = mysqli_fetch_array($query)) {
                    ?>

                    <tr> 
                    <td style="text-align:center;"><?php echo htmlentities($row['ComplaintNumber']);?></td>                   
                      <td style="text-align:center;"><?php echo htmlentities($row['FullName']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['Email']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['ContactNo']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['Department']);?></td>
                      <td style="text-align:center;"><?php echo htmlentities($row['Updated_Time']);?></td>   
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </section>
            </div>
          </div>
        </div>

    <!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div style="background-color: #8f0303;" class="modal-header">
        <h4 class="modal-title" id="createUserModalLabel">Create New Faculty</h4>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="createUserForm">
          <div class="form-group">
            <label for="fullname">Full Name:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="contact">Contact Number:</label>
            <input type="text" class="form-control" id="contact" name="contact" required>
          </div>
          <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" class="form-control" id="department" name="department" required>
          </div>
          <div id="createUserMessage" class="alert" style="display: none;"></div>
          <button type="submit" class="btn btn-success">Create User</button>
        </form>
      </div>
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