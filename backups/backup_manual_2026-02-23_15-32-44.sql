-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sistema_mineracao
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `abastecimentos`
--

DROP TABLE IF EXISTS `abastecimentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abastecimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `veiculo_id` int(11) DEFAULT NULL,
  `tipo_combustivel` enum('Gasolina','Alcool','Diesel') NOT NULL DEFAULT 'Diesel',
  `motorista_id` int(11) DEFAULT NULL,
  `litros` decimal(10,2) DEFAULT NULL,
  `horimetro` int(11) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `veiculo_id` (`veiculo_id`),
  CONSTRAINT `abastecimentos_ibfk_1` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abastecimentos`
--

LOCK TABLES `abastecimentos` WRITE;
/*!40000 ALTER TABLE `abastecimentos` DISABLE KEYS */;
INSERT INTO `abastecimentos` VALUES (1,2,'Diesel',NULL,20.00,1025252,'2026-02-12 18:15:40'),(2,2,'Diesel',NULL,3000.00,84555,'2026-02-12 18:20:27'),(3,2,'Diesel',NULL,4444.00,25487878,'2026-02-12 19:22:52'),(4,2,'Diesel',NULL,500.00,156556,'2026-02-13 13:20:03');
/*!40000 ALTER TABLE `abastecimentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('Oficina','Posto','Pecas','Outro') DEFAULT NULL,
  `contato` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fornecedores`
--

LOCK TABLES `fornecedores` WRITE;
/*!40000 ALTER TABLE `fornecedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `fornecedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `cargo` enum('Administrador','Gerente','Balanca','Frentista','Motorista','Mecanico') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha_hash` varchar(255) DEFAULT NULL,
  `uuid_cracha` varchar(36) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `uuid_cracha` (`uuid_cracha`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionarios`
--

LOCK TABLES `funcionarios` WRITE;
/*!40000 ALTER TABLE `funcionarios` DISABLE KEYS */;
INSERT INTO `funcionarios` VALUES (1,'Administrador','000.000.000-00','Administrador','admin@mineradora.com','$2y$10$aUXu7A1XW5rpwH4w.TZ7A.vBTHz5zbJKyo20iC2B21.NpvdhAbp4e','ADM-MASTER','2026-02-12 17:56:37',NULL),(2,'Guilherme rinco silva pareira','145.924.116-92','Administrador','mourafu3seti@gmail.com','$2y$10$zy1hWlsMRzNP7ztj8EzGkugQWoBfoO5ppB5lstnLVZYmmrms3xpY.','FUNC-698e19cf1b0d7','2026-02-12 18:19:59',NULL),(3,'Eder Junio dias Silva','195.066.178-48','Administrador','mourafu3seeti@gmail.com','$2y$10$ylvs2Ggsh01bRWuoz4egP.sxHlLDC9XF60pWCSmFBm83yGVLoarCS','FUNC-698e1c5c396e0','2026-02-12 18:30:52','foto_FUNC-698e1c5c396e0.jpg'),(5,'Leonardo','19506617848','Frentista','mourafuseti@hotmail.com','$2y$10$2iDzlf5Ua7DWPk69eo30VeBj3MhrxlRK/ttSjH961S4WtKuUqpu4m','USER-699c537388c5f','2026-02-23 13:17:39',NULL),(6,'Paulo','14592411692','Balanca','balanca@hotmail.com','$2y$10$q.1a60ltCXKYj11wIj8UauOObYBWHe8hb3W9IHwlMwqfN0xJWgVSW','USER-699c55dd9ee86','2026-02-23 13:27:57',NULL),(7,'Carlos','19506618848','Mecanico','oficina@hotmail.com','$2y$10$K5Sc3m.PVzO41iQPaR40W.iYI17OUwxdAAK.UfEsWddeDanB/vmzC','USER-699c560d94dae','2026-02-23 13:28:45',NULL),(8,'Cezar','19506617849','Gerente','gerente@hotmail.com','$2y$10$izep4RQBZOins1Ne33OOWehCUDZIVMzyWMWVYWoG9To/82c7UBp/e','USER-699c563f60990','2026-02-23 13:29:35',NULL);
/*!40000 ALTER TABLE `funcionarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_sistema`
--

DROP TABLE IF EXISTS `logs_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(255) DEFAULT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `funcionarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_sistema`
--

LOCK TABLES `logs_sistema` WRITE;
/*!40000 ALTER TABLE `logs_sistema` DISABLE KEYS */;
INSERT INTO `logs_sistema` VALUES (1,1,'Abasteceu cgpw3434: 500 L de Diesel','2026-02-13 13:20:03'),(2,1,'Criou usuário de sistema: mourafuseti@gmail.com','2026-02-23 13:17:39'),(3,1,'Atualizou acessos do usuário: Leonardo','2026-02-23 13:17:50'),(4,1,'Atualizou acessos do usuário: Leonardo','2026-02-23 13:18:42'),(5,1,'Atualizou acessos do usuário: Guilherme rinco silva pareira','2026-02-23 13:20:15'),(6,1,'Atualizou acessos do usuário: Eder Junio dias Silva','2026-02-23 13:20:25'),(7,1,'Fez login no portal','2026-02-23 17:21:41'),(8,1,'Atualizou acessos do usuário: Leonardo','2026-02-23 13:21:51'),(9,1,'Fez login no portal','2026-02-23 17:22:21'),(10,1,'Atualizou acessos do usuário: Leonardo','2026-02-23 13:22:47'),(11,5,'Fez login no portal','2026-02-23 17:22:54'),(12,1,'Fez login no portal','2026-02-23 17:24:11'),(13,5,'Fez login no portal','2026-02-23 17:25:46'),(14,1,'Fez login no portal','2026-02-23 17:26:45'),(15,1,'Criou usuário de sistema: balanca@hotmail.com','2026-02-23 13:27:57'),(16,1,'Criou usuário de sistema: oficina@hotmail.com','2026-02-23 13:28:45'),(17,1,'Criou usuário de sistema: gerente@hotmail.com','2026-02-23 13:29:35'),(18,8,'Fez login no portal','2026-02-23 17:29:55'),(19,7,'Fez login no portal','2026-02-23 17:30:29'),(20,6,'Fez login no portal','2026-02-23 17:31:10'),(21,1,'Fez login no portal','2026-02-23 17:32:01'),(22,1,'Fez login no portal','2026-02-23 17:36:20'),(23,1,'Fez login no portal','2026-02-23 18:10:05'),(24,1,'Fez logout (saiu do sistema)','2026-02-23 18:12:25'),(25,1,'Fez login no portal','2026-02-23 18:12:34');
/*!40000 ALTER TABLE `logs_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manutencao_pecas`
--

DROP TABLE IF EXISTS `manutencao_pecas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manutencao_pecas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manutencao_id` int(11) NOT NULL,
  `peca_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manutencao_id` (`manutencao_id`),
  KEY `peca_id` (`peca_id`),
  CONSTRAINT `manutencao_pecas_ibfk_1` FOREIGN KEY (`manutencao_id`) REFERENCES `manutencoes` (`id`),
  CONSTRAINT `manutencao_pecas_ibfk_2` FOREIGN KEY (`peca_id`) REFERENCES `pecas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manutencao_pecas`
--

LOCK TABLES `manutencao_pecas` WRITE;
/*!40000 ALTER TABLE `manutencao_pecas` DISABLE KEYS */;
INSERT INTO `manutencao_pecas` VALUES (1,1,1,1),(2,1,1,1);
/*!40000 ALTER TABLE `manutencao_pecas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manutencoes`
--

DROP TABLE IF EXISTS `manutencoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manutencoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `veiculo_id` int(11) NOT NULL,
  `tipo` enum('Corretiva','Preventiva','Preditiva') DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `prioridade` enum('Alta','Media','Baixa') DEFAULT NULL,
  `status` enum('Aberta','Em Andamento','Concluida') DEFAULT 'Aberta',
  `data_abertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_conclusao` datetime DEFAULT NULL,
  `solucao` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `veiculo_id` (`veiculo_id`),
  CONSTRAINT `manutencoes_ibfk_1` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manutencoes`
--

LOCK TABLES `manutencoes` WRITE;
/*!40000 ALTER TABLE `manutencoes` DISABLE KEYS */;
INSERT INTO `manutencoes` VALUES (1,2,'Preventiva','ddddd','Baixa','Aberta','2026-02-12 19:01:56',NULL,'');
/*!40000 ALTER TABLE `manutencoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pecas`
--

DROP TABLE IF EXISTS `pecas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pecas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `quantidade` int(11) DEFAULT 0,
  `minimo` int(11) DEFAULT 5,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pecas`
--

LOCK TABLES `pecas` WRITE;
/*!40000 ALTER TABLE `pecas` DISABLE KEYS */;
INSERT INTO `pecas` VALUES (1,'0001','Oleo caminão',120,5);
/*!40000 ALTER TABLE `pecas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesagens`
--

DROP TABLE IF EXISTS `pesagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `veiculo_id` int(11) NOT NULL,
  `material` varchar(50) DEFAULT NULL,
  `peso_bruto` decimal(10,2) DEFAULT NULL,
  `tara` decimal(10,2) DEFAULT NULL,
  `peso_liquido` decimal(10,2) DEFAULT NULL,
  `data_pesagem` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `veiculo_id` (`veiculo_id`),
  CONSTRAINT `pesagens_ibfk_1` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesagens`
--

LOCK TABLES `pesagens` WRITE;
/*!40000 ALTER TABLE `pesagens` DISABLE KEYS */;
INSERT INTO `pesagens` VALUES (1,2,'Minerio de Ferro',50000.00,1500.00,NULL,'2026-02-12 18:16:13'),(2,2,'Minerio de Ferro',70000.00,1500.00,NULL,'2026-02-12 18:21:01');
/*!40000 ALTER TABLE `pesagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `veiculos`
--

DROP TABLE IF EXISTS `veiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `tipo` enum('Caminhao','Escavadeira','Leve','Outro') DEFAULT NULL,
  `status` enum('Ativo','Manutencao','Inativo') DEFAULT 'Ativo',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `ultima_atualizacao` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veiculos`
--

LOCK TABLES `veiculos` WRITE;
/*!40000 ALTER TABLE `veiculos` DISABLE KEYS */;
INSERT INTO `veiculos` VALUES (1,'V-698e15b7cf12a','cgpw3434','carro',NULL,'Ativo',NULL,NULL,NULL,'2026-02-12 18:02:31'),(2,'V-698e172ddaa13','cgpw3434','carro',NULL,'Manutencao',NULL,NULL,NULL,'2026-02-12 18:08:45'),(3,'V-698e2863e415a','CGW3434','carro',NULL,'Ativo',NULL,NULL,NULL,'2026-02-12 19:22:11');
/*!40000 ALTER TABLE `veiculos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-23 11:32:44
