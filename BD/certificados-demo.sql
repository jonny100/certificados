-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.6-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema certificados
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ certificados;
USE certificados;

--
-- Table structure for table `certificados`.`certificado`
--

DROP TABLE IF EXISTS `certificado`;
CREATE TABLE `certificado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo_certificado_id` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_certificados_tipo_certificado_idx` (`tipo_certificado_id`),
  CONSTRAINT `fk_certificados_tipo_certificado` FOREIGN KEY (`tipo_certificado_id`) REFERENCES `tipo_certificado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`certificado`
--

/*!40000 ALTER TABLE `certificado` DISABLE KEYS */;
INSERT INTO `certificado` (`id`,`descripcion`,`tipo_certificado_id`,`estado`) VALUES 
 (1,'Asistencia a Curso',1,1),
 (2,'Aprobación de Curso',2,1);
/*!40000 ALTER TABLE `certificado` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`certificado_evento`
--

DROP TABLE IF EXISTS `certificado_evento`;
CREATE TABLE `certificado_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `certificado_id` int(11) DEFAULT NULL,
  `evento_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_certificado_evento_template1_idx` (`template_id`),
  KEY `fk_certificado_evento_certificado1_idx` (`certificado_id`),
  KEY `fk_certificado_evento_evento1_idx` (`evento_id`),
  CONSTRAINT `fk_certificado_evento_certificado1` FOREIGN KEY (`certificado_id`) REFERENCES `certificado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_certificado_evento_evento1` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_certificado_evento_template1` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`certificado_evento`
--

/*!40000 ALTER TABLE `certificado_evento` DISABLE KEYS */;
INSERT INTO `certificado_evento` (`id`,`template_id`,`certificado_id`,`evento_id`) VALUES 
 (1,1,1,1),
 (3,NULL,2,1),
 (4,1,1,2);
/*!40000 ALTER TABLE `certificado_evento` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`certificado_evento_firma`
--

DROP TABLE IF EXISTS `certificado_evento_firma`;
CREATE TABLE `certificado_evento_firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificado_evento_id` int(11) DEFAULT NULL,
  `firma_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_certificado_evento_firma_certificado_evento1_idx` (`certificado_evento_id`),
  KEY `fk_firma_firma1_idx` (`firma_id`),
  CONSTRAINT `FK_B5EA0DFF3DDA237F` FOREIGN KEY (`certificado_evento_id`) REFERENCES `certificado_evento` (`id`),
  CONSTRAINT `FK_B5EA0DFF505AEC11` FOREIGN KEY (`firma_id`) REFERENCES `firma` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`certificado_evento_firma`
--

/*!40000 ALTER TABLE `certificado_evento_firma` DISABLE KEYS */;
INSERT INTO `certificado_evento_firma` (`id`,`certificado_evento_id`,`firma_id`) VALUES 
 (1,1,1);
/*!40000 ALTER TABLE `certificado_evento_firma` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`certificado_evento_requisito`
--

DROP TABLE IF EXISTS `certificado_evento_requisito`;
CREATE TABLE `certificado_evento_requisito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requisito_id` int(11) DEFAULT NULL,
  `certificado_evento_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_evento_requisito_requisito1_idx` (`requisito_id`),
  KEY `fk_certificado_evento_requisito_certificado_evento1_idx` (`certificado_evento_id`),
  CONSTRAINT `fk_certificado_evento_requisito_certificado_evento1` FOREIGN KEY (`certificado_evento_id`) REFERENCES `certificado_evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_requisito_requisito1` FOREIGN KEY (`requisito_id`) REFERENCES `requisito` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`certificado_evento_requisito`
--

/*!40000 ALTER TABLE `certificado_evento_requisito` DISABLE KEYS */;
INSERT INTO `certificado_evento_requisito` (`id`,`requisito_id`,`certificado_evento_id`) VALUES 
 (1,1,1),
 (3,2,3),
 (5,4,3),
 (6,1,3);
