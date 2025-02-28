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
  KEY `ArticleID` (`ArticleID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `articlecategories_ibfk_1` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `articlecategories_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articlecategories`
--

LOCK TABLES `articlecategories` WRITE;
/*!40000 ALTER TABLE `articlecategories` DISABLE KEYS */;
INSERT INTO `articlecategories` VALUES (4,2,1),(5,2,3);
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
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`),
  CONSTRAINT `fk_author_id` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (2,2,'../assets/img677adafa930e7.png','i am trying to test if this is going to work or not','this is a new article to be created by me the one and only osama benoujja the greate the amazing the most wonderful so things dont get any harder than this ','2025-01-05 20:18:18'),(4,2,'../assets/img/677b996f569d8.jpg','LOOOOOOOL','ok this is a new article made by my se\r\nlook at me i am soo good at writing stuff\r\n\r\nArt and AGI: A New Frontier in Creativity\r\n\r\nArtificial General Intelligence (AGI) represents a transformative leap in technology, promising machines that can think, learn, and reason at a level comparable to human intelligence. While much of the discourse around AGI focuses on its potential in fields like healthcare, finance, and logistics, its implications for art and creativity are equally profound. As AGI evolves, it challenges our understanding of what it means to create, appreciate, and define art, opening up a new frontier in the intersection of technology and human expression.\r\n\r\nThe Evolution of Art and Technology\r\nArt has always been a reflection of the tools and technologies available to humanity. From the earliest cave paintings to the Renaissance mastery of perspective, from the invention of photography to the rise of digital art, each technological advancement has expanded the boundaries of creativity. AGI represents the next step in this evolution, offering tools that can not only assist artists but also generate art independently.\r\n\r\nUnlike narrow AI, which is designed for specific tasks like image recognition or music composition, AGI possesses the ability to generalize knowledge across domains. This means it can understand context, emotion, and cultural nuanceÔÇöelements that are critical to the creation of meaningful art. AGI can analyze vast datasets of artistic works, identify patterns, and synthesize new ideas, potentially creating art that resonates deeply with human audiences.\r\n\r\nAGI as a Collaborative Partner\r\nOne of the most exciting possibilities of AGI in art is its potential as a collaborative partner. Artists could work alongside AGI systems to explore new creative territories, combining human intuition and emotion with machine precision and scalability. For example, an AGI could suggest novel color palettes, generate intricate patterns, or even propose entirely new artistic styles based on an artistÔÇÖs preferences and historical influences.\r\n\r\nThis collaboration could democratize art, making it more accessible to people who may not have traditional artistic skills. Imagine a world where anyone can describe their vision to an AGI, and the system translates it into a painting, sculpture, or musical composition. This could lead to an explosion of creativity, as more people are empowered to express themselves artistically.\r\n\r\nAGI as an Independent Creator\r\nBeyond collaboration, AGI has the potential to create art autonomously. This raises intriguing questions about authorship and originality. If an AGI generates a painting or writes a symphony, who owns the work? Is it the creator of the AGI, the user who prompted the creation, or the AGI itself? These questions challenge our legal and philosophical frameworks, forcing us to reconsider the nature of creativity and intellectual property.\r\n\r\nMoreover, AGI-generated art could push the boundaries of what we consider aesthetically pleasing or meaningful. By analyzing global artistic trends and cultural histories, AGI might produce works that blend disparate styles or introduce entirely new forms of expression. This could lead to a renaissance of innovation in the art world, as human artists are inspired byÔÇöor compete withÔÇöAGI creations.\r\n\r\nEthical and Cultural Considerations\r\nThe integration of AGI into the art world is not without its challenges. Ethical concerns about bias, cultural appropriation, and the commodification of art must be addressed. For instance, if an AGI is trained on a dataset dominated by Western art, it may inadvertently perpetuate biases or overlook the richness of non-Western traditions. Ensuring that AGI systems are trained on diverse and inclusive datasets will be crucial to fostering a global artistic dialogue.\r\n\r\nAdditionally, the rise of AGI-generated art could disrupt traditional art markets and economies. If AGI can produce high-quality art at scale, what happens to human artists whose livelihoods depend on their craft? Balancing the benefits of AGI with the need to support human creativity will require thoughtful regulation and innovation.\r\n\r\nThe Future of Art and AGI\r\nAs AGI continues to develop, its impact on art will likely be both profound and unpredictable. It has the potential to redefine creativity, blurring the lines between human and machine, and challenging our assumptions about what art is and who can create it. At the same time, it offers an opportunity to deepen our appreciation for the uniquely human qualities that art embodiesÔÇöemotion, intention, and the ability to connect across time and space.\r\n\r\nUltimately, the relationship between art and AGI will be shaped by how we choose to use this technology. By embracing AGI as a tool for exploration and expression, we can unlock new possibilities for creativity while preserving the essence of what makes art a deeply human endeavor. The future of art and AGI is not just about machines creating artÔÇöitÔÇÖs about how we, as a society, choose to integrate this powerful technology into our cultural fabric.','2025-01-06 09:49:53');
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
  KEY `AuthorID` (`AuthorID`),
  CONSTRAINT `authordetails_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`)
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
INSERT INTO `categories` VALUES (4,'Catagory test 1'),(1,'physical arts'),(3,'some really weird type of art');
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
  KEY `fk_user_id` (`UserID`),
  KEY `fk_article_id` (`ArticleID`),
  CONSTRAINT `fk_article_id` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`ArticleID`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  KEY `AuthorID` (`AuthorID`),
  CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `users` (`UserID`),
  CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`)
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
  CONSTRAINT `likedarticles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Osama','Oussama','Benoujja','osama2code79@gmail.com','$2y$12$k4GLc2GsxpNc456mqIHyYes5K1MTPIRzVdEkGV0/l9Yex401doOG2','Member','2025-01-03 03:53:12','2025-01-22','Was the making of this Project that hard.\r\nShort Awnser Yes','6c65e9788d73f5734afbfe1837b09d85','../assets/img/Ren_amamiya_.jpg','no'),(2,'youness','youness','bousmhali','bouchta01@gmail.com','$2y$12$shobpkui2tbR/igY8xPhkO3wDu7uw75a.N8VRxPh7Bj9I7AZBORGW','Author','2025-01-03 10:31:05','2025-01-16','No Bio','f47972acc12d249bd4eb7a4c3d1a4c15','../assets/img/default.jpg','no'),(3,'admin_username','AdminFirstName','AdminLastName','admin@example.com','$2y$10$T7rbB9hkOetwF8GQikzrceuD5I7pSNQwaSGiA0gZkjeMc4IXHMmXy','Admin','2025-01-05 20:35:56','1980-01-01','This is the admin bio.','77f8d66dadcde748cd4d4397f2ecb6b7','../assets/img/default.jpg','no');
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
  CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (8,2,2,'Upvote','2025-01-06 09:46:55');
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

-- Dump completed on 2025-01-06  9:57:02
