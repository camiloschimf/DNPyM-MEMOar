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
$insert_ins="";
if(isset($_POST['MM_corrida']) && $_POST['MM_corrida'] == "cr1") {
	
	$insert_ins=$_POST['sqlscript'];
	$insert_ins=str_replace("\\","",$insert_ins);
	//echo $insert_ins;
	mysql_select_db($database_conn, $conn);
    $Result5= mysql_query($insert_ins, $conn) or die(mysql_error());
	$row_Result5 = mysql_fetch_array($Result5);
	$totalRows_Result5 = mysql_num_rows($Result5);	
	
	$total = $totalRows_Result5;
	$columnas = mysql_num_fields($Result5);
		
	$content="<table border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	
	$content .= "<tr>";
	for ($i = 0; $i < $columnas; $i++) {
    		$content .= "<td style=\"font-family:Arial; font-size:12px; font-weight:bold\">";
			$content .= mysql_field_name( $Result5 , $i);
			$content .= "</td>";
	}
	$content .= "</tr>\n";
	
	do{
		
	$content .= "<tr>";
		
		for ($i = 0; $i < $columnas; $i++) {
    		$content .= "<td style=\"font-family:Arial; font-size:12px\">";
			$content .= $row_Result5[$i];
			$content .= "</td>";
		}
		
	$content .= "</tr>\n";
		
	} while ($row_Result5 = mysql_fetch_array($Result5));
	
	$content .= "</table>";
	
	mysql_free_result($Result5);
	
	
	
}

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

<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="ventana.php">
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    
    <table width="580" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="celdatituloazul_c ins_titulomayor">
        	<table width="560" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    	        <tr>
    	          <td class="ins_titulomayor">Ventana de Emergencia</td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    </td>
  </tr>
  <tr>
       	  <td class="fondolineaszulesvert_c">
          
          <table width="555" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
  <td height="8"  valign="top"></td>
		      </tr>
              <tr>
		        <td width="45" valign="top">
                <?php if($insert_ins == "") { ?>
                  
                <?php } else { 
                	echo "CONSULTA: ".$insert_ins;
                 } ?>
                 <br>
                 <textarea name="sqlscript" id="sqlscript" style="width:555px; height:100px;"></textarea>
	            </td>
            </tr>
               <tr>
  <td height="8" valign="top"></td>
  </tr>
              </table>
          
          </td>
  </tr>
  <tr>
        	<td class="fondolineaszulesvert_c"><table width="570" border="0" cellspacing="0" cellpadding="0">
        	  <tr>
        	    <td width="393"><input name="MM_corrida" type="hidden" id="MM_corrida" value="cr1"></td>
        	    <td width="177" align="right" valign="middle" class="celdabotonera"><input name="button" type="submit" class="botongrabar"  id="button" value="Correr">
       	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      	    </tr>
    </table></td>
  </tr>
        <tr>
		  <td class="celdapiesimple_c"></td>
		  </tr>
</table>
</form>
<?php echo $content; ?>
</body>
</html>