/*!40000 ALTER TABLE `certificado_evento_requisito` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`evento`
--

DROP TABLE IF EXISTS `evento`;
CREATE TABLE `evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(450) DEFAULT NULL,
  `fecha_ini` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `cupo` int(11) DEFAULT NULL,
  `evento_sgi_id` int(11) DEFAULT NULL,
  `tipo_evento_id` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `correspondiente` varchar(450) DEFAULT 'NULL',
  `resolucion` varchar(450) DEFAULT 'NULL',
  `horas` varchar(450) DEFAULT 'NULL',
  PRIMARY KEY (`id`),
  KEY `fk_evento_tipo_evento1_idx` (`tipo_evento_id`),
  CONSTRAINT `fk_evento_tipo_evento1` FOREIGN KEY (`tipo_evento_id`) REFERENCES `tipo_evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`evento`
--

/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` (`id`,`descripcion`,`fecha_ini`,`fecha_fin`,`cupo`,`evento_sgi_id`,`tipo_evento_id`,`estado`,`correspondiente`,`resolucion`,`horas`) VALUES 
 (1,'CICLO I','2019-10-01 00:00:00','2019-10-20 00:00:00',NULL,0,1,1,'Actualización Académica en Pensamiento Nacional','1258/2019','200'),
 (2,'CURSO DE ALEMAN','2019-11-04 00:00:00','2019-11-11 00:00:00',30,0,1,1,'LENGUAJE EXTRANJERO','156/968','200');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`firma`
--

DROP TABLE IF EXISTS `firma`;
CREATE TABLE `firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `codigo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`firma`
--

/*!40000 ALTER TABLE `firma` DISABLE KEYS */;
INSERT INTO `firma` (`id`,`descripcion`,`codigo`,`estado`) VALUES 
 (1,'directora miriam matouk','<p><input alt=\"\" src=\"https://upload.wikimedia.org/wikipedia/commons/f/f8/Firma_Len%C3%ADn_Moreno_Garc%C3%A9s.png\" style=\"width: 150px; height: 80px;\" type=\"image\" /></p>',1);
/*!40000 ALTER TABLE `firma` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`fos_user_group`
--

DROP TABLE IF EXISTS `fos_user_group`;
CREATE TABLE `fos_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_583D1F3E5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`fos_user_group`
--

/*!40000 ALTER TABLE `fos_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `fos_user_group` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`fos_user_user`
--

DROP TABLE IF EXISTS `fos_user_user`;
CREATE TABLE `fos_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `firstname` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `twitter_uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `gplus_uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gplus_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gplus_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_step_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C560D76192FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_C560D761A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_C560D761C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`fos_user_user`
--

/*!40000 ALTER TABLE `fos_user_user` DISABLE KEYS */;
INSERT INTO `fos_user_user` (`id`,`username`,`username_canonical`,`email`,`email_canonical`,`enabled`,`salt`,`password`,`last_login`,`confirmation_token`,`password_requested_at`,`roles`,`created_at`,`updated_at`,`date_of_birth`,`firstname`,`lastname`,`website`,`biography`,`gender`,`locale`,`timezone`,`phone`,`facebook_uid`,`facebook_name`,`facebook_data`,`twitter_uid`,`twitter_name`,`twitter_data`,`gplus_uid`,`gplus_name`,`gplus_data`,`token`,`two_step_code`) VALUES 
 (1,'admin','admin','admin@gmail.com','admin@gmail.com',1,NULL,'$2y$13$Y5kBdLwBkmZ/K3N6S3y/juPuLUMxMRuGQ6HQRgR1XKIkzM2cfwF6K','2019-11-13 19:25:53',NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}','2019-10-21 18:01:14','2019-11-13 19:25:53',NULL,NULL,NULL,NULL,NULL,'u',NULL,NULL,NULL,NULL,NULL,'null',NULL,NULL,'null',NULL,NULL,'null',NULL,NULL);
