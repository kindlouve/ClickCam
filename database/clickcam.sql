-- ClickCam Complete SQL Schema with Triggers & Password Hash Support
DROP DATABASE IF EXISTS clickcam;
CREATE DATABASE clickcam;
USE clickcam;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'penyewa') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- KAMERA TABLE
CREATE TABLE kamera (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kamera VARCHAR(100) NOT NULL,
    harga_per_hari DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PENYEWAAN TABLE
CREATE TABLE penyewaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_kamera INT,
    tanggal_sewa DATE,
    tanggal_kembali DATE,
    total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_kamera) REFERENCES kamera(id)
);

-- LOG PENYEWAAN TABLE
CREATE TABLE log_penyewaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_penyewaan INT,
    status ENUM('dipesan', 'dibatalkan', 'selesai') NOT NULL,
    waktu_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penyewaan) REFERENCES penyewaan(id)
);

-- FUNCTION: Hitung Total Sewa
DELIMITER $$
CREATE FUNCTION fn_hitung_total(tgl_sewa DATE, tgl_kembali DATE, harga DECIMAL(10,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE durasi INT;
    SET durasi = DATEDIFF(tgl_kembali, tgl_sewa);
    RETURN durasi * harga;
END$$
DELIMITER ;

-- PROCEDURE: Laporan Harian
DELIMITER $$
CREATE PROCEDURE sp_laporan_harian()
BEGIN
    SELECT 
        p.id AS id_penyewaan,
        u.username,
        k.nama_kamera,
        p.tanggal_sewa,
        p.tanggal_kembali,
        p.total
    FROM penyewaan p
    JOIN users u ON p.id_user = u.id
    JOIN kamera k ON p.id_kamera = k.id
    WHERE p.tanggal_sewa = CURDATE();
END$$
DELIMITER ;

-- TRIGGER: Kurangi Stok Saat Sewa
DELIMITER $$
CREATE TRIGGER trg_kurangi_stok
AFTER INSERT ON penyewaan
FOR EACH ROW
BEGIN
    UPDATE kamera SET stok = stok - 1 WHERE id = NEW.id_kamera;
END$$
DELIMITER ;

-- TRIGGER: Tambah Stok Saat Dibatalkan / Selesai
DELIMITER $$
CREATE TRIGGER trg_tambah_stok_setelah_selesai
AFTER INSERT ON log_penyewaan
FOR EACH ROW
BEGIN
    DECLARE kamera_id INT;

    IF NEW.status IN ('dibatalkan', 'selesai') THEN
        SELECT id_kamera INTO kamera_id
        FROM penyewaan
        WHERE id = NEW.id_penyewaan;

        UPDATE kamera
        SET stok = stok + 1
        WHERE id = kamera_id;
    END IF;
END$$
DELIMITER ;
