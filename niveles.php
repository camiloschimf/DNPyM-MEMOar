<?php require_once('Connections/conn.php'); ?>
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

$usuario = $_SESSION['MM_Username'];

/*
echo "cr: ".$_POST['cs']."/".$_POST['cn']."<br>";
echo "tn: ".$_POST['tn']."<br>";
echo "cs: ".$_POST['cs']."<br>";
echo "ci: ".$_POST['ci']."<br>";
echo "ci: ".$_POST['tv']."<br>";
*/


//insertamos los niveles y los documentos compuestos
if (isset($_POST['cs']) && isset($_POST['cn']) && isset($_POST['ci']) && isset($_POST['tn']) && $_POST['cs'] != "" && $_POST['cn'] != "" && $_POST['ci'] != "" && $_POST['tn'] != "") {
if($_POST['tn'] <= 5) {
$insertSQL1 = sprintf("INSERT INTO niveles (codigo_referencia, tipo_nivel, cod_ref_sup, codigo_institucion, tv) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cs']."/".$_POST['cn'], "text"),
                       GetSQLValueString($_POST['tn'], "text"),
                       GetSQLValueString($_POST['cs'], "text"),
                       GetSQLValueString($_POST['ci'], "text"),
					   GetSQLValueString($_POST['tv'], "text"));
					   
//cs: conigo del nivel superior
//cn: codigo del nivel que se esta ingresando
//--- con los dos anteriores se forma el codigo de referencia del nivel
//ci: codigo de la institucion a la que pertenece
//tn: tipo de nivel que se esta ingresando

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL1, $conn) or die(mysql_error());
  
  
  
  $insertSQL2 = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_POST['cs']."/".$_POST['cn'], "text"),
                GetSQLValueString("Inicio", "text"),
  				GetSQLValueString($usuario, "text"));
  
 	 mysql_select_db($database_conn, $conn);
  	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
}


//insertamos los documentos simples
if($_POST['tn'] >= 8 && $_POST['tn'] <= 11) {
	
//echo "cs:".$_POST['cs']."<br>cn:".$_POST['cn']."<br>tn:".$_POST['tn']."<br>ci:".$_POST['ci']."<br>tv:".$_POST['tv'];	
	
	$insertSQL1 = sprintf("INSERT INTO documentos (codigo_referencia, tipo_diplomatico, cod_ref_sup, codigo_institucion, tv) VALUES (%s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['cs']."/".$_POST['cn'], "text"),
                       GetSQLValueString($_POST['tn'], "text"),
                       GetSQLValueString($_POST['cs'], "text"),
                       GetSQLValueString($_POST['ci'], "text"),
					   GetSQLValueString($_POST['tv'], "text"));
	mysql_select_db($database_conn, $conn);
  	$Result1 = mysql_query($insertSQL1, $conn) or die(mysql_error());
	
	$insertSQL2 = sprintf("INSERT INTO documentos_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_POST['cs']."/".$_POST['cn'], "text"),
                GetSQLValueString("Inicio", "text"),
  				GetSQLValueString($usuario, "text"));
  
 	 mysql_select_db($database_conn, $conn);
  	$Result2 = mysql_query($insertSQL2, $conn) or die(mysql_error());
}

