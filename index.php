<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">📸 ClickCam</a>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <h1 class="display-4 fw-bold">Selamat Datang di ClickCam</h1>
        <p class="lead text-muted">Sistem penyewaan kamera online berbasis web untuk kebutuhan fotografi Anda.</p>
        
        <div class="mt-4">
            <a href="login.php?role=admin" class="btn btn-primary btn-lg me-3">Login Admin</a>
            <a href="login.php?role=penyewa" class="btn btn-outline-primary btn-lg me-3">Login Penyewa</a>
            <a href="register.php" class="btn btn-secondary btn-lg">Daftar</a>

        </div>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        &copy; <?= date('Y') ?> ClickCam. All rights reserved.
    </footer>

</body>
</html>
