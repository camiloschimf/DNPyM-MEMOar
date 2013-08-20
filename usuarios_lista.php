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

$msgA="";
$t="";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if(isset($_POST['accion']) && $_POST['accion'] == "eliminar") {
	$updateSQL = sprintf("UPDATE usuarios SET activo=0 WHERE idusuario=%s",
                       GetSQLValueString($_POST['elm'], "int"));

     mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

if(isset($_POST['accion']) && $_POST['accion'] == "eliminarrol") {
	$updateSQL = sprintf("DELETE FROM rel_usuarios_roles_instituciones WHERE idrel_usuarios_roles_instituciones=%s",
                       GetSQLValueString($_POST['elm'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  
  $msgA = "window.onload = function() { relocate('".$editFormAction."',{'accion':'roles','idusuario':'".$_POST['idusuario']."'}); } ";
}

if(isset($_POST['accion']) && $_POST['accion'] == "eliminarinstitucion") {
	$updateSQL = sprintf("DELETE FROM rel_usuarios_roles_instituciones WHERE idrel_usuarios_instituciones=%s",
                       GetSQLValueString($_POST['elm'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  
  $update2SQL = sprintf("DELETE FROM rel_usuarios_instituciones WHERE idrel_usuarios_instituciones=%s",
                       GetSQLValueString($_POST['elm'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($update2SQL, $conn) or die(mysql_error());
  
  $msgA = "window.onload = function() { relocate('".$editFormAction."',{'accion':'institucion','idusuario':'".$_POST['idusuario']."'}); } ";
	
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
	if($_POST['idusuario'] != "") {	
  $updateSQL = sprintf("UPDATE usuarios SET nombre=%s, apellido=%s, email=%s, telefono=%s, activo=%s WHERE idusuario=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellido'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
					   GetSQLValueString($_POST['activo'], "int"),
                       GetSQLValueString($_POST['idusuario'], "int"));

     mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  
  if($_POST['pass'] != "") {
  	$updateSQL2 = sprintf("UPDATE usuarios SET pass=%s WHERE idusuario=%s",
                       GetSQLValueString(md5($_POST['pass']), "text"),
                       GetSQLValueString($_POST['idusuario'], "int"));

     mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($updateSQL2, $conn) or die(mysql_error());
  
  
  }
  
  $msgA = " window.onload = function() { relocate('".$editFormAction."',{'accion':'modificar','idusuario':'".$_POST['idusuario']."'}); }";
  
	} else {
		
		mysql_select_db($database_conn, $conn);
		$query_usuarioscount = sprintf("SELECT COUNT(*) AS cant FROM usuarios WHERE usuario=%s OR email=%s",GetSQLValueString($_POST['usuario'], "text"),GetSQLValueString($_POST['email'], "text"));
		$usuarioscount = mysql_query($query_usuarioscount, $conn) or die(mysql_error());
		$row_usuarioscount = mysql_fetch_assoc($usuarioscount);
		$totalRows_usuarioscount = mysql_num_rows($usuarioscount);
		
		if($row_usuarioscount['cant'] == "0") {
		$updateSQL = sprintf("INSERT INTO usuarios (usuario, pass, nombre, apellido, email, telefono) VALUES (%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($_POST['usuario'], "text"),
                       		GetSQLValueString(md5($_POST['pass']), "text"),
                       		GetSQLValueString($_POST['nombre'], "text"),
                       		GetSQLValueString($_POST['apellido'], "text"),
                       		GetSQLValueString($_POST['email'], "text"),
                       		GetSQLValueString($_POST['telefono'], "text"));
							
		 mysql_select_db($database_conn, $conn);
  		 $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
		 
		 $idnuevo=mysql_insert_id();
		 
		 $insertSQL = sprintf("INSERT INTO rel_usuarios_instituciones (idusuario, codigo_identificacion) VALUES (%s,%s)",
							GetSQLValueString($idnuevo, "int"),
                       		GetSQLValueString($_POST['codigo_identificacion'], "text"));
		 
		 mysql_select_db($database_conn, $conn);
  		 $Result2 = mysql_query($insertSQL, $conn) or die(mysql_error());
		 
		 $msgA = " window.onload = function() { relocate('".$editFormAction."',{'accion':'nuevo','institucion':'".$_POST['codigo_identificacion']."'}); }";
		 
		} else {
			
			echo "<script language=\"javascript\"> alert('El Usuario ya existe.'); </script>";
			
		}
	}
	
  $t="0";
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3") && $_POST['codigo_identificacion'] != "" && $_POST['idusuario'] != "") {
  $insertSQL = sprintf("INSERT INTO rel_usuarios_instituciones (idusuario, codigo_identificacion) VALUES (%s, %s)",
                       GetSQLValueString($_POST['idusuario'], "int"),
                       GetSQLValueString($_POST['codigo_identificacion'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  $t="0";
  $msgA = "window.onload = function() { relocate('".$editFormAction."',{'accion':'institucion','idusuario':'".$_POST['idusuario']."'}); } ";
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO rel_usuarios_roles_instituciones (idrol, idrel_usuarios_instituciones) VALUES (%s, %s)",
                       GetSQLValueString($_POST['idrol'], "int"),
                       GetSQLValueString($_POST['idrel_usuarios_instituciones'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  $t="0";
  $msgA = "window.onload = function() { relocate('".$editFormAction."',{'accion':'roles','idusuario':'".$_POST['idusuario']."'}); } ";
}

mysql_select_db($database_conn, $conn);
$query_instituciones = "SELECT * FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die(mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

$wh = "";
if(isset($_POST['institucion']) && $_POST['institucion'] != "") {
	$wh= "WHERE rel_usuarios_instituciones.codigo_identificacion='".$_POST['institucion']."'";	
}
if(isset($_POST['idusuario']) && $_POST['idusuario'] != "") {
	$wh= "WHERE usuarios.idusuario='".$_POST['idusuario']."'";	
}

mysql_select_db($database_conn, $conn);
$query_rel_usuarios_instituciones = "SELECT DISTINCT nombre, apellido, rel_usuarios_instituciones.idusuario, activo FROM rel_usuarios_instituciones INNER JOIN usuarios ON (rel_usuarios_instituciones.idusuario=usuarios.idusuario) ".$wh." ORDER BY usuarios.nombre ASC";
$rel_usuarios_instituciones = mysql_query($query_rel_usuarios_instituciones, $conn) or die(mysql_error());
$row_rel_usuarios_instituciones = mysql_fetch_assoc($rel_usuarios_instituciones);
$totalRows_rel_usuarios_instituciones = mysql_num_rows($rel_usuarios_instituciones);

$colname_usuarios = "-1";
if (isset($_POST['idusuario'])) {
  $colname_usuarios = $_POST['idusuario'];
}
mysql_select_db($database_conn, $conn);
$query_usuarios = sprintf("SELECT * FROM usuarios WHERE idusuario = %s", GetSQLValueString($colname_usuarios, "int"));
$usuarios = mysql_query($query_usuarios, $conn) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);

$colname_usuarios_instituciones = "-1";
if (isset($_POST['idusuario'])) {
  $colname_usuarios_instituciones = $_POST['idusuario'];
}
mysql_select_db($database_conn, $conn);
$query_usuarios_instituciones = sprintf("SELECT * FROM rel_usuarios_instituciones INNER JOIN instituciones ON (rel_usuarios_instituciones.codigo_identificacion=instituciones.codigo_identificacion) WHERE rel_usuarios_instituciones.idusuario = %s", GetSQLValueString($colname_usuarios_instituciones, "int"));
$usuarios_instituciones = mysql_query($query_usuarios_instituciones, $conn) or die(mysql_error());
$row_usuarios_instituciones = mysql_fetch_assoc($usuarios_instituciones);
$totalRows_usuarios_instituciones = mysql_num_rows($usuarios_instituciones);

$colname_cbo_instituciones2 = "-1";
if (isset($_POST['idusuario'])) {
  $colname_cbo_instituciones2 = $_POST['idusuario'];
}

mysql_select_db($database_conn, $conn);
$query_cbo_instituciones2 = sprintf("SELECT * FROM instituciones WHERE codigo_identificacion NOT IN (SELECT codigo_identificacion FROM rel_usuarios_instituciones WHERE idusuario=%s) ORDER BY formas_conocidas_nombre ASC", GetSQLValueString($colname_cbo_instituciones2, "int"));
$cbo_instituciones2 = mysql_query($query_cbo_instituciones2, $conn) or die(mysql_error());
$row_cbo_instituciones2 = mysql_fetch_assoc($cbo_instituciones2);
$totalRows_cbo_instituciones2 = mysql_num_rows($cbo_instituciones2);

$colname_cbo_instituciones3 = "-1";
if (isset($_POST['idusuario'])) {
  $colname_cbo_instituciones3 = $_POST['idusuario'];
}

mysql_select_db($database_conn, $conn);
$query_cbo_instituciones3 = sprintf("SELECT * FROM rel_usuarios_instituciones r INNER JOIN instituciones i ON (r.codigo_identificacion=i.codigo_identificacion) WHERE r.idusuario=%s  ORDER BY i.formas_conocidas_nombre ASC", GetSQLValueString($colname_cbo_instituciones3, "int"));
$cbo_instituciones3 = mysql_query($query_cbo_instituciones3, $conn) or die(mysql_error());
$row_cbo_instituciones3 = mysql_fetch_assoc($cbo_instituciones3);
$totalRows_cbo_instituciones3 = mysql_num_rows($cbo_instituciones3);

mysql_select_db($database_conn, $conn);
$query_roles = "SELECT * FROM roles ORDER BY idrol ASC";
$roles = mysql_query($query_roles, $conn) or die(mysql_error());
$row_roles = mysql_fetch_assoc($roles);
$totalRows_roles = mysql_num_rows($roles);

$colname_rel_usuarios_roles_instituciones = "-1";
if (isset($_POST['idusuario'])) {
  $colname_rel_usuarios_roles_instituciones = $_POST['idusuario'];
}

mysql_select_db($database_conn, $conn);
$query_rel_usuarios_roles_instituciones = sprintf("SELECT * FROM rel_usuarios_roles_instituciones u INNER JOIN rel_usuarios_instituciones r ON (u.idrel_usuarios_instituciones=r.idrel_usuarios_instituciones) INNER JOIN roles l ON (u.idrol=l.idrol) INNER JOIN instituciones i ON (r.codigo_identificacion=i.codigo_identificacion) WHERE r.idusuario=%s ORDER BY i.formas_conocidas_nombre ASC", GetSQLValueString($colname_rel_usuarios_roles_instituciones, "int"));
$rel_usuarios_roles_instituciones = mysql_query($query_rel_usuarios_roles_instituciones, $conn) or die(mysql_error());
$row_rel_usuarios_roles_instituciones = mysql_fetch_assoc($rel_usuarios_roles_instituciones);
$totalRows_rel_usuarios_roles_instituciones = mysql_num_rows($rel_usuarios_roles_instituciones);
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
 
<?php echo $msgA; ?> 

</script>

<body>
<?php if($t != "0") { ?>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php if(!isset($_POST['accion']) || $_POST['accion'] == "filtrar" || $_POST['accion'] == "nuevo") { ?><table width="658" border="0" cellspacing="0" cellpadding="0">
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
		      <td><select name="select" id="select"  class="camposanchos" onChange="relocate('<?php echo $editFormAction; ?>',{'accion':'filtrar','institucion':this.value});">
              <option value="">Selecciones Opción para filtrar</option>
		        <?php
do {  
?>
		        <option value="<?php echo $row_instituciones['codigo_identificacion']; ?>" <?php if(isset($_POST['institucion']) && $_POST['institucion'] == $row_instituciones['codigo_identificacion']) { echo "selected"; } ?> ><?php echo $row_instituciones['formas_conocidas_nombre']?></option>
		        <?php
} while ($row_instituciones = mysql_fetch_assoc($instituciones));
  $rows = mysql_num_rows($instituciones);
  if($rows > 0) {
      mysql_data_seek($instituciones, 0);
	  $row_instituciones = mysql_fetch_assoc($instituciones);
  }
?>
              </select></td>
		      </tr>
	      </table></td>
        </tr>
         <tr>
          <td class="celdapieazul"></td>
        </tr>  
          <tr>
    <td>&nbsp;</td>
  </tr>
    </table><?php } ?></td>
  </tr>
  <tr>
    <td><?php if(!isset($_POST['nuevo'])) { ?>
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
                <?php if($totalRows_rel_usuarios_instituciones > 0) { ?>
    	          <tr>
    	            <td width="534" class="<?php if($row_rel_usuarios_instituciones['activo'] == 1) {echo "tituloscampos"; } else { echo "tituloscamposg"; } ?> ins_celdacolor"><?php echo $row_rel_usuarios_instituciones['nombre']; ?> <?php echo $row_rel_usuarios_instituciones['apellido']; ?></td>
                    <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'accion':'institucion','idusuario':'<?php echo $row_rel_usuarios_instituciones['idusuario'] ?>'});"><img src="images/inst.png"  alt="Instituciones" height="20"></a></td>
                    <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'accion':'roles','idusuario':'<?php echo $row_rel_usuarios_instituciones['idusuario'] ?>'});"><img src="images/rols.png" height="20" alt="Roles"></a></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'accion':'modificar','idusuario':'<?php echo $row_rel_usuarios_instituciones['idusuario'] ?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'accion':'eliminar','elm':'<?php echo $row_rel_usuarios_instituciones['idusuario']?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              <?php } ?>
              <?php } while ($row_rel_usuarios_instituciones = mysql_fetch_assoc($rel_usuarios_instituciones)); ?>
           </table></td>
		  </tr>
          
        <tr>
    	      <td class="celdabotones1"><?php if(isset($_POST['institucion']) && $_POST['institucion'] != "") { ?><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input type="button" class="botongrabar" value="Nuevo" onClick="relocate('<?php echo $editFormAction; ?>',{'accion':'nuevo','institucion':'<?php echo $_POST['institucion']; ?>'});">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
          </table><?php } ?></td>
          </tr>
        <tr>
          <td class="celdapieazul"></td>
        </tr>         
	</table><?php } ?>
   </td>
  </tr>
  <tr>
    <td>
    <?php if(isset($_POST['accion']) && ($_POST['accion'] == "modificar" || $_POST['accion'] == "nuevo")) { ?>
     <form name="form2" method="POST" action="<?php echo $editFormAction; ?>"> 
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
                  <td class="tituloscampos ins_celdacolor">Usuario:
                    <input name="idusuario" type="hidden" id="idusuario" value="<?php echo $row_usuarios['idusuario']; ?>">                    <input name="codigo_identificacion" type="hidden" id="codigo_identificacion" value="<?php if(isset($_POST['institucion'])) { echo $_POST['institucion']; } ?>">                    <span style="display:block"><span class="celdabotones1">
                    
                  </span><span class="celdabotones1">
                  
                  </span></span></td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="usuario" type="text" class="camposanchos" id="usuario" value="<?php echo $row_usuarios['usuario']; ?>" <?php if($row_usuarios['usuario'] != "") { echo "readonly"; } ?> /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Nombre:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="nombre" type="text" class="camposanchos" id="nombre" value="<?php echo $row_usuarios['nombre']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Apellido:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="apellido" type="text" class="camposanchos" id="apellido" value="<?php echo $row_usuarios['apellido']; ?>" /></td>
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
                  <td class="ins_celdacolor"><input name="email" type="text" class="camposanchos" id="email" value="<?php echo $row_usuarios['apellido']; ?>" /></td>
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
                  <td class="ins_celdacolor"><input name="telefono" type="text" class="camposanchos" id="telefono" value="<?php echo $row_usuarios['telefono']; ?>" /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor" >Usuario Activo:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor" style="font-family:Arial; font-size:14px;">Si:<input <?php if (!(strcmp($row_usuarios['activo'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="activo" id="radio" value="1">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No:<input <?php if (!(strcmp($row_usuarios['activo'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="activo" id="radio2" value="0"></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
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
      <input type="hidden" name="MM_update" value="form2">
     </form><?php } ?></td>
  </tr>
<tr>
    <td><?php if(isset($_POST['accion']) && $_POST['accion'] == "institucion") { ?>
    <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Instituciones Asignadas</td>
  	          </tr>
  	        </table></td>
		</tr>
        <tr>
		  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="2" class="separadormenor"></td>
  	          </tr>
    	          <?php do { ?>
    	            <tr>
    	              <td width="606" class="tituloscampos ins_celdacolor"><?php echo $row_usuarios_instituciones['formas_conocidas_nombre']; ?></td>
    	              <td width="24" height="18" align="center" class="tituloscampos ins_celdacolor"><?php if($totalRows_usuarios_instituciones >= 2) { ?><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'accion':'eliminarinstitucion','elm':'<?php echo $row_usuarios_instituciones['idrel_usuarios_instituciones']?>','idusuario':'<?PHP echo $_POST['idusuario']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
  	              </tr>
    	           
<tr>
    	          <td colspan="2" class="separadormenor"></td>
  	          </tr>
			  <?php } while ($row_usuarios_instituciones = mysql_fetch_assoc($usuarios_instituciones)); ?>
           </table>
           <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr> 
              <tr>
    	          <td class="separadormayor"><hr></td>
  	          </tr>   
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>                  
                <tr>
                  <td class="tituloscampos ins_celdacolor">Instituciones disponibles:
                                        <span style="display:block"><span class="celdabotones1">
                    
                  </span><span class="celdabotones1">
                  
                  </span></span></td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><select name="codigo_identificacion" id="codigo_identificacion"  class="camposanchos" >
                    <option value="">Selecciones Opción</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_cbo_instituciones2['codigo_identificacion']?>"><?php echo $row_cbo_instituciones2['formas_conocidas_nombre']?></option>
                    <?php
} while ($row_cbo_instituciones2 = mysql_fetch_assoc($cbo_instituciones2));
  $rows = mysql_num_rows($cbo_instituciones2);
  if($rows > 0) {
      mysql_data_seek($cbo_instituciones2, 0);
	  $row_cbo_instituciones2 = mysql_fetch_assoc($cbo_instituciones2);
  }
?>
                  </select>
                  <input name="idusuario" type="hidden" id="idusuario" value="<?php echo $_POST['idusuario']; ?>"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
           </td>
		  </tr>
          <tr>
    	      <td class="celdabotones1"><table width="650" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="473"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="Enviar" type="submit" class="botongrabar" value="Asignar">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
          </table></td>
          </tr>
          <tr>
          <td class="celdapieazul"></td>
        </tr> 
       </table>
      <input type="hidden" name="MM_insert" value="form3">
    </form><?php } ?></td>
  </tr>
  <tr>
    <td>
    <?php if(isset($_POST['accion']) && $_POST['accion'] == "roles") { ?>
    <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Roles Asignados</td>
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
    	              <td colspan="2" class="tituloscampos ins_celdacolor"><?php echo $row_rel_usuarios_roles_instituciones['formas_conocidas_nombre']; ?></td>
    	              <td width="24" align="center" class="tituloscampos ins_celdacolor">&nbsp;</td>
  	              </tr>
    	            <tr>
                <td width="46" align="center" valign="top" class="tituloscampos ins_celdacolor"><img src="images/curva.png" width="25" height="11"></td>
    	            <td width="560" class="tituloscampos ins_celdacolor"><?php echo $row_rel_usuarios_roles_instituciones['rol']; ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'accion':'eliminarrol', 'elm':'<?php echo $row_rel_usuarios_roles_instituciones['idrel_usuarios_roles_instituciones']?>','idusuario':'<?PHP echo $_POST['idusuario']; ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a></td>
  	              </tr>
    	         <tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_rel_usuarios_roles_instituciones = mysql_fetch_assoc($rel_usuarios_roles_instituciones)); ?>
           </table>
           <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormayor"></td>
  	          </tr> 
              <tr>
    	          <td class="separadormayor"><hr></td>
  	          </tr>   
              <tr>
    	          <td class="separadormayor"></td>
  	          </tr>                  
                <tr>
                  <td class="tituloscampos ins_celdacolor">Instituciones disponibles:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><select name="idrel_usuarios_instituciones" id="idrel_usuarios_instituciones"  class="camposanchos" >
                    <option value="">Selecciones Opción</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_cbo_instituciones3['idrel_usuarios_instituciones']?>"><?php echo $row_cbo_instituciones3['formas_conocidas_nombre']?></option>
                    <?php
} while ($row_cbo_instituciones3 = mysql_fetch_assoc($cbo_instituciones3));
  $rows = mysql_num_rows($cbo_instituciones3);
  if($rows > 0) {
      mysql_data_seek($cbo_instituciones3, 0);
	  $row_cbo_instituciones3 = mysql_fetch_assoc($cbo_instituciones3);
  }
?>
                  </select>
                  <input name="idusuario" type="hidden" id="idusuario" value="<?php echo $_POST['idusuario']; ?>"></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Roles disponibles:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><select name="idrol" id="idrol"  class="camposanchos" >
                    <option value="">Selecciones Opción</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_roles['idrol']?>"><?php echo $row_roles['rol']?></option>
                    <?php
} while ($row_roles = mysql_fetch_assoc($roles));
  $rows = mysql_num_rows($roles);
  if($rows > 0) {
      mysql_data_seek($roles, 0);
	  $row_roles = mysql_fetch_assoc($roles);
  }
?>
                  </select></td>
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
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="b3" type="submit" class="botongrabar" id="b3" onClick="relocate('<?php echo $editFormAction; ?>',{'nuevo':'nuevo','institucion':'<?php if(isset($_POST['institucion'])) { echo $_POST['institucion']; } ?>'});" value="Asignar">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
          </table></td>
          </tr>
          <tr>
          <td class="celdapieazul"></td>
        </tr> 
       </table>
      <input type="hidden" name="MM_insert" value="form4">
    </form>
    <?php } ?></td>
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
<?php } ?>
</body>
</html>
<?php
mysql_free_result($instituciones);

mysql_free_result($rel_usuarios_instituciones);

mysql_free_result($usuarios);

mysql_free_result($usuarios_instituciones);

mysql_free_result($cbo_instituciones2);

mysql_free_result($cbo_instituciones3);

mysql_free_result($roles);

mysql_free_result($rel_usuarios_roles_instituciones);
?>
