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

/* borro relacion entre un usuario y grupo */
if (isset($_POST['elm']) &&  $_POST['elm'] != "" ) {
	$deleteSQL1 = sprintf("DELETE FROM rel_usuarios_grupos WHERE idrel_usuarios_grupos=%s",
                       GetSQLValueString($_POST['elm'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = $editFormAction;
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* se insertan los registros a la tabla rel_usuarios_grupos*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2") && !empty($_POST['idgrupo'])) {
	//print_r($_POST);exit;
  $insertSQL = sprintf("INSERT INTO rel_usuarios_grupos (idrel_usuarios_grupos, usuario, idgrupo) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['idrel_usuarios_grupos'], "int"),
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['idgrupo'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
 
  $insertGoTo = $editFormAction;
  header(sprintf("Location: %s", $insertGoTo)); 
}


/* se leen los registros a la tabla usuarios filtra por idusuario */
$colname_vis_load_usr_aux = "-1";
if (isset($_POST['usr_aux'])) {
  $colname_vis_load_usr_aux = $_POST['usr_aux'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_usr_aux = sprintf("SELECT idusuario, usuario, nombre, apellido FROM usuarios WHERE idusuario = %s", GetSQLValueString($colname_vis_load_usr_aux, "int"));
$vis_load_usr_aux = mysql_query($query_vis_load_usr_aux, $conn) or die(mysql_error());
$row_vis_load_usr_aux = mysql_fetch_assoc($vis_load_usr_aux);
$totalRows_vis_load_usr_aux = mysql_num_rows($vis_load_usr_aux);

$colname_vis_load_usuario_aux = "-1";  
$grupos_ids="";
if (isset($_POST['usuario_aux'])) {
    $colname_vis_load_usuario_aux = $_POST['usuario_aux'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_usuario_aux = sprintf("SELECT idgrupo FROM rel_usuarios_grupos WHERE usuario = %s", GetSQLValueString($colname_vis_load_usuario_aux, "text"));
$vis_load_usuario_aux = mysql_query($query_vis_load_usuario_aux, $conn) or die(mysql_error());
while($row_vis_load_usuario_aux = mysql_fetch_assoc($vis_load_usuario_aux)) {
	$grupos_ids[]=$row_vis_load_usuario_aux['idgrupo'];
}


/* se leen los registros a la tabla grupos filtra por idgrupo */
$colname_vis_load_aux = "-1";
if (isset($_POST['mod'])) {
  $colname_vis_load_aux = $_POST['mod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_aux = sprintf("SELECT * FROM grupos WHERE idgrupo = %s", GetSQLValueString($colname_vis_load_aux, "int"));
$vis_load_aux = mysql_query($query_vis_load_aux, $conn) or die(mysql_error());
$row_vis_load_aux = mysql_fetch_assoc($vis_load_aux);
$totalRows_vis_load_aux = mysql_num_rows($vis_load_aux);

/* se leen los registros a la tabla codigo_identificacion */
mysql_select_db($database_conn, $conn);
$query_vis_load_instituciones = "SELECT * FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$vis_load_instituciones = mysql_query($query_vis_load_instituciones, $conn) or die(mysql_error());
$row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones);
$totalRows_vis_load_instituciones = mysql_num_rows($vis_load_instituciones);


$colname_vis_load_usuarios_grupos = "-1";
if (isset($_POST['sel_codigo_identificacion'])) {
  $_SESSION['sess_sel_codigo_identificacion'] = $_POST['sel_codigo_identificacion'];
}
if(isset($_SESSION['sess_sel_codigo_identificacion'])) {
	$colname_vis_load_usuarios_grupos = $_SESSION['sess_sel_codigo_identificacion'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_usuarios_grupos = sprintf("SELECT 
usuarios.idusuario, 
usuarios.usuario, 
usuarios.nombre, 
usuarios.apellido, 
grupos.codigo_identificacion, 
grupos.idgrupo, 
grupos.grupo, 
rel_usuarios_grupos.idrel_usuarios_grupos 
FROM instituciones 
INNER JOIN grupos ON grupos.codigo_identificacion=instituciones.codigo_identificacion 
LEFT JOIN rel_usuarios_grupos ON rel_usuarios_grupos.idgrupo=grupos.idgrupo 
LEFT JOIN usuarios ON usuarios.usuario=rel_usuarios_grupos.usuario 
WHERE grupos.codigo_identificacion = %s AND rel_usuarios_grupos.usuario IS NOT NULL ORDER BY grupos.grupo ASC", GetSQLValueString($colname_vis_load_usuarios_grupos, "text"));
$vis_load_usuarios_grupos = mysql_query($query_vis_load_usuarios_grupos, $conn) or die(mysql_error());
$row_vis_load_usuarios_grupos = mysql_fetch_assoc($vis_load_usuarios_grupos);
$totalRows_vis_load_usuarios_grupos = mysql_num_rows($vis_load_usuarios_grupos);
/*
mysql_select_db($database_conn, $conn);
$query_vis_load_usuarios_grupos = sprintf("SELECT 
usuarios.idusuario, 
usuarios.usuario, 
usuarios.nombre, 
usuarios.apellido, 
rel_usuarios_instituciones.codigo_identificacion, 
grupos.idgrupo, 
grupos.grupo, 
rel_usuarios_grupos.idrel_usuarios_grupos 
FROM instituciones 
INNER JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.codigo_identificacion=instituciones.codigo_identificacion 
LEFT JOIN usuarios ON usuarios.idusuario=rel_usuarios_instituciones.idusuario  
LEFT JOIN rel_usuarios_grupos ON usuarios.usuario=rel_usuarios_grupos.usuario 
LEFT JOIN grupos ON rel_usuarios_grupos.idgrupo=grupos.idgrupo 
WHERE rel_usuarios_instituciones.codigo_identificacion = %s AND rel_usuarios_grupos.usuario IS NOT NULL ORDER BY grupos.grupo ASC", GetSQLValueString($colname_vis_load_usuarios_grupos, "text"));
$vis_load_usuarios_grupos = mysql_query($query_vis_load_usuarios_grupos, $conn) or die(mysql_error());
$row_vis_load_usuarios_grupos = mysql_fetch_assoc($vis_load_usuarios_grupos);
$totalRows_vis_load_usuarios_grupos = mysql_num_rows($vis_load_usuarios_grupos);
*/

$colname_vis_load_usuarios = "-1";
if (isset($_POST['sel_codigo_identificacion'])) {
  $colname_vis_load_usuarios = $_POST['sel_codigo_identificacion'];
}
if(isset($_SESSION['sess_sel_codigo_identificacion'])) {
	$colname_vis_load_usuarios = $_SESSION['sess_sel_codigo_identificacion'];
}

mysql_select_db($database_conn, $conn);
$query_vis_load_usuarios = sprintf("SELECT 
usuarios.idusuario, 
usuarios.usuario, 
usuarios.nombre, 
usuarios.apellido, 
rel_usuarios_instituciones.codigo_identificacion, 
grupos.idgrupo, 
grupos.grupo, 
rel_usuarios_grupos.idrel_usuarios_grupos 
FROM instituciones 
INNER JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.codigo_identificacion=instituciones.codigo_identificacion 
LEFT JOIN usuarios ON usuarios.idusuario=rel_usuarios_instituciones.idusuario  
LEFT JOIN rel_usuarios_grupos ON usuarios.usuario=rel_usuarios_grupos.usuario 
LEFT JOIN grupos ON rel_usuarios_grupos.idgrupo=grupos.idgrupo 
WHERE rel_usuarios_instituciones.codigo_identificacion = %s GROUP BY usuarios.idusuario ORDER BY usuarios.nombre ASC", GetSQLValueString($colname_vis_load_usuarios, "text"));
$vis_load_usuarios = mysql_query($query_vis_load_usuarios, $conn) or die(mysql_error());
$row_vis_load_usuarios = mysql_fetch_assoc($vis_load_usuarios);
$totalRows_vis_load_usuarios = mysql_num_rows($vis_load_usuarios);



/* se leen los registros a la tabla grupos filtra por codigo_identificacion */
$colname_vis_load_grupos = "-1";
if (isset($_POST['sel_codigo_identificacion'])) {
  $colname_vis_load_grupos = $_POST['sel_codigo_identificacion'];
}
if(isset($_SESSION['sess_sel_codigo_identificacion'])) {
	$colname_vis_load_grupos = $_SESSION['sess_sel_codigo_identificacion'];
}
mysql_select_db($database_conn, $conn);
$arr_grupos=is_array($grupos_ids)?implode(",", $grupos_ids):"0";
$query_vis_load_grupos = sprintf("SELECT * FROM grupos WHERE codigo_identificacion = %s AND idgrupo NOT IN($arr_grupos) ORDER BY grupo ASC", GetSQLValueString($colname_vis_load_grupos, "text"));
$vis_load_grupos = mysql_query($query_vis_load_grupos, $conn) or die(mysql_error());
$row_vis_load_grupos = mysql_fetch_assoc($vis_load_grupos);
$totalRows_vis_load_grupos = mysql_num_rows($vis_load_grupos);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>
                <tr>
                  <td colspan="4" class="tituloscampos ins_celdacolor">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4" class="separadormenor ins_celdacolor"></td>
                </tr>              
				<tr>
                  <td colspan="4" class="ins_celdacolor">
                  <form name="form3" method="POST" action="">
                  <select name="sel_codigo_identificacion" class="camposanchos" id="sel_codigo_identificacion" onchange="submit()">
                  <option value=""> Seleccione </option>
                    <?php do { ?>
                    <?php if(!empty($_POST['sel_codigo_identificacion']) || isset($_SESSION['sess_sel_codigo_identificacion'])) {$sel_codigo_identificacion = $_SESSION['sess_sel_codigo_identificacion'];} else {$sel_codigo_identificacion = "";}?>
                    <option value="<?php  echo $row_vis_load_instituciones['codigo_identificacion']?>" <?php echo $sel_codigo_identificacion==$row_vis_load_instituciones['codigo_identificacion']?'selected':''; ?> onClick="relocate('<?php echo $editFormAction; ?>',{'sel_codigo_identificacion':'<?php echo $row_vis_load_instituciones['codigo_identificacion']?>'});"><?php echo $row_vis_load_instituciones['formas_conocidas_nombre']; ?> - <?php  echo $row_vis_load_instituciones['codigo_identificacion']?></option>
                    <?php
					} while ($row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones));
					  $rows = mysql_num_rows($vis_load_instituciones);
					  if($rows > 0) {
						  mysql_data_seek($vis_load_instituciones, 0);
						  $row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones);
					  }
					?>
                  </select>
                  </form>
                  </td>
                </tr>              
    	        <tr>
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>     
		</table></td>
		  </tr>
		<tr>
		  <td class="celdapiesimple">&nbsp;</td>
		  </tr>
	</table>    
    <?php if($totalRows_vis_load_usuarios_grupos>0) {?>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Grupos</td>
  	          </tr>
  	        </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>
                 
    	        <?php do { ?>
    	          <tr>
    	            <td width="292" class="tituloscampos ins_celdacolor"><?php echo $row_vis_load_usuarios_grupos['grupo']; ?></td>
                    <td width="292" class="tituloscampos ins_celdacolor"><?php echo $row_vis_load_usuarios_grupos['nombre']; ?> <?php echo $row_vis_load_usuarios_grupos['apellido']; ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor">&nbsp;</td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'elm':'<?php echo $row_vis_load_usuarios_grupos['idrel_usuarios_grupos']?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_vis_load_usuarios_grupos = mysql_fetch_assoc($vis_load_usuarios_grupos)); ?>
              
           </table></td>
		  </tr>
		<tr>
		  <td class="celdapiesimple">&nbsp;</td>
		  </tr>
	</table>
    <?php }?>
   </td>
  </tr>
  <?php if($totalRows_vis_load_usuarios>0) {?>
  <tr>
    <td><form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
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
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>
                 
    	        <?php do { ?>
    	          <tr>
                    <td class="tituloscampos ins_celdacolor"><?php echo ucfirst($row_vis_load_usuarios['nombre']) ?> <?php echo ucfirst($row_vis_load_usuarios['apellido']) ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'usr_aux':'<?php echo $row_vis_load_usuarios['idusuario']?>', 'usuario_aux':'<?php echo $row_vis_load_usuarios['usuario']?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="4" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_vis_load_usuarios = mysql_fetch_assoc($vis_load_usuarios)); ?>
              
           </table></td>
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
                  <td class="ins_celdacolor"><input name="" type="text" class="camposanchos" value="<?php echo ucfirst($row_vis_load_usr_aux['nombre']) ?> <?php echo ucfirst($row_vis_load_usr_aux['apellido'])?>" readonly /><input name="usuario" id="usuario" type="hidden" class="camposanchos" value="<?php echo $row_vis_load_usr_aux['usuario'] ?>" /></td>
                </tr>          
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>                
                <tr>
                  <td class="tituloscampos ins_celdacolor">Grupos:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor">
				<select name="idgrupo" class="camposanchos" id="idgrupo">
                  <option value=""> Seleccione </option>
                    <?php if(!empty($row_vis_load_usr_aux['usuario'])) {?>
                    <?php do { ?>
                    <option value="<?php  echo $row_vis_load_grupos['idgrupo']?>"><?php echo $row_vis_load_grupos['grupo']?></option>
                    <?php
					} while ($row_vis_load_grupos = mysql_fetch_assoc($vis_load_grupos));
					  $rows = mysql_num_rows($vis_load_grupos);
					  if($rows > 0) {
						  mysql_data_seek($vis_load_grupos, 0);
						  $row_vis_load_grupos = mysql_fetch_assoc($vis_load_grupos);
					  }
					?>
                    <?php }?>
                  </select>                  
                  </td>
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
      <input type="hidden" name="MM_insert" value="form2">
    </form></td>
  </tr>
  <?php }?>
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
mysql_free_result($vis_load_aux);

mysql_free_result($vis_load_usr_aux);

mysql_free_result($vis_load_usuario_aux);

mysql_free_result($vis_load_grupos);

mysql_free_result($vis_load_instituciones);

mysql_free_result($vis_load_usuarios);

mysql_free_result($vis_load_usuarios_grupos);
?>
