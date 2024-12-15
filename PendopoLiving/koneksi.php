<?php

$host = "localhost";
$user = "mifmyho2_sismakost";
$password = "@Mif2024";
$db= "mifmyho2_sismakost";

$koneksi = mysqli_connect( $host,$user,$password,$db );

if (!$koneksi) {
   die("Koneksi Gagal".mysqli_connect_error());
 
}
?>