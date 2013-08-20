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

$usuario = $_SESSION['MM_Username'];

function deleteAux($str,$database_conn,$conn) {

		$deleteSQL_D1 = $str;
 	 	mysql_select_db($database_conn, $conn);
  		$Result_SQL_D1 = mysql_query($deleteSQL_D1, $conn) or die(errordisplay('005',$str." - ".mysql_error()));	
}




//damos de baja estructuras************************************************
if(isset($_POST['MM_baja']) && $_POST['MM_baja'] == "form1" && $_POST['normativa_legal_baja'] != "" && $_POST['numero_norma_legal'] != ""  && $_POST['motivo_baja'] != "" && $_POST['fecha_bajaa'] != "") {
	
	
	//BUSCAMOS LA INSTITUCION INDIVIDUAL PARA ACOTAR LA BUSQUEDA DE ELIMINADO***********************************
	mysql_select_db($database_conn, $conn);
	$query_vis_buscaInstitucion = "SELECT codigo_institucion FROM vis_estados_niveles WHERE codigo_referencia='".$_POST['code']."'";
	$vis_buscaInstitucion = mysql_query($query_vis_buscaInstitucion, $conn) or die(mysql_error());
	$row_vis_buscaInstitucion = mysql_fetch_assoc($vis_buscaInstitucion);
	$totalRows_vis_buscaInstitucion = mysql_num_rows($vis_buscaInstitucion);
	
	if($totalRows_vis_buscaInstitucion <= 0) {
		$cod_Instit="-1";
	} else {
		$cod_Instit=$row_vis_buscaInstitucion['codigo_institucion'];
	}
	
	mysql_free_result($vis_buscaInstitucion);
	
	
	//BUSCAMOS LOS REGISTROS QUE SE VAN A DAR DE BAJA************************************************************
	mysql_select_db($database_conn, $conn);
	$query_vis_nivelesBaja = "SELECT * FROM vis_estados_niveles WHERE codigo_institucion='".$cod_Instit."' AND (codigo_referencia LIKE '".$_POST['code']."/%' OR codigo_referencia='".$_POST['code']."' OR codigo_referencia LIKE '".$_POST['code']."_%')  ORDER BY codigo_referencia DESC";
	$vis_nivelesBaja = mysql_query($query_vis_nivelesBaja, $conn) or die(mysql_error());
	$row_vis_nivelesBaja = mysql_fetch_assoc($vis_nivelesBaja);
	$totalRows_vis_nivelesBaja = mysql_num_rows($vis_nivelesBaja);
	
	//COMIENZA EL LOOP PARA DAR DE BAJA LOS ELEMENTOS****************************************
	do {

	if($row_vis_nivelesBaja['tipo'] == 0 && $row_vis_nivelesBaja['estado'] != "Cancelado") {
		//echo $row_vis_nivelesBaja['codigo_referencia']."-".$row_vis_nivelesBaja['tipo']."-ins<br>";
		
		$update_ins = sprintf("UPDATE instituciones SET usuario=%s, fecha_ultima_modificacion=%s WHERE codigo_identificacion=%s",
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result6= mysql_query($update_ins, $conn) or die(errordisplay('012',mysql_error()));
		
		$insert_ins = sprintf("INSERT INTO instituciones_estados (codigo_referencia, estado, fecha, usuario, motivo) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"),
						GetSQLValueString("Cancelado", "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result5= mysql_query($insert_ins, $conn) or die(errordisplay('011',mysql_error()));
		
	} else if($row_vis_nivelesBaja['tipo'] >=1 && $row_vis_nivelesBaja['tipo'] <= 7 && $row_vis_nivelesBaja['estado'] != "Cancelado") {
		//echo $row_vis_nivelesBaja['codigo_referencia']."-".$row_vis_nivelesBaja['tipo']."-niv<br>";
		
		$update_niv = sprintf("UPDATE niveles SET usuario_ultima_modificacion=%s, fecha_ultima_modificacion=%s, normativa_legal_baja=%s, numero_norma_legal=%s, motivo=%s, fecha_baja=%s WHERE codigo_referencia=%s",
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString($_POST['normativa_legal_baja'], "text"),
						GetSQLValueString($_POST['numero_norma_legal'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"),
						GetSQLValueString(fechar($_POST['fecha_bajad'],$_POST['fecha_bajam'],$_POST['fecha_bajaa']), "text"),
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result4= mysql_query($update_niv, $conn) or die(errordisplay('010',mysql_error()));
		
		$insert_niv = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, fecha, usuario, motivo) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"),
						GetSQLValueString("Cancelado", "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result3= mysql_query($insert_niv, $conn) or die(errordisplay('009',mysql_error()));
		
	} else if ($row_vis_nivelesBaja['tipo'] >=8 && $row_vis_nivelesBaja['tipo'] <= 11 && $row_vis_nivelesBaja['estado'] != "Cancelado") {
		//echo $row_vis_nivelesBaja['codigo_referencia']."-".$row_vis_nivelesBaja['tipo']."-doc<br>";
			
		$update_doc = sprintf("UPDATE documentos SET usuario=%s, fecha_ultima_modificacion=%s, situacion=%s, normativa_legal_baja=%s, numero_norma_legal=%s, motivo_baja=%s, fecha_baja=%s WHERE codigo_referencia=%s",
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString("Baja", "text"),
						GetSQLValueString($_POST['normativa_legal_baja'], "text"),
						GetSQLValueString($_POST['numero_norma_legal'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"),
						GetSQLValueString(fechar($_POST['fecha_bajad'],$_POST['fecha_bajam'],$_POST['fecha_bajaa']), "text"),
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result= mysql_query($update_doc, $conn) or die(errordisplay('007',mysql_error()));
		
		$insert_doc = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, fecha, usuario, motivo) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($row_vis_nivelesBaja['codigo_referencia'], "text"),
						GetSQLValueString("Cancelado", "text"),
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
						GetSQLValueString($_SESSION['MM_Username'], "text"),
						GetSQLValueString($_POST['motivo_baja'], "text"));
		mysql_select_db($database_conn, $conn);
		$Result2= mysql_query($insert_doc, $conn) or die(errordisplay('008',mysql_error()));
	}		
	}  while ($row_vis_nivelesBaja = mysql_fetch_assoc($vis_nivelesBaja));
	
	mysql_free_result($vis_nivelesBaja);
}

//REVERTIMOS fisicamente BAJAS***************************************
if(isset($_POST['MM_revbaja']) && $_POST['MM_revbaja'] == "form_reverirBaja") {
	
	$codInst=$_POST['inst'];
	$codElemento=$_POST['code'];
	
	//BUSCAMOS EL ELEMENTO**********************************************************************************
	mysql_select_db($database_conn, $conn);
	$query_buscaElemento="SELECT * FROM vis_estados_niveles WHERE codigo_referencia=".GetSQLValueString($codElemento, "text")." AND codigo_institucion='".$codInst."'";
	$buscaElemento = mysql_query($query_buscaElemento, $conn) or die(mysql_error());
	$row_buscaElemento = mysql_fetch_assoc($buscaElemento);
	$totalRows_buscaElemento = mysql_num_rows($buscaElemento);
	
	if($totalRows_buscaElemento == 1) {
		
		$estado=$row_buscaElemento['estado'];
		$estadoSuperior = $row_buscaElemento['estado_cod_ref_sup'];
		$tipo=$row_buscaElemento['tipo_diplomatico'];
		$codigoReferencia=$row_buscaElemento['codigo_referencia'];
		
		//echo $estado." ".$estadoSuperior." ".$tipo;
		
		if($estadoSuperior != "Cancelado") {
			
			if($tipo==0) {
				$tabla="instituciones";
				$tablaEstado="instituciones_estados";
				$campoBusqueda="codigo_identificacion";
			} else if($tipo >= 1 && $tipo <= 5) {
				$tabla="niveles";
				$tablaEstado="niveles_estados";
				$campoBusqueda="codigo_referencia";
			} else if($tipo >= 8 && $tipo <= 11) {
				$tabla="documentos";
				$tablaEstado="documentos_estados";
				$campoBusqueda="codigo_referencia";
			}
			
			//buscamos el estado anterior al cancelado
			mysql_select_db($database_conn, $conn);
			$query_buscaEstado="SELECT * FROM ".$tablaEstado." WHERE codigo_referencia='".$codigoReferencia."' ORDER BY fecha DESC LIMIT 1,1";
			$buscaEstado = mysql_query($query_buscaEstado, $conn) or die(mysql_error());
			$row_buscaEstado = mysql_fetch_assoc($buscaEstado);
			$totalRows_buscaEstado = mysql_num_rows($buscaEstado);
			
			$estadoAnterior=$row_buscaEstado['estado'];
			
			mysql_free_result($buscaEstado);
			
			//buscamos datos en la tabla de documentos
			mysql_select_db($database_conn, $conn);
			$query_documentos="SELECT * FROM ".$tabla." WHERE ".$campoBusqueda."='".$codigoReferencia."'";
			$documentos = mysql_query($query_documentos, $conn) or die(mysql_error());
			$row_documentos = mysql_fetch_assoc($documentos);
			$totalRows_documentos = mysql_num_rows($documentos);
			
			$normativa_legal_baja=$row_documentos['normativa_legal_baja'];
			$numero_norma_legal=$row_documentos['numero_norma_legal'];
			$motivo_baja=$row_documentos['motivo_baja'];
			$fecha_baja=$row_documentos['fecha_baja'];
			
			mysql_free_result($documentos);
			
			$motivo="MOTIVO REVERSA: ".$_POST['motivo_revertirbaja'];
			$motivo.=" *********** ";
			$motivo.="NORMATIVA LEGAL DE BAJA: ".$normativa_legal_baja;
			$motivo.=" *********** ";
			$motivo.="NUMERO NORMA LEGAL:".$numero_norma_legal;
			$motivo.=" *********** ";
			$motivo.="MOTIVO DE LA BAJA: ".$motivo_baja;
			$motivo.=" *********** ";
			$motivo.="FECHA DE LA BAJA: ".$fecha_baja;
			
			//***********************************************************************
			
			$insertSQL1 = sprintf("INSERT INTO ".$tablaEstado." (codigo_referencia, estado, motivo, usuario) VALUES (%s, %s, %s, %s)",
									GetSQLValueString($codigoReferencia, "text"),
									GetSQLValueString($estadoAnterior, "text"),
									GetSQLValueString($motivo, "text"),
									GetSQLValueString($_SESSION['MM_Username'], "text"));
			
			if($tipo==0) {
				$insertSQL2 = "UPDATE ".$tabla." SET fecha_ultima_modificacion=CURRENT_TIMESTAMP WHERE codigo_identificacion='".$codigoReferencia."'";
			} else if($tipo >= 1 && $tipo <= 5) {
				$insertSQL2 = "UPDATE ".$tabla." SET normativa_legal_baja=null, numero_norma_legal=null, motivo=null, fecha_baja=null, fecha_ultima_modificacion=CURRENT_TIMESTAMP WHERE codigo_referencia='".$codigoReferencia."'";
			} else if($tipo >= 8 && $tipo <= 11) {
				$insertSQL2 = "UPDATE ".$tabla." SET normativa_legal_baja=null, numero_norma_legal=null, motivo_baja=null, fecha_baja=null, situacion='Local', fecha_ultima_modificacion=CURRENT_TIMESTAMP WHERE codigo_referencia='".$codigoReferencia."'";
			}
			
			$error=0;
			
			mysql_select_db($database_conn, $conn);
	 		mysql_query("BEGIN");
	 		$Result1 = mysql_query($insertSQL1, $conn);
	 			if (!$Result1) { $error = 1; $errT="007"; $errorTXT .="1-".mysql_error()."<br>"; }
  	 		$Result2 = mysql_query($insertSQL2, $conn);
	 			if (!$Result2) { $error = 1; $errT="009"; $errorTXT .="2-".mysql_error()."<br>"; }
			
			if ($error == 1){
				mysql_query("ROLLBACK"); 
				echo "<script language=\"javascript\"> alert('Hubo problemas en la reversa. Copie el mensaje y envíelo a sistemas.'); </script>";
				errordisplay($errT,$errorTXT); 
			} else {
				mysql_query("COMMIT");
			}
		
		} else {
			echo "<script language=\"javascript\"> alert('El elemento superior esta cancelado. No se puede habilitar este elemento.'); </script>";  
		}
	} else {
		echo "<script language=\"javascript\"> alert('No hay elemento para habilitar o tiene algun error el código de identificación.'); </script>";  
	}
	
	mysql_free_result($buscaElemento);
}

//eliminamos fisicamente estructuras***************************************
if(isset($_POST['eliminar']) && $_POST['eliminar'] == "si") {
	
	
	//BUSCAMOS LA INSTITUCION INDIVIDUAL PARA ACOTAR LA BUSQUEDA DE ELIMINADO***********************************	
	$cod_Instit2=$_POST['inst'];
	
	//BUSCAMOS QUE NO HAYA NINGUN ELEMINTO DE LA RAMA QUE HAYA ESTADO EN ESTADO VIGENTE*************************
	mysql_select_db($database_conn, $conn);
	$query_vis_nivelesElim = "SELECT codigo_referencia FROM vis_estados_niveles WHERE codigo_referencia LIKE '".$_POST['code']."%' AND codigo_institucion='".$cod_Instit2."' AND vigente=1";
	$vis_nivelesElim = mysql_query($query_vis_nivelesElim, $conn) or die(mysql_error());
	$row_vis_nivelesElim = mysql_fetch_assoc($vis_nivelesElim);
	$totalRows_vis_nivelesElim = mysql_num_rows($vis_nivelesElim);
	
	mysql_free_result($vis_nivelesElim);
	
	//si no hay ningun documento para abajo en estado que no se pueda eliminar seguimos************
	if($totalRows_vis_nivelesElim == 0) {
		
		//buscamos los objetos exactos para eliminar
		mysql_select_db($database_conn, $conn);
		$query_preparaEliminacion = "SELECT codigo_referencia FROM vis_estados_niveles WHERE codigo_referencia LIKE '".$_POST['code']."%' AND codigo_institucion='".$cod_Instit2."' AND vigente=0";
		$preparaEliminacion = mysql_query($query_preparaEliminacion, $conn) or die(mysql_error());
		$row_preparaEliminacion = mysql_fetch_assoc($preparaEliminacion);
		$totalRows_preparaEliminacion = mysql_num_rows($preparaEliminacion);
		
		$in_STR="('-5')";
		if($totalRows_preparaEliminacion > 0) {
			$in_STR ="('-1',";
			do {
				$in_STR .="'".$row_preparaEliminacion['codigo_referencia']."',";
			} while ($row_preparaEliminacion = mysql_fetch_assoc($preparaEliminacion));
			$in_STR .="'-2')";
		}
		mysql_free_result($preparaEliminacion);
		
		//echo $in_STR."|".$_POST['code']."|".$cod_Instit2;
		
		//eliminamos DOCUMENTOS
		$tablasDocumentos = array("rel_documentos_contenedores", "rel_documentos_edificios", "palabras_claves","rel_documentos_idiomas","rel_documentos_rubros_autores","rel_documentos_envases","rel_documentos_descriptores_materias_contenidos","rel_documentos_descriptores_onomasticos","rel_documentos_descriptores_geograficos","rel_documentos_exposiciones","documentos_areasnotas","documentos_estados","archivos_digitales","documentos_no_localizados","documentos_tasaciones_expertizaje","documentos");
		
		$errorTXT = "";
		$error=0;
		$l=1;
		mysql_select_db($database_conn, $conn);
	 	mysql_query("BEGIN");
		foreach ($tablasDocumentos as $value) {
			
			$deleteSQL = "DELETE FROM ".$value." WHERE codigo_referencia IN ".$in_STR;
			//deleteAux($deleteSQL, $database_conn, $conn);
			$Result2 = mysql_query($deleteSQL, $conn);
				if (!$Result2) { $error = 1; $errT="005"; $errorTXT .=$value."::: ".$l."-".mysql_error()."<br>"; }
			$l++;
		
		}
		if ($error == 1){
			mysql_query("ROLLBACK"); 
			errordisplay($errT,$errorTXT); 
		} else {
			mysql_query("COMMIT");
		}
		
		//eliminamos NIVELES
		$tablasNiveles = array("niveles_inventario","rel_niveles_edificios","rel_niveles_contenedores","rel_niveles_productores","rel_niveles_soportes","rel_niveles_idiomas","rel_niveles_niveles","rel_niveles_institucionesinternas","rel_niveles_institucionesexternas","rel_niveles_sistemasorganizacion","niveles_tasacion_expertizaje","niveles_areasnotas","niveles_estados","niveles");
		
		$errorTXT = "";
		$error=0;
		$l=1;
		mysql_select_db($database_conn, $conn);
	 	mysql_query("BEGIN");
		foreach ($tablasNiveles as $value) {
			$suple="";
			if($value == "rel_niveles_niveles") {
				$campo="codigo_referencia1";
				$suple=" OR codigo_referencia2 IN ".$in_STR;
			} else {
				$campo="codigo_referencia";
			}
			
			$deleteSQL = "DELETE FROM ".$value." WHERE ".$campo." IN ".$in_STR.$suple;
			//deleteAux($deleteSQL, $database_conn, $conn);
			$Result1 = mysql_query($deleteSQL, $conn);
				if (!$Result1) { $error = 1; $errT="005"; $errorTXT .=$value."::: ".$l."-".mysql_error()."<br>"; }
			$l++;
		}
			
		if ($error == 1){
			mysql_query("ROLLBACK"); 
			errordisplay($errT,$errorTXT); 
		} else {
			mysql_query("COMMIT");
		}
		
		
		//eliminamos INSTITUCIONES
		$tablasNiveles = array("direcciones_instituciones","responsable_institucion","responsable_archivo","formas_autorizadas_nombre","rel_instituciones_serviciosdereproduccion","area_de_notas_instituciones","instituciones_estados","instituciones");
		
		foreach ($tablasNiveles as $value) {
			if($value == "formas_autorizadas_nombre" || $value == "instituciones") {
				$campo="codigo_identificacion";
			} else if ($value == "instituciones_estados") {
				$campo="codigo_referencia";
			} else {
				$campo="codigo_institucion";
			}
			
			$deleteSQL = "DELETE FROM ".$value." WHERE ".$campo." IN ".$in_STR;
			deleteAux($deleteSQL, $database_conn, $conn);	
		}
		
		if(isset($_GET['pascant'])) { 
			$r="&pascant=s"; 
		} else { 
			$r=""; 
		}
  	
		$updateGoTo = "nivelesfull.php?cod=".$_GET['cod'].$r;
  		header(sprintf("Location: %s", $updateGoTo));	
		
	} else {
		
		if(isset($_GET['pascant'])) { 
			$r="&pascant=s"; 
		} else { 
			$r=""; 
		}
  		$updateGoTo = "nivelesfull.php?cod=".$_GET['cod'].$r."&el=".$_POST['code'];
  		header(sprintf("Location: %s", $updateGoTo));	
		
	}
		
}

//insertamos los niveles y los documentos compuestos***********************
if (isset($_POST['cs']) && isset($_POST['cn']) && isset($_POST['ci']) && isset($_POST['tn']) && $_POST['cs'] != "" && $_POST['cn'] != "" && $_POST['ci'] != "" && $_POST['tn'] != "") {
	
//cs: conigo del nivel superior
//cn: codigo del nivel que se esta ingresando
//--- con los dos anteriores se forma el codigo de referencia del nivel
//ci: codigo de la institucion a la que pertenece
//tn: tipo de nivel que se esta ingresando	


$codigo_armado="";
$cn=$_POST['cn'];
$cn=estabilizarcodigo($cn);
$cn=strtoupper($cn);
$codigo_armado=$_POST['cs']."/".$cn;


if($cn != ""){	
if($_POST['tn'] <= 5) {
$insertSQL1 = sprintf("INSERT INTO niveles (codigo_referencia, tipo_nivel, cod_ref_sup, codigo_institucion, tv, fecha_ultima_modificacion, usuario_ultima_modificacion) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($codigo_armado, "text"),
                       GetSQLValueString($_POST['tn'], "text"),
                       GetSQLValueString($_POST['cs'], "text"),
                       GetSQLValueString($_POST['ci'], "text"),
					   GetSQLValueString($_POST['tv'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));
					   


  //mysql_select_db($database_conn, $conn);
  //$Result1 = mysql_query($insertSQL1, $conn) or die(errordisplay('001',mysql_error()));
  
  $estadoT = "Inicio";
  if($_POST['tn'] == 5) {
  $estadoT = "Vigente";  
  }
  
  $insertSQL2 = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($codigo_armado, "text"),
                GetSQLValueString($estadoT, "text"),
  				GetSQLValueString($usuario, "text"));
  
    
 	 mysql_select_db($database_conn, $conn);
	 mysql_query("BEGIN");
	 $Result1 = mysql_query($insertSQL1, $conn);
	 	if (!$Result1) { $error = 1; $errT="001"; $errorTXT .="1-".mysql_error()."<br>"; }
  	 $Result2 = mysql_query($insertSQL2, $conn);
	 	if (!$Result2) { $error = 1; $errT="003"; $errorTXT .="2-".mysql_error()."<br>"; }
	
	if ($error == 1){
		mysql_query("ROLLBACK"); 
		errordisplay($errT,$errorTXT); 
	} else {
		mysql_query("COMMIT");
	}
	
}

//insertamos los documentos simples****************************************
if($_POST['tn'] >= 8 && $_POST['tn'] <= 11) {
	
	

	
//echo "cs:".$_POST['cs']."<br>cn:".$_POST['cn']."<br>tn:".$_POST['tn']."<br>ci:".$_POST['ci']."<br>tv:".$_POST['tv'];	
	
	$insertSQL1 = sprintf("INSERT INTO documentos (codigo_referencia, tipo_diplomatico, cod_ref_sup, codigo_institucion, tv, fecha_ultima_modificacion, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s)",
						GetSQLValueString($codigo_armado, "text"),
                       GetSQLValueString($_POST['tn'], "text"),
                       GetSQLValueString($_POST['cs'], "text"),
                       GetSQLValueString($_POST['ci'], "text"),
					   GetSQLValueString($_POST['tv'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));
  	
	
	$insertSQL2 = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($codigo_armado, "text"),
                GetSQLValueString("Inicio", "text"),
  				GetSQLValueString($usuario, "text"));
  
 	 mysql_select_db($database_conn, $conn);
	 mysql_query("BEGIN");
	$Result1 = mysql_query($insertSQL1, $conn);
		if (!$Result1) { $error = 1; $errT="002"; $errorTXT .="1-".mysql_error()."<br>"; }
  	$Result2 = mysql_query($insertSQL2, $conn);
		if (!$Result2) { $error = 1; $errT="004"; $errorTXT .="2-".mysql_error()."<br>"; }
		
	if ($error == 1){
		mysql_query("ROLLBACK"); 
		errordisplay($errT,$errorTXT); 
	} else {
		mysql_query("COMMIT");
	}
	
}


	$r="";
	if(isset($_GET['pascant'])) {
		$r="&pascant=s";
	}
	
  $updateGoTo = "nivelesfull.php?cod=".$_GET['cod'].$r."#".$codigo_armado;
  header(sprintf("Location: %s", $updateGoTo));
}
}


//buscamos la insitucion por palabra****************************************
$armado= "";
if(isset($_GET['pal']) && $_GET['pal'] != "") {

	$PAL = GetSQLValueString($_GET['pal'], "text");
	$PAL = str_replace(" ", "|", $PAL);

	mysql_select_db($database_conn, $conn);
	$query_datos_instituciones = "(SELECT I.codigo_identificacion FROM instituciones I
LEFT JOIN direcciones_instituciones DI ON (I.codigo_identificacion=DI.codigo_institucion)  WHERE DI.calle REGEXP $PAL OR DI.provincia REGEXP $PAL OR DI.ciudad REGEXP $PAL OR DI.pais REGEXP $PAL OR I.formas_conocidas_nombre REGEXP $PAL OR I.historia REGEXP $PAL)";
	if(isset($_GET['ins']) && $_GET['ins'] == "si") {
	$query_datos_instituciones .=" UNION ";
	$query_datos_instituciones .="(SELECT DISTINCT codigo_institucion FROM vis_estados_niveles WHERE nombre REGEXP $PAL)";
	}
	//echo $query_datos_instituciones;
	$datos_instituciones = mysql_query($query_datos_instituciones, $conn) or die(mysql_error());
	$row_datos_instituciones = mysql_fetch_assoc($datos_instituciones);
	$totalRows_datos_instituciones = mysql_num_rows($datos_instituciones);

	$armado= "";
	
	if($totalRows_datos_instituciones >= 1){	
		$armado="("	;
		do {	
			$armado .="'".$row_datos_instituciones['codigo_identificacion']."',";
		} while ($row_datos_instituciones = mysql_fetch_assoc($datos_instituciones));
		$armado .="'000000')";
	}

mysql_free_result($datos_instituciones);

}

$were1 = "";
$were2 = "";
$were3 = "";

if(isset($_GET['cod']) && isset($_GET['pascant'])) {
	$were1 = " AND codigo_institucion =".GetSQLValueString($_GET['cod'], "text")." ";
} else if(isset($_GET['pal']) && $_GET['pal'] != "" && isset($_GET['pascant'])) {
	$PAL = GetSQLValueString($_GET['pal'], "text");
	$PAL = str_replace(" ", "|", $PAL);
	$were1 = " AND codigo_institucion IN $armado ";
	if(isset($_GET['ins']) && $_GET['ins'] == "si") {		
		$were3 = " AND (nombre REGEXP $PAL AND tipo <> 0) ";	
	}
}

if(isset($_GET['elm']) && $_GET['elm'] == "si") {
	
} else {
	$were2 = " AND estado <> 'Cancelado' ";
}



mysql_select_db($database_conn, $conn);
$query_vis_nivelesVert = "(SELECT * FROM vis_estados_niveles WHERE 0=0 ".$were1.$were2.$were3.$_SESSION['MM_instituciones']." ORDER BY codigo_referencia ASC)";
if(isset($_GET['ins']) && $_GET['ins'] == "si") {
$query_vis_nivelesVert .= "UNION (SELECT * FROM vis_estados_niveles WHERE tipo=0 ".$were1.$were2.") ORDER BY codigo_referencia ASC";	
}

//echo $query_vis_nivelesVert;
$vis_nivelesVert = mysql_query($query_vis_nivelesVert, $conn) or die(mysql_error());
$row_vis_nivelesVert = mysql_fetch_assoc($vis_nivelesVert);
$totalRows_vis_nivelesVert = mysql_num_rows($vis_nivelesVert);

mysql_select_db($database_conn, $conn);
$query_norma_legal = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$norma_legal = mysql_query($query_norma_legal, $conn) or die(mysql_error());
$row_norma_legal = mysql_fetch_assoc($norma_legal);
$totalRows_norma_legal = mysql_num_rows($norma_legal);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>NIVELES FULL</title>
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
	background-color: #FFFfff;
}
  
.tipo0{   
   
position: absolute;   
width: 200px;   
border: 2px solid black;   
background-color: #ffffff;   
font-family: Verdana;   
line-height: 20px;   
cursor: default;   
visibility: hidden;   
}   
  
.tipo1{   
   
cursor: default;   
position: absolute;   
width: 165px;   
background-color: #ffffff;   
color: MenuText;   
border: 0 solid white;   
visibility: hidden;   
border: 0 outset ButtonHighlight;   
}   
  
a.menuitem {font-size: 0.8em; font-family: Arial, Serif; text-decoration: none;}   
a.menuitem:link {color: #000000; }   
a.menuitem:hover {color: #FFFFFF; background: #0A246A;}   
a.menuitem:visited {color: #868686;} 

.celdas {
	border-color:#000; 
	border-style:solid; 
	border-width:1px;
}

.menu:hover {color: #FFFFFF; background: #0A246A;}

</style>
<script type='text/javascript' src='gyl_menu.js'></script>
<script language="javascript">

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

var q=0;
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

function camb() {
	q=1;
}

function cambno() {
	q=0;
}
   
//Menú contextual con botón derecho by Mauricio Alejandro   
//Actualizado por El Codigo para soporte multinavegador (01/11/2005)   

//Este script y otros muchos pueden   
//descarse on-line de forma gratuita   
//en El Código: www.elcodigo.com   
  
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
  
document.onclick = function visibilidad(e){ 
	if (ns4||ns6)    {  
        if (e.which == 1 && !document.getElementById("codigonivel")){  
      	document.getElementById("cepilomenu").style.visibility = "hidden"; 
   		document.oncontextmenu = "e";
     	} 
    } else if (ie) {  
        document.getElementById("cepilomenu").style.visibility = "hidden"; 
  		document.oncontextmenu = "e";
   	} else if (document.layers && !document.getElementById){ 
     if (e.which == 1){  
      	document.getElementById("cepilomenu").style.visibility = "hidden"; 
   		document.oncontextmenu = "e";
     }  
    } else { 
        document.getElementById("cepilomenu").style.visibility = "hidden"; 
  		document.oncontextmenu = "e";
	}
}   


function colorcelda(a) {
	document.getElementById('c'+a).style.backgroundColor = '#c2ecfe';
}

function coloceldaout() {
	for(var i=1; i <= 15 ; i++) {
		if(document.getElementById('c'+i)) {
			document.getElementById('c'+i).style.backgroundColor = '#FFFFFF';
		}
	}
}

//-----funcion de vista rapida-------------------------------------
function reloadScrollBars() {
    document.documentElement.style.overflow = 'auto';  // firefox, chrome
    document.body.scroll = "yes"; // ie only
}

function unloadScrollBars() {
    document.documentElement.style.overflow = 'hidden';  // firefox, chrome
    document.body.scroll = "no"; // ie only
}

function vistar(i,j,ks,s,v) {
	
	var pag = "instituciones_update.php";
	
	if (j==0){
		pag = "instituciones_update.php";
	} else if(j==1 || j==2 || j==3 || j==4 || j==5) {
		pag = "niveles_update.php";
	} else if(j==8 || j==9 || j==10 || j==11) {
		pag = "diplomaticos_update.php";
	}
	
	
	document.getElementById('fondo_vista_rapida').style.display='block';
	window.frm_vista_rapida.activar();
	window.frm_vista_rapida.document.location=pag+'?vr=1&cod='+i;
	unloadScrollBars();
	
}

function bajar_vistar() {	
	window.frm_vista_rapida.document.location="blanck.php";
	document.getElementById('fondo_vista_rapida').style.display='none';
	reloadScrollBars();
}

//---------------------------------------------------------------------------------------------------------
function openmenuper(i,j,k,s,t,u,v) {
	
//codigo_referencia
//tipo_nivel
//tv
//codigo_institucion
//estado
//situacion
//codigo automatico para compuestos	
	
var m = i;	
	//t=estado
	//u=situacion
var ks = k+1;
var contMenu = "";
contMenu +="<table width=\"150\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" >";

contMenu += "<tr id=\"c1\" ><td class=\"celdas\" ><a onMouseOver=\"colorcelda(1);\" onMouseOut=\"coloceldaout();\"  onclick=\"vistar('"+i+"',"+j+","+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Vista Rapida<br></a></td></tr>";

contMenu += "<tr><td class=\"celdas\" ><hr></td>";

if (j <= 1 && t!='Cancelado') {
contMenu += "<tr id=\"c1\" ><td class=\"celdas\" ><a onMouseOver=\"colorcelda(1);\" onMouseOut=\"coloceldaout();\"  onclick=\"insertnivel('"+i+"',1,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Fondo/Sub-Fondo<br></a></td></tr>";
}
if (j <= 2 && t!='Cancelado') {
contMenu += "<tr id=\"c2\" ><td class=\"celdas\" ><a onMouseOver=\"colorcelda(2);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+i+"',2,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Sección/sub-secc.<br></a></td></tr>";
}
if (j <= 3 && t!='Cancelado') {
contMenu += "<tr id=\"c3\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(3);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+i+"',3,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Serie/Sub-serie.<br></a></td></tr>";
}
if (j <= 3 && t!='Cancelado') {
contMenu += "<tr id=\"c4\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(4);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+i+"',4,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Agrupación Doc.<br></a></td></tr>";
}
if(j <= 3) {
contMenu += "<tr><td class=\"celdas\" ><hr></td></tr>";
}
if (j >=1) {
if (j <5 && t!='Cancelado') {	
contMenu += "<tr id=\"c5\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(5);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+i+"',5,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Compuestos<br></a></td></tr>";
}
if (j <8) {	
if(j == 5) {
	var lt= i.split("/");
	var lk="";
	for(h=0; h<lt.length-1; h++) {
		lk += lt[h];
		if(h<lt.length-2) {
		lk += "/";
		}
	}
	i=lk;
	//alert(i); 
}
if(t!='Cancelado') {
contMenu += "<tr id=\"c6\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(6);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+m+"',8,"+ks+",'"+s+"','"+v+"','"+j+"');\" class=\"niv_menu\">Doc. Visuales<br></a></td></tr>";

contMenu += "<tr id=\"c7\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(7);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+m+"',9,"+ks+",'"+s+"','"+v+"','"+j+"');\" class=\"niv_menu\">Doc. Audiovisuales<br></a></td></tr>";

contMenu += "<tr id=\"c8\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(8);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+m+"',10,"+ks+",'"+s+"','"+v+"','"+j+"');\" class=\"niv_menu\">Doc. Sonoros<br></a></td></tr>";

contMenu += "<tr id=\"c9\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(9);\" onMouseOut=\"coloceldaout();\" onclick=\"insertnivel('"+m+"',11,"+ks+",'"+s+"','"+v+"','"+j+"');\" class=\"niv_menu\">Doc. Textuales<br></a></td></tr>";

 contMenu += "<tr><td class=\"celdas\" ><hr></td></tr>";
}
}
}
//if (j == 8 || j == 9 || j == 10 || j == 11) {	
if (t=='Vigente' && (u=='Local' || u.substr(0,8)=='Conserva') && (j == 8 || j == 9 || j == 10 || j == 11)) {	
contMenu += "<tr id=\"c10\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(10);\" onMouseOut=\"coloceldaout();\" onclick=\"conservacion('"+i+"');\" class=\"niv_menu\">Conservacion<br></a></td></tr>";
}
if (t=='Vigente' && (u=='Local' || u.substr(0,8)=='Exposici') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<tr id=\"c11\"><td class=\"celdas\"><a onMouseOver=\"colorcelda(11);\" onMouseOut=\"coloceldaout();\" onclick=\"exposicion('"+i+"');\" class=\"niv_menu\">Exposición<br></a></td></tr>";
}
if (t=='Vigente' && (u=='Local' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<tr id=\"c12\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(12);\" onMouseOut=\"coloceldaout();\" onclick=\"prestamos('"+i+"');\" class=\"niv_menu\">Prestamos<br></a></td></tr>";
}
if (t=='Vigente' && (u=='Local' || u=='Robado' || u.substr(0,8)=='Conserva' || u.substr(0,8)=='Exposici' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<tr id=\"c13\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(13);\" onMouseOut=\"coloceldaout();\" onclick=\"robo('"+i+"','"+u+"');\" class=\"niv_menu\">Robo<br></a></td></tr>";
}
if (t=='Vigente' && (u=='Local' || u=='No Localizado' || u.substr(0,8)=='Conserva' || u.substr(0,8)=='Exposici' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<tr id=\"c14\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(14);\" onMouseOut=\"coloceldaout();\" onclick=\"nolocalizado('"+i+"','"+u+"');\" class=\"niv_menu\">No Localizado<br></a></td></tr>";
}
if((j == 8 || j == 9 || j == 10 || j == 11) && t != "Inicio" && t != "Pendiente") {
contMenu += "<tr><td class=\"celdas\" ><hr></td>";
}
if (t!='Cancelado' && u=='Local'){
contMenu += "<tr id=\"c15\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(15);\" onMouseOut=\"coloceldaout();\" onclick=\"activar(); eliminar('"+m+"','"+s+"');\" class=\"niv_menu\">Eliminar</a><br></td></tr>";
}
<?php if($_SESSION['MM_idrol'] == 1 || $_SESSION['MM_idrol'] == 2) { ?>
if (t=='Cancelado'){
contMenu += "<tr id=\"c15\"><td class=\"celdas\" ><a onMouseOver=\"colorcelda(15);\" onMouseOut=\"coloceldaout();\" onclick=\"activar(); revertirbaja('"+m+"','"+s+"');\" class=\"niv_menu\">Revertir Baja</a><br></td></tr>";
}
<?php } ?>
contMenu +="</table>"

<?php if($_SESSION['MM_idrol'] == 1 || $_SESSION['MM_idrol'] == 2) { ?>
	document.getElementById("cepilomenu").innerHTML = contMenu;
	document.oncontextmenu = sombra;
<?php } else { ?>
if(t != "Cancelado" && u != "Baja") {
	document.getElementById("cepilomenu").innerHTML = contMenu;
	document.oncontextmenu = sombra;
}
<?php } ?>

}

//-----------------------------------------------------------------------------------------------------------

function conservacion(i) {
	window.parent.document.location='fr_conservacion.php?cod='+i;	
}

function exposicion(i) {
	window.parent.document.location='fr_exposicion.php?cod='+i;
}

function robo(i,u) {
	window.parent.document.location='fr_robo.php?cod='+i+'&sit='+u;
}

function nolocalizado(i,u) {
	window.parent.document.location='fr_nolocalizado.php?cod='+i+'&sit='+u;
}

function prestamos(i) {
	window.parent.document.location='fr_prestamos.php?cod='+i;
}



function eliminar(i,s) {
	relocate('nivelesfull.php?cod=<?php if(isset($_GET['cod'])) { echo $_GET['cod']; } ?>&<?php if(isset($_GET['pascant'])) { echo "pascant=s"; } ?>',{'eliminar':'si','code':i,'inst':s});
}

function revertirbaja(i,s) {
	relocate('nivelesfull.php?rev=ok<?php if(isset($_GET['cod'])) { echo "&cod=".$_GET['cod']; } ?><?php if(isset($_GET['pascant'])) { echo "&pascant=".$_GET['pascant']; } ?><?php if(isset($_GET['elm'])) { echo "&elm=".$_GET['elm']; } ?>',{'revertirbaja':'si','code':i,'inst':s});
}

function cancelarNuevo() {
	document.location='nivelesfull.php?t=t<?php if(isset($_GET['cod'])) { echo "&cod=".$_GET['cod']; } ?><?php if(isset($_GET['pascant'])) { echo "&pascant=".$_GET['pascant']; } ?><?php if(isset($_GET['elm'])) { echo "&elm=".$_GET['elm']; } ?>';
}

//-------------------------------------------------------------------------------------------

function insertnivel(i,j,k,s,v,w) {
	
	//alert("que que que");
	//cs: conigo del nivel superior
	//cn: codigo del nivel que se esta ingresando
	//--- con los dos anteriores se forma el codigo de referencia del nivel
	//ci: codigo de la institucion a la que pertenece
	//tn: tipo de nivel que se esta ingresando
	document.getElementById("cepilomenu").style.visibility = "hidden";
	
	var contMenu = "<form id=\"form_nivel\" name=\"form_nivel\" method=\"post\" action=\"nivelesfull.php?cod=<?php if(isset($_GET['cod'])) { echo $_GET['cod']; } ?>&<?php if(isset($_GET['pascant'])) { echo "pascant=s"; } ?>\" >";
	contMenu +="";
	contMenu +="<table width=\"160\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"2\" ><tr><td>&nbsp;</td></tr><tr bgcolor=\"#FFFFFF\"><td class=\"celdas\">";
	contMenu += "	<table width=\"160\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	contMenu += "  <tr>";
	contMenu += "    <td class=\"niv_menu\">Código del nuevo nivel:</td>";
	contMenu += "  </tr>";
	contMenu += "  <tr>";
	contMenu += "    <td height=\"5\"></td>";
	contMenu += "  </tr>";
	contMenu += "  <tr>";
	contMenu += "  <td>";
	contMenu += "<input type=\"text\" value=\""+v+"\"  name=\"cn\" id=\"cn\" class=\"niv_campochico\" Style=\"width:148px;\" onkeyup=\"iden(this.value)\" />";
	contMenu += "<br>";
	contMenu += "<input name=\"button\" type=\"submit\" class=\"niv_botchico\" id=\"button\" value=\"Agregar\" onclick=\"activar(); \" />&nbsp;&nbsp;";
	contMenu += "<input name=\"button2\" type=\"button\" class=\"niv_botchico\" id=\"button2\" value=\"Cancelar\" onclick=\"activar(); cancelarNuevo();\" />";
	/* onclick=\"enviarDatos('"+i+"','"+j+"','"+k+"','"+s+"');\" */
	contMenu += "  </td>";
	contMenu += "  </tr>";
	contMenu += "</table></td></tr></table>";
	contMenu +="<input name=\"cs\" id=\"cs\" type=\"hidden\" value=\""+i+"\" />";
	contMenu +="<input name=\"ci\" id=\"ci\" type=\"hidden\" value=\""+s+"\" />";
	contMenu +="<input name=\"tn\" id=\"tn\" type=\"hidden\" value=\""+j+"\" />";
	contMenu +="<input name=\"tv\" id=\"tv\" type=\"hidden\" value=\""+k+"\" />";
	contMenu +="</form>";
	document.getElementById("v-"+i).innerHTML = contMenu;
	muestra_coloca("v-"+i);
	document.getElementById("cn").focus();
	document.getElementById("cn").select();
	if(w==5){
		document.getElementById("cn").setAttribute('readOnly','readonly');    
	}

}

function enviarDatos(i,j,k,s) {
	relocate('nivelesfull.php?cod=<?php if(isset($_GET['cod'])) { echo $_GET['cod']; } ?>&<?php if(isset($_GET['pascant'])) { echo "pascant=s"; } ?>',{'cs':i,'cn':document.getElementById("codigonivel").value,'ci':s,'tn':j,'tv':k});
}
 
 
function pasCant() {
	<?php if(isset($_GET['pascant'])) { ?>
	window.parent.document.getElementById("fondos_agrupaciones").value = <?php echo $totalRows_vis_nivelesVert;  ?>;
	<?php } ?>
}


function redirect(a,b) {
	
	window.parent.parent.frameestados.location = 'nivdoc_estados.php?cod='+a ;
	//document.location.target= '_blank';
	if(b==0) {
	window.parent.document.location.href = 'fr_institucion.php?cod='+a ;	
	} else if(b==1) {
	window.parent.document.location.href = 'fr_nivel.php?cod='+a ;
	} else if(b>=8 && b<=11) {
	window.parent.document.location.href = 'fr_diplomaticos.php?cod='+a ;	
	}
	
}

function opencomentario() {
	 document.getElementById('comentarios').style.display="block";
 }
 
 

window.onload = function () {
	
	window.parent.parent.frameestados.location = 'inicio_home.php';
}

</script>
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
	function blanco() {
		window.parent.parent.frameestados.location='inicio_home.php';
	}
</script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="pasCant(); blanco();">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="47"></td>
  </tr>
  <tr>
  <?php $ty=1; $lt=0; $poss=0; $tipo0=0; $tipo1=0; $tipo2=0; $tipo3=0; $tipo4=0; $tipo5=0; $tipo8=0; $tipo9=0; $tipo10=0; $tipo11=0; ?>
  <?php do { ?>
  <?php 
  	if($row_vis_nivelesVert['tipo'] < 8) { 
		echo "</tr><tr>";
		$ty=1; 
	} else if($ty==1) {
		echo "</tr><tr>"; 
		//$ty=0;
	}
?> 
<?php
  	//se genera el codigo para un documento compuesto**************************************
	if($row_vis_nivelesVert['tipo_nivel'] == 5) {
		
		$codigo_individual = end(explode("/",$row_vis_nivelesVert['codigo_referencia']));
		
		mysql_select_db($database_conn, $conn);
		$query_ft = "SELECT codigo_referencia FROM documentos WHERE codigo_referencia LIKE '".$row_vis_nivelesVert['codigo_referencia']."%' ORDER BY codigo_referencia DESC LIMIT 1";
		$ft = mysql_query($query_ft, $conn) or die(mysql_error());
		$row_ft = mysql_fetch_assoc($ft);
		$totalRows_ft = mysql_num_rows($ft);
		
		$segmiento = explode("/",$row_ft['codigo_referencia']);
		$ultimo_segmento = end($segmiento);
		//--------------------------------------------
		$cardinal = explode("_",$ultimo_segmento);
		$cantidad_cardinal = count($cardinal);
		
		if($cantidad_cardinal >= 2) {
			$numero_cardinal = end($cardinal);
			$nuevo_numero_cardinal = $numero_cardinal+1;
			$nuevo_codigo = $codigo_individual."_".$nuevo_numero_cardinal;
		} else if($cantidad_cardinal == 1) {
			$numero_cardinal = 0;
			$nuevo_numero_cardinal = $numero_cardinal+1;
			$nuevo_codigo = $codigo_individual."_".$nuevo_numero_cardinal;
		} else {
			$nuevo_codigo = "";
		}
		
		$ftc=$nuevo_codigo;
		
		mysql_free_result($ft);
	} else {
		$ftc="";
	}
?>
<?php
//se arma la figura cruazada de la iquierda de un icono**********************************************
$i=0;
$j=$row_vis_nivelesVert['tv'];
if($row_vis_nivelesVert['tipo'] < 8 || $ty==1) {
	while($i < $j) {
		if($j == ($i+1)) {  
			echo "<td width=\"47\"><img src=\"images/t3.png\" width=\"100\" height=\"84\"></td>";
		} else {
			echo "<td width=\"47\"><!-- <img src=\"images/t2.jpg\" width=\"47\" height=\"58\">--></td>"; 	
		}
		$i++; 
	}
}
?>
<?php 
if($row_vis_nivelesVert['tipo'] >= 8 && $ty==1) {
	$ty=0;
	$lt=$j;
}
?>
<?php 

$j=$row_vis_nivelesVert['tv'];
if($row_vis_nivelesVert['tipo'] >= 8 && $ty==0) {
	$lt++;
	if($lt == 13 || ($j < $poss)){
		if($row_vis_nivelesVert['tipo'] <= $tupu){
			echo "</tr><tr>";
		}
		$lt=$j+1;
		while($i < $j) {
			if($i == $j-1 && $j < $poss) {
				echo "<td width=\"47\"><img src=\"images/t3.png\" width=\"100\" height=\"84\"></td>";
			} else {
				echo "<td width=\"47\"></td>";
			}
			$i++;
		}
	}
}
$poss=$row_vis_nivelesVert['tv'];
$tupu=$row_vis_nivelesVert['tipo'];
if($tupu >= 8 || $tupu <= 11) {
	$tupu=100;	
}
?>
 width="47" valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100"><tr><td align="center"><?php 
	  	
	  	if($row_vis_nivelesVert['tipo'] == 0) {
		  $tipo0++;	
		  $tipo="INSTITUCIÓN";
	  	} else if ($row_vis_nivelesVert['tipo'] == 1) {
			$tipo1++;
			$tipo="FONDO/SUBFONDO";	
		} else if ($row_vis_nivelesVert['tipo'] == 2) {
			$tipo2++;
			$tipo="SECCIÓN/SUBSECCIÓN";	
		} else if ($row_vis_nivelesVert['tipo'] == 3) {
			$tipo3++;
			$tipo="SERIE/SUBSERIE";	
		} else if ($row_vis_nivelesVert['tipo'] == 4) {
			$tipo4++;
			$tipo="AGRUPACIÓN DOCUMENTAL";	
		} else if ($row_vis_nivelesVert['tipo'] == 5) {
			$tipo5++;
			$tipo="DOC. COMPUESTO";	
		} else if ($row_vis_nivelesVert['tipo'] == 8) {
			$tipo8++;
			$tipo="DOC. VISUAL";	
		} else if ($row_vis_nivelesVert['tipo'] == 9) {
			$tipo9++;
			$tipo="DOC. AUDIOVISUAL";	
		} else if ($row_vis_nivelesVert['tipo'] == 10) {
			$tipo10++;
			$tipo="DOC. SONORO";	
		} else if ($row_vis_nivelesVert['tipo'] == 11) {
			$tipo11++;
			$tipo="DOC. TEXTUAL";	
		} else {
			$tipo="";
		}
		$nomTip = "v-".$row_vis_nivelesVert['codigo_referencia'];
		$nomTipB = "";
		$nomTipB .= "<div id=\"".$nomTip."\" style=\"position:absolute; visibility:hidden; margin-left:60px; width:530px;\"  onmouseover=\"muestra_retarda('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\"><table width=\"505\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
		$nomTipB .= "  <tr>";
		//$nomTipB .=	"<td width=\"25\" rowspan=\"3\"></td>";
		$nomTipB .= "    <td><img src=\"images/globo4.png\" width=\"505\" height=\"40\"></td>";
		$nomTipB .= "  </tr>";
		$nomTipB .= "  <tr>";
		$nomTipB .= "    <td><table width=\"490\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">";
		$nomTipB .= "      <tr>";
		$nomTipB .= "        <td style=\"background:url(images/globo3.png);\"><table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
		$nomTipB .= "          <tr>";
		$nomTipB .= "            <td class=\"tips\">".$tipo." - ".$row_vis_nivelesVert['codigo_referencia']."<br>".$row_vis_nivelesVert['nombre']."<br>"."<b>ESTADO: </b>".$row_vis_nivelesVert['estado'];
		$nomTipB .= "</td>";
		$nomTipB .= "          </tr>";
		$nomTipB .= "        </table></td>";
		$nomTipB .= "      </tr>";
		$nomTipB .= "    </table></td>";
		$nomTipB .= "  </tr>";
		$nomTipB .= "  <tr>";
		$nomTipB .= "    <td align=\"right\"><img src=\"images/globo2.png\" width=\"490\" height=\"10\"></td>";
		$nomTipB .= "  </tr>";
		$nomTipB .= "</table>";
		$nomTipB .= "</div>";
		echo $nomTipB;
	   ?><a name="<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>" /><?php if((isset($_GET['s']) && $_GET['s'] != "C") || !isset($_GET['s'])) { ?><a  <?php if ($row_vis_nivelesVert['tipo_nivel'] !=5) { ?>onClick="activar(); redirect('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>','<?php echo $row_vis_nivelesVert['tipo_diplomatico']; ?>');" <?php } ?> onMouseOver="muestra_coloca('<?php echo "v-".$row_vis_nivelesVert['codigo_referencia']; ?>')" onMouseOut="oculta_retarda('<?php echo "v-".$row_vis_nivelesVert['codigo_referencia']; ?>')" onMouseDown="camb(); openmenuper('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>',<?php echo $row_vis_nivelesVert['tipo_nivel']; ?>,<?php echo $row_vis_nivelesVert['tv']; ?>,'<?php echo $row_vis_nivelesVert['codigo_institucion']; ?>','<?php echo $row_vis_nivelesVert['estado']; ?>','<?php  echo $row_vis_nivelesVert['situacion'];  ?>','<?php echo $ftc; ?>');" href="#<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>" ><?php } ?><img src="images/c<?php echo $row_vis_nivelesVert['tipo_nivel']; ?><?php if($row_vis_nivelesVert['situacion'] == "Baja" || $row_vis_nivelesVert['estado'] == "Cancelado") { echo "k"; } else if($row_vis_nivelesVert['estado'] == "Inicio") { echo "o"; }  else if($row_vis_nivelesVert['estado'] == "Completo") { echo "c"; } else if($row_vis_nivelesVert['estado'] == "No Vigente") { echo "n"; } else if($row_vis_nivelesVert['estado'] == "Pendiente") { echo "t"; } else if($row_vis_nivelesVert['situacion'] == "Conservación") { echo "s"; } else if($row_vis_nivelesVert['situacion'] == "Exposición") { echo "x"; } else if($row_vis_nivelesVert['situacion'] == "No Localizado") { echo "l"; } else if($row_vis_nivelesVert['situacion'] == "Prestamo") { echo "p"; } else if($row_vis_nivelesVert['situacion'] == "Robado") { echo "r"; } ?>.png"  <?php if($row_vis_nivelesVert['tipo'] < 8) { echo "width=\"100\" height=\"84\" "; } else { ?>width="70" height="59"<?php } ?> border="0" style="margin-top: 10px; " ></a></td></tr><tr><td><table width="90" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="nivNombre"><?php echo end(explode("/" , $row_vis_nivelesVert['codigo_referencia'])); ?></td>
          </tr>
      </table></td></tr></table></td>
 <?php } while ($row_vis_nivelesVert = mysql_fetch_assoc($vis_nivelesVert)); ?>
</tr>     
</table>
<!-- Capa que construye el menu -->  
<div id="cepilomenu"></div> 
<div id="cancelar" style="width:100%; height:100%; position:absolute; z-index:100; left: 0px; top: 0px; display: <?php if(isset($_GET['el']) && $_GET['el'] != "") { echo "block"; } else { echo "none"; } ?>"><form action="nivelesfull.php" method="post" enctype="multipart/form-data" name="form1">
 	<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">DATOS DE LA BAJA</td>
    	          <td align="right">&nbsp;</td>
  	          </tr>
  	        </table></td>
            </tr>
            <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Norma Legal de Baja:</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><select name="normativa_legal_baja" id="normativa_legal_baja" class="camposanchos">
    	            <option value="" >Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_norma_legal['norma_legal']?>"><?php echo $row_norma_legal['norma_legal']?></option>
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
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>N&uacute;mero Norma Legal:</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="numero_norma_legal" type="text" class="camposanchos" id="numero_norma_legal"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Motivo de la Baja:</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><textarea name="motivo_baja" rows="5" class="camposanchos" id="motivo_baja"></textarea></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor" ><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Fecha Baja:</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor">D&iacute;a:
    	            <input name="fecha_bajad" type="text" id="fecha_bajad" size="8" maxlength="2">
    	            Mes:
    	            <input name="fecha_bajam" type="text" id="fecha_bajam" size="8" maxlength="2">
    	            A&ntilde;o:
    	            <input name="fecha_bajaa" type="text" id="fecha_bajaa" size="12" maxlength="4">
    	            (dd/mm/aaaa)
    	            
    	              <input name="code" type="hidden" id="code" value="<?php if(isset($_GET['el']) && $_GET['el'] != "") { echo $_GET['el']; }?>" >
  	              </td>
  	          </tr>
    	        <tr>
    	          <td >&nbsp;</td>
  	          </tr>
              </table></td>
      </tr>
             <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"><input name="MM_baja" type="hidden" id="MM_baja" value="form1"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
             <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
    </table></form>
</div>
<!-- capa para revertir la baja -->
<div id="cancelar" style="width:100%; height:100%; position:absolute; z-index:100; left: 0px; top: 0px; display:none; <?php if(isset($_GET['rev']) && $_GET['rev'] != "") { echo "block"; } else { echo "none"; } ?>">


<form action="nivelesfull.php?t=t<?php if(isset($_GET['pascant'])) { echo "&pascant=".$_GET['pascant'];} ?><?php if(isset($_GET['elm'])) { echo "&elm=si"; } ?><?php if(isset($_GET['cod'])) { echo "&cod=".$_GET['cod'];} ?>" method="post" enctype="multipart/form-data" name="form_reverirBaja">
	<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
            <td>&nbsp;</td>
            </tr>
    </table>
 	<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">DATOS DE LA REVERSION DE LA BAJA</td>
    	          <td align="right">&nbsp;</td>
  	          </tr>
  	        </table></td>
            </tr><td class="fondolineaszulesvert">
            <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
            <tr>
    	          <td class="tituloscampos ins_celdacolor">Motivo de la Reversión de la Baja:</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><textarea name="motivo_revertirbaja" rows="5" class="camposanchos" id="motivo_revertirbaja"></textarea></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              </table>
              </td> 
              <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"><input name="MM_revbaja" type="hidden" id="MM_revbaja" value="form_reverirBaja">
   	              <input name="code" type="hidden" id="code" value="<?php echo $_POST['code']; ?>">
   	              <input name="inst" type="hidden" id="inst" value="<?php echo $_POST['inst']; ?>"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar" onClick="activar();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
          <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
           </table>
 </form>          


</div>
<br>
<table width="100%" border="1" align="center" cellpadding="5" cellspacing="1">
	<tr>
    	<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
    	  <tr>
    	    <td align="center"><img src="images/c0.png" alt="Instituciones" width="32" height="32"></td>
    	    <td align="center"><img src="images/c1.png" alt="Fondos / Sub-fondos"  height="32"></td>
    	    <td align="center"><img src="images/c2.png"  height="32"></td>
    	    <td align="center"><img src="images/c3.png" alt="Serie / Sub-serie"  height="32"></td>
    	    <td align="center"><img src="images/c4.png" alt="Agrupaci&oacute;n Documental"  height="32"></td>
    	    <td align="center"><img src="images/c5.png"  height="32"></td>
    	    <td align="center"><img src="images/c8.png" alt="Documento Visual"  height="32"></td>
    	    <td align="center"><img src="images/c9.png" alt="Documento Audiovisual"  height="32"></td>
    	    <td align="center"><img src="images/c10.png" alt="Documento Sonoro"  height="32"></td>
    	    <td align="center"><img src="images/c11.png" alt="Documento Textual"  height="32"></td>
  	      </tr>
    	  <tr>
    	    <td height="7" colspan="10"></td>
   	      </tr>
    	  <tr class="botongrabar">
    	    <td align="center"><?php echo $tipo0; ?></td>
    	    <td align="center"><?php echo $tipo1; ?></td>
    	    <td align="center"><?php echo $tipo2; ?></td>
    	    <td align="center"><?php echo $tipo3; ?></td>
    	    <td align="center"><?php echo $tipo4; ?></td>
    	    <td align="center"><?php echo $tipo5; ?></td>
    	    <td align="center"><?php echo $tipo8; ?></td>
    	    <td align="center"><?php echo $tipo9; ?></td>
    	    <td align="center"><?php echo $tipo10; ?></td>
    	    <td align="center"><?php echo $tipo11; ?></td>
  	    </tr>
      </table></td>
    </tr>
</table>



<!--
<div id="fondo_vista_rapida" style="width:100%; height:100%; position:absolute; z-index:100000; left:0px; top:0px; background-color:#000; filter: Alpha(Opacity=50); opacity: 0.5; display:none;"></div> -->
<!-- Inicializacion de estilos -->  
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
<?php require_once('activo.php'); ?>
</body>
</html>
<?php
//mysql_free_result($vis_instituciones);

mysql_free_result($vis_nivelesVert);

mysql_free_result($norma_legal);


?>
