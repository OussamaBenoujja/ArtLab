-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: artbase
-- ------------------------------------------------------
-- Server version	8.0.31

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
-- Table structure for table `articlecategories`
--

DROP TABLE IF EXISTS `articlecategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articlecategories` (
  `ArticleCategoryID` int NOT NULL AUTO_INCREMENT,
  `ArticleID` int NOT NULL,
  `CategoryID` int NOT NULL,
  PRIMARY KEY (`ArticleCategoryID`),
  KEY `articlecategories_ibfk_1` (`ArticleID`),
  KEY `articlecategories_ibfk_2` (`CategoryID`),
  CONSTRAINT `articlecategories_ibfk_1` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `articlecategories_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articlecategories`
--

LOCK TABLES `articlecategories` WRITE;
/*!40000 ALTER TABLE `articlecategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `articlecategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `articledetailsview`
--

DROP TABLE IF EXISTS `articledetailsview`;
/*!50001 DROP VIEW IF EXISTS `articledetailsview`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `articledetailsview` AS SELECT 
 1 AS `ArticleID`,
 1 AS `Title`,
 1 AS `InnerText`,
 1 AS `BannerImage`,
 1 AS `ArticleCreatedAt`,
 1 AS `AuthorID`,
 1 AS `AuthorName`,
 1 AS `AuthorEmail`,
 1 AS `VoteType`,
 1 AS `VoteCreatedAt`,
 1 AS `CommentID`,
 1 AS `CommentText`,
 1 AS `CommentCreatedAt`,
 1 AS `CommenterID`,
 1 AS `CommenterName`,
 1 AS `CommenterEmail`,
 1 AS `CategoryID`,
 1 AS `CategoryName`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `ArticleID` int NOT NULL AUTO_INCREMENT,
  `AuthorID` int DEFAULT NULL,
  `BannerImage` varchar(255) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `InnerText` text NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ArticleID`),
  KEY `fk_author_id` (`AuthorID`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  CONSTRAINT `fk_author_id` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authordetails`
--

DROP TABLE IF EXISTS `authordetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authordetails` (
  `AuthorDetailID` int NOT NULL AUTO_INCREMENT,
  `AuthorID` int DEFAULT NULL,
  `FollowersCount` int DEFAULT '0',
  PRIMARY KEY (`AuthorDetailID`),
  KEY `authordetails_ibfk_1` (`AuthorID`),
  CONSTRAINT `authordetails_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authordetails`
--

LOCK TABLES `authordetails` WRITE;
/*!40000 ALTER TABLE `authordetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `authordetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) NOT NULL,
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `CategoryName` (`CategoryName`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `CommentID` int NOT NULL AUTO_INCREMENT,
  `ArticleID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `CommentText` text NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CommentID`),
  KEY `fk_article_id` (`ArticleID`),
  KEY `fk_user_id` (`UserID`),
  CONSTRAINT `fk_article_id` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `followers`
--

DROP TABLE IF EXISTS `followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `followers` (
  `FollowerID` int NOT NULL AUTO_INCREMENT,
  `MemberID` int DEFAULT NULL,
  `AuthorID` int DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`FollowerID`),
  UNIQUE KEY `MemberID` (`MemberID`,`AuthorID`),
  KEY `followers_ibfk_2` (`AuthorID`),
  CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `followers`
--

LOCK TABLES `followers` WRITE;
/*!40000 ALTER TABLE `followers` DISABLE KEYS */;
/*!40000 ALTER TABLE `followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likedarticles`
--

DROP TABLE IF EXISTS `likedarticles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likedarticles` (
  `LikedArticleID` int NOT NULL AUTO_INCREMENT,
  `UserID` int DEFAULT NULL,
  `ArticleID` int DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LikedArticleID`),
  UNIQUE KEY `UserID` (`UserID`,`ArticleID`),
  KEY `likedarticles_ibfk_2` (`ArticleID`),
  CONSTRAINT `likedarticles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  CONSTRAINT `likedarticles_ibfk_2` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likedarticles`
--

LOCK TABLES `likedarticles` WRITE;
/*!40000 ALTER TABLE `likedarticles` DISABLE KEYS */;
/*!40000 ALTER TABLE `likedarticles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` enum('Admin','Member','Author') NOT NULL,
  `DateOfJoining` datetime DEFAULT CURRENT_TIMESTAMP,
  `Birthday` date NOT NULL,
  `Bio` text,
  `token_auth` text,
  `ProfileImage` varchar(255) DEFAULT '../assets/img/default.jpg',
  `IsBanned` enum('yes','no') DEFAULT 'no',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `VoteID` int NOT NULL AUTO_INCREMENT,
  `ArticleID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `VoteType` enum('Upvote','Downvote') NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`VoteID`),
  UNIQUE KEY `UserID` (`UserID`,`ArticleID`),
  KEY `votes_ibfk_1` (`ArticleID`),
  CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `articledetailsview`
--

/*!50001 DROP VIEW IF EXISTS `articledetailsview`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = cp850 */;
/*!50001 SET character_set_results     = cp850 */;
/*!50001 SET collation_connection      = cp850_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `articledetailsview` AS select `articles`.`ArticleID` AS `ArticleID`,`articles`.`Title` AS `Title`,`articles`.`InnerText` AS `InnerText`,`articles`.`BannerImage` AS `BannerImage`,`articles`.`CreatedAt` AS `ArticleCreatedAt`,`users`.`UserID` AS `AuthorID`,concat(`users`.`FirstName`,' ',`users`.`LastName`) AS `AuthorName`,`users`.`Email` AS `AuthorEmail`,`votes`.`VoteType` AS `VoteType`,`votes`.`CreatedAt` AS `VoteCreatedAt`,`comments`.`CommentID` AS `CommentID`,`comments`.`CommentText` AS `CommentText`,`comments`.`CreatedAt` AS `CommentCreatedAt`,`commenters`.`UserID` AS `CommenterID`,concat(`commenters`.`FirstName`,' ',`commenters`.`LastName`) AS `CommenterName`,`commenters`.`Email` AS `CommenterEmail`,`categories`.`CategoryID` AS `CategoryID`,`categories`.`CategoryName` AS `CategoryName` from ((((((`articles` left join `users` on((`articles`.`AuthorID` = `users`.`UserID`))) left join `votes` on((`articles`.`ArticleID` = `votes`.`ArticleID`))) left join `comments` on((`articles`.`ArticleID` = `comments`.`ArticleID`))) left join `users` `commenters` on((`comments`.`UserID` = `commenters`.`UserID`))) left join `articlecategories` on((`articles`.`ArticleID` = `articlecategories`.`ArticleID`))) left join `categories` on((`articlecategories`.`CategoryID` = `categories`.`CategoryID`))) */;
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

-- Dump completed on 2025-01-06 12:58:18
