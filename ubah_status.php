<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['id'];
$id_penyewaan = $_GET['id'] ?? null;
$aksi = $_GET['aksi'] ?? null;

if (!$id_penyewaan || !in_array($aksi, ['batal', 'selesai'])) {
    header("Location: riwayat.php");
    exit;
}

// Cek kepemilikan penyewaan
$stmt = $conn->prepare("SELECT id FROM penyewaan WHERE id = ? AND id_user = ?");
$stmt->bind_param("ii", $id_penyewaan, $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: riwayat.php");
    exit;
}

$status_baru = $aksi === 'batal' ? 'dibatalkan' : 'selesai';

// Tambahkan status ke log
$stmt = $conn->prepare("INSERT INTO log_penyewaan (id_penyewaan, status) VALUES (?, ?)");
$stmt->bind_param("is", $id_penyewaan, $status_baru);
$stmt->execute();

header("Location: riwayat.php");
exit;
