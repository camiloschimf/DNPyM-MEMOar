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

mysql_select_db($database_conn, $conn);
$query_instituciones = "SELECT instituciones.codigo_identificacion, instituciones.formas_conocidas_nombre, (select count(*) from documentos where documentos.codigo_institucion=instituciones.codigo_identificacion) AS cantDoc, (select count(*) from niveles where niveles.codigo_institucion=instituciones.codigo_identificacion AND niveles.tipo_nivel <> 5) AS cantNiv  
FROM instituciones 
ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
</head>
<script language="javascript">

function ver(j,t) {
		window.parent.frderecha.document.location='ficha_registro_pre.php?cod='+j+'&tipo='+t;
	}
	
function verT() {
		window.open('ficha_registro_tot.php');
}
	
</script>
<body>
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">&nbsp;</td>
    	          <td align="right"></td>
  	          </tr>
  	        </table></td>
  </tr>
  <tr>
    	  <td class="fondolineaszulesvert">
          
          <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="3" class="separadormenor">&nbsp;</td>
  	          </tr>
              <tr class="tituloscampos">
    	          <td  bgcolor="#CCCCCC" class="separadormenor">NOMBRE DE LAS INSTITUCIONES</td>
    	          <td align="center" bgcolor="#CCCCCC" class="separadormenor">Niveles</td>
    	          <td align="center" bgcolor="#CCCCCC" class="separadormenor">Diplom&aacute;ticos</td>
  	          </tr>
              <tr>
    	          <td colspan="3" class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <?php do { ?>
   	            <tr>
    	            <td width="489" class="textosbusqueda"><?php echo $row_instituciones['formas_conocidas_nombre']; ?></td>
    	            <td width="90" align="right" class="textosbusqueda"><input name="button" type="submit" class="btnpequeno" id="button" value="Imprimir <?php echo "".$row_instituciones['cantNiv']; ?>" style="width:80px;" <?php if($row_instituciones['cantNiv'] == 0) { echo "disabled";} ?> onClick="ver('<?php echo $row_instituciones['codigo_identificacion']; ?>','N')"></td>
    	            <td width="90" align="right" class="separadormenor" ><input name="button" type="submit" class="btnpequeno" id="button" value="Imprimir <?php echo "".$row_instituciones['cantDoc']; ?>" <?php if($row_instituciones['cantDoc'] == 0) { echo "disabled";} ?> onClick="ver('<?php echo $row_instituciones['codigo_identificacion']; ?>','D')" style="width:80px;" ></td>
  	            </tr>
                <tr>
    	          <td height="2" colspan="3" bgcolor="#f0f0f0" class="separadormenor"></td>
  	          </tr>
    	          <?php } while ($row_instituciones = mysql_fetch_assoc($instituciones)); ?>
    	        <tr>
    	          <td class="textosbusqueda" >Ficha de Inventario Final</td>
    	          <td colspan="2" align="right" class="textosbusqueda" ><span class="separadormenor">
    	            <input name="button2" type="submit" class="btnpequeno" id="button2" value="Imprimir Resumen" style="width:170px;"  onClick="verT()" >
  	            </span></td>
   	          </tr>
    	        <tr>
    	          <td colspan="3" class="separadormenor">&nbsp;</td>
  	          </tr>
    	        <tr>
    	          <td colspan="3" class="separadormenor">&nbsp;</td>
  	          </tr>
              </table>
          
          
          </td>
  </tr>
  <tr>
    	  <td class="celdapieazul"></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($instituciones);
?>
