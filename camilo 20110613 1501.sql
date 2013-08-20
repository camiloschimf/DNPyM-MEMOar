-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.35-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema dnpym
--

CREATE DATABASE IF NOT EXISTS dnpym;
USE dnpym;

--
-- Temporary table structure for view `vis_estados_niveles`
--
DROP TABLE IF EXISTS `vis_estados_niveles`;
DROP VIEW IF EXISTS `vis_estados_niveles`;
CREATE TABLE `vis_estados_niveles` (
  `codigo_referencia` varchar(255),
  `tipo` varbinary(11),
  `nombre` varchar(100),
  `estado` varchar(50),
  `fecha` timestamp,
  `cod_ref_sup` varchar(255)
);

--
-- Definition of table `acceso_documentacion`
--

DROP TABLE IF EXISTS `acceso_documentacion`;
CREATE TABLE `acceso_documentacion` (
  `acceso_documentacion` varchar(255) NOT NULL,
  PRIMARY KEY (`acceso_documentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acceso_documentacion`
--

/*!40000 ALTER TABLE `acceso_documentacion` DISABLE KEYS */;
INSERT INTO `acceso_documentacion` (`acceso_documentacion`) VALUES 
 ('Documentación afectada por la legislación vigente en materia de acceso '),
 ('Documentación en mal estado de conservación o restauración '),
 ('Documentación en proceso de organización.'),
 ('Fondos especiales (pergaminos, placas de vidrio, etc.).'),
 ('Toda la documentación es de libre acceso');
/*!40000 ALTER TABLE `acceso_documentacion` ENABLE KEYS */;


--
-- Definition of table `agregados`
--

DROP TABLE IF EXISTS `agregados`;
CREATE TABLE `agregados` (
  `codigo_agregado` int(11) NOT NULL AUTO_INCREMENT,
  `agregado` varchar(50) NOT NULL,
  `tipo_diplomatico` int(11) NOT NULL,
  PRIMARY KEY (`codigo_agregado`),
  UNIQUE KEY `agregado_UNIQUE` (`agregado`,`tipo_diplomatico`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agregados`
--

/*!40000 ALTER TABLE `agregados` DISABLE KEYS */;
INSERT INTO `agregados` (`codigo_agregado`,`agregado`,`tipo_diplomatico`) VALUES 
 (4,'agregado audio visual 1',9),
 (6,'agregado audiovisual 2',9),
 (7,'agregado sonoro 1',10),
 (8,'agregado sonoro 2',10),
 (9,'agregado textual 1',11),
 (10,'agregado textual 2',11),
 (1,'agregado visual 1',8),
 (3,'agregado visual 2',8);
/*!40000 ALTER TABLE `agregados` ENABLE KEYS */;


--
-- Definition of table `archivos_digitales`
--

DROP TABLE IF EXISTS `archivos_digitales`;
CREATE TABLE `archivos_digitales` (
  `codigo_archivo` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_referencia` varchar(255) NOT NULL,
  `fecha_toma_archivo` datetime DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `codigo_gestion` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo_archivo`),
  UNIQUE KEY `codigo_referencia_UNIQUE` (`codigo_referencia`,`fecha_toma_archivo`),
  KEY `fk_archivos_digitales_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_archivos_digitales_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_archivos_digitales_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_archivos_digitales_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `archivos_digitales`
--

/*!40000 ALTER TABLE `archivos_digitales` DISABLE KEYS */;
INSERT INTO `archivos_digitales` (`codigo_archivo`,`codigo_referencia`,`fecha_toma_archivo`,`tipo`,`extension`,`codigo_gestion`) VALUES 
 (14,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','2005-11-15 00:00:00',10,'mp3',1),
 (15,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','2009-09-10 00:00:00',10,'mp3',1),
 (16,'AA/URQ/VIS','1985-10-01 00:00:00',8,'jpg',NULL),
 (17,'AA/URQ/VIS','1980-10-01 00:00:00',8,'jpg',NULL),
 (18,'AA/URQ/VIS','2000-02-01 00:00:00',8,'jpg',NULL),
 (19,'AA/URQ/VIS','2008-10-01 00:00:00',8,'jpg',NULL),
 (20,'AA/URQ/VIS','2011-06-01 00:00:00',8,'jpg',NULL),
 (21,'AA/URQ/VIS','2011-10-10 00:00:00',8,'jpg',4),
 (22,'AA/URQ/SON1','2011-06-13 00:00:00',10,'mp3',5);
/*!40000 ALTER TABLE `archivos_digitales` ENABLE KEYS */;


--
-- Definition of table `area_de_notas_instituciones`
--

DROP TABLE IF EXISTS `area_de_notas_instituciones`;
CREATE TABLE `area_de_notas_instituciones` (
  `codigo_notas` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  `nota_descripcion` text,
  `fecha_descripcion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_notas`),
  KEY `fk_area_de_notas_instituciones_instituciones1` (`codigo_institucion`),
  CONSTRAINT `fk_area_de_notas_instituciones_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `area_de_notas_instituciones`
--

/*!40000 ALTER TABLE `area_de_notas_instituciones` DISABLE KEYS */;
INSERT INTO `area_de_notas_instituciones` (`codigo_notas`,`codigo_institucion`,`nota_descripcion`,`fecha_descripcion`,`fecha_modificacion`) VALUES 
 (1,'AR/ST/FFSDD/KASSD/GGe','<span style=\"FONT-FAMILY: Arial; COLOR: #ff4500; FONT-SIZE: 14pt\">Esta es una nota con respecto a la institucion correcpondiente</span>','2011-03-30 00:00:00',NULL),
 (2,'AR//BFG//DE//RE','nota 1 de la institucion estrellita','2011-05-20 00:00:00',NULL),
 (3,'AA','nota de un amor que mas da','2011-06-11 00:00:00',NULL),
 (4,'AR/SC/DNPYM/HS','ES una institucion reciente<br />\r\n','2011-06-13 00:00:00',NULL);
/*!40000 ALTER TABLE `area_de_notas_instituciones` ENABLE KEYS */;


--
-- Definition of table `areas_tips`
--

DROP TABLE IF EXISTS `areas_tips`;
CREATE TABLE `areas_tips` (
  `area` varchar(50) NOT NULL,
  PRIMARY KEY (`area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `areas_tips`
--

/*!40000 ALTER TABLE `areas_tips` DISABLE KEYS */;
INSERT INTO `areas_tips` (`area`) VALUES 
 ('Conservación'),
 ('Diplomaticos'),
 ('Instituciones'),
 ('Niveles');
/*!40000 ALTER TABLE `areas_tips` ENABLE KEYS */;


--
-- Definition of table `autores`
--

DROP TABLE IF EXISTS `autores`;
CREATE TABLE `autores` (
  `autor` varchar(100) NOT NULL,
  PRIMARY KEY (`autor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `autores`
--

/*!40000 ALTER TABLE `autores` DISABLE KEYS */;
INSERT INTO `autores` (`autor`) VALUES 
 ('Maria de los Angeles Medrano'),
 ('Maria Martha Serralima'),
 ('Mirtha Legrand'),
 ('Nini Marshal');
/*!40000 ALTER TABLE `autores` ENABLE KEYS */;


--
-- Definition of table `caracteristicas_montaje`
--

DROP TABLE IF EXISTS `caracteristicas_montaje`;
CREATE TABLE `caracteristicas_montaje` (
  `caracteristica_montaje` varchar(50) NOT NULL,
  PRIMARY KEY (`caracteristica_montaje`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caracteristicas_montaje`
--

/*!40000 ALTER TABLE `caracteristicas_montaje` DISABLE KEYS */;
INSERT INTO `caracteristicas_montaje` (`caracteristica_montaje`) VALUES 
 ('Caracteristicas del montaje 1'),
 ('Caracteristicas del montaje 2'),
 ('Caracteristicas del montaje 3'),
 ('Caracteristicas del montaje 4'),
 ('Caracteristicas del montaje 5');
/*!40000 ALTER TABLE `caracteristicas_montaje` ENABLE KEYS */;


--
-- Definition of table `caracteristicas_toma_fotografica`
--

DROP TABLE IF EXISTS `caracteristicas_toma_fotografica`;
CREATE TABLE `caracteristicas_toma_fotografica` (
  `caracteristica_toma_fotografica` varchar(50) NOT NULL,
  PRIMARY KEY (`caracteristica_toma_fotografica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caracteristicas_toma_fotografica`
--

/*!40000 ALTER TABLE `caracteristicas_toma_fotografica` DISABLE KEYS */;
INSERT INTO `caracteristicas_toma_fotografica` (`caracteristica_toma_fotografica`) VALUES 
 ('caracteristica de la toma fotografica 1'),
 ('caracteristica de la toma fotografica 2'),
 ('caracteristica de la toma fotografica 3');
/*!40000 ALTER TABLE `caracteristicas_toma_fotografica` ENABLE KEYS */;


--
-- Definition of table `contenedores`
--

DROP TABLE IF EXISTS `contenedores`;
CREATE TABLE `contenedores` (
  `contenedor` varchar(50) NOT NULL,
  PRIMARY KEY (`contenedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contenedores`
--

/*!40000 ALTER TABLE `contenedores` DISABLE KEYS */;
INSERT INTO `contenedores` (`contenedor`) VALUES 
 ('Disco Móvil'),
 ('Microfilm');
/*!40000 ALTER TABLE `contenedores` ENABLE KEYS */;


--
-- Definition of table `cromias`
--

DROP TABLE IF EXISTS `cromias`;
CREATE TABLE `cromias` (
  `cromia` varchar(50) NOT NULL,
  PRIMARY KEY (`cromia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cromias`
--

/*!40000 ALTER TABLE `cromias` DISABLE KEYS */;
INSERT INTO `cromias` (`cromia`) VALUES 
 ('cromia 1'),
 ('cromia 2'),
 ('cromia 3'),
 ('cromia 4');
/*!40000 ALTER TABLE `cromias` ENABLE KEYS */;


--
-- Definition of table `descriptores_geograficos`
--

DROP TABLE IF EXISTS `descriptores_geograficos`;
CREATE TABLE `descriptores_geograficos` (
  `descriptor_geografico` varchar(255) NOT NULL,
  PRIMARY KEY (`descriptor_geografico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `descriptores_geograficos`
--

/*!40000 ALTER TABLE `descriptores_geograficos` DISABLE KEYS */;
INSERT INTO `descriptores_geograficos` (`descriptor_geografico`) VALUES 
 ('descriptor geografico 1'),
 ('descriptor geografico 2'),
 ('descriptor geografico 3');
/*!40000 ALTER TABLE `descriptores_geograficos` ENABLE KEYS */;


--
-- Definition of table `descriptores_materias_contenidos`
--

DROP TABLE IF EXISTS `descriptores_materias_contenidos`;
CREATE TABLE `descriptores_materias_contenidos` (
  `descriptor_materias_contenido` varchar(255) NOT NULL,
  PRIMARY KEY (`descriptor_materias_contenido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `descriptores_materias_contenidos`
--

/*!40000 ALTER TABLE `descriptores_materias_contenidos` DISABLE KEYS */;
INSERT INTO `descriptores_materias_contenidos` (`descriptor_materias_contenido`) VALUES 
 ('Descriptor de Materia 1'),
 ('Descriptor de Materia 2'),
 ('Descriptor de Materia 3'),
 ('Descriptor de Materia 4'),
 ('Descriptor de Materia 5');
/*!40000 ALTER TABLE `descriptores_materias_contenidos` ENABLE KEYS */;


--
-- Definition of table `descriptores_onomasticos`
--

DROP TABLE IF EXISTS `descriptores_onomasticos`;
CREATE TABLE `descriptores_onomasticos` (
  `descriptor_onomastico` varchar(255) NOT NULL,
  PRIMARY KEY (`descriptor_onomastico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `descriptores_onomasticos`
--

/*!40000 ALTER TABLE `descriptores_onomasticos` DISABLE KEYS */;
INSERT INTO `descriptores_onomasticos` (`descriptor_onomastico`) VALUES 
 ('descriptor onomastico 1'),
 ('descriptor onomastico 2'),
 ('descriptor onomastico 3');
/*!40000 ALTER TABLE `descriptores_onomasticos` ENABLE KEYS */;


--
-- Definition of table `direcciones_instituciones`
--

DROP TABLE IF EXISTS `direcciones_instituciones`;
CREATE TABLE `direcciones_instituciones` (
  `codigo_direcciones` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `codigo_postal` varchar(50) DEFAULT NULL,
  `provincia` varchar(50) DEFAULT NULL,
  `casilla_correo` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `telefonos` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `tipo_direccion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_direcciones`),
  KEY `fk_direcciones_instituciones_instituciones` (`codigo_institucion`),
  KEY `fk_direcciones_instituciones_tipo_direcciones1` (`tipo_direccion`),
  CONSTRAINT `fk_direcciones_instituciones_instituciones` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_direcciones_instituciones_tipo_direcciones1` FOREIGN KEY (`tipo_direccion`) REFERENCES `tipo_direcciones` (`tipo_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `direcciones_instituciones`
--

/*!40000 ALTER TABLE `direcciones_instituciones` DISABLE KEYS */;
INSERT INTO `direcciones_instituciones` (`codigo_direcciones`,`codigo_institucion`,`calle`,`numero`,`codigo_postal`,`provincia`,`casilla_correo`,`ciudad`,`pais`,`telefonos`,`fax`,`tipo_direccion`) VALUES 
 (1,'AR/ST/FFSDD/KASSD/GGe','San Juan Pedro','324','1045','santamaria@hotmail.com','san pedro','lujan','arganistan','el mundo','42514 52152',NULL),
 (2,'AR//BFG//DE//RE','Uriburu','326','1045','CABA','CC2045AAC','Buenos Aires','Argentina','4569 4523','7856 2589',NULL),
 (3,'AA','pueryrredon','1234','1045','Buenos aires','CC3409','buenos Aires','Argentina','123456','234567',NULL),
 (4,'AR/SC/DNPYM/HS','Olazabal','123','1426','Buenos Aires','hsigal@hotmail.com','Capital Federal','Argentina','49239922','49235555',NULL);
/*!40000 ALTER TABLE `direcciones_instituciones` ENABLE KEYS */;


--
-- Definition of table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
CREATE TABLE `documentos` (
  `codigo_referencia` varchar(255) NOT NULL,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  `titulo_original` varchar(100) DEFAULT NULL,
  `titulo_atribuido` varchar(100) DEFAULT NULL,
  `titulo_traducido` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `numero_registro_inventario_anterior` varchar(50) DEFAULT NULL,
  `numero_inventario_unidad_documental` varchar(50) DEFAULT NULL,
  `cod_ref_sup` varchar(255) DEFAULT NULL,
  `tipo_general_documento` varchar(50) DEFAULT NULL,
  `tipo_especifico_documento` varchar(50) DEFAULT NULL,
  `tradicion_documental` varchar(50) DEFAULT NULL,
  `numero_registro_sur` varchar(100) DEFAULT NULL,
  `numero_registro_bibliografico` varchar(100) DEFAULT NULL,
  `sistema_organizacion` varchar(50) DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `nombre_productor` varchar(50) DEFAULT NULL,
  `fecha_edicion_anio_editorial` varchar(50) DEFAULT NULL,
  `fecha_accion_representada` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `signos_especiales` varchar(255) DEFAULT NULL,
  `fecha_inicial` datetime DEFAULT NULL,
  `fecha_final` datetime DEFAULT NULL,
  `alcance_contenido` varchar(255) DEFAULT NULL,
  `soporte` varchar(50) DEFAULT NULL,
  `duracion_metraje` varchar(50) DEFAULT NULL,
  `cromia` varchar(50) DEFAULT NULL,
  `tecnica_fotografica` varchar(50) DEFAULT NULL,
  `tecnica_visual` varchar(50) DEFAULT NULL,
  `tecnica_digital` varchar(50) DEFAULT NULL,
  `emulsion` varchar(50) DEFAULT NULL,
  `sonido` varchar(50) DEFAULT NULL,
  `integridad` varchar(50) DEFAULT NULL,
  `forma_presentacion_unidad` varchar(50) DEFAULT NULL,
  `cantidad_fojas_album` int(11) DEFAULT NULL,
  `caracteristica_montaje` varchar(50) DEFAULT NULL,
  `requisito_ejecucion` varchar(50) DEFAULT NULL,
  `unidades` int(11) DEFAULT NULL,
  `cantidad_envases_unidad_documental` int(11) DEFAULT NULL,
  `coleccion` varchar(100) DEFAULT NULL,
  `evento` varchar(255) DEFAULT NULL,
  `manifestacion` varchar(255) DEFAULT NULL,
  `forma_ingreso` varchar(50) DEFAULT NULL,
  `procedencia` text,
  `fecha_inicio_ingreso` varchar(50) DEFAULT NULL,
  `precio` varchar(50) DEFAULT NULL,
  `norma_legal_ingreso` varchar(50) DEFAULT NULL,
  `numero_legal_ingreso` varchar(50) DEFAULT NULL,
  `numero_administrativo` varchar(50) DEFAULT NULL,
  `derechos_restricciones` text,
  `titular_derecho` varchar(100) DEFAULT NULL,
  `tipo_acceso` varchar(50) DEFAULT NULL,
  `requisitos_acceso` varchar(100) DEFAULT NULL,
  `acceso_documentacion` varchar(255) DEFAULT NULL,
  `servicio_reproduccion` varchar(50) DEFAULT NULL,
  `publicaciones_instrumentos_accesos` text,
  `subsidios` text,
  `normativa_legal_baja` varchar(50) DEFAULT NULL,
  `numero_norma_legal` varchar(50) DEFAULT NULL,
  `motivo_baja` varchar(255) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `tipo_diplomatico` int(11) DEFAULT NULL,
  `situacion` varchar(50) DEFAULT 'Local',
  `fecha_ultimo_relevamiento_visu` datetime DEFAULT NULL,
  `tv` int(11) DEFAULT NULL,
  `na` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `fecha_ultima_modificacion` datetime DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `fecha_ultimo_tratamiento` datetime DEFAULT NULL,
  `restringido_exhibicion` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`),
  KEY `fk_documentos_tipos_especificos_documentos1` (`tipo_especifico_documento`),
  KEY `fk_documentos_tradiciones_documentales1` (`tradicion_documental`),
  KEY `fk_documentos_sistemas_organizaciones1` (`sistema_organizacion`),
  KEY `fk_documentos_autores1` (`autor`),
  KEY `fk_documentos_registro_autoridad1` (`nombre_productor`),
  KEY `fk_documentos_versiones1` (`version`),
  KEY `fk_documentos_generos1` (`genero`),
  KEY `fk_documentos_soportes1` (`soporte`),
  KEY `fk_documentos_cromias1` (`cromia`),
  KEY `fk_documentos_sonidos1` (`sonido`),
  KEY `fk_documentos_requisitos_ejecucion1` (`requisito_ejecucion`),
  KEY `fk_documentos_formas_ingreso1` (`forma_ingreso`),
  KEY `fk_documentos_norma_legal1` (`norma_legal_ingreso`),
  KEY `fk_documentos_tipos_accesos1` (`tipo_acceso`),
  KEY `fk_documentos_requisitos_accesos1` (`requisitos_acceso`),
  KEY `fk_documentos_acceso_documentacion1` (`acceso_documentacion`),
  KEY `fk_documentos_servicios_reproduccion1` (`servicio_reproduccion`),
  KEY `fk_documentos_norma_legal2` (`normativa_legal_baja`),
  KEY `fk_documentos_tecnicas_fotograficas1` (`tecnica_fotografica`),
  KEY `fk_documentos_tecnicas_visuales1` (`tecnica_visual`),
  KEY `fk_documentos_tecnicas_digitales1` (`tecnica_digital`),
  KEY `fk_documentos_emulsiones1` (`emulsion`),
  KEY `fk_documentos_fomas_presentacion_unidad1` (`forma_presentacion_unidad`),
  KEY `fk_documentos_caracteristicas_montaje1` (`caracteristica_montaje`),
  KEY `fk_documentos_niveles1` (`cod_ref_sup`),
  KEY `fk_documentos_instituciones1` (`codigo_institucion`),
  KEY `fk_documentos_tipos_general_documentos1` (`tipo_general_documento`),
  KEY `fk_documentos_usuarios1` (`usuario`),
  KEY `fk_documentos_situaciones1` (`situacion`),
  CONSTRAINT `fk_documentos_acceso_documentacion1` FOREIGN KEY (`acceso_documentacion`) REFERENCES `acceso_documentacion` (`acceso_documentacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_caracteristicas_montaje1` FOREIGN KEY (`caracteristica_montaje`) REFERENCES `caracteristicas_montaje` (`caracteristica_montaje`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_cromias1` FOREIGN KEY (`cromia`) REFERENCES `cromias` (`cromia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_emulsiones1` FOREIGN KEY (`emulsion`) REFERENCES `emulsiones` (`emulsion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_fomas_presentacion_unidad1` FOREIGN KEY (`forma_presentacion_unidad`) REFERENCES `fomas_presentacion_unidad` (`forma`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_formas_ingreso1` FOREIGN KEY (`forma_ingreso`) REFERENCES `formas_ingreso` (`forma_ingreso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_generos1` FOREIGN KEY (`genero`) REFERENCES `generos` (`genero`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_niveles1` FOREIGN KEY (`cod_ref_sup`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_norma_legal1` FOREIGN KEY (`norma_legal_ingreso`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_norma_legal2` FOREIGN KEY (`normativa_legal_baja`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_registro_autoridad1` FOREIGN KEY (`nombre_productor`) REFERENCES `registro_autoridad` (`nombre_productor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_requisitos_accesos1` FOREIGN KEY (`requisitos_acceso`) REFERENCES `requisitos_accesos` (`requisito_acceso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_requisitos_ejecucion1` FOREIGN KEY (`requisito_ejecucion`) REFERENCES `requisitos_ejecucion` (`requisito_ejecucion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_servicios_reproduccion1` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_sistemas_organizaciones1` FOREIGN KEY (`sistema_organizacion`) REFERENCES `sistemas_organizaciones` (`sistema_organizacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_situaciones1` FOREIGN KEY (`situacion`) REFERENCES `situaciones` (`situacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_sonidos1` FOREIGN KEY (`sonido`) REFERENCES `sonidos` (`sonido`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_soportes1` FOREIGN KEY (`soporte`) REFERENCES `soportes` (`soporte`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_digitales1` FOREIGN KEY (`tecnica_digital`) REFERENCES `tecnicas_digitales` (`tecnica_digital`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_fotograficas1` FOREIGN KEY (`tecnica_fotografica`) REFERENCES `tecnicas_fotograficas` (`tecnica_fotografica`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_visuales1` FOREIGN KEY (`tecnica_visual`) REFERENCES `tecnicas_visuales` (`tecnica_visual`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tipos_accesos1` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipos_accesos` (`tipo_acceso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tipos_especificos_documentos1` FOREIGN KEY (`tipo_especifico_documento`) REFERENCES `tipos_especificos_documentos` (`tipo_especifico_documento`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tipos_general_documentos1` FOREIGN KEY (`tipo_general_documento`) REFERENCES `tipos_general_documentos` (`tipo_general_documento`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tradiciones_documentales1` FOREIGN KEY (`tradicion_documental`) REFERENCES `tradiciones_documentales` (`tradicion_documental`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_versiones1` FOREIGN KEY (`version`) REFERENCES `versiones` (`version`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos`
--

/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
INSERT INTO `documentos` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`numero_inventario_unidad_documental`,`cod_ref_sup`,`tipo_general_documento`,`tipo_especifico_documento`,`tradicion_documental`,`numero_registro_sur`,`numero_registro_bibliografico`,`sistema_organizacion`,`autor`,`nombre_productor`,`fecha_edicion_anio_editorial`,`fecha_accion_representada`,`version`,`genero`,`signos_especiales`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`soporte`,`duracion_metraje`,`cromia`,`tecnica_fotografica`,`tecnica_visual`,`tecnica_digital`,`emulsion`,`sonido`,`integridad`,`forma_presentacion_unidad`,`cantidad_fojas_album`,`caracteristica_montaje`,`requisito_ejecucion`,`unidades`,`cantidad_envases_unidad_documental`,`coleccion`,`evento`,`manifestacion`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`tipo_acceso`,`requisitos_acceso`,`acceso_documentacion`,`servicio_reproduccion`,`publicaciones_instrumentos_accesos`,`subsidios`,`normativa_legal_baja`,`numero_norma_legal`,`motivo_baja`,`fecha_baja`,`tipo_diplomatico`,`situacion`,`fecha_ultimo_relevamiento_visu`,`tv`,`na`,`estado`,`fecha_creacion`,`fecha_ultima_modificacion`,`usuario`,`fecha_ultimo_tratamiento`,`restringido_exhibicion`) VALUES 
 ('AA/URQ/ADV1','AA',NULL,NULL,NULL,NULL,NULL,NULL,'AA/URQ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Local',NULL,2,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AA/URQ/ADV2','AA',NULL,NULL,NULL,NULL,NULL,NULL,'AA/URQ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Conservación','2008-10-10 00:00:00',2,NULL,1,NULL,NULL,NULL,NULL,0),
 ('AA/URQ/SON1','AA',NULL,NULL,NULL,NULL,NULL,NULL,'AA/URQ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'Conservación',NULL,2,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AA/URQ/VIS','AA','freddie te quire','freddie',NULL,'2008-10-10 00:00:00','245','1458','AA/URQ','Audiovisual','Cartas','Copia Fotográfica',NULL,NULL,'Maria martha serralima','Maria de los Angeles Medrano','Juan Jose Campanella','10 de enero de 2009','15 de mayo de 2010','version 1','genero 1','mas es mas','1810-10-10 00:00:00','1910-10-10 00:00:00','todos los alcances','Soporte 1','10 min','cromia 1','tecnica fotografica 1','tecnica visual 2','tecnica digital 3',NULL,'sonido 2','totalmente',NULL,20,'Caracteristicas del montaje 1','Requisito de Ejecucion 1',320,10,'15','teatro al aire libre','manifestacion contra los trabajadores del teatro colon',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Conservación',NULL,2,3,3,NULL,'2011-06-13 00:55:51','camilo',NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DAV01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Local',NULL,8,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dav01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_ds01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'Conservación',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dt01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dv01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DS01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'Local',NULL,8,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DT01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'Local',NULL,8,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DV01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,8,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/AGLP/DC1/DCT','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,'AR/SC/DNPYM/HS/AGLP/DC1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'Local',NULL,3,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/AGLP/DC1/DV01','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,'AR/SC/DNPYM/HS/AGLP/DC1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,3,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','AR/ST/FFSDD/KASSD/GGe','Discurso General 25 de Mayo','Discurso de Eva Peron','Eva Peron Speech','1964-01-01 00:00:00','A.S. 25434','12374','AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1','Cartográfico','Carteles','Copia Manuscrita',NULL,NULL,'Sistema 1',NULL,'Melisa Gilbert','10 de mayo de 1810','9 de julio de 1916','version 3','genero 3',NULL,'1916-10-10 00:00:00','1920-10-20 00:00:00','alcanza todos los contenidos','Soporte 2','duracion monumental',NULL,NULL,NULL,NULL,NULL,NULL,'integridad nacional',NULL,NULL,NULL,'Requisito de Ejecucion 2',200,20,'coleccion 1','eventos para este documento','manifestacion documental','forma de ingreso 2','procesede de cualquier lado','15 de mayo de 1980','12 mil pesos','norma legal 2','12.3456','2548','todos los derechos reservados','maria antonieta','Gratuito  - Solo para investigadores','DNI','Documentación en mal estado de conservación o restauración ','Microfilmar','siempre publicadas todas','el suicidio de la cabeza',NULL,NULL,NULL,NULL,10,'Local','2015-06-06 00:00:00',6,3,5,NULL,'2011-05-10 17:55:02','camilo','2015-06-06 00:00:00',0),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_v1','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,6,1,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01/DR01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,7,NULL,1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;


--
-- Definition of table `documentos_areasnotas`
--

DROP TABLE IF EXISTS `documentos_areasnotas`;
CREATE TABLE `documentos_areasnotas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `nota_descripcion` text,
  `nota_archivero` text,
  `fuentes` varchar(255) DEFAULT NULL,
  `fecha_descripcion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`),
  CONSTRAINT `fk_documentos_areasnotas_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_areasnotas`
--

/*!40000 ALTER TABLE `documentos_areasnotas` DISABLE KEYS */;
INSERT INTO `documentos_areasnotas` (`codigo_referencia`,`nota_descripcion`,`nota_archivero`,`fuentes`,`fecha_descripcion`,`fecha_modificacion`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','\r\n\r\n<p>nota</p>','nota del archivero','fuente','2006-11-10 00:00:00',NULL);
/*!40000 ALTER TABLE `documentos_areasnotas` ENABLE KEYS */;


--
-- Definition of table `documentos_estados`
--

DROP TABLE IF EXISTS `documentos_estados`;
CREATE TABLE `documentos_estados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`estado`,`fecha`),
  KEY `fk_documentos_estados_estados1` (`estado`),
  KEY `fk_documentos_estados_usuarios1` (`usuario`),
  CONSTRAINT `fk_documentos_estados_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_estados_estados1` FOREIGN KEY (`estado`) REFERENCES `estados` (`estado`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_estados_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_estados`
--

/*!40000 ALTER TABLE `documentos_estados` DISABLE KEYS */;
INSERT INTO `documentos_estados` (`codigo_referencia`,`estado`,`motivo`,`fecha`,`usuario`) VALUES 
 ('AA/URQ/ADV1','Inicio',NULL,'2011-06-13 14:28:56','camilo'),
 ('AA/URQ/ADV2','Inicio',NULL,'2011-06-13 14:36:07','camilo'),
 ('AA/URQ/SON1','Inicio',NULL,'2011-06-13 14:50:31','camilo'),
 ('AA/URQ/VIS','Inicio',NULL,'2011-06-11 18:17:05','camilo'),
 ('AA/URQ/VIS','Pendiente',NULL,'2011-06-13 00:48:29','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DAV01_a','Inicio',NULL,'2011-05-18 14:57:40','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dav01_a','Inicio',NULL,'2011-05-18 15:01:04','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_ds01_a','Inicio',NULL,'2011-05-18 15:01:38','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dt01_a','Inicio',NULL,'2011-05-18 15:02:15','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_dv01_a','Inicio',NULL,'2011-05-18 15:00:24','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DS01_a','Inicio',NULL,'2011-05-18 14:57:56','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DT01_a','Inicio',NULL,'2011-05-18 14:58:08','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DV01_a','Inicio',NULL,'2011-05-18 14:57:27','camilo'),
 ('AR/SC/DNPYM/HS/AGLP/DC1/DCT','Inicio',NULL,'2011-06-13 12:26:55','camilo'),
 ('AR/SC/DNPYM/HS/AGLP/DC1/DV01','Inicio',NULL,'2011-06-13 12:26:38','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Completo',NULL,'2011-05-04 08:58:18','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Completo',NULL,'2011-05-04 08:59:08','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Completo',NULL,'2011-05-04 15:11:45','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Completo',NULL,'2011-05-05 10:44:06','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Inicio',NULL,'2011-04-21 13:10:01','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Pendiente',NULL,'2011-04-27 12:25:45','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_v1','Inicio',NULL,'2011-04-21 13:10:19','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01/DR01','Inicio',NULL,'2011-05-04 11:07:12','camilo');
/*!40000 ALTER TABLE `documentos_estados` ENABLE KEYS */;


--
-- Definition of table `documentos_no_localizados`
--

DROP TABLE IF EXISTS `documentos_no_localizados`;
CREATE TABLE `documentos_no_localizados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `nro_nota` varchar(100) NOT NULL,
  `fecha_recuperacion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`motivo`,`nro_nota`),
  KEY `fk_documentos_no_localizados_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_documentos_no_localizados_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_no_localizados`
--

/*!40000 ALTER TABLE `documentos_no_localizados` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_no_localizados` ENABLE KEYS */;


--
-- Definition of table `documentos_prestamos`
--

DROP TABLE IF EXISTS `documentos_prestamos`;
CREATE TABLE `documentos_prestamos` (
  `codigo_referencia` varchar(255) NOT NULL,
  `forma` varchar(50) DEFAULT NULL,
  `institucion_destinataria` varchar(50) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_termino` datetime DEFAULT NULL,
  `norma_legal_prestamo` varchar(50) DEFAULT NULL,
  `nro_legal_prestamo` varchar(50) DEFAULT NULL,
  `transporte` varchar(50) DEFAULT NULL,
  `nombre_deposito` varchar(50) DEFAULT NULL,
  `estanteria_deposito` varchar(50) DEFAULT NULL,
  `contenedor_deposito` varchar(50) DEFAULT NULL,
  `grilla_deposito` varchar(50) DEFAULT NULL,
  `planera_deposito` varchar(50) DEFAULT NULL,
  `nombre_exhibicion` varchar(50) DEFAULT NULL,
  `vitrinas_paredes_exhibicion` varchar(50) DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`),
  KEY `fk_documentos_prestamos_instituciones1` (`institucion_destinataria`),
  KEY `fk_documentos_prestamos_norma_legal1` (`norma_legal_prestamo`),
  KEY `fk_documentos_prestamos_transportes1` (`transporte`),
  CONSTRAINT `fk_documentos_prestamos_instituciones1` FOREIGN KEY (`institucion_destinataria`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_prestamos_norma_legal1` FOREIGN KEY (`norma_legal_prestamo`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_prestamos_transportes1` FOREIGN KEY (`transporte`) REFERENCES `transportes` (`transporte`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_prestamos`
--

/*!40000 ALTER TABLE `documentos_prestamos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_prestamos` ENABLE KEYS */;


--
-- Definition of table `documentos_robados`
--

DROP TABLE IF EXISTS `documentos_robados`;
CREATE TABLE `documentos_robados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `fecha_sustraído` datetime NOT NULL,
  `fecha_resolución_apertura_sumario` datetime DEFAULT NULL,
  `fecha_resolución_cierre_sumario` datetime DEFAULT NULL,
  `denunciante` varchar(100) DEFAULT NULL,
  `nro_causa_judicial` varchar(100) DEFAULT NULL,
  `nro_juzgado` varchar(100) DEFAULT NULL,
  `tipo_tramite_administrativo` varchar(100) DEFAULT NULL,
  `nro_sumario_administrativo` varchar(100) DEFAULT NULL,
  `fallo` text,
  `fecha_recuperacion` datetime DEFAULT NULL,
  `object_id_interpol` varchar(50) DEFAULT NULL,
  `fecha_registro_interpol` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`fecha_sustraído`),
  KEY `fk_documentos_robados_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_documentos_robados_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_robados`
--

/*!40000 ALTER TABLE `documentos_robados` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_robados` ENABLE KEYS */;


--
-- Definition of table `documentos_tasaciones_expertizaje`
--

DROP TABLE IF EXISTS `documentos_tasaciones_expertizaje`;
CREATE TABLE `documentos_tasaciones_expertizaje` (
  `codigo_referencia` varchar(255) NOT NULL,
  `valuacion` varchar(50) NOT NULL,
  `valuacionusd` varchar(50) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `tasador_experto` varchar(255) DEFAULT NULL,
  `comentario_expertizaje` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`valuacion`,`fecha`),
  KEY `fk_documentos_tasaciones_expertizaje_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_documentos_tasaciones_expertizaje_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_tasaciones_expertizaje`
--

/*!40000 ALTER TABLE `documentos_tasaciones_expertizaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_tasaciones_expertizaje` ENABLE KEYS */;


--
-- Definition of table `edificios`
--

DROP TABLE IF EXISTS `edificios`;
CREATE TABLE `edificios` (
  `nombre_edificio` varchar(255) NOT NULL,
  `tipo_direccion` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nombre_edificio`),
  KEY `fk_edificios_tipo_direcciones1` (`tipo_direccion`),
  CONSTRAINT `fk_edificios_tipo_direcciones1` FOREIGN KEY (`tipo_direccion`) REFERENCES `tipo_direcciones` (`tipo_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `edificios`
--

/*!40000 ALTER TABLE `edificios` DISABLE KEYS */;
INSERT INTO `edificios` (`nombre_edificio`,`tipo_direccion`,`direccion`) VALUES 
 ('Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','Depósito','Avenida de Mayo 666');
/*!40000 ALTER TABLE `edificios` ENABLE KEYS */;


--
-- Definition of table `emulsiones`
--

DROP TABLE IF EXISTS `emulsiones`;
CREATE TABLE `emulsiones` (
  `emulsion` varchar(50) NOT NULL,
  PRIMARY KEY (`emulsion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emulsiones`
--

/*!40000 ALTER TABLE `emulsiones` DISABLE KEYS */;
INSERT INTO `emulsiones` (`emulsion`) VALUES 
 ('Emulsiones 1'),
 ('Emulsiones 2'),
 ('Emulsiones 3'),
 ('Emulsiones 4');
/*!40000 ALTER TABLE `emulsiones` ENABLE KEYS */;


--
-- Definition of table `envases`
--

DROP TABLE IF EXISTS `envases`;
CREATE TABLE `envases` (
  `idenvases` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(100) DEFAULT NULL,
  `dimension` varchar(50) DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idenvases`),
  KEY `fk_envases_materiales1` (`material`),
  KEY `fk_envases_autores1` (`autor`),
  CONSTRAINT `fk_envases_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_envases_materiales1` FOREIGN KEY (`material`) REFERENCES `materiales` (`material`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `envases`
--

/*!40000 ALTER TABLE `envases` DISABLE KEYS */;
INSERT INTO `envases` (`idenvases`,`material`,`dimension`,`autor`) VALUES 
 (1,'material 3','dimension 1','Maria Martha Serralima');
/*!40000 ALTER TABLE `envases` ENABLE KEYS */;


--
-- Definition of table `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `estados`
--

/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` (`estado`) VALUES 
 ('Cancelado'),
 ('Completo'),
 ('Inicio'),
 ('No Vigente'),
 ('Pendiente'),
 ('Vigente');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;


--
-- Definition of table `exposiciones`
--

DROP TABLE IF EXISTS `exposiciones`;
CREATE TABLE `exposiciones` (
  `codigo_exposicion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_termino` datetime DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `curador` varchar(255) DEFAULT NULL,
  `organizador` varchar(255) DEFAULT NULL,
  `patrocinador` varchar(255) DEFAULT NULL,
  `ciudad_pais` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_exposicion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exposiciones`
--

/*!40000 ALTER TABLE `exposiciones` DISABLE KEYS */;
INSERT INTO `exposiciones` (`codigo_exposicion`,`fecha_inicio`,`fecha_termino`,`nombre`,`lugar`,`curador`,`organizador`,`patrocinador`,`ciudad_pais`) VALUES 
 (1,'2011-06-13 00:00:00','2011-06-23 00:00:00','EXPO Anticuaria 2011','Sheraton Hotel','Candido Silva','Antiquaria Santelmo','Sherathon Hotel','Buenos Aires - Argentina');
/*!40000 ALTER TABLE `exposiciones` ENABLE KEYS */;


--
-- Definition of table `fomas_presentacion_unidad`
--

DROP TABLE IF EXISTS `fomas_presentacion_unidad`;
CREATE TABLE `fomas_presentacion_unidad` (
  `forma` varchar(50) NOT NULL,
  PRIMARY KEY (`forma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fomas_presentacion_unidad`
--

/*!40000 ALTER TABLE `fomas_presentacion_unidad` DISABLE KEYS */;
INSERT INTO `fomas_presentacion_unidad` (`forma`) VALUES 
 ('forma presentacion 1'),
 ('forma presentacion 2'),
 ('forma presetnacion 3');
/*!40000 ALTER TABLE `fomas_presentacion_unidad` ENABLE KEYS */;


--
-- Definition of table `formas_autorizadas_nombre`
--

DROP TABLE IF EXISTS `formas_autorizadas_nombre`;
CREATE TABLE `formas_autorizadas_nombre` (
  `codigo_formas_autorizadas_nombre` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_identificacion` varchar(50) DEFAULT NULL,
  `forma_autorizada_nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codigo_formas_autorizadas_nombre`),
  KEY `fk_formas_autorizadas_nombre_instituciones` (`codigo_identificacion`),
  CONSTRAINT `fk_formas_autorizadas_nombre_instituciones` FOREIGN KEY (`codigo_identificacion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formas_autorizadas_nombre`
--

/*!40000 ALTER TABLE `formas_autorizadas_nombre` DISABLE KEYS */;
INSERT INTO `formas_autorizadas_nombre` (`codigo_formas_autorizadas_nombre`,`codigo_identificacion`,`forma_autorizada_nombre`) VALUES 
 (1,'AR/ST/FFSDD/KASSD/GGe','Santa Marina de los Angeles Marinos'),
 (2,'AR//BFG//DE//RE','Estrellita Mía'),
 (3,'AA','AA'),
 (4,'AA','BB'),
 (5,'AA','CC'),
 (6,'AR/SC/DNPYM/HS','Hugo Sigal'),
 (7,'AR/SC/DNPYM/HS','Huguito Sigal');
/*!40000 ALTER TABLE `formas_autorizadas_nombre` ENABLE KEYS */;


--
-- Definition of table `formas_ingreso`
--

DROP TABLE IF EXISTS `formas_ingreso`;
CREATE TABLE `formas_ingreso` (
  `forma_ingreso` varchar(50) NOT NULL,
  PRIMARY KEY (`forma_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formas_ingreso`
--

/*!40000 ALTER TABLE `formas_ingreso` DISABLE KEYS */;
INSERT INTO `formas_ingreso` (`forma_ingreso`) VALUES 
 ('forma de ingreso 1'),
 ('forma de ingreso 2'),
 ('forma de ingreso 3'),
 ('forma de ingreso 4');
/*!40000 ALTER TABLE `formas_ingreso` ENABLE KEYS */;


--
-- Definition of table `formatos_soportes`
--

DROP TABLE IF EXISTS `formatos_soportes`;
CREATE TABLE `formatos_soportes` (
  `formato_soporte` varchar(50) NOT NULL,
  PRIMARY KEY (`formato_soporte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formatos_soportes`
--

/*!40000 ALTER TABLE `formatos_soportes` DISABLE KEYS */;
/*!40000 ALTER TABLE `formatos_soportes` ENABLE KEYS */;


--
-- Definition of table `generos`
--

DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `genero` varchar(50) NOT NULL,
  PRIMARY KEY (`genero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `generos`
--

/*!40000 ALTER TABLE `generos` DISABLE KEYS */;
INSERT INTO `generos` (`genero`) VALUES 
 ('genero 1'),
 ('genero 2'),
 ('genero 3'),
 ('genero 4'),
 ('genero 5');
/*!40000 ALTER TABLE `generos` ENABLE KEYS */;


--
-- Definition of table `gestion_conservacion`
--

DROP TABLE IF EXISTS `gestion_conservacion`;
CREATE TABLE `gestion_conservacion` (
  `codigo_gestion` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_referencia` varchar(255) NOT NULL,
  `fecha_inicio_tratamiento` datetime DEFAULT NULL,
  `fecha_fin_tratamiento` datetime DEFAULT NULL,
  `estado_conservacion` varchar(50) DEFAULT NULL,
  `descripcion_fisica_pormenorizada` varchar(50) DEFAULT NULL,
  `recubrimiento_superficial` varchar(50) DEFAULT NULL,
  `diagnostico_especifico` text,
  `tiempo_reformateo` varchar(50) DEFAULT NULL,
  `caducidad_reformateo` varchar(50) DEFAULT NULL,
  `fecha_imagen_incio` datetime DEFAULT NULL,
  `fecha_imagen_posterior` datetime DEFAULT NULL,
  `informe_tratamiento` text,
  `resultados_tratamiento` text,
  `restringido_exibicion` tinyint(4) DEFAULT '0',
  `caracteristica` text,
  `responsable` text,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  `fecha_ultima_modificacion` datetime DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `full` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`codigo_gestion`),
  UNIQUE KEY `codigo_referencia_UNIQUE` (`codigo_referencia`,`fecha_inicio_tratamiento`),
  KEY `fk_gestion_conservacion_usuarios1` (`usuario`),
  KEY `fk_gestion_conservacion_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_gestion_conservacion_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_gestion_conservacion_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gestion_conservacion`
--

/*!40000 ALTER TABLE `gestion_conservacion` DISABLE KEYS */;
INSERT INTO `gestion_conservacion` (`codigo_gestion`,`codigo_referencia`,`fecha_inicio_tratamiento`,`fecha_fin_tratamiento`,`estado_conservacion`,`descripcion_fisica_pormenorizada`,`recubrimiento_superficial`,`diagnostico_especifico`,`tiempo_reformateo`,`caducidad_reformateo`,`fecha_imagen_incio`,`fecha_imagen_posterior`,`informe_tratamiento`,`resultados_tratamiento`,`restringido_exibicion`,`caracteristica`,`responsable`,`codigo_institucion`,`fecha_ultima_modificacion`,`usuario`,`full`) VALUES 
 (1,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','2011-05-31 00:00:00','2015-06-06 00:00:00','estado de la conservacion','descripcion fisica permenorizada','recubrimiento superficial','diagnostico',NULL,NULL,'2005-11-15 00:00:00','2009-09-10 00:00:00','holaaa','hola \r\n\r\n<p>&nbsp;</p>',0,'caracteristicas','\r\n\r\n<p>responsables</p>','AR/ST/FFSDD/KASSD/GGe','2011-06-11 17:29:37','camilo',1),
 (2,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a/DC_ds01_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'AR//BFG//DE//RE','2011-06-09 16:07:22','camilo',0),
 (3,'AA/URQ/ADV2','2008-10-10 00:00:00',NULL,'eatado 1','estado 2','cuero','diagnosticos',NULL,NULL,NULL,NULL,NULL,NULL,0,'caraqcteristicas',NULL,'AA','2011-06-13 14:38:28','camilo',0),
 (4,'AA/URQ/VIS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2011-10-10 00:00:00',NULL,NULL,NULL,NULL,NULL,NULL,'AA','2011-06-13 14:49:44','camilo',0),
 (5,'AA/URQ/SON1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2011-06-13 00:00:00',NULL,NULL,NULL,NULL,NULL,NULL,'AA','2011-06-13 14:51:00','camilo',0);
/*!40000 ALTER TABLE `gestion_conservacion` ENABLE KEYS */;


--
-- Definition of table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos` (
  `idgrupo` int(11) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(50) DEFAULT NULL,
  `jerarquia` int(11) DEFAULT NULL,
  `codigo_identificacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idgrupo`),
  UNIQUE KEY `grupo_UNIQUE` (`grupo`,`codigo_identificacion`),
  KEY `fk_grupos_instituciones1` (`codigo_identificacion`),
  CONSTRAINT `fk_grupos_instituciones1` FOREIGN KEY (`codigo_identificacion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grupos`
--

/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;


--
-- Definition of table `guardas`
--

DROP TABLE IF EXISTS `guardas`;
CREATE TABLE `guardas` (
  `guarda` varchar(100) NOT NULL,
  PRIMARY KEY (`guarda`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guardas`
--

/*!40000 ALTER TABLE `guardas` DISABLE KEYS */;
INSERT INTO `guardas` (`guarda`) VALUES 
 ('Guarda 1'),
 ('Guarda 2'),
 ('Guarda 3');
/*!40000 ALTER TABLE `guardas` ENABLE KEYS */;


--
-- Definition of table `idiomas`
--

DROP TABLE IF EXISTS `idiomas`;
CREATE TABLE `idiomas` (
  `idioma` varchar(50) NOT NULL,
  PRIMARY KEY (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `idiomas`
--

/*!40000 ALTER TABLE `idiomas` DISABLE KEYS */;
INSERT INTO `idiomas` (`idioma`) VALUES 
 ('Alemán'),
 ('Español'),
 ('Inglés'),
 ('Italiano'),
 ('Portugués');
/*!40000 ALTER TABLE `idiomas` ENABLE KEYS */;


--
-- Definition of table `instituciones`
--

DROP TABLE IF EXISTS `instituciones`;
CREATE TABLE `instituciones` (
  `codigo_identificacion` varchar(50) NOT NULL,
  `formas_conocidas_nombre` varchar(100) DEFAULT NULL,
  `tipo_institucion` varchar(50) DEFAULT NULL,
  `tipo_entidad` varchar(50) DEFAULT NULL,
  `telefono_de_contacto` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `www` varchar(50) DEFAULT NULL,
  `historia` text,
  `estructura_organica` text,
  `politicas_ingresos` text,
  `proyecto_nuevos_ingresos` text,
  `instalaciones_archivo` varchar(255) DEFAULT NULL,
  `fondos_agrupaciones` varchar(100) DEFAULT NULL,
  `tipo_acceso` varchar(50) DEFAULT NULL,
  `requisito_acceso` varchar(100) DEFAULT NULL,
  `acceso_documentacion` varchar(255) DEFAULT NULL,
  `dias_apertura` varchar(255) DEFAULT NULL,
  `horaios_apertura` varchar(255) DEFAULT NULL,
  `fechas_anuales_cierre` varchar(255) DEFAULT NULL,
  `codigo_interno` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT '0',
  PRIMARY KEY (`codigo_identificacion`),
  KEY `fk_instituciones_tipo_institucion` (`tipo_institucion`),
  KEY `fk_instituciones_tipo_entidad` (`tipo_entidad`),
  KEY `fk_instituciones_tipos_accesos` (`tipo_acceso`),
  KEY `fk_instituciones_requisitos_accesos` (`requisito_acceso`),
  KEY `fk_instituciones_acceso_documentacion` (`acceso_documentacion`),
  CONSTRAINT `fk_instituciones_acceso_documentacion` FOREIGN KEY (`acceso_documentacion`) REFERENCES `acceso_documentacion` (`acceso_documentacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instituciones_requisitos_accesos` FOREIGN KEY (`requisito_acceso`) REFERENCES `requisitos_accesos` (`requisito_acceso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instituciones_tipos_accesos` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipos_accesos` (`tipo_acceso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instituciones_tipo_entidad` FOREIGN KEY (`tipo_entidad`) REFERENCES `tipo_entidad` (`tipo_entidad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_instituciones_tipo_institucion` FOREIGN KEY (`tipo_institucion`) REFERENCES `tipo_institucion` (`tipo_institucion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instituciones`
--

/*!40000 ALTER TABLE `instituciones` DISABLE KEYS */;
INSERT INTO `instituciones` (`codigo_identificacion`,`formas_conocidas_nombre`,`tipo_institucion`,`tipo_entidad`,`telefono_de_contacto`,`email`,`www`,`historia`,`estructura_organica`,`politicas_ingresos`,`proyecto_nuevos_ingresos`,`instalaciones_archivo`,`fondos_agrupaciones`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`dias_apertura`,`horaios_apertura`,`fechas_anuales_cierre`,`codigo_interno`,`estado`) VALUES 
 ('AA','AA','Estatal',NULL,'AA234567','AA@aa.com.ar','www.aa.com.ar','Es la historia de un amor','Todo es igual','para que saber','para que saber','un estante solamente','1','Arancelado - Libre',NULL,NULL,'de 9a a 18 los viernes',NULL,'25 de mayo unicamente',NULL,5),
 ('AR//BFG//DE//RE','Nación de las Estrellas','Empresarial',NULL,'1256 3256','nacion@estrellita.com.ar','www.estrellita.com.ar','historia de estrellita','estructura organica de estrellita','historia de la formacion de estrellita','proyecto paras nuevos ingreso de estrellita','istalacions del archivo','16','Arancelado - Libre',NULL,NULL,'lunes a viernes de 10 a 18',NULL,'25 de mayo',NULL,5),
 ('AR//DD//CFG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('AR/CC/DD/EE/FF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('AR/SC/DNPYM/HS','Chugo','Estatal',NULL,'13513451','hsigal@hotmail.com',NULL,'Habia una vez','verso','verso','verso','verso','7','Arancelado - Libre',NULL,NULL,'Todos los días',NULL,'Todos los días',NULL,5),
 ('AR/ST/FFSDD/KASSD/GGe','Palacio de Santa Marina','Estatal','Institucional','15 3258 8964','angel@hotmail.com','www.hotmail.com','Es la historia del palacio santa marina','es la estructura organica del a istitucion','es un historial de la formacion de mas de 1500 ','es un historial de la formacion de mas de 1500 ','las instalaciones del archivo','5','Arancelado - Libre',NULL,NULL,'Martes a viernes de 10 a 18hs',NULL,'25 de mayo de 1810',NULL,5),
 ('AR\\BB\\CC\\DDAR//BB//CC//DDAR\\BB\\CC\\DDAR/BB/CC/DD',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('AR\\BC',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('AR\\BVC\\DCD',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('CC/DD/EE/FF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
 ('r',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `instituciones` ENABLE KEYS */;


--
-- Definition of table `instituciones_estados`
--

DROP TABLE IF EXISTS `instituciones_estados`;
CREATE TABLE `instituciones_estados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`estado`,`fecha`),
  KEY `fk_instituciones_estados_estados1` (`estado`),
  KEY `fk_instituciones_estados_instituciones1` (`codigo_referencia`),
  KEY `fk_instituciones_estados_usuarios1` (`usuario`),
  CONSTRAINT `fk_instituciones_estados_estados1` FOREIGN KEY (`estado`) REFERENCES `estados` (`estado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instituciones_estados_instituciones1` FOREIGN KEY (`codigo_referencia`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instituciones_estados_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instituciones_estados`
--

/*!40000 ALTER TABLE `instituciones_estados` DISABLE KEYS */;
INSERT INTO `instituciones_estados` (`codigo_referencia`,`estado`,`motivo`,`fecha`,`usuario`) VALUES 
 ('AA','Completo',NULL,'2011-06-11 18:16:24','camilo'),
 ('AA','Pendiente',NULL,'2011-06-11 18:11:13','camilo'),
 ('AR//BFG//DE//RE','Completo',NULL,'2011-05-20 20:45:16','camilo'),
 ('AR//BFG//DE//RE','Pendiente',NULL,'2011-04-06 15:41:12','camilo'),
 ('AR//BFG//DE//RE','Vigente',NULL,'2011-05-24 11:21:01','camilo'),
 ('AR/CC/DD/EE/FF','Pendiente',NULL,'2011-04-06 15:18:54','camilo'),
 ('AR/SC/DNPYM/HS','Completo',NULL,'2011-06-13 12:31:30','camilo'),
 ('AR/SC/DNPYM/HS','Pendiente',NULL,'2011-06-13 12:05:26','camilo'),
 ('AR/SC/DNPYM/HS','Vigente',NULL,'2011-06-13 14:53:03','camilo'),
 ('AR\\BB\\CC\\DDAR//BB//CC//DDAR\\BB\\CC\\DDAR/BB/CC/DD','Pendiente',NULL,'2011-04-06 15:47:32','camilo'),
 ('CC/DD/EE/FF','Pendiente',NULL,'2011-04-06 15:48:29','camilo'),
 ('r','Pendiente',NULL,'2011-04-26 10:56:11','camilo');
/*!40000 ALTER TABLE `instituciones_estados` ENABLE KEYS */;


--
-- Definition of table `instituciones_externas`
--

DROP TABLE IF EXISTS `instituciones_externas`;
CREATE TABLE `instituciones_externas` (
  `codigo_referencia_intitucion_externa` varchar(50) NOT NULL,
  `nombre_autorizado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia_intitucion_externa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instituciones_externas`
--

/*!40000 ALTER TABLE `instituciones_externas` DISABLE KEYS */;
INSERT INTO `instituciones_externas` (`codigo_referencia_intitucion_externa`,`nombre_autorizado`) VALUES 
 ('AR/BB/CCD','Institucion Primera'),
 ('AR/INS/02','Institución Segunda');
/*!40000 ALTER TABLE `instituciones_externas` ENABLE KEYS */;


--
-- Definition of table `material_soporte`
--

DROP TABLE IF EXISTS `material_soporte`;
CREATE TABLE `material_soporte` (
  `codigo_material_soporte` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(50) NOT NULL,
  `tipo_diplomatico` int(11) NOT NULL,
  PRIMARY KEY (`codigo_material_soporte`),
  UNIQUE KEY `material_UNIQUE` (`material`,`tipo_diplomatico`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_soporte`
--

/*!40000 ALTER TABLE `material_soporte` DISABLE KEYS */;
INSERT INTO `material_soporte` (`codigo_material_soporte`,`material`,`tipo_diplomatico`) VALUES 
 (2,'soporte para audiovisual 1',9),
 (10,'soporte para audiovisual 2',9),
 (4,'soporte para sonoro 1',10),
 (8,'soporte para sonoro 2',10),
 (5,'soporte para textual 1',11),
 (7,'soporte para textual 2',11),
 (3,'soporte para visual 1',8),
 (9,'soporte para visual 2',8);
/*!40000 ALTER TABLE `material_soporte` ENABLE KEYS */;


--
-- Definition of table `materiales`
--

DROP TABLE IF EXISTS `materiales`;
CREATE TABLE `materiales` (
  `material` varchar(100) NOT NULL,
  PRIMARY KEY (`material`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materiales`
--

/*!40000 ALTER TABLE `materiales` DISABLE KEYS */;
INSERT INTO `materiales` (`material`) VALUES 
 ('material 1'),
 ('material 2'),
 ('material 3'),
 ('material 4'),
 ('material 5');
/*!40000 ALTER TABLE `materiales` ENABLE KEYS */;


--
-- Definition of table `medios`
--

DROP TABLE IF EXISTS `medios`;
CREATE TABLE `medios` (
  `codigo_medio` int(11) NOT NULL AUTO_INCREMENT,
  `medio` varchar(50) NOT NULL,
  `tipo_diplomatico` int(11) NOT NULL,
  PRIMARY KEY (`codigo_medio`),
  UNIQUE KEY `medio_UNIQUE` (`medio`,`tipo_diplomatico`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medios`
--

/*!40000 ALTER TABLE `medios` DISABLE KEYS */;
INSERT INTO `medios` (`codigo_medio`,`medio`,`tipo_diplomatico`) VALUES 
 (4,'medio audiovisual 1',9),
 (5,'medio audiovisual 2',9),
 (6,'medio sonoro 1',10),
 (7,'medio sonoro 2',10),
 (8,'medio textual 1',11),
 (9,'medio textual 2',11),
 (1,'medio visual 1',8),
 (2,'medio visual 2',8);
/*!40000 ALTER TABLE `medios` ENABLE KEYS */;


--
-- Definition of table `montajes_fotografias`
--

DROP TABLE IF EXISTS `montajes_fotografias`;
CREATE TABLE `montajes_fotografias` (
  `montaje` varchar(50) NOT NULL,
  PRIMARY KEY (`montaje`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `montajes_fotografias`
--

/*!40000 ALTER TABLE `montajes_fotografias` DISABLE KEYS */;
/*!40000 ALTER TABLE `montajes_fotografias` ENABLE KEYS */;


--
-- Definition of table `niveles`
--

DROP TABLE IF EXISTS `niveles`;
CREATE TABLE `niveles` (
  `codigo_referencia` varchar(255) NOT NULL,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  `titulo_original` varchar(100) DEFAULT NULL,
  `titulo_atribuido` varchar(100) DEFAULT NULL,
  `titulo_traducido` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `numero_registro_inventario_anterior` varchar(50) DEFAULT NULL,
  `fondo_tesoro_sn` bit(1) DEFAULT NULL,
  `fondo_tesoro` text,
  `fecha_inicial` datetime DEFAULT NULL,
  `fecha_final` datetime DEFAULT NULL,
  `alcance_contenido` text,
  `metros_lineales` varchar(50) DEFAULT NULL,
  `unidades` varchar(50) DEFAULT NULL,
  `historia_institucional_productor` text,
  `historia_archivistica` text,
  `forma_ingreso` varchar(255) DEFAULT NULL,
  `procedencia` text,
  `fecha_inicio_ingreso` varchar(255) DEFAULT NULL,
  `precio` varchar(50) DEFAULT NULL,
  `norma_legal_ingreso` varchar(255) DEFAULT NULL,
  `numero_legal_ingreso` varchar(255) DEFAULT NULL,
  `numero_administrativo` varchar(255) DEFAULT NULL,
  `derechos_restricciones` text,
  `titular_derecho` varchar(100) DEFAULT NULL,
  `publicaciones_acceso` text,
  `subsidios_otorgados` text,
  `tipo_nivel` int(11) DEFAULT '0',
  `cod_ref_sup` varchar(255) DEFAULT NULL,
  `tv` int(11) DEFAULT '0',
  `numero_registro` int(11) DEFAULT NULL,
  `tipo_acceso` varchar(255) DEFAULT NULL,
  `requisito_acceso` varchar(255) DEFAULT NULL,
  `acceso_documentacion` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT '1',
  `normativa_legal_baja` varchar(50) DEFAULT NULL,
  `numero_norma_legal` varchar(50) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_ultima_modificacion` timestamp NULL DEFAULT NULL,
  `usuario_ultima_modificacion` varchar(50) DEFAULT NULL,
  `na` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`),
  KEY `fk_niveles_tipos_niveles1` (`tipo_nivel`),
  KEY `fk_niveles_instituciones1` (`codigo_institucion`),
  KEY `fk_niveles_norma_legal1` (`normativa_legal_baja`),
  CONSTRAINT `fk_niveles_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_niveles_norma_legal1` FOREIGN KEY (`normativa_legal_baja`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_niveles_tipos_niveles1` FOREIGN KEY (`tipo_nivel`) REFERENCES `tipos_niveles` (`codigo_tipo_nivel`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles`
--

/*!40000 ALTER TABLE `niveles` DISABLE KEYS */;
INSERT INTO `niveles` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`fondo_tesoro_sn`,`fondo_tesoro`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`metros_lineales`,`unidades`,`historia_institucional_productor`,`historia_archivistica`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`publicaciones_acceso`,`subsidios_otorgados`,`tipo_nivel`,`cod_ref_sup`,`tv`,`numero_registro`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`estado`,`normativa_legal_baja`,`numero_norma_legal`,`motivo`,`fecha_baja`,`fecha_ultima_modificacion`,`usuario_ultima_modificacion`,`na`) VALUES 
 ('AA/URQ','AA','Urquiza','Genial Urquiza',NULL,'2011-10-10 00:00:00',NULL,NULL,NULL,'2006-10-10 00:00:00','2207-10-10 00:00:00','alcance 1','2 metros','40 unidades','es la historia como siempre','\r\n\r\n<p>historia archivistica</p>','forma de ingreso nomas','la procedencia desconocida','20081010',NULL,NULL,NULL,NULL,'no tiene derechos','no tiene','no se publico nunca','no recibio ni un misero centavo',1,'AA',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-11 18:22:12','camilo',4),
 ('AR//BFG//DE//RE/FF01_a','AR//BFG//DE//RE','Fondo General Urquiza','Urquiza',NULL,'1830-01-01 00:00:00',NULL,NULL,NULL,'1830-01-01 00:00:00','1840-01-01 00:00:00','todos los alcances','22','150','historia institucional','historia archivistica','forma de ingreso 1','procendencia ','19100101',NULL,NULL,NULL,NULL,'no tiene derechos ni restricciones','nadie','publicacion y instrumento de acceso',NULL,1,'AR//BFG//DE//RE',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-20 20:59:00','camilo',4),
 ('AR//BFG//DE//RE/FF01_a/SF01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR//BFG//DE//RE/FF01_a',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR//BFG//DE//RE/FF01_a/SF01_a',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a',6,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a',7,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a','AR//BFG//DE//RE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a',8,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/AGLP','AR/SC/DNPYM/HS','La Plata','Platense','River Plate','1967-12-12 00:00:00','el 12 anteriormente era el 24 y el 16 era el 48',0x01,'Es importante, bomberos rescarlo!!!!! a hugo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/SC/DNPYM/HS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2011-06-13 12:38:42','camilo',1),
 ('AR/SC/DNPYM/HS/AGLP/DC1','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR/SC/DNPYM/HS/AGLP',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/FO','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/SC/DNPYM/HS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/FO/S01','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/SC/DNPYM/HS/FO',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/SC/DNPYM/HS/FP','AR/SC/DNPYM/HS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/SC/DNPYM/HS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe/FF01',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/ST/FFSDD/KASSD/GGe/FF01/SF01',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/CDF34','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/CDF34/SS004','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/CDF34',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/SS01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/SS01/AD01','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/SS01',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/ST/FFSDD/KASSD/GGe','Titulo original de GGe','Santa Maria de Dios de los buenso Aires','Saint Roman','1987-05-25 00:00:00','numero de registro 2345654',0x01,'es un fondo tesoro porsu','1965-01-01 00:00:00','2001-01-01 00:00:00','alcance de contenidos','30 metros','40','historia institucional','historia archivistica','forma de ingreso 1','procendencia 1','19640101','3000','norma 1','252432','254589','derechos 1','marcos marconi','publicaciones e instrumentos de acceso 1','subsidios otorgados de la institucion 1',1,'AR/ST/FFSDD/KASSD/GGe/FF02',2,NULL,'tipo de acceso 1','requisitos de acceso 1','acceso a documentacion 1',5,NULL,NULL,NULL,NULL,'2011-04-28 14:20:25','camilo',1),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/ST/FFSDD/KASSD/GGe/FF02/SF02',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/Doc_c_2','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST/BST02','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe/SF03',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1','AR/ST/FFSDD/KASSD/GGe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','AR/ST/FFSDD/KASSD/GGe','titulo original1','titulo atr1','titulo tradcu','2008-10-05 00:00:00','233454',0x01,'fondo tesoro',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02',5,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2011-04-28 15:08:56','camilo',1),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','AR/ST/FFSDD/KASSD/GGe','subserie 1','titulo atribuido 1','titulo traducido 1','1932-01-01 00:00:00','1456',0x01,'fondo tesoro','1964-01-01 00:00:00','1972-01-01 00:00:00','lacance de contenido 1','13 mts','2345','histria 1','historia archvistica 1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456',6,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2011-04-29 12:32:37','camilo',3);
/*!40000 ALTER TABLE `niveles` ENABLE KEYS */;


--
-- Definition of table `niveles_areasnotas`
--

DROP TABLE IF EXISTS `niveles_areasnotas`;
CREATE TABLE `niveles_areasnotas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `nota_descripcion` text,
  `nota_archivero` text,
  `fuentes` varchar(255) DEFAULT NULL,
  `fecha_descripcion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`),
  CONSTRAINT `fk_niveles_areasnotas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles_areasnotas`
--

/*!40000 ALTER TABLE `niveles_areasnotas` DISABLE KEYS */;
INSERT INTO `niveles_areasnotas` (`codigo_referencia`,`nota_descripcion`,`nota_archivero`,`fuentes`,`fecha_descripcion`,`fecha_modificacion`) VALUES 
 ('AA/URQ','una nota nad mas',NULL,NULL,NULL,'2011-06-11 18:22:26'),
 ('AR//BFG//DE//RE/FF01_a','nota de descripcion',NULL,NULL,NULL,'2011-05-20 20:59:15'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','\r\n\r\n<p>notas descripcion</p>','\r\n\r\n<p>notas archivero</p>','asdfasd','2001-01-01 00:00:00','2011-04-16 01:27:23');
/*!40000 ALTER TABLE `niveles_areasnotas` ENABLE KEYS */;


--
-- Definition of table `niveles_estados`
--

DROP TABLE IF EXISTS `niveles_estados`;
CREATE TABLE `niveles_estados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`estado`,`fecha`),
  KEY `fk_niveles_estados_estados1` (`estado`),
  KEY `fk_niveles_estados_niveles1` (`codigo_referencia`),
  KEY `fk_niveles_estados_usuarios1` (`usuario`),
  CONSTRAINT `fk_niveles_estados_estados1` FOREIGN KEY (`estado`) REFERENCES `estados` (`estado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_niveles_estados_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_niveles_estados_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles_estados`
--

/*!40000 ALTER TABLE `niveles_estados` DISABLE KEYS */;
INSERT INTO `niveles_estados` (`codigo_referencia`,`estado`,`motivo`,`fecha`,`usuario`) VALUES 
 ('AA/URQ','Completo',NULL,'2011-06-11 18:22:26','camilo'),
 ('AA/URQ','Inicio',NULL,'2011-06-11 18:15:36','camilo'),
 ('AA/URQ','Pendiente',NULL,'2011-06-11 18:18:02','camilo'),
 ('AR//BFG//DE//RE/FF01_a','Completo',NULL,'2011-05-20 20:59:15','camilo'),
 ('AR//BFG//DE//RE/FF01_a','Inicio',NULL,'2011-05-18 14:55:17','camilo'),
 ('AR//BFG//DE//RE/FF01_a','No Vigente',NULL,'2011-06-06 23:53:02','camilo'),
 ('AR//BFG//DE//RE/FF01_a','Pendiente',NULL,'2011-05-20 20:54:16','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a','Inicio',NULL,'2011-05-18 14:55:31','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a','Inicio',NULL,'2011-05-18 14:55:44','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a','Inicio',NULL,'2011-05-18 14:56:02','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a','Inicio',NULL,'2011-05-18 14:56:21','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a','Inicio',NULL,'2011-05-18 14:56:37','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a','Inicio',NULL,'2011-05-18 14:56:53','camilo'),
 ('AR//BFG//DE//RE/FF01_a/SF01_a/SE01_a/SS01_a/SR01_a/SRS01_a/AD01_a/DC01_a','Inicio',NULL,'2011-05-18 14:59:58','camilo'),
 ('AR/SC/DNPYM/HS/AGLP','Inicio',NULL,'2011-06-13 12:22:02','camilo'),
 ('AR/SC/DNPYM/HS/AGLP','Pendiente',NULL,'2011-06-13 12:35:08','camilo'),
 ('AR/SC/DNPYM/HS/AGLP/DC1','Inicio',NULL,'2011-06-13 12:23:25','camilo'),
 ('AR/SC/DNPYM/HS/FO','Inicio',NULL,'2011-06-13 12:21:29','camilo'),
 ('AR/SC/DNPYM/HS/FO/S01','Inicio',NULL,'2011-06-13 12:21:47','camilo'),
 ('AR/SC/DNPYM/HS/FP','Inicio',NULL,'2011-06-13 12:20:57','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/CDF34','Pendiente',NULL,'2011-04-19 17:19:45','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/CDF34/SS004','Inicio',NULL,'2011-05-18 14:54:37','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Completo',NULL,'2011-04-16 01:27:23','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Pendiente',NULL,'2011-04-06 14:01:06','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/Doc_c_2','Inicio',NULL,'2011-04-21 13:11:14','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST','Pendiente',NULL,'2011-04-06 00:00:00','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST/BST02','Pendiente',NULL,'2011-04-06 11:45:26','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03','Pendiente',NULL,'2011-04-06 14:58:39','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3','Pendiente',NULL,'2011-04-19 11:52:52','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02','Inicio',NULL,'2011-04-20 12:08:35','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1','Inicio',NULL,'2011-04-20 12:20:13','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Inicio',NULL,'2011-04-28 16:36:32','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:36:46','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:36:55','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:11','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:18','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:25','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:32','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:46','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:37:57','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:38:35','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:39:05','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:40:30','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:42:30','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:43:21','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:46:14','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:48:32','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:49:09','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:50:02','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:51:01','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:52:17','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:53:48','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:53:55','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:54:29','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:55:09','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:55:52','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 14:56:14','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 15:00:33','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 15:01:10','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Pendiente',NULL,'2011-04-28 15:08:56','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Inicio',NULL,'2011-04-29 12:05:16','camilo'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Pendiente',NULL,'2011-04-29 12:05:37','camilo');
/*!40000 ALTER TABLE `niveles_estados` ENABLE KEYS */;


--
-- Definition of table `niveles_inventario`
--

DROP TABLE IF EXISTS `niveles_inventario`;
CREATE TABLE `niveles_inventario` (
  `codigo_referencia` varchar(255) NOT NULL,
  `numero_inventario` int(11) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`numero_inventario`),
  CONSTRAINT `fk_niveles_inventario_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles_inventario`
--

/*!40000 ALTER TABLE `niveles_inventario` DISABLE KEYS */;
INSERT INTO `niveles_inventario` (`codigo_referencia`,`numero_inventario`) VALUES 
 ('AR//BFG//DE//RE/FF01_a',2545),
 ('AR/SC/DNPYM/HS/AGLP',12),
 ('AR/SC/DNPYM/HS/AGLP',16),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02',345),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456',123456),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01',235),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01',1234);
/*!40000 ALTER TABLE `niveles_inventario` ENABLE KEYS */;


--
-- Definition of table `niveles_tasacion_expertizaje`
--

DROP TABLE IF EXISTS `niveles_tasacion_expertizaje`;
CREATE TABLE `niveles_tasacion_expertizaje` (
  `codigo_referencia` varchar(255) NOT NULL,
  `valuacion` varchar(50) NOT NULL,
  `valuacion_usd` varchar(50) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `tasador_experto` varchar(255) DEFAULT NULL,
  `comentario_expertizaje` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`valuacion`,`fecha`),
  KEY `fk_niveles_tasacion_expertizaje_niveles1` (`codigo_referencia`),
  CONSTRAINT `fk_niveles_tasacion_expertizaje_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles_tasacion_expertizaje`
--

/*!40000 ALTER TABLE `niveles_tasacion_expertizaje` DISABLE KEYS */;
INSERT INTO `niveles_tasacion_expertizaje` (`codigo_referencia`,`valuacion`,`valuacion_usd`,`fecha`,`tasador_experto`,`comentario_expertizaje`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','$4000','4000','2001-01-01 00:00:00','Candido Silva','Es una tasacion pura y franca');
/*!40000 ALTER TABLE `niveles_tasacion_expertizaje` ENABLE KEYS */;


--
-- Definition of table `norma_legal`
--

DROP TABLE IF EXISTS `norma_legal`;
CREATE TABLE `norma_legal` (
  `norma_legal` varchar(50) NOT NULL,
  PRIMARY KEY (`norma_legal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `norma_legal`
--

/*!40000 ALTER TABLE `norma_legal` DISABLE KEYS */;
INSERT INTO `norma_legal` (`norma_legal`) VALUES 
 ('norma legal 1'),
 ('norma legal 2'),
 ('norma legal 3'),
 ('norma legal 4');
/*!40000 ALTER TABLE `norma_legal` ENABLE KEYS */;


--
-- Definition of table `palabras_claves`
--

DROP TABLE IF EXISTS `palabras_claves`;
CREATE TABLE `palabras_claves` (
  `palabra_clave` varchar(255) NOT NULL,
  `codigo_referencia` varchar(255) NOT NULL,
  PRIMARY KEY (`palabra_clave`,`codigo_referencia`),
  KEY `fk_palabras_claves_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_palabras_claves_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `palabras_claves`
--

/*!40000 ALTER TABLE `palabras_claves` DISABLE KEYS */;
INSERT INTO `palabras_claves` (`palabra_clave`,`codigo_referencia`) VALUES 
 ('freddie','AA/URQ/VIS'),
 ('mercury','AA/URQ/VIS'),
 ('jabon','AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1'),
 ('marruecos','AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1'),
 ('urquiza','AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1');
/*!40000 ALTER TABLE `palabras_claves` ENABLE KEYS */;


--
-- Definition of table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `idseccion` int(11) NOT NULL,
  `idrol` int(11) NOT NULL,
  `consultar` bit(1) DEFAULT b'0',
  `modificar` bit(1) DEFAULT b'0',
  `eliminar` bit(1) DEFAULT b'0',
  PRIMARY KEY (`idpermiso`),
  UNIQUE KEY `idseccion_idrol_UNIQUE` (`idseccion`,`idrol`),
  KEY `fk_permisos_secciones1` (`idseccion`),
  KEY `fk_permisos_roles1` (`idrol`),
  CONSTRAINT `fk_permisos_roles1` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_permisos_secciones1` FOREIGN KEY (`idseccion`) REFERENCES `secciones` (`idseccion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permisos`
--

/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;


--
-- Definition of table `recomendaciones_tratamiento`
--

DROP TABLE IF EXISTS `recomendaciones_tratamiento`;
CREATE TABLE `recomendaciones_tratamiento` (
  `recomendaciones_tratamiento` varchar(100) NOT NULL,
  PRIMARY KEY (`recomendaciones_tratamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recomendaciones_tratamiento`
--

/*!40000 ALTER TABLE `recomendaciones_tratamiento` DISABLE KEYS */;
INSERT INTO `recomendaciones_tratamiento` (`recomendaciones_tratamiento`) VALUES 
 ('recomendacion tratamiento 1'),
 ('recomendacion tratamiento 2'),
 ('recomendacion tratamiento 3');
/*!40000 ALTER TABLE `recomendaciones_tratamiento` ENABLE KEYS */;


--
-- Definition of table `registro_autoridad`
--

DROP TABLE IF EXISTS `registro_autoridad`;
CREATE TABLE `registro_autoridad` (
  `nombre_productor` varchar(50) NOT NULL,
  `codigo_institucion` varchar(50) NOT NULL,
  PRIMARY KEY (`nombre_productor`,`codigo_institucion`) USING BTREE,
  KEY `fk_registro_autoridad_instituciones1` (`codigo_institucion`),
  CONSTRAINT `fk_registro_autoridad_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registro_autoridad`
--

/*!40000 ALTER TABLE `registro_autoridad` DISABLE KEYS */;
INSERT INTO `registro_autoridad` (`nombre_productor`,`codigo_institucion`) VALUES 
 ('Juan Jose Campanella','AA'),
 ('productor 1','AR//BFG//DE//RE'),
 ('Carmen Flores Rodriguez','AR/ST/FFSDD/KASSD/GGe'),
 ('Juan Jose Camero','AR/ST/FFSDD/KASSD/GGe'),
 ('Melisa Gilbert','AR/ST/FFSDD/KASSD/GGe'),
 ('Susana Gimenez','AR/ST/FFSDD/KASSD/GGe');
/*!40000 ALTER TABLE `registro_autoridad` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_agregados`
--

DROP TABLE IF EXISTS `rel_conserv_agregados`;
CREATE TABLE `rel_conserv_agregados` (
  `codigo_gestion` int(11) NOT NULL,
  `codigo_agregado` int(11) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`codigo_agregado`),
  KEY `fk_rel_conserv_agregados_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_agregados_agregados1` (`codigo_agregado`),
  CONSTRAINT `fk_rel_conserv_agregados_agregados1` FOREIGN KEY (`codigo_agregado`) REFERENCES `agregados` (`codigo_agregado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_conserv_agregados_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_agregados`
--

/*!40000 ALTER TABLE `rel_conserv_agregados` DISABLE KEYS */;
INSERT INTO `rel_conserv_agregados` (`codigo_gestion`,`codigo_agregado`) VALUES 
 (1,7),
 (3,4);
/*!40000 ALTER TABLE `rel_conserv_agregados` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_guardas`
--

DROP TABLE IF EXISTS `rel_conserv_guardas`;
CREATE TABLE `rel_conserv_guardas` (
  `codigo_gestion` int(11) NOT NULL,
  `guarda` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`guarda`),
  KEY `fk_rel_conserv_guardas_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_guardas_guardas1` (`guarda`),
  CONSTRAINT `fk_rel_conserv_guardas_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_conserv_guardas_guardas1` FOREIGN KEY (`guarda`) REFERENCES `guardas` (`guarda`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_guardas`
--

/*!40000 ALTER TABLE `rel_conserv_guardas` DISABLE KEYS */;
INSERT INTO `rel_conserv_guardas` (`codigo_gestion`,`guarda`) VALUES 
 (1,'Guarda 1'),
 (1,'Guarda 2');
/*!40000 ALTER TABLE `rel_conserv_guardas` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_guardas_sugeridas`
--

DROP TABLE IF EXISTS `rel_conserv_guardas_sugeridas`;
CREATE TABLE `rel_conserv_guardas_sugeridas` (
  `codigo_gestion` int(11) NOT NULL,
  `guarda` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`guarda`),
  KEY `fk_rel_conserv_guardas_sugeridas_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_guardas_sugeridas_guardas1` (`guarda`),
  CONSTRAINT `fk_rel_conserv_guardas_sugeridas_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_conserv_guardas_sugeridas_guardas1` FOREIGN KEY (`guarda`) REFERENCES `guardas` (`guarda`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_guardas_sugeridas`
--

/*!40000 ALTER TABLE `rel_conserv_guardas_sugeridas` DISABLE KEYS */;
INSERT INTO `rel_conserv_guardas_sugeridas` (`codigo_gestion`,`guarda`) VALUES 
 (1,'Guarda 1'),
 (1,'Guarda 2');
/*!40000 ALTER TABLE `rel_conserv_guardas_sugeridas` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_material_soporte`
--

DROP TABLE IF EXISTS `rel_conserv_material_soporte`;
CREATE TABLE `rel_conserv_material_soporte` (
  `codigo_gestion` int(11) NOT NULL,
  `codigo_material_soporte` int(11) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`codigo_material_soporte`),
  KEY `fk_rel_conserv_material_soporte_material_soporte1` (`codigo_material_soporte`),
  KEY `fk_rel_conserv_material_soporte_gestion_conservacion1` (`codigo_gestion`),
  CONSTRAINT `fk_rel_conserv_material_soporte_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_conserv_material_soporte_material_soporte1` FOREIGN KEY (`codigo_material_soporte`) REFERENCES `material_soporte` (`codigo_material_soporte`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_material_soporte`
--

/*!40000 ALTER TABLE `rel_conserv_material_soporte` DISABLE KEYS */;
INSERT INTO `rel_conserv_material_soporte` (`codigo_gestion`,`codigo_material_soporte`) VALUES 
 (3,2),
 (1,4),
 (1,8);
/*!40000 ALTER TABLE `rel_conserv_material_soporte` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_medios`
--

DROP TABLE IF EXISTS `rel_conserv_medios`;
CREATE TABLE `rel_conserv_medios` (
  `codigo_gestion` int(11) NOT NULL,
  `codigo_medio` int(11) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`codigo_medio`),
  KEY `fk_rel_conserv_medios_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_medios_medios1` (`codigo_medio`),
  CONSTRAINT `fk_rel_conserv_medios_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_conserv_medios_medios1` FOREIGN KEY (`codigo_medio`) REFERENCES `medios` (`codigo_medio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_medios`
--

/*!40000 ALTER TABLE `rel_conserv_medios` DISABLE KEYS */;
INSERT INTO `rel_conserv_medios` (`codigo_gestion`,`codigo_medio`) VALUES 
 (1,6),
 (3,4);
/*!40000 ALTER TABLE `rel_conserv_medios` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_tratamientos_anteriores`
--

DROP TABLE IF EXISTS `rel_conserv_tratamientos_anteriores`;
CREATE TABLE `rel_conserv_tratamientos_anteriores` (
  `codigo_gestion` int(11) NOT NULL,
  `tratamiento_anterior` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`tratamiento_anterior`),
  KEY `fk_rel_conserv_tratamientos_anteriores_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_tratamientos_anteriores_tratamientos_anteriore1` (`tratamiento_anterior`),
  CONSTRAINT `fk_rel_conserv_tratamientos_anteriores_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_conserv_tratamientos_anteriores_tratamientos_anteriore1` FOREIGN KEY (`tratamiento_anterior`) REFERENCES `tratamientos_anteriores_evidentes` (`tratamiento_anterior`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_tratamientos_anteriores`
--

/*!40000 ALTER TABLE `rel_conserv_tratamientos_anteriores` DISABLE KEYS */;
INSERT INTO `rel_conserv_tratamientos_anteriores` (`codigo_gestion`,`tratamiento_anterior`) VALUES 
 (1,'tratamiento 1'),
 (1,'tratamiento 2');
/*!40000 ALTER TABLE `rel_conserv_tratamientos_anteriores` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_contenedores`
--

DROP TABLE IF EXISTS `rel_documentos_contenedores`;
CREATE TABLE `rel_documentos_contenedores` (
  `codigo_referencia` varchar(255) NOT NULL,
  `contenedor` varchar(50) NOT NULL,
  `ruta_acceso` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`contenedor`),
  KEY `fk_rel_documentos_contenedores_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_contenedores_contenedores1` (`contenedor`),
  CONSTRAINT `fk_rel_documentos_contenedores_contenedores1` FOREIGN KEY (`contenedor`) REFERENCES `contenedores` (`contenedor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_contenedores_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_contenedores`
--

/*!40000 ALTER TABLE `rel_documentos_contenedores` DISABLE KEYS */;
INSERT INTO `rel_documentos_contenedores` (`codigo_referencia`,`contenedor`,`ruta_acceso`) VALUES 
 ('AA/URQ/VIS','Disco Móvil','sector 9 estante 4'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Disco Móvil','estante 4'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Microfilm','estante 4');
/*!40000 ALTER TABLE `rel_documentos_contenedores` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_descriptores_geograficos`
--

DROP TABLE IF EXISTS `rel_documentos_descriptores_geograficos`;
CREATE TABLE `rel_documentos_descriptores_geograficos` (
  `codigo_referencia` varchar(255) NOT NULL,
  `descriptor_geografico` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`descriptor_geografico`),
  KEY `fk_rel_documentos_descriptores_geograficos_descriptores_geogr1` (`descriptor_geografico`),
  KEY `fk_rel_documentos_descriptores_geograficos_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_documentos_descriptores_geograficos_descriptores_geogr1` FOREIGN KEY (`descriptor_geografico`) REFERENCES `descriptores_geograficos` (`descriptor_geografico`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_descriptores_geograficos_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_descriptores_geograficos`
--

/*!40000 ALTER TABLE `rel_documentos_descriptores_geograficos` DISABLE KEYS */;
INSERT INTO `rel_documentos_descriptores_geograficos` (`codigo_referencia`,`descriptor_geografico`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','descriptor geografico 1'),
 ('AA/URQ/VIS','descriptor geografico 2'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','descriptor geografico 2');
/*!40000 ALTER TABLE `rel_documentos_descriptores_geograficos` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_descriptores_materias_contenidos`
--

DROP TABLE IF EXISTS `rel_documentos_descriptores_materias_contenidos`;
CREATE TABLE `rel_documentos_descriptores_materias_contenidos` (
  `codigo_referencia` varchar(255) NOT NULL,
  `descriptor_materias_contenido` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`descriptor_materias_contenido`),
  KEY `fk_rel_documentos_descriptores_materias_contenidos_descriptor1` (`descriptor_materias_contenido`),
  KEY `fk_rel_documentos_descriptores_materias_contenidos_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_documentos_descriptores_materias_contenidos_descriptor1` FOREIGN KEY (`descriptor_materias_contenido`) REFERENCES `descriptores_materias_contenidos` (`descriptor_materias_contenido`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_descriptores_materias_contenidos_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_descriptores_materias_contenidos`
--

/*!40000 ALTER TABLE `rel_documentos_descriptores_materias_contenidos` DISABLE KEYS */;
INSERT INTO `rel_documentos_descriptores_materias_contenidos` (`codigo_referencia`,`descriptor_materias_contenido`) VALUES 
 ('AA/URQ/VIS','Descriptor de Materia 1'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Descriptor de Materia 3'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Descriptor de Materia 4');
/*!40000 ALTER TABLE `rel_documentos_descriptores_materias_contenidos` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_descriptores_onomasticos`
--

DROP TABLE IF EXISTS `rel_documentos_descriptores_onomasticos`;
CREATE TABLE `rel_documentos_descriptores_onomasticos` (
  `codigo_referencia` varchar(255) NOT NULL,
  `descriptor_onomastico` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`descriptor_onomastico`),
  KEY `fk_rel_documentos_descriptores_onomasticos_descriptores_onoma1` (`descriptor_onomastico`),
  KEY `fk_rel_documentos_descriptores_onomasticos_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_documentos_descriptores_onomasticos_descriptores_onoma1` FOREIGN KEY (`descriptor_onomastico`) REFERENCES `descriptores_onomasticos` (`descriptor_onomastico`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_descriptores_onomasticos_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_descriptores_onomasticos`
--

/*!40000 ALTER TABLE `rel_documentos_descriptores_onomasticos` DISABLE KEYS */;
INSERT INTO `rel_documentos_descriptores_onomasticos` (`codigo_referencia`,`descriptor_onomastico`) VALUES 
 ('AA/URQ/VIS','descriptor onomastico 2'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','descriptor onomastico 2'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','descriptor onomastico 3');
/*!40000 ALTER TABLE `rel_documentos_descriptores_onomasticos` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_edificios`
--

DROP TABLE IF EXISTS `rel_documentos_edificios`;
CREATE TABLE `rel_documentos_edificios` (
  `codigo_referencia` varchar(255) NOT NULL,
  `nombre_edificio` varchar(255) NOT NULL,
  `ubicacion_topografica` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`nombre_edificio`),
  KEY `fk_rel_documentos_edificios_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_edificios_edificios1` (`nombre_edificio`),
  CONSTRAINT `fk_rel_documentos_edificios_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_edificios_edificios1` FOREIGN KEY (`nombre_edificio`) REFERENCES `edificios` (`nombre_edificio`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_edificios`
--

/*!40000 ALTER TABLE `rel_documentos_edificios` DISABLE KEYS */;
INSERT INTO `rel_documentos_edificios` (`codigo_referencia`,`nombre_edificio`,`ubicacion_topografica`) VALUES 
 ('AA/URQ/VIS','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','sector 9'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','estanteria de rojas 4');
/*!40000 ALTER TABLE `rel_documentos_edificios` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_envases`
--

DROP TABLE IF EXISTS `rel_documentos_envases`;
CREATE TABLE `rel_documentos_envases` (
  `codigo_referencia` varchar(255) NOT NULL,
  `idenvases` int(11) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`idenvases`),
  KEY `fk_rel_documentos_envases_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_envases_envases1` (`idenvases`),
  CONSTRAINT `fk_rel_documentos_envases_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_envases_envases1` FOREIGN KEY (`idenvases`) REFERENCES `envases` (`idenvases`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_envases`
--

/*!40000 ALTER TABLE `rel_documentos_envases` DISABLE KEYS */;
INSERT INTO `rel_documentos_envases` (`codigo_referencia`,`idenvases`) VALUES 
 ('AA/URQ/VIS',1),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1',1);
/*!40000 ALTER TABLE `rel_documentos_envases` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_exposiciones`
--

DROP TABLE IF EXISTS `rel_documentos_exposiciones`;
CREATE TABLE `rel_documentos_exposiciones` (
  `codigo_referencia` varchar(255) NOT NULL,
  `codigo_exposicion` int(11) NOT NULL,
  `numero_asignado` varchar(50) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`codigo_exposicion`),
  KEY `fk_rel_documentos_exposiciones_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_exposiciones_exposiciones1` (`codigo_exposicion`),
  CONSTRAINT `fk_rel_documentos_exposiciones_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_exposiciones_exposiciones1` FOREIGN KEY (`codigo_exposicion`) REFERENCES `exposiciones` (`codigo_exposicion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_exposiciones`
--

/*!40000 ALTER TABLE `rel_documentos_exposiciones` DISABLE KEYS */;
INSERT INTO `rel_documentos_exposiciones` (`codigo_referencia`,`codigo_exposicion`,`numero_asignado`,`fecha_entrega`,`fecha_devolucion`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1',1,'2512','2008-03-05 00:00:00','2009-03-04 00:00:00');
/*!40000 ALTER TABLE `rel_documentos_exposiciones` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_idiomas`
--

DROP TABLE IF EXISTS `rel_documentos_idiomas`;
CREATE TABLE `rel_documentos_idiomas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `idioma` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`idioma`),
  KEY `fk_rel_documentos_idiomas_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_idiomas_idiomas1` (`idioma`),
  CONSTRAINT `fk_rel_documentos_idiomas_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_idiomas_idiomas1` FOREIGN KEY (`idioma`) REFERENCES `idiomas` (`idioma`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_idiomas`
--

/*!40000 ALTER TABLE `rel_documentos_idiomas` DISABLE KEYS */;
INSERT INTO `rel_documentos_idiomas` (`codigo_referencia`,`idioma`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Alemán'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Español'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Italiano');
/*!40000 ALTER TABLE `rel_documentos_idiomas` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_rubros_autores`
--

DROP TABLE IF EXISTS `rel_documentos_rubros_autores`;
CREATE TABLE `rel_documentos_rubros_autores` (
  `codigo_referencia` varchar(255) NOT NULL,
  `rubro` varchar(50) NOT NULL,
  `autor` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`rubro`,`autor`),
  KEY `fk_rel_documentos_rubros_autores_documentos1` (`codigo_referencia`),
  KEY `fk_rel_documentos_rubros_autores_autores1` (`autor`),
  KEY `fk_rel_documentos_rubros_autores_rubros1` (`rubro`),
  CONSTRAINT `fk_rel_documentos_rubros_autores_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_rubros_autores_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_rubros_autores_rubros1` FOREIGN KEY (`rubro`) REFERENCES `rubros` (`rubro`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_rubros_autores`
--

/*!40000 ALTER TABLE `rel_documentos_rubros_autores` DISABLE KEYS */;
INSERT INTO `rel_documentos_rubros_autores` (`codigo_referencia`,`rubro`,`autor`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Arreglos Musicales','Mirtha Legrand'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/DOC_C1/doc_c_s1','Pista Principal','Nini Marshal');
/*!40000 ALTER TABLE `rel_documentos_rubros_autores` ENABLE KEYS */;


--
-- Definition of table `rel_documentos_usuarios`
--

DROP TABLE IF EXISTS `rel_documentos_usuarios`;
CREATE TABLE `rel_documentos_usuarios` (
  `codigo_referencia` varchar(255) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`idusuario`),
  KEY `fk_rel_documentos_usuarios_usuarios1` (`idusuario`),
  KEY `fk_rel_documentos_usuarios_documentos1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_documentos_usuarios_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_usuarios_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_usuarios`
--

/*!40000 ALTER TABLE `rel_documentos_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_documentos_usuarios` ENABLE KEYS */;


--
-- Definition of table `rel_gestion_conserv_caract_toma_fotog`
--

DROP TABLE IF EXISTS `rel_gestion_conserv_caract_toma_fotog`;
CREATE TABLE `rel_gestion_conserv_caract_toma_fotog` (
  `codigo_gestion` int(11) NOT NULL,
  `caracteristica_toma_fotografica` varchar(50) NOT NULL,
  `posterior` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigo_gestion`,`caracteristica_toma_fotografica`,`posterior`),
  KEY `fk_rel_gestion_conserv_caract_toma_fotog_caracteristicas_toma1` (`caracteristica_toma_fotografica`),
  CONSTRAINT `fk_rel_gestion_conserv_caract_toma_fotog_caracteristicas_toma1` FOREIGN KEY (`caracteristica_toma_fotografica`) REFERENCES `caracteristicas_toma_fotografica` (`caracteristica_toma_fotografica`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_gestion_conserv_caract_toma_fotog_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_gestion_conserv_caract_toma_fotog`
--

/*!40000 ALTER TABLE `rel_gestion_conserv_caract_toma_fotog` DISABLE KEYS */;
INSERT INTO `rel_gestion_conserv_caract_toma_fotog` (`codigo_gestion`,`caracteristica_toma_fotografica`,`posterior`) VALUES 
 (1,'caracteristica de la toma fotografica 1',0),
 (1,'caracteristica de la toma fotografica 1',1),
 (1,'caracteristica de la toma fotografica 2',0),
 (1,'caracteristica de la toma fotografica 2',1);
/*!40000 ALTER TABLE `rel_gestion_conserv_caract_toma_fotog` ENABLE KEYS */;


--
-- Definition of table `rel_gestion_conserv_recomendaciones_tratamiento`
--

DROP TABLE IF EXISTS `rel_gestion_conserv_recomendaciones_tratamiento`;
CREATE TABLE `rel_gestion_conserv_recomendaciones_tratamiento` (
  `codigo_gestion` int(11) NOT NULL,
  `recomendaciones_tratamiento` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`recomendaciones_tratamiento`),
  KEY `fk_rel_gestion_conserv_recomendaciones_tratamiento_recomendac1` (`recomendaciones_tratamiento`),
  KEY `fk_rel_gestion_conserv_recomendaciones_tratamiento_gestion_co1` (`codigo_gestion`),
  CONSTRAINT `fk_rel_gestion_conserv_recomendaciones_tratamiento_gestion_co1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_gestion_conserv_recomendaciones_tratamiento_recomendac1` FOREIGN KEY (`recomendaciones_tratamiento`) REFERENCES `recomendaciones_tratamiento` (`recomendaciones_tratamiento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_gestion_conserv_recomendaciones_tratamiento`
--

/*!40000 ALTER TABLE `rel_gestion_conserv_recomendaciones_tratamiento` DISABLE KEYS */;
INSERT INTO `rel_gestion_conserv_recomendaciones_tratamiento` (`codigo_gestion`,`recomendaciones_tratamiento`) VALUES 
 (1,'recomendacion tratamiento 1'),
 (1,'recomendacion tratamiento 2');
/*!40000 ALTER TABLE `rel_gestion_conserv_recomendaciones_tratamiento` ENABLE KEYS */;


--
-- Definition of table `rel_gestion_conservacion_usuarios`
--

DROP TABLE IF EXISTS `rel_gestion_conservacion_usuarios`;
CREATE TABLE `rel_gestion_conservacion_usuarios` (
  `codigo_gestion` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo_gestion`,`idusuario`),
  KEY `fk_rel_gestion_conservacion_usuarios_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_gestion_conservacion_usuarios_usuarios1` (`idusuario`),
  CONSTRAINT `fk_rel_gestion_conservacion_usuarios_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_gestion_conservacion_usuarios_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_gestion_conservacion_usuarios`
--

/*!40000 ALTER TABLE `rel_gestion_conservacion_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_gestion_conservacion_usuarios` ENABLE KEYS */;


--
-- Definition of table `rel_instituciones_serviciosdereproduccion`
--

DROP TABLE IF EXISTS `rel_instituciones_serviciosdereproduccion`;
CREATE TABLE `rel_instituciones_serviciosdereproduccion` (
  `codigo_institucion` varchar(50) NOT NULL,
  `servicio_reproduccion` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_institucion`,`servicio_reproduccion`),
  KEY `fk_rel_instituciones_serviciosdereproduccion_servicios_reprod` (`servicio_reproduccion`),
  KEY `fk_rel_instituciones_serviciosdereproduccion_instituciones` (`codigo_institucion`),
  CONSTRAINT `fk_rel_instituciones_serviciosdereproduccion_instituciones` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_instituciones_serviciosdereproduccion_servicios_reprod` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_instituciones_serviciosdereproduccion`
--

/*!40000 ALTER TABLE `rel_instituciones_serviciosdereproduccion` DISABLE KEYS */;
INSERT INTO `rel_instituciones_serviciosdereproduccion` (`codigo_institucion`,`servicio_reproduccion`) VALUES 
 ('AA','Copiar'),
 ('AR/SC/DNPYM/HS','Copiar'),
 ('AR//BFG//DE//RE','Digitalizar'),
 ('AR/ST/FFSDD/KASSD/GGe','Digitalizar'),
 ('AR/SC/DNPYM/HS','Transcribir'),
 ('AR/ST/FFSDD/KASSD/GGe','Transcribir');
/*!40000 ALTER TABLE `rel_instituciones_serviciosdereproduccion` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_contenedores`
--

DROP TABLE IF EXISTS `rel_niveles_contenedores`;
CREATE TABLE `rel_niveles_contenedores` (
  `codigo_referencia` varchar(255) NOT NULL,
  `contenedor` varchar(50) NOT NULL,
  `ruta_acceso` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`contenedor`,`ruta_acceso`),
  KEY `fk_rel_niveles_contenedores_contenedores1` (`contenedor`),
  CONSTRAINT `fk_rel_niveles_contenedores_contenedores1` FOREIGN KEY (`contenedor`) REFERENCES `contenedores` (`contenedor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_contenedores_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_contenedores`
--

/*!40000 ALTER TABLE `rel_niveles_contenedores` DISABLE KEYS */;
INSERT INTO `rel_niveles_contenedores` (`codigo_referencia`,`contenedor`,`ruta_acceso`) VALUES 
 ('AA/URQ','Disco Móvil','estante 4'),
 ('AR//BFG//DE//RE/FF01_a','Disco Móvil','estante 4'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Disco Móvil','estante 4 estacion 5'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Disco Móvil','estanto 4'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Disco Móvil','estantes de discos moviles'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Microfilm','estante 1 pieza 5');
/*!40000 ALTER TABLE `rel_niveles_contenedores` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_edificios`
--

DROP TABLE IF EXISTS `rel_niveles_edificios`;
CREATE TABLE `rel_niveles_edificios` (
  `codigo_referencia` varchar(255) NOT NULL,
  `nombre_edificio` varchar(255) NOT NULL,
  `ubicacion_topografica` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia`,`nombre_edificio`),
  KEY `fk_rel_niveles_edificios_edificios1` (`nombre_edificio`),
  CONSTRAINT `fk_rel_niveles_edificios_edificios1` FOREIGN KEY (`nombre_edificio`) REFERENCES `edificios` (`nombre_edificio`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_edificios_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_edificios`
--

/*!40000 ALTER TABLE `rel_niveles_edificios` DISABLE KEYS */;
INSERT INTO `rel_niveles_edificios` (`codigo_referencia`,`nombre_edificio`,`ubicacion_topografica`) VALUES 
 ('AA/URQ','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','estante 4'),
 ('AR//BFG//DE//RE/FF01_a','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','sector 4'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','sector4'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','4'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Sector Oficinas Museo Histórico Nacional Del Cabildo y de la Revolución de Mayo','estante 3');
/*!40000 ALTER TABLE `rel_niveles_edificios` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_idiomas`
--

DROP TABLE IF EXISTS `rel_niveles_idiomas`;
CREATE TABLE `rel_niveles_idiomas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `idioma` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`idioma`),
  KEY `fk_rel_niveles_idiomas_idiomas1` (`idioma`),
  CONSTRAINT `fk_rel_niveles_idiomas_idiomas1` FOREIGN KEY (`idioma`) REFERENCES `idiomas` (`idioma`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_idiomas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_idiomas`
--

/*!40000 ALTER TABLE `rel_niveles_idiomas` DISABLE KEYS */;
INSERT INTO `rel_niveles_idiomas` (`codigo_referencia`,`idioma`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Alemán'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Español'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Español');
/*!40000 ALTER TABLE `rel_niveles_idiomas` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_institucionesexternas`
--

DROP TABLE IF EXISTS `rel_niveles_institucionesexternas`;
CREATE TABLE `rel_niveles_institucionesexternas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `codigo_referencia_institucion_externa` varchar(50) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`codigo_referencia`,`codigo_referencia_institucion_externa`),
  KEY `fk_rel_niveles_institucionesexternas_instituciones_externas1` (`codigo_referencia_institucion_externa`),
  CONSTRAINT `fk_rel_niveles_institucionesexternas_instituciones_externas1` FOREIGN KEY (`codigo_referencia_institucion_externa`) REFERENCES `instituciones_externas` (`codigo_referencia_intitucion_externa`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_institucionesexternas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_institucionesexternas`
--

/*!40000 ALTER TABLE `rel_niveles_institucionesexternas` DISABLE KEYS */;
INSERT INTO `rel_niveles_institucionesexternas` (`codigo_referencia`,`codigo_referencia_institucion_externa`,`descripcion`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/BB/CCD','aaaaaaa');
/*!40000 ALTER TABLE `rel_niveles_institucionesexternas` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_institucionesinternas`
--

DROP TABLE IF EXISTS `rel_niveles_institucionesinternas`;
CREATE TABLE `rel_niveles_institucionesinternas` (
  `codigo_referencia` varchar(255) NOT NULL,
  `codigo_referencia_institucion_interna` varchar(255) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`codigo_referencia`,`codigo_referencia_institucion_interna`),
  KEY `fk_rel_niveles_institucionesinternas_instituciones1` (`codigo_referencia_institucion_interna`),
  CONSTRAINT `fk_rel_niveles_institucionesinternas_instituciones1` FOREIGN KEY (`codigo_referencia_institucion_interna`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_institucionesinternas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_institucionesinternas`
--

/*!40000 ALTER TABLE `rel_niveles_institucionesinternas` DISABLE KEYS */;
INSERT INTO `rel_niveles_institucionesinternas` (`codigo_referencia`,`codigo_referencia_institucion_interna`,`descripcion`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/ST/FFSDD/KASSD/GGe','bbbbbbb');
/*!40000 ALTER TABLE `rel_niveles_institucionesinternas` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_niveles`
--

DROP TABLE IF EXISTS `rel_niveles_niveles`;
CREATE TABLE `rel_niveles_niveles` (
  `codigo_referencia1` varchar(255) NOT NULL,
  `codigo_referencia2` varchar(255) NOT NULL,
  `descripcion_vinculo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_referencia1`,`codigo_referencia2`),
  KEY `fk_rel_niveles_niveles_niveles2` (`codigo_referencia2`),
  CONSTRAINT `fk_rel_niveles_niveles_niveles1` FOREIGN KEY (`codigo_referencia1`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_niveles_niveles2` FOREIGN KEY (`codigo_referencia2`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_niveles`
--

/*!40000 ALTER TABLE `rel_niveles_niveles` DISABLE KEYS */;
INSERT INTO `rel_niveles_niveles` (`codigo_referencia1`,`codigo_referencia2`,`descripcion_vinculo`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/ST/FFSDD/KASSD/GGe/FF01/SF01/SE01/SS01/AD01','ccccccc'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/ST/FFSDD/KASSD/GGe/FF02/SF02/SE02/RRST/BST02',NULL),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','AR/ST/FFSDD/KASSD/GGe/SF03',NULL),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','AR/ST/FFSDD/KASSD/GGe/SF03','vinculo');
/*!40000 ALTER TABLE `rel_niveles_niveles` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_productores`
--

DROP TABLE IF EXISTS `rel_niveles_productores`;
CREATE TABLE `rel_niveles_productores` (
  `codigo_referencia` varchar(255) NOT NULL,
  `nombre_productor` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`nombre_productor`),
  KEY `fk_rel_niveles_productores_registro_autoridad1` (`nombre_productor`),
  KEY `fk_rel_niveles_productores_niveles1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_niveles_productores_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_productores_registro_autoridad1` FOREIGN KEY (`nombre_productor`) REFERENCES `registro_autoridad` (`nombre_productor`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_productores`
--

/*!40000 ALTER TABLE `rel_niveles_productores` DISABLE KEYS */;
INSERT INTO `rel_niveles_productores` (`codigo_referencia`,`nombre_productor`) VALUES 
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Carmen Flores Rodriguez'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Juan Jose Camero'),
 ('AA/URQ','Juan Jose Campanella'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Melisa Gilbert'),
 ('AR//BFG//DE//RE/FF01_a','productor 1');
/*!40000 ALTER TABLE `rel_niveles_productores` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_serviciosreproduccion`
--

DROP TABLE IF EXISTS `rel_niveles_serviciosreproduccion`;
CREATE TABLE `rel_niveles_serviciosreproduccion` (
  `codigo_referencia` varchar(255) NOT NULL,
  `servicio_reproduccion` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`servicio_reproduccion`),
  KEY `fk_rel_niveles_serviciosreproduccion_servicios_reproduccion1` (`servicio_reproduccion`),
  CONSTRAINT `fk_rel_niveles_serviciosreproduccion_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_serviciosreproduccion_servicios_reproduccion1` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_serviciosreproduccion`
--

/*!40000 ALTER TABLE `rel_niveles_serviciosreproduccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_niveles_serviciosreproduccion` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_sistemasorganizacion`
--

DROP TABLE IF EXISTS `rel_niveles_sistemasorganizacion`;
CREATE TABLE `rel_niveles_sistemasorganizacion` (
  `codigo_referencia` varchar(255) NOT NULL,
  `sistema_organizacion` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`sistema_organizacion`),
  KEY `fk_rel_niveles_sistemasorganizacion_sistemas_organizaciones1` (`sistema_organizacion`),
  CONSTRAINT `fk_rel_niveles_sistemasorganizacion_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_sistemasorganizacion_sistemas_organizaciones1` FOREIGN KEY (`sistema_organizacion`) REFERENCES `sistemas_organizaciones` (`sistema_organizacion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_sistemasorganizacion`
--

/*!40000 ALTER TABLE `rel_niveles_sistemasorganizacion` DISABLE KEYS */;
INSERT INTO `rel_niveles_sistemasorganizacion` (`codigo_referencia`,`sistema_organizacion`) VALUES 
 ('AA/URQ','Maria martha serralima'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Sistema 2'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Sistema 2'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Sistema 4');
/*!40000 ALTER TABLE `rel_niveles_sistemasorganizacion` ENABLE KEYS */;


--
-- Definition of table `rel_niveles_soportes`
--

DROP TABLE IF EXISTS `rel_niveles_soportes`;
CREATE TABLE `rel_niveles_soportes` (
  `codigo_referencia` varchar(255) NOT NULL,
  `soporte` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_referencia`,`soporte`),
  KEY `fk_rel_niveles_soportes_soportes1` (`soporte`),
  KEY `fk_rel_niveles_soportes_niveles1` (`codigo_referencia`),
  CONSTRAINT `fk_rel_niveles_soportes_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_soportes_soportes1` FOREIGN KEY (`soporte`) REFERENCES `soportes` (`soporte`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_soportes`
--

/*!40000 ALTER TABLE `rel_niveles_soportes` DISABLE KEYS */;
INSERT INTO `rel_niveles_soportes` (`codigo_referencia`,`soporte`) VALUES 
 ('AA/URQ','Soporte 1'),
 ('AR/ST/FFSDD/KASSD/GGe/SF03/GDE3/GFR34/GG02/ser0456/sub01','Soporte 1'),
 ('AR/ST/FFSDD/KASSD/GGe/FF02/SF02','Soporte 3');
/*!40000 ALTER TABLE `rel_niveles_soportes` ENABLE KEYS */;


--
-- Definition of table `rel_usuarios_grupos`
--

DROP TABLE IF EXISTS `rel_usuarios_grupos`;
CREATE TABLE `rel_usuarios_grupos` (
  `idrel_usuarios_grupos` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) DEFAULT NULL,
  `idgrupo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idrel_usuarios_grupos`),
  KEY `fk_rel_usuarios_grupos_usuarios1` (`usuario`),
  KEY `fk_rel_usuarios_grupos_grupos1` (`idgrupo`),
  CONSTRAINT `fk_rel_usuarios_grupos_grupos1` FOREIGN KEY (`idgrupo`) REFERENCES `grupos` (`idgrupo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuarios_grupos_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_usuarios_grupos`
--

/*!40000 ALTER TABLE `rel_usuarios_grupos` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_usuarios_grupos` ENABLE KEYS */;


--
-- Definition of table `rel_usuarios_instituciones`
--

DROP TABLE IF EXISTS `rel_usuarios_instituciones`;
CREATE TABLE `rel_usuarios_instituciones` (
  `idrel_usuarios_instituciones` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) DEFAULT NULL,
  `codigo_identificacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idrel_usuarios_instituciones`),
  UNIQUE KEY `idusuario_UNIQUE` (`idusuario`,`codigo_identificacion`),
  KEY `fk_rel_usuarios_instituciones_instituciones1` (`codigo_identificacion`),
  KEY `fk_rel_usuarios_instituciones_usuarios1` (`idusuario`),
  CONSTRAINT `fk_rel_usuarios_instituciones_instituciones1` FOREIGN KEY (`codigo_identificacion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuarios_instituciones_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_usuarios_instituciones`
--

/*!40000 ALTER TABLE `rel_usuarios_instituciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_usuarios_instituciones` ENABLE KEYS */;


--
-- Definition of table `rel_usuarios_roles_instituciones`
--

DROP TABLE IF EXISTS `rel_usuarios_roles_instituciones`;
CREATE TABLE `rel_usuarios_roles_instituciones` (
  `idrel_usuarios_roles_instituciones` int(11) NOT NULL AUTO_INCREMENT,
  `idrol` int(11) NOT NULL,
  `idrel_usuarios_instituciones` int(11) DEFAULT NULL,
  PRIMARY KEY (`idrel_usuarios_roles_instituciones`),
  UNIQUE KEY `idusuario_UNIQUE` (`idrol`,`idrel_usuarios_instituciones`),
  KEY `fk_rel_usuarios_roles_instituciones_roles1` (`idrol`),
  KEY `fk_rel_usuarios_roles_instituciones_rel_usuarios_instituciones1` (`idrel_usuarios_instituciones`),
  CONSTRAINT `fk_rel_usuarios_roles_instituciones_rel_usuarios_instituciones1` FOREIGN KEY (`idrel_usuarios_instituciones`) REFERENCES `rel_usuarios_instituciones` (`idrel_usuarios_instituciones`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuarios_roles_instituciones_roles1` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_usuarios_roles_instituciones`
--

/*!40000 ALTER TABLE `rel_usuarios_roles_instituciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_usuarios_roles_instituciones` ENABLE KEYS */;


--
-- Definition of table `requisitos_accesos`
--

DROP TABLE IF EXISTS `requisitos_accesos`;
CREATE TABLE `requisitos_accesos` (
  `requisito_acceso` varchar(100) NOT NULL,
  PRIMARY KEY (`requisito_acceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requisitos_accesos`
--

/*!40000 ALTER TABLE `requisitos_accesos` DISABLE KEYS */;
INSERT INTO `requisitos_accesos` (`requisito_acceso`) VALUES 
 ('DNI'),
 ('Documento de identificación equivalente para no ciudadanos del país'),
 ('Nota de autoridad académica'),
 ('Nota de autoridad nacional'),
 ('Pasaporte');
/*!40000 ALTER TABLE `requisitos_accesos` ENABLE KEYS */;


--
-- Definition of table `requisitos_ejecucion`
--

DROP TABLE IF EXISTS `requisitos_ejecucion`;
CREATE TABLE `requisitos_ejecucion` (
  `requisito_ejecucion` varchar(50) NOT NULL,
  PRIMARY KEY (`requisito_ejecucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requisitos_ejecucion`
--

/*!40000 ALTER TABLE `requisitos_ejecucion` DISABLE KEYS */;
INSERT INTO `requisitos_ejecucion` (`requisito_ejecucion`) VALUES 
 ('Requisito de Ejecucion 1'),
 ('Requisito de Ejecucion 2'),
 ('Requisito de Ejecucion 3'),
 ('Requisito de Ejecucion 4');
/*!40000 ALTER TABLE `requisitos_ejecucion` ENABLE KEYS */;


--
-- Definition of table `responsable_archivo`
--

DROP TABLE IF EXISTS `responsable_archivo`;
CREATE TABLE `responsable_archivo` (
  `codigo_responsable` int(11) NOT NULL AUTO_INCREMENT,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `area_responsabilidad` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `movil` varchar(100) DEFAULT NULL,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_responsable`),
  KEY `fk_responsable_archivo_instituciones` (`codigo_institucion`),
  CONSTRAINT `fk_responsable_archivo_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `responsable_archivo`
--

/*!40000 ALTER TABLE `responsable_archivo` DISABLE KEYS */;
INSERT INTO `responsable_archivo` (`codigo_responsable`,`apellido`,`nombre`,`area_responsabilidad`,`email`,`telefono`,`movil`,`codigo_institucion`) VALUES 
 (1,'martinez','uan manuel',NULL,'joako@elsitio.com',NULL,NULL,'AR/ST/FFSDD/KASSD/GGe'),
 (2,'De Luca','Juan Manuel','Area Central','jmdl@estrellita.com.ar','4569 4523',NULL,'AR//BFG//DE//RE'),
 (3,'Sciciliani','jose maria','comandante','jose@hotmail.com','2345 3434','23456 2345','AA'),
 (4,'Saez','Karina','Cantante','ksaez@gylgroup.com','1435135','12351345','AR/SC/DNPYM/HS');
/*!40000 ALTER TABLE `responsable_archivo` ENABLE KEYS */;


--
-- Definition of table `responsable_institucion`
--

DROP TABLE IF EXISTS `responsable_institucion`;
CREATE TABLE `responsable_institucion` (
  `codigo_responsable` int(11) NOT NULL AUTO_INCREMENT,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `area_responsabilidad` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `movil` varchar(100) DEFAULT NULL,
  `codigo_institucion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codigo_responsable`),
  KEY `fk_responsable_institucion_instituciones` (`codigo_institucion`),
  CONSTRAINT `fk_responsable_institucion_instituciones` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `responsable_institucion`
--

/*!40000 ALTER TABLE `responsable_institucion` DISABLE KEYS */;
INSERT INTO `responsable_institucion` (`codigo_responsable`,`apellido`,`nombre`,`area_responsabilidad`,`email`,`telefono`,`movil`,`codigo_institucion`) VALUES 
 (1,'lucas','martinez','espeluznante','lucas@fa.com.ar','4523 4523','15 7856 5698','AR/ST/FFSDD/KASSD/GGe'),
 (2,'pereyra','juan jose','Ocupante','jjp@pereira.com.ar','4526 4562','15 2369 1236','AR//BFG//DE//RE'),
 (3,'shuanet',NULL,NULL,NULL,NULL,NULL,'AA'),
 (4,'Schimf','Mario Daniel','Programador Sr','camilito@hotmail.com','455555','4564768','AR/SC/DNPYM/HS');
/*!40000 ALTER TABLE `responsable_institucion` ENABLE KEYS */;


--
-- Definition of table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `idrol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(100) NOT NULL,
  `activo` bit(1) DEFAULT b'1',
  PRIMARY KEY (`idrol`),
  UNIQUE KEY `rol_UNIQUE` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;


--
-- Definition of table `rubros`
--

DROP TABLE IF EXISTS `rubros`;
CREATE TABLE `rubros` (
  `rubro` varchar(50) NOT NULL,
  PRIMARY KEY (`rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rubros`
--

/*!40000 ALTER TABLE `rubros` DISABLE KEYS */;
INSERT INTO `rubros` (`rubro`) VALUES 
 ('Arreglos Musicales'),
 ('Marco ISO Audigy'),
 ('Pista Principal'),
 ('Populate');
/*!40000 ALTER TABLE `rubros` ENABLE KEYS */;


--
-- Definition of table `secciones`
--

DROP TABLE IF EXISTS `secciones`;
CREATE TABLE `secciones` (
  `idseccion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`idseccion`),
  UNIQUE KEY `descripcion_UNIQUE` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `secciones`
--

/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;


--
-- Definition of table `servicios_reproduccion`
--

DROP TABLE IF EXISTS `servicios_reproduccion`;
CREATE TABLE `servicios_reproduccion` (
  `servicio_reproduccion` varchar(50) NOT NULL,
  PRIMARY KEY (`servicio_reproduccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servicios_reproduccion`
--

/*!40000 ALTER TABLE `servicios_reproduccion` DISABLE KEYS */;
INSERT INTO `servicios_reproduccion` (`servicio_reproduccion`) VALUES 
 ('Copiar'),
 ('Digitalizar'),
 ('Microfilmar'),
 ('Transcribir');
/*!40000 ALTER TABLE `servicios_reproduccion` ENABLE KEYS */;


--
-- Definition of table `sistemas_organizaciones`
--

DROP TABLE IF EXISTS `sistemas_organizaciones`;
CREATE TABLE `sistemas_organizaciones` (
  `sistema_organizacion` varchar(50) NOT NULL,
  PRIMARY KEY (`sistema_organizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sistemas_organizaciones`
--

/*!40000 ALTER TABLE `sistemas_organizaciones` DISABLE KEYS */;
INSERT INTO `sistemas_organizaciones` (`sistema_organizacion`) VALUES 
 ('Maria martha serralima'),
 ('Sistema 1'),
 ('Sistema 2'),
 ('Sistema 3'),
 ('Sistema 4');
/*!40000 ALTER TABLE `sistemas_organizaciones` ENABLE KEYS */;


--
-- Definition of table `situaciones`
--

DROP TABLE IF EXISTS `situaciones`;
CREATE TABLE `situaciones` (
  `situacion` varchar(50) NOT NULL,
  PRIMARY KEY (`situacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `situaciones`
--

/*!40000 ALTER TABLE `situaciones` DISABLE KEYS */;
INSERT INTO `situaciones` (`situacion`) VALUES 
 ('Baja'),
 ('Conservación'),
 ('Exposición'),
 ('Local'),
 ('No Localizado'),
 ('Prestamo'),
 ('Robado');
/*!40000 ALTER TABLE `situaciones` ENABLE KEYS */;


--
-- Definition of table `sonidos`
--

DROP TABLE IF EXISTS `sonidos`;
CREATE TABLE `sonidos` (
  `sonido` varchar(50) NOT NULL,
  PRIMARY KEY (`sonido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sonidos`
--

/*!40000 ALTER TABLE `sonidos` DISABLE KEYS */;
INSERT INTO `sonidos` (`sonido`) VALUES 
 ('sonido 1'),
 ('sonido 2'),
 ('sonido 3');
/*!40000 ALTER TABLE `sonidos` ENABLE KEYS */;


--
-- Definition of table `soportes`
--

DROP TABLE IF EXISTS `soportes`;
CREATE TABLE `soportes` (
  `soporte` varchar(50) NOT NULL,
  PRIMARY KEY (`soporte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `soportes`
--

/*!40000 ALTER TABLE `soportes` DISABLE KEYS */;
INSERT INTO `soportes` (`soporte`) VALUES 
 ('Soporte 1'),
 ('Soporte 2'),
 ('Soporte 3');
/*!40000 ALTER TABLE `soportes` ENABLE KEYS */;


--
-- Definition of table `tecnicas_digitales`
--

DROP TABLE IF EXISTS `tecnicas_digitales`;
CREATE TABLE `tecnicas_digitales` (
  `tecnica_digital` varchar(50) NOT NULL,
  PRIMARY KEY (`tecnica_digital`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tecnicas_digitales`
--

/*!40000 ALTER TABLE `tecnicas_digitales` DISABLE KEYS */;
INSERT INTO `tecnicas_digitales` (`tecnica_digital`) VALUES 
 ('tecnica digial 1'),
 ('tecnica digital 2'),
 ('tecnica digital 3'),
 ('tecnica digital 4');
/*!40000 ALTER TABLE `tecnicas_digitales` ENABLE KEYS */;


--
-- Definition of table `tecnicas_fotograficas`
--

DROP TABLE IF EXISTS `tecnicas_fotograficas`;
CREATE TABLE `tecnicas_fotograficas` (
  `tecnica_fotografica` varchar(50) NOT NULL,
  PRIMARY KEY (`tecnica_fotografica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tecnicas_fotograficas`
--

/*!40000 ALTER TABLE `tecnicas_fotograficas` DISABLE KEYS */;
INSERT INTO `tecnicas_fotograficas` (`tecnica_fotografica`) VALUES 
 ('tecnica fotografica 1'),
 ('tecnica fotografica 2'),
 ('tecnica fotografica 3'),
 ('tecnica fotografica 4');
/*!40000 ALTER TABLE `tecnicas_fotograficas` ENABLE KEYS */;


--
-- Definition of table `tecnicas_visuales`
--

DROP TABLE IF EXISTS `tecnicas_visuales`;
CREATE TABLE `tecnicas_visuales` (
  `tecnica_visual` varchar(50) NOT NULL,
  PRIMARY KEY (`tecnica_visual`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tecnicas_visuales`
--

/*!40000 ALTER TABLE `tecnicas_visuales` DISABLE KEYS */;
INSERT INTO `tecnicas_visuales` (`tecnica_visual`) VALUES 
 ('tecnica visual 1'),
 ('tecnica visual 2'),
 ('tecnica visual 3'),
 ('tecnica visual 4');
/*!40000 ALTER TABLE `tecnicas_visuales` ENABLE KEYS */;


--
-- Definition of table `tipo_direcciones`
--

DROP TABLE IF EXISTS `tipo_direcciones`;
CREATE TABLE `tipo_direcciones` (
  `tipo_direccion` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipo_direcciones`
--

/*!40000 ALTER TABLE `tipo_direcciones` DISABLE KEYS */;
INSERT INTO `tipo_direcciones` (`tipo_direccion`) VALUES 
 ('Depósito'),
 ('Sede Central');
/*!40000 ALTER TABLE `tipo_direcciones` ENABLE KEYS */;


--
-- Definition of table `tipo_entidad`
--

DROP TABLE IF EXISTS `tipo_entidad`;
CREATE TABLE `tipo_entidad` (
  `tipo_entidad` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_entidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipo_entidad`
--

/*!40000 ALTER TABLE `tipo_entidad` DISABLE KEYS */;
INSERT INTO `tipo_entidad` (`tipo_entidad`) VALUES 
 ('Familia'),
 ('Institucional'),
 ('Persona');
/*!40000 ALTER TABLE `tipo_entidad` ENABLE KEYS */;


--
-- Definition of table `tipo_institucion`
--

DROP TABLE IF EXISTS `tipo_institucion`;
CREATE TABLE `tipo_institucion` (
  `tipo_institucion` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_institucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipo_institucion`
--

/*!40000 ALTER TABLE `tipo_institucion` DISABLE KEYS */;
INSERT INTO `tipo_institucion` (`tipo_institucion`) VALUES 
 ('Empresarial'),
 ('Estatal'),
 ('Sociedad Civil');
/*!40000 ALTER TABLE `tipo_institucion` ENABLE KEYS */;


--
-- Definition of table `tipo_normas`
--

DROP TABLE IF EXISTS `tipo_normas`;
CREATE TABLE `tipo_normas` (
  `tipo_norma` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_norma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipo_normas`
--

/*!40000 ALTER TABLE `tipo_normas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_normas` ENABLE KEYS */;


--
-- Definition of table `tipos_accesos`
--

DROP TABLE IF EXISTS `tipos_accesos`;
CREATE TABLE `tipos_accesos` (
  `tipo_acceso` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_acceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_accesos`
--

/*!40000 ALTER TABLE `tipos_accesos` DISABLE KEYS */;
INSERT INTO `tipos_accesos` (`tipo_acceso`) VALUES 
 (''),
 ('Arancelado - Libre'),
 ('Arancelado - Solo para investigadores'),
 ('Gratuito  - Solo para investigadores'),
 ('Gratuito - Libre');
/*!40000 ALTER TABLE `tipos_accesos` ENABLE KEYS */;


--
-- Definition of table `tipos_especificos_documentos`
--

DROP TABLE IF EXISTS `tipos_especificos_documentos`;
CREATE TABLE `tipos_especificos_documentos` (
  `tipo_especifico_documento` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_especifico_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_especificos_documentos`
--

/*!40000 ALTER TABLE `tipos_especificos_documentos` DISABLE KEYS */;
INSERT INTO `tipos_especificos_documentos` (`tipo_especifico_documento`) VALUES 
 ('Cartas'),
 ('Carteles'),
 ('Dibujos'),
 ('Fotos'),
 ('Grabados');
/*!40000 ALTER TABLE `tipos_especificos_documentos` ENABLE KEYS */;


--
-- Definition of table `tipos_general_documentos`
--

DROP TABLE IF EXISTS `tipos_general_documentos`;
CREATE TABLE `tipos_general_documentos` (
  `tipo_general_documento` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_general_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_general_documentos`
--

/*!40000 ALTER TABLE `tipos_general_documentos` DISABLE KEYS */;
INSERT INTO `tipos_general_documentos` (`tipo_general_documento`) VALUES 
 ('Audiovisual'),
 ('Cartográfico'),
 ('Fotográfico'),
 ('Hemerográfico'),
 ('Sonoro'),
 ('Textual'),
 ('Visual');
/*!40000 ALTER TABLE `tipos_general_documentos` ENABLE KEYS */;


--
-- Definition of table `tipos_niveles`
--

DROP TABLE IF EXISTS `tipos_niveles`;
CREATE TABLE `tipos_niveles` (
  `codigo_tipo_nivel` int(11) NOT NULL,
  `tipo_nivel` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_tipo_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_niveles`
--

/*!40000 ALTER TABLE `tipos_niveles` DISABLE KEYS */;
INSERT INTO `tipos_niveles` (`codigo_tipo_nivel`,`tipo_nivel`) VALUES 
 (1,'Fondo'),
 (2,'Serie'),
 (3,'Sección'),
 (4,'Agrupaciones Documentales'),
 (5,'Documento');
/*!40000 ALTER TABLE `tipos_niveles` ENABLE KEYS */;


--
-- Definition of table `tips`
--

DROP TABLE IF EXISTS `tips`;
CREATE TABLE `tips` (
  `idtips` int(11) NOT NULL AUTO_INCREMENT,
  `tip` text,
  `area` varchar(50) DEFAULT NULL,
  `requerido` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`idtips`),
  KEY `fk_tips_areas_tips1` (`area`),
  CONSTRAINT `fk_tips_areas_tips1` FOREIGN KEY (`area`) REFERENCES `areas_tips` (`area`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tips`
--

/*!40000 ALTER TABLE `tips` DISABLE KEYS */;
INSERT INTO `tips` (`idtips`,`tip`,`area`,`requerido`) VALUES 
 (1,'Código normalizado que permite identificar el archivo o centro de referencia donde se custodia la unidad de descripción.','Instituciones',1),
 (2,'Punto de acceso normalizado que identifica de manera unívoca la institución archivística objeto de la descripción. Registra la forma autorizada del nombre de la institución archivística.','Instituciones',1),
 (3,'Registra la(s) forma(s) paralela(s) del nombre de la institución archivística que está siendo descripta.','Instituciones',0),
 (4,'Especifica la tipología de la institución archivística de acuerdo al principio de procedencia del productor.','Instituciones',1),
 (5,'Consigna la localización espacial de la institución.','Instituciones',1),
 (6,'Dato de contacto complementario de la institución. Incorporando el prefijo de localidad y país.','Instituciones',1),
 (7,'Dirección de correo electrónico de la institución archivística.','Instituciones',1),
 (8,'Consigna la URL de la página Web de la institución archivística.','Instituciones',0),
 (9,'Proporciona a los usuarios la información necesaria para contactar con la persona responsable de la institución.','Instituciones',1),
 (10,'Proporciona a los usuarios la información necesaria para contactar con la persona responsable del área archivo.','Instituciones',1),
 (11,'Proporciona un resúmen de la historia de una institución que custodia los fondos documentales.','Instituciones',1),
 (12,'Describe y/o representa la actual estructura funcional de la institución y del área archivo. Describe la forma en la que el archivo se inserta dentro del funcionamiento de la institución.','Instituciones',0),
 (13,'Informa sobre la política de ingreso de documentos desarrollada por la institución de archivo.','Instituciones',1),
 (14,'Describe la política de ingreso para las nuevas incorporaciones.','Instituciones',1),
 (15,'Aporta información sobre las instalaciones de la sede del archivo de la institución.','Instituciones',1),
 (16,'Facilita un resúmen general sobre los fondos y agrupaciones documentales custodiados en la institución archivística.','Instituciones',0),
 (17,'Facilita información relativa a los procedimientos necesarios para el acceso a los servicios del archivo.','Instituciones',1),
 (18,'Días de atención a usuarios pública para la consulta. Facilita información sobre horario de apertura y las fechas anuales de cierre de la institución de archivo. Consigna los días y horario de apertura, las vacaciones anuales y los días feriados de la institución de archivo, así como cualquier cierre que se tenga previsto.','Instituciones',1),
 (19,'Indica las fechas de cierre de la consulta a usuarios, vacaciones, etc.','Instituciones',1),
 (20,'Proporciona información relativa a los servicios de reproducción ofrecidos al público.','Instituciones',1),
 (22,'Detalla las peculiaridades de las instituciones.','Instituciones',1),
 (23,'Código normalizado que permite identificar el archivo o centro de referencia donde se custodia la unidad de descripción. ','Niveles',1),
 (24,'Código de referencia del nivel. ','Niveles',1),
 (25,'Describe el título del nivel vinculado a la unidad de descripción','Niveles',1),
 (26,'Describe la forma más conocida por la cual se conoce al nivel que contiene la unidad de descripción','Niveles',0),
 (27,'Traducción a otro idioma del título original que figura en la unidad de descripción. ','Niveles',0),
 (28,'Día, mes y año en que se asignó el número de registro a los documentos de la unidad de descripción ingresados en el archivo o centro de referencia.','Niveles',1),
 (29,'Número que identifica la custodia patrimonial administrativa de la unidad de descripción.','Niveles',1),
 (30,'Requiere el número de inventario asignado al documento previo a la aplicación del estándar normalizado.','Niveles',0),
 (31,'Describe la pertenencia del nivel al Fondo tesoro. Puede tratarse de un Fondo tesoro identificado por el Productor del Fondo, o bien, puede identificarse como Fondo tesoro aquella documentación considerada tesoro patrimonial por una acción de valoración contemporánea y/o la aplicación de una resolución oficial. Fondo tesoro: conformados por los documentos de mayor relevancia del que custodia el archivo.','Niveles',1),
 (32,'Datos que indican la ubicación espacial de los niveles y las unidades de descripción. ','Niveles',1),
 (33,'Indica en qué estará contenida la unidad de descripción, y el camino informático para un documento electrónico, se indicará la máquina, la unidad de volumen, estructura de directorios, nombre de archivo.','Niveles',0),
 (34,'Nombre de la persona o personas, órgano y órganos administrativos o entidad responsable de la producción de la documentación de la unidad de descripción. ','Niveles',1),
 (35,'Fecha del inicio de la producción de la unidad de descripción. ','Niveles',1),
 (36,'Fecha de finalización de la producción de la documentación de la unidad de descripción. ','Niveles',1),
 (37,'Sinopsis breve de los asuntos, temas o aspectos esenciales tratados en la documentación de la unidad de descripción. \r\n','Niveles',1),
 (38,'Descripción fisicotécnica del tipo de material en que se presenta la documentación de la unidad de descripción.','Niveles',1),
 (39,'Lengua utilizada en la documentación de la unidad de descripción.','Niveles',1),
 (40,'Cantidad de metros de estantería del nivel que lo componen.','Niveles',1),
 (41,'Unidades contenidas.','Niveles',1),
 (42,'Describe la historia de la institución, familia o biografía de la persona productora del fondo y de los niveles. Fechas de existencia, lugares, misiones y funciones ejercidas, cambios y características. ','Niveles',1),
 (43,'Descripción de los lugares de custodia y características de intervención técnica archivísticas aplicadas al Fondo y niveles. Asimismo las personas que intervinieron. ','Niveles',1),
 (44,'Reseña los códigos de referencia de niveles relacionados.','Niveles',0),
 (45,'Describe la relación entre los niveles y alguna institución de las que utilizan el sistema.','Niveles',0),
 (46,'Describe el vínculo de los niveles con instituciones que no usan la aplicación.','Niveles',0),
 (47,'Se indicará la estructura interna de organización, el sistema de clasificación y/o el orden de la documentación de la unidad de descripción.','Niveles',0),
 (48,'Indica la forma de ingreso de la documentación a la institución. ','Niveles',0),
 (49,'Nombre de la persona o entidad que donó, vendió, legó, prestó, etc. El documento al organismo. ','Niveles',0),
 (50,'Fecha de ingreso en la que el documento pasó a ser patrimonio de la institución. ','Niveles',1),
 (51,'Monto pagado por la adquisición o compra del documento. Incluir tipo de unidad monetaria.','Niveles',0),
 (52,'Se refiere a las normas administrativas por las que ingresaron los documentos a los Fondos de la Institución.','Niveles',0),
 (53,'Se refiere a los números administrativos de los documentos ingresados.','Niveles',0),
 (54,'Derechos y restricciones impuestas sobre el documento por la persona o grupo que lo entrega a la institución. ','Niveles',1),
 (55,'Especifica la titularidad de la explotación de los documentos (reproducción, uso del documento en cualquier tipo de acción o soporte). Para el caso de la DNPM consigna link de la Secretaría de Cultura de la Nación. Pudiéndose tratar de una titularidad que restringa la reproducción, explotación y exhibición de los documentos.','Niveles',1),
 (56,'Es título. Información acerca de expertizajes realizados por investigadores o peritos de los documentos que se ingrsaron.','Niveles',0),
 (57,'Facilita información relativa a procedimientos necesarios para el acceso a los servicios del archivo.','Niveles',0),
 (58,'Facilita información relativa a requisitos necesarios para el acceso a los servicios del archivo.','Niveles',0),
 (59,'Facilita información relativa a condiciones necesarias para el acceso a los servicios del archivo. ','Niveles',0),
 (60,'Describe las publicaciones para la consulta del archivo como así también todas aquellas que realizadas por la institución presentan los documentos de los Fondos custodiados. ','Niveles',1),
 (61,'Indica nombre de la institución que ha otorgado los fondos para el tratamiento de los documentos (título del proyecto, período), el monto y describe el área de aplicación (si es archivística y de conservación).','Niveles',1),
 (62,'Se refiere a las normas administrativas por las que se dieron de baja los documentos de los Fondos de la Institución.','Niveles',0),
 (63,'Se refiere a los números administrativos de los documentos dados de baja.','Niveles',0),
 (64,'Descripción del motivo de baja. \r\n','Niveles',0),
 (65,'Fecha en la que se realizó la baja del documento.','Niveles',0),
 (66,'Detalla las peculiaridades de los documentos.','Niveles',1),
 (67,'Notas específicas del Archivero.','Niveles',1),
 (68,'Nombres de las fuentes de las que proviene la información.','Niveles',1),
 (69,'Fecha en la que se realizó la modificación de la descripción.','Niveles',1),
 (70,'Código normalizado que permite identificar el archivo o centro de referencia donde se custodia la unidad de descripción.','Diplomaticos',1),
 (71,'Código normalizado que permite identificar al documento.','Diplomaticos',1),
 (72,'Indica si es un documento textual, visual, sonoro, audiovisual. ','Diplomaticos',1),
 (73,'Describe el título del documento.','Diplomaticos',1),
 (74,'Describe la forma más conocida por la cual se conoce al nivel que contiene la unidad de descripción.','Diplomaticos',0),
 (75,'Traducción a otro idioma del título original del documento.','Diplomaticos',0),
 (76,'Día, mes y año en que se asignó el número de registro a los documentos de la unidad de descripción ingresados en el archivo o centro de referencia.','Diplomaticos',1),
 (77,'Corresponde al número o código vigente, asignado por su institución propietaria, que identifica a un objeto determinado.','Diplomaticos',1),
 (78,'Refiere al número de inventario asignado al documento previo a la aplicación del estándar normalizado.','Diplomaticos',0),
 (79,'Palabra o término  que delimita la naturaleza estandarización de la documentación, de la unidad de descripción. ','Diplomaticos',1),
 (80,'Palabra o término que, dentro de cada tipo general, delimita la naturaleza específica de la documentación de la unidad de descripción. ','Diplomaticos',1),
 (81,'Palabra o término de acuerdo a  las características de originalidad en que se encuentra la unidad de descripción. ','Diplomaticos',1),
 (82,'Datos que indican la ubicación espacial de los niveles y las unidades de descripción. ','Diplomaticos',1),
 (83,'Contenedores de la unidad de descripción. Nombres que indican el camino informático para un documento electrónico, se indicará máquina, unidad de volumen, estructura de directorios, nombre del archivo.','Diplomaticos',0),
 (84,'Objetos conexos al objeto registrado y la descripción de la relación entre ellos.','Diplomaticos',0),
 (85,'Número asignado por la base de registro inventario bibliográfico.','Diplomaticos',0),
 (86,'Código normalizado que permite identificar al documento. ','Conservación',1),
 (87,'Nombre que identifica al documento.','Conservación',1),
 (88,'Indica si es un documento textual, visual, sonoro, audiovisual.','Conservación',1),
 (89,'Fecha en la que comienza el tratamiento realmente del documento. El formato es (dd/mm/aaaa)','Conservación',1),
 (90,'Fecha en la que se finaliza el tratamiento del documento.','Conservación',0),
 (91,'Estado del documento','Conservación',1),
 (92,'Descripción de la situación en la que se encuentra el documento previo a la conservación.','Conservación',1),
 (93,'Indica clase de papel, color actual, características superficieles, método de fabricación, marcas de agua, etc.','Conservación',1),
 (94,'Describe Emulsión, tinta al agua, tinta ferroálica, etc.','Conservación',1),
 (95,'Descripción del recubrimiento que posee el documento.','Conservación',1),
 (96,'Describe si tiene cintas, sujeciones, sellos, forrados, respaldo, etiquetas, rótulos.','Conservación',1),
 (97,'Se indica el sobre, envoltorio, caja, carpeta, estante abierto, cajón, etc.','Conservación',0),
 (98,'Se indica si tenía sellos y lacre rotos, sellos y lacres rotos y desaparecidos, abrasiones, cortes, rasgaduras, punzados, daño por insectos, manchas, acreciones, soporte perforado en concidencia con la escritura, soporte acidificado, decoloración, foxing, ataque de hongos, manchas, presencia de ganchos/grapas/clips metálicos, inaudibilidad.','Conservación',1),
 (99,'Descripción de los tratamientos recibidos.','Conservación',0),
 (100,'Detalle del diagnóstico relevado, antes, durante y post tratamiento.','Conservación',1),
 (101,'Imagen del documento previo al tratamiento en curso.','Conservación',0),
 (102,'Fecha en que se tomó la imagen previa al tratamiento.','Conservación',0),
 (103,'Imagen del documento posterior al tratamiento.','Conservación',0),
 (104,'Fecha de la imagen posterior al tratamiento.','Conservación',0),
 (105,'Se refiere a particularidades del momento de la toma fotográfica.','Conservación',0),
 (106,'Indica que realizar para el tratamiento, por ejemplo retirar grapas, reemplazar envoltorio, reparar roturas, etc.','Conservación',0),
 (107,'Se describe todo lo relevado durante el tratamiento.','Conservación',0),
 (108,'Descripción acerca del resultado obtenido durante el tratamiento y luego de la finalización del mismo.','Conservación',0),
 (109,'Esto indicará si el documento podrá exhibirse, de lo contrario no podrá darse en préstamo ni participar de exposiciones.','Conservación',1),
 (110,'Conservadores a cargo de la tarea de conservación del documento.','Conservación',NULL),
 (111,'Se refiere a particularidades del momento de la toma fotográfica. ','Conservación',0),
 (112,'Se indica el sobre, envoltorio, caja, carpeta, estante abierto, cajón, etc. Esto es previo a la conservación.','Conservación',1);
/*!40000 ALTER TABLE `tips` ENABLE KEYS */;


--
-- Definition of table `tradiciones_documentales`
--

DROP TABLE IF EXISTS `tradiciones_documentales`;
CREATE TABLE `tradiciones_documentales` (
  `tradicion_documental` varchar(50) NOT NULL,
  PRIMARY KEY (`tradicion_documental`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tradiciones_documentales`
--

/*!40000 ALTER TABLE `tradiciones_documentales` DISABLE KEYS */;
INSERT INTO `tradiciones_documentales` (`tradicion_documental`) VALUES 
 ('Copia Digital'),
 ('Copia Fotográfica'),
 ('Copia Manuscrita'),
 ('Original');
/*!40000 ALTER TABLE `tradiciones_documentales` ENABLE KEYS */;


--
-- Definition of table `transportes`
--

DROP TABLE IF EXISTS `transportes`;
CREATE TABLE `transportes` (
  `transporte` varchar(50) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_postal` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `contacto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`transporte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transportes`
--

/*!40000 ALTER TABLE `transportes` DISABLE KEYS */;
INSERT INTO `transportes` (`transporte`,`nombre`,`direccion`,`codigo_postal`,`pais`,`telefono`,`mail`,`contacto`) VALUES 
 ('AWER0034','Transporte Avellaneda','Marruecos 1234','1034','argentina','4523 2569','tranaveell@hotmail.com','marcos pedraza');
/*!40000 ALTER TABLE `transportes` ENABLE KEYS */;


--
-- Definition of table `tratamientos_anteriores_evidentes`
--

DROP TABLE IF EXISTS `tratamientos_anteriores_evidentes`;
CREATE TABLE `tratamientos_anteriores_evidentes` (
  `tratamiento_anterior` varchar(100) NOT NULL,
  PRIMARY KEY (`tratamiento_anterior`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tratamientos_anteriores_evidentes`
--

/*!40000 ALTER TABLE `tratamientos_anteriores_evidentes` DISABLE KEYS */;
INSERT INTO `tratamientos_anteriores_evidentes` (`tratamiento_anterior`) VALUES 
 ('tratamiento 1'),
 ('tratamiento 2'),
 ('tratamiento 3');
/*!40000 ALTER TABLE `tratamientos_anteriores_evidentes` ENABLE KEYS */;


--
-- Definition of table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `pass` varchar(33) NOT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `activo` bit(1) DEFAULT b'1',
  `fecha_ultimo_acceso` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuarios`
--

/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`idusuario`,`usuario`,`pass`,`fecha_alta`,`nombre`,`apellido`,`email`,`telefono`,`activo`,`fecha_ultimo_acceso`,`fecha_baja`) VALUES 
 (1,'camilo','camilo1',NULL,'camilo','schimf','camilo@juanrodo.com.ar','4564565',0x01,NULL,NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;


--
-- Definition of table `versiones`
--

DROP TABLE IF EXISTS `versiones`;
CREATE TABLE `versiones` (
  `version` varchar(50) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `versiones`
--

/*!40000 ALTER TABLE `versiones` DISABLE KEYS */;
INSERT INTO `versiones` (`version`) VALUES 
 ('version 1'),
 ('version 2'),
 ('version 3'),
 ('version 4');
/*!40000 ALTER TABLE `versiones` ENABLE KEYS */;


--
-- Definition of view `vis_estados_niveles`
--

DROP TABLE IF EXISTS `vis_estados_niveles`;
DROP VIEW IF EXISTS `vis_estados_niveles`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vis_estados_niveles` AS (select `instituciones`.`codigo_identificacion` AS `codigo_referencia`,'0' AS `tipo`,`instituciones`.`formas_conocidas_nombre` AS `nombre`,(select `instituciones_estados`.`estado` AS `estado` from `instituciones_estados` where (`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`) order by `instituciones_estados`.`fecha` desc limit 1) AS `estado`,max(`instituciones_estados`.`fecha`) AS `fecha`,'0' AS `cod_ref_sup` from (`instituciones` join `instituciones_estados` on((`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`))) where (0 = 0) group by `instituciones`.`codigo_identificacion`) union (select `niveles`.`codigo_referencia` AS `codigo_referencia`,`niveles`.`tipo_nivel` AS `tipo`,`niveles`.`titulo_original` AS `nombre`,(select `niveles_estados`.`estado` AS `estado` from `niveles_estados` where (`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`) order by `niveles_estados`.`fecha` desc limit 1) AS `estado`,max(`niveles_estados`.`fecha`) AS `fecha`,`niveles`.`cod_ref_sup` AS `cod_ref_sup` from (`niveles` join `niveles_estados` on((`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`))) where (0 = 0) group by `niveles`.`codigo_referencia`) union (select `documentos`.`codigo_referencia` AS `codigo_referencia`,`documentos`.`tipo_diplomatico` AS `tipo`,`documentos`.`titulo_original` AS `nombre`,(select `documentos_estados`.`estado` AS `estado` from `documentos_estados` where (`documentos_estados`.`codigo_referencia` = `documentos`.`codigo_referencia`) order by `documentos_estados`.`fecha` desc limit 1) AS `estado`,max(`documentos_estados`.`fecha`) AS `fecha`,`documentos`.`cod_ref_sup` AS `cod_ref_sup` from (`documentos` join `documentos_estados` on((`documentos`.`codigo_referencia` = `documentos_estados`.`codigo_referencia`))) where (0 = 0) group by `documentos`.`codigo_referencia`) order by `codigo_referencia`;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
