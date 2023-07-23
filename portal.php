<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

if (isset($_POST['submit'])) {
    $studentNumber = $_POST['studnumber'];
    $password = $_POST['pass'];

    $sql = "SELECT UserID, Password, StudentNumber FROM student_login WHERE StudentNumber = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['UserID'] = $row['UserID'];

        if (password_verify($password, $row['Password'])) {
            // Redirect to the regular user dashboard
            header("Location: ../Student/Dashboard");
            exit;
        } else if ($password == $row['Password']) {
            // Redirect to the regular user dashboard
            header("Location: ../Student/Dashboard");
            exit;
        }
    }

    // If no match found or password is incorrect, redirect to the login page with an error message
    header("Location: ../Student/Portal?error");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>
    <link rel="stylesheet" href="style.css" />
	<link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
    <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
  </head>
  <body>
  <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <form action="" autocomplete="off" class="sign-in-form">
                        <div class="logo">
                            <img src="./img/CMS.png" alt="easyclass" />
                            <h2>e:Reklamo</h2>
                        </div>

                     <!--   <div class="heading">
                            <h2>Student Portal</h2>
                            <h6></h6>
                        <a href="#" class="toggle">Go to Faculty's Portal</a> 
                        </div> -->

                        <div class="actual-form">
                            <div class="input-wrap-text">
                                <input type="text" maxlength="15"  name="studnumber" class="input-field" autocomplete="off" required />
                                <label> Enter your Student Number</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" maxlength="20" name="pass" class="input-field" autocomplete="off" required />
                                <label>Password</label>
                            </div>
                            <p class="text-password">
                                Forgot <a href="#">Password?</a>
                            </p>
							
                            <input type="submit" value="Sign In" name="submit" class="sign-btn"/>
							<p class="text">
                                By signing up, I agree to the PUP Online Services
                                <a href="https://www.pup.edu.ph/terms/">Terms of Services</a> and
                                <a href="https://www.pup.edu.ph/privacy/">Privacy Statement</a>
                            </p>

                        </div>
                    </form>

                    <form action="index.html" autocomplete="off" class="sign-up-form">
                        <div class="logo">
						<img src="./img/CMS.png" alt="easyclass" />
                            <h2>e:Reklamo</h2>
                        </div>

						<div class="heading">
                            <h2>Student Portal</h2>
                            <h6></h6>
                        <a href="#" class="toggle">Go to Faculty's Portal</a> 
                        </div>                   

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" maxlength="15" class="input-field" autocomplete="off" required />
                                <label>Enter your Student Number</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" maxlength="20" class="input-field" autocomplete="off" required />
                                <label>Password</label>
                            </div>

                            <input type="submit" value="Sign Up" class="sign-btn"/>

                            <p class="text">
                                By signing up, I agree to the PUP Online Services
                                <a href="https://www.pup.edu.ph/terms/">Terms of Services</a> and
                                <a href="https://www.pup.edu.ph/privacy/">Privacy Statement</a>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="carousel">
                    <div class="images-wrapper">
                        <img src=".//img/complaints.png" class="image img-1 show" alt="" />
                        <img src=".//img/school.jpg" class="image img-2" alt="" />
                        <img src=".//img/cat.jpg" class="image img-3" alt="" />
                    </div>

                   <div class="text-slider">
                      <!--  <div class="text-wrap">
                            <div class="text-group">
                                <h2>Create your own courses</h2>
                                <h2>Customize as you like</h2>
                                <h2>Invite students to your class</h2>
                            </div> 
                        </div> 

                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascript file -->
    <script src="app.js"></script>
</body>
</html>



