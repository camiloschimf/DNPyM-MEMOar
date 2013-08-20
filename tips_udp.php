<?php require_once('Connections/conn.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
 if(!isset($_POST['requerido'])) {
	 $requerido=1;
 } else {
	 $requerido=$_POST['requerido'];
 }
	
  $updateSQL = sprintf("UPDATE tips SET tip=%s, requerido=%s, item=%s, d8=%s, d9=%s, d10=%s, d11=%s WHERE idtips=%s",
                       GetSQLValueString($_POST['tip'], "text"),
                       GetSQLValueString($requerido, "int"),
                       GetSQLValueString($_POST['item'], "text"),
                       GetSQLValueString($_POST['d8'], "int"),
                       GetSQLValueString($_POST['d9'], "int"),
                       GetSQLValueString($_POST['d10'], "int"),
                       GetSQLValueString($_POST['d11'], "int"),
                       GetSQLValueString($_POST['idTips'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "tips_udp.php?gr=ok";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_tips = "-1";
if (isset($_GET['mod'])) {
  $colname_tips = $_GET['mod'];
}
mysql_select_db($database_conn, $conn);
$query_tips = sprintf("SELECT * FROM tips WHERE idtips = %s limit 1", GetSQLValueString($colname_tips, "int"));
$tips = mysql_query($query_tips, $conn) or die(mysql_error());
$row_tips = mysql_fetch_assoc($tips);
$totalRows_tips = mysql_num_rows($tips);
 require_once('funtions.php'); ?>
<html>
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
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if(isset($_GET['gr']) && $_GET['gr'] == "ok") { ?>

<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td class="ins_titulomayor">Modificar Tip Seleccionado</td>
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
        <td class="tituloscampos ins_celdacolor">&nbsp;</td>
      </tr>
      <tr>
        <td class="separadormayor"></td>
      </tr>
      <tr>
        <td align="center" class="tituloscampos ins_celdacolor">EL TIP SE HA GUARDADO CON EXITO</td>
      </tr>
      <tr>
        <td class="separadormayor"></td>
      </tr>
      <tr>
        <td class="tituloscampos ins_celdacolor">&nbsp;</td>
      </tr>
      <tr>
        <td style="color:#F00" class="niv_menu">&nbsp;</td>
      </tr>
      <tr>
        <td class="separadormayor"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="celdapieazul_c"></td>
  </tr>
  <tr>
    <td class="fondolineaszulesvert_c"><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php } else { ?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td class="ins_titulomayor">Modificar Tip Seleccionado</td>
        <td align="right"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="fondolineaszulesvert_c"><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
         <tr>
        <td colspan="2" class="tituloscampos ins_celdacolor">Campo de Entrada:</td>
        </tr>
        <tr>
        <td colspan="2"><input name="campo" class="camposmenores" type="text" id="campo" value="<?php echo $row_tips['campo']; ?>" disabled  /></td>
        </tr>
        <tr>
        <td colspan="2" class="separadormayor"></td>
        </tr>
        <tr>
         <td width="50%" class="tituloscampos ins_celdacolor">Seccion:</td>
         <td width="50%" class="tituloscampos ins_celdacolor">Sector:</td>
        </tr>
        <tr>
        <td class="separadormayor"><input name="area"  type="text" id="area" value="<?php echo $row_tips['area']; ?>" disabled style="width:260px;"  /></td>
        <td class="separadormayor"><input name="sector"  type="text" id="sector" value="<?php echo $row_tips['sector']; ?>" disabled style="width:275px;"  /></td>
        </tr>
        <tr>
        <td colspan="2" class="separadormayor"></td>
        </tr>
      <tr>
        <td colspan="2" class="tituloscampos ins_celdacolor">Etiqueda del Campo:</td>
        </tr>
      <tr>
        <td colspan="2">
          <input name="item" class="camposmenores" type="text" id="item" value="<?php echo $row_tips['item']; ?>"  /></td>
        </tr>
  <tr>
    	          <td colspan="2" class="separadormayor"></td>
        </tr>
        <tr>
        <td height="15" valign="top" class="tituloscampos ins_celdacolor">Tipos de Diplomáticos afectados:</td>
        <td valign="top" class="tituloscampos ins_celdacolor">Miscelaneas:</td>
        </tr>
        <tr>
        <td><table width="275" border="0" cellspacing="0" cellpadding="0">
          <tr class="tituloscampos ins_celdacolor">
            <td>Diplom&aacute;tico 8:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['d8'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="d8" id="radio" value="1">&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['d8'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="d8" id="radio2" value="0"></td>
          </tr>
          <tr class="tituloscampos ins_celdacolor">
            <td>Diplom&aacute;tico 9:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['d9'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="d9" id="radio" value="1">&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['d9'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="d9" id="radio2" value="0"></td>
          </tr>
          <tr class="tituloscampos ins_celdacolor">
            <td>Diplom&aacute;tico 10:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['d10'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="d10" id="radio" value="1">&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['d10'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="d10" id="radio2" value="0"></td>
          </tr>
          <tr class="tituloscampos ins_celdacolor">
            <td>Diplom&aacute;tico 11:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['d11'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="d11" id="radio" value="1">&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['d11'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="d11" id="radio2" value="0"></td>
          </tr>
        </table></td>
        <td valign="top"><table width="275" border="0" cellspacing="0" cellpadding="0">
         <tr class="tituloscampos ins_celdacolor">
            <td>Obligatorio:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['requerido'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="requerido" id="radio3" value="1" <?php if (!(strcmp($row_tips['requerido_obligatorio'],"1"))) {echo "disabled";} ?>>&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['requerido'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="requerido" id="radio4" value="0" <?php if (!(strcmp($row_tips['requerido_obligatorio'],"1"))) {echo "disabled";} ?>></td>
          </tr>
          <tr class="tituloscampos ins_celdacolor">
            <td>Obligatorio Forzoso:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['requerido_obligatorio'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="requerido_obligatorio" id="radio3" value="1" disabled>&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['requerido_obligatorio'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="requerido_obligatorio" id="radio4" value="0" disabled></td>
          </tr>
         <tr class="tituloscampos ins_celdacolor">
            <td>De Fecha:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['defecha'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="defecha" id="radio3" value="1" disabled>&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['defecha'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="defecha" id="radio4" value="0" disabled></td>
          </tr>
          <tr class="tituloscampos ins_celdacolor">
            <td>De Cantidad:</td>
            <td>Si:<input <?php if (!(strcmp($row_tips['decantidad'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="decantidad" id="radio3" value="1" disabled>&nbsp;&nbsp;&nbsp;
No:<input <?php if (!(strcmp($row_tips['decantidad'],"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="decantidad" id="radio4" value="0" disabled></td>
          </tr>
        </table></td>
        </tr>
    	        <tr>
    	          <td colspan="2" class="separadormayor"></td>
  	          </tr>
      <tr>
        <td colspan="2" class="tituloscampos ins_celdacolor">Tip:</td>
        </tr>
      <tr>
        <td colspan="2" class="niv_menu" style="color:#F00"><textarea name="tip" rows="5" class="camposmenores" id="tip"><?php echo $row_tips['tip']; ?></textarea></td>
        </tr>
      <tr>
    	          <td colspan="2" class="separadormayor"></td>
        </tr>
    </table></td>
</tr>
<tr>
  <td class="fondolineaszulesvert_c"><table width="570" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="385"><input name="idTips" type="hidden" id="idTips" value="<?php echo $row_tips['idtips']; ?>"></td>
    	          <td width="185" align="right" valign="middle" class="celdabotonera"><input name="button3" type="submit" class="botongrabar" id="button3" value="Grabar">
    	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  	          </tr>
  	        </table></td></tr>
  <tr>
    <td class="celdapieazul_c"></td>
  </tr>
  <tr>
    <td class="fondolineaszulesvert_c"><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="form1">

</form>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($tips);
?>
