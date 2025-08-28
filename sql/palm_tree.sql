CREATE DATABASE  IF NOT EXISTS `palm_tree` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `palm_tree`;
-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: 127.0.0.1    Database: palm_tree
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.21-MariaDB

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
-- Table structure for table `about`
--

DROP TABLE IF EXISTS `about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about` (
  `about_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'About ID (Surrogate Key)',
  `about_developer` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'About Author',
  `about_contact` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'About Contact',
  `about_website` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'About Website',
  PRIMARY KEY (`about_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about`
--

LOCK TABLES `about` WRITE;
/*!40000 ALTER TABLE `about` DISABLE KEYS */;
INSERT INTO `about` VALUES (1,'Scott Grivner','scott.grivner@gmail.com','https://www.scottgrivner.dev');
/*!40000 ALTER TABLE `about` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company` (
  `comp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Company ID (Primary Key)',
  `comp_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Title',
  `comp_subtitle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Subtitle',
  `comp_owner` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Owner Name',
  `comp_address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Address',
  `comp_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Phone',
  `comp_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Email',
  `comp_website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Website',
  `comp_google_place_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Google Place ID',
  `comp_google_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Google Business URL',
  `comp_facebook_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Facebook Business URL',
  `comp_x_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company X Business URL',
  `comp_linkedin_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company LinkedIn Business URL',
  `comp_instagram_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Instagram Business URL',
  `comp_youtube_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company YouTube Business URL',
  `comp_amazon_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Amazon Business URL',
  `comp_pinterest_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Pinterest Business URL',
  `comp_etsy_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Etsy Business URL',
  `comp_shopify_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company Shopify Business URL',
  `comp_hubspot_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Company HubSpot Business URL',
  `comp_created` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Company Date/Time Created',
  `comp_edited` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Company Date/Time Edited',
  PRIMARY KEY (`comp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'Your Company Name','Your Company Description',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `integrations`
--

DROP TABLE IF EXISTS `integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE `integrations` (
  `integration_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Integration ID (Primary Key)',
  `integration_name` VARCHAR(50) NOT NULL COMMENT 'Integration Name (e.g., HubSpot)',
  `api_key` TEXT NOT NULL COMMENT 'API Key or Token (encrypted or hidden in UI)',
  `endpoint_url` TEXT NOT NULL COMMENT 'API Endpoint URL',
  `enabled` TINYINT(1) DEFAULT 1 COMMENT 'Enable/Disable Integration',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Timestamp',
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last Updated Timestamp',
  PRIMARY KEY (`integration_id`),
  UNIQUE KEY `unique_integration_name` (`integration_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `integrations`
--

LOCK TABLES `integrations` WRITE;
/*!40000 ALTER TABLE `integrations` DISABLE KEYS */;
INSERT INTO `integrations` (`integration_name`, `api_key`, `endpoint_url`, `enabled`, `created_at`) 
VALUES 
('HubSpot', '', '', 1, NOW());
/*!40000 ALTER TABLE `integrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Customer ID (Primary Key)',
  `cust_first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Customer First Name',
  `cust_last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Customer Last Name',
  `cust_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Customer Email',
  `cust_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Customer Phone',
  `cust_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Customer Notes',
  `cust_email_sent` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Customer Email Sent (0=Not Sent, 1=Sent)',
  `cust_created` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Customer Date/Time Created',
  `cust_edited` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Customer Date/Time Edited',
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Email Template ID (Primary Key)',
  `mail_smtp` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email SMTP Server',
  `mail_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email From Address',
  `mail_from_password` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email From Password',
  `mail_cc` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email Carbon Copy Address',
  `mail_bcc` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email Blind Carbon Copy Address',
  `mail_subject` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email Template Subject',
  `mail_body` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email Template Body',
  `mail_created` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Email Template Date/Time Created',
  `mail_edited` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Email Template Date/Time Edited',
  PRIMARY KEY (`mail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email`
--

LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;
INSERT INTO `email` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `v_customer_email_duplicate_audit`
--

DROP TABLE IF EXISTS `v_customer_email_duplicate_audit`;
/*!50001 DROP VIEW IF EXISTS `v_customer_email_duplicate_audit`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_customer_email_duplicate_audit` AS SELECT 
 1 AS `cust_email`,
 1 AS `Count`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_customer_email_format_audit`
--

DROP TABLE IF EXISTS `v_customer_email_format_audit`;
/*!50001 DROP VIEW IF EXISTS `v_customer_email_format_audit`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_customer_email_format_audit` AS SELECT 
 1 AS `cust_id`,
 1 AS `cust_first_name`,
 1 AS `cust_last_name`,
 1 AS `cust_email`,
 1 AS `cust_phone`,
 1 AS `cust_notes`,
 1 AS `cust_email_sent`,
 1 AS `cust_created`,
 1 AS `cust_edited`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_email_build`
--

DROP TABLE IF EXISTS `v_email_build`;
/*!50001 DROP VIEW IF EXISTS `v_email_build`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_email_build` AS SELECT 
 1 AS `comp_title`,
 1 AS `comp_subtitle`,
 1 AS `comp_owner`,
 1 AS `comp_address`,
 1 AS `comp_phone`,
 1 AS `comp_email`,
 1 AS `comp_website`,
 1 AS `comp_google_place_id`,
 1 AS `comp_google_url`,
 1 AS `comp_facebook_url`,
 1 AS `comp_x_url`,
 1 AS `comp_linkedin_url`,
 1 AS `comp_instagram_url`,
 1 AS `comp_youtube_url`,
 1 AS `comp_amazon_url`,
 1 AS `comp_pinterest_url`,
 1 AS `comp_etsy_url`,
 1 AS `comp_shopify_url`,
 1 AS `mail_smtp`,
 1 AS `mail_from`,
 1 AS `mail_from_password`,
 1 AS `mail_cc`,
 1 AS `mail_bcc`,
 1 AS `mail_subject`,
 1 AS `mail_body`,
 1 AS `cust_id`,
 1 AS `cust_first_name`,
 1 AS `cust_last_name`,
 1 AS `cust_email`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_email_template`
--

DROP TABLE IF EXISTS `v_email_template`;
/*!50001 DROP VIEW IF EXISTS `v_email_template`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_email_template` AS SELECT 
 1 AS `mail_id`,
 1 AS `mail_smtp`,
 1 AS `mail_from`,
 1 AS `mail_from_password`,
 1 AS `mail_cc`,
 1 AS `mail_bcc`,
 1 AS `mail_subject`,
 1 AS `mail_body`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'palm_tree'
--

--
-- Dumping routines for database 'palm_tree'
--

--
-- Final view structure for view `v_customer_email_duplicate_audit`
--

/*!50001 DROP VIEW IF EXISTS `v_customer_email_duplicate_audit`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_customer_email_duplicate_audit` AS select `customers`.`cust_email` AS `cust_email`,count(0) AS `Count` from `customers` where `customers`.`cust_email` <> '' group by `customers`.`cust_email` having count(0) > 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_customer_email_format_audit`
--

/*!50001 DROP VIEW IF EXISTS `v_customer_email_format_audit`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_customer_email_format_audit` AS select `customers`.`cust_id` AS `cust_id`,`customers`.`cust_first_name` AS `cust_first_name`,`customers`.`cust_last_name` AS `cust_last_name`,`customers`.`cust_email` AS `cust_email`,`customers`.`cust_phone` AS `cust_phone`,`customers`.`cust_notes` AS `cust_notes`,`customers`.`cust_email_sent` AS `cust_email_sent`,`customers`.`cust_created` AS `cust_created`,`customers`.`cust_edited` AS `cust_edited` from `customers` where !(`customers`.`cust_email` regexp '^[A-Z0-9._%-]+@[A-Z0-9.-]+.[A-Z]{2,4}$') and `customers`.`cust_email` <> '' */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_email_build`
--

/*!50001 DROP VIEW IF EXISTS `v_email_build`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_email_build` AS select `company`.`comp_title` AS `comp_title`,`company`.`comp_subtitle` AS `comp_subtitle`,`company`.`comp_owner` AS `comp_owner`,`company`.`comp_address` AS `comp_address`,`company`.`comp_phone` AS `comp_phone`,`company`.`comp_email` AS `comp_email`, `company`.`comp_website` AS `comp_website`, `company`.`comp_google_place_id` AS `comp_google_place_id`,`company`.`comp_google_url` AS `comp_google_url`,`company`.`comp_facebook_url` AS `comp_facebook_url`,`company`.`comp_x_url` AS `comp_x_url`,`company`.`comp_linkedin_url` AS `comp_linkedin_url`,`company`.`comp_instagram_url` AS `comp_instagram_url`,`company`.`comp_youtube_url` AS `comp_youtube_url`,`company`.`comp_amazon_url` AS `comp_amazon_url`,`company`.`comp_pinterest_url` AS `comp_pinterest_url`,`company`.`comp_etsy_url` AS `comp_etsy_url`,`company`.`comp_shopify_url` AS `comp_shopify_url`,`email`.`mail_smtp` AS `mail_smtp`,`email`.`mail_from` AS `mail_from`,`email`.`mail_from_password` AS `mail_from_password`,`email`.`mail_cc` AS `mail_cc`,`email`.`mail_bcc` AS `mail_bcc`,`email`.`mail_subject` AS `mail_subject`,`email`.`mail_body` AS `mail_body`,`customers`.`cust_id` AS `cust_id`,`customers`.`cust_first_name` AS `cust_first_name`,`customers`.`cust_last_name` AS `cust_last_name`,`customers`.`cust_email` AS `cust_email` from ((`company` left join `customers` on(`company`.`comp_id` = 1)) left join `email` on(`email`.`mail_id` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_email_template`
--

/*!50001 DROP VIEW IF EXISTS `v_email_template`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_email_template` AS select `email`.`mail_id` AS `mail_id`,`email`.`mail_smtp` AS `mail_smtp`,`email`.`mail_from` AS `mail_from`,`email`.`mail_from_password` AS `mail_from_password`,`email`.`mail_cc` AS `mail_cc`,`email`.`mail_bcc` AS `mail_bcc`,`email`.`mail_subject` AS `mail_subject`,`email`.`mail_body` AS `mail_body` from `email` where `email`.`mail_id` = 1 */;
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

-- Dump completed on 2022-12-31 14:08:36
