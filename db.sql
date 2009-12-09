-- MySQL dump 10.13  Distrib 5.1.37, for apple-darwin10.0.0 (i386)
--
-- Host: localhost    Database: wolfkrow_diner
-- ------------------------------------------------------
-- Server version	5.1.37
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_number` varchar(20) DEFAULT 'demo' COMMENT 'obviously not secure',
  `cc_type` varchar(20) DEFAULT NULL COMMENT 'visa or mastercard',
  `cc_security_code` int(5) DEFAULT '0',
  `billing_name` varchar(50) DEFAULT NULL,
  `billing_address` text,
  `cc_exp_month` int(2) DEFAULT NULL,
  `cc_exp_year` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='probably not the proper way to store cc info';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` VALUES (4,'demo','visa',0,'demo name','123 address',12,2010),(3,'demo','visa',0,'demo name','123 address',12,2010),(5,'demo','visa',0,'demo name','123 address',12,2010),(6,'demo','visa',0,'demo name','123 address',12,2010),(7,'demo','visa',0,'demo name','123 address',12,2010),(8,'demo','visa',0,'demo name','123 address',12,2010),(9,'admin','mastercard',0,'The Admin','here',12,2010);

--
-- Table structure for table `meals`
--

DROP TABLE IF EXISTS `meals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time_started` datetime DEFAULT NULL,
  `time_finished` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='A record for each time a guest comes in for a meal.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` VALUES (17,15,'2009-12-07 22:35:42','2009-12-08 00:02:46'),(19,19,'2009-12-09 00:32:53',NULL),(18,15,'2009-12-08 00:50:47',NULL),(14,13,'2009-12-07 20:42:07',NULL),(16,12,'2009-12-07 20:45:31',NULL);

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `meal_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `total_price` decimal(7,2) DEFAULT '0.00',
  `activated_date` datetime DEFAULT NULL COMMENT 'mark when order is up',
  `filled` datetime DEFAULT NULL COMMENT 'boolean when order has been fulfilled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` VALUES (45,12,16,4,'1.23',NULL,NULL),(35,13,14,1,'5.00','2009-12-07 20:42:11',NULL),(36,13,14,4,'1.23',NULL,NULL),(44,15,17,6,'5.89','2009-12-07 23:32:40','2009-12-07 23:52:10'),(40,12,16,6,'5.89','2009-12-07 20:45:38','2009-12-08 00:09:35'),(43,12,16,2,'78.56','2009-12-08 00:09:35',NULL),(46,15,17,4,'1.23','2009-12-07 23:52:56',NULL),(47,15,17,2,'78.56',NULL,NULL),(48,15,17,5,'3.50',NULL,NULL),(49,15,18,6,'5.89','2009-12-08 00:50:55','2009-12-08 00:56:05');

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT '0' COMMENT 'vendor for whom the rating is for',
  `user_id` int(11) DEFAULT '0' COMMENT 'guest who is rating',
  `rating` tinyint(1) DEFAULT '0' COMMENT 'star rating 1 to 5 scale',
  `rated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--


--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `vendor_type_id` int(11) DEFAULT '0' COMMENT 'link additional service to vendor type',
  `price` decimal(7,2) DEFAULT '0.00' COMMENT 'only for non-standard additional services. currently fixed price for these services, could change to allow vendors to configure',
  `standard` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Additional services that can by offered by vendors.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

INSERT INTO `services` VALUES (1,'High Chair',NULL,1,'0.00',0),(2,'Do a Little Dance',NULL,1,'50.00',0),(3,'Birthday Song',NULL,2,'10.00',0),(4,'Extra Sides',NULL,3,'6.99',0),(5,'Specialty Drink',NULL,3,'3.29',0),(6,'Seat you at your choice of table or booth.',NULL,1,'0.00',1),(7,'Provide menus.',NULL,1,'0.00',1),(8,'Take your order.',NULL,2,'0.00',1),(9,'Make sure your glass is full.',NULL,2,'0.00',1),(10,'Bring you your order.',NULL,2,'0.00',1),(11,'Cook tonight’s meal.',NULL,3,'0.00',1),(12,'Clean up your mess.',NULL,4,'0.00',1),(13,'Take the tip.',NULL,4,'0.00',1);

--
-- Table structure for table `services_for_orders`
--

DROP TABLE IF EXISTS `services_for_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services_for_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT '0' COMMENT 'from orders table',
  `service_id` int(11) DEFAULT '0' COMMENT 'from services table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COMMENT='Lookup between orders and additional services requested.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_for_orders`
--

INSERT INTO `services_for_orders` VALUES (77,49,7),(76,49,6),(75,48,13),(74,48,12),(73,47,11),(72,46,10),(71,46,9),(70,46,8),(46,35,6),(47,35,7),(48,36,8),(49,36,9),(50,36,10),(64,43,11),(69,45,10),(68,45,9),(67,45,8),(66,44,7),(65,44,6),(57,40,6),(58,40,7);

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giver_account` int(11) DEFAULT NULL COMMENT 'account id of giver of money',
  `recipient_account` int(11) DEFAULT NULL COMMENT 'account id of recipient of funds',
  `order_id` int(11) DEFAULT NULL COMMENT 'careful to distinguish giver when taking total cost of meals',
  `amount` decimal(7,2) DEFAULT '0.00',
  `sale_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='monetary transactions between guests, vendors, and manager.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` VALUES (45,6,8,49,'5.89','2009-12-08 00:50:55'),(44,6,4,48,'3.50','2009-12-08 00:02:46'),(43,6,7,47,'78.56','2009-12-07 23:53:07'),(42,6,5,46,'1.23','2009-12-07 23:52:56'),(39,3,7,43,'78.56','2009-12-07 23:01:09'),(40,6,8,44,'5.89','2009-12-07 23:32:40'),(41,3,5,45,'1.23','2009-12-07 23:33:52'),(30,3,3,34,'5.00','2009-12-07 20:40:20'),(31,4,3,35,'5.00','2009-12-07 20:42:11'),(32,4,5,36,'1.23','2009-12-07 20:42:22'),(33,3,6,37,'8.52','2009-12-07 20:45:17'),(34,3,7,38,'78.56','2009-12-07 20:45:19'),(35,3,4,39,'3.50','2009-12-07 20:45:23'),(36,3,8,40,'5.89','2009-12-07 20:45:38');

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL COMMENT 'md5 encryption',
  `user_type` varchar(20) DEFAULT NULL COMMENT 'guest, vendor, or manager',
  `account_id` int(11) DEFAULT NULL,
  `flag` varchar(1) DEFAULT NULL COMMENT 'to mark special event like needing to change password',
  `deactive` datetime DEFAULT NULL COMMENT 'to expire user account',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES (17,'Joe Dirt','joe@dirt.com','8ff32489f92f33416694be8fdc2d4c22','vendor',8,NULL,NULL),(16,'Wolfgang Puck','wolfgang@puck.com','3720f54e919b22cce392b05de57102dd','vendor',7,NULL,NULL),(15,'Test Person','test@test.com','098f6bcd4621d373cade4e832627b4f6','guest',6,NULL,NULL),(14,'Lincoln Pomeroy','lincoln@byu.edu','098f6bcd4621d373cade4e832627b4f6','guest',5,NULL,NULL),(13,'Michelle Pomeroy','michellepomeroy@gmail.com','58d2770722ebccc66ef50bbf16021332','guest',4,NULL,NULL),(12,'Miles Pomeroy','miles@byu.net','58d2770722ebccc66ef50bbf16021332','guest',3,NULL,NULL),(19,'The Admin','admin@admin.com','21232f297a57a5a743894a0e4a801fc3','manager',9,NULL,NULL);

