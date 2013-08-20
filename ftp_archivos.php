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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1258" />
<title>Documento sin t&iacute;tulo</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>
<script language="javascript">
	function cargar() {
		if(document.getElementById('archivoselect').value != "" && document.getElementById('fecha_toma_archivoa').value != "") {
		window.parent.document.getElementById('cargando').style.display='block';
		window.parent.document.getElementById('nuevoarchivo').style.display='none';
		}
	}
</script>
<body>
<form action="ftp_archivos_sp.php" method="POST" enctype="multipart/form-data" name="form1" target="frmftp">
<table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
    	            <tr>
    	              <td colspan="2" class="ins_separadormayor"></td>
  	              </tr>
    	            <tr>
    	              <td width="69" class="ins_tituloscampos">&nbsp;&nbsp;Archivo:</td>
    	              <td width="441" class="ins_tituloscampos"><input type="file" name="archivoselect" id="archivoselect" style="width:400px;">
    	                <label for="archivoselect"></label>
    	                <input name="codigo_referencia" type="hidden" id="codigo_referencia" value="<?php echo $_GET['cod']; ?>">
    	                <input name="tipo_documento" type="hidden" id="tipo_documento" value="<?php echo $_GET['tipo_diplomatico']; ?>"></td>
  	              </tr>
    	            <tr>
    	              <td colspan="2" class="ins_separadormayor"></td>
  	              </tr>
    	            <tr>
    	              <td class="ins_tituloscampos">&nbsp;&nbsp;Fecha:</td>
    	              <td class="ins_tituloscampos tituloscampos">D&iacute;a:
                          <input name="fecha_toma_archivoa" type="text" id="fecha_toma_archivoa" size="4" maxlength="2" >
Mes:
<input name="fecha_toma_archivom" type="text" id="textfield2" size="4" maxlength="2" >
A&ntilde;o:
<input name="fecha_toma_archivoa" type="text" id="fecha_toma_archivoa" size="8" maxlength="4" >
(dd/mm/aaaa)    	                <input onClick="cargar();" type="submit" name="button10" id="button10" value="Subir" ></td>
  	                </tr>
    	            <tr>
    	              <td colspan="2" class="ins_separadormayor"></td>
  	              </tr>
</table>
</form>
</body>
</html>