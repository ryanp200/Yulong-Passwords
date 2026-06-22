-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: yulongpasswords
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `yulongpasswords`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `yulongpasswords` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `yulongpasswords`;

--
-- Table structure for table `senhas`
--

DROP TABLE IF EXISTS `senhas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `senhas` (
  `id_senhas` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_senhas`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `senhas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `senhas`
--

LOCK TABLES `senhas` WRITE;
/*!40000 ALTER TABLE `senhas` DISABLE KEYS */;
INSERT INTO `senhas` VALUES (6,'Netflix','Woshihanguoren',2),(7,'Xuesheng','iskcr',1),(8,'ForIMiss','Ineed',1),(9,'KakaoTalk','ye-jin1900',2),(10,'Starbucks','gwangjuisnotajoke',2),(11,'Celular do Samuel','_samuel>>>s',3),(12,'Internet do Angelo','rocha1103',3),(13,'Email do Pedro','augustoamasamuel',3),(14,'Site Suspeito 1','pranchote',4),(15,'Site Suspeito 2','jaji wonhae',4),(16,'Termo','querojaji',4);
/*!40000 ALTER TABLE `senhas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `hierarquia` int(11) DEFAULT 1,
  `privilegio` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Ryan Aparecido Peres','2008-01-12','ryan.peres2311@gmail.com','$2y$10$yA4AM1j/c4xHSdkzyXfTu.xQzhDcVYxmmkfnen3fJGZrVtF6zUpPa','rp2008',3,'admin'),(2,'Ye-jin Jo','2000-11-04','joye.jin2@kcna.kp','$2y$10$l4Il7zEF6L3bp.1JHK1A9.ui6bpBTrpnNlOPbHGQYCrEH0uTWeQkK','jyj',1,'admin'),(3,'Yasmim Camargo','2008-11-11','yasmimdesandra@gmail.com','$2y$10$Z/KGWAuZwsfNu3ZBSihnWOOD9nxZ4F44tf8mBEo1OgFk3YvBUDTrW','ysc',1,NULL),(4,'Matheus Barboza Prancha','2008-09-12','matheus7prancha@gmail.com','$2y$10$BT9mzyZic.gLyQwg6S4V6eJ.9H4FiO10IRzwCjGBUXQy9AD84CnjK','prancha',1,NULL),(5,'Do-yun Ha','1988-11-23','hadoyun@kcna.kp','$2y$10$P50RDz89qvQAse6Mk0Fn9.g65sXbPy3NODMhf7QfrzCQXOnNBy88S','hadoyun',2,NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-22 13:23:20
