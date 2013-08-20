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
//if($_POST) {echo '<pre>'; print_r($_POST); echo '</pre>';exit;}

/* se insertan o modifican en cascada los registros a la rel_usuarios_roles_instituciones*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	foreach($_POST['idseccion'] AS $idseccion) {
		foreach(array_unique($_POST['idrol']) AS $idrol) {
			
		  if(empty($_POST['idpermiso'][$idseccion][$idrol])) {
			  
		  $insertOrUpdateSQL = sprintf("INSERT INTO permisos (idseccion, idrol, consultar, modificar, eliminar) VALUES (%s, %s, %s, %s, %s)",
							   GetSQLValueString($idseccion, "int"),
							   GetSQLValueString($idrol, "int"),
							   !empty($_POST['consultar'][$idseccion][$idrol])?1:0,
							   !empty($_POST['modificar'][$idseccion][$idrol])?1:0,
							   !empty($_POST['eliminar'][$idseccion][$idrol])?1:0);
		  } else {
		  $insertOrUpdateSQL = sprintf("UPDATE permisos SET idseccion=%s, idrol=%s, consultar=%s, modificar=%s, eliminar=%s WHERE idpermiso=%s",
							   GetSQLValueString($idseccion, "int"),
							   GetSQLValueString($idrol, "int"),
							   !empty($_POST['consultar'][$idseccion][$idrol])?1:0,
							   !empty($_POST['modificar'][$idseccion][$idrol])?1:0,
							   !empty($_POST['eliminar'][$idseccion][$idrol])?1:0,
							   GetSQLValueString($_POST['idpermiso'][$idseccion][$idrol], "int"));
		  }
	  mysql_select_db($database_conn, $conn);
	  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());		  		  
		}
	}
 
  $insertOrUpdateGoTo = $editFormAction;
  header(sprintf("Location: %s", $insertOrUpdateGoTo));
}

/* se leen los registros a la tabla secciones */
mysql_select_db($database_conn, $conn);
$query_vis_load_secciones = "SELECT * FROM secciones ORDER BY descripcion ASC";
$vis_load_secciones = mysql_query($query_vis_load_secciones, $conn) or die(mysql_error());
$row_vis_load_secciones = mysql_fetch_assoc($vis_load_secciones);
$totalRows_vis_load_secciones = mysql_num_rows($vis_load_secciones);

/* se leen los registros a la tabla roles */
mysql_select_db($database_conn, $conn);
$query_vis_load_roles = "SELECT * FROM roles WHERE idrol <> 1 AND idrol <> 2 AND idrol <> 3";
$vis_load_roles = mysql_query($query_vis_load_roles, $conn) or die(mysql_error());
$row_vis_load_roles = mysql_fetch_assoc($vis_load_roles);
$totalRows_vis_load_roles = mysql_num_rows($vis_load_roles);

/* se leen los registros a la tabla permisos */
mysql_select_db($database_conn, $conn);
$query_vis_load_permisos = "SELECT * FROM permisos";
$vis_load_permisos = mysql_query($query_vis_load_permisos, $conn) or die(mysql_error());
$row_vis_load_permisos = mysql_fetch_assoc($vis_load_permisos);
$totalRows_vis_load_permisos = mysql_num_rows($vis_load_permisos);

/* array secciones */
do {
	$arr_secciones[] = $row_vis_load_secciones;
} while ($row_vis_load_secciones = mysql_fetch_assoc($vis_load_secciones));
/* array roles */
do {
	$arr_roles[] = $row_vis_load_roles;	
} while ($row_vis_load_roles = mysql_fetch_assoc($vis_load_roles));
/* array permisos */
do {
	$arr_acciones[$row_vis_load_permisos['idseccion']][$row_vis_load_permisos['idrol']] = $row_vis_load_permisos;	
} while ($row_vis_load_permisos = mysql_fetch_assoc($vis_load_permisos));

