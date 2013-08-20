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

if(isset($_POST['eliminar']) && $_POST['eliminar'] == "form3") {
	
	$insertSQL = sprintf("DELETE FROM archivos_digitales WHERE codigo_archivo=%s",
						GetSQLValueString($_POST['arc'], "int"));
	
	mysql_select_db($database_conn, $conn);
  	$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	if (file_exists("archivos/".$_POST['archivo'])){ 
		unlink("archivos/".$_POST['archivo']);
	}
	
	$insertGoTo = "documentos_archivos.php?cod=".$_POST['cod'];
 	header(sprintf("Location: %s", $insertGoTo));
	
	
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  
  //verificamos que venga el archivo final
  if($_FILES['imagenintro']['name'] != "" ) {
	  
	//datos del archivo que se va a insertar
	$nombre = $_FILES['imagenintro']['name'];
	$partes = explode(".", $nombre);
	$extencion = end($partes);
	
		
	//inicializamos el archivo en la base de datos
	if($_POST['fecha_toma_archivoa'] != "") {
	$insertSQL = sprintf("INSERT INTO archivos_digitales (codigo_referencia, fecha_toma_archivo, tipo, extension) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['codigo_referencia'], "text"),
                       GetSQLValueString(fechar($_POST['fecha_toma_archivod'],$_POST['fecha_toma_archivom'],$_POST['fecha_toma_archivoa']), "date"),
					   GetSQLValueString($_POST['tipo_documento'], "date"),
					   GetSQLValueString($extencion, "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	$idT2 = mysql_insert_id();
	} else {
	$msg2="No se ingreso la fecha del archivo.";
		 $idT2 = 0; 	
	}
	
	if($_POST['tipo_documento'] == "8" || $_POST['tipo_documento'] == "11") {

			if($extencion == "jpg" || $extencion == "JPG") {
				if(!move_uploaded_file($_FILES['imagenintro']['tmp_name'], "archivos/".md5($idT2).".jpg")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.jpg";
			}
		  
	  } else if ($_POST['tipo_documento'] == "9") {

			if($extencion == "flv" || $extencion == "FLV") {
				if(!move_uploaded_file($_FILES['imagenintro']['tmp_name'], "archivos/".md5($idT2).".flv")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				}
			} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.flv";
			}
		  
	  } else if ($_POST['tipo_documento'] == "10") {

			if($extencion == "mp3" || $extencion == "MP3") {
				if(!move_uploaded_file($_FILES['imagenintro']['tmp_name'], "archivos/".md5($idT2).".mp3")) {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
				} 
	  		} else {
					$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
					mysql_select_db($database_conn, $conn);
					$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	
					$msg2="Verifique que sea un archivo *.mp3";
			}
		  
	  }
	  
	  
	  
  }
  
  
  

  $insertGoTo = "documentos_archivos.php?cod=".$_POST['codigo_referencia']."&arc=".$idT2."&msg2=".$msg2;
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_archivos_digitales = "-1";
if (isset($_GET['cod'])) {
  $colname_archivos_digitales = $_GET['cod'];
}

if(isset($_GET['arc']) && $_GET['arc'] != "") {
	$w=" AND codigo_archivo=".$_GET['arc']; 
} else {
	$w="";
}


mysql_select_db($database_conn, $conn);
$query_archivos_digitales = sprintf("SELECT * FROM archivos_digitales WHERE codigo_referencia = %s  ORDER BY fecha_toma_archivo DESC", GetSQLValueString($colname_archivos_digitales, "text"));
$archivos_digitales = mysql_query($query_archivos_digitales, $conn) or die(mysql_error());
$row_archivos_digitales = mysql_fetch_assoc($archivos_digitales);
$totalRows_archivos_digitales = mysql_num_rows($archivos_digitales);


mysql_select_db($database_conn, $conn);
$query_archivos_digitales2 = sprintf("SELECT * FROM archivos_digitales WHERE codigo_referencia = %s ".$w." ORDER BY fecha_toma_archivo DESC", GetSQLValueString($colname_archivos_digitales, "text"));
$archivos_digitales2 = mysql_query($query_archivos_digitales2, $conn) or die(mysql_error());
$row_archivos_digitales2 = mysql_fetch_assoc($archivos_digitales2);
$totalRows_archivos_digitales2 = mysql_num_rows($archivos_digitales2);

$colname_documentos = "-1";
if (isset($_GET['cod'])) {
  $colname_documentos = $_GET['cod'];
}
mysql_select_db($database_conn, $conn);
$query_documentos = sprintf("SELECT * FROM documentos WHERE codigo_referencia = %s LIMIT 1", GetSQLValueString($colname_documentos, "text"));
$documentos = mysql_query($query_documentos, $conn) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);
$totalRows_documentos = mysql_num_rows($documentos);


$s1=permiso($_SESSION['MM_usuario'], $row_documentos['codigo_institucion'], 'NIV1' ,$database_conn,$conn);
$s2=permiso($_SESSION['MM_usuario'], $row_documentos['codigo_institucion'], 'NIV2' ,$database_conn,$conn);
$s3=permiso($_SESSION['MM_usuario'], $row_documentos['codigo_institucion'], 'NIV3' ,$database_conn,$conn);
$s4=permiso($_SESSION['MM_usuario'], $row_documentos['codigo_institucion'], 'NIV4' ,$database_conn,$conn);

//echo $s1.$s2.$s3.$s4;

if($s1 != "C" && $s2 != "C" && $s3 != "C" && $s4 != "C") {
  $ts=1;
} else {
  $ts=0;
}

if($s1 == "" || $s2 == "" || $s3 == "" || $s4 == "") {
	$rd = "fr_nopermisos.php";
 	header(sprintf("Location: %s", $rd));
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
</head>
<script language="javascript">
function archivero() {
	document.location='documentos_archivos.php?cod=<?php echo $_GET['cod']; ?>&arc='+document.getElementById('selectArchivo').value;
}


function nuevoarchivos() {
	if(document.getElementById('nuevoarchivo').style.display == 'none' ) {
		document.getElementById('nuevoarchivo').style.display='block';
	} else {
		document.getElementById('nuevoarchivo').style.display='none';
	}
}
</script>
<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        <td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Archivos digitales del Documento</td>
    	          <td align="right"></td>
  	          </tr>
  	        </table></td>
        </tr>
        <tr>
    	  <td class="fondolineaszulesvert_c"><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	      <td>&nbsp;</td>
  	      </tr>
          <tr>
          <td class="tituloscampos ins_celdacolor">Selecciones la fecha del archivo:</td>
          </tr>
    	    <tr>
    	      <td><select name="selectArchivo" id="selectArchivo" style="width:450px;" onChange="archivero();">
    	        <?php
do {  
?>
    	        <option value="<?php echo $row_archivos_digitales['codigo_archivo']?>" <?php  if(isset($_GET['arc']) && $_GET['arc'] == $row_archivos_digitales['codigo_archivo']) { echo "selected"; } ?> ><?php echo desfechar($row_archivos_digitales['fecha_toma_archivo']); ?></option>
    	        <?php
} while ($row_archivos_digitales = mysql_fetch_assoc($archivos_digitales));
  $rows = mysql_num_rows($archivos_digitales);
  if($rows > 0) {
      mysql_data_seek($archivos_digitales, 0);
	  $row_archivos_digitales = mysql_fetch_assoc($archivos_digitales);
  }
?>
              </select>
   	          <input name="button" type="button"  id="button" style="width:90px;" value="Nuevo" onClick="nuevoarchivos();" <?php if($row_documentos['situacion'] == "Baja" || $ts == 0) { echo "disabled"; } ?>></td>
  	      </tr>
    	    <tr>
    	      <td><iframe id="frmftp" name="frmftp" frameborder="0" width="5" height="5"></iframe>&nbsp;</td>
  	      </tr>
          <tr>
          <td style="color:#F00" class="niv_menu"><?php if(isset($_GET['msg2'])) { echo $_GET['msg2']; } ?></td>
          </tr>
          <tr>
    	      <td><div id="nuevoarchivo" style="display:none;"><table width="550" border="1" cellspacing="0" cellpadding="0" class="ins_tabladirecciones">
    	        <tr>
    	          <td align="center" class="ins_tabladirecciones"><iframe style="width:510px; height:75px;" src="ftp_archivos.php?tipo_diplomatico=<?php echo $row_documentos['tipo_diplomatico']; ?>&cod=<?php echo $_GET['cod']; ?>" frameborder="0" scrolling="no" ></iframe></td>
  	          </tr>
  	        </table></div>
   	        <div align="center" id="cargando" style="width:550; height:20; background-color:#ff0000; display:none;"><span class="botongrabar" style="color:#FFF;">
   	          <object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="50" height="20">
   	            <param name="movie" value="FLECHA.swf">
   	            <param name="quality" value="high">
   	            <param name="wmode" value="opaque">
   	            <param name="swfversion" value="6.0.65.0">
   	            <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
   	            <param name="expressinstall" value="Scripts/expressInstall.swf">
   	            <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
   	            <!--[if !IE]>-->
   	            <object type="application/x-shockwave-flash" data="FLECHA.swf" width="50" height="20">
   	              <!--<![endif]-->
   	              <param name="quality" value="high">
   	              <param name="wmode" value="opaque">
   	              <param name="swfversion" value="6.0.65.0">
   	              <param name="expressinstall" value="Scripts/expressInstall.swf">
   	              <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
   	              <div>
   	                <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
   	                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
                  </div>
   	              <!--[if !IE]>-->
                </object>
   	            <!--<![endif]-->
              </object>
   	          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CARGANDO</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="50" height="20">
   	            <param name="movie" value="FLECHA.swf">
   	            <param name="quality" value="high">
   	            <param name="wmode" value="opaque">
   	            <param name="swfversion" value="6.0.65.0">
   	            <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
   	            <param name="expressinstall" value="Scripts/expressInstall.swf">
   	            <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
   	            <!--[if !IE]>-->
   	            <object type="application/x-shockwave-flash" data="FLECHA.swf" width="50" height="20">
   	              <!--<![endif]-->
   	              <param name="quality" value="high">
   	              <param name="wmode" value="opaque">
   	              <param name="swfversion" value="6.0.65.0">
   	              <param name="expressinstall" value="Scripts/expressInstall.swf">
   	              <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
   	              <div>
   	                <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
   	                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
                  </div>
   	              <!--[if !IE]>-->
                </object>
   	            <!--<![endif]-->
              </object>
   	        </div></td>
  	      </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
  	      </table></td>
  </tr>
       <tr>
    	  <td class="celdapieazul_c"></td>
  	  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
		<td class="celdasuperiorsimple_c"></td>
	</tr>
    <tr>
          <td align="center" class="fondolineaszulesvert_c">
          
          <table width="550" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="ins_titulomayor ins_celdacolor"><span><?php echo desfechar($row_archivos_digitales2['fecha_toma_archivo']); ?></span><br><?php if($row_archivos_digitales2['tipo'] == 9 || $row_archivos_digitales2['tipo'] == 10) { ?><embed 
src="player.swf" 
width="550" 
height="<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>"
 allowscriptaccess="always" 
allowfullscreen="true" 
flashvars="width=510&height=<?php if($row_archivos_digitales2['tipo'] == 9) { echo "310"; } else if($row_archivos_digitales2['tipo'] == 10) { echo "24"; } ?>&file=archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>"
 /><?php } ?><?php if($row_archivos_digitales2['tipo'] == 8 || $row_archivos_digitales2['tipo'] == 11) { ?>
              <a href="archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>" target="_blank"><img src="archivos/<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension'] ?>" width="550" ></a>                <?php } ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          
 
          
      </td>
    </tr>
    <tr>
          <td class="celdapieazul_c"></td>
        </tr>
  </table>
<form name="form3" method="post" action="documentos_archivos.php">
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
			<td class="celdasuperiorsimple_c"></td>
  </tr>
    	<tr>
          <td align="center" class="fondolineaszulesvert_c"><input type="submit" name="button2" id="button2" value="Eliminar" style="width:550px;"></td>
        </tr>
		<tr>
			<td class="celdapieazul_c"></td>
		</tr>
</table>
<input name="cod" type="hidden" id="cod" value="<?php echo $_GET['cod']; ?>">
<input name="arc" type="hidden" id="arc" value="<?php echo $row_archivos_digitales2['codigo_archivo']; ?>">
<input name="archivo" type="hidden" id="archivo" value="<?php echo md5($row_archivos_digitales2['codigo_archivo']).".".$row_archivos_digitales2['extension']; ?>">
<input name="eliminar" type="hidden" id="eliminar" value="form3">
</form>
<p>&nbsp; </p>
<script type="text/javascript">
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
    </script>
</body>
</html>
<?php
mysql_free_result($archivos_digitales);
mysql_free_result($archivos_digitales2);

mysql_free_result($documentos);
?>
