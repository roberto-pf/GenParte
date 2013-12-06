<?php
/*
 ===== LICENSE =====

GENPARTE by Roberto Pérez Fernández is licensed under a Creative Commons Attribution-Noncommercial-Share Alike 3.0.
Permissions beyond the scope of this license may be available.
The author can be contacted here: http://disastercode.com.es


License details: http://creativecommons.org/licenses/by-nc-sa/3.0/


You are free:

    to Share — to copy, distribute and transmit the work
    to Remix — to adapt the work

Under the following conditions:

    Attribution — You must attribute the work in the manner specified by the author or licensor (but not in any way that suggests 
    			that they endorse you or your use of the work).

    Noncommercial — You may not use this work for commercial purposes.

    Share Alike — If you alter, transform, or build upon this work, you may distribute the resulting work only under the same or 
    			similar license to this one.

With the understanding that:

    Waiver — Any of the above conditions can be waived if you get permission from the copyright holder.
    Public Domain — Where the work or any of its elements is in the public domain under applicable law, that status is in no way 
    			affected by the license.
    Other Rights — In no way are any of the following rights affected by the license:
        Your fair dealing or fair use rights, or other applicable copyright exceptions and limitations;
        The author's moral rights;
        Rights other persons may have either in the work itself or in how the work is used, such as publicity or privacy rights.
    Notice — For any reuse or distribution, you must make clear to others the license terms of this work. The best way to do this 
    	is with a link to this web page.

===== LICENSE ===== 
*/
require_once("Classes/PHPExcel.php");
require_once("Classes/PHPExcel/Writer/Excel5.php");
require_once("GenTable.php");
require_once("GenModel.php");

//Inicialice Variables
//monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
//if(isset($_POST['generateXLS'])){

$VAR_NAME = $_POST['name'];
$VAR_TELEPHONE = $_POST['telephone'];
$VAR_NUM_ORDER = $_POST['numOrder'];
$VAR_SAT = $_POST['sat'];
$VAR_AT = $_POST['at'];
$VAR_NUM_ORDER_SALE = $_POST['numOrderSale'];
$VAR_NUM_SALE = $_POST['numSale'];
$VAR_COMMENTS = $_POST['comments'];
$VAR_MODEL_PART = $_POST['modelPart'];
$VAR_EVENTS = $_POST['events'];
$VAR_PERIOD = $_POST['period'];


$objPHPExcel = new PHPExcel();

//Datos sobre autoría
$objPHPExcel->getProperties()->setCreator("genparte.disastercode.com.es");
$objPHPExcel->getProperties()->setLastModifiedBy("genparte.disastercode.com.es");
$objPHPExcel->getProperties()->setTitle("Horas de ".$VAR_PERIOD);
$objPHPExcel->getProperties()->setSubject("Parte de horas perteneciente a: ".$VAR_PERIOD);
$objPHPExcel->getProperties()->setDescription("Este parte de horas ha sido generado con la herramienta gratuita alojada en http://genparte.disastercode.com.es");


$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);


//Se crean 3 hojas y se trabaja en la primera "0"
$objPHPExcel->createSheet();
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Hoja2');
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setTitle('Hoja3');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Hoja1');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);


//Columns Format
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setVisible(false);
$objPHPExcel->getActiveSheet()->getDefaultColumnDimension('C')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(27);

for($i=9; $i<17; $i++)
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':Q'.$i);
	
$objPHPExcel->getActiveSheet()->mergeCells('B32:B36');
$objPHPExcel->getActiveSheet()->mergeCells('B39:B42');

$objPHPExcel->getActiveSheet()->mergeCells('C39:J42');


//$objPHPExcel->getActiveSheet()->getStyle('B9:B16')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF808080');

$styleGrayBoldLeft = array( 
	'font' => array( 'bold' => true, ), 
	'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
	'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), 
	'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 
	'startcolor' => array( 'argb' => 'FF808080', ), 
	'endcolor' => array( 'argb' => 'FF808080', ), ), ); 
	
$styleGrayBoldCenter = array( 
	'font' => array( 'bold' => true, ), 
	'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
	'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), 
	'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 
	'startcolor' => array( 'argb' => 'FF808080', ), 
	'endcolor' => array( 'argb' => 'FF808080', ), ), ); 

$styleBoldLeft = array( 
	'font' => array( 'bold' => true, ), 
	'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
	'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), ); 
	
$styleBoldCenter = array( 
	'font' => array( 'bold' => true, ), 
	'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
	'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), ); 
	

for($i=9; $i<17; $i++){
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleGrayBoldLeft);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':Q'.$i)->applyFromArray($styleBoldLeft);
}
$objPHPExcel->getActiveSheet()->getStyle('B19')->applyFromArray($styleGrayBoldCenter);	

