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

$_SESSION['estadoregistral'] = $row_estado_registral['estado'];


mysql_select_db($database_conn, $conn);
$query_documentos_tasaciones_expertizaje = "SELECT * FROM documentos_tasaciones_expertizaje WHERE codigo_referencia='".$colname_estado_registral."' ORDER BY fecha DESC";
$documentos_tasaciones_expertizaje = mysql_query($query_documentos_tasaciones_expertizaje, $conn) or die(mysql_error());
$row_documentos_tasaciones_expertizaje = mysql_fetch_assoc($documentos_tasaciones_expertizaje);
$totalRows_documentos_tasaciones_expertizaje = mysql_num_rows($documentos_tasaciones_expertizaje);


//--actualizamos el area donde se esta trabajando----------------------------
function area_trabajo($na_t, $cod_ref,$database_conn,$conn) {	
	$updateSQL="UPDATE documentos SET na=".$na_t." WHERE codigo_referencia='".$cod_ref."'";
	
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die("area_trabajo: ".mysql_error());
}

//--actualizamos el area que se ha completado--------------------------------
function estado_completado($est_t, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET estado=".$est_t." WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos el estado a completado--------------------------------
function estados($est_t, $cod_ref,$database_conn,$conn) {
	$insertSQL = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, fecha, usuario) VALUES (%s, %s, %s, %s)",
					   GetSQLValueString($cod_ref, "text"),
					   GetSQLValueString($est_t, "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($insertSQL, $conn) or die(mysql_error());
}

//--actualizamos la situacion--------------------------------
function situacion($est_t, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET  situacion='".$est_t."' WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die("situacion:".mysql_error());
}

//--actualizamos la fecha visu--------------------------------
function visu($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET  fecha_ultimo_relevamiento_visu=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos ultima modificacion--------------------------------
function ultimamodificacion($cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultima_modificacion=%s, usuario=%s WHERE codigo_referencia=%s ",
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die("ultimamodificacion:".mysql_error());
}


//--eliminamos valores de tablas relacionadas*********************************************
if ((isset($_POST["elmaux"])) && ($_POST["elmaux"] == 1)) {
	
	if($_POST["tab"] == "e1") {  //rel_documentos_edificios
		$na_t="1";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_edificios WHERE codigo_referencia=%s AND nombre_edificio=%s AND ubicacion_topografica=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
	} elseif($_POST["tab"] == "e2") {  //rel_documentos_contenedores
	 	$na_t="1";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_contenedores WHERE codigo_referencia=%s AND contenedor=%s AND ruta_acceso=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
		
	} elseif($_POST["tab"] == "e3") {  //palabras_claves
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM palabras_claves WHERE codigo_referencia=%s AND palabra_clave=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	} elseif($_POST["tab"] == "e4") {  //rel_documentos_idiomas
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_idiomas WHERE codigo_referencia=%s AND idioma=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	} elseif($_POST["tab"] == "e5") {  //rel_documentos_rubros_autores
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_rubros_autores WHERE codigo_referencia=%s AND rubro=%s AND autor=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"),
							GetSQLValueString($_POST['val2'], "text"));
		
	} elseif($_POST["tab"] == "e6") {  //rel_documentos_envases
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_envases WHERE codigo_referencia=%s AND idenvases=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	} elseif($_POST["tab"] == "e7") {  //rel_documentos_descriptores_materias_contenidos
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_descriptores_materias_contenidos WHERE codigo_referencia=%s AND descriptor_materias_contenido=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	} elseif($_POST["tab"] == "e8") {  //rel_documentos_descriptores_onomasticos
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_descriptores_onomasticos WHERE codigo_referencia=%s AND descriptor_onomastico=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	} elseif($_POST["tab"] == "e9") {  //rel_documentos_descriptores_geograficos
	 	$na_t="2";
		$deleteSQL=sprintf("DELETE FROM rel_documentos_descriptores_geograficos WHERE codigo_referencia=%s AND descriptor_geografico=%s",
							GetSQLValueString($_POST['cod'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
		
	}
	
	
	
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($deleteSQL, $conn) or die($_POST["tab"]."-"."elmaux: ".mysql_error());
	
	
	//actualizamos  le fecha de modificacion, porque estos borrados no toca la tabla principal
	ultimamodificacion($_POST['cod'],$database_conn,$conn);
	
	//si esta en vigiente, debe volver a completo por ser modificado
	if($_SESSION['estadoregistral'] == "Vigente" || $_SESSION['estadoregistral'] == "No Vigente") {
		estados("Completo", $_POST['cod'],$database_conn,$conn);
	}
	
	//actualizamos el sector de trabajo donde se borro
	area_trabajo($na_t, $_POST['cod'],$database_conn,$conn);
	
	//luego de todas las modificaciones redirigimos a si misma para limpiar todo
	$updateGoTo = "diplomaticos_update.php?cod=".$_POST['cod'];
	header(sprintf("Location: %s", $updateGoTo));
	
}

//--actualizamos la fecha de la ultima gestion--------------------------------
function ultimagestion($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultima_gestion=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}


//--actualizamos la fecha de la ultima gestion--------------------------------
function reset_control($cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET control=0 WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//ejecutamos las querys principales-------------------------------------------
function ejecutarSelect($querySQL,$database_conn,$conn) {
	
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($querySQL, $conn) or die(mysql_error());
	
}


//--AREA DE IDENTIFICACION--------------------------------------------------------------

/*
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE documentos SET codigo_institucion=%s, titulo_original=%s, titulo_atribuido=%s, titulo_traducido=%s, fecha_registro=%s, numero_registro_inventario_anterior=%s, numero_inventario_unidad_documental=%s, tipo_general_documento=%s, tipo_especifico_documento=%s, tradicion_documental=%s, numero_registro_sur=%s, numero_registro_bibliografico=%s, tipo_diplomatico=%s, usuario=%s, fecha_ultima_modificacion=%s, control=1, supervicion=0 WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['codigo_institucion'], "text"),
                       GetSQLValueString($_POST['titulo_original'], "text"),
                       GetSQLValueString($_POST['titulo_atribuido'], "text"),
                       GetSQLValueString($_POST['titulo_traducido'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_registrod'],$_POST['fecha_registrom'],$_POST['fecha_registroa']), "date"),
                       GetSQLValueString($_POST['numero_registro_inventario_anterior'], "text"),
                       GetSQLValueString($_POST['numero_inventario_unidad_documental'], "text"),
                       GetSQLValueString($_POST['tipo_general_documento'], "text"),
                       GetSQLValueString($_POST['tipo_especifico_documento'], "text"),
                       GetSQLValueString($_POST['tradicion_documental'], "text"),
                       GetSQLValueString($_POST['numero_registro_sur'], "text"),
                       GetSQLValueString($_POST['numero_registro_bibliografico'], "text"),
                       GetSQLValueString($_POST['tipo_diplomatico'], "int"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
                       GetSQLValueString($_POST['codigo_referencia'], "text"));

  
  
   //--ponemos en estado pendiente si estaba en estado de inicio
  if($row_estado_registral['estado'] == "Inicio") {
   estados('Pendiente', $_POST['codigo_referencia'],$database_conn,$conn);
  }
  
  //--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(1, $_POST['codigo_referencia'],$database_conn,$conn);
  
  //--insertamos contenedores-------------------------------------------------------
  if(isset($_POST['contenedor']) && $_POST['contenedor'] != "") {
  $updateSQL2=sprintf("INSERT INTO rel_documentos_contenedores (codigo_referencia, contenedor, ruta_acceso) VALUES (%s, %s, %s)",
  					GetSQLValueString($_POST['codigo_referencia'], "text"),
  					GetSQLValueString($_POST['contenedor'], "text"),
					GetSQLValueString($_POST['ruta_acceso'], "text"));
					
  mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($updateSQL2, $conn) or die(mysql_error());
  }
  
  if(($_POST['ultimagestion'] == "" || $row_estado_registral['estado'] == "Inicio" || $row_estado_registral['estado'] == "Pendiente") && $_POST['fecha_registroa'] != "") {
  //--actualizamos fecha de gestion------------------------------------------------
	ultimagestion(fechar($_POST['fecha_registrod'],$_POST['fecha_registrom'],$_POST['fecha_registroa']),$_POST['codigo_referencia'],$database_conn,$conn);	
  }
  
  //--insertamos signatura topografica----------------------------------------------
  if(isset($_POST['nombre_edificio']) && $_POST['nombre_edificio'] != "") {
  $updateSQL3=sprintf("INSERT INTO rel_documentos_edificios (codigo_referencia, nombre_edificio, ubicacion_topografica) VALUES (%s, %s, %s)",
  					GetSQLValueString($_POST['codigo_referencia'], "text"),
  					GetSQLValueString($_POST['nombre_edificio'], "text"),
					GetSQLValueString($_POST['ubicacion_topografica'], "text"));
					
  mysql_select_db($database_conn, $conn);
  $Result3 = mysql_query($updateSQL3, $conn) or die(mysql_error());
  }
  
  
  //--redireccionamos luegro de grabar hacia el mismo formulario---------------
	$updateGoTo = "diplomaticos_update.php?cod=".$_POST['codigo_referencia'];
	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if($_POST["codigo_institucion"] != "" && $_POST["codigo_referencia"] != "" && $_POST["tipo_diplomatico"] != "" && $_POST["titulo_original"] != "" && $_POST["fecha_registroa"] != "" && $_POST["numero_inventario_unidad_documental"] != "" && $_POST["tipo_general_documento"] != "" && $_POST["tipo_especifico_documento"] != ""  && $_POST["tradicion_documental"] != "" && $_POST["cant_sgnaturatopografica"] >= 1) {
		
		ejecutarSelect($updateSQL,$database_conn,$conn);
		reset_control($_POST['codigo_referencia'],$database_conn,$conn);
		
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
			
			ejecutarSelect($updateSQL,$database_conn,$conn);
			reset_control($_POST['codigo_referencia'],$database_conn,$conn);
			
			header(sprintf("Location: %s", $updateGoTo));	
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Identificación no se ha grabado.\\nVerifique los campos obligatorios')</script>";  
		}	
	}
}

*/

//--actualizamos el area donde se esta trabajando----------------------------
function fijar_area_trabajo($na_t, $cod_ref) {	
	$updateSQL="UPDATE documentos SET na=".$na_t." WHERE codigo_referencia='".$cod_ref."'";
	return $updateSQL;
}

function cambio_Estado_Sistema($code, $Sector, $completo){
	$estadoSis=$_SESSION['estado'];
	if($completo == 1){
		if($Sector == 1 && $_SESSION['estado'] == 1) {
			$estadoSis=2;
		}
		if($Sector == 2 && $_SESSION['estado'] == 2) {
			$estadoSis=3;
		}
		if($Sector == 3 && $_SESSION['estado'] == 3) {
			$estadoSis=4;
		}
		if($Sector == 4 && $_SESSION['estado'] == 4) {
			$estadoSis=5;
		}
	}
	$updateSQL = sprintf("UPDATE documentos SET estado=%s WHERE codigo_referencia=%s ",
						GetSQLValueString($estadoSis, "int"),
					 	GetSQLValueString($code, "text"));
	
	if($_SESSION['estado'] == $estadoSis) {
		$updateSQL="";	
	}
	return $updateSQL;
}

function cambio_Estado_Registral($code,$sector){
	$estadoR="";
	if($_SESSION['estadoregistral'] == "Inicio") {
		$estadoR="Pendiente";
	}
	if($_SESSION['estadoregistral'] == "Vigente" || $_SESSION['estadoregistral'] == "No Vigente"){
		$estadoR="Completo";
	}
	
	$estadoUot = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, fecha, usuario) VALUES (%s, %s, %s, %s)",
					   GetSQLValueString($code, "text"),
					   GetSQLValueString($estadoR, "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));
					   
	if($_SESSION['estadoregistral'] == $estadoR || $estadoR == "") {
		$estadoUot = "";
	}
	return $estadoUot;
}


//--AREA DE IDENTIFICACION--------------------------------------------------------------
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

//control de campos completo
mysql_select_db($database_conn, $conn);
$query_registroTips = "SELECT * FROM tips WHERE area='Diplomaticos' AND sector='identificacion' AND info=0 AND d".GetSQLValueString($_POST['tipo_diplomatico'], "int")."=1";
$registroTips = mysql_query($query_registroTips, $conn) or die(mysql_error());
$row_estado_registroTips = mysql_fetch_assoc($registroTips);
$totalRows_registroTips = mysql_num_rows($registroTips);


$requeridosOK=1; $mensajeRequerido="";
do{

	//controlamos los campos de texto	
	if ($row_estado_registroTips['decantidad'] == 0 && $row_estado_registroTips['defecha'] == 0) {
		if($row_estado_registroTips['requerido'] == 1){
			if($_POST[$row_estado_registroTips['campo']] == ""){
				$requeridosOK=0;
				$mensajeRequerido .="El Campo ".$row_estado_registroTips['item']." es Obligatorio\\n";
				//echo $row_estado_registroTips['campo']."<br>";	
			}
		}
	}
	//controlamos los campos de cantidad
	if ($row_estado_registroTips['decantidad'] == 1 && $row_estado_registroTips['defecha'] == 0) {
		if($row_estado_registroTips['requerido'] == 1){
			if(GetSQLValueString($_POST[$row_estado_registroTips['campo']], "int") < 1){
				$requeridosOK=0;
				$mensajeRequerido .="El Campo ".$row_estado_registroTips['item']." es Obligatorio\\n";
				//echo $row_estado_registroTips['campo']."<br>";
			}
		}
	}
	//controlo los campos de fecha
	if ($row_estado_registroTips['decantidad'] == 0 && $row_estado_registroTips['defecha'] == 1) {
		if($row_estado_registroTips['requerido'] == 1){
			if($_POST[$row_estado_registroTips['campo'].'d'] == "" || $_POST[$row_estado_registroTips['campo'].'m'] == "" || $_POST[$row_estado_registroTips['campo'].'a'] == ""){
				$requeridosOK=0;
				$mensajeRequerido .="El Campo ".$row_estado_registroTips['item']." es Obligatorio\\n";
				//echo $row_estado_registroTips['campo']."<br>";
			}
		}
	}
	if ($row_estado_registroTips['decantidad'] == 1 && $row_estado_registroTips['defecha'] == 1) {
		$requeridosOK=0;
		$mensajeRequerido .="Errores en la Asignación de Permisos 1+1-".$row_estado_registroTips['idtips'];
	}
	
} while ($row_estado_registroTips = mysql_fetch_assoc($registroTips));

if($requeridosOK == 0){
	$mensajeRequerido = str_replace(":","",$mensajeRequerido);
	$mensajeRequerido = fromHtml($mensajeRequerido);
	echo "<script language=\"javascript\"> alert('".$mensajeRequerido."'); </script>";
	//echo $requeridosOK;
}

mysql_free_result($registroTips);

//preparamos el update del formulario general sector 1
$updateSQL1 = sprintf("UPDATE documentos SET codigo_institucion=%s, titulo_original=%s, titulo_atribuido=%s, titulo_traducido=%s, fecha_registro=%s, numero_registro_inventario_anterior=%s, numero_inventario_unidad_documental=%s, tipo_general_documento=%s, tipo_especifico_documento=%s, tradicion_documental=%s, numero_registro_sur=%s, numero_registro_bibliografico=%s, tipo_diplomatico=%s, usuario=%s, fecha_ultima_modificacion=%s, control=1, supervicion=0 WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['codigo_institucion'], "text"),
                       GetSQLValueString($_POST['titulo_original'], "text"),
                       GetSQLValueString($_POST['titulo_atribuido'], "text"),
                       GetSQLValueString($_POST['titulo_traducido'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_registrod'],$_POST['fecha_registrom'],$_POST['fecha_registroa']), "date"),
                       GetSQLValueString($_POST['numero_registro_inventario_anterior'], "text"),
                       GetSQLValueString($_POST['numero_inventario_unidad_documental'], "text"),
                       GetSQLValueString($_POST['tipo_general_documento'], "text"),
                       GetSQLValueString($_POST['tipo_especifico_documento'], "text"),
                       GetSQLValueString($_POST['tradicion_documental'], "text"),
                       GetSQLValueString($_POST['numero_registro_sur'], "text"),
                       GetSQLValueString($_POST['numero_registro_bibliografico'], "text"),
                       GetSQLValueString($_POST['tipo_diplomatico'], "int"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
                       GetSQLValueString($_POST['codigo_referencia'], "text"));
					   
//--preparamos signatura topografica----------------------------------------------
if(isset($_POST['nombre_edificio']) && $_POST['nombre_edificio'] != "") {
	$updateSQL2=sprintf("INSERT INTO rel_documentos_edificios (codigo_referencia, nombre_edificio, ubicacion_topografica) VALUES (%s, %s, %s)",
  					GetSQLValueString($_POST['codigo_referencia'], "text"),
  					GetSQLValueString($_POST['nombre_edificio'], "text"),
					GetSQLValueString($_POST['ubicacion_topografica'], "text"));
} else {
	$updateSQL2="";	
}
					
//--preparamos contenedores-------------------------------------------------------
if(isset($_POST['contenedor']) && $_POST['contenedor'] != "") {
	$updateSQL3=sprintf("INSERT INTO rel_documentos_contenedores (codigo_referencia, contenedor, ruta_acceso) VALUES (%s, %s, %s)",
  					GetSQLValueString($_POST['codigo_referencia'], "text"),
  					GetSQLValueString($_POST['contenedor'], "text"),
					GetSQLValueString($_POST['ruta_acceso'], "text"));
} else {
	$updateSQL3="";	
}

//preparamos el estado------------------------------------------------------------					
$updateSQL4 = cambio_Estado_Registral($_POST['codigo_referencia'],1);

//preparamos el area de trabajo para que quede abierto el form--------------------
if($requeridosOK == 1 && $_SESSION['estado'] == 1) { $l=2; } else { $l=1; }
$updateSQL5 = fijar_area_trabajo($l, $_POST['codigo_referencia']);

//preparamos el estado del sistema------------------------------------------------
$updateSQL6 = cambio_Estado_Sistema($_POST['codigo_referencia'], 1, $requeridosOK);


if($_SESSION['estado'] <= 1 || ($_SESSION['estado'] > 1 && $requeridosOK == 1)) {
//hacemos todos los pasos por transaccion **************************************************************************************
$errorTrans=0; $errorTXT=""; $errorScript="diplomaticos_update";
mysql_select_db($database_conn, $conn);
mysql_query("BEGIN");
for($i=1; $i <= 6; $i++) {
	if(${"updateSQL".$i} != "") {
		${"Result".$i} = mysql_query(${"updateSQL".$i}, $conn);
			if (!${"Result".$i}) { $errorTrans = 1; $errT="S1C".$i; $errorTXT .= $i."-".mysql_error()."<br>"; }
	}
}

if ($errorTrans == 1){
	mysql_query("ROLLBACK");
	$errorTXT .="Script de ejecución: ".$errorScript;
	$errorTXT .="<br><br>Copiar el mensaje de error y enviar a Sistemas";
	errordisplay($errT,$errorTXT);
} else {
	mysql_query("COMMIT");
}

} else {
	echo "<script language=\"javascript\"> alert('Ya no se pueden dejar Campos Obligatorios en blanco. \\n No se grabará cambio alguno.'); </script>";
}
	area_trabajo(1, $_POST['codigo_referencia'],$database_conn,$conn);
	echo "<script language=\"javascript\"> document.location='diplomaticos_update.php?cod=".$_POST['codigo_referencia']."'; </script>";
}





//--AREA DE DESCRIPCION-----S2---------------------------------------------------------------------

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL1 = sprintf("UPDATE documentos SET sistema_organizacion=%s, autor=%s, nombre_productor=%s, fecha_edicion_anio_editorial=%s, fecha_accion_representada=%s, version=%s, genero=%s, signos_especiales=%s, fecha_inicial=%s, fecha_final=%s, alcance_contenido=%s, soporte=%s, duracion_metraje=%s, cromia=%s, tecnica_fotografica=%s, tecnica_visual=%s, tecnica_digital=%s, emulsion=%s, integridad=%s, forma_presentacion_unidad=%s, cantidad_fojas_album=%s, caracteristica_montaje=%s, requisito_ejecucion=%s, unidades=%s, cantidad_envases_unidad_documental=%s, coleccion=%s, evento=%s, manifestacion=%s, usuario=%s, fecha_ultima_modificacion=%s, sonido=%s, control=1, supervicion=0, estado_conservacion=%s WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['sistema_organizacion'], "text"),
                       GetSQLValueString($_POST['autor2'], "text"),
                       GetSQLValueString($_POST['nombre_productor'], "text"),
                       GetSQLValueString($_POST['fecha_edicion_anio_editorial'], "text"),
                       GetSQLValueString($_POST['fecha_accion_representada'], "text"),
                       GetSQLValueString($_POST['version'], "text"),
                       GetSQLValueString($_POST['genero'], "text"),
                       GetSQLValueString($_POST['signos_especiales'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_iniciald'],$_POST['fecha_inicialm'],$_POST['fecha_iniciala']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_finald'],$_POST['fecha_finalm'],$_POST['fecha_finala']), "date"),
                       GetSQLValueString($_POST['alcance_contenido'], "text"),
                       GetSQLValueString($_POST['soporte'], "text"),
                       GetSQLValueString($_POST['duracion_metraje'], "text"),
                       GetSQLValueString($_POST['cromia'], "text"),
                       GetSQLValueString($_POST['tecnica_fotografica'], "text"),
                       GetSQLValueString($_POST['tecnica_visual'], "text"),
                       GetSQLValueString($_POST['tecnica_digital'], "text"),
                       GetSQLValueString($_POST['emulsion'], "text"),
                       GetSQLValueString($_POST['integridad'], "text"),
                       GetSQLValueString($_POST['forma_presentacion_unidad'], "text"),
                       GetSQLValueString($_POST['cantidad_fojas_album'], "int"),
                       GetSQLValueString($_POST['caracteristica_montaje'], "text"),
                       GetSQLValueString($_POST['requisito_ejecucion'], "text"),
                       GetSQLValueString($_POST['unidades'], "int"),
                       GetSQLValueString($_POST['cantidad_envases_unidad_documental'], "int"),
                       GetSQLValueString($_POST['coleccion'], "text"),
                       GetSQLValueString($_POST['evento'], "text"),
                       GetSQLValueString($_POST['manifestacion'], "text"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_POST['sonido'], "text"),
					   GetSQLValueString($_POST['estado_conservacion'], "text"),
                       GetSQLValueString($_POST['codigo_referencia2'], "text"));
					   
					   
//--agregamos palabras claves-------------------------------------------------------------					   
if(isset($_POST['palabra_clave']) && $_POST['palabra_clave'] != "") {
	  $insertSQL2 = sprintf("INSERT INTO palabras_claves (palabra_clave, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['palabra_clave'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
					
	mysql_select_db($database_conn, $conn);
  	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());				
}

//--insertamos idiomas----------------------------------------------------------------------
if(isset($_POST['cbidioma']) && $_POST['cbidioma'] != "") {
	  
	  $insertSQL3 = sprintf("INSERT INTO rel_documentos_idiomas (idioma, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['cbidioma'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result3 = mysql_query($insertSQL3, $conn) or die(mysql_error());				
}

//--insertamos banda sonora-----------------------------------------------------------------
if(isset($_POST['rubro']) && $_POST['rubro'] != "" && $_POST['autor'] != "") {
	  
	  $insertSQL4 = sprintf("INSERT INTO rel_documentos_rubros_autores (rubro, autor, codigo_referencia) VALUES (%s, %s, %s)",
	  				GetSQLValueString($_POST['rubro'], "text"),
					GetSQLValueString($_POST['autor'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result4 = mysql_query($insertSQL4, $conn) or die(mysql_error());				
}


//--insertamos envases-----------------------------------------------------------------
if(isset($_POST['cbenvases']) && $_POST['cbenvases'] != "" ) {
	  
	  $insertSQL5 = sprintf("INSERT INTO rel_documentos_envases (idenvases, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['cbenvases'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result5 = mysql_query($insertSQL5, $conn) or die(mysql_error());				
}

//--insertamos descriptores de materias del contenido---------------------------------
if(isset($_POST['descriptor_materias_contenido']) && $_POST['descriptor_materias_contenido'] != "" ) {
	  
	  $insertSQL6 = sprintf("INSERT INTO rel_documentos_descriptores_materias_contenidos (descriptor_materias_contenido, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['descriptor_materias_contenido'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result6 = mysql_query($insertSQL6, $conn) or die(mysql_error());				
}


//--insertamos descriptores onomasticos-----------------------------------------------
if(isset($_POST['descriptor_onomastico']) && $_POST['descriptor_onomastico'] != "" ) {
	  
	  $insertSQL7 = sprintf("INSERT INTO rel_documentos_descriptores_onomasticos (descriptor_onomastico, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['descriptor_onomastico'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result7 = mysql_query($insertSQL7, $conn) or die(mysql_error());				
}


//--insertamos descriptores geografico-----------------------------------------------
if(isset($_POST['descriptor_geografico']) && $_POST['descriptor_geografico'] != "" ) {
	  
	  $insertSQL8 = sprintf("INSERT INTO rel_documentos_descriptores_geograficos (descriptor_geografico, codigo_referencia) VALUES (%s, %s)",
	  				GetSQLValueString($_POST['descriptor_geografico'], "text"),
					GetSQLValueString($_POST['codigo_referencia2'], "text"));
	
	mysql_select_db($database_conn, $conn);
  	$Result8 = mysql_query($insertSQL8, $conn) or die(mysql_error());				
}
	
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(2, $_POST['codigo_referencia2'],$database_conn,$conn);
	
	//--redireccionamos luegro de grabar hacia el mismo formulario---------------
	$updateGoTo = "diplomaticos_update.php?cod=".$_POST['codigo_referencia2'];


	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if($_POST["nombre_productor"] != "" && $_POST["fecha_accion_representada"] != "" && $_POST["fecha_iniciala"] != "" && $_POST["fecha_finala"] != "" && $_POST["alcance_contenido"] != "" && $_POST["soporte"] != "" && $_POST["integridad"] != "" && $_POST["unidades"] != "" && $_POST["totalRows_palabra_clave"] >= 1 && $_POST["totalRows_rel_documentos_descriptores_materias_contenidos"] >= 1) {
		
		ejecutarSelect($updateSQL1,$database_conn,$conn);
		reset_control($_POST['codigo_referencia2'],$database_conn,$conn);
		
		if($_SESSION['estado'] <= 2) {
			estado_completado(3, $_POST['codigo_referencia2'],$database_conn,$conn);
			area_trabajo(3, $_POST['codigo_referencia2'],$database_conn,$conn);
		}
		
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente") {
			estados('Completo', $_POST['codigo_referencia2'],$database_conn,$conn);
		}
		
		header(sprintf("Location: %s", $updateGoTo));	
		
	} else {
		
		if($_SESSION['estado'] <= 2) {
			
			ejecutarSelect($updateSQL1,$database_conn,$conn);
			reset_control($_POST['codigo_referencia2'],$database_conn,$conn);
			
			header(sprintf("Location: %s", $updateGoTo));
				
		} else {
			
			echo "<script languaje=\"javascript\">alert('El Área de Descripción no se ha grabado.\\nVerifique los campos obligatorios')</script>";  
			
		}	
	}			   
  
}

//--AREA DE ADMINISTRACION------------------------------------------------------------------------

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE documentos SET forma_ingreso=%s, procedencia=%s, fecha_inicio_ingreso=%s, precio=%s, norma_legal_ingreso=%s, numero_legal_ingreso=%s, numero_administrativo=%s, derechos_restricciones=%s, titular_derecho=%s, tipo_acceso=%s, requisitos_acceso=%s, acceso_documentacion=%s, servicio_reproduccion=%s, publicaciones_instrumentos_accesos=%s, subsidios=%s, fecha_ultimo_relevamiento_visu=%s, control=1, supervicion=0 WHERE codigo_referencia=%s",
                       GetSQLValueString($_POST['forma_ingreso'], "text"),
                       GetSQLValueString($_POST['procedencia'], "text"),
                       GetSQLValueString($_POST['fecha_inicio_ingreso'], "text"),
                       GetSQLValueString($_POST['precio'], "text"),
                       GetSQLValueString($_POST['norma_legal_ingreso'], "text"),
                       GetSQLValueString($_POST['numero_legal_ingreso'], "text"),
                       GetSQLValueString($_POST['numero_administrativo'], "text"),
                       GetSQLValueString($_POST['derechos_restricciones'], "text"),
                       GetSQLValueString($_POST['titular_derecho'], "text"),
                       GetSQLValueString($_POST['tipo_acceso'], "text"),
                       GetSQLValueString($_POST['requisitos_acceso'], "text"),
                       GetSQLValueString($_POST['acceso_documentacion'], "text"),
                       GetSQLValueString($_POST['servicio_reproduccion'], "text"),
                       GetSQLValueString($_POST['publicaciones_instrumentos_accesos'], "text"),
                       GetSQLValueString($_POST['subsidios'], "text"),
					   GetSQLValueString(fechar($_POST['fecha_ultimo_relevamiento_visud'],$_POST['fecha_ultimo_relevamiento_visum'],$_POST['fecha_ultimo_relevamiento_visua']), "text"),
                       GetSQLValueString($_POST['codigo_referencia3'], "text"));


  
  //--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(3, $_POST['codigo_referencia3'],$database_conn,$conn);

	//--redireccionamos luegro de grabar hacia el mismo formulario---------------
  	$updateGoTo = "diplomaticos_update.php?cod=".$_GET['cod'];
	
	
	//--DAMOS DE BAJA-----------------------------------------------------------
	
	if(isset($_POST['normativa_legal_baja']) && $_POST['normativa_legal_baja'] != "" && $_POST['numero_norma_legal'] != "" && $_POST['motivo_baja'] != "" && $_POST['fecha_bajad'] != "" && $_POST['fecha_bajam'] != "" && $_POST['fecha_bajaa'] != "" && $_SESSION['estadoregistral'] == "Vigente"  && $_SESSION['situacion'] == "Local") {

	$updateSQL2 = sprintf("UPDATE documentos SET normativa_legal_baja=%s, numero_norma_legal=%s, motivo_baja=%s, fecha_baja=%s   WHERE codigo_referencia=%s",
						GetSQLValueString($_POST['normativa_legal_baja'], "text"),
						GetSQLValueString($_POST['numero_norma_legal'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"),
						GetSQLValueString(fechar($_POST['fecha_bajad'],$_POST['fecha_bajam'],$_POST['fecha_bajaa']), "date"),
						GetSQLValueString($_POST['codigo_referencia3'], "text"));
						
	situacion('Baja', $_POST['codigo_referencia3'],$database_conn,$conn);
	estados('Cancelado', $_POST['codigo_referencia3'],$database_conn,$conn);
	
	mysql_select_db($database_conn, $conn);
  	$Result2 = mysql_query($updateSQL2, $conn) or die(mysql_error());
	
	$_SESSION['situacion'] = "Baja";
	}

	//--Enviamos a una exposicion------------------------------------------------------
  	
	if(isset($_POST['codigo_exposicion']) && $_POST['codigo_exposicion'] != "" && $_POST['numero_asignado'] != "" && $_POST['fecha_entregad'] != "" && $_POST['fecha_entregam'] != "" && $_POST[''] != "fecha_entregaa" ) {
		
	if($_SESSION['situacion'] == "Local") {
		$updateSQL3 = sprintf("REPLACE INTO rel_documentos_exposiciones (codigo_referencia, codigo_exposicion, numero_asignado, fecha_entrega) VALUES (%s, %s, %s, %s)",
						GetSQLValueString($_POST['codigo_referencia3'], "text"),
						GetSQLValueString($_POST['codigo_exposicion'], "int"),
						GetSQLValueString($_POST['numero_asignado'], "text"),
						GetSQLValueString(fechar($_POST['fecha_entregad'],$_POST['fecha_entregam'],$_POST['fecha_entregaa']), "date"));
		mysql_select_db($database_conn, $conn);
  		$Result3 = mysql_query($updateSQL3, $conn) or die(mysql_error());
		situacion('Exposición', $_POST['codigo_referencia3'],$database_conn,$conn);
		$_SESSION['situacion'] = "Exposición";
		$updateSQL = str_replace(", fecha_ultimo_relevamiento_visu=".GetSQLValueString(fechar($_POST['fecha_ultimo_relevamiento_visud'],$_POST['fecha_ultimo_relevamiento_visum'],$_POST['fecha_ultimo_relevamiento_visua']), "text"), "", $updateSQL);
		visu(fechar($_POST['fecha_entregad'],$_POST['fecha_entregam'],$_POST['fecha_entregaa']), $_POST['codigo_referencia3'],$database_conn,$conn);
	}
	
	if($_POST['fecha_devoluciond'] != "" && $_POST['fecha_devolucionm'] != "" && $_POST['fecha_devoluciona'] != "") {
		$updateSQL4 = sprintf("UPDATE rel_documentos_exposiciones SET fecha_devolucion=%s WHERE codigo_referencia=%s AND codigo_exposicion=%s",
						GetSQLValueString(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']), "date"),
						GetSQLValueString($_POST['codigo_referencia3'], "text"),
						GetSQLValueString($_POST['codigo_exposicion'], "int"));	
		
		mysql_select_db($database_conn, $conn);
  		$Result4 = mysql_query($updateSQL4, $conn) or die(mysql_error());
		situacion('Local', $_POST['codigo_referencia3'],$database_conn,$conn);
		$_SESSION['situacion'] = "Local";
		$updateSQL = str_replace(", fecha_ultimo_relevamiento_visu=".GetSQLValueString(fechar($_POST['fecha_ultimo_relevamiento_visud'],$_POST['fecha_ultimo_relevamiento_visum'],$_POST['fecha_ultimo_relevamiento_visua']), "text"), "", $updateSQL);
		visu(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']), $_POST['codigo_referencia3'],$database_conn,$conn);
	}
	}
	
	//--tasacion y expertizaje------------------------------------------------------------------------
	
	if(isset($_POST['valuacion']) && $_POST['valuacion'] != "" && $_POST['fechavaluaciona'] != "" && $_POST['codigo_referencia3'] != ""){
		
		if($_POST['totalRows_documentos_tasaciones_expertizaje'] == "0") {
			
			$updateSQL15 =  sprintf("INSERT INTO documentos_tasaciones_expertizaje (codigo_referencia, valuacion, valuacionusd, fecha, tasador_experto, comentario_expertizaje) VALUES (%s, %s, %s, %s, %s, %s) ",
							GetSQLValueString($_POST['codigo_referencia3'], "text"),
							GetSQLValueString($_POST['valuacion'], "text"),
							GetSQLValueString($_POST['valuacionusd'], "text"),
							GetSQLValueString(fechar($_POST['fechavaluaciond'],$_POST['fechavaluacionm'],$_POST['fechavaluaciona']), "date"),
							GetSQLValueString($_POST['tasador_experto'], "text"),
							GetSQLValueString($_POST['comentario_expertizaje'], "text"));
			
			
			
		} else {
			
			if($_POST['codigo_referencia3'] == $row_documentos_tasaciones_expertizaje['codigo_referencia'] && $_POST['valuacion'] == $row_documentos_tasaciones_expertizaje['valuacion'] && $row_documentos_tasaciones_expertizaje['fecha'] ==  date('Y-m-d H:i:s', strtotime(fechar($_POST['fechavaluaciond'],$_POST['fechavaluacionm'],$_POST['fechavaluaciona'])))) {
			
				$updateSQL15 = sprintf("UPDATE documentos_tasaciones_expertizaje SET valuacion=%s, valuacionusd=%s, comentario_expertizaje=%s, tasador_experto=%s WHERE codigo_referencia=%s AND valuacion=%s AND fecha=%s",
							GetSQLValueString($_POST['valuacion'], "text"),
							GetSQLValueString($_POST['valuacionusd'], "text"),
							GetSQLValueString($_POST['comentario_expertizaje'], "text"),
							GetSQLValueString($_POST['tasador_experto'], "text"),
							
							GetSQLValueString($_POST['codigo_referencia3'], "text"),
							GetSQLValueString($_POST['valuacion'], "text"),
							GetSQLValueString(fechar($_POST['fechavaluaciond'],$_POST['fechavaluacionm'],$_POST['fechavaluaciona']), "text"));
							

				
				
			} else {
			
				$updateSQL15 =  sprintf("INSERT INTO documentos_tasaciones_expertizaje (codigo_referencia, valuacion, valuacionusd, fecha, tasador_experto, comentario_expertizaje) VALUES (%s, %s, %s, %s, %s, %s) ",
							GetSQLValueString($_POST['codigo_referencia3'], "text"),
							GetSQLValueString($_POST['valuacion'], "text"),
							GetSQLValueString($_POST['valuacionusd'], "text"),
							GetSQLValueString(fechar($_POST['fechavaluaciond'],$_POST['fechavaluacionm'],$_POST['fechavaluaciona']), "date"),
							GetSQLValueString($_POST['tasador_experto'], "text"),
							GetSQLValueString($_POST['comentario_expertizaje'], "text"));	
							
						
			}
			
		}

		mysql_select_db($database_conn, $conn);
  		$Result15 = mysql_query($updateSQL15, $conn) or die( scape() );
		
	}
	

//---------------------------------------------------------------------------------------



	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------
	if($_POST["forma_ingreso"] != "" && $_POST["procedencia"] != "" && $_POST["fecha_inicio_ingreso"] != "" && $_POST["norma_legal_ingreso"] != "" && $_POST["numero_legal_ingreso"] != "" && $_POST["publicaciones_instrumentos_accesos"] != "" && $_POST["fecha_ultimo_relevamiento_visua"] != "") {
		
		ejecutarSelect($updateSQL,$database_conn,$conn);
		reset_control($_POST['codigo_referencia3'],$database_conn,$conn);
		
		ultimamodificacion($_POST['codigo_referencia3'],$database_conn,$conn);
		if($_SESSION['estado'] <= 3) {
			estado_completado(4, $_POST['codigo_referencia3'],$database_conn,$conn);
			area_trabajo(4, $_POST['codigo_referencia3'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente") {
			estados('Completo', $_POST['codigo_referencia3'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));
	} else {
		if($_SESSION['estado'] <= 3) {
			
			ejecutarSelect($updateSQL,$database_conn,$conn);
		    reset_control($_POST['codigo_referencia3'],$database_conn,$conn);
			
			ultimamodificacion($_POST['codigo_referencia3'],$database_conn,$conn);
			header(sprintf("Location: %s", $updateGoTo));
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Administración no se ha grabado.\\nVerifique los campos obligatorios')</script>"; 
		} 
	}
}


function scape() {
	echo "<script language=\"javascript\"> alert('LOS VALORES DE TASACIÓN HAN SIDO INGRESADOS CON ANTERIORIDAD'); document.location='diplomaticos_update.php?cod=".$_GET['cod']."'; </script>";
}

//--AREA DE NOTAS------------------------------------------------------------------------------

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form4")) {
  $updateSQL = sprintf("REPLACE INTO documentos_areasnotas SET codigo_referencia=%s, nota_descripcion=%s, nota_archivero=%s, fuentes=%s, fecha_descripcion=%s",
                       GetSQLValueString($_POST['codigo_referencia4'], "text"),
					   GetSQLValueString($_POST['nota_descripcion'], "text"),
                       GetSQLValueString($_POST['nota_archivero'], "text"),
                       GetSQLValueString($_POST['fuentes'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_descripciond'],$_POST['fecha_descripcionm'],$_POST['fecha_descripciona']), "date"));
	
					   
	//--actualizamos el area donde se esta trabajando----------------------------
	area_trabajo(4, $_POST['codigo_referencia4'],$database_conn,$conn);
 	
	//--redireccionamos luegro de grabar hacia el mismo formulario--------------- 
	$updateGoTo = "diplomaticos_update.php?cod=".$_POST['codigo_referencia4'];
	
	//--actualizamos el estado numerico y comprobamos los campos requeridos------ 
	if($_POST["nota_descripcion"] != "" ) {
		mysql_select_db($database_conn, $conn);
  		$Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
		if($_SESSION['estado'] <= 4) {
			estado_completado(5, $_POST['codigo_referencia4'],$database_conn,$conn);
			area_trabajo(5, $_POST['codigo_referencia4'],$database_conn,$conn);
		}
		if($row_estado_registral['estado'] == "Vigente" || $row_estado_registral['estado'] == "No Vigente" || $row_estado_registral['estado'] == "Pendiente") {
			estados('Completo', $_POST['codigo_referencia4'],$database_conn,$conn);
		}
		header(sprintf("Location: %s", $updateGoTo));
	} else {
		if($_SESSION['estado'] <= 4) {
			mysql_select_db($database_conn, $conn);
  			$Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
			header(sprintf("Location: %s", $updateGoTo));
		} else {
			echo "<script languaje=\"javascript\">alert('El Área de Notas no se ha grabado.\\nVerifique los campos obligatorios')</script>"; 
		}
	}
}

//--vemos los datos de los documetnos-------------------------------------
$colname_diplomaticos = "-1";
if (isset($_GET['cod'])) {
  $colname_diplomaticos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_diplomaticos = sprintf("SELECT * FROM documentos  WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_diplomaticos, "text"));
$diplomaticos = mysql_query($query_diplomaticos, $conn) or die(mysql_error());
$row_diplomaticos = mysql_fetch_assoc($diplomaticos);
$totalRows_diplomaticos = mysql_num_rows($diplomaticos);

$_SESSION['estado'] = $row_diplomaticos['estado'];
$_SESSION['na'] = $row_diplomaticos['na'];
$_SESSION['situacion'] = $row_diplomaticos['situacion'];


$s1=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'NIV1' ,$database_conn,$conn);
$s2=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'NIV2' ,$database_conn,$conn);
$s3=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'NIV3' ,$database_conn,$conn);
$s4=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'NIV4' ,$database_conn,$conn);


//echo $s1.$s2.$s3.$s4;

if($s1 == "" || $s2 == "" || $s3 == "" || $s4 == "") {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}



//--tomas datos de las relacion entre edificio y documentos----------------------
$colname_rel_documentos_edificios = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_edificios = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_edificios = sprintf("SELECT * FROM rel_documentos_edificios WHERE codigo_referencia = %s", GetSQLValueString($colname_rel_documentos_edificios, "text"));
$rel_documentos_edificios = mysql_query($query_rel_documentos_edificios, $conn) or die(mysql_error());
$row_rel_documentos_edificios = mysql_fetch_assoc($rel_documentos_edificios);
$totalRows_rel_documentos_edificios = mysql_num_rows($rel_documentos_edificios);

//--llenamos el combo de edificios-----------------------------------------------
mysql_select_db($database_conn, $conn);
$query_edificios = "SELECT * FROM edificios ORDER BY nombre_edificio ASC";
$edificios = mysql_query($query_edificios, $conn) or die(mysql_error());
$row_edificios = mysql_fetch_assoc($edificios);
$totalRows_edificios = mysql_num_rows($edificios);


//--mostrar los contenedores en los documentos----------------------------------
$colname_rel_documentos_contenedores = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_contenedores = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_contenedores = sprintf("SELECT * FROM rel_documentos_contenedores WHERE codigo_referencia = %s ORDER BY contenedor ASC", GetSQLValueString($colname_rel_documentos_contenedores, "text"));
$rel_documentos_contenedores = mysql_query($query_rel_documentos_contenedores, $conn) or die(mysql_error());
$row_rel_documentos_contenedores = mysql_fetch_assoc($rel_documentos_contenedores);
$totalRows_rel_documentos_contenedores = mysql_num_rows($rel_documentos_contenedores);

//--llenamos el combo de contenedores------------------------------------------
mysql_select_db($database_conn, $conn);
$query_contenedores = "SELECT * FROM contenedores ORDER BY contenedor ASC";
$contenedores = mysql_query($query_contenedores, $conn) or die(mysql_error());
$row_contenedores = mysql_fetch_assoc($contenedores);
$totalRows_contenedores = mysql_num_rows($contenedores);

mysql_select_db($database_conn, $conn);
$query_tipos_general_documentos = "SELECT * FROM tipos_general_documentos ORDER BY tipo_general_documento ASC";
$tipos_general_documentos = mysql_query($query_tipos_general_documentos, $conn) or die(mysql_error());
$row_tipos_general_documentos = mysql_fetch_assoc($tipos_general_documentos);
$totalRows_tipos_general_documentos = mysql_num_rows($tipos_general_documentos);

mysql_select_db($database_conn, $conn);
$query_tipos_especificos_documentos = "SELECT * FROM tipos_especificos_documentos ORDER BY tipo_especifico_documento ASC";
$tipos_especificos_documentos = mysql_query($query_tipos_especificos_documentos, $conn) or die(mysql_error());
$row_tipos_especificos_documentos = mysql_fetch_assoc($tipos_especificos_documentos);
$totalRows_tipos_especificos_documentos = mysql_num_rows($tipos_especificos_documentos);

mysql_select_db($database_conn, $conn);
$query_tradiciones_documentales = "SELECT * FROM tradiciones_documentales ORDER BY tradicion_documental ASC";
$tradiciones_documentales = mysql_query($query_tradiciones_documentales, $conn) or die(mysql_error());
$row_tradiciones_documentales = mysql_fetch_assoc($tradiciones_documentales);
$totalRows_tradiciones_documentales = mysql_num_rows($tradiciones_documentales);

$colname_palabra_clave = "-1";
if (isset($_GET['cod'])) {
  $colname_palabra_clave = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_palabra_clave = sprintf("SELECT * FROM palabras_claves WHERE codigo_referencia = %s ORDER BY palabra_clave ASC", GetSQLValueString($colname_palabra_clave, "text"));
$palabra_clave = mysql_query($query_palabra_clave, $conn) or die(mysql_error());
$row_palabra_clave = mysql_fetch_assoc($palabra_clave);
$totalRows_palabra_clave = mysql_num_rows($palabra_clave);

$colname_rel_documentos_idiomas = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_idiomas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_idiomas = sprintf("SELECT * FROM rel_documentos_idiomas WHERE codigo_referencia = %s ORDER BY idioma ASC", GetSQLValueString($colname_rel_documentos_idiomas, "text"));
$rel_documentos_idiomas = mysql_query($query_rel_documentos_idiomas, $conn) or die(mysql_error());
$row_rel_documentos_idiomas = mysql_fetch_assoc($rel_documentos_idiomas);
$totalRows_rel_documentos_idiomas = mysql_num_rows($rel_documentos_idiomas);

mysql_select_db($database_conn, $conn);
$query_idiomas = "SELECT * FROM idiomas ORDER BY idioma ASC";
$idiomas = mysql_query($query_idiomas, $conn) or die(mysql_error());
$row_idiomas = mysql_fetch_assoc($idiomas);
$totalRows_idiomas = mysql_num_rows($idiomas);


//--banda sonora-----------------------------------------------------------------------
$colname_rel_documentos_rubros_autores = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_rubros_autores = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_rubros_autores = sprintf("SELECT * FROM rel_documentos_rubros_autores WHERE codigo_referencia = %s ORDER BY rubro ASC", GetSQLValueString($colname_rel_documentos_rubros_autores, "text"));
$rel_documentos_rubros_autores = mysql_query($query_rel_documentos_rubros_autores, $conn) or die(mysql_error());
$row_rel_documentos_rubros_autores = mysql_fetch_assoc($rel_documentos_rubros_autores);
$totalRows_rel_documentos_rubros_autores = mysql_num_rows($rel_documentos_rubros_autores);

mysql_select_db($database_conn, $conn);
$query_autores = "SELECT * FROM autores ORDER BY autor ASC";
$autores = mysql_query($query_autores, $conn) or die(mysql_error());
$row_autores = mysql_fetch_assoc($autores);
$totalRows_autores = mysql_num_rows($autores);

mysql_select_db($database_conn, $conn);
$query_rubros = "SELECT * FROM rubros ORDER BY rubro ASC";
$rubros = mysql_query($query_rubros, $conn) or die(mysql_error());
$row_rubros = mysql_fetch_assoc($rubros);
$totalRows_rubros = mysql_num_rows($rubros);

mysql_select_db($database_conn, $conn);
$query_sistemas_organizaciones = "SELECT * FROM sistemas_organizaciones ORDER BY sistema_organizacion ASC";
$sistemas_organizaciones = mysql_query($query_sistemas_organizaciones, $conn) or die(mysql_error());
$row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones);
$totalRows_sistemas_organizaciones = mysql_num_rows($sistemas_organizaciones);

mysql_select_db($database_conn, $conn);
$query_registro_autoridad = "SELECT * FROM registro_autoridad WHERE codigo_institucion = '".$row_diplomaticos['codigo_institucion']."' ORDER BY nombre_productor ASC";
$registro_autoridad = mysql_query($query_registro_autoridad, $conn) or die(mysql_error());
$row_registro_autoridad = mysql_fetch_assoc($registro_autoridad);
$totalRows_registro_autoridad = mysql_num_rows($registro_autoridad);

mysql_select_db($database_conn, $conn);
$query_versiones = "SELECT * FROM versiones ORDER BY version ASC";
$versiones = mysql_query($query_versiones, $conn) or die(mysql_error());
$row_versiones = mysql_fetch_assoc($versiones);
$totalRows_versiones = mysql_num_rows($versiones);

mysql_select_db($database_conn, $conn);
$query_generos = "SELECT * FROM generos ORDER BY genero ASC";
$generos = mysql_query($query_generos, $conn) or die(mysql_error());
$row_generos = mysql_fetch_assoc($generos);
$totalRows_generos = mysql_num_rows($generos);

mysql_select_db($database_conn, $conn);
$query_soportes = "SELECT * FROM soportes ORDER BY soporte ASC";
$soportes = mysql_query($query_soportes, $conn) or die(mysql_error());
$row_soportes = mysql_fetch_assoc($soportes);
$totalRows_soportes = mysql_num_rows($soportes);

mysql_select_db($database_conn, $conn);
$query_cromias = "SELECT * FROM cromias ORDER BY cromia ASC";
$cromias = mysql_query($query_cromias, $conn) or die(mysql_error());
$row_cromias = mysql_fetch_assoc($cromias);
$totalRows_cromias = mysql_num_rows($cromias);

mysql_select_db($database_conn, $conn);
$query_tecnicas_fotograficas = "SELECT * FROM tecnicas_fotograficas ORDER BY tecnica_fotografica ASC";
$tecnicas_fotograficas = mysql_query($query_tecnicas_fotograficas, $conn) or die(mysql_error());
$row_tecnicas_fotograficas = mysql_fetch_assoc($tecnicas_fotograficas);
$totalRows_tecnicas_fotograficas = mysql_num_rows($tecnicas_fotograficas);

mysql_select_db($database_conn, $conn);
$query_tecnicas_visuales = "SELECT * FROM tecnicas_visuales ORDER BY tecnica_visual ASC";
$tecnicas_visuales = mysql_query($query_tecnicas_visuales, $conn) or die(mysql_error());
$row_tecnicas_visuales = mysql_fetch_assoc($tecnicas_visuales);
$totalRows_tecnicas_visuales = mysql_num_rows($tecnicas_visuales);

mysql_select_db($database_conn, $conn);
$query_tecnicas_digitales = "SELECT * FROM tecnicas_digitales ORDER BY tecnica_digital ASC";
$tecnicas_digitales = mysql_query($query_tecnicas_digitales, $conn) or die(mysql_error());
$row_tecnicas_digitales = mysql_fetch_assoc($tecnicas_digitales);
$totalRows_tecnicas_digitales = mysql_num_rows($tecnicas_digitales);

mysql_select_db($database_conn, $conn);
$query_emulsiones = "SELECT * FROM emulsiones ORDER BY emulsion ASC";
$emulsiones = mysql_query($query_emulsiones, $conn) or die(mysql_error());
$row_emulsiones = mysql_fetch_assoc($emulsiones);
$totalRows_emulsiones = mysql_num_rows($emulsiones);

mysql_select_db($database_conn, $conn);
$query_caracteristicas_montaje = "SELECT * FROM caracteristicas_montaje ORDER BY caracteristica_montaje ASC";
$caracteristicas_montaje = mysql_query($query_caracteristicas_montaje, $conn) or die(mysql_error());
$row_caracteristicas_montaje = mysql_fetch_assoc($caracteristicas_montaje);
$totalRows_caracteristicas_montaje = mysql_num_rows($caracteristicas_montaje);

mysql_select_db($database_conn, $conn);
$query_requisitos_ejecucion = "SELECT * FROM requisitos_ejecucion ORDER BY requisito_ejecucion ASC";
$requisitos_ejecucion = mysql_query($query_requisitos_ejecucion, $conn) or die(mysql_error());
$row_requisitos_ejecucion = mysql_fetch_assoc($requisitos_ejecucion);
$totalRows_requisitos_ejecucion = mysql_num_rows($requisitos_ejecucion);

$colname_rel_documentos_envases = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_envases = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_envases = sprintf("SELECT envases.* FROM rel_documentos_envases LEFT JOIN envases ON(rel_documentos_envases.idenvases=envases.idenvases) WHERE codigo_referencia = %s ORDER BY idenvases ASC", GetSQLValueString($colname_rel_documentos_envases, "text"));
$rel_documentos_envases = mysql_query($query_rel_documentos_envases, $conn) or die(mysql_error());
$row_rel_documentos_envases = mysql_fetch_assoc($rel_documentos_envases);
$totalRows_rel_documentos_envases = mysql_num_rows($rel_documentos_envases);

$colname_rel_documentos_descriptores_materias_contenidos = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_descriptores_materias_contenidos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_descriptores_materias_contenidos = sprintf("SELECT * FROM rel_documentos_descriptores_materias_contenidos WHERE codigo_referencia = %s ORDER BY descriptor_materias_contenido ASC", GetSQLValueString($colname_rel_documentos_descriptores_materias_contenidos, "text"));
$rel_documentos_descriptores_materias_contenidos = mysql_query($query_rel_documentos_descriptores_materias_contenidos, $conn) or die(mysql_error());
$row_rel_documentos_descriptores_materias_contenidos = mysql_fetch_assoc($rel_documentos_descriptores_materias_contenidos);
$totalRows_rel_documentos_descriptores_materias_contenidos = mysql_num_rows($rel_documentos_descriptores_materias_contenidos);

$colname_rel_documentos_descriptores_onomasticos = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_descriptores_onomasticos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_descriptores_onomasticos = sprintf("SELECT * FROM rel_documentos_descriptores_onomasticos WHERE codigo_referencia = %s ORDER BY descriptor_onomastico ASC", GetSQLValueString($colname_rel_documentos_descriptores_onomasticos, "text"));
$rel_documentos_descriptores_onomasticos = mysql_query($query_rel_documentos_descriptores_onomasticos, $conn) or die(mysql_error());
$row_rel_documentos_descriptores_onomasticos = mysql_fetch_assoc($rel_documentos_descriptores_onomasticos);
$totalRows_rel_documentos_descriptores_onomasticos = mysql_num_rows($rel_documentos_descriptores_onomasticos);

$colname_rel_documentos_descriptores_geograficos = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_descriptores_geograficos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_descriptores_geograficos = sprintf("SELECT * FROM rel_documentos_descriptores_geograficos WHERE codigo_referencia = %s ORDER BY descriptor_geografico ASC", GetSQLValueString($colname_rel_documentos_descriptores_geograficos, "text"));
$rel_documentos_descriptores_geograficos = mysql_query($query_rel_documentos_descriptores_geograficos, $conn) or die(mysql_error());
$row_rel_documentos_descriptores_geograficos = mysql_fetch_assoc($rel_documentos_descriptores_geograficos);
$totalRows_rel_documentos_descriptores_geograficos = mysql_num_rows($rel_documentos_descriptores_geograficos);

mysql_select_db($database_conn, $conn);
$query_descriptores_geograficos = "SELECT * FROM descriptores_geograficos ORDER BY descriptor_geografico ASC";
$descriptores_geograficos = mysql_query($query_descriptores_geograficos, $conn) or die(mysql_error());
$row_descriptores_geograficos = mysql_fetch_assoc($descriptores_geograficos);
$totalRows_descriptores_geograficos = mysql_num_rows($descriptores_geograficos);

mysql_select_db($database_conn, $conn);
$query_descriptores_onomasticos = "SELECT * FROM descriptores_onomasticos ORDER BY descriptor_onomastico ASC";
$descriptores_onomasticos = mysql_query($query_descriptores_onomasticos, $conn) or die(mysql_error());
$row_descriptores_onomasticos = mysql_fetch_assoc($descriptores_onomasticos);
$totalRows_descriptores_onomasticos = mysql_num_rows($descriptores_onomasticos);

mysql_select_db($database_conn, $conn);
$query_descriptores_materias_contenidos = "SELECT * FROM descriptores_materias_contenidos ORDER BY descriptor_materias_contenido ASC";
$descriptores_materias_contenidos = mysql_query($query_descriptores_materias_contenidos, $conn) or die(mysql_error());
$row_descriptores_materias_contenidos = mysql_fetch_assoc($descriptores_materias_contenidos);
$totalRows_descriptores_materias_contenidos = mysql_num_rows($descriptores_materias_contenidos);

mysql_select_db($database_conn, $conn);
$query_envases = "SELECT * FROM envases ORDER BY material ASC";
$envases = mysql_query($query_envases, $conn) or die(mysql_error());
$row_envases = mysql_fetch_assoc($envases);
$totalRows_envases = mysql_num_rows($envases);

mysql_select_db($database_conn, $conn);
$query_fomas_presentacion_unidad = "SELECT * FROM fomas_presentacion_unidad ORDER BY forma ASC";
$fomas_presentacion_unidad = mysql_query($query_fomas_presentacion_unidad, $conn) or die(mysql_error());
$row_fomas_presentacion_unidad = mysql_fetch_assoc($fomas_presentacion_unidad);
$totalRows_fomas_presentacion_unidad = mysql_num_rows($fomas_presentacion_unidad);

mysql_select_db($database_conn, $conn);
$query_sonidos = "SELECT * FROM sonidos ORDER BY sonido ASC";
$sonidos = mysql_query($query_sonidos, $conn) or die(mysql_error());
$row_sonidos = mysql_fetch_assoc($sonidos);
$totalRows_sonidos = mysql_num_rows($sonidos);

mysql_select_db($database_conn, $conn);
$query_formas_ingreso = "SELECT * FROM formas_ingreso ORDER BY forma_ingreso ASC";
$formas_ingreso = mysql_query($query_formas_ingreso, $conn) or die(mysql_error());
$row_formas_ingreso = mysql_fetch_assoc($formas_ingreso);
$totalRows_formas_ingreso = mysql_num_rows($formas_ingreso);

mysql_select_db($database_conn, $conn);
$query_norma_legal = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$norma_legal = mysql_query($query_norma_legal, $conn) or die(mysql_error());
$row_norma_legal = mysql_fetch_assoc($norma_legal);
$totalRows_norma_legal = mysql_num_rows($norma_legal);

mysql_select_db($database_conn, $conn);
$query_tipos_accesos = "SELECT * FROM tipos_accesos ORDER BY tipo_acceso ASC";
$tipos_accesos = mysql_query($query_tipos_accesos, $conn) or die(mysql_error());
$row_tipos_accesos = mysql_fetch_assoc($tipos_accesos);
$totalRows_tipos_accesos = mysql_num_rows($tipos_accesos);

mysql_select_db($database_conn, $conn);
$query_requisitos_accesos = "SELECT * FROM requisitos_accesos ORDER BY requisito_acceso ASC";
$requisitos_accesos = mysql_query($query_requisitos_accesos, $conn) or die(mysql_error());
$row_requisitos_accesos = mysql_fetch_assoc($requisitos_accesos);
$totalRows_requisitos_accesos = mysql_num_rows($requisitos_accesos);

mysql_select_db($database_conn, $conn);
$query_acceso_documentacion = "SELECT * FROM acceso_documentacion ORDER BY acceso_documentacion ASC";
$acceso_documentacion = mysql_query($query_acceso_documentacion, $conn) or die(mysql_error());
$row_acceso_documentacion = mysql_fetch_assoc($acceso_documentacion);
$totalRows_acceso_documentacion = mysql_num_rows($acceso_documentacion);

mysql_select_db($database_conn, $conn);
$query_documentos_estados_conservacion = "SELECT * FROM documentos_estados_conservacion ORDER BY estado_conservacion ASC";
$documentos_estados_conservacion = mysql_query($query_documentos_estados_conservacion, $conn) or die(mysql_error());
$row_documentos_estados_conservacion = mysql_fetch_assoc($documentos_estados_conservacion);
$totalRows_documentos_estados_conservacion = mysql_num_rows($documentos_estados_conservacion);

mysql_select_db($database_conn, $conn);
$query_servicios_reproduccion = "SELECT * FROM servicios_reproduccion ORDER BY servicio_reproduccion ASC";
$servicios_reproduccion = mysql_query($query_servicios_reproduccion, $conn) or die(mysql_error());
$row_servicios_reproduccion = mysql_fetch_assoc($servicios_reproduccion);
$totalRows_servicios_reproduccion = mysql_num_rows($servicios_reproduccion);

$colname_documentos_areasnotas = "-1";
if (isset($_GET['cod'])) {
  $colname_documentos_areasnotas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_documentos_areasnotas = sprintf("SELECT * FROM documentos_areasnotas WHERE codigo_referencia = %s ORDER BY fecha_modificacion DESC", GetSQLValueString($colname_documentos_areasnotas, "text"));
$documentos_areasnotas = mysql_query($query_documentos_areasnotas, $conn) or die(mysql_error());
$row_documentos_areasnotas = mysql_fetch_assoc($documentos_areasnotas);
$totalRows_documentos_areasnotas = mysql_num_rows($documentos_areasnotas);


//buscamos las relaciones entre el documento y las expocisiones enviadas
$colname_rel_documentos_exposiciones = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_exposiciones = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_exposiciones = sprintf("SELECT * FROM rel_documentos_exposiciones INNER JOIN exposiciones ON (rel_documentos_exposiciones.codigo_exposicion=exposiciones.codigo_exposicion) WHERE rel_documentos_exposiciones.codigo_referencia = %s AND fecha_devolucion IS NOT NULL  ORDER BY fecha_entrega DESC", GetSQLValueString($colname_rel_documentos_exposiciones, "text"));
$rel_documentos_exposiciones = mysql_query($query_rel_documentos_exposiciones, $conn) or die(mysql_error());
$row_rel_documentos_exposiciones = mysql_fetch_assoc($rel_documentos_exposiciones);
$totalRows_rel_documentos_exposiciones = mysql_num_rows($rel_documentos_exposiciones);

mysql_select_db($database_conn, $conn);
$query_exposiciones = "SELECT * FROM exposiciones ORDER BY nombre ASC";
$exposiciones = mysql_query($query_exposiciones, $conn) or die(mysql_error());
$row_exposiciones = mysql_fetch_assoc($exposiciones);
$totalRows_exposiciones = mysql_num_rows($exposiciones);

$colname_rel_documentos_exposiciones2 = "-1";
if (isset($_GET['cod'])) {
  $colname_rel_documentos_exposiciones2 = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_exposiciones2 = sprintf("SELECT * FROM rel_documentos_exposiciones WHERE codigo_referencia = %s AND fecha_devolucion IS NULL ORDER BY fecha_entrega DESC LIMIT 1", GetSQLValueString($colname_rel_documentos_exposiciones2, "text"));
$rel_documentos_exposiciones2 = mysql_query($query_rel_documentos_exposiciones2, $conn) or die(mysql_error());
$row_rel_documentos_exposiciones2 = mysql_fetch_assoc($rel_documentos_exposiciones2);
$totalRows_rel_documentos_exposiciones2 = mysql_num_rows($rel_documentos_exposiciones2);

$colname_auditoria_usuarios = "-1";
if (isset($_GET['cod'])) {
  $colname_auditoria_usuarios = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_auditoria_usuarios = sprintf("SELECT fecha_ultima_modificacion, usuario FROM documentos_aud WHERE codigo_referencia = %s ORDER BY fecha_update DESC LIMIT 10", GetSQLValueString($colname_auditoria_usuarios, "text"));
$auditoria_usuarios = mysql_query($query_auditoria_usuarios, $conn) or die(mysql_error());
$row_auditoria_usuarios = mysql_fetch_assoc($auditoria_usuarios);
$totalRows_auditoria_usuarios = mysql_num_rows($auditoria_usuarios);

mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Diplomáticos' AND (sector='identificacion' OR sector='descripcion' OR sector='administracion' OR sector='notas') ORDER BY idtips ASC";
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);

do {

$nomTip = "v".$row_tips['idtips'];
$$nomTip = "<a name=\"p".$row_tips['idtips']."\" />";
$$nomTip .= "<div id=\"".$nomTip."\" style=\"position:absolute; z-index:1000; visibility:hidden; width:505px;\"  onmouseover=\"muestra_retarda('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\"><table width=\"505\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><img src=\"images/globo1.png\" width=\"505\" height=\"28\"></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><table width=\"490\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "      <tr>";
$$nomTip .= "        <td style=\"background:url(images/globo3.png);\"><table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "          <tr>";
$$nomTip .= "            <td class=\"tips\">".$row_tips['idtips']." - ".$row_tips['tip'];
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
if($row_tips['info'] == 1) {
$$nomTip .= "<img src=\"images/help3.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";
} else {
if ($row_tips['requerido'] == 1) {
$$nomTip .= "<img src=\"images/help2.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";
} else {
$$nomTip .= "<img src=\"images/help1.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";	
}}
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	margin-left: 00px;
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
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
<script language=JavaScript src='Scripts/innovaeditor.js'></script>
<script type='text/javascript' src='gyl_menu.js'></script>
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
</head>
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
	
	function relocate(page,params)
 	{
	activar();
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
  <!--<tr>
    <td> Estado: <?php echo $row_estado_registral['estado']; ?>  - <?php echo $_SESSION['estado']." - ".$_SESSION['na']." - ".$_SESSION['MM_Username']; ?> - <?php echo $_SESSION['situacion']; ?> - <?php echo $_SESSION['estadoregistral']; ?> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
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
    <div id="f1c" style="display:<?php if($_SESSION['estado'] >= 1 && $_SESSION['na'] != 1) { echo "block"; } else { echo "none"; } ?>" >
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
    <div id="f1" style="display:<?php if($_SESSION['estado'] >= 1 && $_SESSION['na'] == 1) { echo "block"; } else { echo "none"; } ?>">
    <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
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
    	  <td class="fondolineaszulesvert">
    	    <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v70; ?><!-- C&oacute;digo de Referencia de la Instituci&oacute;n: --></td>
  	          </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="codigo_institucion" type="text" class="camposanchos" id="codigo_institucion" value="<?php echo $row_diplomaticos['codigo_institucion']; ?>" readonly /></td>
              </tr>
              <tr>
                     <td class="separadormayor"></td>
                </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v71; ?><!-- C&oacute;digo de Referencia: --></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="codigo_referencia" type="text" class="camposanchos" id="codigo_referencia" value="<?php echo $row_diplomaticos['codigo_referencia']; ?>" readonly /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v72; ?><!--  Tipo Documento Diplom&aacute;tico: --></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="tipo_diplomatico" type="text" class="camposanchos" id="tipo_diplomatico" value="<?php if($row_diplomaticos['tipo_diplomatico'] == "8") {echo "8 - Documentos Visuales"; } else if($row_diplomaticos['tipo_diplomatico'] == "9") {echo "9 - Documentos Audiovisuales"; } else if($row_diplomaticos['tipo_diplomatico'] == "10") {echo "10 - Documentos Sonoros"; } else if($row_diplomaticos['tipo_diplomatico'] == "11") {echo "11 - Documentos Textuales"; }?>" readonly /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v73; ?><!--  T&iacute;tulo Original: --></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="titulo_original" type="text" class="camposanchos" id="titulo_original" value="<?php echo $row_diplomaticos['titulo_original']; ?>"  />
                </td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v74; ?><!-- T&iacute;tulo Atribu&iacute;do: --></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="titulo_atribuido" type="text" class="camposanchos" id="titulo_atribuido" value="<?php echo $row_diplomaticos['titulo_atribuido']; ?>"  /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v75; ?><!-- T&iacute;tulo Traducido:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor">
                  <input name="titulo_traducido" type="text" class="camposanchos" id="titulo_traducido" value="<?php echo $row_diplomaticos['titulo_traducido']; ?>"  />
                </td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v76; ?><!-- Fecha de Registro:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_registrod" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_registro'],8,2); ?>">
Mes:
<input name="fecha_registrom" type="text" id="fecha_registrom" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_registro'],5,2); ?>">
A&ntilde;o:
<input name="fecha_registroa" type="text" id="fecha_registroa" size="12" maxlength="4" value="<?php echo substr($row_diplomaticos['fecha_registro'],0,4); ?>">
(dd/mm/aaaa)</td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v77; ?><!-- N&uacute;mero de Inventario:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor">
                  <input name="numero_inventario_unidad_documental" type="text" class="camposanchos" id="numero_inventario_unidad_documental" value="<?php echo $row_diplomaticos['numero_inventario_unidad_documental']; ?>"  />
                </td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v78; ?><!-- N&uacute;mero de Registro Anterior:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><input name="numero_registro_inventario_anterior" type="text" class="camposanchos" id="numero_registro_inventario_anterior" value="<?php echo $row_diplomaticos['numero_registro_inventario_anterior']; ?>"  /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td><table width="630" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="121" height="30" class="niv_menu">Tipo de Documento</td>
                    <td width="509"><hr></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v79; ?><!-- Tipo General de Documento:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor">
                  <select name="tipo_general_documento" id="tipo_general_documento" class="camposanchos">
                    <option value="" <?php if (!(strcmp("", $row_diplomaticos['tipo_general_documento']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_tipos_general_documentos['tipo_general_documento']?>"<?php if (!(strcmp($row_tipos_general_documentos['tipo_general_documento'], $row_diplomaticos['tipo_general_documento']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipos_general_documentos['tipo_general_documento']?></option>
                    <?php
} while ($row_tipos_general_documentos = mysql_fetch_assoc($tipos_general_documentos));
  $rows = mysql_num_rows($tipos_general_documentos);
  if($rows > 0) {
      mysql_data_seek($tipos_general_documentos, 0);
	  $row_tipos_general_documentos = mysql_fetch_assoc($tipos_general_documentos);
  }
?>
                  </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v80; ?><!-- Tipo Espec&iacute;fico de Documento:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><select name="tipo_especifico_documento" id="tipo_especifico_documento" class="camposanchos">
                  <option value="" <?php if (!(strcmp("", $row_diplomaticos['tipo_especifico_documento']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_tipos_especificos_documentos['tipo_especifico_documento']?>"<?php if (!(strcmp($row_tipos_especificos_documentos['tipo_especifico_documento'], $row_diplomaticos['tipo_especifico_documento']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipos_especificos_documentos['tipo_especifico_documento']?></option>
                  <?php
} while ($row_tipos_especificos_documentos = mysql_fetch_assoc($tipos_especificos_documentos));
  $rows = mysql_num_rows($tipos_especificos_documentos);
  if($rows > 0) {
      mysql_data_seek($tipos_especificos_documentos, 0);
	  $row_tipos_especificos_documentos = mysql_fetch_assoc($tipos_especificos_documentos);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v81; ?><!-- Tradici&oacute;n Documental:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><select name="tradicion_documental" id="tradicion_documental" class="camposanchos">
                  <option value="" <?php if (!(strcmp("", $row_diplomaticos['tradicion_documental']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_tradiciones_documentales['tradicion_documental']?>"<?php if (!(strcmp($row_tradiciones_documentales['tradicion_documental'], $row_diplomaticos['tradicion_documental']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tradiciones_documentales['tradicion_documental']?></option>
                  <?php
} while ($row_tradiciones_documentales = mysql_fetch_assoc($tradiciones_documentales));
  $rows = mysql_num_rows($tradiciones_documentales);
  if($rows > 0) {
      mysql_data_seek($tradiciones_documentales, 0);
	  $row_tradiciones_documentales = mysql_fetch_assoc($tradiciones_documentales);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v82; ?><!-- Signatura Topogr&aacute;fica:--></td>
              </tr>
              <tr>
                <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_documentos_edificios > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_edificios['nombre_edificio']; ?><br><?php echo $row_rel_documentos_edificios['ubicacion_topografica']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?>
                             <?php if($totalRows_rel_documentos_edificios >= 1) { ?><a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e1','val1':'<?php echo $row_rel_documentos_edificios['nombre_edificio']; ?>','val2':'<?php echo $row_rel_documentos_edificios['ubicacion_topografica']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?>
                           <?php } ?></td>
                         </tr>
                         <?php } while ($row_rel_documentos_edificios = mysql_fetch_assoc($rel_documentos_edificios)); ?>
                    </table></td>
              </tr>
              <tr>
                <td ><input name="cant_sgnaturatopografica" id="cant_sgnaturatopografica" value="<?php echo $totalRows_rel_documentos_edificios; ?>" type="hidden"><div id="dv_aux_c_0">
                <?php if($totalRows_rel_documentos_edificios < 1) { ?>
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><a onClick="abriraux(0);"><img src="images/bt_023.jpg" width="161" height="20" border="0"></a><?php } ?></td>
  	                </tr>
  	              </table>
                  <?php } ?>
                    </div>
                    <div id="dv_aux_a_0" style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Edificio:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="nombre_edificio" id="nombre_edificio" class="camposmedios">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_edificios['nombre_edificio']?>"><?php echo $row_edificios['nombre_edificio']?></option>
    	                          <?php
} while ($row_edificios = mysql_fetch_assoc($edificios));
  $rows = mysql_num_rows($edificios);
  if($rows > 0) {
      mysql_data_seek($edificios, 0);
	  $row_edificios = mysql_fetch_assoc($edificios);
  }
?>
                                </select></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Ubicaci&oacute;n Topogr&aacute;fica:</td>
    	                        <td class="ins_tituloscampos">
    	                          <input name="ubicacion_topografica" type="text" class="camposmedioscbt" id="ubicacion_topografica" value="">
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio"></td>
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
                  	<td align="right"><a onClick="cerraraux(0);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v83; ?><!-- Contenedores:--></td>
              </tr>
              <tr>
                <td class="ins_celdacolor">
                	<table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_documentos_contenedores > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_contenedores['contenedor']; ?> - <?php echo $row_rel_documentos_contenedores['ruta_acceso']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?>
                             <?php if($totalRows_rel_documentos_contenedores >= 1) { ?><a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e2','val1':'<?php echo $row_rel_documentos_contenedores['contenedor']; ?>','val2':'<?php echo $row_rel_documentos_contenedores['ruta_acceso']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?><?php } ?>
                           </td>
                         </tr>
                         <?php } while ($row_rel_documentos_contenedores = mysql_fetch_assoc($rel_documentos_contenedores)); ?>
                    </table>
                </td>
              </tr>
              <tr>
                <td><div id="dv_aux_c_1">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><input name="cant_documentos_contenedores" id="cant_documentos_contenedores" value="<?php echo $totalRows_rel_documentos_edificios; ?>" type="hidden">
    	                  <?php if($s1!="C") { ?><a onClick="abriraux(1);"><img src="images/bt_015.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Contenedor:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="contenedor" id="contenedor" class="camposmedios">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_contenedores['contenedor']?>"><?php echo $row_contenedores['contenedor']?></option>
    	                          <?php
} while ($row_contenedores = mysql_fetch_assoc($contenedores));
  $rows = mysql_num_rows($contenedores);
  if($rows > 0) {
      mysql_data_seek($contenedores, 0);
	  $row_contenedores = mysql_fetch_assoc($contenedores);
  }
?>
                                </select></td>
  	                        </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td class="ins_tituloscampos">&nbsp;&nbsp;Ruta de acceso:</td>
    	                        <td class="ins_tituloscampos">
    	                          <input name="ruta_acceso" type="text" class="camposmedioscbt" id="ruta_acceso" value="">
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio"></td>
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
                <td class="tituloscampos ins_celdacolor"><?php echo $v84; ?><!-- Objeto CONAR:--></td>
              </tr>
              <tr>
                <td class="ins_celdacolor"><span class="ins_tituloscampos">
                  <input name="numero_registro_sur" type="text" class="camposmedioscbt" id="numero_registro_sur" value="<?php echo $row_diplomaticos['numero_registro_sur']; ?>"><input type="button" name="button2" id="button2" value="Buscar Identificador" class="btongrande">
                </span></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v85; ?><!-- Registro Bibliogr&aacute;fico:--></td>
              </tr>
              <tr>
                <td class="ins_celdacolor"><span class="ins_tituloscampos">
                  <input name="numero_registro_bibliografico" type="text" class="camposmedioscbt" id="numero_registro_bibliografico" value="<?php echo $row_diplomaticos['numero_registro_bibliografico']; ?>">
                  <input type="button" name="button3" id="button3" value="Buscar Identificador" class="btongrande">
                </span></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v215; ?><!-- ultimos modificantes:--></td>
              </tr>
              <tr>
                <td class="ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="153" class="tituloscampos ins_celdacolor">Fecha:</td>
                    <td width="167" class="tituloscampos ins_celdacolor">Ususario:</td>
                    <td width="310" class="tituloscampos ins_celdacolor">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td class="tituloscampos ins_celdacolor"><?php echo "".$row_diplomaticos['fecha_ultima_modificacion']; ?></td>
                    <td class="tituloscampos ins_celdacolor"><?php echo "".$row_diplomaticos['usuario']; ?></td>
                    <td class="tituloscampos ins_celdacolor">&nbsp;</td>
                  </tr>
                  <?php do { ?>
                    <tr>
                      <td class="tituloscampos ins_celdacolor"><?php echo "".$row_auditoria_usuarios['fecha_ultima_modificacion']; ?></td>
                      <td class="tituloscampos ins_celdacolor"><?php echo "".$row_auditoria_usuarios['usuario']; ?></td>
                      <td class="tituloscampos ins_celdacolor">&nbsp;</td>
                    </tr>
                    <?php } while ($row_auditoria_usuarios = mysql_fetch_assoc($auditoria_usuarios)); ?>
                </table></td>
               </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
             </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdabotones1"><?php if($s1!="C") { ?><?php if($_SESSION['estado'] != "Vigente") { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Grabar" onClick="activar();" <?php if($_SESSION['situacion'] == "Baja") { echo "disabled";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
    	<tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input name="ultimagestion" type="hidden" id="ultimagestion" value="<?php echo $row_diplomaticos['fecha_ultima_gestion']; ?>">
    </form><?php } ?></td>
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
    <div id="f2c" style="display:<?php if($_SESSION['estado'] >= 2 && $_SESSION['na'] != 2) { echo "block"; } else { echo "none"; } ?>">
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
    <div id="f2" style="display:<?php if($_SESSION['estado'] >= 2 && $_SESSION['na'] == 2) { echo "block"; } else { echo "none"; } ?>">
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
    	<tr>
    	  <td class="fondolineaszulesvert">
          
          <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	         <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v148; ?><!-- Sistema de Organizacion:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">
    	            <select name="sistema_organizacion" id="sistema_organizacion" class="camposanchos">
    	              <option value="" <?php if (!(strcmp("", $row_diplomaticos['sistema_organizacion']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	              <?php
do {  
?>
    	              <option value="<?php echo $row_sistemas_organizaciones['sistema_organizacion']?>"<?php if (!(strcmp($row_sistemas_organizaciones['sistema_organizacion'], $row_diplomaticos['sistema_organizacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_sistemas_organizaciones['sistema_organizacion']?></option>
    	              <?php
} while ($row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones));
  $rows = mysql_num_rows($sistemas_organizaciones);
  if($rows > 0) {
      mysql_data_seek($sistemas_organizaciones, 0);
	  $row_sistemas_organizaciones = mysql_fetch_assoc($sistemas_organizaciones);
  }
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v149; ?><!-- Autor:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor" id="autor2"><select name="autor2" id="autor2" class="camposanchos">
<option value="" <?php if (!(strcmp("", $row_diplomaticos['autor']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
<?php do {  ?>
<option value="<?php echo $row_autores['autor']; ?>" <?php if (!(strcmp($row_autores['autor'], $row_diplomaticos['autor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_autores['autor']?></option>                 
<?php
} while ($row_autores = mysql_fetch_assoc($autores));
  $rows = mysql_num_rows($autores);
  if($rows > 0) {
      mysql_data_seek($autores, 0);
	  $row_autores = mysql_fetch_assoc($autores);
  }
?>                 
</select></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v150; ?><!-- Nombre del Productor:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="nombre_productor" id="nombre_productor" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['nombre_productor']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_registro_autoridad['nombre_productor']?>"<?php if (!(strcmp($row_registro_autoridad['nombre_productor'], $row_diplomaticos['nombre_productor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_registro_autoridad['nombre_productor']?></option>
    	            <?php
} while ($row_registro_autoridad = mysql_fetch_assoc($registro_autoridad));
  $rows = mysql_num_rows($registro_autoridad);
  if($rows > 0) {
      mysql_data_seek($registro_autoridad, 0);
	  $row_registro_autoridad = mysql_fetch_assoc($registro_autoridad);
  }
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="84" height="30" class="niv_menu">Publicaci&oacute;n</td>
    	              <td width="546"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v151; ?><!-- Fecha de edici&oacute;n y a&ntilde;o editorial:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><span class="ins_celdacolor">
    	            <input name="fecha_edicion_anio_editorial" type="text" class="camposanchos" id="fecha_edicion_anio_editorial" value="<?php echo $row_diplomaticos['fecha_edicion_anio_editorial']; ?>" maxlength="50"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v152; ?><!-- Fecha acci&oacute;n representada:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="fecha_accion_representada" type="text" class="camposanchos" id="fecha_accion_representada" value="<?php echo $row_diplomaticos['fecha_accion_representada']; ?>" maxlength="50"  /></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v153; ?><!-- Palabras Claves:-->
    	            <input type="hidden" name="totalRows_palabra_clave" id="totalRows_palabra_clave" value="<?php echo $totalRows_palabra_clave; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor">
                	<table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_palabra_clave > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_palabra_clave['palabra_clave']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?>
                             <?php if($totalRows_palabra_clave >= 1) { ?><a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e3','val1':'<?php echo $row_palabra_clave['palabra_clave']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?>
                           <?php } ?></td>
                         </tr>
                         <?php } while ($row_palabra_clave = mysql_fetch_assoc($palabra_clave)); ?>
                    </table>
                </td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_2">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(2);"><img src="images/bt_016.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Palabra:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <input name="palabra_clave" type="text" class="camposmedioscbt" id="palabra_clave" value="">
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio" onClick="activar();"></td>
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
                    </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v154; ?><!-- Versi&oacute;n:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="version" id="version" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['version']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_versiones['version']?>"<?php if (!(strcmp($row_versiones['version'], $row_diplomaticos['version']))) {echo "selected=\"selected\"";} ?>><?php echo $row_versiones['version']?></option>
    	            <?php
} while ($row_versiones = mysql_fetch_assoc($versiones));
  $rows = mysql_num_rows($versiones);
  if($rows > 0) {
      mysql_data_seek($versiones, 0);
	  $row_versiones = mysql_fetch_assoc($versiones);
  }
?>
                  </select></td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v155; ?><!-- G&eacute;nero: --></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="genero" id="genero" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['genero']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_generos['genero']?>"<?php if (!(strcmp($row_generos['genero'], $row_diplomaticos['genero']))) {echo "selected=\"selected\"";} ?>><?php echo $row_generos['genero']?></option>
    	            <?php
} while ($row_generos = mysql_fetch_assoc($generos));
  $rows = mysql_num_rows($generos);
  if($rows > 0) {
      mysql_data_seek($generos, 0);
	  $row_generos = mysql_fetch_assoc($generos);
  }
?>
                  </select></td>
  	          </tr>
    	      <?php if($row_diplomaticos['tipo_diplomatico'] == "8" || $row_diplomaticos['tipo_diplomatico'] == "11") { ?>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v156; ?><!-- Signos Especiales: --></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="signos_especiales" type="text" class="camposanchos" id="signos_especiales" value="<?php echo $row_diplomaticos['signos_especiales']; ?>"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">Fechas</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v157; ?><!-- Fecha Inicial:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                      <input name="fecha_iniciald" type="text" id="fecha_iniciald" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_inicial'],8,2); ?>">
Mes:
<input name="fecha_inicialm" type="text" id="textfield2" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_inicial'],5,2); ?>">
A&ntilde;o:
<input name="fecha_iniciala" type="text" id="textfield3" size="12" maxlength="4" value="<?php echo substr($row_diplomaticos['fecha_inicial'],0,4); ?>">
(dd/mm/aaaa)</td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v158; ?><!-- Fecha Final:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                      <input name="fecha_finald" type="text" id="textfield4" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_final'],8,2); ?>">
Mes:
<input name="fecha_finalm" type="text" id="textfield5" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_final'],5,2); ?>">
A&ntilde;o:
<input name="fecha_finala" type="text" id="textfield6" size="12" maxlength="4" value="<?php echo substr($row_diplomaticos['fecha_final'],0,4); ?>">
(dd/mm/aaaa)</td>
  	          </tr>
    	     <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v159; ?><!-- Alcance de Contenidos:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="alcance_contenido" type="text" class="camposanchos" id="alcance_contenido" value="<?php echo $row_diplomaticos['alcance_contenido']; ?>"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="630"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v160; ?><!-- Soporte:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="soporte" id="soporte" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['soporte']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_soportes['soporte']?>"<?php if (!(strcmp($row_soportes['soporte'], $row_diplomaticos['soporte']))) {echo "selected=\"selected\"";} ?>><?php echo $row_soportes['soporte']?></option>
    	            <?php
} while ($row_soportes = mysql_fetch_assoc($soportes));
  $rows = mysql_num_rows($soportes);
  if($rows > 0) {
      mysql_data_seek($soportes, 0);
	  $row_soportes = mysql_fetch_assoc($soportes);
  }
?>
                  </select></td>
  	          </tr>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "9" || $row_diplomaticos['tipo_diplomatico'] == "10" || $row_diplomaticos['tipo_diplomatico'] == "11") { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v161; ?><!-- Idiomas:--></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor">
                	<table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_documentos_idiomas > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_idiomas['idioma']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?>
                             <?php if($totalRows_rel_documentos_idiomas >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e4','val1':'<?php echo $row_rel_documentos_idiomas['idioma']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?>
                           <?php } ?></td>
                         </tr>
                        <?php } while ($row_rel_documentos_idiomas = mysql_fetch_assoc($rel_documentos_idiomas)); ?>
                    </table>
                </td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_3">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(3);"><img src="images/bt_017.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Idioma:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="cbidioma" id="cbidioma" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
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
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio" onClick="activar();"></td>
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
                    </div></td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "9" || $row_diplomaticos['tipo_diplomatico'] == "10") { ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v162; ?><!-- Duraci&oacute;n-Metraje:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="duracion_metraje" type="text" class="camposanchos" id="duracion_metraje" value="<?php echo $row_diplomaticos['duracion_metraje']; ?>" maxlength="50"  /></td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "8" || $row_diplomaticos['tipo_diplomatico'] == "9" ) { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v163; ?><!-- Crom&iacute;a:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><select name="cromia" id="cromia" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['cromia']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_cromias['cromia']?>"<?php if (!(strcmp($row_cromias['cromia'], $row_diplomaticos['cromia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cromias['cromia']?></option>
    	            <?php
} while ($row_cromias = mysql_fetch_assoc($cromias));
  $rows = mysql_num_rows($cromias);
  if($rows > 0) {
      mysql_data_seek($cromias, 0);
	  $row_cromias = mysql_fetch_assoc($cromias);
  }
?>
                  </select></td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "9") { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v164; ?><!-- Sonido:--></td>
  	          </tr>
              <tr>
    	          <td class="separadormenor">
    	            <select name="sonido" id="sonido" class="camposanchos">
    	              <option value="" <?php if (!(strcmp("", $row_diplomaticos['sonido']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	              <?php
do {  
?>
    	              <option value="<?php echo $row_sonidos['sonido']?>"<?php if (!(strcmp($row_sonidos['sonido'], $row_diplomaticos['sonido']))) {echo "selected=\"selected\"";} ?>><?php echo $row_sonidos['sonido']?></option>
    	              <?php
} while ($row_sonidos = mysql_fetch_assoc($sonidos));
  $rows = mysql_num_rows($sonidos);
  if($rows > 0) {
      mysql_data_seek($sonidos, 0);
	  $row_sonidos = mysql_fetch_assoc($sonidos);
  }
?>
                    </select></td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "9" || $row_diplomaticos['tipo_diplomatico'] == "10" ) { ?>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v165; ?><!-- Banda Sonora:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">
                  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                       <?php do { ?>
                       <?php if($totalRows_rel_documentos_rubros_autores > 1) {  ?>
                       <tr>
                       	<td height="2" colspan="2" bgcolor="#FFFFFF"></td>
                       </tr>
                       <?php } ?>
                         <tr>
                           <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_rubros_autores['rubro']." - ".$row_rel_documentos_rubros_autores['autor']; ?></td>
                           <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?>
                             <?php if($totalRows_rel_documentos_rubros_autores >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e5','val1':'<?php echo $row_rel_documentos_rubros_autores['rubro']; ?>','val2':'<?php echo $row_rel_documentos_rubros_autores['autor']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } else { ?>&nbsp;<?php } ?>
                           <?php } ?></td>
                         </tr>
                        <?php } while ($row_rel_documentos_rubros_autores = mysql_fetch_assoc($rel_documentos_rubros_autores)); ?>
                    </table>
                  </td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">
                  <div id="dv_aux_c_4">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(4);"><img src="images/bt_018.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Rubro:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="rubro" id="rubro" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_rubros['rubro']?>"><?php echo $row_rubros['rubro']?></option>
    	                            <?php
} while ($row_rubros = mysql_fetch_assoc($rubros));
  $rows = mysql_num_rows($rubros);
  if($rows > 0) {
      mysql_data_seek($rubros, 0);
	  $row_rubros = mysql_fetch_assoc($rubros);
  }
?>
                                  </select>
    	                          <input type="submit" name="button10" id="button10" value="G" class="btonmedio"></td>
  	                          </tr>
    	                      <tr>
    	                        <td colspan="2" class="ins_separadormayor"></td>
  	                        </tr>
    	                      <tr>
    	                        <td  class="ins_tituloscampos">&nbsp;&nbsp;Autor:</td>
    	                        <td ><span class="ins_tituloscampos">
    	                          <select name="autor" id="autor" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_autores['autor']?>"><?php echo $row_autores['autor']?></option>
    	                            <?php
} while ($row_autores = mysql_fetch_assoc($autores));
  $rows = mysql_num_rows($autores);
  if($rows > 0) {
      mysql_data_seek($autores, 0);
	  $row_autores = mysql_fetch_assoc($autores);
  }
?>
                                  </select>
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
                  	<td align="right"><a onClick="cerraraux(4);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div>
                  </td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "8" ) { ?>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">T&eacute;cnica</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v166; ?><!-- T&eacute;cnica fotogr&aacute;fica:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="tecnica_fotografica" id="tecnica_fotografica" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['tecnica_fotografica']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_tecnicas_fotograficas['tecnica_fotografica']?>"<?php if (!(strcmp($row_tecnicas_fotograficas['tecnica_fotografica'], $row_diplomaticos['tecnica_fotografica']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tecnicas_fotograficas['tecnica_fotografica']?></option>
    	            <?php
} while ($row_tecnicas_fotograficas = mysql_fetch_assoc($tecnicas_fotograficas));
  $rows = mysql_num_rows($tecnicas_fotograficas);
  if($rows > 0) {
      mysql_data_seek($tecnicas_fotograficas, 0);
	  $row_tecnicas_fotograficas = mysql_fetch_assoc($tecnicas_fotograficas);
  }
?>
                  </select></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v167; ?><!-- T&eacute;cnica Visual:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="tecnica_visual" id="tecnica_visual" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['tecnica_visual']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_tecnicas_visuales['tecnica_visual']?>"<?php if (!(strcmp($row_tecnicas_visuales['tecnica_visual'], $row_diplomaticos['tecnica_visual']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tecnicas_visuales['tecnica_visual']?></option>
    	            <?php
} while ($row_tecnicas_visuales = mysql_fetch_assoc($tecnicas_visuales));
  $rows = mysql_num_rows($tecnicas_visuales);
  if($rows > 0) {
      mysql_data_seek($tecnicas_visuales, 0);
	  $row_tecnicas_visuales = mysql_fetch_assoc($tecnicas_visuales);
  }
?>
                  </select></td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v168; ?><!-- T&eacute;cnica Digital:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="tecnica_digital" id="tecnica_digital" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['tecnica_digital']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_tecnicas_digitales['tecnica_digital']?>"<?php if (!(strcmp($row_tecnicas_digitales['tecnica_digital'], $row_diplomaticos['tecnica_digital']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tecnicas_digitales['tecnica_digital']?></option>
    	            <?php
} while ($row_tecnicas_digitales = mysql_fetch_assoc($tecnicas_digitales));
  $rows = mysql_num_rows($tecnicas_digitales);
  if($rows > 0) {
      mysql_data_seek($tecnicas_digitales, 0);
	  $row_tecnicas_digitales = mysql_fetch_assoc($tecnicas_digitales);
  }
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="630"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
              
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v169; ?><!-- Emulsi&oacute;n: --></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="emulsion" id="emulsion" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['emulsion']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_emulsiones['emulsion']?>"<?php if (!(strcmp($row_emulsiones['emulsion'], $row_diplomaticos['emulsion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_emulsiones['emulsion']?></option>
    	            <?php
} while ($row_emulsiones = mysql_fetch_assoc($emulsiones));
  $rows = mysql_num_rows($emulsiones);
  if($rows > 0) {
      mysql_data_seek($emulsiones, 0);
	  $row_emulsiones = mysql_fetch_assoc($emulsiones);
  }
?>
                  </select></td>
  	          </tr>
              <?php } ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v170; ?><!-- Integridad:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="integridad" type="text" class="camposanchos" id="integridad" value="<?php echo $row_diplomaticos['integridad']; ?>" maxlength="50"  /></td>
  	          </tr>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "8" || $row_diplomaticos['tipo_diplomatico'] == "11") { ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v218; ?><!-- Estados de Conservación: --></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="estado_conservacion" id="estado_conservacion" class="camposanchos">
                  <option value="">Sin Especificar</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_documentos_estados_conservacion['estado_conservacion']?>"<?php if (!(strcmp($row_documentos_estados_conservacion['estado_conservacion'], $row_diplomaticos['estado_conservacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_documentos_estados_conservacion['estado_conservacion']?></option>
    	            <?php
} while ($row_documentos_estados_conservacion = mysql_fetch_assoc($documentos_estados_conservacion));
  $rows = mysql_num_rows($documentos_estados_conservacion);
  if($rows > 0) {
      mysql_data_seek($documentos_estados_conservacion, 0);
	  $row_documentos_estados_conservacion = mysql_fetch_assoc($documentos_estados_conservacion);
  }
?>
    	          </select></td>
              </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v171; ?><!-- Forma de Presentaci&oacute;n de la Unidad: --></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="forma_presentacion_unidad" id="forma_presentacion_unidad" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['forma_presentacion_unidad']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_fomas_presentacion_unidad['forma']?>"<?php if (!(strcmp($row_fomas_presentacion_unidad['forma'], $row_diplomaticos['forma_presentacion_unidad']))) {echo "selected=\"selected\"";} ?>><?php echo $row_fomas_presentacion_unidad['forma']?></option>
    	            <?php
} while ($row_fomas_presentacion_unidad = mysql_fetch_assoc($fomas_presentacion_unidad));
  $rows = mysql_num_rows($fomas_presentacion_unidad);
  if($rows > 0) {
      mysql_data_seek($fomas_presentacion_unidad, 0);
	  $row_fomas_presentacion_unidad = mysql_fetch_assoc($fomas_presentacion_unidad);
  }
?>
                  </select></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v172; ?><!-- Cantidad de Fojas del &Aacute;lbum:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="cantidad_fojas_album" type="text" class="camposanchos" id="cantidad_fojas_album" value="<?php echo $row_diplomaticos['cantidad_fojas_album']; ?>"  /></td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v173; ?><!-- Caractir&iacute;sticas del montaje del documento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="caracteristica_montaje" id="caracteristica_montaje" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['caracteristica_montaje']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_caracteristicas_montaje['caracteristica_montaje']?>"<?php if (!(strcmp($row_caracteristicas_montaje['caracteristica_montaje'], $row_diplomaticos['caracteristica_montaje']))) {echo "selected=\"selected\"";} ?>><?php echo $row_caracteristicas_montaje['caracteristica_montaje']?></option>
    	            <?php
} while ($row_caracteristicas_montaje = mysql_fetch_assoc($caracteristicas_montaje));
  $rows = mysql_num_rows($caracteristicas_montaje);
  if($rows > 0) {
      mysql_data_seek($caracteristicas_montaje, 0);
	  $row_caracteristicas_montaje = mysql_fetch_assoc($caracteristicas_montaje);
  }
?>
                  </select></td>
  	          </tr>
              <?php } ?>
              <?php if($row_diplomaticos['tipo_diplomatico'] == "8" || $row_diplomaticos['tipo_diplomatico'] == "9" || $row_diplomaticos['tipo_diplomatico'] == "10") { ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v174; ?><!-- Envases:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php do { ?>
    	            <?php if($totalRows_rel_documentos_envases > 1) {  ?>
    	            <tr>
    	              <td height="2" colspan="2" bgcolor="#FFFFFF"></td>
  	              </tr>
    	            <?php } ?>
    	            <tr>
    	              <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_envases['material']." - ".$row_rel_documentos_envases['dimension']." - ".$row_rel_documentos_envases['autor']; ?></td>
    	              <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><?php if($totalRows_rel_documentos_envases >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e6','val1':'<?php echo $row_rel_documentos_envases['idenvases']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
    	                <?php } else { ?>
    	                &nbsp;
    	                <?php } ?><?php } ?></td>
  	              </tr>
    	            <?php } while ($row_rel_documentos_envases = mysql_fetch_assoc($rel_documentos_envases)); ?>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_5">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(5);"><img src="images/bt_019.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Envases:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="cbenvases" id="cbenvases" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_envases['idenvases']?>"><?php echo $row_envases['material']."-".$row_envases['dimension']."-".$row_envases['autor']; ?></option>
    	                            <?php
} while ($row_envases = mysql_fetch_assoc($envases));
  $rows = mysql_num_rows($envases);
  if($rows > 0) {
      mysql_data_seek($envases, 0);
	  $row_envases = mysql_fetch_assoc($envases);
  }
?>
                                  </select>
    	                          <input type="submit" name="button10" id="button10" value="G" onClick="activar();" class="btonmedio"></td>
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
                    </div>
                  </td>
  	          </tr>
              <?php } ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v175; ?><!-- Requisitos de Ejecuci&oacute;n: --></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><select name="requisito_ejecucion" id="requisito_ejecucion" class="camposanchos">
    	            <option value="" <?php if (!(strcmp("", $row_diplomaticos['requisito_ejecucion']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_requisitos_ejecucion['requisito_ejecucion']?>"<?php if (!(strcmp($row_requisitos_ejecucion['requisito_ejecucion'], $row_diplomaticos['requisito_ejecucion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_requisitos_ejecucion['requisito_ejecucion']?></option>
    	            <?php
} while ($row_requisitos_ejecucion = mysql_fetch_assoc($requisitos_ejecucion));
  $rows = mysql_num_rows($requisitos_ejecucion);
  if($rows > 0) {
      mysql_data_seek($requisitos_ejecucion, 0);
	  $row_requisitos_ejecucion = mysql_fetch_assoc($requisitos_ejecucion);
  }
?>
                  </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">Vol&uacute;men</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v176; ?><!-- Unidades:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="unidades" type="text" class="camposanchos" id="unidades" value="<?php echo $row_diplomaticos['unidades']; ?>"  /></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v177; ?><!-- Cantidad de Envases de la Unidad Documental:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="cantidad_envases_unidad_documental" type="text" class="camposanchos" id="cantidad_envases_unidad_documental" value="<?php echo $row_diplomaticos['cantidad_envases_unidad_documental']; ?>"  /></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v178; ?><!-- Colecci&oacute;n:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="coleccion" type="text" class="camposanchos" id="coleccion" value="<?php echo $row_diplomaticos['coleccion']; ?>"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="89" height="30" class="niv_menu">Descriptores</td>
    	              <td width="541"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v179; ?><!-- Descriptores de Materias del Contenido:--><input type="hidden" name="totalRows_rel_documentos_descriptores_materias_contenidos" id="totalRows_rel_documentos_descriptores_materias_contenidos" value="<?php echo $totalRows_rel_documentos_descriptores_materias_contenidos; ?>"></td>                                   
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php do { ?>
    	            <?php if($totalRows_rel_documentos_descriptores_materias_contenidos > 1) {  ?>
    	            <tr>
    	              <td height="2" colspan="2" bgcolor="#FFFFFF"></td>
  	              </tr>
    	            <?php } ?>
    	            <tr>
    	              <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_descriptores_materias_contenidos['descriptor_materias_contenido']; ?></td>
    	              <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><?php if($totalRows_rel_documentos_descriptores_materias_contenidos >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e7','val1':'<?php echo $row_rel_documentos_descriptores_materias_contenidos['descriptor_materias_contenido']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
    	                <?php } else { ?>
    	                &nbsp;
    	                <?php } ?><?php } ?></td>
  	              </tr>
    	            <?php } while ($row_rel_documentos_descriptores_materias_contenidos = mysql_fetch_assoc($rel_documentos_descriptores_materias_contenidos)); ?>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_6">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(6);"><img src="images/bt_020.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Descriptor de Materias:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="descriptor_materias_contenido" id="descriptor_materias_contenido" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_descriptores_materias_contenidos['descriptor_materias_contenido']?>"><?php echo $row_descriptores_materias_contenidos['descriptor_materias_contenido']?></option>
    	                            <?php
} while ($row_descriptores_materias_contenidos = mysql_fetch_assoc($descriptores_materias_contenidos));
  $rows = mysql_num_rows($descriptores_materias_contenidos);
  if($rows > 0) {
      mysql_data_seek($descriptores_materias_contenidos, 0);
	  $row_descriptores_materias_contenidos = mysql_fetch_assoc($descriptores_materias_contenidos);
  }
?>
                                  </select>
    	                          <input type="submit" name="button10" id="button10" value="G" onClick="activar();" class="btonmedio"></td>
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
                    </div>
                  </td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v180; ?><!-- Descriptores Onom&aacute;sticos:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php do { ?>
    	            <?php if($totalRows_rel_documentos_descriptores_onomasticos > 1) {  ?>
    	            <tr>
    	              <td height="2" colspan="2" bgcolor="#FFFFFF"></td>
  	              </tr>
    	            <?php } ?>
    	            <tr>
    	              <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_descriptores_onomasticos['descriptor_onomastico']; ?></td>
    	              <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><?php if($totalRows_rel_documentos_descriptores_onomasticos >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e8','val1':'<?php echo $row_rel_documentos_descriptores_onomasticos['descriptor_onomastico']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
    	                <?php } else { ?>
    	                &nbsp;
    	                <?php } ?><?php } ?></td>
  	              </tr>
    	            <?php } while ($row_rel_documentos_descriptores_onomasticos = mysql_fetch_assoc($rel_documentos_descriptores_onomasticos)); ?>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_7">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(7);"><img src="images/bt_021.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Descriptor Onom&aacute;stico:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="descriptor_onomastico" id="descriptor_onomastico" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_descriptores_onomasticos['descriptor_onomastico']?>"><?php echo $row_descriptores_onomasticos['descriptor_onomastico']?></option>
    	                            <?php
} while ($row_descriptores_onomasticos = mysql_fetch_assoc($descriptores_onomasticos));
  $rows = mysql_num_rows($descriptores_onomasticos);
  if($rows > 0) {
      mysql_data_seek($descriptores_onomasticos, 0);
	  $row_descriptores_onomasticos = mysql_fetch_assoc($descriptores_onomasticos);
  }
?>
                                  </select>
    	                          <input type="submit" name="button10" id="button10" value="G" onClick="activar();" class="btonmedio"></td>
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
                  </div></td>
  	          </tr>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v181; ?><!-- Descriptores Geogr&aacute;ficos:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <?php do { ?>
    	            <?php if($totalRows_rel_documentos_descriptores_geograficos > 1) {  ?>
    	            <tr>
    	              <td height="2" colspan="2" bgcolor="#FFFFFF"></td>
  	              </tr>
    	            <?php } ?>
    	            <tr>
    	              <td width="543" class="ins_celdtadirecciones " ><?php echo $row_rel_documentos_descriptores_geograficos['descriptor_geografico']; ?></td>
    	              <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s2!="C") { ?><?php if($totalRows_rel_documentos_descriptores_geograficos >= 1) { ?>
<a onDblClick="relocate('diplomaticos_update.php?cod=<?php echo $_GET['cod']; ?>',{'cod':'<?PHP echo $_GET['cod']; ?>','elmaux':'1','tab':'e9','val1':'<?php echo  $row_rel_documentos_descriptores_geograficos['descriptor_geografico']; ?>'});" ><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>
    	                <?php } else { ?>
    	                &nbsp;
    	                <?php } ?><?php } ?></td>
  	              </tr>
    	            <?php } while ($row_rel_documentos_descriptores_geograficos = mysql_fetch_assoc($rel_documentos_descriptores_geograficos)); ?>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><div id="dv_aux_c_8">
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s2!="C") { ?><a onClick="abriraux(8);"><img src="images/bt_022.jpg" width="161" height="20" border="0"></a><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos">&nbsp;&nbsp;Descriptor Geogr&aacute;fico:</td>
    	                        <td width="484" class="ins_tituloscampos">
    	                          <select name="descriptor_geografico" id="descriptor_geografico" class="camposmedioscbt">
    	                            <option value="">Seleccione Opci&oacute;n</option>
    	                            <?php
do {  
?>
    	                            <option value="<?php echo $row_descriptores_geograficos['descriptor_geografico']?>"><?php echo $row_descriptores_geograficos['descriptor_geografico']?></option>
    	                            <?php
} while ($row_descriptores_geograficos = mysql_fetch_assoc($descriptores_geograficos));
  $rows = mysql_num_rows($descriptores_geograficos);
  if($rows > 0) {
      mysql_data_seek($descriptores_geograficos, 0);
	  $row_descriptores_geograficos = mysql_fetch_assoc($descriptores_geograficos);
  }
?>
                                  </select>
    	                          <input type="submit" name="button10" id="button10" value="G" onClick="activar();" class="btonmedio"></td>
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
                  </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="89" height="30" class="niv_menu">Entidades</td>
    	              <td width="541"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v182; ?><!-- Eventos:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="evento" type="text" class="camposanchos" id="evento" value="<?php echo $row_diplomaticos['evento']; ?>"  /></td>
  	          </tr>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v183; ?><!-- Manifestaci&oacute;n:--></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="manifestacion" type="text" class="camposanchos" id="manifestacion" value="<?php echo $row_diplomaticos['manifestacion']; ?>"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor">&nbsp;</td>
  	          </tr>
             </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdabotones1"><?php if($s2!="C") { ?><?php if($_SESSION['estado'] != "Vigente") { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" onClick="activar();" class="botongrabar" id="button" value="Grabar" <?php if($_SESSION['situacion'] == "Baja") { echo "disabled";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
    	<tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
    </table>
    <input name="codigo_referencia2" type="hidden"  id="codigo_referencia2" value="<?php echo $row_diplomaticos['codigo_referencia']; ?>"  />
    <input type="hidden" name="MM_update" value="form2">
    <input name="tipo_diplomatico2" type="hidden"  id="tipo_diplomatico2" value="<?php echo $row_diplomaticos['tipo_diplomatico']; ?>"  />
    </form></div>
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
    <div id="f3c" style="display:<?php if($_SESSION['estado'] >= 3 && $_SESSION['na'] != 3) { echo "block"; } else { echo "none"; } ?>">
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
    <div id="f3" style="display:<?php if($_SESSION['estado'] >= 3 && $_SESSION['na'] == 3) { echo "block"; } else { echo "none"; } ?>">
    <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	<tr>
        	<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE ADMINISTRACI&Oacute;N</td>
    	          <td align="right"><a onClick="cerrarformularios(3)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
        </tr>
    	<tr>
    	  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	           <td class="separadormenor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><?php echo $v184; ?><!-- Forma de Ingreso: --></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><select name="forma_ingreso" id="forma_ingreso" class="camposanchos">
    	            <option value=""  <?php if (!(strcmp("", $row_diplomaticos['forma_ingreso']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_formas_ingreso['forma_ingreso']?>"<?php if (!(strcmp($row_formas_ingreso['forma_ingreso'], $row_diplomaticos['forma_ingreso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_formas_ingreso['forma_ingreso']?></option>
    	            <?php
} while ($row_formas_ingreso = mysql_fetch_assoc($formas_ingreso));
  $rows = mysql_num_rows($formas_ingreso);
  if($rows > 0) {
      mysql_data_seek($formas_ingreso, 0);
	  $row_formas_ingreso = mysql_fetch_assoc($formas_ingreso);
  }
?>
                  </select></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><?php echo $v185; ?><!-- Procedencia: --></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><textarea name="procedencia" rows="5" class="camposanchos" id="procedencia"><?php echo $row_diplomaticos['procedencia']; ?></textarea></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><?php echo $v186; ?><!-- Fecha de inicio del Ingreso: --></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><input name="fecha_inicio_ingreso" type="text" class="camposanchos" id="fecha_inicio_ingreso" value="<?php echo $row_diplomaticos['fecha_inicio_ingreso']; ?>"  /></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor" ><?php echo $v187; ?><!-- Precio:--></td>
  	          </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><input name="precio" type="text" class="camposanchos" id="precio" value="<?php echo $row_diplomaticos['precio']; ?>"  /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v188; ?><!-- Norma Legal de Ingreso:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><select name="norma_legal_ingreso" id="norma_legal_ingreso" class="camposanchos">
                  <option value=""  <?php if (!(strcmp("", $row_diplomaticos['norma_legal_ingreso']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_norma_legal['norma_legal']?>"<?php if (!(strcmp($row_norma_legal['norma_legal'], $row_diplomaticos['norma_legal_ingreso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_norma_legal['norma_legal']?></option>
                  <?php
} while ($row_norma_legal = mysql_fetch_assoc($norma_legal));
  $rows = mysql_num_rows($norma_legal);
  if($rows > 0) {
      mysql_data_seek($norma_legal, 0);
	  $row_norma_legal = mysql_fetch_assoc($norma_legal);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v189; ?><!-- N&uacute;mero Legal de Ingreso:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><input name="numero_legal_ingreso" type="text" class="camposanchos" id="numero_legal_ingreso" value="<?php echo $row_diplomaticos['numero_legal_ingreso']; ?>"  /></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v190; ?><!-- N&uacute;mero Administrativo:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><input name="numero_administrativo" type="text" class="camposanchos" id="numero_administrativo" value="<?php echo $row_diplomaticos['numero_administrativo']; ?>"  /></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v191; ?><!-- Derechos y Restricciones:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><textarea name="derechos_restricciones" rows="5" class="camposanchos" id="derechos_restricciones"><?php echo $row_diplomaticos['derechos_restricciones']; ?></textarea></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v192; ?><!-- Titular de Derechos:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><input name="titular_derecho" type="text" class="camposanchos" id="titular_derecho" value="<?php echo $row_diplomaticos['titular_derecho']; ?>"  /></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <?php if(($_SESSION['estadoregistral'] == "Vigente" || $_SESSION['estadoregistral'] == "Cancelado" ) && ($_SESSION['situacion'] == "Local" || $_SESSION['situacion'] == "Baja")) { ?>
              <tr>
               <td ><table width="630" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="63" height="30" class="niv_menu">Baja</td>
                   <td width="567"><hr></td>
                 </tr>
               </table></td>
              </tr>
              <tr>
               <td >&nbsp;</td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor"><?php echo $v193; ?><!-- Norma Legal de Baja:--></td>
              </tr>
              <tr>
               <td  class="tituloscampos ins_celdacolor">
                 <select name="normativa_legal_baja" id="normativa_legal_baja" class="camposanchos">
                   <option value=""  <?php if (!(strcmp("", $row_diplomaticos['normativa_legal_baja']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                   <?php
do {  
?>
                   <option value="<?php echo $row_norma_legal['norma_legal']?>"<?php if (!(strcmp($row_norma_legal['norma_legal'], $row_diplomaticos['normativa_legal_baja']))) {echo "selected=\"selected\"";} ?>><?php echo $row_norma_legal['norma_legal']?></option>
                   <?php
} while ($row_norma_legal = mysql_fetch_assoc($norma_legal));
  $rows = mysql_num_rows($norma_legal);
  if($rows > 0) {
      mysql_data_seek($norma_legal, 0);
	  $row_norma_legal = mysql_fetch_assoc($norma_legal);
  }
?>
                 </select>
              </td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor"><?php echo $v194; ?><!-- N&uacute;mero Norma Legal:--></td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor">
                 <input name="numero_norma_legal" type="text" class="camposanchos" id="numero_norma_legal" value="<?php echo $row_diplomaticos['numero_norma_legal']; ?>"  /></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor"><?php echo $v195; ?><!-- Motivo de la Baja:--></td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor"><textarea name="motivo_baja" rows="5" class="camposanchos" id="motivo_baja"><?php echo $row_diplomaticos['motivo_baja']; ?></textarea></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
               <td class="tituloscampos ins_celdacolor" ><?php echo $v196; ?><!-- Fecha Baja:--></td>
              </tr>
              <tr>
               <td  class="tituloscampos ins_celdacolor">D&iacute;a:
                   <input name="fecha_bajad" type="text" id="fecha_bajad" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_baja'],8,2); ?>">
Mes:
<input name="fecha_bajam" type="text" id="fecha_bajam" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_baja'],5,2); ?>">
A&ntilde;o:
<input name="fecha_bajaa" type="text" id="fecha_bajaa" size="12" maxlength="4" value="<?php echo substr($row_diplomaticos['fecha_baja'],0,4); ?>">
(dd/mm/aaaa)</td>
              </tr>
              <tr>
               <td >&nbsp;</td>
              </tr>
              <tr>
               <td ><table width="630" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td><hr></td>
                 </tr>
               </table></td>
              </tr>
              <tr>
                <td >&nbsp;</td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
			  <?php } ?>
              <tr>
                <td ><table width="630" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="136" height="30" class="niv_menu">Tasaci&oacute;n Expertizaje</td>
                    <td width="494"><hr></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v197; ?><!-- Valuaci&oacute;n:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><input name="valuacion" type="text" class="camposanchos" id="valuacion" value="<?php echo $row_documentos_tasaciones_expertizaje['valuacion']; ?>"  /></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v198; ?><!-- Valuaci&oacute;n USD:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><input name="valuacionusd" type="text" class="camposanchos" id="valuacionusd" value="<?php echo $row_documentos_tasaciones_expertizaje['valuacionusd']; ?>"  /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v199; ?><!-- Fecha Tasador Experto:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" >D&iacute;a:
                    <input name="fechavaluaciond" type="text" id="fechavaluaciond" size="8" maxlength="2" value="<?php echo substr($row_documentos_tasaciones_expertizaje['fecha'],8,2); ?>">
Mes:
<input name="fechavaluacionm" type="text" id="fechavaluacionm" size="8" maxlength="2" value="<?php echo substr($row_documentos_tasaciones_expertizaje['fecha'],5,2); ?>">
A&ntilde;o:
<input name="fechavaluaciona" type="text" id="fechavaluaciona" size="12" maxlength="4" value="<?php echo substr($row_documentos_tasaciones_expertizaje['fecha'],0,4); ?>">
(dd/mm/aaaa)</td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v211; ?><!-- Tasador Experto:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><input name="tasador_experto" type="text" class="camposanchos" id="tasador_experto" value="<?php echo $row_documentos_tasaciones_expertizaje['tasador_experto']; ?>"  /></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v212; ?><!-- Comentario Exspertizaje:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><textarea name="comentario_expertizaje" rows="6" class="camposanchos" id="comentario_expertizaje"><?php echo $row_documentos_tasaciones_expertizaje['comentario_expertizaje']; ?></textarea>
                  <input name="totalRows_documentos_tasaciones_expertizaje" type="hidden" id="totalRows_documentos_tasaciones_expertizaje" value="<?php  echo $totalRows_documentos_tasaciones_expertizaje; ?>"></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
              	<td><table width="630" border="0" cellspacing="0" cellpadding="0">
              	  <tr>
              	    
              	    <td width="630"><hr></td>
            	    </tr>
            	  </table></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v200; ?><!-- Subsidios Otorgados:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><textarea name="subsidios" rows="5" class="camposanchos" id="subsidios"><?php echo $row_diplomaticos['subsidios']; ?></textarea></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v201; ?><!-- Tipo de Acceso:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><select name="tipo_acceso" id="tipo_acceso" class="camposanchos">
                  <option value=""  <?php if (!(strcmp("", $row_diplomaticos['tipo_acceso']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_tipos_accesos['tipo_acceso']?>"<?php if (!(strcmp($row_tipos_accesos['tipo_acceso'], $row_diplomaticos['tipo_acceso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipos_accesos['tipo_acceso']?></option>
                  <?php
} while ($row_tipos_accesos = mysql_fetch_assoc($tipos_accesos));
  $rows = mysql_num_rows($tipos_accesos);
  if($rows > 0) {
      mysql_data_seek($tipos_accesos, 0);
	  $row_tipos_accesos = mysql_fetch_assoc($tipos_accesos);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v202; ?><!-- Requisitos de Acceso:--></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><select name="requisitos_acceso" id="requisitos_acceso" class="camposanchos">
                  <option value=""  <?php if (!(strcmp("", $row_diplomaticos['requisitos_acceso']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_requisitos_accesos['requisito_acceso']?>"<?php if (!(strcmp($row_requisitos_accesos['requisito_acceso'], $row_diplomaticos['requisitos_acceso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_requisitos_accesos['requisito_acceso']?></option>
                  <?php
} while ($row_requisitos_accesos = mysql_fetch_assoc($requisitos_accesos));
  $rows = mysql_num_rows($requisitos_accesos);
  if($rows > 0) {
      mysql_data_seek($requisitos_accesos, 0);
	  $row_requisitos_accesos = mysql_fetch_assoc($requisitos_accesos);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v203; ?><!-- Acceso Documentaci&oacute;n:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><select name="acceso_documentacion" id="acceso_documentacion" class="camposanchos">
                  <option value=""  <?php if (!(strcmp("", $row_diplomaticos['acceso_documentacion']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_acceso_documentacion['acceso_documentacion']?>"<?php if (!(strcmp($row_acceso_documentacion['acceso_documentacion'], $row_diplomaticos['acceso_documentacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_acceso_documentacion['acceso_documentacion']?></option>
                  <?php
} while ($row_acceso_documentacion = mysql_fetch_assoc($acceso_documentacion));
  $rows = mysql_num_rows($acceso_documentacion);
  if($rows > 0) {
      mysql_data_seek($acceso_documentacion, 0);
	  $row_acceso_documentacion = mysql_fetch_assoc($acceso_documentacion);
  }
?>
                </select></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v204; ?><!-- Servicio de Reproducci&oacute;n:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><select name="servicio_reproduccion" id="servicio_reproduccion" class="camposanchos">
                  <option value=""  <?php if (!(strcmp("", $row_diplomaticos['servicio_reproduccion']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_servicios_reproduccion['servicio_reproduccion']?>"<?php if (!(strcmp($row_servicios_reproduccion['servicio_reproduccion'], $row_diplomaticos['servicio_reproduccion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_servicios_reproduccion['servicio_reproduccion']?></option>
                  <?php
} while ($row_servicios_reproduccion = mysql_fetch_assoc($servicios_reproduccion));
  $rows = mysql_num_rows($servicios_reproduccion);
  if($rows > 0) {
      mysql_data_seek($servicios_reproduccion, 0);
	  $row_servicios_reproduccion = mysql_fetch_assoc($servicios_reproduccion);
  }
?>
                </select></td>
              </tr>
              <tr>
               <td class="separadormayor">&nbsp;</td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><?php echo $v205; ?><!-- Publicaciones e instrumentos de acceso:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" ><textarea name="publicaciones_instrumentos_accesos" rows="5" class="camposanchos" id="publicaciones_instrumentos_accesos"><?php echo $row_diplomaticos['publicaciones_instrumentos_accesos']; ?></textarea></td>
              </tr>
              <tr>
               <td class="separadormayor"></td>
              </tr>
              <tr>
                <td  class="tituloscampos ins_celdacolor"><?php echo $v206; ?><!-- Fecha &uacute;ltimo relevamiento visu:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor" >D&iacute;a:
                    <input name="fecha_ultimo_relevamiento_visud" type="text" id="fecha_ultimo_relevamiento_visud" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_ultimo_relevamiento_visu'],8,2); ?>">
Mes:
<input name="fecha_ultimo_relevamiento_visum" type="text" id="fecha_ultimo_relevamiento_visum" size="8" maxlength="2" value="<?php echo substr($row_diplomaticos['fecha_ultimo_relevamiento_visu'],5,2); ?>">
A&ntilde;o:
<input name="fecha_ultimo_relevamiento_visua" type="text" id="textfield11" size="12" maxlength="4" value="<?php echo substr($row_diplomaticos['fecha_ultimo_relevamiento_visu'],0,4); ?>">
(dd/mm/aaaa)</td>
              </tr>
              <tr>
               <td >&nbsp;</td>
              </tr>
             </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdabotones1"><?php if($s3!="C") { ?><?php if($_SESSION['estado'] != "Vigente") { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar" onClick="activar();" <?php if($_SESSION['situacion'] == "Baja") { echo "disabled";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
    	<tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
    </table><input name="codigo_referencia3" type="hidden"  id="codigo_referencia3" value="<?php echo $row_diplomaticos['codigo_referencia']; ?>"  />
    <input type="hidden" name="MM_update" value="form3">
    </form></div><?php } ?></td>
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
              <td class="ins_titulomayordes">&Aacute;REA DE NOTAS</td>
              <td align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <?php  } else { ?>
    <div id="f4c" style="display:<?php if($_SESSION['estado'] >= 4 && $_SESSION['na'] != 4) { echo "block"; } else { echo "none"; } ?>">
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
    <div id="f4" style="display:<?php if($_SESSION['estado'] >= 4 && $_SESSION['na'] == 4) { echo "block"; } else { echo "none"; } ?>">
    <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	<tr>
        	<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&Aacute;REA DE NOTAS</td>
    	          <td align="right"><a onClick="cerrarformularios(4)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
        </tr>
    	<tr>
    	  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td class="separadormenor"></td>
  	      </tr>
    	    <tr>
    	      <td class="separadormayor"></td>
  	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><?php echo $v207; ?><!-- Notas Descripcion:--></td>
  	      </tr>
    	    <tr>
    	      <td class="ins_celdacolor"><textarea name="nota_descripcion" rows="10" class="camposanchos" id="nota_descripcion"><?php echo $row_documentos_areasnotas['nota_descripcion']; ?></textarea>
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
    	      <td class="tituloscampos ins_celdacolor"><?php echo $v208; ?><!-- Notas del Archivero:--></td>
  	      </tr>
    	    <tr>
    	      <td class="ins_celdacolor"><textarea name="nota_archivero" rows="10" class="camposanchos" id="nota_archivero"><?php echo $row_documentos_areasnotas['nota_archivero']; ?></textarea>
    	        <script>
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
    	      <td class="tituloscampos ins_celdacolor"><?php echo $v209; ?><!-- Fuentes:--></td>
  	      </tr>
    	    <tr>
    	      <td class="separadormenor ins_celdacolor"><textarea name="fuentes" rows="5"  class="camposanchos" id="fuentes"><?php echo $row_documentos_areasnotas['fuentes']; ?></textarea></td>
  	      </tr>
    	    <tr>
    	      <td class="separadormayor"></td>
  	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor"><?php echo $v210; ?><!-- Fecha Descripci&oacute;n:--></td>
  	      </tr>
    	    <tr>
    	      <td class="ins_celdacolor camposmedios">D&iacute;a:
    	        <input name="fecha_descripciond" type="text" id="fecha_descripciond" size="8" maxlength="2" value="<?php echo substr($row_documentos_areasnotas['fecha_descripcion'],8,2); ?>">
    	        Mes:
    	        <input name="fecha_descripcionm" type="text" id="fecha_descripcionm" size="8" maxlength="2" value="<?php echo substr($row_documentos_areasnotas['fecha_descripcion'],5,2); ?>">
    	        A&ntilde;o:
    	        <input name="fecha_descripciona" type="text" id="fecha_descripciona" size="12" maxlength="4" value="<?php echo substr($row_documentos_areasnotas['fecha_descripcion'],0,4); ?>">
    	        (dd/mm/aaaa)</td>
  	      </tr>
    	    <tr>
    	      <td >&nbsp;</td>
  	      </tr>
  	    </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdabotones1"><?php if($s4!="C") { ?><?php if($_SESSION['estado'] != "Vigente") { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar" onClick="activar();" <?php if($_SESSION['situacion'] == "Baja") { echo "disabled";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
    	<tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
    </table><input name="codigo_referencia4" type="hidden"  id="codigo_referencia4" value="<?php echo $row_diplomaticos['codigo_referencia']; ?>"  />
    <input type="hidden" name="MM_update" value="form4">
    </form></div><?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><!--<p>AREA DE IDENTIFICACION</p>
    <p>C&oacute;digo de Referencia de La Instituci&oacute;n</p>
    <p>C&oacute;digo de Referencia del Nivel:</p>
    <p>Tipo documento diplomatico</p>
    <p>T&iacute;tulo Original:</p>
    <p>Titulo Atribuido:</p>
    <p>T&iacute;tulo Traducido:</p>
    <p>Fecha de registro:</p>
    <p>N&uacute;meros de inventario:</p>
    <p>N&uacute;mero de registro inventario anterior:</p>
    <p>tipo de documento--------------------------------------------------------</p>
<p>tipo general de documento (cb)</p>
    <p>tipo espesifico de documento (cb)</p>
    <p>tradicion documental (cb)</p>
    <p>signatura topografica---------------------------------------------------- uno solo</p>
    <p>edificio (cb) </p>
    <p>ubicacion (txt)</p>
    <p>contenedores ----------------------------------------------------------- multi</p>
    <p>contenedor (cb) </p>
    <p>ruta de acceso (txt)</p>
    <p>&nbsp;</p>
    <p>object ID SUR (popup sur)</p>
    <p>vista registro bibliografico (popup biblioteca)</p>
    <p>---------------------------------------------------------------------------------------------------------</p> 
    <p>AREA DE DESCRIPCION</p>
<p>sistema de organizacion ( cb) </p>
    <p>autor (cb) </p>
    <p>nombre productor (cb)</p>
    <p>publicacion ------------------------------------------------------</p>
    <p>fecha de edicion y a&ntilde;o editorial (txt)</p>
    <p>fecha accion representada (txt)</p>
    <p>palabras claver (cb &amp; txt)    </p>
    <p>version (cb)</p>
    <p>genero (cb)</p>
    <p>signos especiales (txt) (8,11)</p>
    <p>fechas ------------------------------------------------------------</p>
    <p>fecha inicial (dt)(8,9,10,11)</p>
    <p>fecha final (dt)(8,9,10,11)</p>
    <p>alcance contenido (txt)(8,9,10,11)</p>
    <p>-----------------------------------------------------------------</p>
    <p>soporte (cb) (8,9,10,11)</p>
    <p>idiomas(M)(9,10,11)</p>
    <p>duracion metratje (txt) (8,9,10)</p>
    <p>cromia (cb) (8,9,)</p>
    <p>banda sonora (M) (rubro, autor))(9,10)</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>tecnica---------------------------------------------------------- (8)</p>
    <p>tecnica fotografica (cb) (8)</p>
    <p>tecnica visual (cb) (8)</p>
    <p>tecnica digital (cb) (8)</p>
    <p>-------------------------------------------------------------</p>
    <p>emulsion (cb) (8)</p>
    <p>integridad (txt) (8,9,10,11)</p>
    <p>forma presentacion unidad (txt) (8,11)</p>
    <p>cantidad fojas del album (txt)(8,11)</p>
    <p>caracteristicas del montaje del dcomento (cb)(8,11)</p>
    <p>envases (cb) multivalorado(8,9,10)</p>
    <p>requisitos de ejecucion(cb)(8,9,10,11)</p>
    <p>volumen -------------------------------------------------------</p>
    <p>unidades (txt)(8,9,10,11)</p>
    <p>cantidad de envases de la unidad documental (txt)(8,9,10,11)</p>
    <p>coleccion (txt)(8,9,10,11)</p>
    <p>descriptores ---------------------------------------------------</p>
    <p>descriptores de materias del contenido (cb) multivalorado(8,9,10,11)</p>
    <p>descriptores onomasticos (cb) multivalorado(8,9,10,11)</p>
    <p>descriptores geograficos (cb) multivalorado(8,9,10,11)</p>
    <p>entidades---------------------------------------------------------</p>
    <p>evento(txt)(8,9,10,11)</p>
    <p>manifestacion (txt)(8,9,10,11)</p>
    <p>-----------------------------------------------------------------------------------------------------------</p> -->
 <!--   
<p>ADMINISTRACION</p>
    <p>Forma de ingreso(cb)(8,9)</p>
    <p>Procendencia (text)(8,9)</p>
    <p>Fecha de incio del ingreso(8,9)</p>
    <p>Precio(txt)(8,9)</p>
    <p>Norma legal de de ingreso(cb)(8,9)</p>
    <p>Numero legal de ingreso(txt)(8,9)</p>
    <p>Numero Administrativo:(txt)(8,9)</p>
    <p>Derechos/Restricciones(txt)(8,9)</p>
    <p>Titular de Derechos(txt)(8,9)</p>
    <p>Exposiciones-------------------------(si esta vigente, y no esta en prestamos, y no robado, y no localizado)</p>
    <p>codigo exposicion(cb)(8,9)</p>
    <p>numero asignado (txt)(8,9)</p>
    <p>fecha de devolucion (DT)(8,9)</p>
    <p>----------------------------------------------</p>
    <p>Documentos en prestamo ----------------(condiciones )----------------------</p>
    <p>forma (txt)(8,9)</p>
    <p>institucion destinataria (cb)(8,9)</p>
    <p>fecha inicio (DT)(8,9)</p>
    <p>fecha termino (DT)(8,9)</p>
    <p>normal legal del prestamos (cb)(8,9)</p>
    <p>numero legal de prestamo (txt)(8,9)</p>
    <p>transporte (cb)(8,9)</p>
    <p>deposito--------<br>
      nomre, estanteria, contenedor, grilla planera (txt)</p>
    <p>nombre exhibicion (txt)</p>
    <p>vitrinas(txt)</p>
    <p>fecha de devolucion</p>
    <p>--------------------------------------</p>
    <p>Documentos no localizados-------------------------(condiciones)---------</p>
    <p>motivo(txt)</p>
    <p>numero nota (txt)</p>
    <p>fecha de recuperacion</p>
    <p>--------------------------------------</p>
    <p>Documentos robados---------------------------(condiciones )----------</p>
    <p>fecha sustraido</p>
    <p>fecha resolucion apertura sumario</p>
    <p>fecha resolucion cierre sumario</p>
    <p>denunciante(txt)</p>
    <p>numero causa judicial(txt)</p>
    <p>numero juzgado(txt)</p>
    <p>tipo tramite administrativo (txt)</p>
    <p>numero sumario administrativo (txt)</p>
    <p>fallo (txt)</p>
    <p>fecha de recupeacion (DT)</p>
    <p>identificador de interpol(txt)</p>
    <p>fecha registro interpol (DT)</p>
    <p>------------------------------------------------</p>
    <p>Baja----------------------------------------(condiciones )--------------</p>
    <p>normal legal de baja(cb)</p>
    <p>numero norma legal (txt)</p>
    <p>motivo baja (txt)</p>
    <p>fecha baja (DT)</p>
    <p>----------------------------------------------------------------------</p>
    <p>Tasacion expertizaje-----------------------------------------------------</p>
    <p>valuacion (txt)</p>
    <p>valuacion USD (txt)</p>
    <p>fecha tasador experto</p>
    <p>comentario expertizaje(txt)</p>
    <p>----------------------------------------------------------------------</p>
    <p>subsidios otorgados(txt)</p>
    <p>tipo acceso (cb)</p>
    <p>requisito acceso (cb)</p>
    <p>acceso documentacion (cb)</p>
    <p>servicio de reproduccion (cb)</p>
    <p>publicaciones e instrumentos de acceso(txt)</p>
    <p>fecha ultimo relevamiento visu(DT)</p>
    <p>--------------------------------------------------------------------------------------------------------</p>
    <p>AREA DE NOTAS </p>
    <p>Notas Descripcion</p>
    <p>Notas del Archivero</p>
    <p>Notas del Archivero</p>
    <p>Fecha Descripci&oacute;n</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>--></td>
  </tr>
  <tr>
    <td><?php if(!isset($_GET['vr'])) { ?><a href="fr_niveles.php?upi=<?php echo $row_diplomaticos['codigo_referencia']; ?>" target="_parent"><img src="images/flecha.png" width="79" height="22" border="0"></a><?php } ?></td>
  </tr>
</table>
<?php require_once('activo.php'); ?>
</body>
</html>
<?php
mysql_free_result($diplomaticos);

mysql_free_result($estado_registral);

mysql_free_result($rel_documentos_edificios);

mysql_free_result($edificios);

mysql_free_result($rel_documentos_contenedores);

mysql_free_result($contenedores);

mysql_free_result($tipos_general_documentos);

mysql_free_result($tipos_especificos_documentos);

mysql_free_result($tradiciones_documentales);

mysql_free_result($palabra_clave);

mysql_free_result($rel_documentos_idiomas);

mysql_free_result($idiomas);

mysql_free_result($rel_documentos_rubros_autores);

mysql_free_result($autores);

mysql_free_result($rubros);

mysql_free_result($sistemas_organizaciones);

mysql_free_result($registro_autoridad);

mysql_free_result($versiones);

mysql_free_result($generos);

mysql_free_result($soportes);

mysql_free_result($cromias);

mysql_free_result($tecnicas_fotograficas);

mysql_free_result($tecnicas_visuales);

mysql_free_result($tecnicas_digitales);

mysql_free_result($emulsiones);

mysql_free_result($caracteristicas_montaje);

mysql_free_result($requisitos_ejecucion);

mysql_free_result($rel_documentos_envases);

mysql_free_result($rel_documentos_descriptores_materias_contenidos);

mysql_free_result($rel_documentos_descriptores_onomasticos);

mysql_free_result($rel_documentos_descriptores_geograficos);

mysql_free_result($descriptores_geograficos);

mysql_free_result($descriptores_onomasticos);

mysql_free_result($descriptores_materias_contenidos);

mysql_free_result($envases);

mysql_free_result($fomas_presentacion_unidad);

mysql_free_result($sonidos);

mysql_free_result($formas_ingreso);

mysql_free_result($norma_legal);

mysql_free_result($tipos_accesos);

mysql_free_result($documentos_areasnotas);

mysql_free_result($rel_documentos_exposiciones);

mysql_free_result($exposiciones);

mysql_free_result($rel_documentos_exposiciones2);

mysql_free_result($tips);

mysql_free_result($auditoria_usuarios);

mysql_free_result($documentos_tasaciones_expertizaje);

?>
