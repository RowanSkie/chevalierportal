-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: schoolportal
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announce`
--

DROP TABLE IF EXISTS `announce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `announce` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `postdate` date DEFAULT NULL COMMENT 'date for posted announcement',
  `enddate` date DEFAULT NULL COMMENT 'date of when to remove the post from the announcement board',
  `post` varchar(200) DEFAULT NULL,
  `importance` int(11) DEFAULT NULL COMMENT '1 - alarming\n2 - subject events\n3 - classroom',
  PRIMARY KEY (`postid`),
  UNIQUE KEY `postid_UNIQUE` (`postid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announce`
--

LOCK TABLES `announce` WRITE;
/*!40000 ALTER TABLE `announce` DISABLE KEYS */;
INSERT INTO `announce` VALUES (1,'2019-03-08','2019-03-11','This is a test announcement.',1),(2,'2019-03-09','2019-03-09','yes',1),(3,'2019-03-11','2019-03-11','3 homeworks due Tuesday',2),(4,'2019-03-15','2019-03-15','This is a test announcement',2),(5,'2019-03-18','2019-03-21','This 200-letter announcement will soon vanish the day after OD.',1),(6,'2019-03-21','2019-04-05','Deadlineon announcement on April 5',3);
/*!40000 ALTER TABLE `announce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sectionlist`
--

DROP TABLE IF EXISTS `sectionlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sectionlist` (
  `sectionid` int(11) NOT NULL,
  `sectionname` varchar(45) NOT NULL,
  PRIMARY KEY (`sectionid`,`sectionname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sectionlist`
--

LOCK TABLES `sectionlist` WRITE;
/*!40000 ALTER TABLE `sectionlist` DISABLE KEYS */;
INSERT INTO `sectionlist` VALUES (1001,'1A'),(1002,'1B'),(1003,'1C'),(1004,'1D'),(1101,'2A'),(1102,'2B'),(1103,'2C'),(1104,'2D'),(1201,'3A'),(1202,'3B'),(1203,'3C'),(1204,'3D'),(1301,'4A'),(1302,'4B'),(1303,'4C'),(1304,'4D'),(1401,'5A'),(1402,'5B'),(1403,'5C'),(1404,'5D'),(1501,'6A'),(1502,'6B'),(1503,'6C'),(1504,'6D'),(1601,'7A'),(1602,'7B'),(1603,'7C'),(1604,'7D'),(1701,'8A'),(1702,'8B'),(1703,'8C'),(1704,'8D'),(1801,'9A'),(1802,'9B'),(1803,'9C'),(1804,'9D'),(1901,'10A'),(1902,'10B'),(1903,'10C'),(1904,'10D'),(2001,'11A'),(2002,'11B'),(2003,'11C'),(2004,'11D'),(2005,'11E'),(2101,'12A'),(2102,'12B'),(2103,'12C'),(2104,'12D'),(2105,'12E');
/*!40000 ALTER TABLE `sectionlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studentclass`
--

DROP TABLE IF EXISTS `studentclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `studentclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentid` int(11) NOT NULL,
  `subjectid` int(11) NOT NULL,
  `sectionid` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `teacherid` int(11) NOT NULL,
  `grade` double DEFAULT NULL,
  `remarks` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`studentid`,`subjectid`,`sectionid`,`year`,`teacherid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studentclass`
--

LOCK TABLES `studentclass` WRITE;
/*!40000 ALTER TABLE `studentclass` DISABLE KEYS */;
INSERT INTO `studentclass` VALUES (1,1,104,2002,2019,1,89,'passed'),(2,1,102,2002,2019,2,90,'excellent'),(3,2,102,1801,2019,2,89,'Pasado na!'),(4,2,104,1801,2019,1,100,'Thank you Lord!');
/*!40000 ALTER TABLE `studentclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studentlist`
--

DROP TABLE IF EXISTS `studentlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `studentlist` (
  `idstudent` int(11) NOT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `BirthDate` datetime DEFAULT NULL,
  PRIMARY KEY (`idstudent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studentlist`
--

LOCK TABLES `studentlist` WRITE;
/*!40000 ALTER TABLE `studentlist` DISABLE KEYS */;
INSERT INTO `studentlist` VALUES (1,'Mark Joshua','Zuniga','2001-01-07 00:00:00'),(2,'John Michael','Zuniga','2004-04-11 00:00:00'),(3,'Dennis','Zuniga','1978-10-02 00:00:00'),(4,'Marianne Joyce','Zuniga','2010-08-25 00:00:00');
/*!40000 ALTER TABLE `studentlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `subject` (
  `idsubject` int(3) NOT NULL,
  `subjectname` varchar(45) NOT NULL,
  `comment` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idsubject`,`subjectname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES (101,'eng','English'),(102,'fil','Filipino'),(103,'math','Mathematics'),(104,'sci','Science'),(105,'ap','Araling Panlipunan'),(106,'cle','ESP'),(107,'mapeh','MAPEH'),(108,'tle','TLE (ICT or COOKERY)');
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacherlist`
--

DROP TABLE IF EXISTS `teacherlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `teacherlist` (
  `idteacher` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `subjectid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idteacher`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacherlist`
--

LOCK TABLES `teacherlist` WRITE;
/*!40000 ALTER TABLE `teacherlist` DISABLE KEYS */;
INSERT INTO `teacherlist` VALUES (1,'Jarelle','Yanga',104),(2,'Jemille','Bognot',101);
/*!40000 ALTER TABLE `teacherlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id is system generated, we only need to add type, email and password',
  `type` tinyint(4) NOT NULL COMMENT '1=student\n2=teacher',
  `email` varchar(50) NOT NULL,
  `password` varchar(45) NOT NULL,
  `userid` int(11) DEFAULT NULL COMMENT 'this will be the \n  - id of stundent from studentlist table\n  - id of teacher from teacherlist table\n\nthis will identify the content of the homepage',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `iduser_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'mark@gmail.com','1234',1),(2,1,'john@gmail.com','1234',2),(3,2,'jarelle@gmail.com','1234',1),(4,2,'jemille@gmail.com','1234',2);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `v_teacher`
--

DROP TABLE IF EXISTS `v_teacher`;
/*!50001 DROP VIEW IF EXISTS `v_teacher`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `v_teacher` AS SELECT 
 1 AS `idteacher`,
 1 AS `FirstName`,
 1 AS `LastName`,
 1 AS `subjectid`,
 1 AS `subjectname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_userlist`
--

DROP TABLE IF EXISTS `v_userlist`;
/*!50001 DROP VIEW IF EXISTS `v_userlist`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `v_userlist` AS SELECT 
 1 AS `type`,
 1 AS `email`,
 1 AS `password`,
 1 AS `FirstName`,
 1 AS `LastName`,
 1 AS `userid`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `v_teacher`
--

/*!50001 DROP VIEW IF EXISTS `v_teacher`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_teacher` AS select `a`.`idteacher` AS `idteacher`,`a`.`FirstName` AS `FirstName`,`a`.`LastName` AS `LastName`,`a`.`subjectid` AS `subjectid`,`b`.`subjectname` AS `subjectname` from (`teacherlist` `a` join `subject` `b`) where (`b`.`idsubject` = `a`.`subjectid`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_userlist`
--

/*!50001 DROP VIEW IF EXISTS `v_userlist`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_userlist` AS select `a`.`type` AS `type`,`a`.`email` AS `email`,`a`.`password` AS `password`,`b`.`FirstName` AS `FirstName`,`b`.`LastName` AS `LastName`,`b`.`idstudent` AS `userid` from (`user` `a` join `studentlist` `b`) where ((`a`.`userid` = `b`.`idstudent`) and (`a`.`type` = 1)) union select `a`.`type` AS `type`,`a`.`email` AS `email`,`a`.`password` AS `password`,`b`.`FirstName` AS `FirstName`,`b`.`LastName` AS `LastName`,`b`.`idteacher` AS `userid` from (`user` `a` join `teacherlist` `b`) where ((`a`.`userid` = `b`.`idteacher`) and (`a`.`type` = 2)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-21 14:33:32
