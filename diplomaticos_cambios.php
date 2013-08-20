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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_documentos_modificado = 10;
$pageNum_documentos_modificado = 0;
if (isset($_GET['pageNum_documentos_modificado'])) {
  $pageNum_documentos_modificado = $_GET['pageNum_documentos_modificado'];
}
$startRow_documentos_modificado = $pageNum_documentos_modificado * $maxRows_documentos_modificado;

mysql_select_db($database_conn, $conn);
$query_documentos_modificado = "SELECT * FROM vis_diplomaticos WHERE vigente = 1 AND estado_str = 'Completo' ORDER BY fecha_ultima_modificacion ASC";
$query_limit_documentos_modificado = sprintf("%s LIMIT %d, %d", $query_documentos_modificado, $startRow_documentos_modificado, $maxRows_documentos_modificado);
$documentos_modificado = mysql_query($query_limit_documentos_modificado, $conn) or die(mysql_error());
$row_documentos_modificado = mysql_fetch_assoc($documentos_modificado);

if (isset($_GET['totalRows_documentos_modificado'])) {
  $totalRows_documentos_modificado = $_GET['totalRows_documentos_modificado'];
} else {
  $all_documentos_modificado = mysql_query($query_documentos_modificado);
  $totalRows_documentos_modificado = mysql_num_rows($all_documentos_modificado);
}
$totalPages_documentos_modificado = ceil($totalRows_documentos_modificado/$maxRows_documentos_modificado)-1;

$queryString_documentos_modificado = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_documentos_modificado") == false && 
        stristr($param, "totalRows_documentos_modificado") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_documentos_modificado = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_documentos_modificado = sprintf("&totalRows_documentos_modificado=%d%s", $totalRows_documentos_modificado, $queryString_documentos_modificado);






mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Diplomáticos' ORDER BY idtips ASC";
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);



do {

$nomTip = "v".$row_tips['idtips'];
$$nomTip = "";

$$nomTip .= "<div id=\"".$nomTip."\" style=\"position:absolute; z-index:1000; visibility:hidden; width:505px;\"  onmouseover=\"muestra_retarda('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\"><table width=\"505\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><img src=\"images/globo1.png\" width=\"505\" height=\"28\"></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td><table width=\"490\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "      <tr>";
$$nomTip .= "        <td style=\"background:url(images/globo3.png);\"><table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$$nomTip .= "          <tr>";
$$nomTip .= "            <td class=\"tips\">".$row_tips['tip'];
if($row_tips['requerido'] == 1) {
$$nomTip .= "<b> CAMPO OBLIGATORIO</b>";
}
$$nomTip .= "</td>";
$$nomTip .= "          </tr>";
$$nomTip .= "        </table></td>";
$$nomTip .= "      </tr>";
$$nomTip .= "    </table></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "  <tr>";
$$nomTip .= "    <td align=\"right\"><img src=\"images/globo2.png\" width=\"490\" height=\"10\"></td>";
$$nomTip .= "  </tr>";
$$nomTip .= "</table>";
$$nomTip .= "</div><a onMouseOver=\"muestra_coloca('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\">";
if ($row_tips['requerido'] == 1) {
$$nomTip .= "<img src=\"images/help2.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";
} else {
$$nomTip .= "<img src=\"images/help1.png\" width=\"18\" height=\"18\" align=\"absmiddle\">";	
}
$$nomTip .= "</a>";
$$nomTip .= "&nbsp;".$row_tips['item'];


} while ($row_tips = mysql_fetch_assoc($tips));
$rows = mysql_num_rows($tips);
  if($rows > 0) {
      mysql_data_seek($tips, 0);
	  $row_tips = mysql_fetch_assoc($tips);
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	background-color: #FFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script type='text/javascript' src='gyl_menu.js'></script>
<script type='text/javascript'> 
var empezar = false
var e = "0";
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
//var capas = new Array("e1")
var retardo 
var ocultar

function ct(g) {
	if(g != "" && g != "0") {
		document.getElementById(g).style.visibility='hidden';
	}
}
 
function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}

function muestra_coloca(capa){
	ct(e);
	clearTimeout(retardo)
	xShow(capa)
	e=capa;
}
 
function oculta_retarda(capa){
	ocultar =capa
	clearTimeout(retardo)
	retardo = setTimeout("xHide('" + ocultar + "')",1000)
}
 
