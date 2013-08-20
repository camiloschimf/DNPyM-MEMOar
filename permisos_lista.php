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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

foreach ( $_POST as $key => $value) { //Iniciamos el foreach. KEY = name del input, $value=valor
	$partes=explode("_", $key);
	if (count($partes) >= 3) {
		$idseccion=$partes[1];
		$idrol=$partes[2];
		
		if($partes[0] == "in") {
			$insertSQL = sprintf("REPLACE INTO permisos (idseccion, idrol, accion) VALUES (%s, %s, %s)",
						GetSQLValueString($idseccion, "int"),
						GetSQLValueString($idrol, "int"),
						GetSQLValueString($value, "text"));
			
			mysql_select_db($database_conn, $conn);
			$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());	
		}
	}

} 

  $insertOrUpdateGoTo = "permisos_lista.php";;
  header(sprintf("Location: %s", $insertOrUpdateGoTo));
	
}

mysql_select_db($database_conn, $conn);
$query_secciones = "SELECT * FROM secciones ORDER BY idseccion ASC";
$secciones = mysql_query($query_secciones, $conn) or die(mysql_error());
$row_secciones = mysql_fetch_assoc($secciones);
$totalRows_secciones = mysql_num_rows($secciones);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 00px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>
<body>
<form name="form2" method="POST" action="">
<table width="658" border="0" align="center" cellpadding="0" cellspacing="0">
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
    	         <td colspan="6" class="separadormayor"></td>
  	          </tr>
            </table>
            <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
              <?php do { ?>
              <?php 
			  		mysql_select_db($database_conn, $conn);
					$query_roles = "SELECT r.*, p.idseccion, p.accion FROM roles r LEFT JOIN permisos p ON (r.idrol=p.idrol AND p.idseccion=".$row_secciones['idseccion'].") WHERE r.idrol <> 1 AND r.idrol <> 2 AND r.idrol <> 3 AND r.activo=1 ORDER BY r.idrol ASC";
					$roles = mysql_query($query_roles, $conn) or die(mysql_error());
					$row_roles = mysql_fetch_assoc($roles);
					$totalRows_roles = mysql_num_rows($roles);
			  ?>
              <tr>
                  <td >
				  <table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
                  	<tr>
                    	<td colspan="2" class="botongrabar ins_celdacolor"><?php echo $row_secciones['descripcion']; ?></td>
                        <td align="center" class="botongrabar ins_celdacolor">C</td>
                        <td align="center" class="botongrabar ins_celdacolor">M</td>
                        <td align="center" class="botongrabar ins_celdacolor">E</td>
                    </tr>
                    <tr>
                    <td colspan="5" height="1"></td>
                    </tr>
                    <?php do { ?>
                    <tr>
                    	<td width="62" align="center" class="ins_celdacolor"><img src="images/curva.png" width="25" height="11"></td>
               	    <td width="463" class="tituloscampos ins_celdacolor"><?php echo $row_roles['rol']; ?></td>
                    	<td width="35" align="center" class="ins_celdacolor"><input type="radio" name="in_<?php echo $row_secciones['idseccion']."_".$row_roles['idrol']; ?>" id="radio3" value="C" <?php if($row_roles['accion'] != "M" &&  $row_roles['accion'] != "E") { echo "checked"; } ?> ></td>
                   	  <td width="35" align="center" class="ins_celdacolor"><input type="radio" name="in_<?php echo $row_secciones['idseccion']."_".$row_roles['idrol']; ?>" id="radio2" value="M" <?php if($row_roles['accion'] == "M") { echo "checked"; } ?>></td>
                    	<td width="35" align="center" class="ins_celdacolor"><input type="radio" name="in_<?php echo $row_secciones['idseccion']."_".$row_roles['idrol']; ?>" id="radio" value="E" <?php if($row_roles['accion'] == "E") { echo "checked"; } ?>></td>
                    </tr>
                    <tr>
                    <td class="ins_celdacolor"></td>
                    <td colspan="4" height="1"></td>
                    </tr>
                    <?php } while ($row_roles = mysql_fetch_assoc($roles)); ?>
                  </table>
			    </td>
              </tr>
                
<tr>
            <td colspan="6" class="separadormayor"></td>
  	          </tr>
              <?php } while ($row_secciones = mysql_fetch_assoc($secciones)); ?>
            </table>
            
            
          </td>
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
</form>
</body>
</html>
<?php
mysql_free_result($secciones);

mysql_free_result($roles);
?>
