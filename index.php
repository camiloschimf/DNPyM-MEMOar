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
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MEMORar - Dirección Nacional de Patrimonio y Museos</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
<script type='text/javascript' src='gyl_menu.js'></script>
<script type='text/javascript'> 
var empezar = false
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
var capas = new Array("e1")
var retardo 
var ocultar

function ct() {
	document.getElementById('e1').style.visibility='hidden';
	document.getElementById('e2').style.visibility='hidden';
	document.getElementById('e3').style.visibility='hidden';
	document.getElementById('e4').style.visibility='hidden';
	document.getElementById('e5').style.visibility='hidden';
	document.getElementById('e6').style.visibility='hidden';
	document.getElementById('e7').style.visibility='hidden';
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
</head>
<script language="javascript">
function TamVentana() {   
  var Tamanyo = [0, 0];   
  if (typeof window.innerWidth != 'undefined')   
  {   
    Tamanyo = [   
        window.innerWidth,   
        window.innerHeight   
    ];   
  }   
  else if (typeof document.documentElement != 'undefined'   
      && typeof document.documentElement.clientWidth !=   
      'undefined' && document.documentElement.clientWidth != 0)   
  {   
 Tamanyo = [   
        document.documentElement.clientWidth,   
        document.documentElement.clientHeight   
    ];   
  }   
  else   {   
    Tamanyo = [   
        document.getElementsByTagName('body')[0].clientWidth,   
        document.getElementsByTagName('body')[0].clientHeight   
    ];   
  }   
  return Tamanyo;   
}


function tamaniar() {
	var Tam = TamVentana();
  	document.getElementById('frcentral').style.height = Tam[1]-130; 
}

function zoomf() {
	var Tam = TamVentana();
	var a = parseInt(Tam[0]);
	if(a >= 1334) {
		a = 1334;
	} 
	 var ap = a * 100 / 1334;
	 var t = ap / 100;
	 document.body.style.zoom = t;
 }
 
function zoomin(){
	if(document.body.style.zoom!=0) {
		document.body.style.zoom*=1.2; 
	} else {
		document.body.style.zoom=1.2;
	}
}

function zoomout(){
	if(document.body.style.zoom!=0) {
		document.body.style.zoom*=0.833; 
	} else {
		document.body.style.zoom=0.833;
	}
	alert(document.body.style.zoom);
}

function zoomrest(){
	if(document.body.style.zoom!=0) {
		document.body.style.zoom*=0; 
	} else {
		document.body.style.zoom=0;
	}
}
 

 window.onload = function() { 
	zoomf(); 	
	var Tam = TamVentana();
	document.getElementById('frcentral').style.height = Tam[1]-130; 
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');    
}; 

window.onresize = function() {  
	//zoomf();<!-- Codes by Quackit.com -->
	//location.reload(true);
	var Tam = TamVentana();
	document.getElementById('frcentral').style.height = Tam[1]-130;   
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');   
};

</script>
<body scroll="no">
<table width="1334" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="1334" height="57" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="453" height="57" style="background-image:url(images/encabezado_01.jpg)"><table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="40">&nbsp;</td>
          </tr>
          <tr>
            <td  style="color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:10px;">Bienvenido: <?php echo $_SESSION['MM_Nombre']; ?> <?php echo $_SESSION['MM_Apellido']; ?></td>
          </tr>
        </table></td>
        <td width="870" style="background-image:url(images/encabezado_03.jpg)"><?php require_once('menusup.php'); ?></td>
        <td width="11" valign="top"><img src="images/encabezado_02.jpg" width="11" height="47"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><iframe frameborder="0" width="1334" height="<?php echo $_SESSION['MM_Height']; ?>" id="frcentral" name="frcentral" scrolling="no" src="fr_inicio.php"></iframe></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><table width="1334" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="11" valign="bottom"><img src="images/pie_02.jpg" width="11" height="47"></td>
        <td width="871" valign="bottom" style="background-image:url(images/pie_01.jpg)"><iframe style="height:45px; width:200px;" name="frameestados" id="frameestados" frameborder="0" src="inicio_home.php"></iframe></td>
        <td width="452"><img src="images/pie_03.jpg" width="452" height="57"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>