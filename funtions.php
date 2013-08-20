<?php
	if (!isset($_SESSION)) {
  		session_start();
	}
?>
<?php
function capitalizar($texto) {
	$resultado = "";
	
	// divido el texto basandome en los fines de linea \n\r en un array
	$parrafos = split("[\n\r][\n\r]", $texto); 
	// recorro cada elemento del array (foreach significa por cada uno)
	// $ value es una variable que va a ir tomando como valor cada linea del array
	foreach ($parrafos as $parrafosimple) {
	  // por cada linea del array
	  $parrafosimple = ($parrafosimple);
  	  $lineas = split("[\n\r]", $parrafosimple); 
	  foreach ($lineas as $lineasimple) {
	  	$resultado = $resultado . $lineasimple ."<br>";
	  }
	  // tira el br del parrafo
	  $resultado = $resultado ;
	}
 return $resultado;
}


function fechar($d,$m,$a) {
	$fecha = "";
	$vd = "01";
	$vm = "01";
	if ($a != "" && strlen($a) == 4) {
		if($d != "") {	
			$vd = substr("0".$d,-2,2);
		}
		if($m != "") {	
			$vm = substr("0".$m,-2,2);
		}
	$fecha = $a.$vm.$vd;
	} else {
	$fecha = "";	
	}
return 	$fecha;
}

function desfechar($fecha) {
	return substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
}


function permiso($usuario, $institucion, $seccion, $database_conn, $conn) {
	if ($_SESSION['MM_idrol'] == 1 || $_SESSION['MM_idrol'] == 2 || $_SESSION['MM_idrol']  == 3) {
		return "E";
	} else {
		mysql_select_db($database_conn, $conn);
		$query_permisos = sprintf("SELECT p.*, s.cod_secc, s.descripcion,  t.codigo_identificacion, u.usuario
FROM permisos p
LEFT JOIN secciones s ON (p.idseccion=s.idseccion)
LEFT JOIN rel_usuarios_roles_instituciones i ON (p.idrol=i.idrol)
LEFT JOIN rel_usuarios_instituciones t ON (i.idrel_usuarios_instituciones=t.idrel_usuarios_instituciones)
LEFT JOIN usuarios u ON (t.idusuario=u.idusuario)
WHERE u.usuario=%s AND t.codigo_identificacion = %s AND cod_secc=%s",
							GetSQLValueString($usuario, "text"),
							GetSQLValueString($institucion, "text"),
							GetSQLValueString($seccion, "text"));
		$permisos = mysql_query($query_permisos, $conn) or die(mysql_error());
		$row_permisos = mysql_fetch_assoc($permisos);
		$totalRows_permisos = mysql_num_rows($permisos);
		return $row_permisos['accion'];
		mysql_free_result($permisos);
	}	
}

function correctorcodigo($texto) {

$texto = str_replace("\\", "/", $texto);
$texto = str_replace("//", "/", $texto);
$texto = str_replace("&", "-", $texto);
$texto = str_replace("ñ", "n", $texto);

$texto = str_replace("á", "a", $texto);
$texto = str_replace("é", "e", $texto);
$texto = str_replace("í", "i", $texto);
$texto = str_replace("ó", "o", $texto);
$texto = str_replace("ú", "u", $texto);

$texto = str_replace("Á", "A", $texto);
$texto = str_replace("É", "E", $texto);
$texto = str_replace("Í", "I", $texto);
$texto = str_replace("Ú", "U", $texto);
$texto = str_replace("Ó", "O", $texto);

return $texto;
}

function estabilizarcodigo($texto) {
	
$texto = str_replace(array('á','à','â','ã','ª','ä'),'a',$texto);
$texto = str_replace(array('Á','À','Â','Ã','Ä'),'A',$texto);
$texto = str_replace(array('Í','Ì','Î','Ï'),'I',$texto);
$texto = str_replace(array('í','ì','î','ï'),'i',$texto);
$texto = str_replace(array('é','è','ê','ë'),'e',$texto);
$texto = str_replace(array('É','È','Ê','Ë'),'E',$texto);
$texto = str_replace(array('ó','ò','ô','õ','ö','º'),'o',$texto);
$texto = str_replace(array('Ó','Ò','Ô','Õ','Ö'),'O',$texto);
$texto = str_replace(array('ú','ù','û','ü'),'u',$texto);
$texto = str_replace(array('Ú','Ù','Û','Ü'),'U',$texto);
$texto = str_replace(array('[','^','´','`','¨','~',']'),'',$texto);
$texto = str_replace('ç','c',$texto);
$texto = str_replace('Ç','C',$texto);
$texto = str_replace('ñ','n',$texto);
$texto = str_replace('Ñ','N',$texto);
$texto = str_replace('Ý','Y',$texto);
$texto = str_replace('ý','y',$texto);
$texto = str_replace("\\", "-", $texto);
$texto = str_replace("//", "-", $texto);
$texto = str_replace("&", "-", $texto);
$texto = str_replace(" ", "_", $texto);

return $texto;
}


function errordisplay($r,$e) {
$t="";	
$t.="	<table width=\"100%\" height=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>";
$t.="	<table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">";
$t.="        <tr>";
$t.="	          <td style=\"border-color:#a99e88; border-style:solid; border-width:medium;\"><table width=\"350\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$t.="	            <tr>";
$t.="	              <td>&nbsp;</td>";
$t.="                </tr>";
$t.="	            <tr>";
$t.="	              <td  style=\"color:#a99e88; font-family:Arial; font-size:12px; font-weight:bold;\"><strong>ERROR: ".$r."</strong></td>";
$t.="                </tr>";
$t.="	            <tr>";
$t.="	              <td>&nbsp;</td>";
$t.="                </tr>";
$t.="	            <tr>";
$t.="	              <td   style=\"color:#a99e88; font-family:Arial; font-size:10px;\"><strong>".$e."</strong></td>";
$t.="                </tr>";
$t.="	            <tr>";
$t.="	              <td>&nbsp;</td>";
$t.="                </tr>";
$t.="	            <tr>";
$t.="	              <td class=\"textoCampo\">&nbsp;</td>";
$t.="                </tr>";
$t.="              </table></td>";
$t.="            </tr>";
$t.="        </table>";
$t.="        </td></tr></table>";
echo $t;
exit;
}

function  aHtml($cadena){

$minusculas = array ("á"=>"&aacute;","é"=>"&eacute;","í"=>"&iacute;","ó"=>"&oacute;", "ú"=>"&uacute;","ñ"=>"&ntilde;");
 $mayusculas = array ("Á"=>"&Aacute;","É"=>"&Eacute;","Í"=>"&Iacute;","Ó"=>"&Oacute;", "Ú"=>"&Uacute;","Ñ"=>"&Ntilde;");

$cad = strtr($cadena,$minusculas);
 $cad = strtr($cadena,$mayusculas);
 return $cad;

}

function  fromHtml($cadena){

$minusculas = array ("&aacute;"=>"á","&eacute;"=>"é","&iacute;"=>"í","&oacute;"=>"ó", "&uacute;"=>"ú","&ntilde;"=>"ñ");
 $mayusculas = array ("&Aacute;"=>"Á","&Eacute;"=>"É","&Iacute;"=>"Í","&Oacute;"=>"Ó", "&Uacute;"=>"Ú","&Ntilde;"=>"Ñ");

$cad = strtr($cadena,$minusculas);
 $cad = strtr($cadena,$mayusculas);
 return $cad;

}


?>