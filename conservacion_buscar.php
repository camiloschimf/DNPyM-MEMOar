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

$colname_documentos = "-1";
if (isset($_GET['cod'])) {
  $colname_documentos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_documentos = sprintf("SELECT * FROM documentos WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_documentos, "text"));
$documentos = mysql_query($query_documentos, $conn) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);
$totalRows_documentos = mysql_num_rows($documentos);

$s1=permiso($_SESSION['MM_usuario'], $row_documentos['codigo_institucion'], 'GCON' ,$database_conn,$conn);


//echo $s1.$s2.$s3.$s4;

//$s1 = "";

if($s1 == "" ) {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}




$currentPage = $_SERVER["PHP_SELF"];

$maxRows_gestion_conservacion = 50;
$pageNum_gestion_conservacion = 0;
if (isset($_GET['pageNum_gestion_conservacion'])) {
  $pageNum_gestion_conservacion = $_GET['pageNum_gestion_conservacion'];
}
$startRow_gestion_conservacion = $pageNum_gestion_conservacion * $maxRows_gestion_conservacion;

$colname_gestion_conservacion = "-1";
if (isset($_GET['cod'])) {
  $colname_gestion_conservacion = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_gestion_conservacion = sprintf("SELECT * FROM gestion_conservacion WHERE codigo_referencia = %s ORDER BY codigo_gestion ASC", GetSQLValueString($colname_gestion_conservacion, "text"));
$query_limit_gestion_conservacion = sprintf("%s LIMIT %d, %d", $query_gestion_conservacion, $startRow_gestion_conservacion, $maxRows_gestion_conservacion);
$gestion_conservacion = mysql_query($query_limit_gestion_conservacion, $conn) or die(mysql_error());
$row_gestion_conservacion = mysql_fetch_assoc($gestion_conservacion);

if (isset($_GET['totalRows_gestion_conservacion'])) {
  $totalRows_gestion_conservacion = $_GET['totalRows_gestion_conservacion'];
} else {
  $all_gestion_conservacion = mysql_query($query_gestion_conservacion);
  $totalRows_gestion_conservacion = mysql_num_rows($all_gestion_conservacion);
}
$totalPages_gestion_conservacion = ceil($totalRows_gestion_conservacion/$maxRows_gestion_conservacion)-1;

$colname_gestion_conservacion_nuevo = "-1";
if (isset($_GET['cod'])) {
  $colname_gestion_conservacion_nuevo = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_gestion_conservacion_nuevo = sprintf("SELECT * FROM gestion_conservacion WHERE codigo_referencia = %s ORDER BY fecha_inicio_tratamiento DESC", GetSQLValueString($colname_gestion_conservacion_nuevo, "text"));
$gestion_conservacion_nuevo = mysql_query($query_gestion_conservacion_nuevo, $conn) or die(mysql_error());
$row_gestion_conservacion_nuevo = mysql_fetch_assoc($gestion_conservacion_nuevo);
$totalRows_gestion_conservacion_nuevo = mysql_num_rows($gestion_conservacion_nuevo);

$queryString_gestion_conservacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_gestion_conservacion") == false && 
        stristr($param, "totalRows_gestion_conservacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_gestion_conservacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_gestion_conservacion = sprintf("&totalRows_gestion_conservacion=%d%s", $totalRows_gestion_conservacion, $queryString_gestion_conservacion);
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
	
	scrollbar-face-color: #a99e88;
 	scrollbar-base-color: #d4dce7;
 	scrollbar-arrow-color: #000000;
 	//scrollbar-highlight-color: #181502;
 	scrollbar-3d-light-color: #369;
	 scrollbar-shadow-color: #036;
 	scrollbar-dark-shadow-color: #036;
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript">

	function gestion(i) {
		window.parent.document.location='fr_conservacion_update.php?ges='+i;
	}
	
	function ver(j) {
		window.parent.frderecha.document.location='conservacion_update.php?v=s&ges='+j;
	}
	
	function nuevo(k) {
		window.parent.document.location='fr_conservacion_update.php?cod='+k;
	}

</script>
<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    	<table width="580" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Gestiones de Conservación</td>
    	          <td class="ins_titulomayor">
                    <table border="0" align="right">
                      <tr>
                        <td><?php if ($pageNum_gestion_conservacion > 0) { // Show if not first page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, 0, $queryString_gestion_conservacion); ?>"><img src="First.gif" border="0"></a>
                        <?php } // Show if not first page ?></td>
                        <td><?php if ($pageNum_gestion_conservacion > 0) { // Show if not first page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, max(0, $pageNum_gestion_conservacion - 1), $queryString_gestion_conservacion); ?>"><img src="Previous.gif" border="0"></a>
<?php } // Show if not first page ?></td>
                            <td class="contadorbusqueda"><?php if($totalRows_gestion_conservacion == 0) { echo $startRow_gestion_conservacion; } else { echo ($startRow_gestion_conservacion + 1); } ?> a <?php echo min($startRow_gestion_conservacion + $maxRows_gestion_conservacion, $totalRows_gestion_conservacion) ?> de <?php echo $totalRows_gestion_conservacion ?></td>
                        <td><?php if ($pageNum_gestion_conservacion < $totalPages_gestion_conservacion) { // Show if not last page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, min($totalPages_gestion_conservacion, $pageNum_gestion_conservacion + 1), $queryString_gestion_conservacion); ?>"><img src="Next.gif" border="0"></a>
                        <?php } // Show if not last page ?></td>
                        <td><?php if ($pageNum_gestion_conservacion < $totalPages_gestion_conservacion) { // Show if not last page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, $totalPages_gestion_conservacion, $queryString_gestion_conservacion); ?>"><img src="Last.gif" border="0"></a>
                        <?php } // Show if not last page ?></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
        </tr>
        <tr>
       	  <td class="fondolineaszulesvert_c"><table width="555" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
  <td height="8" colspan="3" valign="top"></td>
		      </tr>
		    <?php do { ?>
		      <tr>
		        <td width="45" valign="top"><img src="images/c<?php echo $row_documentos['tipo_diplomatico']; ?>.jpg" width="40" height="47" border="0"></td>
		        <td width="448" valign="middle"  class="textosbusqueda"><strong><?php echo "DESDE: ".substr($row_gestion_conservacion['fecha_inicio_tratamiento'],8,2)."/".substr($row_gestion_conservacion['fecha_inicio_tratamiento'],5,2)."/".substr($row_gestion_conservacion['fecha_inicio_tratamiento'],0,4); ?> - <?php echo "HASTA: ".substr($row_gestion_conservacion['fecha_fin_tratamiento'],8,2)."/".substr($row_gestion_conservacion['fecha_fin_tratamiento'],5,2)."/".substr($row_gestion_conservacion['fecha_fin_tratamiento'],0,4); ?></strong></td>
		        <td width="62" align="center" valign="middle" >
		          <input type="button" name="button2" id="button2" value="Ver" class="btnpequeno" style="width:60px;" onClick="ver('<?php echo $row_gestion_conservacion['codigo_gestion']; ?>');"><?php if($s1!="C") { ?><input type="button" name="button2" id="button2" value="Continuar" class="btnpequeno" style="width:60px;" onClick="gestion('<?php echo $row_gestion_conservacion['codigo_gestion']; ?>');" <?php if($row_gestion_conservacion['full'] == 1) { echo "disabled"; } ?> ><?php } ?></td>
		        </tr>
		      
<tr>
  <td height="8" colspan="3" valign="top"><hr></td>
		      </tr><?php } while ($row_gestion_conservacion = mysql_fetch_assoc($gestion_conservacion)); ?>
	      </table></td>
        </tr>
        <tr>
        	<td class="fondolineaszulesvert_c"><?php if($s1!="C") { ?><table width="570" border="0" cellspacing="0" cellpadding="0">
        	  <tr>
        	    <td width="393">&nbsp;</td>
        	    <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Nuevo" <?php if($row_gestion_conservacion_nuevo['full'] == 0) { echo "disabled"; } ?>  onClick="JavaScript:nuevo('<?php if(isset($_GET['cod'])){ echo $_GET['cod']; } ?>');">
       	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      	    </tr>
      	  </table><?php } ?></td>
        </tr>
        <tr>
		  <td class="celdapiesimple_c"></td>
		  </tr>
        </table>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($gestion_conservacion);

mysql_free_result($gestion_conservacion_nuevo);

mysql_free_result($documentos);
?>
