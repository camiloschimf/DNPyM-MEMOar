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


/* desactivo a una rol */
if (isset($_POST['elm']) &&  $_POST['elm'] != "" ) {
	$deleteSQL1 = sprintf("UPDATE roles SET activo=%s WHERE idrol=%s",
                       0,
					   GetSQLValueString($_POST["elm"], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = $editFormAction;
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* se insertan o modifican en cascada los registros a la tabla roles*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	if(isset($_POST["idrol"]) && $_POST["idrol"] == "") {	
  		$insertOrUpdateSQL = sprintf("INSERT INTO roles (rol) VALUES (%s)",
                       GetSQLValueString($_POST['rol'], "text"));
	} else {
		$insertOrUpdateSQL = sprintf("UPDATE roles SET rol=%s WHERE idrol=%s",
                       GetSQLValueString($_POST['rol'], "text"),
					   GetSQLValueString($_POST['idrol'], "int"));
	}
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());
 
  $insertOrUpdateGoTo = $editFormAction;
  header(sprintf("Location: %s", $insertOrUpdateGoTo));
}


/* se leen los registros a la tabla para la grilla de muestra */
mysql_select_db($database_conn, $conn);
$query_vis_load = "SELECT  roles.* FROM roles WHERE roles.activo=1 ORDER BY roles.idrol ASC";
$vis_load = mysql_query($query_vis_load, $conn) or die(mysql_error());
$row_vis_load = mysql_fetch_assoc($vis_load);
$totalRows_vis_load = mysql_num_rows($vis_load);

$colname_vis_load_aux = "-1";
if (isset($_POST['mod'])) {
  $colname_vis_load_aux = $_POST['mod'];
}
mysql_select_db($database_conn, $conn);
$query_vis_load_aux = sprintf("SELECT * FROM roles WHERE idrol = %s", GetSQLValueString($colname_vis_load_aux, "int"));
$vis_load_aux = mysql_query($query_vis_load_aux, $conn) or die(mysql_error());
$row_vis_load_aux = mysql_fetch_assoc($vis_load_aux);
$totalRows_vis_load_aux = mysql_num_rows($vis_load_aux);
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
    	          <td class="ins_titulomayor">Roles</td>
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
    	            <td width="582" height="18" class="tituloscampos ins_celdacolor"><?php echo $row_vis_load['rol']; ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><?php if($row_vis_load['idrol'] != 1 && $row_vis_load['idrol'] != 2) {?><a onClick="relocate('<?php echo $editFormAction; ?>',{'mod':'<?php echo $row_vis_load['idrol'] ?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a><?php } ?></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><?php if($row_vis_load['idrol'] != 1 && $row_vis_load['idrol'] != 2) {?><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'elm':'<?php echo $row_vis_load['idrol']?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
  	              </tr>
    	         
<tr>
    	          <td colspan="3" class="separadormenor"></td>
  	          </tr>
              <?php } while ($row_vis_load = mysql_fetch_assoc($vis_load)); ?>
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
                  <td class="tituloscampos ins_celdacolor">Rol:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><input name="rol" type="text" class="camposanchos" id="rol" value="<?php echo $row_vis_load_aux['rol']; ?>" /></td>
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
      
      <input name="idrol" type="hidden" id="idrol" value="<?php echo $row_vis_load_aux['idrol']; ?>">      
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

mysql_free_result($vis_load_aux);
?>
