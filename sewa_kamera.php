<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['id'];
$message = "";

// Proses Penyewaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kamera = $_POST['id_kamera'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    // Validasi tanggal
    if (strtotime($tanggal_kembali) <= strtotime($tanggal_sewa)) {
        $message = "<div class='alert alert-danger'>Tanggal kembali harus setelah tanggal sewa.</div>";
    } else {
        // Ambil harga kamera
        $stmt = $conn->prepare("SELECT harga_per_hari FROM kamera WHERE id = ?");
        $stmt->bind_param("i", $id_kamera);
        $stmt->execute();
        $result = $stmt->get_result();
        $kamera = $result->fetch_assoc();
        $harga_per_hari = $kamera['harga_per_hari'];

        // Hitung total via function
        $res = $conn->query("SELECT fn_hitung_total('$tanggal_sewa', '$tanggal_kembali', $harga_per_hari) AS total");
        $total = $res->fetch_assoc()['total'];

        // Transaction: insert ke penyewaan
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO penyewaan (id_user, id_kamera, tanggal_sewa, tanggal_kembali, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iissd", $id_user, $id_kamera, $tanggal_sewa, $tanggal_kembali, $total);
            $stmt->execute();

            $penyewaan_id = $conn->insert_id;

            // Log status awal
            $stmt = $conn->prepare("INSERT INTO log_penyewaan (id_penyewaan, status) VALUES (?, 'dipesan')");
            $stmt->bind_param("i", $penyewaan_id);
            $stmt->execute();

            $conn->commit();
            $message = "<div class='alert alert-success'>Penyewaan berhasil!</div>";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "<div class='alert alert-danger'>Terjadi kesalahan saat menyewa.</div>";
        }
    }
}

// Ambil kamera tersedia
$kamera = $conn->query("SELECT * FROM kamera WHERE stok > 0 ORDER BY nama_kamera");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sewa Kamera - ClickCam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar_penyewa.php'; ?>

<div class="container mt-4">
    <h3>📸 Form Penyewaan Kamera</h3>

    <?= $message ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="id_kamera" class="form-label">Pilih Kamera</label>
            <select name="id_kamera" class="form-select" required>
                <option value="">-- Pilih Kamera --</option>
                <?php while ($row = $kamera->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['nama_kamera'] ?> (Rp <?= number_format($row['harga_per_hari'], 0, ',', '.') ?>/hari - Stok: <?= $row['stok'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_sewa" class="form-label">Tanggal Sewa</label>
            <input type="date" name="tanggal_sewa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Sewa Sekarang</button>
    </form>
</div>
</body>
</html>
