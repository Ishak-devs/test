-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ecom2425
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adresses`
--

DROP TABLE IF EXISTS `adresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adresses` (
  `adresse_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `ligne1` varchar(255) NOT NULL,
  `ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(255) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `cree_le` date DEFAULT NULL,
  `dmod` date DEFAULT NULL,
  PRIMARY KEY (`adresse_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `clients` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adresses`
--

LOCK TABLES `adresses` WRITE;
/*!40000 ALTER TABLE `adresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `adresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avis` (
  `avis_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `note` tinyint(1) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_avis` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`avis_id`),
  KEY `client_id` (`client_id`),
  KEY `produit_id` (`produit_id`),
  CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`utilisateur_id`) ON DELETE CASCADE,
  CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis`
--

LOCK TABLES `avis` WRITE;
/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `categorie_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) NOT NULL,
  PRIMARY KEY (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Ordinateurs'),(2,'Ecrans'),(3,'Souris'),(4,'Clavier'),(5,'Casques'),(6,'Disque dur'),(7,'Caméra'),(8,'Imprimantes');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `numero_telephone` varchar(20) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `token_date` datetime DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Jean','Dupont','jean.dupont@example.com','password1','0123456789',NULL,1,NULL),(2,'Marie','Curie','marie.curie@example.com','password2','9876543210',NULL,0,NULL),(10,'','','','$2y$10$VMnmL2/wsaw8w.BgVmHzEuvsSA2ZJ7K9ClKaa6c0dbJvGf9Axh0Vq','','3578a4b092a55d2a1211e738b33d1ff6',0,'2024-12-02 09:27:58'),(11,'aze','aze','azae@yahoo.fr','$2y$10$8CD3nD2agCsWW0naWa8H9.cU7DnQV2YJVjkTvwnSdfSE3aeTbyXeC','0689564512','19306124f6386c02e791154c502fd940',0,'2024-12-02 11:50:39'),(12,'aze','zae','aze.kcc@gmail.com','$2y$10$vZRleL/2axECLEy1NX5fJuoljEPnJsVOFSNFB4f4pUXKJEWkJWJ3a','0656897845','eff5fc536ffefd79a55c0124bcb0c180',0,'2024-12-02 12:17:55'),(13,'aze','aze','azeaa@gmail.com','$2y$10$ZGfo5vEePAUXxJ/mjML9MeWJBgFJgdq4hGrBITIxFjJaqtS/nWSDu','0656894578','c9aa3831e711e597eaa10ffbac9aa6a7',0,'2024-12-02 12:28:11'),(18,'aza','aza','contact.kcc0@gmail.com','$2y$10$dD7XGzLVYpTITWaaGz6/TeL5KAG8tznztnCyAvfvVTLG0MFhnQHb6','0656451256','a97d58477a28aceef4efb598119ae1a8',0,'2025-01-27 16:51:56');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commandes` (
  `commande_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `date_commande` datetime NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `adresse_id` int(11) NOT NULL,
  PRIMARY KEY (`commande_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `adresse_id` (`adresse_id`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `clients` (`utilisateur_id`),
  CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`adresse_id`) REFERENCES `adresses` (`adresse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_commandes`
--

DROP TABLE IF EXISTS `details_commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `details_commandes` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `adresse_id` int(11) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `commande_id` (`commande_id`),
  KEY `produit_id` (`produit_id`),
  KEY `adresse_id` (`adresse_id`),
  CONSTRAINT `details_commandes_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`commande_id`),
  CONSTRAINT `details_commandes_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`),
  CONSTRAINT `details_commandes_ibfk_3` FOREIGN KEY (`adresse_id`) REFERENCES `adresses` (`adresse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_commandes`
--

LOCK TABLES `details_commandes` WRITE;
/*!40000 ALTER TABLE `details_commandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `details_commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_panier`
--

DROP TABLE IF EXISTS `details_panier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `details_panier` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `panier_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `panier_id` (`panier_id`),
  KEY `produit_id` (`produit_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `details_panier_ibfk_1` FOREIGN KEY (`panier_id`) REFERENCES `paniers` (`panier_id`),
  CONSTRAINT `details_panier_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`),
  CONSTRAINT `details_panier_ibfk_3` FOREIGN KEY (`utilisateur_id`) REFERENCES `clients` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_panier`
--

LOCK TABLES `details_panier` WRITE;
/*!40000 ALTER TABLE `details_panier` DISABLE KEYS */;
/*!40000 ALTER TABLE `details_panier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fabricants`
--

DROP TABLE IF EXISTS `fabricants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fabricants` (
  `fabricant_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_fabricant` varchar(255) NOT NULL,
  PRIMARY KEY (`fabricant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fabricants`
--

LOCK TABLES `fabricants` WRITE;
/*!40000 ALTER TABLE `fabricants` DISABLE KEYS */;
INSERT INTO `fabricants` VALUES (1,'Dell'),(2,'HP'),(3,'Logitech'),(4,'Dell'),(5,'HP'),(6,'Logitech'),(7,'Dell'),(8,'HP'),(9,'Logitech');
/*!40000 ALTER TABLE `fabricants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paniers`
--

DROP TABLE IF EXISTS `paniers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paniers` (
  `panier_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `cree_le` date DEFAULT current_timestamp(),
  PRIMARY KEY (`panier_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `paniers_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `clients` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paniers`
--

LOCK TABLES `paniers` WRITE;
/*!40000 ALTER TABLE `paniers` DISABLE KEYS */;
/*!40000 ALTER TABLE `paniers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produits` (
  `produit_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite_stock` int(11) NOT NULL,
  `nombre_ventes` int(11) NOT NULL,
  `cree_le` date DEFAULT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `fabricant_id` int(11) NOT NULL,
  PRIMARY KEY (`produit_id`),
  KEY `categorie_id` (`categorie_id`),
  KEY `fabricant_id` (`fabricant_id`),
  CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`categorie_id`),
  CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`fabricant_id`) REFERENCES `fabricants` (`fabricant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
INSERT INTO `produits` VALUES (1,'Ordinateur Portable Dell XPS 13',1200.50,0,5,'2024-10-23','Ordinateur Portable',1,1),(2,'Souris Sans Fil Logitech MX Master',150.00,8,20,'2024-10-23','Souris Sans Fil',2,3),(3,'Clavier Mécanique HP',80.00,14,15,'2024-10-23','Clavier Mécanique',2,2),(4,'Ordinateur Portable Dell XPS 13',1200.50,10,5,'2024-10-23','Ordinateur Portable',1,1),(5,'Souris Sans Fil Logitech MX Master',150.00,0,20,'2024-10-23','Souris Sans Fil',2,3),(6,'Clavier Mécanique HP',80.00,25,15,'2024-10-23','Clavier Mécanique',2,2),(7,'Ordinateur Portable Dell XPS 13',1200.50,10,5,'2024-10-23','Ordinateur Portable',1,1),(8,'Souris Sans Fil Logitech MX Master',150.00,15,20,'2024-10-23','Souris Sans Fil',2,3),(9,'Clavier Mécanique HP',80.00,25,15,'2024-10-23','Clavier Mécanique',2,2),(11,'Souris sans fil',29.99,150,120,'2024-11-05','Souris sans fil',3,1),(12,'Souris gaming',59.99,100,80,'2024-11-05','Souris gaming',3,2),(13,'Souris ergonomique',49.99,120,90,'2024-11-05','Souris ergonomique',3,3),(14,'Souris USB',19.99,200,150,'2024-11-05','Souris USB',3,4),(15,'Souris Bluetooth',39.99,180,120,'2024-11-05','Souris Bluetooth',3,5),(16,'Clavier mécanique',129.99,100,60,'2024-11-05','Clavier mécanique',4,6),(17,'Clavier rétroéclairé',79.99,150,100,'2024-11-05','Clavier rétroéclairé',4,7),(18,'Clavier ergonomique',69.99,120,80,'2024-11-05','Clavier ergonomique',4,8),(19,'Clavier sans fil',39.99,200,150,'2024-11-05','Clavier sans fil',4,9),(20,'Clavier gaming',149.99,60,30,'2024-11-05','Clavier gaming',4,1),(21,'Casque Bluetooth',99.99,150,100,'2024-11-05','Casque Bluetooth',5,2),(22,'Casque gaming',149.99,120,80,'2024-11-05','Casque gaming',5,3),(23,'Casque stéréo',79.99,200,120,'2024-11-05','Casque stéréo',5,4),(24,'Casque sans fil',129.99,100,50,'2024-11-05','Casque sans fil',5,5),(25,'Casque avec microphone',69.99,180,90,'2024-11-05','Casque avec micro',5,6),(26,'Disque dur externe 1To',59.99,150,100,'2024-11-05','Disque dur externe',6,7),(27,'Disque dur SSD 500Go',99.99,120,80,'2024-11-05','Disque dur SSD',6,8),(28,'Disque dur 2To',139.99,100,50,'2024-11-05','Disque dur 2To',6,9),(29,'Disque dur portable 1To',89.99,180,120,'2024-11-05','Disque dur portable',6,1),(30,'Disque dur externe 3To',179.99,60,30,'2024-11-05','Disque dur 3To',6,2),(31,'Caméra de surveillance',129.99,100,70,'2024-11-05','Caméra surveillance',7,3),(32,'Caméra 4K',399.99,60,40,'2024-11-05','Caméra 4K',7,4),(33,'Caméra sport',199.99,120,90,'2024-11-05','Caméra sport',7,5),(34,'Caméra sans fil',249.99,80,60,'2024-11-05','Caméra sans fil',7,6),(35,'Caméra webcam',49.99,150,100,'2024-11-05','Caméra webcam',7,7),(36,'Imprimante laser',129.99,100,70,'2024-11-05','Imprimante laser',8,8),(37,'Imprimante jet d\'encre',79.99,150,100,'2024-11-05','Imprimante jet d\'encre',8,9),(38,'Imprimante multifonction',199.99,90,60,'2024-11-05','Imprimante multifonction',8,1),(39,'Imprimante 3D',499.99,40,30,'2024-11-05','Imprimante 3D',8,2),(40,'Imprimante photo',149.99,120,90,'2024-11-05','Imprimante photo',8,3);
/*!40000 ALTER TABLE `produits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tendances`
--

DROP TABLE IF EXISTS `tendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tendances` (
  `tendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_ajout` date DEFAULT NULL,
  `produit_id` int(11) NOT NULL,
  `nombre_ventes` int(11) NOT NULL,
  PRIMARY KEY (`tendance_id`),
  KEY `produit_id` (`produit_id`),
  CONSTRAINT `tendances_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tendances`
--

LOCK TABLES `tendances` WRITE;
/*!40000 ALTER TABLE `tendances` DISABLE KEYS */;
INSERT INTO `tendances` VALUES (1,'2024-11-01',1,150),(2,'2024-11-02',2,200),(3,'2024-11-03',3,75),(4,'2024-11-04',4,300),(5,'2024-11-05',5,50);
/*!40000 ALTER TABLE `tendances` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-29 10:35:09
