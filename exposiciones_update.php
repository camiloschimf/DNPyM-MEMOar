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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//--actualizamos el estado a completado--------------------------------
function estados($est_t, $cod_ref,$database_conn,$conn) {
	$insertSQL = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, fecha, usuario) VALUES (%s, %s, %s, %s)",
					   GetSQLValueString($cod_ref, "text"),
					   GetSQLValueString($est_t, "text"),
					   GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($insertSQL, $conn) or die(mysql_error());
}

//--actualizamos la situacion--------------------------------
function situacion($est_t, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET situacion='".$est_t."' WHERE codigo_referencia=%s ",
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos la fecha visu--------------------------------
function visu($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultimo_relevamiento_visu=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos ultima modificacion--------------------------------
function ultimamodificacion($cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultima_modificacion=%s, usuario=%s WHERE codigo_referencia=%s ",
						GetSQLValueString(date("Y/m/d H:i,s"), "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

//--actualizamos la fecha de la ultima gestion--------------------------------
function ultimagestion($fec, $cod_ref,$database_conn,$conn) {
	$updateSQL = sprintf("UPDATE documentos SET fecha_ultima_gestion=%s WHERE codigo_referencia=%s ",
					   GetSQLValueString($fec, "date"),
					   GetSQLValueString($cod_ref, "text"));
					   				   

	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("REPLACE INTO rel_documentos_exposiciones (codigo_referencia, codigo_exposicion, numero_asignado, fecha_entrega, fecha_devolucion, full) VALUES (%s, %s, %s, %s, %s, 0)",
                       GetSQLValueString($_POST['codigo_referencia'], "text"),
					   GetSQLValueString($_POST['codigo_exposicion'], "int"),
					   GetSQLValueString($_POST['numero_asignado'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_entregad'],$_POST['fecha_entregam'],$_POST['fecha_entregaa']), "date"),
					   GetSQLValueString(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']), "date"));
					   
	
	 if (isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['codigo_exposicion'] != "" && $_POST['numero_asignado'] != "" && $_POST['fecha_entregaa'] != "" && $_POST['fecha_devoluciona'] != "") {
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--modficamos la situacon a robado del documento--
	situacion("Local", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']),$_POST['codigo_referencia'],$database_conn,$conn);	
		
	$insertSQL = str_replace("0)", "1)", $insertSQL);		
	mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "exposiciones_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_entregaa']."-".$_POST['fecha_entregam']."-".$_POST['fecha_entregad']." 00:00:00&v=s";
	header(sprintf("Location: %s", $insertGoTo));	
	
	}  else if(isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['codigo_exposicion'] != "" && $_POST['fecha_entregaa'] != "") {
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
 
 	//--modficamos la situacon a robado del documento--
	situacion("Exposición", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_entregad'],$_POST['fecha_entregam'],$_POST['fecha_entregaa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_entregad'],$_POST['fecha_entregam'],$_POST['fecha_entregaa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	 mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "exposiciones_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_entregaa']."-".$_POST['fecha_entregam']."-".$_POST['fecha_entregad']." 00:00:00";
	header(sprintf("Location: %s", $insertGoTo));
	
	} else {
		
		$insertGoTo = "exposiciones_update.php?cod=".$_GET['cod'];
		header(sprintf("Location: %s", $insertGoTo));
	
	
	}
  
}


$colname_diplomaticos = "-1";
if (isset($_GET['cod'])) {
  $colname_diplomaticos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_diplomaticos = sprintf("SELECT * FROM documentos  WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_diplomaticos, "text"));
$diplomaticos = mysql_query($query_diplomaticos, $conn) or die(mysql_error());
$row_diplomaticos = mysql_fetch_assoc($diplomaticos);
$totalRows_diplomaticos = mysql_num_rows($diplomaticos);


$s1=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'GEXP' ,$database_conn,$conn);

mysql_free_result($diplomaticos);

//echo $s1.$s2.$s3.$s4;

//$s1 = "";

if($s1 == "" ) {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}


$colname_rel_documentos_exposiciones = "-1";
if (isset($_GET['fecha'])) {
  $colname_rel_documentos_exposiciones = $_GET['fecha'];
}
mysql_select_db($database_conn, $conn);
$query_rel_documentos_exposiciones = sprintf("SELECT * FROM rel_documentos_exposiciones WHERE codigo_referencia='".$_GET['cod']."' AND fecha_entrega = %s", GetSQLValueString($colname_rel_documentos_exposiciones, "date"));
$rel_documentos_exposiciones = mysql_query($query_rel_documentos_exposiciones, $conn) or die(mysql_error());
$row_rel_documentos_exposiciones = mysql_fetch_assoc($rel_documentos_exposiciones);
$totalRows_rel_documentos_exposiciones = mysql_num_rows($rel_documentos_exposiciones);


mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Exposición' ORDER BY idtips ASC";
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);

$w="";
if(isset($_GET['expo']) && $_GET['expo'] != "") {
$w=" WHERE codigo_exposicion=".$_GET['expo'];	
}

mysql_select_db($database_conn, $conn);
$query_exposiciones = "SELECT * FROM exposiciones ".$w." ORDER BY fecha_inicio DESC";
$exposiciones = mysql_query($query_exposiciones, $conn) or die(mysql_error());
$row_exposiciones = mysql_fetch_assoc($exposiciones);
$totalRows_exposiciones = mysql_num_rows($exposiciones);

do {

$nomTip = "v".$row_tips['idtips'];
$$nomTip = "";

$$nomTip .= "<div id=\"".$nomTip."\" style=\"position:absolute; z-index:100; visibility:hidden; width:505px;\"  onmouseover=\"muestra_retarda('".$nomTip."')\" onMouseOut=\"oculta_retarda('".$nomTip."')\"><table width=\"505\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
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
<title>Documento sin t&iacute;tulo</title>
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
<script language=JavaScript src='Scripts/innovaeditor.js'></script>
<script type='text/javascript' src='gyl_menu.js'></script>
</head>
<?php if(!isset($_GET['v'])) { 
echo "<script type='text/javascript'>";
echo "	window.parent.frderecha.document.location='muestras_archivos.php?cod=".$_GET['cod']."';";
echo "</script>";
 } ?>
<script type='text/javascript'> 
var empezar = false
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
//var capas = new Array("e1")
var retardo 
var ocultar

function ct() {
<?Php	
do {
$nomTip = "v".$row_tips['idtips'];
$vt .= "if(document.getElementById('".$nomTip."')){"."\n";
$vt .= "document.getElementById('".$nomTip."').style.visibility='hidden'"."\n";
$vt .= " } "."\n";
echo $vt;
} while ($row_tips = mysql_fetch_assoc($tips));
?>	
}
 
function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}

function muestra_coloca(capa){
	ct();
	clearTimeout(retardo)
	xShow(capa)
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
<script language="javascript">

	//--abre los insert de las tablas auxiliares
	function abriraux(m) {
		document.getElementById('dv_aux_c_'+m).style.display = "none";
		document.getElementById('dv_aux_a_'+m).style.display = "block";
	}
	
	//--cierra las areas de las tablas auxiliares
	function cerraraux(n) {
		document.getElementById('dv_aux_a_'+n).style.display = "none";
		document.getElementById('dv_aux_c_'+n).style.display = "block";
	}

</script>
<script language="javascript">
		//--envia variables por post
	function relocate(page,params)
 {
	  var body = document.body;
	  form=document.createElement('form'); 
	  form.method = 'POST'; 
	  form.action = page;
	  form.name = 'jsform';
	  for (index in params)
	  {
			var input = document.createElement('input');
			input.type='hidden';
			input.name=index;
			input.id=index;
			input.value=params[index];
			form.appendChild(input);
	  }	  		  			  
	  body.appendChild(form);
	  form.submit();
 }
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">GESTI&Oacute;N DE EXPOSICI&Oacute;N</td>
    	          <td align="right"></td>
  	          </tr>
  	        </table></td>
        </tr>
        <tr>
    	  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v125; ?><!-- Fecha de Entrega -->
                   <input name="codigo_referencia" type="hidden" id="codigo_referencia" value="<?php  echo $_GET['cod']; ?>"></td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_entregad" type="text" id="fecha_entregad" size="8" maxlength="2" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_entrega'],8,2); ?>" <?php if($row_rel_documentos_exposiciones['fecha_entrega'] != "") { echo "readonly"; } ?>>
Mes:
<input name="fecha_entregam" type="text" id="fecha_entregam" size="8" maxlength="2" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_entrega'],5,2); ?>" <?php if($row_rel_documentos_exposiciones['fecha_entrega'] != "") { echo "readonly"; } ?>>
A&ntilde;o:
<input name="fecha_entregaa" type="text" id="fecha_entregaa" size="12" maxlength="4" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_entrega'],0,4); ?>" <?php if($row_rel_documentos_exposiciones['fecha_entrega'] != "") { echo "readonly"; } ?> >
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td height="9" class="separadormayor"></td>
            </tr>
            <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v126; ?><!-- Exposici&oacute;n:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><select name="codigo_exposicion" id="codigo_exposicion" class="camposanchos" <?php if($row_rel_documentos_exposiciones['codigo_exposicion'] != "") { echo "readonly"; } ?>>
                 <?php if(!isset($_GET['expo'])) { ?>
                 <option value="">Selecciones Opción</option>
                 <?php } ?>
                   <?php
do {  
?>
                   <option value="<?php echo $row_exposiciones['codigo_exposicion']?>"<?php if (!(strcmp($row_exposiciones['codigo_exposicion'], $row_rel_documentos_exposiciones['codigo_exposicion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_exposiciones['codigo_exposicion']?> - <?php echo desfechar($row_exposiciones['fecha_inicio']); ?> - <?php echo $row_exposiciones['nombre']?></option>
                   <?php
} while ($row_exposiciones = mysql_fetch_assoc($exposiciones));
  $rows = mysql_num_rows($exposiciones);
  if($rows > 0) {
      mysql_data_seek($exposiciones, 0);
	  $row_exposiciones = mysql_fetch_assoc($exposiciones);
  }
?>
                 </select></td>
                </tr>
             <tr>
    	          <td height="9" class="separadormayor"></td>
          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v127; ?><!-- N&uacute;mero Asignado--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="numero_asignado" type="text" class="camposanchos" id="numero_asignado" value="<?php echo $row_rel_documentos_exposiciones['numero_asignado']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v128; ?><!-- Fecha de Devoluci&oacute;n:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_devoluciond" type="text" id="fecha_devoluciond" size="8" maxlength="2" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_devolucion'],8,2); ?>">
Mes:
<input name="fecha_devolucionm" type="text" id="fecha_devolucionm" size="8" maxlength="2" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_devolucion'],5,2); ?>">
A&ntilde;o:
<input name="fecha_devoluciona" type="text" id="fecha_devoluciona" size="12" maxlength="4" value="<?php echo substr($row_rel_documentos_exposiciones['fecha_devolucion'],0,4); ?>">
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td>&nbsp;</td>
  	          </tr>
              </table></td>
        </tr>
        <tr>
    	  <td class="celdabotones1"><?php if($s1!="C") { ?><?php if(!isset($_GET['v']) && $row_rel_documentos_exposiciones['full'] == 0) { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Grabar" onClick="" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
      <tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
  </table>
<input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($tips);

mysql_free_result($exposiciones);

mysql_free_result($rel_documentos_exposiciones);
?>
