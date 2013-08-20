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

$editFormAction = $_SERVER['PHP_SELF'];
$pageFormAction = $_SERVER ['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$_where1 = " WHERE 0=1 ";
if(isset($_POST['busqueda']) && $_POST['busqueda'] != "") {
$_where1 = " WHERE codigo_identificacion LIKE '%".$_POST['busqueda']."%' OR formas_conocidas_nombre LIKE'%".$_POST['busqueda']."%' ";	
}

$maxRows_ver_instituciones = 50;
$pageNum_ver_instituciones = 0;
if (isset($_GET['pageNum_ver_instituciones'])) {
  $pageNum_ver_instituciones = $_GET['pageNum_ver_instituciones'];
}
$startRow_ver_instituciones = $pageNum_ver_instituciones * $maxRows_ver_instituciones;

mysql_select_db($database_conn, $conn);
$query_ver_instituciones = "SELECT * FROM instituciones ".$_where1." ORDER BY codigo_identificacion ASC";
$query_limit_ver_instituciones = sprintf("%s LIMIT %d, %d", $query_ver_instituciones, $startRow_ver_instituciones, $maxRows_ver_instituciones);
$ver_instituciones = mysql_query($query_limit_ver_instituciones, $conn) or die(mysql_error());
$row_ver_instituciones = mysql_fetch_assoc($ver_instituciones);

if (isset($_GET['totalRows_ver_instituciones'])) {
  $totalRows_ver_instituciones = $_GET['totalRows_ver_instituciones'];
} else {
  $all_ver_instituciones = mysql_query($query_ver_instituciones);
  $totalRows_ver_instituciones = mysql_num_rows($all_ver_instituciones);
}
$totalPages_ver_instituciones = ceil($totalRows_ver_instituciones/$maxRows_ver_instituciones)-1;
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
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">RESULTADO DE LA BUSQUEDA</td>
    	          <td align="right">&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
          <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="4" class="separadormayor"></td>
   	            </tr>
    	        <?php do { ?>
                <?php if($totalRows_ver_instituciones >= 1) { ?>
   	            <tr>
   	              <td width="564" class="ins_celdtadirecciones"><?php echo $row_ver_instituciones['codigo_identificacion']; ?> - <?php echo $row_ver_instituciones['formas_conocidas_nombre']; ?></td>
   	              <td width="22" align="center" class="ins_celdtadirecciones"><a href="instituciones_ver.php?cod=<?php echo $row_ver_instituciones['codigo_identificacion']; ?>"><img src="images/ico_005.png" height="18" border="0"></a></td>
   	              <td width="22" align="center" class="ins_celdtadirecciones"><a href="instituciones_update.php?cod=<?php echo $row_ver_instituciones['codigo_identificacion']; ?>"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
   	              <td width="22" align="center" class="ins_celdtadirecciones"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></td>
  	            </tr>
    	          
<tr>
   	            <td colspan="4" class="separadormayor"></td>
   	            </tr>
                <?php } ?>
				<?php } while ($row_ver_instituciones = mysql_fetch_assoc($ver_instituciones)); ?>
        </table></td><tr>
               <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><form  id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"> <table width="658" border="0" cellspacing="0" cellpadding="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">AREA DE BUSQUEDA</td>
    	          <td align="right"><a onClick="cerrarformularios(1)"><img src="images/ico_004.png" alt="Ver detalle"  height="18" border="0"></a></td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">B&uacute;squeda:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="busqueda" type="text" class="camposanchos" id="busqueda" value="<?php echo $row_vis_instituciones['codigo_identificacion']; ?>" /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr></table></td></tr>
              <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Buscar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
    	    <tr>
    	      <td class="celdapieazul"></td>
  	      </tr></table></form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($ver_instituciones);
?>
