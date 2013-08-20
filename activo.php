<script language="javascript">
	function activar() {
		document.getElementById('pantallanegra').style.display='block';
		document.getElementById('pantallaespera').style.display='block';
		
	}
	
	function desactivar() {
		document.getElementById('pantallanegra').style.display='none';
		document.getElementById('pantallaespera').style.display='none';
		
	}

</script>
<div id="pantallanegra" style="width:100%; height:500%; position:absolute; z-index:100000; left:0px; top:0px; background-color:#000; filter: Alpha(Opacity=50); opacity: 0.5; display:none;"></div>
<div id="pantallaespera" style="width:100%; height:100%; position:absolute; z-index:100001; left:0px; top:0px; text-align:center; display:none;">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><img src="images/loader_pos.gif" width="40" height="40"></p>
</div>
<!-- codigo vista rapida -->
<div id="fondo_vista_rapida" style="width:100%; height:100%; position:absolute; z-index:100000; left:0px; top:0px; display:none; ">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top"><div id="fondo_vista_rapida_1" style="width:100%; height:100%;  background-color:#000; filter: Alpha(Opacity=50); opacity: 0.5;">&nbsp;</div></td>
      <td width="680" valign="top"><table width="680" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#75bdd9" class="niv_menu colorBlanco">&nbsp;&nbsp;Vista R&aacute;pida</td>
    <td align="right" bgcolor="#75bdd9"><a href="#" onClick="bajar_vistar();"><img src="images/cerr.jpg"  alt="Cerrar" width="43" height="17" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="2"><iframe width="680" height="<?php echo $_SESSION['MM_Height']-80; ?>" frameborder="1" src="blanck.php" id="frm_vista_rapida" name="frm_vista_rapida" style="background-color:#FFFfff;"></iframe></td>
  </tr>
</table>
</td>
      <td valign="top"><div id="fondo_vista_rapida_1" style="width:100%; height:100%;  background-color:#000; filter: Alpha(Opacity=50); opacity: 0.5;">&nbsp;</div></td>
    </tr>
  </table>
</div>