<?php require_once('Connections/conn.php'); 
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
if (isset($_GET['cod'])) {
  $colname_intitucion = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_intitucion = sprintf("SELECT * FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($colname_intitucion, "text"));
$intitucion = mysql_query($query_intitucion, $conn) or die(mysql_error());
$row_intitucion = mysql_fetch_assoc($intitucion);
$totalRows_intitucion = mysql_num_rows($intitucion);

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_documentos = 100;
$pageNum_documentos = 0;
if (isset($_GET['pageNum_documentos'])) {
  $pageNum_documentos = $_GET['pageNum_documentos'];
}
$startRow_documentos = $pageNum_documentos * $maxRows_documentos;

$colname_documentos = "-1";
if (isset($_GET['cod'])) {
  $colname_documentos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_documentos = sprintf("SELECT * FROM documentos WHERE codigo_institucion = %s ORDER BY codigo_referencia ASC", GetSQLValueString($colname_documentos, "text"));
$query_limit_documentos = sprintf("%s LIMIT %d, %d", $query_documentos, $startRow_documentos, $maxRows_documentos);
$documentos = mysql_query($query_limit_documentos, $conn) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);

if (isset($_GET['totalRows_documentos'])) {
  $totalRows_documentos = $_GET['totalRows_documentos'];
} else {
  $all_documentos = mysql_query($query_documentos);
  $totalRows_documentos = mysql_num_rows($all_documentos);
}
$totalPages_documentos = ceil($totalRows_documentos/$maxRows_documentos)-1;

$queryString_documentos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_documentos") == false && 
        stristr($param, "totalRows_documentos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_documentos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_documentos = sprintf("&totalRows_documentos=%d%s", $totalRows_documentos, $queryString_documentos);

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

$content="";

do { 

$content .="<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$content .="<tr>";
$content .="    <td></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="  </tr>";
$content .="</table>";
$content .="  <table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$content .="  <tr>";
$content .="    <td width=\"20\" style=\"font-family:Arial; font-size:16px; font-weight:bold;\">1. </td>";
$content .="    <td width=\"680\" style=\"font-family:Arial; font-size:16px; font-weight:bold;\">Nombre de la instituci&oacute;n:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:16px;\">".$row_intitucion['formas_conocidas_nombre']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">2.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Fecha de Ingreso:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".dvFecha($row_documentos['fecha_registro'])."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">3.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">N&uacute;mero de inventario:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['numero_inventario_unidad_documental']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">4.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\"> N&uacute;meros de inventarios anteriores:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['numero_registro_inventario_anterior']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">5.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\"> N&uacute;mero administrativo:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['numero_administrativo']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">6.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\"> T&iacute;tulo:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['titulo_original']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">7.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Nombre del productor/autor:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['nombre_productor']." / ".$row_documentos['autor']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">8.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\"> Fecha:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".dvFecha($row_documentos['fecha_inicial'])." - ".dvFecha($row_documentos['fecha_final'])."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">9.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Alcance de contenidos:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['alcance_contenido']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">10.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Idioma:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".imprimirMultiples($row_documentos['codigo_referencia'], 'rel_documentos_idiomas','codigo_referencia', 'idioma', $database_conn, $conn)."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">11.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Soporte:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['soporte']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">12.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Tipo General de documento:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['tipo_general_documento']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">13.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Tipo espec&iacute;fico de documento:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['tipo_especifico_documento']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">14.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Tradici&oacute;n documental:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['tradicion_documental']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">15.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Unidades:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['unidades']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">16.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Valuaci&oacute;n:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".imprimirMultiples($row_documentos['codigo_referencia'], 'documentos_tasaciones_expertizaje','codigo_referencia', 'valuacion', $database_conn, $conn, 1, 'fecha')."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">17.</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\"> Integridad/signos especiales:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['integridad']." / ".$row_documentos['signos_especiales']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">18.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Estado de conservaci&oacute;n:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['estado_conservacion']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">19.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Forma de ingreso y procedencia:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['forma_ingreso']." / ".$row_documentos['procedencia']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">20.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Norma legal de ingreso:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['norma_legal_ingreso']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">21.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">N&uacute;mero&nbsp; legal de ingreso:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['numero_legal_ingreso']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">22.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Norma legal de baja:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['normativa_legal_baja']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">23.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Motivo de la baja:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['motivo_baja']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">24.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Fecha de la baja:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['fecha_baja']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">25.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Restricciones para la consulta:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".$row_documentos['tipo_acceso']." / ".$row_intitucion['requisito_acceso']."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\" height=\"4\"></td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">26.  </td>";
$content .="    <td style=\"font-family:Arial; font-size:12px; font-weight:bold;\">Responsable del ingreso:</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="  <td>&nbsp;</td>";
$content .="    <td style=\"font-family:Arial; font-size:12px;\">".imprimirMultiples($row_documentos['codigo_referencia'], 'documentos_areasnotas','codigo_referencia', 'nota_archivero', $database_conn, $conn, 1, 'fecha_descripcion')."</td>";
$content .="  </tr>";
$content .="  <tr>";
$content .="    <td colspan=\"2\">&nbsp;</td>";
$content .="  </tr>";
$content .="  </table>\n";
//$content .= "<page pageset=\"old\" pagegroup=\"new\"></page>";
 } while ($row_documentos = mysql_fetch_assoc($documentos)); 
 
$content = str_replace("Times New Roman","",$content);
$content = str_replace("'","",$content);
 
 echo $content;
 
mysql_free_result($intitucion);

mysql_free_result($documentos); 



/*
require_once(dirname(__FILE__).'/script/html2pdf_v4.02/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','es');
$html2pdf->WriteHTML($content);
$html2pdf->Output('comprobante.pdf');
*/

?>