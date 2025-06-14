<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['id'];

$query = "
    SELECT 
        p.id,
        k.nama_kamera,
        p.tanggal_sewa,
        p.tanggal_kembali,
        p.total,
        (
            SELECT status 
            FROM log_penyewaan 
            WHERE id_penyewaan = p.id 
            ORDER BY waktu_log DESC LIMIT 1
        ) AS status_terakhir
    FROM penyewaan p
    JOIN kamera k ON p.id_kamera = k.id
    WHERE p.id_user = $id_user
    ORDER BY p.tanggal_sewa DESC
";

$riwayat = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penyewaan - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_penyewa.php'; ?>

<div class="container mt-4">
    <h3>📁 Riwayat Penyewaan Anda</h3>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kamera</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total (Rp)</th>
                    <th>Status & Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($riwayat->num_rows > 0): $no = 1; ?>
                    <?php while ($row = $riwayat->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_kamera']) ?></td>
                            <td><?= $row['tanggal_sewa'] ?></td>
                            <td><?= $row['tanggal_kembali'] ?></td>
                            <td><?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $row['status_terakhir'] === 'selesai' ? 'success' : 
                                    ($row['status_terakhir'] === 'dibatalkan' ? 'danger' : 'warning'
                                ) ?>">
                                    <?= ucfirst($row['status_terakhir']) ?>
                                </span>
                                
                                <?php if ($row['status_terakhir'] === 'dipesan'): ?>
                                    <div class="mt-2 d-flex flex-column gap-1">
                                        <a href="ubah_status.php?id=<?= $row['id'] ?>&aksi=batal" 
                                           onclick="return confirm('Batalkan penyewaan ini?')" 
                                           class="btn btn-sm btn-danger">Batalkan</a>
                                        <a href="ubah_status.php?id=<?= $row['id'] ?>&aksi=selesai" 
                                           onclick="return confirm('Sudah selesai sewa?')" 
                                           class="btn btn-sm btn-success">Selesai</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">Belum ada penyewaan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
