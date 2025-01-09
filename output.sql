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
 1 AS `VoteTypes`,
 1 AS `Comments`,
 1 AS `Categories`,
 1 AS `Tags`*/;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (5,27,'../assets/img/677e8a4200edb.jpg','this is the title','The Relationship Between Cheese and Cosmic Radiation: A Curious Intersection of Science and Culture\r\n\r\nCheese and cosmic radiationÔÇöon the surface, these topics seem worlds apart, one rooted in the culinary arts and the other in astrophysics. Yet, delving deeper, the peculiar intersection between these two fields unveils fascinating scientific, historical, and cultural connections. From cheese\'s microbial life adapting to radiation, to its metaphoric presence in space exploration folklore, the relationship between cheese and cosmic radiation is a curious one worth exploring.\r\n\r\nCheese as a Biological System\r\nCheese is a product of fermentation, reliant on bacteria, yeast, and molds to develop its unique flavors and textures. These microorganisms thrive under specific conditions, including controlled levels of temperature, moisture, and pH. However, what happens when cheese, or the microbes within it, is exposed to cosmic radiationÔÇöa potent form of high-energy particles originating from outer space?\r\n\r\nCosmic radiation, comprising gamma rays, X-rays, and energetic particles such as protons, neutrons, and heavy ions, poses challenges for biological systems. These particles can disrupt molecular structures, causing mutations or even complete cellular destruction. For cheese, the exposure of its microbial colonies to cosmic radiation could fundamentally alter the fermentation process. Scientists have begun studying how such exposure might affect the microbiota within cheese, particularly in the context of long-duration space missions.\r\n\r\nCheese in Space: A Literal Experiment\r\nIn 2019, researchers sent cheese to the International Space Station (ISS) as part of an experiment to study food preservation and microbial behavior in space. The study aimed to determine how prolonged exposure to microgravity and cosmic radiation impacts cheese\'s texture, flavor, and microbial activity. Results indicated that radiation-induced stress altered the metabolism of certain bacteria, which, in turn, affected the cheese\'s flavor profile.\r\n\r\nThis research has implications beyond culinary curiosity. Understanding how cosmic radiation impacts cheese could offer insights into preserving perishable food items during interstellar travel, a critical concern for future Mars missions or deep-space colonization efforts.\r\n\r\nMicrobial Adaptations and Cosmic Radiation\r\nSome microorganisms found in cheese, such as Penicillium molds and lactic acid bacteria, have shown remarkable resilience to radiation. These microbes might offer clues to developing radiation-resistant strains for biotechnological applications. Scientists hypothesize that microbial colonies exposed to moderate radiation doses could develop adaptive mutations, potentially making them more robust. This discovery has implications for food safety, as radiation is often used as a sterilization method.\r\n\r\nMoreover, radiation-resistant microbes could be used in synthetic biology to create bioengineered organisms capable of surviving in extraterrestrial environments. These organisms could aid in food production for astronauts or even contribute to terraforming efforts on other planets.\r\n\r\nCosmic Cheese Myths and Cultural Significance\r\nBeyond scientific inquiry, cheese holds a whimsical place in space-related folklore. The ancient idea that the Moon is made of cheeseÔÇöa concept popularized in childrenÔÇÖs stories and even referenced in early scientific textsÔÇöillustrates humanity\'s long-standing fascination with cheese and the cosmos. While no one seriously believes the Moon is a dairy product, this myth highlights the symbolic link between cheese and the universe.\r\n\r\nThe \"cosmic cheese\" metaphor has inspired numerous cultural creations, from art installations to speculative fiction. In some science fiction stories, cheese is humorously depicted as a universal delicacy sought after by extraterrestrial civilizations. This playful narrative underscores how deeply cheese is embedded in human culture, even in contexts as far-reaching as space.\r\n\r\nRadiation as a Cheese Preservative\r\nRadiation has long been used on Earth as a method of food preservation. Gamma irradiation, for instance, is employed to sterilize cheese and extend its shelf life. This process eliminates harmful bacteria and pathogens without significantly altering the cheese\'s flavor or texture. However, when cosmic radiation is introduced into the equation, the preservation effects are more unpredictable.\r\n\r\nThe high-energy particles in cosmic radiation can penetrate deeper into the molecular structure of cheese, potentially affecting its protein and fat content. This raises questions about the feasibility of using cosmic radiation as a natural preservative for food storage in space. Ongoing experiments aim to determine whether these changes are beneficial or detrimental, paving the way for innovations in space-based food technologies.\r\n\r\nCheese as a Cosmic Radiation Shield\r\nInterestingly, cheese might have practical applications in shielding against cosmic radiation. While not as effective as lead or other heavy materials, the dense composition of certain cheeses could theoretically absorb low-energy radiation particles. This idea, while unconventional, is not entirely implausible. Scientists have experimented with various organic materials, including water and plastics, to create lightweight, flexible radiation shields. Could cheese serve as a dual-purpose food and protective material for astronauts? Although this idea remains speculative, it highlights the creative thinking spurred by interdisciplinary research.\r\n\r\nCheese in the Context of Astrobiology\r\nThe microbial life in cheese offers a model for studying extremophilesÔÇöorganisms that thrive in extreme environments. Cosmic radiation is one of the harshest conditions any life form can endure, and understanding how cheese\'s microbial inhabitants respond to such exposure could provide clues about the limits of life. This research might even inform the search for extraterrestrial life, helping scientists identify biomarkers or adapt life-support systems for alien environments.\r\n\r\nThe Future of Cheese and Cosmic Radiation Research\r\nAs humanity ventures further into space, the interplay between cheese and cosmic radiation will likely continue to be explored. Potential areas of future research include:\r\n\r\nCheese Production in Space: Developing techniques to produce cheese in extraterrestrial environments using locally available resources, such as Martian or lunar water and soil.\r\n\r\nRadiation-Enhanced Fermentation: Investigating whether controlled exposure to cosmic radiation could yield new cheese varieties with unique flavors and textures.\r\n\r\nMicrobial Engineering: Harnessing radiation-resistant microbes from cheese to develop biotechnologies for space exploration.\r\n\r\nConclusion\r\nThe relationship between cheese and cosmic radiation is a fascinating confluence of science, culture, and speculation. From its role as a subject of microbial research to its metaphorical significance in human imagination, cheese bridges the gap between the terrestrial and the cosmic. As we look to the stars, the humble wheel of cheese may hold surprising secrets, inspiring innovation and connecting us to the vast universe in unexpected ways.','2025-01-08 15:22:58'),(6,27,'../assets/img/677fb3a572c5b.jpg','new article','thisisid ├ásidisqjkdjkbxcbhbskjckxjkwxkcihcbdcjkd','2025-01-09 12:31:49');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articletags`
--

DROP TABLE IF EXISTS `articletags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articletags` (
  `ArticleTagID` int NOT NULL AUTO_INCREMENT,
  `ArticleID` int NOT NULL,
  `TagID` int NOT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ArticleTagID`),
  UNIQUE KEY `unique_article_tag` (`ArticleID`,`TagID`),
  KEY `TagID` (`TagID`),
  CONSTRAINT `articletags_ibfk_1` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `articletags_ibfk_2` FOREIGN KEY (`TagID`) REFERENCES `tags` (`TagID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articletags`
--

LOCK TABLES `articletags` WRITE;
/*!40000 ALTER TABLE `articletags` DISABLE KEYS */;
/*!40000 ALTER TABLE `articletags` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (5,'physical arts');
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
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `TagID` int NOT NULL AUTO_INCREMENT,
  `TagName` varchar(255) NOT NULL,
  `Description` text,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TagID`),
  UNIQUE KEY `TagName` (`TagName`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'this is a tag lol','this is a random tag mohaha','2025-01-09 11:30:06'),(4,'another tag','new tag','2025-01-09 11:31:17');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (17,'OussamaBenoujja','Oussama','Benoujja','osama2code79@gmail.com','$2y$12$oOVZA6dpG22G2/iWHxouEuGX5vOwvvh6JIe4ghUQSd4exSR14x0Oy','Member','2025-01-06 17:12:36','2025-01-23','I am Amazing please say that i am hhhhh','d20fe396f071981b582182150d4a8829','../assets/img/677c00f427a4d.jpg','no'),(27,'osamaXDDDD','Oussama','Benoujja','ahmed01@gmail.com','$2y$12$6Yz7jdjGoRzKJblIcpTlku7uLbIXZAevHiDJxnmP0xTYfAdsdFFdC','Author','2025-01-08 15:13:05','2025-01-04','No Bio','a35197fce16c6587d1363a78d0e0fae9','../assets/img/677e87f10142e.gif','no');
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
/*!50001 VIEW `articledetailsview` AS select `articles`.`ArticleID` AS `ArticleID`,`articles`.`Title` AS `Title`,`articles`.`InnerText` AS `InnerText`,`articles`.`BannerImage` AS `BannerImage`,`articles`.`CreatedAt` AS `ArticleCreatedAt`,`users`.`UserID` AS `AuthorID`,concat(`users`.`FirstName`,' ',`users`.`LastName`) AS `AuthorName`,`users`.`Email` AS `AuthorEmail`,group_concat(distinct `votes`.`VoteType` separator ',') AS `VoteTypes`,group_concat(distinct `comments`.`CommentText` separator ',') AS `Comments`,group_concat(distinct `categories`.`CategoryName` separator ',') AS `Categories`,group_concat(distinct `tags`.`TagName` separator ',') AS `Tags` from (((((((`articles` left join `users` on((`articles`.`AuthorID` = `users`.`UserID`))) left join `votes` on((`articles`.`ArticleID` = `votes`.`ArticleID`))) left join `comments` on((`articles`.`ArticleID` = `comments`.`ArticleID`))) left join `articlecategories` on((`articles`.`ArticleID` = `articlecategories`.`ArticleID`))) left join `categories` on((`articlecategories`.`CategoryID` = `categories`.`CategoryID`))) left join `articletags` on((`articles`.`ArticleID` = `articletags`.`ArticleID`))) left join `tags` on((`articletags`.`TagID` = `tags`.`TagID`))) group by `articles`.`ArticleID`,`articles`.`Title`,`articles`.`InnerText`,`articles`.`BannerImage`,`articles`.`CreatedAt`,`users`.`UserID`,`users`.`FirstName`,`users`.`LastName`,`users`.`Email` */;
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

-- Dump completed on 2025-01-09 15:38:43
