<?php 
if($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2" || $_SESSION['MM_idrol'] == "3") { ?>
<table width="119" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="6"></td>
	</tr>
	<tr>
		<td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tipo_institucion" target="frcentral" onclick="blanco();">Tipo de Institucion</a></td>
	</tr>
	<tr>
		<td height="6"></td>
	</tr>
	<tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tipodeentidad" target="frcentral" onclick="blanco();">Tipo de Entidad</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=tipo_direccion" target="frcentral" onclick="blanco();">Tipo de Direcciones</a></td>
      </tr>
<?php } ?>
<?php if($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2") { ?>
		<tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_tips.php" target="frcentral" onclick="blanco();">Tips</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_estadosconservacion" target="frcentral" onclick="blanco();">Estados de Conservacion</a></td>
      </tr>
<?php } ?>
<?php if($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2" || $_SESSION['MM_idrol'] == "3") { ?>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=tipo_edificios" target="frcentral" onclick="blanco();">Edificios</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=tipo_contenedores" target="frcentral" onclick="blanco();">Contenedores</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=tipo_registro_autoridad" target="frcentral" onclick="blanco();">Registro Autoridad</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_soportes" target="frcentral" onclick="blanco();">Soportes</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_idiomas" target="frcentral" onclick="blanco();">Idiomas</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_instituciones_externas" target="frcentral" onclick="blanco();">Instituciones Externas</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_sistemas_organizaciones" target="frcentral" onclick="blanco();">Sistema de organizacion</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_autor" target="frcentral" onclick="blanco();">Autor</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_exposiciones" target="frcentral" onclick="blanco();">Exposiciones</a></td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tipo_general_documento" target="frcentral" onclick="blanco();">Tipo General de Documento</a></td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tipo_especifico_documento" target="frcentral" onclick="blanco();">Tipo Específico de Documento</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tradicion_documental" target="frcentral" onclick="blanco();">Tradición Documental</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_rubros" target="frcentral" onclick="blanco();">Rubros(Banda Sonora)</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_versiones" target="frcentral" onclick="blanco();">Versiones</a></td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_generos" target="frcentral" onclick="blanco();">Géneros</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_cromias" target="frcentral" onclick="blanco();">Cromías</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tecnicasvisuales" target="frcentral" onclick="blanco();">Técnicas Visuales</a></td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tecnicasdigitales" target="frcentral" onclick="blanco();">Técnica Digital</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tecnicasfotograficas" target="frcentral" onclick="blanco();">Técnica Fotográfica</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_emulsiones" target="frcentral" onclick="blanco();">Emulsiones</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_caracteristicasdelmontaje" target="frcentral" onclick="blanco();">Caracteristicas del montaje</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_requisitosdeejecucion" target="frcentral" onclick="blanco();">Requisitos de ejecucion</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_descriptoresmateriacontenidos" target="frcentral" onclick="blanco();">Descriptores de Materia</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_descriptoresonomasticos" target="frcentral" onclick="blanco();">Descriptores Onomasticos</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_descriptoresgeograficos" target="frcentral" onclick="blanco();">Descriptores Geograficos</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_formasdeingreso" target="frcentral" onclick="blanco();">Formas de Ingreso</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_transportes" target="frcentral" onclick="blanco();">Transportes</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_normaslegales" target="frcentral" onclick="blanco();">Norma Legales</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_tiposdeacceso" target="frcentral" onclick="blanco();">Tipos de Acceso</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_requisitosdeacceso" target="frcentral" onclick="blanco();">Requisitos de Acceso</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_accesoaladocumentacion" target="frcentral" onclick="blanco();">Acceso a Documentacion</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_serviciosdereproduccion" target="frcentral" onclick="blanco();">Servicio de Reproduccion</a></td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_envases" target="frcentral" onclick="blanco();">Envases</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_materiales" target="frcentral" onclick="blanco();">Materiales</a></td>
      </tr>
      <tr>
         <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_formaspresentaciondelaunidad" target="frcentral" onclick="blanco();">Formas presentacion de la Unidad</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_sonido" target="frcentral" onclick="blanco();">Sonidos</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a class="botonera" href="fr_administracion.php?p=aux_materialesdesoporte" target="frcentral" onclick="blanco();">Materiales de Soporte</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_medios" class="botonera"  target="frcentral" onclick="blanco();">Medios</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_agregados" class="botonera"  target="frcentral" onclick="blanco();">Agregados</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_guardas" class="botonera"  target="frcentral" onclick="blanco();">Guardas</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_tratamientosanteriores" class="botonera"  target="frcentral" onclick="blanco();">Tratamientos Anteriores</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_recomendacionestratamientos" class="botonera"  target="frcentral" onclick="blanco();">Recomendaciones de Tratamiento</a></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=aux_caracteristicatomafotografica" class="botonera"  target="frcentral" onclick="blanco();">Características de la Toma Fotográfica</a></td>
      </tr>
<?php } ?> 
<?php if(($_SESSION['MM_idrol'] == "1" || $_SESSION['MM_idrol'] == "2") &&  $_SESSION['MM_Username'] == "cschimf") { ?>  
		<tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td class="botonera"><a href="fr_administracion.php?p=ventana" class="botonera"  target="frcentral" onclick="blanco();">Ventana</a></td>
      </tr>
      
<?php } ?>   
</table>
