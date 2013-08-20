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

$colname_archivos_digitales = "-1";
if (isset($_GET['ges'])) {
  $colname_archivos_digitales = $_GET['ges'];
}

if(isset($_GET['arc']) && $_GET['arc'] != "") {
	$w=" AND codigo_archivo=".$_GET['arc']; 
} else  {
	$w="";
}

mysql_select_db($database_conn, $conn);
$query_archivos_digitales = sprintf("SELECT * FROM archivos_digitales WHERE codigo_gestion = %s  ORDER BY fecha_toma_archivo DESC", GetSQLValueString($colname_archivos_digitales, "int"));
$archivos_digitales = mysql_query($query_archivos_digitales, $conn) or die(mysql_error());
$row_archivos_digitales = mysql_fetch_assoc($archivos_digitales);
$totalRows_archivos_digitales = mysql_num_rows($archivos_digitales);


mysql_select_db($database_conn, $conn);
$query_archivos_digitales2 = sprintf("SELECT * FROM archivos_digitales WHERE codigo_gestion = %s ".$w." ORDER BY fecha_toma_archivo DESC", GetSQLValueString($colname_archivos_digitales, "int"));
$archivos_digitales2 = mysql_query($query_archivos_digitales2, $conn) or die(mysql_error());
$row_archivos_digitales2 = mysql_fetch_assoc($archivos_digitales2);
$totalRows_archivos_digitales2 = mysql_num_rows($archivos_digitales2);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
function archivero() {
	document.location='conservacion_archivos.php?ges=<?php echo $_GET['ges']; ?>&arc='+document.getElementById('selectArchivo').value;
}
</script>
<body><form name="form1" method="post" action="">
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Archivos de la Conservación</td>
    	          <td align="right"></td>
  	          </tr>
  	        </table></td>
        </tr>
        <tr>
    	  <td class="fondolineaszulesvert_c"><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td>&nbsp;</td>
  	      </tr>
          <tr>
          <td class="tituloscampos ins_celdacolor">Selecciones la fecha del archivo:</td>
          </tr>
    	    <tr>
    	      <td><select name="selectArchivo" id="selectArchivo" style="width:550px;" onChange="archivero();">
    	        <?php
do {  
?>
    	        <option value="<?php echo $row_archivos_digitales['codigo_archivo']?>" <?php if(isset($_GET['arc']) && $_GET['arc'] == $row_archivos_digitales['codigo_archivo']) { echo "selected"; } ?> ><?php echo desfechar($row_archivos_digitales['fecha_toma_archivo']); ?></option>
    	        <?php
} while ($row_archivos_digitales = mysql_fetch_assoc($archivos_digitales));
  $rows = mysql_num_rows($archivos_digitales);
  if($rows > 0) {
      mysql_data_seek($archivos_digitales, 0);
	  $row_archivos_digitales = mysql_fetch_assoc($archivos_digitales);
  }
?>
              </select></td>
  	      </tr>
    	    <tr>
    	      <td>&nbsp;</td>
  	      </tr>
  	    </table></td>
  </tr>
       <tr>
    	  <td class="celdapieazul_c"></td>
  	  </tr>
  </table></form>
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="celdasuperiorsimple_c"></td>
	</tr>
    <tr>
          <td align="center" class="fondolineaszulesvert_c">
          
          <table width="550" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="ins_titulomayor ins_celdacolor"><span><?php echo desfechar($row_archivos_digitales2['fecha_toma_archivo']); ?></span><br><?php if($row_archivos_digitales2['tipo'] == 9 || $row_archivos_digitales2['tipo'] == 10) { ?><embed 
src="player.swf" 
width="550" 
height="<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>"
 allowscriptaccess="always" 
allowfullscreen="true" 
flashvars="width=510&height=<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>&file=archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>"
 /><?php } ?><?php if($row_archivos_digitales2['tipo'] == 8 || $row_archivos_digitales2['tipo'] == 11) { ?><img src="archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>" width="550" >            <?php } ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          
 
          
      </td>
    </tr>
    <tr>
          <td class="celdapieazul_c"></td>
        </tr>
  </table>
</body>
</html>
<?php
mysql_free_result($archivos_digitales);
mysql_free_result($archivos_digitales2);
?>
