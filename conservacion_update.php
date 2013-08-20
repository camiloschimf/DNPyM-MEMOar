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


//--eliminamos valores de tablas relacionadas-------------------------------
if ((isset($_POST["elm"])) && ($_POST["val1"] != "")) {

	if($_POST["elm"] == 1) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_material_soporte WHERE codigo_gestion=%s AND codigo_material_soporte=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 2) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_medios WHERE codigo_gestion=%s AND codigo_medio=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 3) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_agregados WHERE codigo_gestion=%s AND codigo_agregado=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 4) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_guardas WHERE codigo_gestion=%s AND guarda=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 5) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_guardas_sugeridas WHERE codigo_gestion=%s AND guarda=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 6) {
		$deleteSQL = sprintf("DELETE FROM rel_conserv_tratamientos_anteriores WHERE codigo_gestion=%s AND tratamiento_anterior=%s",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 7) {
		$deleteSQL = sprintf("DELETE FROM rel_gestion_conserv_caract_toma_fotog WHERE codigo_gestion=%s AND caracteristica_toma_fotografica=%s AND posterior=0",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 8) {
		$deleteSQL = sprintf("DELETE FROM rel_gestion_conserv_caract_toma_fotog WHERE codigo_gestion=%s AND caracteristica_toma_fotografica=%s AND posterior=1",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	} else if($_POST["elm"] == 9) {
		$deleteSQL = sprintf("DELETE FROM rel_gestion_conserv_recomendaciones_tratamiento WHERE codigo_gestion=%s AND recomendaciones_tratamiento=%s ",
							GetSQLValueString($_POST['ges'], "text"),
							GetSQLValueString($_POST['val1'], "text"));
	}


	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($deleteSQL, $conn) or die(mysql_error());
	
	$updateGoTo = "conservacion_update.php?ges=".$_POST['ges'];
	header(sprintf("Location: %s", $updateGoTo));
	
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
	$updateSQL = sprintf("UPDATE documentos SET situacion='".$est_t."' WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos la fecha visu--------------------------------
function visu($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultimo_relevamiento_visu=%s WHERE codigo_referencia=%s ",
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
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos la fecha de la ultima gestion--------------------------------
function ultimagestion($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultima_gestion=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos si esta restringido a exibicion o no--------------------------------
function restringido($res, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET restringido_exhibicion=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($res, "int"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos la fecha del ultimo tratamiento--------------------------------
function ultimotratamiendo($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultimo_tratamiento=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--empezamos a grabar los datos de la tabla principal-----------------------------------------
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['codigo_gestion'] == "") {
  	//--inicializamos la gestion----------------------------------------
	$insertSQL0 = sprintf("INSERT INTO gestion_conservacion (codigo_referencia, usuario, fecha_ultima_modificacion) VALUES (%s, %s, %s) ",
					GetSQLValueString($_POST['codigo_referencia'], "text"),
					GetSQLValueString($_SESSION['MM_usuario'], "text"),
					GetSQLValueString(date("Y/m/d H:i,s"), "date"));
	mysql_select_db($database_conn, $conn);
  	$Result0 = mysql_query($insertSQL0, $conn) or die(mysql_error());
	
	$idK = mysql_insert_id();
	
  	} else {
		
	 $idK = $_POST['codigo_gestion'];
	 
  	}
	//--modificamos los datos de la gestion------------------------------
  $updateSQL = sprintf("UPDATE gestion_conservacion SET codigo_referencia=%s, fecha_inicio_tratamiento=%s, fecha_fin_tratamiento=%s, estado_conservacion=%s, descripcion_fisica_pormenorizada=%s, recubrimiento_superficial=%s, diagnostico_especifico=%s, fecha_imagen_incio=%s, fecha_imagen_posterior=%s, informe_tratamiento=%s, resultados_tratamiento=%s, restringido_exibicion=%s, caracteristica=%s, responsable=%s, codigo_institucion=%s, fecha_ultima_modificacion=%s, usuario=%s, full=0 WHERE codigo_gestion=%s", 
                       GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_inicio_tratamientod'],$_POST['fecha_inicio_tratamientom'],$_POST['fecha_inicio_tratamientoa']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_fin_tratamientod'],$_POST['fecha_fin_tratamientom'],$_POST['fecha_fin_tratamientoa']), "date"),
                       GetSQLValueString($_POST['estado_conservacion'], "text"),
                       GetSQLValueString($_POST['descripcion_fisica_pormenorizada'], "text"),
                       GetSQLValueString($_POST['recubrimiento_superficial'], "text"),
                       GetSQLValueString($_POST['diagnostico_especifico'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_imagen_inciod'],$_POST['fecha_imagen_inciom'],$_POST['fecha_imagen_incioa']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_imagen_posteriord'],$_POST['fecha_imagen_posteriorm'],$_POST['fecha_imagen_posteriora']), "date"),
                       GetSQLValueString($_POST['informe_tratamiento'], "text"),
                       GetSQLValueString($_POST['resultados_tratamiento'], "text"),
                       GetSQLValueString($_POST['restringido_exibicion'], "int"),
                       GetSQLValueString($_POST['caracteristica'], "text"),
                       GetSQLValueString($_POST['responsable'], "text"),
					   GetSQLValueString($_POST['codigo_institucion'], "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_usuario'], "text"),
					   GetSQLValueString($idK, "int"));
  
  
  
  //--material de sorporte--
  if(isset($_POST['codigo_material_soporte']) && $_POST['codigo_material_soporte'] != "") {	
 		$insertSQL2=sprintf("REPLACE INTO rel_conserv_material_soporte(codigo_gestion, codigo_material_soporte) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['codigo_material_soporte'], "int"));
		
		mysql_select_db($database_conn, $conn);
  		$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
  }
  
  //--insertamos los medios
  if(isset($_POST['codigo_medio']) && $_POST['codigo_medio'] != "") {
	  $insertSQL3=sprintf("REPLACE INTO rel_conserv_medios(codigo_gestion, codigo_medio) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['codigo_medio'], "int"));
		
		mysql_select_db($database_conn, $conn);
  		$Result3 = mysql_query($insertSQL3, $conn) or die(mysql_error());
  }
  
  //--insertamos los agregados
  if(isset($_POST['codigo_agregado']) && $_POST['codigo_agregado'] != "") {
	  	$insertSQL4=sprintf("REPLACE INTO rel_conserv_agregados(codigo_gestion, codigo_agregado) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['codigo_agregado'], "int"));
		
		mysql_select_db($database_conn, $conn);
  		$Result4 = mysql_query($insertSQL4, $conn) or die(mysql_error());
  }
  
  //--insertamos las guaradas
  if(isset($_POST['guarda']) && $_POST['guarda'] != "") {
	  $insertSQL5=sprintf("REPLACE INTO rel_conserv_guardas (codigo_gestion, guarda) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['guarda'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result5 = mysql_query($insertSQL5, $conn) or die(mysql_error());
  }
  
  //--insertamos las guardas sugeridas
  if(isset($_POST['guardasugerida']) && $_POST['guardasugerida'] != "") {
	  $insertSQL6=sprintf("REPLACE INTO rel_conserv_guardas_sugeridas (codigo_gestion, guarda) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['guardasugerida'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result6 = mysql_query($insertSQL6, $conn) or die(mysql_error());
  }
  
  //--insertamos los tratamientos anteriores
  if(isset($_POST['tratamiento_anterior']) && $_POST['tratamiento_anterior'] != "") {
	  $insertSQL7=sprintf("REPLACE INTO rel_conserv_tratamientos_anteriores(codigo_gestion, tratamiento_anterior) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['tratamiento_anterior'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result7 = mysql_query($insertSQL7, $conn) or die(mysql_error());
  }
  
  //--insertamos las caracteristicas de las tomas fotograficas
  if(isset($_POST['caracteristica_toma_fotografica_inicio']) && $_POST['caracteristica_toma_fotografica_inicio'] != "") {
	  $insertSQL8=sprintf("REPLACE INTO rel_gestion_conserv_caract_toma_fotog (codigo_gestion, caracteristica_toma_fotografica) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['caracteristica_toma_fotografica_inicio'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result8 = mysql_query($insertSQL8, $conn) or die(mysql_error());
  }
  
  //--insertamos las caracteristicas de las tomas fotograficas posteriores
  if(isset($_POST['caracteristica_toma_fotografica_posterior']) && $_POST['caracteristica_toma_fotografica_posterior'] != "") {
	  $insertSQL9=sprintf("REPLACE INTO rel_gestion_conserv_caract_toma_fotog (codigo_gestion, caracteristica_toma_fotografica, posterior) VALUES(%s, %s, 1)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['caracteristica_toma_fotografica_posterior'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result9 = mysql_query($insertSQL9, $conn) or die(mysql_error());
  }
  
 //--insertamos las recomendacioens de tratamientos
  if(isset($_POST['recomendaciones_tratamiento']) && $_POST['recomendaciones_tratamiento'] != "") {
	   $insertSQL10=sprintf("REPLACE INTO rel_gestion_conserv_recomendaciones_tratamiento(codigo_gestion, recomendaciones_tratamiento) VALUES(%s, %s)",
					GetSQLValueString($idK, "text"),
					GetSQLValueString($_POST['recomendaciones_tratamiento'], "text"));
		
		mysql_select_db($database_conn, $conn);
  		$Result10 = mysql_query($insertSQL10, $conn) or die(mysql_error());
  }
  
  
  
  if($_POST['fecha_inicio_tratamientoa'] != "" && $_POST['fecha_fin_tratamientoa'] != "" && $_POST['descripcion_fisica_pormenorizada'] != "" && $_POST['cant_rel_conserv_material_soporte'] >= 1 && $_POST['cant_rel_conserv_medios'] >= 1 && $_POST['responsable'] != "" && $_POST['informe_tratamiento'] != "" && $_POST['resultados_tratamiento'] != "") {
	  
	  //--modficamos la situacon a robado del documento--
	situacion("Local", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_fin_tratamientod'],$_POST['fecha_fin_tratamientom'],$_POST['fecha_fin_tratamientoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_fin_tratamientod'],$_POST['fecha_fin_tratamientom'],$_POST['fecha_fin_tratamientoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos si es restringido o no
	restringido($_POST['restringido_exibicion'],$_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha del ultimo tratamiendo 
	ultimotratamiendo(fechar($_POST['fecha_fin_tratamientod'],$_POST['fecha_fin_tratamientom'],$_POST['fecha_fin_tratamientoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
   $updateSQL = str_replace("full=0", "full=1", $updateSQL);
   mysql_select_db($database_conn, $conn);
   $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());				
	  
  } else {
	  
	   //--modficamos la situacon a robado del documento--
		situacion("Conservación", $_POST['codigo_referencia'],$database_conn,$conn);
		
		//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
		visu(fechar($_POST['fecha_inicio_tratamientod'],$_POST['fecha_inicio_tratamientom'],$_POST['fecha_inicio_tratamientoa']),$_POST['codigo_referencia'],$database_conn,$conn);
		
		//-modificamos si es restringido o no
		restringido($_POST['restringido_exibicion'],$_POST['codigo_referencia'],$database_conn,$conn);
	  
	  mysql_select_db($database_conn, $conn);
   	  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());	
					
  }
  
  
  
  
  
	//$_FILES['archivo']['size']: tamaño en bytes del archivo recibido
	//$_FILES['archivo']['type']: tipo mime del archivo, por ejemplo image/gif
	//$_FILES['archivo']['name']: nombre original del archivo
	//$_FILES['archivo']['tmp_name']:
  
	$msg="";
	$msg2="";
	  
	//verificamos que venga archivo desde la imagen inicial
	if($_FILES['imagenInicio']['name'] != "") {
		
	//si existe lo borramos
	$delete1 = sprintf("DELETE FROM archivos_digitales WHERE codigo_gestion=%s AND fecha_toma_archivo =%s",
					GetSQLValueString($_POST['codigo_gestion'], "text"),
					GetSQLValueString($_POST['fecha_imagen_incio_vieja'], "date"));
	mysql_select_db($database_conn, $conn);
  	$Rdelete1 = mysql_query($delete1, $conn) or die(mysql_error());				
		
	if(file_exists("archivos/".md5($_POST['codigo_archivo']).".".$_POST['extension'])) {
		unlink("archivos/".md5($_POST['codigo_archivo']).".".$_POST['extension']);
	}
	
	//datos del archivo que se va a insertar
	$nombre = $_FILES['imagenInicio']['name'];
	$partes = explode(".", $nombre);
	$extencion = end($partes);
	  
	 //inicializamos el archivo en la base de datos
	  if($_POST['fecha_imagen_incioa'] != "") {
	  $updateSQL12 = sprintf("INSERT INTO archivos_digitales (codigo_referencia, fecha_toma_archivo, tipo, extension, codigo_gestion)  VALUES (%s, %s, %s, %s, %s)",
	  					GetSQLValueString($_POST['codigo_referencia'], "text"),
						GetSQLValueString(fechar($_POST['fecha_imagen_inciod'],$_POST['fecha_imagen_inciom'],$_POST['fecha_imagen_incioa']), "date"),
						GetSQLValueString($_POST['tipo_documento'], "int"),
						GetSQLValueString($extencion, "text"),
	  					GetSQLValueString($idK, "int")); 
						
	mysql_select_db($database_conn, $conn);
  $Result12 = mysql_query($updateSQL12, $conn) or die(mysql_error());
  $idT = mysql_insert_id();
	  } else {
		 $msg="No se ingreso la fecha del archivo.";
		 $idT = 0; 
	  }
  
	  if($_POST['tipo_documento'] == "8" || $_POST['tipo_documento'] == "11") {

			if($extencion == "jpg" || $extencion == "JPG") {
				if(!move_uploaded_file($_FILES['imagenInicio']['tmp_name'], "archivos/".md5($idT).".jpg")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg="Verifique que sea un archivo *.jpg";
			}
		  
	  } else if ($_POST['tipo_documento'] == "9") {

			if($extencion == "flv" || $extencion == "FLV") {
				if(!move_uploaded_file($_FILES['imagenInicio']['tmp_name'], "archivos/".md5($idT).".flv")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg="Verifique que sea un archivo *.flv";
			}
		  
	  } else if ($_POST['tipo_documento'] == "10") {

			if($extencion == "mp3" || $extencion == "MP3") {
				if(!move_uploaded_file($_FILES['imagenInicio']['tmp_name'], "archivos/".md5($idT).".mp3")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				} 
	  		} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg="Verifique que sea un archivo *.mp3";
			}
		  
	  }
	  

	  
  }
  
  //verificamos que venga el archivo final
  if($_FILES['imagenfinal']['name'] != "") {
	  
	  //si existe lo borramos
	$delete2 = sprintf("DELETE FROM archivos_digitales WHERE codigo_gestion=%s AND fecha_toma_archivo =%s",
					GetSQLValueString($_POST['codigo_gestion'], "text"),
					GetSQLValueString($_POST['fecha_imagen_posterior_vieja'], "date"));
	mysql_select_db($database_conn, $conn);
  	$Rdelete2 = mysql_query($delete2, $conn) or die(mysql_error());				
		
	if(file_exists("archivos/".md5($_POST['codigo_archivo_posterior']).".".$_POST['extension_posterior'])) {
		unlink("archivos/".md5($_POST['codigo_archivo_posterior']).".".$_POST['extension_posterior']);
	}
	
	//datos del archivo que se va a insertar
	$nombre = $_FILES['imagenfinal']['name'];
	$partes = explode(".", $nombre);
	$extencion = end($partes);
	
	//inicializamos el archivo en la base de datos
	  if($_POST['fecha_imagen_posteriora'] != "") {
	  $updateSQL12 = sprintf("INSERT INTO archivos_digitales (codigo_referencia, fecha_toma_archivo, tipo, extension, codigo_gestion)  VALUES (%s, %s, %s, %s, %s)",
	  					GetSQLValueString($_POST['codigo_referencia'], "text"),
						GetSQLValueString(fechar($_POST['fecha_imagen_posteriord'],$_POST['fecha_imagen_posteriorm'],$_POST['fecha_imagen_posteriora']), "date"),
						GetSQLValueString($_POST['tipo_documento'], "int"),
						GetSQLValueString($extencion, "text"),
	  					GetSQLValueString($idK, "int")); 
						
		mysql_select_db($database_conn, $conn);
  		$Result12 = mysql_query($updateSQL12, $conn) or die(mysql_error());
  		$idT2 = mysql_insert_id();
	  } else {
		 $msg2="No se ingreso la fecha del archivo.";
		 $idT2 = 0; 
	  }
	
	
	if($_POST['tipo_documento'] == "8" || $_POST['tipo_documento'] == "11") {

			if($extencion == "jpg" || $extencion == "JPG") {
				if(!move_uploaded_file($_FILES['imagenfinal']['tmp_name'], "archivos/".md5($idT2).".jpg")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.jpg";
			}
		  
	  } else if ($_POST['tipo_documento'] == "9") {

			if($extencion == "flv" || $extencion == "FLV") {
				if(!move_uploaded_file($_FILES['imagenfinal']['tmp_name'], "archivos/".md5($idT2).".flv")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.flv";
			}
		  
	  } else if ($_POST['tipo_documento'] == "10") {

			if($extencion == "mp3" || $extencion == "MP3") {
				if(!move_uploaded_file($_FILES['imagenfinal']['tmp_name'], "archivos/".md5($idT2).".mp3")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				} 
	  		} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.mp3";
			}
		  
	  } 
	
	  
  }
  
  
  $insertGoTo = "conservacion_update.php?ges=".$idK."&msg=".$msg."&msg2=".$msg2;
  header(sprintf("Location: %s", $insertGoTo));
}



//--llenado de combos auxiliares-------------------------------------------------------------------

$colname_diplomaticos = "-1";
if (isset($_GET['cod'])) {
  $colname_diplomaticos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_diplomaticos = sprintf("SELECT * FROM documentos  WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_diplomaticos, "text"));
$diplomaticos = mysql_query($query_diplomaticos, $conn) or die(mysql_error());
$row_diplomaticos = mysql_fetch_assoc($diplomaticos);
$totalRows_diplomaticos = mysql_num_rows($diplomaticos);


$s1=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'GCON' ,$database_conn,$conn);

mysql_free_result($diplomaticos);

//echo $s1.$s2.$s3.$s4;

//$s1 = "";

if($s1 == "" ) {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}

$colname_gestion_conservacion = "-1";
if (isset($_GET['ges'])) {
  $colname_gestion_conservacion = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_gestion_conservacion = sprintf("SELECT * FROM gestion_conservacion WHERE codigo_gestion = %s LIMIT 1", GetSQLValueString($colname_gestion_conservacion, "int"));
$gestion_conservacion = mysql_query($query_gestion_conservacion, $conn) or die(mysql_error());
$row_gestion_conservacion = mysql_fetch_assoc($gestion_conservacion);
$totalRows_gestion_conservacion = mysql_num_rows($gestion_conservacion);

$colname_documentos = "-1";
if (isset($_GET['cod'])) {
  $colname_documentos = $_GET['cod'];
}
if (isset($_GET['ges'])) {
  $colname_documentos = $row_gestion_conservacion['codigo_referencia'];
}

mysql_select_db($database_conn, $conn);
$query_documentos = sprintf("SELECT * FROM documentos WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_documentos, "text"));
$documentos = mysql_query($query_documentos, $conn) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);
$totalRows_documentos = mysql_num_rows($documentos);

$colname_material_soporte = "-1";
if ($totalRows_documentos >= 1) {
  $colname_material_soporte = $row_documentos['tipo_diplomatico'];
}
mysql_select_db($database_conn, $conn);
$query_material_soporte = sprintf("SELECT * FROM material_soporte WHERE tipo_diplomatico = %s ORDER BY material ASC", GetSQLValueString($colname_material_soporte, "int"));
$material_soporte = mysql_query($query_material_soporte, $conn) or die(mysql_error());
$row_material_soporte = mysql_fetch_assoc($material_soporte);
$totalRows_material_soporte = mysql_num_rows($material_soporte);

$colname_medios = "-1";
if ($totalRows_documentos >= 1) {
  $colname_medios = $row_documentos['tipo_diplomatico'];
}
mysql_select_db($database_conn, $conn);
$query_medios = sprintf("SELECT * FROM medios WHERE tipo_diplomatico = %s ORDER BY medio ASC", GetSQLValueString($colname_medios, "int"));
$medios = mysql_query($query_medios, $conn) or die(mysql_error());
$row_medios = mysql_fetch_assoc($medios);
$totalRows_medios = mysql_num_rows($medios);

$colname_agregados = "-1";
if ($totalRows_documentos >= 1) {
  $colname_agregados = $row_documentos['tipo_diplomatico'];
}
mysql_select_db($database_conn, $conn);
$query_agregados = sprintf("SELECT * FROM agregados WHERE tipo_diplomatico = %s ORDER BY agregado ASC", GetSQLValueString($colname_agregados, "int"));
$agregados = mysql_query($query_agregados, $conn) or die(mysql_error());
$row_agregados = mysql_fetch_assoc($agregados);
$totalRows_agregados = mysql_num_rows($agregados);

mysql_select_db($database_conn, $conn);
$query_guardas = "SELECT * FROM guardas ORDER BY guarda ASC";
$guardas = mysql_query($query_guardas, $conn) or die(mysql_error());
$row_guardas = mysql_fetch_assoc($guardas);
$totalRows_guardas = mysql_num_rows($guardas);

mysql_select_db($database_conn, $conn);
$query_tratamientos_anteriores_evidentes = "SELECT * FROM tratamientos_anteriores_evidentes ORDER BY tratamiento_anterior ASC";
$tratamientos_anteriores_evidentes = mysql_query($query_tratamientos_anteriores_evidentes, $conn) or die(mysql_error());
$row_tratamientos_anteriores_evidentes = mysql_fetch_assoc($tratamientos_anteriores_evidentes);
$totalRows_tratamientos_anteriores_evidentes = mysql_num_rows($tratamientos_anteriores_evidentes);

mysql_select_db($database_conn, $conn);
$query_caracteristicas_toma_fotografica = "SELECT * FROM caracteristicas_toma_fotografica ORDER BY caracteristica_toma_fotografica ASC";
$caracteristicas_toma_fotografica = mysql_query($query_caracteristicas_toma_fotografica, $conn) or die(mysql_error());
$row_caracteristicas_toma_fotografica = mysql_fetch_assoc($caracteristicas_toma_fotografica);
$totalRows_caracteristicas_toma_fotografica = mysql_num_rows($caracteristicas_toma_fotografica);

//--relaciones -----------------------------------------------------------------------------------

$colname_rel_conserv_material_soporte = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_material_soporte = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_material_soporte = sprintf("SELECT *, material_soporte.material FROM rel_conserv_material_soporte INNER JOIN material_soporte ON (rel_conserv_material_soporte.codigo_material_soporte = material_soporte.codigo_material_soporte)  WHERE codigo_gestion = %s", GetSQLValueString($colname_rel_conserv_material_soporte, "int"));
$rel_conserv_material_soporte = mysql_query($query_rel_conserv_material_soporte, $conn) or die(mysql_error());
$row_rel_conserv_material_soporte = mysql_fetch_assoc($rel_conserv_material_soporte);
$totalRows_rel_conserv_material_soporte = mysql_num_rows($rel_conserv_material_soporte);

$colname_rel_conserv_medios = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_medios = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_medios = sprintf("SELECT *, medios.medio FROM rel_conserv_medios INNER JOIN medios ON (rel_conserv_medios.codigo_medio = medios.codigo_medio) WHERE codigo_gestion = %s", GetSQLValueString($colname_rel_conserv_medios, "int"));
$rel_conserv_medios = mysql_query($query_rel_conserv_medios, $conn) or die(mysql_error());
$row_rel_conserv_medios = mysql_fetch_assoc($rel_conserv_medios);
$totalRows_rel_conserv_medios = mysql_num_rows($rel_conserv_medios);

$colname_rel_conserv_agregados = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_agregados = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_agregados = sprintf("SELECT *, agregados.agregado FROM rel_conserv_agregados INNER JOIN agregados ON (rel_conserv_agregados.codigo_agregado = agregados.codigo_agregado) WHERE codigo_gestion = %s", GetSQLValueString($colname_rel_conserv_agregados, "int"));
$rel_conserv_agregados = mysql_query($query_rel_conserv_agregados, $conn) or die(mysql_error());
$row_rel_conserv_agregados = mysql_fetch_assoc($rel_conserv_agregados);
$totalRows_rel_conserv_agregados = mysql_num_rows($rel_conserv_agregados);

$colname_rel_conserv_guardas = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_guardas = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_guardas = sprintf("SELECT * FROM rel_conserv_guardas WHERE codigo_gestion = %s ORDER BY guarda ASC", GetSQLValueString($colname_rel_conserv_guardas, "int"));
$rel_conserv_guardas = mysql_query($query_rel_conserv_guardas, $conn) or die(mysql_error());
$row_rel_conserv_guardas = mysql_fetch_assoc($rel_conserv_guardas);
$totalRows_rel_conserv_guardas = mysql_num_rows($rel_conserv_guardas);

$colname_rel_conserv_guardas_sugeridas = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_guardas_sugeridas = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_guardas_sugeridas = sprintf("SELECT * FROM rel_conserv_guardas_sugeridas WHERE codigo_gestion = %s ORDER BY guarda ASC", GetSQLValueString($colname_rel_conserv_guardas_sugeridas, "int"));
$rel_conserv_guardas_sugeridas = mysql_query($query_rel_conserv_guardas_sugeridas, $conn) or die(mysql_error());
$row_rel_conserv_guardas_sugeridas = mysql_fetch_assoc($rel_conserv_guardas_sugeridas);
$totalRows_rel_conserv_guardas_sugeridas = mysql_num_rows($rel_conserv_guardas_sugeridas);

$colname_rel_conserv_tratamientos_anteriores = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_conserv_tratamientos_anteriores = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_conserv_tratamientos_anteriores = sprintf("SELECT * FROM rel_conserv_tratamientos_anteriores WHERE codigo_gestion = %s", GetSQLValueString($colname_rel_conserv_tratamientos_anteriores, "int"));
$rel_conserv_tratamientos_anteriores = mysql_query($query_rel_conserv_tratamientos_anteriores, $conn) or die(mysql_error());
$row_rel_conserv_tratamientos_anteriores = mysql_fetch_assoc($rel_conserv_tratamientos_anteriores);
$totalRows_rel_conserv_tratamientos_anteriores = mysql_num_rows($rel_conserv_tratamientos_anteriores);

$colname_rel_gestion_conserv_caract_toma_fotog_anterior = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_gestion_conserv_caract_toma_fotog_anterior = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_gestion_conserv_caract_toma_fotog_anterior = sprintf("SELECT * FROM rel_gestion_conserv_caract_toma_fotog WHERE codigo_gestion = %s AND posterior=0 ORDER BY caracteristica_toma_fotografica ASC", GetSQLValueString($colname_rel_gestion_conserv_caract_toma_fotog_anterior, "int"));
$rel_gestion_conserv_caract_toma_fotog_anterior = mysql_query($query_rel_gestion_conserv_caract_toma_fotog_anterior, $conn) or die(mysql_error());
$row_rel_gestion_conserv_caract_toma_fotog_anterior = mysql_fetch_assoc($rel_gestion_conserv_caract_toma_fotog_anterior);
$totalRows_rel_gestion_conserv_caract_toma_fotog_anterior = mysql_num_rows($rel_gestion_conserv_caract_toma_fotog_anterior);

$colname_rel_gestion_conserv_caract_toma_fotog_posterior = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_gestion_conserv_caract_toma_fotog_posterior = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_gestion_conserv_caract_toma_fotog_posterior = sprintf("SELECT * FROM rel_gestion_conserv_caract_toma_fotog WHERE codigo_gestion = %s AND posterior=1 ORDER BY caracteristica_toma_fotografica ASC", GetSQLValueString($colname_rel_gestion_conserv_caract_toma_fotog_posterior, "int"));
$rel_gestion_conserv_caract_toma_fotog_posterior = mysql_query($query_rel_gestion_conserv_caract_toma_fotog_posterior, $conn) or die(mysql_error());
$row_rel_gestion_conserv_caract_toma_fotog_posterior = mysql_fetch_assoc($rel_gestion_conserv_caract_toma_fotog_posterior);
$totalRows_rel_gestion_conserv_caract_toma_fotog_posterior = mysql_num_rows($rel_gestion_conserv_caract_toma_fotog_posterior);

$colname_rel_gestion_conserv_recomendaciones_tratamiento = "-1";
if (isset($_GET['ges'])) {
  $colname_rel_gestion_conserv_recomendaciones_tratamiento = $_GET['ges'];
}
mysql_select_db($database_conn, $conn);
$query_rel_gestion_conserv_recomendaciones_tratamiento = sprintf("SELECT * FROM rel_gestion_conserv_recomendaciones_tratamiento WHERE codigo_gestion = %s ORDER BY recomendaciones_tratamiento ASC", GetSQLValueString($colname_rel_gestion_conserv_recomendaciones_tratamiento, "int"));
$rel_gestion_conserv_recomendaciones_tratamiento = mysql_query($query_rel_gestion_conserv_recomendaciones_tratamiento, $conn) or die(mysql_error());
$row_rel_gestion_conserv_recomendaciones_tratamiento = mysql_fetch_assoc($rel_gestion_conserv_recomendaciones_tratamiento);
$totalRows_rel_gestion_conserv_recomendaciones_tratamiento = mysql_num_rows($rel_gestion_conserv_recomendaciones_tratamiento);

mysql_select_db($database_conn, $conn);
$query_recomendaciones_tratamiento = "SELECT * FROM recomendaciones_tratamiento ORDER BY recomendaciones_tratamiento ASC";
$recomendaciones_tratamiento = mysql_query($query_recomendaciones_tratamiento, $conn) or die(mysql_error());
$row_recomendaciones_tratamiento = mysql_fetch_assoc($recomendaciones_tratamiento);
$totalRows_recomendaciones_tratamiento = mysql_num_rows($recomendaciones_tratamiento);

mysql_select_db($database_conn, $conn);
$query_archivos_digitales1 =  sprintf("SELECT * FROM archivos_digitales WHERE codigo_gestion=%s AND fecha_toma_archivo=%s", GetSQLValueString($row_gestion_conservacion['codigo_gestion'],"int"), GetSQLValueString($row_gestion_conservacion['fecha_imagen_incio'],"date"));
$archivos_digitales1 = mysql_query($query_archivos_digitales1, $conn) or die(mysql_error());
$row_archivos_digitales1 = mysql_fetch_assoc($archivos_digitales1);
$totalRows_archivos_digitales1 = mysql_num_rows($archivos_digitales1);

mysql_select_db($database_conn, $conn);
$query_archivos_digitales2 =  sprintf("SELECT * FROM archivos_digitales WHERE codigo_gestion=%s AND fecha_toma_archivo=%s", GetSQLValueString($row_gestion_conservacion['codigo_gestion'],"int"), GetSQLValueString($row_gestion_conservacion['fecha_imagen_posterior'],"date"));
$archivos_digitales2 = mysql_query($query_archivos_digitales2, $conn) or die(mysql_error());
$row_archivos_digitales2 = mysql_fetch_assoc($archivos_digitales2);
$totalRows_archivos_digitales2 = mysql_num_rows($archivos_digitales2);

mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Conservación' ORDER BY idtips ASC";
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
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
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
<script language=JavaScript src='Scripts/innovaeditor.js'></script>
<script type='text/javascript' src='gyl_menu.js'></script>
</head>
<?php if(!isset($_GET['v'])) { 
echo "<script type='text/javascript'>";
echo "	window.parent.frderecha.document.location='conservacion_archivos.php?ges=".$row_gestion_conservacion['codigo_gestion']."';";
echo "</script>";
 } ?>
<script type='text/javascript'> 
var empezar = false
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
//var capas = new Array("e1")
var retardo 
var ocultar

function ct() {
<?Php	
do {
$nomTip = "v".$row_tips['idtips'];
$vt .= "if(document.getElementById('".$nomTip."')){"."\n";
$vt .= "document.getElementById('".$nomTip."').style.visibility='hidden'"."\n";
$vt .= " } "."\n";
echo $vt;
} while ($row_tips = mysql_fetch_assoc($tips));
?>	
}
 
function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}

function muestra_coloca(capa){
	ct();
	clearTimeout(retardo)
	xShow(capa)
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

</script>
<script language="javascript">
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
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">CONSERVACI&Oacute;N - N&ordm; <?php echo $row_gestion_conservacion['codigo_gestion']; ?></td>
    	          <td align="right"></td>
  	          </tr>
  	        </table></td>
        </tr>
        <tr>
    	  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v86 ?><!-- C&oacute;digo de Referencia:-->
   	              <input name="codigo_gestion" type="hidden" id="codigo_gestion" value="<?php echo $row_gestion_conservacion['codigo_gestion']; ?>">
   	              <input name="codigo_institucion" type="hidden" id="codigo_institucion" value="<?php echo $row_documentos['codigo_institucion']; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="codigo_referencia" type="text" class="camposanchos" id="codigo_referencia" readonly value="<?php echo $row_documentos['codigo_referencia']; ?>" /></td>
                </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v87 ?><!-- Nombre del Documento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">
    	            <input name="codigo_identificacion2" type="text" class="camposanchos" id="codigo_identificacion2" readonly value="<?php echo $row_documentos['titulo_original']; ?>" /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v88 ?><!-- Tipo de Diplom&aacute;tico--><input name="tipo_documento" type="hidden" id="tipo_documento" value="<?php echo $row_documentos['tipo_diplomatico']; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">
    	            <input name="codigo_identificacion2" type="text" class="camposanchos" id="codigo_identificacion2" readonly value="<?php if($row_documentos['tipo_diplomatico'] == "8") {echo "8 - Documentos Visuales"; } else if($row_documentos['tipo_diplomatico'] == "9") {echo "9 - Documentos Audiovisuales"; } else if($row_documentos['tipo_diplomatico'] == "10") {echo "10 - Documentos Sonoros"; } else if($row_documentos['tipo_diplomatico'] == "11") {echo "11 - Documentos Textuales"; }?>" /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v89 ?><!-- Fecha Inicio del Tratamiento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_inicio_tratamientod" type="text" id="fecha_inicio_tratamientod" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_inicio_tratamiento'],8,2); ?>">
Mes:
<input name="fecha_inicio_tratamientom" type="text" id="fecha_inicio_tratamientom" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_inicio_tratamiento'],5,2); ?>">
A&ntilde;o:
<input name="fecha_inicio_tratamientoa" type="text" id="fecha_inicio_tratamientoa" size="12" maxlength="4" value="<?php echo substr($row_gestion_conservacion['fecha_inicio_tratamiento'],0,4); ?>">
(dd/mm/aaaa) *</td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v90 ?><!-- Fecha Fin del Tratamiento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_fin_tratamientod" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_fin_tratamiento'],8,2); ?>">
Mes:
<input name="fecha_fin_tratamientom" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_fin_tratamiento'],5,2); ?>">
A&ntilde;o:
<input name="fecha_fin_tratamientoa" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_gestion_conservacion['fecha_fin_tratamiento'],0,4); ?>">
(dd/mm/aaaa)</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v91 ?><!-- Estado de conservaci&oacute;n:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="estado_conservacion" rows="4"  class="camposanchos" id="estado_conservacion"><?php echo $row_gestion_conservacion['estado_conservacion']; ?></textarea></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	         <td class="tituloscampos ins_celdacolor"><?php echo $v92 ?><!-- Descripci&oacute;n f&iacute;sica pormenorizada: --></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td><textarea name="descripcion_fisica_pormenorizada" rows="4"  class="camposanchos" id="descripcion_fisica_pormenorizada"><?php echo $row_gestion_conservacion['descripcion_fisica_pormenorizada']; ?></textarea></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v93 ?><!-- Materiales de Soporte: --> 
   	                <input name="cant_rel_conserv_material_soporte" type="hidden" id="cant_rel_conserv_material_soporte" value="<?php echo $totalRows_rel_conserv_material_soporte; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor">
                  	<table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_material_soporte > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_material_soporte['material']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_material_soporte >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'1','val1':'<?php echo $row_rel_conserv_material_soporte['codigo_material_soporte']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_material_soporte = mysql_fetch_assoc($rel_conserv_material_soporte)); ?>
                    </table>
                  </td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_0">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(0);"><img src="images/bt_024.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
  	                </tr>
  	              </table>
                  
                    </div>
                    <div id="dv_aux_a_0"  style="display:none;">
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Material de Soporte:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="codigo_material_soporte" id="codigo_material_soporte" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_material_soporte['codigo_material_soporte']?>"><?php echo $row_material_soporte['material']?></option>
    	                          <?php
} while ($row_material_soporte = mysql_fetch_assoc($material_soporte));
  $rows = mysql_num_rows($material_soporte);
  if($rows > 0) {
      mysql_data_seek($material_soporte, 0);
	  $row_material_soporte = mysql_fetch_assoc($material_soporte);
  }
?>
                                </select>
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
                    </div>
                    </td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v94 ?><!-- Medios: --><input name="cant_rel_conserv_medios" type="hidden" id="cant_rel_conserv_medios" value="<?php echo $totalRows_rel_conserv_medios; ?>"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_medios > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_medios['medio']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_medios >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'2','val1':'<?php echo $row_rel_conserv_medios['codigo_medio']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_medios = mysql_fetch_assoc($rel_conserv_medios)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_1">
               
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(1);"><img src="images/bt_025.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Medio:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="codigo_medio" id="codigo_medio" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_medios['codigo_medio']?>"><?php echo $row_medios['medio']?></option>
    	                          <?php
} while ($row_medios = mysql_fetch_assoc($medios));
  $rows = mysql_num_rows($medios);
  if($rows > 0) {
      mysql_data_seek($medios, 0);
	  $row_medios = mysql_fetch_assoc($medios);
  }
?>
                                </select>
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
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v95 ?><!-- Recubrimiento Superficial:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="recubrimiento_superficial" rows="4"  class="camposanchos" id="recubrimiento_superficial"><?php echo $row_gestion_conservacion['recubrimiento_superficial']; ?></textarea></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v96 ?><!-- Agregados:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_agregados > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_agregados['agregado']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_agregados >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'3','val1':'<?php echo $row_rel_conserv_agregados['codigo_agregado']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_agregados = mysql_fetch_assoc($rel_conserv_agregados)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_2">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(2);"><img src="images/bt_026.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Agregado:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="codigo_agregado" id="codigo_agregado" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_agregados['codigo_agregado']?>"><?php echo $row_agregados['agregado']?></option>
    	                          <?php
} while ($row_agregados = mysql_fetch_assoc($agregados));
  $rows = mysql_num_rows($agregados);
  if($rows > 0) {
      mysql_data_seek($agregados, 0);
	  $row_agregados = mysql_fetch_assoc($agregados);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(2);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v97 ?><!-- Guardas:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_guardas > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_guardas['guarda']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_guardas >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'4','val1':'<?php echo $row_rel_conserv_guardas['guarda']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_guardas = mysql_fetch_assoc($rel_conserv_guardas)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_8">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(8);"><img src="images/bt_027.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Guarda:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="guarda" id="guarda" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_guardas['guarda']?>"><?php echo $row_guardas['guarda']?></option>
    	                          <?php
} while ($row_guardas = mysql_fetch_assoc($guardas));
  $rows = mysql_num_rows($guardas);
  if($rows > 0) {
      mysql_data_seek($guardas, 0);
	  $row_guardas = mysql_fetch_assoc($guardas);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(8);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v214 ?><!-- Guardas Sugeridas:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_guardas_sugeridas > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_guardas_sugeridas['guarda']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_guardas_sugeridas >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'5','val1':'<?php echo $row_rel_conserv_guardas_sugeridas['guarda']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_guardas_sugeridas = mysql_fetch_assoc($rel_conserv_guardas_sugeridas)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_3">
               
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(3);"><img src="images/bt_027.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Guarda:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="guardasugerida" id="guardasugerida" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_guardas['guarda']?>"><?php echo $row_guardas['guarda']?></option>
    	                          <?php
} while ($row_guardas = mysql_fetch_assoc($guardas));
  $rows = mysql_num_rows($guardas);
  if($rows > 0) {
      mysql_data_seek($guardas, 0);
	  $row_guardas = mysql_fetch_assoc($guardas);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(3);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v98 ?><!-- Caracter&iacute;sticas:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td><textarea name="caracteristica" rows="10" class="camposanchos" id="caracteristica"><?php echo $row_gestion_conservacion['caracteristica']; ?></textarea><script>
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
    ["grpEdit", "", ["Undo", "Redo"<?php if($s1!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
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

    oEdit6.REPLACE("caracteristica");

            </script></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v99 ?><!-- Tratamientos Anteriores:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_conserv_tratamientos_anteriores > 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_conserv_tratamientos_anteriores['tratamiento_anterior']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_conserv_tratamientos_anteriores >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'6','val1':'<?php echo $row_rel_conserv_tratamientos_anteriores['tratamiento_anterior']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_conserv_tratamientos_anteriores = mysql_fetch_assoc($rel_conserv_tratamientos_anteriores)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_4">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(4);"><img src="images/bt_028.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Tratamiendo Anterior:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="tratamiento_anterior" id="tratamiento_anterior" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_tratamientos_anteriores_evidentes['tratamiento_anterior']?>"><?php echo $row_tratamientos_anteriores_evidentes['tratamiento_anterior']?></option>
    	                          <?php
} while ($row_tratamientos_anteriores_evidentes = mysql_fetch_assoc($tratamientos_anteriores_evidentes));
  $rows = mysql_num_rows($tratamientos_anteriores_evidentes);
  if($rows > 0) {
      mysql_data_seek($tratamientos_anteriores_evidentes, 0);
	  $row_tratamientos_anteriores_evidentes = mysql_fetch_assoc($tratamientos_anteriores_evidentes);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(4);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v100 ?><!-- Diagn&oacute;stico Espec&iacute;fico:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="diagnostico_especifico" rows="10" class="camposanchos" id="diagnostico_especifico"><?php echo $row_gestion_conservacion['diagnostico_especifico']; ?></textarea><script>
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
    ["grpEdit", "", ["Undo", "Redo"<?php if($s1!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
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

    oEdit2.REPLACE("diagnostico_especifico");

            </script></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v101 ?><!-- Imagen de Inicio del Tratamiendo: --><span style="color:#F00"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?> 
    	          <input name="codigo_archivo" type="hidden" id="codigo_archivo" value="<?php echo $row_archivos_digitales1['codigo_archivo']; ?>">
    	          <input name="extension" type="hidden" id="extension" value="<?php echo $row_archivos_digitales1['extension']; ?>">
    	          </span></td>
  	          </tr>
              <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="37" height="38" align="center"><img src="images/c<?php echo $row_archivos_digitales1['tipo']; ?>.jpg" width="25" height="30"></td>
    	              <td width="552"><input type="file" name="imagenInicio" id="imagenInicio" style="width:552px;"></td>
    	              <td width="41" align="center"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><input type="submit" name="button2" id="button2" value="G" class="btonmedio"><?php } ?><?php } ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v102 ?><!-- Fecha de la Imagen del Inicio del Tratamiendo:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_imagen_inciod" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_incio'],8,2); ?>">
Mes:
<input name="fecha_imagen_inciom" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_incio'],5,2); ?>">
A&ntilde;o:
<input name="fecha_imagen_incioa" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_incio'],0,4); ?>">
(dd/mm/aaaa)
<input name="fecha_imagen_incio_vieja" type="hidden" id="fecha_imagen_incio_vieja" value="<?php echo $row_gestion_conservacion['fecha_imagen_incio']; ?>"></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v105 ?><!-- Caracter&iacute;sticas de la toma fotogr&aacute;fica del inicio del tratamiento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_gestion_conserv_caract_toma_fotog_anterior> 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_gestion_conserv_caract_toma_fotog_anterior['caracteristica_toma_fotografica']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_gestion_conserv_caract_toma_fotog_anterior >= 1) { ?>
                        <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'7','val1':'<?php echo $row_rel_gestion_conserv_caract_toma_fotog_anterior['caracteristica_toma_fotografica']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_gestion_conserv_caract_toma_fotog_anterior = mysql_fetch_assoc($rel_gestion_conserv_caract_toma_fotog_anterior)); ?>
                    </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_5">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(5);"><img src="images/bt_029.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Caracter&iacute;stica:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="caracteristica_toma_fotografica_inicio" id="caracteristica_toma_fotografica_inicio" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_caracteristicas_toma_fotografica['caracteristica_toma_fotografica']?>"><?php echo $row_caracteristicas_toma_fotografica['caracteristica_toma_fotografica']?></option>
    	                          <?php
} while ($row_caracteristicas_toma_fotografica = mysql_fetch_assoc($caracteristicas_toma_fotografica));
  $rows = mysql_num_rows($caracteristicas_toma_fotografica);
  if($rows > 0) {
      mysql_data_seek($caracteristicas_toma_fotografica, 0);
	  $row_caracteristicas_toma_fotografica = mysql_fetch_assoc($caracteristicas_toma_fotografica);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(5);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v103 ?><!-- Imagen del Fin del Tratamiendo: --><span style="color:#F00"><?php if(isset($_GET['msg2'])) { echo $_GET['msg2']; } ?></span><input name="codigo_archivo_posterior" type="hidden" id="codigo_archivo_posterior" value="<?php echo $row_archivos_digitales2['codigo_archivo']; ?>">
    	          <input name="extension_posterior" type="hidden" id="extension_posterior" value="<?php echo $row_archivos_digitales2['extension']; ?>"></td>
  	          </tr>
              <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="37" align="center"><img src="images/c<?php echo $row_archivos_digitales2['tipo']; ?>.jpg" width="25" height="30"></td>
    	              <td width="552"><input type="file" name="imagenfinal" id="imagenfinal" style="width:552px;"></td>
    	              <td width="41" align="center"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><input type="submit" name="button3" id="button3" value="G" class="btonmedio"><?php } ?><?php } ?></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v104 ?><!-- Fecha de la Imagen posterior al Tratamiendo:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_imagen_posteriord" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_posterior'],8,2); ?>">
Mes:
<input name="fecha_imagen_posteriorm" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_posterior'],5,2); ?>">
A&ntilde;o:
<input name="fecha_imagen_posteriora" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_gestion_conservacion['fecha_imagen_posterior'],0,4); ?>">
(dd/mm/aaaa)<input name="fecha_imagen_posterior_vieja" type="hidden" id="fecha_imagen_posterior_vieja" value="<?php echo $row_gestion_conservacion['fecha_imagen_posterior']; ?>"></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v111; ?><!-- Caracter&iacute;sticas de la toma fotogr&aacute;fica del fin del tratamiento:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td class="ins_celdtadirecciones "><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_gestion_conserv_caract_toma_fotog_posterior> 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_gestion_conserv_caract_toma_fotog_posterior['caracteristica_toma_fotografica']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_gestion_conserv_caract_toma_fotog_posterior >= 1) { ?>
                          <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'8','val1':'<?php echo $row_rel_gestion_conserv_caract_toma_fotog_posterior['caracteristica_toma_fotografica']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_gestion_conserv_caract_toma_fotog_posterior = mysql_fetch_assoc($rel_gestion_conserv_caract_toma_fotog_posterior)); ?>
                    </table></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_6">
               
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(6);"><img src="images/bt_029.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Caracter&iacute;stica:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="caracteristica_toma_fotografica_posterior" id="caracteristica_toma_fotografica_posterior" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_caracteristicas_toma_fotografica['caracteristica_toma_fotografica']?>"><?php echo $row_caracteristicas_toma_fotografica['caracteristica_toma_fotografica']?></option>
    	                          <?php
} while ($row_caracteristicas_toma_fotografica = mysql_fetch_assoc($caracteristicas_toma_fotografica));
  $rows = mysql_num_rows($caracteristicas_toma_fotografica);
  if($rows > 0) {
      mysql_data_seek($caracteristicas_toma_fotografica, 0);
	  $row_caracteristicas_toma_fotografica = mysql_fetch_assoc($caracteristicas_toma_fotografica);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(6);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v106 ?><!-- Recomendaciones y/o propuestas de tratamientos:--></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td class="ins_celdtadirecciones "><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
                    <?php do { ?>
                    <?php if($totalRows_rel_gestion_conserv_recomendaciones_tratamiento> 1) { ?>
                    <tr>
                    	<td height="1" colspan="2" bgcolor="#FFFFFF"></td>
                    </tr>
                    <?php } ?>
              <tr>
                    	<td width="543" class="ins_celdtadirecciones "><?php echo $row_rel_gestion_conserv_recomendaciones_tratamiento['recomendaciones_tratamiento']; ?></td>
                        <td width="24" align="center" class="ins_celdtadirecciones " ><?php if($s1!="C") { ?><?php if($totalRows_rel_gestion_conserv_recomendaciones_tratamiento >= 1) { ?>
                          <a onDblClick="relocate('conservacion_update.php',{'ges':'<?PHP echo $_GET['ges']; ?>','elm':'9','val1':'<?php echo $row_rel_gestion_conserv_recomendaciones_tratamiento['recomendaciones_tratamiento']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a>                          <?php } ?><?php } ?></td>
                    </tr>
                     <?php } while ($row_rel_gestion_conserv_recomendaciones_tratamiento = mysql_fetch_assoc($rel_gestion_conserv_recomendaciones_tratamiento)); ?>
                    </table></td>
  	              </tr>
  	            </table></td>
  	          </tr>
    	        <tr>
    	          <td><div id="dv_aux_c_7">
                
    	            <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
    	              <tr>
    	                <td align="right"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><a onClick="abriraux(7);"><img src="images/bt_030.jpg" width="161" height="20" border="0"></a><?php } ?><?php } ?></td>
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
    	                        <td width="126" class="ins_tituloscampos" >&nbsp;&nbsp;Recomendaci&oacute;n:</td>
    	                        <td width="484" class="ins_tituloscampos"><select name="recomendaciones_tratamiento" id="recomendaciones_tratamiento" class="camposmedioscbt">
    	                          <option value="">Seleccione Opci&oacute;n</option>
    	                          <?php
do {  
?>
    	                          <option value="<?php echo $row_recomendaciones_tratamiento['recomendaciones_tratamiento']?>"><?php echo $row_recomendaciones_tratamiento['recomendaciones_tratamiento']?></option>
    	                          <?php
} while ($row_recomendaciones_tratamiento = mysql_fetch_assoc($recomendaciones_tratamiento));
  $rows = mysql_num_rows($recomendaciones_tratamiento);
  if($rows > 0) {
      mysql_data_seek($recomendaciones_tratamiento, 0);
	  $row_recomendaciones_tratamiento = mysql_fetch_assoc($recomendaciones_tratamiento);
  }
?>
                                </select>
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
                  	<td align="right"><a onClick="cerraraux(7);"><img src="images/bt_002.jpg" width="161" height="20" border="0"></a></td>
                  <tr>
                  </table>
                    </div></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v110 ?><!-- Responsables: --></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="responsable" rows="10" class="camposanchos" id="responsable"><?php echo $row_gestion_conservacion['responsable']; ?></textarea><script>
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
    ["grpEdit", "", ["Undo", "Redo"<?php if($s1!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
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

    oEdit3.REPLACE("responsable");

            </script></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v107 ?><!-- Informe del Tratamiento: --></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="informe_tratamiento" rows="10" class="camposanchos" id="informe_tratamiento"><?php echo $row_gestion_conservacion['informe_tratamiento']; ?></textarea><script>
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
    ["grpEdit", "", ["Undo", "Redo"<?php if($s1!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
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

    oEdit4.REPLACE("informe_tratamiento");

            </script></td>
  	          </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v108 ?><!-- Resultado del Tratamiento: --></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><textarea name="resultados_tratamiento" rows="10" class="camposanchos" id="resultados_tratamiento"><?php echo $row_gestion_conservacion['resultados_tratamiento']; ?></textarea><script>
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
    ["grpEdit", "", ["Undo", "Redo"<?php if($s1!="C") { ?>, "Save"<?php } ?>, "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
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

    oEdit5.REPLACE("resultados_tratamiento");

            </script></td>
  	          </tr>
    	        <tr>
    	          <td>&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v109 ?><!-- Restringido a exibici&oacute;n:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
   	              SI:
    	            <input <?php if (!(strcmp($row_gestion_conservacion['restringido_exibicion'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="restringido_exibicion" id="radio" value="1">
   	              &nbsp;&nbsp;&nbsp;&nbsp;NO:
   	              <input <?php if($row_gestion_conservacion['restringido_exibicion'] == "0" || $row_gestion_conservacion['restringido_exibicion'] == NULL) {echo "checked=\"checked\"";} ?> type="radio" name="restringido_exibicion" id="radio2" value="0"></td>
  	          </tr>
    	        <tr>
    	          <td>&nbsp;</td>
  	          </tr>
              </table></td>
        </tr>
        <tr>
    	  <td class="celdabotones1"><?php if($s1!="C") { ?><?php if(!isset($_GET['v'])) { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Grabar" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
      <tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
  </table>
<input type="hidden" name="MM_insert" value="form1">
</form>
    <!-- <table width="526" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>codigo referencia (var 50)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Tipo de Diplom&aacute;tico (inf)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>fecha inicio tratamiento (datetime)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>fecha fin tratamiento (datetime)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>estado conservacion (texto) var 50</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Descripcion fisica pormenorizada (var 50)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Materiales de Soporte (MV) </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Medios (MV)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Recubrimiento superficial (var 50)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Agregados (mv)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>guardas (mv)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Guardas Sugeridas (mv)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Caracteristicas (texto) editor</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>tratamientos anteriores(MV)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>diagnostico especifico (texto) Editor</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>imagen inicio Tratamiento</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Caracteristicas de la toma fotografica Inicio Tratamiento(MV)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>imagen Fin Tratamiento</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Caracteristicas de la toma fotografica Fin Tratamiento(MV)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Recomendaciones y/o propuestas de tratamiento(Mv)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Responsables (texto) editor</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Informe del Tratamiento(texto) Editor</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Resultado del tratamiento (texto) Editor</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Restringido exhibicion (s/n)</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table> -->
</body>
</html>
<?php
mysql_free_result($documentos);

mysql_free_result($gestion_conservacion);

mysql_free_result($material_soporte);

mysql_free_result($medios);

mysql_free_result($agregados);

mysql_free_result($guardas);

mysql_free_result($tratamientos_anteriores_evidentes);

mysql_free_result($caracteristicas_toma_fotografica);

mysql_free_result($rel_conserv_material_soporte);

mysql_free_result($rel_conserv_medios);

mysql_free_result($rel_conserv_agregados);

mysql_free_result($rel_conserv_guardas);

mysql_free_result($rel_conserv_tratamientos_anteriores);

mysql_free_result($rel_gestion_conserv_caract_toma_fotog_anterior);

mysql_free_result($rel_gestion_conserv_caract_toma_fotog_posterior);

mysql_free_result($rel_gestion_conserv_recomendaciones_tratamiento);

mysql_free_result($recomendaciones_tratamiento);

mysql_free_result($rel_conserv_guardas_sugeridas);
?>
