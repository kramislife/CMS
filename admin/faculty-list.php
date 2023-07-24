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
  <title>CMS | Faculty</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/style-responsive.css">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
  <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
  <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
   <link rel="stylesheet" href="//cdn.datatables.net/autofill/2.5.3/css/autoFill.bootstrap4.min.css"> 
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>
<body>

<?php include("../admin/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

$errorMessage = '';
$successMessage = '';

?>

<section id="container">
  <section id="main-content">
    <section class="wrapper">
      <h4 style="padding-bottom: 10px; padding-top: 10px; font-weight: bolder; font-family: 'Times New Roman', Times, serif;">FACULTY MEMBER</h4>
      
      <div class="row mt">
     <!-- <button class="btn fa fa-plus" style="float: right; margin-bottom: 10px; font-weight: bolder; color: black; font-size: larger;" data-toggle="modal" data-target="#createUserModal"> Create New Faculty</button> -->
        <div class="col-lg-12">

     <!--   <div class="btn-group">
              <button class="btn btn-default buttons-copy" tabindex="0" aria-controls="records" type="button">
                <span>Copy</span>
              </button>
              <button class="btn btn-default buttons-csv" tabindex="0" aria-controls="records" type="button">
                <span>CSV</span>
              </button>
              <button class="btn btn-default buttons-excel" tabindex="0" aria-controls="records" type="button">
                <span>Excel</span>
              </button>
              <button class="btn btn-default buttons-pdf" tabindex="0" aria-controls="records" type="button">
                <span>PDF</span>
              </button>
              <button class="btn btn-default buttons-print" tabindex="0" aria-controls="records" type="button">
                <span>Print</span>
              </button>
            </div> -->

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
              <table id="faculty" class="table table-bordered table-striped table-condensed">
             
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Date of Birth</th>
                    <th>Department</th>
                    <th>Updated Time</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query($conn, "SELECT * FROM faculty_login");
                  while ($row = mysqli_fetch_array($query)) {
                  ?>
                    <tr>
                      <td data-label="Name:"><?php echo htmlentities($row['FullName']); ?></td>
                      <td data-label="Email:"><?php echo htmlentities($row['Email']); ?></td>
                      <td  data-label="Contact Number:"><?php echo htmlentities($row['ContactNo']); ?></td>
                      <td  data-label="Birth Date:"><?php echo htmlentities($row['DateofBirth']); ?></td>
                      <td  data-label="Department:"><?php echo htmlentities($row['Department']); ?></td>
                      <td  data-label="Time:"><?php echo htmlentities($row['Updated_Time']); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
</section>


      <!-- Create User Modal 
      <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div style="background-color: #8f0303;" class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                  <input type="text" class="form-control" id="contact" name="contact" required maxlength="11">
                </div>
                <div class="form-group">
                  <label for="department">Department:</label>
                  <select name="department" id="department" class="form-control" required="">
                    <option disabled selected value="Department">Department</option>
                    <option value="Administrator">Administrator</option>
                    <option value="HR Department">HR Department</option>
                    <option value="IT Department">IT Department</option>
                  </select>
                </div>
                <div id="createUserMessage" class="alert" style="display: none;"></div>
                <button type="submit" class="btn btn-success">Create User</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
    </section>
  </section>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <!-- jQuery -->
  
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>


<!--DATA TABLES -->
<script>
  $(document).ready(function() {
    $('#faculty').DataTable({
      dom: 'lBfrtip',
      buttons: [
        {
          extend: 'copy',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          },
          filename: 'CMS | Faculty',
          title: 'CMS | List of Faculty'
      
        },
        {
          extend: 'excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          },
          filename: 'CMS | Faculty',
          title: 'CMS | List of Faculty'
        },
        {
          extend: 'pdf',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          },
          filename: 'CMS | Faculty',
          title: 'CMS | List of Faculty'
        },
        {
          extend: 'csv',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          },
          filename: 'CMS | Faculty',
          title: 'CMS | List of Faculty'
        },
        {
          extend: 'print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          },
          filename: 'CMS | Faculty',
          title: 'CMS | List of Faculty'
        },
      ],
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      searching: true,
      paging: true,
      ordering: true,
      info: true,
      order: true,
    });
  });
</script>

          
  <script src="../assets/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../assets/js/jquery.scrollTo.min.js"></script>
  <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="../assets/js/common-scripts.js"></script>
</body>
</html> 



