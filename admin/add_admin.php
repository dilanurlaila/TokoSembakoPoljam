<?php
session_start();
include('../config/database.php'); // Sesuaikan path bila perlu

// Pastikan hanya admin yang sudah login yang bisa mengakses halaman ini
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Buat/refresh CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Permintaan tidak valid (CSRF token salah).';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '') {
            $errors[] = 'Username harus diisi.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = 'Username harus antara 3-50 karakter.';
        }

        if ($password === '') {
            $errors[] = 'Password harus diisi.';
        } elseif ($password !== $password_confirm) {
            $errors[] = 'Password dan konfirmasi password tidak cocok.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        } else {
            if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $errors[] = 'Password harus mengandung huruf besar, huruf kecil, dan angka.';
            }
        }

        if (empty($errors)) {
            $stmt = $conn->prepare('SELECT id FROM tb_users WHERE namaUser = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $errors[] = 'Username sudah digunakan. Silakan pilih username lain.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Terjadi error (prepare): ' . $conn->error;
            }
        }

        if (empty($errors)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('INSERT INTO tb_users (namaUser, password) VALUES (?, ?)');
            if ($stmt) {
                $stmt->bind_param('ss', $username, $password_hash);
                if ($stmt->execute()) {
                    unset($_SESSION['csrf_token']);
                    header("Location: login.php?status=admin_created");
                    exit();
                } else {
                    $errors[] = 'Gagal menyimpan akun: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $errors[] = 'Terjadi error (prepare insert): ' . $conn->error;
            }
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Admin - Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Tambah Akun Admin</h4>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $e): ?>
                                        <li><?php echo htmlspecialchars($e); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required maxlength="50" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <div class="form-text">Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Buat Akun</button>
                        </form>

                        <hr>
                        <p class="small text-muted">Catatan keamanan: setelah selesai menambah akun, pertimbangkan untuk menghapus atau memproteksi file ini agar tidak bisa diakses publik. Pastikan situs berjalan lewat HTTPS.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>