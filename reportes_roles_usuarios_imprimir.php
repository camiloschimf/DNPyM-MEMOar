<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index_log.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$titulo='ROLES y USUARIOS';
$institucion=!empty($row_vis_load_instituciones_aux['formas_conocidas_nombre'])?$row_vis_load_instituciones_aux['formas_conocidas_nombre']:"";

if(!empty($_POST['imprimir']) && $_POST['imprimir']=='doc') {
	
	require 'Classes/PHPRtfLite.php';
	
	// register PHPRtfLite class loader
	PHPRtfLite::registerAutoloader();
	
	//paragraph formats
	$parFormat = new PHPRtfLite_ParFormat();
	
	$parGreyLeft = new PHPRtfLite_ParFormat();
	$parGreyLeft->setShading(10);
	
	$parGreyCenter = new PHPRtfLite_ParFormat('center');
	$parGreyCenter->setShading(10);
		
	// rtf document
	$rtf = new PHPRtfLite();
	
	// header
	$header = $rtf->addHeader('all');
	//$header->addImage('images/encabezado_01.jpg', $parFormat);
	$header->writeText($titulo, new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	$header->writeText($institucion, new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	$header->writeText('<hr>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	
	// footer
	$footer = $rtf->addFooter('all');
	$footer->writeText('<hr>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	
	$sect = $rtf->addSection();
//	$sect->writeText('Reportes de Modificaciones de Inventario:', new PHPRtfLite_Font(14), new PHPRtfLite_ParFormat('left'));
//	$sect->writeText('<br>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());

	//table
	$table = $sect->addTable();
	$table->addRows($totalRows_vis_load_usuarios);	
	$table->addColumnsList(array(7,7));	
	$border = PHPRtfLite_Border::create(1, '#CCCCCC', 'dash', 0.1, false, true, false, false);
	$start_row = 1;
	$rol_registrado = "";
	if($totalRows_vis_load_usuarios > 0) {
		foreach($rows_usuarios AS $usuario) {
			// columna 1
			$rol_imprimir = $rol_registrado == $usuario['rol'] ? "" : $usuario['rol'];
			//!empty($rol_imprimir) ? $table->setBorderForCellRange($border, $start_row, 1, 1, 2) : "";
			$table->writeToCell($start_row, 1, $rol_imprimir, new PHPRtfLite_Font(11, 'Arial'), new PHPRtfLite_ParFormat());
			// columna 2
			$table->writeToCell($start_row, 2, ucfirst($usuario['nombre']).' '.ucfirst($usuario['apellido']), new PHPRtfLite_Font(11, 'Arial'), new PHPRtfLite_ParFormat());
			$start_row++;
			$rol_registrado = $usuario['rol'];
		}		
	}
	
	$sect->writeRtfCode('\par ');
	
	
	// send to browser
	$rtf->sendRtf('reportes.doc');
	
} elseif(!empty($_POST['imprimir']) && $_POST['imprimir']=='excel') {
		
	
	/** Error reporting */
	error_reporting(E_ALL);
	date_default_timezone_set('America/Buenos_Aires');
	require_once 'Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
		
	$rol_registrado = "";	
	$baseRow = 2;
	$r = 1;
	foreach($rows_usuarios AS $usuario) {
		$rol_imprimir = $rol_registrado == $usuario['rol'] ? "" : $usuario['rol'];
		$row = $baseRow + $r;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
	
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r++);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $rol_imprimir);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, ucfirst($usuario['nombre']).' '.ucfirst($usuario['apellido']));
		
		$rol_registrado = $usuario['rol'];
	}
	$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle(htmlentities($titulo));
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="reportes_imrimir.xls"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	
} elseif(!empty($_POST['imprimir']) && $_POST['imprimir']=='pdf') {
		
	
	require_once('Classes/tcpdf/config/lang/eng.php');
	require_once('Classes/tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set default header data
	$pdf->SetHeaderData("", 0, $titulo);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);

	// ---------------------------------------------------------
	
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	
	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	$pdf->SetFont('helvetica', '', 12, '', true);
	
	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	
	// set JPEG quality
	$pdf->setJPEGQuality(75);	
		
	// Set some content to print
	// $pdf->Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, $palign, $ismask, $imgmask, $border, $fitbox, $hidden, $fitonpage)

	$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', "<h4>".$institucion."</h4>", $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
	$rol_registrado = "";
	if($totalRows_vis_load_usuarios > 0) {
		foreach($rows_usuarios AS $usuario) {
			if($rol_registrado == $usuario['rol']) {
				$rol_imprimir = "";
				$htmlContent='<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlentities(ucfirst($usuario['nombre']).' '.ucfirst($usuario['apellido'])).'</div>';
			} else {
				$rol_imprimir = $usuario['rol'];
				$htmlContent='<div><br>'.$rol_imprimir.' <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlentities(ucfirst($usuario['nombre']).' '.ucfirst($usuario['apellido'])).'</div>';				
			}
		
			$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $htmlContent, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
			$rol_registrado = $usuario['rol'];
		}		
	}
		
	// ---------------------------------------------------------
	
	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output('reportes.pdf', 'D');	
	
}