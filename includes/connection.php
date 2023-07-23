<?php

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "cms";

$conn = mysqli_connect($servername, $username, $password, $db_name);
    if($conn){
    echo('');
}
    else{
    echo('failed');
}   
?>

