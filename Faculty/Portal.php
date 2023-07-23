<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

if (isset($_POST['submit'])) {
    $Username = $_POST['user'];
    $Password = $_POST['pass'];
    
    $sql = "SELECT UserID, Username, Password FROM faculty_login WHERE Username = '$Username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) == 1) {
        $username = $row['Username'];
        $_SESSION['UserID'] = $row['UserID'];
    
        if (password_verify($Password, $row['Password'])) {
            // Password is correct (hashed password matches)
            if (strpos($username, "admin") !== false) {
                // Redirect to the Analytics module for admin
                header("Location: ../osas/dashboard");
            } else {
                // Redirect to the regular user dashboard
                header("Location: ../Faculty/dashboard");
            }
            exit;
        } elseif ($Password == $row['Password']) {
            // Password is correct (not hashed password matches)
            if (strpos($username, "admin") !== false) {
                // Redirect to the Analytics module for admin
                header("Location: ../osas/dashboard");
            } else {
                // Redirect to the regular user dashboard
                header("Location: ../Faculty/dashboard");
            }
            exit;
        }
    }
    
    // If no match found, redirect to the login page with an error message
    header("Location: ../Faculty/Portal?error");
    exit;
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Log-in</title>
    
    <link rel="stylesheet" href="../Faculty/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Faculty/css/Faculty_Portal.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
    <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">   
</head>
<body>

    <h1>Faculty's Portal</h1>
            <form action="" name="form" method="post">
                
            <div class="container">

            <div class="mx-auto col-10 col-md-8 col-lg-6">

            <?php
                if(isset($_GET['error']))
                { ?>
                 <div class="alert alert-danger alert-dismissible" role="alert" id="alert_danger">
                <a href="" class="btn-close" data-dismiss="alert" aria-label="close"></a>
                    <strong>Invalid Credentials!</strong> Please Try Again.
            </div>
            <?php } ?>

                          <!--USERNAME-->
    <div class="form-floating mb-3 opacity-75 fw-bold">
        <input type="text" class="form-control" style="font-weight:bolder;" name="user" id="floatingInput" placeholder="q" required>
        <label for="floatingInput">Enter your Username</label>
    </div>      

                        <!--PASSWORD-->
    <div class="form-floating opacity-75 fw-bold">
        <input type="password" class="form-control" style="font-weight:bolder;" name="pass" id="floatingPassword" placeholder="Enter your Password" required maxlength="20">
        <label for="floatingPassword">Enter your Password</label>
    </div> 
                         <!--SUBMIT-->
    
    <div class="d-grid gap-2">
        <input class="btn btn-primary" id="btn" type="submit" name="submit" value="Sign-in">
    <a href="../Faculty/forgotpassword" button type="button" class="btn btn-danger" id="dngr">Forgot Password?</a>
        </div>
               
        </div> 
        </div>
                
    <script src="js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>





