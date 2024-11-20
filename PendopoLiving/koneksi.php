<?php

$host = "localhost";
$user = "root";
$password = "kaka1234";
$db= "kostKamar";

$koneksi = mysqli_connect( $host,$user,$password,$db );

if (!$koneksi) {
   die("Koneksi Gagal".mysqli_connect_error());
 
}
?>