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

/*borramos tipo de institucion que no se hayan hutilizados aun */
if (isset($_POST['elm']) &&  $_POST['elm'] != "" ) {
	$deleteSQL1 = sprintf("DELETE FROM transportes WHERE transporte=%s",
                       GetSQLValueString($_POST['elm'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = $editFormAction;
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* se insertan o modifican en cascada los registros a la tabla tipo_institucion*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	if(isset($_POST["texto_aux"]) && $_POST["texto_aux"] == "") {	
  		$insertOrUpdateSQL = sprintf("INSERT INTO transportes (transporte, nombre, direccion, codigo_postal, pais, telefono, mail, contacto) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
					 GetSQLValueString($_POST['texto2'], "text"),
					 GetSQLValueString($_POST['texto3'], "text"),
					 GetSQLValueString($_POST['texto4'], "text"),
					 GetSQLValueString($_POST['texto5'], "text"),
					 GetSQLValueString($_POST['texto6'], "text"),
					 GetSQLValueString($_POST['texto7'], "text"),
					 GetSQLValueString($_POST['texto8'], "text"),
					 GetSQLValueString($_POST['texto9'], "text"));
	} else {
		$insertOrUpdateSQL = sprintf("UPDATE transportes SET transporte=%s, nombre%s, direccion%s, codigo_postal%s, pais%s, telefono%s, mail%s, contacto%s  WHERE transporte=%s",
					 GetSQLValueString($_POST['texto2'], "text"),
					 GetSQLValueString($_POST['texto3'], "text"),
					 GetSQLValueString($_POST['texto4'], "text"),
					 GetSQLValueString($_POST['texto5'], "text"),
					 GetSQLValueString($_POST['texto6'], "text"),
					 GetSQLValueString($_POST['texto7'], "text"),
					 GetSQLValueString($_POST['texto8'], "text"),
					 GetSQLValueString($_POST['texto9'], "text"),
					 GetSQLValueString($_POST['texto_aux'], "text"));
		
	}
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());
 
  $insertOrUpdateGoTo = $editFormAction;
  header(sprintf("Location: %s", $insertOrUpdateGoTo));
}

/* se leen los registros a la tabla para la grilla de muestra */
mysql_select_db($database_conn, $conn);
$query_vis_load = "SELECT *, (select count(*) from documentos_prestamos WHERE documentos_prestamos.transporte = transportes.transporte) as cant FROM transportes ORDER BY nombre ASC";
$vis_load = mysql_query($query_vis_load, $conn) or die(mysql_error());
$row_vis_load = mysql_fetch_assoc($vis_load);
$totalRows_vis_load = mysql_num_rows($vis_load);

$colname_vis_loadaux = "-1";
if (isset($_POST['mod'])) {
  $colname_vis_loadaux = $_POST['mod'];
}
/* se buscan los datos si hay opcion de modificar */
mysql_select_db($database_conn, $conn);
$query_vis_loadaux = sprintf("SELECT * FROM transportes WHERE transporte = %s", GetSQLValueString($colname_vis_loadaux, "text"));
$vis_loadaux = mysql_query($query_vis_loadaux, $conn) or die(mysql_error());
$row_vis_loadaux = mysql_fetch_assoc($vis_loadaux);
$totalRows_vis_loadaux = mysql_num_rows($vis_loadaux);

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
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Transporte</td>
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
    	            <td width="582" class="tituloscampos ins_celdacolor"><?php echo "&nbsp;&nbsp;".$row_vis_load['transporte']; ?> <em>(<?php echo $row_vis_load['nombre']; ?>)</em></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'mod':'<?php echo $row_vis_load['transporte']   ?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><?php if($row_vis_load['cant'] <= 0 && $row_vis_load['cant'] != "") { ?><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'elm':'<?php echo $row_vis_load['transporte']   ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr> <?php } while ($row_vis_load = mysql_fetch_assoc($vis_load)); ?>
           </table></td>
		  </tr>
		<tr>
		  <td class="celdapiesimple">&nbsp;</td>
		  </tr>
	</table>
   </td>
  </tr>
  <tr>
    <td><form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
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
                  <td class="tituloscampos ins_celdacolor">Transporte:<span class="ins_celdacolor">
                    <input name="texto_aux" type="hidden" id="texto_aux" value="<?php echo $row_vis_loadaux['transporte']; ?>">
                  </span></td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="texto2" type="text" class="camposanchos" id="texto2" value="<?php echo $row_vis_loadaux['transporte']; ?>"  /></td>
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
                  <td class="tituloscampos ins_celdacolor"><input name="texto3" type="text" id="textfield7" class="camposanchos" value="<?php echo $row_vis_loadaux['nombre']; ?>"></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Direccion:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor"><input name="texto4" type="text" id="texto4" class="camposanchos" value="<?php echo $row_vis_loadaux['direccion']; ?>"></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">C&oacute;digo Postal:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="texto5" type="text" class="camposanchos" id="texto5" value="<?php echo $row_vis_loadaux['codigo_postal']; ?>"  /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Pa&iacute;s:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="texto6" type="text" class="camposanchos" id="texto6" value="<?php echo $row_vis_loadaux['pais']; ?>"  /></td>
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
                  <td class="ins_celdacolor"><input name="texto7" type="text" class="camposanchos" id="texto7" value="<?php echo $row_vis_loadaux['telefono']; ?>"  /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Mail:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="texto8" type="text" class="camposanchos" id="texto8" value="<?php echo $row_vis_loadaux['mail']; ?>"  /></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Caontacto:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor">
                    <input name="texto9" type="text" class="camposanchos" id="texto9" value="<?php echo $row_vis_loadaux['contacto']; ?>"  />
                  </td>
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
</table>
</body>
</html>
<?php
mysql_free_result($vis_load);

mysql_free_result($vis_loadaux);
?>
