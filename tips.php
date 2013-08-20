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


$we="WHERE 1=0 ";

if(isset($_GET['seccion']) && $_GET['seccion'] != "") {
	$we="WHERE area=".GetSQLValueString($_GET['seccion'], "text");
}

/* se leen los registros a la tabla para la grilla de muestra */
mysql_select_db($database_conn, $conn);
$query_vis_tipoinstitucion = "SELECT * FROM tips ".$we." ORDER BY idtips ASC";
$vis_tipoinstitucion = mysql_query($query_vis_tipoinstitucion, $conn) or die(mysql_error());
$row_vis_tipoinstitucion = mysql_fetch_assoc($vis_tipoinstitucion);
$totalRows_vis_tipoinstitucion = mysql_num_rows($vis_tipoinstitucion);


mysql_select_db($database_conn, $conn);
$query_areas_tips = "SELECT * FROM areas_tips ORDER BY area ASC";
$areas_tips = mysql_query($query_areas_tips, $conn) or die(mysql_error());
$row_areas_tips = mysql_fetch_assoc($areas_tips);
$totalRows_areas_tips = mysql_num_rows($areas_tips);


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
	  form.target = 'frderecha';
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
 
 function secciones() {
	 document.location='tips.php?seccion='+document.getElementById('seccion').value;
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
            <td class="ins_titulomayor">Secciones</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20" class="separadormenor"></td>
          </tr>
          <tr>
            <td colspan="2" valign="top" class="tituloscampos ins_celdacolor"><select name="seccion" id="seccion" class="camposanchos" onChange="secciones();">
            <option value="">Selecciones una opción</option>
              <?php
do {  
?>
              <option value="<?php echo $row_areas_tips['area'];  ?>"<?php if(isset($_GET['seccion']) && $_GET['seccion'] == $row_areas_tips['area']) { echo "selected"; } ?>><?php echo $row_areas_tips['area']?></option>
              <?php
} while ($row_areas_tips = mysql_fetch_assoc($areas_tips));
  $rows = mysql_num_rows($areas_tips);
  if($rows > 0) {
      mysql_data_seek($areas_tips, 0);
	  $row_areas_tips = mysql_fetch_assoc($areas_tips);
  }
?>
            </select></td>
            </tr>
          <tr>
            <td class="separadormenor"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="celdapiesimple">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php if($totalRows_vis_tipoinstitucion >= 1) { ?>
  <tr>
    <td>
    <table width="658" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul ins_titulomayor"><table width="610" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">TIPS</td>
  	          </tr>
  	        </table></td>
		</tr>
		<tr>
		  <td class="fondolineaszulesvert"><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <?php do { ?>
                <tr>
                 	<td><table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                 	  <tr>
                 	    <td width="30" rowspan="9" align="center" class="tituloscampos"><img src="images/help<?php if($row_vis_tipoinstitucion['requerido'] == 1) { echo "2"; } else { echo "1"; }  ?>.png" width="26" height="28"><br><?php echo "".$row_vis_tipoinstitucion['idtips']; ?></td>
                 	    <td width="10" rowspan="9">&nbsp;</td>
                 	    <td width="50" align="right" valign="top" class="tituloscampos">Campo: </td>
                 	    <td width="10" rowspan="9" valign="top" class="tituloscampos">&nbsp;</td>
                 	    <td width="497" valign="top" class="tips"><?php echo $row_vis_tipoinstitucion['campo']; ?></td>
                 	    <td width="33" rowspan="9" align="center"><a href="tips_udp.php?mod=<?php echo $row_vis_tipoinstitucion['idtips']; ?>" target="frderecha" ><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
               	    </tr>
                 	  <tr>
                 	    <td align="right" valign="top" height="1" class="ins_celdacolor"></td>
                 	    <td width="497" valign="top" class="ins_celdacolor"></td>
               	      </tr>
                 	  <tr>
                 	    <td align="right" valign="top" class="tituloscampos">Sector:</td>
                 	    <td width="497" valign="top" class="tips"><?php echo $row_vis_tipoinstitucion['sector']; ?></td>
               	      </tr>
                 	  <tr>
                 	    <td height="1" align="right" valign="top" class="ins_celdacolor"></td>
                 	    <td width="497" valign="top" class="ins_celdacolor"></td>
               	      </tr>
                 	  <tr>
                 	    <td align="right" valign="top" class="tituloscampos">Item:</td>
                 	    <td width="497" valign="top"  class="tips"><?php echo $row_vis_tipoinstitucion['item']; ?></td>
               	      </tr>
                 	  <tr>
                 	    <td height="1" align="right" valign="top" class="ins_celdacolor"></td>
                 	    <td valign="top"  class="ins_celdacolor"></td>
               	      </tr>
                 	  <tr>
                 	    <td align="right" valign="top" class="tituloscampos">Tip:</td>
                 	    <td valign="top"  class="tips"><?php echo $row_vis_tipoinstitucion['tip']; ?></td>
               	      </tr>
                 	  <tr>
                 	    <td height="1" align="right" valign="top" class="ins_celdacolor"></td>
                 	    <td valign="top"  class="ins_celdacolor"></td>
               	      </tr>
                 	  <tr>
                 	    <td align="right" valign="top" class="tituloscampos">Otros:</td>
                 	    <td valign="top"  class="tips">Diplom&aacute;ticos(<?php if($row_vis_tipoinstitucion['d8'] == 1) { echo "8;"; } ?><?php if($row_vis_tipoinstitucion['d9'] == 1) { echo "9;"; } ?><?php if($row_vis_tipoinstitucion['d10'] == 1) { echo "10;"; } ?><?php if($row_vis_tipoinstitucion['d11'] == 1) { echo "11"; } ?>) </td>
               	      </tr>
                  </table></td>
                 </tr> 
                
   	            <tr>
                  <td height="2" valign="top" class="ins_celdacolor"></td>
   	              </tr>
    	         
<tr>
    	          <td class="separadormenor"></td>
  	          </tr> <?php } while ($row_vis_tipoinstitucion = mysql_fetch_assoc($vis_tipoinstitucion)); ?>
          </table></td>
		  </tr>
		<tr>
		  <td class="celdapiesimple">&nbsp;</td>
		  </tr>
	</table>
   </td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>


</body>
</html>
<?php
mysql_free_result($vis_tipoinstitucion);

mysql_free_result($areas_tips);
?>
