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

/* se insertan los registros del formulario 1 */
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	
	
mysql_select_db($database_conn, $conn);
$query_inst_busc_dup = sprintf("SELECT codigo_identificacion FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($_POST['codigo_identificacion_nuevo'], "text"));
$inst_busc_dup = mysql_query($query_inst_busc_dup, $conn) or die(mysql_error());
$row_inst_busc_dup = mysql_fetch_assoc($inst_busc_dup);
$totalRows_inst_busc_dup = mysql_num_rows($inst_busc_dup);	

$error = 0;

if($totalRows_inst_busc_dup <= 0) {
	
$lenActual = strlen($_POST['codigo_identificacion_actual']);
$lenActual = $lenActual+1;

$strSQL1="UPDATE documentos
SET codigo_referencia=CONCAT('".$_POST['codigo_identificacion_nuevo']."',SUBSTRING(codigo_referencia, ".$lenActual."))
WHERE codigo_referencia LIKE '".$_POST['codigo_identificacion_actual']."/%' 
AND codigo_institucion='".$_POST['codigo_identificacion_actual']."'";

$strSQL2="UPDATE documentos_aud
SET codigo_referencia=CONCAT('".$_POST['codigo_identificacion_nuevo']."',SUBSTRING(codigo_referencia, ".$lenActual.")),
cod_ref_sup=CONCAT('".$_POST['codigo_identificacion_nuevo']."',SUBSTRING(cod_ref_sup, ".$lenActual."))
WHERE codigo_referencia LIKE '".$_POST['codigo_identificacion_actual']."/%' 
AND codigo_institucion='".$_POST['codigo_identificacion_actual']."'";

$strSQL3="UPDATE niveles
SET codigo_referencia=CONCAT('".$_POST['codigo_identificacion_nuevo']."',SUBSTRING(codigo_referencia, ".$lenActual.")),
cod_ref_sup=CONCAT('".$_POST['codigo_identificacion_nuevo']."',SUBSTRING(cod_ref_sup, ".$lenActual."))
WHERE codigo_referencia LIKE '".$_POST['codigo_identificacion_actual']."/%' 
AND codigo_institucion='".$_POST['codigo_identificacion_actual']."'";

$strSQL4="UPDATE instituciones
SET codigo_identificacion='".$_POST['codigo_identificacion_nuevo']."'
WHERE codigo_identificacion='".$_POST['codigo_identificacion_actual']."'";

$strSQL5="UPDATE documentos_aud
SET codigo_institucion='".$_POST['codigo_identificacion_nuevo']."'
WHERE codigo_institucion='".$_POST['codigo_identificacion_actual']."'";


	
	

$errorTXT = "";
mysql_select_db($database_conn, $conn);
mysql_query("BEGIN");

$Result1 = mysql_query($strSQL1, $conn);
	if (!$Result1) { $error = 1; $errorTXT .="1-".mysql_error()."<br>"; }
$Result2 = mysql_query($strSQL2, $conn);
	if (!$Result2) { $error = 1; $errorTXT .="2-".mysql_error()."<br>"; }
$Result3 = mysql_query($strSQL3, $conn);
	if (!$Result3) { $error = 1; $errorTXT .="3-".mysql_error()."<br>"; }
$Result4 = mysql_query($strSQL4, $conn);
	if (!$Result4) { $error = 1; $errorTXT .="4-".mysql_error()."<br>"; }
$Result5 = mysql_query($strSQL5, $conn);
	if (!$Result5) { $error = 1; $errorTXT .="5-".mysql_error()."<br>"; }
	
	if ($error == 1){
		mysql_query("ROLLBACK"); 
		echo "<script language=\"javascript\"> alert('No hubo modificación alguna.'); </script>";  
		echo "ERROR: ".$errorTXT;
	} else {
		mysql_query("COMMIT");
	}
	
} else {
	$error = 1;
	echo "<script language=\"javascript\"> alert('El Código Ingresado ya Existe.'); </script>"; 	
}

if($error == 0) {
	$insertGoTo = "instituciones_update.php?cod=".correctorcodigo($_POST['codigo_identificacion_nuevo']);
	header(sprintf("Location: %s", $insertGoTo));
}
  
  
}

mysql_select_db($database_conn, $conn);
$query_Instituciones = "SELECT codigo_identificacion, formas_conocidas_nombre FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$Instituciones = mysql_query($query_Instituciones, $conn) or die(mysql_error());
$row_Instituciones = mysql_fetch_assoc($Instituciones);
$totalRows_Instituciones = mysql_num_rows($Instituciones);

$colname_instituciones_buscadas = "-1";
if (isset($_GET['cod'])) {
  $colname_instituciones_buscadas = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_instituciones_buscadas = sprintf("SELECT codigo_identificacion FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($colname_instituciones_buscadas, "text"));
$instituciones_buscadas = mysql_query($query_instituciones_buscadas, $conn) or die(mysql_error());
$row_instituciones_buscadas = mysql_fetch_assoc($instituciones_buscadas);
$totalRows_instituciones_buscadas = mysql_num_rows($instituciones_buscadas);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1258" />
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
</head>
<script language="javascript">
	function seleccionar() {
		activar();
		document.location='instituciones_udp_codigo.php?cod='+document.getElementById("institucion").value;
		
	}
</script>
<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <div id="f1">
    	<form  id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    	  <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">CORREGIR C&Oacute;DIGO DE UNA INSTITUCI&Oacute;N</td>
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
    	          <td  class="tituloscampos ins_celdacolor">Instituci&oacute;n a Modificar:</td>
  	          </tr>
               <tr>
    	          <td class="ins_celdacolor">
    	            <select name="institucion" id="institucion" class="camposanchos" onChange="seleccionar();">
                    <option value="">Selecciones una Institución</option>
    	              <?php
do {  
?>
    	              <option value="<?php echo $row_Instituciones['codigo_identificacion']?>"<?php if (!(strcmp($row_Instituciones['codigo_identificacion'], $_GET['cod']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Instituciones['formas_conocidas_nombre']?></option>
    	              <?php
} while ($row_Instituciones = mysql_fetch_assoc($Instituciones));
  $rows = mysql_num_rows($Instituciones);
  if($rows > 0) {
      mysql_data_seek($Instituciones, 0);
	  $row_Instituciones = mysql_fetch_assoc($Instituciones);
  }
?>
                    </select></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor">C&oacute;digo de Identificaci&oacute;n Actual:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="codigo_identificacion_actual" type="text" class="camposanchos" id="codigo_identificacion_actual" value="<?php echo $row_instituciones_buscadas['codigo_identificacion']; ?>" readonly /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
             <tr>
    	          <td class="tituloscampos ins_celdacolor">C&oacute;digo de Identificaci&oacute;n Nuevo:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="codigo_identificacion_nuevo" type="text" class="camposanchos" id="codigo_identificacion_nuevo"  /></td>
  	          </tr> 
    	        <tr>
    	          <td class="separadormayor"></td>
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
    	          <td width="177" align="right" valign="bottom" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" onClick="activar();" value="Cambiar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
  	    </table>
    	  <input type="hidden" name="MM_insert" value="form1">
        </form>
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
 <?php require_once('activo.php'); ?>
</body>
</html>
<?php

mysql_free_result($Instituciones);

mysql_free_result($instituciones_buscadas);
?>
