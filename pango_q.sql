-- MySQL dump 10.13  Distrib 8.4.5, for Linux (x86_64)
--
-- Host: localhost    Database: pango_q
-- ------------------------------------------------------
-- Server version	8.4.5

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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itinerary_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_optional` tinyint(1) NOT NULL DEFAULT '0',
  `is_highlight` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activities_itinerary_id_foreign` (`itinerary_id`),
  KEY `activities_created_by_foreign` (`created_by`),
  CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activities_itinerary_id_foreign` FOREIGN KEY (`itinerary_id`) REFERENCES `itineraries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinations`
--

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` VALUES (1,'Bali','Indonesia','Denpasar','A beautiful island paradise known for its stunning beaches, vibrant culture, and picturesque landscapes.','image1.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(2,'Paris','France','Paris','The City of Light, known for its art, fashion, gastronomy, and culture.','image2.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(3,'Tokyo','Japan','Tokyo','A bustling metropolis that blends ultramodern and traditional, with neon-lit skyscrapers and historic temples.','image3.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(4,'New York','United States','New York','The Big Apple, a global center for business, art, fashion, food, and entertainment.','image4.jpeg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(5,'Barcelona','Spain','Barcelona','A vibrant city known for its stunning architecture, Mediterranean beaches, and rich cultural heritage.','image5.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(6,'Cape Town','South Africa','Cape Town','A coastal city with dramatic scenery, including Table Mountain and beautiful beaches.','image6.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(7,'Sydney','Australia','Sydney','Famous for its stunning harbor, iconic Opera House, and beautiful beaches.','image7.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(8,'Rio de Janeiro','Brazil','Rio de Janeiro','Known for its spectacular beaches, Carnival, and the Christ the Redeemer statue.','image8.jpeg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(9,'Marrakech','Morocco','Marrakech','A historic city with vibrant markets, gardens, and traditional architecture.','image9.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(10,'Santorini','Greece','Thira','A stunning island with white-washed buildings, blue domes, and breathtaking sunsets.','image10.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(11,'Kyoto','Japan','Kyoto','Japan\'s cultural capital, known for its classical Buddhist temples, gardens, and traditional wooden houses.','image11.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(12,'Venice','Italy','Venice','A unique city built on a lagoon, famous for its canals, gondolas, and historic architecture.','image12.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(13,'Kenya','Kenya','Nairobi','Kenya is renowned for its classic savanna safaris, deserts, dramatic mountain ranges, cultures and beautiful beaches.','image13.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37');
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itineraries`
--

DROP TABLE IF EXISTS `itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itineraries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trip_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `day_number` int NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `itineraries_trip_id_day_number_unique` (`trip_id`,`day_number`),
  CONSTRAINT `itineraries_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itineraries`
--

LOCK TABLES `itineraries` WRITE;
/*!40000 ALTER TABLE `itineraries` DISABLE KEYS */;
INSERT INTO `itineraries` VALUES (1,1,'Day 1: Kenya','Activities for day 1 in Kenya',1,'2025-06-03','2025-05-20 05:58:06','2025-05-20 05:58:06'),(2,2,'Day 1: Kenya','Activities for day 1 in Kenya',1,'2025-06-03','2025-05-20 06:09:56','2025-05-20 06:09:56'),(3,3,'Day 1: Kenya','Activities for day 1 in Kenya',1,'2025-06-03','2025-05-20 08:40:15','2025-05-20 08:40:15'),(4,4,'Day 1: Kenya','Activities for day 1 in Kenya',1,'2025-06-03','2025-05-20 08:45:28','2025-05-20 08:45:28');
/*!40000 ALTER TABLE `itineraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_04_20_143450_create_destinations_table',1),(5,'2025_04_20_143500_create_trip_templates_table',1),(6,'2025_04_20_143511_create_password_reset_tokens_table',1),(7,'2025_04_20_143602_create_personal_access_tokens_table',1),(8,'2025_04_20_143610_create_trips_table',1),(9,'2025_04_20_143619_create_trip_members_table',1),(10,'2025_04_20_143628_create_itineraries_table',1),(11,'2025_04_20_143636_create_activities_table',1),(12,'2025_04_20_143644_create_savings_wallets_table',1),(13,'2025_04_20_143652_create_wallet_transactions_table',1),(14,'2025_04_20_143710_create_notifications_table',1),(15,'2025_04_20_204409_create_sessions_table',1),(16,'2025_04_30_120741_fix_password_reset_tokens_table',1),(17,'2025_04_30_120741_fix_personal_access_tokens_table',1),(18,'2025_05_07_121632_create_template_activities_table',1),(19,'2025_05_19_131422_add_selected_optional_activities_column',1),(20,'2025_05_20_053441_add_currency_to_savings_wallets_table',1),(21,'2025_05_20_053553_modify_name_column_in_savings_wallets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_wallets`
--

DROP TABLE IF EXISTS `savings_wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trip_id` bigint unsigned NOT NULL,
  `name` json NOT NULL,
  `minimum_goal` decimal(10,2) NOT NULL,
  `custom_goal` decimal(10,2) DEFAULT NULL,
  `current_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `target_date` date DEFAULT NULL,
  `contribution_frequency` enum('weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `savings_wallets_trip_id_foreign` (`trip_id`),
  CONSTRAINT `savings_wallets_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_wallets`
--

LOCK TABLES `savings_wallets` WRITE;
/*!40000 ALTER TABLE `savings_wallets` DISABLE KEYS */;
INSERT INTO `savings_wallets` VALUES (1,1,'{\"en\": \"Savings for Trip to Kenya\"}',4550.00,NULL,0.00,'2025-06-03','weekly','USD','2025-05-20 05:58:06','2025-05-20 05:58:06'),(2,2,'{\"en\": \"Savings for Trip to Kenya\"}',2070.00,NULL,1000.00,'2025-06-03','weekly','USD','2025-05-20 06:09:55','2025-05-20 06:09:55'),(3,3,'{\"en\": \"Savings for Trip to Kenya\"}',4550.00,NULL,0.00,'2025-06-03','weekly','USD','2025-05-20 08:40:15','2025-05-20 08:40:15'),(4,4,'{\"en\": \"Savings for Trip to Kenya\"}',1710.00,NULL,0.00,'2025-06-03','weekly','USD','2025-05-20 08:45:28','2025-05-20 08:45:28');
/*!40000 ALTER TABLE `savings_wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_activities`
--

DROP TABLE IF EXISTS `template_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trip_template_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_number` int NOT NULL,
  `time_of_day` enum('morning','afternoon','evening') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'morning',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_highlight` tinyint(1) NOT NULL DEFAULT '0',
  `is_optional` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_activities_trip_template_id_foreign` (`trip_template_id`),
  CONSTRAINT `template_activities_trip_template_id_foreign` FOREIGN KEY (`trip_template_id`) REFERENCES `trip_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_activities`
--

LOCK TABLES `template_activities` WRITE;
/*!40000 ALTER TABLE `template_activities` DISABLE KEYS */;
INSERT INTO `template_activities` VALUES (1,1,'Airport Pickup & Transfer to Amboseli','Arrival at Jomo Kenyatta International Airport and transfer to Amboseli National Park.','Nairobi to Amboseli National Park',1,'morning','08:00:00','12:00:00',0.00,'transfer','image5.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(2,1,'Lunch at Amboseli Sopa Lodge','Enjoy a delicious lunch while taking in the views at Amboseli Sopa Lodge.','Amboseli Sopa Lodge',1,'afternoon','13:00:00','14:00:00',0.00,'meal','image12.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(3,1,'Evening Game Drive','Your first game drive in Amboseli National Park with a chance to see elephants with Mt. Kilimanjaro in the background.','Amboseli National Park',1,'evening','16:00:00','18:30:00',45.00,'safari','image7.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(4,1,'Full Day Game Drive','Spend the whole day exploring Amboseli National Park, known for its large elephant herds and views of Mt. Kilimanjaro.','Amboseli National Park',2,'morning','06:30:00','18:00:00',65.00,'safari','image7.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(5,1,'Picnic Lunch','Enjoy a picnic lunch in the wild surrounded by the beautiful landscapes of Amboseli.','Amboseli National Park',2,'afternoon','12:30:00','13:30:00',0.00,'meal','image4.jpeg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(6,1,'Transfer to Lake Naivasha','Scenic drive from Amboseli to Lake Naivasha.','Amboseli to Lake Naivasha',3,'morning','07:30:00','12:30:00',0.00,'transfer','image2.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(7,1,'Lunch at Lake Naivasha Sopa Resort','Lunch at the beautiful lakeside resort.','Lake Naivasha Sopa Resort',3,'afternoon','13:00:00','14:00:00',0.00,'meal','image16.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(8,1,'Boat Trip on Lake Naivasha','Enjoy a boat ride on Lake Naivasha to see hippos and numerous bird species.','Lake Naivasha',3,'afternoon','15:00:00','17:00:00',35.00,'water activity','image14.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(9,1,'Walking Safari','Take a guided walking safari to see wildlife up close in a safe environment.','Animal Sanctuary',3,'evening','17:30:00','18:30:00',25.00,'safari','image4.jpeg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(10,1,'Transfer to Hell\'s Gate National Park','Short drive to Hell\'s Gate National Park, known for its scenic landscape and geothermal activity.','Lake Naivasha to Hell\'s Gate',4,'morning','08:00:00','09:00:00',0.00,'transfer','image17.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(11,1,'Cycling Safari','Experience a unique safari on bicycles in Hell\'s Gate National Park.','Hell\'s Gate National Park',4,'morning','09:30:00','11:30:00',30.00,'adventure','image4.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(12,1,'Gorge Hiking','Hike through the impressive gorges of Hell\'s Gate.','Hell\'s Gate National Park',4,'morning','11:45:00','13:00:00',15.00,'adventure','image12.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(13,1,'Transfer to Lake Nakuru','Drive to Lake Nakuru National Park.','Hell\'s Gate to Lake Nakuru',4,'afternoon','13:30:00','15:00:00',0.00,'transfer','image1.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(14,1,'Afternoon Game Drive','Game drive in Lake Nakuru National Park, known for rhinos, lions, and sometimes flamingos.','Lake Nakuru National Park',4,'afternoon','16:00:00','18:30:00',45.00,'safari','image7.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(15,1,'Transfer to Masai Mara','Scenic drive to the world-famous Masai Mara National Reserve.','Lake Nakuru to Masai Mara',5,'morning','07:30:00','12:30:00',0.00,'transfer','image12.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(16,1,'Lunch at Masai Mara Sopa Lodge','Enjoy lunch at the lodge with panoramic views of the savannah.','Masai Mara Sopa Lodge',5,'afternoon','13:00:00','14:00:00',0.00,'meal','image18.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(17,1,'Maasai Village Visit','Visit a traditional Maasai village to learn about their culture and way of life.','Maasai Village',5,'afternoon','15:00:00','16:30:00',25.00,'cultural','image19.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(18,1,'Evening Game Drive','Your first game drive in Masai Mara, home to the \"Big Five\" and many other wildlife species.','Masai Mara National Reserve',5,'evening','17:00:00','19:00:00',45.00,'safari','image11.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(19,1,'Full Day Game Drive','Full day exploring the vast plains of Masai Mara with a chance to see lions, elephants, giraffes, and more.','Masai Mara National Reserve',6,'morning','06:30:00','18:00:00',85.00,'safari','image4.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(20,1,'Picnic Lunch','Enjoy a picnic lunch in the midst of the African wilderness.','Masai Mara National Reserve',6,'afternoon','12:30:00','13:30:00',0.00,'meal','image14.jpeg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(21,1,'Breakfast at Masai Mara Sopa Lodge','Enjoy your last morning in the Masai Mara with a delicious breakfast.','Masai Mara Sopa Lodge',7,'morning','07:00:00','08:00:00',0.00,'meal','image18.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(22,1,'Transfer to Nairobi','Return journey to Nairobi with a stop at a viewpoint over the Great Rift Valley.','Masai Mara to Nairobi',7,'morning','08:30:00','13:30:00',0.00,'transfer','image7.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(23,1,'Nairobi City Tour','Brief tour of Nairobi before heading to the airport.','Nairobi',7,'afternoon','14:00:00','16:00:00',20.00,'sightseeing','image12.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(24,1,'Airport Transfer','Transfer to Jomo Kenyatta International Airport for your departure.','Nairobi',7,'afternoon','16:30:00','17:30:00',0.00,'transfer','image19.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(25,1,'Hot Air Balloon Safari','Experience the breathtaking Masai Mara from above in a hot air balloon followed by a champagne breakfast in the bush.','Masai Mara National Reserve',6,'morning','05:00:00','09:00:00',500.00,'adventure','image16.jpg',1,1,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(26,1,'Professional Wildlife Photography Tour','Join a professional wildlife photographer for specialized instruction and guidance to capture incredible safari moments.','Masai Mara National Reserve',5,'afternoon','15:00:00','19:00:00',200.00,'special interest','image4.jpeg',0,1,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(27,1,'Amboseli Elephant Research Center Visit','Visit the renowned elephant research center and learn about conservation efforts from scientists studying these magnificent creatures.','Amboseli National Park',2,'afternoon','14:00:00','16:00:00',75.00,'educational','image5.jpg',0,1,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(28,1,'Private Sunset Sundowner','Exclusive sundowner experience on a hilltop overlooking the savannah with premium drinks and appetizers.','Masai Mara National Reserve',5,'evening','18:00:00','19:30:00',120.00,'luxury','image2.jpg',0,1,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(29,2,'Nairobi Pickup & Transfer to Lake Nakuru','Meet your guide at Jomo Kenyatta International Airport or your Nairobi hotel and depart for Lake Nakuru National Park.','Nairobi to Lake Nakuru',1,'morning','07:30:00','12:00:00',0.00,'transfer','image5.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(30,2,'Great Rift Valley Viewpoint','Stop at a scenic viewpoint overlooking the dramatic Great Rift Valley.','Great Rift Valley',1,'morning','09:30:00','10:00:00',0.00,'sightseeing','image14.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(31,2,'Lake Nakuru Game Drive','Afternoon game drive to see flamingos, rhinos, lions and more in this compact but wildlife-rich park.','Lake Nakuru National Park',1,'afternoon','14:00:00','18:00:00',45.00,'safari','image1.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(32,2,'Morning Game Drive & Rhino Sanctuary Visit','Early morning game drive focusing on the protected rhino sanctuary within Lake Nakuru National Park.','Lake Nakuru National Park',2,'morning','06:30:00','09:30:00',35.00,'safari','image14.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(33,2,'Transfer to Masai Mara','Journey to the world-famous Masai Mara Game Reserve through the scenic countryside.','Lake Nakuru to Masai Mara',2,'morning','10:00:00','16:00:00',0.00,'transfer','image6.jpg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(34,2,'Evening Game Drive','First game drive in the Masai Mara, timed for optimal wildlife viewing at dusk.','Masai Mara National Reserve',2,'evening','16:30:00','18:30:00',40.00,'safari','image5.jpg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(35,2,'Full Day Masai Mara Safari','Full day exploring the expansive Masai Mara in search of the Big Five and other wildlife.','Masai Mara National Reserve',3,'morning','07:00:00','17:00:00',70.00,'safari','image4.jpeg',1,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(36,2,'Bush Picnic Lunch','Enjoy lunch in the wilderness surrounded by the magnificent landscapes of the Mara.','Masai Mara National Reserve',3,'afternoon','12:30:00','13:30:00',0.00,'meal','image4.jpeg',0,0,'2025-05-20 05:53:37','2025-05-20 05:53:37'),(37,2,'Sunrise Game Drive','Early morning game drive to catch predators on their morning hunts.','Masai Mara National Reserve',4,'morning','06:00:00','09:00:00',45.00,'safari','image5.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(38,2,'Maasai Village Cultural Visit','Visit a local Maasai village to learn about their traditions, daily activities, and way of life.','Maasai Village near Masai Mara',4,'afternoon','11:00:00','13:00:00',25.00,'cultural','image14.jpeg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(39,2,'Afternoon Wildlife Safari','Afternoon game drive focusing on finding wildlife you may have missed during earlier drives.','Masai Mara National Reserve',4,'afternoon','15:00:00','18:00:00',45.00,'safari','image8.jpeg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(40,2,'Morning Game Drive','Final morning game drive in the Masai Mara.','Masai Mara National Reserve',5,'morning','06:30:00','09:00:00',40.00,'safari','image14.jpeg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(41,2,'Return Transfer to Nairobi','Journey back to Nairobi with comfort stops along the way.','Masai Mara to Nairobi',5,'morning','09:30:00','15:30:00',0.00,'transfer','image10.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(42,2,'Farewell Dinner','Enjoy a farewell dinner at a local restaurant in Nairobi.','Nairobi',5,'evening','19:00:00','21:00:00',30.00,'meal','image19.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(43,2,'Hot Air Balloon Safari','Sunrise hot air balloon safari over the Masai Mara with champagne breakfast.','Masai Mara National Reserve',4,'morning','05:00:00','09:00:00',450.00,'adventure','image14.jpeg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(44,2,'Bush Breakfast','Exclusive breakfast set up in the wild after a morning game drive.','Masai Mara National Reserve',3,'morning','08:30:00','10:00:00',65.00,'meal','image19.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(45,2,'Nairobi National Park Visit','Visit the unique wildlife park located just outside Nairobi city on your last day.','Nairobi',5,'afternoon','15:00:00','18:00:00',60.00,'safari','image17.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(46,3,'VIP Airport Welcome','Exclusive VIP welcome at Jomo Kenyatta International Airport with fast-track immigration.','Nairobi',1,'morning','08:00:00','09:00:00',75.00,'luxury','image10.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(47,3,'Giraffe Center Visit','Encounter and feed endangered Rothschild giraffes at this conservation center.','Nairobi',1,'morning','10:00:00','11:30:00',25.00,'wildlife','image14.jpeg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(48,3,'Helicopter Flight to Amboseli','Scenic helicopter transfer from Nairobi to Amboseli National Park with aerial views of Mt. Kilimanjaro.','Nairobi to Amboseli',1,'afternoon','14:00:00','15:30:00',650.00,'luxury transfer','image5.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(49,3,'Private Dinner with Maasai Elders','Exclusive cultural experience with traditional storytelling and knowledge sharing with Maasai tribal elders.','Masai Mara',6,'evening','19:00:00','21:30:00',250.00,'cultural','image16.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(50,3,'Private Bush Dinner Under the Stars','Luxury dinner experience set up in the wilderness with personal chef and waitstaff, lanterns, and champagne.','Masai Mara',7,'evening','19:00:00','22:00:00',300.00,'luxury','image1.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(51,3,'Private Helicopter Wildlife Tracking','Track wildlife from the air with a specialist guide to find elusive species like black rhino or leopard.','Various Parks',5,'morning','06:30:00','10:30:00',1200.00,'luxury safari','image5.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(52,4,'Nairobi to Tsavo East Transfer','Morning departure from Nairobi to Tsavo East National Park.','Nairobi to Tsavo East',1,'morning','07:30:00','12:30:00',0.00,'transfer','image18.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(53,4,'Afternoon Game Drive','First safari in Tsavo East, searching for the park\'s famous red elephants.','Tsavo East National Park',1,'afternoon','15:00:00','18:00:00',50.00,'safari','image9.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(54,4,'Full Day Tsavo Safari','Comprehensive exploration of Tsavo East National Park with picnic lunch.','Tsavo East National Park',2,'morning','06:30:00','17:00:00',80.00,'safari','image2.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(55,4,'Transfer to Diani Beach','Scenic drive from Tsavo to the pristine beaches of Diani on the Kenyan coast.','Tsavo to Diani Beach',3,'morning','08:00:00','13:00:00',0.00,'transfer','image17.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(56,4,'Beach Relaxation','Free time to enjoy the white sands and turquoise waters of Diani Beach.','Diani Beach',3,'afternoon','14:00:00','18:00:00',0.00,'beach','image2.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(57,4,'Snorkeling Excursion','Half-day snorkeling trip to a marine reserve to see colorful coral and tropical fish.','Kisite-Mpunguti Marine Park',4,'morning','08:30:00','13:00:00',65.00,'water activity','image16.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(58,4,'Beachfront Massage','Relaxing massage in a beach cabana with ocean sounds.','Diani Beach Resort',4,'afternoon','15:00:00','16:00:00',60.00,'wellness','image14.jpeg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(59,4,'Traditional Dhow Sailing Trip','Sail on a traditional wooden dhow boat along the coast with local seafood lunch.','Diani Coastline',5,'morning','09:00:00','14:00:00',70.00,'cultural','image17.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(60,4,'Final Beach Day & Transfer','Morning relaxation at the beach before transfer to Mombasa airport for departure.','Diani Beach to Mombasa',6,'morning','09:00:00','15:00:00',0.00,'transfer','image5.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(61,4,'Deep Sea Fishing','Half-day fishing expedition for marlin, sailfish, and tuna in the Indian Ocean.','Diani Waters',5,'morning','06:00:00','12:00:00',220.00,'water activity','image19.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(62,4,'Scuba Diving Experience','Guided scuba diving to explore vibrant coral reefs and marine life.','Diani Waters',4,'morning','08:00:00','12:00:00',150.00,'water activity','image11.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(63,4,'Skydiving Adventure','Tandem skydiving experience with breathtaking views of the coastline.','Diani Beach',5,'afternoon','14:00:00','16:00:00',350.00,'adventure','image18.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(64,5,'Airport Pickup & Beach Relaxation','Arrival at Ngurah Rai International Airport and transfer to your beachfront hotel in Kuta.','Kuta Beach, Bali',1,'afternoon','14:00:00','18:00:00',0.00,'relaxation','image7.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(65,5,'Welcome Dinner','Enjoy a traditional Balinese dinner with cultural performances.','Jimbaran Bay, Bali',1,'evening','19:00:00','21:00:00',45.00,'food','image15.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(66,5,'Ubud Cultural Tour','Visit the cultural heart of Bali, including the Sacred Monkey Forest and local craft villages.','Ubud, Bali',2,'morning','09:00:00','13:00:00',65.00,'cultural','image5.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(67,5,'Rice Terrace Experience','Explore the beautiful Tegallalang Rice Terraces with a local guide.','Tegallalang, Bali',2,'afternoon','14:00:00','17:00:00',30.00,'nature','image17.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(68,5,'Sunrise Mount Batur Hike','Early morning hike to witness the breathtaking sunrise from Mount Batur volcano.','Mount Batur, Bali',3,'morning','04:00:00','10:00:00',85.00,'adventure','image8.jpeg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(69,5,'Hot Springs & Spa','Relax in natural hot springs and enjoy a traditional Balinese massage.','Kintamani, Bali',3,'afternoon','12:00:00','16:00:00',55.00,'relaxation','image12.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(70,5,'Uluwatu Temple & Kecak Dance','Visit the clifftop Uluwatu Temple and watch the mesmerizing Kecak Fire Dance at sunset.','Uluwatu, Bali',4,'afternoon','15:00:00','19:00:00',40.00,'cultural','image11.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(71,5,'Seafood Dinner on the Beach','Enjoy fresh seafood at a romantic beachside dinner.','Jimbaran Beach, Bali',4,'evening','19:30:00','21:30:00',60.00,'food','image10.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(72,5,'Water Sports Adventure','Experience thrilling water activities including jet skiing, parasailing and banana boat rides.','Nusa Dua, Bali',5,'morning','09:00:00','13:00:00',120.00,'adventure','image3.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(73,5,'Farewell Dinner & Shopping','Enjoy a farewell dinner and shop for souvenirs at local markets.','Seminyak, Bali',5,'evening','18:00:00','21:00:00',50.00,'food','image8.jpeg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(74,5,'Airport Transfer','Transfer to Ngurah Rai International Airport for your departure.','Denpasar, Bali',5,'afternoon','14:00:00','15:00:00',0.00,'transfer','image16.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(75,5,'Private Sunset Photography Tour','Professional photography tour to capture Bali\'s magnificent sunsets at the best locations.','Various Locations, Bali',4,'afternoon','15:30:00','19:30:00',95.00,'special interest','image15.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(76,5,'Nusa Penida Island Day Trip','Full-day excursion to the spectacular Nusa Penida island with visits to Kelingking Beach and Angel\'s Billabong.','Nusa Penida, Bali',2,'morning','07:00:00','19:00:00',130.00,'adventure','image16.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(77,5,'Traditional Cooking Class','Learn to prepare authentic Balinese dishes with a local chef, including market visit and ingredients selection.','Ubud, Bali',3,'morning','08:00:00','13:00:00',65.00,'cultural','image15.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(78,6,'Welcome & Wellness Assessment','Arrival at Ngurah Rai Airport, transfer to Ubud, and personalized wellness consultation.','Ubud, Bali',1,'afternoon','14:00:00','16:00:00',75.00,'wellness','image18.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(79,6,'Opening Ceremony & Meditation','Traditional Balinese blessing ceremony followed by guided sunset meditation.','Ubud, Bali',1,'evening','17:30:00','19:00:00',45.00,'spiritual','image6.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(80,6,'Morning Yoga Session','Energizing morning yoga session focusing on alignment and breath work.','Ubud, Bali',2,'morning','07:00:00','08:30:00',0.00,'yoga','image19.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(81,6,'Holistic Healing Spa Treatment','Traditional Balinese healing treatment combining massage, aromatherapy, and energy work.','Ubud, Bali',2,'afternoon','14:00:00','16:00:00',120.00,'wellness','image6.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(82,6,'Private Yoga Instruction','One-on-one yoga session with master instructor to deepen your practice.','Ubud, Bali',3,'morning','09:00:00','10:30:00',85.00,'yoga','image15.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(83,6,'Ayurvedic Consultation','Personal consultation with an Ayurvedic doctor for health assessment and recommendations.','Ubud, Bali',2,'afternoon','14:00:00','15:30:00',100.00,'wellness','image12.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(84,6,'Sound Healing Journey','Immersive sound therapy session using traditional instruments for deep relaxation and healing.','Ubud, Bali',5,'evening','19:00:00','20:30:00',75.00,'spiritual','image4.jpeg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(85,7,'Arrival & Adventure Briefing','Airport pickup, transfer to Ubud, and adventure trip orientation with experienced guides.','Ubud, Bali',1,'afternoon','15:00:00','17:00:00',0.00,'orientation','image15.jpg',0,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(86,7,'Ayung River White Water Rafting','Thrilling class III rapids expedition down Bali\'s longest river through stunning rainforest gorges.','Ayung River, Bali',2,'morning','08:00:00','13:00:00',85.00,'adventure','image2.jpg',1,0,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(87,7,'Bali Treetop Adventure Park','High ropes course experience with zip lines, Tarzan swings, and aerial bridges in the jungle canopy.','Bedugul, Bali',3,'morning','09:00:00','12:00:00',65.00,'adventure','image3.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(88,7,'Bali Swing Experience','Swing over jungle ravines on giant swings with breathtaking views of the rainforest.','Ubud, Bali',6,'morning','10:00:00','12:00:00',45.00,'adventure','image4.jpeg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38'),(89,7,'Underwater Scooter Experience','Explore underwater marine life on a motorized sea scooter without diving certification.','Sanur, Bali',4,'afternoon','13:00:00','16:00:00',95.00,'water activity','image5.jpg',0,1,'2025-05-20 05:53:38','2025-05-20 05:53:38');
/*!40000 ALTER TABLE `template_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trip_members`
--

DROP TABLE IF EXISTS `trip_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trip_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trip_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `role` enum('organizer','member') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `invitation_status` enum('pending','accepted','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `invitation_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trip_members_trip_id_user_id_unique` (`trip_id`,`user_id`),
  KEY `trip_members_user_id_foreign` (`user_id`),
  CONSTRAINT `trip_members_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trip_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_members`
--

LOCK TABLES `trip_members` WRITE;
/*!40000 ALTER TABLE `trip_members` DISABLE KEYS */;
INSERT INTO `trip_members` VALUES (1,1,1,'organizer','accepted',NULL,'2025-05-20 08:30:52','2025-05-20 08:30:52'),(2,2,2,'organizer','accepted',NULL,'2025-05-20 08:30:52','2025-05-20 08:30:52');
/*!40000 ALTER TABLE `trip_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trip_templates`
--

DROP TABLE IF EXISTS `trip_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trip_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destination_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `highlights` text COLLATE utf8mb4_unicode_ci,
  `duration_days` int NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `difficulty_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'moderate',
  `trip_style` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trip_templates_destination_id_foreign` (`destination_id`),
  CONSTRAINT `trip_templates_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trip_templates`
--

LOCK TABLES `trip_templates` WRITE;
/*!40000 ALTER TABLE `trip_templates` DISABLE KEYS */;
INSERT INTO `trip_templates` VALUES (1,13,'7 DAY BEST OF KENYA SOPA CIRCUIT','Experience the best of Kenya with visits to Amboseli National Park, Lake Naivasha, Lake Nakuru National Park, and Masai Mara National Reserve. This luxury package includes stays at premium Sopa Lodges throughout the circuit.','\"[\\\"Mt. Kilimanjaro views from Amboseli National Park\\\",\\\"Incredible elephant herds in their natural habitat\\\",\\\"Lake Naivasha boat safari with hippo encounters\\\",\\\"Cycling safari through Hell\'s Gate National Park\\\",\\\"Big Five wildlife viewing in Masai Mara\\\",\\\"Cultural interaction with Maasai tribes\\\"]\"',7,1800.00,'moderate','safari',1,'image13.jpg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(2,13,'5 DAY MAASAI MARA & LAKE NAKURU SAFARI','Perfect for travelers on a budget, this 5-day safari focuses on Kenya\'s most iconic wildlife destinations. Experience the world-famous Maasai Mara National Reserve and the flamingo-dotted Lake Nakuru while staying in comfortable tented camps.','\"[\\\"Big Five game viewing in Maasai Mara\\\",\\\"Flamingo flocks at Lake Nakuru\\\",\\\"Affordable tented camp accommodations\\\",\\\"Great Rift Valley viewpoints\\\",\\\"Rhino sanctuary visit\\\"]\"',5,1200.00,'easy','safari',0,'image4.jpeg','2025-05-20 05:53:37','2025-05-20 05:53:37'),(3,13,'10 DAY ULTIMATE KENYA ADVENTURE','The most comprehensive Kenya safari experience combining wildlife, culture, and adventure. From the Amboseli plains beneath Mt. Kilimanjaro to the Samburu wilderness and the Maasai Mara, this premium journey showcases Kenya\'s extraordinary diversity.','\"[\\\"Private luxury safari vehicles\\\",\\\"Samburu tribal experiences\\\",\\\"Night game drives in private conservancies\\\",\\\"Helicopter flight over the Great Rift Valley\\\",\\\"Hot air balloon safari in Maasai Mara\\\",\\\"Mount Kenya helicopter tour\\\",\\\"Premium lodge accommodation\\\"]\"',10,2800.00,'moderate','adventure',1,'image9.jpg','2025-05-20 05:53:38','2025-05-20 05:53:38'),(4,13,'6 DAY KENYAN COAST & WILDLIFE','Combine the best of Kenya\'s wildlife and pristine beaches in this perfectly balanced itinerary. Start with safari adventures in Tsavo National Park before relaxing on the white sands of Diani Beach along the Indian Ocean.','\"[\\\"Red elephant sightings in Tsavo East\\\",\\\"Luxurious beachfront resort stay\\\",\\\"Snorkeling in marine reserves\\\",\\\"Traditional dhow sailing trip\\\",\\\"Fresh seafood dining experiences\\\",\\\"Optional water sports activities\\\"]\"',6,1500.00,'easy','beach',0,'image11.jpg','2025-05-20 05:53:38','2025-05-20 05:53:38'),(5,1,'BALI ISLAND PARADISE EXPLORER','Experience the best of Bali with this carefully curated trip featuring pristine beaches, ancient temples, and cultural experiences.','\"[\\\"Sacred Monkey Forest exploration in Ubud\\\",\\\"Traditional Balinese cultural performances\\\",\\\"Spectacular sunrise hike on Mount Batur\\\",\\\"Seaside Uluwatu Temple with Kecak Fire Dance\\\",\\\"Pristine beach time in Kuta and Jimbaran\\\"]\"',5,1200.00,'easy','cultural',1,'image1.jpg','2025-05-20 05:53:38','2025-05-20 05:53:38'),(6,1,'BALI WELLNESS & YOGA RETREAT','Rejuvenate your mind, body, and spirit with this transformative wellness journey through Bali. Featuring daily yoga, meditation, spa treatments, and healthy cuisine in serene settings from Ubud\'s jungle to Canggu\'s beaches.','\"[\\\"Daily yoga and meditation sessions\\\",\\\"Traditional Balinese healing ceremonies\\\",\\\"Organic farm-to-table dining experiences\\\",\\\"Traditional water purification ritual\\\",\\\"Sacred site meditation\\\",\\\"Balinese massage and spa treatments\\\"]\"',7,1800.00,'easy','wellness',1,'image5.jpg','2025-05-20 05:53:38','2025-05-20 05:53:38'),(7,1,'BALI ADVENTURE & HIDDEN TREASURES','Go beyond the typical tourist experience and discover Bali\'s hidden gems. This adventure-packed itinerary takes you through secret waterfalls, off-the-beaten-path temples, remote villages, and adrenaline-pumping activities across the island.','\"[\\\"White water rafting on Ayung River\\\",\\\"Sunrise trek to hidden waterfalls\\\",\\\"Cliff jumping adventures\\\",\\\"Traditional village homestays\\\",\\\"Off-road ATV jungle experiences\\\",\\\"Secret beach discoveries\\\",\\\"Authentic local cuisine cooking classes\\\"]\"',8,1600.00,'moderate','adventure',0,'image7.jpg','2025-05-20 05:53:38','2025-05-20 05:53:38');
/*!40000 ALTER TABLE `trip_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trips`
--

DROP TABLE IF EXISTS `trips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` bigint unsigned NOT NULL,
  `trip_template_id` bigint unsigned DEFAULT NULL,
  `planning_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'self_planned',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `selected_optional_activities` json DEFAULT NULL,
  `status` enum('planning','active','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trips_creator_id_foreign` (`creator_id`),
  KEY `trips_trip_template_id_foreign` (`trip_template_id`),
  CONSTRAINT `trips_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trips_trip_template_id_foreign` FOREIGN KEY (`trip_template_id`) REFERENCES `trip_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trips`
--

LOCK TABLES `trips` WRITE;
/*!40000 ALTER TABLE `trips` DISABLE KEYS */;
INSERT INTO `trips` VALUES (1,1,3,'pre_planned','Trip to Kenya',NULL,'Kenya','2025-06-03','2025-06-10',4550.00,4550.00,NULL,'planning','2025-05-20 05:58:06','2025-05-20 05:58:06'),(2,2,4,'pre_planned','Trip to Kenya',NULL,'Kenya','2025-06-03','2025-06-10',2070.00,2070.00,NULL,'planning','2025-05-20 06:09:55','2025-05-20 06:09:55'),(3,3,3,'pre_planned','Trip to Kenya',NULL,'Kenya','2025-06-03','2025-06-10',4550.00,4550.00,NULL,'planning','2025-05-20 08:40:15','2025-05-20 08:40:15'),(4,4,2,'pre_planned','Trip to Kenya',NULL,'Kenya','2025-06-03','2025-06-10',1710.00,1710.00,NULL,'planning','2025-05-20 08:45:28','2025-05-20 08:45:28');
/*!40000 ALTER TABLE `trips` ENABLE KEYS */;
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
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_type` enum('personal','business') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'personal',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `linked_bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_status` enum('active','inactive','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `preferred_payment_method` enum('wallet','bank_transfer','credit_card','m_pesa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wallet',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `auth_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Minji W','minji@gmail.com',NULL,NULL,'$2y$12$S7rMAvKHJj51Ah4YByX0dOxAVMbIKedsHhLoWaBICPX4Bz2p6g1tG','esXt61s6rnnblt9hjNZpwEd5qhVxgaJywscQjRfNyNaWGO2f4pqFLeJNfRWx','2025-05-20 05:56:42','2025-05-20 05:56:42',NULL,NULL,NULL,NULL,NULL,NULL,'0551926647','personal','USD',NULL,NULL,'active','wallet',NULL,NULL,NULL),(2,'benja','benja@gmail.com','+254729384345','profile-photos/rI9TNB6lIminZzNT2eyJ8Z2PAZZfbyuxpBQlPe1e.jpg','$2y$12$q86XrXymeQLlr2NE3zMTh.6CZxKRWO25v7N/Ei1wlWsf39utJBK42','WX6ZQlGqjgqg2SGYSEHnnVntFTAg8ZPwqrQDRokaRREwSZuSR4xEYI7psBme','2025-05-20 06:08:48','2025-05-20 08:35:12','849849','4353423','1997-10-22',NULL,'Kenyan','Kira, Nairobi','0551443414','personal','USD',NULL,NULL,'active','wallet',NULL,NULL,NULL),(3,'jiko','jiko@gmail.com',NULL,NULL,'$2y$12$5V2QKh1nlBDKY2SXVXAir.EVRHuK./4M2Xh2tH3MjFv.kTGr4wV8C','cOqd6NR3WIXqlPQEbydyUFMo1zU6UaLnQGliEut5NHdMeicSdfcor4knAowr','2025-05-20 08:38:49','2025-05-20 08:38:49',NULL,NULL,NULL,NULL,NULL,NULL,'0551478512','personal','USD',NULL,NULL,'active','wallet',NULL,NULL,NULL),(4,'new users','new@gmail.com','0746677878','profile-photos/mXxQvRi8xmlx6hWLo3UeiX1S5LF26FibRcny3zml.jpg','$2y$12$w/uml/veFRoxU2jRyWReo.wM4wcCbudbsll/AP5l7dN.PKO/1rPyy','BQknJVZGh01sGEhZxiyxFzPiQC2LjJ2vxuZz9RPI7wvSIz5TwssVGwNrAP9U','2025-05-20 08:44:19','2025-05-20 08:52:53',NULL,NULL,NULL,NULL,NULL,NULL,'0551571999','personal','USD',NULL,NULL,'active','wallet',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('deposit','withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wallet_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `savings_wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-20 16:32:33
