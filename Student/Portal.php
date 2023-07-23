<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

$successmsg = '';
$errormsg = '';

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
 
            header("Location: ../Student/Dashboard");
            exit;
        } else if ($password == $row['Password']) {

            header("Location: ../Student/dashboard");
            exit;
        }
        
    header("Location: ../Student/Portal?error");
    exit;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Student Portal</title>
    <link rel="stylesheet" href="../Student/css/Student_Portal.css">
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
                    <form action="" name="form" method="POST" autocomplete="off" class="sign-in-form">
                        <div class="logo">
                            <img src="../img/CMS.png" alt="CMS">
                            <h1>e:Reklamo</h1>
                        </div>
                        <div class="actual-form">
                            <div class="input-wrap-text">
                                <input type="text" maxlength="15" name="studnumber" class="input-field" autocomplete="off" required>
                                <label>Enter your Student Number</label>
                            </div>
                            <div class="input-wrap">
                                <input type="password" maxlength="20" name="pass" class="input-field" autocomplete="off" required>
                                <label>Password</label>
                            </div>
                            <p class="text text-password">
                               <a href="../Student/forgotpassword.php">Forgot Password?</a>
                            </p>
                            <input class="sign-btn btn btn-primary" id="btn" type="submit" name="submit" value="Sign-in">
                            <p class="text">
                                By signing up, I agree to the PUP Online Services
                                <a href="https://www.pup.edu.ph/terms/" target="_blank">Terms of Services</a> and
                                <a href="https://www.pup.edu.ph/privacy/" target="_blank">Privacy Statement</a>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="carousel">
                    <div class="images-wrapper">
                        <img src=".//img/complaints.png" class="image img-1 show" alt="">
                        <img src=".//img/school.jpg" class="image img-2" alt="">
                        <img src=".//img/cat.jpg" class="image img-3" alt="">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/app.js"></script>
</body>
</html>