function muestra_retarda(ind){
	clearTimeout(retardo)
}
</script>
</head>
<script language="javascript">
	function vigencia(a) {
		activar();
		window.parent.parent.frameestados.location='vigencia_sp.php?k=l&v=s&cod='+a;
		document.getElementById('div-'+a).style.display = 'none';
	}
	
	function novigencia(a) {
		activar();
		window.parent.parent.frameestados.location='vigencia_sp.php?k=l&v=n&cod='+a;
		document.getElementById('div-'+a).style.display = 'none';
	}
	
	function modificar(a) {
	
	window.parent.parent.frameestados.location = 'nivdoc_estados.php?cod='+a ;
	//document.location.target= '_blank';
	window.parent.document.location.href = 'fr_diplomaticos.php?cod='+a ;	
	
}
</script>
<body><table width="1250" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="1250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table width="270" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="25" align="left"><?php if ($pageNum_documentos_modificado > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, 0, $queryString_documentos_modificado); ?>"><img src="First.gif" width="18" height="13" border="0"></a>
              <?php } // Show if not first page ?></td>
          <td width="25" align="left"><?php if ($pageNum_documentos_modificado > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, max(0, $pageNum_documentos_modificado - 1), $queryString_documentos_modificado); ?>"><img src="Previous.gif" width="14" height="13" border="0"></a>
              <?php } // Show if not first page ?></td>
              <td width="170" align="center" class="estiloCampos">Documentos <?php echo ($startRow_documentos_modificado + 1) ?> a <?php echo min($startRow_documentos_modificado + $maxRows_documentos_modificado, $totalRows_documentos_modificado) ?> de <?php echo $totalRows_documentos_modificado ?></td>
          <td width="25" align="right"><?php if ($pageNum_documentos_modificado < $totalPages_documentos_modificado) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, min($totalPages_documentos_modificado, $pageNum_documentos_modificado + 1), $queryString_documentos_modificado); ?>"><img src="Next.gif" width="14" height="13"  border="0"></a>
              <?php } // Show if not last page ?></td>
          <td width="25" align="right"><?php if ($pageNum_documentos_modificado < $totalPages_documentos_modificado) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, $totalPages_documentos_modificado, $queryString_documentos_modificado); ?>"><img src="Last.gif" width="18" height="13" border="0"></a>
              <?php } // Show if not last page ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<table width="1250" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php do { ?>
<?php

mysql_select_db($database_conn, $conn);
$query_archivos_digitales2 = sprintf("SELECT * FROM archivos_digitales WHERE codigo_referencia = '".$row_documentos_modificado['codigo_referencia']."' ORDER BY fecha_toma_archivo DESC", GetSQLValueString($colname_archivos_digitales, "text"));
$archivos_digitales2 = mysql_query($query_archivos_digitales2, $conn) or die(mysql_error());
$row_archivos_digitales2 = mysql_fetch_assoc($archivos_digitales2);
$totalRows_archivos_digitales2 = mysql_num_rows($archivos_digitales2);


mysql_select_db($database_conn, $conn);
$query_documentos_adu = sprintf("SELECT * FROM documentos_aud WHERE codigo_referencia = %s ORDER BY fecha_update DESC LIMIT 1", GetSQLValueString($row_documentos_modificado['codigo_referencia'], "text"));
$documentos_adu = mysql_query($query_documentos_adu, $conn) or die(mysql_error());
$row_documentos_adu = mysql_fetch_assoc($documentos_adu);
$totalRows_documentos_adu = mysql_num_rows($documentos_adu);

?><table width="1250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" class="ins_celdacolor ins_titulomayor">&nbsp;&nbsp;&nbsp;<?php echo $row_documentos_modificado['formas_conocidas_nombre']; ?> (<?php echo $row_documentos_modificado['codigo_institucion']; ?>)</td>
  </tr>
