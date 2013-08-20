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


$colname_TIPOnivel = "-1";
if (isset($_GET['cod'])) {
  $colname_TIPOnivel = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_TIPOnivel = sprintf("SELECT * FROM niveles WHERE codigo_referencia=%s", GetSQLValueString($colname_TIPOnivel, "text"));
$TIPOnivel = mysql_query($query_TIPOnivel, $conn) or die(mysql_error());
$row_TIPOnivel = mysql_fetch_assoc($TIPOnivel);
$totalRows_TIPOnivel = mysql_num_rows($TIPOnivel);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
body {
	margin-left: 00px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
  document.getElementById('frambos').style.height = Tam[1]-60;    
};

window.onresize = function() {   
  var Tam = TamVentana();
  document.getElementById('frambos').style.height = Tam[1]-60;   
};   


</script>
<body>
<table width="1334" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" background="images/angulo_08_b.jpg" class="titulosSecciones"><?php if($row_TIPOnivel['tipo_nivel'] == 1) { echo "FONDO / SUBFONDO"; } else if($row_TIPOnivel['tipo_nivel'] == 2) { echo "SECCIÓN / SUBSECCIÓN"; } else if($row_TIPOnivel['tipo_nivel'] == 3) { echo "SERIE / SUBSERIE"; } else if($row_TIPOnivel['tipo_nivel'] == 4) { echo "AGRUPACIÓN DOCUMENTAL"; } ?></td>
  </tr>
  <tr>
    <td align="center" style="background:url(images/angulo_12.jpg)"><iframe width="1300" src="niveles_update.php?cod=<?php echo $_GET['cod']; ?>" height="<?php echo $_SESSION['MM_Height']-60; ?>" frameborder="0" name="frambos" id="frambos"></iframe></td>
  </tr>
  <tr>
    <td height="30" background="images/angulo_11_b.jpg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($TIPOnivel);
?>