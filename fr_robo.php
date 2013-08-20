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

$colname_gestion_conservacion = "-1";
if (isset($_GET['cod'])) {
  $colname_gestion_conservacion = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_gestion_conservacion = sprintf("SELECT * FROM documentos_robados WHERE codigo_referencia = %s", GetSQLValueString($colname_gestion_conservacion, "text"));
$gestion_conservacion = mysql_query($query_gestion_conservacion, $conn) or die(mysql_error());
$row_gestion_conservacion = mysql_fetch_assoc($gestion_conservacion);
$totalRows_gestion_conservacion = mysql_num_rows($gestion_conservacion);

$sit="";
if(isset($_GET['sit']) && $_GET['sit'] != "" && $_GET['sit'] != "Robado" && $_GET['sit'] != "No Localizado") {
	$sit="&sit=".$_GET['sit'];
}

if($totalRows_gestion_conservacion == 0) {
	$updateGoTo = "fr_robo_update.php?cod=".$_GET['cod'].$sit;
	header(sprintf("Location: %s", $updateGoTo));
}

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
}
</style>
</head>
<script language="javascript">

function TamVentana() {   
  var Tamanyo = [0, 0];   
  if (typeof window.innerWidth != 'undefined')   
  {   
    Tamanyo = [   
        window.innerWidth,   
        window.innerHeight   
    ];   
  }   
  else if (typeof document.documentElement != 'undefined'   
      && typeof document.documentElement.clientWidth !=   
      'undefined' && document.documentElement.clientWidth != 0)   
  {   
 Tamanyo = [   
        document.documentElement.clientWidth,   
        document.documentElement.clientHeight   
    ];   
  }   
  else   {   
    Tamanyo = [   
        document.getElementsByTagName('body')[0].clientWidth,   
        document.getElementsByTagName('body')[0].clientHeight   
    ];   
  }   
  return Tamanyo;   
}

window.onload = function() {   
  var Tam = TamVentana();
  document.getElementById('frizquierda').style.height = Tam[1]-60;
  document.getElementById('frderecha').style.height = Tam[1]-60;
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');    
};

window.onresize = function() {   
  var Tam = TamVentana();
  document.getElementById('frizquierda').style.height = Tam[1]-60;
  document.getElementById('frderecha').style.height = Tam[1]-60;  
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');   
};   


</script>
<body>
<table width="1334" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="617" height="30" background="images/angulo_02_b.jpg">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="702" background="images/angulo_01_b.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background:url(images/angulo_06.jpg);"><iframe width="600" src="robo_buscar.php?cod=<?php echo $_GET['cod']; ?><?php echo $sit; ?>" height="
<?php echo $_SESSION['MM_Height']-60; ?>" frameborder="0" name="frizquierda" id="frizquierda"></iframe></td>
    <td>&nbsp;</td>
    <td align="center" valign="top" style="background:url(images/angulo_05.jpg);"><iframe width="680"  height="
<?php echo $_SESSION['MM_Height']-60; ?>" frameborder="0" name="frderecha" id="frderecha"></iframe></td>
  </tr>
  <tr>
    <td height="27" background="images/angulo_04_b.jpg">&nbsp;</td>
    <td>&nbsp;</td>
    <td background="images/angulo_03_b.jpg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($gestion_conservacion);
?>
