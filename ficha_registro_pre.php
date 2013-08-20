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

mysql_select_db($database_conn, $conn);
$query_institucion = "SELECT * FROM instituciones WHERE codigo_identificacion=".GetSQLValueString($_GET['cod'], "text");
$institucion = mysql_query($query_institucion, $conn) or die(mysql_error());
$row_institucion = mysql_fetch_assoc($institucion);
$totalRows_institucion = mysql_num_rows($institucion);

mysql_select_db($database_conn, $conn);
$query_estados = "SELECT * FROM estados ORDER BY estado ASC";
$estados = mysql_query($query_estados, $conn) or die(mysql_error());
$row_estados = mysql_fetch_assoc($estados);
$totalRows_estados = mysql_num_rows($estados);

mysql_select_db($database_conn, $conn);
$query_situaciones = "SELECT * FROM situaciones ORDER BY situacion ASC";
$situaciones = mysql_query($query_situaciones, $conn) or die(mysql_error());
$row_situaciones = mysql_fetch_assoc($situaciones);
$totalRows_situaciones = mysql_num_rows($situaciones);


$querySQL = "SELECT codigo_referencia FROM vis_estados_niveles";

$wer1 = " WHERE ";
if($_GET['tipo'] == "N") {
	$wer1 .= " (tipo_nivel=1 OR tipo_nivel=2 OR tipo_nivel=3 OR tipo_nivel=4) ";
	$tabla="niveles";	
} else {
	$wer1 .= " (tipo_nivel=8 OR tipo_nivel=9 OR tipo_nivel=10 OR tipo_nivel=11) ";
	$tabla="documentos";	
}

if(isset($_GET['cod']) && $_GET['cod'] != "") {
	$wer0 = " AND codigo_institucion='".$_GET['cod']."' ";
}

$querySQL = $querySQL.$wer1.$wer0;
$querySQLtmp = $querySQL;
$desde = 1;
$codigo="CI:".$_GET['tipo'];
if(isset($_POST['desdeMas']) && $_POST['desdeMas'] != "") {
	$desde = $_POST['desdeMas'];
}

if(isset($_POST['busqueda1']) && ($_POST['busqueda1'] == "1" || $_POST['busqueda1'] == "2" || $_POST['busqueda1'] == "3")) {


$wer2 = "";
if(!(empty($_POST['e']))){
	$wer2 = " AND (";
	foreach ($_POST['e'] as $valor) {
		$wer2 .= "estado='$valor' OR ";
		$codigo .= substr($valor,0,1);
	}
	$wer2 .= "estado='-1') ";
}

$wer3 = "";
if(!(empty($_POST['s']))){
	$wer3 = " AND (";
	foreach ($_POST['s'] as $valor) {
		$wer3 .= "situacion='$valor' OR ";
		$codigo .= substr($valor,0,1);
	}
	$wer3 .= "situacion='-1') ";
}

$order = " ORDER BY codigo_referencia ASC ";

$limit = "";
if(isset($_POST['selectCANT']) && $_POST['selectCANT'] != "") {
	$limit .= " LIMIT $desde, ".$_POST['selectCANT'];
	$codigo .= "-".$_POST['selectCANT'];
} else {
	$codigo .= "-0";
}

$querySQLtmp = $querySQL.$wer2.$wer3.$order;	
$querySQL = $querySQL.$wer2.$wer3.$order.$limit;
	

} 


//echo $querySQL;	


mysql_select_db($database_conn, $conn);
$busqueda  = mysql_query($querySQL , $conn) or die(mysql_error());
$row_busqueda  = mysql_fetch_assoc($busqueda);
$totalRows_busqueda  = mysql_num_rows($busqueda);


if(isset($_POST['selectCANT']) && $_POST['selectCANT'] != "") {
	$busqueda2  = mysql_query($querySQLtmp , $conn) or die(mysql_error());
	$totalRows_busqueda2  = mysql_num_rows($busqueda2);
	$total = $totalRows_busqueda2;
} else {
	$total = $totalRows_busqueda;
}

$hasta = $total;
if(isset($_POST['hastaMas']) && $_POST['hastaMas'] != "") {
	$hasta = $_POST['hastaMas'];
}
if($desde == 1 && isset($_POST['selectCANT']) && $_POST['selectCANT']!= ""){
	$hasta = $_POST['selectCANT'];
}
if($hasta >= $total){
	$hasta = $total;
}

if($totalRows_busqueda <= 0) { 
	$desde=0;
}

