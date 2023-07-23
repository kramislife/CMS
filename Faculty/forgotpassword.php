<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../Faculty/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Faculty/css/Faculty_Portal.css">
</head>
<body>
 
    <div class="container flex-column " style="font-family: 'Times New Roman', Times, serif;">  
  
    <form action="../Faculty/forgotpasswordprocess.php" method="post">
  
    <div class="row align-items-center justify-content-center min-vh-100">

        <div class="col-12 col-md-8 col-lg-4">
          <div class="card shadow-lg mb-5">
            <div class="card-body">
              <div class="mb-3">    
                <h5 class="mb-3 "><strong>Forgot Your Password?</strong></h5>

                <!--ERROR MESSAGE-->
                <?php if (isset($_SESSION['error'])) : ?>
                  <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; ?>
                  </div>
                  <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!--SUCCESS MESSAGE-->
                <?php
                if (isset($_SESSION['success'])) {
                  echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                  unset($_SESSION['success']);
                }
                
                ?>

                <p class="mb-">Enter your registered <strong>Email</strong> to reset your password.</p>
              </div>
              
                <div class="form-floating mb-3">

                   <input type="email" class="form-control" name="Email" placeholder="Email" required >
                   <label for="floatingInput" class="form-label">example@gmail.com</label>
                </div> 
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary" name="forgot_password">Request Password Reset Link</button>
                </div>    
            </div>
          </div>
        </div>
      </div>
    </div>              
</form>

<script src="../Faculty/js/bootstrap.bundle.min.js"></script>

</body>
</html>
