<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

$successmsg = '';
$errormsg = '';

// Define the password requirements using regular expressions
$uppercaseRegex = '/[A-Z]/';        // At least one uppercase letter
$lowercaseRegex = '/[a-z]/';        // At least one lowercase letter
$numericRegex = '/[0-9]/';           // At least one numeric digit
$specialCharRegex = '/[^A-Za-z0-9]/'; // At least one special character

// CHECK IF THE USER CLICKED THE SUBMIT BUTTON TO CHANGE PASSWORD
if (isset($_POST['change_pass'])) {
    $newPassword = $_POST['pass'];
    $confirmPassword = $_POST['cpass'];

    if ($newPassword == $confirmPassword) {
        if (
            preg_match($uppercaseRegex, $newPassword) &&
            preg_match($lowercaseRegex, $newPassword) &&
            preg_match($numericRegex, $newPassword) &&
            preg_match($specialCharRegex, $newPassword)
        ) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE faculty_login SET Password = '$hashedPassword' WHERE UserID = '" . $_SESSION['UserID'] . "'";
            mysqli_query($conn, $updateQuery);

            $_SESSION['successmsg'] = 'Password changed successfully!';
            header("Location: ../Faculty/passwordchanged");
            exit();
        } else {
            $_SESSION['errormsg'] = 'Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.';
            header("Location: ../Faculty/changepassword");
            exit();
        }
    } else {
        $_SESSION['errormsg'] = 'New and Confirm Password do not match!';
        header("Location: ../Faculty/changepassword");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../Faculty/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Faculty/css/Faculty_Portal.css">
</head>
<body>
    <div class="container flex-column" style="font-family: 'Times New Roman', Times, serif;">
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-12 col-md-8 col-lg-4">
                <div class="card shadow-lg mb-5">
                    <div class="card-body">
                        <h5 class="mb-3"><strong>Change Password</strong></h5>
                        <?php
                        if(isset($_SESSION['errormsg'])) {
                            echo '<div class="alert alert-danger">'.$_SESSION['errormsg'].'</div>';
                            unset($_SESSION['errormsg']);
                        }
                        if(isset($_SESSION['successmsg'])) {
                            echo '<div class="alert alert-success">'.$_SESSION['successmsg'].'</div>';
                            unset($_SESSION['successmsg']);
                        }
                        ?>
                        <p class="mb-3">Type and confirm a secure new <strong>Password.</strong></p>
                        <form action="" method="post">
                            <div class="form-floating mb-3"> 
                            <input type="password" class="form-control" name="pass" placeholder="Enter pass" id="newPassword" required>                      
                               <label for="floatingInput" class="form-label">Enter New Password</label>
                                
                                <medium style="color: red;" id="newPasswordRequirements" class="form-text text-muted"></medium>
                            </div>
                            <div class="form-floating mb-3"> 
                            <input type="password" class="form-control" name="cpass" placeholder="Enter cpass" id="confirmPassword" required>                             
                                <label for="floatingInput" class="form-label">Confirm New Password</label>
                                
                                <medium style="color:red;" id="confirmPasswordRequirements" class="form-text text-muted"></medium>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="change_pass">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Get the input fields and the password requirements message elements
    var newPasswordInput = document.getElementById('newPassword');
    var newPasswordRequirements = document.getElementById('newPasswordRequirements');
    var confirmPasswordInput = document.getElementById('confirmPassword');
    var confirmPasswordRequirements = document.getElementById('confirmPasswordRequirements');

    // Function to display the password requirements message for newPassword
    function showNewPasswordRequirements() {
        newPasswordRequirements.textContent = "Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
    }

    // Function to display the password requirements message for confirmPassword
    function showConfirmPasswordRequirements() {
        confirmPasswordRequirements.textContent = "Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
    }

    // Function to hide the password requirements message for newPassword
    function hideNewPasswordRequirements() {
        newPasswordRequirements.textContent = "";
    }

    // Function to hide the password requirements message for confirmPassword
    function hideConfirmPasswordRequirements() {
        confirmPasswordRequirements.textContent = "";
    }

    // Add event listeners to show/hide the password requirements message for newPassword
    newPasswordInput.addEventListener('focus', showNewPasswordRequirements);
    newPasswordInput.addEventListener('blur', hideNewPasswordRequirements);

    // Add event listeners to show/hide the password requirements message for confirmPassword
    confirmPasswordInput.addEventListener('focus', showConfirmPasswordRequirements);
    confirmPasswordInput.addEventListener('blur', hideConfirmPasswordRequirements);
</script>

</body>
</html>
