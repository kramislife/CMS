<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

if (isset($_POST['submit'])) {
    $Username = $_POST['user'];
    $Password = $_POST['pass'];

    $sql = "SELECT UserID, Username, Password FROM faculty_login WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 1) {
        $username = $row['Username'];
        $_SESSION['UserID'] = $row['UserID'];

        if (password_verify($Password, $row['Password']) || $Password == $row['Password']) {

            if (!password_verify($Password, $row['Password'])) {
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

                $updateSql = "UPDATE faculty_login SET Password = ? WHERE Username = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "ss", $hashedPassword, $Username);
                mysqli_stmt_execute($updateStmt);
            }

            if (strpos($username, "admin") !== false) {
                header("Location: ../admin/dashboard");
            } else {
                header("Location: ../Faculty/dashboard");
            }
            exit;
        }
    }

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
    <title>CMS | Faculty Portal</title>
    <link rel="stylesheet" href="../Student/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/faculty_portal.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
    <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
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
                        
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="badge-dismissable">
                                Incorrect Credentials!
                                <span class="close" aria-label="Close" onclick="this.parentElement.style.display='none';">&times;</span>
                            </div>
                        <?php } ?>

                        <div class="actual-form">                           
                            <div class="input-wrap-text">
                                <input type="text" name="user" class="input-field" autocomplete="off" required>
                                <label>Enter your Username</label>
                            </div>
                            <div class="input-wrap">
                                <input type="password" maxlength="20" name="pass" class="input-field" autocomplete="off" required>
                                <label>Password</label>
                            </div>
                            <p class="text text-password">
                               <a href="../Faculty/forgotpassword">Forgot Password?</a>
                            </p>
                            <input class="sign-btn btn btn-danger" id="btn" type="submit" name="submit" value="Sign-in">
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
                        <img src="../img/complaints.png" class="image img-1 show" alt="complaints">
                        <img src="../img/school.jpg" class="image img-2" alt="">
                        <img src="../img/pup.jpg" class="image img-3" alt="">
                    </div>
                </div>
            </div>
        </div>
    </main>    
    <script src="../js/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>

