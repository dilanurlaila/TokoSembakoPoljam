<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
include('../config/database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tb_products WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
