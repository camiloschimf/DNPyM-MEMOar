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
<html >
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
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
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

window.onload = function() {   
  var Tam = TamVentana();
  document.getElementById('frizquierda').style.height = Tam[1]-60;
  document.getElementById('frderecha').style.height = Tam[1]-60;
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');    
};

window.onresize = function() {   
  var Tam = TamVentana();
  document.getElementById('frizquierda').style.height = Tam[1]-60;
  document.getElementById('frderecha').style.height = Tam[1]-60;  
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');   
};   


</script>
<?php

$sit="";
if(isset($_GET['sit']) && $_GET['sit'] != "" && $_GET['sit'] != "Robado" && $_GET['sit'] != "No Localizado") {
	$sit="&sit=".$_GET['sit'];
}

?>
<body>
<table width="1334" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="702" height="30" align="center" background="images/angulo_01_b.jpg" class="titulosSecciones">FICHAS DE REGISTRO DE DIPLOMATICOS</td>
    <td>&nbsp;</td>
    <td width="617" background="images/angulo_02_b.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" style="background:url(images/angulo_05.jpg);"><iframe width="680" src="ficha_registro_menu.php" frameborder="0" name="frizquierda" id="frizquierda" height="<?php echo $_SESSION['MM_Height']-60; ?>"></iframe></td>
    <td>&nbsp;</td>
    <td align="center" valign="top" style="background:url(images/angulo_06.jpg);"><iframe width="600"  height="<?php echo $_SESSION['MM_Height']-60; ?>" frameborder="0" name="frderecha" id="frderecha" src=""></iframe></td>
  </tr>
  <tr>
    <td height="27" background="images/angulo_03_b.jpg">&nbsp;</td>
    <td>&nbsp;</td>
    <td background="images/angulo_04_b.jpg">&nbsp;</td>
  </tr>
</table>
</body>
</html>