/*!40000 ALTER TABLE `fos_user_user` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`fos_user_user_group`
--

DROP TABLE IF EXISTS `fos_user_user_group`;
CREATE TABLE `fos_user_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_B3C77447A76ED395` (`user_id`),
  KEY `IDX_B3C77447FE54D947` (`group_id`),
  CONSTRAINT `FK_B3C77447A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B3C77447FE54D947` FOREIGN KEY (`group_id`) REFERENCES `fos_user_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`fos_user_user_group`
--

/*!40000 ALTER TABLE `fos_user_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `fos_user_user_group` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`inscripto`
--

DROP TABLE IF EXISTS `inscripto`;
CREATE TABLE `inscripto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_insc` datetime DEFAULT NULL,
  `evento_id` int(11) DEFAULT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `legajo` varchar(450) DEFAULT 'NULL',
  PRIMARY KEY (`id`),
  KEY `fk_inscripto_evento1_idx` (`evento_id`),
  KEY `fk_inscripto_persona1_idx` (`persona_id`),
  CONSTRAINT `fk_inscripto_evento1` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_persona1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`inscripto`
--

/*!40000 ALTER TABLE `inscripto` DISABLE KEYS */;
INSERT INTO `inscripto` (`id`,`fecha_insc`,`evento_id`,`persona_id`,`estado`,`legajo`) VALUES 
 (1,'2019-10-24 00:00:00',1,1,1,'1259/2019'),
 (2,'2019-10-27 00:00:00',1,2,1,'NULL');
/*!40000 ALTER TABLE `inscripto` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`inscripto_certificado`
--

DROP TABLE IF EXISTS `inscripto_certificado`;
CREATE TABLE `inscripto_certificado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_obt` datetime DEFAULT NULL,
  `inscripto_id` int(11) DEFAULT NULL,
  `certificado_evento_id` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `codigo_verificacion` varchar(450) DEFAULT 'NULL',
  PRIMARY KEY (`id`),
  KEY `fk_inscripto_certificado_inscripto1_idx` (`inscripto_id`),
  KEY `fk_inscripto_certificado_certificado_evento1_idx` (`certificado_evento_id`),
  KEY `fk_inscripto_certificado_codigoverificacion1_idx` (`codigo_verificacion`),
  CONSTRAINT `fk_inscripto_certificado_certificado_evento1` FOREIGN KEY (`certificado_evento_id`) REFERENCES `certificado_evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_certificado_inscripto1` FOREIGN KEY (`inscripto_id`) REFERENCES `inscripto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`inscripto_certificado`
--

/*!40000 ALTER TABLE `inscripto_certificado` DISABLE KEYS */;
INSERT INTO `inscripto_certificado` (`id`,`fecha_obt`,`inscripto_id`,`certificado_evento_id`,`estado`,`codigo_verificacion`) VALUES 
 (1,'2019-10-25 00:00:00',1,1,1,'19102817561666'),
 (4,'2019-10-27 00:00:00',1,3,1,'NULL'),
 (5,'2019-10-27 00:00:00',2,1,1,'NULL');
/*!40000 ALTER TABLE `inscripto_certificado` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`inscripto_evento_requisito`
--

DROP TABLE IF EXISTS `inscripto_evento_requisito`;
CREATE TABLE `inscripto_evento_requisito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inscripto_id` int(11) DEFAULT NULL,
  `certificado_evento_requisito_id` int(11) DEFAULT NULL,
  `excluir` tinyint(1) DEFAULT NULL COMMENT 'para casos en que un inscripto no necesite cumplir el requisito',
  PRIMARY KEY (`id`),
  KEY `fk_inscripto_evento_requisito_inscripto1_idx` (`inscripto_id`),
  KEY `fk_inscripto_evento_requisito_certificado_evento_requisito1_idx` (`certificado_evento_requisito_id`),
  CONSTRAINT `fk_inscripto_evento_requisito_certificado_evento_requisito1` FOREIGN KEY (`certificado_evento_requisito_id`) REFERENCES `certificado_evento_requisito` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_evento_requisito_inscripto1` FOREIGN KEY (`inscripto_id`) REFERENCES `inscripto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`inscripto_evento_requisito`
--

/*!40000 ALTER TABLE `inscripto_evento_requisito` DISABLE KEYS */;
INSERT INTO `inscripto_evento_requisito` (`id`,`inscripto_id`,`certificado_evento_requisito_id`,`excluir`) VALUES 
 (3,1,1,0),
 (7,2,1,0),
 (9,1,3,0),
 (10,1,5,0),
 (12,2,5,0),
 (13,1,6,0),
 (14,2,6,0);
