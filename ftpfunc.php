<?
# FUNCIONES FTP 
# CONSTANTES 
# Cambie estos datos por los de su Servidor FTP
define("SERVER","192.168.0.99"); //IP o Nombre del Servidor
define("PORT",21); //Puerto
define("USER","ftpuser"); //Nombre de Usuario
define("PASSWORD","see.2011"); //Contraseña de acceso
define("PASV",true); //Activa modo pasivo

# FUNCIONES

function ConectarFTP(){
//Permite conectarse al Servidor FTP
 $id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
ftp_login($id_ftp,USER,PASSWORD); //Se loguea al Servidor FTP
ftp_pasv($id_ftp,MODO); //Establece el modo de conexión
return $id_ftp; //Devuelve el manejador a la función
 }

function SubirArchivo($archivo_local,$archivo_remoto){
	echo $archivo_local."<br>";
	echo $archivo_remoto."<br>";
//Sube archivo de la maquina Cliente al Servidor (Comando PUT)
 $id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
 
 echo ftp_systype($id_ftp)."<br>";
 
 ftp_chdir($id_ftp, '/'); 
if (ftp_nb_put($id_ftp,'filejpg.jpg','F:/dia2_47.jpg',FTP_BINARY)) {
	echo "subio correctamente";
} else {
	echo "no subio";	
}
//Sube un archivo al Servidor FTP en modo Binario
ftp_quit($id_ftp); //Cierra la conexion FTP
 }

function ObtenerRuta(){
//Obriene ruta del directorio del Servidor FTP (Comando PWD)
 $id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
$Directorio=ftp_pwd($id_ftp); //Devuelve ruta actual p.e. "/home/willy"
ftp_quit($id_ftp); //Cierra la conexion FTP
return $Directorio; //Devuelve la ruta a la función
 }
?>
