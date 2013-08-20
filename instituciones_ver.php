<?php require_once('Connections/conn.php'); ?>
<?php require_once('funtions.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index_log.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

/* vemos los estados*/
$colname_vis_estados = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_estados = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_estados = sprintf("SELECT * FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($colname_vis_estados, "text"));
$vis_estados = mysql_query($query_vis_estados, $conn) or die(mysql_error());
$row_vis_estados = mysql_fetch_assoc($vis_estados);
$totalRows_vis_estados = mysql_num_rows($vis_estados);

$_SESSION['estado'] = $row_vis_estados['estado'];

$editFormAction = $_SERVER['PHP_SELF'];
$pageFormAction = $_SERVER ['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*borramos direcciones */
if (isset($_GET['el']) &&  $_GET['el'] != "" ) {
	$deleteSQL1 = sprintf("DELETE FROM direcciones_instituciones WHERE codigo_direcciones=%s",
                       GetSQLValueString($_GET['el'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error());
}

/* borramos responsables de insituticiones */
 if (isset($_GET['elri']) &&  $_GET['elri'] != "" ) {
	$deleteSQL2 = sprintf("DELETE FROM responsable_institucion WHERE codigo_responsable=%s",
                       GetSQLValueString($_GET['elri'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($deleteSQL2, $conn) or die(mysql_error());
}

/* borramos responsables de archivos */
 if (isset($_GET['elra']) &&  $_GET['elra'] != "" ) {
	$deleteSQL3 = sprintf("DELETE FROM responsable_archivo WHERE codigo_responsable=%s",
                       GetSQLValueString($_GET['elra'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result3 = mysql_query($deleteSQL3, $conn) or die(mysql_error());
}

/* borramos formas autorizadas de nombres */
 if (isset($_GET['elfa']) &&  $_GET['elfa'] != "" ) {
	$deleteSQL15 = sprintf("DELETE FROM formas_autorizadas_nombre WHERE codigo_formas_autorizadas_nombre=%s",
                       GetSQLValueString($_GET['elfa'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result15 = mysql_query($deleteSQL15, $conn) or die(mysql_error());
}

/* AREA DE IDENTIFICACION -------------------------------------------------------------------------------------------*/
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
 /*actualizar datos instituciones */
 $updateSQL4 = sprintf("UPDATE instituciones SET formas_conocidas_nombre=%s, tipo_institucion=%s, email=%s, www=%s, codigo_interno=%s WHERE codigo_identificacion=%s",
                       GetSQLValueString($_POST['formas_conocidas_nombre'], "text"),
                       GetSQLValueString($_POST['tipo_institucion'], "text"),
					   GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['www'], "text"),
					   GetSQLValueString($_POST['codigo_interno'], "text"),
					   GetSQLValueString($_POST['codigo_identificacion'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result4 = mysql_query($updateSQL4, $conn) or die(mysql_error());

  if (isset($_POST['codigo_direcciones']) && $_POST['codigo_direcciones'] != "") {
	  $updateSQL5 = sprintf("UPDATE direcciones_instituciones SET calle=%s, numero=%s, codigo_postal=%s, provincia=%s, casilla_correo=%s, ciudad=%s, pais=%s, telefonos=%s, fax=%s, tipo_direccion=%s WHERE codigo_direcciones = %s",
                       GetSQLValueString($_POST['calle'], "text"),
                       GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['codigo_postal'], "text"),
                       GetSQLValueString($_POST['casilla_correo'], "text"),
					   GetSQLValueString($_POST['ciudad'], "text"),
					   GetSQLValueString($_POST['provincia'], "text"),
					   GetSQLValueString($_POST['pais'], "text"),
					   GetSQLValueString($_POST['telefonos'], "text"),
					   GetSQLValueString($_POST['fax'], "text"),
					   GetSQLValueString($_POST['tipo_direccion'], "text"),
					   GetSQLValueString($_POST['codigo_direcciones'], "int"));
					   
	 mysql_select_db($database_conn, $conn);
  	 $Result5 = mysql_query($updateSQL5, $conn) or die(mysql_error());
	  
  } elseif (isset($_POST['codigo_direcciones']) && $_POST['codigo_direcciones'] == "" && $_POST['calle'] != "") {
  /* insercion de datos tabla direcciones */
  $insertSQL6 = sprintf("INSERT INTO direcciones_instituciones (codigo_institucion, calle, numero, codigo_postal, provincia, casilla_correo, ciudad, pais, telefonos, fax, tipo_direccion) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['codigo_identificacion'], "text"),
                       GetSQLValueString($_POST['calle'], "text"),
                       GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['codigo_postal'], "text"),
                       GetSQLValueString($_POST['casilla_correo'], "text"),
					   GetSQLValueString($_POST['ciudad'], "text"),
					   GetSQLValueString($_POST['provincia'], "text"),
					   GetSQLValueString($_POST['pais'], "text"),
					   GetSQLValueString($_POST['telefonos'], "text"),
					   GetSQLValueString($_POST['fax'], "text"),
					   GetSQLValueString($_POST['tipo_direccion'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result6 = mysql_query($insertSQL6, $conn) or die(mysql_error());
  
  }
  
  /* insertamos o modificamos resposables de instituciones */
   if (isset($_POST['codigo_responsable']) && $_POST['codigo_responsable'] != "") {
	$updateSQL7 = sprintf("UPDATE responsable_institucion SET codigo_institucion=%s, apellido=%s, nombre=%s, area_responsabilidad=%s, email=%s, telefono=%s, movil=%s WHERE codigo_responsable=%s",
                       GetSQLValueString($_POST['codigo_identificacion'], "text"),
                       GetSQLValueString($_POST['apellidori'], "text"),
                       GetSQLValueString($_POST['nombreri'], "text"),
                       GetSQLValueString($_POST['area_responsabilidadri'], "text"),
                       GetSQLValueString($_POST['emailri'], "text"),
					   GetSQLValueString($_POST['telefonori'], "text"),
					   GetSQLValueString($_POST['movilri'], "text"),
					   GetSQLValueString($_POST['codigo_responsable'], "text"));
					   
	 mysql_select_db($database_conn, $conn);
	$Result7 = mysql_query($updateSQL7, $conn) or die(mysql_error());
	
	} elseif (isset($_POST['codigo_responsable']) && $_POST['codigo_responsable'] == "" && $_POST['apellidori'] != "") {
	$insertSQL8 = sprintf("INSERT INTO responsable_institucion (codigo_institucion, apellido, nombre, area_responsabilidad, email, telefono, movil) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['codigo_identificacion'], "text"),
                       GetSQLValueString($_POST['apellidori'], "text"),
                       GetSQLValueString($_POST['nombreri'], "text"),
                       GetSQLValueString($_POST['area_responsabilidadri'], "text"),
                       GetSQLValueString($_POST['emailri'], "text"),
					   GetSQLValueString($_POST['telefonori'], "text"),
					   GetSQLValueString($_POST['movilri'], "text"));
					   
	 mysql_select_db($database_conn, $conn);
	$Result8 = mysql_query($insertSQL8, $conn) or die(mysql_error());
	}
	
	/* insertamos o modificamos responsables de archivos */
	   if (isset($_POST['codigo_responsablera']) && $_POST['codigo_responsablera'] != "") {
	$updateSQL9 = sprintf("UPDATE responsable_archivo SET codigo_institucion=%s, apellido=%s, nombre=%s, area_responsabilidad=%s, email=%s, telefono=%s, movil=%s WHERE codigo_responsable=%s",
                       GetSQLValueString($_POST['codigo_identificacion'], "text"),
                       GetSQLValueString($_POST['apellidora'], "text"),
                       GetSQLValueString($_POST['nombrera'], "text"),
                       GetSQLValueString($_POST['area_responsabilidadra'], "text"),
                       GetSQLValueString($_POST['emailra'], "text"),
					   GetSQLValueString($_POST['telefonora'], "text"),
					   GetSQLValueString($_POST['movilra'], "text"),
					   GetSQLValueString($_POST['codigo_responsablera'], "text"));
					   
	 mysql_select_db($database_conn, $conn);
	$Result9 = mysql_query($updateSQL9, $conn) or die(mysql_error());
	} elseif (isset($_POST['codigo_responsablera']) && $_POST['codigo_responsablera'] == "" && $_POST['apellidora'] != "") {
	$insertSQL10 = sprintf("INSERT INTO responsable_archivo (codigo_institucion, apellido, nombre, area_responsabilidad, email, telefono, movil) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['codigo_identificacion'], "text"),
                       GetSQLValueString($_POST['apellidora'], "text"),
                       GetSQLValueString($_POST['nombrera'], "text"),
                       GetSQLValueString($_POST['area_responsabilidadra'], "text"),
                       GetSQLValueString($_POST['emailra'], "text"),
					   GetSQLValueString($_POST['telefonora'], "text"),
					   GetSQLValueString($_POST['movilra'], "text"));
					   
	 mysql_select_db($database_conn, $conn);
	$Result10 = mysql_query($insertSQL10, $conn) or die(mysql_error());
	}
	
	/*insertamos o modificamos los resgistros de las formas autorizadas de nombres*/
	if (isset($_POST['codigo_formas_autorizadas_nombre']) && $_POST['codigo_formas_autorizadas_nombre'] != "") {
		$updateSQL16 = sprintf("UPDATE formas_autorizadas_nombre SET forma_autorizada_nombre=%s WHERE codigo_formas_autorizadas_nombre=%s",
                       GetSQLValueString($_POST['forma_autorizada_nombre'], "text"),
                       GetSQLValueString($_POST['codigo_formas_autorizadas_nombre'], "int"));
					   
		mysql_select_db($database_conn, $conn);
		$Result16 = mysql_query($updateSQL16, $conn) or die(mysql_error());			   
	} else if(isset($_POST['codigo_formas_autorizadas_nombre']) && $_POST['codigo_formas_autorizadas_nombre'] == "" && $_POST['forma_autorizada_nombre'] != "") {
		$updateSQL17 = sprintf("INSERT INTO formas_autorizadas_nombre (forma_autorizada_nombre, codigo_identificacion) VALUES (%s, %s)",
                       GetSQLValueString($_POST['forma_autorizada_nombre'], "text"),
                       GetSQLValueString($_POST['codigo_identificacion'], "text"));
					   
		mysql_select_db($database_conn, $conn);
		$Result17 = mysql_query($updateSQL17, $conn) or die(mysql_error());	
	}
  
 /* Pasamos al estado 2 */  
if($_POST['codigo_identificacion'] != "" && $_POST['cant_fa'] >= 1 && $_POST['formas_conocidas_nombre'] != "" && $_POST['tipo_institucion'] != "" && $_POST['cant_dir'] >= 1 && $_POST['telefono_de_contacto'] != "" && $_POST['email'] != "" && $_POST['cant_pri'] >= 1 && $_POST['cant_pra'] >= 1 && $_SESSION['estado'] == 1) {
	
	$updateSQL18 = sprintf("UPDATE instituciones SET estado=2 WHERE codigo_identificacion=%s",
					   GetSQLValueString($_POST['codigo_identificacion'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result18 = mysql_query($updateSQL18, $conn) or die(mysql_error());
	
}
  
  $updateGoTo = "instituciones_update.php?cod=".$_POST['codigo_identificacion'];
 header(sprintf("Location: %s", $updateGoTo));
}


/* AREA DE DESCRIPCION -------------------------------------------------------------------------------------------*/
/* actualizamos instituciones en el area de descripcion */
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL11 = sprintf("UPDATE instituciones SET historia=%s, estructura_organica=%s, politicas_ingresos=%s, instalaciones_archivo=%s, fondos_agrupaciones=%s WHERE codigo_identificacion=%s",
                       GetSQLValueString($_POST['historia'], "text"),
                       GetSQLValueString($_POST['estructura_organica'], "text"),
                       GetSQLValueString($_POST['politicas_ingresos'], "text"),
                       GetSQLValueString($_POST['instalaciones_archivo'], "text"),
                       GetSQLValueString($_POST['fondos_agrupaciones'], "text"),
                       GetSQLValueString($_POST['codigo_identificacion2'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result11 = mysql_query($updateSQL11, $conn) or die(mysql_error());
  
    /* Pasamos al estado 3 */
  if($_POST['historia'] != "" && $_POST['politicas_ingresos'] != "" && $_POST['instalaciones_archivo'] != "" && $_POST['cant_fondos'] >= 1 && $_SESSION['estado'] == 2) {
	
	$updateSQL19 = sprintf("UPDATE instituciones SET estado=3 WHERE codigo_identificacion=%s",
					   GetSQLValueString($_POST['codigo_identificacion2'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result19 = mysql_query($updateSQL19, $conn) or die(mysql_error());
	
}

  $updateGoTo = "instituciones_update.php?cod=".$_POST['codigo_identificacion2'];
  header(sprintf("Location: %s", $updateGoTo));
}

/* AREA DE ADMINISTRACION -------------------------------------------------------------------------------------------*/

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL12 = sprintf("UPDATE instituciones SET tipo_acceso=%s, requisito_acceso=%s, acceso_documentacion=%s, dias_apertura=%s, horaios_apertura=%s, fechas_anuales_cierre=%s, servicio_reproduccion=%s WHERE codigo_identificacion=%s",
                       GetSQLValueString($_POST['tipo_acceso'], "text"),
                       GetSQLValueString($_POST['requisito_acceso'], "text"),
                       GetSQLValueString($_POST['acceso_documentacion'], "text"),
                       GetSQLValueString($_POST['dias_apertura'], "text"),
                       GetSQLValueString($_POST['horaios_apertura'], "text"),
                       GetSQLValueString($_POST['fechas_anuales_cierre'], "text"),
                       GetSQLValueString($_POST['servicio_reproduccion'], "text"),
                       GetSQLValueString($_POST['codigo_identificacion3'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result12 = mysql_query($updateSQL12, $conn) or die(mysql_error());
  
    /* Pasamos al estado 4 */
  if($_POST['tipo_acceso'] != "" && $_POST['dias_apertura'] != "" && $_POST['horaios_apertura'] != "" && $_POST['fechas_anuales_cierre'] != "" && $_POST['servicio_reproduccion'] != "" && $_SESSION['estado'] == 3) {
	
	$updateSQL20 = sprintf("UPDATE instituciones SET estado=4 WHERE codigo_identificacion=%s",
					   GetSQLValueString($_POST['codigo_identificacion3'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result20 = mysql_query($updateSQL20, $conn) or die(mysql_error());
	
}

  $updateGoTo = "instituciones_update.php?cod=".$_POST['codigo_identificacion3'];
  header(sprintf("Location: %s", $updateGoTo));
}

/* AREA DE NOTAS -------------------------------------------------------------------------------------------*/

/* insertamos o modificamos las notas */
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
	date_default_timezone_set('UTC');
	
	if(isset($_POST['codigo_notas']) && $_POST['codigo_notas'] != "") {
		
		$updateSQL13 = sprintf("UPDATE area_de_notas_instituciones SET nota_descripcion=%s, fecha_modificacion=%s, codigo_institucion=%s WHERE codigo_notas=%s",
                       GetSQLValueString($_POST['nota_descripcion'], "text"),
					   GetSQLValueString(date("Ymd"), "date"),
					   GetSQLValueString($_POST['codigo_identificacion4'], "text"),
					   GetSQLValueString($_POST['codigo_notas'], "int"));

  mysql_select_db($database_conn, $conn);
  $updateSQL13 = mysql_query($updateSQL13, $conn) or die(mysql_error());
		
	} else {
	
  $insertSQL14 = sprintf("INSERT INTO area_de_notas_instituciones (nota_descripcion, fecha_descripcion, codigo_institucion) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['nota_descripcion'], "text"),
					   GetSQLValueString(date("Ymd"), "date"),
					   GetSQLValueString($_POST['codigo_identificacion4'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result14 = mysql_query($insertSQL14, $conn) or die(mysql_error()); 
  
	}
	
	    /* Pasamos al estado 5 */
  if($_POST['nota_descripcion'] != "" && $_SESSION['estado'] == 4) {
	
	$updateSQL21 = sprintf("UPDATE instituciones SET estado=5 WHERE codigo_identificacion=%s",
					   GetSQLValueString($_POST['codigo_identificacion4'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result21 = mysql_query($updateSQL21, $conn) or die(mysql_error());
	
}

   $updateGoTo = "instituciones_update.php?cod=".$_POST['codigo_identificacion4'];
  header(sprintf("Location: %s", $updateGoTo));
}


/* AREA DE ESTADOS -------------------------------------------------------------------------------------------*/


/* QUERYS -------------------------------------------------------------------------------------------*/
/* auxiliar tipo de institucion */
mysql_select_db($database_conn, $conn);
$query_vis_tipo_institucion = "SELECT * FROM tipo_institucion ORDER BY tipo_institucion ASC";
$vis_tipo_institucion = mysql_query($query_vis_tipo_institucion, $conn) or die(mysql_error());
$row_vis_tipo_institucion = mysql_fetch_assoc($vis_tipo_institucion);
$totalRows_vis_tipo_institucion = mysql_num_rows($vis_tipo_institucion);


/* auxiliar tipo de entidad */
/* mysql_select_db($database_conn, $conn);
$query_vis_tipo_entidad = "SELECT * FROM tipo_entidad ORDER BY tipo_entidad ASC";
$vis_tipo_entidad = mysql_query($query_vis_tipo_entidad, $conn) or die(mysql_error());
$row_vis_tipo_entidad = mysql_fetch_assoc($vis_tipo_entidad);
$totalRows_vis_tipo_entidad = mysql_num_rows($vis_tipo_entidad); 
*/


/* datos principales de instituciones */
$colname_vis_instituciones = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_instituciones = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_instituciones = sprintf("SELECT * FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($colname_vis_instituciones, "text"));
$vis_instituciones = mysql_query($query_vis_instituciones, $conn) or die(mysql_error());
$row_vis_instituciones = mysql_fetch_assoc($vis_instituciones);
$totalRows_vis_instituciones = mysql_num_rows($vis_instituciones);

/* extranccion de direcciones */
$colname_vis_direccionesinstituciones = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_direccionesinstituciones = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_direccionesinstituciones = sprintf("SELECT * FROM direcciones_instituciones WHERE codigo_institucion = %s ORDER BY calle ASC", GetSQLValueString($colname_vis_direccionesinstituciones, "text"));
$vis_direccionesinstituciones = mysql_query($query_vis_direccionesinstituciones, $conn) or die(mysql_error());
$row_vis_direccionesinstituciones = mysql_fetch_assoc($vis_direccionesinstituciones);
$totalRows_vis_direccionesinstituciones = mysql_num_rows($vis_direccionesinstituciones);

/* direccioens para update */
$colname_vis_unidaddireccionesinst = "-1";
if (isset($_GET['ed'])) {
  $colname_vis_unidaddireccionesinst = $_GET['ed'];
}
mysql_select_db($database_conn, $conn);
$query_vis_unidaddireccionesinst = sprintf("SELECT * FROM direcciones_instituciones WHERE codigo_direcciones = %s", GetSQLValueString($colname_vis_unidaddireccionesinst, "int"));
$vis_unidaddireccionesinst = mysql_query($query_vis_unidaddireccionesinst, $conn) or die(mysql_error());
$row_vis_unidaddireccionesinst = mysql_fetch_assoc($vis_unidaddireccionesinst);
$totalRows_vis_unidaddireccionesinst = mysql_num_rows($vis_unidaddireccionesinst);


/* responsables de las instituciones */
$colname_vis_responsablesinstitucion = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_responsablesinstitucion = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_responsablesinstitucion = sprintf("SELECT * FROM responsable_institucion WHERE codigo_institucion = %s ORDER BY apellido ASC", GetSQLValueString($colname_vis_responsablesinstitucion, "text"));
$vis_responsablesinstitucion = mysql_query($query_vis_responsablesinstitucion, $conn) or die(mysql_error());
$row_vis_responsablesinstitucion = mysql_fetch_assoc($vis_responsablesinstitucion);
$totalRows_vis_responsablesinstitucion = mysql_num_rows($vis_responsablesinstitucion);


/*responsable update de instituciones */
$colname_vis_unorespinstitucion = "-1";
if (isset($_GET['ri'])) {
  $colname_vis_unorespinstitucion = $_GET['ri'];
}
mysql_select_db($database_conn, $conn);
$query_vis_unorespinstitucion = sprintf("SELECT * FROM responsable_institucion WHERE codigo_responsable = %s", GetSQLValueString($colname_vis_unorespinstitucion, "int"));
$vis_unorespinstitucion = mysql_query($query_vis_unorespinstitucion, $conn) or die(mysql_error());
$row_vis_unorespinstitucion = mysql_fetch_assoc($vis_unorespinstitucion);
$totalRows_vis_unorespinstitucion = mysql_num_rows($vis_unorespinstitucion);

/* responsable de archivo */
$colname_vis_responsablesarchivo = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_responsablesarchivo = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_responsablesarchivo = sprintf("SELECT * FROM responsable_archivo WHERE codigo_institucion = %s ORDER BY apellido ASC", GetSQLValueString($colname_vis_responsablesarchivo, "text"));
$vis_responsablesarchivo = mysql_query($query_vis_responsablesarchivo, $conn) or die(mysql_error());
$row_vis_responsablesarchivo = mysql_fetch_assoc($vis_responsablesarchivo);
$totalRows_vis_responsablesarchivo = mysql_num_rows($vis_responsablesarchivo);

/* responsable de archivo en unidad */
$colname_vis_unoresponsablesarchivo = "-1";
if (isset($_GET['ra'])) {
  $colname_vis_unoresponsablesarchivo = $_GET['ra'];
}
mysql_select_db($database_conn, $conn);
$query_vis_unoresponsablesarchivo = sprintf("SELECT * FROM responsable_archivo WHERE codigo_responsable = %s", GetSQLValueString($colname_vis_unoresponsablesarchivo, "text"));
$vis_unoresponsablesarchivo = mysql_query($query_vis_unoresponsablesarchivo, $conn) or die(mysql_error());
$row_vis_unoresponsablesarchivo = mysql_fetch_assoc($vis_unoresponsablesarchivo);
$totalRows_vis_unoresponsablesarchivo = mysql_num_rows($vis_unoresponsablesarchivo);

/* llenamos el combo tipo de aaceso */
mysql_select_db($database_conn, $conn);
$query_vis_tipoAcceso = "SELECT * FROM tipos_accesos ORDER BY tipo_acceso ASC";
$vis_tipoAcceso = mysql_query($query_vis_tipoAcceso, $conn) or die(mysql_error());
$row_vis_tipoAcceso = mysql_fetch_assoc($vis_tipoAcceso);
$totalRows_vis_tipoAcceso = mysql_num_rows($vis_tipoAcceso);

/* llenamos combo requisitos para el acceso */
/*mysql_select_db($database_conn, $conn);
$query_vis_requisitosparaacceso = "SELECT * FROM requisitos_accesos ORDER BY requisito_acceso ASC";
$vis_requisitosparaacceso = mysql_query($query_vis_requisitosparaacceso, $conn) or die(mysql_error());
$row_vis_requisitosparaacceso = mysql_fetch_assoc($vis_requisitosparaacceso);
$totalRows_vis_requisitosparaacceso = mysql_num_rows($vis_requisitosparaacceso);*/

/* llenamos combo acceso a la documentacion */
/*mysql_select_db($database_conn, $conn);
$query_vis_accesodocumentacion = "SELECT * FROM acceso_documentacion ORDER BY acceso_documentacion ASC";
$vis_accesodocumentacion = mysql_query($query_vis_accesodocumentacion, $conn) or die(mysql_error());
$row_vis_accesodocumentacion = mysql_fetch_assoc($vis_accesodocumentacion);
$totalRows_vis_accesodocumentacion = mysql_num_rows($vis_accesodocumentacion);*/

/* llenamos combo servicio de reproduccion */
mysql_select_db($database_conn, $conn);
$query_vis_serviciodereproduccion = "SELECT * FROM servicios_reproduccion ORDER BY servicio_reproduccion ASC";
$vis_serviciodereproduccion = mysql_query($query_vis_serviciodereproduccion, $conn) or die(mysql_error());
$row_vis_serviciodereproduccion = mysql_fetch_assoc($vis_serviciodereproduccion);
$totalRows_vis_serviciodereproduccion = mysql_num_rows($vis_serviciodereproduccion);

/* areas de notas */
$colname_vis_areasnotas = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_areasnotas = $_GET['cod'];
}

mysql_select_db($database_conn, $conn);
$query_vis_areasnotas = sprintf("SELECT * FROM area_de_notas_instituciones WHERE codigo_institucion = %s ORDER BY codigo_notas DESC", GetSQLValueString($colname_vis_areasnotas, "text"));
$vis_areasnotas = mysql_query($query_vis_areasnotas, $conn) or die(mysql_error());
$row_vis_areasnotas = mysql_fetch_assoc($vis_areasnotas);
$totalRows_vis_areasnotas = mysql_num_rows($vis_areasnotas);


/* buscamos los nombres autorizados*/
$colname_vis_formaautorizadadelnombre = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_formaautorizadadelnombre = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_formaautorizadadelnombre = sprintf("SELECT * FROM formas_autorizadas_nombre WHERE codigo_identificacion = %s ORDER BY forma_autorizada_nombre ASC", GetSQLValueString($colname_vis_formaautorizadadelnombre, "text"));
$vis_formaautorizadadelnombre = mysql_query($query_vis_formaautorizadadelnombre, $conn) or die(mysql_error());
$row_vis_formaautorizadadelnombre = mysql_fetch_assoc($vis_formaautorizadadelnombre);
$totalRows_vis_formaautorizadadelnombre = mysql_num_rows($vis_formaautorizadadelnombre);

$colname_vis_editfanombre = "-1";
if (isset($_GET['fa'])) {
  $colname_vis_editfanombre = $_GET['fa'];
}
mysql_select_db($database_conn, $conn);
$query_vis_editfanombre = sprintf("SELECT * FROM formas_autorizadas_nombre WHERE codigo_formas_autorizadas_nombre = %s", GetSQLValueString($colname_vis_editfanombre, "int"));
$vis_editfanombre = mysql_query($query_vis_editfanombre, $conn) or die(mysql_error());
$row_vis_editfanombre = mysql_fetch_assoc($vis_editfanombre);
$totalRows_vis_editfanombre = mysql_num_rows($vis_editfanombre);

$colname_rel_instituciones_servrep = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_instituciones_servrep = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_instituciones_servrep = sprintf("SELECT * FROM rel_instituciones_serviciosdereproduccion WHERE codigo_institucion = %s ORDER BY servicio_reproduccion ASC", GetSQLValueString($colname_rel_instituciones_servrep, "text"));
$rel_instituciones_servrep = mysql_query($query_rel_instituciones_servrep, $conn) or die(mysql_error());
$row_rel_instituciones_servrep = mysql_fetch_assoc($rel_instituciones_servrep);
$totalRows_rel_instituciones_servrep = mysql_num_rows($rel_instituciones_servrep);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language=JavaScript src='Scripts/innovaeditor.js'></script>
</head>
<script language="javascript">

//--direcciones-------------------------------------------------------------------
	function adddirecciones() {
		document.getElementById('fmrDirecciones').style.display = "block";
		document.getElementById('divadddirecciones').style.display = "none";
		document.getElementById('codigo_direcciones').value = "";
		document.getElementById('calle').value = "";
		document.getElementById('numero').value = "";
		document.getElementById('codigo_postal').value = "";
		document.getElementById('casilla_correo').value = "";
		document.getElementById('ciudad').value = "";
		document.getElementById('provincia').value = "";
		document.getElementById('pais').value = "";
		document.getElementById('telefonos').value = "";
		document.getElementById('fax').value = "";
		document.getElementById('tipo_direccion').value = "";
	}
	
	function esconderdirecciones() {
		document.getElementById('fmrDirecciones').style.display = "none";
		document.getElementById('divadddirecciones').style.display = "block";
	}
	
	function direcciones_ver(i) {
		document.getElementById('detalle'+i).style.display = "block";
		document.getElementById('abr_det'+i).style.display = "none";
		document.getElementById('cer_det'+i).style.display = "block";
	}
	
	function direcciones_cerrar(l) {
		document.getElementById('detalle'+l).style.display = "none";
		document.getElementById('abr_det'+l).style.display = "block";
		document.getElementById('cer_det'+l).style.display = "none";
	}
	
	function direcciones_editar(j) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&ed="; ?>'+j;
	}
	
	function direcciones_eliminar(k) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&el="; ?>'+k;
	}

//--responsable institucion-------------------------------------------------------

function addrespinst() {
		document.getElementById('fmrResponsableInstituciones').style.display = "block";
		document.getElementById('divaddrespinst').style.display = "none";
		document.getElementById('codigo_responsable').value = "";
		document.getElementById('apellidori').value = "";
		document.getElementById('nombreri').value = "";
		document.getElementById('emailri').value = "";
		document.getElementById('telefonori').value = "";
		document.getElementById('movilri').value = "";
		document.getElementById('area_responsabilidadri').value = "";
	}
	
	function esconderrespinst() {
		document.getElementById('fmrResponsableInstituciones').style.display = "none";
		document.getElementById('divaddrespinst').style.display = "block";
	}
	
	function respinst_ver(i) {
		document.getElementById('detalleri'+i).style.display = "block";
		document.getElementById('abr_ri'+i).style.display = "none";
		document.getElementById('cer_ri'+i).style.display = "block";
	}
	
	function respinst_cerrar(l) {
		document.getElementById('detalleri'+l).style.display = "none";
		document.getElementById('abr_ri'+l).style.display = "block";
		document.getElementById('cer_ri'+l).style.display = "none";
	}
	
	function respinst_editar(j) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&ri="; ?>'+j;
	}
	
	function respinst_eliminar(k) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&elri="; ?>'+k;
	}
	
//--responsable archivo-------------------------------------------------------

function addresparch() {
		document.getElementById('fmrResponsableArchivo').style.display = "block";
		document.getElementById('divaddresparch').style.display = "none";
		document.getElementById('codigo_responsablera').value = "";
		document.getElementById('apellidora').value = "";
		document.getElementById('nombrera').value = "";
		document.getElementById('emailra').value = "";
		document.getElementById('telefonora').value = "";
		document.getElementById('movilra').value = "";
		document.getElementById('area_responsabilidadra').value = "";
	}
	
	function esconderresparch() {
		document.getElementById('fmrResponsableArchivo').style.display = "none";
		document.getElementById('divaddresparch').style.display = "block";
	}
	
	function ra_ver(i) {
		document.getElementById('detallera'+i).style.display = "block";
		document.getElementById('abr_ra'+i).style.display = "none";
		document.getElementById('cer_ra'+i).style.display = "block";
	}
	
	function ra_cerrar(l) {
		document.getElementById('detallera'+l).style.display = "none";
		document.getElementById('abr_ra'+l).style.display = "block";
		document.getElementById('cer_ra'+l).style.display = "none";
	}
	
	function ra_editar(j) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&ra="; ?>'+j;
	}
	
	function ra_eliminar(k) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&elra="; ?>'+k;
	}
	
//--formas autorizadas de nombres-------------------------------------------------------

function addfa() {
		document.getElementById('fmrfa').style.display = "block";
		document.getElementById('divaddfa').style.display = "none";
		document.getElementById('forma_autorizada_nombre').value = "";
		document.getElementById('codigo_formas_autorizadas_nombre').value = "";
	}
	
	function esconderfa() {
		document.getElementById('fmrfa').style.display = "none";
		document.getElementById('divaddfa').style.display = "block";
	}
	
	function fa_ver(i) {
		document.getElementById('detallefa'+i).style.display = "block";
		document.getElementById('abr_fa'+i).style.display = "none";
		document.getElementById('cer_fa'+i).style.display = "block";
	}
	
	function fa_cerrar(l) {
		document.getElementById('detallefa'+l).style.display = "none";
		document.getElementById('abr_fa'+l).style.display = "block";
		document.getElementById('cer_fa'+l).style.display = "none";
	}
	
	function fa_editar(j) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&fa="; ?>'+j;
	}
	
	function fa_eliminar(k) {
		document.location= '<?php echo $pageFormAction."?cod=".$_GET['cod']."&elfa="; ?>'+k;
	}
	
//-- expansion--------------------------------------------------------------------------------------------------

	function abrirformularios(j) {
		cerrartodo();
		document.getElementById('f'+j).style.display = "block";
		document.getElementById('f'+j+'c').style.display = "none";
	}
	
	function cerrarformularios(l) {
		document.getElementById('f'+l+'c').style.display = "block";
		document.getElementById('f'+l).style.display = "none";
	}


	function cerrartodo() {
		for(i=1;i<=<?php if($_SESSION['estado'] >= 5) { echo "5"; } else { echo $_SESSION	['estado']; }  ; ?>;i++) {
		document.getElementById('f'+i).style.display = "none";
		document.getElementById('f'+i+'c').style.display = "block";
		//document.getElementById('f'+i+'d').style.display = "none";
		}
	}

</script>
<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?php if($_SESSION['estado'] < 1) { ?>
    <div id="f1d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">&Aacute;REA DE IDENTIFICACI&Oacute;N</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php  } else { ?>
    <div id="f1c" style="display:<?php if($_SESSION['estado'] != 1) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">&Aacute;REA DE IDENTIFICACI&Oacute;N</td>
              <td align="right"><a onClick="abrirformularios(1);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f1" style="display:<?php if($_SESSION['estado'] == 1) { echo "block"; } else { echo "none"; } ?>">
    	<form  id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    	  <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE IDENTIFICACI&Oacute;N</td>
    	          <td align="right"><a onClick="cerrarformularios(1)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">C&oacute;digo de Identificaci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_instituciones['codigo_identificacion']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <!-- <tr>
    	          <td class="tituloscampos ins_celdacolor">C&oacute;digo Interno:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php //echo $row_vis_instituciones['codigo_interno']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr> 
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr> -->
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Forma/s autorizada/s de nombre:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                  	<?php if($totalRows_vis_formaautorizadadelnombre >= 1) { ?>
					<?php do{ ?>
					<?php 
					$colname_vis_unoformaautorizada = $row_vis_formaautorizadadelnombre['codigo_formas_autorizadas_nombre'];

					mysql_select_db($database_conn, $conn);
					$query_vis_unoformaautorizada = sprintf("SELECT * FROM formas_autorizadas_nombre WHERE codigo_formas_autorizadas_nombre = %s", GetSQLValueString($colname_vis_unoformaautorizada, "int"));
					$vis_unoformaautorizada = mysql_query($query_vis_unoformaautorizada, $conn) or die(mysql_error());
					$row_vis_unoformaautorizada = mysql_fetch_assoc($vis_unoformaautorizada);
					$totalRows_vis_unoformaautorizada = mysql_num_rows($vis_unoformaautorizada);
					?>
					
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                    <tr>
                    	<td width="588" class="ins_celdtadirecciones"><?php echo $row_vis_formaautorizadadelnombre['forma_autorizada_nombre']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones "><div id="abr_fa<?php echo $row_vis_formaautorizadadelnombre['codigo_formas_autorizadas_nombre']; ?>"><a onClick="fa_ver(<?php echo $row_vis_formaautorizadadelnombre['codigo_formas_autorizadas_nombre']; ?>);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></div><div id="cer_fa<?php echo $row_vis_formaautorizadadelnombre['codigo_formas_autorizadas_nombre']; ?>" style="display:none;"><a onClick="fa_cerrar(<?php echo $row_vis_formaautorizadadelnombre['codigo_formas_autorizadas_nombre']; ?>);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></div></td>
                    </tr>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"><div id="detallefa<?php echo $row_vis_unoformaautorizada['codigo_formas_autorizadas_nombre']; ?>" style="display:none;"><table width="610" border="2" cellspacing="0" cellpadding="0" class="ins_detalledirecciones">
                    	    <tr>
                    	      <td class="ins_detalledirecciones"><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td width="100" class="tituloscampos">Nombre:</td>
                    	          <td width="490" class="ins_detalledirecciones"><?php echo $row_vis_unoformaautorizada['forma_autorizada_nombre']; ?></td>
                  	          </tr>
                    	        
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                  	          </table></td>
                  	      </tr>
                  	    </table>
                    	</div></td>
  	              </tr>
                  <?php } while ($row_vis_formaautorizadadelnombre = mysql_fetch_assoc($vis_formaautorizadadelnombre)); ?>
                  <?php } else {  ?>
                  <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                   <tr>
    	              <td width="543" height="2" class="ins_celdtadirecciones " ></td>
                  </tr>
                  <?php } ?>
                  </table></td>
                   </tr>
                   <tr>
    	          <td><div id="divaddfa">
    	          </div></td>
  	          </tr>
  	          <tr>
              <td><div id="fmrfa" style="display:<?php if(isset($_GET['fa'])) { echo "blck"; } else { echo "none";} ?>;">
    	            <table width="630" border="0" cellspacing="0" cellpadding="0">
    	              <tr>
    	                <td>&nbsp;</td>
  	                </tr>
    	              <tr>
    	                <td><table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
    	                  <tr>
    	                    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Nombre:</td>
    	                        <td width="484" class="ins_tituloscampos"><input name="forma_autorizada_nombre" type="text" class="camposmedios" id="forma_autorizada_nombre" value="<?php echo $row_vis_editfanombre['forma_autorizada_nombre']; ?>">
    	                          <input name="codigo_formas_autorizadas_nombre" type="hidden" id="codigo_formas_autorizadas_nombre" value="<?php echo $row_vis_editfanombre['codigo_formas_autorizadas_nombre']; ?>"></td>
  	                        </tr>
    	                      
    	                      
    	                     
    	                     
    	                      
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
  	                        </table></td>
  	                    </tr>
  	                  </table></td>
  	                </tr>
  	              </table>
                  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="esconderfa();"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
    	          </div></td>
              </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor">Forma conocida de nombre:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_instituciones['formas_conocidas_nombre']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Tipo de Instituci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_instituciones['tipo_institucion']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	       <!-- <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Tipo de entidad:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><select name="tipo_entidad" class="camposanchos" id="tipo_entidad">
    	            <?php
//do {  
 ?>
    	            <option value="<?php /* echo $row_vis_tipo_entidad['tipo_entidad']?>"<?php if (!(strcmp($row_vis_tipo_entidad['tipo_entidad'], $row_vis_instituciones['tipo_entidad']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vis_tipo_entidad['tipo_entidad']?></option>
    	            <?php
} while ($row_vis_tipo_entidad = mysql_fetch_assoc($vis_tipo_entidad));
  $rows = mysql_num_rows($vis_tipo_entidad);
  if($rows > 0) {
      mysql_data_seek($vis_tipo_entidad, 0);
	  $row_vis_tipo_entidad = mysql_fetch_assoc($vis_tipo_entidad);
  } */
 ?>
  	            </select></td>
  	          </tr> -->
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Direcciones:</td>
  	          </tr>
              <tr>
    	          <td  class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php if($totalRows_vis_direccionesinstituciones >= 1) { ?>
					<?php do { ?>
                    <?php
					/* detalle de cada una de las direcciones */
					mysql_select_db($database_conn, $conn);
					$query_vis_detalledirecciones = sprintf("SELECT * FROM direcciones_instituciones WHERE codigo_direcciones = %s", GetSQLValueString($row_vis_direccionesinstituciones['codigo_direcciones'], "int"));
					$vis_detalledirecciones = mysql_query($query_vis_detalledirecciones, $conn) or die(mysql_error());
					$row_vis_detalledirecciones = mysql_fetch_assoc($vis_detalledirecciones);
					$totalRows_vis_detalledirecciones = mysql_num_rows($vis_detalledirecciones);
					?>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                    <tr>
    	              <td width="588" class="ins_celdtadirecciones " ><?php echo $row_vis_direccionesinstituciones['calle']." ".$row_vis_direccionesinstituciones['numero']." - CP:".$row_vis_direccionesinstituciones['codigo_postal']." - ".$row_vis_direccionesinstituciones['ciudad'].", ".$row_vis_direccionesinstituciones['provincia'].", ".$row_vis_direccionesinstituciones['pais']; ?></td>
    	              <td width="22" class="ins_celdtadirecciones " ><div id="abr_det<?php echo $row_vis_direccionesinstituciones['codigo_direcciones']; ?>"><a onClick="direcciones_ver(<?php echo $row_vis_direccionesinstituciones['codigo_direcciones']; ?>);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></div><div id="cer_det<?php echo $row_vis_direccionesinstituciones['codigo_direcciones']; ?>" style="display:none;"><a onClick="direcciones_cerrar(<?php echo $row_vis_direccionesinstituciones['codigo_direcciones']; ?>);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></div></td>
  	                </tr>
                    <tr>
                    	<td colspan="4"><div id="detalle<?php echo $row_vis_direccionesinstituciones['codigo_direcciones']; ?>" style="display:none;"><table width="610" border="2" cellspacing="0" cellpadding="0" class="ins_detalledirecciones">
                    	    <tr>
                    	      <td class="ins_detalledirecciones"><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td width="100" class="tituloscampos">Calle:</td>
                    	          <td width="490" class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['calle']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">N&uacute;mero:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['numero']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">C.P.:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['codigo_postal']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">C.C.:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['casilla_correo']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Ciudad:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['ciudad']; ?></td>
                  	          </tr>
                    	       <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Provincia:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['provincia']; ?></td>
                  	          </tr>
                    	       <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Pa&iacute;s:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['pais']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tel&eacute;fono:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['telefonos']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Fax:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['fax']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tipo Direccion:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalledirecciones['tipo_direccion']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                  	          </table></td>
                  	      </tr>
                  	    </table>
                    	</div></td>
                    </tr>
                  <?php } while ($row_vis_direccionesinstituciones = mysql_fetch_assoc($vis_direccionesinstituciones)); ?>
  	           <?php } else { ?>
               	<tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                   <tr>
    	              <td width="543" height="2" class="ins_celdtadirecciones " ></td></tr>
               <?php } ?>
               </table></td>
  	          </tr>
              <tr>
    	          <td><div id="divadddirecciones"></div></td>
  	          </tr>
    	        <tr>
    	          <td><div id="fmrDirecciones" style="display:<?php if(isset($_GET['ed'])) { echo "blck"; } else { echo "none";} ?>;">
    	            <table width="630" border="0" cellspacing="0" cellpadding="0">
    	              <tr>
    	                <td>&nbsp;</td>
  	                </tr>
    	              <tr>
    	                <td><table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
    	                  <tr>
    	                    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Calle:</td>
    	                        <td width="484" class="ins_tituloscampos"><input name="calle" type="text" class="camposmedios" id="calle" value="<?php echo $row_vis_unidaddireccionesinst['calle']; ?>">
    	                          <input name="codigo_direcciones" type="hidden" id="codigo_direcciones" value="<?php echo $row_vis_unidaddireccionesinst['codigo_direcciones']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
    	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;N&uacute;mero:</td>
    	                        <td class="ins_tituloscampos"><input name="numero" type="text" class="camposmedios" id="numero" value="<?php echo $row_vis_unidaddireccionesinst['numero']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;C&oacute;digo Postal:</td>
    	                        <td class="ins_tituloscampos"><input name="codigo_postal" type="text" class="camposmedios" id="codigo_postal" value="<?php echo $row_vis_unidaddireccionesinst['codigo_postal']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                       <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Casilla Correo:</td>
    	                        <td class="ins_tituloscampos"><input name="casilla_correo" type="text" class="camposmedios" id="casilla_correo" value="<?php echo $row_vis_unidaddireccionesinst['casilla_correo']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Ciudad:</td>
    	                        <td class="ins_tituloscampos"><input name="ciudad" type="text" class="camposmedios" id="ciudad" value="<?php echo $row_vis_unidaddireccionesinst['ciudad']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Provincia:</td>
    	                        <td class="ins_tituloscampos"><input name="provincia" type="text" class="camposmedios" id="provincia" value="<?php echo $row_vis_unidaddireccionesinst['provincia']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Pa&iacute;s:</td>
    	                        <td class="ins_tituloscampos"><input name="pais" type="text" class="camposmedios" id="pais" value="<?php echo $row_vis_unidaddireccionesinst['pais']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Tel&eacute;fonos:</td>
    	                        <td class="ins_tituloscampos"><input name="telefonos" type="text" class="camposmedios" id="telefonos" value="<?php echo $row_vis_unidaddireccionesinst['telefonos']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Fax:</td>
    	                        <td class="ins_tituloscampos"><input name="fax" type="text" class="camposmedios" id="fax" value="<?php echo $row_vis_unidaddireccionesinst['fax']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Tipo Direcci&oacute;n:</td>
    	                        <td class="ins_tituloscampos"><input name="tipo_direccion" type="text" class="camposmedios" id="tipo_direccion" value="<?php echo $row_vis_unidaddireccionesinst['tipo_direccion']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
  	                        </table></td>
  	                    </tr>
  	                  </table></td>
  	                </tr>
  	              </table>
                  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="esconderdirecciones();"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
    	          </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	           <td class="tituloscampos ins_celdacolor">E-mail:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_instituciones['email']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">P&aacute;gina Web</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_instituciones['www']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Persona responsable de la Instituci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php if($totalRows_vis_responsablesinstitucion >= 1) { ?>
					<?php do { ?>
                    <?php
					/* detalle de cada una de los responsables de las instituciones */
					mysql_select_db($database_conn, $conn);
					$query_vis_detalleri = sprintf("SELECT * FROM responsable_institucion WHERE codigo_responsable = %s", GetSQLValueString($row_vis_responsablesinstitucion['codigo_responsable'], "int"));
					$vis_detalleri = mysql_query($query_vis_detalleri, $conn) or die(mysql_error());
					$row_vis_detalleri = mysql_fetch_assoc($vis_detalleri);
					$totalRows_vis_detalleri = mysql_num_rows($vis_detalleri);


					?>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                    <tr>
    	              <td  width="588" class="ins_celdtadirecciones "><?php echo $row_vis_responsablesinstitucion['apellido']; ?>, <?php echo $row_vis_responsablesinstitucion['nombre']; ?></td>
                      <td width="22" class="ins_celdtadirecciones " ><div id="abr_ri<?php echo $row_vis_responsablesinstitucion['codigo_responsable']; ?>"><a onClick="respinst_ver(<?php echo $row_vis_responsablesinstitucion['codigo_responsable']; ?>);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></div><div id="cer_ri<?php echo $row_vis_responsablesinstitucion['codigo_responsable']; ?>" style="display:none;"><a onClick="respinst_cerrar(<?php echo $row_vis_responsablesinstitucion['codigo_responsable']; ?>);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></div></td>
  	              </tr>
                  <tr>
                  	<td colspan="4"><div id="detalleri<?php echo $row_vis_responsablesinstitucion['codigo_responsable']; ?>" style="display:none;"><table width="610" border="2" cellspacing="0" cellpadding="0" class="ins_detalledirecciones">
                    	    <tr>
                    	      <td class="ins_detalledirecciones"><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td width="100" class="tituloscampos">Apellido:</td>
                    	          <td width="490" class="ins_detalledirecciones"><?php echo $row_vis_detalleri['apellido']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Nombre:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_responsablesinstitucion['nombre']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">E-mail:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalleri['email']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tel&eacute;fono:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalleri['telefono']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tel&eacute;fono M&oacute;vil:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalleri['movil']; ?></td>
                  	          </tr>
                    	       <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">&Aacute;rea de 
  responsabilidad:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detalleri['area_responsabilidad']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                  	          </table></td>
                  	      </tr>
                  	    </table>
                    	</div></td>
                  </tr>
                  <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                  <?php } while ($row_vis_responsablesinstitucion = mysql_fetch_assoc($vis_responsablesinstitucion)); ?>
  	           <?php } else { ?>
               	<tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                   <tr>
    	              <td width="543" height="2" class="ins_celdtadirecciones " ></td></tr>
               <?php } ?>
  	            </table></td>
  	          </tr>
              <tr>
              	<td><div id="divaddrespinst">
                <?php if($totalRows_vis_responsablesinstitucion <= 0) {  ?> 
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><a onClick="addrespinst();"><img src="images/bt_003.jpg" width="161" height="20" border="0"></a></td>
  	                </tr>
  	              </table>
                  <?php } ?>
    	          </div></td>
              </tr>
              <tr>
    	          <td><div id="fmrResponsableInstituciones" style="display:<?php if(isset($_GET['ri'])) { echo "blck"; } else { echo "none";} ?>;">
    	            <table width="630" border="0" cellspacing="0" cellpadding="0">
    	              <tr>
    	                <td>&nbsp;</td>
  	                </tr>
    	              <tr>
    	                <td><table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
    	                  <tr>
    	                    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Apellido:</td>
    	                        <td width="484" class="ins_tituloscampos"><input name="apellidori" type="text" class="camposmedios" id="apellidori" value="<?php echo $row_vis_unorespinstitucion['apellido']; ?>">
    	                          <input name="codigo_responsable" type="hidden" id="codigo_responsable" value="<?php echo $row_vis_unorespinstitucion['codigo_responsable']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
    	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Nombre:</td>
    	                        <td class="ins_tituloscampos"><input name="nombreri" type="text" class="camposmedios" id="nombreri" value="<?php echo $row_vis_unorespinstitucion['nombre']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;E-mail:</td>
    	                        <td class="ins_tituloscampos"><input name="emailri" type="text" class="camposmedios" id="emailri" value="<?php echo $row_vis_unorespinstitucion['email']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                       <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Tel&eacute;fono:</td>
    	                        <td class="ins_tituloscampos"><input name="telefonori" type="text" class="camposmedios" id="telefonori" value="<?php echo $row_vis_unorespinstitucion['telefono']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Otro Tel&eacute;fono:</td>
    	                        <td class="ins_tituloscampos"><input name="movilri" type="text" class="camposmedios" id="movilri" value="<?php echo $row_vis_unorespinstitucion['movil']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Cargo:</td>
    	                        <td class="ins_tituloscampos"><input name="area_responsabilidadri" type="text" class="camposmedios" id="area_responsabilidadri" value="<?php echo $row_vis_unorespinstitucion['area_responsabilidad']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
  	                        </table></td>
  	                    </tr>
  	                  </table></td>
  	                </tr>
  	              </table>
                  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="esconderrespinst();"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
    	          </div></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Responsable del Archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="ins_celdacolor">
                  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                  <?php if($totalRows_vis_responsablesarchivo >= 1) { ?>
                  	<?php do { ?>
                    <?php
					/* detalle de cada una de los responsables de las instituciones */
					mysql_select_db($database_conn, $conn);
					$query_vis_detallera = sprintf("SELECT * FROM responsable_archivo WHERE codigo_responsable = %s", GetSQLValueString($row_vis_responsablesarchivo['codigo_responsable'], "int"));
					$vis_detallera = mysql_query($query_vis_detallera, $conn) or die(mysql_error());
					$row_vis_detallera = mysql_fetch_assoc($vis_detallera);
					$totalRows_vis_detallera = mysql_num_rows($vis_detallera);
					?>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                    <tr>
                    	<td  width="588" class="ins_celdtadirecciones"><?php echo $row_vis_responsablesarchivo['apellido']; ?>, <?php echo $row_vis_responsablesarchivo['nombre']; ?></td>
                        <td width="22" class="ins_celdtadirecciones " ><div id="abr_ra<?php echo $row_vis_responsablesarchivo['codigo_responsable']; ?>"><a onClick="ra_ver(<?php echo $row_vis_responsablesarchivo['codigo_responsable']; ?>);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></div><div id="cer_ra<?php echo $row_vis_responsablesarchivo['codigo_responsable']; ?>" style="display:none;"><a onClick="ra_cerrar(<?php echo $row_vis_responsablesarchivo['codigo_responsable']; ?>);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></div></td>
                    </tr>
                    <tr>
    	              <td colspan="4"><div id="detallera<?php echo $row_vis_responsablesarchivo['codigo_responsable']; ?>" style="display:none;"><table width="610" border="2" cellspacing="0" cellpadding="0" class="ins_detalledirecciones">
                    	    <tr>
                    	      <td class="ins_detalledirecciones"><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td width="100" class="tituloscampos">Apellido:</td>
                    	          <td width="490" class="ins_detalledirecciones"><?php echo $row_vis_detallera['apellido']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Nombre:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detallera['nombre']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">E-mail:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detallera['email']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tel&eacute;fono:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detallera['telefono']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">Tel&eacute;fono M&oacute;vil:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detallera['movil']; ?></td>
                  	          </tr>
                    	       <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="1" colspan="2" bgcolor="#000000"></td>
                    	        </tr>
                                 <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td class="tituloscampos">&Aacute;rea de 
  responsabilidad:</td>
                    	          <td class="ins_detalledirecciones"><?php echo $row_vis_detallera['area_responsabilidad']; ?></td>
                  	          </tr>
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                  	          </table></td>
                  	      </tr>
                  	    </table>
                    	</div></td>
  	              </tr>
                  <?php } while ($row_vis_responsablesarchivo = mysql_fetch_assoc($vis_responsablesarchivo)); ?>
                  <?php } else { ?>
               	<tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                   <tr>
    	              <td width="543" height="2" class="ins_celdtadirecciones " ></td></tr>
               <?php } ?>
                  </table></td>
  	          </tr>
              <tr>
              	<td><div id="divaddresparch">
    	          <?php if($totalRows_vis_responsablesarchivo <= 0) {  ?>  
                    <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><a onClick="addresparch();"><img src="images/bt_003.jpg" width="161" height="20" border="0"></a></td>
  	                </tr>
  	              </table>
                  <?php } ?>
    	          </div></td>
              </tr>
              <tr>
              	<td><div id="fmrResponsableArchivo" style="display:<?php if(isset($_GET['ra'])) { echo "blck"; } else { echo "none";} ?>;">
    	            <table width="630" border="0" cellspacing="0" cellpadding="0">
    	              <tr>
    	                <td>&nbsp;</td>
  	                </tr>
    	              <tr>
    	                <td><table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
    	                  <tr>
    	                    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Apellido:</td>
    	                        <td width="484" class="ins_tituloscampos"><input name="apellidora" type="text" class="camposmedios" id="apellidora" value="<?php echo $row_vis_unoresponsablesarchivo['apellido']; ?>">
    	                          <input name="codigo_responsablera" type="hidden" id="codigo_responsablera" value="<?php echo $row_vis_unoresponsablesarchivo['codigo_responsable']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
    	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Nombre:</td>
    	                        <td class="ins_tituloscampos"><input name="nombrera" type="text" class="camposmedios" id="nombrera" value="<?php echo $row_vis_unoresponsablesarchivo['nombre']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;E-mail:</td>
    	                        <td class="ins_tituloscampos"><input name="emailra" type="text" class="camposmedios" id="emailra" value="<?php echo $row_vis_unoresponsablesarchivo['email']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                       <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Tel&eacute;fono:</td>
    	                        <td class="ins_tituloscampos"><input name="telefonora" type="text" class="camposmedios" id="telefonora" value="<?php echo $row_vis_unoresponsablesarchivo['telefono']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Otro Tel&eacute;fono:</td>
    	                        <td class="ins_tituloscampos"><input name="movilra" type="text" class="camposmedios" id="movilra" value="<?php echo $row_vis_unoresponsablesarchivo['movil']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;&Aacute;rea de <br>&nbsp;&nbsp;responsabilidad:</td>
    	                        <td class="ins_tituloscampos"><input name="area_responsabilidadra" type="text" class="camposmedios" id="area_responsabilidadra" value="<?php echo $row_vis_unoresponsablesarchivo['area_responsabilidad']; ?>"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
  	                        </table></td>
  	                    </tr>
  	                  </table></td>
  	                </tr>
  	              </table>
                  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="esconderresparch();"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
    	          </div></td>
              </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
  	          </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
  	    </table>
    	  <input type="hidden" name="MM_update" value="form1">
    	  <input name="cant_fa" type="hidden" id="cant_fa" value="<?php echo $totalRows_vis_formaautorizadadelnombre; ?>">
    	  <input name="cant_dir" type="hidden" id="cant_dir" value="<?php echo $totalRows_vis_direccionesinstituciones; ?>">
    	  <input name="cant_pri" type="hidden" id="cant_pri" value="<?php echo $totalRows_vis_responsablesinstitucion; ?>">
    	  <input name="cant_pra" type="hidden" id="cant_pra" value="<?php echo $totalRows_vis_responsablesarchivo ; ?>">
    	</form>
    </div><?php  } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?php if($_SESSION['estado'] < 2) { ?>
    <div id="f2d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">&Aacute;REA DE DESCRIPCI&Oacute;N</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php  } else { ?>
    <div id="f2c" style="display:<?php if($_SESSION['estado'] != 2) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">&Aacute;REA DE DESCRIPCI&Oacute;N</td>
              <td align="right"><a onClick="abrirformularios(2);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f2" style="display:<?php if($_SESSION['estado'] == 2) { echo "block"; } else { echo "none"; } ?>">
    <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
    	          <tr>
    	            <td class="ins_titulomayor">&Aacute;REA DE DESCRIPCI&Oacute;N</td>
    	            <td align="right"><a onClick="cerrarformularios(2);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	            </tr>
  	          </table></td>
  	      </tr>
          <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Historia:    	            <span class="ins_celdacolor">
    	            <input name="codigo_identificacion2" type="hidden"  id="codigo_identificacion2" readonly value="<?php echo $row_vis_instituciones['codigo_identificacion']; ?>" />
    	          </span></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['historia']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Estructura Org&aacute;nica funcional de la Instituci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['estructura_organica']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Historia de la formaci&oacute;n del Archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['politicas_ingresos']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Proyectos para nuevos Ingrsos:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['politicas_ingresos']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Instalaciones del Archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['instalaciones_archivo']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Fondos y agrupaciones documentales custodiadas:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
                <!-- SCAR ESTE CAMPO -->
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['fondos_agrupaciones']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              
              
              
              </table></td>
          
            <tr>
              <td class="celdapieazul"></td>
            </tr>
      </table>
      <input type="hidden" name="MM_update" value="form2">
    </form>
    </div><?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?php if($_SESSION['estado'] < 3) { ?>
    <div id="f3d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">AREA DE ADMINISTRACION</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php } else { ?>
    <div id="f3c" style="display:<?php if($_SESSION['estado'] != 3) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">AREA DE ADMINISTRACION</td>
              <td align="right"><a onClick="abrirformularios(3);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f3" style="display:<?php if($_SESSION['estado'] == 3) { echo "block"; } else { echo "none"; } ?>">
    <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor">
    	        <table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
    	          <tr>
    	            <td class="ins_titulomayor">AREA DE ADMINISTRACION</td>
    	            <td align="right"><a onClick="cerrarformularios(3);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	            </tr>
  	          </table></td>
  	      </tr>
          <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
              <td class="tituloscampos ins_celdacolor">Tipo de Acceso al archivo:    	            <span class="ins_celdacolor">
    	            <input name="codigo_identificacion3" type="hidden"  id="codigo_identificacion3" readonly value="<?php echo $row_vis_instituciones['codigo_identificacion']; ?>" />
    	          </span></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['tipo_acceso']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	      <!-- se va para fondos   <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
               <tr>
              <td class="tituloscampos ins_celdacolor">Requisitos para el acceso:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	       <tr>
    	          <td class="ins_celdacolor"><select name="requisito_acceso" class="camposanchos" id="requisito_acceso">
    	            <option value="" <?php //if (!(strcmp("", $row_vis_instituciones['requisito_acceso']))) {echo "selected=\"selected\"";} ?>></option>
    	            <?php
// do {  
?>
    	            <option value="<?php /* echo $row_vis_requisitosparaacceso['requisito_acceso']?>"<?php if (!(strcmp($row_vis_requisitosparaacceso['requisito_acceso'], $row_vis_instituciones['requisito_acceso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vis_requisitosparaacceso['requisito_acceso']?></option>
    	            <?php
} while ($row_vis_requisitosparaacceso = mysql_fetch_assoc($vis_requisitosparaacceso));
  $rows = mysql_num_rows($vis_requisitosparaacceso);
  if($rows > 0) {
      mysql_data_seek($vis_requisitosparaacceso, 0);
	  $row_vis_requisitosparaacceso = mysql_fetch_assoc($vis_requisitosparaacceso);
  } */
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
              <td class="tituloscampos ins_celdacolor">Acceso de la documentaci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>-->
    	       <!-- se va para fondos  <tr>
    	          <td class="ins_celdacolor"><select name="acceso_documentacion" class="camposanchos" id="acceso_documentacion">
    	            <option value="" <?php //if (!(strcmp("", $row_vis_instituciones['acceso_documentacion']))) {echo "selected=\"selected\"";} ?>></option>
    	            <?php
//do {  
?>
    	            <option value="<?php /* echo $row_vis_accesodocumentacion['acceso_documentacion']?>"<?php if (!(strcmp($row_vis_accesodocumentacion['acceso_documentacion'], $row_vis_instituciones['acceso_documentacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vis_accesodocumentacion['acceso_documentacion']?></option>
    	            <?php
} while ($row_vis_accesodocumentacion = mysql_fetch_assoc($vis_accesodocumentacion));
  $rows = mysql_num_rows($vis_accesodocumentacion);
  if($rows > 0) {
      mysql_data_seek($vis_accesodocumentacion, 0);
	  $row_vis_accesodocumentacion = mysql_fetch_assoc($vis_accesodocumentacion);
  } */
?>
                  </select></td>
  	          </tr> -->
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
              <td class="tituloscampos ins_celdacolor">D&iacute;as y horario de apertura del  archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['dias_apertura']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <!--<tr>
              <td class="tituloscampos ins_celdacolor">Horario de apertura de la instituci&oacute;n y del &aacute;rea de archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"> sacamos este campo <input name="horaios_apertura" type="text" class="camposanchos" id="horaios_apertura" value="<?php //echo $row_vis_instituciones['horaios_apertura']; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>-->
              <tr>
              <td class="tituloscampos ins_celdacolor">Fechas anuales de cierre  del archivo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo capitalizar($row_vis_instituciones['fechas_anuales_cierre']); ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
              <td class="tituloscampos ins_celdacolor">Servicios de reproducci&oacute;n</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                  	<?php if($totalRows_rel_instituciones_servrep  >= 1) { ?>
					<?php do{ ?>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                    <tr>
                    	<td height="18" colspan="2" class="ins_celdtadirecciones"><?php echo $row_rel_instituciones_servrep['servicio_reproduccion']; ?></td>
</tr>
                    <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"><div id="detallefa<?php echo $row_vis_unoformaautorizada['codigo_formas_autorizadas_nombre']; ?>" style="display:none;"><table width="610" border="2" cellspacing="0" cellpadding="0" class="ins_detalledirecciones">
                    	    <tr>
                    	      <td class="ins_detalledirecciones"><table width="590" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                    	        <tr>
                    	          <td width="100" class="tituloscampos">Nombre:</td>
                    	          <td width="490" class="ins_detalledirecciones"><?php echo $row_vis_unoformaautorizada['forma_autorizada_nombre']; ?></td>
                  	          </tr>
                    	        
                    	        <tr>
                    	          <td height="2" colspan="2"></td>
                    	        </tr>
                  	          </table></td>
                  	      </tr>
                  	    </table>
                    	</div></td>
  	              </tr>
                  <?php } while ($row_rel_instituciones_servrep = mysql_fetch_assoc($rel_instituciones_servrep)); ?>
                  <?php } else {  ?>
                  <tr>
    	              <td class="separadormenor ins_celdacolor" colspan="4"></td>
  	              </tr>
                   <tr>
    	              <td width="584" height="2" class="ins_celdtadirecciones " ></td>
                  </tr>
                  <?php } ?>
                  </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
             </table></td>
          
            <tr>
              <td class="celdapieazul"></td>
            </tr>
      </table>
      <input type="hidden" name="MM_update" value="form3">
    </form>
    </div><?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?php if($_SESSION['estado'] < 4) { ?>
    <div id="f4d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">AREA DE NOTAS</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php } else { ?>
    <div id="f4c" style="display:<?php if($_SESSION['estado'] != 4) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">AREA DE NOTAS</td>
              <td align="right"><a onClick="abrirformularios(4);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f4" style="display:<?php if($_SESSION['estado'] == 4) { echo "block"; } else { echo "none"; } ?>">
    <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor">
    	        <table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
    	          <tr>
    	            <td class="ins_titulomayor">AREA DE NOTAS</td>
    	            <td align="right"><a onClick="cerrarformularios(4);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	            </tr>
  	          </table></td>
  	      </tr>
          <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Notas de descripci&oacute;n:    	            <span class="ins_celdacolor">
    	            <input name="codigo_identificacion4" type="hidden"  id="codigo_identificacion4" readonly value="<?php echo $row_vis_instituciones['codigo_identificacion']; ?>" />
    	            <input name="codigo_notas" type="hidden" id="codigo_notas" value="<?php echo $row_vis_areasnotas['codigo_notas']; ?>">
    	          </span></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td height="18" class="ins_celdtadirecciones"><?php echo $row_vis_areasnotas['nota_descripcion']; ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr></table></td>
          
            <tr>
              <td class="celdapieazul"></td>
            </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form4">
    </form>
    </div><?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?php if($_SESSION['estado'] < 5) { ?>
    <div id="f5d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">AREA DE ESTADOS</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?PHP  } else { ?>
    <div id="f5c" style="display:<?php if($_SESSION['estado'] != 5) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">AREA DE ESTADOS</td>
              <td align="right"><a onClick="abrirformularios(5);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f5" style="display:<?php if($_SESSION['estado'] == 5) { echo "block"; } else { echo "none"; } ?>"><form name="form5" method="POST" action="<?php echo $editFormAction; ?>"><table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor">
    	        <table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
    	          <tr>
    	            <td class="ins_titulomayor">AREA DE ESTADOS</td>
    	            <td align="right"><a onClick="cerrarformularios(5);"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	            </tr>
                
  	          </table></td>
  	      </tr>
           <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Estado:</td>
                </tr>
                 <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><select name="estado" class="camposanchos" id="estado">
    	            <option value=""></option>
  	            </select>
    	            <input name="usuario" type="hidden" id="usuario" value="camilo"></td>
                 </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
             </table></td>
           </tr>
          <tr>
            <td class="celdapieazul"></td>
          </tr>
          
      </table></form></div><?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php 
mysql_free_result($vis_tipo_institucion);
//mysql_free_result($vis_tipo_entidad);
mysql_free_result($vis_instituciones);
mysql_free_result($vis_direccionesinstituciones);
mysql_free_result($vis_unidaddireccionesinst);
mysql_free_result($vis_responsablesinstitucion);
mysql_free_result($vis_unorespinstitucion);
mysql_free_result($vis_responsablesarchivo);
mysql_free_result($vis_unoresponsablesarchivo);
mysql_free_result($vis_tipoAcceso);
//mysql_free_result($vis_requisitosparaacceso);
//mysql_free_result($vis_accesodocumentacion);
mysql_free_result($vis_serviciodereproduccion);
mysql_free_result($vis_areasnotas);
mysql_free_result($vis_estados);
mysql_free_result($vis_formaautorizadadelnombre);
mysql_free_result($vis_editfanombre); 
?>