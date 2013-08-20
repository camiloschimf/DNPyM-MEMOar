<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MEMORar Direción Nacional de Pantrimonio y Museos</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
	scrollbar-face-color: #a99e88;
 	scrollbar-base-color: #d4dce7;
 	scrollbar-arrow-color: #000000;
 	//scrollbar-highlight-color: #181502;
 	scrollbar-3d-light-color: #369;
	 scrollbar-shadow-color: #036;
 	scrollbar-dark-shadow-color: #036;
}

</style>
<script type='text/javascript' src='gyl_menu.js'></script>
<script type='text/javascript'> 
var empezar = false
//var anclas = new Array ("ancla1","ancla2","ancla3","ancla4")
var capas = new Array("e1")
var retardo 
var ocultar

function ct() {
	document.getElementById('e1').style.visibility='hidden';
	document.getElementById('e2').style.visibility='hidden';
	document.getElementById('e3').style.visibility='hidden';
	document.getElementById('e4').style.visibility='hidden';
	document.getElementById('e5').style.visibility='hidden';
	document.getElementById('e6').style.visibility='hidden';
	document.getElementById('e7').style.visibility='hidden';
}
 
function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}

function muestra_coloca(capa){
	ct();
	clearTimeout(retardo)
	xShow(capa)
}
 
function oculta_retarda(capa){
	ocultar =capa
	clearTimeout(retardo)
	retardo = setTimeout("xHide('" + ocultar + "')",1000)
}
 
function muestra_retarda(ind){
	clearTimeout(retardo)
}
</script>
</head>
<script language="javascript">
function TamVentana() {   
  var Tamanyo = [0, 0];   
  if (typeof window.innerWidth != 'undefined')   
  {   
    Tamanyo = [   
        window.innerWidth,   
        window.innerHeight   
    ];   
  }   
  else if (typeof document.documentElement != 'undefined'   
      && typeof document.documentElement.clientWidth !=   
      'undefined' && document.documentElement.clientWidth != 0)   
  {   
 Tamanyo = [   
        document.documentElement.clientWidth,   
        document.documentElement.clientHeight   
    ];   
  }   
  else   {   
    Tamanyo = [   
        document.getElementsByTagName('body')[0].clientWidth,   
        document.getElementsByTagName('body')[0].clientHeight   
    ];   
  }   
  return Tamanyo;   
}

function tamaniar() {
	var Tam = TamVentana();
  	document.getElementById('frcentral').style.height = Tam[1]-130; 
}

 window.onload = function() {   
  var Tam = TamVentana();
  document.getElementById('frcentral').style.height = Tam[1]-130; 
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');    
}; 

window.onresize = function() {   
  var Tam = TamVentana();
  document.getElementById('frcentral').style.height = Tam[1]-130;   
  //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');   
};

</script>
<body>
<table width="1334" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="1334" height="57" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="453"><a href="index.php"><img src="images/encabezado_01.jpg" width="453" height="57" border="0"></a></td>
        <td width="870" style="background-image:url(images/encabezado_03.jpg)"></td>
        <td width="11" valign="top"><img src="images/encabezado_02.jpg" width="11" height="47"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><div id="frcentral">
      <table width="1334" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30"><img src="images/angulo_08.jpg" width="1334" height="30"></td>
        </tr>
        <tr>
          <td style="background:url(images/angulo_12.jpg)">
            <table width="300" border="1" align="center" cellpadding="5" cellspacing="0">
              <tr>
                <td style="border-color:#a99e88; border-style:solid; border-width:medium;"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" class="textoCampo" style="font-size:16px;"><strong>No tiene acceso al sistema.</strong></td>
                  </tr>
                  <tr>
                    <td height="6"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                <!--  <tr>
                    <td align="center" class="textoCampo"><strong>Si no recuerda su contraseña ingrese <a href="index_relog.php">aquí</a></strong></td>
                  </tr> -->
                </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="30"><img src="images/angulo_11.jpg" width="1334" height="30"></td>
        </tr>
    </table></div></td>
  </tr>
  <tr>
    <td height="6"></td>
  </tr>
  <tr>
    <td><table width="1334" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="11" valign="bottom"><img src="images/pie_02.jpg" width="11" height="47"></td>
        <td width="871" valign="bottom" style="background-image:url(images/pie_01.jpg)">&nbsp;</td>
        <td width="452"><img src="images/pie_03.jpg" width="452" height="57"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>