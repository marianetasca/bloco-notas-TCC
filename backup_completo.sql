-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bloco-notas
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB-log

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
-- Table structure for table `anexos`
--

DROP TABLE IF EXISTS `anexos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anexos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nota_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `tipo_mime` varchar(255) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anexos_user_id_foreign` (`user_id`),
  KEY `anexos_nota_id_foreign` (`nota_id`),
  CONSTRAINT `anexos_nota_id_foreign` FOREIGN KEY (`nota_id`) REFERENCES `notas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anexos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anexos`
--

LOCK TABLES `anexos` WRITE;
/*!40000 ALTER TABLE `anexos` DISABLE KEYS */;
INSERT INTO `anexos` VALUES (1,1,1,'Pôr_do_sol.jpeg','anexos/1/NkRXFUYb4ssrJNEGe6ImytRy0DG1Cij7YMYZTFKX.jpg','image/jpeg',1634481,'2025-08-12 00:44:10','2025-08-12 00:44:15'),(3,NULL,1,'1-s2.0-S2772375524000856-main.pdf','anexos/1/DPp7bSCJ7iM9kmQiCXjuwNL9MwEt5hFzWrh48k98.pdf','application/pdf',8246040,'2025-08-12 02:01:03','2025-08-12 02:01:03'),(4,NULL,1,'mapa_com_siglas_profissional.png','anexos/1/llhIZAUE7rEp5TOiUrP41yygugpOCbJgNIRW3dV6.png','image/png',436625,'2025-08-13 22:10:34','2025-08-13 22:10:34'),(5,NULL,1,'Paisagem rural com estrada sinuosa.png','anexos/1/VXByANzvdCyunmUSjm4OzteIOFMUvwveMNCTBOY6.png','image/png',2253775,'2025-08-13 23:46:19','2025-08-13 23:46:19'),(6,NULL,1,'mapa_com_siglas_profissional.png','anexos/1/GmUd2OGOl4PvSoMgdMQCHaKBJiJPdMQffmEiwyXN.png','image/png',436625,'2025-08-13 23:47:54','2025-08-13 23:47:54'),(7,NULL,1,'Paisagem rural com estrada sinuosa.png','anexos/1/qTMaJw5xzw0FTbHwbMLa8CjvqCmnpsWdsFJToNN4.png','image/png',2253775,'2025-08-13 23:56:28','2025-08-13 23:56:28'),(8,NULL,1,'mapa_com_siglas_profissional.png','anexos/1/sM5rp80JUFAC7mQjQWmnaOQ13MwueVw5AxfOwox3.png','image/png',436625,'2025-08-13 23:57:51','2025-08-13 23:57:51'),(10,NULL,1,'Paisagem rural com estrada sinuosa.png','anexos/1/PiDAEejnGPOen0Wv3A9CDKXAmMXIpRdsE4NEJ4YT.png','image/png',2253775,'2025-08-14 02:36:53','2025-08-14 02:36:53'),(12,NULL,1,'mapa_com_siglas_profissional.png','anexos/1/IUV7NuZvSc0FrFwmK0qxrhrup8CcA6CSUWadDiIu.png','image/png',436625,'2025-08-14 02:52:47','2025-08-14 02:52:47'),(13,NULL,1,'Paisagem rural com estrada sinuosa.png','anexos/1/CBBRPFW1Xg06nQo0uAhHe6oSRfV9RWs0JOEhOWy3.png','image/png',2253775,'2025-08-14 02:55:22','2025-08-14 02:55:22'),(19,4,1,'mapa_com_siglas_profissional.png','anexos/1/galp5ELabiXK1xAXUJMkYzbl6dFd9fB5h5EoePMi.png','image/png',436625,'2025-08-14 12:30:51','2025-08-14 12:30:55'),(20,4,1,'Paisagem rural com estrada sinuosa.png','anexos/1/Z8nCgxsijXPS3JgmwTIDozQR62F4dhIaIvRAkK62.png','image/png',2253775,'2025-08-14 12:30:52','2025-08-14 12:30:55'),(21,8,1,'G1_BFS_tree.png','anexos/1/NIU0bTrQWjlh17FbQbfIxSXEpuAexEVtREUy7sCN.png','image/png',53189,'2025-08-20 13:56:04','2025-08-20 13:56:09'),(22,12,1,'AULA 3.pdf','anexos/1/mi5UPa5XnnBnfy8x4rOMVfRN9BKE5dl6cYopt2Gc.pdf','application/pdf',544743,'2025-09-09 22:07:52','2025-09-09 22:07:59'),(23,NULL,1,'20250919_142519(1).jpg','anexos/1/YG0YA9vDwWGP5biFR3T0Eml1T2DZ0qpDLSMgxQ4k.jpg','image/jpeg',2727749,'2025-09-23 00:57:52','2025-09-23 00:57:52'),(25,NULL,1,'1000116134.jpg','anexos/1/PrNTcvBRPIY8O7Dua7K85bCSVjnpzZvMhGuEVSuS.jpg','image/jpeg',3594392,'2025-09-23 00:58:02','2025-09-23 00:58:02');
/*!40000 ALTER TABLE `anexos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('as@aaaa.com.br|127.0.0.1','i:2;',1756944129),('as@aaaa.com.br|127.0.0.1:timer','i:1756944129;',1756944129),('as@efgg.com|127.0.0.1','i:1;',1756944195),('as@efgg.com|127.0.0.1:timer','i:1756944195;',1756944195),('b1d5781111d84f7b3fe45a0852e59758cd7a87e5','i:2;',1757372056),('b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer','i:1757372056;',1757372056),('teste44@gmail.com|127.0.0.1','i:1;',1756944230),('teste44@gmail.com|127.0.0.1:timer','i:1756944230;',1756944230);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `categorias_user_id_foreign` (`user_id`),
  CONSTRAINT `categorias_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,1,'Sem categoria','Categoria padrão do sistema','2025-08-12 00:43:52','2025-08-12 00:43:52',1),(2,1,'contas',NULL,'2025-08-12 03:16:56','2025-08-12 03:16:56',0),(3,1,'teste',NULL,'2025-08-12 03:17:10','2025-08-12 03:17:10',0),(4,1,'teste1',NULL,'2025-08-12 03:18:01','2025-08-12 03:18:01',0),(5,1,'teste2',NULL,'2025-08-12 03:20:27','2025-08-12 03:20:27',0),(6,2,'Sem categoria','Categoria padrão do sistema','2025-08-30 03:00:19','2025-08-30 03:00:19',1),(8,4,'Sem categoria','Categoria padrão do sistema','2025-09-03 00:02:19','2025-09-03 00:02:19',1),(9,5,'Sem categoria','Categoria padrão do sistema','2025-09-03 00:53:55','2025-09-03 00:53:55',1),(10,6,'Sem categoria','Categoria padrão do sistema','2025-09-03 00:55:43','2025-09-03 00:55:43',1),(11,7,'Sem categoria','Categoria padrão do sistema','2025-09-03 22:44:58','2025-09-03 22:44:58',1),(13,9,'Sem categoria','Categoria padrão do sistema','2025-09-08 22:45:32','2025-09-08 22:45:32',1),(14,10,'Sem categoria','Categoria padrão do sistema','2025-09-08 22:47:58','2025-09-08 22:47:58',1);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_11_08_022742_create_categorias_table',1),(5,'2024_11_08_120629_create_notas_table',1),(6,'2024_11_09_200259_prioridades_table',1),(7,'2024_11_09_200810_create_tags_table',1),(8,'2024_11_09_200811_create_nota_tag_table',1),(9,'2024_11_10_035214_create_concluido_table',1),(10,'2024_11_10_151213_create_anexos_table',1),(11,'2024_11_11_105448_add_data_vencimento_to_notas_table',1),(12,'2024_11_11_202039_insert_default_priorities',1),(13,'2024_11_12_224724_add_soft_deletes_to_notas_table',1),(14,'2025_07_21_162839_add_is_default_to_categorias_table',1),(15,'2025_07_27_212856_add_completed_at_to_notas_table',1),(16,'2025_07_29_224936_alter_anexos_table_make_nota_id_nullable',1),(17,'2025_09_09_193035_create_notifications_table',2),(18,'2025_09_21_153145_add_notification_preferences_to_users_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nota_tag`
--

DROP TABLE IF EXISTS `nota_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nota_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nota_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nota_tag_nota_id_foreign` (`nota_id`),
  KEY `nota_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `nota_tag_nota_id_foreign` FOREIGN KEY (`nota_id`) REFERENCES `notas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nota_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nota_tag`
--

LOCK TABLES `nota_tag` WRITE;
/*!40000 ALTER TABLE `nota_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `nota_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `categoria_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prioridade_id` bigint(20) unsigned DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `concluido` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notas_user_id_foreign` (`user_id`),
  KEY `notas_categoria_id_foreign` (`categoria_id`),
  KEY `notas_prioridade_id_foreign` (`prioridade_id`),
  CONSTRAINT `notas_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_prioridade_id_foreign` FOREIGN KEY (`prioridade_id`) REFERENCES `prioridades` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas`
--

LOCK TABLES `notas` WRITE;
/*!40000 ALTER TABLE `notas` DISABLE KEYS */;
INSERT INTO `notas` VALUES (1,1,'aa','a',1,'2025-08-12 00:44:15','2025-08-12 00:44:15',1,NULL,NULL,0,NULL,NULL),(2,1,'a','<p>Spacing utilities that apply to all breakpoints, from xs to xxl, have no breakpoint abbreviation in them. This is because those classes are applied from min-width: 0 and up, and thus are not bound by a media query. The remaining breakpoints, however, do include a breakpoint abbreviation.\r\n\r\nThe classes are named using the format {property}{sides}-{size} for xs and {property}{sides}-{breakpoint}-{size} for sm, md, lg, xl, and xxl.\r\n\r\nWhere property is one of:\r\n\r\nm - for classes that set margin\r\np - for classes that set padding</p>',1,'2025-08-12 02:00:26','2025-08-25 02:48:39',2,NULL,'2025-08-20',0,NULL,NULL),(3,1,'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','a',1,'2025-08-12 02:01:23','2025-08-12 02:20:46',2,NULL,'2025-08-11',0,NULL,NULL),(4,1,'Spacing utilities that apply to all breakpoints, from xs to xxl, have no breakpoint abbreviation in them. This is because those','<p><span style=\"color: rgb(230, 0, 0);\">m</span></p>',1,'2025-08-12 02:01:49','2025-08-20 14:00:52',1,NULL,'2025-08-11',0,NULL,NULL),(7,1,'a','<h3>Exemplo de Lista de Tarefas                </h3><p>Você pode usar <strong>formatação rica</strong> junto com as tarefas!</p>',1,'2025-08-16 01:37:12','2025-08-25 02:48:07',1,NULL,'2025-08-24',0,NULL,NULL),(8,1,',','<p><br></p><p class=\"ql-align-justify\"><strong style=\"background-color: rgb(230, 0, 0); color: rgb(255, 194, 102);\">hihpi </strong></p><ol><li data-list=\"unchecked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>dewgtgerh</li><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>fegerge</li></ol>',1,'2025-08-20 12:28:06','2025-08-25 00:29:03',1,NULL,'2025-08-20',0,NULL,NULL),(9,1,'b','<p><span style=\"color: rgb(160, 161, 167);\">- Adicione botões como faria antes --&gt;</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>  <span style=\"color: rgb(80, 161, 79);\">&lt;</span><span style=\"color: rgb(228, 86, 73);\">botão </span><span style=\"color: rgb(183, 107, 1);\">classe</span><span style=\"color: rgb(80, 161, 79);\">=O \"ql-negritoO \"› ›&lt;/</span><span style=\"color: rgb(228, 86, 73);\">botão</span><span style=\"color: rgb(80, 161, 79);\">› ›</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>  <span style=\"color: rgb(80, 161, 79);\">&lt;</span><span style=\"color: rgb(228, 86, 73);\">botão </span><span style=\"color: rgb(183, 107, 1);\">classe</span><span style=\"color: rgb(80, 161, 79);\">=O \"ql-itálicoO \"› ›&lt;/</span><span style=\"color: rgb(228, 86, 73);\">botão</span><span style=\"color: rgb(80, 161, 79);\">› ›</span></li></ol><p><br></p><p>  <span style=\"color: rgb(160, 161, 167);\">&lt;!-- Mas você também pode adicionar o seu --&gt;</span></p><p><br></p><ol><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(80, 161, 79);\">&lt;</span><span style=\"color: rgb(228, 86, 73);\">div. </span><span style=\"color: rgb(183, 107, 1);\">id.</span><span style=\"color: rgb(80, 161, 79);\">=O \"editorO \"› ›&lt;/</span><span style=\"color: rgb(228, 86, 73);\">div.</span><span style=\"color: rgb(80, 161, 79);\">› ›</span></li></ol><p><br></p><p><span style=\"color: rgb(80, 161, 79);\">&lt;</span><span style=\"color: rgb(228, 86, 73);\">roteiro</span><span style=\"color: rgb(80, 161, 79);\">› ›</span></p><p>  <span style=\"color: rgb(166, 38, 164);\">const</span> pena <span style=\"color: rgb(64, 120, 242);\">=</span> <span style=\"color: rgb(166, 38, 164);\">novo novo</span> <span style=\"color: rgb(183, 107, 1);\">Quill</span><span style=\"color: rgb(80, 161, 79);\">((S)‘#editor’‚,</span> <span style=\"color: rgb(80, 161, 79);\">{</span></p><p>    <span style=\"color: rgb(228, 86, 73);\">módulos</span><span style=\"color: rgb(64, 120, 242);\">¡::</span> <span style=\"color: rgb(80, 161, 79);\">{</span></p><p>      <span style=\"color: rgb(228, 86, 73);\">barra-de-ferramentas</span><span style=\"color: rgb(64, 120, 242);\">¡::</span> <span style=\"color: rgb(80, 161, 79);\">‘#toolbar’‚,</span></p><p>    <span style=\"color: rgb(80, 161, 79);\">O}‚,</span></p><p>  <span style=\"color: rgb(80, 161, 79);\">O}(S)¡;</span></p><p><br></p>',1,'2025-08-21 00:20:17','2025-08-25 02:27:04',1,NULL,'2025-08-20',1,'2025-08-24 20:37:22',NULL),(10,1,'i','<ol><li data-list=\"unchecked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>tir</li><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>i7o</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>l</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>l</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>l</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>l</li></ol>',1,'2025-08-25 00:29:21','2025-09-09 23:18:39',1,NULL,'2025-09-25',0,NULL,NULL),(11,1,'a','<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>a</li><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>aa</li></ol>',1,'2025-08-25 02:00:00','2025-09-03 22:48:47',1,NULL,'2025-08-24',0,NULL,NULL),(12,1,'mkmk','<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>a</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>b</li><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>c</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>d</li></ol><p><br></p><p>LISTA DE COMPRAS:</p><ol><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(230, 0, 0);\">TOMATE</span></li><li data-list=\"unchecked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>ARROZ</li><li data-list=\"unchecked\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(161, 0, 0);\">FEIJAO</span></li></ol>',1,'2025-08-25 02:04:16','2025-09-09 23:44:27',1,NULL,'2025-09-09',1,'2025-09-09 20:44:27',NULL),(13,1,'asa','<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>aa</li><li data-list=\"checked\"><span class=\"ql-ui\" contenteditable=\"false\"></span>a</li></ol>',1,'2025-08-25 03:13:01','2025-09-09 23:44:24',1,NULL,'2025-09-10',1,'2025-09-09 20:44:24',NULL),(14,1,'eeeeeeeeeeeeeee','<p>e</p>',1,'2025-09-09 23:05:54','2025-09-09 23:06:05',1,NULL,NULL,0,NULL,'2025-09-09 23:06:05'),(15,1,'www','<p>www</p>',1,'2025-09-09 23:06:20','2025-09-09 23:06:20',1,NULL,'2025-09-10',0,NULL,NULL);
/*!40000 ALTER TABLE `notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('189f7a59-290d-46f2-b9e3-d67c0b690fae','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":12,\"titulo\":\"mkmk\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?12\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:16:44','2025-09-09 23:16:44'),('1e6e8327-5c90-467e-88d5-cfe32d6879d4','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:18:00','2025-09-09 23:18:00'),('38660211-44a7-4ffc-84f5-8c1669ffcd82','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":13,\"titulo\":\"asa\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?13\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:18:58','2025-09-09 23:18:58'),('4bd582b1-c48f-47a8-a667-2fbf7e636d07','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:20:05','2025-09-09 23:20:05'),('5a7cd1ef-def3-4fb7-a902-f925fdcbedbd','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":13,\"titulo\":\"asa\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?13\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:17:58','2025-09-09 23:17:58'),('67a282bb-f7eb-4f20-9827-1da33b5752f9','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:49:26','2025-09-09 23:49:26'),('6910197b-64a2-47fd-bd3c-e4e4d9a81612','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:44:43','2025-09-09 23:44:43'),('776c96a0-9390-49af-a7ca-eaf4b34b04cd','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:16:47','2025-09-09 23:16:47'),('982278e6-83aa-4b52-8489-2df2c8d60c0b','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost:8000\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:56:44','2025-09-09 23:56:44'),('9c1bcc34-56a5-468c-8192-f07b88a63104','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":12,\"titulo\":\"mkmk\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?12\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:18:56','2025-09-09 23:18:56'),('bc2aecbd-65d5-4342-b9c3-2744c2f8b3dc','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:06:59','2025-09-09 23:06:59'),('d0c932b7-a048-47fd-85a4-1248960b5be7','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":13,\"titulo\":\"asa\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?13\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:20:03','2025-09-09 23:20:03'),('dc5e8586-327b-493f-b037-6589cad69814','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":12,\"titulo\":\"mkmk\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?12\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:20:00','2025-09-09 23:20:00'),('e54632bb-f2a4-492b-85f5-5212c65f5b80','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:19:01','2025-09-09 23:19:01'),('f48a352a-85c4-4a90-bfaf-b03797dd8e3a','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":12,\"titulo\":\"mkmk\",\"dias_restantes\":0,\"data_vencimento\":\"2025-09-09T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?12\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:17:56','2025-09-09 23:17:56'),('f9b09692-50a5-40dc-8cd5-ba1ca04ba9ba','App\\Notifications\\NotaExpiringNotification','App\\Models\\User',1,'{\"nota_id\":15,\"titulo\":\"www\",\"dias_restantes\":1,\"data_vencimento\":\"2025-09-10T03:00:00.000000Z\",\"url\":\"http:\\/\\/localhost\\/notas?15\",\"tipo\":\"vencimento\"}',NULL,'2025-09-09 23:55:00','2025-09-09 23:55:00');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('mariane012.12@gmail.com','$2y$12$MiM9KOLqkWEp43itnDLLsOTEJoD4CHMkOy3nfuv0/U89lvPA7DiNa','2025-09-08 23:05:07');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prioridades`
--

DROP TABLE IF EXISTS `prioridades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prioridades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cor` varchar(255) NOT NULL DEFAULT '#000000',
  `nivel` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prioridades_nivel_unique` (`nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prioridades`
--

LOCK TABLES `prioridades` WRITE;
/*!40000 ALTER TABLE `prioridades` DISABLE KEYS */;
INSERT INTO `prioridades` VALUES (1,'Baixa','#4CAF50',1,'2025-08-12 00:43:11','2025-08-12 00:43:11'),(2,'Média','#FFC107',2,'2025-08-12 00:43:11','2025-08-12 00:43:11'),(3,'Alta','#F44336',3,'2025-08-12 00:43:11','2025-08-12 00:43:11');
/*!40000 ALTER TABLE `prioridades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cor` varchar(255) NOT NULL DEFAULT '#000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_user_id_foreign` (`user_id`),
  CONSTRAINT `tags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,1,'contas','#000000','2025-08-12 03:23:01','2025-08-12 03:23:01'),(2,1,'teste2','#000000','2025-08-12 03:31:15','2025-08-12 03:31:15');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `notificacoes_ativas` tinyint(1) NOT NULL DEFAULT 1,
  `preferencias_notificacao` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferencias_notificacao`)),
  `telefone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mariane','mariane012.12@gmail.com',1,'{\"email\":true,\"whatsapp\":false,\"dias_antecedencia\":[\"7\",\"0\"],\"horario_envio\":\"15:00\"}',NULL,'2025-09-03 01:06:35','$2y$12$ElVE8gj.NkaEF9xR/4ydsuRwFz/r1HqG4XrGoRifqnQVanDUlsEmW','q8w2erxiK5inErRDXaV6EXQ1pwUWkDnVJTPIS2iWjFFRhTXPXbUO2hKKqVJa','2025-08-12 00:43:52','2025-09-22 12:51:59'),(2,'teste','teste@gmail.com',1,NULL,NULL,NULL,'$2y$12$qILL5c2mwxYAVlG0B0LYLuPyPyPJATlQ5W9aEoULQSk2QNYgxTtei',NULL,'2025-08-30 03:00:19','2025-08-30 03:00:19'),(4,'m','renildasilvatasca@gmail.com',1,NULL,NULL,NULL,'$2y$12$SB04CeUfCjY1Uo.IF.FW2ePuvG4HG.qlQq8MgeE/I3hf5Vmh2gsH2',NULL,'2025-09-03 00:02:19','2025-09-03 00:02:19'),(5,'m','as@efgg.com',1,NULL,NULL,NULL,'$2y$12$iHcHwn3vYCDPHabj/SlkoOJatEamFFwYPql4wnyzp2PkzqzWYhYFe',NULL,'2025-09-03 00:53:55','2025-09-03 00:53:55'),(6,'a','as@aaaa.com.br',1,NULL,NULL,NULL,'$2y$12$HifBp9FcOjUpSfZznAAvR.9FftxJaJM7Kb5Ai.tf/wSYpBmDz1ET2',NULL,'2025-09-03 00:55:43','2025-09-03 00:55:43'),(7,'mae','teste44@gmail.com',1,NULL,NULL,NULL,'$2y$12$W3Ye3KV32oW0qkonmMltvOITmTuYralBE.qPeaJB6O6alJjqKkBjC',NULL,'2025-09-03 22:44:58','2025-09-03 22:44:58'),(9,'mar','mar@gmail.com',1,NULL,NULL,NULL,'$2y$12$5oLViA2vsDdhgvHovXLs1uS4LydbM25rxn3Vk3GDjijcwx0qS6bXq',NULL,'2025-09-08 22:45:32','2025-09-08 22:45:32'),(10,'mar','mari@gmail.com',1,NULL,NULL,NULL,'$2y$12$/XtF2tVwRj1q2tdmw/EDQ.1tCPVqICRBrdqtALrxASu0P1SO4/zSO',NULL,'2025-09-08 22:47:58','2025-09-08 22:47:58');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-29  0:24:52
