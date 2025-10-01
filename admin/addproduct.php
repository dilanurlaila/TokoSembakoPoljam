<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaBarang = $_POST['namaBarang'];
    $deskripsiBarang = $_POST['deskripsiBarang'];
    $hargaBarang = $_POST['hargaBarang'];
    $jumlahStock = $_POST['jumlahStock'];
    
    // Upload file gambar
    $gambarBarang = $_FILES['gambarBarang']['name'];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($gambarBarang);
    
    if (move_uploaded_file($_FILES['gambarBarang']['tmp_name'], $target_file)) {
        $query = "INSERT INTO tb_products (namaBarang, deskripsiBarang, hargaBarang, jumlahStock, gambarBarang) 
                  VALUES ('$namaBarang', '$deskripsiBarang', '$hargaBarang', '$jumlahStock', '$gambarBarang')";
        
        if ($conn->query($query) === TRUE) {
            echo "<script>
                    alert('Produk berhasil ditambahkan!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Gagal meng-upload gambar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Toko Sembako Poljam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Tambah Produk</h1>
        <form method="POST" enctype="multipart/form-data" action="">
            <div class="mb-3">
                <label for="namaBarang" class="form-label">Nama Barang</label>
                <input type="text" name="namaBarang" id="namaBarang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deskripsiBarang" class="form-label">Deskripsi Barang</label>
                <textarea name="deskripsiBarang" id="deskripsiBarang" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="hargaBarang" class="form-label">Harga Barang</label>
                <input type="number" name="hargaBarang" id="hargaBarang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jumlahStock" class="form-label">Jumlah Stock</label>
                <input type="number" name="jumlahStock" id="jumlahStock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="gambarBarang" class="form-label">Gambar Barang</label>
                <input type="file" name="gambarBarang" id="gambarBarang" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Produk</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