//eliminamos el nivel y todo el parentesco
if($_POST['tn'] == 6) {
	
	$V = "(SELECT niveles.codigo_referencia, codigo_institucion, '1' as tipo_diplomatico, tv, tipo_nivel, fecha_baja, niveles_estados.estado, niveles_estados.fecha FROM niveles LEFT JOIN niveles_estados ON (niveles.codigo_referencia=niveles_estados.codigo_referencia ) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND niveles_estados.fecha=(SELECT MAX(niveles_estados.fecha) FROM niveles_estados WHERE niveles_estados.codigo_referencia=niveles.codigo_referencia) or niveles_estados.fecha is null) UNION (SELECT documentos.codigo_referencia, codigo_institucion, tipo_diplomatico, tv, tipo_diplomatico as tipo_nivel, fecha_baja, documentos_estados.estado, documentos_estados.fecha FROM documentos LEFT JOIN documentos_estados ON (documentos.codigo_referencia=documentos_estados.codigo_referencia) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND documentos_estados.fecha=(SELECT MAX(documentos_estados.fecha) FROM documentos_estados WHERE documentos_estados.codigo_referencia=documentos.codigo_referencia) or documentos_estados.fecha is null) ORDER BY  codigo_referencia ASC";
	
	$v2= "(SELECT niveles.codigo_referencia, codigo_institucion, '1' as tipo_diplomatico, tv, tipo_nivel, fecha_baja, niveles_estados.estado, niveles_estados.fecha FROM niveles LEFT JOIN niveles_estados ON (niveles.codigo_referencia=niveles_estados.codigo_referencia ) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (niveles_estados.fecha=(SELECT MAX(niveles_estados.fecha) FROM niveles_estados WHERE niveles_estados.codigo_referencia=niveles.codigo_referencia) OR niveles_estados.fecha IS NULL) AND niveles.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%') UNION (SELECT documentos.codigo_referencia, codigo_institucion, tipo_diplomatico, tv, tipo_diplomatico as tipo_nivel, fecha_baja, documentos_estados.estado, documentos_estados.fecha FROM documentos LEFT JOIN documentos_estados ON (documentos.codigo_referencia=documentos_estados.codigo_referencia) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (documentos_estados.fecha=(SELECT MAX(documentos_estados.fecha) FROM documentos_estados WHERE documentos_estados.codigo_referencia=documentos.codigo_referencia) or documentos_estados.fecha is null) AND documentos.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%') ORDER BY  codigo_referencia ASC";
	
	$v="(SELECT niveles.codigo_referencia, codigo_institucion, '1' as tipo_diplomatico, tv, tipo_nivel, fecha_baja, niveles_estados.estado, niveles_estados.fecha FROM niveles LEFT JOIN niveles_estados ON (niveles.codigo_referencia=niveles_estados.codigo_referencia ) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (niveles_estados.fecha=(SELECT MAX(niveles_estados.fecha) FROM niveles_estados WHERE niveles_estados.codigo_referencia=niveles.codigo_referencia) OR niveles_estados.fecha IS NULL) AND niveles.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%' AND niveles_estados.estado <> 'Inicio') UNION (SELECT documentos.codigo_referencia, codigo_institucion, tipo_diplomatico, tv, tipo_diplomatico as tipo_nivel, fecha_baja, documentos_estados.estado, documentos_estados.fecha FROM documentos LEFT JOIN documentos_estados ON (documentos.codigo_referencia=documentos_estados.codigo_referencia) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (documentos_estados.fecha=(SELECT MAX(documentos_estados.fecha) FROM documentos_estados WHERE documentos_estados.codigo_referencia=documentos.codigo_referencia) or documentos_estados.fecha is null) AND documentos.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%' AND documentos_estados.estado <> 'Inicio') ORDER BY  codigo_referencia ASC";
	
mysql_select_db($database_conn, $conn);
$query_hijosusados = sprintf("(SELECT niveles.codigo_referencia, codigo_institucion, '1' as tipo_diplomatico, tv, tipo_nivel, fecha_baja, niveles_estados.estado, niveles_estados.fecha FROM niveles LEFT JOIN niveles_estados ON (niveles.codigo_referencia=niveles_estados.codigo_referencia ) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (niveles_estados.fecha=(SELECT MAX(niveles_estados.fecha) FROM niveles_estados WHERE niveles_estados.codigo_referencia=niveles.codigo_referencia) OR niveles_estados.fecha IS NULL) AND niveles.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%' AND niveles_estados.estado <> 'Inicio') UNION (SELECT documentos.codigo_referencia, codigo_institucion, tipo_diplomatico, tv, tipo_diplomatico as tipo_nivel, fecha_baja, documentos_estados.estado, documentos_estados.fecha FROM documentos LEFT JOIN documentos_estados ON (documentos.codigo_referencia=documentos_estados.codigo_referencia) WHERE codigo_institucion='AR/ST/FFSDD/KASSD/GGe' AND (documentos_estados.fecha=(SELECT MAX(documentos_estados.fecha) FROM documentos_estados WHERE documentos_estados.codigo_referencia=documentos.codigo_referencia) or documentos_estados.fecha is null) AND documentos.codigo_referencia LIKE 'AR/ST/FFSDD/KASSD/GGe/FF02/SF02%' AND documentos_estados.estado <> 'Inicio') ORDER BY  codigo_referencia ASC", GetSQLValueString($colname_hijosusados, "text"));
$hijosusados= mysql_query($query_hijosusados, $conn) or die(mysql_error());
$row_hijosusados = mysql_fetch_assoc($hijosusados);
$totalRows_hijosusados = mysql_num_rows($hijosusados);
	
}
  
  $updateGoTo = "niveles.php?cod=".$_GET['cod'];
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_vis_instituciones = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_instituciones = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_instituciones = sprintf("SELECT * FROM instituciones WHERE codigo_identificacion = %s", GetSQLValueString($colname_vis_instituciones, "text"));
$vis_instituciones = mysql_query($query_vis_instituciones, $conn) or die(mysql_error());
$row_vis_instituciones = mysql_fetch_assoc($vis_instituciones);
$totalRows_vis_instituciones = mysql_num_rows($vis_instituciones);

