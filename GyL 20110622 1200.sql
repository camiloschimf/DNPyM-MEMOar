-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12


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
  `cod_ref_sup` varchar(255),
  `codigo_institucion` varchar(50),
  `estado_cod_ref_sup` varchar(50),
  `tv` varbinary(11),
  `tipo_diplomatico` varbinary(11),
  `tipo_nivel` varbinary(11),
  `situacion` varchar(50),
  `fecha_ultima_modificacion` datetime
);

--
-- Temporary table structure for view `vis_niveles`
--
DROP TABLE IF EXISTS `vis_niveles`;
DROP VIEW IF EXISTS `vis_niveles`;
CREATE TABLE `vis_niveles` (
  `codigo_referencia` varchar(255),
  `tipo` varbinary(11),
  `nombre` varchar(100),
  `estado` varchar(50),
  `fecha` timestamp,
  `cod_ref_sup` varchar(255),
  `codigo_institucion` varchar(50)
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agregados`
--

/*!40000 ALTER TABLE `agregados` DISABLE KEYS */;
INSERT INTO `agregados` (`codigo_agregado`,`agregado`,`tipo_diplomatico`) VALUES 
 (1,'Cintas',10),
 (3,'forrados',11),
 (2,'sellos',11);
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
  CONSTRAINT `fk_archivos_digitales_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_archivos_digitales_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `archivos_digitales`
--

/*!40000 ALTER TABLE `archivos_digitales` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `area_de_notas_instituciones`
--

/*!40000 ALTER TABLE `area_de_notas_instituciones` DISABLE KEYS */;
INSERT INTO `area_de_notas_instituciones` (`codigo_notas`,`codigo_institucion`,`nota_descripcion`,`fecha_descripcion`,`fecha_modificacion`) VALUES 
 (19,'AR/PN/SC/DNPM/PSJ','\r\n\r\n<p>Notas de Descripción.</p>','2011-05-23 00:00:00','2011-06-24 00:00:00'),
 (20,'AR/PN/SC/DNPM/MCASN','Nota del Archivero: <span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-ansi-language: ES; mso-fareast-font-family: \'Times New Roman\'; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">Licenciada en Museología: Elsa Victoria González.<br />\r\n	<br />\r\n	<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-ansi-language: ES; mso-fareast-font-family: \'Times New Roman\'; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">Reglas o Normas: <span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Normas Isad(g)<br />\r\n			\r\n			<o:p><br />\r\n				<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-ansi-language: ES; mso-fareast-font-family: \'Times New Roman\'; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">Fecha(s) de la(s) descripción(es): <span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Inicio julio 2008.</span></span></o:p></span></span></span>','2011-06-03 00:00:00','2011-06-13 00:00:00'),
 (21,'AR/PN/SC/DNPM/CNSDR','No hay datos..','2011-06-15 00:00:00','2011-06-21 00:00:00'),
 (22,'AR/PN/SC/DNPM/CNB',NULL,'2011-06-22 00:00:00',NULL);
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
 ('Exposición'),
 ('Instituciones'),
 ('Niveles'),
 ('No Localizado'),
 ('Prestamo'),
 ('Robo');
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
 ('Atahualpa Yupanqui'),
 ('Fontana Lucio'),
 ('Harvard University'),
 ('Lumiere'),
 ('Oficina de conferencias del Rectorado de la Universidad de Buenos Aires');
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
 ('Con esquineros'),
 ('Otros'),
 ('Pegada');
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
 ('Blanco y Negro'),
 ('Cámara analógica'),
 ('Cámara digital'),
 ('Cámara Polaroid'),
 ('Color'),
 ('Con Flash'),
 ('Resolución'),
 ('Sepia'),
 ('Sin Flash'),
 ('Tamaño de la foto');
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
 ('Microfilm'),
 ('No posee'),
 ('URL');
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
 ('Blanco y Negro'),
 ('Color'),
 ('Sepia');
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
 ('Buenos Aires');
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
 ('Educación'),
 ('Gobierno');
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
 ('Augusto Belin. María Celia García Rojas Belin Sarmiento');
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `direcciones_instituciones`
--

/*!40000 ALTER TABLE `direcciones_instituciones` DISABLE KEYS */;
INSERT INTO `direcciones_instituciones` (`codigo_direcciones`,`codigo_institucion`,`calle`,`numero`,`codigo_postal`,`provincia`,`casilla_correo`,`ciudad`,`pais`,`telefonos`,`fax`,`tipo_direccion`) VALUES 
 (1,'AR/PN/SC/DNPM/PSJ','Ruta Provincial N°39','Kilómetro 128 (Desvío al norte de 3 Km.)','3260','Entre Ríos','psjmuseourquiza@hotmail.com','Departamento Uruguay','República Argentina','(3442) 432620','No se conoce',NULL),
 (2,'AR/PN/SC/DNPM/CNS','Sarmiento','121 – sur – Capital',NULL,'San Juan','casnatsarm@sinectis.com.ar',NULL,'República Argentina','0264-4224603','No se conoce',NULL),
 (3,'AR/PN/SC/DNPM/MCASN','Calle De la Nación','139 / 143','B2900AAC','Buenos Aires','museodelacuerdo@intercom.com.ar','San Nicolás de los Arroyos ','República Argentina','(03461) 428980',NULL,NULL),
 (4,'AR/PN/SC/DNPM/CNB','Riobamba','985','C1116ABB','Buenos Aires','casa@bicentenario.gov.ar','de Buenos Aires','República Argentina','(+54) +11 4813.0301 / 0679 ',NULL,NULL),
 (5,'AR/PN/SC/DNPM/MJN','Pedro Oñate','S/Nº','5220','Córdoba','mjn-jm@coop5.com.ar','Jesús María','República Argentina','54 (3525) 420-126',NULL,NULL),
 (6,'AR/PN/SC/DNPM/INET','Av. Córdoba','1199','1055','Buenos Aires','estudiosdeteatro@inet.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','54+11 4815-8817/8883',NULL,NULL),
 (7,'AR/PN/SC/DNPM/INMCV','México','564','C1097AAL','Buenos Aires','inmuvega@cultura.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4361-6520/6013','(54 11) 4361-6520/6013',NULL),
 (8,'AR/PN/SC/DNPM/INJDP','Austria','2593','C1425EGQ','Buenos Aires','instituto@jdperon.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','4802-8010/ 4802-4596 / 4801-3562 / 4801-9404',NULL,NULL),
 (9,'AR/PN/SC/DNPM/MCY','O\'Higgins','2390','1428','Buenos Aires','info@museocasadeyrurtia.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4781-0385',NULL,NULL),
 (10,'AR/PN/SC/DNPM/ME','Lafinur','2988','C1425FAB','Buenos Aires','info@museoevita.org','Ciudad Autónoma de Buenos Aires','República Argentina','(011) 4807-0630',NULL,NULL),
 (11,'AR/PN/SC/DNPM/MHN','Defensa','1600','C1143AAH','Buenos Aires','informes@mhn.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4307-1182',NULL,NULL),
 (12,'AR/PN/SC/DNPM/MHNCYRM','Bolivar','65','1066','Buenos Aires','cabildomuseo_nac@cultura.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(+54 - 11) 4342-6729 y 4334-1782',NULL,NULL),
 (13,'AR/PN/SC/DNPM/MNAO','Av. del Libertador ','1902','1425','Buenos Aires','mnao@mnao.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4801-5988',NULL,NULL),
 (14,'AR/PN/SC/DNPM/MNG','Agüero','2502','C1425EID','Buenos Aires','museodelgrabado@yahoo.com.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(011) 4802-3295',NULL,NULL),
 (15,'AR/PN/SC/DNPM/MRPJAT','Rivadavia','352','4624','Jujuy','museoterry@tilcanet.com.ar','Tilcara','República Argentina','54 (388) 495-5005',NULL,NULL),
 (16,'AR/PN/SC/DNPM/MR','Vicente López ','2220','C1128ACJ','Buenos Aires','museoroca@hotmail.com','Ciudad Autónoma de Buenos Aires','República Argentina','(54-11) 4803-2798','(54-11) 4803-2798',NULL),
 (17,'AR/PN/SC/DNPM/MNBA','Av. Del Libertador','1473','C1425AAA','Buenos Aires','prensa@mnba.org.ar / edumnba@yahoo.com.ar / biblio','Ciudad Autónoma de Buenos Aires','República Argentina','5288-9900','5288-9999',NULL),
 (18,'AR/PN/SC/DNPM/MNAD','Av.del Libertador','1902','C1425AAS','Buenos Aires','museo@mnad.org','Ciudad Autónoma de Buenos Aires','República Argentina','(54-11) 4801-8248 / 4802-6606 / 4806-8306','(54-11) 4801-8248 / 4802-6606 / 4806-8306',NULL),
 (19,'AR/PN/SC/DNPM/MM','San Martin','336','1004','Buenos Aires',NULL,'Ciudad Autónoma de Buenos Aires','República Argentina','54 11 4394-8240/7659','54 11 4394-8240/7659',NULL),
 (20,'AR/PN/SC/DNPM/MHS','Juramento','2180','C1428DNJ','Buenos Aires','info@museosarmiento.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(011) 4783 - 7555 // (011) 4781 - 2989','No posee',NULL),
 (21,'AR/PN/SC/DNPM/MHDN','Caseros','549','A4400DMK ','Salta','cabildosalta@uolsinectis.com.ar','c','República Argentina','0387 - 4215340','0387 - 4215340',NULL),
 (22,'AR/PN/SC/DNPM/MCHI','Calle Congreso','151','cp','San Miguel de Tucumán','museocasahistorica@arnetbiz.com.ar',NULL,'República Argentina','+54 - 381 - 4310826 / 4221335. ',NULL,NULL),
 (23,'AR/PN/SC/DNPM/MEJAGYCVL','Av. Padre Domingo Viera ','41','5186','Córdoba','c','Alta Gracia','República Argentina','03547-421303','03547-421303',NULL),
 (24,'AR/PN/SC/DNPM/INAPL','3 de Febrero','1378','C1426BJN','Buenos Aires',NULL,'Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4782-7251 / 4783-6554','(54 11) 4782-7251 / 4783-6554',NULL),
 (25,'AR/PN/SC/DNPM/CNML','Perú','272','C1067AAF','Buenos Aires',NULL,'Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4343-3260 / 4342-6973','(011) 4343-3260',NULL),
 (26,'AR/PN/SC/DNPM/INB','Av. Almirante Brown','401','C1155AEB','Buenos Aires','info@inb.gov.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(5411) 4362-1225 / 4307-9925',NULL,NULL),
 (27,'AR/PN/SC/DNPM/INS','Plaza Grand Bourg',NULL,'1425','Buenos Aires','insadministracion@arnetbiz.com.ar','Ciudad Autónoma de Buenos Aires','República Argentina','(54 11) 4802-3311 / 4801-0848',NULL,NULL),
 (28,'AA','cale','numero','codigo','prov','casilla','ciudad','pais','tel','fax',NULL),
 (29,'AR/PN/SC/DNPM/CNSDR','calle','num','1254','prov','cf@jhadfugd','ciuada','pais','2553213231','4441121242',NULL),
 (30,'AR/PN/SC/DNPM/Prueba','Pueyrredón','1234','1426',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
  CONSTRAINT `fk_documentos_tipos_especificos_documentos1` FOREIGN KEY (`tipo_especifico_documento`) REFERENCES `tipos_especificos_documentos` (`tipo_especifico_documento`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tradiciones_documentales1` FOREIGN KEY (`tradicion_documental`) REFERENCES `tradiciones_documentales` (`tradicion_documental`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_sistemas_organizaciones1` FOREIGN KEY (`sistema_organizacion`) REFERENCES `sistemas_organizaciones` (`sistema_organizacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_registro_autoridad1` FOREIGN KEY (`nombre_productor`) REFERENCES `registro_autoridad` (`nombre_productor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_versiones1` FOREIGN KEY (`version`) REFERENCES `versiones` (`version`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_generos1` FOREIGN KEY (`genero`) REFERENCES `generos` (`genero`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_soportes1` FOREIGN KEY (`soporte`) REFERENCES `soportes` (`soporte`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_sonidos1` FOREIGN KEY (`sonido`) REFERENCES `sonidos` (`sonido`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_requisitos_ejecucion1` FOREIGN KEY (`requisito_ejecucion`) REFERENCES `requisitos_ejecucion` (`requisito_ejecucion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tipos_accesos1` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipos_accesos` (`tipo_acceso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_acceso_documentacion1` FOREIGN KEY (`acceso_documentacion`) REFERENCES `acceso_documentacion` (`acceso_documentacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_norma_legal2` FOREIGN KEY (`normativa_legal_baja`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_digitales1` FOREIGN KEY (`tecnica_digital`) REFERENCES `tecnicas_digitales` (`tecnica_digital`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_emulsiones1` FOREIGN KEY (`emulsion`) REFERENCES `emulsiones` (`emulsion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_fomas_presentacion_unidad1` FOREIGN KEY (`forma_presentacion_unidad`) REFERENCES `fomas_presentacion_unidad` (`forma`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_caracteristicas_montaje1` FOREIGN KEY (`caracteristica_montaje`) REFERENCES `caracteristicas_montaje` (`caracteristica_montaje`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_niveles1` FOREIGN KEY (`cod_ref_sup`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_situaciones1` FOREIGN KEY (`situacion`) REFERENCES `situaciones` (`situacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_cromias1` FOREIGN KEY (`cromia`) REFERENCES `cromias` (`cromia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_formas_ingreso1` FOREIGN KEY (`forma_ingreso`) REFERENCES `formas_ingreso` (`forma_ingreso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_norma_legal1` FOREIGN KEY (`norma_legal_ingreso`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_requisitos_accesos1` FOREIGN KEY (`requisitos_acceso`) REFERENCES `requisitos_accesos` (`requisito_acceso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_servicios_reproduccion1` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_fotograficas1` FOREIGN KEY (`tecnica_fotografica`) REFERENCES `tecnicas_fotograficas` (`tecnica_fotografica`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tecnicas_visuales1` FOREIGN KEY (`tecnica_visual`) REFERENCES `tecnicas_visuales` (`tecnica_visual`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_tipos_general_documentos1` FOREIGN KEY (`tipo_general_documento`) REFERENCES `tipos_general_documentos` (`tipo_general_documento`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos`
--

/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
INSERT INTO `documentos` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`numero_inventario_unidad_documental`,`cod_ref_sup`,`tipo_general_documento`,`tipo_especifico_documento`,`tradicion_documental`,`numero_registro_sur`,`numero_registro_bibliografico`,`sistema_organizacion`,`autor`,`nombre_productor`,`fecha_edicion_anio_editorial`,`fecha_accion_representada`,`version`,`genero`,`signos_especiales`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`soporte`,`duracion_metraje`,`cromia`,`tecnica_fotografica`,`tecnica_visual`,`tecnica_digital`,`emulsion`,`sonido`,`integridad`,`forma_presentacion_unidad`,`cantidad_fojas_album`,`caracteristica_montaje`,`requisito_ejecucion`,`unidades`,`cantidad_envases_unidad_documental`,`coleccion`,`evento`,`manifestacion`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`tipo_acceso`,`requisitos_acceso`,`acceso_documentacion`,`servicio_reproduccion`,`publicaciones_instrumentos_accesos`,`subsidios`,`normativa_legal_baja`,`numero_norma_legal`,`motivo_baja`,`fecha_baja`,`tipo_diplomatico`,`situacion`,`fecha_ultimo_relevamiento_visu`,`tv`,`na`,`estado`,`fecha_creacion`,`fecha_ultima_modificacion`,`usuario`,`fecha_ultimo_tratamiento`,`restringido_exhibicion`) VALUES 
 ('AR/PN/SC/DNPM/CNS/C/DOCAUVI02','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNS/C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Local',NULL,2,1,1,NULL,'2011-06-12 19:39:29','camilo',NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','AR/PN/SC/DNPM/CNS','Documento Sonoro',NULL,NULL,'2010-01-01 00:00:00',NULL,'01','AR/PN/SC/DNPM/CNS/C','Sonoro','Film','Copia auténtica',NULL,NULL,NULL,NULL,'Casa Natal de Sarmiento. Monumento Histórico','2011','09-06-2011',NULL,NULL,NULL,'2000-01-01 00:00:00','2000-01-01 00:00:00','.','DVD','00:59:00',NULL,NULL,NULL,NULL,NULL,NULL,'Intacto',NULL,NULL,NULL,NULL,200,NULL,NULL,NULL,NULL,'Hallazgo','procedencia','09-06-2011','100.000 pesos argentinos','Resolución','01/02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'P',NULL,NULL,NULL,NULL,NULL,10,'Conservación','2009-01-01 00:00:00',2,5,5,NULL,'2011-06-11 16:55:59','camilo',NULL,1),
 ('AR/PN/SC/DNPM/CNS/C/DOCTEX','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNS/C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'Local',NULL,2,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','AR/PN/SC/DNPM/CNS','Casa Natal Sarmiento',NULL,NULL,'2010-01-01 00:00:00',NULL,'123','AR/PN/SC/DNPM/CNS/C','Visual','Videos','Copia digital',NULL,NULL,'Cronológico','Atahualpa Yupanqui','Casa Natal de Sarmiento. Monumento Histórico',NULL,'Agosto 2004',NULL,NULL,': Dibujo a mano alzada en tinta sobre lateral izquierdo, autorretrato y retrato de Atahualpa Yupanqui, presumiblemente de autoría de Ricardo Rojas.','1911-01-01 00:00:00','2008-01-01 00:00:00','El acervo contiene documentación referida a: adquisición del edificio, declaración Monumento Histórico, trabajos de refacciones y reconstrucción parcial desde l911 a la fecha, instalación de servicios que se fueron logrando a lo largo del tiempo, como: en','DVD','01:00:00 hrora','Color',NULL,NULL,NULL,NULL,'Sonoro',NULL,NULL,NULL,NULL,NULL,100,NULL,NULL,NULL,NULL,'Donación','Producción y donaciones ','2010/02/25','15.000 pesos argentinos','Decreto','00000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Acceso restringido. Su consulta está permitida según el Reglamento General de este Archivo, al personal del mismo, bajo recibo y bajo autorización de la Dirección.',NULL,NULL,NULL,NULL,NULL,8,'Local','2010-01-01 00:00:00',2,2,5,NULL,'2011-06-23 12:28:05','camilo',NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/DOC01','AR/PN/SC/DNPM/CNSDR','TIKANDVPADS','DFJWR','RGJKWTR,M','2011-01-01 00:00:00','1254','125487','AR/PN/SC/DNPM/CNSDR/fdo01','Audiovisual','Grabados','Copia electrónica',NULL,NULL,NULL,NULL,'Productor',NULL,'RTJHR',NULL,NULL,'RGHRATH','2009-01-01 00:00:00','2011-01-01 00:00:00','SFHR','Canson','AJMAR','Blanco y Negro','Ambrotipo','Grabado en relieve - Fototipía','No especificado','Colodión','Mixto','RYMJARYJ','Álbum encuadernado cosido',0,'Con esquineros',NULL,0,NULL,NULL,NULL,NULL,'Compra','ARTHTRJ','RHTRJHR','RTJRQJ','Resolución','RTJHRT',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'RJQRTHRTHRTHRTH',NULL,NULL,NULL,NULL,NULL,8,'Local','2009-01-01 00:00:00',2,5,5,NULL,'2011-06-15 16:18:46','camilo',NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/auvisu','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/son','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/text','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/visu','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,9,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/sono','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'Local',NULL,8,NULL,1,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/visu','AR/PN/SC/DNPM/CNSDR','Prueba',NULL,NULL,'0000-01-01 00:00:00',NULL,'12563','AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc','Visual','Film','Copia auténtica',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,8,2,2,NULL,'2011-06-17 17:17:50','camilo',NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/Visu','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'Local',NULL,7,NULL,1,NULL,NULL,NULL,NULL,NULL);
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','No hay datos...',NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','<span style=\"LINE-HEIGHT: 115%; FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES-AR; mso-fareast-language: ES-AR; mso-bidi-language: AR-SA\" lang=\"ES-AR\">Anteriormente este sub-fondo era conocido como: Carpeta de la Casa</span>','<span style=\"LINE-HEIGHT: 115%; FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES-AR; mso-fareast-language: ES-AR; mso-bidi-language: AR-SA\" lang=\"ES-AR\">Oviedo, Margarita Esther&nbsp;</span>',NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/DOC01','RTHRJ','RJRYW','RTJRJRR5','2010-01-01 00:00:00',NULL);
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
  CONSTRAINT `fk_documentos_estados_estados1` FOREIGN KEY (`estado`) REFERENCES `estados` (`estado`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_estados_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_documentos_estados_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documentos_estados`
--

/*!40000 ALTER TABLE `documentos_estados` DISABLE KEYS */;
INSERT INTO `documentos_estados` (`codigo_referencia`,`estado`,`motivo`,`fecha`,`usuario`) VALUES 
 ('AR/PN/SC/DNPM/CNS/C/DOCAUVI02','Inicio',NULL,'2011-05-27 06:08:32','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCAUVI02','Pendiente',NULL,'2011-06-12 19:39:29','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Completo',NULL,'2011-06-21 15:33:55','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Inicio',NULL,'2011-05-27 06:12:20','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Pendiente',NULL,'2011-06-08 14:30:32','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCTEX','Inicio',NULL,'2011-05-27 06:13:42','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Completo',NULL,'2011-05-27 06:04:42','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Inicio',NULL,'2011-05-26 02:59:05','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Pendiente',NULL,'2011-05-27 03:51:54','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/auvisu','Inicio',NULL,'2011-06-17 13:56:20','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/son','Inicio',NULL,'2011-06-17 13:56:29','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/text','Inicio',NULL,'2011-06-17 13:56:39','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp/visu','Inicio',NULL,'2011-06-17 13:56:09','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/sono','Inicio',NULL,'2011-06-17 13:57:04','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/visu','Inicio',NULL,'2011-06-17 13:56:51','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/visu','Pendiente',NULL,'2011-06-17 17:17:45','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/Visu','Inicio',NULL,'2011-06-17 13:57:17','camilo');
/*!40000 ALTER TABLE `documentos_estados` ENABLE KEYS */;


--
-- Definition of table `documentos_no_localizados`
--

DROP TABLE IF EXISTS `documentos_no_localizados`;
CREATE TABLE `documentos_no_localizados` (
  `codigo_referencia` varchar(255) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `nro_nota` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha_recuperacion` datetime DEFAULT NULL,
  `full` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`codigo_referencia`,`fecha_inicio`),
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
  `fecha_inicio` datetime NOT NULL,
  `forma` varchar(50) DEFAULT NULL,
  `institucion_destinataria` varchar(50) DEFAULT NULL,
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
  `full` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`codigo_referencia`,`fecha_inicio`),
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
  `fecha_sustraido` datetime NOT NULL,
  `fecha_resolucion_apertura_sumario` datetime DEFAULT NULL,
  `fecha_resolucion_cierre_sumario` datetime DEFAULT NULL,
  `denunciante` varchar(100) DEFAULT NULL,
  `nro_causa_judicial` varchar(100) DEFAULT NULL,
  `nro_juzgado` varchar(100) DEFAULT NULL,
  `tipo_tramite_administrativo` varchar(100) DEFAULT NULL,
  `nro_sumario_administrativo` varchar(100) DEFAULT NULL,
  `fallo` text,
  `fecha_recuperacion` datetime DEFAULT NULL,
  `object_id_interpol` varchar(50) DEFAULT NULL,
  `fecha_registro_interpol` datetime DEFAULT NULL,
  `full` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`codigo_referencia`,`fecha_sustraido`),
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
 ('Museo Casa de Yrurtia','Sede Central','O\'Higgins 2390, Ciudad Autónoma de Buenos Aires'),
 ('Museo Casa Natal de Sarmiento','Sede Central','Sarmiento 21 sur, San Juan'),
 ('Museo Histórico Nacional','Sede Central','Defensa 1600, Ciudad Autónoma de Buenos Aires'),
 ('Museo Histórico Nacional del Cabildo y de la Revolución de Mayo','Sede Central','Bolívar 65, Buenos Aires'),
 ('Museo Mitre','Sede Central','San Martín 336, Buenos Aires'),
 ('Museo Nacional Estancia Jesuítica de Alta Gracia y Casa del Virrey Liniers','Sede Central','Av. Padre Domingo Viera 41 esq. Paseo de la Estancia, Córdoba'),
 ('Museo Palacio San José','Sede Central','Ruta Provincial Nro 39 Kilómetro 128, Entre Ríos'),
 ('Museo Regional de Pintura José A. Terry','Sede Central','Rivadavia 352, Tilcara, Jujuy'),
 ('Museo Regional de Pintura José Antonio Terry','Sede Central','Rivadavia 459, Tilcara, Jujuy'),
 ('Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','Sede Central','De la Nación 139, San Nicolás de los Arroyos, Buenos Aires');
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
 ('Albúmina'),
 ('Colodión'),
 ('Gelatina');
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
  CONSTRAINT `fk_envases_materiales1` FOREIGN KEY (`material`) REFERENCES `materiales` (`material`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_envases_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `envases`
--

/*!40000 ALTER TABLE `envases` DISABLE KEYS */;
INSERT INTO `envases` (`idenvases`,`material`,`dimension`,`autor`) VALUES 
 (1,'Canson','9 x 18 cm','Atahualpa Yupanqui'),
 (2,'Papel','20 x 20 cm','Harvard University'),
 (3,'Papel vegetal','1 x 1 m','Lumiere'),
 (5,'DVD','20X29X12 Cm.','Oficina de conferencias del Rectorado de la Universidad de Buenos Aires'),
 (6,'Cartón','12 x 35 Cm.',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exposiciones`
--

/*!40000 ALTER TABLE `exposiciones` DISABLE KEYS */;
INSERT INTO `exposiciones` (`codigo_exposicion`,`fecha_inicio`,`fecha_termino`,`nombre`,`lugar`,`curador`,`organizador`,`patrocinador`,`ciudad_pais`) VALUES 
 (2,'1998-09-01 00:00:00','2004-10-02 00:00:00','El Retrato','Museo Regional de Pintura ','Hugo Pontoriero. Museo de Arte Decorativo. Dirección Nacional de Patrimonio y Museos','Secretaría de Cultura de la Nación. Dirección Nacional de Patrimonio y Museos. Museo de Arte Decorativo.','Telefónica Argentina','Paraná, Entre Ríos, Argentina'),
 (3,'1950-09-01 00:00:00','2009-01-01 00:00:00','El Nombre','Museo Regional del Nombre','Hugo Pontoriero. Museo de Arte de Nombres. Dirección Nacional de Patrimonio y Museos','Secretaría de Cultura de la Nación. Dirección Nacional de Patrimonio y Museos. Museo de Arte de Nombres.','BC','Capital Federal, Buenos Aires, Argentina');
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
 ('Álbum desarmable (ganchos, tiras, cintas, otros)'),
 ('Álbum encuadernado cosido'),
 ('Encuadernado cosido'),
 ('Unidad suelta'),
 ('Unidad suelta con envase');
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formas_autorizadas_nombre`
--

/*!40000 ALTER TABLE `formas_autorizadas_nombre` DISABLE KEYS */;
INSERT INTO `formas_autorizadas_nombre` (`codigo_formas_autorizadas_nombre`,`codigo_identificacion`,`forma_autorizada_nombre`) VALUES 
 (1,'AR/PN/SC/DNPM/PSJ','Museo y monunmento histórico nacional Justo José de Urquiza'),
 (3,'AR/PN/SC/DNPM/CNS','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Casa Nat'),
 (4,'AR/PN/SC/DNPM/MCASN','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo de'),
 (5,'AR/PN/SC/DNPM/CNB','Casa Nacional del Bicentenario'),
 (6,'AR/PN/SC/DNPM/MJN','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Je'),
 (7,'AR/PN/SC/DNPM/INET','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Institut'),
 (8,'AR/PN/SC/DNPM/INMCV','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Institut'),
 (9,'AR/PN/SC/DNPM/INJDP','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Institut'),
 (10,'AR/PN/SC/DNPM/MCY','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Ca'),
 (11,'AR/PN/SC/DNPM/ME','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Ev'),
 (12,'AR/PN/SC/DNPM/MHN','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Hi'),
 (13,'AR/PN/SC/DNPM/MHNCYRM','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Hi'),
 (14,'AR/PN/SC/DNPM/MNAO','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Na'),
 (15,'AR/PN/SC/DNPM/MNG','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Na'),
 (16,'AR/PN/SC/DNPM/MRPJAT','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Re'),
 (17,'AR/PN/SC/DNPM/MR','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Ro'),
 (18,'AR/PN/SC/DNPM/MNBA','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Na'),
 (19,'AR/PN/SC/DNPM/MNAD','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museos. Museo Na'),
 (20,'AR/PN/SC/DNPM/MM','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museos. Museo Mit'),
 (21,'AR/PN/SC/DNPM/MHS','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Museo Hist'),
 (22,'AR/PN/SC/DNPM/MHDN','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Museo Hist'),
 (23,'AR/PN/SC/DNPM/MCHI','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Museo Casa'),
 (24,'AR/PN/SC/DNPM/MEJAGYCVL','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Museo Naci'),
 (25,'AR/PN/SC/DNPM/INAPL','Presidencia de la Nación. Secretaría de Cultura. Dirección Nacional de Patrimonio y Museo. Instituto'),
 (26,'AR/PN/SC/DNPM/CNML','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Comisión N'),
 (27,'AR/PN/SC/DNPM/INB','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Instituto '),
 (28,'AR/PN/SC/DNPM/INS','Presidencia de la Nación. Secretaría de Cultura.Dirección Nacional de Patrimonio y Museo. Instituto '),
 (29,'AA','Nombre'),
 (30,'AR/PN/SC/DNPM/CNSDR','NOMBRE 2'),
 (31,'AR/PN/SC/DNPM/Prueba','Prueba ');
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
 ('Canje'),
 ('Compra'),
 ('Dacion'),
 ('Donación'),
 ('Expropiación'),
 ('Hallazgo'),
 ('Legado'),
 ('Recolección'),
 ('Transferencia');
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
 ('Demo'),
 ('Documental'),
 ('Ficción'),
 ('Video Registro');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gestion_conservacion`
--

/*!40000 ALTER TABLE `gestion_conservacion` DISABLE KEYS */;
INSERT INTO `gestion_conservacion` (`codigo_gestion`,`codigo_referencia`,`fecha_inicio_tratamiento`,`fecha_fin_tratamiento`,`estado_conservacion`,`descripcion_fisica_pormenorizada`,`recubrimiento_superficial`,`diagnostico_especifico`,`tiempo_reformateo`,`caducidad_reformateo`,`fecha_imagen_incio`,`fecha_imagen_posterior`,`informe_tratamiento`,`resultados_tratamiento`,`restringido_exibicion`,`caracteristica`,`responsable`,`codigo_institucion`,`fecha_ultima_modificacion`,`usuario`,`full`) VALUES 
 (1,'AR/PN/SC/DNPM/CNS/C/DOCSON','2011-06-09 00:00:00',NULL,'Intacto','En este campo de texto se deberá describir o enume','Recubrimiento superficial','\r\n\r\n<p>Descripción ...</p>',NULL,NULL,NULL,NULL,NULL,NULL,0,'\r\n\r\n<p>Descripción ... ¿?)(=/&amp;%$\"!ª</p>',NULL,'AR/PN/SC/DNPM/CNS','2011-06-10 14:41:19','camilo',0),
 (2,'AR/PN/SC/DNPM/CNS/C/DOCSON','2009-01-01 00:00:00',NULL,'Dañado','Descripción...','Recubrimiento...','diagnóstico...',NULL,NULL,NULL,NULL,NULL,NULL,1,'caracteristicas',NULL,'AR/PN/SC/DNPM/CNS','2011-06-10 14:51:34','camilo',0);
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
 ('Caja'),
 ('Cajón'),
 ('Carpeta'),
 ('Envoltorio'),
 ('Estante abierto'),
 ('Sobre');
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
 ('Francés'),
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
  `fecha_ultima_modificacion` datetime DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
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
INSERT INTO `instituciones` (`codigo_identificacion`,`formas_conocidas_nombre`,`tipo_institucion`,`tipo_entidad`,`telefono_de_contacto`,`email`,`www`,`historia`,`estructura_organica`,`politicas_ingresos`,`proyecto_nuevos_ingresos`,`instalaciones_archivo`,`fondos_agrupaciones`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`dias_apertura`,`horaios_apertura`,`fechas_anuales_cierre`,`codigo_interno`,`estado`,`fecha_ultima_modificacion`,`usuario`) VALUES 
 ('AA',NULL,'Estatal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2011-06-15 16:03:12','camilo'),
 ('AR/PN/SC/DNPM/CNB','Casa Nacional del Bicentenario','Estatal',NULL,'(+54) +11 4813.0301 / 0679','casa@bicentenario.gov.ar','http://www.casadelbicentenario.gob.ar/la-casa/info','La Secretaría de Cultura de la Presidencia de la Nación crea la Casa Nacional del Bicentenario en el marco de la conmemoración de los 200 años de la Independencia. La Casa es un nuevo espacio cultural en la ciudad de Buenos Aires, dedicado a reflexionar acerca de las transformaciones políticas, sociales y culturales producidas en la Argentina en los últimos 200 años, donde los documentos del pasado y los testimonios del presente integran un tejido vivo que nos  permite   acercarnos al pasado para   pensar nuestro futuro.','La Casa se propone, desde una perspectiva federal y pluralista, ofrecer a los visitantes: exhibiciones, debates, seminarios, ciclos de cine, música, danza y teatro, en torno a temas que recorren la historia argentina, con la finalidad de reconocer la diversidad, repensar y reflexionar acerca de la identidad nacional y desde allí abrir la discusión del futuro que los argentinos anhelamos como Nación.','h','h','i','3','Arancelado - Solo para investigadores',NULL,NULL,'Todos los días',NULL,'Nunca',NULL,4,'2011-06-22 11:41:30','camilo'),
 ('AR/PN/SC/DNPM/CNML','Comisión Nacional de la Manzana de las Luces','Estatal',NULL,'4343-3260 /4331-9543 interno 122','bibliomanzanadelasluces@gmail.com','http://www.manzanadelasluces.gov.ar','La Época Jesuítica - Siglos XVII y XVIII\r\n\r\nCuando Juan de Garay llegó a estas tierras, trazó por primera vez la cuadrícula de la que estaba destinada a ser ciudad de la Trinidad y Puerto de Buenos Aires. Así quedó al mismo tiempo marcado el emplazamiento que daría origen a la secular Manzana de las Luces.\r\n La primera misión jesuítica llego al Perú en 1568. La componían ocho religiosos y su intención era la de convertir a los aborígenes. Desde Lima irradiaron su acción evangelizadora hacia el sur, pero recien en 1608 pudo concretar su establecimiento en buenos Aires, donde se instalaron en lo que actualmente es la mitad oriental de la Plaza de Mayo, en el terreno que Garay había anteriormente cedido al Adelantado Juan de Vera y Aragón. Allí los jesuitas construyeron su primera residencia, iglesia y colegio.\r\n Estas edificaciones originales no tardaron en sufrir varios deterioros, a causa de la precariedades de los materiales que se habían utilizado en su construcción.\r\n En 1659 se resolvió poner la ciudad en buen estado de defensa, puesto que por entonces el Río de la Plata se veía amenazado por corsarios y piratas ingleses, franceses holandeses.\r\n El terreno donde se habían radicado los jesuitas, aledaño al Fuerte, resultaba desde todo punto de vista inadecuado, por lógicas razones militares, pues cualquier construcción obstaculizaría el empleo de la artillería emplazada en la fachada del Fuerte donde estaba la portada principal. Por eso, el 25 de mayo de 1661 se trasladaron a un nuevo predio, cedido a la Compañía de Jesús por Isabel de Carvajal, situado en la manzana limitada por las actuales calles Bolívar, Moreno, Perú y Alsina.\r\n Este solar quedo identificado por primera vez como Manzana de las Luces, en un articulo aparecido en el periódico \"El Argos\" del 1° de septiembre de 1821, en el cual se mencionaban las instituciones de irradiación cultural que funcionaban en su ámbito.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS','Casa Natal de Sarmiento','Estatal',NULL,'No se conoce','No se conoce','No se conoce','En este Monumento Histórico Nacional, hoy convertido en Museo y Biblioteca, nació el ilustre Prócer Domingo Faustino Sarmiento, el 15 de febrero de 1811. La casa comenzó a construirla Doña Paula Albarracín, madre del Prócer en 1801, la misma es una típica construcción de estilo colonial con amplio pasillo de entrada, arco de medio punto y patio principal donde se yergue la patrialcal higuera. Como todas las casa de aquella época en San Juan los materiales y técnicas usadas son adobe y tapias con techos de caña y barro sostenido por rollizos de álamo. La casa se fue ampliando lentamente atento a las necesidades familiares.. En 1862 cuando Sarmiento fue Gobernador de San Juan, adquiere el tamaño y la forma con que se conserva en la actualidad. La construcción ha soportado cuatro grandes terremotos, el de l894,1944,1952 y 1977. El que causó mayores daños fue el 1944 a consecuencia del cual debió reconstruirse, respetando el plano original de 1866, toda el ala norte. \r\nEl ala sur no sufrió grandes daños y solo recibió obras de restauración y consolidación.\r\nAl cumplirse el Primer Centenario del Natalico del Prócer el Gobierno Nacional adquirió la Casa declarándola Monumento Histórico. Las descendientes de la familia Sarmiento- Albarracín vivieron en ella hasta1910.\r\n','Estructura','Historia','Historia','Instalaciones','1','Arancelado - Libre',NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR','Forma conocida de nombre','Sociedad Civil',NULL,'Tel+efono','e-mail@correo.com.ar','Página Web 1236_0)','No hay datos','No hay datos','No hay datos','No hay datos','No hay datos','1','Arancelado - Libre',NULL,NULL,'bdfheaf',NULL,'hrathjratja',NULL,4,'2011-06-17 10:48:10',NULL),
 ('AR/PN/SC/DNPM/INAPL','Instituto Nacional de Antropología y Pensamiento Latinoamericano','Estatal',NULL,'No se conoce','No se conoce','http://www.inapl.gov.ar/','El Instituto Nacional de Antropología y Pensamiento Latinoamericano (INAPL) fue creado el 20 de diciembre de 1943 con el nombre de Instituto Nacional de la Tradición, siendo su primer director el maestro Juan Alfonso Carrizo. El Estado Nacional adquiere su actual sede en el año 1973, donde alberga el Museo, la Biblioteca y los laboratorios de investigación. A partir del año 1991 se le da su presente denominación.\r\nDesde su creación, se dedica a la investigación en las áreas de antropología social, folklore y arqueología. Actualmente se llevan adelante más de 20 proyectos de investigación, especializados en recuperar, documentar y gestionar el patrimonio cultural tangible e intangible. Asimismo, asesora en propuestas alternativas de desarrollo socio-cultural y económico regional.\r\nEl reconocimiento académico con que cuenta a nivel nacional e internacional hace que prestigiosos profesores extranjeros anualmente dicten seminarios en el organismo. Dentro de su sede y fuera de ella brinda cursos tanto de posgrado para investigadores formados, como de difusión y formación. Estos cursos cuentan generalmente con un 70% de alumnos del interior del país. Los investigadores del INAPL participan de Congresos, Seminarios y Jornadas, eventos en los que son invitados gracias a su trayectoria y en los cuales presentan numerosas ponencias y comunicaciones, ya sea en el país o en el exterior. Los proyectos de investigación que dirigen cuentan con estudiantes que realizan pasantías, formando de esta manera nuevos profesionales en investigación.La calidad de las investigaciones que se desarrollan en el INAPL ha hecho que recibiera subsidios de diferentes organizaciones nacionales e internacionales. El Instituto edita una publicación de nivel académico y otras especializadas y de difusión, contando además con un Boletín Informativo cuatrimestral.\r\nCabe destacar que el Instituto cuenta con una biblioteca especializada con más de 25.000 volúmenes y que la misma es utilizada por investigadores, estudiantes y público en general. El Museo Nacional del Hombre que se encuentra en la planta baja del edificio realiza múltiples actividades y talleres para escuelas; en el último año recibió 13.000 visitantes. El INAPL también posee una videoteca especializada en la temática antropológica que sobrepasa los 600 títulos.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/INB','Instituto Nacional Browniano','Estatal',NULL,'(5411) 4362-1225 / 4307-9925','info@inb.gov.ar','www.inb.gov.ar','El Instituto Browniano, fue creado el 22 de febrero de 1948 con el fin de exaltar la figura de nuestro héroe máximo en el mar y fundador de la Armada Argentina, el Almirante Guillermo Brown, y fomentar la conciencia naval y marítima argentina.\r\n\r\nEl 18 de diciembre de 1996, por decreto del Poder Ejecutivo Nacional, fue elevado a la categoría de Instituto Nacional, pasando al ámbito de la Secretaría de Cultura de la Presidencia de la Nación.\r\n\r\nSu actividad es de carácter histórico patriótico. Cumple sus fines por medio de la investigación y la exposición de la vida y la obra del Almirante Brown, de sus colaboradores y subordinados; la divulgación de la historia de la Armada Argentina; el estudio y la difusión de los intereses marítimos y fluviales argentinos, circunscriptos a sus aspectos históricos.\r\n\r\n•  Fomenta la investigación histórica dentro y fuera del país; colabora con las autoridades nacionales, provinciales o municipales, así como instituciones oficiales y privadas en temas de su competencia.\r\n•  Promueve e impulsa actos cívicos, patrióticos y culturales (conferencias, conciertos, congresos, obras de teatro, muestras pictóricas, etc.) que tienden a enaltecer la figura de los próceres navales.\r\n•  Coopera y asesora al Gobierno de la República de Irlanda en el homenaje permanente al héroe naval, en la conservación de fuentes documentales y objetos personales existentes en su pueblo natal, Foxford, condado de Mayo.\r\n•  Publica libros, folletos y la revista \"Del Mar\", que es su órgano de difusión histórica y reseña de sus actividades.\r\n\r\nEl Instituto cuenta con representaciones en el país y en el extranjero, encargadas de llevar adelante la difusión browniana en sus zonas de influencia, y a través del accionar de los miembros de las mismas.\r\n\r\n•  Promueve la formación de archivos y registros documentales, propicia la construcción y colocación de monumentos en todo el territorio de la Nación y en el extranjero, así como la formación de bibliotecas brownianas.\r\n•  Mantiene relaciones de reciprocidad con fuerzas armadas y de seguridad, embajadas, institutos nacionales, museos, centros de investigación, asociaciones culturales, etc.\r\n','En el museo se encuentran en exhibición permanente:\r\n\r\n•  Daguerrotipos\r\n•  Cuadros realizados en distintas técnicas por diversos autores, que aluden a la figura del Almirante\r\n•  Objetos personales\r\n•  Réplicas de uniformes\r\n•  Mobiliario\r\n•  Estandartes\r\n•  Bustos en diversos materiales\r\n•  Filatelia\r\n•  Numismática \r\n\r\n\r\nLa Biblioteca\r\n\r\nEl Instituto dispone de una vasta biblioteca especializada de producción propia y de reconocidos autores\r\n\r\n•  Documentos\r\n•  Revista Del Mar\r\n•  Folletos\r\n•  Enciclopedias\r\n•  Libros\r\n•  Historietas\r\n•  Láminas\r\n\r\n',NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/INET','Instituto Nacional de Estudios de Teatro - INET','Estatal',NULL,'54+11 4815-8817','estudiosdeteatro@inet.gov.ar','http://www.cultura.gov.ar/direcciones/','Creado en 1936, al mismo tiempo que la Comedia Nacional y el Conservatorio Nacional de Arte Dramático, este Museo tiene hoy su sede en la planta baja del Teatro Nacional Cervantes. \r\nEs un edificio de estilo Barroco Español, su puerta principal es entrada a un mundo de fotografías, afiches y programas de mano que cuentan la historia de la dramaturgia nacional. \r\n\r\nSus salas albergan memoria de la actividad teatral desde los días coloniales, pasando por la Revolución de Mayo, la Independencia, la Federación, la Comedia Nacional y más.Los documentos históricos se alternan con plantas escenográficas, vestuario y objetos personales de actores, actrices, directoresa y autores de la escena argentina. \r\n\r\nEn la sala principal (Trinidad Guevara) se desarrollan actividades de extensión: teatro leído, presentaciones de libros, exposiciones temáticas, ciclos de teatro argentino filmado, cuento teatralizado, talleres y jornadas de investigación, en conjunto con otras entidades del medio. \r\n\r\nEl Museo cuenta con el archivo documental sobre teatro más amplio del país, con material genuino que puede ser consultado a través de su biblioteca Las tres unidades funcionales: Museo, Archivo Documental y Biblioteca forman parte del Instituto Nacional de Estudios de Teatro que además, brinda servicios gratuitos a grupos de teatro de todo el país. \r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/INJDP','Instituto Nacional Juan Domingo Perón','Estatal',NULL,'4802-8010/ 4802-4596 / 4801-3562 / 4801-9404','instituto@jdperon.gov.ar','www.jdperon.gov.ar ','El edificio donde se ha instalado el Instituto Nacional Juan Domingo Perón está ubicado al lado del nuevo edificio de la Biblioteca Nacional, en el predio en el que se levantaba (hasta 1955) la ex residencia presidencial, donde vivió el Tte. Gral. Juan Domingo Perón en sus dos primeros gobiernos, y donde murió Eva Perón en 1952. Consta de varias salas de estudios e investigaciones, un gran salón de actos para los ciclos de conferencias y cursos; una sala multimedia, una biblioteca, lugar de lectura y exposiciones; un salón en planta baja en donde se encuentra la \"librería peronista\"; una sala de reuniones y recepciones, el despacho del Secretario General y oficinas para las áreas de administración, informática y publicaciones. ',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/INMCV','Instituto Nacional de Musicología ','Estatal',NULL,'(54 11) 4361-6520/6013','inmuvega@cultura.gov.ar','inmuvega.gov.ar','El Instituto Nacional de Musicología \"Carlos Vega\" fue fundado en 1931 por el maestro Carlos Vega, para la investigación musical.\r\n\r\nSu objetivo es el estudio de las diversas músicas que se producen en la Argentina y en otros países latinoamericanos. \r\n\r\nEl Instituto consta de un vasto archivo sonoro, fonográfico, fotográfico, de instrumentos musicales aborígenes y criollos, manuscritos y partituras de música argentina. También cuenta con una fonoteca y una amplia biblioteca especializada en música argentina y latinoamericana, que se encuentra abierta al público.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/INS','Instituto Nacional Sanmartiniano','Estatal',NULL,'(54 11) 4802-3311 / 4801-0848','insadministracion@arnetbiz.com.ar','www.sanmartiniano.gob.ar','En la capital de la República Argentina el 5 de abril de 1933, aniversario de la batalla de Maipú, por iniciativa del doctor José Pacífico Otero y en la sede del Circulo Militar, se procedió solemnemente a la fundación del Instituto Sanmartiniano.\r\n\r\nEl doctor Otero presidió el Instituto desde la fecha de su fundación hasta el momento de fallecer el 14 de mayo de 1937.\r\n\r\nEn el año 1941 su viuda, la señora Manuela Stegmann de Otero, donó al Instituto- en memoria de su esposo una casa a construir especialmente, reproducción de la que ocupara el General San Martín en Grand Bourg entre los años 1834 y 1848. La Municipalidad de la ciudad de Buenos Aires, durante la gestión del Grl Basilio Pertin, cedió un terreno de 290 m2 en la plaza formada por las calles Sánchez de Bustamante (hoy Mariscal Castilla) y Alejandro Aguado, en la que se concretó la donación, siendo la fecha de inauguración de su nueva sede el 11 de agosto de 1946.\r\n\r\nConsiderando el Poder Ejecutivo Nacional la necesidad de dar carácter oficial a una institución encargada de difundir la gloria, vida y obra del Libertador, dada la magnitud del héroe máximo y la trascendente obra histórica de la entidad, dispuso darle al Instituto la jerarquía que merecía y a tal efecto dictó, el 16 de agosto de 1944, el decreto N 22.131 por el cual fue oficializado con la denominación de Instituto Nacional Sanmartiniano.\r\n\r\nEl 27 de junio de 1945, se designó su primer Consejo Superior, presidido por el Cnl Bartolomé Descalzo con dependencia del entonces Ministerio de Guerra. En la actualidad depende de la Secretaria de Cultura de la Nación.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás ','Estatal',NULL,'54 (0) 3461 428980','museocasadelacuerdo@intercom.com.ar','http://www.cultura.gov.ar/','Hacia 1852 el propietario de esta casa era Don Pedro Alurralde, Juez de Paz del partido y primera autoridad de la ciudad, quien la había adquirido en 1849.\r\nSiendo Alurralde amigo de Urquiza, le cedió su casa después de la Batalla de Caseros, para que presidiera la reunión de los gobernadores de las 14 provincias de la que surgió el Pacto del 31 de mayo de 1852, el “Acuerdo de San Nicolás”, que sentó las bases del Congreso Constituyente en el que se delineó la Constitución Nacional cuyos fundamentos prevalecen hasta hoy.\r\n','La casa, edificada hacia 1830, está emplazada en el área central de la ciudad. Tiene las formas sencillas de las construcciones de mediados del siglo XIX, y constituye un ejemplo de vivienda particular urbana de esa época, con un patio central con baldosas coloradas, jazmines, madreselvas y estrellas federales, sin galería y encerrado por las habitaciones, al que se accedía desde la calle a través del zaguán.\r\nSu única planta está compuesta por habitaciones de paredes de ladrillos, las más antiguas revocadas en barro alisado.\r\nLos techos planos o “de azotea” apoyan sobre tirantería y alfajías de pinotea. Su cubierta original de ladrillotes fue reemplazada por chapa ondulada.\r\n','h','p','i','1','Gratuito - Libre',NULL,NULL,'Enero 2008: Lunes a viernes de 9 a 13. A partir de febrero 2008: Lunes a viernes de 12 a 19 hrs. Sábados, domingos y feriados de 9 a 13 hrs',NULL,'.',NULL,5,NULL,NULL),
 ('AR/PN/SC/DNPM/MCHI','Museo Casa Histórica de la Independencia','Estatal',NULL,'+54 - 381 - 4310826 / 4221335. ','museocasahistorica@arnetbiz.com.ar','http://www.museocasadetucuman.com.ar/','En esta casa, en 1816, se reunieron 29 hombres para decidir el futuro de estas tierras... hasta entonces españolas.\r\n\r\nEn concreto :\r\nLos países que hoy son Argentina, Bolivia, Paraguay, Uruguay y porciones de Brasil y Chile conformaban el llamado \"Virreinato del Río de la Plata\", y eran tierras pertenecientes a la Corona Española. Siguiendo los acontencimientos históricos iniciados en 1810 en búsqueda de la Independencia, fue el 9 de Julio de 1816 que en esta casa, en la ciudad de Tucumán, se reunieron los hombres que decidieron que a partir de ese día, estas tierras serían independientes de España y de toda dominación extranjera.',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MCY','Museo Casa de Yrurtia','Estatal',NULL,'(54 11) 4781-0385','info@museocasadeyrurtia.gov.ar','http://www.cultura.gov.ar/direcciones/?info=organi','En 1942, por ley 12.824 del 30 de septiembre, cuyo proyecto fue presentado en el Senado por el Dr. Alfredo L. Palacios, el escultor Rogelio Yrurtia y su esposa la pintora Lía Correa Morales, transfirieron al Estado su casa del barrio de Belgrano con su mobiliario y obras de arte. La misma se abrió al público como museo en 1949. Yrurtia había comprado una vieja casa de fines de siglo XIX con un terreno de 1.200 metros cuadrados. El mismo fue el responsable del diseño de la ampliación y total reforma del edificio existente. El estilo elegido por el maestro fue el neocolonial, acorde con una revalorización de la tradición hispánica que por entonces se había impuesto en círculos intelectuales. El arquitecto que realizó los planos técnicos en base a los de Yrurtia y llevó a cabo la obra fue K. A. Schmit El constructor fue Pedro Rossi. La casa obtuvo en 1921 un premio municipal de arquitectura. Rogelio Yrurtia habla nacido en Buenos Aires el 6 de diciembre de 1879. En 1898 ingresó a la Escuela de la Sociedad Estimulo de Bellas Artes donde fue su maestro Lucio Correa Morales. A fines de 1899 obtuvo por concurso una de las becas instituidas por el Ministerio de Instrucción Pública, para realizar estudios en Europa. \r\n\r\nLos monumentos realizados por Yrurtia se encuentran en la ciudad de Buenos Aires: Canto al Trabajo, en Paseo Colón e Independencia, Monumento al Coronel Manuel Dorrego, en Suipacha y Viamonte, al Dr. Alejandro Castro en el Hospital de Clínicas, Justicia, en el Palacio de Tribunales, Mausoleo de Bernardino Rivadavia, en Plaza Miserere. Numerosos son los objetos coleccionados por los esposos Yrurtia a lo largo de sus viajes y durante las prolongadas estadías en el exterior. \r\nTodos ellos eran expuestos en los distintos ambientes de su casa y, si les sumamos las obras del maestro, muchas de ellas de gran formato y las distintas pinturas y dibujos de su esposa y de sus amigos, obtendremos el conjunto que hoy puede parecer abigarrado pero que responde a un cierto gusto victoriano aún en boga. La colección de esculturas de Yrurtia es amplia. Pueden verse los modelos a tamaño natural del Moisés, perteneciente al Mausoleo de Bernardino Rivadavia, la Victoria del Monumento a Manuel Dorrego y la Justicia, entre otras. \r\n\r\nNumerosos retratos de bronce y en yeso, cabezas femeninas como Solicitude, Primavera, Daphne o Romana, estudios de torsos, pies y manos y otras obras completan el conjunto. Numerosas pinturas y dibujos de Lía Correa Morales, segunda esposa de Yrurtia e hija de su primer maestro, Lucio Correa Morales, pueblan los muros. Los géneros más visitados por Lía fueron la naturaleza muerta y el retrato, especialmente femenino o infantil, además de los croquis de bailarinas que tomaba del natural. La colección de pinturas de artistas argentinos comprende obras de Martín Malharro, Angel Della Valle, Eduardo Sívori, Cesáreo Bernaldo de Quirós, Octavio Pinto, Benito Quinquela Martín, Walter de Navazio, entre otros. Entre los extranjeros destaca una obra temprana de Pablo Picasso que Yrurtia adquirió durante una de sus estancias en París. Gran atracción por los textiles, alfombras y tapices, demuestra la vasta colección que reunieron los esposos Yrurtia. Integran el conjunto batiks javaneses, tapices chinos bordados, alfombras anudadas de distinta procedencia, chales de Cachemira, textiles de México y Bolivia, un tapiz de la Manufacture Nationale des Gobelins, etc. \r\n\r\nEl mobiliario reunido es de estilo diverso. Hay varios armarios de estilo Renacimiento flamenco, mesas, sillas y sillones de procedencias española, muebles ingleses y franceses de estilo imperio, provenzal, etc. Objetos de cerámica se encuentran diseminados por las distintas vitrinas y sobre los muebles. Entre ellos destacan los de manufactura de Talavera de la Reina (España) y Delft (Holanda). Son numerosos los objetos de uso doméstico de peltre, bronce o cobre, como platos, pavas, calientacamas, velones, etc. Finalmente, y sin por ello agotar la variedad de piezas existentes en el museo, son abundantes los objetos de distinto tipo provenientes de China, Japón y Java.\r\n','e','h','p','i','1',NULL,NULL,NULL,'martes a viernes de 13 a 19, sábados y domingos de 15 a 19.',NULL,NULL,NULL,3,NULL,NULL),
 ('AR/PN/SC/DNPM/ME','Museo Evita','Estatal',NULL,'(011) 4807-0630','info@museoevita.org','www.museoevita.org','En un recorrido por las trece salas de exposición permanente con las que cuenta el Museo Evita, el visitante puede conocer la historia de Eva Duarte. Su niñez, su juventud como actriz, su vida como primera dama junto a Juan Domingo Perón, su lucha por los derechos cívicos femeninos, la obra social desarrollada en la Fundación Evita, el renunciamiento y su muerte. \r\n  \r\nHay allí alrededor de 400 piezas originales entre trajes, vestidos de gala, sombreros, su libreta cívica (la número 1), fotos, publicaciones y registros de la época, filmaciones, ambientaciones que recuerdan el hogar de tránsito donde hoy funciona el Museo, un patio andaluz de 1923, un auto de la antigua ciudad infantil, juguetes y una de las máquinas de coser que donaba la Fundación. \r\n  \r\nEl Museo es presidido por Cristina Álvarez Rodríguez, sobrina nieta de Evita. El Instituto de Investigaciones Históricas Eva Perón cuenta con biblioteca, un auditorio con capacidad para 90 personas, un bar restaurante con entrada independiente y una tienda de recuerdos. \r\n  \r\nEl edificio \r\n  \r\nDe gran belleza y refinamiento, también tiene su historia. Es un petit hotel de planta baja y dos pisos construido por la familia Carabassa en la primera década del siglo XX, y luego intervenido por el arquitecto Estanislao Pirovano en un reciclaje que conjuga elementos del renacimiento español e italiano en sus tres niveles y torre. \r\n  \r\nEn 1948, esta casona de la calle Lafinur en la Ciudad de Buenos Aires, fue adquirida por la Fundación de Ayuda Social Eva Perón para albergar al hogar de tránsito Nº 2, un lugar que recibía a mujeres del interior del país con problemas de salud, trabajo, documentación o vivienda en su paso por Buenos Aires. \r\n  \r\nDesde el 2000 funcionana allí el Instituto que intervino el edificio para instalar el Museo Evita, con un plan director que respetó, restauró y puso en valor aquellas áreas que le dieron identidad. Se inauguró el 26 de julio de 2002, al cumplirse 50 años del fallecimiento de Eva. ',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MEJAGYCVL','Museo Nacional Estancia Jesuítica de Alta Gracia y Casa del Virrey Liniers','Estatal',NULL,'03547-421303','e','http://www.museoliniers.org.ar','El Museo Histórico Nacional de la Estancia Jesuítica de Alta Gracia y Casa del Virrey Liniers, tiene su sede en una antigua residencia del siglo XVII que integraba el casco de la Estancia Jesuítica de Alta Gracia.\r\n\r\nCórdoba era en aquella época capital de la Provincia Jesuítica del Paraguay. Esa Provincia comprendía los actuales territorios de Paraguay, Brasil, Uruguay, Bolivia y Argentina, conformando una red social, económica y cultural que convirtió a Córdoba en uno de los centros de desarrollo más importantes de Sudamérica.\r\nDentro de esa estructura surgieron las estancias de Caroya, Jesús María, Santa Catalina, Alta Gracia, La Candelaria y San Ignacio de los Ejercicios (hoy en ruinas) que solventaron con su producción económica los establecimientos educativos fundados por los jesuitas y que actualmente forman parte de la Manzana de la Compañía en la ciudad de Córdoba. La Estancia de Alta Gracia fue uno de los centros rurales más prósperos de la compañía cordobesa. Tenía como objetivo el sostén del Colegio Máximo, luego primera universidad del territorio argentino y mantenía un fluido intercambio económico con las otras estancias jesuíticas.\r\n\r\nEste centro rural estaba integrado por la Residencia (actual museo), la Iglesia, el Obraje donde se desarrollaban las actividades industriales, la Ranchería (vivienda de negros esclavos), el Tajamar (dique de 80 m de largo), los Molinos Harineros, el Batán (edificio que alberga una máquina movida por el agua y compuesta por mazos de madera cuyos mangos giran sobre un eje para golpear, desengrasar los cueros y dar consistencia a los paños) y otras construcciones que datan de los siglos XVII y XVIII.\r\n \r\nEn 1810 la Estancia fue adquirida por Santiago de Liniers quien vivió unos pocos meses en la casa.\r\nEn 1820 José Manuel Solares compró la propiedad a la familia de Liniers, siendo el último dueño de la estancia.\r\nPor voluntad testamentaria decidió el deslinde de los terrenos para la conformación de una villa, hoy ciudad de Alta Gracia, quedando la residencia en el centro del núcleo urbano al que dio origen. Durante cien años los Lozada, herederos de Solares fueron los propietarios de las construcciones jesuíticas y tierras adyacentes. \r\n \r\nEn 1969 la Nación Argentina expropió a sus dueños la residencia, convirtiéndola en Museo, inaugurado oficialmente en 1977. Su importancia radica fundamentalmente en su valor arquitectónico, que se preserva fiel a la estructura original.\r\nEsta casa museo ha sido ambientada de acuerdo a los modos de vida que tenían los cordobeses y serranos en los siglos XVII, XVIII y XIX y su patrimonio está compuesto por objetos de gran significación, evocadores de la vida cotidiana y las formas de trabajo en la antigua estancia.\r\n\r\nPor medio de dioramas, maquetas, gráficos, fotografías en las salas y ambientaciones de época (herrería, alcoba, cocina, etc.) se procura que el visitante descubra quiénes fueron los actores sociales que vivieron y trabajaron en ella: jesuitas, negros, aborígenes., europeos y criollos.\r\n\r\nEl 2 de diciembre de 2000 como parte del sistema Jesuítico Cordobés, Alta Gracia, las otras estancias y la Manzana de la Compañía: Iglesia, Capilla Doméstica, Residencia de los Padres, Rectorado de la U.N.C. y Colegio Monserrat, fueron declaradas por la UNESCO Patrimonio de la Humanidad.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MHDN','Museo Histórico del Norte','Estatal',NULL,'0387 - 4215340','cabildosalta@uolsinectis.com.ar','http://www.museonor.gov.ar/',NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MHN','Museo Histórico Nacional','Estatal',NULL,'(54 11) 4307-1182','informes@mhn.gov.ar','http://museoevita.org/','Hacia la década de 1880, luego de medio siglo de guerras civiles, la Argentina se embarcó en el proceso de consolidación de su Estado Nación. La rápida expansión económica, el desarrollo de las comunicaciones y la llegada masiva de inmigrantes cambiaron por completo la composición socio-económica del país y Buenos Aires, en particular, se transformó en una ciudad moderna y cosmopolita. Fue entonces cuando los grupos dirigentes se enfrentaron con la necesidad de desarrollar un sentimiento de nacionalidad que, ante los acelerados y profundos cambios que estaban ocurriendo, lograra unificar a la sociedad argentina apelando a un acervo común de tradiciones y a una historia compartida. \r\n\r\nCon la creación del Museo Histórico Nacional –una de las tantas propuestas culturales de ese momento – se buscaba desarrollar una educación patriótica para las nuevas generaciones y, de esta manera, impulsar un sentimiento de pertenencia nacional. El Museo fue concebido como el Panteón de la Patria donde se guardaban y veneraban las reliquias de los próceres de la Revolución de Mayo y las guerras de la independencia. Desde sus exhibiciones se difundió una narración histórica unilineal y homogénea –muchas veces respaldada por una iconografía patriótica hecha por encargo– que ignoraba los conflictos y la diversidad de identidades étnicas, regionales y sociales que convivían dentro de los límites del Estado argentino. \r\n\r\nLa renovación del Museo Histórico Nacional\r\nYa iniciado el siglo XXI el Museo Histórico Nacional tiene que brindar a sus visitantes un relato que muestre el papel de los diversos actores y, asimismo, interprete los múltiples procesos sociales que contribuyeron a la formación de la Argentina moderna. Para ello es necesario remontarse a los tiempos del poblamiento inicial de lo que hoy es el territorio nacional, hace no menos de 10.000 años, para culminar en el país contemporáneo: esa realidad histórica compleja, muchas veces desconcertante, contradictoria y siempre fascinante.\r\n\r\nEl Museo aspira a ser una institución destinada a rescatar, investigar, valorizar e interpretar, con las mejores técnicas y métodos posibles, la realidad pasada y presente de la Argentina, para luego proyectarla de manera crítica a la población. Deberá incentivar la curiosidad del público, plantear interrogantes, estimular el debate y provocar la reflexión. Se trata de proponer un museo dinámico que ofrezca distintas visiones del pasado, a través de un diálogo amplio capaz de manifestar las diversas maneras de ser argentino. \r\n\r\nLa fundación del Museo Histórico Nacional\r\nPor iniciativa de Adolfo P. Carranza, la municipalidad de la ciudad de Buenos Aires dispuso la creación del Museo Histórico en mayo de 1889. Se encargó su organización a una comisión de notables, entre los que figuraban Bartolomé Mitre y Julio A. Roca. Adolfo Carranza fue nombrado director de la nueva institución y ejerció el cargo hasta su muerte, en 1914.\r\n\r\nEn los primeros años, el Museo ocupó tres sedes distintas: la de Esmeralda 848, Moreno 330 y en el actual Jardín Botánico hasta que en 1897 pasó a depender de la administración nacional y se instaló definitivamente en la residencia que el acaudalado comerciante salteño José Gregorio Lezama había mandado a construir en el parque que actualmente lleva su nombre. \r\n\r\nLas colecciones del Museo Histórico Nacional\r\nEl Museo Histórico Nacional ha logrado reunir una colección de diversos objetos: excelentes grabados, litografías, cuadros, imágenes religiosas y esculturas; banderas, estandartes, armas y uniformes de las guerras de la Independencia; muebles, relojes, partituras, instrumentos musicales y vajillas de las familias tradicionales del siglo XIX; recuerdos de la celebración del Centenario de la Revolución de Mayo, relicarios y miniaturas, daguerrotipos, fotos y tarjetas postales; aperos, ponchos, objetos de plata y prendas gauchas. \r\n\r\nEntre sus colecciones pictóricas resaltan los cuadros de José Gil de Castro, que retrató contemporáneamente a varios protagonistas de la emancipación sudamericana; las pinturas de Cándido López, cuya obra constituye un valioso testimonio de la Guerra del Paraguay (1865-1870); y los trabajos de los artistas europeos León Palliere, César Bacle y Emeric Vidal quienes, a través de sus litografías, abordaron diversos aspectos de los usos y costumbres del Río de la Plata en el siglo XIX. Dignos de mención son los instrumentos musicales históricos como los pianos y los forte pianos de la familia Escalada, de María Sánchez de Thompson y de Eduarda Mansilla. \r\n\r\nEn el Museo puede visitarse la reproducción del dormitorio de José de San Martín en Boulogne-Sur-Mer (Francia), ambientado con objetos originales de acuerdo al bosquejo enviado por su nieta Josefa Balcarce.\r\n\r\nEl archivo personal de Adolfo Carranza forma parte del acervo histórico de la institución y dado su particular interés por la rica información que contiene, está abierto para los investigadores. Una valiosa biblioteca de alrededor de quince mil volúmenes, dedicada principalmente a la historia argentina y americana, puede ser consultada por el público general.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MHNCYRM','Museo Histórico Nacional del Cabildo y de la Revolución de Mayo','Estatal',NULL,'(+54 - 11) 4342-6729 y 4334-1782','cabildomuseo_nac@cultura.gov.ar','http://www.cultura.gov.ar/direcciones/?info=organi','El Cabildo es un edificio recuperado como un museo del siglo XXI y es el único testigo arquitectónico civil de los 200 años de vida independiente del país. \r\n\r\nComo parte de las obras del Bicentenario, el 23 de mayo de 2010 finalizó la puesta en valor y nuevo guión museológico del Museo Histórico del Cabildo y de la Revolución de Mayo, dependiente de la Secretaría de Cultura de la Nación.\r\n\r\nCon una inversión de 3.146.000 pesos, el histórico edificio y centro neurálgico de los acontecimientos de Mayo de 1810, ha sido dotado de un nuevo diseño museográfico y se han habilitado espacios que plantean la revalorización de los bienes exhibidos con un mensaje más claro para los visitantes.\r\n\r\nEntre las novedades, se incorporaron modernos sistemas interactivos de comunicación que favorecen la participación del público. Desde su reapertura, en la Semana de Mayo, ya pasaron 26.784 visitantes, el doble que durante todo mayo de 2009.\r\n\r\nPor primera vez en su historia, el público podrá acceder al balcón principal del edificio y visitar todas sus salas además de ver sus históricos túneles, a través de una cámara subterránea.\r\n\r\nEl nuevo guión también cuenta con dos pantallas que les permiten a los visitantes interactuar con la imagen del famoso cuadro del 22 de mayo y con un mapa con los puntos geográficos más importantes de la ciudad en los febriles días de la Revolución de Mayo.\r\n\r\nTambién se realizaron tareas de conservación y restauración de las piezas en exposición que fueron dotadas de nuevos soportes y exhibidores. Entre las obras más importantes, se destacan la puesta en valor de los techos de tejas; la reparación de revoques y pintura a la cal del edificio; la restauración de carpinterías de madera, umbrales de accesos, descansos de escalera y elementos de herrería; la puesta en valor integral de los espacios exteriores del predio; la renovación de todo el sistema de iluminación y del proyecto museográfico como la puesta en valor del acervo museal; y por último, la provisión de equipamiento tecnológico de apoyo para la exposición. Asimismo, se implementó un nuevo sistema de accesibilidad para personas con mobilidad reducida.\r\n\r\nLa administración de la obra se llevó a cabo a través de la Oficina para Proyectos de las Naciones Unidas (UNOPS), organismo que se encargó del proceso licitatorio con fondos de la Secretaría de Cultura de la Nación.\r\n\r\nEl equipo técnico a cargo de las obras estuvo dirigido por la arquitecta Elina Tassara, mientras que la nueva puesta museográfica fue coordinada por Gabriel Miremont y el equipo del propio Museo, dirigido por María Angélica Vernet. \r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MHS','Museo Histórico Sarmiento','Estatal',NULL,'(011) 4783 - 7555 // (011) 4781 - 2989','martagermani@museosarmiento.gov.ar','http://www.museosarmiento.gov.ar/','El Museo Histórico Sarmiento se fundó al cumplirse \r\nel cincuentenario de la muerte del prócer. \r\nEl 28 de julio de 1938 el presidente Ortiz firmó \r\nel decreto de su instalación por iniciativa \r\ndel Dr. Ricardo Levene, titular de la Comisión Nacional de Homenaje a Sarmiento y de la Comisión de Museos \r\ny Monumentos Históricos.\r\n\r\nEl Museo se asentó en la antigua casa \r\nde la Municipalidad de Belgrano, construída \r\npor el Arquitecto Antonio Buschiazzo e inaugurada \r\nel 8 de diciembre de 1872. En ella, el presidente\r\nAvellaneda y parte del Congreso Nacional habían\r\nsesionado entre los meses de junio y septiembre \r\nde 1880, durante el enfrentamiento \r\ndel Poder Ejecutivo y el gobernador bonaerense \r\nCarlos Tejedor.\r\n\r\nConcluída la guerra civil, se firmó allí \r\nla Ley de Federalización, por lo cual el edificio \r\nfue declarado Monumento Histórico. \r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MJN','Estancia de Jesús María - Museo Jesuítico Nacional','Estatal',NULL,'54 (3525) 420-126','mjn-jm@coop5.com.ar','http://www.cultura.gov.ar/direcciones/?info=organi','La Compañía de Jesús llega a la provincia de Córdoba, en la actual Argentina en 1599. En 1608 crean el Noviciado y dos años después se declara al Colegio de Córdoba como Colegio Máximo. Debido a algunos problemas económicos que se presentan, comienzan a adquirir establecimientos o tierras que destinarán a la explotación rural, en diversos lugares de la provincia. \r\n\r\nAños más tarde, estos convertidos en estancias colaboraron con el sostén económico de los colegios de la Orden en la capital cordobesa. El 15 de enero de 1618, el R.P. Pedro de Orate, provincial de la Orden, compra al Alférez Real Don. Gaspar de Quevedo, las tierras en el lugar que los aborígenes denominaban \"Guanusacate\". Ya en la escritura de compra se cita la existencia de veinte mil cepas de viña, algunas construcciones y un molino. La producción de Jesús María, se orientó así, principalmente a la elaboración de vino. También, se realizaban otras actividades como: crianza de ganado vacuno, tejido en telares de cordellate, fabricación y jabón y velas y trabajos en la huerta de la que se obtenían: manzanas, granadas, duraznos, cebada, azafrán, garbanzos, lentejas, habas y arvejas. \r\n\r\nAdemás, de sementeras de trigo, maíz, Durante el primer tercio del siglo XVIII, comenzó la construcción de los sectores más destacados del edificio. Según cita un documento de la época inauguraron la nueva bodega, el refectorio y ocho cuartos. \r\n\r\nFrente al presbiterio se encuentra la cúpula en cuyos entablamentos hay cuatro ángeles de fisonomía nativa y cuatro cabezas con tocados aborígenes. Los detalles ornamentales del frente del Templo no estaban terminados antes de la expulsión. En 1767, con la Real Pragmática de Carlos III, rey de España, los jesuitas son expulsados de los reinos de España, Portugal y Nápoles y todas sus posesiones pasan a ser administradas por la denominada Junta de Temporalidades. Desde la expulsión de la Compañía hasta 1775, la estancia fue administrada por la citada Junta. Por último, sale a remate en tres oportunidades y los interesados forman parte de la familia de don Félix Correas, originaria de Mendoza, quienes finalmente la compran. \r\n\r\n','El museo \r\n\r\nEn 1941, la Comisión Nacional de Museos y Monumentos y Lugares Históricos declara a la antigua estancia como Monumento Histórico Nacional (Decreto Nº 90732). Posteriormente, comienzan las tareas de restauración y puesta en funcionamiento del edificio y el 18 de mayo de 1946 se instala en su interior el Museo. Este tiene, actualmente, dieciocho salas de exposición distribuidas entre planta baja y primer piso. En la exhibición permanente de sus colecciones de gran importancia histórica y artística, se destacan: la de Arte Sacro Colonial de los siglos XVII Y XVIII y el material de Arqueología y Etnografía del Noroeste y Centro Argentino con colecciones que van desde el año 300 a. C. hasta el siglo XVI. \r\n\r\nTambién se pueden ver; Grabados Europeos, monedas, medallas, Mobiliario civil y religioso europeo y americano, porcelanas y cerámicas europeas, todos de diversos períodos. En esta Institución se realizan durante el año: conciertos, conferencias, talleres, seminarios, presentaciones de libros y exposiciones temporarias. Desde el año 2000, integra con la Manzana jesuítica de Córdoba y el conjunto de estancias de la provincia, la Lista de Patrimonio Mundial de la UNESCO. ',NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MM','Museo Mitre','Estatal',NULL,'54 11 4394-8240/7659','museomitre@gov.com.ar','http://www.museomitre.gov.ar/','Las gestiones del diputado nacional Dr. Manuel Carlés hicieron posible que unos meses después de la muerte de Bartolomé Mitre se sancionase la ley Nº 4943 por la cual se autorizaba al Poder Ejecutivo comprar el inmueble donde vivió el patricio para formar el Museo Mitre (26 de junio de 1906). \r\n\r\nComo primer director fue nombrado Alejandro Rosa, quien había compartido con Mitre sus pasiones por los estudios históricos y numismáticos, plasmadas en su momento con la fundación de la Junta de Historia y Numismática Americana, institución que en 1938 cambió su nombre y hoy es la reconocida Academia Nacional de la Historia. \r\n\r\nGracias a la celeridad en las tareas de organizar el museo sus puertas se abrieron al público el 3 de junio de 1907. Un primer objetivo fue editar numerosos volúmenes de documentación histórica y el Catálogo de lenguas americanas (archivo en PDF). \r\n\r\nTras el deceso de Rosa, fue nombrado director honorario un nieto de Mitre, Luis Domingo Mitre. En dicho período fue muy importante la acción de Rómulo Zabala quien hizo editar los catálogos del museo y de numismática, prosiguiendo la transcripción y publicación de los papeles del archivo colonial. Cabe destacar que el 21 de mayo de 1942 el museo fue declarado monumento histórico nacional. \r\n\r\nPosteriormente le cupo a Juan Angel Fariní la conducción de la institución en dos períodos (1948-1956 y 1966-1973), época durante la cual se recuperaron elementos originales de la casa, como así también la adquisición de óleos y retratos de Mitre. Entre 1956 y 1966 otro nieto del patricio, Jorge Adolfo Mitre, ejerció como director honorario, procediéndose a realizar numerosas refacciones del edificio. \r\n\r\nA partir de 1973 ocupó la dirección del museo un bisnieto del general, Jorge Carlos Mitre, incrementándose sensiblemente su patrimonio gracias a un gran número de donaciones y adquisiciones. \r\n\r\nDesde 1978 el museo cuenta con la inestimable colaboración de la Asociación de Amigos del Museo cuya presidente es la señora Magdalena Sofía Narvaja.\r\n','El Museo Mitre es una casa museo de gran valor patrimonial en el sentido histórico y cultural.\r\n\r\nEsta fue la casa que habitó el General Bartolomé Mitre y su familia desde 1859 hasta su muerte en 1906. Al finalizar su presidencia (1862-1868), Mitre, quien alquilaba la casa, no disponía de fondos para comprarla, por lo cual se constituyó una comisión popular que se la obsequió en agradecimiento por los servicios prestados a la patria. En 1907 la casa fue comprada por el Estado Nacional y luego decretada monumento histórico en 1942. Por su parte, el general Mitre donó todo el patrimonio que la misma contenía.\r\n\r\nPoco antes de fallecer el general Mitre fue su voluntad, expresada verbalmente a sus herederos, que donaran al Estado sus valiosas colecciones, biblioteca, archivo, monetario americano y muebles.\r\nEsta donación se llevó a cabo en la Escribanía General de Gobierno y copia de la misma fue remitida años después al museo por el que fue escribano Mayor de la Nación, don Jorge Garrido.\r\n\r\nEl diputado Manuel Carlés presentaba en el Congreso de la Nación un proyecto propiciando la compra de la casa por parte del Estado a fin de convertirla en museo público. El proyecto fue aprobado por unanimidad y se convirtió en ley el 26 de junio de 1907. Dicha casa fue adquirida por ley de la Nación Nº4943 del 27 de junio del mismo año, de manera que el museo inaugurado el 3 de junio pasó a ser propiedad del Estado y se convirtió en monumento nacional el 21 de mayo de 1942.\r\n\r\nEs un museo de ambientación histórica, representativo de costumbres y modos de vida de la sociedad argentina en la segunda mitad del siglo XIX y un importante centro bibliográfico, documental y numismático, que a través de más de cien años de vida ha sido de incalculable valor para el historiador investigador y un importante centro de estudios. \r\n\r\nDe esos bienes, los más utilizados hoy en día por estudiosos e investigadores de todo el mundo son la Biblioteca Americana (que representa la historia intelectual de un hombre del siglo XIX) y el archivo histórico, con cerca de 53.000 documentos (que incluyen cartas, proclamas, medallas y monedas) que nos legó Mitre.\r\n\r\nEn el Museo Mitre tenemos el testimonio vívido de una casa de origen colonial de 1785 con tres patios (uno de los cuales fue convertido en auditorio en 1937) y un agregado de 1883 realizado por el hijo de Bartolomé Mitre --el ingeniero Emilio Mitre--   en “los altos” de la casa, donde se ubicó a la biblioteca americana y lo que hoy es la sala Moores y la dirección. Allí vivió Emilio con su familia y este sector responde al estilo art nouveau.\r\n\r\nLa casa contiene en general mobiliario en diferentes estilos y es así como comienza a cumplir con un aspecto de su función cultural como representación de una parte de la historia del mueble, de la pintura, de la arquitectura, etc.\r\nHoy en día una casa museo puede verse no sólo como una representación visual del pasado sino como un abanico de posibilidades y potencialidades que se abre gracias a la tecnología, la cual permite el acceso al patrimonio no sólo para el visitante real sino también el acceso virtual del visitante remoto.\r\n\r\nSurge, entonces, de esta casa museo, no sólo el modo de vida y la historia de las ideas de uno de los constructores de la argentina moderna y padre de la historiografía argentina, sino también toda una tarea derivada del propio patrimonio en las diversas áreas de investigación y desarrollo de ciencias auxiliares de la historia, tales como la archivística, la conservación, la numismática, la medallística y la estadística como fuente de conocimiento histórico.\r\n',NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MNAD','Museo Nacional de Arte Decorativo','Estatal',NULL,'(54-11) 4801-8248 / 4802-6606 / 4806-8306','museo@mnad.org','http://www.mnad.org/','El Museo Nacional de Arte Decorativo fue creado en 1937 por Ley 12351 del Gobierno Nacional que determinó la adquisición de la residencia y la colección de arte de Josefina de Alvear y  Matías Errázuriz. El edificio, excelente ejemplo del estilo ecléctico francés de gran auge en la ciudad de Buenos Aires a principios del siglo XX,  los muebles de época,   las pinturas, las esculturas y los objetos de arte decorativo justificaron plenamente esa inversión para brindar a la comunidad un nuevo museo.\r\n\r\nEl arquitecto francés René Sergent (1865 -1927) realizó el proyecto de la residencia en 1911.  En ese año la Sociedad Central de Arquitectos Franceses le había otorgado la Gran Medalla de la Arquitectura Privada destacando las cualidades de su obra sobria y elegante dentro de un contexto ecléctico.  Sergent había estudiado en la Escuela Especial de Arquitectura de París.  En 1884 entró en el estudio de Ernest Sanson considerado el mejor arquitecto diseñador de viviendas privadas.  Bajo su dirección Sergent se apasionó por las obras de algunos arquitectos franceses de los siglos XVII y XVIII.\r\n\r\nHacia 1899 estableció su propio estudio. Su arquitectura ponía el acento en el confort y en la comodidad  de la distribución y se hizo célebre por  la construcción de residencias particulares de estilo neoclásico.   \r\n\r\nEn los primeros quince años del siglo XX trabajó en París, Buenos Aires y Nueva York; en Londres realizó la ampliación y decoración de los hoteles Claridge y Savoy.  En París proyectó las mansiones del diseñador de modas Worth,  del empresario Otto Bemberg y de los anticuarios Duveen Brothers a las que se agrega el gran hotel de pasajeros Trianon Palace en Versailles y entre 1912 y 1914 la construcción de la residencia del Conde Moisés de Camondo, hoy destinada a Museo de Artes Decorativas.\r\n\r\nPara la sociedad porteña, además de la mansión de los Errázuriz-Alvear, proyectó las residencias de la familia Atucha, de los Bosch-Alvear, la mansión Unzué, el Palacio Sans Souci en San Fernando y el Hogar Luis María Saavedra.\r\n\r\nRené Sergent trabajaba en equipo con un selecto grupo de decoradores especialistas en interiores y jardines. Para la residencia Errázuriz-Alvear los elegidos fueron H. Nelson, Georges Hoentschel,  André Carlhian y el paisajista Achille Duchêne.   El Palacio Errázuriz Alvear fue construido entre 1911 y 1917 con la dirección de obra de los arquitectos  locales Eduardo M. Lanús y Pablo Hary.\r\n\r\nTodos los materiales, salvo la mampostería gruesa, fueron traídos de Europa. Los revestimientos de madera, espejos, mármoles, carpinterías, fallebas, molduras, llegaron preparados para su directa colocación en obra y para algunas tareas específicas, como la realización de estucos,  vinieron artesanos europeos.\r\n\r\nEl aspecto externo del edificio es sobrio e imponente, inspirado en el neoclasicismo del siglo XVIII, en especial en las obras de Jacques A. Gabriel artista de la corte de Luis XV.\r\nLos cuatro niveles son visibles desde el exterior: el subsuelo tiene ventanas que se abren en el basamento; la planta principal está comunicada con el jardín y la terraza por puertas en arco de medio punto; encima de éstas se abren  las ventanas que corresponden a los aposentos; ya en el último nivel, detrás de la balaustrada, se ven las lucarnas de ventilación de las áreas de servicio que ocupan la mansarda.\r\n\r\nLos salones de la planta principal, destinados a las recepciones, fueron decorados en diversos estilos franceses de los siglos XVII y XVIII excepto el Gran Hall inspirado en los grandes salones característicos de la Inglaterra del siglo XVI en la época de la dinastía Tudor.\r\n\r\n.En los departamentos privados del primer piso es evidente también el gusto por la decoración francesa en los estilos Luis XV, Luis XVI, Directorio e Imperio; la excepción es la sala Art Déco decorada por el artista catalán José María Sert. \r\n\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MNAO','Museo Nacional de Arte Oriental','Estatal',NULL,'(54 11) 4801-5988','mnao@mnao.gov.ar','http://mnaobuenosaires.blogspot.com/','El objetivo del Museo Nacional de Arte Oriental es la difusión del conocimiento de las culturas asiáticas, africanas y de oceanía entre el público, con el deseo de fomentar la comprensión entre los pueblos del mundo, a través de exposiciones de variada temática, conferencias, cursos, talleres, seminarios, visitas guiadas, espectáculos de música y danzas, proyección de documentales, etcétera. \r\n\r\nDesde su creación, el 14 de julio de 1965, formó su patrimonio artístico sobre la base de compras directas, legados y donaciones de colecciones particulares y de embajadas de países asiáticos acreditados en nuestro país totalizando en la actualidad un patrimonio artístico de más de 3000 piezas. El Museo posee una biblioteca especializada consistente en 1500 libros, una hemeroteca con 2500 revistas y publicaciones periódicas y una videoteca con más de 150 documentales. \r\n\r\nEn el mes de julio de 1996 fueron concedidos en forma definitiva, para funcionamiento de la nueva sede del Museo, los edificios ubicados en la calle Riobamba 983-993 de esta ciudad, debiendo los mismos ser reciclados para tal fin. La apertura de los pliegos para la adaptación y adecuación edilicia se realizó el 14 de diciembre de 1998.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MNBA','Museo Nacional de Bellas Artes','Estatal',NULL,'5288-9900','prensa@mnba.org.ar / edumnba@yahoo.com.ar / biblio','http://www.mnba.org.ar/','El Museo Nacional de Bellas Artes (MNBA) de Argentina está ubicado en la Avenida del Libertador 1473 de la Ciudad de Buenos Aires. Ocupa actualmente la antigua Casa de Bombas, edificio perteneciente a los establecimientos Recoleta de Obras Sanitarias de la Nación (1870).\r\n\r\nLa primera sede del MNBA se estableció en las  galerías del Bon Marché, de la calle Florida, un edificio construido para albergar la tienda de origen francés y donde actualmente se sitúan las Galerías Pacífico.\r\n\r\nEduardo Schiaffino, pintor y crítico de arte, fue su primer director. El MNBA fue  acrecentando su acervo gracias a donaciones y compras. Pronto su espacio en el  Bon Marché resultó insuficiente. \r\n\r\nEn 1909, la situación se hizo crítica. El patrimonio inicial se había multiplicado por veinte. El Museo se trasladó entonces al Pabellón Argentino, un edificio típico de la arquitectura de hierro y cristal construido para representar a la Argentina en la Exposición Universal de París de 1889, erigido en la Plaza San Martín. Las obras permanecieron allí durante dos décadas, antes de ser instaladas en  su sede definitiva de la Avenida del  Libertador.\r\n\r\nEl edificio fue  reformado por el arquitecto Alejandro Bustillo, quien lo adaptó a las necesidades de un museo. Bustillo conservó el frente original y proyectó un nuevo pórtico. En el interior la remodelación se realizó de acuerdo con un modelo de exhibición moderno, de salas espaciosas, correctamente iluminadas y paredes lisas con el propósito de contribuir a una lectura directa de las obras expuestas. En su transformación, el arquitecto concibió para el MNBA un itinerario espacial ordenado, para que el visitante disfrute de una contemplación atractiva e instructiva. \r\n\r\nLa actual sede se inauguró el 23 de mayo de 1933, con la presencia del Presidente de la Nación, Agustín P. Justo. La Asociación de Amigos había sido creada el 22 de octubre de 1931.\r\n\r\nDesde su inauguración en la sede actual, el museo fue sometido a varias reformas. En 1961 se le adicionó un Pabellón para  exhibir las muestras temporarias. \r\n\r\nEn 1980 se inauguró en el primer piso una sala de 96 metros de largo por 16 de ancho,   que actualmente alberga la colección permanente de arte argentino del siglo XX. Con la reciente inauguración de la Sala de Arte Precolombino Andino, concluyó la reforma de la  planta dedicada al arte argentino y del continente. En esta planta funciona,  además,  el Auditorio en el que se realizan numerosas actividades artísticas,  de extensión cultural y educativa.\r\n\r\nEn 1984 finalizó la ampliación del segundo piso. Allí se encuentran la dirección, los departamentos técnicos y administrativos y dos terrazas de esculturas al aire libre. También en este piso se destinó, en febrero del 2004, una sala permanente para exhibiciones fotográficas, tanto del patrimonio como temporarias. \r\n\r\nLa Planta Baja, de 2.000 m2, está dedicada principalmente a mostrar las colecciones de  arte internacional desde la Edad Media hasta el siglo XX. Una  importante biblioteca especializada en arte, cuyo patrimonio actual es de más de 150.000 piezas, completa la planta que se proyecta sobre los parques linderos. En la recepción, una librería artística  propone a los visitantes bibliografía actualizada mientras que la boutique de la Asociación Amigos ofrece los catálogos de las exposiciones, reproducciones de obras y souvenirs de diseño. \r\n\r\nRecientemente se ha incorporado el alquiler de audioguías en castellano e inglés. A través de cómodas  unidades de audio digital que los orienta y acompaña,  los visitantes pueden recorrer la muestra permanente del museo siguiendo un orden cronológico y estético.\r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MNG','Museo Nacional del Grabado','Estatal',NULL,'(011) 4802-3295','museodelgrabado@yahoo.com.ar','http://www.cultura.gov.ar/direcciones/?info=organi','El Museo del Grabado nació en el año 1960 por iniciativa privada del Profesor Oscar Carlos Pecora, con la intención de crear el primer museo dedicado en exclusividad a esta temática. Años más tarde la importante colección privada fue generosamente donada al Estado Nacional y en el año 1983 se transformó en el Museo Nacional del Grabado, funcionando por entonces en diferentes sedes en la ciudad de Buenos Aires, hasta su traslado a este edificio. \r\n\r\nLa casa se halla ubicada en el casco histórico de la ciudad de Buenos Aires y es una típica edificación de fines del siglo XIX, que ha tenido varios destinos, razón por la cual sufrió numerosas remodelaciones que fueron desdibujando su estilo original. Desde el año 1993 es sede del Museo y desde entonces alberga en su interior, tres salas de exhibiciones, un espacio de biblioteca y sala de lectores, un taller de conservación y restauración de papel, un taller de grabado, oficinas técnicas, la tienda del Museo y otras instalaciones. Cuenta también con un magnífico patio ubicado al contrafrente del terreno que se destina a diferentes actividades culturales. \r\n\r\nEl Museo es un ámbito de difusión de la obra gráfica, y ofrece sus servicios a la comunidad a través de muestras, conferencias, visitas guiadas, cursos, seminarios, talleres sobre distintas técnicas de grabado y otras actividades relacionadas con el arte del grabado en general. \r\n\r\nSu acervo está compuesto por obras de los más importantes artistas nacionales y extranjeros del siglo XX, y el mismo se traduce en un patrimonio que comprende aproximadamente once mil piezas museográficas, que incluyen grabados originales sueltos y en carpetas, ediciones con grabados originales y libros de artistas, como así también una importante colección de matrices; tacos xilográficos, planchas metálicas, piedras litográficas, y diversas herramientas de trabajo. \r\n\r\nLa misión del museo es atender la custodia, conservación, investigación, difusión, promoción y desarrollo del arte del grabado. \r\n',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MR','Museo Roca','Estatal',NULL,'(54-11) 4803-2798','museoroca@hotmail.com','http://www.museoroca.gov.ar/','El General Julio A. Roca, estadista y militar, representante del orden conservador, incorporó los amplios territorios patagónicos a la administración nacional y afianzó la modernización y economía al promover la inmigración, sancionar la Ley de Educación Común y la Ley del Registro Civil e integrar la economía agropecuaria al orden internacional.\r\n\r\nJulio Argentino Roca (1843-1914)\r\n\r\nFue elegido en dos oportunidades Presidente de la Nación Argentina (1880-1886 y 1898-1904). Polémico árbitro de la escena política durante tres décadas, afianzó los límites territoriales con la República de Chile y propuso en la política internacional la integración regional.\r\n\r\nDebido a la excelencia de gestión de los intendentes de Buenos Aires durante ambas presidencias, se transformó la ciudad en Gran Capital de la modernización luego de su federalización en 1880. ',NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/MRPJAT','Museo Regional de Pintura \"José A. Terry\"','Estatal',NULL,'54 (388) 495-5005','museoterry@tilcanet.com.ar','http://www.cultura.gov.ar/direcciones/?info=organi','El Museo Regional de Pintura \"JOSE ANTONIO TERRY\" fue creado el 13 de Julio de 1956, por Decreto PRESIDENCIAL Nº 12.637, por el que el Estado Nacional adquiere la propiedad y las Obras de Arte del Pintor José Antonio Terry y se habilita en forma precaria en Enero del año 1964, siendo a poco tiempo clausurado para llevar a cabo tareas de mantenimiento del edificio adecuándolo a la nueva función, sin que por esta circunstancia pierda su tradicional línea arquitectónica, reabriendo definitivamente sus puertas al público en Acto especial el 31 de Julio de 1966 \r\n','Descripción de las salas\r\n\r\nEn el primer patio se encuentra las salas I y II en la que se exponen obras del Arista José Antonio Terry, realizadas en Tilcara. \r\n\r\nSala I\r\n\r\nTiene una dimensión:3,76 x 4,97 mts., vemos una ventana que da sobre la calle Rivadavia, techo con cielo raso de madera de cardón, y las obras del Pintor Terry En semana Santa en Tilcara realizada el año 1936, Juancito en Tilcara año 1928, La Chichería año 1914 en Tilcara, El Tuerto de pucara año 1929, como si también el Autorretrato del Pintor en Buenos Aires 1945 y retrato de la esposa AMALIA en Buenos Aires 1929 y de las hermanas Leonor Y Sotera Terry, también vemos retratada la modelo del pintor Nita y Magdalena óleo sobre tela, también se exhiben los muebles coloniales que pertenecen a la casa.\r\n\r\nSala II \r\n\r\nTiene una dimensión de 3,35 x 6,06 mts. , nos muestra las obras realizadas por José Antonio Terry en Buenos Aires y en otros países como OLE TU MARE! en Madrid en 1908, DESNUDO DE VIEJO pintado en Santiago de Chile en 1902 , DESNUDO DE MUJER en Buenos Aires en 1903 , HOGAR SAGRADO en París en 1927 , SAN PEDRO en España en 1910, SAN SEBASTIÁN en España 1924, ULTIMO PARA EL ESTRIBO en Buenos Aires 1944, EL ULTIMO CHANGADOR en el puerto de Buenos Aires en 1941 Y TOMANDO MATE en Buenos Aires 1937.- En el primer patio hacia un costado opuesto y muy bien ubicada vemos una escalera que nos lleva a la planta alta donde encontramos un amplio Atelier con la siguiente medidas: 8,30 x 5,10 mts, lugar donde Terry proyectaba y realizaba sus grandes obras, ya en el vemos un gran ventanal otorgando al pintor la cantidad suficiente de luz para su trabajo. \r\n\r\nEn el aspecto arquitectónico se observa en la pared del fondo y muy bien ubicada una chimenea a leña de señorial estilo, y sobre la pestaña superior que posee la misma están los retratos de su padre Dr. José Antonio Terry, su madre LEONOR QUIRNO COSTA DE TERRY y su padre Dr. José Antonio Terry, hacia el centro del atelier se puede ver una mesita y en ella la valija de campo que usara el pintor como así también la paleta y los pinceles. En el caballete mayor esta una de sus últimas obras inconclusas, retrato de su señora madre doña LEONOR QUIRNO COSTA DE TERRY y en el caballete tijera una fotografía de la obra LA ENANA CHEPA y SU CANTARO que se expuso en Paris en el año 1924 y el gobierno Francés adquiere la obra para el Museo Luxemburgo. \r\n\r\nEntre los objetos de su pertenencia están: un viejo catre de tientos, el primero usado por él cundo vino a Tilcara año 1911, dos arcones de cuero primitivos, un virque y un gran cántaro modelo de su obra LA ENANA CHEPA Y SU CANTARO.- En las paredes del Atelier están colocadas las obras: ESPERANDO AL GAUCHO, LA VENUS CRIOLLA, ULTIMO PARA EL ESTRIBO, PISANDO MAIZ, NEGRITA CEBANDO MATE, ANUNCIACIÓN DE Maria, obras pintadas en Buenos Aires, dibujos y croquis del pintor Terry, también las obras de las hermanas Leonor y Sotera como naturaleza muerta, patios del museo Terry. En el segundo patio encontramos las salas IV con una dimensión de 6,73 x 3,37 mts. \r\n\r\nSala Atelier \r\n\r\nSobre la pestaña superior de la chimenea vemos los retratos del padre del pintor y de la esposa abajo en el hogar una obra titulada naturaleza muerta de autoría Leonor Terry, hermana del Pintor hacia el centro se puede ver una mesita con la valija de campo que utilizaba el pintor Terry. En el caballete mayor está presentada la ú.tima obra inconclusa del pintor Terry \"Retrato de su señora madre \" Desde otro angulo de vista de la sala Atelier nos muestra las siguientes obras de arte: \" Venus Criolla\" \"Figura de mujer Tilcareña\" (Obra inconclusa). \r\n\r\nSala Félix \r\n\r\nLeonardo Pereyra tiene las siguientes medidas: 5,10 x 4,97 mts. en estas salas se realizan exposiciones temporarias de Artistas Argentinos, artesanías, fotografías y también están destinadas para exhibir las obras de la Pinacoteca del Museo Terry.- En el Tercer patio están las Sala Fortabat, taller de conservación y preservación de los objetos museológicos y un salón conferencias con las siguientes medidas: 10,20 x 6,83 mts. y 7,00 x 2,80 mts., estas salas también están destinadas a la promoción de las actividades artístico - plástica a través de exposiciones,publicaciones, concursos y demás actos de difusión y proyección cultural de la zona y de otros lugares.- Sala CONFERENCIAS Medardo Pantoja sus medidas son 20,80 mts, x 6,83 mts., cuenta con 180 butacas y un escenario y esta destinada la realización de teatro, conferencias, recitales etc. \r\n\r\nEl Museo Regional de Pintura \"José Antonio Terry\" cuenta con el ÁREA EDUCACIÓN Y EXTENSIÓN CULTURAL, que es la encargada de la diagramación, coordinación, realización y presentación de las Actividades Culturales. Cuenta para ello con tres Salas de Exposiciones Temporarias (Sala IV, Sala \"Félix Leonardo Pereyra\" y Sala \"Amalia Lacroze de Fortabat y Alfredo Fortabat\") en las que se realizan exposiciones de Pintura, Artesanías, Fotografías y todas las expresiones que el hombre pueda demostrar a través de sus habilidades culturales; y con una Sala de Conferencias \"Medardo Pantoja\" en la que se proyectan Audiovisuales y Videos, se presentan Obras de Teatro, Recitales Folklóricos, Conferencias y se dictan Cursos. \r\n\r\nLa labor Cultural que realiza este Museo desde sus inicio ha favorecido notablemente a promocionar, promover y exaltar la Plástica y la Cultura Argentina desde este lugar tan pintoresco de la Quebrada de Humahuaca en la que nos sentimos inmersos por el color de su paisaje. Esta Institución Museológica cede, además, de la realización de sus Actividades específicas, estas Salas a otras Instituciones del Medio (Hospitales, Escuelas, Asociaciones Civiles entre otras) para la realización de Eventos y Actos con la sola presentación de una nota solicitando con la debida antelación la Sala para una buena organización y diagramación. Durante el año se realizan las Actividades Culturales mencionadas; observando que durante el mes de enero (Enero Tilcareño), Semana Santa y el Julio Cultural son los períodos de mayor Actividad por la afluencia del turismo y de las vacaciones, época de mayor participación de la Comunidad de Tilcara, sin dejar de mencionar los otros meses del año que también se programan dichas actividades.\r\n',NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL),
 ('AR/PN/SC/DNPM/Prueba','Pruebita','Empresarial',NULL,'123456','karinapatitasaez@hotmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'2011-06-22 11:59:44','camilo');
INSERT INTO `instituciones` (`codigo_identificacion`,`formas_conocidas_nombre`,`tipo_institucion`,`tipo_entidad`,`telefono_de_contacto`,`email`,`www`,`historia`,`estructura_organica`,`politicas_ingresos`,`proyecto_nuevos_ingresos`,`instalaciones_archivo`,`fondos_agrupaciones`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`dias_apertura`,`horaios_apertura`,`fechas_anuales_cierre`,`codigo_interno`,`estado`,`fecha_ultima_modificacion`,`usuario`) VALUES 
 ('AR/PN/SC/DNPM/PSJ','Palacio San José','Estatal',NULL,'(3442) 432620','psjmuseourquiza@hotmail.com','http://www.palaciosanjose.com.ar/','Justo José de Urquiza\r\n 18 de octubre de 1801 – 11 de abril de 1870\r\n Caudillo y Gobernador entrerriano. Empresario y estanciero.\r\n Primer Presidente Constitucional\r\n\r\n1801 Nace en las cercanías de Concepción del Uruguay\r\n\r\n 1819 Inicia su actividad comercial que con el tiempo lo convertirá en propietario de tierras y fuerte empresario. \r\n\r\n1826 Elegido Diputado Provincial, enarbola las banderas del federalismo en la senda de Ramírez y Artigas. \r\n\r\n1830 En su carrera militar integra el ejército entrerriano a las órdenes del Gdor Echagüe. Oficial destacado, Comandante Departamental, participa hacia fines de la década en las filas defensoras de la Confederación Argentina y el Pacto Federal ante las invasiones unitarias apoyadas por franceses e ingleses.\r\n\r\n 1842 Asume como Gobernador de Entre Ríos siendo reelecto en 1845, 1849 y 1853 en forma consecutiva, consolidando su figura de caudillo entrerriano. Al frente del Ejército Confederal continúa la campaña militar expulsando a los invasores de Entre Ríos en la batalla de Arroyo Grande (1842). Realiza la campaña en la Banda Oriental triunfando en India Muerta (1845). En Laguna Limpia (1846) y en Vences (1847), derrota a las fuerzas correntinas. \r\n\r\n1849 Finalizada la lucha, a través de su tarea de gobierno, Entre Ríos comienza un período de desarrollo muy fuerte y ordenado. \r\n\r\n1851 Diferencias con J. M. Rosas provocan el Pronunciamiento (1 de mayo), liderando el movimiento para organizar constitucionalmente la Nación. Forma el Ejército Grande.\r\n\r\n 1852 El 3 de febrero se enfrenta con Rosas y triunfa en la Batalla de Caseros. Firmado el Acuerdo de San Nicolás, asume como Director Provisorio de la Confederación Argentina, convocando al Congreso Constituyente. Se forma el Estado de Buenos Aires, al separarse esta provincia de la Confederación. \r\n\r\n1853 El 1 de mayo se sanciona la Constitución Nacional en Santa Fe.\r\n\r\n 1854 Es elegido Primer Presidente Constitucional de la Confederación Argentina para el período 1854-1860, con capital en Paraná. Entre Ríos es federalizada. \r\n\r\n1859 Triunfa en la batalla de Cepeda, derrotando a Mitre. Firma el Pacto de San José de Flores. 1860 Reorganizada la Provincia de Entre Ríos, es electo Gobernador para el período \r\n\r\n1860 – 1864. Derqui es electo Presidente. Mitre es recibido en el Palacio San José. \r\n\r\n1861 Enfrentados nuevamente con Buenos Aires, retira el Ejército Confederal a sus órdenes de la Batalla de Pavón. Buenos Aires se hace cargo del Ejecutivo Nacional. Finaliza la experiencia de la Confederación Argentina con capital en Paraná. \r\n\r\n1868 Inicia un nuevo período como Gobernador de Entre Ríos. \r\n\r\n1870 El 3 de febrero recibe la visita del Presidente Sarmiento. El 11 de abril muere asesinado en esta Casa, al estallar el movimiento revolucionario jordanista.','Estructura Orgánica funcional de la Institución.','Historia de la formación del Archivo.','Proyectos para nuevos Ingrsos.','Instalaciones del Archivo.','1','Arancelado - Libre',NULL,NULL,'Lunes a Viernes 08:00 a 19:00 horas. Sábados, Domingos y Feriados 09:00 a 18:00 horas. Duración visita guiada: aproximadamente 80 minutos.',NULL,'2011',NULL,5,NULL,NULL);
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
 ('AR/PN/SC/DNPM/CNB','Pendiente',NULL,'2011-05-29 06:29:15','camilo'),
 ('AR/PN/SC/DNPM/CNML','Pendiente',NULL,'2011-05-30 21:58:44','camilo'),
 ('AR/PN/SC/DNPM/CNS','Pendiente',NULL,'2011-05-24 04:45:52','camilo'),
 ('AR/PN/SC/DNPM/CNSDR','Completo',NULL,'2011-06-15 16:06:51','camilo'),
 ('AR/PN/SC/DNPM/CNSDR','Completo',NULL,'2011-06-15 16:06:54','camilo'),
 ('AR/PN/SC/DNPM/CNSDR','Completo',NULL,'2011-06-17 10:48:28',NULL),
 ('AR/PN/SC/DNPM/CNSDR','Completo',NULL,'2011-06-21 12:42:34',NULL),
 ('AR/PN/SC/DNPM/CNSDR','Inicio',NULL,'2011-06-15 16:03:45','camilo'),
 ('AR/PN/SC/DNPM/CNSDR','No Vigente',NULL,'2011-06-21 12:46:07',NULL),
 ('AR/PN/SC/DNPM/CNSDR','Pendiente',NULL,'2011-06-15 16:04:32','camilo'),
 ('AR/PN/SC/DNPM/CNSDR','Vigente',NULL,'2011-06-21 12:41:57',NULL),
 ('AR/PN/SC/DNPM/INAPL','Pendiente',NULL,'2011-05-30 21:33:35','camilo'),
 ('AR/PN/SC/DNPM/INB','Pendiente',NULL,'2011-05-30 22:27:33','camilo'),
 ('AR/PN/SC/DNPM/INET','Pendiente',NULL,'2011-05-29 09:38:13','camilo'),
 ('AR/PN/SC/DNPM/INJDP','Pendiente',NULL,'2011-05-29 11:10:54','camilo'),
 ('AR/PN/SC/DNPM/INMCV','Pendiente',NULL,'2011-05-29 10:39:51','camilo'),
 ('AR/PN/SC/DNPM/INS','Pendiente',NULL,'2011-05-30 22:41:40','camilo'),
 ('AR/PN/SC/DNPM/MCASN','Completo',NULL,'2011-06-03 00:36:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN','Pendiente',NULL,'2011-05-24 10:24:55','camilo'),
 ('AR/PN/SC/DNPM/MCASN','Vigente',NULL,'2011-06-08 14:54:30',NULL),
 ('AR/PN/SC/DNPM/MCHI','Pendiente',NULL,'2011-05-30 04:04:55','camilo'),
 ('AR/PN/SC/DNPM/MCY','Pendiente',NULL,'2011-05-29 11:19:03','camilo'),
 ('AR/PN/SC/DNPM/ME','Pendiente',NULL,'2011-05-29 12:33:59','camilo'),
 ('AR/PN/SC/DNPM/MEJAGYCVL','Pendiente',NULL,'2011-05-30 04:24:42','camilo'),
 ('AR/PN/SC/DNPM/MHDN','Pendiente',NULL,'2011-05-30 03:39:41','camilo'),
 ('AR/PN/SC/DNPM/MHN','Pendiente',NULL,'2011-05-29 13:06:01','camilo'),
 ('AR/PN/SC/DNPM/MHNCYRM','Pendiente',NULL,'2011-05-29 13:29:14','camilo'),
 ('AR/PN/SC/DNPM/MHS','Pendiente',NULL,'2011-05-30 03:17:48','camilo'),
 ('AR/PN/SC/DNPM/MJN','Pendiente',NULL,'2011-05-29 06:52:48','camilo'),
 ('AR/PN/SC/DNPM/MM','Pendiente',NULL,'2011-05-29 14:27:36','camilo'),
 ('AR/PN/SC/DNPM/MNAD','Pendiente',NULL,'2011-05-29 14:20:12','camilo'),
 ('AR/PN/SC/DNPM/MNAO','Pendiente',NULL,'2011-05-29 13:36:04','camilo'),
 ('AR/PN/SC/DNPM/MNBA','Pendiente',NULL,'2011-05-29 14:11:30','camilo'),
 ('AR/PN/SC/DNPM/MNG','Pendiente',NULL,'2011-05-29 13:44:26','camilo'),
 ('AR/PN/SC/DNPM/MR','Pendiente',NULL,'2011-05-29 14:01:26','camilo'),
 ('AR/PN/SC/DNPM/MRPJAT','Pendiente',NULL,'2011-05-29 13:49:46','camilo'),
 ('AR/PN/SC/DNPM/Prueba','Inicio',NULL,'2011-06-22 11:52:32','camilo'),
 ('AR/PN/SC/DNPM/Prueba','Pendiente',NULL,'2011-06-22 11:52:36','camilo'),
 ('AR/PN/SC/DNPM/PSJ','Completo',NULL,'2011-05-23 11:34:11','camilo'),
 ('AR/PN/SC/DNPM/PSJ','Pendiente',NULL,'2011-05-23 10:58:39','camilo');
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
 ('AR/PN/SC/DNPM/CNDS','Casanova, Casandra'),
 ('AR/PN/SC/DNPM/ET','López, Gustavo'),
 ('AR/PN/SC/DNPM/PSJ','Díaz, Juana');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_soporte`
--

/*!40000 ALTER TABLE `material_soporte` DISABLE KEYS */;
INSERT INTO `material_soporte` (`codigo_material_soporte`,`material`,`tipo_diplomatico`) VALUES 
 (3,'Características superficiales',10),
 (1,'Clase de papel',11),
 (2,'Color actual',11),
 (5,'Marcas de agua',8),
 (4,'Método de fabricación',11);
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
 ('Canson'),
 ('Cartón'),
 ('DVD'),
 ('Papel'),
 ('Papel continuo'),
 ('Papel de calco'),
 ('Papel de plano'),
 ('Papel vegetal');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medios`
--

/*!40000 ALTER TABLE `medios` DISABLE KEYS */;
INSERT INTO `medios` (`codigo_medio`,`medio`,`tipo_diplomatico`) VALUES 
 (1,'Emulsión',10),
 (2,'Tinta al agua',8),
 (3,'Tinta ferrogálica',11);
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
  CONSTRAINT `fk_niveles_tipos_niveles1` FOREIGN KEY (`tipo_nivel`) REFERENCES `tipos_niveles` (`codigo_tipo_nivel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_niveles_instituciones1` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_niveles_norma_legal1` FOREIGN KEY (`normativa_legal_baja`) REFERENCES `norma_legal` (`norma_legal`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `niveles`
--

/*!40000 ALTER TABLE `niveles` DISABLE KEYS */;
INSERT INTO `niveles` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`fondo_tesoro_sn`,`fondo_tesoro`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`metros_lineales`,`unidades`,`historia_institucional_productor`,`historia_archivistica`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`publicaciones_acceso`,`subsidios_otorgados`,`tipo_nivel`,`cod_ref_sup`,`tv`,`numero_registro`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`estado`,`normativa_legal_baja`,`numero_norma_legal`,`motivo`,`fecha_baja`,`fecha_ultima_modificacion`,`usuario_ultima_modificacion`,`na`) VALUES 
 ('AR/PN/SC/DNPM/CNB/Fdo_01','AR/PN/SC/DNPM/CNB','Titulo','Titu','titu','2000-01-01 00:00:00','no',0x00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNB',1,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2011-06-15 16:08:09','camilo',1),
 ('AR/PN/SC/DNPM/CNB/Fondo 01','AR/PN/SC/DNPM/CNB','a','a',NULL,'2001-12-12 00:00:00',NULL,0x01,NULL,'2002-12-12 00:00:00','2003-12-12 00:00:00','alcanzado','12','12','1','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNB',1,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2011-06-22 17:47:42','camilo',2),
 ('AR/PN/SC/DNPM/CNB/Fondo 01/SubFondo 01','AR/PN/SC/DNPM/CNB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNB/Fondo 01',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/C','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/C/DOCCOM','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR/PN/SC/DNPM/CNS/C',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNS/M','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2011-06-21 09:11:01','camilo',1),
 ('AR/PN/SC/DNPM/CNS/MH','AR/PN/SC/DNPM/CNS','Monumento Histórico','Archivo Monumento Histórico',NULL,'1911-01-01 00:00:00',NULL,NULL,NULL,'1911-01-01 00:00:00','2008-01-01 00:00:00','\r\n\r\n<p style=\"TEXT-JUSTIFY: inter-ideograph; TEXT-ALIGN: justify; LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'\">El acervo contiene documentación referida a: adquisición del edificio, declaración Monumento Histórico, trabajos de refacciones y reconstrucción parcial&nbsp;desde l911 a la fecha, instalación de servicios que se fueron logrando a lo largo del tiempo, como: energía el&eacute;ctrica, tel&eacute;fono, cloacas, entre otros. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-JUSTIFY: inter-ideograph; TEXT-ALIGN: justify; LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'\">Tipos Documentales: expedientes (con fotografías),<span style=\"mso-spacerun: yes\">&nbsp; </span>planos, notas.<br />\r\n		\r\n		<o:p><br />\r\n			</o:p></span></p>','Medio metro lineal.','1','\r\n\r\n<p style=\"LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'\">&nbsp;En este Monumento Histórico Nacional, hoy convertido en Museo y Biblioteca, nació el ilustre Prócer Domingo Faustino Sarmiento, el 15 de febrero de 1811. La casa comenzó a construirla Doña Paula Albarracín, madre del Prócer en 1801, la misma es una típica construcción de estilo colonial con amplio pasillo de entrada, arco de medio punto y patio principal donde se yergue la patrialcal higuera. Como todas las casa de aquella &eacute;poca en San Juan los materiales y t&eacute;cnicas usadas son adobe y tapias con techos de caña y barro sostenido por rollizos de álamo. La casa se fue ampliando lentamente atento a las necesidades familiares.. En 1862 cuando Sarmiento fue Gobernador de San Juan, adquiere el tamaño y la forma con que se conserva en la actualidad. La construcción ha soportado cuatro grandes terremotos, el de l894,1944,1952 y 1977. El que causó mayores daños fue el 1944 a consecuencia del cual debió reconstruirse, respetando el plano original de 1866, toda el ala norte. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'\">El ala sur no sufrió grandes daños y solo recibió obras de restauración y consolidación. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'\">Al cumplirse el Primer Centenario del Natalico del Prócer el Gobierno Nacional adquirió la Casa declarándola Monumento Histórico. Las descendientes de la familia Sarmiento- Albarracín vivieron en ella hasta1910. \r\n		<o:p></o:p></span></p>','<span style=\"LINE-HEIGHT: 115%; FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES-AR; mso-fareast-language: ES-AR; mso-bidi-language: AR-SA\">La documentación es producción del Museo y se fue recopilando a trav&eacute;s de los años requerida por la actividad del mismo.<br />\r\n	</span>','Producción y donaciones.','Persona que Donó.','20100225',NULL,NULL,NULL,NULL,'El documento sólo será utilizado según consta en...','Títulos de derechos','No hay Publicaciones',NULL,1,'AR/PN/SC/DNPM/CNS',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-30 23:11:57','camilo',1),
 ('AR/PN/SC/DNPM/CNS/STDFS','AR/PN/SC/DNPM/CNS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNS',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','AR/PN/SC/DNPM/CNSDR','titi','titulo atribuido','tit','2000-01-01 00:00:00','12/25',0x00,NULL,'2000-01-01 00:00:00','2011-01-01 00:00:00','al','al','un','sssss','sssssssss','fr','fr','20110101','po','gtr',NULL,'gtr','gtr','gtr','ferhrkj','jtwrtsykjwty',1,'AR/PN/SC/DNPM/CNSDR',1,NULL,'tyu','yuu','tyuyuj',5,NULL,NULL,NULL,NULL,'2011-06-17 17:12:58','camilo',1),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo','AR/PN/SC/DNPM/CNSDR','titulo original','titulo atribuido',NULL,'0000-01-01 00:00:00','32LL',0x00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/CNSDR/fdo01',2,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2011-06-17 14:25:30','camilo',1),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc',4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc',5,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie',6,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser',7,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp','AR/PN/SC/DNPM/CNSDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc',8,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FBSN','AR/PN/SC/DNPM/MCASN','Fondo Batallón de San Nicolás','Fondo Batallón de San Nicolás',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Contiene documentación referida a la guerra de la Triple Alianza, el Batallón San Nicolás de Guardias Nacionales se alistó con casi 500 efectivos, partiendo en los primeros días de junio de 1865, al mando del coronel Juan Carlos Boerr. Finalizada la guerra casi cinco años más tarde, el Batallón regresó a la ciudad en enero de 1870, llegó al mando del coronel Juan Lucio Somosa, quien había reemplazado a Boerr, al promediar la campa</span><span lang=\"ES\">ña. </span></p>','.','Caja 12, Carpeta verde 2 y Bibliorato IX y Biblior','.','.','Donación ','Enrique Udaondo, José de la Torre, Mercedes Azopardo y Luís M, Campo Urquiza y adquisición: Casa Pardo por la Comisión Honoraria Casa del Acuerdo','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 20:39:52','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FBSN',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','AR/PN/SC/DNPM/MCASN','Juan Carlos Boerr','Juan Carlos Boerr',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1831-01-01 00:00:00','1942-01-01 00:00:00','.','.','Biblioratos XIII ','.','.','Donación','Donación Alfredo Witry, Federico Molina y Segura, José de la Torre y Arturo Cobbold','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,3,'AR/PN/SC/DNPM/MCASN/FBSN/GP',3,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 21:07:26','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','AR/PN/SC/DNPM/MCASN','Juan Lucio Somoza','.',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1866-01-01 00:00:00','1870-01-01 00:00:00','.','.','Caja 12, Carpeta verde 2 y Bibliorato IX','.','.','Donación','Donación de la srta. María Trinidad Somoza hija del Coronel Juan Lucio Somoza','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,3,'AR/PN/SC/DNPM/MCASN/FBSN/GP',3,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 20:55:13','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','AR/PN/SC/DNPM/MCASN','Fondo Coronel De Marina Juan Bautista  Azopardo','Coronel De Marina Juan Bautista  Azopardo',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1809-01-01 00:00:00','1949-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Este fondo cuenta con documentación del traslados de los restos </span><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt\" lang=\"ES\">Coronel de Marina Juan Bautista Azopardo , y de su actuación en las Invasiones Inglesas documentación referentes<span style=\"mso-spacerun: yes\">&nbsp; </span>a hechos históricos acaecido desde 1820 hasta 1862.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt\" lang=\"ES\">Nació en la ciudad de Senglea, isla de Malta en 1774. Durante las dos invasiones inglesas a Buenos Aires y en calidad de oficial de artillería, se batió con los enemigos.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt\" lang=\"ES\">Producido la Revolución de Mayo fue nombrado por la Junta para formar la escuadrilla que se batió el 2 de marzo de 1811 en aguas de San Nicolás. Fue apresado cuando iba a hacer volar la Santabárbara, sufrió cruento cautiverio en la prisión de Ceuta donde se fugó a los nueves años. Al regresar a Buenos Aires en 1820 el gobierno del Brigadier Martín Rodríguez le reconoció sus servicios.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt\" lang=\"ES\">Durante la guerra con el Brasil fue segundo comandante de la escuadras de Brown. Falleció en Buenos Aires el 23 de octubre de 1848.<br />\r\n		</span></p>','.','238 Documentos','.','.','Donación','Donación de la Bisnieta Srta. Mercedes Azopardo ','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 23:30:29','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/CdT','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FCMJBA',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FCMJBA',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/SdZ','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FCMJBA',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FDBM','AR/PN/SC/DNPM/MCASN','Fondo Documental Barreras Matías ','Documental Barreras Matías ',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1809-01-01 00:00:00','1907-01-01 00:00:00','.','.','524 Documentos','.','.','Adquisición','.','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-03 00:26:02','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FDBM/CLNMB','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FDBM',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FDBM/C_TP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FDBM',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FDBM/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FDBM',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FDCA','AR/PN/SC/DNPM/MCASN','Fondo De Casa del Acuerdo','Fondo Documental Museo y Biblioteca Casa del Acuerdo de San Nicolás de los Arroyos',NULL,'1824-01-01 00:00:00',NULL,0x00,NULL,'1824-01-01 00:00:00','1854-01-01 00:00:00','\r\n\r\n<p><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA; mso-bidi-font-size: 10.0pt\" lang=\"ES\">Documentación institucional</span></p>','45m.l','8 Documentos ','\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; BACKGROUND: white\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: Arial; COLOR: black; FONT-SIZE: 10pt\" lang=\"ES\">El Museo y Biblioteca de \r\n		<st1:personname productid=\"la Casa\" w:st=\"on\">la Casa</st1:personname> del Acuerdo de San Nicolás ha sido creado por el P. E. de \r\n		<st1:personname productid=\"la Nación\" w:st=\"on\">la Nación</st1:personname>, mediante decreto del 14 de mayo de 1936, habiendo encomendado su instalación y organización a una comisión honoraria. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; BACKGROUND: white\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: Arial; COLOR: black; FONT-SIZE: 10pt\" lang=\"ES\">Concreto con este acto gubernativo, una gestión laboriosa, promovida veintisiete años antes por el diputado Dr. Aquiles González Oliver, este presento un proyecto de ley a la legislatura de Buenos Aires , el 13 de agosto de 1909, disponiendo la creación de una biblioteca pública en la finca patricia, a la que daría el nombre General Justo Jos&eacute; de Urquiza . Al<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>exponer el autor el fundamento de su iniciativa, se promovió en \r\n		<st1:personname productid=\"la Cámara\" w:st=\"on\">la Cámara</st1:personname> una agitada pol&eacute;mica, de la que salieron robustecidas la personalidad y la obra del prócer. El proyecto quedó convertido en ley por el voto favorable de ambas ramas de parlamento bonaerense. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; BACKGROUND: white\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: Arial; COLOR: black; FONT-SIZE: 10pt\" lang=\"ES\">Debe mencionarse además como verdadero precursor de la fundación de este Museo y Biblioteca , al profesor D. Francisco M. Santillán, por su activa e inteligente pr&eacute;dica desde la columnas de varios diarios, en los años inmediatos que precedieron<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>a la creación de este centro cultural<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp;&nbsp;&nbsp; </span>\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\">Marco Social de \r\n	<st1:personname productid=\"la Casa\" w:st=\"on\">la Casa</st1:personname> del Acuerdo: La casa, edificada hacia 1830, está emplazada en el área central de la ciudad. Tiene las formas sencillas de las construcciones típicas de mediados del S. XIX, y constituye un ejemplo de vivienda particular urbana de esa &eacute;poca,<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>con un patio central sin galería y encerrado por las habitaciones, al que se accedía desde la calle a trav&eacute;s de un zaguán, con sendas aberturas a ambos lados. Su única planta está compuesta por habitaciones de paredes de ladrillos, las más antiguas revocadas en barro alisado en cal. Los techos planos o \"de azotea\" apoyan sobre tirantería y alfajías de pinotea. Su cubierta original de ladrillones fue reemplazada por chapa ondulada. La fachada, simple, muestra aberturas protegidas por rejas que llegan casi hasta nivel de piso, sin guardapolvo, y rematan en una cornisa recta a bastante altura de las ventanas. En 1852 se le agregaron nuevas dependencias. El importante mobiliario, documentación, pinturas y objetos que se exponen en las distintas salas forman parte del patrimonio de este<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>Museo Nacional \r\n	<o:p></o:p></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: Arial; FONT-SIZE: 10pt\" lang=\"ES\">El 7 de junio de 1957 fue declarado Monumento Histórico por decreto del Poder Ejecutivo Nacional. El 31 de mayo de 1852, se firmó en \r\n		<st1:personname productid=\"la Casa\" w:st=\"on\">la Casa</st1:personname> del Acuerdo, hoy monumento histórico (exp.76033/57), el Acuerdo de San Nicolás, acuerdo político sobre la organización de la república que daría pie a \r\n		<st1:personname productid=\"la Constitución Nacional\" w:st=\"on\">la Constitución Nacional</st1:personname> del año siguiente.<br />\r\n		Despu&eacute;s del 17 de septiembre de 1861 (día de la batalla de Pavón), el general Mitre entró en la ciudad y nombró a una de las vías Calle de \r\n		<st1:personname productid=\"la Nación. En\" w:st=\"on\">la Nación. En</st1:personname> 1868 la epidemia de cólera azotó a San Nicolás. Los funerales se sucedieron durante una semana sin interrupción. En 1869 el general Mitre fijó su cuartel general en la ciudad, hizo fortificar la plaza y concentró allí el ej&eacute;rcito y la escuadra, como preparativos para \r\n		<st1:personname productid=\"la Guerra\" w:st=\"on\">la Guerra</st1:personname> de \r\n		<st1:personname productid=\"la Triple Alianza\" w:st=\"on\">la Triple Alianza</st1:personname>, que destruiría a la vecina república del Paraguay. La batería San Jorge y las fortificaciones de la ciudad costaron medio millón de pesos fuertes.<br />\r\n		El 12 de marzo de 1854, de acuerdo a las disposiciones de la nueva ley orgánica, se constituye la municipalidad, integrada por una Comisión Municipal que presidió el juez de paz don Teodoro Fernández, integrándose con seis miembros más. Aunque ya en 1819 —cuando fue declarada ciudad— se había autorizado para instalar Cabildo, reci&eacute;n 35 años más tarde se cubría esa necesidad que reclamaba una administración más orgánica que la de los Jueces de Paz, para una población con 8.470 habitantes. \r\n		<st1:personname productid=\"La Comisión\" w:st=\"on\">La Comisión</st1:personname> inició su cometido el 27 del mismo mes, considerando la redacción de un reglamento interno, el que luego fue elevado al Gobierno para su aprobación, estableciendo multas para los infractores a las disposiciones vigentes, fijando precio para la venta de carne, sacando a remate el alumbrado público, designando dos municipales para vigilar la venta de carne y decidir sobre multas, etc.<br />\r\n		Tras otras resoluciones, tales como la designación de ocho alcaldes para la ciudad, que los miembros de \r\n		<st1:personname productid=\"La Comisión\" w:st=\"on\">la Comisión</st1:personname>, turnándose, debían asistir todo el día a la casa municipal, y alguna rudimentaria obra pública como lo fue el relleno de los pantanos utilizando escombros, el 29 de mayo se aprobó el proyecto de la primera nomenclatura de las calles, presentado por el municipal Casiano López para las 18 trazadas por \r\n		<st1:personname productid=\"La Comisión\" w:st=\"on\">la Comisión</st1:personname> de Solares que presidió el agrimensor Jos&eacute; Rufino Núñez.<br />\r\n		En 1857 aparece el primer periódico, que es a la vez el primero de la campaña del Estado de Buenos Aires, titulado \r\n		<st1:personname productid=\"La Revista Comercial\" w:st=\"on\">La Revista Comercial</st1:personname>, y en 1872 el primer diario - tambi&eacute;n primero de la campaña - que se tituló El Progreso. La ciudad de San Nicolás es, así, cuna del periodismo en la provincia. En 1873 aparece El Centinela del Norte cambiando nombre dos años despu&eacute;s por El Norte de Buenos Aires, el cual cesó su actividad en septiembre de 1924.<br />\r\n		El 1 de diciembre de 1863 abría sus puertas la sucursal del Banco de \r\n		<st1:personname productid=\"la Provincia\" w:st=\"on\">la Provincia</st1:personname> de Buenos Aires, la primera habilitada en el interior, con un capital de un millón de pesos moneda corriente.<br />\r\n		Con motivo de la guerra de \r\n		<st1:personname productid=\"la Triple Alianza\" w:st=\"on\">la Triple Alianza</st1:personname>, el Batallón San Nicolás de Guardias Nacionales se alistó con casi 500 efectivos, partiendo en los primeros días de junio de 1865, al mando del coronel Juan Carlos Boerr. Finalizada la guerra casi cinco años más tarde, el Batallón regresó a la ciudad en enero de 1870, siendo calurosamente recibido por la población Nicoleña. Llegó al mando del coronel Juan Lucio Somoza, quien había reemplazado a Boerr, al promediar la campaña.<br />\r\n		Si bien la ciudad contaba desde 1856 con Juzgado del Crimen, reci&eacute;n el 3 de febrero de 1875 quedaron integrados los Tribunales con la creación de \r\n		<st1:personname productid=\"la Cámara\" w:st=\"on\">la Cámara</st1:personname> de Apelaciones, y agregado de un Juzgado de Primera Instancia en lo Civil y Comercial. Anteriormente, desde 1822 había funcionado un Juzgado de Primera Instancia en lo criminal, creado durante el gobierno de Martín Rodríguez. Fue suprimido en la &eacute;poca de Rosas. Este, pasó a ser Tribunal de Justicia del Departamento del Norte. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; BACKGROUND: white\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: Arial; COLOR: black; FONT-SIZE: 10pt\" lang=\"ES\">Por una resolución del Director General de Escuelas de \r\n		<st1:personname productid=\"la Provincia\" w:st=\"on\">la Provincia</st1:personname>, don Domingo Faustino Sarmiento, se iniciaron las gestiones para la instalación del Consejo Escolar en este Distrito, el que quedó constituido con autoridades provisionales en una reunión realizada en \r\n		<st1:personname productid=\"la Municipalidad\" w:st=\"on\">la Municipalidad</st1:personname>, el 20 de enero de 1876. Ocupó la presidencia Melchor Echagüe, quien la desempeñó hasta 1890.<br />\r\n		En este aspecto educacional cabe destacar la imponderable labor realizada por los Padres Salesianos integrantes de la primera misión enviada fuera de Italia, por Don Bosco, con la finalidad de regentear el Colegio San Nicolás de Los Arroyos, de propiedad municipal, que inició sus clases el 24 de marzo de 1876. Fue el primer colegio de enseñanza secundaria en la ciudad.<br />\r\n		La Orden Salesiana habilitó su casa propia - el actual Colegio Don Bosco en 1900.<br />\r\n		Un gran acontecimiento, que fue el paso inicial de un aspecto importante de la economía argentina, lo constituyó la fundación del frigorífico \r\n		<st1:personname productid=\"La Elisa\" w:st=\"on\">La Elisa</st1:personname> primero de \r\n		<st1:personname productid=\"la Argentina\" w:st=\"on\">la Argentina</st1:personname> y de Sudam&eacute;rica, del que fue propietario fundador el industrial Eugenio Terrassón, en 1882. De origen franc&eacute;s, se había radicado en San Nicolás dedicándose a la industria saladeril y a la explotación agraria, de alto nivel. Desde ese frigorífico se efectuó el primer envío de carnes congeladas a Europa.<br />\r\n		El 3 de febrero de 1884 se habilita la línea ferroviaria a Pergamino dando un importante impulso a la zona rural, con el funcionamiento de las estaciones Conesa y General Rojo que posibilitaron un mejor y más rápido medio de transporte, facilitando, a la vez, las comunicaciones. Hubo grandes festejos con ese motivo. Dos años despu&eacute;s, el 12 de febrero de 1886, quedaba inaugurada la línea Buenos Aires - Rosario, al habilitarse este último tramo de la misma. Tambi&eacute;n en 1884 (23 de febrero) tuvo lugar la inauguración del nuevo Templo Parroquial -hoy Catedral- cuyas obras se habían iniciado a fines de 1885. Se encontraba al frente del mismo el Pbro. Dr. Pedro B. Cecarell. \r\n		<o:p></o:p></span></p><span style=\"FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">Por modificaciones de la ley orgánica de municipalidades, el 22 de junio de 1886 entraban en funcionamiento el Departamento Deliberativo Municipal (hoy Concejo Deliberante), y el Departamento Ejecutivo, a cargo de una persona con el título de Intendente.<br />\r\n	El 29 de agosto de 1888 tiene lugar otro significativo acontecimiento en el aspecto educacional: la inauguración de \r\n	<st1:personname productid=\"la Escuela Normal\" w:st=\"on\">la Escuela Normal</st1:personname> Mixta, con la dirección de la profesora Francisca C. Armstrong (despu&eacute;s señora de Besler) una de las maestras norteamericanas que desempeñaban sus tareas en nuestro país, llegadas por iniciativa de Sarmiento. La primera promoción de maestras tuvo lugar en 1891.<br />\r\n	El Partido contaba ya con más de 24 mil habitantes que gozaban de servicios acordes con su importancia, en funcionamiento en la ciudad cabecera: empedrado de adoquines, luz el&eacute;ctrica pública (siendo San Nicolás de los Arroyos, la 2ª ciudad de \r\n	<st1:personname productid=\"la República\" w:st=\"on\">la República</st1:personname> que lo adoptó), gas de alumbrado, y aguas corrientes.<br />\r\n	Esos antecedentes y la importancia industrial y comercial lograda, pesó en la decisión de las autoridades del Banco de \r\n	<st1:personname productid=\"la Nación Argentina\" w:st=\"on\">la Nación Argentina</st1:personname>, de instalar una sucursal en &eacute;sta, a pocos meses de la inauguración de su casa matriz. La medida se concretó, y el 11 de marzo de 1892 la sucursal abría sus puertas. Se ampliaba así la actividad que ya venía desarrollando el Banco de \r\n	<st1:personname productid=\"la Provincia\" w:st=\"on\">la Provincia</st1:personname> de Buenos Aires, desde casi tres d&eacute;cadas atrás</span>','\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; LAYOUT-GRID-MODE: char; tab-stops: 35.4pt center 212.6pt right 425.2pt\" class=\"MsoHeader\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-font-kerning: .5pt\" lang=\"ES\">Es la documentación generada y recibida</span><span style=\"FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-font-kerning: .5pt\" lang=\"ES\"> </span><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: Arial; FONT-SIZE: 10pt\" lang=\"ES\">por la colaboración de muchos particulares, en su mayoría descendientes de prohombres de la organización nacional. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; tab-stops: 35.4pt center 212.6pt right 425.2pt\" class=\"MsoHeader\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: Arial; FONT-SIZE: 10pt\" lang=\"ES\">Ha ingresado una parte importante de lo que fue el archivo de D. Pedro R. Rodríguez, oriundo de esta ciudad y descendiente directo de su fundador D. Rafael de Aguiar. Actuó desde joven al lado de D. Juan Manuel de Rosas, a quien acompaño todo el período de su dictadura. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt; tab-stops: 35.4pt center 212.6pt right 425.2pt\" class=\"MsoHeader\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: Arial; FONT-SIZE: 10pt\" lang=\"ES\">Posteriormente, el archivo se vio acrecentado<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span><span style=\"FONT-SIZE: 10pt; mso-font-kerning: .5pt\">con la documentación generada por la donación de los</span> hijos del ingeniero Ariodante Ghisolfi quienes donaron el archivo de su progenitor, quien actuó en<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>nuestro medio durante 45 años consecutivos, ocupándose del desarrollo de la vida local. Tambi&eacute;n hay que destacar al señor G. Santiago Chervo investigador del periodismo comarcano la donación de colecciones por el reunida que incluyen diarios, revistas y otras publicaciones locales.<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>\r\n		<o:p></o:p></span></p><span style=\"FONT-FAMILY: Arial; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">Gran parte de los documentos fueron adquiridos en la gestión del director<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>el Sr. Walter Sigfrido Cartey<span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>en Casa Pardo por \r\n	<st1:personname productid=\"la Comisión Honoraria\" w:st=\"on\">la Comisión Honoraria</st1:personname><span style=\"FONT-SIZE: 10pt; mso-spacerun: yes\">&nbsp; </span>de la casa del Acuerdo.</span>','Donación y Adquisición','p','20110101',NULL,NULL,NULL,NULL,'d','t','p',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2011-06-01 21:50:35','camilo',2),
 ('AR/PN/SC/DNPM/MCASN/FI','AR/PN/SC/DNPM/MCASN','Fondo Institucional','.',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1937-01-01 00:00:00','1999-01-01 00:00:00','.','.','.','.','.','Se generó y se actualiza en la Institución ','.','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 23:53:45','camilo',2),
 ('AR/PN/SC/DNPM/MCASN/FI/B','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FI/CRyE','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FI/DRMyE','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FI/M','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2011-06-07 15:08:23','camilo',1),
 ('AR/PN/SC/DNPM/MCASN/FI/NT','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FI/OCP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FI/WSC','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FI',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG','AR/PN/SC/DNPM/MCASN','Fondo Ingeniero Ariodante Ghisolfi','Ingeniero Ariodante Ghisolfi',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 14pt 54pt 14pt 0cm\" class=\"blockquote\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Pertenece al itinerario de los puntos más importante del Municipio de San Nicolás y su gestión pública a su respecto, dependiente de la oficinas t&eacute;cnica municipal a su cargo como jefe de Obras Públicas. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 14pt 54pt 14pt 0cm\" class=\"blockquote\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Llega a San Nicolás en 1904 y tuvo a su cargo la importante obra del Puerto Nuevo de esta ciudad. De allí paso a las Oficina T&eacute;cnica de la Municipalidad donde permaneció<span style=\"mso-spacerun: yes\">&nbsp;&nbsp; </span>por 45 años. Fue miembro fundador del Círculo de Periodistas, en su carácter de corresponsal del veterano órgano ”La Patria degli italiani”. Fue tambi&eacute;n crítico teatral. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\">Fallece el 3 de agosto de1949 a los 86 años. \r\n		<o:p></o:p></span></p>','.','82 Documentos Textuales; 173 Documentos Visuales','\r\n\r\n<p>.</p>','\r\n\r\n<p>.</p>','Donación','De Hernán Ghisolfi y Hnos.','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 20:14:44','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FIAG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/Ases','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/ObPublic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/CR','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/ObPublic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/IGC','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/ObPublic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/Insp','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/ObPublic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/UyO','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/ObPublic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FIAG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/Partic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic/EMA','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FIAG/Partic',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU','AR/PN/SC/DNPM/MCASN','Fondo Justo José De Urquiza','Justo José De Urquiza',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1837-01-01 00:00:00','1901-01-01 00:00:00','<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">La documentación abarca su actividad como hombre público. En una trayectoria tan amplia como la del referido, los contenidos documentales reflejan su actividad como hombre de la historia como político militante y servidor público.<span style=\"mso-spacerun: yes\">&nbsp;&nbsp;&nbsp;&nbsp;</span><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-spacerun: yes\">&nbsp;</span></b></span>','.','278 Documentos','.','.','Luís María Campos Urquiza, Sra. Ana Piaggio de Outon y Srta. Ángela C. Piggio, adquisición: Director Honorario    ','.','00000101',NULL,NULL,NULL,NULL,'.\r\n','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 23:39:04','camilo',2),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Aren','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/ASN','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Corr','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Cred','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Decr','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Desp','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/EG','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Man','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/PP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Procl','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Pron','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/PUN','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Res','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJJU/TC','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJJU',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG','AR/PN/SC/DNPM/MCASN','Fondo Juan María Gutiérrez','Juan María Gutiérrez',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1828-01-01 00:00:00','1875-01-01 00:00:00','\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">El legado de Juan María Guti&eacute;rrez incluye, fotografías, cuadernos de campo, mapas geológicos, informes científicos, manuscritos que recogen parajes naturales e incluso urbanos de las zonas estudiadas por el investigador, de gran curiosidad e inter&eacute;s. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Estadista, jurisconsulto, agrimensor, historiador, crítico y poeta. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Genuino representante del liberalismo constructor argentino de su &eacute;poca es considerado uno de los más grandes promotores de la cultura de su país durante la mayor parte del siglo XIX. Fue autor de obras de diversa índole: cuadros de costumbres, novelas, biografías, críticas literarias y trabajos científicos. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Tuvo una importante actividad política como miembro por Entre Ríos, de la Convención Constituyente de 1853 y como Ministro de Relaciones Exteriores de la Confederación Argentina entre 1854 y 1856 además de haber sido uno de los fundadores de la Asociación de Mayo. Fue un importante promotor de la actividad científica y t&eacute;cnica en la Argentina. Ocupó el cargo de rector de la </span><span lang=\"ES\"><a href=\"http://es.wikipedia.org/wiki/Universidad_de_Buenos_Aires\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-family: \'Times New Roman\'\">universidad Buenos</span></a></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\"> Aires desde 1861 hasta su jubilación en 1874 y gracias a su gestión, numerosos y destacados profesores europeos enseñaron en ella. Fue, junto con </span><span lang=\"ES\"><a href=\"http://es.wikipedia.org/wiki/Hermann_Burmeister\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-family: \'Times New Roman\'\">Hermam</span></a></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\"> Burmesteir, el impulsor del estudio de las ciencias naturales en la Argentina. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Se inició desde muy joven en las letras pero no desdeñó la ciencia, en especial la matemática. Realizó estudios tanto de ingeniería como de derecho, graduándose de doctor en jurisprudencia a los 27 años con una tesis<b style=\"mso-bidi-font-weight: normal\"> </b><span style=\"mso-bidi-font-style: italic\">Sobre lo<i>s </i>tres poderes públicos</span>, eximi&eacute;ndole del pago del arancel debido a la mala situación económica de su familia. Sin embargo prefirió desempeñarse como agrimensor e ingeniero en el Departamento Topográfico y a su vez colaborar en diversos diarios con críticas literarias y traducciones. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Formó y presidió la <span style=\"mso-bidi-font-style: italic\">Asociación de Estudios Históricos</span> y fue un asiduo concurrente del <span style=\"mso-bidi-font-style: italic\">Salón Literario</span> abierto por Marcos Sastre, pronunciando en 1837 el discurso sobre <span style=\"mso-bidi-font-style: italic\">Fisonomía del saber español</span>. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Debido a su apoyo, en &eacute;pocas de Rosas, expatriados a Montevideo, fue encarcelado y cesanteado. Emigró entonces en 1840 al Uruguay donde se<b style=\"mso-bidi-font-weight: normal\"> </b>destacó como literato colaborando con <span style=\"mso-bidi-font-style: italic\">El Iniciador</span><b style=\"mso-bidi-font-weight: normal\"> </b>en forma anónima a la vez que continuó en otros periódicos su obra de divulgación y crítica. Tambi&eacute;n realizó trabajos como ingeniero y topógrafo. Fundó con Juan Bautista Alberdi y Esteban Echeverría la </span><span lang=\"ES\"><a href=\"http://es.wikipedia.org/w/index.php?title=Asociación_de_Mayo&amp;action=edit&amp;redlink=1\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-family: \'Times New Roman\'\">Asociación</span></a></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\"> de Mayo y viajó en 1843 con Alberdi por Am&eacute;rica y Europa. Durante este viaje estuvo en Valparaíso, Chile, donde se dedicó a la docencia, escribió libros y la colección de poesías <span style=\"mso-bidi-font-style: italic\">Am&eacute;rica Po&eacute;tica</span>, que tuvo muy buena crítica. Tambi&eacute;n allí fue el primer director de la Escuela Náutica. Publicó biografías traducidas del franc&eacute;s y el resultado de sus investigaciones por el nuevo mundo. Comenzó una labor periodística en diarios de Buenos Aires y fue diputado nacional. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">El presidente Bartolom&eacute; Mitre le ofreció la dirección de la Universidad de Buenos Aires, cargo que ejerció desde 1861 hasta 1874 siendo además integrante de la Convención Constituyente bonaerense de 1870-1873. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Sus <span style=\"mso-bidi-font-style: italic\">Noticias históricas sobre el origen y desarrollo de la Enseñanza Superior en Buenos Aires</span> (1868) constituye un clásico en el cual volcó todos sus conocimientos sobre el tema. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Como rector de la Universidad creó el Departamento de Ciencias Exactas e inició gestiones para contar con profesores que provinieran de Europa. Así vinieron Bernardino Speluzzi de la universidad de Pavia, Emilio Rossetti de la universidad de Turín (ambos como profesores de matemáticas) y </span><span lang=\"ES\"><a href=\"http://es.wikipedia.org/w/index.php?title=Pelegrino_Strobel&amp;action=edit&amp;redlink=1\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-family: \'Times New Roman\'\">Pelegrino Strobel</span></a></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\"> de Parma, para historia natural. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1865 presidió una comisión que presentó el «proyecto de un plan de instrucción general y universitaria» cuyo informe constituyó un documento valioso tanto desde el punto de vista histórico como tambi&eacute;n por sus concepciones didácticas y científicas. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1875 se lo nombró jefe del Departamento de Escuelas de la Provincia. Proyectó escuelas de agricultura, comercio y náutica, e hizo lo posible por fundar una Facultad de Química y Farmacia. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1876 rechazó el diploma de la Real Academia Española de la Lengua. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Las obras escritas por Juan María Guti&eacute;rrez son numerosas. Su estilo se caracterizaba por estar despojado de toda ostentación verbal y por su modernidad.</span><b style=\"mso-bidi-font-weight: normal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\"> \r\n			<o:p></o:p>\r\n			\r\n<p><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Falleció en Buenos Aires, en su casa, el 26 de febrero de 1878.<br />\r\n					\r\n					<o:p></o:p></span></p>\r\n			\r\n<p>&nbsp;</p>\r\n			\r\n<p>&nbsp;</p>\r\n			\r\n<p>&nbsp;</p>\r\n			\r\n<p>&nbsp;</p></<?xml:namespace></b></p>','.','519 Documentos','.','.','Donación','Donación de su nieto Juan María Etchevarry Gutiérrez','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 21:27:36','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FJMG/AP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Dipl','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/DJJA','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Edu','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/EEpist','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/ENI','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/GJJU','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Lit','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Soc','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'AR/PN/SC/DNPM/MCASN/FJMG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR','AR/PN/SC/DNPM/MCASN','Fondo Juan Manuel de Rosas','Fondo Juan Manuel de Rosas',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1850-01-01 00:00:00','1864-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Documentación referente a su administración en el gobierno y acciones de guerras, conjunto de cartas enviadas y recibidas con su hija Manuelita,</span><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt\" lang=\"ES\"> </span><strong><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-WEIGHT: normal; mso-bidi-font-weight: bold\" lang=\"ES\">reúne materiales muy diversos. En general se trata de proclamas y correspondencia de corte político entre gobernadores de provincias del territorio argentino en la primera mitad del siglo XIX. Entre otras, contiene cartas autógrafas de Juan Manuel de Rosas (durante su gestión como gobernador de la provincia de Buenos Aires) tambi&eacute;n presenta algunas proclamas y cartas del general Jos&eacute; María Paz</span></strong><strong><span style=\"LINE-HEIGHT: 150%; FONT-SIZE: 10pt; FONT-WEIGHT: normal; mso-bidi-font-weight: bold\" lang=\"ES\">. \r\n			<o:p></o:p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Juan Manuel de Rosas nace el 30 de marzo de 1793 en Buenos Aires.&nbsp; Hijo de un matrimonio de hacendado, solía ser rebelde a los deseos de sus padres. Ya en el año de 1806 con 13 años de edad se presentó voluntariamente a Liniers para participar en la reconquista de Buenos Aires. En 1813 contrae matrimonio con Encarnación Ezcurra, contra los deseos de sus padres. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Encarnación Ezcurra ayudó a Juan Manuel de Rosas en el proceso político que posibilitó a Rosas la segunda Gobernación de Buenos Aires. Tenía mucha influencia y ayudo a aumentar el poder y la fortuna de Rosas. En 1820 se convierte en el artífice político del Tratado de Paz entre el Gobernador Dorrego de Bs. As. y Estanislao López de Santa Fe. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1829, Rosas es electo Gobernador de Buenos Aires. En 1833 comanda la primera campaña del Desierto, Rosas fue más proclive a realizar pactos con los indios, para hacerse amigo de ellos y no deseaba confrontar con ellos. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1835, el asesinato de Quiroga en Barranca Yaco, posibilitó la decisión de los Legisladores de Buenos Aires, de otorgarle a Juan Manuel de Rosas, la suma del poder público y de facultades extraordinarias para volver a gobernar. Gracias a ello Rosas es elegido por segunda vez Gobernador de la Confederación Argentina \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En 1838, Se inicia el bloqueo Franc&eacute;s del puerto de Buenos Aires. Rosas tenía conflictos con los Franceses, a quienes no les reconocía ciertas prerrogativas a sus ciudadanos (ejemplo a los ciudadanos franceses que residían en Buenos Aires no los libraba de realizar el Servicio Militar); sumado tambi&eacute;n a conflictos comerciales. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En&nbsp;1840, es el año del fin del Bloqueo Franc&eacute;s, Rosas derrota a Lavalle. En 1845 Rosas se convierte nuevamente en un baluarte de la defensa nacional. . Durante la intervención armada anglo -francesa en el Río de la Plata, se produce nuevamente el bloqueo de Buenos Aires y la Batalla de la Vuelta de Obligado (Lucio V Mansilla). Si bien los ingleses nos derrotaron en la batalla de la Vuelta de Obligado, no lograron su objetivo de comerciar con los pueblos del Río Paraná. Los ingleses vieron decrecer sustancialmente el comercio que tenían con el Río de la Plata, la astucia de Rosas cómo político, le permiten ganarle a los ingleses en las negociaciones. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">&nbsp;En 1850 la confederación rompe relaciones diplomáticas con Brasil. \r\n					<o:p></o:p></span></p>\r\n			\r\n<p style=\"LINE-HEIGHT: 150%\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">&nbsp;En 1852 Rosas es derrotado por Urquiza en la Batalla de Caseros \r\n					<o:p></o:p></span></p><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">&nbsp;Rosas fallece el 14 de Marzo de 1877 y es enterrado en el cementerio de Southampton.</span></span></strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>','.','55 Documentos','\r\n\r\n<p>.</p>','\r\n\r\n<p>.</p>','Donación','.','00000101',NULL,NULL,NULL,NULL,'.','.','\r\n\r\n<p>.</p>',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 01:16:47','camilo',3),
 ('AR/PN/SC/DNPM/MCASN/FJMR/ManT','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/ManT/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/ManT',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','AR/PN/SC/DNPM/MCASN','Máximo Terrero','Máximo Terrero',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1852-01-01 00:00:00','1887-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Esta sección esta compuesto por documentación de índole personal, referencias de publicaciones políticas \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Máximo Terrero,<span style=\"COLOR: black\"> hijo de Juan Nepomuceno Terrero, amigo de Juan Manuel de Rosas. El 23 de octubre de 1852, pudo unirse en matrimonio con su novia Manuelita de Rosas. Del matrimonio nacieron dos hijos varones: Manuel Máximo Nepomuceno, nacido el 20 de mayo de 1856, y Rodrigo Tomás, que vino al mundo el 22 de septiembre de 1858. Vivieron en Hampstead, Londres</span><b style=\"mso-bidi-font-weight: normal\"> \r\n			<o:p></o:p>\r\n			\r\n<p>&nbsp;</p></<?xml:namespace></span></p>','.','21 documentos','.','.','Donación','de Hernán García','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,2,'AR/PN/SC/DNPM/MCASN/FJMR',2,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 01:36:43','camilo',2),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','AR/PN/SC/DNPM/MCASN','Epistolario','Epistolario',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1898-01-01 00:00:00','1903-01-01 00:00:00','Hace referencia de la muerte de su madre Doña Manuela Rosas de Terrero y agradece las condolencias recibidas.<br />\r\nManuel Terrero hijo de Manuelita de Rosas y Terrero Máximo nació el 20 de mayo de 1856, vivió en Hampstead, Londres.','.','6 documentos','.','.','Donación',' Hernán García','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/MaxT',3,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 01:47:48','camilo',4);
INSERT INTO `niveles` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`fondo_tesoro_sn`,`fondo_tesoro`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`metros_lineales`,`unidades`,`historia_institucional_productor`,`historia_archivistica`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`publicaciones_acceso`,`subsidios_otorgados`,`tipo_nivel`,`cod_ref_sup`,`tv`,`numero_registro`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`estado`,`normativa_legal_baja`,`numero_norma_legal`,`motivo`,`fecha_baja`,`fecha_ultima_modificacion`,`usuario_ultima_modificacion`,`na`) VALUES 
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/Lite','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/MaxT',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/Pers','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/MaxT',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FJMR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR/Doc','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/MR',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR/EhMdR','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FJMR/MR',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT','AR/PN/SC/DNPM/MCASN','Fondo Pbro. Rodolfo Torti','Pbro. Rodolfo Torti',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1828-01-01 00:00:00','1875-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span class=\"style21\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: Arial; FONT-SIZE: 9pt\" lang=\"ES\">La documentación conservada en el archivo nos permite conocer con detenimiento las diferentes etapas por las que ha atravesado la Parroquia de San Nicolás a lo largo de su historia. Conviene reseñar la importancia de</span></span><span lang=\"ES\"> </span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">los documentos que son inseparables de la gestión de las mismas: Escrituras, pleitos, obras, libros de cuentas, cobros de luminarias, gastos de entierros, las prácticas culturales. Es en definitiva la memoria fiel de la vida de la Institución la lo largo de su existencia. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\">Cura vicario de San Nicolás, obispado de la Plata. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">En <span style=\"mso-bidi-font-weight: bold; mso-bidi-font-style: italic\">1908,</span><b> </b><span style=\"mso-bidi-font-weight: bold; mso-bidi-font-style: italic\">Santiago Luis Copello, Secretario del Obispado de la Plata, comunica&nbsp; la designación del Presbítero Dr. Rodolfo Torti, </span><span style=\"mso-bidi-font-weight: bold\">Para desempeñarse en la Iglesia Parroquial de San Nicolás,<b> </b><span style=\"mso-bidi-font-style: italic\"><span style=\"mso-spacerun: yes\">&nbsp;</span>como cura Vicario de San Nicolás,</span> procedente de San Martín. Tuvo en la ciudad una destacada actuación en los medios sociales y culturales. Fue capellán&nbsp;del ej&eacute;rcito durante 42 años. Dirigió importantes publicaciones católicas, como \"El Sembrador” y \"Renovación”. <span style=\"mso-bidi-font-style: italic\"><span style=\"mso-spacerun: yes\">&nbsp;</span>Se desempeñó al frente de la parroquia, despu&eacute;s Catedral, durante 52 años. Falleció </span>17 de agosto<b> </b><span style=\"mso-bidi-font-style: italic\">en 1960. Sus restos descansan en la cripta de la Catedral. \r\n				<o:p></o:p></span></span></span></p>','.','Biblioratos XIX, XIX bis, XX, XXI, XXII, XXII bis','.','.','Donación','Donación de su hermana Josefina Torti','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 21:11:37','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FPRT/AM','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/AP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/Cap','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/ER','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/FEI','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/FP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/IB','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/IV','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/Obisp','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/SP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FPRT/TyCP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FPRT',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP','AR/PN/SC/DNPM/MCASN','Fondo Rodríguez Gamez, Pedro','Fondo Rodríguez Gamez, Pedro',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1820-01-01 00:00:00','1840-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Este fondo trata de documentación personales, militares y juicios y demanda de tierras<span style=\"mso-spacerun: yes\">&nbsp; </span>\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\">Casado con Da. Laureana Aguiar y Ortega, nieta de D. Rafael Aguiar de ese matrimonio nació D. Pedro Regalado Rodríguez<span style=\"mso-spacerun: yes\">&nbsp;&nbsp;&nbsp; </span>\r\n		<o:p></o:p></span></p>','.','51Documentos','h','h','Donación Hernán García','no se conoce','00000101',NULL,NULL,NULL,NULL,'d','t','no hay',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 01:01:29','camilo',3),
 ('AR/PN/SC/DNPM/MCASN/FRGP/DMJMR','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FRGP',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FRGP',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP/RJ','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FRGP',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FRGP',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T/JyD','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FRGP/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T/PyM','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FRGP/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FSRG','AR/PN/SC/DNPM/MCASN','Fondo Segundo Román García','Segundo Román García',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 14pt 0cm\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; COLOR: black\" lang=\"ES\">El Fondo está constituido principalmente por manuscritos del autor, entre ellos destacan transacciones comerciales, y demandas por juicios. En la serie de documentos personales es interesante ver las firmas de contratos y documentos de cr&eacute;dito que nos dejan ver muy claro cómo cambió la situación económica de Román.<span style=\"mso-spacerun: yes\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 14pt 0cm\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-weight: bold; mso-bidi-font-style: italic\" lang=\"ES\"><span style=\"mso-spacerun: yes\">&nbsp;</span>Fallece en 1872 en San Nicolás, Segundo Román García, comerciante, hacendado y político. Había nacido en Rojas en 1817. Fue uno de los propietarios de la antigua cigarrería \"El Toro”. Integró la primara comisión municipal al instalarse la municipalidad en 1854&nbsp;cargo que desempeño en diversos períodos posteriores. Tuvo la primera barraca y como industrial, un saladero. \r\n		<o:p></o:p></span></p>','.','374 Documentos','.','.','Hernán García','.','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 21:38:19','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FSRG/ActCom','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FSRG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FSRG/ActPub','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FSRG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FSRG/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FSRG',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','AR/PN/SC/DNPM/MCASN','Fondo Tesoro Pedro Regalado Rodríguez','Fondo Tesoro Pedro Regalado Rodríguez',NULL,'2000-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Este fondo se refiere a la actuación pública y eventos particulares llevados<span style=\"mso-spacerun: yes\">&nbsp; </span>por Don Pedro Regalado Rodríguez, como tambi&eacute;n juicios y demandas de tierras\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">La correspondencia incluida en el fondo resulta especialmente interesante, incluyendo cartas, entre otros, de Antonino Reyes y</span><span style=\"LINE-HEIGHT: 150%; FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-bidi-font-size: 10.0pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\"> Juan Manuel de Rosas, Francisco Llobet, Jos&eacute; Scotto</span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">, así como otras que dan testimonio de su gran actividad política. Tambi&eacute;n contiene trabajos originales y borradores de trabajos sobre historia. <br />\r\n		Este fondo resulta de especial inter&eacute;s para el conocimiento de la vida del personaje, pero tambi&eacute;n para el estudio de la historia del país en un periodo especialmente problemático.\r\n		<o:p></o:p></span></p>','1','493 Documentos','h','h','Donación Hernán García','Se desconoce','00000101',NULL,NULL,NULL,NULL,'d','t','no hay',NULL,1,'AR/PN/SC/DNPM/MCASN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-31 03:04:47','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/MG','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/AP',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/MGue','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/AP',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/P','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/AP',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','AR/PN/SC/DNPM/MCASN','Antonio Reyes','Antonio Reyes',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','\r\n\r\n<p style=\"LINE-HEIGHT: 150%; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">documentación de índole personal y su actuación<span style=\"mso-spacerun: yes\">&nbsp; </span>relacionada como edecán de Don Juan Manuel De Rosas Antonino Reyes acompañó a Rosas en la primera expedición al desierto en 1833, que significó una considerable ampliación del reducido territorio nacional de entonces, provocando el desplazamiento de los aborígenes. Muy poco tiempo despu&eacute;s, cuando Rosas regresa<span style=\"mso-spacerun: yes\">&nbsp; </span>a la gobernación de Buenos Aires, Reyes se convirtió en su secretario personal hasta la caída en Caseros.<br />\r\n		Luego fue capturado en Luján y condenado a muerte. Reyes pudo escapar de la prisión y huir al Uruguay, cuyo presidente Venancio Flores había solicitado, sin &eacute;xito, la conmutación de su pena. Aunque luego la pena se conmutó, Antonino Reyes permaneció en Montevideo, donde colaboró con Adolfo Saldías en la redacción de la \"Historia de la Confederación Argentina\", una apología de Rosas por la defensa de la nación contra la incursión extranjera de Francia.<br />\r\n		Las numerosas cartas de Reyes a Adolfo Saldías mencionan a<span style=\"mso-spacerun: yes\">&nbsp; </span>Rosas y a Manuelita. \r\n		<o:p></o:p></span></p><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: AR-SA; mso-bidi-language: AR-SA\" lang=\"ES\">En el archivo general de la Nación hay una compilación del epistolario entre Manuelita Rosas y Antonino Reyes.<br />\r\n	</span>','.','178 documentos','H','H','Donación Hernán García','.','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/E',3,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 00:38:00','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','AR/PN/SC/DNPM/MCASN','Francisco Llobet','Francisco Llobet',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1835-01-01 00:00:00','1836-01-01 00:00:00','\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; TEXT-ALIGN: justify; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'\" lang=\"ES\">Conjuntos de cartas con referencias a acciones militares, reuniones en San Nicolás, caída de Don Juan Manuel de Rosas y referencias a terrenos en litigios situados en San Nicolás \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"PAGE-BREAK-AFTER: avoid; LINE-HEIGHT: 150%; MARGIN: 12pt 0cm 3pt\" class=\"MsoNormal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-ansi-language: ES-TRAD; mso-bidi-font-weight: bold\" lang=\"ES-TRAD\">M&eacute;dico. Nació en la ciudad de San Nicolás de los Arroyos el día 16 de abril del año 1883. Sus padres fueron Don Pedro J. Llobet y Doña Jovita Millán.<br />\r\n		&nbsp;El doctor Francisco Llobet de larga y destacada actuación docente y profesional fue sucesivamente interno de Los hospitales, jefe de disección del Instituto de Anatomía Normal, jefe de trabajos prácticos de histología, cirujano del Hospital Rawson, profesor suplente de anatomía topográfica y por último profesor extraordinario de cirugía en la Facultad de Ciencias M&eacute;dicas de Buenos Aires.<br />\r\n		&nbsp;En cursos oficiales y libres, el doctor Llobet contribuyó eficazmente en la orientación de la enseñanza quirúrgica de la Escuela de Medicina. De regreso de uno de sus numerosos viajes de estudio&nbsp;a las clínicas europeas, hizo conocer en nuestros institutos de anatomía normal y patológica, los nuevos procedimientos de t&eacute;cnica aplicados a la preparación y conservación de los tejidos, facilitando la comprensión de su estructura íntima.<br />\r\n		&nbsp;En varias oportunidades en que se hizo cargo de la cátedra de medicina operatoria, encaminó el estudio de la materia con procedimientos sint&eacute;ticos y propios de las operaciones de los miembros, simplificando los que describe el libro de Farabeuf. Ante nutrido alumnado dictó los primeros cursos completos y metódicos sobre cirugía operatoria visceral, en tiempos en que los programas se reducían por lo común a al cirugía&nbsp;de los miembros. Distinguió al Doctor Llobet la claridad de su exposición, la que era acompañada por ágiles y esquemáticos dibujos trazados simultáneamente.<br />\r\n		&nbsp;El maestro cirujano, resuelto a retirarse de las actividades, renunció a su cátedra y demás cargos t&eacute;cnicos, obsequiando su valiosa biblioteca de cirugía a los servicios de los Doctores Jos&eacute; Arce y Bernardino Maraini y donando luego todo su moderno instrumental operatorio que representa una considerable suma al Hospital Parmeiro Piñero, servicio del Doctor Ricardo Rodríguez Villegas.<br />\r\n		&nbsp;El Consejo Directivo de la Facultad de Ciencias M&eacute;dicas, le hizo objeto de una distinción muy alta, al designarlo Profesor Honorario, lo que aprobó por voto unánime el Consejo Superior de la Universidad.<br />\r\n		&nbsp;El Doctor Llobet hombre de ciencia y autoridad reconocida en los círculos m&eacute;dicos argentinos, se impuso tambi&eacute;n en el orden espiritual como destacada figura de relieves propios que mantuvo en acción constante, su fervor idealista.<br />\r\n		&nbsp;Formó como conocedor y erudito, una de las más importantes galerías del país, compuesta en su casi totalidad por obras de elevadas significación de las escuelas francesas, románticas, desde Eugene Delacroix hasta lo más grandes pintores de 1830, impresionistas y moderna, siendo en este último grupo de importantísima su colección de esculturas, conjunto que por su rara calidad y valor representativo mereció un extenso estudio de Camille Maulair, que terminan con un emocionando elogio al coleccionista de respeto, \"porque ha sabido escoger bien sus alegrías y servir a su país, ornando su casa con bellas cosas de Francia”. Junto a esta importante galería</span><span style=\"FONT-FAMILY: \'Verdana\',\'sans-serif\'; mso-ansi-language: ES-TRAD; mso-bidi-font-weight: bold\" lang=\"ES-TRAD\">, </span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-ansi-language: ES-TRAD; mso-bidi-font-weight: bold\" lang=\"ES-TRAD\">el Doctor Llobet </span><span style=\"FONT-FAMILY: \'Verdana\',\'sans-serif\'; mso-ansi-language: ES-TRAD; mso-bidi-font-weight: bold\" lang=\"ES-TRAD\">había<b> </b></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; mso-ansi-language: ES-TRAD; mso-bidi-font-weight: bold\" lang=\"ES-TRAD\">reunido una serie de porcelanas, cristales de roca marfiles de las grandes dinastías (...) biblioteca compuesta en su mayor parte por preciosos ejemplares únicos, exornados por originales de artistas famosos y por encuadernaciones de maestros de la materia.<br />\r\n		&nbsp;Como Director General de Bellas Artes, el Doctor Llobet puso todos sus conocimientos y su entusiasmo a favor de una gran obra y, despu&eacute;s de abandonar el cargo en defensa de los intereses confiados a su custodia, que consideraba en peligro, continuó apoyando todos lo que implicase una iniciativa feliz en los campos de l arte. Así efectuó numerosas donaciones importantes a los museos nacionales, contribuyendo con las obras más hermosas de su galería, ala campaña educativa del Museo Nacional y sosteniendo la publicación del Boletín, de gran inter&eacute;s didáctico e informativo.<br />\r\n		&nbsp;Tambi&eacute;n, enriqueció colecciones como las del Círculo de Armas fundó la Sociedad Amigos del Museo y presidió la de Amigos del Arte; pero no se detuvieron ahí sus actividades pues en la prensa diaria, en folletos y estudios diversos, hizo conocer interesantes trabajos críticos sobre numerosos pintores, como tambi&eacute;n artículos de ideas novedosas y definidas sobre temas artísticos de actualidad, de análisis social y de investigación, relacionada con la belleza y el carácter de las impresiones espirituales. El Gobierno de Francia, le designó caballero de la Legión de Honor, como premio&nbsp; a la intensa labor realizada y en homenaje \"a los servicios prestados a la influencia francesa en al Argentina” como expresaba la comunicación del embajador Clinchant, al anunciarle la resolución del presidente Doumergue, falleció en Buenos Aires en 1939. \r\n		<o:p></o:p></span></p>','.','25 documentos','H','H','Donación','Donación de Hernán García  de la séptima generación de descendiente del fundador de la ciudad de San Nicolás, Don Rafael de Aguiar  ','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 00:49:18','camilo',2),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JMR','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','AR/PN/SC/DNPM/MCASN','José Scotto','José Scotto',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1897-01-01 00:00:00','1901-01-01 00:00:00','Hace referencias de artículos publicados en diferentes medios gráficos.','.','6 documentos','h','h','Donación','Hernán P. García de la séptima generación de descendiente del fundador de la ciudad de San Nicolás, Don Rafael de Aguiar  ','00000101',NULL,NULL,NULL,NULL,'.','.','.',NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-06-02 00:56:51','camilo',4),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN/Inf','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/SN',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN/Salad','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/SN',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'AR/PN/SC/DNPM/MCASN/FTPRR',2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/CD','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/CL','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/Dem','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/FU','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/JLS','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/Juic','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/SS','AR/PN/SC/DNPM/MCASN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'AR/PN/SC/DNPM/MCASN/FTPRR/T',3,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MCY/FRY','AR/PN/SC/DNPM/MCY','Rogelio Yrurtia','Fondo Rogelio Yrurtia',NULL,'1883-01-01 00:00:00',NULL,NULL,NULL,'1883-01-01 00:00:00','1950-01-01 00:00:00','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">El acervo contiene documentos pertenecientes al escultor Rogelio Yrurtia, sobre la creación y producción de sus obras, adquisición y reformas de sus viviendas, creación del museo, correspondencia personal. </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Los tipos de documentos: son textuales, en soporte papel y visuales fotografías soporte papel y negativos en vidrio y celuloide.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Formatos: correspondencia manuscrita y mecanografiada, postales, bitácora de medios, planos, recibos y documentos legales.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">los niveles encontrados en la clasificación del fondo son: </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n		<o:p>&nbsp;</o:p> \r\n		\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Secciones y series:</span></p>\r\n		\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n				<o:p>&nbsp;</o:p> \r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Monumentos</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">1 - Serie Monumento Canto al Trabajo</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">2 – Serie Monumento Dorrego</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">3 – Serie Monumento Rivadavia</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">4 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Monumento Justicia</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">5 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Monumento Combate de Box</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">6 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Monumento Urquiza</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">7 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Monumento a \r\n						<st1:personname w:st=\"on\" productid=\"la Bandera\">la Bandera</st1:personname></span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">8 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Monumento Independencia</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">9 -<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie Fuentes de Plaza (Miserere)</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">10 – Serie Planos de Obra.</span></p>\r\n				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n						<o:p>&nbsp;</o:p> \r\n						\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección<span style=\"mso-spacerun: yes\">&nbsp; </span>Academia Nacional de Bellas Artes.</span></p>\r\n						\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n								<o:p>&nbsp;</o:p> \r\n								\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Viviendas</span></p>\r\n								\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">1 – Serie Casa de París</span></p>\r\n								\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">2 – Serie Casa Museo</span></p>\r\n								\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n										<o:p>&nbsp;</o:p> \r\n										\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Rogelio Yrurtia</span></p>\r\n										\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n												<o:p>&nbsp;</o:p> \r\n												\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Bitácora de Medios</span></p>\r\n												\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n														<o:p>&nbsp;</o:p> \r\n														\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Monedas y Medallas</span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n																<o:p>&nbsp;</o:p> \r\n																\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Fotografías </span></p>\r\n																\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">\r\n																		<o:p>&nbsp;</o:p> \r\n																		\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Sección Tarjetas postales</span></p>\r\n																		\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">1 – Serie Tarjetas postales</span></p>\r\n																		\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">2 – Serie epistolario</span></p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p>\r\n																		\r\n<p>&nbsp;</p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p></?xml:namespace></p>','1','900 Fotografías, 1300 Negativos, 1554 Tarjetas Pos','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Rogelio Yrurtia: Artista, escultor, medallista, dibujante y dueño de un talento sin fin.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Nació en Buenos Aires el 6 de diciembre de 1879 y murió el 4 de marzo de \r\n		<st1:metricconverter w:st=\"on\" productid=\"1950 a\">1950 a</st1:metricconverter> los 71 años.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Hijo de inmigrantes vascos dedicados al comercio, reveló desde muy temprano su pasión por las artes. Debido a la influencia familiar estudió contabilidad y trabajó como tenedor de libros para poder seguir sus clases de arte.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Realizó sus primeros estudios de escultura con un santero llamado Casals con qui&eacute;n modeló su primera obra \"pie de niño” </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">En 1898 ingresa a \r\n		<st1:personname w:st=\"on\" productid=\"la Escuela\">la Escuela</st1:personname> de \r\n		<st1:personname w:st=\"on\" productid=\"la Sociedad\">la Sociedad</st1:personname> de Estímulo de Bellas Artes a la clase del maestro Lucio Correa Morales. </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Un año más tarde obtuvo por concurso una beca otorgada por el Ministerio de Instrucción Pública </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">para estudiar en \r\n		<st1:personname w:st=\"on\" productid=\"la Academia Jullien\">la Academia Jullien</st1:personname> de París, y continua su formación con F&eacute;lix Coutan.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Vuelve al país en \r\n		<st1:metricconverter w:st=\"on\" productid=\"1904, a\">1904, a</st1:metricconverter> partir de allí trabaja en varios monumentos y esculturas entre los que se encuentran el Monumento al Doctor Alejandro Castro, Canto al Trabajo, el Monumento al Coronel Manuel Dorrego, el Mausoleo de Bernardino Rivadavía, Combate de Box y Justicia.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Se presenta a lo largo de su carrera artística en numerosos concursos entre los cuales se encuentran el Concurso al Monumento del Centenario, Concurso del Monumento a \r\n		<st1:personname w:st=\"on\" productid=\"la Bandera\">la Bandera</st1:personname>, etc.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Rogelio Yrurtia tenía un carácter fuerte y se auto exigía en extremo.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Era dueño de una inagotable voluntad para trabajar<span style=\"mso-spacerun: yes\">&nbsp; </span>que lo llevó a la búsqueda de la perfección </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">estas características lo hacían ver obsesivo, minucioso, detallista y estricto. </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Pero esta severidad para el trabajo no opacaba su gran sensibilidad, que no sólo fue expresada en sus obras, sino tambi&eacute;n en sus relaciones personales.<br />\r\n		</span></p>','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Rogelio Yrurtia y Lía Correa Morales, crean el archivo documental; en el año 1998 Alejandra Siquier le da orden y realiza las primeras fichas catalográficas.</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Se adjunta el informe realizado por \r\n		<st1:personname w:st=\"on\" productid=\"la Conservadora Alejandra\">la Conservadora Alejandra</st1:personname> Siquier y Profesora de Historia María Claudia Bojorque. </span></p>','Donación','Se desconoce','20110529',NULL,NULL,NULL,NULL,'D','t','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Rinaldini, Rogelio Yrurtia</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Luis Tossoni, Investigación sobre \r\n		<st1:personname w:st=\"on\" productid=\"la Casa\">la Casa</st1:personname> de Yrurtia </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Laura Malosetti, Primeros Modernos</span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Catalogo del Museo Casa de Yrurtia 1967</span></p>',NULL,1,'AR/PN/SC/DNPM/MCY',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-29 12:28:38','camilo',4),
 ('AR/PN/SC/DNPM/MHN/FAPC','AR/PN/SC/DNPM/MHN','FONDO ADOLFO PEDRO CARRANZA','ARCHIVO CARRANZA',NULL,'2011-01-01 00:00:00',NULL,NULL,NULL,'1834-01-01 00:00:00','1950-01-01 00:00:00','\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"TEXT-DECORATION: underline; mso-ansi-language: ES-TRAD\">Temas principales: \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">\r\n		<o:p>&nbsp;</o:p>\r\n		\r\n<p style=\"TEXT-INDENT: 35.4pt; MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Toda ponderación es poca acerca de la importancia del Archivo para profundizar el conocimiento de nuestro pasado, durante las postrimerías del siglo XIX y los comienzos del siglo XX, por el caudal y diversidad de la información que provee.<span style=\"mso-spacerun: yes\">&nbsp; </span><span style=\"mso-spacerun: yes\">&nbsp;</span></p>\r\n		\r\n<p style=\"TEXT-INDENT: 35.4pt; MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Consignamos a modo de ejemplo -aparte de los aspectos biográficos de Adolfo Carranza-, algunos temas sobre los cuales la documentación hace interesantes aportes: pintores y<span style=\"mso-spacerun: yes\">&nbsp; </span>obras pictóricas; escultores argentinos y extranjeros; monumentos, esculturas, mausoleos de la Ciudad y Provincia de Buenos Aires y otros puntos del país; escritores y poetas; Centenario de Mayo; medallas y placas conmemorativas, publicaciones periódicas y de libros; encuentros sociales; inauguraciones de diverso tipo; destinos de esparcimiento: Sierra de la Ventana, Alta Gracia; acciones de centros, juntas, clubes y asociaciones; congresos científicos realizados en Argentina y en Am&eacute;rica; pensamientos y obra de los principales hombres de la política, la cultura y las relaciones exteriores del país e hispanoamericanos; corrientes historiográficas y de pensamiento; opiniones de los principales personajes de su tiempo sobre aspectos de la historia argentina y americana; homenajes a hombres protagónicos de nuestra historia; partidos políticos y comicios; fundación del Museo Histórico; conformación de su fondo patrimonial; divulgación de la historia a trav&eacute;s del Museo; aspectos administrativos de la Institución; origen y condiciones en que se realizaban las donaciones, entre tantos otros temas existentes en el Fondo.<br />\r\n			<br />\r\n			</p>\r\n		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"TEXT-DECORATION: underline; mso-ansi-language: ES-TRAD\">Tipos documentales: \r\n				<o:p></o:p></span></p>\r\n		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">-Documentos textuales: Manuscritos, impresos \r\n				<o:p></o:p></span></p>\r\n		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">-Documentos de Imagen y Sonido: fotografías, postales. \r\n				<o:p></o:p></span></p>\r\n		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">\r\n				<o:p>&nbsp;</o:p>\r\n				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"TEXT-DECORATION: underline; mso-ansi-language: ES-TRAD\">Tradición documental</span><span style=\"mso-ansi-language: ES-TRAD\">: \r\n						<o:p></o:p>\r\n						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">-Originales. \r\n								<o:p></o:p>\r\n								\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">-Borradores. \r\n										<o:p></o:p>\r\n										\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">-Copias (manuscritas) \r\n												<o:p></o:p></span></p>\r\n										\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">\r\n												<o:p>&nbsp;</o:p>\r\n												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"TEXT-DECORATION: underline; mso-ansi-language: ES-TRAD\">Contenido: \r\n														<o:p></o:p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b><span style=\"TEXT-DECORATION: underline; mso-ansi-language: ES-TRAD\">Descripción de contenido por cajones numerados: \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">1- Documentación personal \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Clasificaciones escolares. Boleta de inscripción a examen de facultad. Fotocopia autenticada del acta de defunción. Plano de propiedades en Victoria, Provincia de Entre Ríos. Certificados de enrolamiento en la Guardia Nacional. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">2- Diarios / Memorias / Evocaciones / Reseñas de viajes \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Libreta de notas: Diario de viaje a Santiago del Estero (1869). \"Cartera de apuntes” de su viaje a Bolivia (1872, incluye dibujos). Diario (1875, impresiones de viajes). Notas sueltas con descripciones de lugares y de viajes. Notas con síntesis de su accionar en la juventud. Datos geográficos. Memorias de sus cuarenta y un años. Evocaciones de la casa familiar. Porta documento con libreta con notas. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">3- Escritos I \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span lang=\"ES-AR\">Escritos personales / Entrevistas y reflexiones \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">4- Escritos II \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Páginas literarias / Poesías / Canciones / Escritos incompletos \r\n																<o:p></o:p></span></h1>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">5- </span><span lang=\"ES-AR\">Diario de Adolfo P. Carranza (Libro I: mayo 1907 - oct. 1912)</span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Descripción periódica de actividades. Reseña desde 1873 a 1906. Listados de medallas, placas, bustos y cuadros en los cuales Adolfo P. Carranza interviene en su realización. Libro copiador de correspondencia. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span lang=\"ES-AR\">6- Diario de Adolfo P. Carranza (Libro II: </span></b><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">octubre 1912 - agosto 1914) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Descripción periódica de actividades, hasta la fecha de su fallecimiento. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">7- Agendas / Índices \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b style=\"mso-bidi-font-weight: normal\"><span lang=\"ES-AR\">Índices de nombres. S/f. \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">8- Tarjetas personales / Tarjetas institucionales \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Adolfo P. Carranza / Carmen G. de Carranza \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Tarjetas personales, tarjetas institucionales y esquelas institucionales pertenecientes a Adolfo P. Carranza En su carácter de Secretario en la Legación Argentina en el Paraguay y como Director del Museo Histórico Nacional. Tarjeta personal de Carmen García de Carranza<span style=\"mso-spacerun: yes\">&nbsp; </span>y tarjetas de agradecimiento de ambos. (Sin inscripciones manuscritas). \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">9- Tarjetas personales \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Tarjetas personales entregadas a Adolfo P. Carranza (con/ sin inscripciones manuscritas). \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">10- Esquelas \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Esquelas dirigidas a Adolfo P. Carranza con/sin texto manuscrito. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">11- Homenajes a Adolfo P. Carranza \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Homenaje de 1891, por aniversario de la \"Revista Nacional”. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Homenaje del 24 de mayo de 1901. Otros \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">12- Homenaje a Adolfo P. Carranza (1910) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Comisión de homenaje al Dr. Adolfo P. Carranza. Incluye notas de salutación. Libreta índice de la Comisión de homenaje. Libreta con recortes de publicaciones (17 de octubre de 1910) \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">13- Material sobre Adolfo P. Carranza (1915-1950) de Arturo Carranza Casares \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Himno a Carranza (partitura y texto). Conferencia sobre el Museo Histórico Nacional (1950). Recortes periodísticos. Trascripción de escritos de A. P. Carranza. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">14- Correspondencia / Documentación. Ángel Fernando / Adolfo Esteban Carranza \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Ángel Fernando Carranza (abuelo): Carta familiar, de Mauro Carranza a su hermano Ángel Fernando (1834). \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Adolfo Esteban Carranza (padre): Libro copiador de correspondencia y documentación de la Representación en la República de Bolivia (1857-1870). Correspondencia emitida y recibida, documentación pública y privada. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">15- Correspondencia / Documentación. Ángel Justiniano Carranza \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Correspondencia emitida y recibida, documentación pública y privada. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">16- Correspondencia / Documentación. Carmen G. de Carranza \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Correspondencia emitida o recibida, de orden familiar. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">17- Agradecimientos de p&eacute;same / P&eacute;sames por la muerte de su hija (agosto de 1882) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Agradecimientos de p&eacute;same recibidos por Adolfo P. Carranza. Algunos con anotaciones manuscritas. Documentos y correspondencia relativa al fallecimiento de su hija, ocurrido a temprana edad en Asunción del Paraguay. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">18- P&eacute;sames por la muerte de su padre (junio 1896) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><span style=\"mso-bidi-font-weight: bold\" lang=\"ES-AR\">Correspondencia, telegramas y tarjetas de p&eacute;same por la muerte de su padre Adolfo Esteban Carranza. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">19- P&eacute;sames por la muerte de su hermana (agosto de 1902) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><span style=\"mso-bidi-font-weight: bold\" lang=\"ES-AR\">Correspondencia, telegramas y tarjetas de p&eacute;same por la muerte de su hermana Blanca Carranza. Caja en la que fueron guardadas las tarjetas. Con texto manuscrito de Adolfo P. Carranza. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">20- P&eacute;sames<span style=\"mso-spacerun: yes\">&nbsp; </span>por la muerte de su madre (marzo 1912) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><span style=\"mso-bidi-font-weight: bold\" lang=\"ES-AR\">Correspondencia, telegramas y tarjetas de p&eacute;same. Agenda índice de quienes presentaron condolencias y recortes periodísticos por el fallecimiento de su madre, María Eugenia del Mármol de Carranza. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">21- Invitaciones \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Notas y esquelas de invitación a eventos, actos, inauguraciones, etc. dirigidas a Adolfo P. Carranza. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">22- Menús \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Menús correspondientes a banquetes ofrecidos en diversas conmemoraciones. (1874-1914) \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">23- Programas / Pasajes / Pases / Comprobantes / Folletos \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Programas de actos públicos, pases de instituciones, congresos, eventos, comprobantes de acreditación. \r\n																<o:p></o:p></span></p>\r\n														\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">24- Facturas / Recibos / Sobres con inscripciones / Carpetas (sin contenido) \r\n																	<o:p></o:p></span></b></p>\r\n														\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">25- Fotografías</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																<o:p></o:p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Fotografías de la vida pública: inauguración de monumentos, de subterráneos. Ágapes. Fotografías familiares: individuales y grupales, en sus lugares de residencia. Fotografías de la provincia de Catamarca. Fotografías/retratos de otras personalidades (algunas en formato de postal). Cabe hacer mención de la existencia de fotografías de Adolfo P. Carranza en el Archivo Fotográfico del Museo debidamente catalogadas con anterioridad a este ordenamiento, al cual corresponderá su inclusión. (Remitidas al archivo fotográfico de MHN) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">26- Postales (I) \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Europa (España, Portugal, Italia, Reino Unido, Alemania, Holanda, B&eacute;lgica, Suiza, Francia, Mónaco) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Asia, África (India, Dakar) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Am&eacute;rica (Brasil, Chile, Colombia, Cuba, Ecuador, Estados Unidos, Panamá, Perú, Uruguay, Venezuela) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Otras (fotografías de personajes públicos, del retrato de Adolfo P. Carranza, de texto) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">27- Postales (II) \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; LINE-HEIGHT: normal; MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText2\"><span style=\"mso-bidi-font-weight: bold\" lang=\"ES-AR\">Argentina (Ciudad de Buenos Aires, Provincia de Buenos Aires, Catamarca, Córdoba, Corrientes, Entre Ríos, Jujuy, Mendoza, Patagonia, Salta, Santa Fe, Tucumán). Recibidas y enviadas por A. P. Carranza \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">28- Correspondencia general \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">(No incluida en series de correspondencia ni por orden temático) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">29- Correspondencia general \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">(No incluida en series de correspondencia ni por orden temático) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">30- Correspondencia general / Correspondencia incompleta/no identificada / Correspondencia por Adolfo P. Carranza</span></b><span style=\"mso-ansi-language: ES-TRAD\"> (No incluida en series de correspondencia ni por orden temático) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">31- Correspondencia (Países americanos I) \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">(Proveniente de países americanos. Incluye series de correspondencia). Bolivia ( M. Baptista, Jos&eacute; D. Barrios, Modesto Omiste). Brasil. Colombia. Cuba (Antonio Alcover). Chile (Diego Barros Arana, Adolfo Ibáñez, Justa A de Las Heras, María Teresa G. de las Heras, Mercedes A. de Las Heras, Jos&eacute; Toribio Medina, Pedro Montt). Ecuador. M&eacute;xico. Paraguay (Guillermo Stewart) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">32- Correspondencia<span style=\"mso-spacerun: yes\">&nbsp; </span>(Países americanos II) \r\n																			<o:p></o:p></span></b></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">(Proveniente de países americanos. Incluye series de correspondencia) \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Perú (Jacinto García, Ricardo Palma, Carlos Paz Soldán, Felipe Paz Soldán, Ernesto de Pinto, Domingo de Vivero).<span style=\"mso-spacerun: yes\">&nbsp; </span>Puerto Rico. Uruguay (A. Eastman, L. Melián Lafinur). Venezuela. \r\n																		<o:p></o:p></span></p>\r\n																\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">33- Series de correspondencia A-B</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																		<o:p></o:p>\r\n																		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 1: Luis AGOTE, Donato ALVAREZ, Juan AMBROSETTI. \r\n																				<o:p></o:p></span></p>\r\n																		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 2: Tomás Manual de ANCHORENA, Benjamín ARAOZ. \r\n																				<o:p></o:p></span></p>\r\n																		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 3: Jos&eacute; Juan BIEDMA, Fray Jos&eacute; M. BOTTARO, Fray Zenón BUSTOS. \r\n																				<o:p></o:p></span></p>\r\n																		\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">34- Series de correspondencia C-E</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																				<o:p></o:p>\r\n																				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Carpeta 4: Jos&eacute; Z. CAMINOS, Evaristo CARRIEGO, Eduardo COLOMBRES, Tomás R. CULLEN.</p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 5: Adolfo DECOUD, Jos&eacute; Segundo DECOUD. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">35- Series de correspondencia F-L \r\n																							<o:p></o:p></span></b></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 6: Jos&eacute; FIGUEROA ALCORTA, Ignacio H. FOTHERINGHAM. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 7: Martín GARCÍA MEROU, Jos&eacute; Ignacio GARMENDIA, Carlos GUIDO Y SPANO. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 8: Bernardo de IRIGOYEN, Samuel A. LAFONE QUEVEDO, Andr&eacute;s LAMAS. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 9: Ramón LASSAGA, Dolores LAVALLE de LAVALLE, Vicente Fidel LOPEZ \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">36- Series de Correspondencia M-N \r\n																							<o:p></o:p></span></b></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 10: Lucio V. MANSILLA, Juan R. MANTILLA, Manuel MANTILLA. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 11: Bartolom&eacute; MITRE, Jos&eacute; María MORENO, Jos&eacute; Pascasio MORENO, Rómulo S. NAÓN. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">37- Series de Correspondencia O-Q \r\n																							<o:p></o:p></span></b></p>\r\n																				\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Carpeta 12: Pastor OBLIGADO, Fray Pacífico OTERO, Carlos PELLEGRINI, Juan A. PRADÈRE.</p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 13: Ernesto QUESADA, Vicente G. QUESADA, Manuel QUINTANA. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">38- Series de Correspondencia R-S \r\n																							<o:p></o:p></span></b></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 14: Julio A. ROCA, Dardo ROCHA, Marqu&eacute;s de ROJAS, Manuela ROZAS DE TERRERO. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 15: Luis SAENZ PEÑA, Roque SAENZ PEÑA, Adolfo SALDÍAS. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 16: Carlos TEJEDOR, Wenceslao TELLO, Manuel M. TERRERO, Manuel Ricardo TRELLES. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Carpeta 17: Jos&eacute; Evaristo URIBURU, Mariano de VEDIA, Estanislao ZEBALLOS. \r\n																						<o:p></o:p></span></p>\r\n																				\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">39- Correspondencia / Documentación /Notas \r\n																							<o:p></o:p></span></b></p>\r\n																				\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-SIZE: 12pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'\">Esculturas / Monumentos / Estatuas / Mausoleos</span><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-WEIGHT: normal; mso-bidi-font-weight: bold\"> \r\n																							<o:p></o:p></<?xml:namespace>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Miembros de la Primera Junta, Patricias Argentinas, Batalla de Salta, Batalla de Ituzaingo, Imprenta, La Independencia, El Centenario, Expedicionarios al Desierto, Escudo Argentino (Tandil), la Reconquista. A: M. Moreno, B. Rivadavia, G. Brown, B. Rivadavia, F. L. Beltrán, M. Dorrego, F. J. C. Rodríguez, N. Avellaneda, A. Alsina, C. Saavedra, M. Belgrano, Jos&eacute; Mármol, Jos&eacute; de San Martín (Lima, Mendoza, San Lorenzo, Boulogne Sur Mer, El Callao, Yapeyú), San Francisco Solano (Santiago del Estero), González Ocampo (La Rioja), B. Mitre. J. M. Pueyrredón (Flores, Mar del Plata), C. Colón (Buenos Aires, Chivilcoy), V. López y Planes, G. de Las Heras, J. de Olavarría.</span></p>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span lang=\"ES-AR\">40</span></b><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">- Correspondencia / Documentación / Notas. \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Esculturas / Monumentos / Bustos \r\n																								<o:p></o:p></span></h1>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Correspondencia con<span style=\"mso-spacerun: yes\">&nbsp; </span>Jacques BRODSKY, Lucio CORREA MORALES, Gustav<span style=\"mso-spacerun: yes\">&nbsp; </span>EBERLEIN, Torcuato TASSO. Otros.<span style=\"mso-spacerun: yes\">&nbsp; </span></span><span style=\"mso-ansi-language: ES-TRAD\">Monumento a Antonio ZINNY. \r\n																								<o:p></o:p></span></p>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">41- Correspondencia / Documentación / Notas \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Obras pictóricas \r\n																								<o:p></o:p></span></h1>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Sobre obras pictóricas encomendadas para el Museo Histórico Nacional. Series de correspondencia de Juan BLANES, Jean BOUCHET, Eliseo COPINI, Guillermo<span style=\"mso-spacerun: yes\">&nbsp; </span>DA RE, Pedro SUBERCASEUX. Otros. Reproducciones de retratos de próceres enviados a municipios. \r\n																								<o:p></o:p></span></p>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">42- Correspondencia / Documentación / Notas \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Láminas / Medallas / Placas \r\n																								<o:p></o:p></span></h1>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Encomendadas por Adolfo P Carranza en ocasión de conmemoraciones o, en el caso de las láminas para ser difundidas en todo el país. \r\n																								<o:p></o:p></span></p>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">43- Correspondencia / Documentación / Notas \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Nominación de estaciones de FFCC. / Parques / Plazas \r\n																								<o:p></o:p></span></h1>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Consultas sobre sellos postales y cuestiones de límites \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">44- Correspondencia / Documentación / Notas \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h2 style=\"LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"FONT-SIZE: 12pt\">Centenario de Mayo / Panteón Nacional / Proyecto Centenario de la Independencia \r\n																									<o:p></o:p></span></b></h2>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">45- Correspondencia / Notas \r\n																									<o:p></o:p></span></b></p>\r\n																						\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Asuntos históricos \r\n																								<o:p></o:p></span></h1>\r\n																						\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">46- Escritos</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																								<o:p></o:p>\r\n																								\r\n<h2 style=\"LINE-HEIGHT: normal; MARGIN: 0cm 0cm 0pt\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"FONT-SIZE: 12pt\">Temas históricos (Anteriores a S. XIX y S. XIX) \r\n																											<o:p></o:p></span></b></h2>\r\n																								\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">47- Escritos</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																										<o:p></o:p>\r\n																										\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Temas históricos. (S. XIX) \r\n																												<o:p></o:p></span></h1>\r\n																										\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">48- Escritos \r\n																													<o:p></o:p></span></b></p>\r\n																										\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Temas históricos (S. XIX y XX) \r\n																												<o:p></o:p></span></h1>\r\n																										\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">49- Transcripciones de documentos \r\n																													<o:p></o:p></span></b></p>\r\n																										\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Efectuadas por Adolfo P. Carranza</span></b><span style=\"mso-ansi-language: ES-TRAD\">. \r\n																												<o:p></o:p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">50- Transcripciones de documentos / Facsímiles \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<h1 style=\"MARGIN: 0cm 0cm 0pt\"><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt\">Efectuadas por Adolfo P. Carranza \r\n																														<o:p></o:p></span></h1>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">51- Actividad pública anterior a 1881 / Legación argentina en el Paraguay (1881-1883) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Correspondencia y documentación de los períodos referidos.</p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">52- Ministerio del Interior (1883-1886) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">53- Gestión pública:</span></b><span lang=\"ES-AR\"> <b style=\"mso-bidi-font-weight: normal\">Consejo Nacional de Educación, Colegio Nacional, Correspondencia de Presidencia de la Nación. \r\n																															<o:p></o:p></b></span></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">54- Museo Histórico (1889-1892) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Documentación institucional. \r\n																														<o:p></o:p></span></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">55- Museo Histórico Nacional (1893-1899) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Documentación institucional. \r\n																														<o:p></o:p></span></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">56- Museo Histórico Nacional (1900-1914) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Documentación Institucional \r\n																														<o:p></o:p></span></p>\r\n																												\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">57- Museo Histórico (1889-1892) / Museo Histórico Nacional (1892-1896) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Notas I (1892-1896) (</span></b><span lang=\"ES-AR\">Libros copiadores de documentación emitida) </span></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">58- Museo Histórico Nacional \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Notas II (1896-1904) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">59- Museo Histórico Nacional \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Notas III (1904-1910) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">60- Museo Histórico Nacional \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Notas IV (1910-1914) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">61- Museo Histórico Nacional \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">Libro de Registro Administrativo (1894-1902). Libro de Gastos Eventuales (1890) \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">62- Publicaciones \r\n																															<o:p></o:p></span></b></p>\r\n																												\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Revista Nacional (1886), Homenajes Patrióticos (1901), San Martín (1905), San Martín. Su correspondencia (1906)<b style=\"mso-bidi-font-weight: normal\"> \r\n																															<o:p></o:p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">63- Publicaciones \r\n																																		<o:p></o:p></span></b></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">El Clero Argentino (1907-1908), Memorias y Autobiografías (1909) \r\n																																	<o:p></o:p></span></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">64- Publicaciones \r\n																																		<o:p></o:p></span></b></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Ilustración Histórica (1910), San Martín. Su correspondencia<span style=\"mso-spacerun: yes\">&nbsp; </span>(1910), Días de Mayo. Actas del Cabildo de Buenos Aires<span style=\"mso-spacerun: yes\">&nbsp; </span>(1910), Actas del Cabildo de Buenos Aires (1912), Patricias Argentinas (1913). Difusión bibliográfica. \r\n																																	<o:p></o:p></span></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span lang=\"ES-AR\">65- Correspondencia / Documentación. Asociaciones, Centros, Sociedades \r\n																																		<o:p></o:p></span></b></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Asociación de Instrucción Militar, El Ateneo, Asociación Literaria, Asociación Nacional Pro-Patria de Señoritas. Asociación Pro-Patria. Asociación Patriótica estudiantil General San Martín, Asociación Patriótica, Juventud de Mayo, Asociación Nacional de Damas Descendientes de Guerreros y próceres de la Independencia, Asociación Patriótica la Bandera Argentina, Ateneo Nacional, Ateneo de Lima, Asociación \"Dr. Mariano Moreno”. Biblioteca nacional \"Non Plus Ultra”, Centro de Estudios Históricos Literarios \"Comandante Espora”, Centro literario \"Bartolom&eacute; Mitre”, Centro \"Bernardino Rivadavia”, Centro Literario \"Esteban Echeverría”, Centro Literario \"Manual Belgrano”, Centro Literario Nicolás Avellaneda” Centro Argentino Montevideo, Centro Patriótico Estudiantil, Centro Científico Literario, Comisión Organizadora de Juegos Florales de Santa Fe, Comisión para la Exposición Nacional de 1898, Club General San Martín, Club Gimnasia y Esgrima, Comisión Patriótica Parroquia de San Telmo, Junta de Historia y Numismática Americana, Junta Filantrópica Balvanera Norte y Oeste, Junta Patriótica Argentina, Liga Patriótica Nacional, Sociedad Rural Argentina, Sociedad Científico Literaria (Mar del Plata), Sociedad de estudio \"Belgrano”, Sociedad \"Ensayos científico-literarios”, Sociedad Geográfica de Lima, Sociedad Literaria Inglesa, Sociedad \"Patricias Argentinas”, Sociedad Santa Marta (Carmen G. Carranza) \r\n																																	<o:p></o:p></span></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">66- Congresos \r\n																																		<o:p></o:p></span></b></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Congreso Americano de Artes, Letras y Ciencias Morales (Buenos Aires, 1899), 2º Congreso Científico Latino Americano (Montevideo, 1901), 4º Congreso científico, 1º Pan Americano (Santiago de Chile, 1908) XVII Congreso de Americanistas (La Plata, 1910). \r\n																																	<o:p></o:p></span></p>\r\n																															\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">67- Homenajes</span></b><span style=\"mso-ansi-language: ES-TRAD\"> \r\n																																	<o:p></o:p>\r\n																																	\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Centenarios de natalicios (Jos&eacute; de San Martín, Simón Bolívar, Jos&eacute; María Paz, Benito Nazar, Juan Thorne, Tomás Guido, Juan Lavalle, Gregorio Aráoz de La Madrid, Nicolás Rodríguez Peña, Coronel Lorenzo Lugones ) Jubileos (B. Mitre, Benjamín Victorica, Jos&eacute; E. Uriburu,<span style=\"mso-spacerun: yes\">&nbsp; </span>Homenajes a la memoria : a Carlos Tejedor, Ángel Justiniano Carranza, Adolfo Alsina, Manuel R. Trelles. \r\n																																			<o:p></o:p></span></p>\r\n																																	\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Repatriación, traslado y recepción de restos: Notas, apuntes, Impresos de discursos y programa de repatriación de restos del General San Martín. ( 1880) Recepción de restos de Gregorio de Las Heras. Brigadier General Manuel Hornos, General Juan E. Pedernera. \r\n																																			<o:p></o:p></span></p>\r\n																																	\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">68- Asuntos políticos. \r\n																																				<o:p></o:p></span></b></p>\r\n																																	\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><span style=\"mso-ansi-language: ES-TRAD\">Correspondencia relativa a asuntos políticos. Boletas electorales. Invitaciones a reuniones políticas. Propagandas de candidaturas. Libro de Actas del \"Comit&eacute; Conciliación” de Belgrano (1877). Documentos relativos al Partido Autonomista. \r\n																																			<o:p></o:p></span></p>\r\n																																	\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Comicios: Documentos relativos a empadronamientos, Registro Cívico, Censos electorales. \r\n																																			<o:p></o:p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">69- Recortes periodísticos I \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">70- Recortes periodísticos II \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">71- Impresos \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">72- Libros de Donaciones. Carpetas I a IV (Fotocopias) \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">Material contenido en los libros \"Documentos de Donaciones” ordenadas en carpetas según el contenido de los originales.</p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">73- Libros de Donaciones Carpetas V a VIII (Fotocopias) \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-ansi-language: ES-TRAD\">74- Libros de Donaciones Carpetas IX a XII (Fotocopias) \r\n																																						<o:p></o:p></span></b></p>\r\n																																			\r\n<p style=\"MARGIN: 0cm 0cm 6pt\" class=\"MsoBodyText\"><b><span lang=\"ES-AR\">75-</span></b><span lang=\"ES-AR\"> <b>Libros de Donaciones Carpetas XIII a XVI (Fotocopias) / Carpeta Archivo Carranza en el Archivo General de la Nación (</b>Catálogo y digitalización)</span></p>\r\n																																			\r\n<p style=\"TEXT-INDENT: 35.4pt; MARGIN: 0cm 0cm 0pt\" class=\"MsoBodyText3\">&nbsp;</p></omicios:></p></<?xml:namespace></p></<?xml:namespace></span></p></span></p></<?xml:namespace></p></<?xml:namespace></p></span></h1></<?xml:namespace></p></<?xml:namespace></p></<?xml:namespace></p></ontenido:></p></?xml:namespace></p></span></p></span></p></span></p></?xml:namespace></p></?xml:namespace></p>','6 metros lineales','10.000. unidades documentales','\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Adolfo Pedro Carranza nace en Buenos Aires, el 7 de agosto de 1857. Era hijo de Adolfo Esteban Carranza y de María Eugenia del Mármol. Hizo sus primeros estudios en el Colegio San Martín y en 1875 ingresa en la Universidad de Buenos Aires. Ya desde adolescente demostró su inquietud por la historia, en el marco de una familia en contacto con el interior, Santiago del Estero y Catamarca y de amplias relaciones con los personajes públicos de la &eacute;poca.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Antes de 1880 comienza su carrera en la administración pública como empleado del Ministerio del Interior. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">De 1881 a 1883 se desempeña como secretario de la Legación y encargado de negocios en el Paraguay. Allí funda un centro social y de estudios, y una revista.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">De regreso a Buenos Aires es Jefe de sección en el Ministerio del Interior, cargo que abandona al asumir el poder el doctor Juárez Celman, a quien había combatido su candidatura. Se retira entonces de la actividad pública en la Administración Nacional.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">A comienzos de 1886 funda <i>Revista Nacional </i>una importante publicación de historia, letras y jurisprudencia, de la cual es su director hasta 1893.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Para esa fecha había cumplido su mayor objetivo: promovió la creación y fue el primer Director del Museo Histórico (primeramente \"de la Capital”, luego \"Nacional”), creado por decreto del intendente Francisco Seeber, el 24 de mayo de 1889.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Muchas fueron las publicaciones que impulsó y cientos sus escritos sobre historia argentina. Dirigió La Ilustración Histórica Argentina (1908) y La Ilustración Histórica (1911).\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Algunos de sus trabajos fueron recopilados en forma de libros: <i>Leyendas nacionales, Razón del nombre de las calles, plazas y parques de la ciudad de Buenos Aires, Hojas históricas, Patricias Argentinas, Días de Mayo</i>, y sobre todo su <i>Archivo General de la República Argentina</i> con la reproducción de un gran número de documentos.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Desde el Museo Histórico Nacional encomendó innumerable cantidad de obras pictóricas y participó en la realización de los monumentos a próceres, placas, medallas conmemorativas, láminas, reproducción de retratos, homenajes en centenarios de natalicios, repatriación de restos y otras muchas formas de preservar la memoria y difundir la historia de su país. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Formó parte de asociaciones, clubes, sociedades, entre muchas otras agrupaciones públicas, fue miembro de la Junta de Historia y Numismática.\r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Fallece repentinamente por enfermedad, en Buenos Aires el 15 de agosto de 1914, a los 57 años.<br />\r\n		\r\n		<o:p></o:p></span></p>','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: SimSun; mso-ansi-language: ES-TRAD; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">El Archivo reúne documentos producidos con anterioridad a su vida pública, y a todas las etapas de sus gestiones públicas y a su vida privada. Tambi&eacute;n reúne documentos producidos por sus familiares Ángel Fernando (abuelo), Adolfo Esteban (padre), y Ángel Justiniano (tío) Carranza, así como de su esposa, Carmen García. </span>','La documentación fue donada por su esposa al Museo Histórico Nacional en 1919, por carta escrita al entonces director del mismo, Antonio Dellepianne, con expreso pedido de que en ese lugar permaneciesen todos los documentos que pertenecieron a su marido.\r','Esposa','20110101',NULL,NULL,NULL,NULL,'d','t','\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">El acceso al FONDO ADOLFO PEDRO CARRANZA se realiza previa solicitud escrita por correo, personalmente o e-mail, dirigida al Director del MHN. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Se diligencia internamente, ante el Área de Administración para gestionar la autorización. \r\n		<o:p></o:p></span></p>\r\n\r\n<p style=\"TEXT-ALIGN: justify; MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span style=\"mso-ansi-language: ES-TRAD\">Cumplido, se acuerda en forma telefónica o por e-mail día y hora de la consulta. \r\n		<o:p></o:p></span></p><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: SimSun; mso-ansi-language: ES-TRAD; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">Se pueden efectuar consultas sobre la existencia de documentación en el Archivo, telefónicamente 011-4307-1182, dirigi&eacute;ndose a la que suscribe o vía e-mail a : archivohistorico@mhn.gov.ar<br />\r\n	<br />\r\n	<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: SimSun; mso-ansi-language: ES-TRAD; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">Existen unidades documentales del ARCHIVO CARRANZA que han sido trascriptas o reproducidas en diversas publicaciones desde el siglo XIX a la fecha<br />\r\n		<br />\r\n		</span></span>',NULL,1,'AR/PN/SC/DNPM/MHN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-29 13:27:14','camilo',4),
 ('AR/PN/SC/DNPM/MJN/FFE','AR/PN/SC/DNPM/MJN','Fotos Estereográficas','Fondo Fotos Estereográficas',NULL,'1999-01-01 00:00:00',NULL,NULL,NULL,'1999-01-01 00:00:00','2011-01-01 00:00:00','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES\">El acervo contiene documentos con material relativo a la forma de vida rural y a \r\n		<st1:personname w:st=\"on\" productid=\"la Estancia Jesuítica.\">la Estancia Jesuítica.</st1:personname> </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES\">Los tipos son documentos visuales: negativos fotográficos.</span></p><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">Se trata de originales en soporte vidrio</span>','1 metro lineal','1 caja: 32 fotografías estereográficas en vidrio','H','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">Originalmente perteneció a Rosario Cafferata de Aghina, despu&eacute;s a Adelita Aghina Cafferata y finalmente a Jorge Bettolli.</span>','Donación de Jorge Bettolli','P','20100101',NULL,NULL,NULL,NULL,'D','T','P',NULL,1,'AR/PN/SC/DNPM/MJN',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-29 09:12:50','camilo',4);
INSERT INTO `niveles` (`codigo_referencia`,`codigo_institucion`,`titulo_original`,`titulo_atribuido`,`titulo_traducido`,`fecha_registro`,`numero_registro_inventario_anterior`,`fondo_tesoro_sn`,`fondo_tesoro`,`fecha_inicial`,`fecha_final`,`alcance_contenido`,`metros_lineales`,`unidades`,`historia_institucional_productor`,`historia_archivistica`,`forma_ingreso`,`procedencia`,`fecha_inicio_ingreso`,`precio`,`norma_legal_ingreso`,`numero_legal_ingreso`,`numero_administrativo`,`derechos_restricciones`,`titular_derecho`,`publicaciones_acceso`,`subsidios_otorgados`,`tipo_nivel`,`cod_ref_sup`,`tv`,`numero_registro`,`tipo_acceso`,`requisito_acceso`,`acceso_documentacion`,`estado`,`normativa_legal_baja`,`numero_norma_legal`,`motivo`,`fecha_baja`,`fecha_ultima_modificacion`,`usuario_ultima_modificacion`,`na`) VALUES 
 ('AR/PN/SC/DNPM/MJN/FRPB','AR/PN/SC/DNPM/MJN','Raúl P. Bassani','Fondo Raúl P. Bassani',NULL,'1999-01-01 00:00:00',NULL,NULL,NULL,'2010-01-01 00:00:00','2011-01-01 00:00:00','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES\">El acervo contiene cartas, notas, inventarios y recibos del siglo XIX relativos a la familia O’Gorman y Marco del Pont. </span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES\">Los tipos son documentos textuales: manuscritos.</span></p><span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">Se trata de originales en soporte papel.</span>','1 metro lineal','2 carpetas que contienen 29 docum. del siglo XIX','No se obtienen','\r\n\r\n<p>Se desconocen</p>','Donación de Raúl P. Bassani','No se conoce','20110101',NULL,NULL,NULL,NULL,'D','T','Se desconoce',NULL,1,'AR/PN/SC/DNPM/MJN',1,NULL,NULL,NULL,'La consulta a los originales está restringida.',5,NULL,NULL,NULL,NULL,'2011-05-29 09:34:47','camilo',4),
 ('AR/PN/SC/DNPM/MM/FBM','AR/PN/SC/DNPM/MM','Fondo Bartolomé Mitre','Fondo Bartolomé Mitre',NULL,'0000-01-01 00:00:00',NULL,NULL,NULL,'1514-01-01 00:00:00','1965-01-01 00:00:00','Las fechas extremas de la documentación son 1514-1965, la documentación predominante abarca el período 1800-1900.El área geográfica que abarca es predominantemente el territorio nacional, aunque hay documentación referida a Sudam&eacute;rica. Tambi&eacute;n existe gran cantidad de documentación referida a la provincia de Buenos Aires. Asimismo hay documentación generada en exterior o que se refiere a asuntos del extranjero. Las series documentales más relevantes en cuanto a su volumen e información son: a) Correspondencia, b) Ordenes militares. Cepeda / Pavón / Guerra del Paraguay c) Informes militares d) Memorias militares. Guerra del Paraguay, e) Escritos históricos, f) Estudios lingüisticos. La subsección DOCUMENTOS DEL ARCHIVO COLONIAL merece especial antención por la complejidad de la documentación que agrupa. Las series documentales de esta subsección son: a) oficios reales, b) Reales c&eacute;dulas, c) Proclamas, d) Edictos, e) Cartas annuas, d) Correspondencia. Son documentos textuales manuscritos y cartográficos; hay originales y copias de &eacute;poca en soporte papel.<br />\r\n','31,40 metros lineales','22.892 documentos en papel','Nació en Buenos Aires. Hijo de Ambrosio Mitre y Josefa Martinez Wetherton.Vivió parte de su infancia en Uruguay donde inició su carrera militar y literaria. Formó parte del ej&eacute;rcito de Fructuoso Rivera y luchó contra las fuerzas de Oribe. Desterrado pasó a Bolivia y colaboró con el presidente Ballivian y luego a Chile. Participó en la batalla de Caseros (1852) y más tarde de la Asamblea Constituyente de 1854. Fue elegido gobernador de Buenos Aires (1852-1860) Llevó adelante la campaña de Cepeda (1859) y la de Pavón (1861). Fue elegido presidente constitucional de la República (1862-1868) reorganizó institucionalmente el país (justicia, educación, obras publicas) Fue Comandante en Jefe de las fuerzas aliadas durante la Guerra contra Paraguay (1865-1869) Fue diputado y senador nacional. En 1870 fundó el diario La Nacion. Escribió dos grandes estudios históricos \"Historia de Belgrano\" e \"Historia de San Martin\" , realizó la traducción de la Divina Comedia de Dante Alighieri, escribió varios ensayos literarios y estudios sobre lenguas aborígenes y arqueología.<br />\r\n','Este fondo fue coleccionado por Bartolom&eacute; Mitre a lo largo de toda su vida. No solamente resguarda sus papeles personales sino tambi&eacute;n las colecciones documentales sobre los temas históricos que &eacute;l mismo se dedicó a investigar.<br />\r\n','El fondo aparece como donación al gobierno nacional en la escritura Nº 468 del 15 de diciembre de 1906 para la apertura del Museo Mitre que fuera creado por ley ese mismo año. La escritura está firmada por el Dr. Enrique Garrido Escribano General de Gobie','P','20110101',NULL,NULL,NULL,NULL,'d','t','No hay publicaciones',NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,'2011-05-30 03:08:26','camilo',4),
 ('AR/PN/SC/DNPM/MM/FFT','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MM/FGEM','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MM/FGJSM','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MM/FGMB','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MM/FIEMYV','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MM/FWP','AR/PN/SC/DNPM/MM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MM',1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 ('AR/PN/SC/DNPM/MRPJAT/MT','AR/PN/SC/DNPM/MRPJAT','Museo Terry','Archivo de José Antonio Terry',NULL,'2011-01-01 00:00:00',NULL,NULL,NULL,'0000-01-01 00:00:00','0000-01-01 00:00:00','No hay datos','No hay datos','No hay datos','No hay datos','No hay datos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'AR/PN/SC/DNPM/MRPJAT',1,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2011-06-17 11:09:25','camilo',2),
 ('AR/PN/SC/DNPM/PSJ/FDC','AR/PN/SC/DNPM/PSJ','Fondo Dolores Costa','Sección  Libros de Administración',NULL,'2010-08-10 00:00:00','Carpeta 1345, Nª 2315- donación  Arturo Chespeer',0x00,NULL,'1774-01-16 00:00:00','1870-04-11 00:00:00','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">El fondo abarca cronológicamente desde los antecedentes familiares del General Urquiza y se extiende hasta su muerte acaecida en 1870. La documentación ofrece un amplio espectro temático en el que la figura del Gral. Urquiza se perfila como eje conductor de una etapa decisiva de la historia nacional y regional. Contribuye a un conocimiento más acabado de la figura del caudillo entrerriano y el de una &eacute;poca, con una marcada especificidad hacia lo económico y familiar. Fue constituido básicamente por las distintas administraciones llevadas a cabo durante la vida del General Urquiza. De carácter comercial, jurídico, político y familiar e integrada por documentos y libros contables, reflejan en forma pormenorizada especialmente la organización económica practicada en los numerosos emprendimientos realizados por el propietario. Contribuyen a conocer datos referidos a las colonias agrícolas, saladeros, comercio, navegación, bancos, así como de novedosas industrias para la &eacute;poca. Aporta asimismo la correspondencia íntima y familiar, datos referidos a diversos aspectos de la vida cotidiana de una familia representativa de una clase social y de una &eacute;poca en el ámbito rural. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Los tipos documentales son: documentos textuales manuscritos e impresos: acciones, cartas, contratos, esquelas, expedientes,<span style=\"mso-spacerun: yes\">&nbsp; </span>facturas de pago, recibos, vales, etc. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Se trata de originales en soporte papel y pergamino. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Planos y croquis y mapas: Originales, en soporte papel y papel entelado \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Documentos cartográficos textuales manuscritos e impresos: mapas, planos, croquis.: Originales y copias en soporte papel y entelados. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Libros contables originales y libros copiadores, foliados y algunos sin foliar. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Libretas y<span style=\"mso-spacerun: yes\">&nbsp; </span>cuadernos. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Documentos microfilmados. \r\n			<o:p></o:p></span></i></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">(Realizado por</span></i><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-bidi-font-family: \'Times New Roman\'\" lang=\"ES\"> </span></i><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">SC. DNPM. Palacio San Jos&eacute;. Museo y Monumento Histórico Nacional Justo Jos&eacute; de Urquiza. Archivo Palacio San Jos&eacute;. : Barreto, Ana M. Bonus, Esteban R .Delzart, Alicia M Gradizuela, Mabel M.Izaguirre, Maria A.Rocca, Gabriela. \r\n			<o:p></o:p></span></i></p>','15 metros lineales','115000 documentos','<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\"><em>Justo Jos&eacute; de Urquiza nació el 18 de octubre de 1801. Tiempos de profundas transformaciones gestadas en el siglo anterior. La revolución francesa en lo político y la industrial en lo económico y tecnológico se combinaron para impactar decididamente sobre las estructuras sociales. Realismo, romanticismo, positivismo, liberalismo, capitalismo, socialismo utópico, comunismo, fueron entre otras, las expresiones que tomaron forma en este nuevo siglo para reflejar esos cambios. <br />\r\n		<br />\r\n		\r\n		<st1:personname w:st=\"on\" productid=\"La Argentina\">La Argentina</st1:personname> abandonó, por la fuerza revolucionaria, el dominio español para ingresar así en el tránsito del complejo camino de vida independiente. Etapa caracterizada por la inestabilidad y los enfrentamientos armados en la que el nuevo país intentó adquirir el perfil de nación organizada. Difícil objetivo: las luchas civiles y la disolución de las autoridades nacionales apenas una d&eacute;cada despu&eacute;s de iniciado el proceso independentista daban cuenta de esa realidad. Los esfuerzos por dar forma orgánica a la incipiente nación fueron vanos. Las diferencias ideológicas primaron sobre la necesidad de organizar un nuevo gobierno. Monárquicos y republicanos, unitarios y federales fueron algunas de las fuerzas dicotómicas que intentaron dirimir sus diferencias en los campos de batalla.<br />\r\n		<br />\r\n		Los factores situacionales prefijan condiciones políticas, económicas y sociales delimitadoras de un contexto historio-social específico, escenario en el que los \"hombres producen –al decir de Anthony Giddens- la sociedad, pero lo hacen como actores históricamente situados, no bajo las condiciones de su propia elección.” Y esas condiciones que limitan el obrar humano no son solamente constrictivas sino tambi&eacute;n habilitadoras del hacer. Especialmente para aquellos actores sociales que poseen la capacidad de ser protagonistas de los procesos de transformación de una sociedad a trav&eacute;s de su comportamiento y desempeño. De su capacidad para abordar, procesar y actuar sobre las condiciones históricas en las que se sitúan. Justo Jos&eacute; de Urquiza se constituyó, así, en uno de los protagonistas de su tiempo.<br />\r\n		<br />\r\n		Su padre Joseph de Urquiza – de origen vasco - se radicó en la provincia de Entre Ríos junto a su esposa Cándida García y su numerosa familia, para dedicarse a la actividad rural pero tambi&eacute;n a la función pública. Los sucesos de Mayo de 1810 lo obligaron a emigrar a \r\n		<st1:personname w:st=\"on\" productid=\"la Banda Oriental\">la Banda Oriental</st1:personname> para poder sostener su fidelidad a España. En 1812 pudo retornar, para continuar con una vida circunscripta al ámbito rural y sujeto a los avatares de los cambiantes gobiernos locales. Allí educó a su prole, apegados a la libertad que la naturaleza ofrece y al amor al terruño como forma de identificación con su espacio vital. <br />\r\n		<br />\r\n		En 1817 Justo Jos&eacute; fue enviado al Colegio de San Carlos en Buenos Aires, al que debió abandonar dos años más tarde ante la clausura de los cursos, retornando a su tierra natal. Instalado en Concepción del Uruguay, se dedicó a las actividades económicas en las que demostró un espíritu arriesgado y progresista, necesario para triunfar en el ámbito comercial. <br />\r\n		<br />\r\n		A los 19 años fue padre de una niña. Este hecho, sumado a su negativa sostenida al casamiento, su fortuna creciente y su agraciada presencia, le otorgaron una fama donjuanesca particular que trascendió los límites de su ciudad natal.<br />\r\n		<br />\r\n		A mediados de la d&eacute;cada del 20 se inició en la actividad política. Contaba con poco más de veinte años cuando se comprometió fervientemente a defender el ideario federal. En el Congreso provincial, para el que fue designado diputado por los vecinos de Concepción del Uruguay en 1826, comenzó con sus primeras experiencias en la vida pública.<br />\r\n		<br />\r\n		Entre Ríos se debatía entonces en una fuerte anarquía. Entre las influencias de Buenos Aires y Santa Fe, los gobernadores entrerrianos se sucedían ininterrumpidamente por el poco tiempo que duraban las alianzas para sostenerlos, en medio de intrigas y traiciones. La incipiente república, despu&eacute;s del fracaso del Congreso de Tucumán, ensayaba un nuevo proyecto constitucional con Rivadavia, que se transformó en un nuevo fracaso al intentar imponer un modelo contrario a la voluntad de las provincias. <br />\r\n		<br />\r\n		Pero el deseo de organizarse permanecía incólume. Así lo demostró el Pacto Federal firmado en 1831, vigente por más de veinte años. Este creó, con la adhesión de todas las provincias, \r\n		<st1:personname w:st=\"on\" productid=\"la Confederaci&#65533;n Argentina\">la Confederación Argentina</st1:personname> y tomó el compromiso de organizarse jurídicamente en un Congreso General bajo el sistema federal. Juan Manuel de Rosas asumió el manejo indiscutido del país, dispuesto a restaurar la amenazada tranquilidad pública, imponiendo el orden por la fuerza.<br />\r\n		<br />\r\n		La posición estrat&eacute;gica de la provincia de Entre Ríos, por su proximidad con el Imperio del Brasil y \r\n		<st1:personname w:st=\"on\" productid=\"la Rep&#65530;blica Oriental\">la República Oriental</st1:personname> del Uruguay la convirtieron en epicentro de conflictos que fueron más allá de las luchas fraticidas, y en las que se mezclaron intereses y alianzas internacionales. Campo de duras batallas donde a fuerza de lanza y de sangre se dirimían las ideas. Rosas, Oribe, Echagüe, Urquiza por un lado, Rivera, Lavalle, Paz, Berón de Astrada, Ferre por otro, son los protagonistas de años de lucha y sangre.<br />\r\n		<br />\r\n		En los campos de batalla Justo Jos&eacute; de Uquiza comenzó a distinguirse nítidamente, entre los suyos primero y ante sus adversarios despu&eacute;s. Dos frentes de batalla arreciaban la provincia: Corrientes y las costas del Uruguay. En ambos, los triunfos de las armas consolidaron el prestigio del estratega. Así, los congresales entrerrianos en 1841, ante la necesidad de elegir un nuevo gobernador, proponen al General Urquiza para desempeñar el cargo.<br />\r\n		<br />\r\n		En 1842 asumió por primera vez la gobernación entrerriana. Siendo reelecto en el cargo en 1845 y nuevamente en 1849 y 1853. Años de campañas militares marcada por &eacute;xitos y fracasos en los que Entre Ríos adquirió preponderancia y fuerza en la defensa del sistema de la federación. Triunfos contundentes como Arroyo Grande, India Muerta, Laguna Limpia, Vences afianzaron la autoridad y el orden en el convulsionado litoral. <br />\r\n		<br />\r\n		&Eacute;poca de obediencia irrestricta y mando incuestionable. En la que el coraje, la severidad de conducta, la rigurosidad en la aplicación de castigos y premios y el magnetismo personal, crearon una forma de conducción que se repitió en todo el país. El caudillo fue la expresión de ese tiempo. Dominación carismática estructurada sobre la base de la creencia en la legitimidad del poder fundada en el reconocimiento de heroísmo o ejemplaridad de la figura. Legitimidad que justificaba la interacción de poder y autoridad entre mandante y mandados bajo el consentimiento mutuo.<br />\r\n		<br />\r\n		Se sumó para robustecer este fenómeno la instabilidad política y las prolongadas campañas militares que se produjeron desde los albores de la vida independiente. Contra los españoles primero, entre hermanos por diferentes consignas políticas despu&eacute;s. Fuerte presencia, autoridad, respeto y fuerza militar, constituyeron entonces las notas distintivas que fue asumiendo el ejercicio de poder en esta etapa de \r\n		<st1:personname w:st=\"on\" productid=\"La Argentina\">la Argentina</st1:personname> en construcción. El caudillo se transformó en el conductor por excelencia, convirtiendo los campos de batalla en escenarios de aprendizaje y graduación. El aislamiento, la falta de comunicación, la escasez poblacional y la inmensidad territorial tambi&eacute;n aportó lo suyo. La autoridad se reconocía como condición natural, pero tambi&eacute;n se forjaba, se ganaba en las reyertas con el respeto y la obediencia de paisanos y milicianos que confiaban en el caudillo, movidos por una subordinación mezcla de amor, respeto y temor. <br />\r\n		<br />\r\n		Emparentado con Rosas en el ejercicio autoritario del poder provincial, compartió el ideario federal y defendió el proyecto confederal en la frontera del Uruguay. Pero se diferenció nítidamente del gobernador porteño al comprender la importancia del orden constitucional como premisa para asegurar la continuidad del orden y el progreso del país, como así tambi&eacute;n una exigencia de desarrollo económico. <br />\r\n		<br />\r\n		El 1 de mayo de 1851, el decreto conocido como el Pronunciamiento significó el rompimiento entre Urquiza y Rosas. La batalla de Caseros los enfrentó el 3 de febrero de 1852 para cambiar una historia de más de veinte años. El triunfo del entrerriano posibilitó el inicio de la etapa constitucional concretada un año más tarde.<br />\r\n		<br />\r\n		El triunfo de Caseros y el posterior Acuerdo de San Nicolás celebrado entre los gobernadores en mayo de 1852, profundizó la dicotomía de intereses entre Buenos Aires y el interior, defendiendo ambos ideales económicos contrapuestos. Los caminos se bifurcaron entre la ciudad portuaria y el resto del país con la revolución de septiembre de 1852. La conformación de \r\n		<st1:personname w:st=\"on\" productid=\"la Confederaci&#65533;n\">la Confederación</st1:personname> por un lado y el Estado de Buenos Aires por otro fue una realidad, a pesar de los intentos conciliadores con resultados efímeros de casi una d&eacute;cada. No dejaban sin embargo de reconocer ambas que formaban parte de una unidad, una historia común las hermanaba. <br />\r\n		<br />\r\n		\r\n		<st1:personname w:st=\"on\" productid=\"La Constituci&#65533;n Nacional\">La Constitución Nacional</st1:personname> sancionada en 1853 definió la pugna por el sistema organizativo entre unitarios y federales. Pero tambi&eacute;n inició un nuevo tiempo. La apertura del país a un mundo cambiante. Nuevas ideologías, condiciones económicas, culturales, sociales, modificaron tambi&eacute;n el panorama argentino. Las consecuencias de la revolución industrial, el auge masivo de la inmigración, las innovaciones tecnológicas y culturales en general fueron perfilando una Argentina globalizada, integrada al resto del mundo.<br />\r\n		<br />\r\n		El fracaso de la experiencia de \r\n		<st1:personname w:st=\"on\" productid=\"la Confederaci&#65533;n Argentina\">la Confederación Argentina</st1:personname>, especialmente desde la perspectiva económica al no poder integrar a todo el territorio por el rechazo de Buenos Aires, dejaron en manos de los dirigentes de la ciudad puerto la consolidación del estado nacional. Despu&eacute;s de la batalla de Pavón, Bartolom&eacute; Mitre asumió el cargo de Presidente de \r\n		<st1:personname w:st=\"on\" productid=\"la Naci&#65533;n\">la Nación</st1:personname>, con sede en Buenos Aires, retomándose así el camino de la unidad definitiva. <br />\r\n		<br />\r\n		La provincia de Entre Ríos se había constituido, en la d&eacute;cada anterior, en el epicentro del cambio. En ella consolidó su poder el General Urquiza y a su conducción retornó despu&eacute;s de ejercer \r\n		<st1:personname w:st=\"on\" productid=\"la Primera Magistratura\">la Primera Magistratura</st1:personname> Nacional. Pero tambi&eacute;n en ese ámbito encontró la muerte, resultado de la expresión de una oposición que eligió el camino de la violencia y las armas para provocar los cambios. Sin embargo no borró por ello las huellas dejadas por el ilustre Entrerriano. <br />\r\n		<br />\r\n		Representó y provocó el tránsito a tiempos diferentes. Las formas de hacer la consiguió gracias a una mentalidad abierta, capaz de identificar a los hombres más capaces y darles el espacio suficiente para desplegar sus propias potencialidades, sin abandonar decididamente las premisas culturales en las que se formó. La genialidad tambi&eacute;n se demuestra en la elección del entorno humano para cumplimentar los objetivos que se pretenden lograr, y el General Urquiza contó con colaboradores brillantes, muchos de ellos representativos de las nuevas ideas que hacia la d&eacute;cada del cincuenta hacían ebullición en Europa. De intelectuales y científicos formados en un mundo distinto, protagonistas de una realidad integracionista a la que el resto del mundo occidental marchaba inexorablemente, y que les posibilitaba dimensionar la problemática Argentina desde una perspectiva diferente. Hombres de la llamada generación del 37, argentinos obligados a vivir en el exilio y que se sumaron al espectro político argentino despu&eacute;s de Caseros y del que habían sido excluidos por la fuerza durante el gobierno de Rosas. Tambi&eacute;n colaboraron extranjeros, muchos exilados que al no conseguir en sus países las condiciones apropiadas para llevar a la práctica sus ideas, buscaban nuevos horizontes propicios, especialmente franceses republicanos expulsados por los imperialistas bonapartistas, iniciadores de nuevas corrientes de pensamiento en estas tierras.<br />\r\n		<br />\r\n		Es cierto tambi&eacute;n que su visión como estadista la consolidó desde una perspectiva social y económica en particular, la que le brindó su propio patrimonio particular y la variedad de rubros productivos y comerciales en los que incursionó. <br />\r\n		<br />\r\n		Los recursos básicos que explotó y que constituyó el sustento de su fortuna, –hacienda y saladero- representaban los principales intereses económicos de la incipiente nación y la forma de inserción en los mercados internacionales requería de las garantías que brinda un país organizado y libre de los males que conlleva la inestabilidad política y armada. Su experiencia empresarial le demostraba la necesidad del cambio.<br />\r\n		<br />\r\n		La maximización del rendimiento económico conciliando la producción primaria y la industrial en un circuito completo, la consiguió con su propia producción. En estancias de su propiedad se criaba el ganado, con el que despu&eacute;s se abastecían los saladeros, lugar donde se aprovechaban especialmente los cueros y carnes saladas además de otros subproductos como velas, jabones, carne envasada, etc., los que eran distribuidos en los mercados internos y externos en compañías navieras de las que tambi&eacute;n forma parte. Aspectos que dan muestra acabada una nueva mentalidad empresarial. Pero su sus incursiones en la faz económica fueron mucho más variadas. Diversificación del capital en novedosos emprendimientos surge del análisis del cuantioso patrimonio que poseyó: Acciones en Bancos, ferrocarriles, empresas navieras, de mensajerías, industrias varias, acciones en empresas periodísticas, de colonización, entre otras, demuestran su adecuación a los tiempos modernos.<br />\r\n		<br />\r\n		Exteriorizó en lo privado y en lo público su conciliación entre la modernidad a la que ingresaba el país y las bases tradicionales de una herencia colonial recibida de sus ancestros y consolidadas por las condiciones del ambiente social en que se desarrolló.<br />\r\n		<br />\r\n		Mantuvo en el ámbito provincial las características de caudillo indisputable, con el manejo del poder por casi treinta años. En 1868 asumió una vez mas la primer magistratura provincial, la que en definitiva le costó la vida. \"Usted no necesita de ese puesto para ser el hijo querido y obedecido del pueblo entrerriano” [1] – le aconsejaba uno de sus hijos-, sin poder convencerlo. <br />\r\n		<br />\r\n		Constituyó, seguramente, un error político el retorno del General Urquiza al gobierno entrerriano en los últimos años de la d&eacute;cada del sesenta. Signos de oposición habían comenzado a manifestarse en varios sectores desde tiempo atrás. Contribuyeron a moldear el panorama adverso innumerables factores coyunturales y estructurales, actuales y de larga data. Entre ellos, la actitud de acatamiento al gobierno de Mitre frente a la guerra del Paraguay cuando el sentimiento popular entrerriano la rechazaba –las deserciones de Basualdo y Toledo lo evidencian-; la presencia del Presidente Sarmiento visitando a su antiguo adversario político en el Palacio San Jos&eacute; para conmemorar la batalla de Caseros; las medidas económicas adoptadas ante la grave crisis que vivió la provincia litoraleña; la oposición política agrupada en torno a la figura de Ricardo López Jordán. <br />\r\n		<br />\r\n		Suma de razones que condujeron al movimiento revolucionario que se inició con el asalto a la residencia particular de Urquiza y que provocara su asesinato el 11 de abril de 1870 en su dormitorio. Las consecuencias de la trágica muerte del General Entrerriano fueron la intervención de las tropas nacionales y los enfrentamientos armados, campos devastados, estancias saqueadas, gobiernos inestables, p&eacute;rdida de peso político en el contexto nacional, razones que a su vez postergaron por largos años el crecimiento de Entre Ríos.<br />\r\n		<br />\r\n		Aciertos y errores generaron adhesiones y oposiciones. Perspectivas diferentes de sus propios coetáneos, las que despu&eacute;s se extendieron a lo largo del tiempo generando posturas históricas contrapuestas. Actuó en un contexto histórico determinado, el que le brindó posibilidades y limitaciones, y quizás es dable aplicar lo sostenido por Maquiavelo para el Príncipe, \"prospera aquel que armoniza su modo de proceder con la condición de los tiempos y... paralelamente, decae aquel cuya conducta entra en contradicción con ella. <br />\r\n		<br />\r\n		La vida del General Urquiza concluyó, pero no el producto de su acción. Su huella fue lo suficientemente profunda para mantenerse en el tiempo. Comprendida o no, aceptada o rechazada, con &eacute;xitos y errores, pero indeleble, su figura continua hoy siendo el símbolo por excelencia de la provincia litoraleña a la que perteneció y a partir de la cual extendió su acción al resto del país con una mentalidad nueva, aunque sin abandonar definitivamente los modelos culturales en los que originariamente se formó. <br />\r\n		<br />\r\n		Justo Jos&eacute; de Urquiza concilió la transición entre dos tiempos diferentes, forjando una obra que pervive hoy por su magnitud e incidencia en la historia nacional.</em><br style=\"mso-special-character: line-break\" />\r\n	<br style=\"mso-special-character: line-break\" />\r\n	\r\n	\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Prof. Ana María Barreto<br />\r\n				Jefe de Departamento Educativo y de Extensión Cultural<br />\r\n				Palacio San Jos&eacute; – Museo Urquiza \r\n				<o:p></o:p></span></i></p><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\">Extraído 15/05/2010:<span style=\"mso-spacerun: yes\">&nbsp; </span>http://www.palaciosanjose.com.ar/urquiza_ysu_epoca.htm<br />\r\n			</span></i></span>','\r\n\r\n<p style=\"TEXT-INDENT: -18pt; MARGIN: 0cm 0cm 6pt 36pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt\" class=\"MsoBodyText\"><span style=\"FONT-FAMILY: Wingdings; FONT-SIZE: 10pt; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings\" lang=\"ES\"><span style=\"mso-list: Ignore\">Ø<span style=\"FONT: 7pt \'Times New Roman\'\">&nbsp; </span></span></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">El archivo del Palacio San Jos&eacute; se originó en la documentación generada en <i style=\"mso-bidi-font-style: normal\">el escritorio central de \r\n			<st1:personname w:st=\"on\" productid=\"la Administraci&#65533;n Urquiza.\">la Administración Urquiza.</st1:personname> Conformado por documentos de índole comercial, familiar, política, jurídica que pueden situarse entre \r\n			<st1:metricconverter w:st=\"on\" productid=\"1830 a\">1830 a</st1:metricconverter> 1940.<br />\r\n			Una vez que el Palacio fue declarado Monumento Nacional, en agosto de 1935, comenzaron a llevarse a cabo las primeras acciones de la organización de un Museo, como lo establecía la norma de la declaratoria. Dos años despu&eacute;s se iniciaron las tareas de intervención en el fondo documental de la institución. Antonio P Castro, asumió \r\n			<st1:personname w:st=\"on\" productid=\"la Direcci&#65533;n\">la Dirección</st1:personname> del Palacio San Jos&eacute; y fue quien tuvo a su cargo los primeros pasos en la organización del archivo. En esta primera etapa se arribó a un ordenamiento cronológico: antes de 1870 y despu&eacute;s de 1870. <br />\r\n			En 1945, fue designado Director del Palacio San Jos&eacute; el Profesor Manuel Macchi, quien elaboró el Plan que sirvió de guía a la organización intelectual que durante muchos años tuvo dicho Fondo. Los documentos se encontraban clasificados tomando como referencia dos períodos cronológicos amplios: \r\n			<st1:metricconverter w:st=\"on\" productid=\"1838 a\">1838 a</st1:metricconverter> 1870 y \r\n			<st1:metricconverter w:st=\"on\" productid=\"1870 a\">1870 a</st1:metricconverter> 1940 y poseía diferentes secciones: Palacio San Jos&eacute;, Beneficencia, Correspondencia familiar, Política, Colonias Agrícolas, Interior, Guerra, Hacienda, Colonia, Estancias y propiedades, Conmemoraciones, Palacio San Jos&eacute; y Casas en Uruguay, Saladeros, y Familia. <br />\r\n			El Archivo Histórico del Palacio San Jos&eacute;, se constituyó desde sus inicios en un lugar de referencia para estudiosos e investigadores interesados en la figura del General Urquiza, su obra y su contexto. Además de la tarea fundacional de Castro en los primeros años institucionales y el trabajo sostenido y criterioso del Profesor Macchi, por más de treinta años, el Museo siguió desarrollando una acción de protección del Fondo y atención de los usuarios investigadores. \r\n			<o:p></o:p></i></span></p>\r\n\r\n<p style=\"MARGIN: 0cm 0cm 6pt 18pt\" class=\"MsoBodyText\"><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">En la actualidad, se ha puesto en marcha el Proyecto de Preservación y Reordenamiento Intelectual del Archivo con el objetivo de lograr mejores condiciones de almacenaje de documentos, además de facilitar el acceso a los documentos a trav&eacute;s de un adecuado procesamiento de la información.<br />\r\n			\r\n			<o:p></o:p></span></i></p>','Compra','Presidencia de la Nación . Secretaría de Cultura. Res. 1530','19830905','100.000 pesos argentinos; 800 Euros.','Decreto.',NULL,'R.S.C. Nº 1328/08','El documento sólo será utilizado según consta en...','Titular de derechos','<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\"><em>Inventario índice</em></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\">: presenta<span style=\"mso-spacerun: yes\">&nbsp; </span>referencias generales del contenido de cada unidad de guarda ( carpetas, <b style=\"mso-bidi-font-weight: normal\"><span style=\"mso-spacerun: yes\">&nbsp;</span></b>libros), en soporte papel y planilla excel.<br />\r\n	<br />\r\n	<i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Guía del Archivo</span></i><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">: Patrimonio archivístico \"Herencia cultural legada al porvenir”, en soporte papel y base de datos.<br />\r\n		<br />\r\n		\r\n		<o:p><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Cuadro de clasificación</span></i><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">: Fondo Urquiza. Aplicado a todas las series. En soporte papel.<br />\r\n				<br />\r\n				<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\"><em>Boceto catálogo</em></span><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\"><span style=\"mso-spacerun: yes\">&nbsp; </span>Fondo Justo Jos&eacute; de Urquiza.<span style=\"mso-spacerun: yes\">&nbsp; </span>Serie: Colonia San Jos&eacute; en soporte papel y base de datos.<br />\r\n					<br />\r\n					<i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Catálogo e indización</span></i><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">: Fondo Justo Jos&eacute; de Urquiza. Serie: Palacio San Jos&eacute;.<br />\r\n						<br />\r\n						\r\n						<o:p><i style=\"mso-bidi-font-style: normal\"><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\">Catálogo<span style=\"mso-spacerun: yes\">&nbsp; </span>e indización: Fondo Justo Jos&eacute; de Urquiza. Serie: Familia.<br />\r\n									<br />\r\n									Catálogo:<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\"> Agrupación Documental Cartográfica.<br />\r\n										</span>\r\n									<o:p><span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt\" lang=\"ES\"><br />\r\n											Fichas descriptivas de<span style=\"mso-spacerun: yes\">&nbsp; </span>documentos del fondo Justo Jos&eacute; de Urquiza ubicadas en ficheros por orden alfab&eacute;tico.<br />\r\n											\r\n											<o:p></o:p></span></o:p></span></i></o:p></span></span></span></o:p></span></span>','<span style=\"FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ES; mso-bidi-language: AR-SA\" lang=\"ES\"><em>Fundación Andrew Mellon- Universidad de Harvard.USA Guarda baja normas de documentación Fondo<span style=\"mso-spacerun: yes\">&nbsp; </span>Domingo Faustino Sarmiento.<span style=\"mso-spacerun: yes\">&nbsp; </span>Marzo octubre-2000- ärea conservación.<br />\r\n		<br />\r\n		</em></span>',1,'AR/PN/SC/DNPM/PSJ',1,NULL,'Gratuito.','DNI','Toda la documentación es de libre acceso.',5,NULL,NULL,NULL,NULL,'2011-05-24 08:56:31','camilo',4);
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
 ('AR/PN/SC/DNPM/CNS/MH','<span style=\"LINE-HEIGHT: 115%; FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES-AR; mso-fareast-language: ES-AR; mso-bidi-language: AR-SA\">Anteriormente este sub-fondo era conocido como: Carpeta de la Casa.</span>','<span style=\"LINE-HEIGHT: 115%; FONT-FAMILY: \'Arial\',\'sans-serif\'; FONT-SIZE: 10pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES-AR; mso-fareast-language: ES-AR; mso-bidi-language: AR-SA\">Oviedo, Margarita Esther.</span>',NULL,NULL,'2011-05-24 10:14:07'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','kgowqiúe','hsdgfpiuw','FHRJWQYK','2011-01-01 00:00:00','2011-06-15 16:15:24'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','.',NULL,NULL,NULL,'2011-06-02 20:40:00'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','.',NULL,NULL,NULL,'2011-06-02 21:07:31'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','.',NULL,NULL,NULL,'2011-06-02 20:55:19'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','.',NULL,NULL,NULL,'2011-06-02 23:31:08'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','.',NULL,NULL,NULL,'2011-06-03 00:26:13'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','n',NULL,NULL,NULL,'2011-05-29 05:31:24'),
 ('AR/PN/SC/DNPM/MCASN/FI','.',NULL,NULL,NULL,'2011-06-02 23:53:00'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','.',NULL,NULL,NULL,'2011-06-02 20:14:54'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','.',NULL,NULL,NULL,'2011-06-02 23:38:47'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','.',NULL,NULL,NULL,'2011-06-02 21:27:44'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','\r\n\r\n<p>.</p>',NULL,NULL,NULL,'2011-06-02 01:15:20'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','.',NULL,NULL,NULL,'2011-06-02 01:29:43'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','.',NULL,NULL,NULL,'2011-06-02 01:47:55'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','.',NULL,NULL,NULL,'2011-06-02 21:11:43'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','no',NULL,NULL,NULL,'2011-05-31 03:15:47'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','.',NULL,NULL,NULL,'2011-06-02 21:38:24'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','n','\r\n\r\n<p>&nbsp;</p>',NULL,NULL,'2011-05-31 03:05:13'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','.',NULL,NULL,NULL,'2011-06-02 00:38:28'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','.',NULL,NULL,NULL,'2011-06-02 00:47:22'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','.',NULL,NULL,NULL,'2011-06-02 00:57:01'),
 ('AR/PN/SC/DNPM/MCY/FRY','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Existe documentación relacionada con Yrurtia por ejemplo en el Archivo Canale, Publicación de \r\n		<st1:personname w:st=\"on\" productid=\"la Fundación Espigas.\">la Fundación Espigas.</st1:personname> </span></p>','\r\n\r\n<p style=\"MARGIN: 0cm 0cm 0pt\" class=\"MsoNormal\"><span lang=\"ES-AR\">Mlg. Oviedo Bustos Cecilia; Mlg. Varela Constanza</span></p>',NULL,'2010-01-01 00:00:00','2011-05-29 12:29:41'),
 ('AR/PN/SC/DNPM/MHN/FAPC','No se registran','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: SimSun; mso-ansi-language: ES-TRAD; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">Oguic, Sofía Rufina</span>',NULL,'2010-02-28 00:00:00','2011-05-29 13:28:14'),
 ('AR/PN/SC/DNPM/MJN/FFE','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">El Fondo forma parte del Inventario Museológico de la institución con la numeración de \r\n	<st1:metricconverter w:st=\"on\" productid=\"739 a\">739 a</st1:metricconverter> 771</span>','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">Reyna, María Teresa</span>',NULL,'2010-07-08 00:00:00','2011-05-29 09:14:32'),
 ('AR/PN/SC/DNPM/MJN/FRPB','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">El Fondo forma parte del Inventario Museológico de la institución con la numeración de 400/25 y 403/4</span>','<span style=\"FONT-FAMILY: \'Times New Roman\'; FONT-SIZE: 12pt; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: ES; mso-fareast-language: ZH-CN; mso-bidi-language: HI\" lang=\"ES\">Reyna, María Teresa</span>',NULL,'2010-08-20 00:00:00','2011-05-29 09:37:18'),
 ('AR/PN/SC/DNPM/MM/FBM','\"Archivo del General Mitre\", Buenos Aires, Biblioteca La Nación. 1914. 28 vol. Transcripción de una selección de documentos.','Iglesias, María Ximena',NULL,'2008-11-03 00:00:00','2011-05-30 03:10:09'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Notas Descripción...',NULL,'Informes de investigaciones realizadas por el Museo, Departamento de extensión Educativa, folletos institucionales, memoria institucional 1997,999,2008. Presidencia de la Nación.  Secretaría de Cultura.  Dirección Nacional de Patrimonio y Museos. Palacio ','2010-10-08 00:00:00','2011-05-24 08:57:17');
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
 ('AR/PN/SC/DNPM/CNB/Fondo 01','Inicio',NULL,'2011-06-22 11:30:08','camilo'),
 ('AR/PN/SC/DNPM/CNB/Fondo 01','Pendiente',NULL,'2011-06-22 17:09:11','camilo'),
 ('AR/PN/SC/DNPM/CNB/Fondo 01/SubFondo 01','Inicio',NULL,'2011-06-22 11:30:56','camilo'),
 ('AR/PN/SC/DNPM/CNS/C','Inicio',NULL,'2011-05-24 10:18:57','camilo'),
 ('AR/PN/SC/DNPM/CNS/C/DOCCOM','Inicio',NULL,'2011-05-27 06:17:27','camilo'),
 ('AR/PN/SC/DNPM/CNS/M','Inicio',NULL,'2011-05-24 10:17:53','camilo'),
 ('AR/PN/SC/DNPM/CNS/M','Pendiente',NULL,'2011-06-21 09:11:01','camilo'),
 ('AR/PN/SC/DNPM/CNS/MH','Completo',NULL,'2011-05-24 10:14:00','camilo'),
 ('AR/PN/SC/DNPM/CNS/MH','Completo',NULL,'2011-05-24 10:14:07','camilo'),
 ('AR/PN/SC/DNPM/CNS/MH','Inicio',NULL,'2011-05-24 05:18:39','camilo'),
 ('AR/PN/SC/DNPM/CNS/MH','Pendiente',NULL,'2011-05-24 05:19:47','camilo'),
 ('AR/PN/SC/DNPM/CNS/STDFS','Inicio',NULL,'2011-05-24 10:18:49','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Completo',NULL,'2011-06-15 16:15:24','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Inicio',NULL,'2011-06-15 16:10:28','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Pendiente',NULL,'2011-06-15 16:10:57','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo','Inicio',NULL,'2011-06-17 13:54:39','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo','Pendiente',NULL,'2011-06-17 14:11:14','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc','Inicio',NULL,'2011-06-17 13:54:58','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc','Inicio',NULL,'2011-06-17 13:55:08','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie','Inicio',NULL,'2011-06-17 13:55:19','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser','Inicio',NULL,'2011-06-17 13:55:28','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc','Inicio',NULL,'2011-06-17 13:55:41','camilo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/doccomp','Vigente',NULL,'2011-06-17 13:55:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Completo',NULL,'2011-06-02 20:40:00','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Inicio',NULL,'2011-05-31 03:29:42','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','No Vigente',NULL,'2011-06-08 15:02:01','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Pendiente',NULL,'2011-06-02 20:36:55','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP','Inicio',NULL,'2011-06-02 20:41:54','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','Completo',NULL,'2011-06-02 21:07:31','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','Inicio',NULL,'2011-06-02 21:01:03','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','Pendiente',NULL,'2011-06-02 21:02:06','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Completo',NULL,'2011-06-02 20:55:19','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Inicio',NULL,'2011-06-02 20:42:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Pendiente',NULL,'2011-06-02 20:43:29','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Completo',NULL,'2011-06-02 23:30:36','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Completo',NULL,'2011-06-02 23:31:08','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Inicio',NULL,'2011-05-31 03:32:50','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Pendiente',NULL,'2011-06-02 23:28:19','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Vigente',NULL,'2011-06-08 15:02:33','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/CdT','Inicio',NULL,'2011-06-02 23:32:17','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/E','Inicio',NULL,'2011-06-02 23:34:01','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA/SdZ','Inicio',NULL,'2011-06-02 23:34:14','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Completo',NULL,'2011-06-03 00:26:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Inicio',NULL,'2011-05-31 03:33:53','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','No Vigente',NULL,'2011-06-08 15:02:50','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Pendiente',NULL,'2011-06-03 00:23:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM/CLNMB','Inicio',NULL,'2011-06-03 00:28:41','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM/C_TP','Inicio',NULL,'2011-06-03 00:29:25','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM/E','Inicio',NULL,'2011-06-03 00:28:09','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Completo',NULL,'2011-05-29 05:31:24','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Inicio',NULL,'2011-05-29 05:04:24','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Pendiente',NULL,'2011-05-29 05:08:27','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI','Completo',NULL,'2011-06-02 23:53:00','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI','Inicio',NULL,'2011-05-31 03:33:28','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI','No Vigente',NULL,'2011-06-08 15:03:44','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI','Pendiente',NULL,'2011-06-02 23:51:15','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/B','Inicio',NULL,'2011-06-02 23:58:32','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/CRyE','Inicio',NULL,'2011-06-02 23:59:09','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/DRMyE','Inicio',NULL,'2011-06-02 23:57:42','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/M','Inicio',NULL,'2011-06-02 23:58:16','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/M','Pendiente',NULL,'2011-06-07 15:08:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/NT','Inicio',NULL,'2011-06-02 23:58:46','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/OCP','Inicio',NULL,'2011-06-02 23:58:05','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FI/WSC','Inicio',NULL,'2011-06-02 23:54:34','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Completo',NULL,'2011-06-02 20:14:54','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Inicio',NULL,'2011-05-31 03:29:20','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Pendiente',NULL,'2011-06-02 19:24:26','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Vigente',NULL,'2011-06-08 15:03:29','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic','Inicio',NULL,'2011-06-02 20:16:54','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/Ases','Inicio',NULL,'2011-06-02 20:18:09','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/CR','Inicio',NULL,'2011-06-02 20:18:36','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/IGC','Inicio',NULL,'2011-06-02 20:18:48','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/Insp','Inicio',NULL,'2011-06-02 20:18:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/ObPublic/UyO','Inicio',NULL,'2011-06-02 20:17:57','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic','Inicio',NULL,'2011-06-02 20:15:35','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic/E','Inicio',NULL,'2011-06-02 20:16:02','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG/Partic/EMA','Inicio',NULL,'2011-06-02 20:16:22','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Completo',NULL,'2011-06-02 23:38:47','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Inicio',NULL,'2011-05-31 03:33:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Pendiente',NULL,'2011-06-02 23:35:57','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Aren','Inicio',NULL,'2011-06-02 23:45:33','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/ASN','Inicio',NULL,'2011-06-02 23:43:08','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Corr','Inicio',NULL,'2011-06-02 23:44:32','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Cred','Inicio',NULL,'2011-06-02 23:45:48','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Decr','Inicio',NULL,'2011-06-02 23:44:15','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Desp','Inicio',NULL,'2011-06-02 23:46:01','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/EG','Inicio',NULL,'2011-06-02 23:41:36','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Man','Inicio',NULL,'2011-06-02 23:45:18','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/PP','Inicio',NULL,'2011-06-02 23:43:21','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Procl','Inicio',NULL,'2011-06-02 23:44:49','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Pron','Inicio',NULL,'2011-06-02 23:42:55','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/PUN','Inicio',NULL,'2011-06-02 23:41:48','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/Res','Inicio',NULL,'2011-06-02 23:45:04','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU/TC','Inicio',NULL,'2011-06-02 23:43:46','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Completo',NULL,'2011-06-02 21:27:44','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Inicio',NULL,'2011-05-31 03:31:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Pendiente',NULL,'2011-06-02 21:18:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/AP','Inicio',NULL,'2011-06-02 21:31:55','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Dipl','Inicio',NULL,'2011-06-02 21:42:44','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/DJJA','Inicio',NULL,'2011-06-02 21:33:41','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Edu','Inicio',NULL,'2011-06-02 21:32:11','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/EEpist','Inicio',NULL,'2011-06-02 21:32:37','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/ENI','Inicio',NULL,'2011-06-02 21:41:59','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/GJJU','Inicio',NULL,'2011-06-02 21:33:21','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Lit','Inicio',NULL,'2011-06-02 21:32:22','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG/Soc','Inicio',NULL,'2011-06-02 21:42:33','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Completo',NULL,'2011-05-31 03:27:49','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Completo',NULL,'2011-06-02 01:15:20','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Inicio',NULL,'2011-05-31 03:16:31','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Pendiente',NULL,'2011-05-31 03:17:20','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/ManT','Inicio',NULL,'2011-06-02 01:18:21','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/ManT/E','Inicio',NULL,'2011-06-02 01:18:35','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Completo',NULL,'2011-06-02 01:29:43','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Inicio',NULL,'2011-06-02 01:18:11','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Pendiente',NULL,'2011-06-02 01:25:34','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Completo',NULL,'2011-06-02 01:47:55','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Inicio',NULL,'2011-06-02 01:35:34','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Pendiente',NULL,'2011-06-02 01:39:19','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/Lite','Inicio',NULL,'2011-06-02 01:35:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/Pers','Inicio',NULL,'2011-06-02 01:35:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR','Inicio',NULL,'2011-06-02 01:17:57','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR/Doc','Inicio',NULL,'2011-06-02 01:22:51','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MR/EhMdR','Inicio',NULL,'2011-06-02 01:22:34','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Completo',NULL,'2011-06-02 21:11:43','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Inicio',NULL,'2011-05-31 03:30:54','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Pendiente',NULL,'2011-06-02 21:09:36','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/AM','Inicio',NULL,'2011-06-02 21:16:16','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/AP','Inicio',NULL,'2011-06-02 21:15:31','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/Cap','Inicio',NULL,'2011-06-02 21:16:29','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/ER','Inicio',NULL,'2011-06-02 21:17:08','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/FEI','Inicio',NULL,'2011-06-02 21:14:12','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/FP','Inicio',NULL,'2011-06-02 21:16:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/IB','Inicio',NULL,'2011-06-02 21:14:43','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/IV','Inicio',NULL,'2011-06-02 21:17:21','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/Obisp','Inicio',NULL,'2011-06-02 21:16:43','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/SP','Inicio',NULL,'2011-06-02 21:15:44','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT/TyCP','Inicio',NULL,'2011-06-02 21:14:30','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Completo',NULL,'2011-05-31 03:15:47','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Inicio',NULL,'2011-05-31 03:07:40','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Pendiente',NULL,'2011-05-31 03:09:22','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/DMJMR','Inicio',NULL,'2011-06-02 01:10:32','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/E','Inicio',NULL,'2011-06-02 01:10:06','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/RJ','Inicio',NULL,'2011-06-02 01:10:18','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T','Inicio',NULL,'2011-06-02 01:11:06','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T/JyD','Inicio',NULL,'2011-06-02 01:11:22','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP/T/PyM','Inicio',NULL,'2011-06-02 01:11:33','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Completo',NULL,'2011-06-02 21:38:25','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Inicio',NULL,'2011-05-31 03:31:38','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Pendiente',NULL,'2011-06-02 21:35:02','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG/ActCom','Inicio',NULL,'2011-06-02 21:39:02','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG/ActPub','Inicio',NULL,'2011-06-02 21:39:39','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG/E','Inicio',NULL,'2011-06-02 21:39:53','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','Completo',NULL,'2011-05-31 03:05:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','Inicio',NULL,'2011-05-31 02:57:13','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','Pendiente',NULL,'2011-05-31 02:58:21','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP','Inicio',NULL,'2011-06-01 21:54:48','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/MG','Inicio',NULL,'2011-06-01 22:01:56','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/MGue','Inicio',NULL,'2011-06-01 22:03:16','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/AP/P','Inicio',NULL,'2011-06-01 22:02:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E','Inicio',NULL,'2011-06-01 21:55:44','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Completo',NULL,'2011-06-02 00:38:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Completo',NULL,'2011-06-02 00:38:28','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Inicio',NULL,'2011-06-02 00:23:24','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Pendiente',NULL,'2011-06-02 00:31:02','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','Completo',NULL,'2011-06-02 00:47:22','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','Inicio',NULL,'2011-06-01 21:57:29','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','Pendiente',NULL,'2011-06-02 00:43:23','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JMR','Inicio',NULL,'2011-06-01 21:57:10','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Completo',NULL,'2011-06-02 00:57:01','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Inicio',NULL,'2011-06-01 21:57:58','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Pendiente',NULL,'2011-06-02 00:53:12','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN','Inicio',NULL,'2011-06-01 21:56:51','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN/Inf','Inicio',NULL,'2011-06-02 00:26:36','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/SN/Salad','Inicio',NULL,'2011-06-02 00:26:50','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T','Inicio',NULL,'2011-06-01 21:56:30','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/CD','Inicio',NULL,'2011-06-02 00:25:34','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/CL','Inicio',NULL,'2011-06-02 00:25:24','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/Dem','Inicio',NULL,'2011-06-02 00:25:14','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/FU','Inicio',NULL,'2011-06-02 00:24:49','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/JLS','Inicio',NULL,'2011-06-02 00:24:26','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/Juic','Inicio',NULL,'2011-06-02 00:25:00','camilo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/T/SS','Inicio',NULL,'2011-06-02 00:24:38','camilo'),
 ('AR/PN/SC/DNPM/MCY/FRY','Completo',NULL,'2011-05-29 12:29:31','camilo'),
 ('AR/PN/SC/DNPM/MCY/FRY','Completo',NULL,'2011-05-29 12:29:41','camilo'),
 ('AR/PN/SC/DNPM/MCY/FRY','Inicio',NULL,'2011-05-29 11:48:03','camilo'),
 ('AR/PN/SC/DNPM/MCY/FRY','Pendiente',NULL,'2011-05-29 12:13:42','camilo'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Completo',NULL,'2011-05-29 13:28:01','camilo'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Completo',NULL,'2011-05-29 13:28:14','camilo'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Inicio',NULL,'2011-05-29 13:10:49','camilo'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Pendiente',NULL,'2011-05-29 13:15:28','camilo'),
 ('AR/PN/SC/DNPM/MJN/FFE','Completo',NULL,'2011-05-29 09:14:20','camilo'),
 ('AR/PN/SC/DNPM/MJN/FFE','Completo',NULL,'2011-05-29 09:14:32','camilo'),
 ('AR/PN/SC/DNPM/MJN/FFE','Inicio',NULL,'2011-05-29 07:09:24','camilo'),
 ('AR/PN/SC/DNPM/MJN/FFE','Pendiente',NULL,'2011-05-29 07:12:18','camilo'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Completo',NULL,'2011-05-29 09:26:21','camilo'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Completo',NULL,'2011-05-29 09:27:07','camilo'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Completo',NULL,'2011-05-29 09:37:18','camilo'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Inicio',NULL,'2011-05-29 09:15:57','camilo'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Pendiente',NULL,'2011-05-29 09:17:47','camilo'),
 ('AR/PN/SC/DNPM/MM/FBM','Completo',NULL,'2011-05-30 03:10:04','camilo'),
 ('AR/PN/SC/DNPM/MM/FBM','Completo',NULL,'2011-05-30 03:10:09','camilo'),
 ('AR/PN/SC/DNPM/MM/FBM','Inicio',NULL,'2011-05-30 02:49:30','camilo'),
 ('AR/PN/SC/DNPM/MM/FBM','Pendiente',NULL,'2011-05-30 02:54:40','camilo'),
 ('AR/PN/SC/DNPM/MM/FFT','Inicio',NULL,'2011-05-30 22:19:31','camilo'),
 ('AR/PN/SC/DNPM/MM/FGEM','Inicio',NULL,'2011-05-30 22:18:51','camilo'),
 ('AR/PN/SC/DNPM/MM/FGJSM','Inicio',NULL,'2011-05-30 22:20:01','camilo'),
 ('AR/PN/SC/DNPM/MM/FGMB','Inicio',NULL,'2011-05-30 22:19:47','camilo'),
 ('AR/PN/SC/DNPM/MM/FIEMYV','Inicio',NULL,'2011-05-30 22:19:08','camilo'),
 ('AR/PN/SC/DNPM/MM/FWP','Inicio',NULL,'2011-05-30 22:19:21','camilo'),
 ('AR/PN/SC/DNPM/MRPJAT/MT','Inicio',NULL,'2011-05-29 13:56:17','camilo'),
 ('AR/PN/SC/DNPM/MRPJAT/MT','Pendiente',NULL,'2011-05-29 13:57:14','camilo'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Completo',NULL,'2011-05-24 04:36:51','camilo'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Completo',NULL,'2011-05-24 04:37:25','camilo'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Completo',NULL,'2011-05-24 08:57:17','camilo'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Inicio',NULL,'2011-05-23 11:29:42','camilo'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Pendiente',NULL,'2011-05-24 02:44:26','camilo');
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
 ('AR/PN/SC/DNPM/CNB/Fdo_01',0),
 ('AR/PN/SC/DNPM/CNB/Fdo_01',1236),
 ('AR/PN/SC/DNPM/CNSDR/fdo01',123),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo',0),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo',2154),
 ('AR/PN/SC/DNPM/PSJ/FDC',60427);
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
 ('AR/PN/SC/DNPM/CNSDR/fdo01','fg','fg','2008-01-01 00:00:00','fgtr','fehaetjrtskjtykyudktsykstyjstjtshjt'),
 ('AR/PN/SC/DNPM/PSJ/FDC','3000 pesos argentinos; 1.000 Euros.','U$S 1000','2008-02-10 00:00:00','Banco Ciudad de Buenos Aires; Sotheby´s','Forma parte de una serie, no del original.');
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
 ('Decreto'),
 ('Disposición'),
 ('Ordenanza'),
 ('Resolución');
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
 ('Educar','AR/PN/SC/DNPM/CNS/C/DOCSON'),
 ('Congreso','AR/PN/SC/DNPM/CNS/C/DOCVIS01'),
 ('RTJHRQ','AR/PN/SC/DNPM/CNSDR/fdo01/DOC01');
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
  CONSTRAINT `fk_permisos_secciones1` FOREIGN KEY (`idseccion`) REFERENCES `secciones` (`idseccion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_permisos_roles1` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permisos`
--

/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` (`idpermiso`,`idseccion`,`idrol`,`consultar`,`modificar`,`eliminar`) VALUES 
 (1,1,1,0x01,0x01,0x01),
 (2,1,2,0x01,0x01,0x01),
 (3,2,1,0x01,0x01,0x01),
 (4,2,2,0x01,0x01,0x00),
 (5,3,1,0x01,0x00,0x00),
 (6,3,2,0x01,0x00,0x00),
 (7,1,3,0x01,0x01,0x00),
 (8,1,4,0x01,0x00,0x00),
 (9,2,3,0x01,0x01,0x00),
 (10,2,4,0x01,0x01,0x00),
 (11,3,3,0x01,0x00,0x00),
 (12,3,4,0x01,0x00,0x00),
 (13,15,1,0x01,0x01,0x01),
 (14,15,2,0x01,0x01,0x01),
 (15,15,3,0x01,0x00,0x00),
 (16,15,4,0x01,0x01,0x00),
 (17,15,5,0x01,0x01,0x01),
 (18,15,6,0x01,0x00,0x00),
 (19,9,1,0x01,0x01,0x01),
 (20,9,2,0x01,0x01,0x01),
 (21,9,3,0x01,0x00,0x00),
 (22,9,4,0x01,0x01,0x01),
 (23,9,5,0x01,0x01,0x01),
 (24,9,6,0x01,0x00,0x00),
 (25,10,1,0x01,0x01,0x01),
 (26,10,2,0x01,0x01,0x01),
 (27,10,3,0x01,0x00,0x00),
 (28,10,4,0x01,0x01,0x01),
 (29,10,5,0x01,0x01,0x01),
 (30,10,6,0x01,0x00,0x00),
 (31,11,1,0x01,0x01,0x01),
 (32,11,2,0x01,0x01,0x01),
 (33,11,3,0x01,0x00,0x00),
 (34,11,4,0x01,0x01,0x01),
 (35,11,5,0x01,0x01,0x01),
 (36,11,6,0x01,0x00,0x00),
 (37,12,1,0x01,0x01,0x01),
 (38,12,2,0x01,0x01,0x01),
 (39,12,3,0x01,0x00,0x00),
 (40,12,4,0x01,0x01,0x01),
 (41,12,5,0x01,0x01,0x01),
 (42,12,6,0x01,0x00,0x00),
 (43,14,1,0x00,0x00,0x00),
 (44,14,2,0x00,0x00,0x00),
 (45,14,3,0x00,0x00,0x00),
 (46,14,4,0x00,0x00,0x00),
 (47,14,5,0x00,0x00,0x00),
 (48,14,6,0x00,0x00,0x00),
 (49,3,5,0x00,0x00,0x00),
 (50,3,6,0x00,0x00,0x00),
 (51,2,5,0x00,0x00,0x00),
 (52,2,6,0x00,0x00,0x00),
 (53,1,5,0x00,0x00,0x00),
 (54,1,6,0x00,0x00,0x00),
 (55,4,1,0x01,0x01,0x01),
 (56,4,2,0x01,0x01,0x00),
 (57,4,3,0x01,0x00,0x00),
 (58,4,4,0x01,0x01,0x01),
 (59,4,5,0x01,0x01,0x01),
 (60,4,6,0x01,0x00,0x00),
 (61,5,1,0x00,0x00,0x00),
 (62,5,2,0x00,0x00,0x00),
 (63,5,3,0x00,0x00,0x00),
 (64,5,4,0x00,0x00,0x00),
 (65,5,5,0x00,0x00,0x00),
 (66,5,6,0x00,0x00,0x00),
 (67,6,1,0x01,0x01,0x01),
 (68,6,2,0x01,0x01,0x01),
 (69,6,3,0x01,0x00,0x00),
 (70,6,4,0x01,0x01,0x01),
 (71,6,5,0x01,0x01,0x01),
 (72,6,6,0x01,0x00,0x00),
 (73,7,1,0x00,0x00,0x00),
 (74,7,2,0x00,0x00,0x00),
 (75,7,3,0x00,0x00,0x00),
 (76,7,4,0x00,0x00,0x00),
 (77,7,5,0x00,0x00,0x00),
 (78,7,6,0x00,0x00,0x00),
 (79,8,1,0x00,0x00,0x00),
 (80,8,2,0x00,0x00,0x00),
 (81,8,3,0x00,0x00,0x00),
 (82,8,4,0x00,0x00,0x00),
 (83,8,5,0x00,0x00,0x00),
 (84,8,6,0x00,0x00,0x00),
 (85,17,1,0x00,0x00,0x00),
 (86,17,2,0x00,0x00,0x00),
 (87,17,3,0x00,0x00,0x00),
 (88,17,4,0x00,0x00,0x00),
 (89,17,5,0x00,0x00,0x00),
 (90,17,6,0x00,0x00,0x00),
 (91,16,1,0x00,0x00,0x00),
 (92,16,2,0x00,0x00,0x00),
 (93,16,3,0x00,0x00,0x00),
 (94,16,4,0x00,0x00,0x00),
 (95,16,5,0x00,0x00,0x00),
 (96,16,6,0x00,0x00,0x00),
 (97,13,1,0x01,0x01,0x00),
 (98,13,2,0x00,0x00,0x00),
 (99,13,3,0x00,0x00,0x00),
 (100,13,4,0x00,0x00,0x00),
 (101,13,5,0x00,0x00,0x00),
 (102,13,6,0x00,0x00,0x00);
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
 ('Colocar respaldo'),
 ('Reemplazar envoltorio'),
 ('Reparar roturas'),
 ('Retirar grapas');
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
 ('César Bustamante','AR/PN/SC/DNPM/CNB'),
 ('Casa Natal de Sarmiento. Monumento Histórico','AR/PN/SC/DNPM/CNS'),
 ('Productor','AR/PN/SC/DNPM/CNSDR'),
 ('Museo Y Biblioteca Casa Del Acuerdo','AR/PN/SC/DNPM/MCASN'),
 ('Rogelio Yrurtia','AR/PN/SC/DNPM/MCY'),
 ('Doctor Adolfo Pedro Carranza','AR/PN/SC/DNPM/MHN'),
 ('Jorge Betolli','AR/PN/SC/DNPM/MJN'),
 ('Raúl P. Bassani','AR/PN/SC/DNPM/MJN'),
 ('Mitre, Bartolomé','AR/PN/SC/DNPM/MM'),
 ('Justo José de Urquiza','AR/PN/SC/DNPM/PSJ');
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
  CONSTRAINT `fk_rel_conserv_agregados_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_conserv_agregados_agregados1` FOREIGN KEY (`codigo_agregado`) REFERENCES `agregados` (`codigo_agregado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_agregados`
--

/*!40000 ALTER TABLE `rel_conserv_agregados` DISABLE KEYS */;
INSERT INTO `rel_conserv_agregados` (`codigo_gestion`,`codigo_agregado`) VALUES 
 (1,1),
 (2,1);
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
 (1,'Cajón');
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
 (1,'Envoltorio');
/*!40000 ALTER TABLE `rel_conserv_guardas_sugeridas` ENABLE KEYS */;


--
-- Definition of table `rel_conserv_material_soporte`
--

DROP TABLE IF EXISTS `rel_conserv_material_soporte`;
CREATE TABLE `rel_conserv_material_soporte` (
  `codigo_gestion` int(11) NOT NULL,
  `codigo_material_soporte` int(11) NOT NULL,
  PRIMARY KEY (`codigo_gestion`,`codigo_material_soporte`),
  KEY `fk_rel_conserv_material_soporte_gestion_conservacion1` (`codigo_gestion`),
  KEY `fk_rel_conserv_material_soporte_material_soporte1` (`codigo_material_soporte`),
  CONSTRAINT `fk_rel_conserv_material_soporte_material_soporte1` FOREIGN KEY (`codigo_material_soporte`) REFERENCES `material_soporte` (`codigo_material_soporte`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_conserv_material_soporte_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_conserv_material_soporte`
--

/*!40000 ALTER TABLE `rel_conserv_material_soporte` DISABLE KEYS */;
INSERT INTO `rel_conserv_material_soporte` (`codigo_gestion`,`codigo_material_soporte`) VALUES 
 (1,3),
 (2,3);
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
 (1,1),
 (2,1);
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
 (1,'Fragilidad');
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
  CONSTRAINT `fk_rel_documentos_contenedores_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_contenedores_contenedores1` FOREIGN KEY (`contenedor`) REFERENCES `contenedores` (`contenedor`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_contenedores`
--

/*!40000 ALTER TABLE `rel_documentos_contenedores` DISABLE KEYS */;
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
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Buenos Aires');
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Educación'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/DOC01','Educación'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Gobierno');
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Augusto Belin. María Celia García Rojas Belin Sarmiento'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Augusto Belin. María Celia García Rojas Belin Sarmiento');
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Museo Mitre','caja 6'),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01','Museo Casa Natal de Sarmiento',NULL),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo/secc/subsecc/serie/subser/AgrupDoc/visu','Museo Casa de Yrurtia','caja 10');
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON',5),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/DOC01',5),
 ('AR/PN/SC/DNPM/CNS/C/DOCVIS01',6);
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
  `full` tinyint(4) DEFAULT '0',
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
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Español');
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
  CONSTRAINT `fk_rel_documentos_rubros_autores_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_rubros_autores_autores1` FOREIGN KEY (`autor`) REFERENCES `autores` (`autor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_rubros_autores_rubros1` FOREIGN KEY (`rubro`) REFERENCES `rubros` (`rubro`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_documentos_rubros_autores`
--

/*!40000 ALTER TABLE `rel_documentos_rubros_autores` DISABLE KEYS */;
INSERT INTO `rel_documentos_rubros_autores` (`codigo_referencia`,`rubro`,`autor`) VALUES 
 ('AR/PN/SC/DNPM/CNS/C/DOCSON','Arreglos Musicales','Atahualpa Yupanqui');
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
  CONSTRAINT `fk_rel_documentos_usuarios_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_documentos_usuarios_documentos1` FOREIGN KEY (`codigo_referencia`) REFERENCES `documentos` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
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
  CONSTRAINT `fk_rel_gestion_conserv_caract_toma_fotog_gestion_conservacion1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_gestion_conserv_caract_toma_fotog_caracteristicas_toma1` FOREIGN KEY (`caracteristica_toma_fotografica`) REFERENCES `caracteristicas_toma_fotografica` (`caracteristica_toma_fotografica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_gestion_conserv_caract_toma_fotog`
--

/*!40000 ALTER TABLE `rel_gestion_conserv_caract_toma_fotog` DISABLE KEYS */;
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
  CONSTRAINT `fk_rel_gestion_conserv_recomendaciones_tratamiento_recomendac1` FOREIGN KEY (`recomendaciones_tratamiento`) REFERENCES `recomendaciones_tratamiento` (`recomendaciones_tratamiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_gestion_conserv_recomendaciones_tratamiento_gestion_co1` FOREIGN KEY (`codigo_gestion`) REFERENCES `gestion_conservacion` (`codigo_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_gestion_conserv_recomendaciones_tratamiento`
--

/*!40000 ALTER TABLE `rel_gestion_conserv_recomendaciones_tratamiento` DISABLE KEYS */;
INSERT INTO `rel_gestion_conserv_recomendaciones_tratamiento` (`codigo_gestion`,`recomendaciones_tratamiento`) VALUES 
 (1,'Reparar roturas');
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
  CONSTRAINT `fk_rel_instituciones_serviciosdereproduccion_servicios_reprod` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_instituciones_serviciosdereproduccion_instituciones` FOREIGN KEY (`codigo_institucion`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_instituciones_serviciosdereproduccion`
--

/*!40000 ALTER TABLE `rel_instituciones_serviciosdereproduccion` DISABLE KEYS */;
INSERT INTO `rel_instituciones_serviciosdereproduccion` (`codigo_institucion`,`servicio_reproduccion`) VALUES 
 ('AR/PN/SC/DNPM/CNB','Copiar'),
 ('AR/PN/SC/DNPM/CNSDR','Copiar'),
 ('AR/PN/SC/DNPM/MCASN','Copiar'),
 ('AR/PN/SC/DNPM/PSJ','Microfilmar');
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
  CONSTRAINT `fk_rel_niveles_contenedores_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_contenedores_contenedores1` FOREIGN KEY (`contenedor`) REFERENCES `contenedores` (`contenedor`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_contenedores`
--

/*!40000 ALTER TABLE `rel_niveles_contenedores` DISABLE KEYS */;
INSERT INTO `rel_niveles_contenedores` (`codigo_referencia`,`contenedor`,`ruta_acceso`) VALUES 
 ('AR/PN/SC/DNPM/CNB/Fondo 01','Disco Móvil','asdfasdfadf'),
 ('AR/PN/SC/DNPM/CNB/Fdo_01','No posee','No existe'),
 ('AR/PN/SC/DNPM/CNS/MH','No posee','No posee'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','No posee','no posee'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo','No posee','no posee'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','No posee','r'),
 ('AR/PN/SC/DNPM/MCASN/FI','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','No posee','no posee'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','No posee','no posee'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','No posee','-'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','No posee','.'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','No posee','.'),
 ('AR/PN/SC/DNPM/MJN/FFE','No posee','No posee'),
 ('AR/PN/SC/DNPM/MJN/FRPB','No posee','No posee'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','URL','http://www.cultura.gov.ar/direcciones/?info=organi'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','URL','http://www.cultura.gov.ar/direcciones/?info=organi'),
 ('AR/PN/SC/DNPM/MCY/FRY','URL','http://www.cultura.gov.ar/direcciones/?info=organi'),
 ('AR/PN/SC/DNPM/MHN/FAPC','URL','http://www.cultura.gov.ar/direcciones/?info=organi'),
 ('AR/PN/SC/DNPM/MM/FBM','URL','http://www.museomitre.gov.ar/'),
 ('AR/PN/SC/DNPM/MRPJAT/MT','URL','http://www.cultura.gov.ar/direcciones/?info=organi'),
 ('AR/PN/SC/DNPM/PSJ/FDC','URL','http://www.palaciosanjose.com.ar/arcatroc/01000.rt');
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
 ('AR/PN/SC/DNPM/CNB/Fdo_01','Museo Casa de Yrurtia','caja10'),
 ('AR/PN/SC/DNPM/CNB/Fondo 01','Museo Casa de Yrurtia','asdfadf'),
 ('AR/PN/SC/DNPM/CNS/MH','Museo Casa Natal de Sarmiento','Caja 10 (inventado)'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Museo Casa de Yrurtia','Caja 10 (inventado)'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01/subfdo','Museo Casa de Yrurtia','cajon'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','c'),
 ('AR/PN/SC/DNPM/MCASN/FI','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','Cajas 1'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','Caja 8'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Museo y Biblioteca de la Casa del Acuerdo de San Nicolás','.'),
 ('AR/PN/SC/DNPM/MCY/FRY','Museo Casa de Yrurtia','U'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Museo Histórico Nacional','U'),
 ('AR/PN/SC/DNPM/MJN/FFE','Museo Nacional Estancia Jesuítica de Alta Gracia y Casa del Virrey Liniers','U'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Museo Nacional Estancia Jesuítica de Alta Gracia y Casa del Virrey Liniers','U'),
 ('AR/PN/SC/DNPM/MM/FBM','Museo Mitre','c'),
 ('AR/PN/SC/DNPM/MRPJAT/MT','Museo Regional de Pintura José Antonio Terry','u'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Museo Palacio San José','M!-01 a 200  (Mueble 1-  números0 1 a 200)');
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
  CONSTRAINT `fk_rel_niveles_idiomas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_idiomas_idiomas1` FOREIGN KEY (`idioma`) REFERENCES `idiomas` (`idioma`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_idiomas`
--

/*!40000 ALTER TABLE `rel_niveles_idiomas` DISABLE KEYS */;
INSERT INTO `rel_niveles_idiomas` (`codigo_referencia`,`idioma`) VALUES 
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Alemán'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Alemán'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Español'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Español'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Español'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Español'),
 ('AR/PN/SC/DNPM/MCASN/FI','Español'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Español'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Español'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Español'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Español'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Español'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Español'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Francés'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Francés'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Francés'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Inglés'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Italiano');
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
 ('AR/PN/SC/DNPM/CNSDR/fdo01','AR/PN/SC/DNPM/CNDS','desc');
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
  CONSTRAINT `fk_rel_niveles_institucionesinternas_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_institucionesinternas_instituciones1` FOREIGN KEY (`codigo_referencia_institucion_interna`) REFERENCES `instituciones` (`codigo_identificacion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_institucionesinternas`
--

/*!40000 ALTER TABLE `rel_niveles_institucionesinternas` DISABLE KEYS */;
INSERT INTO `rel_niveles_institucionesinternas` (`codigo_referencia`,`codigo_referencia_institucion_interna`,`descripcion`) VALUES 
 ('AR/PN/SC/DNPM/CNSDR/fdo01','AA','des');
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
 ('AR/PN/SC/DNPM/CNSDR/fdo01','AR/PN/SC/DNPM/CNSDR/fdo01','desd');
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
  CONSTRAINT `fk_rel_niveles_productores_registro_autoridad1` FOREIGN KEY (`nombre_productor`) REFERENCES `registro_autoridad` (`nombre_productor`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_productores_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_productores`
--

/*!40000 ALTER TABLE `rel_niveles_productores` DISABLE KEYS */;
INSERT INTO `rel_niveles_productores` (`codigo_referencia`,`nombre_productor`) VALUES 
 ('AR/PN/SC/DNPM/CNS/MH','Casa Natal de Sarmiento. Monumento Histórico'),
 ('AR/PN/SC/DNPM/CNB/Fondo 01','César Bustamante'),
 ('AR/PN/SC/DNPM/MHN/FAPC','Doctor Adolfo Pedro Carranza'),
 ('AR/PN/SC/DNPM/MJN/FFE','Jorge Betolli'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Justo José de Urquiza'),
 ('AR/PN/SC/DNPM/MM/FBM','Mitre, Bartolomé'),
 ('AR/PN/SC/DNPM/MCASN/FBSN','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JCB','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FBSN/GP/JLS','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FCMJBA','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FDBM','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FDCA','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FI','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FIAG','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FJJU','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FJMG','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FJMR/MaxT/E','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FPRT','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FRGP','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FSRG','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/E/AR','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/FL','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/MCASN/FTPRR/JS','Museo Y Biblioteca Casa Del Acuerdo'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Productor'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Raúl P. Bassani'),
 ('AR/PN/SC/DNPM/MCY/FRY','Rogelio Yrurtia');
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
  CONSTRAINT `fk_rel_niveles_serviciosreproduccion_servicios_reproduccion1` FOREIGN KEY (`servicio_reproduccion`) REFERENCES `servicios_reproduccion` (`servicio_reproduccion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_serviciosreproduccion_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
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
  CONSTRAINT `fk_rel_niveles_sistemasorganizacion_sistemas_organizaciones1` FOREIGN KEY (`sistema_organizacion`) REFERENCES `sistemas_organizaciones` (`sistema_organizacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_sistemasorganizacion_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_sistemasorganizacion`
--

/*!40000 ALTER TABLE `rel_niveles_sistemasorganizacion` DISABLE KEYS */;
INSERT INTO `rel_niveles_sistemasorganizacion` (`codigo_referencia`,`sistema_organizacion`) VALUES 
 ('AR/PN/SC/DNPM/PSJ/FDC','Alfabético'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','Cronológico'),
 ('AR/PN/SC/DNPM/MJN/FRPB','Cronológico');
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
  CONSTRAINT `fk_rel_niveles_soportes_soportes1` FOREIGN KEY (`soporte`) REFERENCES `soportes` (`soporte`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_niveles_soportes_niveles1` FOREIGN KEY (`codigo_referencia`) REFERENCES `niveles` (`codigo_referencia`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_niveles_soportes`
--

/*!40000 ALTER TABLE `rel_niveles_soportes` DISABLE KEYS */;
INSERT INTO `rel_niveles_soportes` (`codigo_referencia`,`soporte`) VALUES 
 ('AR/PN/SC/DNPM/PSJ/FDC','Cartón'),
 ('AR/PN/SC/DNPM/CNSDR/fdo01','DVD'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Papel'),
 ('AR/PN/SC/DNPM/PSJ/FDC','Papel de plano');
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
  CONSTRAINT `fk_rel_usuarios_grupos_usuarios1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuarios_grupos_grupos1` FOREIGN KEY (`idgrupo`) REFERENCES `grupos` (`idgrupo`) ON DELETE NO ACTION ON UPDATE CASCADE
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_usuarios_instituciones`
--

/*!40000 ALTER TABLE `rel_usuarios_instituciones` DISABLE KEYS */;
INSERT INTO `rel_usuarios_instituciones` (`idrel_usuarios_instituciones`,`idusuario`,`codigo_identificacion`) VALUES 
 (3,1,'AR/PN/SC/DNPM/CNS'),
 (6,1,'AR/PN/SC/DNPM/MCASN'),
 (4,1,'AR/PN/SC/DNPM/MCY'),
 (5,1,'AR/PN/SC/DNPM/MM');
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
  CONSTRAINT `fk_rel_usuarios_roles_instituciones_roles1` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuarios_roles_instituciones_rel_usuarios_instituciones1` FOREIGN KEY (`idrel_usuarios_instituciones`) REFERENCES `rel_usuarios_instituciones` (`idrel_usuarios_instituciones`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_usuarios_roles_instituciones`
--

/*!40000 ALTER TABLE `rel_usuarios_roles_instituciones` DISABLE KEYS */;
INSERT INTO `rel_usuarios_roles_instituciones` (`idrel_usuarios_roles_instituciones`,`idrol`,`idrel_usuarios_instituciones`) VALUES 
 (6,5,3);
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
 ('Lector de microfilm'),
 ('PC'),
 ('Proyector cinematográfico'),
 ('Proyector diapositivas'),
 ('Reproductor de cinta abierta'),
 ('Reproductor de DVD');
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `responsable_archivo`
--

/*!40000 ALTER TABLE `responsable_archivo` DISABLE KEYS */;
INSERT INTO `responsable_archivo` (`codigo_responsable`,`apellido`,`nombre`,`area_responsabilidad`,`email`,`telefono`,`movil`,`codigo_institucion`) VALUES 
 (1,'Smit','s','s','sanjoseesp@sanjose.com','s','s','AR/PN/SC/DNPM/PSJ'),
 (2,'No se conoce','No se conoce','No se conoce','No se conoce','No se conoce','No se conoce','AR/PN/SC/DNPM/CNS'),
 (3,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MCASN'),
 (4,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNB'),
 (5,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MJN'),
 (6,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INET'),
 (7,'No hay datos',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INMCV'),
 (8,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INJDP'),
 (9,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MCY'),
 (10,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/ME'),
 (11,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MHN'),
 (12,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MHNCYRM'),
 (13,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNAO'),
 (14,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNG'),
 (15,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MRPJAT'),
 (16,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MR'),
 (17,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNBA'),
 (18,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNAD'),
 (19,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MM'),
 (20,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MHS'),
 (21,'A',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MHDN'),
 (22,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MCHI'),
 (23,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MEJAGYCVL'),
 (24,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INAPL'),
 (25,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/CNML'),
 (26,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INB'),
 (27,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INS'),
 (28,'ap',NULL,NULL,NULL,NULL,NULL,'AA'),
 (29,'fdghoghs','djeohf','3255656','kodhwoi','kldhf','54564655','AR/PN/SC/DNPM/CNSDR'),
 (30,'Schimf','Camilo','Documentos','cschimf@gmail.com','123456','123456','AR/PN/SC/DNPM/Prueba');
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `responsable_institucion`
--

/*!40000 ALTER TABLE `responsable_institucion` DISABLE KEYS */;
INSERT INTO `responsable_institucion` (`codigo_responsable`,`apellido`,`nombre`,`area_responsabilidad`,`email`,`telefono`,`movil`,`codigo_institucion`) VALUES 
 (1,'Apellido','mn','Archivo','&#9472;','4521','4152','AR/PN/SC/DNPM/PSJ'),
 (2,'No se conoce','No se conoce','No se conoce','No se conoce','No se conoce','No se conoce','AR/PN/SC/DNPM/CNS'),
 (3,'Peirano de Mendonca','Diana María','Directora','museodelacuerdo@intercom.com.ar','54 (0) 3461 428980',NULL,'AR/PN/SC/DNPM/MCASN'),
 (5,'Lenarduzzi','D. Nelso','Director','mjn-jm@coop5.com.ar','54 (3525) 420-126','No posee','AR/PN/SC/DNPM/MJN'),
 (6,'Lastra Belgrano','María Cristina','Directora','estudiosdeteatro@inet.gov.ar','54+11 4815-8817','54+11 4815-8883','AR/PN/SC/DNPM/INET'),
 (7,'No hay datos',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INMCV'),
 (8,'Pepe','Lorenzo','Secretario General. Diputado.',NULL,NULL,NULL,'AR/PN/SC/DNPM/INJDP'),
 (10,'Mastromauro','Osvaldo','Director','info@museocasadeyrurtia.gov.ar','(54 11) 4781-0385',NULL,'AR/PN/SC/DNPM/MCY'),
 (11,'Durango','Norma','Directora','info@museoevita.org','(011) 4807-0630',NULL,'AR/PN/SC/DNPM/ME'),
 (12,'Perez Gollán','José Antonio','Director','informes@mhn.gov.ar','(54 11) 4307-1182',NULL,'AR/PN/SC/DNPM/MHN'),
 (13,'Vernet Martinez','María Angélica','Directora','cabildomuseo_nac@cultura.gov.ar','(+54 - 11) 4342-6729 y 4334-1782',NULL,'AR/PN/SC/DNPM/MHNCYRM'),
 (14,'Siquier','Alejandra',NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNAO'),
 (15,'Duprat','Andrés','Interventor','museodelgrabado@yahoo.com.ar','(011) 4802-3295',NULL,'AR/PN/SC/DNPM/MNG'),
 (16,'Tinte','D. Francisco','Director','museoterry@tilcanet.com.ar','54 (388) 495-5005',NULL,'AR/PN/SC/DNPM/MRPJAT'),
 (17,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MR'),
 (18,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MNBA'),
 (19,'Bellucci','Alberto G.','Director','museo@mnad.org','(54-11) 4801-8248','4802-6606 / 4806-8306','AR/PN/SC/DNPM/MNAD'),
 (20,'Gowland','María Angélica','Directora','museomitre@infovia.com.ar','54 11 4394-8240/7659',NULL,'AR/PN/SC/DNPM/MM'),
 (21,'Gaudencio de Germani','Marta','Directora','martagermani@museosarmiento.gov.ar','(011) 4783 - 7555 // (011) 4781 - 2989','No posee','AR/PN/SC/DNPM/MHS'),
 (22,'Ríos','María Ester','Dirección','cabildosalta@uolsinectis.com.ar','0387 - 4215340',NULL,'AR/PN/SC/DNPM/MHDN'),
 (23,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MCHI'),
 (24,'a',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/MEJAGYCVL'),
 (25,'ROLANDI ','Diana','Directora',NULL,NULL,NULL,'AR/PN/SC/DNPM/INAPL'),
 (26,'Raffellini','Patricia','Coordinador General',NULL,NULL,NULL,'AR/PN/SC/DNPM/CNML'),
 (27,'Menotti ','Emilia Edda',NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INB'),
 (28,'No se conoce',NULL,NULL,NULL,NULL,NULL,'AR/PN/SC/DNPM/INS'),
 (29,'ap',NULL,NULL,NULL,NULL,NULL,'AA'),
 (30,'sdgfuda','hfoifheq','vnwgw','nsoighwi@ksgfiwuegf','3736786783','65785785','AR/PN/SC/DNPM/CNSDR'),
 (31,'Rodriguez','Gabriela','Cargo de Prueba','grodriguez@yahoo.com','123456','123456','AR/PN/SC/DNPM/Prueba');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`idrol`,`rol`,`activo`) VALUES 
 (1,'Administrador DNPyM',0x01),
 (2,'Administrador Institucion',0x01),
 (3,'Conservador',0x01),
 (4,'Usuario Carga Institucion',0x01),
 (5,'Administrador Sistemas',0x01),
 (6,'Usuario Consulta',0x01);
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
 ('Autores De Letra'),
 ('Dirección'),
 ('Interpretación'),
 ('Títulos de las Obras');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `secciones`
--

/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
INSERT INTO `secciones` (`idseccion`,`descripcion`) VALUES 
 (15,'Administración - Tablas Validadas'),
 (14,'Diplomáticos  - Conservación'),
 (9,'Diplomáticos y Área Administración'),
 (10,'Diplomáticos y Área Descripción'),
 (11,'Diplomáticos y Área Identificación'),
 (12,'Diplomáticos y Área Notas'),
 (3,'Instituciones - Área Administración'),
 (2,'Instituciones - Área Descripción'),
 (1,'Instituciones - Área Identificación'),
 (4,'Instituciones - Área Notas'),
 (5,'Niveles - Area Administración'),
 (6,'Niveles - Area Descripción'),
 (7,'Niveles - Area Identificación'),
 (8,'Niveles - Área Notas'),
 (17,'Reportes Administración'),
 (16,'Reportes Conservación'),
 (13,'Verificar Documentos Completos');
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
 ('Alfabético'),
 ('Cronológico'),
 ('Geográfico'),
 ('Numérico');
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
 ('Mixto'),
 ('Mudo'),
 ('Sonoro');
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
 ('Betacam'),
 ('Betamax'),
 ('Canson'),
 ('Cartón'),
 ('DVD'),
 ('Microfilm'),
 ('Papel'),
 ('Papel continuo'),
 ('Papel de calco'),
 ('Papel de plano'),
 ('Papel positivo'),
 ('Papel vegetal'),
 ('Soporte magnético'),
 ('Soporte óptico'),
 ('Super VHS'),
 ('U-matic'),
 ('VHS'),
 ('Video 8');
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
 ('BMP'),
 ('GIF'),
 ('JPG'),
 ('No especificado'),
 ('TIFF');
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
 ('Ambrotipo'),
 ('Calotipo'),
 ('Daguerrotipo'),
 ('Ferrotipo'),
 ('Otras');
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
 ('Grabado al vacío'),
 ('Grabado en relieve - Fotograbado'),
 ('Grabado en relieve - Fototipía'),
 ('Grabado en relieve - Linografía'),
 ('Grabado en relieve - Xilografía'),
 ('Serigrafía');
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
 ('Sede Central'),
 ('Sede Temporaria'),
 ('Sociedad Civil'),
 ('Sucursales'),
 ('Tipos tjcjx5451454758\"#$=======');
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
 ('Film'),
 ('Fotos'),
 ('Grabados'),
 ('Imagen en mapa de bits'),
 ('Impresos'),
 ('Mapas'),
 ('Negativos fotográficos'),
 ('Películas'),
 ('Planos'),
 ('Positivos fotográficos'),
 ('Recorte textual impreso'),
 ('Recorte textual manuscrito'),
 ('Recorte visual a mano alzada'),
 ('Recorte visual impreso'),
 ('Testimonios orales'),
 ('Videos');
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
  CONSTRAINT `fk_tips_areas_tips1` FOREIGN KEY (`area`) REFERENCES `areas_tips` (`area`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

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
 (26,'Describe la forma más conocida por la cual se conoce al nivel que contiene la unidad de descripción','Niveles',1),
 (27,'Traducción a otro idioma del título original que figura en la unidad de descripción. ','Niveles',0),
 (28,'Día, mes y año en que se asignó el número de registro a los documentos de la unidad de descripción ingresados en el archivo o centro de referencia.','Niveles',1),
 (29,'Número que identifica la custodia patrimonial administrativa de la unidad de descripción.','Niveles',0),
 (30,'Requiere el número de inventario asignado al documento previo a la aplicación del estándar normalizado.','Niveles',0),
 (31,'Describe la pertenencia del nivel al Fondo tesoro. Puede tratarse de un Fondo tesoro identificado por el Productor del Fondo, o bien, puede identificarse como Fondo tesoro aquella documentación considerada tesoro patrimonial por una acción de valoración contemporánea y/o la aplicación de una resolución oficial. Fondo tesoro: conformados por los documentos de mayor relevancia del que custodia el archivo.','Niveles',1),
 (32,'Datos que indican la ubicación espacial de los niveles y las unidades de descripción. ','Niveles',1),
 (33,'Indica en qué estará contenida la unidad de descripción, y el camino informático para un documento electrónico, se indicará la máquina, la unidad de volumen, estructura de directorios, nombre de archivo.','Niveles',1),
 (34,'Nombre de la persona o personas, órgano y órganos administrativos o entidad responsable de la producción de la documentación de la unidad de descripción. ','Niveles',1),
 (35,'Fecha del inicio de la producción de la unidad de descripción. ','Niveles',1),
 (36,'Fecha de finalización de la producción de la documentación de la unidad de descripción. ','Niveles',1),
 (37,'Sinopsis breve de los asuntos, temas o aspectos esenciales tratados en la documentación de la unidad de descripción. \r\n','Niveles',1),
 (38,'Descripción fisicotécnica del tipo de material en que se presenta la documentación de la unidad de descripción.','Niveles',0),
 (39,'Lengua utilizada en la documentación de la unidad de descripción.','Niveles',0),
 (40,'Cantidad de metros de estantería del nivel que lo componen.','Niveles',1),
 (41,'Unidades contenidas.','Niveles',1),
 (42,'Describe la historia de la institución, familia o biografía de la persona productora del fondo y de los niveles. Fechas de existencia, lugares, misiones y funciones ejercidas, cambios y características. ','Niveles',1),
 (43,'Descripción de los lugares de custodia y características de intervención técnica archivísticas aplicadas al Fondo y niveles. Asimismo las personas que intervinieron. ','Niveles',1),
 (44,'Reseña los códigos de referencia de niveles relacionados.','Niveles',0),
 (45,'Describe la relación entre los niveles y alguna institución de las que utilizan el sistema.','Niveles',0),
 (46,'Describe el vínculo de los niveles con instituciones que no usan la aplicación.','Niveles',0),
 (47,'Se indicará la estructura interna de organización, el sistema de clasificación y/o el orden de la documentación de la unidad de descripción.','Niveles',0),
 (48,'Indica la forma de ingreso de la documentación a la institución. ','Niveles',1),
 (49,'Nombre de la persona o entidad que donó, vendió, legó, prestó, etc. El documento al organismo. ','Niveles',1),
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
 (112,'Se indica el sobre, envoltorio, caja, carpeta, estante abierto, cajón, etc. Esto es previo a la conservación.','Conservación',1),
 (113,'Fecha en la que se produjo la sustracción del documento.','Robo',1),
 (114,'Fecha en la que se produjo la apertura del Sumario','Robo',0),
 (115,'Fecha en la que se informa el cierre del sumario','Robo',0),
 (116,'Nombre de la persona que denuncia el robo del documento.','Robo',1),
 (117,'Número de la Causa Judicial correspondiente al robo','Robo',0),
 (118,'Número de Juzgado que atiende la causa','Robo',0),
 (119,'Tipo de Trámite administrativo que se ha realizado por el tema del robo.','Robo',0),
 (120,'Número de Sumario Administrativo asociado al robo','Robo',0),
 (121,'Descripción del fallo asociado al robo del documento.','Robo',0),
 (122,'En el caso en que el documento aparezca o se lo recupere de alguna forma, indicar dicha fecha','Robo',0),
 (123,'Identificador de INTERPOL, se lo obtiene del sistema correspondiente y se lo copia en este','Robo',0),
 (124,'Fecha en la que se registra el robo para INTERPOL','Robo',0),
 (125,'Fecha en que se entrega el documento para la exposición, esta fecha modifica la última fecha visú.','Exposición',1);
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
 ('Borrador'),
 ('Copia auténtica'),
 ('Copia digital'),
 ('Copia electrónica'),
 ('Copia fotográfica'),
 ('Copia Heliográfica'),
 ('Copia manuscrita'),
 ('Falso'),
 ('Original'),
 ('Transcripción');
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
 ('Bisagras de montaje'),
 ('Cintas adhesivas'),
 ('Fragilidad'),
 ('injertos'),
 ('Reintegración de pérdidas'),
 ('Reparación de roturas'),
 ('Residuos de adhesivos'),
 ('Retoque de color');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuarios`
--

/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`idusuario`,`usuario`,`pass`,`fecha_alta`,`nombre`,`apellido`,`email`,`telefono`,`activo`,`fecha_ultimo_acceso`,`fecha_baja`) VALUES 
 (1,'camilo','camilo1',NULL,'camilo','schimf','mschimf@gylgroup.com','48284257',0x01,NULL,NULL),
 (2,'ksaez','70f9086166b4fa751b37832e1de39ea2','2011-05-06 00:00:00','Karina ','Saez','karinasaez@yahoo.com','373737373',0x01,NULL,NULL),
 (3,'gnn','c65aea2c82a552d83ef8f02d8845ab2d','2011-06-17 00:00:00','Gabriela','Nazar','gnn@e-mail.com','4587-8965',0x01,NULL,NULL);
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
 ('Demo'),
 ('Final');
/*!40000 ALTER TABLE `versiones` ENABLE KEYS */;


--
-- Definition of view `vis_estados_niveles`
--

DROP TABLE IF EXISTS `vis_estados_niveles`;
DROP VIEW IF EXISTS `vis_estados_niveles`;
CREATE ALGORITHM=UNDEFINED DEFINER=`mschimf`@`%` SQL SECURITY DEFINER VIEW `vis_estados_niveles` AS (select `instituciones`.`codigo_identificacion` AS `codigo_referencia`,'0' AS `tipo`,`instituciones`.`formas_conocidas_nombre` AS `nombre`,(select `instituciones_estados`.`estado` AS `estado` from `instituciones_estados` where (`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`) order by `instituciones_estados`.`fecha` desc limit 1) AS `estado`,max(`instituciones_estados`.`fecha`) AS `fecha`,'0' AS `cod_ref_sup`,`instituciones`.`codigo_identificacion` AS `codigo_institucion`,'Vigente' AS `estado_cod_ref_sup`,'0' AS `tv`,'0' AS `tipo_diplomatico`,'0' AS `tipo_nivel`,'Local' AS `situacion`,`instituciones`.`fecha_ultima_modificacion` AS `fecha_ultima_modificacion` from (`instituciones` join `instituciones_estados` on((`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`))) where (0 = 0) group by `instituciones`.`codigo_identificacion`) union (select `niveles`.`codigo_referencia` AS `codigo_referencia`,`niveles`.`tipo_nivel` AS `tipo`,`niveles`.`titulo_original` AS `nombre`,(select `niveles_estados`.`estado` AS `estado` from `niveles_estados` where (`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`) order by `niveles_estados`.`fecha` desc limit 1) AS `estado`,max(`niveles_estados`.`fecha`) AS `fecha`,`niveles`.`cod_ref_sup` AS `cod_ref_sup`,`niveles`.`codigo_institucion` AS `codigo_institucion`,(select `vis_niveles`.`estado` AS `estado` from `vis_niveles` where (`vis_niveles`.`codigo_referencia` = `niveles`.`cod_ref_sup`)) AS `estado_cod_ref_sup`,`niveles`.`tv` AS `tv`,'1' AS `tipo_diplomatico`,`niveles`.`tipo_nivel` AS `tipo_nivel`,'Local' AS `situacion`,`niveles`.`fecha_ultima_modificacion` AS `fecha_ultima_modificacion` from (`niveles` join `niveles_estados` on((`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`))) where (0 = 0) group by `niveles`.`codigo_referencia`) union (select `documentos`.`codigo_referencia` AS `codigo_referencia`,`documentos`.`tipo_diplomatico` AS `tipo`,`documentos`.`titulo_original` AS `nombre`,(select `documentos_estados`.`estado` AS `estado` from `documentos_estados` where (`documentos_estados`.`codigo_referencia` = `documentos`.`codigo_referencia`) order by `documentos_estados`.`fecha` desc limit 1) AS `estado`,max(`documentos_estados`.`fecha`) AS `fecha`,`documentos`.`cod_ref_sup` AS `cod_ref_sup`,`documentos`.`codigo_institucion` AS `codigo_institucion`,(select `vis_niveles`.`estado` AS `estado` from `vis_niveles` where (`vis_niveles`.`codigo_referencia` = `documentos`.`cod_ref_sup`)) AS `estado_cod_ref_sup`,`documentos`.`tv` AS `tv`,`documentos`.`tipo_diplomatico` AS `tipo_diplomatico`,`documentos`.`tipo_diplomatico` AS `tipo_nivel`,`documentos`.`situacion` AS `situacion`,`documentos`.`fecha_ultima_modificacion` AS `fecha_ultima_modificacion` from (`documentos` join `documentos_estados` on((`documentos`.`codigo_referencia` = `documentos_estados`.`codigo_referencia`))) where (0 = 0) group by `documentos`.`codigo_referencia`) order by `codigo_referencia`;

--
-- Definition of view `vis_niveles`
--

DROP TABLE IF EXISTS `vis_niveles`;
DROP VIEW IF EXISTS `vis_niveles`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vis_niveles` AS (select `instituciones`.`codigo_identificacion` AS `codigo_referencia`,'0' AS `tipo`,`instituciones`.`formas_conocidas_nombre` AS `nombre`,(select `instituciones_estados`.`estado` AS `estado` from `instituciones_estados` where (`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`) order by `instituciones_estados`.`fecha` desc limit 1) AS `estado`,max(`instituciones_estados`.`fecha`) AS `fecha`,'0' AS `cod_ref_sup`,`instituciones`.`codigo_identificacion` AS `codigo_institucion` from (`instituciones` join `instituciones_estados` on((`instituciones`.`codigo_identificacion` = `instituciones_estados`.`codigo_referencia`))) where (0 = 0) group by `instituciones`.`codigo_identificacion`) union (select `niveles`.`codigo_referencia` AS `codigo_referencia`,`niveles`.`tipo_nivel` AS `tipo`,`niveles`.`titulo_original` AS `nombre`,(select `niveles_estados`.`estado` AS `estado` from `niveles_estados` where (`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`) order by `niveles_estados`.`fecha` desc limit 1) AS `estado`,max(`niveles_estados`.`fecha`) AS `fecha`,`niveles`.`cod_ref_sup` AS `cod_ref_sup`,`niveles`.`codigo_institucion` AS `codigo_institucion` from (`niveles` join `niveles_estados` on((`niveles`.`codigo_referencia` = `niveles_estados`.`codigo_referencia`))) where (0 = 0) group by `niveles`.`codigo_referencia`) union (select `documentos`.`codigo_referencia` AS `codigo_referencia`,`documentos`.`tipo_diplomatico` AS `tipo`,`documentos`.`titulo_original` AS `nombre`,(select `documentos_estados`.`estado` AS `estado` from `documentos_estados` where (`documentos_estados`.`codigo_referencia` = `documentos`.`codigo_referencia`) order by `documentos_estados`.`fecha` desc limit 1) AS `estado`,max(`documentos_estados`.`fecha`) AS `fecha`,`documentos`.`cod_ref_sup` AS `cod_ref_sup`,`documentos`.`codigo_institucion` AS `codigo_institucion` from (`documentos` join `documentos_estados` on((`documentos`.`codigo_referencia` = `documentos_estados`.`codigo_referencia`))) where (0 = 0) group by `documentos`.`codigo_referencia`) order by `codigo_referencia`;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
