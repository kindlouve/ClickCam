<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Tambah Kamera
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $stmt = $conn->prepare("INSERT INTO kamera (nama_kamera, harga_per_hari, stok) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $nama, $harga, $stok);
    $stmt->execute();
    header("Location: kamera.php");
    exit;
}

// Hapus Kamera
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM kamera WHERE id = $id");
    header("Location: kamera.php");
    exit;
}

// Ambil semua kamera
$result = $conn->query("SELECT * FROM kamera ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kamera - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_admin.php'; // Optional, buat navbar agar tidak ulang2 ?>

<div class="container mt-4">
    <h3>📷 Kelola Kamera</h3>

    <!-- Form Tambah Kamera -->
    <div class="card mt-3 mb-4">
        <div class="card-header">Tambah Kamera Baru</div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Kamera" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="harga" class="form-control" placeholder="Harga / Hari" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="stok" class="form-control" placeholder="Stok" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="tambah" class="btn btn-success w-100">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Kamera -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Kamera</th>
                <th>Harga / Hari</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_kamera']) ?></td>
                <td>Rp <?= number_format($row['harga_per_hari'], 0, ',', '.') ?></td>
                <td><?= $row['stok'] ?></td>
                <td>
                    <a href="edit_kamera.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="kamera.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus kamera ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
