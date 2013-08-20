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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Busqueda = 50;
$pageNum_Busqueda = 0;
if (isset($_GET['pageNum_Busqueda'])) {
  $pageNum_Busqueda = $_GET['pageNum_Busqueda'];
}
$startRow_Busqueda = $pageNum_Busqueda * $maxRows_Busqueda;

$were1 = "";

if(isset($_POST['MM_search']) && $_POST['MM_search'] == "form2") {
	
	if(isset($_POST['codigo_referencia']) && $_POST['codigo_referencia'] != "") {
		$were1 .= "AND codigo_referencia LIKE '%".$_POST['codigo_referencia']."%' ";
	}
	
	if(isset($_POST['nombre']) && $_POST['nombre'] != "") {
		$were1 .= "AND (nombre LIKE '%".$_POST['nombre']."%' OR otrosnombres LIKE '%".$_POST['nombre']."%') ";
	}
	
	if(isset($_POST['estado']) && $_POST['estado'] != "") {
		$were1 .= "AND estado= '".$_POST['estado']."' ";
	}
	
	if(isset($_POST['institucion']) && $_POST['institucion'] != "") {
		$were1 .= "AND codigo_institucion= '".$_POST['institucion']."' ";
	}
	
	if(isset($_POST['situacion']) && $_POST['situacion'] != "") {
		$were1 .= "AND situacion= '".$_POST['situacion']."' ";
	}
	
	if(isset($_POST['palabra_clave']) && $_POST['palabra_clave'] != "") {
		$were1 .= "AND palabra_clave LIKE '%".$_POST['palabra_clave']."%' ";
	}
	
	
}

mysql_select_db($database_conn, $conn);
$query_Busqueda = "SELECT * FROM vis_busqueda_general WHERE 0=0 ".$were1.$_SESSION['MM_instituciones']." ORDER BY codigo_referencia ASC";
$query_limit_Busqueda = sprintf("%s LIMIT %d, %d", $query_Busqueda, $startRow_Busqueda, $maxRows_Busqueda);
$Busqueda = mysql_query($query_limit_Busqueda, $conn) or die("Q2:".mysql_error());
$row_Busqueda = mysql_fetch_assoc($Busqueda);


if (isset($_GET['totalRows_Busqueda'])) {
  $totalRows_Busqueda = $_GET['totalRows_Busqueda'];
} else {
  $all_Busqueda = mysql_query($query_Busqueda);
  $totalRows_Busqueda = mysql_num_rows($all_Busqueda);
}
$totalPages_Busqueda = ceil($totalRows_Busqueda/$maxRows_Busqueda)-1;

mysql_select_db($database_conn, $conn);
$query_estados = "SELECT * FROM estados ORDER BY estado ASC";
$estados = mysql_query($query_estados, $conn) or die("Q3:".mysql_error());
$row_estados = mysql_fetch_assoc($estados);
$totalRows_estados = mysql_num_rows($estados);

mysql_select_db($database_conn, $conn);
$query_instituciones = "SELECT * FROM instituciones WHERE 0=0 ".$_SESSION['MM_instituciones_norm']." ORDER BY codigo_identificacion ASC";
$instituciones = mysql_query($query_instituciones, $conn) or die("Q4:".mysql_error());
$row_instituciones = mysql_fetch_assoc($instituciones);
$totalRows_instituciones = mysql_num_rows($instituciones);

mysql_select_db($database_conn, $conn);
$query_situaciones = "SELECT * FROM situaciones ORDER BY situacion ASC";
$situaciones = mysql_query($query_situaciones, $conn) or die("Q5:".mysql_error());
$row_situaciones = mysql_fetch_assoc($situaciones);
$totalRows_situaciones = mysql_num_rows($situaciones);

mysql_select_db($database_conn, $conn);
$query_palabras_claves = "SELECT DISTINCT palabra_clave FROM palabras_claves WHERE 0=0 ".$_SESSION['MM_instituciones_niv']." ORDER BY palabra_clave ASC";
$palabras_claves = mysql_query($query_palabras_claves, $conn) or die("Q1:".mysql_error());
$row_palabras_claves = mysql_fetch_assoc($palabras_claves);
$totalRows_palabras_claves = mysql_num_rows($palabras_claves);

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

