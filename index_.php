<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<table width="250" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Instituciones</td>
  </tr>
  <tr>
    <td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="instituciones.php">Nueva institucion</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="instituciones_buscar.php">Buscar</a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>Niveles</td>
  </tr>
    <tr>
    <td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tmp_niveles.php">Buscar</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>Diplomáticos</td>
  </tr>
  <tr>
  <td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
    <tr>
      <td height="6"></td>
    </tr>
    <tr>
      <td><a href="tmp_niveles.php">Buscar</a></td>
    </tr>
    <tr>
      <td height="6"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
  <tr>
    <td>Seguridad</td>
  </tr>
  <tr>
  <td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
    <tr>
      <td height="6"></td>
    </tr>
    <tr>
      <td><a href="usuarios.php">Usuarios</a></td>
    </tr>
    <tr>
    	<td height="6"></td>
    </tr>
    <tr>
      <td height="6"><a href="roles.php">Roles</a></td>
    </tr>
    <tr>
    	<td height="6"></td>
    </tr>
    <tr>
      <td height="6"><a href="secciones.php">Secciones</a></td>
    </tr>
    <tr>
    	<td height="6"></td>
    </tr>
    <tr>
      <td height="6"><a href="permisos.php">Permisos</a></td>
    </tr>
    <tr>
    	<td height="6"></td>
    </tr>
    <tr>
      <td height="6"><a href="grupos.php">Grupos</a></td>
    </tr>
    <tr>
      <td height="6"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
  <tr>
  	<td>Busqueda</td>
  </tr>
  <tr>
  	<td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
  	  <tr>
  	    <td height="6"></td>
	    </tr>
  	  <tr>
  	    <td><a href="vigencia.php">Vigencia</a></td>
	    </tr>
  	  <tr>
  	    <td height="6"></td>
	    </tr>
  	  <tr>
  	    <td height="6">&nbsp;</td>
	    </tr>
  	  <tr>
  	    <td height="6"></td>
	    </tr>
    </table></td>
  </tr>
  <tr>
    <td>Tablas Auxiliares</td>
  </tr>
  <tr>
    <td><table width="230" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tipo_institucion.php">Tipo de Institucion</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tipodeentidad.php">Tipo de Entidad</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tipo_direccion.php">Tipo de Direcciones</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tips.php">Tips</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tipo_edificios.php">Edificios</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tipo_contenedores.php">Contenedores</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="tipo_registro_autoridad.php">Registro Autoridad</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_soportes.php">Soportes</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_idiomas.php">Idiomas</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_instituciones_externas.php">Instituciones Externas</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_sistemas_organizaciones.php">Sistema de organizacion</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_autor.php">Autor</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_exposiciones.php">Exposiciones</a></td>
      </tr>
      <tr>
       <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tipo_general_documento.php">Tipo General de Documento</a></td>
      </tr>
      <tr>
       <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tipo_especifico_documento.php">Tipo Específico de Documento</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tradicion_documental.php">Tradición Documental</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_rubros.php">Rubros(Banda Sonora)</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_versiones.php">Versiones</a></td>
      </tr>
      <tr>
       <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_generos.php">Géneros</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_cromias.php">Cromías</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tecnicasvisuales.php">Técnicas Visuales</a></td>
      </tr>
      <tr>
       <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tecnicasdigitales.php">Técnica Digital</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tecnicasfotograficas.php">Técnica Fotográfica</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_emulsiones.php">Emulsiones</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_caracteristicasdelmontaje.php">Caracteristicas del montaje</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_requisitosdeejecucion.php">Requisitos de ejecucion</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_descriptoresmateriacontenidos.php">Descriptores de Materia</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_descriptoresonomasticos.php">Descriptores Onomasticos</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_descriptoresgeograficos.php">Descriptores Geograficos</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_formasdeingreso.php">Formas de Ingreso</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_transportes.php">Transportes</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_normaslegales.php">Norma Legales</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_tiposdeacceso.php">Tipos de Acceso</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_requisitosdeacceso.php">Requisitos de Acceso</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_accesoaladocumentacion.php">Acceso a Documentacion</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_serviciosdereproduccion.php">Servicio de Reproduccion</a></td>
      </tr>
      <tr>
       <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_envases.php">Envases</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_materiales.php">Materiales</a></td>
      </tr>
      <tr>
         <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_formaspresentaciondelaunidad.php">Formas presentacion de la Unidad</a></td>
      </tr>
      <tr>
        <td height="6"></td>
      </tr>
      <tr>
        <td><a href="aux_sonido.php">Sonidos</a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>