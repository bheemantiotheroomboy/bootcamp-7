<?php
$host="localhost";
$username="root";   
$password="";
$database="db_sesi8";

$koneksi=mysqli_connect($host,$username,$password,$database);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

echo "Koneksi Berhasil";
?>