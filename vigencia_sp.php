<?php require_once('Connections/conn.php'); ?>
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
?>
<?php

//--comprobando el estado del nivel que se va a cambiar
$estado="";
mysql_select_db($database_conn, $conn);
$query_estados = "SELECT * FROM vis_estados_niveles WHERE codigo_referencia='".$_GET['cod']."' LIMIT 1 ";
$estados = mysql_query($query_estados, $conn) or die(mysql_error());
$row_estados = mysql_fetch_assoc($estados);
$totalRows_estados = mysql_num_rows($estados);

if($row_estados['estado'] == "Completo") {
	
	if(isset($_GET['v']) && $_GET['v'] == "s") {
		$estadofuturo = "Vigente";
	} else if($_GET['v'] == "n") {
		$estadofuturo = "No Vigente";
	}
	
	//--comprobando la vigencia del nivel superior al que se va a cambiar
	mysql_select_db($database_conn, $conn);
	$query_estadosuperior = "SELECT * FROM vis_estados_niveles WHERE codigo_referencia='".$row_estados['cod_ref_sup']."' LIMIT 1 ";
	$estadosuperior = mysql_query($query_estadosuperior, $conn) or die(mysql_error());
	$row_estadosuperior = mysql_fetch_assoc($estadosuperior);
	$totalRows_estadosuperior = mysql_num_rows($estadosuperior);
	if($row_estados['cod_ref_sup'] == "0") {
		$estado="Vigente";
	} else {
		$estado=$row_estadosuperior['estado'];
	}
	
	
	if($estado == "Vigente") {
		if ($row_estados['tipo'] == "0") {
		$insertSQL1 = sprintf("INSERT INTO instituciones_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_GET['cod'], "text"),
                GetSQLValueString($estadofuturo, "text"),
  				GetSQLValueString($_SESSION['MM_usuario'], "text"));
				
			if(isset($_GET['v']) && $_GET['v'] == "s") {		
				$updateSQL1 = sprintf("UPDATE instituciones SET vigente=1 WHERE codigo_identificacion=%s",
				GetSQLValueString($_GET['cod'], "text"));
			}
				
		} else if ($row_estados['tipo'] == "1" || $row_estados['tipo'] == "2" || $row_estados['tipo'] == "3" || $row_estados['tipo'] == "4" || $row_estados['tipo'] == "5" || $row_estados['tipo'] == "6" || $row_estados['tipo'] == "7") {
			
		$insertSQL1 = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_GET['cod'], "text"),
                GetSQLValueString($estadofuturo, "text"),
  				GetSQLValueString($_SESSION['MM_usuario'], "text"));	
				
			if(isset($_GET['v']) && $_GET['v'] == "s") {		
				$updateSQL1 = sprintf("UPDATE niveles SET vigente=1 WHERE codigo_referencia=%s",
				GetSQLValueString($_GET['cod'], "text"));	
			}
				
		} else if ($row_estados['tipo'] == "8" || $row_estados['tipo'] == "9" || $row_estados['tipo'] == "10" || $row_estados['tipo'] == "11") {
			
		$insertSQL1 = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_GET['cod'], "text"),
                GetSQLValueString($estadofuturo, "text"),
  				GetSQLValueString($_SESSION['MM_usuario'], "text"));
		
			if(isset($_GET['v']) && $_GET['v'] == "s") {		
			$updateSQL1 = sprintf("UPDATE documentos SET vigente=1, supervicion=1 WHERE codigo_referencia=%s",
				GetSQLValueString($_GET['cod'], "text"));
			}
						
		}

		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL1, $conn) or die(mysql_error());
		
		if(isset($_GET['v']) && $_GET['v'] == "s") {
			mysql_select_db($database_conn, $conn);
			$Result2 = mysql_query($updateSQL1, $conn) or die(mysql_error());
		}
		
		 
	} else {
		echo "<script language=\"javascript\">alert(\"El Nivel Superior no está en estado Vigente\");</script>";	
	}
	mysql_free_result($estadosuperior);
}
mysql_free_result($estados);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #afa088;
}
</style>
</head>
<script language="javascript">
	<?php if(isset($_GET['k'])){ ?>
	window.parent.frcentral.frambos.desactivar();
	<?PHP } ?>
	<?php if(!isset($_GET['k'])){ ?>
	window.parent.frcentral.frizquierda.desactivar();
	window.parent.frcentral.frderecha.location='blanco.php';
	<?PHP } ?>
</script>
<body>
</body>
</html>