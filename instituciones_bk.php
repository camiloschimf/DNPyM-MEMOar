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
  $insertSQL = sprintf("INSERT INTO instituciones (codigo_identificacion, formas_conocidas_nombre, estado, fecha_ultima_modificacion) VALUES (%s, %s, 1, %s)",
                       GetSQLValueString(strtoupper(correctorcodigo($_POST['codigo_identificacion'])), "text"),
					   GetSQLValueString($_POST['formas_conocidas_nombre'], "text"),	
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  
  $insertSQL2 = sprintf("INSERT INTO instituciones_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString(correctorcodigo($_POST['codigo_identificacion']), "text"),
                GetSQLValueString("Inicio", "text"),
  				GetSQLValueString($_SESSION['MM_usuario'], "text"));
  

 	 mysql_select_db($database_conn, $conn);
  	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
  

/* redirecciones a la pagina de update */
  $insertGoTo = "instituciones_update.php?cod=".correctorcodigo($_POST['codigo_identificacion']);
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conn, $conn);
$query_vis_tipo_institucion = "SELECT * FROM tipo_institucion ORDER BY tipo_institucion ASC";
$vis_tipo_institucion = mysql_query($query_vis_tipo_institucion, $conn) or die(mysql_error());
$row_vis_tipo_institucion = mysql_fetch_assoc($vis_tipo_institucion);
$totalRows_vis_tipo_institucion = mysql_num_rows($vis_tipo_institucion);

mysql_select_db($database_conn, $conn);
$query_vis_tipo_entidad = "SELECT * FROM tipo_entidad ORDER BY tipo_entidad ASC";
$vis_tipo_entidad = mysql_query($query_vis_tipo_entidad, $conn) or die(mysql_error());
$row_vis_tipo_entidad = mysql_fetch_assoc($vis_tipo_entidad);
$totalRows_vis_tipo_entidad = mysql_num_rows($vis_tipo_entidad);
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
    	          <td class="ins_titulomayor">NUEVA INSTITUCI&Oacute;N</td>
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
    	          <td  class="tituloscampos ins_celdacolor">C&oacute;digo de Identificaci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="codigo_identificacion" type="text" class="camposanchos" id="codigo_identificacion" value="AR/PN/SC/DNPM/" /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
             <tr>
    	          <td class="tituloscampos ins_celdacolor">Forma conocida del nombre:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="formas_conocidas_nombre" type="text" class="camposanchos" id="formas_conocidas_nombre"  /></td>
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
    	          <td width="177" align="right" valign="bottom" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
</body>
</html>
<?php
mysql_free_result($vis_tipo_institucion);

mysql_free_result($vis_tipo_entidad);
?>
