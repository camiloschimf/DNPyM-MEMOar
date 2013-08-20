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
	$deleteSQL1 = sprintf("DELETE FROM envases WHERE idenvases=%s",
                       GetSQLValueString($_POST['elm'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL1, $conn) or die(mysql_error()); 
  
  $deleteGoTo = $editFormAction;
  header(sprintf("Location: %s", $deleteGoTo)); 
}

/* se insertan o modifican en cascada los registros a la tabla tipo_institucion*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	if(isset($_POST["texto_aux"]) && $_POST["texto_aux"] == "") {	
  		$insertOrUpdateSQL = sprintf("INSERT INTO envases (material, dimension, autor) VALUES (%s,%s,%s)",
					 GetSQLValueString($_POST['texto2'], "text"),
					 GetSQLValueString($_POST['texto3'], "text"),
					 GetSQLValueString($_POST['texto4'], "text"));
	} else {
		$insertOrUpdateSQL = sprintf("UPDATE envases SET material=%s, dimension%s, autor%s  WHERE idenvases=%s",
					 GetSQLValueString($_POST['texto2'], "text"),
					 GetSQLValueString($_POST['texto3'], "text"),
					 GetSQLValueString($_POST['texto4'], "text"),
					 GetSQLValueString($_POST['texto_aux'], "text"));
		
	}
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertOrUpdateSQL, $conn) or die(mysql_error());
 
  $insertOrUpdateGoTo = $editFormAction;
  header(sprintf("Location: %s", $insertOrUpdateGoTo));
}

/* se leen los registros a la tabla para la grilla de muestra */
mysql_select_db($database_conn, $conn);
$query_vis_load = "SELECT *, (select count(*) from rel_documentos_envases WHERE rel_documentos_envases.idenvases = envases.idenvases) as cant FROM envases ORDER BY material ASC";
$vis_load = mysql_query($query_vis_load, $conn) or die(mysql_error());
$row_vis_load = mysql_fetch_assoc($vis_load);
$totalRows_vis_load = mysql_num_rows($vis_load);

$colname_vis_loadaux = "-1";
if (isset($_POST['mod'])) {
  $colname_vis_loadaux = $_POST['mod'];
}
/* se buscan los datos si hay opcion de modificar */
mysql_select_db($database_conn, $conn);
$query_vis_loadaux = sprintf("SELECT * FROM envases WHERE idenvases = %s", GetSQLValueString($colname_vis_loadaux, "text"));
$vis_loadaux = mysql_query($query_vis_loadaux, $conn) or die(mysql_error());
$row_vis_loadaux = mysql_fetch_assoc($vis_loadaux);
$totalRows_vis_loadaux = mysql_num_rows($vis_loadaux);

mysql_select_db($database_conn, $conn);
$query_lista1 = "SELECT * FROM materiales ORDER BY material ASC";
$lista1 = mysql_query($query_lista1, $conn) or die(mysql_error());
$row_lista1 = mysql_fetch_assoc($lista1);
$totalRows_lista1 = mysql_num_rows($lista1);

mysql_select_db($database_conn, $conn);
$query_lista2 = "SELECT * FROM autores ORDER BY autor ASC";
$lista2 = mysql_query($query_lista2, $conn) or die(mysql_error());
$row_lista2 = mysql_fetch_assoc($lista2);
$totalRows_lista2 = mysql_num_rows($lista2);

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
    	          <td class="ins_titulomayor">Envases</td>
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
    	            <td width="582" class="tituloscampos ins_celdacolor"><?php echo "&nbsp;&nbsp;".$row_vis_load['material']."-".$row_vis_load['dimension']; ?> <em>(<?php echo $row_vis_load['autor']; ?>)</em></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><a onClick="relocate('<?php echo $editFormAction; ?>',{'mod':'<?php echo $row_vis_load['idenvases']   ?>'});"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
    	            <td width="24" align="center" class="tituloscampos ins_celdacolor"><?php if($row_vis_load['cant'] <= 0 && $row_vis_load['cant'] != "") { ?><a onDblClick="relocate('<?php echo $editFormAction; ?>',{'elm':'<?php echo $row_vis_load['idenvases']   ?>'});"><img src="images/ico_002.png" alt="Eliminar"  height="18" border="0"></a><?php } ?></td>
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
                  <td class="tituloscampos ins_celdacolor">Material:<span class="ins_celdacolor">
                    <input name="texto_aux" type="hidden" id="texto_aux" value="<?php echo $row_vis_loadaux['idenvases']; ?>">
                  </span></td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="ins_celdacolor"><select name="texto2" id="texto2" class="camposanchos">
                    <option value="">Seleccione Opción</option>
					<?php
do {  
?>
                    <option value="<?php echo $row_lista1['material']?>"<?php if (!(strcmp($row_lista1['material'], $row_vis_loadaux['material']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista1['material']?></option>
                    <?php
} while ($row_lista1 = mysql_fetch_assoc($lista1));
  $rows = mysql_num_rows($lista1);
  if($rows > 0) {
      mysql_data_seek($lista1, 0);
	  $row_lista1 = mysql_fetch_assoc($lista1);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Dimensi&oacute;n:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor"><input name="texto3" type="text" id="textfield7" class="camposanchos" value="<?php echo $row_vis_loadaux['dimension']; ?>"></td>
                </tr>
                <tr>
                  <td class="separadormayor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Autor:</td>
                </tr>
                <tr>
                  <td class="separadormenor ins_celdacolor"></td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor"><span class="ins_celdacolor">
                    <select name="texto4" id="texto4" class="camposanchos">
                      <option value="" <?php if (!(strcmp("", $row_vis_loadaux['autor']))) {echo "selected=\"selected\"";} ?>>Seleccione Opci&oacute;n</option>
                      <?php
do {  
?>
                      <option value="<?php echo $row_lista2['autor']?>"<?php if (!(strcmp($row_lista2['autor'], $row_vis_loadaux['autor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista2['autor']?></option>
                      <?php
} while ($row_lista2 = mysql_fetch_assoc($lista2));
  $rows = mysql_num_rows($lista2);
  if($rows > 0) {
      mysql_data_seek($lista2, 0);
	  $row_lista2 = mysql_fetch_assoc($lista2);
  }
?>
                    </select>
                  </span></td>
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

mysql_free_result($lista1);

mysql_free_result($lista2);
?>
