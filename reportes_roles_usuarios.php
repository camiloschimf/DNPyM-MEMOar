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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Busqueda = 50;
$pageNum_Busqueda = 0;
if (isset($_GET['pageNum_Busqueda'])) {
  $pageNum_Busqueda = $_GET['pageNum_Busqueda'];
}
$startRow_Busqueda = $pageNum_Busqueda * $maxRows_Busqueda;

$codigo_institucion = "-1";
if((isset($_POST['MM_search']) && $_POST['MM_search'] == "form2") || !empty($_POST['codigo_institucion'])) {
		$codigo_institucion = " AND rel_usuarios_instituciones.codigo_identificacion = '".$_POST['codigo_institucion']."' ";
}

mysql_select_db($database_conn, $conn);
$query_Busqueda = "SELECT 
roles.idrol, 
roles.rol, 
rel_usuarios_instituciones.codigo_identificacion  
FROM usuarios 
LEFT JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.idusuario=usuarios.idusuario 
LEFT JOIN rel_usuarios_roles_instituciones ON rel_usuarios_roles_instituciones.idrel_usuarios_instituciones=rel_usuarios_instituciones.idrel_usuarios_instituciones 
LEFT JOIN roles ON roles.idrol=rel_usuarios_roles_instituciones.idrol 
WHERE usuarios.activo=1 
".$codigo_institucion."
GROUP BY roles.rol 
ORDER BY roles.rol ASC";
$query_limit_Busqueda = sprintf("%s LIMIT %d, %d", $query_Busqueda, $startRow_Busqueda, $maxRows_Busqueda);
$Busqueda = mysql_query($query_limit_Busqueda, $conn) or die(mysql_error());
$row_Busqueda = mysql_fetch_assoc($Busqueda);


if (isset($_GET['totalRows_Busqueda'])) {
  $totalRows_Busqueda = $_GET['totalRows_Busqueda'];
} else {
  $all_Busqueda = mysql_query($query_Busqueda);
  $totalRows_Busqueda = mysql_num_rows($all_Busqueda);
}
$totalPages_Busqueda = ceil($totalRows_Busqueda/$maxRows_Busqueda)-1;


/* Traigo datos para imprimir */
mysql_select_db($database_conn, $conn);
$query_Imprimir = "SELECT 
usuarios.idusuario,
usuarios.apellido, 
roles.idrol, 
roles.rol, 
rel_usuarios_instituciones.codigo_identificacion  
FROM usuarios 
LEFT JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.idusuario=usuarios.idusuario 
LEFT JOIN rel_usuarios_roles_instituciones ON rel_usuarios_roles_instituciones.idrel_usuarios_instituciones=rel_usuarios_instituciones.idrel_usuarios_instituciones 
LEFT JOIN roles ON roles.idrol=rel_usuarios_roles_instituciones.idrol 
WHERE usuarios.activo=1 
".$codigo_institucion."
GROUP BY roles.rol 
ORDER BY roles.rol ASC";
$Imprimir = mysql_query($query_Imprimir, $conn) or die(mysql_error());
$row_Imprimir = mysql_fetch_assoc($Imprimir);
$totalRows_Imprimir = mysql_num_rows($Imprimir);


mysql_select_db($database_conn, $conn);
$query_vis_load_instituciones = "SELECT codigo_identificacion, formas_conocidas_nombre FROM instituciones ORDER BY formas_conocidas_nombre ASC";
$vis_load_instituciones = mysql_query($query_vis_load_instituciones, $conn) or die(mysql_error());
$row_vis_load_instituciones = mysql_fetch_assoc($vis_load_instituciones);
$totalRows_vis_load_instituciones = mysql_num_rows($vis_load_instituciones);

