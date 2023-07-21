-- MySQL dump 10.13  Distrib 5.5.9, for osx10.6 (i386)
--
-- Host: localhost    Database: dbtest
-- ------------------------------------------------------
-- Server version	5.5.9

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `dbtest`
--

/*!40000 DROP DATABASE IF EXISTS `dbtest`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dbtest` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dbtest`;

--
-- Table structure for table `data_types`
--

DROP TABLE IF EXISTS `data_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `colVarcharNotNull` varchar(20) NOT NULL,
  `colVarcharNull` varchar(20) DEFAULT NULL,
  `colDateNotNull` date NOT NULL,
  `colDateNull` date DEFAULT NULL,
  `colFloatNotNull` float(5,2) NOT NULL,
  `colFloatNull` float(5,2) DEFAULT NULL,
  `colDoubleNotNull` double(5,2) NOT NULL,
  `colDoubleNull` double(5,2) DEFAULT NULL,
  `colDecimalNotNull` decimal(5,2) NOT NULL,
  `colDecimalNull` decimal(5,2) DEFAULT NULL,
  `colDatetimeNotNull` datetime NOT NULL,
  `colDatetimeNull` datetime DEFAULT NULL,
  `colTimestampNotNull` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `colTimestampNull` timestamp NULL DEFAULT NULL,
  `colTimeNotNull` time NOT NULL,
  `colTimeNull` time DEFAULT NULL,
  `colCharNotNull` char(2) NOT NULL,
  `colCharNull` char(2) DEFAULT NULL,
  `colBlobNotNull` blob NOT NULL,
  `colBlobNull` blob,
  `colEnumNotNull` enum('One','Two','Three') NOT NULL DEFAULT 'One',
  `colEnumNull` enum('One','Two','Three') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=ascii;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_types`
--

LOCK TABLES `data_types` WRITE;                                                       
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;
INSERT INTO `data_types` (`id`, `colVarcharNotNull`, `colVarcharNull`, `colDateNotNull`, `colDateNull`, `colFloatNotNull`, `colFloatNull`, `colDoubleNotNull`, `colDoubleNull`, `colDecimalNotNull`, `colDecimalNull`, `colDatetimeNotNull`, `colDatetimeNull`, `colTimestampNotNull`, `colTimestampNull`, `colTimeNotNull`, `colTimeNull`, `colCharNotNull`, `colCharNull`, `colBlobNotNull`, `colBlobNull`, `colEnumNotNull`, `colEnumNull`) VALUES
(1,'var_char_not_null','var_char_null','2014-01-01','2014-01-01',1.01,1.01,1.01,1.01,1.01,1.01,'2014-01-01 01:00:00','2014-01-01 01:00:00','2014-01-01 06:00:00','2013-09-27 01:29:50','01:00:00','01:00:00','RC','RC','blob\n','blob\n','One','One'),
(2, '', NULL, '0000-00-00', NULL, 0.00, NULL, 0.00, NULL, 0.00, NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', '2013-09-27 13:41:53', '00:00:00', NULL, '', NULL, '', NULL, 'One', NULL);
/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userName` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `nickName` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `empId` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dept` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `officePhone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobilePhone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessCode` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`userName`)
) ENGINE=MEMORY AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `firstName`, `lastName`, `userName`, `nickName`, `empId`, `title`, `dept`, `office`, `email`, `officePhone`, `mobilePhone`, `accessCode`)  VALUES
  (1,'Rob','Callahan','rcallaha',NULL,'002386','Principal Systems Engr','005160 - Strategic Tools & Solut','B8 Office/Cube 32','Robert.Callahan@neustar.biz','(571) 434-5165','(703) 851-5412',3),
  (2,'Meg (Geisler)','Callahan','mgeisler',NULL,'003907','Principal Bus Systems Analyst','005165 - Shared Systems Administ','Sterling','meg.geisler@neustar.biz','703)-464-4245','703-328-2544',0),
  (3,'Iris','Culpepper','iculpepp',NULL,'003194','Principal Bus Systems Analyst','005235 - SysQ','Bldg 10, 2nd Floor, Cube 53','Iris.Culpepper@neustar.biz','(571) 434-6050',NULL,0),
  (4,'James','Weber','jweber',NULL,'002746','Dir Data Centers','005117 - Operations Mgmt','B8 Office/Cube 76','James.Weber@neustar.biz','571-434-5917','571-334-0587',0),
  (5,'Melinda','Miller','mmiller',NULL,'003171','Fraud Mgmt Svcs Dir','005106 - Data Center Facility Op',NULL,'Melinda.Miller@neustar.biz','(303) 802-1383',NULL,0),
  (6,'David','Bennett','dbennett',NULL,'002453','Sr Data Center Techn','005106 - Data Center Facility Op','B8 Office/Cube 106','David.Bennett@neustar.biz','(571) 434-3513','571-221-8521',0),
  (7,'Steve','Clark','sclark',NULL,'000968','Staff Sys Admin','005165 - Shared Systems Administ','B8 Office/Cube 13','Steve.Clark@neustar.biz','(571) 434-5646','3042791289',0),
  (8,'Angelica','Brown','abrown',NULL,'SPP0000899','Contractor - Data Storage Scienc',NULL,NULL,'Angelica.Brown@neustar.biz','703-547-6023','703-309-9073',0),
  (9,'Allyson','Raines','s_araine',NULL,NULL,'Contractor',NULL,NULL,'Allyson.Raines@neustar.biz','571-434-6816',NULL,0);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-09-26 21:46:18
