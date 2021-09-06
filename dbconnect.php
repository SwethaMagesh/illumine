<?php

$servername= "localhost";
$database="illumine"; 
$username="root";
$password= "swetha_2000";

$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn)
{
    /*die("Connection error: " . mysqli_connect_errno());*/
    header('location:error.php');
}
mysqli_set_charset($conn,"utf8");
?>
