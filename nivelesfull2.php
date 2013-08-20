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

function deleteAux($str,$database_conn,$conn) {

		$deleteSQL_D1 = $str;
 	 	mysql_select_db($database_conn, $conn);
  		$Result_SQL_D1 = mysql_query($deleteSQL_D1, $conn) or die(mysql_error());	
}


//eliminamos fisicamente estructuras
if(isset($_POST['eliminar']) && $_POST['eliminar'] == "si") {
	
	mysql_select_db($database_conn, $conn);
$query_vis_nivelesElim = "SELECT * FROM vis_estados_niveles WHERE codigo_referencia LIKE '".$_POST['code']."%' AND estado <> 'Inicio' AND estado <> 'Pendiente' AND estado <> 'Completo' AND tipo <> 5  ORDER BY codigo_referencia ASC";
$vis_nivelesElim = mysql_query($query_vis_nivelesElim, $conn) or die(mysql_error());
$row_vis_nivelesElim = mysql_fetch_assoc($vis_nivelesElim);
$totalRows_vis_nivelesElim = mysql_num_rows($vis_nivelesElim);
	

		
	
//liminamos areas de documentos		
	
	//eliminamos DOCUMENTOS
	$tablasDocumentos = array("rel_documentos_contenedores", "rel_documentos_edificios", "palabras_claves","rel_documentos_idiomas","rel_documentos_rubros_autores","rel_documentos_envases","rel_documentos_descriptores_materias_contenidos","rel_documentos_descriptores_onomasticos","rel_documentos_descriptores_geograficos","rel_documentos_exposiciones","documentos_areasnotas","documentos_estados","archivos_digitales","documentos_no_localizados","gestion_conservacion","documentos");
	
	foreach ($tablasDocumentos as $value) {
		
		
		$deleteSQL = "DELETE FROM ".$value." WHERE codigo_referencia LIKE '".$_POST['code']."%' ";
		deleteAux($deleteSQL, $database_conn, $conn);
	}
	
	//eliminamos NIVELES
	
	$tablasNiveles = array("niveles_inventario","rel_niveles_edificios","rel_niveles_contenedores","rel_niveles_productores","rel_niveles_soportes","rel_niveles_idiomas","rel_niveles_niveles","rel_niveles_institucionesinternas","rel_niveles_institucionesexternas","rel_niveles_sistemasorganizacion","niveles_tasacion_expertizaje","niveles_areasnotas","niveles_estados","niveles");
	
	foreach ($tablasNiveles as $value) {
		if($value == "rel_niveles_niveles") {
			$campo="codigo_referencia1";
		} else {
			$campo="codigo_referencia";
		}
		$deleteSQL = "DELETE FROM ".$value." WHERE ".$campo." LIKE '".$_POST['code']."%' ";
		deleteAux($deleteSQL, $database_conn, $conn);
	}

	//eliminamos INSTITUCIONES
	
	$tablasNiveles = array("direcciones_instituciones","responsable_institucion","responsable_archivo","formas_autorizadas_nombre","rel_instituciones_serviciosdereproduccion","area_de_notas_instituciones","instituciones_estados");
	
	foreach ($tablasNiveles as $value) {
		
		if($value == "formas_autorizadas_nombre") {
			$campo="codigo_identificacion";
		} else if ($value == "instituciones_estados") {
			$campo="codigo_referencia";
		} else {
			$campo="codigo_institucion";
		}
		
		$deleteSQL = "DELETE FROM ".$value." WHERE ".$campo." LIKE '".$_POST['code']."%' ";
		deleteAux($deleteSQL, $database_conn, $conn);
	}
	
	 $deleteSQL_D1 = "DELETE FROM instituciones WHERE codigo_identificacion LIKE '".$_POST['code']."%' ";
 	 mysql_select_db($database_conn, $conn);
  	$Result_SQL_D1 = mysql_query($deleteSQL_D1, $conn) or die(mysql_error());
	
	mysql_free_result($vis_nivelesElim);
	
	if(isset($_GET['pascant'])) { $r="&pascant=s"; } else { $r=""; }
  	$updateGoTo = "nivelesfull2.php?cod=".$_GET['cod'].$r;
  	header(sprintf("Location: %s", $updateGoTo));	
		
	
}

