<?php include('includes/header.php'); ?>

<div class="container mt-5 ">

    <div class="d-flex flex-column min-vh-100">

        <div class="container mt-5 flex-grow-1">
            <?php
            if (isset($_GET['id'])) {
                include('config/database.php');
                $id = intval($_GET['id']);
                $query = "SELECT * FROM tb_products WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
            ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-image-container">
                                <img src="images/<?php echo htmlspecialchars($row['gambarBarang']); ?>" class="img-fluid product-image" alt="<?php echo htmlspecialchars($row['namaBarang']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h1 class="product-title"><?php echo htmlspecialchars($row['namaBarang']); ?></h1>
                            <p class="product-description"><?php echo htmlspecialchars($row['deskripsiBarang']); ?></p>
                            <p class="product-price"><strong>Rp <?php echo number_format($row['hargaBarang'], 0, ',', '.'); ?></strong></p>
                            <p class="product-stock">Stok: <?php echo htmlspecialchars($row['jumlahStock']); ?></p>

                            <div class="d-flex align-items-center">
                                <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" name="quantity">
                                <button class="btn btn-outline-dark flex-shrink-0" type="submit" style="padding: 5px 10px; font-size: 14px;">
                                    <i class="bi-cart-fill me-1"></i>
                                    Add to cart
                                </button>
                            </div>

                        </div>

                    </div>

            <?php
                } else {
                    echo "<p>Produk tidak ditemukan.</p>";
                }
            } else {
                echo "<p>Produk tidak ditemukan.</p>";
            }
            ?>
        </div>

        </div>
    


        <?php include('includes/footer.php'); ?>
    
