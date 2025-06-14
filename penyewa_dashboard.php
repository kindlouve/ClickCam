<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penyewa - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">📸 ClickCam - Penyewa</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Halo, <?= htmlspecialchars($username) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Penyewa</h3>
    <p>Selamat datang! Silakan pilih fitur di bawah ini:</p>

    <div class="row g-3">
        <div class="col-md-6">
            <a href="sewa_kamera.php" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">📸 Sewa Kamera</h5>
                        <p class="card-text">Pilih dan sewa kamera yang tersedia secara online.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="riwayat.php" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">📁 Riwayat Penyewaan</h5>
                        <p class="card-text">Lihat daftar penyewaan yang pernah Anda lakukan.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>