$colname_vis_nivelesVert = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_nivelesVert = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_nivelesVert = sprintf("(SELECT codigo_referencia, codigo_institucion, '1' as tipo_diplomatico, tv, tipo_nivel, fecha_baja FROM niveles WHERE codigo_institucion=%s) UNION (SELECT codigo_referencia, codigo_institucion, tipo_diplomatico, tv, tipo_diplomatico as tipo_nivel, fecha_baja FROM documentos WHERE codigo_institucion=%s) ORDER BY  codigo_referencia ASC", GetSQLValueString($colname_vis_nivelesVert, "text"), GetSQLValueString($colname_vis_nivelesVert, "text"));
$vis_nivelesVert = mysql_query($query_vis_nivelesVert, $conn) or die(mysql_error());
$row_vis_nivelesVert = mysql_fetch_assoc($vis_nivelesVert);
$totalRows_vis_nivelesVert = mysql_num_rows($vis_nivelesVert);


$colname_vis_maxTV = "-1";
if (isset($_GET['cod'])) {
  $colname_vis_maxTV = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_maxTV = sprintf("SELECT max(tv) as maxtv  FROM niveles WHERE codigo_institucion=%s ORDER BY  codigo_referencia ASC", GetSQLValueString($colname_vis_maxTV, "text"));
$vis_maxTV = mysql_query($query_vis_maxTV, $conn) or die(mysql_error());
$row_vis_maxTV = mysql_fetch_assoc($vis_maxTV);
$totalRows_vis_maxTV = mysql_num_rows($vis_maxTV);


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
}
  
.tipo0{   
padding: 6px;   
position: absolute;   
width: 200px;   
border: 2px solid black;   
background-color: menu;   
font-family: Verdana;   
line-height: 20px;   
cursor: default;   
visibility: hidden;   
}   
  
.tipo1{   
padding: 6px;   
cursor: default;   
position: absolute;   
width: 165px;   
background-color: Menu;   
color: MenuText;   
border: 0 solid white;   
visibility: hidden;   
border: 2 outset ButtonHighlight;   
}   
  
