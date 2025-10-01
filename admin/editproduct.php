<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
include('../config/database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM tb_products WHERE id=$id";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaBarang = $_POST['namaBarang'];
    $deskripsiBarang = $_POST['deskripsiBarang'];
    $hargaBarang = $_POST['hargaBarang'];
    $jumlahStock = $_POST['jumlahStock'];
    $gambarBarang = $product['gambarBarang'];

    if (isset($_FILES['gambarBarang']) && $_FILES['gambarBarang']['size'] > 0) {
        $gambarBarang = $_FILES['gambarBarang']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["gambarBarang"]["name"]);
        move_uploaded_file($_FILES["gambarBarang"]["tmp_name"], $target_file);
    }

    $query = "UPDATE tb_products SET namaBarang='$namaBarang', deskripsiBarang='$deskripsiBarang', hargaBarang='$hargaBarang', jumlahStock='$jumlahStock', gambarBarang='$gambarBarang' WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="namaBarang" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="namaBarang" name="namaBarang" value="<?php echo $product['namaBarang']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsiBarang" class="form-label">Product Description</label>
                <textarea class="form-control" id="deskripsiBarang" name="deskripsiBarang" rows="3" required><?php echo $product['deskripsiBarang']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="hargaBarang" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="hargaBarang" name="hargaBarang" value="<?php echo $product['hargaBarang']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="jumlahStock" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="jumlahStock" name="jumlahStock" value="<?php echo $product['jumlahStock']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambarBarang" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="gambarBarang" name="gambarBarang">
                <img src="../assets/images/<?php echo $product['gambarBarang']; ?>" width="100" height="100">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
