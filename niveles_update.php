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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//--vemos los estado alfanumericos del nivel-------------------------------------
$colname_estado_registral = "-1";
if (isset($_GET['cod'])) {
  $colname_estado_registral = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_estado_registral = sprintf("SELECT estado FROM vis_estados_niveles WHERE codigo_referencia = %s ORDER BY fecha DESC", GetSQLValueString($colname_estado_registral, "text"));
$estado_registral = mysql_query($query_estado_registral, $conn) or die(mysql_error());
$row_estado_registral = mysql_fetch_assoc($estado_registral);
$totalRows_estado_registral = mysql_num_rows($estado_registral);




//--actualizamos el area donde se esta trabajando----------------------------
function area_trabajo($na_t, $cod_ref,$database_conn,$conn) {	
	$updateSQL="UPDATE niveles SET na=".$na_t." WHERE codigo_referencia='".$cod_ref."'";
	
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos el area que se ha completado--------------------------------
function estado_completado($est_t, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE niveles SET estado=".$est_t." WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos el estado a completado--------------------------------
function estados($est_t, $cod_ref,$database_conn,$conn) {
	$insertSQL = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, fecha, usuario) VALUES (%s, %s, %s, %s)",
					   GetSQLValueString($cod_ref, "text"),
					   GetSQLValueString($est_t, "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($insertSQL, $conn) or die(mysql_error());
}

//--eliminamos valores de tablas relacionadas-------------------------------
if ((isset($_POST["elm"])) && ($_POST["val1"] != "")) {
	if($_POST["elm"] == 1) {
		$deleteSQL = sprintf("DELETE FROM niveles_inventario WHERE codigo_referencia=%s AND numero_inventario=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(1, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 2) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_edificios WHERE codigo_referencia=%s AND nombre_edificio=%s AND ubicacion_topografica=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
		area_trabajo(1, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 3) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_contenedores WHERE codigo_referencia=%s AND contenedor=%s AND ruta_acceso=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
		area_trabajo(1, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 4) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_productores WHERE codigo_referencia=%s AND nombre_productor=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 5) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_soportes WHERE codigo_referencia=%s AND soporte=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 6) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_idiomas WHERE codigo_referencia=%s AND idioma=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 7) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_niveles WHERE codigo_referencia=%s AND codigo_referencia2=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 8) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_institucionesinternas WHERE codigo_referencia=%s AND codigo_referencia_institucion_interna=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 9) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_institucionesexternas WHERE codigo_referencia=%s AND codigo_referencia_institucion_externa=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(2, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 10) {
		$deleteSQL = sprintf("DELETE FROM rel_niveles_sistemasorganizacion WHERE codigo_referencia=%s AND sistema_organizacion=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		area_trabajo(3, $_POST['cod'],$database_conn,$conn);
	} else if ($_POST["elm"] == 11) {
		$deleteSQL = sprintf("DELETE FROM niveles_tasacion_expertizaje WHERE codigo_referencia=%s AND valuacion=%s AND fecha=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
		area_trabajo(3, $_POST['cod'],$database_conn,$conn);
	}
	
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($deleteSQL, $conn) or die(mysql_error());
	
	$updateGoTo = "niveles_update.php?cod=".$_POST['cod'];
	header(sprintf("Location: %s", $updateGoTo));
}

//--AREA DE IDENTIFICACION-----------------------------------------------------------
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	$updateSQL1 = sprintf("UPDATE niveles SET titulo_original=%s, titulo_atribuido=%s, titulo_traducido=%s, tipo_nivel=%s, fecha_registro=%s,  numero_registro_inventario_anterior=%s, fondo_tesoro_sn=%s, fondo_tesoro=%s, usuario_ultima_modificacion=%s, fecha_ultima_modificacion=%s WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['titulo_original'], "text"),
                       GetSQLValueString($_POST['titulo_atribuido'], "text"),
                       GetSQLValueString($_POST['titulo_traducido'], "text"),
					   GetSQLValueString($_POST['tipo_nivel_text'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_registrod'],$_POST['fecha_registrom'],$_POST['fecha_registroa']), "date"),
                       GetSQLValueString($_POST['numero_registro_inventario_anterior'], "text"),
                       GetSQLValueString($_POST['fondo_tesoro_sn'], "int"),
                       GetSQLValueString($_POST['fondo_tesoro'], "text"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
                       GetSQLValueString($_POST['codigo_referencia'], "text"));
			   
					   
	 //--ponemos en estado pendiente si estaba en estado de inicio
  if($row_estado_registral['estado'] == "Inicio") {
   estados('Pendiente', $_POST['codigo_referencia'],$database_conn,$conn);
  }


	//--numeros de inventario------------
	if (isset($_POST['numero_inventario']) && $_POST['numero_inventario'] != "") {
		$insertSQL2 = sprintf("INSERT INTO niveles_inventario (codigo_referencia, numero_inventario) VALUES (%s, %s)",
	  				   		GetSQLValueString($_POST['codigo_referencia'], "text"),
							GetSQLValueString($_POST['numero_inventario'], "int"));

	mysql_select_db($database_conn, $conn);
	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
	}
	
	//--ubicacion topografica------------
	if (isset($_POST['ubicacion_topografica']) && $_POST['ubicacion_topografica'] != "" && $_POST['nombre_edificio'] != "") {
	$insertSQL3 = sprintf("INSERT INTO rel_niveles_edificios (codigo_referencia, nombre_edificio, ubicacion_topografica) VALUES (%s, %s, %s)",
	  				   GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString($_POST['nombre_edificio'], "text"),
					   GetSQLValueString($_POST['ubicacion_topografica'], "text"));
					   
	mysql_select_db($database_conn, $conn);
	$Result3 = mysql_query($insertSQL3, $conn) or die(mysql_error());  
  	}

	//--ruta de acceso------------------
	if (isset($_POST['ruta_acceso']) && $_POST['ruta_acceso'] != "" && $_POST['contenedor'] != "") {
	$insertSQL4 = sprintf("INSERT INTO rel_niveles_contenedores (codigo_referencia, contenedor, ruta_acceso) VALUES (%s, %s, %s)",
	  				   GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString($_POST['contenedor'], "text"),
					   GetSQLValueString($_POST['ruta_acceso'], "text"));
					   
	 mysql_select_db($database_conn, $conn);
  	 $Result4 = mysql_query($insertSQL4, $conn) or die(mysql_error());  
	}
	
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(1, $_POST['codigo_referencia'],$database_conn,$conn);
	
	//--redireccionamos luegro de grabar hacia el mismo formulario---------------
	$updateGoTo = "niveles_update.php?cod=".$_GET['cod'];
	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if(isset($_POST["codigo_institucion"]) && $_POST["codigo_institucion"] != "" && $_POST["codigo_referencia"] != "" && $_POST["titulo_original"] != "" && $_POST["titulo_atribuido"] != "" && $_POST["fecha_registroa"] != "" && $_POST["signaturaTopograficaCant"] >= 1 && $_POST["niveles_contenedores"] >= 1) {
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
		if($_SESSION['estado'] <= 1) {
			estado_completado(2, $_POST['codigo_referencia'],$database_conn,$conn);
			area_trabajo(2, $_POST['codigo_referencia'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente") {
			estados('Completo', $_POST['codigo_referencia'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));	
	} else {
		if($_SESSION['estado'] <= 1) {
			mysql_select_db($database_conn, $conn);
			$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
			header(sprintf("Location: %s", $updateGoTo));	
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Identificación no se ha grabado.\\nVerifique los campos obligatorios')</script>";  
		}	
	}
 
} //--fin del formulario identificacion------------------------------------------------------------

//-- AREA DE DESCRIPCION --------------------------------------------------------------------------
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  
	$updateSQL1 = sprintf("UPDATE niveles SET fecha_inicial=%s, fecha_final=%s, alcance_contenido=%s, metros_lineales=%s, unidades=%s, historia_institucional_productor=%s, historia_archivistica=%s, usuario_ultima_modificacion=%s, fecha_ultima_modificacion=%s WHERE codigo_referencia=%s",
                       GetSQLValueString(fechar($_POST['fecha_iniciald'],$_POST['fecha_inicialm'],$_POST['fecha_iniciala']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_finald'],$_POST['fecha_finalm'],$_POST['fecha_finala']), "date"),
                       GetSQLValueString($_POST['alcance_contenido'], "text"),
                       GetSQLValueString($_POST['metros_lineales'], "text"),
                       GetSQLValueString($_POST['unidades'], "text"),
                       GetSQLValueString($_POST['historia_institucional_productor'], "text"),
                       GetSQLValueString($_POST['historia_archivistica'], "text"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
                       GetSQLValueString($_POST['codigo_referencia_a_2'], "text"));


	//--nombre del productor--------------------------------------------------
	if(isset($_POST['nombre_productor']) && $_POST['nombre_productor'] != "") {
  	$insertSQL2 = sprintf("INSERT INTO rel_niveles_productores (codigo_referencia, nombre_productor) VALUES (%s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['nombre_productor'], "text"));
  
	mysql_select_db($database_conn, $conn);
	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
	}

	//--Soportes--------------------------------------------------------------
	if(isset($_POST['soporte']) && $_POST['soporte'] != "") {
 	$insertSQL3 = sprintf("INSERT INTO rel_niveles_soportes (codigo_referencia, soporte) VALUES (%s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['soporte'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Result3 = mysql_query($insertSQL3, $conn) or die(mysql_error());
	}
  
	//--idiomas----------------------------------------------------------------
	if(isset($_POST['idioma']) && $_POST['idioma'] != "") {
  	$insertSQL4 = sprintf("INSERT INTO rel_niveles_idiomas (codigo_referencia, idioma) VALUES (%s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['idioma'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Result4 = mysql_query($insertSQL4, $conn) or die(mysql_error());
	} 
  
	//--vinculacion entre niveles---------------------------------------------
	if(isset($_POST['codigo_referencia2']) && $_POST['codigo_referencia2'] != "") {
  	$insertSQL5 = sprintf("INSERT INTO rel_niveles_niveles (codigo_referencia1, codigo_referencia2, descripcion_vinculo) VALUES (%s, %s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['codigo_referencia2'], "text"),
						GetSQLValueString($_POST['descripcion_vinculo'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Result5 = mysql_query($insertSQL5, $conn) or die(mysql_error());
	}

	//--vinculacion con intituciones internas---------------------------------
	if(isset($_POST['codigo_referencia_institucion_interna']) && $_POST['codigo_referencia_institucion_interna'] != "") {
  	$insertSQL6 = sprintf("INSERT INTO rel_niveles_institucionesinternas (codigo_referencia, codigo_referencia_institucion_interna, descripcion) VALUES (%s, %s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['codigo_referencia_institucion_interna'], "text"),
						GetSQLValueString($_POST['descripcion'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Resul6 = mysql_query($insertSQL6, $conn) or die(mysql_error());
	}

	//--vinculacion con instituciones externas-------------------------------
	if(isset($_POST['codigo_referencia_institucion_externa']) && $_POST['codigo_referencia_institucion_externa'] != "") {
  	$insertSQL7 = sprintf("INSERT INTO rel_niveles_institucionesexternas (codigo_referencia, codigo_referencia_institucion_externa, descripcion) VALUES (%s, %s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_2'], "text"),
						GetSQLValueString($_POST['codigo_referencia_institucion_externa'], "text"),
						GetSQLValueString($_POST['descripcion2'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Resul7 = mysql_query($insertSQL7, $conn) or die(mysql_error());
	}
	
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(2, $_POST['codigo_referencia_a_2'],$database_conn,$conn);

	//--redireccionamos luegro de grabar hacia el mismo formulario---------------
  	$updateGoTo = "niveles_update.php?cod=".$_GET['cod'];
  
	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if($_POST["rel_niveles_productoresCant"] >= 1 && $_POST["fecha_iniciala"] != "" && $_POST["fecha_finala"] != "" && $_POST["alcance_contenido"] != "" && $_POST["metros_lineales"] != "" && $_POST["unidades"] != "" && $_POST["historia_institucional_productor"] != "" && $_POST["historia_archivistica"] != "") {
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
		if($_SESSION['estado'] <= 2) {
			estado_completado(3, $_POST['codigo_referencia_a_2'],$database_conn,$conn);
			area_trabajo(3, $_POST['codigo_referencia_a_2'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente") {
			estados('Completo', $_POST['codigo_referencia_a_2'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));
	} else {	
	  	if($_SESSION['estado'] <= 2) {
			mysql_select_db($database_conn, $conn);
			$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
			header(sprintf("Location: %s", $updateGoTo));
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Descripción no se ha grabado.\\nVerifique los campos obligatorios')</script>"; 	
		}
 	}
	
} //--fin del formulario descripcion---------------------------------------------------------------

//--AREA DE ADMINISTRACION-------------------------------------------------------------------------
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
	$updateSQL1 = sprintf("UPDATE niveles SET forma_ingreso=%s, procedencia=%s, fecha_inicio_ingreso=%s, precio=%s, norma_legal_ingreso=%s,  numero_administrativo=%s, derechos_restricciones=%s, titular_derecho=%s, tipo_acceso=%s, requisito_acceso=%s, acceso_documentacion=%s, publicaciones_acceso=%s, subsidios_otorgados=%s,  usuario_ultima_modificacion=%s, fecha_ultima_modificacion=%s WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['forma_ingreso'], "text"),
                       GetSQLValueString($_POST['procedencia'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_inicio_ingresod'],$_POST['fecha_inicio_ingresom'],$_POST['fecha_inicio_ingresoa']), "text"),
                       GetSQLValueString($_POST['precio'], "text"),
                       GetSQLValueString($_POST['norma_legal_ingreso'], "text"),
                       GetSQLValueString($_POST['numero_administrativo'], "text"),
                       GetSQLValueString($_POST['derechos_restricciones'], "text"),
                       GetSQLValueString($_POST['titular_derecho'], "text"),
					   GetSQLValueString($_POST['tipo_acceso'], "text"),
					   GetSQLValueString($_POST['requisito_acceso'], "text"),
					   GetSQLValueString($_POST['acceso_documentacion'], "text"),
                       GetSQLValueString($_POST['publicaciones_acceso'], "text"),
					   GetSQLValueString($_POST['subsidios_otorgados'], "text"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
                       GetSQLValueString($_POST['codigo_referencia_a_3'], "text"));
					   
  
  //--sistemas de organizacion-------------------------------------------------
	if(isset($_POST['sistema_organizacion']) && $_POST['sistema_organizacion'] != "") {
  	$insertSQL2 = sprintf("INSERT INTO rel_niveles_sistemasorganizacion (codigo_referencia, sistema_organizacion) VALUES (%s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_3'], "text"),
						GetSQLValueString($_POST['sistema_organizacion'], "text"));
  
 	mysql_select_db($database_conn, $conn);
  	$Resul2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
	}

  //--tasacion expertizaje-----------------------------------------------------
	if(isset($_POST['valuacion']) && $_POST['valuacion'] != "" && $_POST['fechatasaciona'] != "" && $_POST['tasador_experto'] != "") {
  	$insertSQL3 = sprintf("INSERT INTO niveles_tasacion_expertizaje (codigo_referencia, valuacion, valuacion_usd, fecha, tasador_experto, comentario_expertizaje) VALUES (%s, %s, %s, %s, %s, %s)",
  						GetSQLValueString($_POST['codigo_referencia_a_3'], "text"),
						GetSQLValueString($_POST['valuacion'], "text"),
						GetSQLValueString($_POST['valuacion_usd'], "text"),
						GetSQLValueString(fechar($_POST['fechatasaciond'],$_POST['fechatasacionm'],$_POST['fechatasaciona']), "text"),
						GetSQLValueString($_POST['tasador_experto'], "text"),
						GetSQLValueString($_POST['comentario_expertizaje'], "text"));
  
  	mysql_select_db($database_conn, $conn);
  	$Resul3 = mysql_query($insertSQL3, $conn) or die(mysql_error());
	}
 
 //--normativa de baja-------------------------------------------------------------------
	if(isset($_POST['normativa_legal_baja']) && $_POST['normativa_legal_baja'] != "" && $_POST['numero_norma_legal'] != "" && $_POST['motivo'] != "" && $_POST['fechabajaa'] != "") {
	$updateSQL4 = sprintf("UPDATE niveles SET normativa_legal_baja=%s, numero_norma_legal=%s, motivo=%s, fecha_baja=%s WHERE codigo_referencia=%s",
						GetSQLValueString($_POST['normativa_legal_baja'], "text"),
					   GetSQLValueString($_POST['numero_norma_legal'], "text"),
					   GetSQLValueString($_POST['motivo'], "text"),
					   GetSQLValueString(fechar($_POST['fechabajad'],$_POST['fechabajam'],$_POST['fechabajaa']), "text"),
					   GetSQLValueString($_POST['codigo_referencia_a_3'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Resul4 = mysql_query($insertSQL4, $conn) or die(mysql_error());
	}
 
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(3, $_POST['codigo_referencia_a_3'],$database_conn,$conn);

	//--redireccionamos luegro de grabar hacia el mismo formulario---------------
  	$updateGoTo = "niveles_update.php?cod=".$_GET['cod'];
  	
	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if($_POST["forma_ingreso"] != "" && $_POST["procedencia"] != "" && $_POST["fecha_inicio_ingresoa"] != "" && $_POST["derechos_restricciones"] != "" && $_POST["titular_derecho"] != "" && $_POST["publicaciones_acceso"] != "" ) {
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
		if($_SESSION['estado'] <= 3) {
			estado_completado(4, $_POST['codigo_referencia_a_3'],$database_conn,$conn);
			area_trabajo(4, $_POST['codigo_referencia_a_3'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente") {
			estados('Completo', $_POST['codigo_referencia_a_3'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));
	} else {
		if($_SESSION['estado'] <= 3) {
			mysql_select_db($database_conn, $conn);
			$Result1 = mysql_query($updateSQL1, $conn) or die(mysql_error());
			header(sprintf("Location: %s", $updateGoTo));
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Administración no se ha grabado.\\nVerifique los campos obligatorios')</script>"; 
		}
	}
		
} //--fin del formulario administracion----------------------------------------------------------

//--AREAS DE NOTAS-------------------------------------------------------------------------------
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form4")) {	
	$replaceSQL1 = sprintf("REPLACE INTO niveles_areasnotas SET codigo_referencia=%s, nota_descripcion=%s, nota_archivero=%s, fuentes=%s, fecha_descripcion=%s, fecha_modificacion=%s",
						GetSQLValueString($_POST['codigo_referencia_a_4'], "text"),
						GetSQLValueString($_POST['nota_descripcion'], "text"),
                       	GetSQLValueString($_POST['nota_archivero'], "text"),
                       	GetSQLValueString($_POST['fuentes'], "text"),
                       	GetSQLValueString(fechar($_POST['fecha_descripciond'],$_POST['fecha_descripcionm'],$_POST['fecha_descripciona']), "text"),
                       	GetSQLValueString(date("Y/m/d H:i,s"), "date"));
  
	
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(4, $_POST['codigo_referencia_a_4'],$database_conn,$conn);
 	
	//--redireccionamos luegro de grabar hacia el mismo formulario--------------- 
	$updateGoTo = "niveles_update.php?cod=".$_GET['cod'];
	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------  
	if($_POST["nota_descripcion"] != "" ) {
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($replaceSQL1, $conn) or die(mysql_error());
		if($_SESSION['estado'] <= 4) {
			estado_completado(5, $_POST['codigo_referencia_a_4'],$database_conn,$conn);
			area_trabajo(5, $_POST['codigo_referencia_a_4'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente" || $row_estado_registral['estado'] == "Pendiente") {
			estados('Completo', $_POST['codigo_referencia_a_4'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));
	} else {
		if($_SESSION['estado'] <= 4) {
			mysql_select_db($database_conn, $conn);
			$Result1 = mysql_query($replaceSQL1, $conn) or die(mysql_error());
			header(sprintf("Location: %s", $updateGoTo));
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Notas no se ha grabado.\\nVerifique los campos obligatorios')</script>"; 
		}
	}
 
} //--fin del formulario notas---------------------------------------------------------------




//--vemos la tabla principal de niveles-----------------------------------------
$colname_vis_niveles = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_niveles = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_niveles = sprintf("SELECT N1.*,
IFNULL((SELECT N2.tipo_nivel FROM niveles N2 WHERE N1.cod_ref_sup=N2.codigo_referencia),0) AS tipo_sup,
IFNULL((SELECT min(N3.tipo_nivel) FROM niveles N3 WHERE N3.cod_ref_sup=N1.codigo_referencia),7) AS tipo_inf
FROM niveles N1
WHERE N1.codigo_referencia = %s 
LIMIT 1", GetSQLValueString($colname_vis_niveles, "text"));
$vis_niveles = mysql_query($query_vis_niveles, $conn) or die(mysql_error());
$row_vis_niveles = mysql_fetch_assoc($vis_niveles);
$totalRows_vis_niveles = mysql_num_rows($vis_niveles);

$_SESSION['estado'] = $row_vis_niveles['estado'];
$_SESSION['na'] = $row_vis_niveles['na'];

if($row_vis_niveles['fecha_baja'] != "") {
	$_SESSION['situacion'] = "Baja";
} else {
	$_SESSION['situacion'] = "Local";
}

$s1=permiso($_SESSION['MM_usuario'], $row_vis_niveles['codigo_institucion'], 'NIV1' ,$database_conn,$conn);
$s2=permiso($_SESSION['MM_usuario'], $row_vis_niveles['codigo_institucion'], 'NIV2' ,$database_conn,$conn);
$s3=permiso($_SESSION['MM_usuario'], $row_vis_niveles['codigo_institucion'], 'NIV3' ,$database_conn,$conn);
$s4=permiso($_SESSION['MM_usuario'], $row_vis_niveles['codigo_institucion'], 'NIV4' ,$database_conn,$conn);

//echo $s1.$s2.$s3.$s4;

if($s1 == "" || $s2 == "" || $s3 == "" || $s4 == "") {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}

//--vemos la relacion entre niveles e inventario--------------------------------
$colname_vis_numerosInventarios = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_numerosInventarios = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_numerosInventarios = sprintf("SELECT * FROM niveles_inventario WHERE codigo_referencia = %s ORDER BY numero_inventario ASC", GetSQLValueString($colname_vis_numerosInventarios, "text"));
$vis_numerosInventarios = mysql_query($query_vis_numerosInventarios, $conn) or die(mysql_error());
$row_vis_numerosInventarios = mysql_fetch_assoc($vis_numerosInventarios);
$totalRows_vis_numerosInventarios = mysql_num_rows($vis_numerosInventarios);

//--vemos los edificios para este nivel----------------------------------------
$colname_vis_signaturaTopografica = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_signaturaTopografica = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_signaturaTopografica = sprintf("SELECT * FROM rel_niveles_edificios WHERE codigo_referencia = %s ORDER BY nombre_edificio ASC", GetSQLValueString($colname_vis_signaturaTopografica, "text"));
$vis_signaturaTopografica = mysql_query($query_vis_signaturaTopografica, $conn) or die(mysql_error());
$row_vis_signaturaTopografica = mysql_fetch_assoc($vis_signaturaTopografica);
$totalRows_vis_signaturaTopografica = mysql_num_rows($vis_signaturaTopografica);

//--vemos la relacion entre este nivel y los contenedores ----------------------
$colname_vis_niveles_contenedores = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_niveles_contenedores = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_niveles_contenedores = sprintf("SELECT * FROM rel_niveles_contenedores WHERE codigo_referencia = %s ORDER BY contenedor ASC", GetSQLValueString($colname_vis_niveles_contenedores, "text"));
$vis_niveles_contenedores = mysql_query($query_vis_niveles_contenedores, $conn) or die(mysql_error());
$row_vis_niveles_contenedores = mysql_fetch_assoc($vis_niveles_contenedores);
$totalRows_vis_niveles_contenedores = mysql_num_rows($vis_niveles_contenedores);

//--vemos los productores del nivel seleccionado---------------------------------
$colname_rel_niveles_productores = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_productores = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_productores = sprintf("SELECT * FROM rel_niveles_productores WHERE codigo_referencia = %s ORDER BY nombre_productor ASC", GetSQLValueString($colname_rel_niveles_productores, "text"));
$rel_niveles_productores = mysql_query($query_rel_niveles_productores, $conn) or die(mysql_error());
$row_rel_niveles_productores = mysql_fetch_assoc($rel_niveles_productores);
$totalRows_rel_niveles_productores = mysql_num_rows($rel_niveles_productores);


//--vemos los soportes del nivel-------------------------------------------------
$colname_rel_niveles_soportes = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_soportes = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_soportes = sprintf("SELECT * FROM rel_niveles_soportes WHERE codigo_referencia = %s ORDER BY soporte ASC", GetSQLValueString($colname_rel_niveles_soportes, "text"));
$rel_niveles_soportes = mysql_query($query_rel_niveles_soportes, $conn) or die(mysql_error());
$row_rel_niveles_soportes = mysql_fetch_assoc($rel_niveles_soportes);
$totalRows_rel_niveles_soportes = mysql_num_rows($rel_niveles_soportes);

//--venos los idiomas del nivel---------------------------------------------------
$colname_rel_niveles_idiomas = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_idiomas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_idiomas = sprintf("SELECT * FROM rel_niveles_idiomas WHERE codigo_referencia = %s ORDER BY idioma ASC", GetSQLValueString($colname_rel_niveles_idiomas, "text"));
$rel_niveles_idiomas = mysql_query($query_rel_niveles_idiomas, $conn) or die(mysql_error());
$row_rel_niveles_idiomas = mysql_fetch_assoc($rel_niveles_idiomas);
$totalRows_rel_niveles_idiomas = mysql_num_rows($rel_niveles_idiomas);

//--venis las vinculaciones entre niveles-----------------------------------------
$colname_rel_niveles_niveles = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_niveles = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_niveles = sprintf("SELECT * FROM rel_niveles_niveles WHERE codigo_referencia1 = %s ORDER BY codigo_referencia2 ASC", GetSQLValueString($colname_rel_niveles_niveles, "text"));
$rel_niveles_niveles = mysql_query($query_rel_niveles_niveles, $conn) or die(mysql_error());
$row_rel_niveles_niveles = mysql_fetch_assoc($rel_niveles_niveles);
$totalRows_rel_niveles_niveles = mysql_num_rows($rel_niveles_niveles);

//--vemos las vinculaciones con las instituciones internas------------------------
$colname_rel_niveles_institucionesinternas = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_institucionesinternas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_institucionesinternas = sprintf("SELECT * FROM rel_niveles_institucionesinternas WHERE codigo_referencia = %s ORDER BY codigo_referencia_institucion_interna ASC", GetSQLValueString($colname_rel_niveles_institucionesinternas, "text"));
$rel_niveles_institucionesinternas = mysql_query($query_rel_niveles_institucionesinternas, $conn) or die(mysql_error());
$row_rel_niveles_institucionesinternas = mysql_fetch_assoc($rel_niveles_institucionesinternas);
$totalRows_rel_niveles_institucionesinternas = mysql_num_rows($rel_niveles_institucionesinternas);


//--vemos las vinculaciones con las instituciones externas------------------------
$colname_rel_niveles_institucionesexternas = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_institucionesexternas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_institucionesexternas = sprintf("SELECT * FROM rel_niveles_institucionesexternas WHERE codigo_referencia = %s ORDER BY codigo_referencia_institucion_externa ASC", GetSQLValueString($colname_rel_niveles_institucionesexternas, "text"));
$rel_niveles_institucionesexternas = mysql_query($query_rel_niveles_institucionesexternas, $conn) or die(mysql_error());
$row_rel_niveles_institucionesexternas = mysql_fetch_assoc($rel_niveles_institucionesexternas);
$totalRows_rel_niveles_institucionesexternas = mysql_num_rows($rel_niveles_institucionesexternas);

//--vemos las notas del nivel-----------------------------------------------------
$colname_niveles_areasnotas = "-1";
if (isset($_GET['cod'])) {
  $colname_niveles_areasnotas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_niveles_areasnotas = sprintf("SELECT * FROM niveles_areasnotas WHERE codigo_referencia = %s", GetSQLValueString($colname_niveles_areasnotas, "text"));
$niveles_areasnotas = mysql_query($query_niveles_areasnotas, $conn) or die(mysql_error());
$row_niveles_areasnotas = mysql_fetch_assoc($niveles_areasnotas);
$totalRows_niveles_areasnotas = mysql_num_rows($niveles_areasnotas);

//--vemos los sistemas de organizacion del nivel----------------------------------
$colname_rel_niveles_sistemasorganizacion = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_niveles_sistemasorganizacion = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_niveles_sistemasorganizacion = sprintf("SELECT * FROM rel_niveles_sistemasorganizacion WHERE codigo_referencia = %s ORDER BY sistema_organizacion ASC", GetSQLValueString($colname_rel_niveles_sistemasorganizacion, "text"));
$rel_niveles_sistemasorganizacion = mysql_query($query_rel_niveles_sistemasorganizacion, $conn) or die(mysql_error());
$row_rel_niveles_sistemasorganizacion = mysql_fetch_assoc($rel_niveles_sistemasorganizacion);
$totalRows_rel_niveles_sistemasorganizacion = mysql_num_rows($rel_niveles_sistemasorganizacion);

//--vemos las tasaciones del nivel------------------------------------------------
$colname_niveles_tasacion_expertizaje = "-1";
if (isset($_GET['cod'])) {
  $colname_niveles_tasacion_expertizaje = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_niveles_tasacion_expertizaje = sprintf("SELECT * FROM niveles_tasacion_expertizaje WHERE codigo_referencia = %s ORDER BY fecha ASC", GetSQLValueString($colname_niveles_tasacion_expertizaje, "text"));
$niveles_tasacion_expertizaje = mysql_query($query_niveles_tasacion_expertizaje, $conn) or die(mysql_error());
$row_niveles_tasacion_expertizaje = mysql_fetch_assoc($niveles_tasacion_expertizaje);
$totalRows_niveles_tasacion_expertizaje = mysql_num_rows($niveles_tasacion_expertizaje);

//--llena el combo contenedores-------------------------------------------------
mysql_select_db($database_conn, $conn);
$query_vis_contenedores = "SELECT * FROM contenedores ORDER BY contenedor ASC";
$vis_contenedores = mysql_query($query_vis_contenedores, $conn) or die(mysql_error());
$row_vis_contenedores = mysql_fetch_assoc($vis_contenedores);
$totalRows_vis_contenedores = mysql_num_rows($vis_contenedores);

//--llena el combo edificios----------------------------------------------------
mysql_select_db($database_conn, $conn);
$query_vis_edificios = "SELECT * FROM edificios ORDER BY nombre_edificio ASC";
$vis_edificios = mysql_query($query_vis_edificios, $conn) or die(mysql_error());
$row_vis_edificios = mysql_fetch_assoc($vis_edificios);
$totalRows_vis_edificios = mysql_num_rows($vis_edificios);

mysql_select_db($database_conn, $conn);
$query_registro_autoridad = "SELECT * FROM registro_autoridad WHERE codigo_institucion='".$row_vis_niveles['codigo_institucion']."' ORDER BY nombre_productor ASC";
$registro_autoridad = mysql_query($query_registro_autoridad, $conn) or die(mysql_error());
$row_registro_autoridad = mysql_fetch_assoc($registro_autoridad);
$totalRows_registro_autoridad = mysql_num_rows($registro_autoridad);


$colname_cbo_niveles = $row_vis_niveles['codigo_institucion'];
mysql_select_db($database_conn, $conn);
$query_cbo_niveles = sprintf("SELECT * FROM niveles WHERE codigo_institucion = %s ORDER BY titulo_original ASC", GetSQLValueString($colname_cbo_niveles, "text"));
$cbo_niveles = mysql_query($query_cbo_niveles, $conn) or die(mysql_error());
$row_cbo_niveles = mysql_fetch_assoc($cbo_niveles);
$totalRows_cbo_niveles = mysql_num_rows($cbo_niveles);

mysql_select_db($database_conn, $conn);
$query_formas_ingreso = "SELECT * FROM formas_ingreso ORDER BY forma_ingreso ASC";
$formas_ingreso = mysql_query($query_formas_ingreso, $conn) or die(mysql_error());
$row_formas_ingreso = mysql_fetch_assoc($formas_ingreso);
$totalRows_formas_ingreso = mysql_num_rows($formas_ingreso);

mysql_select_db($database_conn, $conn);
$query_cbo_normalegalingreso = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$cbo_normalegalingreso = mysql_query($query_cbo_normalegalingreso, $conn) or die(mysql_error());
$row_cbo_normalegalingreso = mysql_fetch_assoc($cbo_normalegalingreso);
$totalRows_cbo_normalegalingreso = mysql_num_rows($cbo_normalegalingreso);

mysql_select_db($database_conn, $conn);
$query_soportes = "SELECT * FROM soportes ORDER BY soporte ASC";
$soportes = mysql_query($query_soportes, $conn) or die(mysql_error());
$row_soportes = mysql_fetch_assoc($soportes);
$totalRows_soportes = mysql_num_rows($soportes);

mysql_select_db($database_conn, $conn);
$query_idiomas = "SELECT * FROM idiomas ORDER BY idioma ASC";
$idiomas = mysql_query($query_idiomas, $conn) or die(mysql_error());
$row_idiomas = mysql_fetch_assoc($idiomas);
$totalRows_idiomas = mysql_num_rows($idiomas);

mysql_select_db($database_conn, $conn);
$query_instituciones = "SELECT * FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

mysql_select_db($database_conn, $conn);
$query_instituciones_externas = "SELECT * FROM instituciones_externas ORDER BY nombre_autorizado ASC";
$instituciones_externas = mysql_query($query_instituciones_externas, $conn) or die(mysql_error());
$row_instituciones_externas = mysql_fetch_assoc($instituciones_externas);
$totalRows_instituciones_externas = mysql_num_rows($instituciones_externas);

mysql_select_db($database_conn, $conn);
$query_sistemas_organizaciones = "SELECT * FROM sistemas_organizaciones ORDER BY sistema_organizacion ASC";
$sistemas_organizaciones = mysql_query($query_sistemas_organizaciones, $conn) or die(mysql_error());
$row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones);
$totalRows_sistemas_organizaciones = mysql_num_rows($sistemas_organizaciones);

mysql_select_db($database_conn, $conn);
$query_norma_legal = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$norma_legal = mysql_query($query_norma_legal, $conn) or die(mysql_error());
$row_norma_legal = mysql_fetch_assoc($norma_legal);
$totalRows_norma_legal = mysql_num_rows($norma_legal);



mysql_select_db($database_conn, $conn);
$query_numeros_inventarios_reales = "SELECT DISTINCT numero_inventario_unidad_documental FROM documentos WHERE codigo_referencia LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND numero_inventario_unidad_documental IS NOT NULL ORDER BY numero_inventario_unidad_documental ASC";
$numeros_inventarios_reales = mysql_query($query_numeros_inventarios_reales, $conn) or die(mysql_error());
$row_numeros_inventarios_reales = mysql_fetch_assoc($numeros_inventarios_reales);
$totalRows_numeros_inventarios_reales = mysql_num_rows($numeros_inventarios_reales);

mysql_select_db($database_conn, $conn);
$query_sistema_organizacion_real = "SELECT DISTINCT sistema_organizacion FROM documentos WHERE codigo_referencia LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND sistema_organizacion IS NOT NULL ORDER BY sistema_organizacion ASC";
$sistema_organizacion_real = mysql_query($query_sistema_organizacion_real, $conn) or die(mysql_error());
$row_sistema_organizacion_real = mysql_fetch_assoc($sistema_organizacion_real);
$totalRows_sistema_organizacion_real = mysql_num_rows($sistema_organizacion_real);

mysql_select_db($database_conn, $conn);
$query_forma_ingreso_real = "SELECT DISTINCT forma_ingreso FROM documentos WHERE codigo_referencia LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND forma_ingreso IS NOT NULL ORDER BY forma_ingreso ASC";
$forma_ingreso_real = mysql_query($query_forma_ingreso_real, $conn) or die(mysql_error());
$row_forma_ingreso_real = mysql_fetch_assoc($forma_ingreso_real);
$totalRows_forma_ingreso_real = mysql_num_rows($forma_ingreso_real);

mysql_select_db($database_conn, $conn);
$query_precio_real = "SELECT precio FROM documentos WHERE codigo_referencia LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND precio IS NOT NULL ORDER BY precio ASC";
$precio_real = mysql_query($query_precio_real, $conn) or die(mysql_error());
$row_precio_real = mysql_fetch_assoc($precio_real);
$totalRows_precio_real = mysql_num_rows($precio_real);


mysql_select_db($database_conn, $conn);
$query_doc_soportes = "SELECT DISTINCT soporte FROM documentos WHERE codigo_referencia LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND soporte IS NOT NULL ORDER BY soporte ASC";
$doc_soportes = mysql_query($query_doc_soportes, $conn) or die(mysql_error());
$row_doc_soportes = mysql_fetch_assoc($doc_soportes);
$totalRows_doc_soportes = mysql_num_rows($doc_soportes);

mysql_select_db($database_conn, $conn);
$query_doc_idiomas = "SELECT DISTINCT idioma FROM rel_documentos_idiomas WHERE idioma LIKE '".$row_vis_niveles['codigo_referencia']."/%' AND idioma IS NOT NULL ORDER BY idioma ASC";
$doc_idiomas = mysql_query($query_doc_idiomas, $conn) or die(mysql_error());
$row_doc_idiomas = mysql_fetch_assoc($doc_idiomas);
$totalRows_doc_idiomas = mysql_num_rows($doc_idiomas);


mysql_select_db($database_conn, $conn);
$query_niveles_tipos = "SELECT * FROM niveles_tipos WHERE idnivel_tipo < 5 AND idnivel_tipo >= ".$row_vis_niveles['tipo_sup']." AND idnivel_tipo <=".$row_vis_niveles['tipo_inf']." ORDER BY idnivel_tipo ASC";
$niveles_tipos = mysql_query($query_niveles_tipos, $conn) or die(mysql_error());
$row_niveles_tipos = mysql_fetch_assoc($niveles_tipos);
$totalRows_niveles_tipos = mysql_num_rows($niveles_tipos);


mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Niveles' ORDER BY idtips ASC";
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);




do {

$nomTip = "v".$row_tips['idtips'];
$$nomTip = "";

$$nomTip .= "<div id=\"".$nomTip."\" style=\"position:absolute; z-index:100; visibility:hidden; width:505px;\"  onmouseover=\"muestra_retarda('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\"><table width=\"505\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><img src=\"images/globo1.png\" width=\"505\" height=\"28\"></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><table width=\"490\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "      <tr>";
$$nomTip .= "        <td style=\"background:url(images/globo3.png);\"><table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "          <tr>";
$$nomTip .= "            <td class=\"tips\">".$row_tips['tip'];
if($row_tips['requerido'] == 1) {
$$nomTip .= "<b> CAMPO OBLIGATORIO</b>";
}
$$nomTip .= "</td>";
$$nomTip .= "          </tr>";
$$nomTip .= "        </table></td>";
$$nomTip .= "      </tr>";
$$nomTip .= "    </table></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td align=\"right\"><img src=\"images/globo2.png\" width=\"490\" height=\"10\"></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "</table>";
$$nomTip .= "</div><a onMouseOver=\"muestra_coloca('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\">";
if ($row_tips['requerido'] == 1) {
$$nomTip .= "<img src=\"images/help2.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";
} else {
$$nomTip .= "<img src=\"images/help1.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";	
}
$$nomTip .= "</a>";
$$nomTip .= "&nbsp;".$row_tips['item'];


} while ($row_tips = mysql_fetch_assoc($tips));
$rows = mysql_num_rows($tips);
  if($rows > 0) {
      mysql_data_seek($tips, 0);
	  $row_tips = mysql_fetch_assoc($tips);
}




?>
<html>
<head>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
	scrollbar-face-color: #a99e88;
 	scrollbar-base-color: #d4dce7;
 	scrollbar-arrow-color: #000000;
 	//scrollbar-highlight-color: #181502;
 	scrollbar-3d-light-color: #369;
	 scrollbar-shadow-color: #036;
 	scrollbar-dark-shadow-color: #036;
}
  
.tipo0{   
padding: 6px;   
position: absolute;   
width: 200px;   
border: 2px solid black;   
background-color: menu;   
font-family: Verdana;   
line-height: 20px;   
cursor: default;   
visibility: hidden;   
}   
  
.tipo1{   
padding: 6px;   
cursor: default;   
position: absolute;   
width: 165px;   
background-color: Menu;   
color: MenuText;   
border: 0 solid white;   
visibility: hidden;   
border: 2 outset ButtonHighlight;   
}   
  
a.menuitem {font-size: 0.8em; font-family: Arial, Serif; text-decoration: none;}   
a.menuitem:link {color: #000000; }   
a.menuitem:hover {color: #FFFFFF; background: #0A246A;}   
a.menuitem:visited {color: #868686;} 

</style>
<script language="javascript">
	//--abre las areas de los formularios
	function abrirformularios(j) {
		cerrartodo();
		document.getElementById('f'+j).style.display = "block";
		document.getElementById('f'+j+'c').style.display = "none";
	}
	
	//--cierra las areas de los formularios
	function cerrarformularios(l) {
		document.getElementById('f'+l+'c').style.display = "block";
		document.getElementById('f'+l).style.display = "none";
	}
	
	//--cierra todas las areas de los formularios
	function cerrartodo() {
		for(i=1;i<=<?php if($_SESSION['estado'] >= 4) { echo "4"; } else { echo $_SESSION['estado']; }  ; ?>;i++) {
		document.getElementById('f'+i).style.display = "none";
		document.getElementById('f'+i+'c').style.display = "block";
		//document.getElementById('f'+i+'d').style.display = "none";
		}
	}
	
	//--abre los insert de las tablas auxiliares
	function abriraux(m) {
		document.getElementById('dv_aux_c_'+m).style.display = "none";
		document.getElementById('dv_aux_a_'+m).style.display = "block";
	}
	
	//--cierra las areas de las tablas auxiliares
	function cerraraux(n) {
		document.getElementById('dv_aux_a_'+n).style.display = "none";
		document.getElementById('dv_aux_c_'+n).style.display = "block";
	}
	
	//--muesta o esconde el campo de fondo tesoro
	function fondotesoro(v) {
		if (v==1) {
		document.getElementById('tbfondotesoro').style.display = "block";
		} else  {
		document.getElementById('tbfondotesoro').style.display = "none";	
		}
	}
	
	//--envia variables por post
	function relocate(page,params)
 {
	  var body = document.body;
	  form=document.createElement('form'); 
	  form.method = 'POST'; 
	  form.action = page;
	  form.name = 'jsform';
	  for (index in params)
	  {
			var input = document.createElement('input');
			input.type='hidden';
			input.name=index;
			input.id=index;
			input.value=params[index];
			form.appendChild(input);
	  }	  		  			  
	  body.appendChild(form);
	  form.submit();
 }
 
 
var ns4 = (document.layers)? true:false  
var ie = (document.all)? true:false  
var ns6 = (document.getElementById && !document.all) ? true: false;  
var coorX, coorY;  
if (ns6) document.addEventListener("mousedown", coord, true)  
if (ns4) {document.captureEvents(Event.MOUSEDOWN); document.mousedown = coord;}  
if (ie) document.onmousedown = coord 

function coord(e) 
{ 
   if (ns4||ns6)    {  
      coorX = e.pageX;  
      coorY = e.pageY;  
   }  
   if (ie)    {  
      coorX = event.x;  
      coorY = event.y;  
   }  
   if (document.layers && !document.getElementById){  
     if (e.which == 2 || e.which == 3){  
      mostrar() 
      return false;  
     }  
    }  

   return true; 
} 

//pocicion absoluta del menu=0, menu con el boton derecho=1   
var menutipo = 1   
  
//muestra el menu   
function sombra(e){   
  
   if (document.getElementById) {   
      mimenu = document.getElementById("cepilomenu")   
   }else if (document.all) {   
      mimenu = document.all.cepilomenu   
   }   
       
   /*La gestion de eventos con IE4 e IE5 utiliza el objeto window.event, que no forma  
   parte de DOM2. IE5 soporta getElementById, pero sigue usando este objeto para la  
   gestion de eventos, por lo que hay que tratarle de forma exclusiva */ 
   if (!e) var e = window.event  
       
   //distancia a bordes   
   var borde_derecho = document.body.clientWidth - e.clientX  
   var borde_inferior = document.body.clientHeight - e.clientY  
  
   //distancia del menu al puntero   
   if (borde_derecho < mimenu.offsetWidth)   
      mimenu.style.left = document.body.scrollLeft + e.clientX - cepilomenu.offsetWidth + 'px'  
   else  
      mimenu.style.left = document.body.scrollLeft + e.clientX + 'px'  
  
   //pocicion vertical   
   if (borde_inferior < mimenu.offsetHeight)   
      mimenu.style.top = document.body.scrollTop + e.clientY - cepilomenu.offsetHeight  
   else  
      mimenu.style.top = document.body.scrollTop + e.clientY  
  
   mimenu.style.visibility = "visible"  
      
   return false   
}  

function visibilidad(){   
  
   if (document.getElementById) {   
      mimenu = document.getElementById("cepilomenu")   
   }else if (document.all) {   
      mimenu = document.all.cepilomenu   
   }   
  
   mimenu.style.visibility = "hidden" 
   document.oncontextmenu = "e";
  
}   

document.onclick = visibilidad

function openmenuper(){
	document.getElementById("cepilomenu").innerHTML = "hola";
	document.oncontextmenu = sombra;
}

</script>
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
<link href="css/style.css" rel="stylesheet" type="text/css">
<script language=JavaScript src='Scripts/innovaeditor.js'></script>
<script type='text/javascript' src='gyl_menu.js'></script>
</head>
<script type='text/javascript'> 
var empezar = false
var e = "0";
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
//var capas = new Array("e1")
var retardo 
var ocultar

function ct(g) {
	if(g != "" && g != "0") {
		document.getElementById(g).style.visibility='hidden';
	}
}
 
function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}

function muestra_coloca(capa){
	ct(e);
	clearTimeout(retardo)
	xShow(capa)
	e=capa;
}
 
function oculta_retarda(capa){
	ocultar =capa
	clearTimeout(retardo)
	retardo = setTimeout("xHide('" + ocultar + "')",1000)
}
 
function muestra_retarda(ind){
	clearTimeout(retardo)
}
</script>
<script language="javascript">
	function statusJS() {
		window.parent.parent.frameestados.location='nivdoc_estados.php?cod=<?php echo $_GET['cod']; ?>';
	}
</script>
<body onLoad="statusJS();">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <!-- <tr>
    <td>Estado: <?php echo $row_estado_registral['estado']; ?>  - <?php echo $_SESSION['estado']."--".$_SESSION['na']; ?> - <?php echo $row_vis_niveles['tipo_nivel']; ?></td>
  </tr>-->
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
    <div id="f1c" style="display:<?php if($_SESSION['estado'] != 1 && $_SESSION['na'] != 1) { echo "block"; } else { echo "none"; } ?>">
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
    <div id="f1" style="display:<?php if($_SESSION['estado'] == 1 || $_SESSION['na'] == 1) { echo "block"; } else { echo "none"; } ?>">
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
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v23; ?><!-- C&oacute;digo de Referencia de La Instituci&oacute;n--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor" id="codigo_institucion"><input name="codigo_institucion" type="text" class="camposanchos" id="codigo_institucion" readonly value="<?php echo $row_vis_niveles['codigo_institucion']; ?>" /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v24; ?><!-- C&oacute;digo de Referencia del Nivel:--></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="codigo_referencia" type="text" class="camposanchos" id="codigo_referencia" readonly value="<?php echo $row_vis_niveles['codigo_referencia']; ?>" /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v213; ?><!--     	            Tipo de Nivel:--></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><select name="tipo_nivel_text" class="camposanchos" id="tipo_nivel_text">
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_niveles_tipos['idnivel_tipo']?>"<?php if (!(strcmp($row_niveles_tipos['idnivel_tipo'], $row_vis_niveles['tipo_nivel']))) {echo "selected=\"selected\"";} ?>><?php echo $row_niveles_tipos['tipo']?></option>
    	              <?php
} while ($row_niveles_tipos = mysql_fetch_assoc($niveles_tipos));
  $rows = mysql_num_rows($niveles_tipos);
  if($rows > 0) {
      mysql_data_seek($niveles_tipos, 0);
	  $row_niveles_tipos = mysql_fetch_assoc($niveles_tipos);
  }
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v25; ?><!-- T&iacute;tulo Original:--></td>
  	          </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><input name="titulo_original" type="text" class="camposanchos" id="titulo_original"  value="<?php echo $row_vis_niveles['titulo_original']; ?>" /></td>
                  </tr>
                  <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v26; ?><!-- T&iacute;tulo Atribuido:--></td>
  	          </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><input name="titulo_atribuido" type="text" class="camposanchos" id="titulo_atribuido"  value="<?php echo $row_vis_niveles['titulo_atribuido']; ?>" /></td>
                  </tr>
                  <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v27; ?><!-- T&iacute;tulo Traducido:--></td>
  	          </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><input name="titulo_traducido" type="text" class="camposanchos" id="titulo_traducido"  value="<?php echo $row_vis_niveles['titulo_traducido']; ?>" /></td>
                  </tr>
                  <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v28; ?><!-- Fecha de registro:--></td>
  	          </tr>
                  <tr>
                     <td class="ins_celdacolor camposmedios">D&iacute;a:
                         <input name="fecha_registrod" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_registro'],8,2); ?>">
Mes:
<input name="fecha_registrom" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_registro'],5,2); ?>">
A&ntilde;o:
<input name="fecha_registroa" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_vis_niveles['fecha_registro'],0,4); ?>">
(dd/mm/aaaa)</td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v29; ?><!-- N&uacute;meros de inventario:--></td>
  	          </tr>
                  <tr>
                     <td class="ins_celdacolor">
                     <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_numeros_inventarios_reales['numero_inventario_unidad_documental'].", "; ?>
                          <?php } while ($row_numeros_inventarios_reales = mysql_fetch_assoc($numeros_inventarios_reales)); ?></td>
                       </tr>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php do { ?>
                       <?php if($totalRows_vis_numerosInventarios > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_vis_numerosInventarios['numero_inventario']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?>
                             <?php if($totalRows_vis_numerosInventarios >= 1) { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'1','val1':'<?php echo $row_vis_numerosInventarios['numero_inventario']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?>
                           <?php } ?></td>
                         </tr>
                         <?php } while ($row_vis_numerosInventarios = mysql_fetch_assoc($vis_numerosInventarios)); ?>
                     </table></td>
                  </tr>
                  <tr>
                  	<td>
                    <div id="dv_aux_c_1">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><a onClick="abriraux(1);"><img src="images/bt_008.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_1"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;N&uacute;mero de Inventario:</td>
    	                        <td width="484" class="ins_separadormayor"><span class="ins_tituloscampos">
    	                          <input name="numero_inventario" type="text" class="camposmedioscbt" id="numero_inventario" value="">
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio">
    	                          </span></td>
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
                  	<td align="right"><a onClick="cerraraux(1);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                     </div></td>
                  </tr>
                  <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v30; ?><!-- N&uacute;meros de registro inventario anterior:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><input name="numero_registro_inventario_anterior" type="text" class="camposanchos" id="numero_registro_inventario_anterior"  value="<?php echo $row_vis_niveles['numero_registro_inventario_anterior']; ?>" /></td>
                  </tr>
                  <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
                    <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v31; ?><!-- Fondo tesoro:--></td>
  	          </tr>
                <td  class="tituloscampos ins_celdacolor">SI:
                  <input <?php if (!(strcmp($row_vis_niveles['fondo_tesoro_sn'], chr(0x01)))) {echo "checked=\"checked\"";} ?> type="radio" name="fondo_tesoro_sn" id="fondo_tesoro_sn1" value="1" onClick="fondotesoro(1);"> 
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No:
                  <input <?php if($row_vis_niveles['fondo_tesoro_sn'] == chr(0x00) || $row_vis_niveles['fondo_tesoro_sn'] == NULL) { echo "checked=\"checked\"";}  ?> name="fondo_tesoro_sn" type="radio" id="fondo_tesoro_sn0" value="0" onClick="fondotesoro(0);"></td>
              <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><div id="tbfondotesoro" style="display:<?php if($row_vis_niveles['fondo_tesoro_sn'] ==  chr(0x01)) { echo "block"; } else { echo "none"; } ?>;"><textarea name="fondo_tesoro" rows="7" class="camposanchos" id="fondo_tesoro"><?php echo $row_vis_niveles['fondo_tesoro']; ?></textarea></div></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><?php echo $v32; ?><!-- Signatura Topogr&aacute;fica--> <input name="signaturaTopograficaCant" type="hidden" id="signaturaTopograficaCant" value="<?php echo $totalRows_vis_signaturaTopografica;  ?>"></td>
                  </tr>
                   <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_vis_signaturaTopografica > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                       <tr>
                         <td width="543" class="ins_celdtadirecciones " ><?php echo $row_vis_signaturaTopografica['nombre_edificio']; ?>,<br><?php echo $row_vis_signaturaTopografica['ubicacion_topografica']; ?></td>
                         <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_vis_signaturaTopografica >= 1) { ?>
                           <a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'2','val1':'<?php echo $row_vis_signaturaTopografica['nombre_edificio']; ?>','val2':'<?php echo $row_vis_signaturaTopografica['ubicacion_topografica']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
                           <?php } else { ?>
                           &nbsp;
                           <?php } ?><?php } ?></td>
                       </tr>
                       <?php } while ($row_vis_signaturaTopografica = mysql_fetch_assoc($vis_signaturaTopografica)); ?>
                     </table>
                     </td>
                  </tr>
                  <tr>
                  	<td>
                    <div id="dv_aux_c_2">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><a onClick="abriraux(2);"><img src="images/bt_006.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_2"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Edificio:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="nombre_edificio" id="nombre_edificio" class="camposmedios">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_vis_edificios['nombre_edificio']?>"><?php echo $row_vis_edificios['nombre_edificio']?></option>
    	                          <?php
} while ($row_vis_edificios = mysql_fetch_assoc($vis_edificios));
  $rows = mysql_num_rows($vis_edificios);
  if($rows > 0) {
      mysql_data_seek($vis_edificios, 0);
	  $row_vis_edificios = mysql_fetch_assoc($vis_edificios);
  }
?>
                                </select></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos" >&nbsp;&nbsp;Ubicaci&oacute;n:</td>
    	                        <td class="ins_separadormayor"><span class="ins_tituloscampos">
    	                          <input name="ubicacion_topografica" type="text" class="camposmedioscbt" id="ubicacion_topografica" value="">
    	                          <input type="submit" name="button9" id="button9" value="G" class="btonmedio">
    	                        </span></td>
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
                  	<td align="right"><a onClick="cerraraux(2);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                     </div>
                    </td>
					<tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td class="tituloscampos ins_celdacolor"><?php echo $v33; ?><!-- Contenedores:--><input name="niveles_contenedores" type="hidden" id="niveles_contenedores" value="<?php echo $totalRows_vis_niveles_contenedores;  ?>"></td>
                  </tr>
                   <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_vis_niveles_contenedores > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                       <tr>
                         <td width="543" class="ins_celdtadirecciones " ><?php echo $row_vis_niveles_contenedores['contenedor']; ?>, <?php echo $row_vis_niveles_contenedores['ruta_acceso']; ?></td>
                         <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_vis_signaturaTopografica >= 1) { ?>
                           <a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'3','val1':'<?php echo $row_vis_niveles_contenedores['contenedor']; ?>','val2':'<?php echo $row_vis_niveles_contenedores['ruta_acceso']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
                           <?php } else { ?>
                           &nbsp;
                           <?php } ?><?php } ?></td>
                       </tr>
                       <?php } while ($row_vis_niveles_contenedores = mysql_fetch_assoc($vis_niveles_contenedores)); ?>
                     </table></td>
                  </tr>
                  <tr>
                  	<td>
                    	<div id="dv_aux_c_3">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><a onClick="abriraux(3);"><img src="images/bt_007.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_3"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Contenedor:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="contenedor" id="contenedor" class="camposmedios">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_vis_contenedores['contenedor']?>"><?php echo $row_vis_contenedores['contenedor']?></option>
    	                          <?php
} while ($row_vis_contenedores = mysql_fetch_assoc($vis_contenedores));
  $rows = mysql_num_rows($vis_contenedores);
  if($rows > 0) {
      mysql_data_seek($vis_contenedores, 0);
	  $row_vis_contenedores = mysql_fetch_assoc($vis_contenedores);
  }
?>
                                </select></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos" >&nbsp;&nbsp;Ruta de acceso:</td>
    	                        <td class="ins_separadormayor"><span class="ins_tituloscampos">
    	                          <input name="ruta_acceso" type="text" class="camposmedioscbt" id="ruta_acceso" value="" >
    	                          <input type="submit" name="button8" id="button8" value="G" class="btonmedio">
    	                        </span></td>
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
                  	<td align="right"><a onClick="cerraraux(3);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                     </div>
                    </td>
                  </tr>
    	        <tr>
    	          <td>&nbsp;</td>
  	          </tr>
  	          </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input onClick="activar();" name="button" type="submit" class="botongrabar" id="button" value="Grabar" <?php if($s1=="C" || $_SESSION['situacion'] == "Baja") { echo "disabled"; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
  	    </table>
    	  <input type="hidden" name="MM_update" value="form1">
    	  <input type="hidden" name="MM_update" value="form1">
        </form>
    </div>
    <?php } ?>
    </td>
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
    <div id="f2c" style="display:<?php if($_SESSION['estado'] != 2 && $_SESSION['na'] != 2) { echo "block"; } else { echo "none"; } ?>">
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
    <div id="f2" style="display:<?php if($_SESSION['estado'] == 2 || $_SESSION['na'] == 2) { echo "block"; } else { echo "none"; } ?>">
    <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE DESCRIPCI&Oacute;N</td>
    	          <td align="right"><a onClick="cerrarformularios(2)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
          </tr>
          <TR>
          <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v34; ?><!-- Nombre del productor/es--><input name="rel_niveles_productoresCant" type="hidden" id="rel_niveles_productoresCant" value="<?php echo $totalRows_rel_niveles_productores;  ?>"></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_niveles_productores > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_productores['nombre_productor']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'4','val1':'<?php echo $row_rel_niveles_productores['nombre_productor']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_niveles_productores = mysql_fetch_assoc($rel_niveles_productores)); ?>
                     </table></td>
                  </tr>
                  <tr>
                  	<td>
                    <div id="dv_aux_c_4">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(4);"><img src="images/bt_012.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_4"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Productor:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="nombre_productor" id="nombre_productor" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_registro_autoridad['nombre_productor']; ?>"><?php echo $row_registro_autoridad['nombre_productor']; ?></option>
    	                          <?php
} while ($row_registro_autoridad = mysql_fetch_assoc($registro_autoridad));
  $rows = mysql_num_rows($registro_autoridad);
  if($rows > 0) {
      mysql_data_seek($registro_autoridad, 0);
	  $row_registro_autoridad = mysql_fetch_assoc($registro_autoridad);
  }
?>
    	                         
	                          
                                </select>
    	                          <input type="submit" name="button7" id="button7" value="G" class="btonmedio"></td>
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
                  	<td align="right"><a onClick="cerraraux(4);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
             <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v35; ?><!-- Fecha Inicial:--></td>
                  </tr>
                         <td class="ins_celdacolor camposmedios">D&iacute;a:
                           <input name="fecha_iniciald" type="text" id="fecha_iniciald" value="<?php echo substr($row_vis_niveles['fecha_inicial'],8,2); ?>" size="8" maxlength="2"> 
                           Mes:
                           <input name="fecha_inicialm" type="text" id="fecha_inicialm" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_inicial'],5,2); ?>"> 
                           A&ntilde;o:
                         <input name="fecha_iniciala" type="text" id="fecha_iniciala" size="12" maxlength="4" value="<?php echo substr($row_vis_niveles['fecha_inicial'],0,4); ?>"> 
                         (dd/mm/aaaa)</td>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v36; ?><!-- Fecha Final:--></td>
                  </tr>
                     <td class="ins_celdacolor"><span class="ins_celdacolor camposmedios">D&iacute;a:
                           <input name="fecha_finald" type="text" id="fecha_finald" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_final'],8,2); ?>">
Mes:
<input name="fecha_finalm" type="text" id="textfield5" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_final'],5,2); ?>">
A&ntilde;o:
<input name="fecha_finala" type="text" id="textfield6" size="12" maxlength="4" value="<?php echo substr($row_vis_niveles['fecha_final'],0,4); ?>">
(dd/mm/aaaa)</span></td>
                <tr>
                   <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v37; ?><!-- Alcance de contenidos:--></td>
                  </tr>
                     <td class="ins_celdacolor"><textarea name="alcance_contenido" rows="10" class="camposanchos" id="alcance_contenido"><?php echo $row_vis_niveles['alcance_contenido']; ?></textarea><script>
    var oEdit1 = new InnovaEditor("oEdit1");

    oEdit1.width=630;
    oEdit1.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit1.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit1.useTab = true;

    oEdit1.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit1.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s2!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit1.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit1.css="style/test.css";//Specify external css file here

    oEdit1.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit1.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit1.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit1.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit1.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit1.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit1.REPLACE("alcance_contenido");

  </script></td>
                <tr>
                    <td class="separadormayor"></td>
                </tr>
                  <tr>
                    <td><table width="630" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                          <td width="196" height="30" class="niv_menu">Caracter&iacute;sticas f&iacute;sicas y t&eacute;cnicas</td>
                          <td width="434"><hr></td>
                      </tr>
                    </table></td>
                </tr>
                  <tr>
                    <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v38; ?><!-- Soporte:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                     <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_doc_soportes['soporte'].", "; ?>
                          <?php } while ($row_doc_soportes = mysql_fetch_assoc($doc_soportes)); ?></td>
                       </tr>
                       <?php do { ?>
                      
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_soportes['soporte']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'5','val1':'<?php echo $row_rel_niveles_soportes['soporte']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_niveles_soportes = mysql_fetch_assoc($rel_niveles_soportes)); ?>
                     </table></td>
                  </tr>
                  <tr>
                  <td>
                 <div id="dv_aux_c_5">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(5);"><img src="images/bt_009.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_5"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Soportes:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="soporte" id="soporte" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_soportes['soporte']?>"><?php echo $row_soportes['soporte']?></option>
    	                          <?php
} while ($row_soportes = mysql_fetch_assoc($soportes));
  $rows = mysql_num_rows($soportes);
  if($rows > 0) {
      mysql_data_seek($soportes, 0);
	  $row_soportes = mysql_fetch_assoc($soportes);
  }
?>
    	                         
	                          
                                </select>
    	                          <input type="submit" name="button6" id="button6" value="G" class="btonmedio"></td>
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
                  	<td align="right"><a onClick="cerraraux(5);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
             <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v39; ?><!-- Idiomas:--></td>
                  </tr>
                       <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_doc_idiomas['soporte'].", "; ?>
                          <?php } while ($row_doc_idiomas = mysql_fetch_assoc($doc_idiomas)); ?></td>
                       </tr>
                         <?php do { ?>
                        
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                      
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_idiomas['idioma']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'6','val1':'<?php echo $row_rel_niveles_idiomas['idioma']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                           <?php } while ($row_rel_niveles_idiomas = mysql_fetch_assoc($rel_niveles_idiomas)); ?>
                       </table></td>
                  <tr>
                  	<td>
                    <div id="dv_aux_c_6">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(6);"><img src="images/bt_011.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_6"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Idiomas:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="idioma" id="idioma" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_idiomas['idioma']?>"><?php echo $row_idiomas['idioma']?></option>
    	                          <?php
} while ($row_idiomas = mysql_fetch_assoc($idiomas));
  $rows = mysql_num_rows($idiomas);
  if($rows > 0) {
      mysql_data_seek($idiomas, 0);
	  $row_idiomas = mysql_fetch_assoc($idiomas);
  }
?>
    	                         
	                          
                                </select>
    	                          <input type="submit" name="button5" id="button5" value="G" class="btonmedio"></td>
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
                  	<td align="right"><a onClick="cerraraux(6);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
             <tr>
                     <td class="separadormayor"><table width="630" border="0" cellspacing="0" cellpadding="0">
                       <tr>
                         <td width="66" height="30" class="niv_menu">Vol&uacute;men</td>
                         <td width="564"><hr></td>
                       </tr>
                     </table></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v40; ?><!-- Metros lineales:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><input name="metros_lineales" type="text" class="camposanchos" id="metros_lineales" value="<?php echo $row_vis_niveles['metros_lineales']; ?>" maxlength="50"  /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v41; ?><!-- Unidades:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><input name="unidades" type="text" class="camposanchos" id="unidades" value="<?php echo $row_vis_niveles['unidades']; ?>" maxlength="50"  /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"><table width="630" border="0" cellspacing="0" cellpadding="0">
                       <tr>
                         <td height="30"><hr></td>
                       </tr>
                     </table></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v42; ?><!-- Historia Institucional y/o biogr&aacute;fica del productor del fondo y/o nivel:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="historia_institucional_productor" rows="10" class="camposanchos" id="historia_institucional_productor"><?php echo $row_vis_niveles['historia_institucional_productor']; ?></textarea><script>
    var oEdit2 = new InnovaEditor("oEdit2");

    oEdit2.width=630;
    oEdit2.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit2.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit2.useTab = true;

    oEdit2.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit2.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s2!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit2.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit2.css="style/test.css";//Specify external css file here

    oEdit2.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit2.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit2.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit2.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit2.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit2.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit2.REPLACE("historia_institucional_productor");

  </script></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v43; ?><!-- Historia Archiv&iacute;stica:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="historia_archivistica" rows="10" class="camposanchos" id="historia_archivistica"><?php echo $row_vis_niveles['historia_archivistica']; ?></textarea><script>
    var oEdit3 = new InnovaEditor("oEdit3");

    oEdit3.width=630;
    oEdit3.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit3.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit3.useTab = true;

    oEdit3.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit3.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s2!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit3.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit3.css="style/test.css";//Specify external css file here

    oEdit3.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit3.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit3.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit3.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit3.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit3.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit3.REPLACE("historia_archivistica");

  </script></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v44; ?><!-- Vinculaciones entre niveles:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_niveles_niveles > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                        <tr>
                          <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_niveles['codigo_referencia2']; ?><br><?php echo $row_rel_niveles_niveles['descripcion_vinculo']; ?></td>
                          <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'7','val1':'<?php echo $row_rel_niveles_niveles['descripcion_vinculo']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                        </tr>
                         <?php } while ($row_rel_niveles_niveles = mysql_fetch_assoc($rel_niveles_niveles)); ?>
                    </table></td>
                  </tr>
                  <tr>
                  	<td>
                   	  <div id="dv_aux_c_7">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(7);"><img src="images/bt_010.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_7"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Nivel (2):</td>
    	                        <td width="484" class="ins_separadormayor"><select name="codigo_referencia2" id="codigo_referencia2" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_cbo_niveles['codigo_referencia']?>"><?php echo $row_cbo_niveles['codigo_referencia']?></option>
    	                            <?php
} while ($row_cbo_niveles = mysql_fetch_assoc($cbo_niveles));
  $rows = mysql_num_rows($cbo_niveles);
  if($rows > 0) {
      mysql_data_seek($cbo_niveles, 0);
	  $row_cbo_niveles = mysql_fetch_assoc($cbo_niveles);
  }
?>
                                </select>
    	                          <span class="ins_tituloscampos">
    	                          <input type="submit" name="button4" id="button4" value="G" class="btonmedio">
    	                          </span></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Descripcion</td>
    	                        <td class="ins_separadormayor"><span class="ins_tituloscampos">
    	                          <textarea name="descripcion_vinculo" rows="2" class="camposmedios" id="descripcion_vinculo"></textarea>
    	                        </span></td>
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
                  	<td align="right"><a onClick="cerraraux(7);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                     </div>
                    </td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v45; ?><!-- Vinculaciones con Instituciones Internas:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_niveles_institucionesinternas > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_institucionesinternas['codigo_referencia_institucion_interna']; ?><br>
                             <?php echo $row_rel_niveles_institucionesinternas['descripcion']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'8','val1':'<?php echo $row_rel_niveles_institucionesinternas['codigo_referencia_institucion_interna']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_niveles_institucionesinternas = mysql_fetch_assoc($rel_niveles_institucionesinternas)); ?>
                    </table></td>
                  </tr>
                  <tr>
                  	<td>
                    <div id="dv_aux_c_8">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(8);"><img src="images/bt_010.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_8"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Instituci&oacute;n Interna:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="codigo_referencia_institucion_interna" id="codigo_referencia_institucion_interna" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_instituciones['codigo_identificacion']?>"><?php if($row_instituciones['formas_conocidas_nombre'] != "") { echo $row_instituciones['formas_conocidas_nombre']; } else { echo $row_instituciones['codigo_identificacion']; } ?></option>
    	                          <?php
} while ($row_instituciones = mysql_fetch_assoc($instituciones));
  $rows = mysql_num_rows($instituciones);
  if($rows > 0) {
      mysql_data_seek($instituciones, 0);
	  $row_instituciones = mysql_fetch_assoc($instituciones);
  }
?>
    	                         
	                          
                                </select>
    	                          <input type="submit" name="button3" id="button3" value="G" class="btonmedio"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Descripci&oacute;n</td>
    	                        <td class="ins_separadormayor"><textarea name="descripcion" rows="2" class="camposmedios" id="descripcion"></textarea></td>
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
                  	<td align="right"><a onClick="cerraraux(8);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                     </div>
                    </td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v46; ?>Vinculaciones con Instituciones Externas:</td>
                  </tr>
                  <tr>
                    <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_niveles_institucionesexternas > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_institucionesexternas['codigo_referencia_institucion_externa']; ?><br>
                             <?php echo $row_rel_niveles_institucionesexternas['descripcion']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'9','val1':'<?php echo $row_rel_niveles_institucionesexternas['codigo_referencia_institucion_externa']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_niveles_institucionesexternas = mysql_fetch_assoc($rel_niveles_institucionesexternas)); ?>
                    </table></td>
                  </tr>
                  <tr>
                  	<td>
                   <div id="dv_aux_c_9">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(9);"><img src="images/bt_010.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_9"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Instituci&oacute;n Externa:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="codigo_referencia_institucion_externa" id="codigo_referencia_institucion_externa" class="camposmedioscbt">
                                <option value="">Seleccione Opción</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_instituciones_externas['codigo_referencia_intitucion_externa']?>"><?php echo $row_instituciones_externas['nombre_autorizado']?></option>
    	                          <?php
} while ($row_instituciones_externas = mysql_fetch_assoc($instituciones_externas));
  $rows = mysql_num_rows($instituciones_externas);
  if($rows > 0) {
      mysql_data_seek($instituciones_externas, 0);
	  $row_instituciones_externas = mysql_fetch_assoc($instituciones_externas);
  }
?>
    	                         
	                          
                                </select>
    	                          <input type="submit" name="button2" id="button2" value="G" class="btonmedio"></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Descripci&oacute;n:</td>
    	                        <td class="ins_separadormayor"><textarea name="descripcion2" rows="2" class="camposmedios" id="descripcion2"></textarea></td>
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
                  	<td align="right"><a onClick="cerraraux(9);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
                  </tr>
                  <tr>
                    <td >&nbsp;</td>
                  </tr>    
          </table></td>
          </TR>
          <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input onClick="activar();" name="button" type="submit" class="botongrabar" id="button" value="Grabar" <?php if($s2=="C" || $_SESSION['situacion'] == "Baja") { echo "disabled"; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
    </table>
    <span class="ins_celdacolor">
    <input name="codigo_referencia_a_2" type="hidden" class="camposanchos" id="codigo_referencia_a_2" readonly value="<?php echo $row_vis_niveles['codigo_referencia']; ?>" />
    </span>
    <input type="hidden" name="MM_update" value="form2">
    </form>
    </div>
    <?php } ?></td>
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
              <td class="ins_titulomayordes">&Aacute;REA DE ADMINISTRACI&Oacute;N</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php  } else { ?>
    <div id="f3c" style="display:<?php if($_SESSION['estado'] != 3 && $_SESSION['na'] != 3) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">&Aacute;REA DE ADMINISTRACI&Oacute;N</td>
              <td align="right"><a onClick="abrirformularios(3);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f3" style="display:<?php if($_SESSION['estado'] == 3 || $_SESSION['na'] == 3) { echo "block"; } else { echo "none"; } ?>">
    <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE ADMINISTRACI&Oacute;N </td>
    	          <td align="right"><a onClick="cerrarformularios(3)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
          </tr>
          <TR>
          <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v47; ?><!-- Sistema de organizaci&oacute;n:--></td>
                  </tr>
                  <tr>
                  	<td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_sistema_organizacion_real['sistema_organizacion'].", "; ?>
                          <?php } while ($row_sistema_organizacion_real = mysql_fetch_assoc($sistema_organizacion_real)); ?></td>
                       </tr>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php do { ?>
                       <?php if($totalRows_rel_niveles_sistemasorganizacion > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_niveles_sistemasorganizacion['sistema_organizacion']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s3!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'9','val1':'<?php echo$row_rel_niveles_sistemasorganizacion['sistema_organizacion']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_niveles_sistemasorganizacion = mysql_fetch_assoc($rel_niveles_sistemasorganizacion)); ?>
                     </table></td>
                  </tr>
                   <tr>
                  	<td>
                    <div id="dv_aux_c_10">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s3!="C") { ?><a onClick="abriraux(10);"><img src="images/bt_013.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_10" style="display:none;">
                     <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td>&nbsp;</td></tr></table>
                    <table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
                  	  <tr>
                  	    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                  	      <tr>
                  	        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Sist. de Organizaci&oacute;n:</td>
                  	        <td width="484" class="ins_tituloscampos"><select name="sistema_organizacion" id="sistema_organizacion" class="camposmedioscbt">
                            <option value="">Seleccione Opción</option>
                  	          <?php
do {  
?>
                  	          <option value="<?php echo $row_sistemas_organizaciones['sistema_organizacion']?>"><?php echo $row_sistemas_organizaciones['sistema_organizacion']?></option>
                  	          <?php
} while ($row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones));
  $rows = mysql_num_rows($sistemas_organizaciones);
  if($rows > 0) {
      mysql_data_seek($sistemas_organizaciones, 0);
	  $row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones);
  }
?>
                	          </select>
                  	          <input type="submit" name="button11" id="button11" value="G" class="btonmedio"></td>
                	        </tr>
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                	      </table></td>
               	      </tr>
               	     </table> 
                     <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="cerraraux(10);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table></div></td>
                  </tr>
                   <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v48; ?><!-- Forma de ingreso:--></td>
                  </tr>
                  <tr>
                  	<td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_forma_ingreso_real['forma_ingreso'].", "; ?>
                          <?php } while ($row_forma_ingreso_real = mysql_fetch_assoc($forma_ingreso_real)); ?></td>
                       </tr>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                      </table>
                     </td>
                   </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="forma_ingreso" rows="5" class="camposanchos" id="forma_ingreso"><?php echo $row_vis_niveles['forma_ingreso']; ?></textarea></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v49; ?><!-- Procedencia:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="procedencia" rows="5" class="camposanchos" id="procedencia"><?php echo $row_vis_niveles['procedencia']; ?></textarea></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v50; ?><!-- Fecha de inicio del ingreso:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><span class="ins_celdacolor camposmedios">D&iacute;a:
                         <input name="fecha_inicio_ingresod" type="text" id="fecha_inicio_ingresod" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_inicio_ingreso'],6,2); ?>">
Mes:
<input name="fecha_inicio_ingresom" type="text" id="fecha_inicio_ingresom" size="8" maxlength="2" value="<?php echo substr($row_vis_niveles['fecha_inicio_ingreso'],4,2); ?>">
A&ntilde;o:
<input name="fecha_inicio_ingresoa" type="text" id="textfield2" size="12" maxlength="4" value="<?php echo substr($row_vis_niveles['fecha_inicio_ingreso'],0,4); ?>">
(dd/mm/aaaa)</span></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v51; ?><!-- Precio:--></td>
                  </tr>
                  <tr>
                  	<td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <tr>
                           <td width="543" height="18" colspan="2" class="ins_celdtadirecciones " ><?php do { ?>
                              <?php echo $row_precio_real['precio'].", "; ?>
                          <?php } while ($row_precio_real = mysql_fetch_assoc($precio_real)); ?></td>
                       </tr>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                      </table>
                     </td>
                   </tr>
                   <tr>
                     <td class="ins_celdacolor"><input name="precio" type="text" class="camposanchos" id="precio" value="<?php echo $row_vis_niveles['precio']; ?>" maxlength="255"  /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v52; ?><!-- Norma Administrativa:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="norma_legal_ingreso" rows="5"  class="camposanchos" id="norma_legal_ingreso" maxlength="255" ><?php echo $row_vis_niveles['norma_legal_ingreso']; ?></textarea></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v53; ?><!-- N&uacute;mero Administrativo:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><input name="numero_administrativo" type="text" class="camposanchos" id="numero_administrativo" value="<?php echo $row_vis_niveles['numero_administrativo']; ?>"  /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v54; ?><!-- Derechos/Restricciones:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="derechos_restricciones" rows="5"  class="camposanchos" id="derechos_restricciones"><?php echo $row_vis_niveles['derechos_restricciones']; ?></textarea></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v55; ?><!-- Titular de Derechos:--></td>
                  </tr>
                   <tr>
                     <td class="ins_celdacolor"><input name="titular_derecho" type="text" class="camposanchos" id="titular_derecho" value="<?php echo $row_vis_niveles['titular_derecho']; ?>"  /></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                   <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v56; ?><!-- Tasaci&oacute;n expertizaje:--></td>
                  </tr>
                  <tr>
                  	<td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_niveles_tasacion_expertizaje > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php if($totalRows_niveles_tasacion_expertizaje >= 1) { ?><?php echo desfechar($row_niveles_tasacion_expertizaje['fecha']); ?>, [ val : <?php echo $row_niveles_tasacion_expertizaje['valuacion']; ?> ], [ val U$S: <?php echo $row_niveles_tasacion_expertizaje['valuacion_usd']; ?> ]<br>Tasador:<?php echo $row_niveles_tasacion_expertizaje['tasador_experto']; ?><br>
                             Comentario: <?php echo $row_niveles_tasacion_expertizaje['comentario_expertizaje']; ?><?php } ?></td>
                           <td width="24" align="center" valign="top" class="ins_celdtadirecciones " ><?php if($s3!="C") { ?><a onDblClick="relocate('niveles_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elm':'3','val1':'<?php echo $row_niveles_tasacion_expertizaje['valuacion']; ?>','val2':'<?php echo $row_niveles_tasacion_expertizaje['fecha']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
                         </tr>
                         <?php } while ($row_niveles_tasacion_expertizaje = mysql_fetch_assoc($niveles_tasacion_expertizaje)); ?>
                     </table></td>
                  </tr>
                   <tr>
                  	<td>
                    <div id="dv_aux_c_11">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s3!="C") { ?><a onClick="abriraux(11);"><img src="images/bt_014.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                    </div>
                    <div id="dv_aux_a_11" style="display:none;">
                     <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td>&nbsp;</td></tr></table>
                    <table width="630" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
                  	  <tr>
                  	    <td class="ins_tabladirecciones"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                  	      <tr>
                  	        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Valuaci&oacute;n:</td>
                  	        <td width="484" class="ins_tituloscampos"><span class="ins_separadormayor">
                  	          <input name="valuacion" type="text" class="camposmedioscbt" id="valuacion" value="" maxlength="255" >
                  	        </span>
                  	          <input type="submit" name="button12" id="button12" value="G" class="btonmedio"></td>
                	        </tr>
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                             <tr>
                  	        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Valuaci&oacute;n en U$S:</td>
                  	        <td width="484" class="ins_tituloscampos"><span class="ins_separadormayor">
                  	          <input name="valuacion_usd" type="text" class="camposmedios" id="valuacion_usd" value="" maxlength="255">
                  	        </span></td>
                	        </tr>
                            <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                             <tr>
                  	        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Fecha Tasaci&oacute;n:</td>
                  	        <td width="484" class="ins_tituloscampos">D&iacute;a:
                                <input name="fechatasaciond" type="text" id="fechatasaciond" size="8" maxlength="2">
Mes:
<input name="fechatasacionm" type="text" id="fechatasacionm" size="8" maxlength="2">
A&ntilde;o:
<input name="fechatasaciona" type="text" id="fechatasaciona" size="12" maxlength="4">
(dd/mm/aaaa)</td>
                	        </tr>
                            <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                             <tr>
                  	        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Tasador Experto:</td>
                  	        <td width="484" class="ins_tituloscampos"><span class="ins_separadormayor">
                  	          <input name="tasador_experto" type="text" class="camposmedios" id="tasador_experto" value="" maxlength="255">
                  	        </span></td>
                	        </tr>
                            
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                            
                  	      <tr>
                  	        <td class="ins_tituloscampos">&nbsp;&nbsp;Comentario:</td>
                  	        <td class="ins_separadormayor"><textarea name="comentario_expertizaje" rows="2" class="camposmedios" id="comentario_expertizaje" maxlength="255"></textarea></td>
                	        </tr>
                  	      <tr>
                  	        <td colspan="2" class="ins_separadormayor"></td>
                	        </tr>
                	      </table></td>
               	      </tr>
               	     </table>
                     <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                  	<td align="right"><a onClick="cerraraux(11);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table></div></td>
                  </tr>
                   <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v57; ?><!-- Tipo de Acceso:--></td>
                  </tr>
                   <tr>
                  	<td class="ins_celdacolor"><textarea name="tipo_acceso" rows="3" class="camposanchos" id="tipo_acceso" maxlength="255"><?php echo $row_vis_niveles['tipo_acceso']; ?></textarea></td>
                  </tr>
                   <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v58; ?><!-- Requisitos de Acceso:--></td>
                  </tr>
                   <tr>
                  	<td class="ins_celdacolor"><textarea name="requisito_acceso" rows="3" class="camposanchos" id="requisito_acceso" maxlength="255"><?php echo $row_vis_niveles['requisito_acceso']; ?></textarea></td>
                  </tr>
                   <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v59; ?><!-- Acceso a Documentaci&oacute;n:--></td>
                  </tr>
                   <tr>
                  	<td class="ins_celdacolor"><textarea name="acceso_documentacion" rows="3" class="camposanchos" id="acceso_documentacion" maxlength="255"><?php echo $row_vis_niveles['acceso_documentacion']; ?></textarea></td>
                  </tr>
                   <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v60; ?><!-- Publicaciones e instrumentos de acceso:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="publicaciones_acceso" rows="10" class="camposanchos" id="publicaciones_acceso"><?php echo $row_vis_niveles['publicaciones_acceso']; ?></textarea><script>
    var oEdit4 = new InnovaEditor("oEdit4");

    oEdit4.width=630;
    oEdit4.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit4.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit4.useTab = true;

    oEdit4.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit4.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s3!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit4.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit4.css="style/test.css";//Specify external css file here

    oEdit4.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit4.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit4.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit4.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit4.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit4.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit4.REPLACE("publicaciones_acceso");

  </script></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v61; ?><!-- Subsidios otorgados a la instituci&oacute;n:--></td>
                  </tr>
                 <tr>
                     <td class="ins_celdacolor"><textarea name="subsidios_otorgados" rows="10" class="camposanchos" id="subsidios_otorgados"><?php echo $row_vis_niveles['subsidios_otorgados']; ?></textarea><script>
    var oEdit5 = new InnovaEditor("oEdit5");

    oEdit5.width=630;
    oEdit5.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit5.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit5.useTab = true;

    oEdit5.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit5.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s3!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit5.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit5.css="style/test.css";//Specify external css file here

    oEdit5.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit5.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit5.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit5.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit5.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit5.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit5.REPLACE("subsidios_otorgados");

  </script></td>
                  </tr>
                  
                  <tr>
    	      <td >&nbsp;</td>
  	      </tr>
<?php if($row_estado_registral['estado'] == "Completo") { ?>
                  <tr>
                    <td ><table width="630" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="66" height="30" class="niv_menu">Baja</td>
                        <td width="564"><hr></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td >&nbsp;</td>
                  </tr>
                  <tr>
                    <td  class="tituloscampos ins_celdacolor"><?php echo $v62; ?><!-- Normativa Legal de Baja:--></td>
                  </tr>
                  <tr>
                    <td class="ins_tituloscampos"><select name="normativa_legal_baja" id="normativa_legal_baja" class="camposanchos"><option value="" <?php if (!(strcmp("", $row_vis_niveles['normativa_legal_baja']))) {echo "selected=\"selected\"";} ?>>Seleccione Opción</option>
                      <?php
do {  
?>
                      <option value="<?php echo $row_norma_legal['norma_legal']?>"<?php if (!(strcmp($row_norma_legal['norma_legal'], $row_vis_niveles['normativa_legal_baja']))) {echo "selected=\"selected\"";} ?>><?php echo $row_norma_legal['norma_legal']?></option>
                      <?php
} while ($row_norma_legal = mysql_fetch_assoc($norma_legal));
  $rows = mysql_num_rows($norma_legal);
  if($rows > 0) {
      mysql_data_seek($norma_legal, 0);
	  $row_norma_legal = mysql_fetch_assoc($norma_legal);
  }
?>
                    </select>
                    
                    </span></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                    <td  class="tituloscampos ins_celdacolor"><?php echo $v63; ?><!-- N&uacute;mero Norma Legal:--></td>
                  </tr>
                  <tr>
                    <td class="ins_tituloscampos"><input name="numero_norma_legal" type="text" class="camposanchos" id="numero_norma_legal" value="" maxlength="50"></td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                    <td  class="tituloscampos ins_celdacolor"><?php echo $v64; ?><!-- Motivo:--></td>
                  </tr>
                  <tr>
                    <td class="ins_celdacolor">
                      <textarea name="motivo" rows="4" class="camposanchos" id="motivo"></textarea>
                    </td>
                  </tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                    <td  class="tituloscampos ins_celdacolor"><?php echo $v65; ?><!-- Fecha Baja:--></td>
                  </tr>
                  <tr>
                    <td class="ins_celdacolor camposmedios">D&iacute;a:
                        <input name="fechabajad" type="text" id="fechabajad" size="8" maxlength="2">
Mes:
<input name="fechabajam" type="text" id="fechabajam" size="8" maxlength="2">
A&ntilde;o:
<input name="fechabajaa" type="text" id="fechabajaa" size="12" maxlength="4">
(dd/mm/aaaa)</td>
                  </tr>
<?php } ?>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
              </table></td>
          </TR>
          <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input onClick="activar();" name="button" type="submit" class="botongrabar" id="button" value="Grabar" <?php if($s3=="C" || $_SESSION['situacion'] == "Baja") { echo "disabled"; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
     </table>
    <span class="ins_celdacolor">
     <input name="codigo_referencia_a_3" type="hidden" class="camposanchos" id="codigo_referencia_a_3" readonly value="<?php echo $row_vis_niveles['codigo_referencia']; ?>" />
     </span>
    <input type="hidden" name="MM_update" value="form3">
    </form>
    </div>
    <?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr><tr>
    <td>
    <?php if($_SESSION['estado'] < 4) { ?>
    <div id="f4d">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayordes">&Aacute;REA DE NOTAS</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php  } else { ?>
    <div id="f4c" style="display:<?php if($_SESSION['estado'] != 4 && $_SESSION['na'] != 4) { echo "block"; } else { echo "none"; } ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_celdatablachica"><table width="610" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="ins_titulomayor">&Aacute;REA DE NOTAS</td>
              <td align="right"><a onClick="abrirformularios(4);"><img src="images/ico_003.png" alt="Ver detalle"  height="18" border="0"></a></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <div id="f4" style="display:<?php if($_SESSION['estado'] == 4 || $_SESSION['na'] == 4) { echo "block"; } else { echo "none"; } ?>">
    <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE NOTAS </td>
    	          <td align="right"><a onClick="cerrarformularios(4)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
          </tr>
          <TR>
          <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
                     <td class="separadormayor"></td>
                  </tr>
                  <tr>
                     <td  class="tituloscampos ins_celdacolor"><?php echo $v66; ?><!-- Notas Descripci&oacute;n:--></td>
                  </tr>
                  <tr>
                     <td class="ins_celdacolor"><textarea name="nota_descripcion" rows="10" class="camposanchos" id="nota_descripcion"><?php echo $row_niveles_areasnotas['nota_descripcion']; ?></textarea>
<script>
    var oEdit6 = new InnovaEditor("oEdit6");

    oEdit6.width=630;
    oEdit6.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit6.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit6.useTab = true;

    oEdit6.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit6.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s4!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit6.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit6.css="style/test.css";//Specify external css file here

    oEdit6.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit6.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit6.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit6.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit6.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit6.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit6.REPLACE("nota_descripcion");

  </script></td>
                  <tr>
                  <tr>
                     <td class="separadormayor"></td>
                  </tr>
                     <tr>
                       <td class="tituloscampos ins_celdacolor"><?php echo $v67; ?><!-- Notas del Archivero--></td>
                     </tr>
                     <tr>
                       <td class="ins_celdacolor"><textarea name="nota_archivero" rows="10" class="camposanchos" id="nota_archivero"><?php echo $row_niveles_areasnotas['nota_archivero']; ?></textarea><script>
    var oEdit7 = new InnovaEditor("oEdit7");

    oEdit7.width=630;
    oEdit7.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit7.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit7.useTab = true;

    oEdit7.tabs=[
    ["tabHome", "Inicio", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objetos", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit7.groups=[
    ["grpEdit", "", ["Undo", "Redo"<?php if($s4!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit7.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit7.css="style/test.css";//Specify external css file here

    oEdit7.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit7.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit7.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit7.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit7.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit7.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit7.REPLACE("nota_archivero");

  </script></td>
  </tr>
                     <tr>
                       <td class="separadormayor"></td>
                     </tr>
                     <tr>
                       <td class="tituloscampos ins_celdacolor"><?php echo $v68; ?><!-- Fuentes--></td>
                       </tr>
                       <tr>
                         <td class="separadormenor ins_celdacolor"><textarea name="fuentes" rows="5"  class="camposanchos" id="fuentes"><?php echo $row_niveles_areasnotas['fuentes']; ?></textarea></td>
                       </tr>
                       <tr>
                         <td class="separadormayor"></td>
                       </tr>
                       <tr>
                         <td class="tituloscampos ins_celdacolor"><?php echo $v69; ?><!-- Fecha Descripci&oacute;n--></td>
                       </tr>
                       <tr>
                         <td class="ins_celdacolor camposmedios">D&iacute;a:
                             <input name="fecha_descripciond" type="text" id="fecha_descripciond" size="8" maxlength="2" value="<?php echo substr($row_niveles_areasnotas['fecha_descripcion'],8,2); ?>">
Mes:
<input name="fecha_descripcionm" type="text" id="fecha_descripcionm" size="8" maxlength="2" value="<?php echo substr($row_niveles_areasnotas['fecha_descripcion'],5,2); ?>">
A&ntilde;o:
<input name="fecha_descripciona" type="text" id="fecha_descripciona" size="12" maxlength="4" value="<?php echo substr($row_niveles_areasnotas['fecha_descripcion'],0,4); ?>">
(dd/mm/aaaa)</td>
                       </tr>
                       <tr>
                         <td >&nbsp;</td>
                       </tr>
             </table></td>
          </TR>
          <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input onClick="activar();" name="button" type="submit" class="botongrabar" id="button" value="Grabar" <?php if($s4=="C" || $_SESSION['situacion'] == "Baja") { echo "disabled"; } ?> >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
     </table><input name="codigo_referencia_a_4" type="hidden" class="camposanchos" id="codigo_referencia_a_4" readonly value="<?php echo $row_vis_niveles['codigo_referencia']; ?>" />
     <input type="hidden" name="MM_update" value="form4">
    </form>
    </div>
    <?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php if(!isset($_GET['vr'])) { ?><a href="fr_niveles.php?cod=<?php echo $row_vis_niveles['codigo_institucion']; ?>&upi=<?php echo $row_vis_niveles['codigo_referencia']; ?>" target="_parent"><img src="images/flecha.png" width="79" height="22" border="0"></a><?php } ?>i</td>
  </tr>
 </table>
 <?php require_once('activo.php'); ?>
 <div id="cepilomenu">  
</div> 
<script type="text/javascript" language="JavaScript">  
   if (document.getElementById) {   
     if (menutipo == 0)   
       document.getElementById("cepilomenu").className = "tipo0"  
     else   
       document.getElementById("cepilomenu").className = "tipo1"  
   } else if (document.all) {   
     if (menutipo == 0)   
       document.all.cepilomenu.className = "tipo0"  
     else   
       document.all.cepilomenu.className = "tipo1"  
   }    
</script>  
</body>
</html>
<?php
mysql_free_result($vis_niveles);
mysql_free_result($vis_numerosInventarios);
mysql_free_result($vis_signaturaTopografica);
mysql_free_result($vis_niveles_contenedores);
mysql_free_result($vis_contenedores);
mysql_free_result($vis_edificios);
mysql_free_result($rel_niveles_productores);
mysql_free_result($estado_registral);
mysql_free_result($rel_niveles_soportes);
mysql_free_result($rel_niveles_idiomas);
mysql_free_result($rel_niveles_niveles);
mysql_free_result($cbo_niveles);
mysql_free_result($rel_niveles_institucionesinternas);
mysql_free_result($rel_niveles_institucionesexternas);
mysql_free_result($formas_ingreso);
mysql_free_result($cbo_normalegalingreso);
mysql_free_result($niveles_areasnotas);
mysql_free_result($registro_autoridad);
mysql_free_result($soportes);
mysql_free_result($idiomas);
mysql_free_result($instituciones);
mysql_free_result($instituciones_externas);
mysql_free_result($sistemas_organizaciones);
mysql_free_result($norma_legal);
mysql_free_result($rel_niveles_sistemasorganizacion);
mysql_free_result($niveles_tasacion_expertizaje);
mysql_free_result($tips);
mysql_free_result($niveles_tipos);
mysql_free_result($numeros_inventarios_reales);
mysql_free_result($sistema_organizacion_real);
mysql_free_result($forma_ingreso_real);
mysql_free_result($precio_real);
mysql_free_result($doc_soportes);
mysql_free_result($doc_idiomas);
?>