a.menuitem {font-size: 0.8em; font-family: Arial, Serif; text-decoration: none;}   
a.menuitem:link {color: #000000; }   
a.menuitem:hover {color: #FFFFFF; background: #0A246A;}   
a.menuitem:visited {color: #868686;} 

</style>
<script language="javascript">

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

var ns4 = (document.layers)? true:false  
var ie = (document.all)? true:false  
var ns6 = (document.getElementById && !document.all) ? true: false;  
var coorX, coorY;  
if (ns6) document.addEventListener("mousedown", coord, true)  
if (ns4) {document.captureEvents(Event.MOUSEDOWN); document.mousedown = coord;}  
if (ie) document.onmousedown = coord 

function coord(e) 
{ 
   if (ns4||ns6)    {  
      coorX = e.pageX;  
      coorY = e.pageY;  
   }  
   if (ie)    {  
      coorX = event.x;  
      coorY = event.y;  
   }  
   if (document.layers && !document.getElementById){  
     if (e.which == 2 || e.which == 3){  
      mostrar() 
      return false;  
     }  
    }  

   return true; 
} 



   
//Menú contextual con botón derecho by Mauricio Alejandro   
//Actualizado por El Codigo para soporte multinavegador (01⁄11⁄2005)   

//Este script y otros muchos pueden   
//descarse on-line de forma gratuita   
//en El Código: www.elcodigo.com   
  
//pocicion absoluta del menu=0, menu con el boton derecho=1   
var menutipo = 1   
  
//muestra el menu   
function sombra(e){   
  
   if (document.getElementById) {   
      mimenu = document.getElementById("cepilomenu")   
   }else if (document.all) {   
      mimenu = document.all.cepilomenu   
   }   
       
   /*La gestion de eventos con IE4 e IE5 utiliza el objeto window.event, que no forma  
   parte de DOM2. IE5 soporta getElementById, pero sigue usando este objeto para la  
   gestion de eventos, por lo que hay que tratarle de forma exclusiva */ 
   if (!e) var e = window.event  
       
   //distancia a bordes   
   var borde_derecho = document.body.clientWidth - e.clientX  
   var borde_inferior = document.body.clientHeight - e.clientY  
  
   //distancia del menu al puntero   
   if (borde_derecho < mimenu.offsetWidth)   
      mimenu.style.left = document.body.scrollLeft + e.clientX - cepilomenu.offsetWidth + 'px'  
   else  
      mimenu.style.left = document.body.scrollLeft + e.clientX + 'px'  
  
   //pocicion vertical   
   if (borde_inferior < mimenu.offsetHeight)   
      mimenu.style.top = document.body.scrollTop + e.clientY - cepilomenu.offsetHeight  
   else  
      mimenu.style.top = document.body.scrollTop + e.clientY  
  
   mimenu.style.visibility = "visible"  
      
   return false   
}   
  
function visibilidad(){   
  
   if (document.getElementById) {   
      mimenu = document.getElementById("cepilomenu")   
   }else if (document.all) {   
      mimenu = document.all.cepilomenu   
   }   
  
   mimenu.style.visibility = "hidden" 
   document.oncontextmenu = "e";
  
}   

document.onclick = visibilidad
  


//---------------------------------------------------------------------------------------------------------
function openmenuper(i,j,k) {
var ks = k+1;
var contMenu = "";
if (j <= 1) {
contMenu += "<a onclick=\"insertnivel('"+i+"',1,"+ks+");\" class=\"niv_menu\">Crear Fondo/Sub-Fondo<br></a>";
}
if (j <= 2) {
contMenu += "<a onclick=\"insertnivel('"+i+"',2,"+ks+");\" class=\"niv_menu\">Crear Sección/sub-secc.<br></a>";
}
if (j <= 3) {
contMenu += "<a onclick=\"insertnivel('"+i+"',3,"+ks+");\" class=\"niv_menu\">Crear Serie/Sub-serie.<br></a>";
}
if (j <= 3) {
contMenu += "<a onclick=\"insertnivel('"+i+"',4,"+ks+");\" class=\"niv_menu\">Crear Agrupación Doc.<br></a>";
}
contMenu += "<hr>";
if (j >=1) {
if (j <5) {	
contMenu += "<a onclick=\"insertnivel('"+i+"',5,"+ks+");\" class=\"niv_menu\">Doc. Compuestos<br></a>";
}
if (j <8) {	
contMenu += "<a onclick=\"insertnivel('"+i+"',8,"+ks+");\" class=\"niv_menu\">Doc. Visuales<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',9,"+ks+");\" class=\"niv_menu\">Doc. Audiovisuales<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',10,"+ks+");\" class=\"niv_menu\">Doc. Sonoros<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',11,"+ks+");\" class=\"niv_menu\">Doc. Textuales<br></a>";
contMenu += "<hr>";
}
}
if (j == 8 || j == 9 || j == 10 || j == 11) {	
contMenu += "<a onclick=\"conservacion('"+i+"',12,"+ks+");\" class=\"niv_menu\">Conservacion<br></a>";
contMenu += "<hr>";
}
contMenu += "<a onclick=\"eliminar('"+i+"',6,"+ks+");\" class=\"niv_menu\">Eliminar<br></a>";

document.getElementById("cepilomenu").innerHTML = contMenu;
document.oncontextmenu = sombra;

}

//-----------------------------------------------------------------------------------------------------------

function conservacion() {
}

function eliminar() {
	
}

//-------------------------------------------------------------------------------------------

function insertnivel(i,j,k) {
	
	//cs: conigo del nivel superior
	//cn: codigo del nivel que se esta ingresando
	//--- con los dos anteriores se forma el codigo de referencia del nivel
	//ci: codigo de la institucion a la que pertenece
	//tn: tipo de nivel que se esta ingresando
	
	var contMenu = "";
	contMenu += "	<table width=\"120\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	contMenu += "  <tr>";
	contMenu += "    <td class=\"niv_menu\">Código del nivel</td>";
	contMenu += "  </tr>";
	contMenu += "  <tr>";
	contMenu += "    <td height=\"5\"></td>";
	contMenu += "  </tr>";
	contMenu += "  <tr>";
	contMenu += "  <td>";
	contMenu += "<input type=\"text\" onclick=\"insertnivel('"+i+"',"+j+","+k+")\" name=\"codigonivel\" id=\"codigonivel\" class=\"niv_campochico\" onkeyup=\"iden(this.value)\" />";
	contMenu += "<input name=\"button\" type=\"button\" class=\"niv_botchico\" id=\"button\" value=\"++\" onclick=\"enviarDatos('"+i+"','"+j+"','"+k+"');\" />";
contMenu += "  </td>";
contMenu += "  </tr>";
contMenu += "</table>";
	document.getElementById("cepilomenu").innerHTML = contMenu;
	document.oncontextmenu = sombra;
	document.getElementById("codigonivel").focus();
	document.getElementById("codigonivel").select();

}

function enviarDatos(i,j,k) {
	relocate('<?php echo $editFormAction; ?>',{'cs':i,'cn':document.getElementById("codigonivel").value,'ci':'<?php echo $_GET['cod']; ?>','tn':j,'tv':k});
}
 
 
function pasCant() {
	window.parent.document.getElementById("fondos_agrupaciones").value = <?php echo $totalRows_vis_nivelesVert;  ?>;
}


function redirect(a,b) {
	//document.location.target= '_blank';
	
	if(b==1) {
	window.parent.document.location.href = 'niveles_update.php?cod='+a ;
	} else if(b>=8 && b<=11) {
	window.parent.document.location.href = 'diplomaticos_update.php?cod='+a ;	
	}
}

</script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="pasCant();">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="47"><a onMouseDown="openmenuper('<?php echo $row_vis_instituciones['codigo_identificacion']; ?>',0,0);"><img src="images/c0.png" alt="<?php echo $row_vis_instituciones['codigo_identificacion']; ?> - <?php echo $row_vis_instituciones['formas_conocidas_nombre']; ?>" width="47" height="57" border="0"></a></td>
  </tr>
  <?php do { ?>
      <tr>
      <?php
	  $i=0;
	  $j=$row_vis_nivelesVert['tv'];
	  while($i < $j) {
		if($j == ($i+1)) {  
		echo "<td width=\"47\"><img src=\"images/t3.jpg\" width=\"47\" height=\"58\"></td>"; 
		} else {
		echo "<td width=\"47\"><!-- <img src=\"images/t2.jpg\" width=\"47\" height=\"58\">--></td>"; 	
		}
		$i++; 
	  }
	   ?>
      <td width="47"><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td align="center"><a <?php if ($row_vis_nivelesVert['tipo_nivel'] !=5) { ?>onClick="redirect('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>','<?php echo $row_vis_nivelesVert['tipo_diplomatico']; ?>');" <?php } ?> onMouseDown="openmenuper('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>',<?php echo $row_vis_nivelesVert['tipo_nivel']; ?>, <?php echo $row_vis_nivelesVert['tv']; ?>);"><img src="images/c<?php echo $row_vis_nivelesVert['tipo_nivel']; ?>.jpg" alt="<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>" width="47" height="57" border="0"></a></td></tr><tr><td  class="nivNombre"><?php $v = explode("/" , $row_vis_nivelesVert['codigo_referencia']);

echo $v[(count(explode("/" , $row_vis_nivelesVert['codigo_referencia']))) - 1]; ?></td></tr></table></td>
    </tr>
    <?php } while ($row_vis_nivelesVert = mysql_fetch_assoc($vis_nivelesVert)); ?>
</table>
<!-- Capa que construye el menu -->  
<div id="cepilomenu">  
</div>  
  
<!-- Inicializacion de estilos -->  
<script type="text/javascript" language="JavaScript">  
   if (document.getElementById) {   
     if (menutipo == 0)   
       document.getElementById("cepilomenu").className = "tipo0"  
     else   
       document.getElementById("cepilomenu").className = "tipo1"  
   } else if (document.all) {   
     if (menutipo == 0)   
       document.all.cepilomenu.className = "tipo0"  
     else   
       document.all.cepilomenu.className = "tipo1"  
   }    
</script>  
</body>
</html>
<?php
mysql_free_result($vis_instituciones);

mysql_free_result($vis_nivelesVert);
?>
