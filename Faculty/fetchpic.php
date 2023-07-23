
<?php
include ("../includes/connection.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$userImage = '/img/user.png';

// fetch image data from database if available
$query = mysqli_query($conn, "SELECT UserImage FROM faculty_login WHERE Username='" . $_SESSION['login'] . "'");
while ($row = mysqli_fetch_array($query)) {
  if ($row['UserImage'] !== null) {
    $userImage = base64_encode($row['UserImage']);
  }
}

echo $userImage;
?>