$objPHPExcel->getActiveSheet()->getStyle('B20')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B21')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B22')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B23')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B24')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B25')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B26')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B27')->applyFromArray($styleBoldLeft);	
$objPHPExcel->getActiveSheet()->getStyle('B28')->applyFromArray($styleBoldLeft);	


$objPHPExcel->getActiveSheet()->getStyle('B29')->applyFromArray($styleGrayBoldCenter);	
$objPHPExcel->getActiveSheet()->getStyle('B32:B36')->applyFromArray($styleGrayBoldCenter);	
$objPHPExcel->getActiveSheet()->getStyle('B39:B42')->applyFromArray($styleGrayBoldCenter);	
$objPHPExcel->getActiveSheet()->getStyle('C39:J42')->applyFromArray($styleBoldCenter);	



$objPHPExcel->getActiveSheet()->SetCellValue("B9", "NOMBRE Y APELLIDOS");
$objPHPExcel->getActiveSheet()->SetCellValue("C9", $VAR_NAME);
$objPHPExcel->getActiveSheet()->SetCellValue("B10", "TELEFONO");
$objPHPExcel->getActiveSheet()->SetCellValue("C10", $VAR_TELEPHONE);
$objPHPExcel->getActiveSheet()->SetCellValue("B11", "PARTE DEL MES");
$objPHPExcel->getActiveSheet()->SetCellValue("C11", $VAR_PERIOD);
$objPHPExcel->getActiveSheet()->SetCellValue("B12", iconv("ISO-8859-1", "UTF-8", "Número de Pedido"));
$objPHPExcel->getActiveSheet()->SetCellValue("C12", $VAR_NUM_ORDER);
$objPHPExcel->getActiveSheet()->SetCellValue("B13", "SAT");
$objPHPExcel->getActiveSheet()->SetCellValue("C13", $VAR_SAT);
$objPHPExcel->getActiveSheet()->SetCellValue("B14", "AT");
$objPHPExcel->getActiveSheet()->SetCellValue("C14", $VAR_AT);
$objPHPExcel->getActiveSheet()->SetCellValue("B15", iconv("ISO-8859-1", "UTF-8", "Nº Pedido Venta"));
$objPHPExcel->getActiveSheet()->SetCellValue("C15", $VAR_NUM_ORDER_SALE);
$objPHPExcel->getActiveSheet()->SetCellValue("B16", iconv("ISO-8859-1", "UTF-8", "Nº de Oferta"));
$objPHPExcel->getActiveSheet()->SetCellValue("C16", $VAR_NUM_SALE);
$objPHPExcel->getActiveSheet()->SetCellValue("C32", $VAR_COMMENTS);

$objPHPExcel->getActiveSheet()->SetCellValue("B19", "ACTIVIDAD");
$objPHPExcel->getActiveSheet()->SetCellValue("B20", iconv("ISO-8859-1", "UTF-8", "Nº HORAS/DIA"));
$objPHPExcel->getActiveSheet()->SetCellValue("B21", iconv("ISO-8859-1", "UTF-8", "Nº HORAS EXTRAS"));
$objPHPExcel->getActiveSheet()->SetCellValue("B22", iconv("ISO-8859-1", "UTF-8", "Nº DE GUARDIAS"));
$objPHPExcel->getActiveSheet()->SetCellValue("B23", iconv("ISO-8859-1", "UTF-8", "INCIDENCIAS EN GUARDIA"));
$objPHPExcel->getActiveSheet()->SetCellValue("B24", iconv("ISO-8859-1", "UTF-8", "INTERVENCIONES"));
$objPHPExcel->getActiveSheet()->SetCellValue("B25", iconv("ISO-8859-1", "UTF-8", "DIAS DE VACACIONES"));
$objPHPExcel->getActiveSheet()->SetCellValue("B26", iconv("ISO-8859-1", "UTF-8", "DIAS DE ENFERMEDAD"));
$objPHPExcel->getActiveSheet()->SetCellValue("B27", iconv("ISO-8859-1", "UTF-8", "OTROS"));
$objPHPExcel->getActiveSheet()->SetCellValue("B28", "");
$objPHPExcel->getActiveSheet()->SetCellValue("B29", "TOTAL");
$objPHPExcel->getActiveSheet()->SetCellValue("B32", "COMENTARIOS");
$objPHPExcel->getActiveSheet()->SetCellValue("B39", "Firma responsable de proyecto:");

$objPHPExcel = GenTable::generateTable($objPHPExcel, $VAR_PERIOD, $VAR_EVENTS);

$objPHPExcel = GenModel::generateModel($objPHPExcel, $VAR_MODEL_PART);




//Create File XLS
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Parte '.$VAR_PERIOD.'.xls"');
header('Cache-Control: max-age=0');
// Creamos el Archivo .xls
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>