$queryNueva="SELECT * FROM ".$tabla." WHERE codigo_referencia ";
$queryNueva .="IN (";
do{
	
	$queryNueva .="'".$row_busqueda['codigo_referencia']."',";
	
} while ($row_busqueda = mysql_fetch_assoc($busqueda));

	$queryNueva .="'-1') ORDER BY codigo_referencia ASC";

//echo $queryNueva;
?>
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
<script language="javascript">

	function imprimir() {
		window.open('ficha_registro_printB.php?<?php echo "pageNum_documentos=".$pageNum_documentos.$queryString_documentos; ?>','_blank');
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
			<td class="celdatituloazul_c ins_titulomayor"><span class="ins_titulomayor">&nbsp;&nbsp;&nbsp;<?php echo substr($row_institucion['formas_conocidas_nombre'],0,80); ?></span></td>
        </tr>
        <tr>
       	  <td class="fondolineaszulesvert_c">
       	    <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
       	      <tr>
       	        <td><form name="form1" method="post" action="">
       	          <table width="550" border="0" cellspacing="0" cellpadding="0"><tr>
       	            <td>&nbsp;</td></tr>
       	            <tr>
       	              <td class="ins_celdtadirecciones">Estados: </td>
   	                </tr>
       	            <tr>
       	              <td align="center" class="textosbusqueda"><?php do { ?>
   	                    <?php echo $row_estados['estado']; ?>:<input name="e[]" type="checkbox" id="e[]" value="<?php echo $row_estados['estado']; ?>" <?php if(!(empty($_POST['e']))){ foreach ($_POST['e'] as $valor) { if($valor == $row_estados['estado']) { echo "checked"; } } } ?> >&nbsp;&nbsp;<?php } while ($row_estados = mysql_fetch_assoc($estados)); ?></td>
   	                </tr>
       	            <tr>
       	              <td>&nbsp;</td>
   	                </tr>
                    <tr>
       	              <td class="ins_celdtadirecciones">Situaciones: </td>
   	                </tr>
       	            <tr>
       	              <td align="center" class="textosbusqueda"><?php do { ?><?php echo $row_situaciones['situacion']; ?>:<input name="s[]" type="checkbox" id="s[]" value="<?php echo $row_situaciones['situacion']; ?>"  <?php if(!(empty($_POST['s']))){ foreach($_POST['s'] as $valor) { if($valor == $row_situaciones['situacion']) { echo "checked"; } } } ?>>&nbsp;<?php } while ($row_situaciones = mysql_fetch_assoc($situaciones)); ?></td>
   	                </tr>
       	            <tr>
       	              <td>&nbsp;</td>
   	                </tr>
       	            <tr>
       	              <td class="ins_celdtadirecciones">Cantidad por Vez: </td>
   	                </tr>
       	            <tr>
       	              <td><select name="selectCANT" class="textosbusqueda" id="selectCANT" style="width:550px;">
       	                  <option value="">Todos</option>
       	                  <option value="1" <?PHP if($_POST['selectCANT'] == "1") { echo "selected"; } ?> >1</option>
       	                  <option value="10" <?PHP if($_POST['selectCANT'] == "10") { echo "selected"; } ?> >10</option>
       	                  <option value="50" <?PHP if($_POST['selectCANT'] == "50") { echo "selected"; } ?> >50</option>
       	                  <option value="100" <?PHP if($_POST['selectCANT'] == "100") { echo "selected"; } ?> >100</option>
       	                  <option value="500" <?PHP if($_POST['selectCANT'] == "500") { echo "selected"; } ?> >500</option>
       	                  <option value="1000" <?PHP if($_POST['selectCANT'] == "1000") { echo "selected"; } ?> >1000</option>
                      </select></td>
   	                </tr>
       	            <tr>
       	              <td>&nbsp;</td>
   	                </tr>
       	            <tr>
       	              <td><input name="button2" type="submit" class="botongrabar" id="button2" value="FILTRAR" style="width:550px;" onClick="activar();" <?php if($desde > 1) { echo "disabled"; } ?>></td>
   	                </tr>
       	            <tr>
       	              <td><input name="busqueda1" type="hidden" id="busqueda1" value="1"></td>
   	                </tr>
   	              </table>
   	            </form></td>
   	          </tr>
       	      <tr>
       	        <td align="center" class="contador"><br><span class="contador"><?php echo "".$desde; ?> a <?php echo $hasta;  ?> de <?php echo "".$total; ?></span></td>
   	          </tr>
       	      <tr>
       	        <td>&nbsp;</td>
   	          </tr>
       	      <tr>
       	        <td><table width="550" border="0" cellspacing="0" cellpadding="0">
       	          <tr>
       	            <td align="right"><form name="form3" method="post" action=""><input name="hastaMas" type="hidden" id="hastaMas" value="<?php echo $desde  - 1;  ?>"><input name="desdeMas" type="hidden" id="desdeMas" value="<?php echo $desde - $_POST['selectCANT'];  ?>"><input name="busqueda1" type="hidden" id="busqueda1" value="3">
       	               <?php
					 if(!(empty($_POST['e']))){
					   	foreach ($_POST['e'] as $valor) {
							echo "<input type=\"hidden\" name=\"e[]\" id=\"e[]\" value=\"$valor\" >";
					 	}
					}

					 if(!(empty($_POST['s']))){
					   	foreach ($_POST['s'] as $valor) {
							echo "<input type=\"hidden\" name=\"s[]\" id=\"s[]\" value=\"$valor\" >";
					 	}
					}
					
					if($_POST['selectCANT'] && $_POST['selectCANT'] != ""){
						echo "<input type=\"hidden\" name=\"selectCANT\" id=\"selectCANT\" value=\"".$_POST['selectCANT']."\" >";	
					}
					
					
					
					
					  ?><input name="button4" type="submit" class="textosbusqueda" id="button4" value="&lt;&lt; Anterior" onClick="activar();" <?PHP if($desde<=1) { echo "disabled";} ?>>
   	                </form></td>
       	            <td width="40">&nbsp;</td>
       	            <td><form name="form2" method="post" action="">
       	              <input name="button3" type="submit" class="textosbusqueda" id="button3" value="Siguiente &gt;&gt;" onClick="activar();" <?PHP if($hasta==$total) { echo "disabled";} ?>>
                      <?php
					 if(!(empty($_POST['e']))){
					   	foreach ($_POST['e'] as $valor) {
							echo "<input type=\"hidden\" name=\"e[]\" id=\"e[]\" value=\"$valor\" >";
					 	}
					}

					 if(!(empty($_POST['s']))){
					   	foreach ($_POST['s'] as $valor) {
							echo "<input type=\"hidden\" name=\"s[]\" id=\"s[]\" value=\"$valor\" >";
					 	}
					}
					
					if($_POST['selectCANT'] && $_POST['selectCANT'] != ""){
						echo "<input type=\"hidden\" name=\"selectCANT\" id=\"selectCANT\" value=\"".$_POST['selectCANT']."\" >";	
					}
					
					
					
					
					  ?>
       	              <input name="busqueda1" type="hidden" id="busqueda1" value="2">
       	              <input name="desdeMas" type="hidden" id="desdeMas" value="<?php echo $desde + $_POST['selectCANT'];  ?>">
                      <input name="hastaMas" type="hidden" id="hastaMas" value="<?php echo $desde + ($_POST['selectCANT']*2) - 1;  ?>">
       	            </form></td>
   	              </tr>
   	            </table></td>
   	          </tr>
       	      <tr>
       	        <td>&nbsp;</td>
   	          </tr>
       	      <tr>
       	        <td><form action="ficha_registro_printB.php" method="post" name="FORM2" target="_blank" id="FORM2">
       	          <input name="button" type="submit" class="botongrabar" id="button" value="IMPRIMIR" style="width:550px;" <?php if($totalRows_busqueda <= 0) { echo "disabled";} ?>>
   	              <input name="QuerySend" type="hidden" id="QuerySend" value="<?php echo $queryNueva; ?>">
       	          <input name="institucionSend" type="hidden" id="institucionSend" value="<?php echo $_GET['cod'];  ?>">
                  <input name="tipoSend" type="hidden" id="tipoSend" value="<?php echo $_GET['tipo'];  ?>">
                  <input name="desdeSend" type="hidden" id="desdeSend" value="<?php echo $desde;  ?>">
                  <input name="totalSend" type="hidden" id="totalSend" value="<?php echo $total;  ?>">
                  <input name="codigoSend" type="hidden" id="codigoSend" value="<?php echo $codigo;  ?>">
       	        </form></td>
   	          </tr>
       	      <tr>
       	        <td>&nbsp;</td>
   	          </tr>
       	      <tr>
       	        <td>&nbsp;</td>
   	          </tr>
   	        </table></td>
        </tr>
        <tr>
		  <td class="celdapiesimple_c"></td>
		</tr>
</table>
<?php require_once('activo.php'); ?>
</body>
</html>
<?php

mysql_free_result($situaciones);

mysql_free_result($estados);

?>