--
-- Table structure for table `vendor_applications`
--

DROP TABLE IF EXISTS `vendor_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `vendor_name` varchar(50) DEFAULT NULL,
  `vendor_type_id` int(11) DEFAULT NULL,
  `vendor_qualifications` mediumtext,
  `offer` varchar(20) DEFAULT NULL COMMENT 'hire or deny',
  `offer_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_applications`
--

INSERT INTO `vendor_applications` VALUES (1,'Neil Patrick Harris','neil@harris.com','Neil',1,'I starred in \"Dr Horrible’s Sing-Along Blog.\"',NULL,NULL),(4,'Scott Baio','scott@baio.com','Scotty',2,'I’m Charles and I’m in charge.\n\nOf our days.\n\nAnd our nights.',NULL,NULL),(5,'Ricky Stratton','ricky@stratton.com','Ricky Schroder',4,'Silver spoons, baby!',NULL,NULL);

--
-- Table structure for table `vendor_types`
--

DROP TABLE IF EXISTS `vendor_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'like host, waiter, etc',
  `description` text,
  `workflow_order` int(2) DEFAULT '99' COMMENT 'could be used to allow configuration of vendor order in meal workflow',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_types`
--

INSERT INTO `vendor_types` VALUES (1,'host',NULL,1),(2,'waiter',NULL,2),(3,'cook',NULL,3),(4,'busboy',NULL,4);

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL COMMENT 'from vendor_types table',
  `qualifications` mediumtext,
  `price` decimal(7,2) DEFAULT '0.00' COMMENT 'for standard package',
  `user_id` int(11) DEFAULT NULL COMMENT 'user id associated with vendor can change',
  `deactive` datetime DEFAULT NULL COMMENT 'used to fire (1) a vendor',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='vendor information for public listing';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` VALUES (1,'Miles Pomeroy',1,'these are my qualifications','5.00',12,NULL),(2,'Wolfgang Puck',3,'Spago Beverly Hills received a James Beard Foundation Outstanding Service Award in 2005. In November 2007 it was awarded 2 Michelin Stars in the 2008 Los Angeles Michelin Guide.\n\nIn 1993, Spago Hollywood was inducted into the Nation\'s Restaurant News Fine Dining Hall of Fame. The next year it received the James Beard Restaurant of the Year Award.\n\nIn 2002 Puck received the 2001-2002 Daytime Emmy Award for Outstanding Service Show, Wolfgang Puck.','78.56',16,NULL),(3,'Test Vendor',2,'I have no qualifications.','8.52',15,NULL),(4,'Lincoln Pomeroy',2,'I’m a baby.','1.23',14,NULL),(5,'Michelle',4,'I love to clean.','3.50',13,NULL),(6,'Joe \"the host\" Dirt',1,'yeah baby','5.89',17,NULL);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-12-09 12:08:16
