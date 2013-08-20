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

$colname_vis_max_nivel = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_max_nivel = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_max_nivel = sprintf("SELECT * FROM niveles WHERE codigo_institucion = %s", GetSQLValueString($colname_vis_max_nivel, "text"));
$vis_max_nivel = mysql_query($query_vis_max_nivel, $conn) or die(mysql_error());
$row_vis_max_nivel = mysql_fetch_assoc($vis_max_nivel);
$totalRows_vis_max_nivel = mysql_num_rows($vis_max_nivel);


$cantInst=count(explode("/" , $_GET['cod']));


$v = explode("/" , $_GET['cod']);

echo $v[(count(explode("/" , $_GET['cod']))) - 1];
/*
$maxCant=0;
do {
$maxCantTmp=count(explode("/" , $row_vis_max_nivel['codigo_referencia']));
echo $maxCantTmp."<br>";
if ($maxCant < $maxCantTmp) {
$maxCant = 	$maxCantTmp;
}
} while ($row_vis_max_nivel = mysql_fetch_assoc($vis_max_nivel));
$maxCant = $maxCant - $cantInst;

echo "-->".$maxCant;
*/
?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	margin-left: 00px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<table width="47" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/c0.png" width="47" height="57"></td>
  </tr>
  <tr>
    <td><img src="images/c1.jpg" width="47" height="58"></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($vis_max_nivel);
?>
