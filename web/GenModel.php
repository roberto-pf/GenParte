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

class GenModel{

    public static function generateModel($phpExcel, $model) {
		if( strtoupper($model) == "BOYCOR"){
			$styleBoldCenter = array( 
				'font' => array( 'bold' => true, ), 
				'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
									'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
				'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
									'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
									'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
									'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), ); 
			
			$phpExcel->getActiveSheet()->getStyle('B2:B7')->applyFromArray($styleBoldCenter);
			$phpExcel->getActiveSheet()->getStyle('B2:B7')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKBLUE);
			
			$phpExcel->getActiveSheet()->SetCellValue("B5", iconv("ISO-8859-1", "UTF-8", "CIF: B70013180") );
			$phpExcel->getActiveSheet()->SetCellValue("B6", iconv("ISO-8859-1", "UTF-8", "Outeiro, 2 - Santa Maria de Vigo") );
			$phpExcel->getActiveSheet()->SetCellValue("B7", iconv("ISO-8859-1", "UTF-8", "15660 Cambre - A Coruña") );
			
			$objDrawing = new PHPExcel_Worksheet_Drawing(); 
			$objDrawing->setName('Logo'); 
			$objDrawing->setDescription('Logo'); 
			$objDrawing->setPath('./img/boycor.png'); 
			$objDrawing->setHeight(36);
			$objDrawing->setCoordinates('B2'); 
			$objDrawing->setOffsetX(40); 
			$objDrawing->setOffsetY(5); 
			$objDrawing->setWorksheet($phpExcel->getActiveSheet());
		}
        return $phpExcel;
    }

}
