-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: kostkamar
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `idAdmin` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(45) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `namaAdmin` varchar(255) DEFAULT NULL,
  `reset_token` varchar(6) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`idAdmin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'wardhatuljannahfiqyani@gmail.com','$2b$12$Rfly3H23jYIqWI49elCV7.A8Tk5lOyVLQdhLnYgk57bO1z6FFSiWa','Wardha',NULL,NULL),(2,'hilmi.af05@gmail.com','$2y$10$jimfw6PGmBhTEX5kGLGoquGO2HQB.khvECPkTaFMLyCXBRUZu4oMu','Hilmi',NULL,NULL),(3,'kakapatria65@gmail.com','$2b$12$5Ttt4v8Y9TM7T2WemkEa4eng6Ayi53zgv3BkiK3E8ZbEARt8vN7Yq','Kaka',NULL,NULL),(4,'alfi3197@gmail.com','$2b$12$jhlOJd3fOmedRAwb.YvpUuZzNzIFB.u/EbdLpHdAPsVK9StiFKbLy','Pia',NULL,NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blok`
--

DROP TABLE IF EXISTS `blok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blok` (
  `idBlok` int NOT NULL AUTO_INCREMENT,
  `namaBlok` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idBlok`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blok`
--

LOCK TABLES `blok` WRITE;
/*!40000 ALTER TABLE `blok` DISABLE KEYS */;
INSERT INTO `blok` VALUES (1,'A'),(2,'B'),(3,'C'),(4,'D'),(5,'E'),(6,'F'),(7,'G'),(8,'H'),(9,'I');
/*!40000 ALTER TABLE `blok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fasilitas`
--

DROP TABLE IF EXISTS `fasilitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fasilitas` (
  `idFasilitas` int NOT NULL AUTO_INCREMENT,
  `namaFasilitas` text NOT NULL,
  `biayaTambahan` int NOT NULL,
  PRIMARY KEY (`idFasilitas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fasilitas`
--

LOCK TABLES `fasilitas` WRITE;
/*!40000 ALTER TABLE `fasilitas` DISABLE KEYS */;
INSERT INTO `fasilitas` VALUES (1,'kamar mandi luar',200000),(2,'kamar mandi dalam',50000),(3,'biaya listrik',45000);
/*!40000 ALTER TABLE `fasilitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kamar`
--

DROP TABLE IF EXISTS `kamar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kamar` (
  `idKamar` int NOT NULL AUTO_INCREMENT,
  `namaKamar` varchar(100) NOT NULL,
  `nomorKamar` varchar(5) NOT NULL,
  `harga` int NOT NULL,
  `status` enum('Tersedia','Kosong','Booking') NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `idBlok` int DEFAULT NULL,
  `deskripsi` text,
  PRIMARY KEY (`idKamar`),
  KEY `fk_kamar_blok` (`idBlok`),
  CONSTRAINT `fk_kamar_blok` FOREIGN KEY (`idBlok`) REFERENCES `blok` (`idBlok`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kamar`
--

LOCK TABLES `kamar` WRITE;
/*!40000 ALTER TABLE `kamar` DISABLE KEYS */;
INSERT INTO `kamar` VALUES (1,'KamarA1','A1',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(2,'KamarA2','A2',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(3,'KamarA3','A3',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(4,'KamarA4','A4',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(5,'KamarA5','A5',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(6,'KamarA6','A6',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(7,'KamarA7','A7',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(8,'KamarA8','A8',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(9,'KamarA9','A9',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(10,'KamarA10','A10',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(11,'KamarA11','A11',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(12,'KamarA12','A12',300000,'Tersedia',NULL,1,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(13,'KamarB1','B1',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(14,'KamarB2','B2',300000,'Booking',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(15,'KamarB3','B3',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(16,'KamarB4','B4',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(17,'KamarB5','B5',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(18,'KamarB6','B6',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(19,'KamarB7','B7',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(20,'KamarB8','B8',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(21,'KamarB9','B9',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(22,'KamarB10','B10',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(23,'KamarB11','B11',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(24,'KamarB12','B12',300000,'Tersedia',NULL,2,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(25,'KamarC1','C1',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(26,'KamarC2','C2',300000,'Booking',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(27,'KamarC3','C3',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(28,'KamarC4','C4',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(29,'KamarC5','C5',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(30,'KamarC6','C6',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(31,'KamarC7','C7',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(32,'KamarC8','C8',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(33,'KamarC9','C9',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(34,'KamarC10','C10',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(35,'KamarC11','C11',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(36,'KamarC12','C12',300000,'Tersedia',NULL,3,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(37,'KamarD1','D1',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(38,'KamarD2','D2',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(39,'KamarD3','D3',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(40,'KamarD4','D4',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(41,'KamarD5','D5',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(42,'KamarD6','D6',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(43,'KamarD7','D7',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(44,'KamarD8','D8',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(45,'KamarD9','D9',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(46,'KamarD10','D10',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(47,'KamarD11','D11',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(48,'KamarD12','D12',300000,'Tersedia',NULL,4,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(49,'KamarE1','E1',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(50,'KamarE2','E2',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(51,'KamarE3','E3',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(52,'KamarE4','E4',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(53,'KamarE5','E5',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(54,'KamarE6','E6',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(55,'KamarE7','E7',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(56,'KamarE8','E8',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(57,'KamarE9','E9',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(58,'KamarE10','E10',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(59,'KamarE11','E11',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(60,'KamarE12','E12',300000,'Tersedia',NULL,5,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(61,'KamarF1','F1',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(62,'KamarF2','F2',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(63,'KamarF3','F3',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(64,'KamarF4','F4',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(65,'KamarF5','F5',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(66,'KamarF6','F6',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(67,'KamarF7','F7',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(68,'KamarF8','F8',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(69,'KamarF9','F9',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(70,'KamarF10','F10',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(71,'KamarF11','F11',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(72,'KamarF12','F12',300000,'Tersedia',NULL,6,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(73,'KamarG1','G1',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(74,'KamarG2','G2',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(75,'KamarG3','G3',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(76,'KamarG4','G4',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(77,'KamarG5','G5',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(78,'KamarG6','G6',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(79,'KamarG7','G7',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(80,'KamarG8','G8',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(81,'KamarG9','G9',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(82,'KamarG10','G10',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(83,'KamarG11','G11',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(84,'KamarG12','G12',300000,'Tersedia',NULL,7,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(85,'KamarH1','H1',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(86,'KamarH2','H2',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(87,'KamarH3','H3',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(88,'KamarH4','H4',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(89,'KamarH5','H5',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(90,'KamarH6','H6',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(91,'KamarH7','H7',300000,'Booking',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(92,'KamarH8','H8',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(93,'KamarH9','H9',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(94,'KamarH10','H10',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(95,'KamarH11','H11',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(96,'KamarH12','H12',300000,'Tersedia',NULL,8,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(97,'KamarI1','I1',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(98,'KamarI2','I2',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(99,'KamarI3','I3',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(100,'KamarI4','I4',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(101,'KamarI5','I5',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(102,'KamarI6','I6',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(103,'KamarI7','I7',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(104,'KamarI8','I8',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(105,'KamarI9','I9',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.'),(106,'KamarI10','I10',300000,'Tersedia',NULL,9,'Kamar dengan fasilitas lengkap, cocok untuk mahasiswa dan pekerja.');
/*!40000 ALTER TABLE `kamar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kamar_fasilitas`
--

DROP TABLE IF EXISTS `kamar_fasilitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kamar_fasilitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idKamar` int DEFAULT NULL,
  `idFasilitas` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idKamar` (`idKamar`),
  KEY `idFasilitas` (`idFasilitas`),
  CONSTRAINT `kamar_fasilitas_ibfk_1` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`),
  CONSTRAINT `kamar_fasilitas_ibfk_2` FOREIGN KEY (`idFasilitas`) REFERENCES `fasilitas` (`idFasilitas`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kamar_fasilitas`
--

LOCK TABLES `kamar_fasilitas` WRITE;
/*!40000 ALTER TABLE `kamar_fasilitas` DISABLE KEYS */;
INSERT INTO `kamar_fasilitas` VALUES (1,10,1),(2,11,1),(3,9,2),(4,91,1),(5,75,2),(6,19,2),(7,92,2),(8,97,1),(9,83,1),(10,63,2),(11,16,1),(12,30,1),(13,40,1),(14,76,1),(15,101,2),(16,77,1),(17,62,1),(18,84,2),(19,12,1),(20,21,2),(21,14,2),(22,47,2),(23,3,2),(24,50,2),(25,39,2),(26,82,1),(27,65,2),(28,73,1),(29,69,1),(30,71,1),(31,26,1),(32,38,1),(33,89,2),(34,2,2),(35,68,2),(36,90,2),(37,56,1),(38,93,2),(39,46,2),(40,58,2),(41,100,1),(42,1,2),(43,23,2),(44,53,1),(45,15,2),(46,17,1),(47,5,2),(48,32,2),(49,54,1),(50,37,2),(51,6,2),(52,35,1),(53,7,2),(54,67,2),(55,98,2),(56,66,2),(57,57,2),(58,102,1),(59,103,2),(60,49,2),(61,99,2),(62,4,2),(63,81,1),(64,96,1),(65,85,2),(66,8,1),(67,55,1),(68,42,2),(69,29,2),(70,79,2),(71,52,2),(72,45,2),(73,33,2),(74,44,2),(75,48,2),(76,28,1),(77,70,2),(78,34,2),(79,20,1),(80,94,2),(81,59,2),(82,72,1),(83,36,2),(84,22,1),(85,25,2),(86,31,2),(87,13,1),(88,41,1),(89,78,2),(90,95,2),(91,88,1),(92,60,2),(93,105,2),(94,87,2),(95,43,1),(96,86,2),(97,27,2),(98,74,1),(99,80,2),(100,61,2),(101,51,1),(102,24,1),(103,104,1),(104,64,1),(105,18,2),(106,106,1),(128,1,3),(129,2,3),(130,3,3),(131,4,3),(132,5,3),(133,6,3),(134,7,3),(135,8,3),(136,9,3),(137,10,3),(138,11,3),(139,12,3),(140,13,3),(141,14,3),(142,15,3),(143,16,3),(144,17,3),(145,18,3),(146,19,3),(147,20,3),(148,21,3),(149,22,3),(150,23,3),(151,24,3),(152,25,3),(153,26,3),(154,27,3),(155,28,3),(156,29,3),(157,30,3),(158,31,3),(159,32,3),(160,33,3),(161,34,3),(162,35,3),(163,36,3),(164,37,3),(165,38,3),(166,39,3),(167,40,3),(168,41,3),(169,42,3),(170,43,3),(171,44,3),(172,45,3),(173,46,3),(174,47,3),(175,48,3),(176,49,3),(177,50,3),(178,51,3),(179,52,3),(180,53,3),(181,54,3),(182,55,3),(183,56,3),(184,57,3),(185,58,3),(186,59,3),(187,60,3),(188,61,3),(189,62,3),(190,63,3),(191,64,3),(192,65,3),(193,66,3),(194,67,3),(195,68,3),(196,69,3),(197,70,3),(198,71,3),(199,72,3),(200,73,3),(201,74,3),(202,75,3),(203,76,3),(204,77,3),(205,78,3),(206,79,3),(207,80,3),(208,81,3),(209,82,3),(210,83,3),(211,84,3),(212,85,3),(213,86,3),(214,87,3),(215,88,3),(216,89,3),(217,90,3),(218,91,3),(219,92,3),(220,93,3),(221,94,3),(222,95,3),(223,96,3),(224,97,3),(225,98,3),(226,99,3),(227,100,3),(228,101,3),(229,102,3),(230,103,3),(231,104,3),(232,105,3),(233,106,3);
/*!40000 ALTER TABLE `kamar_fasilitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `idPembayaran` int NOT NULL AUTO_INCREMENT,
  `tanggalPembayaran` varchar(45) NOT NULL,
  `batasPembayaran` varchar(45) NOT NULL,
  `durasiSewa` varchar(45) NOT NULL,
  `StatusPembayaran` enum('Lunas','Belum Lunas') NOT NULL,
  `idPenyewa` int DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `id_pemesanan` int DEFAULT NULL,
  PRIMARY KEY (`idPembayaran`),
  KEY `fk_idPenyewa` (`idPenyewa`),
  KEY `fk_pembayaran_pemesanan` (`id_pemesanan`),
  CONSTRAINT `fk_idPenyewa` FOREIGN KEY (`idPenyewa`) REFERENCES `penyewa` (`idPenyewa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pembayaran_pemesanan` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemesanan`
--

DROP TABLE IF EXISTS `pemesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemesanan` (
  `id_pemesanan` int NOT NULL AUTO_INCREMENT,
  `pemesanan_kamar` date NOT NULL,
  `uang_muka` decimal(10,2) NOT NULL,
  `status_uang_muka` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_transfer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tenggat_uang_muka` date DEFAULT NULL,
  `mulai_menempati_kos` date DEFAULT NULL,
  `batas_menempati_kos` date DEFAULT NULL,
  `status` enum('Menunggu Pembayaran','Menunggu Dikonfirmasi','Dikonfirmasi','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Menunggu Pembayaran',
  `id_penyewa` int DEFAULT NULL,
  `idKamar` int DEFAULT NULL,
  PRIMARY KEY (`id_pemesanan`),
  KEY `fk_id_penyewa` (`id_penyewa`),
  KEY `fk_pemesanan_kamar` (`idKamar`),
  CONSTRAINT `fk_id_penyewa` FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa` (`idPenyewa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pemesanan_kamar` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemesanan`
--

LOCK TABLES `pemesanan` WRITE;
/*!40000 ALTER TABLE `pemesanan` DISABLE KEYS */;
INSERT INTO `pemesanan` VALUES (1,'2024-11-16',515000.00,'Sudah Bayar',NULL,'2024-11-18','2024-11-16','2024-12-16','Menunggu Pembayaran',1,26),(2,'2024-11-16',365000.00,'Lunas','uploads/This Graffiti Artist Stuns Passerby With His 3D-Looking Abstract Drawings.jpeg','2024-11-18','2024-11-17','2024-12-17','Menunggu Pembayaran',1,14),(3,'2024-11-16',515000.00,'Sudah Bayar',NULL,'2024-11-18','2024-11-17','2024-12-17','Menunggu Pembayaran',1,91);
/*!40000 ALTER TABLE `pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penyewa`
--

DROP TABLE IF EXISTS `penyewa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penyewa` (
  `idPenyewa` int NOT NULL AUTO_INCREMENT,
  `namaPenyewa` varchar(100) NOT NULL,
  `noTelepon` varchar(15) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fotoJaminan` varchar(255) DEFAULT NULL,
  `idKamar` int DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPenyewa`),
  KEY `fk_idKamar` (`idKamar`),
  CONSTRAINT `fk_idKamar` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penyewa`
--

LOCK TABLES `penyewa` WRITE;
/*!40000 ALTER TABLE `penyewa` DISABLE KEYS */;
INSERT INTO `penyewa` VALUES (1,'KakaPatria','085707308476','kakapatria22@gmail.com','$2y$10$1Nh.at/xjhsyrNGYzLB/dO6klG94aNTe3tK27KNVvd2/8pzu17sum',NULL,NULL,NULL,NULL,'2024-11-02 09:33:50'),(2,'Patria','085707308476','kakapatria65@gmail.com','$2y$10$0nC0lOEsS1ceDnckEWZSKubCmsZogwzkfeJY6B/4Lesoz3xZd0Zue',NULL,NULL,NULL,NULL,'2024-11-02 09:35:59'),(3,'KakaPatria','085707308476','kakapatria66@gmail.com','$2y$10$H5ex03jGgsyIBo.Mcgapuu8Hldq0yVCGMM0OZzMze2tG1XlyGMUze',NULL,NULL,NULL,NULL,'2024-11-02 09:41:07'),(4,'Hilmi','081217336386','hilmi.af05@gmail.com','$2y$10$wyWt5NxnLZEvRuVgcydeLes26caN.q7cRqb45ibWF9gTtU82kfm9e',NULL,NULL,NULL,NULL,'2024-11-13 03:03:25'),(5,'Firman','081654323754','firman@gmail.com','$2y$10$sfJ4wqDfXcVNLb18V20PoOebCuFlL10q75eae3cN4WwB.Uwt1FO/i',NULL,NULL,NULL,NULL,'2024-11-16 04:57:08');
/*!40000 ALTER TABLE `penyewa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesan`
--

DROP TABLE IF EXISTS `pesan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesan` (
  `idPesan` int NOT NULL AUTO_INCREMENT,
  `idPenyewa` int DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPesan`),
  KEY `idPenyewa` (`idPenyewa`),
  CONSTRAINT `pesan_ibfk_1` FOREIGN KEY (`idPenyewa`) REFERENCES `penyewa` (`idPenyewa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesan`
--

LOCK TABLES `pesan` WRITE;
/*!40000 ALTER TABLE `pesan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-16 13:03:44