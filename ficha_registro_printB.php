<?php require_once('Connections/conn.php'); 

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
if (!((isset($_SESSION['MM_Username']) && isset($_SESSION['MM_Memorar'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    return strip_tags( $text );
}

$colname_intitucion = "-1";
if (isset($_POST['institucionSend'])) {
  $colname_intitucion = $_POST['institucionSend'];
}
mysql_select_db($database_conn, $conn);
$query_intitucion = sprintf("SELECT formas_conocidas_nombre FROM instituciones WHERE codigo_identificacion = %s LIMIT 1", GetSQLValueString($colname_intitucion, "text"));
$intitucion = mysql_query($query_intitucion, $conn) or die(mysql_error());
$row_intitucion = mysql_fetch_assoc($intitucion);
$totalRows_intitucion = mysql_num_rows($intitucion);

$QueryGet = $_POST['QuerySend'];
$QueryGet = str_replace("\\'","'",$QueryGet);

//echo $QueryGet;

mysql_select_db($database_conn, $conn);
$query_documentos = $QueryGet;
$documentos = mysql_query($query_documentos, $conn) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);
$totalRows_documentos = mysql_num_rows($documentos);

function dvFecha($fecha) {
	$fecha = substr($fecha, 0, 10);
	if($fecha=="0000-00-00") {
		return "//";
	} else {
		$source = $fecha;
		$date = new DateTime($source);
		return $date->format('d/m/Y');
	}
}

function imprimirMultiples($doc, $tabla, $filtro, $campo, $database_conn, $conn, $unico=0, $orden='', $direccion='DESC') {
	
	if($unico == 1) { $limite=" LIMIT 1"; } else { $limite=""; }
	if($orden != "") { $order=" ORDER BY ".$orden." ".$direccion; } else {$order="";} 
	
	mysql_select_db($database_conn, $conn);
	$query_multiples = "SELECT ".$campo." FROM ".$tabla." WHERE ".$filtro." = '".$doc."'".$order.$limite;
	$multiples = mysql_query($query_multiples, $conn) or die(mysql_error());
	$row_multiples = mysql_fetch_assoc($multiples);
	$totalRows_multiples = mysql_num_rows($multiples);
	$vuelta="";
	$filas=1;
	do {
		$vuelta .= $row_multiples[$campo];
		if($filas < $totalRows_multiples) {
			$vuelta .=", ";
		}
		$filas++;	
	} while ($row_multiples = mysql_fetch_assoc($multiples));
	mysql_free_result($multiples);
	return $vuelta;
}

function totales($tipo, $institucion, $database_conn, $conn){
	if($tipo=="N") {
		$tabla="niveles";
		$campo="tipo_nivel";
		$tabla_estado="niveles_estados";	
	}
	if($tipo=="D") {
		$tabla="documentos";
		$campo="tipo_diplomatico";
		$tabla_estado="documentos_estados";		
	}


	$totaDv['Inicio']=0;
	$totaDv['Pendiente']=0;
	$totaDv['Completo']=0;
	$totaDv['Vigente']=0;
	$totaDv['Cancelado']=0;
	$totaDv['No Vigente']=0;
	$totaDv["total"]=0;
	
	
	//$query_totales = "SELECT count(*) AS tot FROM ".$tabla." WHERE codigo_institucion='".$institucion."' AND ".$campo." = ".$tipo;
	mysql_select_db($database_conn, $conn);
	$query_totales ="SELECT DE.estado, count(*) AS cant 
					FROM ".$tabla." D
					INNER JOIN ".$tabla_estado." DE ON(D.codigo_referencia=DE.codigo_referencia AND DE.fecha=(SELECT max(fecha) FROM ".$tabla_estado." DE2 
					WHERE D.codigo_referencia=DE2.codigo_referencia))
					WHERE D.codigo_institucion='".$institucion."' 
					GROUP BY DE.estado";
	$totales  = mysql_query($query_totales, $conn) or die(mysql_error());
	$row_totales = mysql_fetch_assoc($totales);
	$totalRows_totales = mysql_num_rows($totales);

	do{

		$totaDv[$row_totales['estado']]=$row_totales['cant'];
		$totaDv["total"] = $totaDv["total"] + $row_totales['cant'];
		
	} while ($row_totales = mysql_fetch_assoc($totales));
	
	mysql_free_result($totales);
	
	return $totaDv;
	
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	background-color: #FFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

 H1.SaltoDePagina
 {
     PAGE-BREAK-AFTER: always
 }
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php if($_POST['desdeSend'] == 1) { ?>
<table width="800" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td align="center"><span class="contador"><?php echo $row_intitucion['formas_conocidas_nombre']; ?></span><br>
	  <br>
<br><img src="archivos/fotosmuseos/Inst0.jpg" width="600" height="450"><br>
<br>
<p align="center" style="font-family:Arial; font-size:26px;">INVENTARIO DE FICHAS DE REGISTRO<br>
  DE <?PHP if($_POST['tipoSend'] == "N") { echo "NIVELES"; } else { echo "UNIDADES"; } ?> DOCUMENTALES</p>
<p align="center" style="font-family:Arial; font-size:28px; font-weight:bold;">MEMORar</p>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px">&nbsp;</td>
  </tr>
</table>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px">ESTADO Y CANTIDAD DE REGISTRO AL <?php echo date("d/m/Y"); ?>:</td>
  </tr>
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px"><table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="397">Inicio:..........................................................................................</td>
        <td width="103"><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
<?php
$tot1 = totales($_POST['tipoSend'], $_POST['institucionSend'], $database_conn, $conn);
?>
            <td align="right"><?php echo $tot1['Inicio']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="4" colspan="2"></td>
        </tr>
      <tr>
        <td>Pendiente:...................................................................................</td>
        <td><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><?php echo $tot1['Pendiente']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="4" colspan="2"></td>
        </tr>
      <tr>
        <td>Completo:....................................................................................</td>
        <td><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><?php echo $tot1['Completo']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="4" colspan="2"></td>
        </tr>
      <tr>
        <td>Vigente:......................................................................................</td>
        <td><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><?php echo $tot1['Vigente']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="4" colspan="2"></td>
        </tr>
      <tr>
        <td>No Vigente:.................................................................................</td>
        <td><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><?php echo $tot1['No Vigente']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="4" colspan="2"></td>
        </tr>
      <tr>
        <td>Cancelado:..................................................................................</td>
        <td><table width="90" border="1" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><?php echo $tot1['Cancelado']; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" style="font-family:Arial; font-size:20px">&nbsp;</td>
  </tr>
</table>
<p align="center" style="font-family:Arial; font-size:26px;">&nbsp;</p></td>
</tr>
</table>
<?php } ?>
<?php $pag=$_POST['desdeSend']-1; do { $pag++;  ?>
<table width="800" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="140"><img src="images/inf_encab_01.jpg" width="800" height="140"></td>
  </tr>
  <tr>
    <td valign="top"><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="1. Nombre de la instituci&oacute;n:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_intitucion['formas_conocidas_nombre']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="2. Fecha de Ingreso:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo dvFecha($row_documentos['fecha_registro'])."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="3. C&oacute;digo de referencia:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['codigo_referencia']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="4. Estado / Situación:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo imprimirMultiples($row_documentos['codigo_referencia'], 'documentos_estados','codigo_referencia', 'estado', $database_conn, $conn,1,'fecha')."&nbsp;/&nbsp;".$row_documentos['situacion']; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="5. N&uacute;mero de inventario:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['numero_inventario_unidad_documental']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="6. N&uacute;meros de inventarios anteriores:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['numero_registro_inventario_anterior']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="7. N&uacute;mero administrativo:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['numero_administrativo']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="8. T&iacute;tulo:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['titulo_original']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="9. Nombre del productor/autor:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['nombre_productor']." / ".$row_documentos['autor']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="10. Fecha:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo dvFecha($row_documentos['fecha_inicial'])." - ".dvFecha($row_documentos['fecha_final'])."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="11. Alcance de contenidos:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['alcance_contenido']."&nbsp;"; ?></span><br>
    
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="12. Idioma:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo imprimirMultiples($row_documentos['codigo_referencia'], 'rel_documentos_idiomas','codigo_referencia', 'idioma', $database_conn, $conn)."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="13. Soporte:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['soporte']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="14. Tipo General de documento:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['tipo_general_documento']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="15. Tipo espec&iacute;fico de documento:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['tipo_especifico_documento']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="16. Tradici&oacute;n documental:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['tradicion_documental']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="17. Unidades:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['unidades']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="18. Valuaci&oacute;n:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo imprimirMultiples($row_documentos['codigo_referencia'], 'documentos_tasaciones_expertizaje','codigo_referencia', 'valuacion', $database_conn, $conn, 1, 'fecha')."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="19. Integridad/signos especiales:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['integridad']." / ".$row_documentos['signos_especiales']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="20. Estado de conservaci&oacute;n:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['estado_conservacion']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="21. Forma de ingreso y procedencia:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['forma_ingreso']." / ".$row_documentos['procedencia']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="22. Norma legal de ingreso:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['norma_legal_ingreso']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="23. N&uacute;mero&nbsp; legal de ingreso:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['numero_legal_ingreso']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="24. Norma legal de baja:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['normativa_legal_baja']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="25. Motivo de la baja:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['motivo_baja']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="26. Fecha de la baja:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['fecha_baja']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="27. Restricciones para la consulta:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo $row_documentos['tipo_acceso']." / ".$row_intitucion['requisito_acceso']."&nbsp;"; ?></span><br>
    
    <span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span><br>
    <INPUT TYPE="text" style="background-color:#e4e4e4; width:800px; border:0px; font-family:Arial; font-size:12px; font-weight:bold;" value="28. Responsable del ingreso:"><br>
    <span style="font-family:Arial; font-size:12px;"><?php echo imprimirMultiples($row_documentos['codigo_referencia'], 'documentos_areasnotas','codigo_referencia', 'nota_archivero', $database_conn, $conn, 1, 'fecha_descripcion')."&nbsp;"; ?></span><br>
<span style="font-family:Arial; font-size:6px; color:#ffffff;">.</span></td>
  </tr>
  <tr>
    <td height="47" valign="top" style="background-image:url(images/inf_pie_01.jpg)"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><span style="font-family:Arial; font-size:12px;">Impreso: <?php echo $_SESSION['MM_Nombre']." ".$_SESSION['MM_Apellido']; ?> - Fecha Impreso: <?php echo date("d/m/Y"); ?> - Página <?php echo $pag; ?> de <?php echo $_POST['totalSend']; ?> - <?php echo $_POST['codigoSend']; ?></span></td>
      </tr>
    </table></td>
  </tr>
</table>
<?PHP if($_POST['tipoSend'] == "N") { echo "<H1 class=SaltoDePagina></H1>"; } ?>
<?php  } while ($row_documentos = mysql_fetch_assoc($documentos)); ?>
</body>
</html>