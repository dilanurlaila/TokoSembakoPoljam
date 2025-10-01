<?php
$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password MySQL Anda jika ada
$dbname = "db_poljam";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