//echo '<pre>'; print_r($arr_secciones); echo '</pre>';
//echo '<pre>'; print_r($arr_acciones); echo '</pre>';
?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
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
<body>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Permisos</td>
  	          </tr>
  	        </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert">
          <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="6" class="separadormenor"></td>
  	          </tr>
              <tr>
                  <td class="tituloscampos ins_celdacolor">Secciones</td>
                  <td width="200" class="tituloscampos ins_celdacolor">Roles</td>
                  <td width="40" class="tituloscampos ins_celdacolor">C</td>
                  <td width="40" class="tituloscampos ins_celdacolor">M</td>
                  <td width="40" class="tituloscampos ins_celdacolor">E</td>
                  <td width="30" class="tituloscampos ins_celdacolor">&nbsp;</td>
              </tr>
			  <tr>
    	          <td colspan="6" class="separadormenor"></td>
  	          </tr>              
              		
    	            <?php foreach($arr_secciones AS $seccion) { ?>
    	            <tr>
    	              <td class="tituloscampos ins_celdacolor"><?php echo $seccion['descripcion']; ?></td>
    	              <td class="tituloscampos ins_celdacolor" colspan="5" align="right">

					  <?php foreach($arr_roles AS $rol) {?>
                      <?php if(!empty($rol)) {?>
                      <?php $consultar = !empty($arr_acciones[$seccion['idseccion']][$rol['idrol']]['consultar'])?$arr_acciones[$seccion['idseccion']][$rol['idrol']]['consultar']:""?>
                      <?php $modificar = !empty($arr_acciones[$seccion['idseccion']][$rol['idrol']]['modificar'])?$arr_acciones[$seccion['idseccion']][$rol['idrol']]['modificar']:""?>
                      <?php $eliminar = !empty($arr_acciones[$seccion['idseccion']][$rol['idrol']]['eliminar'])?$arr_acciones[$seccion['idseccion']][$rol['idrol']]['eliminar']:""?>
                      <table cellpadding="0" cellspacing="0" border="0" width="100%">
                      	<tr>
                     		<td class="tituloscampos ins_celdacolor" style="border-bottom:1px solid #fff;"><?php echo $rol['rol']?></td>
                            <td width="40" class="tituloscampos ins_celdacolor" style="border-bottom:1px solid #fff;">
                            	<input name="consultar[<?php echo $seccion['idseccion']?>][<?php echo $rol['idrol']?>]" type="checkbox" <?php echo ($consultar==1 || $consultar== chr(1))?'checked':''?> />
                            </td>
                            <td width="40" class="tituloscampos ins_celdacolor" style="border-bottom:1px solid #fff;">
                            	<input name="modificar[<?php echo $seccion['idseccion']?>][<?php echo $rol['idrol']?>]" type="checkbox" <?php echo ($modificar==1 || $modificar==chr(1))?'checked':''?> />
                            </td>
                            <td width="40" class="tituloscampos ins_celdacolor" style="border-bottom:1px solid #fff;">
                            	<input name="eliminar[<?php echo $seccion['idseccion']?>][<?php echo $rol['idrol']?>]" type="checkbox" <?php echo ($eliminar==1 || $eliminar==chr(1)) ?'checked':''?> />
                            </td>
                            <td width="34" align="center" class="tituloscampos ins_celdacolor" style="border-bottom:1px solid #fff;">&nbsp;</td>
                        </tr>
                            <input type="hidden" name="idpermiso[<?php echo $seccion['idseccion']?>][<?php echo $rol['idrol']?>]" value="<?php echo !empty($arr_acciones[$seccion['idseccion']][$rol['idrol']]['idpermiso'])?$arr_acciones[$seccion['idseccion']][$rol['idrol']]['idpermiso']:""?>" />
                            <input type="hidden" name="idrol[]" value="<?php echo $rol['idrol']?>" />
                      </table>
                      <?php } ?>
                      <?php } ?>
                      </td>
  	              </tr>
                  <tr>
                      <td colspan="6" class="separadormenor"></td>
                  </tr>
                  <input type="hidden" name="idseccion[]" value="<?php echo $seccion['idseccion']?>" />                                
    	          <?php } ?>
                  
<tr>
   	            <td colspan="6" class="separadormenor"></td>
  	          </tr>              
          </table></td>
	    </tr>
        <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button3" type="submit" class="botongrabar" id="button3" value="Grabar">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
        </tr>
        <tr>
          <td class="celdapieazul"></td>
        </tr>
	</table>
    <input type="hidden" name="MM_insert" value="form2">

   </td>
  </tr>
  
</table>
</form>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    
   </td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($vis_load_permisos);

mysql_free_result($vis_load_secciones);

mysql_free_result($vis_load_roles);
?>
