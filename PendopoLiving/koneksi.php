<?php

$host = "localhost";
$user = "root";
<<<<<<< HEAD
$password = "";
=======
$password = "kaka1234";
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
$db= "pendopo_living";

$koneksi = mysqli_connect( $host,$user,$password,$db );

if (!$koneksi) {
   die("Koneksi Gagal".mysqli_connect_error());
 
}
?>