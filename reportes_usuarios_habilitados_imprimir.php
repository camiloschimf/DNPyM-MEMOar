<?php
$titulo='USUARIOS ACTIVOS';
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
	
	$parListado = new PHPRtfLite_ParFormat();
	$border = PHPRtfLite_Border::create(1, '#CCCCCC', 'dash', 0.3, false, false, false, true);
	$parListado->setBorder($border);
	
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
	do {
		$table->addRows(1);
		//$table->addColumn(2);
		$table->addColumn(14);	
		// columna 1
		//$cell = $table->getCell(1, 1);
		//$doc_img=$row_Imprimir['tipo'];
		//$cell->addImage('images/c'.$doc_img.'.jpg', null, 1, 1);
		// columna 2
		$cell = $table->getCell(1, 1);
		$cell->writeText(ucfirst($row_Imprimir['nombre'])." ".ucfirst($row_Imprimir['apellido']), new PHPRtfLite_Font(11, 'Arial'), $parListado);
		
	} while ($row_Imprimir = mysql_fetch_assoc($Imprimir));              
	
	
	$sect->writeRtfCode('\par ');
	
	
	// send to browser
	$rtf->sendRtf('reportes.doc');
	
} elseif(!empty($_POST['imprimir']) && $_POST['imprimir']=='excel') {
		
	
	/** Error reporting */
	error_reporting(E_ALL);
	date_default_timezone_set('America/Buenos_Aires');
	require_once 'Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	
	do {
		$data[]=$row_Imprimir;		
	} while ($row_Imprimir = mysql_fetch_assoc($Imprimir));			
	//echo '<pre>';print_r($data);exit;
	$baseRow = 2;
	foreach($data as $r => $dataRow) {
		$row = $baseRow + $r;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
	
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, ucfirst($dataRow['nombre'])." ".ucfirst($dataRow['apellido']));
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

	do {

		// Image
		//$htmlContent=$pdf->Image('images/c'.$row_Imprimir['tipo'].'.jpg', '', '', 10, 12);
		//$pdf->writeHTML($htmlContent, true, false, true, false, '');
		
		$htmlContent='<div>'.$row_Imprimir['codigo_referencia'].'<br />
		'.htmlentities($row_Imprimir['nombre']).'<br />
		'.desfechar($row_Imprimir['fecha']).'</div>';		
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $htmlContent, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', '<hr>', $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
		
	} while ($row_Imprimir = mysql_fetch_assoc($Imprimir));	
		
	// ---------------------------------------------------------
	
	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output('reportes.pdf', 'D');	
	
}