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
$query_instituciones = "SELECT codigo_identificacion, formas_conocidas_nombre FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

$code="";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>NIVELES FULL</title>
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

function ira() {
	window.frambos.activar();
	var ira='nivelesfull.php?pascant=f';
	if(document.getElementById('pal').value != "") {
		ira=ira+'&pal='+document.getElementById('pal').value;
	}
	if(document.getElementById('eliminado').checked) {
		ira=ira+'&elm=si';
	}
	if(document.getElementById('inst').value == 't'){
		ira=ira;
	} else {
		ira=ira+'&cod='+document.getElementById('inst').value;
	}
	if(document.getElementById('Ins').checked) {
		ira=ira+'&ins=si';
	}
	window.frambos.document.location=ira;
}

</script>
<body>
<table width="1334" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" background="images/angulo_08_b.jpg" class="titulosSecciones">&nbsp;&nbsp;&nbsp;&nbsp;Ver Cancelados<input name="eliminado" type="checkbox" id="eliminado" value="1"> 
      | 
      <label for="eliminado"></label>
<?php if($_SESSION['MM_idrol'] == 1 || $_SESSION['MM_idrol'] == 2) { ?>Institucion: <select name="inst" id="inst" style="width:300px;">
    <option value="t">Todas las Instituciones</option>
      <?php
do {  
?>
      <option value="<?php echo $row_instituciones['codigo_identificacion']?>" <?php if(isset($_GET['cod']) && $_GET['cod'] != "" && $_GET['cod'] == $row_instituciones['codigo_identificacion']) { echo "selected"; } ?>><?php echo $row_instituciones['formas_conocidas_nombre']?></option>
      <?php
} while ($row_instituciones = mysql_fetch_assoc($instituciones));
  $rows = mysql_num_rows($instituciones);
  if($rows > 0) {
      mysql_data_seek($instituciones, 0);
	  $row_instituciones = mysql_fetch_assoc($instituciones);
  }
?>
    </select>
    &oacute; 
    <input type="text" name="pal" id="pal" style="width:300px;">
     y objetos:
     <input name="Ins" type="checkbox" id="Ins"><?php } ?>
    <label for="Ins"></label>    <input type="button" name="button" id="button" value="Buscar" onClick="ira();"></td>
  </tr>
  <tr>
    <td align="center" style="background:url(images/angulo_12.jpg)"><iframe width="1300" src="nivelesfull.php<?php if(isset($_GET['cod']) && $_GET['cod'] != "") { echo "?cod=".$_GET['cod']."&pascant=f";} ?><?php if(isset($_GET['upi']) && $_GET['upi'] != "") { echo "#".$_GET['upi']; } else { if($_SESSION['MM_idrol'] == 1) { echo "?cod=DNPYM&pascant=f"; } } ?>" height="<?php echo $_SESSION['MM_Height']-60; ?>" frameborder="0" name="frambos" id="frambos"></iframe></td>
  </tr>
  <tr>
    <td height="30" background="images/angulo_11_b.jpg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($instituciones);
?>
