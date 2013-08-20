<table width="119" border="0" cellspacing="0" cellpadding="0">
            		<?php if($_SESSION['MM_idrol'] == 1) { ?>
                    <tr>
              			<td height="6"></td>
            		</tr>
            		<tr>
              			<td class="botonera"><a class="botonera" href="fr_seguridad.php?p=usuarios_lista" target="frcentral" onclick="blanco();">Usuarios</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>
            		<tr>
              			<td class="botonera"><a class="botonera" href="fr_seguridad.php?p=roles" target="frcentral" onclick="blanco();">Roles</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>
            		<tr>
              			<td class="botonera"><a class="botonera" href="fr_seguridad.php?p=permisos_lista" target="frcentral" onclick="blanco();">Permisos</a></td>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td height="6"></td>
                    </tr>
                    <tr>
              			<td class="botonera"><a class="botonera" href="salir.php"  onclick="blanco();">Salir</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>
            		<!-- <tr>
              			<td class="botonera"><a class="botonera" href="fr_seguridad.php?p=grupos" target="frcentral">Grupos</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>            		<tr>
              			<td class="botonera"><a class="botonera" href="fr_seguridad.php?p=usuarios_grupos" target="frcentral">Usuarios Grupos</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr> -->                                                           
         		 </table>