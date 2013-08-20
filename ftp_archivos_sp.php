<?php require_once('Connections/conn.php'); ?>
<?php require_once('funtions.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php 

function deleteindices($idT2,$database_conn,$conn) {
	$updateSQL13 = "DELETE FROM archivos_digitales WHERE codigo_archivo=".$idT2;
	mysql_select_db($database_conn, $conn);
	$Result13 = mysql_query($updateSQL13, $conn) or die(mysql_error());	;
}

function rediraut($cod,$idT2,$msg2) {
	$m="";
	$m.="	<script>";
	$m.="	window.parent.document.location='documentos_archivos.php?cod=".$cod."&arc=".$idT2."&msg2=".$msg2."';";
	$m.="	</script>";
	echo $m;
}

//verificamos recibir el archivo


if($_FILES['archivoselect']['name'] != "" && $_POST['fecha_toma_archivoa'] != "") {
	echo "Codigo Referencia. ".$_POST['codigo_referencia']."<br>";
	echo "Nombre del archivo: ".$_FILES['archivoselect']['name']."<br>";
	
	$file = $_FILES['archivoselect']['name'];
	$partes = explode(".", $file); 
	$extencion = strtoupper(end($partes));
	
	echo "Extension: ".$extencion ."<br>";
	
	//comprobamos que sean las extensiones permitidas
	if($extencion == "jpg" || $extencion == "JPG" || $extencion == "mp3" || $extencion == "MP3" || $extencion == "FLV" || $extencion == "flv") {
		
		$insertSQL = sprintf("INSERT INTO archivos_digitales (codigo_referencia, fecha_toma_archivo, tipo, extension) VALUES (%s, %s, %s, %s)",
                       	GetSQLValueString($_POST['codigo_referencia'], "text"),
                       	GetSQLValueString(fechar($_POST['fecha_toma_archivod'],$_POST['fecha_toma_archivom'],$_POST['fecha_toma_archivoa']), "date"),
					   	GetSQLValueString($_POST['tipo_documento'], "date"),
					   	GetSQLValueString($extencion, "text"));

  		mysql_select_db($database_conn, $conn);
  		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
		$idT2 = mysql_insert_id();
		
		if($_POST['tipo_documento'] == "8" || $_POST['tipo_documento'] == "11") {
			
			$filename = md5($idT2).".JPG";
			
		} else if ($_POST['tipo_documento'] == "9") {
			
			$filename = md5($idT2).".FLV";
			
		} else if ($_POST['tipo_documento'] == "10") {
			
			$filename = md5($idT2).".MP3";
			
		}
		
		echo "Nuevo Nombre: $filename <br>";
		
		if(($_POST['tipo_documento'] == "8" && $extencion == "JPG") || ($_POST['tipo_documento'] == "11" && $extencion == "JPG") || ($_POST['tipo_documento'] == "9" && $extencion == "FLV") || ($_POST['tipo_documento'] == "10" && $extencion == "MP3")) {
		
			if(!move_uploaded_file($_FILES['archivoselect']['tmp_name'], "archivos/$filename")) {
				
				echo "Mensaje: No se  puedo cargar el archivo";
				deleteindices($idT2,$database_conn,$conn);
				rediraut($_POST['codigo_referencia'],"","No se pudo cargar el archivo");
				
			} else {
				
				echo "Mensaje: El archivo se carco correctaemnte";
				rediraut($_POST['codigo_referencia'],"","El archivo se cargó correctamente");
			}
			
		} else {
			
			deleteindices($idT2,$database_conn,$conn);
			echo "Mensaje: Verifique el tipo de documento";
			rediraut($_POST['codigo_referencia'],"","Verifique el tipo de documento");
			
		}
		
	} else {
		
		echo "Mensaje: No es una extension permitida";
		rediraut($_POST['codigo_referencia'],"","No es un tipo de archivo permitido");
		
	}

} else {
	
	echo "Mensaje: Datos Incompletos";
	rediraut($_POST['codigo_referencia'],"","No hay archivos para subir");
	
}








/*

if(isset($_POST['archivo']) && $_POST['archivo'] != "") {
	
	
	
	
	
	
	
	
	
	
	$file = $_POST['archivo'];
	$partes = explode(".", $file); 
	$extencion = end($partes);
	
	echo $file."<br>";

if($extencion == "jpg" || $extencion == "JPG" || $extencion == "mp3" || $extencion == "MP3" || $extencion == "FLV" || $extencion == "flv") {	
	//inicializamos el archivo en la base de datos
	if($_POST['fecha_toma_archivoa'] != "") {
		$insertSQL = sprintf("INSERT INTO archivos_digitales (codigo_referencia, fecha_toma_archivo, tipo, extension) VALUES (%s, %s, %s, %s)",
                       	GetSQLValueString($_POST['codigo_referencia'], "text"),
                       	GetSQLValueString(fechar($_POST['fecha_toma_archivod'],$_POST['fecha_toma_archivom'],$_POST['fecha_toma_archivoa']), "date"),
					   	GetSQLValueString($_POST['tipo_documento'], "date"),
					   	GetSQLValueString($extencion, "text"));

  		mysql_select_db($database_conn, $conn);
  		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
		$idT2 = mysql_insert_id();
	} else {
	$msg2="No se ingreso la fecha del archivo.";
		 $idT2 = 0; 	
	}
	
	//establecemos nuevo nombre cifrado sin extencion
	$remote_file = $path_ftp.md5($idT2);
	
	echo $remote_file."<br>";
	// establecer una conexión básica
	$conn_id = ftp_connect($hostname_ftp);
	
	
	if($conn_id) {
		echo "conexion correcta<br>";
	} else {
		echo "no se puedo conectar<br>";
	}
	
	// iniciar sesión con nombre de usuario y contraseña
	$login_result = ftp_login($conn_id, $username_ftp , $password_ftp);
	
	if($login_result) {
		echo "autenticacion correcta<br>";
	} else {
		echo "no se puedo autenticar<br>";
	}
	
	ftp_pasv($conn_id, true);
	
	/*$newdir = ftp_chdir($conn_id, "/");
	if($newdir){
		echo "se cambio de directorio correctamente<br>";
	}else {
		echo "no se pudo cambiar de directorio<br>";
	}*/
	
	/*if(ftp_get($conn_id, 'memorar.jpg', 'D:\memorar.jpg', FTP_BINARY)) {
		echo "se pudoooooo";
	} else {
			echo "la mierda no se puedo bajar<br>";
		}*/
/*
	
	if($_POST['tipo_documento'] == "8" || $_POST['tipo_documento'] == "11") {
			echo $_POST['tipo_documento']."<br>";
			if($extencion == "jpg" || $extencion == "JPG") {
				echo $extencion."<br>";
				// cargar un archivo
				if (ftp_put($conn_id, $remote_file.".jpg", $file, FTP_BINARY)) {
				 echo "se ha cargado $file con exito\n";
				 echo $file."<br>";
				 echo $remote_file."<br>";
				} else {
					echo "Error en la caraga de $remote_file.jpg<br>";
				 	deleteindices($idT2,$database_conn,$conn);
				}
			} else {
					deleteindices($idT2,$database_conn,$conn);
					$msg2="Verifique que sea un archivo *.jpg";
			}
		  
	  } else if ($_POST['tipo_documento'] == "9") {

			if($extencion == "flv" || $extencion == "FLV") {
				// cargar un archivo
				if (ftp_put($conn_id, $remote_file.".flv", $file, FTP_BINARY)) {
				 echo "se ha cargado $file con éxito\n";
				} else {
				 	deleteindices($idT2,$database_conn,$conn);
				}
			} else {
					deleteindices($idT2,$database_conn,$conn);	
					$msg2="Verifique que sea un archivo *.flv";
			}
		  
	  } else if ($_POST['tipo_documento'] == "10") {

			if($extencion == "mp3" || $extencion == "MP3") {
				// cargar un archivo
				if (ftp_put($conn_id, $remote_file.".mp3", $file, FTP_BINARY)) {
				 echo "se ha cargado $file con éxito\n";
				} else {
				 	deleteindices($idT2,$database_conn,$conn);
				}
	  		} else {
					deleteindices($idT2,$database_conn,$conn);
					$msg2="Verifique que sea un archivo *.mp3";
			}
		  
	  }
	  
	  // cerrar la conexión ftp
	  ftp_close($conn_id);
	  rediraut($_POST['codigo_referencia'],$idT2,$msg2);

} else {
	rediraut($_POST['codigo_referencia'],"","No es un archivo permitido");
}
	
} else {
	
	rediraut($_POST['codigo_referencia'],"","No hay archivos para subir");
	
}
*/

?>