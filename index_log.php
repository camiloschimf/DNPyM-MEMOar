<?php require_once('Connections/conn.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['usuario'])) {
  $loginUsername=$_POST['usuario'];
  $password=md5($_POST['pass']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "index_error.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_conn, $conn);
  
  $LoginRS__query=sprintf("SELECT logusuario as usuario, pass, nombre, apellido FROM vis_permisos WHERE logusuario=%s AND pass=%s AND usuarioactivo=1",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $row_LoginRS = mysql_fetch_assoc($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	//if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
	$_SESSION['MM_Height'] = $_POST['c_height'];
    $_SESSION['MM_Username'] = $loginUsername;
	$_SESSION['MM_usuario'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Memorar'] = "1.1";
	$_SESSION['MM_Nombre'] = $row_LoginRS['nombre'];	     
	$_SESSION['MM_Apellido'] = $row_LoginRS['apellido'];
	$_SESSION['MM_idrol'] = "0";
	
	//--datos opcionales:
	$_SESSION['MM_instituciones'] = "";
	$ap=0;
	
	//actualizamos ultima entrada del usuarios
	$updateUserSQL = "UPDATE usuarios SET fecha_ultimo_acceso=CURRENT_TIMESTAMP WHERE usuario='$loginUsername' AND pass=".GetSQLValueString($password, "text");
	mysql_select_db($database_conn, $conn);
	$Result5= mysql_query($updateUserSQL, $conn) or die(mysql_error());
	
	$ControlAccesoSQL = "INSERT INTO usuarios_ingresos (usuario) VALUES (".GetSQLValueString($loginUsername, "text").")";
	mysql_select_db($database_conn, $conn);
	$Result6= mysql_query($ControlAccesoSQL, $conn) or die(mysql_error());
	
	mysql_select_db($database_conn, $conn);
	$query_vis_premisos = "SELECT * FROM vis_permisos WHERE logusuario='".$loginUsername."' ORDER BY codigo_identificacion ASC";
	$vis_premisos = mysql_query($query_vis_premisos, $conn) or die(mysql_error());
	$row_vis_premisos = mysql_fetch_assoc($vis_premisos);
	$totalRows_vis_premisos = mysql_num_rows($vis_premisos);
	
	$i_tmp="AND (";
	$i_tmp2="AND (";
	$i_tmp3="AND (";
	do {
	$i_tmp.="codigo_institucion='".$row_vis_premisos['codigo_identificacion']."' OR ";
	$i_tmp2.="codigo_identificacion='".$row_vis_premisos['codigo_identificacion']."' OR ";
	$i_tmp3.="codigo_referencia LIKE '".$row_vis_premisos['codigo_identificacion']."%' OR ";		
		if($row_vis_premisos['idrol'] == 1 || $row_vis_premisos['idrol'] == 2) {
			$ap=1;
			if($row_vis_premisos['idrol'] == 1) {
				$_SESSION['MM_idrol'] = "1";
			}
			if($row_vis_premisos['idrol'] == 2 && $_SESSION['MM_idrol'] != "1") {
				$_SESSION['MM_idrol'] = "2";
			}
		}
		if($row_vis_premisos['idrol'] == 3 && $_SESSION['MM_idrol'] != "1" && $_SESSION['MM_idrol'] != "2") {
			$_SESSION['MM_idrol'] = "3";
		}
	} while ($row_vis_premisos = mysql_fetch_assoc($vis_premisos));
	$i_tmp.="codigo_institucion='0')";	 
	$i_tmp2.="codigo_identificacion='0')";
	$i_tmp3.="codigo_referencia='0')";
	
	$_SESSION['MM_instituciones'] = $i_tmp;
	$_SESSION['MM_instituciones_norm'] = $i_tmp2;
	$_SESSION['MM_instituciones_niv'] = $i_tmp3;
	
	
	
	if($ap == 1 || $ap == 2) {
	$_SESSION['MM_instituciones'] = "";	
	$_SESSION['MM_instituciones_norm'] = "";
	$_SESSION['MM_instituciones_niv'] = "";
	}
	

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
	document.getElementById('c_height').value = Tam[1]-130;  
}

 window.onload = function() {   
  var Tam = TamVentana();
  document.getElementById('frcentral').style.height = Tam[1]-130;
  document.getElementById('c_height').value = Tam[1]-130;  
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');    
}; 

window.onresize = function() {   
  var Tam = TamVentana();
  document.getElementById('frcentral').style.height = Tam[1]-130;
  document.getElementById('c_height').value = Tam[1]-130;    
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');   
};
</script>
<body>
<table width="1334" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="1334" height="57" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="453"><img src="images/encabezado_01.jpg" width="453" height="57"></td>
        <td width="870" style="background-image:url(images/encabezado_03.jpg)"></td>
        <td width="11" valign="top"><img src="images/encabezado_02.jpg" width="11" height="47"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><div id="frcentral">
      <table width="1334" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30" background="images/angulo_08_b.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td style="background:url(images/angulo_12.jpg)"><form name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
            <table width="300" border="1" align="center" cellpadding="5" cellspacing="0">
              <tr>
                <td style="border-color:#a99e88; border-style:solid; border-width:medium;"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="textoCampo"><strong>Usuario:</strong></td>
                  </tr>
                  <tr>
                    <td height="6"></td>
                  </tr>
                  <tr>
                    <td><input class="estiloCampos" type="text" name="usuario" id="usuario"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;<?php //echo md5("admin"); ?></td>
                  </tr>
                  <tr>
                    <td  class="textoCampo"><strong>Contraseña:</strong></td>
                  </tr>
                  <tr>
                    <td height="6"></td>
                  </tr>
                  <tr>
                    <td><input class="estiloCampos" type="password" name="pass" id="pass"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;<?php //echo md5("camilo1"); ?>
                      <input type="hidden" name="c_height" id="c_height"></td>
                  </tr>
                  <tr>
                    <td><input type="submit" name="button" id="button" value="Ingresar" style="width:250px;"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="textoCampo"><strong><!--Si no recuerda su contraseña ingrese <a href="index_relog.php">aquí --></a></strong></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </form></td>
        </tr>
        <tr>
          <td height="30" background="images/angulo_11_b.jpg">&nbsp;</td>
        </tr>
    </table></div></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><table width="1334" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="11" valign="bottom"><img src="images/pie_02.jpg" width="11" height="47"></td>
        <td width="871" valign="bottom" style="background-image:url(images/pie_01.jpg)">&nbsp;</td>
        <td width="452"><img src="images/pie_03.jpg" width="452" height="57"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>