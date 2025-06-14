<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">📸 ClickCam - Admin</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Halo, <?= htmlspecialchars($username) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Admin</h3>
    <p>Silakan pilih fitur di bawah ini:</p>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="kamera.php" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">📷 Kelola Kamera</h5>
                        <p class="card-text">Tambah, edit, dan hapus data kamera yang tersedia.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="laporan.php" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">📊 Laporan Harian</h5>
                        <p class="card-text">Lihat transaksi penyewaan hari ini (via procedure).</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="backup.php" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">💾 Backup Database</h5>
                        <p class="card-text">Backup database sistem secara manual.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>
