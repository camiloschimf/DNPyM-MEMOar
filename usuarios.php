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

/* desactivo a un usuario */
if (isset($_POST['elm']) &&  $_POST['elm'] != "" ) {
	$deleteSQL1 = sprintf("UPDATE usuarios SET activo=%s WHERE idusuario=%s",
                       0,
					   GetSQLValueString($_POST["elm"], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = "usuarios.php?p=usuarios";
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* elimino relacion usuario institucion */
if (isset($_POST['elm_inst']) &&  $_POST['elm_inst'] != "" ) {
	$deleteSQL1 = sprintf("DELETE FROM rel_usuarios_instituciones WHERE idrel_usuarios_instituciones=%s", 
					   GetSQLValueString($_POST["elm_inst"], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = "usuarios.php?p=usuarios";
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* se insertan o modifican en cascada los registros a la tabla usuarios*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2" || $_POST["MM_insert"] == "form3")) {
	if(isset($_POST["idusuario"]) && $_POST["idusuario"] == "") {	
  		$insertOrUpdateSQL = sprintf("INSERT INTO usuarios (usuario, pass, fecha_alta, nombre, apellido, email, telefono) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString(md5($_POST['pass']), "text"),
                       GetSQLValueString(date("Ymd"), "date"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellido'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"));
	} else {
		$insertOrUpdateSQL = sprintf("UPDATE usuarios SET usuario=%s, pass=%s, nombre=%s, apellido=%s, email=%s, telefono=%s WHERE idusuario=%s",
                       GetSQLValueString($_POST['usuario'], "text"),
                       $_POST["pass"]!=""?GetSQLValueString(md5($_POST['pass']), "text"):GetSQLValueString($_POST['pass_actual'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellido'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['idusuario'], "int"));
	}
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());
  
//  $insertOrUpdateGoTo = $editFormAction;
//  header(sprintf("Location: %s", $insertOrUpdateGoTo));  
}


/* se insertan o modifican en cascada los registros a la tabla rel_usuarios_instituciones*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2" || $_POST["MM_insert"] == "form3")) {
	if(!empty($_POST["codigo_identificacion"])) {
  		$insertOrUpdateSQL = sprintf("INSERT INTO rel_usuarios_instituciones (idusuario, codigo_identificacion) VALUES (%s, %s)",
                       GetSQLValueString($_POST['idusuario'], "int"),
                       GetSQLValueString($_POST['codigo_identificacion'], "text"));
	
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());
  }
  
  $insertOrUpdateGoTo = "usuarios.php?p=usuarios";
  header(sprintf("Location: %s", $insertOrUpdateGoTo));  
}

/* se leen los registros a la tabla para la grilla de muestra */
mysql_select_db($database_conn, $conn);
$query_vis_load = "SELECT * FROM usuarios WHERE usuarios.activo=1 ORDER BY nombre ASC";
$vis_load = mysql_query($query_vis_load, $conn) or die(mysql_error());
$row_vis_load = mysql_fetch_assoc($vis_load);
$totalRows_vis_load = mysql_num_rows($vis_load);

mysql_select_db($database_conn, $conn);
$query_vis_load_instituciones = "SELECT codigo_identificacion, formas_conocidas_nombre FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$vis_load_instituciones = mysql_query($query_vis_load_instituciones, $conn) or die(mysql_error());
$row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones);
$totalRows_vis_load_instituciones = mysql_num_rows($vis_load_instituciones);


/* se leen los registros a la instituciones */
$colname_vis_load_instituciones_aux = "-1";
if (isset($_POST['mod']) || isset($_SESSION['sess_mod'])) {
  $_SESSION['sess_mod'] = isset($_POST['mod']) ? $_POST['mod'] : $_SESSION['sess_mod'];
  $colname_vis_load_instituciones_aux = $_SESSION['sess_mod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_instituciones_aux = sprintf("SELECT 
rel_usuarios_instituciones.*, 
instituciones.formas_conocidas_nombre 
FROM rel_usuarios_instituciones 
LEFT JOIN instituciones ON rel_usuarios_instituciones.codigo_identificacion=instituciones.codigo_identificacion 
WHERE rel_usuarios_instituciones.idusuario = %s 
ORDER BY formas_conocidas_nombre ASC", GetSQLValueString($colname_vis_load_instituciones_aux, "text"));
$vis_load_instituciones_aux = mysql_query($query_vis_load_instituciones_aux, $conn) or die(mysql_error());
$row_vis_load_instituciones_aux = mysql_fetch_assoc($vis_load_instituciones_aux);
$totalRows_vis_load_instituciones_aux = mysql_num_rows($vis_load_instituciones_aux);

/* se leen los registros a la tabla para precarga de formulario */
$colname_vis_load_aux = "-1";
if (isset($_POST['mod']) || isset($_SESSION['sess_mod'])) {
  $_SESSION['sess_mod'] = isset($_POST['mod']) ? $_POST['mod'] : $_SESSION['sess_mod'];
  $colname_vis_load_aux = $_SESSION['sess_mod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_aux = sprintf("SELECT 
usuarios.*,  
rel_usuarios_instituciones.idrel_usuarios_instituciones, 
rel_usuarios_instituciones.codigo_identificacion
FROM usuarios 
LEFT JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.idusuario=usuarios.idusuario 
WHERE usuarios.idusuario = %s", GetSQLValueString($colname_vis_load_aux, "int"));
$vis_load_aux = mysql_query($query_vis_load_aux, $conn) or die(mysql_error());
$row_vis_load_aux = mysql_fetch_assoc($vis_load_aux);
$totalRows_vis_load_aux = mysql_num_rows($vis_load_aux);

//echo '<pre>';echo print_r($_SERVER);echo '</pre>';
if (isset($_POST['nuevo']) || empty($_GET['p'])) {
  unset($_SESSION['sess_mod']);
  $insertOrUpdateGoTo = "usuarios.php?p=usuarios";
  header(sprintf("Location: %s", $insertOrUpdateGoTo));    
}

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

</script>
<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Instituciones</td>
  	          </tr>
  	        </table></td>
		</tr>
        <tr>
		  <td class="fondolineaszulesvert">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Usuarios</td>
  	          </tr>
  	        </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
    	        <?php do { ?>
    	          <tr>
    	            <td width="582" class="tituloscampos ins_celdacolor"><?php echo $row_vis_load['nombre']; ?> <?php echo $row_vis_load['apellido']; ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'mod':'<?php echo $row_vis_load['idusuario'] ?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onDblClick="relocate('<?php echo $editFormAction; ?>',{	'elm':'<?php echo $row_vis_load['idusuario']?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_vis_load = mysql_fetch_assoc($vis_load)); ?>
           </table></td>
		  </tr>
          
        <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input type="button" class="botongrabar" value="Nuevo" onClick="relocate('<?php echo $editFormAction; ?>',{'nuevo':'nuevo'});">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
          </tr>
        <tr>
          <td class="celdapieazul"></td>
        </tr>          
	</table>
   </td>
  </tr>
  
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">  
  <tr>
    <td>
      <table width="658" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="celdasuperiorsimple"></td>
        </tr>
        <tr>
          <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>                
                <tr>
                  <td class="tituloscampos ins_celdacolor">Usuario:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="usuario" type="text" class="camposanchos" id="usuario" value="<?php echo $row_vis_load_aux['usuario']; ?>" <?php echo $row_vis_load_aux['usuario']!=""?'readonly':''?> /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Usuario Nombre:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="nombre" type="text" class="camposanchos" id="nombre" value="<?php echo $row_vis_load_aux['nombre']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Usuario Apellido:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="apellido" type="text" class="camposanchos" id="apellido" value="<?php echo $row_vis_load_aux['apellido']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Contrase&ntilde;a:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="pass" type="text" class="camposanchos" id="pass" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Email:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="email" type="text" class="camposanchos" id="email" value="<?php echo $row_vis_load_aux['email']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Tel&eacute;fono:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="telefono" type="text" class="camposanchos" id="telefono" value="<?php echo $row_vis_load_aux['telefono']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="">&nbsp;</td>
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
      
      <input name="idusuario" type="hidden" id="idusuario" value="<?php echo $row_vis_load_aux['idusuario']; ?>">      
      <input name="pass_actual" type="hidden" id="pass_actual" value="<?php echo $row_vis_load_aux['pass']; ?>">
      <input type="hidden" name="MM_insert" value="form2">
    </form></td>
  </tr>
  
  <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
<tr>
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Instituciones</td>
  	          </tr>
  	        </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">          
    	        <tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              
<tr>
		  <td><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
                <?php if($totalRows_vis_load_instituciones_aux > 0) {?>
    	        <?php do { ?>
    	          <tr>
    	            <td width="582" class="tituloscampos ins_celdacolor"><?php echo $row_vis_load_instituciones_aux['formas_conocidas_nombre']; ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor">&nbsp;</td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'elm_inst':'<?php echo $row_vis_load_instituciones_aux['idrel_usuarios_instituciones']?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a></td>
  	              </tr>
<tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_vis_load_instituciones_aux = mysql_fetch_assoc($vis_load_instituciones_aux)); ?>
              <?php }?>
           </table></td>
		  </tr>              
              
              
<tr>
                  <td class="ins_celdacolor"><select name="codigo_identificacion" class="camposanchos" id="codigo_identificacion">
                  	<option value=""> Seleccione </option>
                    <?php do { ?>
                    <option value="<?php  echo $row_vis_load_instituciones['codigo_identificacion']?>"><?php echo $row_vis_load_instituciones['formas_conocidas_nombre']?></option>
                    <?php
					} while ($row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones));
					  $rows = mysql_num_rows($vis_load_instituciones);
					  if($rows > 0) {
						  mysql_data_seek($vis_load_instituciones, 0);
						  $row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones);
					  }
					?>
                  </select></td>
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
    	<input name="usuario" type="hidden" id="usuario" value="<?php echo $row_vis_load_aux['usuario']; ?>" />
    	<input name="nombre" type="hidden" id="nombre" value="<?php echo $row_vis_load_aux['nombre']; ?>" />
        <input name="apellido" type="hidden" id="apellido" value="<?php echo $row_vis_load_aux['apellido']; ?>" />
        <input name="email" type="hidden" id="email" value="<?php echo $row_vis_load_aux['email']; ?>" />
        <input name="telefono" type="hidden" id="telefono" value="<?php echo $row_vis_load_aux['telefono']; ?>" />
    
         <input name="idusuario" type="hidden" id="idusuario" value="<?php echo $row_vis_load_aux['idusuario']; ?>">      
      <input name="pass_actual" type="hidden" id="pass_actual" value="<?php echo $row_vis_load_aux['pass']; ?>">
      <input type="hidden" name="MM_insert" value="form2">
    </form></td>
  </tr>
  
  
</table>
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
mysql_free_result($vis_load);

mysql_free_result($vis_load_instituciones);

mysql_free_result($vis_load_instituciones_aux);

mysql_free_result($vis_load_aux);
?>
