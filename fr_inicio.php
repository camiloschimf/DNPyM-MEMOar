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

mysql_select_db($database_conn, $conn);
$query_vigentes = "SELECT COUNT(*) AS countV FROM vis_estados_niveles WHERE estado = 'Vigente' ".$_SESSION['MM_instituciones'];
$vigentes = mysql_query($query_vigentes, $conn) or die(mysql_error());
$row_vigentes = mysql_fetch_assoc($vigentes);
$totalRows_vigentes = mysql_num_rows($vigentes);

mysql_select_db($database_conn, $conn);
$query_novigente = "SELECT COUNT(*) AS countN FROM vis_estados_niveles WHERE estado = 'No Vigente' ".$_SESSION['MM_instituciones'];
$novigente = mysql_query($query_novigente, $conn) or die(mysql_error());
$row_novigente = mysql_fetch_assoc($novigente);
$totalRows_novigente = mysql_num_rows($novigente);

mysql_select_db($database_conn, $conn);
$query_completos = "SELECT COUNT(*) AS countC FROM vis_estados_niveles WHERE estado = 'Completo' ".$_SESSION['MM_instituciones'];
$completos = mysql_query($query_completos, $conn) or die(mysql_error());
$row_completos = mysql_fetch_assoc($completos);
$totalRows_completos = mysql_num_rows($completos);

mysql_select_db($database_conn, $conn);
$query_pendientes = "SELECT COUNT(*) AS countP FROM vis_estados_niveles WHERE estado = 'Pendiente' ".$_SESSION['MM_instituciones'];
$pendientes = mysql_query($query_pendientes, $conn) or die(mysql_error());
$row_pendientes = mysql_fetch_assoc($pendientes);
$totalRows_pendientes = mysql_num_rows($pendientes);

mysql_select_db($database_conn, $conn);
$query_inicio = "SELECT COUNT(*) AS countI FROM vis_estados_niveles WHERE estado = 'Inicio' ".$_SESSION['MM_instituciones'];
$inicio = mysql_query($query_inicio, $conn) or die(mysql_error());
$row_inicio = mysql_fetch_assoc($inicio);
$totalRows_inicio = mysql_num_rows($inicio);

mysql_select_db($database_conn, $conn);
$query_cancelados = "SELECT COUNT(*) AS countC FROM vis_estados_niveles WHERE estado = 'Cancelado' ".$_SESSION['MM_instituciones'];
$cancelados = mysql_query($query_cancelados, $conn) or die(mysql_error());
$row_cancelados = mysql_fetch_assoc($cancelados);
$totalRows_cancelados = mysql_num_rows($cancelados);
?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Memorar</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="1334" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td height="30" background="images/angulo_08_b.jpg">&nbsp;</td>
    </tr>
	<tr>
	  <td style="background:url(images/angulo_12.jpg)">
	    <table width="300" border="1" align="center" cellpadding="5" cellspacing="0">
        <tr>
	          <td style="border-color:#a99e88; border-style:solid; border-width:medium;"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
	            <tr>
	              <td>&nbsp;</td>
                </tr>
	            <tr>
	              <td>&nbsp;</td>
                </tr>
	            <tr>
	              <td align="center"  class="textoCampo"><strong>BIENVENIDO AL SISTEMA MEMORAR</strong></td>
                </tr>
	            <tr>
	              <td>&nbsp;</td>
                </tr>
	            <tr>
	              <td class="textoCampo">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
        </table>
	    <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
	      <tr>
	        <td height="30">&nbsp;</td>
          </tr>
        </table>
	    <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
	        <td class="botongrabar" bordercolor="#000000">Usted Posee:</td>
          </tr>
          <tr>
	        <td>&nbsp;</td>
          </tr>
	      <tr>
	        <td class="botongrabar"><img src="images/vigente_s.jpg" align="center" > <?php echo $row_vigentes['countV']; ?> elementos Vigentes</td>
          </tr>
	      <tr>
	        <td height="6"></td>
          </tr>
	      <tr>
	        <td class="botongrabar"><img src="images/completo_s.jpg" align="center" > <?php echo $row_completos['countC']; ?> elementos Completos</td>
          </tr>
          <tr>
	        <td height="6"></td>
          </tr>
          <tr>
	        <td class="botongrabar"><img src="images/pendientes_s.jpg" align="center" > <?php echo $row_pendientes['countP']; ?> elementos Pendientes</td>
          </tr>
           <tr>
	        <td height="6"></td>
          </tr>
          <tr>
	        <td class="botongrabar"><img src="images/inicio_s.jpg" align="center" > <?php echo $row_inicio['countI']; ?> elementos Iniciados</td>
          </tr>
           <tr>
	        <td height="6"></td>
          </tr>
          <tr>
	        <td class="botongrabar"><img src="images/cancelado_s.jpg" align="center" > <?php echo $row_cancelados['countC']; ?> elementos Cancelados</td>
          </tr> <tr>
	        <td height="6"></td>
          </tr>
          <tr>
	        <td class="botongrabar"><img src="images/novigente_s.jpg" align="center" > <?php echo $row_novigente['countN']; ?> elementos No Vigentes</td>
          </tr>
	      <tr>
	        <td>&nbsp;</td>
          </tr>
	      <tr>
	        <td height="3" bgcolor="#A99E88"></td>
          </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" background="images/angulo_11_b.jpg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($vigentes);
mysql_free_result($novigente);
mysql_free_result($completos);
mysql_free_result($pendientes);
mysql_free_result($inicio);
mysql_free_result($cancelados);
?>
