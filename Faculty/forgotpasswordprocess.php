<?php

session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');

$error ='';

// Check if the email is submitted
if(isset($_POST['forgot_password'])) {
    $email = $_POST['Email'];
} else {
    $_SESSION['error'] = 'Email not submitted';
    header("Location: ../Faculty/forgotpassword.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../mail/Exception.php';
require '../mail/PHPMailer.php';
require '../mail/SMTP.php';

    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'egariando619@gmail.com';                                    //SMTP username
        $mail->Password   = 'frhbzptagzlzchnj';                                    //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('egariando619@gmail.com', 'Admin');
        $mail->addAddress($email);                                  //Add a recipient   

        //GENERATE CODE 
        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNM'),0,10);

        //SETTING PASSWORD RESET EXPIRATION TIME
        $expiryTime = date('Y-m-d H:i:s', strtotime('+1 DAY'));
    
        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Password Reset!';
        $mail->Body  = 'It appears that you want to <strong>reset</strong> your Password, If it is not you just ignore this Message. <br><br>
        Click this <a href ="http://localhost/ComplaintManagementSystem/Faculty/changepassword?&Code='.$code.'">link</a> to reset your Password. <br><br>
        <b>NOTE:</b> Change your Password within a day.';

        // Check if email exists in the database
        $verifyQuery = $conn->query("SELECT * FROM faculty_login WHERE Email = '$email'"); 
        
    
        //UPDATE CODE AND IMPLEMENT EXPIRATION TIME
        if($verifyQuery->num_rows){
            $codeQuery = $conn->query("UPDATE faculty_login SET Code = '$code', Updated_Time = '$expiryTime' WHERE Email = '$email'");

         } else {
            $_SESSION['error'] = '<strong>Email Address</strong> does not exist!';
            header("Location: ../Faculty/forgotpassword.php?error");
            exit();
        }
    
        // Send email
        $mail->send();
    
        // Email sent successfully
        $_SESSION['success'] = 'Password <strong>reset</strong> link sent to your email.';
        header("Location: ../Faculty/forgotpassword.php");
        exit();
    
    } catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: ../Faculty/forgotpassword.php?error");
        exit();
    }
    ?>
