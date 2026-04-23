<?php
$conn = mysqli_connect("localhost","root","","jarskuy");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
