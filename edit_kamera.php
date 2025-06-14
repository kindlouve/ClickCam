<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM kamera WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Kamera tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $update = $conn->prepare("UPDATE kamera SET nama_kamera = ?, harga_per_hari = ?, stok = ? WHERE id = ?");
    $update->bind_param("sdii", $nama, $harga, $stok, $id);
    $update->execute();

    header("Location: kamera.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kamera - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_admin.php'; ?>

<div class="container mt-5">
    <h3>Edit Kamera</h3>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kamera</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama_kamera']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga per Hari</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga_per_hari'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kamera.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