</table>
<div id="div-<?php echo $row_documentos_modificado['codigo_referencia']; ?>">
  <table width="1250" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="580" valign="top">&nbsp;</td>
      <td width="20" valign="top">&nbsp;</td>
      <td width="650" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
		<td class="celdasuperiorsimple_c"></td>
	</tr>
    <tr>
          <td align="center" class="fondolineaszulesvert_c"><table width="550" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><?php echo desfechar($row_archivos_digitales2['fecha_toma_archivo']); ?></span><br><?php if($row_archivos_digitales2['tipo'] == 9 || $row_archivos_digitales2['tipo'] == 10) { ?><embed 
src="player.swf" 
width="550" 
height="<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>"
 allowscriptaccess="always" 
allowfullscreen="true" 
flashvars="width=510&height=<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>&file=archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>"
 /><?php } ?><?php if($row_archivos_digitales2['tipo'] == 8 || $row_archivos_digitales2['tipo'] == 11) { ?>
              <a href="archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>" target="_blank"><img src="archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>" width="550" ></a>                <?php } ?></td>
            </tr>
     </table></td>
    </tr>
     <tr>
          <td class="celdapieazul_c"></td>
        </tr>
    </table></td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><table width="658" border="0" cellspacing="0" cellpadding="0">
    	<tr>
        	<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor"><!-- &Aacute;REA DE IDENTIFICACI&Oacute;N --></td>
    	          <td align="right">&nbsp;</td>
  	          </tr>
  	        </table></td>
        </tr>
    	<tr>
    	  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v70; ?><!-- C&oacute;digo de Referencia de la Instituci&oacute;n:--></td>
  	          </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['codigo_institucion']; ?></td>
              </tr>
              <tr>
                     <td class="separadormayor"></td>
                </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v71; ?><!-- C&oacute;digo de Referencia: --></td>
              </tr>
              <tr>
                <td class="botongrabar ins_celdacolor"><?php echo $row_documentos_modificado['codigo_referencia']; ?></td>
              </tr>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v72; ?><!-- Tipo Documento Diplom&aacute;tico: --></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php if($row_documentos_modificado['tipo_diplomatico'] == "8") {echo "8 - Documentos Visuales"; } else if($row_documentos_modificado['tipo_diplomatico'] == "9") {echo "9 - Documentos Audiovisuales"; } else if($row_documentos_modificado['tipo_diplomatico'] == "10") {echo "10 - Documentos Sonoros"; } else if($row_documentos_modificado['tipo_diplomatico'] == "11") {echo "11 - Documentos Textuales"; }?></td>
              </tr>
              <?PHP if($row_documentos_modificado['titulo_original'] != $row_documentos_adu['titulo_original']){ ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v73; ?><!-- T&iacute;tulo Original:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['titulo_original']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['titulo_original']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['titulo_atribuido'] != $row_documentos_adu['titulo_atribuido']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v74; ?><!--T&iacute;tulo Atribu&iacute;do:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['titulo_atribuido']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['titulo_atribuido']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['titulo_traducido'] != $row_documentos_adu['titulo_traducido']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v75; ?><!--T&iacute;tulo Traducido:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['titulo_traducido']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['titulo_traducido']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['fecha_registro'] != $row_documentos_adu['fecha_registro']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v76; ?><!--Fecha de Registro:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo substr($row_documentos_modificado['fecha_registro'],8,2); ?>/<?php echo substr($row_documentos_modificado['fecha_registro'],5,2); ?>/<?php echo substr($row_documentos_modificado['fecha_registro'],0,4); ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_registro']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['numero_inventario_unidad_documental'] != $row_documentos_adu['numero_inventario_unidad_documental']){ ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v77; ?><!--N&uacute;mero de Inventario:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_inventario_unidad_documental']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_inventario_unidad_documental']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['numero_registro_inventario_anterior'] != $row_documentos_adu['numero_registro_inventario_anterior']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v78; ?><!--N&uacute;mero de Registro Anterior:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_registro_inventario_anterior']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_registro_inventario_anterior']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td><table width="630" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="121" height="30" class="niv_menu">Tipo de Documento</td>
                    <td width="509"><hr></td>
                  </tr>
                </table></td>
              </tr>
              <?php if($row_documentos_modificado['tipo_general_documento'] != $row_documentos_adu['tipo_general_documento']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v79; ?><!--Tipo General de Documento:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tipo_general_documento']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tipo_general_documento']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['tipo_especifico_documento'] != $row_documentos_adu['tipo_especifico_documento']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v80; ?><!--Tipo Espec&iacute;fico de Documento:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tipo_especifico_documento']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tipo_especifico_documento']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
               <?php if( $row_documentos_modificado['tradicion_documental'] != $row_documentos_adu['tradicion_documental']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v81; ?><!--Tradici&oacute;n Documental:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tradicion_documental']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tradicion_documental']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
               <?php } ?>
               <?php if($row_documentos_modificado['numero_registro_sur'] != $row_documentos_adu['numero_registro_sur']) { ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v84; ?><!--Objeto CONAR:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_registro_sur']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_registro_sur']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['numero_registro_bibliografico'] != $row_documentos_adu['numero_registro_bibliografico']){ ?>
              <tr>
                <td class="separadormayor"></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><?php echo $v85; ?><!--Registro Bibliogr&aacute;fico:--></td>
              </tr>
              <tr>
                <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_registro_bibliografico']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_registro_bibliografico']; ?></td>
  	            </tr>
  	            </table></td>
              </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['sistema_organizacion'] != $row_documentos_adu['sistema_organizacion']){ ?>
    	         <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v148; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['sistema_organizacion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['sistema_organizacion']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['autor'] != $row_documentos_adu['autor']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v149; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['autor']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['autor']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['nombre_productor'] != $row_documentos_adu['nombre_productor']) { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v150; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['nombre_productor']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['nombre_productor']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="84" height="30" class="niv_menu">Publicaci&oacute;n</td>
    	              <td width="546"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['fecha_edicion_anio_editorial'] != $row_documentos_adu['fecha_edicion_anio_editorial']) { ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v151; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['fecha_edicion_anio_editorial']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_edicion_anio_editorial']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['fecha_accion_representada'] != $row_documentos_adu['fecha_accion_representada']) { ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v152; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['fecha_accion_representada']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_accion_representada']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['version'] != $row_documentos_adu['version']) { ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v154; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['version']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['version']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['genero'] != $row_documentos_adu['genero']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v155; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['genero']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['genero']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['signos_especiales'] != $row_documentos_adu['signos_especiales']){ ?>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v156; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['signos_especiales']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['signos_especiales']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">Fechas</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['fecha_inicial'] != $row_documentos_adu['fecha_inicial']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v157; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo substr($row_documentos_modificado['fecha_inicial'],8,2); ?>/<?php echo substr($row_documentos_modificado['fecha_inicial'],5,2); ?>/<?php echo substr($row_documentos_modificado['fecha_inicial'],0,4); ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_inicial']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['fecha_final'] != $row_documentos_adu['fecha_final']) { ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v158; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo substr($row_documentos_modificado['fecha_final'],8,2); ?>/<?php echo substr($row_documentos_modificado['fecha_final'],5,2); ?>/<?php echo substr($row_documentos_modificado['fecha_final'],0,4); ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_final']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
               <?php if($row_documentos_modificado['alcance_contenido'] != $row_documentos_adu['alcance_contenido']) { ?>
    	     <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v159; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['alcance_contenido']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['alcance_contenido']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
               <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="630"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['soporte'] != $row_documentos_adu['soporte']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v160; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['soporte']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['soporte']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['duracion_metraje'] != $row_documentos_adu['duracion_metraje']) { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v162; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['duracion_metraje']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['duracion_metraje']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['cromia'] != $row_documentos_adu['cromia']) { ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v163; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['cromia']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['cromia']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['sonido'] != $row_documentos_adu['sonido']){ ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v164; ?></td>
  	          </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['sonido']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['sonido']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">T&eacute;cnica</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['tecnica_fotografica'] != $row_documentos_adu['tecnica_fotografica']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v166; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tecnica_fotografica']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tecnica_fotografica']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['tecnica_visual'] != $row_documentos_adu['tecnica_visual']){ ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v167; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tecnica_visual']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tecnica_visual']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['tecnica_digital'] != $row_documentos_adu['tecnica_digital']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v168; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tecnica_digital']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tecnica_digital']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="630"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['emulsion'] != $row_documentos_adu['emulsion']){ ?>
    	        <tr>
    	         <td class="separadormayor"></td>
  	          </tr>
              
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v169; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['emulsion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['emulsion']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['integridad'] != $row_documentos_adu['integridad']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v170; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['integridad']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['integridad']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['forma_presentacion_unidad'] != $row_documentos_adu['forma_presentacion_unidad']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v171; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['forma_presentacion_unidad']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['forma_presentacion_unidad']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['cantidad_fojas_album'] != $row_documentos_adu['cantidad_fojas_album']){ ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v172; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['cantidad_fojas_album']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['cantidad_fojas_album']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['caracteristica_montaje'] != $row_documentos_adu['caracteristica_montaje']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v173; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['caracteristica_montaje']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['caracteristica_montaje']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['requisito_ejecucion'] != $row_documentos_adu['requisito_ejecucion']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v175; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['requisito_ejecucion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['requisito_ejecucion']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="62" height="30" class="niv_menu">Vol&uacute;men</td>
    	              <td width="568"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['unidades'] != $row_documentos_adu['unidades']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><?php echo $v176; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['unidades']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['unidades']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['cantidad_envases_unidad_documental'] != $row_documentos_adu['cantidad_envases_unidad_documental']){ ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v177; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['cantidad_envases_unidad_documental']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['cantidad_envases_unidad_documental']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['coleccion'] != $row_documentos_adu['coleccion']){ ?>
    	       <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v178; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['coleccion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['coleccion']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	            <tr>
    	              <td width="89" height="30" class="niv_menu">Entidades</td>
    	              <td width="541"><hr></td>
  	              </tr>
  	            </table></td>
  	          </tr>
              <?php if($row_documentos_modificado['evento'] != $row_documentos_adu['evento']){ ?>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v182; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['evento']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['evento']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['manifestacion'] != $row_documentos_adu['manifestacion']){ ?>
    	      <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><?php echo $v183; ?></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['manifestacion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['manifestacion']; ?></td>
  	            </tr>
  	            </table></td>
  	          </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['forma_ingreso'] != $row_documentos_adu['forma_ingreso']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v184; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['forma_ingreso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['forma_ingreso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['procedencia'] != $row_documentos_adu['procedencia']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v185; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['procedencia']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['procedencia']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['fecha_inicio_ingreso'] != $row_documentos_adu['fecha_inicio_ingreso']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v186; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['fecha_inicio_ingreso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['fecha_inicio_ingreso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['precio'] != $row_documentos_adu['precio']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v187; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['precio']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['precio']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['norma_legal_ingreso'] != $row_documentos_adu['norma_legal_ingreso']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v188; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['norma_legal_ingreso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['norma_legal_ingreso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['numero_legal_ingreso'] != $row_documentos_adu['numero_legal_ingreso']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v189; ?></td>
    	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_legal_ingreso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_legal_ingreso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['numero_administrativo'] != $row_documentos_adu['numero_administrativo']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v190; ?></td>
    	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['numero_administrativo']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['numero_administrativo']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['derechos_restricciones'] != $row_documentos_adu['derechos_restricciones']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v191; ?></td>
    	      </tr>
    	    <tr>
    	      <td >
    	        <table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top"  class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top"  class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['derechos_restricciones']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7"  class="tituloscampos ">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7"  class="tituloscampos "><?php echo $row_documentos_adu['derechos_restricciones']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
               <?php if($row_documentos_modificado['titular_derecho'] != $row_documentos_adu['titular_derecho']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v192; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['titular_derecho']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['titular_derecho']; ?></td>
  	            </tr>
	            </table></td>
    	      </tr>
               <?php } ?>
               <?php if($row_documentos_modificado['subsidios'] != $row_documentos_adu['subsidios']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v200; ?></td>
  	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['subsidios']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['subsidios']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['tipo_acceso'] != $row_documentos_adu['tipo_acceso']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v201; ?></td>
    	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['tipo_acceso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['tipo_acceso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
               <?php if($row_documentos_modificado['requisitos_acceso'] != $row_documentos_adu['requisitos_acceso']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v202; ?></td>
    	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['requisitos_acceso']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['requisitos_acceso']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
               <?php } ?>
               <?php if($row_documentos_modificado['acceso_documentacion'] != $row_documentos_adu['acceso_documentacion']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v203; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['acceso_documentacion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['acceso_documentacion']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
               <?php if($row_documentos_modificado['servicio_reproduccion'] != $row_documentos_adu['servicio_reproduccion']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v204; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['servicio_reproduccion']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['servicio_reproduccion']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
               <?php } ?>
               <?php if($row_documentos_modificado['publicaciones_instrumentos_accesos'] != $row_documentos_adu['publicaciones_instrumentos_accesos']){ ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><?php echo $v205; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo $row_documentos_modificado['publicaciones_instrumentos_accesos']; ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo $row_documentos_adu['publicaciones_instrumentos_accesos']; ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
              <?php if($row_documentos_modificado['fecha_ultimo_relevamiento_visu'] != $row_documentos_adu['fecha_ultimo_relevamiento_visu']) { ?>
    	    <tr>
    	      <td class="separadormayor"></td>
    	      </tr>
    	    <tr>
    	      <td  class="tituloscampos ins_celdacolor"><?php echo $v206; ?></td>
    	      </tr>
    	    <tr>
    	      <td class="tituloscampos ins_celdacolor" ><table width="630" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td width="50" valign="top" class="tituloscampos ins_celdacolor">Act:</td>
    	            <td width="580" valign="top" class="tituloscampos ins_celdacolor"><?php echo substr($row_documentos_modificado['fecha_ultimo_relevamiento_visu'],8,2); ?>/<?php echo substr($row_documentos_modificado['fecha_ultimo_relevamiento_visu'],5,2); ?>/<?php echo substr($row_documentos_modificado['fecha_ultimo_relevamiento_visu'],0,4); ?></td>
  	            </tr>
    	          <tr>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos">Ant:</td>
    	            <td valign="top" bgcolor="#e8e7e7" class="tituloscampos"><?php echo substr($row_documentos_adu['fecha_ultimo_relevamiento_visu'],8,2); ?>/<?php echo substr($row_documentos_adu['fecha_ultimo_relevamiento_visu'],5,2); ?>/<?php echo substr($row_documentos_adu['fecha_ultimo_relevamiento_visu'],0,4); ?></td>
  	            </tr>
  	            </table></td>
    	      </tr>
              <?php } ?>
    	    <tr>
    	      <td >&nbsp;</td>
    	      </tr>
    	    </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473" valign="top">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="button2" type="submit" class="botongrabar" id="button2" value="Modificar" onClick="modificar('<?php echo $row_documentos_modificado['codigo_referencia']; ?>');" >
    	            <input name="button3" type="submit" class="botongrabar" id="button3" value="NO Vigente" onClick="novigencia('<?php echo $row_documentos_modificado['codigo_referencia']; ?>');" ></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Vigente" onClick="vigencia('<?php echo $row_documentos_modificado['codigo_referencia']; ?>');" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	  </tr>
    	<tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
    </table></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
  </table>
</div>
  <?PHP mysql_free_result($documentos_adu); ?>
  <?php } while ($row_documentos_modificado = mysql_fetch_assoc($documentos_modificado)); ?>
  <table width="1250" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="1250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table width="270" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="25" align="left"><?php if ($pageNum_documentos_modificado > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, 0, $queryString_documentos_modificado); ?>"><img src="First.gif" width="18" height="13" border="0"></a>
              <?php } // Show if not first page ?></td>
          <td width="25" align="left"><?php if ($pageNum_documentos_modificado > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, max(0, $pageNum_documentos_modificado - 1), $queryString_documentos_modificado); ?>"><img src="Previous.gif" width="14" height="13" border="0"></a>
              <?php } // Show if not first page ?></td>
              <td width="170" align="center" class="estiloCampos">Documentos <?php echo ($startRow_documentos_modificado + 1) ?> a <?php echo min($startRow_documentos_modificado + $maxRows_documentos_modificado, $totalRows_documentos_modificado) ?> de <?php echo $totalRows_documentos_modificado ?></td>
          <td width="25" align="right"><?php if ($pageNum_documentos_modificado < $totalPages_documentos_modificado) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, min($totalPages_documentos_modificado, $pageNum_documentos_modificado + 1), $queryString_documentos_modificado); ?>"><img src="Next.gif" width="14" height="13"  border="0"></a>
              <?php } // Show if not last page ?></td>
          <td width="25" align="right"><?php if ($pageNum_documentos_modificado < $totalPages_documentos_modificado) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_documentos_modificado=%d%s", $currentPage, $totalPages_documentos_modificado, $queryString_documentos_modificado); ?>"><img src="Last.gif" width="18" height="13" border="0"></a>
              <?php } // Show if not last page ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<table width="1250" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php require_once('activo.php'); ?>
</body>
</html>
<?php
mysql_free_result($documentos_modificado);


?>
<!-- 
1. Nombre de la Institución
2. Fecha de ingreso
3. Número de inventario
4. Números de inventarios anteriores
5. Número administrativo
6. Título
7. Nombre del productor/autor 
8. Fechas
9. Alcance de contenidos
10. Idioma
11. Soporte
12. Tipo General de documento
13. Tipo específico de documento
14. Tradición documental
15. Unidades
16. Valuación
17. Integridad/signos especiales
18. Estado de conservación
19. Forma de ingreso y procedencia
20. Norma legal de ingreso
21. Número  legal de ingreso
22. Norma legal de baja 
23. Motivo de la baja
24. Fecha de la baja
25. Restricciones para la consulta.
26. Responsable del ingreso 
-->