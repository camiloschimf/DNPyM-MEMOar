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
$query_instituciones = "SELECT codigo_identificacion, formas_conocidas_nombre FROM instituciones WHERE codigo_identificacion <> 'DNPyM' ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

function totales($tipo, $institucion, $database_conn, $conn){
	if($tipo==1 || $tipo==2 || $tipo==3 || $tipo==4) {
		$tabla="niveles";
		$campo="tipo_nivel";
		$tabla_estado="niveles_estados";	
	}
	if($tipo==8 || $tipo==9 || $tipo==10 || $tipo==11) {
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
					AND D.".$campo." = ".$tipo."
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

function ultimaFecha($institucion, $database_conn, $conn) {
	
	mysql_select_db($database_conn, $conn);
	$query_fecha = "SELECT max(fecha) AS fecha FROM documentos_estados WHERE estado='Inicio' AND codigo_referencia LIKE '".$institucion."/%' ";
	$fecha  = mysql_query($query_fecha, $conn) or die(mysql_error());
	$row_fecha = mysql_fetch_assoc($fecha);
	$totalRows_fecha = mysql_num_rows($fecha);
	
	if($row_fecha['fecha'] != "") {
		$fechaDev = dvFecha($row_fecha['fecha']);
	} else {
		$fechaDev = "Sin Doc.";
	}
	
	mysql_free_result($fecha);
	
	return $fechaDev;
	
}

$content ="<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td><img src=\"images/ecambezado.jpg\" width=\"800\" height=\"142\"></td>
  </tr>
</table>
<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
  <td>&nbsp;</td>
 </tr>
  <tr>
  <td><table width=\"800\" border=\"1\" cellspacing=\"1\" cellpadding=\"5\">
    <tr>
      <td align=\"center\" width=\"740\" style=\"font-family: Arial; font-size: 14pt; font-weight: bold;\">INVENTARIO GENERAL &quot;MEMORar&quot; </td>
    </tr>
  </table></td></tr>
  <tr>
  <td>&nbsp;</td></tr></table>";
  
do {  
$content .="
 <table width=\"760\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr>
            <td bgcolor=\"#CCCCCC\" style=\"font-family:Arial; font-size:16px; font-weight:bold;\">".htmlentities($row_instituciones['formas_conocidas_nombre'])."</td>
          </tr>
          <tr>
            <td><table width=\"720\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">
			  <tr>
			  	<td width=\"440\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\"></td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">I</td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">P</td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">C</td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">V</td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">NV</td>
				<td width=\"30\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">C</td>
				<td width=\"100\" align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">TOT</td>
			  </tr>
			  <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
			  </tr>	
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Fondo / Subfondo</td>";
$tot1 = totales(1, $row_instituciones['codigo_identificacion'], $database_conn, $conn);				
$content .="    
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot1['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot1['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
			  </tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Serie / Subserie</td>";
$tot2 = totales(2, $row_instituciones['codigo_identificacion'], $database_conn, $conn);				
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot2['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot2['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
				</tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Secci&oacute;n / Subsecci&oacute;n</td>";
$tot3 =	totales(3, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot3['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot3['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
			  </tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Agrupaciones Documentales</td>";
$tot4 =	totales(4, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot4['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot4['total']."</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align=\"right\">&nbsp;</td>
              </tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Documentos Visuales</td>";
$tot8 =	totales(8, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot8['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot8['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
				</tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Documentos Audiovisuales</td>";
$tot9 =	totales(9, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot9['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot9['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
				</tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Documentos Sonoros</td>";
$tot10 =	totales(10, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot10['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot10['total']."</td>
              </tr>
              <tr>
                <td height=\"1\" colspan=\"8\" bgcolor=\"#CCCCCC\"></td>
				</tr>
              <tr>
                <td style=\"font-family:Arial; font-size:14px;\">Documentos Textuales</td>";
$tot11 =	totales(11, $row_instituciones['codigo_identificacion'], $database_conn, $conn);			
$content .="    <td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['Inicio']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['Pendiente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['Completo']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['No Vigente']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px;\">".$tot11['Cancelado']."</td>
				<td  align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".$tot11['total']."</td>
              </tr>
			  <tr>
                <td>&nbsp;</td>
                <td align=\"right\">&nbsp;</td>
              </tr>
			<tr>
                <td style=\"font-family:Arial; font-size:14px;\" colspan=\"7\">Fecha del Ultimo Documento Cargado:</td>
                <td align=\"right\" style=\"font-family:Arial; font-size:14px; font-weight:bold;\">".ultimaFecha($row_instituciones['codigo_identificacion'], $database_conn, $conn)."</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>\n";
} while ($row_instituciones = mysql_fetch_assoc($instituciones));  
$content .="<table width=\"800\" border=\"1\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
      <tr>
        <td align=\"center\"  width=\"740\" style=\"font-family: Arial; font-size: 12pt; \">Impreso el ".date("d/m/Y")." por ".$_SESSION['MM_Nombre']." ".$_SESSION['MM_Apellido']."</td>
      </tr>
    </table>\n";



echo $content;

mysql_free_result($instituciones);

/*
require_once(dirname(__FILE__).'/script/html2pdf_v4.02/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','es');
$html2pdf->WriteHTML($content);
$html2pdf->Output('totales.pdf');
*/

?>
