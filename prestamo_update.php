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
if (!isset($_SESSION)) {
  session_start();
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

	$campo="institucion_destinataria";
	$code="";
	if($_POST['institucion_destinataria'] != ""){
		$tipo=substr($_POST['institucion_destinataria'],0,3);
		$code=substr($_POST['institucion_destinataria'],4);	 
		if($tipo == "EXT") {
			$campo="institucion_destinataria_ext, institucion_destinataria";
			$valor="'".$code."', NULL";
		} else if($tipo == "INT") {
			$campo="institucion_destinataria, institucion_destinataria_ext";
			$valor="'".$code."', NULL";
		}
  }
	
	
  $insertSQL = sprintf("REPLACE INTO documentos_prestamos (codigo_referencia, fecha_inicio, forma, ".$campo.", norma_legal_prestamo, nro_legal_prestamo, transporte, nombre_deposito, estanteria_deposito, contenedor_deposito, grilla_deposito, planera_deposito, nombre_exhibicion, vitrinas_paredes_exhibicion, fecha_termino, fecha_devolucion, full) VALUES (%s, %s, %s, ".$valor." , %s,  %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0)",
                       GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_iniciod'],$_POST['fecha_iniciom'],$_POST['fecha_inicioa']), "date"),
					   GetSQLValueString($_POST['forma'], "text"),
					   GetSQLValueString($_POST['norma_legal_prestamo'], "text"),
					   GetSQLValueString($_POST['nro_legal_prestamo'], "text"),
					   GetSQLValueString($_POST['transporte'], "text"),
					   GetSQLValueString($_POST['nombre_deposito'], "text"),
					   GetSQLValueString($_POST['estanteria_deposito'], "text"),
					   GetSQLValueString($_POST['contenedor_deposito'], "text"),
					   GetSQLValueString($_POST['grilla_deposito'], "text"),
					   GetSQLValueString($_POST['planera_deposito'], "text"),
					   GetSQLValueString($_POST['nombre_exhibicion'], "text"),
					   GetSQLValueString($_POST['vitrinas_paredes_exhibicion'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_terminod'],$_POST['fecha_terminom'],$_POST['fecha_terminoa']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']), "date"));
					   

					   
	//--verificamos que esten todos los campos para cerrar la gestion
	 if (isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['fecha_inicioa'] != "" && $_POST['forma'] != "" && $_POST['institucion_destinataria'] != "" && $_POST['norma_legal_prestamo'] != "" && $_POST['nro_legal_prestamo'] != "" && $_POST['transporte'] != "" && $_POST['nombre_deposito'] != "" && $_POST['estanteria_deposito'] != "" && $_POST['contenedor_deposito'] != "" && $_POST['grilla_deposito'] != "" && $_POST['planera_deposito'] != "" && $_POST['nombre_exhibicion'] != "" && $_POST['vitrinas_paredes_exhibicion'] != "" && $_POST['fecha_terminoa'] != "" && $_POST['fecha_devoluciona'] != "") {
		
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_devoluciond'],$_POST['fecha_devolucionm'],$_POST['fecha_devoluciona']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--modficamos la situacon a robado del documento--
	situacion("Local", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_terminod'],$_POST['fecha_terminom'],$_POST['fecha_terminoa']),$_POST['codigo_referencia'],$database_conn,$conn);		
		
	$insertSQL = str_replace("0)", "1)", $insertSQL);		
	mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "prestamo_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_inicioa']."-".$_POST['fecha_iniciom']."-".$_POST['fecha_iniciod']." 00:00:00&v=s";	
	header(sprintf("Location: %s", $insertGoTo));	
	
	}  else if(isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['fecha_inicioa'] != "") {
		
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_iniciod'],$_POST['fecha_iniciom'],$_POST['fecha_inicioa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
 
 	//--modficamos la situacon a robado del documento--
	situacion("Prestamo", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_iniciod'],$_POST['fecha_iniciom'],$_POST['fecha_inicioa']),$_POST['codigo_referencia'],$database_conn,$conn);	
	
	 mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "prestamo_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_inicioa']."-".$_POST['fecha_iniciom']."-".$_POST['fecha_iniciod']." 00:00:00";
	header(sprintf("Location: %s", $insertGoTo));
	
	} else {
		
		$insertGoTo = "prestamo_update.php?cod=".$_GET['cod'];
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


$s1=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'GPRE' ,$database_conn,$conn);

mysql_free_result($diplomaticos);

//echo $s1.$s2.$s3.$s4;

//$s1 = "C";

if($s1 == "" ) {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}


$colname_documentos_prestamos = "-1";
if (isset($_GET['fecha'])) {
  $colname_documentos_prestamos = $_GET['fecha'];
}
mysql_select_db($database_conn, $conn);
$query_documentos_prestamos = sprintf("SELECT *, case when institucion_destinataria is null then concat('EXT-',institucion_destinataria_ext) else concat('INT-',institucion_destinataria) end as institucion_dest FROM documentos_prestamos WHERE codigo_referencia='".$_GET['cod']."' AND fecha_inicio = %s", GetSQLValueString($colname_documentos_prestamos, "date"));
$documentos_prestamos = mysql_query($query_documentos_prestamos, $conn) or die(mysql_error());
$row_documentos_prestamos = mysql_fetch_assoc($documentos_prestamos);
$totalRows_documentos_prestamos = mysql_num_rows($documentos_prestamos);

mysql_select_db($database_conn, $conn);
$query_instituciones = "SELECT 'INT' AS ubicacion, CONCAT('INT-',codigo_identificacion) AS codigo_identificacion, formas_conocidas_nombre FROM instituciones 
UNION
SELECT 'EXT' as ubicacion, CONCAT('EXT-',codigo_referencia_intitucion_externa) AS codigo_identificacion, nombre_autorizado AS formas_conocidas_nombre FROM instituciones_externas
ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

mysql_select_db($database_conn, $conn);
$query_norma_legal = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$norma_legal = mysql_query($query_norma_legal, $conn) or die(mysql_error());
$row_norma_legal = mysql_fetch_assoc($norma_legal);
$totalRows_norma_legal = mysql_num_rows($norma_legal);

mysql_select_db($database_conn, $conn);
$query_transportes = "SELECT * FROM transportes ORDER BY nombre ASC";
$transportes = mysql_query($query_transportes, $conn) or die(mysql_error());
$row_transportes = mysql_fetch_assoc($transportes);
$totalRows_transportes = mysql_num_rows($transportes);


mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Prestamo' ORDER BY idtips ASC";
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);



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
$$nomTip .= "            <td class=\"tips\">".$row_tips['idtips']." - ".$row_tips['tip'];
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
<body><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">GESTI&Oacute;N DE PR&Eacute;STAMO</td>
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
                 <td class="tituloscampos ins_celdacolor"><?php echo $v217; ?><!-- codigo de referencia --></td>
            </tr>
                <tr>
               	  <td><input name="codigo_referencia" type="text" id="codigo_referencia" value="<?php  echo $_GET['cod']; ?>" class="camposanchos" readonly></td>
                </tr>
              <tr>
   	            <td class="separadormayor"></td>
          	</tr>
    	        <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v133; ?><!-- Fecha Inicio --></td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_iniciod" type="text" id="fecha_iniciod" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_inicio'],8,2); ?>" <?php if($row_documentos_prestamos['fecha_inicio'] != "") { echo "readonly"; } ?>>
Mes:
<input name="fecha_iniciom" type="text" id="fecha_iniciom" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_inicio'],5,2); ?>" <?php if($row_documentos_prestamos['fecha_inicio'] != "") { echo "readonly"; } ?>>
A&ntilde;o:
<input name="fecha_inicioa" type="text" id="fecha_inicioa" size="12" maxlength="4" value="<?php echo substr($row_documentos_prestamos['fecha_inicio'],0,4); ?>" <?php if($row_documentos_prestamos['fecha_inicio'] != "") { echo "readonly"; } ?> >
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td height="9" class="separadormayor"></td>
            </tr>
            <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v134; ?><!-- Forma --></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="forma" type="text" class="camposanchos" id="forma" value="<?php echo $row_documentos_prestamos['forma']; ?>" maxlength="50"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v135; ?><!-- Instituci&oacute;n Destinataria --></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><select class="camposanchos" name="institucion_destinataria" id="institucion_destinataria">
                 <option value="">Seleccione Opción</option>
                   <?php
do {  
?>
                   <option value="<?php echo $row_instituciones['codigo_identificacion']?>"<?php if (!(strcmp($row_instituciones['codigo_identificacion'], $row_documentos_prestamos['institucion_dest']))) {echo "selected=\"selected\"";} ?>><?php echo $row_instituciones['ubicacion']." - ".$row_instituciones['formas_conocidas_nombre']?></option>
                   <?php
} while ($row_instituciones = mysql_fetch_assoc($instituciones));
  $rows = mysql_num_rows($instituciones);
  if($rows > 0) {
      mysql_data_seek($instituciones, 0);
	  $row_instituciones = mysql_fetch_assoc($instituciones);
  }
?>
                 </select></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v136; ?><!-- Norma Legal de Pr&eacute;stamo--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><select class="camposanchos" name="norma_legal_prestamo" id="norma_legal_prestamo">
                 <option value="">Seleccione Opción</option>
                   <?php
do {  
?>
                   <option value="<?php echo $row_norma_legal['norma_legal']?>"<?php if (!(strcmp($row_norma_legal['norma_legal'], $row_documentos_prestamos['norma_legal_prestamo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_norma_legal['norma_legal']?></option>
                   <?php
} while ($row_norma_legal = mysql_fetch_assoc($norma_legal));
  $rows = mysql_num_rows($norma_legal);
  if($rows > 0) {
      mysql_data_seek($norma_legal, 0);
	  $row_norma_legal = mysql_fetch_assoc($norma_legal);
  }
?>
                 </select></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v137; ?><!-- N&uacute;mero Legal de Pr&eacute;stamo--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nro_legal_prestamo" type="text" class="camposanchos" id="nro_legal_prestamo" value="<?php echo $row_documentos_prestamos['nro_legal_prestamo']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v138; ?><!-- Transporte--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><select class="camposanchos" name="transporte" id="transporte">
                 <option value="">Seleccione Opción</option>
                   <?php
do {  
?>
                   <option value="<?php echo $row_transportes['transporte']?>"<?php if (!(strcmp($row_transportes['transporte'], $row_documentos_prestamos['transporte']))) {echo "selected=\"selected\"";} ?>><?php echo $row_transportes['nombre']?></option>
                   <?php
} while ($row_transportes = mysql_fetch_assoc($transportes));
  $rows = mysql_num_rows($transportes);
  if($rows > 0) {
      mysql_data_seek($transportes, 0);
	  $row_transportes = mysql_fetch_assoc($transportes);
  }
?>
                 </select></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v139; ?><!-- Nombre del Dep&oacute;sito--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nombre_deposito" type="text" class="camposanchos" id="nombre_deposito" value="<?php echo $row_documentos_prestamos['nombre_deposito']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v140; ?><!-- Estanter&iacute;a del Dep&oacute;sito:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="estanteria_deposito" type="text" class="camposanchos" id="estanteria_deposito" value="<?php echo $row_documentos_prestamos['estanteria_deposito']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v141; ?><!-- Contenedor del Dep&oacute;sito:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="contenedor_deposito" type="text" class="camposanchos" id="contenedor_deposito" value="<?php echo $row_documentos_prestamos['contenedor_deposito']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v142; ?><!-- Grilla del Dep&oacute;sito:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="grilla_deposito" type="text" class="camposanchos" id="grilla_deposito" value="<?php echo $row_documentos_prestamos['grilla_deposito']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v143; ?><!-- Planera del Dep&oacute;sito:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="planera_deposito" type="text" class="camposanchos" id="planera_deposito" value="<?php echo $row_documentos_prestamos['planera_deposito']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v144; ?><!-- Nombre de Exhibici&oacute;n:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nombre_exhibicion" type="text" class="camposanchos" id="nombre_exhibicion" value="<?php echo $row_documentos_prestamos['nombre_exhibicion']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v145; ?><!-- Vitrinas paredes de Exibici&oacute;n:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="vitrinas_paredes_exhibicion" type="text" class="camposanchos" id="vitrinas_paredes_exhibicion" value="<?php echo $row_documentos_prestamos['vitrinas_paredes_exhibicion']; ?>"  />                   </td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v146; ?><!-- Fecha de T&eacute;rmino:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_terminod" type="text" id="fecha_terminod" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_termino'],8,2); ?>">
Mes:
<input name="fecha_terminom" type="text" id="fecha_terminom" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_termino'],5,2); ?>">
A&ntilde;o:
<input name="fecha_terminoa" type="text" id="fecha_terminoa" size="12" maxlength="4" value="<?php echo substr($row_documentos_prestamos['fecha_termino'],0,4); ?>">
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v147; ?><!-- Fecha de Devoluci&oacute;n--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_devoluciond" type="text" id="fecha_devolucions" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_devolucion'],8,2); ?>">
Mes:
<input name="fecha_devolucionm" type="text" id="fecha_devolucionm" size="8" maxlength="2" value="<?php echo substr($row_documentos_prestamos['fecha_devolucion'],5,2); ?>">
A&ntilde;o:
<input name="fecha_devoluciona" type="text" id="fecha_devoluciona" size="12" maxlength="4" value="<?php echo substr($row_documentos_prestamos['fecha_devolucion'],0,4); ?>">
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
    	  <td class="celdabotones1"><?php if($s1!="C") { ?><?php if(!isset($_GET['v']) && $row_documentos_prestamos['full'] == 0) { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Grabar" onClick="activar();" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table><?php } ?><?php } ?></td>
  	  </tr>
      <tr>
    	  <td class="celdapieazul"></td>
  	  </tr>
  </table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<?php require_once('activo.php'); ?>
</body>
</html>
<?php
mysql_free_result($tips);

mysql_free_result($instituciones);

mysql_free_result($norma_legal);

mysql_free_result($transportes);

mysql_free_result($documentos_prestamos);
?>