/*!40000 ALTER TABLE `inscripto_evento_requisito` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificados`.`migration_versions`
--

/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apellido_nombre` varchar(450) DEFAULT NULL,
  `dni` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `sexo` varchar(45) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`persona`
--

/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` (`id`,`apellido_nombre`,`dni`,`direccion`,`email`,`telefono`,`sexo`,`fecha_nac`,`estado`) VALUES 
 (1,'RAMIREZ JONATHAN ALEXIS','33137641','CALLE 2 326','jonny_0385@hotmail.com','4221259','M','1987-08-18',1),
 (2,'FRAGALITI MARIA BELEN','32568784','3 DE FEBRERO 330','belenfragaliti@gmail.com','154896571','F','1987-01-15',1);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`requisito`
--

DROP TABLE IF EXISTS `requisito`;
CREATE TABLE `requisito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`requisito`
--

/*!40000 ALTER TABLE `requisito` DISABLE KEYS */;
INSERT INTO `requisito` (`id`,`descripcion`,`estado`) VALUES 
 (1,'ASISTIR AL EVENTO',1),
 (2,'APROBAR EL EVENTO',1),
 (3,'PAGAR INSCRIPCION',1),
 (4,'PAGAR CERTIFICACIÓN',1);
/*!40000 ALTER TABLE `requisito` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `codigo` longtext DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`template`
--

/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` (`id`,`descripcion`,`codigo`,`estado`) VALUES 
 (1,'curso_template','<p>Por cuanto #el-la# #apellidoynombre#<br />\r\nDNI N&ordm; #dni# Legajo N&deg; #legajo#&nbsp; &nbsp;ha aprobado el <strong>#cursonombre#</strong> que corresponde a la:</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>#correspondiente#</strong></p>\r\n\r\n<p>Resoluci&oacute;n Rectoral N.&ordm; #resolucion#, con un total de #horas# hs reloj, por el cual se le otorga el presente certificado.<br />\r\nSe &nbsp;expide el mismo en la provincia de Santiago del Estero, Rep&uacute;blica Argentina.<br />\r\nA los #dia#&nbsp;d&iacute;as del mes de #mes#&nbsp;del a&ntilde;o #anio#</p>',1);
/*!40000 ALTER TABLE `template` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`tipo_certificado`
--

DROP TABLE IF EXISTS `tipo_certificado`;
CREATE TABLE `tipo_certificado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='	';

--
-- Dumping data for table `certificados`.`tipo_certificado`
--

/*!40000 ALTER TABLE `tipo_certificado` DISABLE KEYS */;
INSERT INTO `tipo_certificado` (`id`,`descripcion`,`estado`) VALUES 
 (1,'Asistencia',1),
 (2,'Aprobación',1),
 (3,'Disertante',1);
/*!40000 ALTER TABLE `tipo_certificado` ENABLE KEYS */;


--
-- Table structure for table `certificados`.`tipo_evento`
--

DROP TABLE IF EXISTS `tipo_evento`;
CREATE TABLE `tipo_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `certificados`.`tipo_evento`
--

/*!40000 ALTER TABLE `tipo_evento` DISABLE KEYS */;
INSERT INTO `tipo_evento` (`id`,`descripcion`,`estado`) VALUES 
 (1,'Curso',1);
/*!40000 ALTER TABLE `tipo_evento` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
