<table width="119" border="0" cellspacing="0" cellpadding="0">
	<!--<tr>
		<td height="6"></td>
	</tr>
	<tr>
		<td class="botonera">BUSCAR</td>
	</tr>-->
<?php if($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2") { ?>    
    <tr>
		<td height="6"></td>
	</tr>
    <tr>
		<td class="botonera"><a class="botonera" href="fr_institucion.php?url=instituciones_udp_codigo&title=CORREGIR CÓDIGO DE INSITUCIONES" target="frcentral"  onclick="blanco();">CORREGIR CODIGOS</a></td>
	</tr>
	<tr>
		<td height="6"></td>
	</tr>
	<tr>
		<td class="botonera"><a class="botonera" href="fr_institucion.php?url=instituciones" target="frcentral"  onclick="blanco();">NUEVA</a></td>
	</tr>
<?php } ?>  
 	<tr>
		<td height="6"></td>
	</tr>
    <tr>
		<td class="botonera"><a class="botonera" href="fr_niveles.php" target="frcentral"  onclick="blanco();">VER</a></td>
	</tr> 
</table>
