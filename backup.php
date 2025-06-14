<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$backupSuccess = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = 'localhost';
    $dbUser = 'root';      // Ganti jika bukan root
    $dbPass = '';          // Ganti jika ada password
    $dbName = 'clickcam';

    $backupFile = "backup_clickcam_" . date("Ymd_His") . ".sql";
    $backupPath = __DIR__ . "/backup/$backupFile";

    if (!is_dir(__DIR__ . "/backup")) {
        mkdir(__DIR__ . "/backup", 0777, true);
    }

    $command = "mysqldump -u$dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName > \"$backupPath\"";

    if (system($command, $result) !== false && $result === 0) {
        $backupSuccess = true;
    } else {
        $errorMsg = "Gagal membuat backup. Pastikan mysqldump tersedia dan memiliki izin akses.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backup Database - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_admin.php'; ?>

<div class="container mt-4">
    <h3>💾 Backup Database</h3>
    <p>Tekan tombol di bawah untuk membackup seluruh isi database ClickCam.</p>

    <?php if ($backupSuccess): ?>
        <div class="alert alert-success">
            Backup berhasil dibuat. 
            <a href="backup/<?= $backupFile ?>" class="btn btn-sm btn-success ms-2">Unduh Backup</a>
        </div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><?= $errorMsg ?></div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" class="btn btn-primary">Backup Sekarang</button>
    </form>
</div>

</body>
</html>
