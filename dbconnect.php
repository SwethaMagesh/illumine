<?php

$servername= "illumine.mysql.database.azure.com";
$database="illumine"; 
$username="illumine_root@illumine";
$password= "I_swetha2000";

$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn)
{
    /*die("Connection error: " . mysqli_connect_errno());*/
    header('location:error.php');
}
mysqli_set_charset($conn,"utf8");
?>
