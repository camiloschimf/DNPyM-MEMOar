<table width="119" border="0" cellspacing="0" cellpadding="0">
					 <?php if($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2") { ?>
					<tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
                    <td class="botonera"><a href="fr_ficha_registro.php" target="frcentral" class="botonera" onclick="blanco();">Fichas de Registro</a></td>
                    </tr>
                    <?php } ?>
            		<tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_prestamos" target="frcentral" class="botonera" onclick="blanco();">Pr&eacute;stamos</a></td>
                        
                    </tr>
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_localizados" target="frcentral" class="botonera" onclick="blanco();">No Localizados</a></td>
                        
                    </tr>  
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_robados" target="frcentral" class="botonera" onclick="blanco();">Robados</a></td>
                        
                    </tr> 
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_interpol" target="frcentral" class="botonera" onclick="blanco();">Informaci&oacute;n para Interpol</a></td>
                        
                    </tr> 
                     </table>
<table width="119" border="0" cellspacing="0" cellpadding="0"> 
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_inventario" target="frcentral" class="botonera" onclick="blanco();">Modificaci&oacute;n<br />de Inventario</a></td>
                        
                    </tr>  
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_forma_ingreso" target="frcentral" class="botonera" onclick="blanco();">Forma de Ingreso</a></td>
                        
                    </tr>  
                    <tr>
              			<td height="6"></td>
            		</tr>
                    <tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_tipo_documento" target="frcentral" class="botonera" onclick="blanco();">Tipo de Documento</a></td>
                        
                    </tr>  
                    <tr>
              			<td height="6"></td>
            		</tr>    
            		<tr>
              			<td class="botonera"><a href="fr_reportes.php?reporte=reportes_documentos_sinimagenes" target="frcentral" class="botonera" onclick="blanco();">Documentos sin Imagenes</a></td>
                    </tr>
                    </table>
<table width="119" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="6"></td>
                    </tr>
                    <tr>
                      <td class="botonera"><a href="fr_reportes.php?reporte=reportes_conservacion" target="frcentral" class="botonera" onclick="blanco();">Documentos Conservaci&oacute;n</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>
                    <tr>
                      <td class="botonera"><a href="fr_reportes.php?reporte=reportes_restringidos_de_exhibicion" target="frcentral" class="botonera" onclick="blanco();">Restringidos de exhibici&oacute;n</a></td>
                    </tr>
                    </table>
                   <?php if($_SESSION['MM_idrol'] == "1") { ?>
<table width="119" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="6"></td>
                    </tr>                  
                    <tr>
                      <td class="botonera"><a href="fr_reportes.php?reporte=reportes_roles_usuarios" target="frcentral" class="botonera" onclick="blanco();">Roles Usuarios</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>                    
                    <tr>
                      <td class="botonera"><a href="fr_reportes.php?reporte=reportes_roles_premisos" target="frcentral" class="botonera" onclick="blanco();">Roles Permisos</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>                    
                    <tr>
                      <td class="botonera"><a href="fr_reportes.php?reporte=reportes_usuarios_habilitados" target="frcentral" class="botonera" onclick="blanco();">Usuarios Habilitados</a></td>
                    </tr>
                    <tr>
                      <td height="6"></td>
                    </tr>                                               
         		 </table>
                 <?php } ?>