/*
echo "cr: ".$_POST['cs']."/".$_POST['cn']."<br>";
echo "tn: ".$_POST['tn']."<br>";
echo "cs: ".$_POST['cs']."<br>";
echo "ci: ".$_POST['ci']."<br>";
echo "ci: ".$_POST['tv']."<br>";
*/

$usuario = $_SESSION['MM_Username'];
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
  
  $estadoT = "Inicio";
  if($_POST['tn'] == 5) {
  $estadoT = "Vigente";  
  }
  
  $insertSQL2 = sprintf("INSERT INTO niveles_estados (codigo_referencia, estado, usuario) VALUES (%s, %s, %s)",
  				GetSQLValueString($_POST['cs']."/".$_POST['cn'], "text"),
                GetSQLValueString($estadoT, "text"),
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


	$r="";
	if(isset($_GET['pascant'])) {
		$r="&pascant=s";
	}
	
  
  $updateGoTo = "nivelesfull2.php?cod=".$_GET['cod'].$r."#".$_POST['cs']."/".$_POST['cn'];
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


$were1 = "";
$were2 = "";
$were3 = "";

if(isset($_GET['cod']) && isset($_GET['pascant'])) {
$were1 = " AND codigo_institucion='".$_GET['cod']."' ";
}


mysql_select_db($database_conn, $conn);
/*$query_vis_nivelesVert = "(SELECT 
codigo_identificacion AS codigo_referencia,
 '0' AS tipo_nivel, 
 formas_conocidas_nombre AS nombre,
 instituciones_estados.estado AS estado,
 max(instituciones_estados.fecha) as fecha,
 '0' as tv,
 '0' AS tipo_diplomatico,
codigo_identificacion  AS  codigo_institucion,
'Local' AS situacion
 FROM instituciones 
 INNER JOIN instituciones_estados ON (instituciones.codigo_identificacion=instituciones_estados.codigo_referencia)
 WHERE 0=0 ".$were1."
 GROUP BY 
 codigo_identificacion,
 formas_conocidas_nombre 
 ) UNION (
 SELECT 
 niveles.codigo_referencia, 
 tipo_nivel, 
 titulo_original AS nombre,
 niveles_estados.estado AS estado,
 max(niveles_estados.fecha) as fecha,
 tv,
 '1' AS tipo_diplomatico,
 codigo_institucion,
 'Local' AS situacion
 FROM niveles 
 INNER JOIN niveles_estados ON (niveles.codigo_referencia=niveles_estados.codigo_referencia)
 WHERE 0=0 ".$were2."
 GROUP BY 
 niveles.codigo_referencia,
 tipo_nivel,
 titulo_original
 ) UNION (
 SELECT 
 documentos.codigo_referencia, 
 tipo_diplomatico , 
 titulo_original AS nombre,
 documentos_estados.estado AS estado,
 max(documentos_estados.fecha) as fecha,
 tv,
 tipo_diplomatico,
 codigo_institucion,
 situacion
 FROM documentos
 INNER JOIN documentos_estados ON (documentos.codigo_referencia=documentos_estados.codigo_referencia)
 WHERE 0=0 ".$were3."
 GROUP BY 
 documentos.codigo_referencia,
 tipo_diplomatico,
 titulo_original
 ) 
 ORDER BY codigo_referencia ASC";*/
 
 $query_vis_nivelesVert = "SELECT * FROM vis_estados_niveles WHERE 0=0 ".$were1.$_SESSION['MM_instituciones']." ORDER BY codigo_referencia ASC";
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

mysql_select_db($database_conn, $conn);
$query_norma_legal = "SELECT * FROM norma_legal ORDER BY norma_legal ASC";
$norma_legal = mysql_query($query_norma_legal, $conn) or die(mysql_error());
$row_norma_legal = mysql_fetch_assoc($norma_legal);
$totalRows_norma_legal = mysql_num_rows($norma_legal);


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
//Actualizado por El Codigo para soporte multinavegador (01/11/2005)   

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
function openmenuper(i,j,k,s,t,u,v) {
var e = i;	
	//t=estado
	//u=situacion
var ks = k+1;
var contMenu = "";
if (j <= 1) {
contMenu += "<a onclick=\"insertnivel('"+i+"',1,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Fondo/Sub-Fondo<br></a>";
}
if (j <= 2) {
contMenu += "<a onclick=\"insertnivel('"+i+"',2,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Sección/sub-secc.<br></a>";
}
if (j <= 3) {
contMenu += "<a onclick=\"insertnivel('"+i+"',3,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Serie/Sub-serie.<br></a>";
}
if (j <= 3) {
contMenu += "<a onclick=\"insertnivel('"+i+"',4,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Crear Agrupación Doc.<br></a>";
}
contMenu += "<hr>";
if (j >=1) {
if (j <5) {	
contMenu += "<a onclick=\"insertnivel('"+i+"',5,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Compuestos<br></a>";
}
if (j <8) {	
if(j == 5) {
	var lt= i.split("/");
	var lk="";
	for(h=0; h<lt.length-1; h++) {
		lk += lt[h];
		if(h<lt.length-2) {
		lk += "/";
		}
	}
	i=lk;
	//alert(i); 
}
contMenu += "<a onclick=\"insertnivel('"+i+"',8,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Visuales<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',9,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Audiovisuales<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',10,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Sonoros<br></a>";
contMenu += "<a onclick=\"insertnivel('"+i+"',11,"+ks+",'"+s+"','"+v+"');\" class=\"niv_menu\">Doc. Textuales<br></a>";
contMenu += "<hr>";
}
}
//if (j == 8 || j == 9 || j == 10 || j == 11) {	
if (t=='Vigente' && (u=='Local' || u.substr(0,8)=='Conserva') && (j == 8 || j == 9 || j == 10 || j == 11)) {	
contMenu += "<a onclick=\"conservacion('"+i+"');\" class=\"niv_menu\">Conservacion<br></a>";
}
if (t=='Vigente' && (u=='Local' || u.substr(0,8)=='Exposici') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<a onclick=\"exposicion('"+i+"');\" class=\"niv_menu\">Exposición<br></a>";
}
if (t=='Vigente' && (u=='Local' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<a onclick=\"prestamos('"+i+"');\" class=\"niv_menu\">Prestamos<br></a>";
}
if (t=='Vigente' && (u=='Local' || u=='Robado' || u.substr(0,8)=='Conserva' || u.substr(0,8)=='Exposici' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<a onclick=\"robo('"+i+"','"+u+"');\" class=\"niv_menu\">Robo<br></a>";
}
if (t=='Vigente' && (u=='Local' || u=='No Localizado' || u.substr(0,8)=='Conserva' || u.substr(0,8)=='Exposici' || u=='Prestamo') && (j == 8 || j == 9 || j == 10 || j == 11)) {
contMenu += "<a onclick=\"nolocalizado('"+i+"','"+u+"');\" class=\"niv_menu\">No Localizado<br></a>";
}
contMenu += "<hr>";
if (t!='Cancelado' && u=='Local'){
contMenu += "<a onclick=\"eliminar('"+e+"',6,"+ks+",'"+s+"');\" class=\"niv_menu\">Eliminar</a><br>";
}

document.getElementById("cepilomenu").innerHTML = contMenu;
document.oncontextmenu = sombra;

}

//-----------------------------------------------------------------------------------------------------------

function conservacion(i) {
	window.parent.document.location='fr_conservacion.php?cod='+i;	
}

function exposicion(i) {
	window.parent.document.location='fr_exposicion.php?cod='+i;
}

function robo(i,u) {
	window.parent.document.location='fr_robo.php?cod='+i+'&sit='+u;
}

function nolocalizado(i,u) {
	window.parent.document.location='fr_nolocalizado.php?cod='+i+'&sit='+u;
}

function prestamos(i) {
	window.parent.document.location='fr_prestamos.php?cod='+i;
}



function eliminar(i) {
	relocate('nivelesfull2.php?cod=<?php echo $_GET['cod']; ?>&<?php if(isset($_GET['pascant'])) { echo "pascant=s"; } ?>',{'eliminar':'si','code':i});
}

//-------------------------------------------------------------------------------------------

function insertnivel(i,j,k,s,v) {
	
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
	contMenu += "<input type=\"text\" value=\""+v+"\" onclick=\"insertnivel('"+i+"',"+j+","+k+")\" name=\"codigonivel\" id=\"codigonivel\" class=\"niv_campochico\" onkeyup=\"iden(this.value)\" />";
	contMenu += "<input name=\"button\" type=\"button\" class=\"niv_botchico\" id=\"button\" value=\"++\" onclick=\"enviarDatos('"+i+"','"+j+"','"+k+"','"+s+"');\" />";
contMenu += "  </td>";
contMenu += "  </tr>";
contMenu += "</table>";
	document.getElementById("cepilomenu").innerHTML = contMenu;
	document.oncontextmenu = sombra;
	document.getElementById("codigonivel").focus();
	document.getElementById("codigonivel").select();
	if(v!="") {
		document.getElementById("codigonivel").disabled = true;
	}

}

function enviarDatos(i,j,k,s) {
	relocate('nivelesfull2.php?cod=<?php echo $_GET['cod']; ?>&<?php if(isset($_GET['pascant'])) { echo "pascant=s"; } ?>',{'cs':i,'cn':document.getElementById("codigonivel").value,'ci':s,'tn':j,'tv':k});
}
 
 
function pasCant() {
	<?php if(isset($_GET['pascant'])) { ?>
	window.parent.document.getElementById("fondos_agrupaciones").value = <?php echo $totalRows_vis_nivelesVert;  ?>;
	<?php } ?>
}


function redirect(a,b) {
	
	window.parent.parent.frameestados.location = 'nivdoc_estados.php?cod='+a ;
	//document.location.target= '_blank';
	if(b==0) {
	window.parent.document.location.href = 'fr_institucion.php?cod='+a ;	
	} else if(b==1) {
	window.parent.document.location.href = 'fr_nivel.php?cod='+a ;
	} else if(b>=8 && b<=11) {
	window.parent.document.location.href = 'fr_diplomaticos.php?cod='+a ;	
	}
	
}

function opencomentario() {
	 document.getElementById('comentarios').style.display="block";
 }
 
 

window.onload = function () {
	
	window.parent.parent.frameestados.location = 'inicio_home.php';
}

</script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="pasCant();">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="47"></td>
  </tr>
  <?php do { ?>
  <?php
  	//echo $row_vis_nivelesVert['tipo_nivel'];
	if($row_vis_nivelesVert['tipo_nivel'] == 5) {
		
		$codigo_individual = end(explode("/",$row_vis_nivelesVert['codigo_referencia']));
		
		mysql_select_db($database_conn, $conn);
		$query_ft = "SELECT * FROM documentos WHERE codigo_referencia LIKE '".$row_vis_nivelesVert['codigo_referencia']."%' ORDER BY codigo_referencia DESC LIMIT 1";
		$ft = mysql_query($query_ft, $conn) or die(mysql_error());
		$row_ft = mysql_fetch_assoc($ft);
		$totalRows_ft = mysql_num_rows($ft);
		
		$segmiento = explode("/",$row_ft['codigo_referencia']);
		$ultimo_segmento = end($segmiento);
		//--------------------------------------------
		$cardinal = explode("_",$ultimo_segmento);
		$cantidad_cardinal = count($cardinal);
		
		if($cantidad_cardinal >= 2) {
			$numero_cardinal = end($cardinal);
			$nuevo_numero_cardinal = $numero_cardinal+1;
			$nuevo_codigo = $codigo_individual."_".$nuevo_numero_cardinal;
		} else if($cantidad_cardinal == 1) {
			$numero_cardinal = 0;
			$nuevo_numero_cardinal = $numero_cardinal+1;
			$nuevo_codigo = $codigo_individual."_".$nuevo_numero_cardinal;
		} else {
			$nuevo_codigo = "";
		}
		
		$ft=$nuevo_codigo;
	} else {
		$ft="";
	}
  ?>
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
      <td width="47"><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td align="center"><?php //echo $ft; ?><a name="<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>" /><?php if($_GET['s'] != "C") { ?><a <?php if ($row_vis_nivelesVert['tipo_nivel'] !=5) { ?>onClick="redirect('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>','<?php echo $row_vis_nivelesVert['tipo_diplomatico']; ?>');" <?php } ?> onMouseDown="openmenuper('<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>',<?php echo $row_vis_nivelesVert['tipo_nivel']; ?>,<?php echo $row_vis_nivelesVert['tv']; ?>,'<?php echo $row_vis_nivelesVert['codigo_institucion']; ?>','<?php echo $row_vis_nivelesVert['estado']; ?>','<?php  echo $row_vis_nivelesVert['situacion'];  ?>','<?php echo $ft; ?>');"><?php } ?><img src="images/c<?php echo $row_vis_nivelesVert['tipo_nivel']; ?><?php if($row_vis_nivelesVert['situacion'] == "Baja") { echo "k"; } ?>.jpg" alt="<?php echo $row_vis_nivelesVert['codigo_referencia']; ?>" width="47" height="57" border="0"></a></td></tr><tr><td  class="nivNombre"><?php echo end(explode("/" , $row_vis_nivelesVert['codigo_referencia'])); ?></td></tr></table></td>
    </tr>
    <?php } while ($row_vis_nivelesVert = mysql_fetch_assoc($vis_nivelesVert)); ?>
</table>
<!-- Capa que construye el menu -->  
<div id="cepilomenu">  
</div>  
 <div id="cancelar" style="width:100%; height:100%; position:absolute; z-index:100; left: 0px; top: 0px; display:<?php if(isset($_GET['el']) && $_GET['el'] != "") { echo "block"; } else { echo "none"; } ?>"><form action="nivelesfull2.php" method="post" enctype="multipart/form-data" name="form1">
 	<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">DATOS DE LA BAJA</td>
    	          <td align="right">&nbsp;</td>
  	          </tr>
  	        </table></td>
            </tr>
            <tr>
    	      <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Norma Legal de Baja:</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor"><select name="normativa_legal_baja" id="normativa_legal_baja" class="camposanchos">
    	            <option value="" >Seleccione Opci&oacute;n</option>
    	            <?php
do {  
?>
    	            <option value="<?php echo $row_norma_legal['norma_legal']?>"><?php echo $row_norma_legal['norma_legal']?></option>
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
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>N&uacute;mero Norma Legal:</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><input name="numero_norma_legal" type="text" class="camposanchos" id="numero_norma_legal"  /></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Motivo de la Baja:</td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor"><textarea name="motivo_baja" rows="5" class="camposanchos" id="motivo_baja"></textarea></td>
  	          </tr>
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor" ><a  onMouseDown="openmenuper();"><img src="images/help2.png" width="18" height="18" align="absmiddle"></a>Fecha Baja:</td>
  	          </tr>
    	        <tr>
    	          <td  class="tituloscampos ins_celdacolor">D&iacute;a:
    	            <input name="fecha_bajad" type="text" id="fecha_bajad" size="8" maxlength="2">
    	            Mes:
    	            <input name="fecha_bajam" type="text" id="fecha_bajam" size="8" maxlength="2">
    	            A&ntilde;o:
    	            <input name="fecha_bajaa" type="text" id="fecha_bajaa" size="12" maxlength="4">
    	            (dd/mm/aaaa)
    	            
    	              <input name="code" type="hidden" id="code" value="<?php echo $_POST['code']; ?>">
  	              </td>
  	          </tr>
    	        <tr>
    	          <td >&nbsp;</td>
  	          </tr>
              </table></td>
      </tr>
             <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar" id="button" value="Grabar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
  	      </tr>
             <tr>
    	      <td class="celdapieazul"></td>
  	      </tr>
    </table></form>
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

mysql_free_result($norma_legal);

mysql_free_result($vis_nivelesVert);
?>
