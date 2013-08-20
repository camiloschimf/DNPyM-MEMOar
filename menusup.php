<script language="javascript">
	function blanco() {
		window.parent.parent.frameestados.location='inicio_home.php';
	}
</script>
<table width="845" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="119" class="botonera"><div id="mc1" style="width:100%;" onmouseover="muestra_coloca('e1')" onmouseout="oculta_retarda('e1')"><a class="botonera" href="fr_niveles.php" target="frcentral" onclick="blanco();">INSTITUCIONES</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc2" style="width:100%;" onmouseover="muestra_coloca('e2')" onmouseout="oculta_retarda('e2')"><a href="fr_niveles.php" target="frcentral" class="botonera" onclick="blanco();">NIVELES</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc3" style="width:100%;" onmouseover="muestra_coloca('e3')" onmouseout="oculta_retarda('e3')"><a class="botonera" href="fr_niveles.php" target="frcentral" onclick="blanco();">DIPLOMATICOS</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc4" style="width:100%;" onmouseover="muestra_coloca('e4')" onmouseout="oculta_retarda('e4')"><a class="botonera" href="#" onclick="blanco();">BUSQUEDAS</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc5" style="width:100%;" onmouseover="muestra_coloca('e5')" onmouseout="oculta_retarda('e5')"><a class="botonera" href="#" onclick="blanco();">REPORTES</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc6" style="width:100%; height:" onmouseover="muestra_coloca('e6')" onmouseout="oculta_retarda('e6')" ><a class="botonera" href="#" onclick="blanco();">ADMINISTRACION</a></div></td>
        <td width="2"></td>
        <td width="119" class="botonera"><div id="mc7" style="width:100%;" onmouseover="muestra_coloca('e7')" onmouseout="oculta_retarda('e7')"><a class="botonera" href="#" onclick="blanco();">SEGURIDAD</a></div></td>
    </tr>
    <tr>
    	<td valign="top"><div onmouseover="muestra_retarda('e1')" onmouseout="oculta_retarda('e1')" id="e1" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_instituciones.php'); ?></div></td>
        <td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e2')" onmouseout="oculta_retarda('e2')" id="e2" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_niveles.php'); ?></div></td>
        <td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e3')" onmouseout="oculta_retarda('e3')" id="e3" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_diplomaticos.php'); ?></div></td>
        <td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e4')" onmouseout="oculta_retarda('e4')" id="e4" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_busqueda.php'); ?></div></td>
      	<td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e5')" onmouseout="oculta_retarda('e5')" id="e5" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_reportes.php'); ?></div></td>
        <td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e6')" onmouseout="oculta_retarda('e6')" id="e6" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_administracion.php'); ?></div></td>
        <td>&nbsp;</td>
        <td valign="top"><div onmouseover="muestra_retarda('e7')" onmouseout="oculta_retarda('e7')" id="e7" style="position:absolute; width:119px; visibility:hidden; overflow:auto; height:300px;" ><?php require_once('menu_seguridad.php'); ?></div></td>
    </tr>
</table>