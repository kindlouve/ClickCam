<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil laporan dari prosedur sp_laporan_harian
$laporan = $conn->query("CALL sp_laporan_harian()");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_admin.php'; ?>

<div class="container mt-4">
    <h3>📊 Laporan Penyewaan Hari Ini</h3>
    <p class="text-muted">Data penyewaan yang dilakukan pada tanggal <strong><?= date('Y-m-d') ?></strong></p>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Kamera</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($laporan && $laporan->num_rows > 0): ?>
                    <?php while ($row = $laporan->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id_penyewaan'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kamera']) ?></td>
                            <td><?= $row['tanggal_sewa'] ?></td>
                            <td><?= $row['tanggal_kembali'] ?></td>
                            <td><?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">Tidak ada transaksi hari ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
