<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistem_hrm";

$koneksi = new mysqli($host, $user, $pass, $db);

if($koneksi -> connect_error){
    die("koneksi gagal: ". $koneksi->connect_error);
}
?>