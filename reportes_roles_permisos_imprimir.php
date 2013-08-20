<?php
$titulo='ROLES Y PERMISOS';
$seccion=!empty($row_vis_load_secciones_aux['descripcion'])?$row_vis_load_secciones_aux['descripcion']:"";
$seccion=iconv("ISO-8859-1", "UTF-8", $row_vis_load_secciones_aux['descripcion']);
foreach($rows_permisos AS $permiso) {
	$arr_p[$permiso['idrol']][$permiso['idseccion']]['consultar'] = $permiso['consultar'];
	$arr_p[$permiso['idrol']][$permiso['idseccion']]['modificar'] = $permiso['modificar'];
	$arr_p[$permiso['idrol']][$permiso['idseccion']]['eliminar'] = $permiso['eliminar'];	
}
//echo '<pre>';print_r($arr_p);exit;

if($totalRows_Imprimir > 0) {
	foreach($arr_Imprimir AS $value) {		
		$arr_permisos[$value['idrol']]['rol'] = $value['rol'];
		$arr_permisos[$value['idrol']]['consultar'] = $arr_p[$value['idrol']][$value['idseccion']]['consultar'];
		$arr_permisos[$value['idrol']]['modificar'] = $arr_p[$value['idrol']][$value['idseccion']]['modificar'];
		$arr_permisos[$value['idrol']]['eliminar'] = $arr_p[$value['idrol']][$value['idseccion']]['eliminar']; 
	}
}

//echo '<pre>';print_r($arr_permisos);exit;
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
	$header->writeText($titulo, new PHPRtfLite_Font(12, 'Arial'), new PHPRtfLite_ParFormat());
	$header->writeText($seccion, new PHPRtfLite_Font(12, 'Arial'), new PHPRtfLite_ParFormat());
	$header->writeText('<hr>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	
	// footer
	$footer = $rtf->addFooter('all');
	$footer->writeText('<hr>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	
	
	$fontCell = new PHPRtfLite_Font(12, 'Arial');
	$sect = $rtf->addSection();
//	$sect->writeText('Reportes de Modificaciones de Inventario:', new PHPRtfLite_Font(14), new PHPRtfLite_ParFormat('left'));
//	$sect->writeText('<br>', new PHPRtfLite_Font(), new PHPRtfLite_ParFormat());
	
	//table
	$start_row = 1;
	$table = $sect->addTable();
	$table->addRows(count($arr_permisos));
	$table->addColumnsList(array(7,14));	
	if(count($arr_permisos) > 0) {
		foreach($arr_permisos AS $v) {
			// columna 1
			$table->writeToCell($start_row, 1, $v['rol'], new PHPRtfLite_Font(11, 'Arial'), new PHPRtfLite_ParFormat());
			// columna 2
			$cell = $table->getCell($start_row, 2);
			if($v['consultar']==1  || $v['consultar']==chr(1)) {
				$cell->writeText('Consultar ', new PHPRtfLite_Font(11, 'Arial', '#090'));
			} else {
				$cell->writeText('Consultar ', new PHPRtfLite_Font(11, 'Arial', '#F00'));
			}
			if($v['modificar']==1  || $v['modificar']==chr(1)) {
				$cell->writeText(' Modificar ', new PHPRtfLite_Font(11, 'Arial', '#090'));
			} else {
				$cell->writeText(' Modificar ', new PHPRtfLite_Font(11, 'Arial', '#F00'));
			}
			if($v['eliminar']==1  || $v['eliminar']==chr(1)) {
				$cell->writeText(' Eliminar', new PHPRtfLite_Font(11, 'Arial', '#090'));
			} else {
				$cell->writeText(' Eliminar', new PHPRtfLite_Font(11, 'Arial', '#F00'));
			}					
			
			$cell->writeText(' <br>', new PHPRtfLite_Font(11, 'Arial', '#F00'));
			$start_row++;
			
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
	

	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Rol');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Consultar');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Modificar');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Eliminar');
	
	$baseRow = 2;
	$r = 1;
	foreach($arr_permisos AS $v) {
		
		if($v['consultar']==1  || $v['consultar']==chr(1)) {$c='Si';} else {$c='No';}
		if($v['modificar']==1  || $v['modificar']==chr(1)) {$m='Si';} else {$m='No';}
		if($v['eliminar']==1  || $v['eliminar']==chr(1)) {$e='Si';} else {$e='No';}							
		
		$row = $baseRow + $r;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
	
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r++);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $v['rol']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $c);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $m);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $e);
		
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
	$pdf->SetFont('helvetica', '', 10, '', true);
	
	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	
	// set JPEG quality
	$pdf->setJPEGQuality(75);	
		
	// Set some content to print
	// $pdf->Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, $palign, $ismask, $imgmask, $border, $fitbox, $hidden, $fitonpage)

	$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', "<h4>".$seccion."</h4>", $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
	$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', '<br>', $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
	
	$htmlContent="";
	if(count($arr_permisos) > 0) {
		foreach($arr_permisos AS $v) {
			$htmlContent.='<div>'.$v['rol'].'</div>';
			// columna 1
			if($v['consultar']==1  || $v['consultar']==chr(1)) {
				$htmlContent.='<span style="color: #090">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Consultar</span><br>';
			} else {
				$htmlContent.='<span style="color: #F00">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Consultar</span><br>';
			}
			if($v['modificar']==1  || $v['modificar']==chr(1)) {
				$htmlContent.='<span style="color: #090">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modificar</span><br>';
			} else {
				$htmlContent.='<span style="color: #F00">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modificar</span><br>';
			}
			if($v['eliminar']==1  || $v['eliminar']==chr(1)) {
				$htmlContent.='<span style="color: #090">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eliminar</span><br>';
			} else {
				$htmlContent.='<span style="color: #F00">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eliminar</span><br>';
			}			
									
		}
	}	

		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $htmlContent, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
	// ---------------------------------------------------------
	
	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output('reportes.pdf', 'D');	
	
}