$colname_vis_load_usuarios = "";
if (!empty($_POST['codigo_institucion'])) {
  $colname_vis_load_usuarios = "WHERE codigo_identificacion = '".$_POST['codigo_institucion']."'";
}
mysql_select_db($database_conn, $conn);
$query_vis_load_usuarios = sprintf("SELECT 
usuarios.idusuario, 
usuarios.nombre, 
usuarios.apellido, 
roles.idrol, 
roles.rol, 
rel_usuarios_instituciones.codigo_identificacion  
FROM usuarios 
LEFT JOIN rel_usuarios_instituciones ON rel_usuarios_instituciones.idusuario=usuarios.idusuario 
LEFT JOIN rel_usuarios_roles_instituciones ON rel_usuarios_roles_instituciones.idrel_usuarios_instituciones=rel_usuarios_instituciones.idrel_usuarios_instituciones 
LEFT JOIN roles ON roles.idrol=rel_usuarios_roles_instituciones.idrol 
".$colname_vis_load_usuarios."
ORDER BY roles.rol ASC");
$vis_load_usuarios = mysql_query($query_vis_load_usuarios, $conn) or die(mysql_error());
$row_vis_load_usuarios = mysql_fetch_assoc($vis_load_usuarios);
$totalRows_vis_load_usuarios = mysql_num_rows($vis_load_usuarios);
if($totalRows_vis_load_usuarios > 0) {
	do {
		$rows_usuarios[] = $row_vis_load_usuarios;
	} while($row_vis_load_usuarios = mysql_fetch_assoc($vis_load_usuarios));
}
//echo '<pre>'; print_r($rows_usuarios); echo '</pre>';


$queryString_Busqueda = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Busqueda") == false && 
        stristr($param, "totalRows_Busqueda") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Busqueda = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Busqueda = sprintf("&totalRows_Busqueda=%d%s", $totalRows_Busqueda, $queryString_Busqueda);

if ($totalRows_Busqueda == 0) {
	
}

if(!empty($_POST['codigo_institucion'])) {
	mysql_select_db($database_conn, $conn);
	$query_vis_load_instituciones_aux = "SELECT formas_conocidas_nombre FROM instituciones WHERE codigo_identificacion='".$_POST['codigo_institucion']."'";
	$vis_load_instituciones_aux = mysql_query($query_vis_load_instituciones_aux, $conn) or die(mysql_error());
	$row_vis_load_instituciones_aux = mysql_fetch_assoc($vis_load_instituciones_aux);
	$totalRows_vis_load_instituciones_aux = mysql_num_rows($vis_load_instituciones_aux);	
}

?>
<?php if(!empty($_POST['imprimir'])) {require_once('reportes_roles_usuarios_imprimir.php');} ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ibm852" />
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
</style>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
</head>

<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="celdatituloazul_c ins_titulomayor"><form name="form2" method="POST" action="<?php echo $currentPage; ?>">
      <table width="580" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="ins_titulomayor">&nbsp;&nbsp;&nbsp;Roles y Usuarios</td>
        </tr>
        <tr>
          <td class="fondolineaszulesvert_c"><table width="540" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">Instituci&oacute;n:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                <tr>
                  <td class=""><select style="width:540px;" name="codigo_institucion" id="codigo_institucion">
                  <option value=""> Seleccione </option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_vis_load_instituciones['codigo_identificacion']?>"  <?PHP  if(!empty($_POST['codigo_institucion']) && $_POST['codigo_institucion'] == $row_vis_load_instituciones['codigo_identificacion']) {  echo "selected"; } ?>><?php echo $row_vis_load_instituciones['formas_conocidas_nombre']?></option>
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
                
              <tr>
              	<td>&nbsp;</td>
              </tr>
           </table></td>
        </tr>
        <tr>
    	      <td class="celdabotones1_c"><table width="570" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="403"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button3" type="submit" class="botongrabar" id="button3" value="Buscar">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
          </tr>
        <tr>
          <td class="celdapieazul_c"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_search" value="form2">
    </form></td>
  </tr>
  
  <?php if($totalRows_Busqueda > 0) {?>
  <tr>
    <td>
    <table width="580" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Roles</td>
    	          <td class="ins_titulomayor">
                    <table border="0" align="right">
                      <tr>
                        <td><?php if ($pageNum_Busqueda > 0) { // Show if not first page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, 0, $queryString_Busqueda); ?>"><img src="First.gif" border="0"></a>
                        <?php } // Show if not first page ?></td>
                        <td><?php if ($pageNum_Busqueda > 0) { // Show if not first page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, max(0, $pageNum_Busqueda - 1), $queryString_Busqueda); ?>"><img src="Previous.gif" border="0"></a>
    <?php } // Show if not first page ?></td>
                            <td class="contadorbusqueda"><?php if($totalRows_Busqueda == 0) { echo $startRow_Busqueda; } else { echo ($startRow_Busqueda + 1); } ?> a <?php echo min($startRow_Busqueda + $maxRows_Busqueda, $totalRows_Busqueda) ?> de <?php echo $totalRows_Busqueda ?></td>
                        <td><?php if ($pageNum_Busqueda < $totalPages_Busqueda) { // Show if not last page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, min($totalPages_Busqueda, $pageNum_Busqueda + 1), $queryString_Busqueda); ?>"><img src="Next.gif" border="0"></a>
                        <?php } // Show if not last page ?></td>
                        <td><?php if ($pageNum_Busqueda < $totalPages_Busqueda) { // Show if not last page ?>
                            <a href="<?php printf("%s?pageNum_Busqueda=%d%s", $currentPage, $totalPages_Busqueda, $queryString_Busqueda); ?>"><img src="Last.gif" border="0"></a>
                        <?php } // Show if not last page ?></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert_c"><table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
  <td height="8" colspan="2" valign="top"></td>
		      </tr>
            
		    <?php do { ?>
		      <tr>
		        <td valign="top"  class="textosbusqueda"><strong><?php echo ucfirst($row_Busqueda['rol']); ?></strong></td>
                <td valign="middle"  class="textosbusqueda">
                <?php if($totalRows_vis_load_usuarios > 0) {?>
                	<table cellpadding="0" cellspacing="0">
					<?php foreach($rows_usuarios AS $usuario) {?>
                    	<?php if($row_Busqueda['idrol']==$usuario['idrol']) {?>
                            <tr><td  valign="top"  class="textosbusqueda"><strong><?php echo ucfirst($usuario['nombre']).' '.ucfirst($usuario['apellido'])?></strong></td></tr>
                    	<?php } else {?>
                    		<tr><td></td></tr>
                    	<?php }?>
                    <?php }?>
                    </table>
                <?php }?>
                </td>
		        </tr>
		      
<tr>
  <td height="8" colspan="2" valign="top"><?php if($totalRows_Busqueda > 1) {?><hr><?php }?></td>
		      </tr><?php } while ($row_Busqueda = mysql_fetch_assoc($Busqueda)); ?>
              
	      </table></td>
		  </tr>
          <?php if($totalRows_Busqueda > 0) {?>
        <tr>
    	      <td class="celdabotones1_c"><table width="570" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="403"></td>
    	          <td width="177" align="right" valign="middle" class="celdabotonera" style="padding-top:2px">
                  <?php 
				  $post_data="";
				  if(!empty($_POST['texto3d'])) {$post_data.=" ,'texto3d':'".$_POST['texto3d']."'";}
				  if(!empty($_POST['texto3m'])) {$post_data.=" ,'texto3m':'".$_POST['texto3m']."'";}
				  if(!empty($_POST['texto3a'])) {$post_data.=" ,'texto3a':'".$_POST['texto3a']."'";}
				  if(!empty($_POST['texto4d'])) {$post_data.=" ,'texto4d':'".$_POST['texto4d']."'";}
				  if(!empty($_POST['texto4m'])) {$post_data.=" ,'texto4m':'".$_POST['texto4m']."'";}
				  if(!empty($_POST['texto4a'])) {$post_data.=" ,'texto4a':'".$_POST['texto4a']."'";}				  
				  if(!empty($_POST['codigo_institucion'])) {$post_data.=" ,'codigo_institucion':'".$_POST['codigo_institucion']."'";}
				  if(!empty($_POST['MM_search'])) {$post_data.=" ,'MM_search':'form2'";}
				  $cod_institucion=!empty($_POST['codigo_institucion'])?$_POST['codigo_institucion']:"";
				  ?>
                  <a href="javascript:void(0)"><img src="images/print_1.png" title="imprimir EXCEL" border="0" width="24" height="24" onClick="relocate('<?php echo $currentPage; ?>',{'imprimir':'excel','codigo_institucion':'<?php echo $cod_institucion?>'});" /></a>
                  <a href="javascript:void(0)"><img src="images/print_2.png" title="imprimir WORD" border="0" width="24" height="24" onClick="relocate('<?php echo $currentPage; ?>',{'imprimir':'doc','codigo_institucion':'<?php echo $cod_institucion?>'});" /></a>
                  <a href="javascript:void(0)"><img src="images/print_3.png" title="imprimir PDF" border="0" width="24" height="24" onClick="relocate('<?php echo $currentPage; ?>',{'imprimir':'pdf','codigo_institucion':'<?php echo $cod_institucion?>'});" /></a>&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td>
          </tr>
          <?php }?>
        <tr>
          <td class="celdapieazul_c"></td>
        </tr>
	</table>
   </td>
  </tr>
  <?php }?>  
</table>
</body>
</html>
<?php
mysql_free_result($Busqueda);

mysql_free_result($Imprimir);

mysql_free_result($vis_load_instituciones);

mysql_free_result($vis_load_usuarios);
?>