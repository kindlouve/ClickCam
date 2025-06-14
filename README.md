# 📸 ClickCam
ClickCam adalah sistem web sederhana berbasis PHP dan MySQL untuk menyewakan kamera secara online. Aplikasi ini dibangun dengan fitur login multi-user (admin & penyewa) serta mengimplementasikan SQL procedure, function, trigger, dan transaction, serta mendukung backup database.
![img](https://github.com/user-attachments/assets/24d62626-d5e5-49fe-a534-747b1955e485)

# 📌 Detail Konsep
# ⚠ Disclaimer
Peran stored procedure, trigger, transaction, dan stored function dalam proyek ini dirancang khusus untuk kebutuhan sistem ClickCam. Penerapannya bisa berbeda pada sistem lain, tergantung arsitektur dan kebutuhan masing-masing sistem.

# 🧠 Stored Procedure
Stored Procedure (prosedur tersimpan) adalah sekumpulan perintah SQL yang disimpan di dalam database dan dapat dijalankan secara berulang untuk melakukan proses tertentu. Stored Procedure mirip seperti SOP (Standard Operating Procedure) dalam sistem, memastikan proses-proses penting dieksekusi dengan cara yang konsisten, efisien, dan aman.
Stored Procedure **sp_laporan_harian** dalam sistem ClickCam berfungsi sebagai alat bantu administrasi untuk membuat laporan keuangan atau transaksi secara rutin. Ini membantu memastikan bahwa laporan selalu konsisten, cepat diproses, dan minim kesalahan manusia.
![procedure](https://github.com/user-attachments/assets/3cc78b3d-7345-4b6a-8874-5d48ca17f3be)

# 🚨 Trigger
Trigger ini berfungsi sebagai sistem pengaman otomatis yang menjaga integritas data dan membantu otomatisasi proses bisnis langsung di level database tanpa harus menulis logika tambahan di sisi aplikasi PHP.
![triggers](https://github.com/user-attachments/assets/c58bc683-8a15-407f-89eb-a83c1e44b695)
- Trigger **trg_kurangi_stok** berfungsi mengurangi jumlah stok kamera secara otomatis setelah data penyewaan baru dimasukkan ke tabel penyewaan. Ketika pengguna menyewa kamera (melakukan transaksi penyewaan), data penyewaan ditambahkan (INSERT) ke tabel penyewaan. Setelah itu, trigger ini otomatis dijalankan untuk mengurangi stok kamera yang disewa dari tabel kamera, sesuai jumlah unit yang disewa.
- Trigger **trg_tambah_stok_setelah_selesai** berfungsi untuk menambahkan kembali stok kamera secara otomatis setelah penyewaan selesai dan dicatat di tabel log_penyewaan. Ketika kamera sudah dikembalikan oleh penyewa, maka sistem akan mencatat pengembalian tersebut ke dalam tabel log_penyewaan. Setelah itu, trigger ini otomatis aktif untuk menambahkan kembali stok kamera yang sebelumnya disewa.

# 🔄 Transaction (Transaksi)
Dalam sistem ClickCam, transaksi digunakan saat proses penyewaan kamera. Transaksi ini memastikan bahwa seluruh langkah seperti pengurangan stok kamera, perhitungan total sewa, dan pencatatan log penyewaan—berhasil dijalankan sepenuhnya, atau dibatalkan seluruhnya jika terjadi kesalahan di salah satu langkah.
Ini penting agar tidak terjadi perubahan data yang parsial. Misalnya: stok kamera sudah berkurang, tapi penyewaan gagal dicatat—hal ini berpotensi merusak konsistensi sistem.
Transaksi di ClickCam diwujudkan menggunakan:
- beginTransaction() — untuk memulai transaksi
- commit() — menyimpan perubahan jika semua langkah berhasil
- rollback() — membatalkan seluruh proses jika terjadi error

Contohnya pada saat penyewa menyewa kamera, langkah-langkah berikut dibungkus dalam satu transaksi:
- Hitung total biaya sewa (via function fn_hitung_total)
- Masukkan data ke tabel penyewaan
- Kurangi stok kamera (via trigger)
- Tambahkan log penyewaan ke tabel log_penyewaan
Jika salah satu langkah gagal, maka seluruh proses dibatalkan agar database tetap konsisten.

# 📺 Stored Function
Stored Function adalah fungsi di database yang digunakan untuk mengambil atau menghitung informasi, tanpa mengubah data. Sifatnya seperti read-only (hanya menampilkan, tidak menulis/ubah).
![function](https://github.com/user-attachments/assets/61d39efb-c63e-44eb-86c5-8b4a0c8075f8)
Misalnya dalam ClickCam, terdapat function **fn_hitung_total** yang mengembalikan total biaya penyewaan dari seorang penyewa. Fungsi ini digunakan oleh aplikasi untuk menampilkan data seperti total pembayaran, tanpa perlu menghitung ulang di PHP. Contohnya: $total = $transactionModel->getTotal($userId);
Keuntungan utamanya yaitu logika perhitungan disimpan di database, jadi lebih rapi, konsisten, dan mudah dipanggil dari aplikasi maupun prosedur lain.

# 🔄 Backup Otomatis
Untuk menjaga ketersediaan dan keamanan data, sistem ClickCam dilengkapi dengan fitur backup otomatis database menggunakan perintah mysqldump. Backup dilakukan secara berkala, dan hasil file disimpan dengan nama yang menyertakan timestamp, sehingga mudah dilacak. Setiap file backup akan disimpan dalam folder storage/backups.
📄 backup.php
<?php
require_once __DIR__ . '/init.php'; // opsional, jika ada konfigurasi

$date = date('Y-m-d_H-i-s');
$backupFile = __DIR__ . "/storage/backups/clickcam_backup_{$date}.sql";
$command = "\"C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe\" -u root clickcam > \"$backupFile\"";

exec($command);

# 🧩 Relevansi Proyek dengan Pemrosesan Data Terdistribusi
