-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: khachhang_db
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Create & select database
--
CREATE DATABASE IF NOT EXISTS `khachhang_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `khachhang_db`;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT 'ID của Khách hàng, liên kết tới bảng users',
  `room_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên/Loại phòng Khách sạn',
  `check_in` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Tổng chi phí đợt lưu trú',
  `status` enum('confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed' COMMENT 'confirmed: Sắp ở, completed: Đã xong, cancelled: Đã hủy',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_bookings_user` (`user_id`),
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử đặt phòng của Khách hàng Khách sạn';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,3,'Phòng Superior Hướng Phố - P.201','2026-06-01','2026-06-03',3500000.00,'completed','2026-05-25 02:30:00'),(2,3,'Phòng Deluxe Gia Đình - P.305','2026-07-15','2026-07-18',6200000.00,'confirmed','2026-07-10 07:12:00'),(3,4,'Phòng Deluxe Hướng Biển - P.402','2026-05-10','2026-05-13',5400000.00,'completed','2026-05-02 04:00:00'),(4,6,'Phòng Suite Sân Vườn - P.102','2026-04-12','2026-04-15',8900000.00,'completed','2026-04-01 01:45:00'),(5,6,'Phòng Executive Bể Bơi - P.301','2026-06-20','2026-06-25',14500000.00,'completed','2026-06-10 09:20:00'),(6,6,'Căn Hộ Penthouse Tầng Thượng - P.901','2026-08-01','2026-08-04',21000000.00,'confirmed','2026-07-05 03:15:00'),(7,9,'Phòng Standard Hướng Phố - P.105','2026-06-12','2026-06-14',2800000.00,'completed','2026-06-05 06:00:00'),(8,9,'Phòng Deluxe Sân Vườn - P.202','2026-07-01','2026-07-03',4600000.00,'cancelled','2026-06-25 02:10:00'),(9,12,'Căn Hộ Penthouse Tầng Thượng - P.902','2026-03-10','2026-03-15',35000000.00,'completed','2026-03-01 03:00:00'),(10,12,'Phòng Suite Tổng Thống - P.801','2026-05-01','2026-05-05',42000000.00,'completed','2026-04-20 08:30:00'),(11,12,'Phòng Suite Hướng Biển - P.505','2026-07-01','2026-07-04',12800000.00,'completed','2026-06-18 04:25:00'),(12,12,'Phòng Executive Bể Bơi - P.303','2026-08-15','2026-08-18',15600000.00,'confirmed','2026-07-08 01:00:00'),(13,15,'Phòng Superior Hướng Biển - P.404','2026-06-05','2026-06-07',3900000.00,'completed','2026-05-28 07:00:00'),(14,18,'Phòng Deluxe Hướng Biển - P.405','2026-04-18','2026-04-21',6900000.00,'completed','2026-04-10 02:15:00'),(15,18,'Phòng Suite Sân Vườn - P.104','2026-06-15','2026-06-18',9200000.00,'completed','2026-06-01 03:50:00'),(16,18,'Căn Hộ Penthouse Tầng Thượng - P.901','2026-07-10','2026-07-12',15000000.00,'completed','2026-07-02 09:40:00'),(17,19,'Phòng Executive Bể Bơi - P.305','2026-05-14','2026-05-17',11200000.00,'completed','2026-05-05 04:20:00'),(18,19,'Phòng Suite Sân Vườn - P.101','2026-07-20','2026-07-22',7800000.00,'confirmed','2026-07-11 03:00:00'),(19,22,'Phòng Deluxe Hướng Biển - P.501','2026-06-01','2026-06-05',8400000.00,'completed','2026-05-20 06:40:00'),(20,25,'Phòng Suite Hướng Biển - P.602','2026-04-05','2026-04-09',14200000.00,'completed','2026-03-25 08:10:00'),(21,25,'Căn Hộ Penthouse Tầng Thượng - P.902','2026-06-22','2026-06-25',18500000.00,'completed','2026-06-12 02:50:00'),(22,28,'Phòng Executive Bể Bơi - P.302','2026-05-18','2026-05-20',7500000.00,'completed','2026-05-10 05:00:00'),(23,28,'Phòng Deluxe Hướng Phố - P.304','2026-07-02','2026-07-04',4500000.00,'cancelled','2026-06-20 07:15:00'),(24,30,'Phòng Suite Tổng Thống - P.802','2026-05-02','2026-05-04',28000000.00,'completed','2026-04-22 09:00:00'),(25,34,'Phòng Suite Sân Vườn - P.105','2026-06-01','2026-06-04',10500000.00,'completed','2026-05-21 01:30:00'),(26,37,'Phòng Deluxe Gia Đình - P.401','2026-06-10','2026-06-12',5200000.00,'completed','2026-06-02 04:45:00'),(27,39,'Căn Hộ Penthouse Tầng Thượng - P.901','2026-04-10','2026-04-14',24000000.00,'completed','2026-04-01 03:20:00'),(28,39,'Phòng Suite Hướng Biển - P.601','2026-06-18','2026-06-22',16000000.00,'completed','2026-06-10 07:00:00'),(29,42,'Phòng Executive Bể Bơi - P.402','2026-05-25','2026-05-28',9800000.00,'completed','2026-05-15 02:30:00'),(30,44,'Phòng Suite Sân Vườn - P.102','2026-06-08','2026-06-11',11500000.00,'completed','2026-05-30 08:50:00'),(31,46,'Phòng Deluxe Hướng Biển - P.503','2026-06-15','2026-06-18',7800000.00,'completed','2026-06-05 03:10:00'),(32,50,'Phòng Suite Hướng Biển - P.604','2026-06-01','2026-06-03',7500000.00,'completed','2026-05-20 06:00:00'),(33,50,'Phòng Deluxe Gia Đình - P.403','2026-07-25','2026-07-28',8400000.00,'confirmed','2026-07-11 04:30:00'),(34,52,'Căn Hộ Penthouse Tầng Thượng - P.902','2026-05-10','2026-05-14',26000000.00,'completed','2026-05-01 07:20:00'),(35,52,'Phòng Suite Hướng Biển - P.505','2026-07-12','2026-07-15',13500000.00,'confirmed','2026-07-05 02:00:00');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `migrations_filename_unique` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'001_create_users_table.sql','2026-07-10 21:20:58'),(2,'002_create_refresh_tokens_table.sql','2026-07-10 21:20:58'),(3,'003_add_role_to_users.sql','2026-07-10 21:20:58'),(4,'004_add_profile_fields_to_users.sql','2026-07-10 21:20:58');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refresh_tokens`
--

DROP TABLE IF EXISTS `refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refresh_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SHA-256 hash of the raw refresh token',
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refresh_tokens_hash_unique` (`token_hash`),
  KEY `refresh_tokens_user_id_idx` (`user_id`),
  CONSTRAINT `fk_refresh_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refresh_tokens`
--

LOCK TABLES `refresh_tokens` WRITE;
/*!40000 ALTER TABLE `refresh_tokens` DISABLE KEYS */;
INSERT INTO `refresh_tokens` VALUES (3,2,'17abff76eecd2a49f85c901514e0055fa1df0181f97b88920ab8b4469201c193','2026-07-17 23:50:05','2026-07-10 23:50:05'),(4,2,'923e08064fe14d15e3f22e04291e38c6149855637d6f5780b4cfe4411494475d','2026-08-02 14:04:39','2026-07-26 14:04:39'),(5,2,'968fbd96945a8801cd30048de8232db902e942ec8b68005b906a79b5ed8e7d84','2026-08-02 17:58:58','2026-07-26 17:58:58'),(6,2,'765fbd9b111b6bfc041cb24183e332c10b14901984dcf5d1b8b713606e2cdca0','2026-08-02 18:36:54','2026-07-26 18:36:54'),(10,2,'eb16511ea9573bd46e7cf037f6b0cb5515f50b7670b72f43ccec3ded2bc52585','2026-08-02 19:03:54','2026-07-26 19:03:54'),(11,2,'55c4c1b4e05d2b1146298d228fe60f01073ef1446ca4f87296a412e348f5e56a','2026-08-03 03:01:08','2026-07-27 03:01:08'),(14,2,'6bb3985ece450f1fff477f4e3c268ff9e60c562f34d802c5e13a1dfb2fec231a','2026-08-03 04:02:31','2026-07-27 04:02:31'),(16,1,'b4819b4c6b9a8486d03f1bf209329b02d535095a25f537a8b71e8bc5e2e9e812','2026-08-03 04:37:41','2026-07-27 04:37:41'),(18,1,'4abafbb95503238ee8f1b30c1b11489690fef093036548261746823cd4496e1e','2026-08-03 04:52:25','2026-07-27 04:52:25'),(21,2,'313fe8eebd7df08176662342e7ca041afc430f36f1ed11f98c9f396f1d4f587c','2026-08-03 04:59:18','2026-07-27 04:59:18'),(26,2,'55f690274d56a3744c1c3c9662c53bc4b72eeba579cf0828279baeb859abed94','2026-08-03 05:42:14','2026-07-27 05:42:14'),(27,2,'5e0cfc8e8a82c731f748726f006935782b6735d118ffa47f6d45ab02a9833760','2026-08-03 06:44:05','2026-07-27 06:44:05'),(28,2,'2cf240038e0a0b6ffb25c9ea36c42846f4156185e2816c98101efa0578e1b820','2026-08-03 07:00:53','2026-07-27 07:00:53'),(33,2,'31e9f503c2b26f0b7a85bfb72b1f86cedfb697c3bb13070adb9875ff891d2576','2026-08-03 07:37:26','2026-07-27 07:37:26'),(34,2,'9b95cdca70173298dc0bc4fba26fab491b79272fc211e6fdde614eab46974a4d','2026-08-03 08:14:11','2026-07-27 08:14:11'),(37,2,'9d91a924a1198c6c8445fbfc30fb6784f6e1d0163f37c7caabae508de8ea0661','2026-08-03 08:37:40','2026-07-27 08:37:40'),(38,2,'b2aee90bd9d8f2265984c8c0fc00a93d9132b3f3f4e9aa6d5ac2f0523991398d','2026-08-05 06:34:53','2026-07-29 06:34:53'),(42,2,'c4495b769c70dba0e05dc7aba263962444bd8940ca4551bbcf5f2fe0e9abe363','2026-08-05 07:18:46','2026-07-29 07:18:46'),(43,2,'ce97359afd59bd46945ede5a879e4a62d8da6fbb4148395a5478c352e01208bf','2026-08-05 07:51:12','2026-07-29 07:51:12'),(44,2,'427fcd36f81b50924f32cdad62921a705a2b2db6b51aba225f606e07e1dd2576','2026-08-05 08:49:08','2026-07-29 08:49:08'),(50,2,'04ae1137f8098ce29c71ac8fa7dff9e97bc7b9069bb943bc1e68877a7f246c29','2026-08-05 09:09:33','2026-07-29 09:09:33');
/*!40000 ALTER TABLE `refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_points` int unsigned NOT NULL DEFAULT '0',
  `member_tier` enum('dong','bac','vang','vip') COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (`member_points` >= 5000) then _utf8mb4'vip' when (`member_points` >= 2000) then _utf8mb4'vang' when (`member_points` >= 500) then _utf8mb4'bac' else _utf8mb4'dong' end)) STORED,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `dob`, `gender`, `member_points`, `role`, `password_hash`, `created_at`, `updated_at`) VALUES (1,'Nguyễn Văn Anh','anh@gmail.com',NULL,NULL,NULL,NULL,0,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:48:20','2026-07-10 23:48:20'),(2,'Admin Nguyễn','admin@gmail.com',NULL,NULL,NULL,NULL,0,'admin','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:49:22','2026-07-10 23:49:51'),(3,'Nguyễn Văn An','khachhang1@example.com','0901234501','1 Lê Lợi, Quận 1, TP.HCM','1990-05-12','male',200,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-27 07:33:54'),(4,'Trần Thị Bôn','khachhang2@example.com','0901234502','2 Nguyễn Huệ, Quận 1, TP.HCM','1995-08-20','female',200,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-27 08:14:46'),(5,'Lê Hoàng Châu','khachhang3@example.com','0901234503','3 Trần Hưng Đạo, Quận 5, TP.HCM','1988-11-05','other',50,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(6,'Phạm Văn Dũng','khachhang4@example.com','0901234504','4 Lý Tự Trọng, Quận 1, TP.HCM','1992-02-14','male',500,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(7,'Hoàng Thị E','khachhang5@example.com','0901234505','5 Võ Văn Tần, Quận 3, TP.HCM','1998-07-22','female',120,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(8,'Đặng Văn Phong','khachhang6@example.com','0901234506','6 Điện Biên Phủ, Bình Thạnh, TP.HCM','1985-09-09','male',300,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(9,'Vũ Thị Giang','khachhang7@example.com','0901234507','7 Nguyễn Đình Chiểu, Quận 3, TP.HCM','1993-12-01','female',450,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(10,'Bùi Xuân Hoàng','khachhang8@example.com','0901234508','8 Cách Mạng Tháng 8, Quận 10, TP.HCM','1991-04-18','male',0,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(11,'Đỗ Thị Hương','khachhang9@example.com','0901234509','9 Lê Hồng Phong, Quận 10, TP.HCM','1997-06-30','female',80,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(12,'Ngô Văn Khang','khachhang10@example.com','0901234510','10 Phan Đăng Lưu, Phú Nhuận, TP.HCM','1989-10-10','male',1000,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(13,'Lý Thu Lan','khachhang11@example.com','0901234511','11 Hai Bà Trưng, Quận 3, TP.HCM','1996-01-25','female',15,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(14,'Đoàn Văn Minh','khachhang12@example.com','0901234512','12 Nguyễn Thị Minh Khai, Quận 1, TP.HCM','1994-03-08','male',260,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(15,'Trương Thị Nga','khachhang13@example.com','0901234513','13 Nam Kỳ Khởi Nghĩa, Quận 3, TP.HCM','1999-11-20','female',320,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(16,'Đinh Hoàng Nam','khachhang14@example.com','0901234514','14 Nguyễn Văn Trỗi, Phú Nhuận, TP.HCM','1987-05-15','male',410,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(17,'Hồ Thị Oanh','khachhang15@example.com','0901234515','15 Cộng Hòa, Tân Bình, TP.HCM','1992-08-08','female',110,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(18,'Phan Văn Phú','khachhang16@example.com','0901234516','16 Trường Chinh, Tân Bình, TP.HCM','1990-12-12','male',800,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(19,'Võ Thị Quỳnh','khachhang17@example.com','0901234517','17 Âu Cơ, Tân Phú, TP.HCM','1995-02-28','female',650,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(20,'Nguyễn Hoàng Sơn','khachhang18@example.com','0901234518','18 Lũy Bán Bích, Tân Phú, TP.HCM','1986-07-07','male',20,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(21,'Trần Thị Tâm','khachhang19@example.com','0901234519','19 Kinh Dương Vương, Bình Tân, TP.HCM','1998-04-04','female',95,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(22,'Lê Văn Uyển','khachhang20@example.com','0901234520','20 Võ Văn Kiệt, Quận 5, TP.HCM','1991-09-19','male',540,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(23,'Phạm Thị Vui','khachhang21@example.com','0901234521','21 Phạm Văn Đồng, Thủ Đức, TP.HCM','1993-10-31','female',125,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(24,'Hoàng Văn Xuân','khachhang22@example.com','0901234522','22 Kha Vạn Cân, Thủ Đức, TP.HCM','1989-01-01','male',330,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(25,'Đặng Thị Yến','khachhang23@example.com','0901234523','23 Lê Văn Việt, TP Thủ Đức, TP.HCM','1996-03-22','female',870,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(26,'Vũ Văn An','khachhang24@example.com','0901234524','24 Nguyễn Oanh, Gò Vấp, TP.HCM','1994-06-16','male',40,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(27,'Bùi Thị Bảo','khachhang25@example.com','0901234525','25 Phan Văn Trị, Gò Vấp, TP.HCM','1997-11-11','female',290,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(28,'Đỗ Văn Cường','khachhang26@example.com','0901234526','26 Quang Trung, Gò Vấp, TP.HCM','1990-12-25','male',560,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(29,'Ngô Thị Dung','khachhang27@example.com','0901234527','27 Hậu Giang, Quận 6, TP.HCM','1992-05-05','female',180,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(30,'Lý Văn Đạt','khachhang28@example.com','0901234528','28 Nguyễn Tất Thành, Quận 4, TP.HCM','1988-08-08','male',710,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(31,'Đoàn Thị Giang','khachhang29@example.com','0901234529','29 Tôn Đản, Quận 4, TP.HCM','1995-10-20','female',220,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(32,'Trương Văn Hùng','khachhang30@example.com','0901234530','30 Huỳnh Tấn Phát, Quận 7, TP.HCM','1985-02-02','male',490,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(33,'Đinh Thị Khanh','khachhang31@example.com','0901234531','31 Nguyễn Thị Thập, Quận 7, TP.HCM','1999-07-07','female',60,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(34,'Hồ Văn Long','khachhang32@example.com','0901234532','32 Tạ Quang Bửu, Quận 8, TP.HCM','1991-12-24','male',830,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(35,'Phan Thị Mai','khachhang33@example.com','0901234533','33 Phạm Hùng, Bình Chánh, TP.HCM','1996-09-15','female',140,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(36,'Võ Văn Nghĩa','khachhang34@example.com','0901234534','34 Quốc Lộ 50, Bình Chánh, TP.HCM','1987-11-11','male',370,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(37,'Nguyễn Thị Oanh','khachhang35@example.com','0901234535','35 Nguyễn Văn Linh, Quận 7, TP.HCM','1993-01-09','female',520,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(38,'Trần Văn Phát','khachhang36@example.com','0901234536','36 Lê Văn Khương, Quận 12, TP.HCM','1990-06-06','male',280,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(39,'Lê Thị Quyên','khachhang37@example.com','0901234537','37 Nguyễn Ảnh Thủ, Quận 12, TP.HCM','1998-12-12','female',910,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(40,'Phạm Văn Quang','khachhang38@example.com','0901234538','38 Tô Ký, Hóc Môn, TP.HCM','1989-03-03','male',170,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(41,'Hoàng Thị Sen','khachhang39@example.com','0901234539','39 Đỗ Văn Dậy, Hóc Môn, TP.HCM','1994-08-08','female',440,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(42,'Đặng Văn Thái','khachhang40@example.com','0901234540','40 Lê Lợi, Củ Chi, TP.HCM','1986-04-14','male',620,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(43,'Vũ Thị Thu','khachhang41@example.com','0901234541','41 Tỉnh Lộ 8, Củ Chi, TP.HCM','1997-02-20','female',250,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(44,'Bùi Văn Toàn','khachhang42@example.com','0901234542','42 Huỳnh Tấn Phát, Nhà Bè, TP.HCM','1991-05-05','male',780,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(45,'Đỗ Thị Trâm','khachhang43@example.com','0901234543','43 Lê Văn Lương, Nhà Bè, TP.HCM','1995-09-09','female',130,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(46,'Ngô Văn Vỹ','khachhang44@example.com','0901234544','44 Rừng Sác, Cần Giờ, TP.HCM','1988-10-10','male',550,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(47,'Lý Thị Xuyến','khachhang45@example.com','0901234545','45 Duyên Hải, Cần Giờ, TP.HCM','1999-01-01','female',390,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(48,'Đoàn Văn Ý','khachhang46@example.com','0901234546','46 Nguyễn Duy Trinh, Quận 2, TP.HCM','1990-11-11','male',80,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(49,'Trương Thị Tươi','khachhang47@example.com','0901234547','47 Mai Chí Thọ, Quận 2, TP.HCM','1993-06-06','female',210,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(50,'Đinh Văn Sinh','khachhang48@example.com','0901234548','48 Trần Não, Quận 2, TP.HCM','1985-12-12','male',670,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(51,'Hồ Thị Sương','khachhang49@example.com','0901234549','49 Song Hành, Quận 2, TP.HCM','1996-04-04','female',430,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-10 23:57:50'),(52,'Nguyễn Văn Sang','anh1@gmail.com','0901234550','50 Lương Định Của, Quận 2, TP.HCM','1992-07-07','male',900,'customer','$2y$12$SjPwRXxiW0WrTAGAX//cKeW0JBQNRQfA/KwOZxqbCMP2DepWTLiW6','2026-07-10 23:57:50','2026-07-27 07:36:55');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'khachhang_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-04 15:34:32
