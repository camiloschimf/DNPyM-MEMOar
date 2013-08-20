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

//cerramos el nivel anterior para evitar inconsistencias---------------------
function cierresituacionant($sit,$fec,$cod_ref,$database_conn,$conn) {
	
	if($sit == "Exposición") {
		$updateSQL = "UPDATE rel_documentos_exposiciones SET fecha_devolucion='".$fec."', full=1 WHERE codigo_referencia='".$cod_ref."' AND full=0";
	} else if($sit == "Conservación") {
		$updateSQL = "UPDATE gestion_conservacion SET fecha_fin_tratamiento='".$fec."', full=1 WHERE codigo_referencia='".$cod_ref."' AND full=0";
	} else if($sit == "Prestamo") {
		$updateSQL = "UPDATE documentos_prestamos SET fecha_devolucion='".$fec."', full=1 WHERE codigo_referencia='".$cod_ref."' AND full=0";
	}
	
	if($sit == "Exposición" || $sit == "Conservación" || $sit == "Prestamo") {
	mysql_select_db($database_conn, $conn);
	$Result= mysql_query($updateSQL, $conn) or die(mysql_error());
	}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("REPLACE INTO documentos_robados (codigo_referencia, fecha_sustraido, fecha_resolucion_apertura_sumario, fecha_resolucion_cierre_sumario, denunciante, nro_causa_judicial, nro_juzgado, tipo_tramite_administrativo, nro_sumario_administrativo, fallo, fecha_recuperacion, object_id_interpol, fecha_registro_interpol, full) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0)",
                       GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_sustraidod'],$_POST['fecha_sustraidom'],$_POST['fecha_sustraidoa']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_resolucion_apertura_sumariod'],$_POST['fecha_resolucion_apertura_sumariom'],$_POST['fecha_resolucion_apertura_sumarioa']), "date"),
                       GetSQLValueString(fechar($_POST['fecha_resolucion_cierre_sumariod'],$_POST['fecha_resolucion_cierre_sumariom'],$_POST['fecha_resolucion_cierre_sumarioa']), "date"),
                       GetSQLValueString($_POST['denunciante'], "text"),
                       GetSQLValueString($_POST['nro_causa_judicial'], "text"),
                       GetSQLValueString($_POST['nro_juzgado'], "text"),
                       GetSQLValueString($_POST['tipo_tramite_administrativo'], "text"),
                       GetSQLValueString($_POST['nro_sumario_administrativo'], "text"),
                       GetSQLValueString($_POST['fallo'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_recuperaciond'],$_POST['fecha_recuperacionm'],$_POST['fecha_recuperaciona']), "date"),
                       GetSQLValueString($_POST['object_id_interpol'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_registro_interpold'],$_POST['fecha_registro_interpolm'],$_POST['fecha_registro_interpola']), "date"));
					   

	
	
	
	 if (isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['fecha_sustraidoa'] != "" && $_POST['fecha_resolucion_apertura_sumarioa'] != "" && $_POST['fecha_resolucion_cierre_sumarioa'] != "" && $_POST['denunciante'] != "" && $_POST['nro_causa_judicial'] != "" && $_POST['nro_juzgado'] != "" && $_POST['tipo_tramite_administrativo'] != "" && $_POST['nro_sumario_administrativo'] != "" && $_POST['fallo'] != "" && $_POST['fecha_recuperaciona'] != "" && $_POST['object_id_interpol'] != "" && $_POST['fecha_registro_interpola'] != "") {
		
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha visu segun la fecha de sustraccion del comtenido
	visu(fechar($_POST['fecha_recuperaciond'],$_POST['fecha_recuperacionm'],$_POST['fecha_recuperaciona']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--modficamos la situacon a robado del documento--
	situacion("Local", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_recuperaciond'],$_POST['fecha_recuperacionm'],$_POST['fecha_recuperaciona']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--cerramos la situacion anterior si hubiera abierto alguna
	if(isset($_POST['sit']) && $_POST['sit'] != "") {
		cierresituacionant($_POST['sit'],fechar($_POST['fecha_sustraidod'],$_POST['fecha_sustraidom'],$_POST['fecha_sustraidoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	}	
		
	$insertSQL = str_replace("0)", "1)", $insertSQL);		
	mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "robo_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_sustraidoa']."-".$_POST['fecha_sustraidom']."-".$_POST['fecha_sustraidod']." 00:00:00&v=s";	
	header(sprintf("Location: %s", $insertGoTo));	
	
	}  else if(isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "" && $_POST['fecha_sustraidoa'] != "") {
	
	//--modificamos la fecha de modificacion del documento					   
	ultimamodificacion($_POST['codigo_referencia'],$database_conn,$conn);
 
 	//--modficamos la situacon a robado del documento--
	situacion("Robado", $_POST['codigo_referencia'],$database_conn,$conn);
	
	//-modificamos la fecha de la ultima gestion 
	ultimagestion(fechar($_POST['fecha_sustraidod'],$_POST['fecha_sustraidom'],$_POST['fecha_sustraidoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	
	//--cerramos la situacion anterior si hubiera abierto alguna
	if(isset($_POST['sit']) && $_POST['sit'] != "") {
		cierresituacionant($_POST['sit'],fechar($_POST['fecha_sustraidod'],$_POST['fecha_sustraidom'],$_POST['fecha_sustraidoa']),$_POST['codigo_referencia'],$database_conn,$conn);
	}
	
	 mysql_select_db($database_conn, $conn);
	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	$insertGoTo = "robo_update.php?cod=".$_GET['cod']."&fecha=".$_POST['fecha_sustraidoa']."-".$_POST['fecha_sustraidom']."-".$_POST['fecha_sustraidod']." 00:00:00";
	header(sprintf("Location: %s", $insertGoTo));
	
	} else {
		
		$insertGoTo = "robo_update.php?cod=".$_GET['cod'];
		header(sprintf("Location: %s", $insertGoTo));
		
	}
  
}


$colname_documentos_robados = "-1";
if (isset($_GET['fecha'])) {
  $colname_documentos_robados = $_GET['fecha'];
}
mysql_select_db($database_conn, $conn);
$query_documentos_robados = sprintf("SELECT * FROM documentos_robados WHERE codigo_referencia='".$_GET['cod']."' AND fecha_sustraido = %s", GetSQLValueString($colname_documentos_robados, "date"));
$documentos_robados = mysql_query($query_documentos_robados, $conn) or die(mysql_error());
$row_documentos_robados = mysql_fetch_assoc($documentos_robados);
$totalRows_documentos_robados = mysql_num_rows($documentos_robados);


$colname_diplomaticos = "-1";
if (isset($_GET['cod'])) {
  $colname_diplomaticos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_diplomaticos = sprintf("SELECT * FROM documentos  WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_diplomaticos, "text"));
$diplomaticos = mysql_query($query_diplomaticos, $conn) or die(mysql_error());
$row_diplomaticos = mysql_fetch_assoc($diplomaticos);
$totalRows_diplomaticos = mysql_num_rows($diplomaticos);


$s1=permiso($_SESSION['MM_usuario'], $row_diplomaticos['codigo_institucion'], 'GROB' ,$database_conn,$conn);

mysql_free_result($diplomaticos);

//echo $s1.$s2.$s3.$s4;

//$s1 = "";

if($s1 == "" ) {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}


mysql_select_db($database_conn, $conn);
$query_tips = "SELECT * FROM tips WHERE area = 'Robo' ORDER BY idtips ASC";
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
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">GESTI&Oacute;N DE ROBO</td>
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
                 <td class="tituloscampos ins_celdacolor"><?php echo $v216; ?><!-- Codigo de referencia -->
                   </td>
                </tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="codigo_referencia" type="text" id="codigo_referencia" class="camposanchos" value="<?php  echo $_GET['cod']; ?>" readonly></td>
                 </tr>
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v113; ?><!-- Fecha Sustra&iacute;do: --></td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_sustraidod" type="text" id="fecha_sustraidod" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_sustraido'],8,2); ?>" <?php if($row_documentos_robados['fecha_sustraido'] != "") { echo "readonly"; } ?>>
Mes:
<input name="fecha_sustraidom" type="text" id="fecha_sustraidom" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_sustraido'],5,2); ?>" <?php if($row_documentos_robados['fecha_sustraido'] != "") { echo "readonly"; } ?>>
A&ntilde;o:
<input name="fecha_sustraidoa" type="text" id="fecha_sustraidoa" size="12" maxlength="4" value="<?php echo substr($row_documentos_robados['fecha_sustraido'],0,4); ?>" <?php if($row_documentos_robados['fecha_sustraido'] != "") { echo "readonly"; } ?> >
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td height="9" class="separadormayor"></td>
            </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v114; ?><!-- Fecha Resoluci&oacute;n de Apertura de Sumario: --></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_resolucion_apertura_sumariod" type="text" id="fecha_resolucion_apertura_sumariod" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_resolucion_apertura_sumario'],8,2); ?>">
Mes:
<input name="fecha_resolucion_apertura_sumariom" type="text" id="fecha_resolucion_apertura_sumariom" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_resolucion_apertura_sumario'],5,2); ?>">
A&ntilde;o:
<input name="fecha_resolucion_apertura_sumarioa" type="text" id="fecha_resolucion_apertura_sumarioa" size="12" maxlength="4" value="<?php echo substr($row_documentos_robados['fecha_resolucion_apertura_sumario'],0,4); ?>">
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr><tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v115; ?><!-- Fecha Resoluci&oacute;n de Cierre de Sumario:--></td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_resolucion_cierre_sumariod" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_resolucion_cierre_sumario'],8,2); ?>">
Mes:
<input name="fecha_resolucion_cierre_sumariom" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_resolucion_cierre_sumario'],5,2); ?>">
A&ntilde;o:
<input name="fecha_resolucion_cierre_sumarioa" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_documentos_robados['fecha_resolucion_cierre_sumario'],0,4); ?>">
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v116; ?><!-- Denunciante:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="denunciante" type="text" class="camposanchos" id="denunciante" value="<?php echo $row_documentos_robados['denunciante']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v117; ?><!-- N&uacute;mero Causa Judicial:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nro_causa_judicial" type="text" class="camposanchos" id="nro_causa_judicial" value="<?php echo $row_documentos_robados['nro_causa_judicial']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v118; ?><!-- N&uacute;mero Juzgado:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nro_juzgado" type="text" class="camposanchos" id="nro_juzgado" value="<?php echo $row_documentos_robados['nro_juzgado']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v119 ?><!-- Tipo Tr&aacute;mite Administrativo:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="tipo_tramite_administrativo" type="text" class="camposanchos" id="tipo_tramite_administrativo" value="<?php echo $row_documentos_robados['tipo_tramite_administrativo']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v120 ?><!-- N&uacute;mero Sumario Administrativo:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="nro_sumario_administrativo" type="text" class="camposanchos" id="nro_sumario_administrativo" value="<?php echo $row_documentos_robados['nro_sumario_administrativo']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v121 ?><!-- Fallo:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><textarea name="fallo" rows="10" class="camposanchos" id="fallo"><?php echo $row_documentos_robados['fallo']; ?></textarea>
    	        <script>
    var oEdit6 = new InnovaEditor("oEdit6");

    oEdit6.width=630;
    oEdit6.height=300;

    /***************************************************
    ADDING CUSTOM BUTTONS
    ***************************************************/

    oEdit6.arrCustomButtons = [["CustomName1","alert('Command 1 here.')","Caption 1 here","btnCustom1.gif"],
    ["CustomName2","alert(\"Command '2' here.\")","Caption 2 here","btnCustom2.gif"],
    ["CustomName3","alert('Command \"3\" here.')","Caption 3 here","btnCustom3.gif"]]


    /***************************************************
    RECONFIGURE TOOLBAR BUTTONS
    ***************************************************/
	
	oEdit6.useTab = true;

    oEdit6.tabs=[
    ["tabHome", "Home", ["grpEdit", "grpFont", "grpPara", "grpInsert", "grpTables"]],
    ["tabStyle", "Objects", ["grpResource", "grpMedia", "grpMisc", "grpCustom"]]
    ];

    oEdit6.groups=[
    ["grpEdit", "", ["Undo", "Redo", <?php if($s1!="C") { ?><?php if(!isset($_GET['v']) && $row_documentos_robados['full'] == 0) { echo "\"Save\","; } ?><?php } ?> "FullScreen", "RemoveFormat", "BRK", "Cut", "Copy", "Paste", "PasteWord", "PasteText", "HTMLSource"]],
    ["grpFont", "", ["FontName", "FontSize", "Styles", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "Superscript", "ForeColor", "BackColor"]],
    ["grpPara", "", ["Paragraph", "Indent", "Outdent", "StyleAndFormatting", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
    ["grpInsert", "", ["Hyperlink", "Bookmark", "BRK", "Image"]],
    ["grpTables", "", ["Table", "BRK", "Guidelines"]],
    ["grpResource", "", ["InternalLink", "BRK", "CustomObject"]],
    ["grpMedia", "", ["Media", "BRK", "Flash"]],
    ["grpMisc", "", ["Characters", "Line", "Absolute", "BRK", "CustomTag"]],
    ["grpCustom", "", ["CustomName1","CustomName2", "BRK","CustomName3"]]
    ];

    /*Set toolbar mode: 0: standard, 1: tab toolbar, 2: group toolbar */
    oEdit6.toolbarMode = 1;

    /***************************************************
    OTHER SETTINGS
    ***************************************************/
    oEdit6.css="style/test.css";//Specify external css file here

    oEdit6.cmdAssetManager = "modalDialogShow('assetmanager/assetmanager.php',640,465)"; //Command to open the Asset Manager add-on.
    oEdit6.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"; //Command to open your custom link lookup page.
    oEdit6.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"; //Command to open your custom content lookup page.

    oEdit6.arrCustomTag=[["First Name","{%first_name%}"],
    ["Last Name","{%last_name%}"],
    ["Email","{%email%}"]];//Define custom tag selection

    oEdit6.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9","#0f2f4f"];//predefined custom colors

    oEdit6.mode="XHTMLBody"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"

    oEdit6.REPLACE("fallo");

            </script></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v122 ?><!-- Fecha de Recuperaci&oacute;n:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_recuperaciond" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_recuperacion'],8,2); ?>">
Mes:
<input name="fecha_recuperacionm" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_recuperacion'],5,2); ?>">
A&ntilde;o:
<input name="fecha_recuperaciona" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_documentos_robados['fecha_recuperacion'],0,4); ?>">
(dd/mm/aaaa)</td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v123 ?><!-- Identificador de INTERPOL:--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor"><input name="object_id_interpol" type="text" class="camposanchos" id="object_id_interpol" value="<?php echo $row_documentos_robados['object_id_interpol']; ?>"  /></td>
                </tr>
                <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
              <tr>
                 <td class="tituloscampos ins_celdacolor"><?php echo $v124 ?><!-- Fecha de Registro en INTERPOL--></td>
            </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          	</tr>
                <tr>
                 <td class="tituloscampos ins_celdacolor">D&iacute;a:
                    <input name="fecha_registro_interpold" type="text" id="textfield7" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_registro_interpol'],8,2); ?>">
Mes:
<input name="fecha_registro_interpolm" type="text" id="textfield8" size="8" maxlength="2" value="<?php echo substr($row_documentos_robados['fecha_registro_interpol'],5,2); ?>">
A&ntilde;o:
<input name="fecha_registro_interpola" type="text" id="textfield9" size="12" maxlength="4" value="<?php echo substr($row_documentos_robados['fecha_registro_interpol'],0,4); ?>">
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
    	  <td class="celdabotones1"><?php if($s1!="C") { ?><?php if(!isset($_GET['v']) && $row_documentos_robados['full'] == 0) { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
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
<input name="sit" type="hidden" id="sit" value="<?php echo $_GET['sit']; ?>">
</form>
</body>
</html>
<?php
mysql_free_result($tips);

mysql_free_result($documentos_robados);
?>