?>
<html>
<head>

<title>DNPyM</title>
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script language="javascript">
	function vigencia(a) {
		window.parent.parent.frameestados.location='vigencia_sp.php?v=s&cod='+a;
	}
	
	function novigencia(a) {
		window.parent.parent.frameestados.location='vigencia_sp.php?v=n&cod='+a;
	}
	
	function redirect(a,b) {
	
	window.parent.parent.frameestados.location = 'nivdoc_estados.php?cod='+a ;
	//document.location.target= '_blank';
	if(b==0) {
	window.parent.document.location.href = 'fr_institucion.php?cod='+a ;	
	} else if(b==1) {
	window.parent.document.location.href = 'fr_nivel.php?cod='+a ;
	} else if(b>=8 && b<=11) {
	window.parent.document.location.href = 'fr_diplomaticos.php?cod='+a ;	
	}
	
}
</script>
<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <table width="580" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="celdatituloazul_c ins_titulomayor"><table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Resultado de la B&uacute;squeda</td>
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
		  <td class="fondolineaszulesvert_c"><table width="555" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
  <td height="8" colspan="3" valign="top"></td>
		      </tr>
		    <?php do { ?>
		      <tr>
		        <td width="45" valign="top"><a <?php if($row_Busqueda['tipo'] != "5") { ?>href="<?php if($row_Busqueda['tipo'] == "0") { echo "instituciones_update.php";} else if ($row_Busqueda['tipo'] == "1" || $row_Busqueda['tipo'] == "2" || $row_Busqueda['tipo'] == "3" || $row_Busqueda['tipo'] == "4" || $row_Busqueda['tipo'] == "5" || $row_Busqueda['tipo'] == "6" || $row_Busqueda['tipo'] == "7") { echo "niveles_update.php";} else if ($row_Busqueda['tipo'] == "8" || $row_Busqueda['tipo'] == "9" || $row_Busqueda['tipo'] == "10" || $row_Busqueda['tipo'] == "11") { echo "diplomaticos_update.php";} ?>?cod=<?php echo $row_Busqueda['codigo_referencia'];  ?>" <?php } ?> target="frderecha"><img src="images/c<?php echo $row_Busqueda['tipo']; ?><?php if($row_Busqueda['situacion'] == "Baja" || $row_Busqueda['estado'] == "Cancelado") { echo "k"; } else if($row_Busqueda['estado'] == "Inicio") { echo "o"; }  else if($row_Busqueda['estado'] == "Completo") { echo "c"; } else if($row_Busqueda['estado'] == "No Vigente") { echo "n"; } else if($row_Busqueda['estado'] == "Pendiente") { echo "t"; } else if($row_Busqueda['situacion'] == "Conservación") { echo "s"; } else if($row_Busqueda['situacion'] == "Exposición") { echo "x"; } else if($row_Busqueda['situacion'] == "No Localizado") { echo "l"; } else if($row_Busqueda['situacion'] == "Prestamo") { echo "p"; } else if($row_Busqueda['situacion'] == "Robado") { echo "r"; } ?>.png"  height="47" border="0"></a></td>
		        <td width="472" valign="middle"  class="textosbusqueda"><strong><?php echo str_replace("<br><br>","", str_replace("<->", "<br>", substr($row_Busqueda['codigo_referencia'],0,70)."<->".substr($row_Busqueda['codigo_referencia'],71,140)."<->".substr($row_Busqueda['codigo_referencia'],141,210))); ?></strong><br>
		          <?php echo $row_Busqueda['nombre']; ?></td>
		        <td width="38" align="center" valign="middle" ><a href="#" onClick="redirect('<?php echo $row_Busqueda['codigo_referencia']; ?>','<?php echo $row_Busqueda['tipo_diplomatico']; ?>');"><img src="images/ico_001.png" alt="Editar" width="18" height="18" border="0"></a></td>
		        </tr>
		      
<tr>
  <td height="8" colspan="3" valign="top"><hr></td>
		      </tr><?php } while ($row_Busqueda = mysql_fetch_assoc($Busqueda)); ?>
	      </table>
          </td>
		  </tr>
		<tr>
		  <td class="celdapiesimple_c">&nbsp;</td>
		  </tr>
	</table>
   </td>
  </tr>
  <tr>
    <td><form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="580" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="celdasuperiorsimple_c"></td>
        </tr>
        <tr>
          <td class="fondolineaszulesvert_c"><table width="540" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="separadormenor"></td>
  	          </tr>
    	        <tr>
    	          <td class="tituloscampos ins_celdacolor">C&oacute;digo de Referencia:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
              <tr>
    	          <td class="ins_celdacolor"><input name="codigo_referencia" type="text" style="width:540px;" id="codigo_referencia" value="<?php if(isset($_POST['codigo_referencia'])) { echo $_POST['codigo_referencia'];  } ?>"  /></td>
                </tr>
              <tr>
              	<td>&nbsp;</td>
              </tr>
              <tr>
    	          <td class="tituloscampos ins_celdacolor">Nombre:</td>
  	          </tr>
    	        <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
    	        <tr>
    	          <td class="ins_celdacolor"><input name="nombre" type="text" style="width:540px;" id="nombre" value="<?php if(isset($_POST['nombre'])) { echo $_POST['nombre'];  } ?>"  /></td>
                </tr>
                <tr>
                  <td class="">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Estado:</td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                <tr>
                  <td class=""><select style="width:540px;" name="estado" id="estado">
                  <option value="">Seleccione opci&oacute;n</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_estados['estado']?>"  <?PHP  if(isset($_POST['estado']) && $_POST['estado'] == $row_estados['estado']) {  echo "selected"; } ?>><?php echo $row_estados['estado']?></option>
                    <?php
} while ($row_estados = mysql_fetch_assoc($estados));
  $rows = mysql_num_rows($estados);
  if($rows > 0) {
      mysql_data_seek($estados, 0);
	  $row_estados = mysql_fetch_assoc($estados);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td class="">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Situaci&oacute;n:</td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                <tr>
                  <td class=""><select style="width:540px;" name="situacion" id="situacion">
                    <option value="">Seleccione opci&oacute;n</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_situaciones['situacion']?>" <?PHP  if(isset($_POST['situacion']) && $_POST['situacion'] == $row_situaciones['situacion']) {  echo "selected"; } ?>><?php echo $row_situaciones['situacion']?></option>
                    <?php
} while ($row_situaciones = mysql_fetch_assoc($situaciones));
  $rows = mysql_num_rows($situaciones);
  if($rows > 0) {
      mysql_data_seek($situaciones, 0);
	  $row_situaciones = mysql_fetch_assoc($situaciones);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td class="">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Palabras Claves:</td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
                <tr>
                  <td class=""><select style="width:540px;" name="palabra_clave" id="palabra_clave">
                    <option value="">Seleccione opci&oacute;n</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_palabras_claves['palabra_clave']?>" <?PHP  if(isset($_POST['palabra_clave']) && $_POST['palabra_clave'] == $row_palabras_claves['palabra_clave']) {  echo "selected"; } ?>><?php echo $row_palabras_claves['palabra_clave']?></option>
                    <?php
} while ($row_palabras_claves = mysql_fetch_assoc($palabras_claves));
  $rows = mysql_num_rows($palabras_claves);
  if($rows > 0) {
      mysql_data_seek($palabras_claves, 0);
	  $row_palabras_claves = mysql_fetch_assoc($palabras_claves);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td class="">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tituloscampos ins_celdacolor">Instituci&oacute;n:</td>
                </tr>
                <tr>
    	          <td class="separadormenor ins_celdacolor"></td>
  	          </tr>
              <tr>
                  <td class=""><select style="width:540px;" name="institucion" id="institucion">
                    <option value="">Seleccione opci&oacute;n</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_instituciones['codigo_identificacion']?>"><?php echo $row_instituciones['formas_conocidas_nombre']?></option>
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
              <tr>
                <td class="">&nbsp;</td>
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
</table>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Busqueda);

mysql_free_result($estados);

mysql_free_result($instituciones);

mysql_free_result($situaciones);

mysql_free_result($palabras_claves);
?>
