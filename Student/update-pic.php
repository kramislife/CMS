<?php
include("../includes/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userImage'])) {
    $imageData = file_get_contents($_FILES['userImage']['tmp_name']);
    $studentNumber = $_SESSION['UserID'];

    // update database with new image
    $query = "UPDATE student_login SET UserImage=? WHERE UserID=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $imageData, $studentNumber);
    $stmt->execute();
    $stmt->close();

    // update the session variable with the new image data
    $_SESSION['userImage'] = 'data:image/jpeg;base64,' . base64_encode($imageData);
}
?>