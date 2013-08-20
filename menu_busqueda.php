<table width="119" border="0" cellspacing="0" cellpadding="0">
	<?php if($_SESSION['MM_idrol'] == 1 || $_SESSION['MM_idrol'] == 2) { ?>
    <tr>
		<td height="6"></td>
	</tr>
	<tr>
		<td class="botonera"><a class="botonera" href="fr_busqueda.php" target="frcentral"  onclick="blanco();">VIGENCIA</a></td>
	</tr>
    <?PHP } ?>
     <tr>
		<td height="6"></td>
	</tr>
	<tr>
		<td class="botonera"><a class="botonera" href="fr_busquedageneral.php" target="frcentral"  onclick="blanco();">GENERAL</a></td>
	</tr>
	<tr>
	  <td height="6"></td>
  </tr>
	<tr>
	  <td class="botonera" ><a class="botonera" href="fr_cambios.php" target="frcentral"  onclick="blanco();">CAMBIOS</a></td>
  </tr>
</table>
