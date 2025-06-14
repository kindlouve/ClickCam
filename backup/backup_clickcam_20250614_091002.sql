-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: clickcam
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `kamera`
--

DROP TABLE IF EXISTS `kamera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kamera` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kamera` varchar(100) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kamera`
--

LOCK TABLES `kamera` WRITE;
/*!40000 ALTER TABLE `kamera` DISABLE KEYS */;
INSERT INTO `kamera` VALUES (1,'Panasonic Lumix G7',160000.00,3,'2025-06-14 09:06:20'),(2,'Canon EOS M50 Mark II',175000.00,2,'2025-06-14 09:06:52'),(3,'Sony Alpha a6400',220000.00,1,'2025-06-14 09:07:13'),(4,'Fujifilm X-T30',200000.00,3,'2025-06-14 09:07:38'),(5,'Nikon Z50',190000.00,4,'2025-06-14 09:08:12');
/*!40000 ALTER TABLE `kamera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_penyewaan`
--

DROP TABLE IF EXISTS `log_penyewaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_penyewaan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penyewaan` int DEFAULT NULL,
  `status` enum('dipesan','dibatalkan','selesai') NOT NULL,
  `waktu_log` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_penyewaan` (`id_penyewaan`),
  CONSTRAINT `log_penyewaan_ibfk_1` FOREIGN KEY (`id_penyewaan`) REFERENCES `penyewaan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_penyewaan`
--

LOCK TABLES `log_penyewaan` WRITE;
/*!40000 ALTER TABLE `log_penyewaan` DISABLE KEYS */;
INSERT INTO `log_penyewaan` VALUES (1,1,'dipesan','2025-06-14 09:08:55'),(2,2,'dipesan','2025-06-14 09:09:15');
/*!40000 ALTER TABLE `log_penyewaan` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_tambah_stok_setelah_selesai` AFTER INSERT ON `log_penyewaan` FOR EACH ROW BEGIN
    DECLARE kamera_id INT;

    IF NEW.status IN ('dibatalkan', 'selesai') THEN
        SELECT id_kamera INTO kamera_id
        FROM penyewaan
        WHERE id = NEW.id_penyewaan;

        UPDATE kamera
        SET stok = stok + 1
        WHERE id = kamera_id;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `penyewaan`
--

DROP TABLE IF EXISTS `penyewaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penyewaan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_kamera` int DEFAULT NULL,
  `tanggal_sewa` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_kamera` (`id_kamera`),
  CONSTRAINT `penyewaan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `penyewaan_ibfk_2` FOREIGN KEY (`id_kamera`) REFERENCES `kamera` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penyewaan`
--

LOCK TABLES `penyewaan` WRITE;
/*!40000 ALTER TABLE `penyewaan` DISABLE KEYS */;
INSERT INTO `penyewaan` VALUES (1,2,1,'2025-06-14','2025-06-15',160000.00,'2025-06-14 09:08:55'),(2,2,5,'2025-06-16','2025-06-17',190000.00,'2025-06-14 09:09:15');
/*!40000 ALTER TABLE `penyewaan` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_kurangi_stok` AFTER INSERT ON `penyewaan` FOR EACH ROW BEGIN
    UPDATE kamera SET stok = stok - 1 WHERE id = NEW.id_kamera;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','penyewa') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ara','$2y$10$lVfVdqP4uIjfd1sap31jFul96LOV.5F/JVEwjGC0GlgO83qx07uSq','admin','2025-06-14 09:05:25'),(2,'zahra','$2y$10$BSkoQW3VWznmTOHEKQsdL.tV3JAixgYotYd/Zl26xiHxZPQIVH7xe','penyewa','2025-06-14 09:05:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-14 16:10:02
