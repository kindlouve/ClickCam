<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="penyewa_dashboard.php">📸 ClickCam - Penyewa</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Halo, <?= htmlspecialchars($_SESSION['username'] ?? 'Penyewa') ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
