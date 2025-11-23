<?php include('includes/header.php'); ?>

<!-- Main Content -->
<div class="container mt-5">

    <!-- Carousel -->
    <div id="banner-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <img src="images/poljamart.png" class="d-block w-100 banner-image" alt="Banner 1">
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="images/poljamart3.png" class="d-block w-100 banner-image" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="images/poljamart4.png" class="d-block w-100 banner-image" alt="Banner 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <br>

    <!-- Featured Products -->
    <div class="row mb-4">
        <div class="col-12">
            <h4>Produk Pilihan</h4>
        </div>
    </div>
    <div id="produk" class="row">
        <?php
        include('config/database.php');
        $query = "SELECT * FROM tb_products"; // Limit to 6 products for the feature section
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='col-6 col-md-4 col-lg-2 mb-4'>";
            echo "<div class='card'>";
            echo "<img src='images/" . $row['gambarBarang'] . "' class='card-img-top' alt='" . $row['namaBarang'] . "' style='height: 200px; object-fit: cover;'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['namaBarang'] . "</h5>";
            echo "<p class='card-text'>" . $row['deskripsiBarang'] . "</p>";
            echo "<p class='card-text'><strong>Rp " . number_format($row['hargaBarang'], 0, ',', '.') . "</strong></p>";
            echo "<a href='product.php?id=" . $row['id'] . "' class='btn btn-primary'>Beli</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    $(document).ready(function() {
        $('.banner-carousel').slick({
            dots: true,
            infinite: true,
            speed: 100,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000
        });
    });
</script>

<?php include('includes/footer.php'); ?>b