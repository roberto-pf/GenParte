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

class GenTable{
						
						
    public static function generateTable($phpExcel, $period, $events) {
		$words = split(" ", $period);
		$monthNames = array();
		array_push($monthNames, "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
		$letters = array();
		array_push($letters, "C","D","E","F","G","H","I", "J", "K", "L", "M", "N", "O", "P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI", "AJ", "AK", "AL", "AM" );
		
		$i = 0;
		for($i=0; $i<12; $i++)
			if($monthNames[$i] == $words[0])
				break;
		$mes = intval($i+1);
		$anyo = intval($words[1]);
		
		$dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anyo);
		
		$styleBoldCenter = array( 
		'font' => array( 'bold' => true, ), 
		'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
		'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), ); 
		
		$styleCenter = array( 
		'font' => array( 'bold' => false, ), 
		'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 
		'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'left' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), 
						'right' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), ); 
						
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
						
		
		$phpExcel->getActiveSheet()->mergeCells('C32:'.$letters[$dias-1].'36');		
		$phpExcel->getActiveSheet()->getStyle('C32:'.$letters[$dias-1].'36')->applyFromArray($styleBoldCenter);	

		
		for($i=0; $i<$dias; $i++){
			$phpExcel->getActiveSheet()->SetCellValue($letters[$i]."19", $i+1);
			// 0->domingo	 | 6->sabado
			$aux= date("w",mktime(0, 0, 0, $mes, $i+1, $anyo));
			if($aux==0){
				$phpExcel->getActiveSheet()->SetCellValue($letters[$i]."18", "D");
			}else if($aux==6){
				$phpExcel->getActiveSheet()->SetCellValue($letters[$i]."18", "S");
			}
			for($j=18; $j<=29; $j++){
				if($j==18 || $j==19 || $j==29)
					$phpExcel->getActiveSheet()->getStyle($letters[$i].$j)->applyFromArray($styleBoldCenter);
				else
					$phpExcel->getActiveSheet()->getStyle($letters[$i].$j)->applyFromArray($styleCenter);
				
				if($j==20)
					$phpExcel->getActiveSheet()->getStyle($letters[$i].$j)->getNumberFormat() ->setFormatCode('#,##0.0');
			}
		}
		
		//Columna total
		$phpExcel->getActiveSheet()->getColumnDimension($letters[$dias])->setWidth(11);
		$phpExcel->getActiveSheet()->SetCellValue($letters[$dias]."19", "TOTAL");
		for($j=19; $j<=29; $j++){
			if($j!=29)
				$phpExcel->getActiveSheet()->getStyle($letters[$dias].$j)->applyFromArray($styleBoldCenter);
			else
				$phpExcel->getActiveSheet()->getStyle($letters[$dias].$j)->applyFromArray($styleGrayBoldCenter);
			
			if($j!=19)
				$phpExcel->getActiveSheet()->setCellValue($letters[$dias].$j,'=SUM('.$letters[0].$j.':'.$letters[$dias-1].$j.')');
		}
		
		
		//Fila total
		for($i=0; $i<$dias; $i++){
			$phpExcel->getActiveSheet()->setCellValue($letters[$i].'29','=SUM('.$letters[$i].'20:'.$letters[$i].'28)');
		}
		
		
		//Rellenar valores horas
		//[{\"start\":\"2013-12-13\",\"end\":\"\",\"title\":\"4\",\"activity\":\"1\"}]
		$events = str_replace("\\", "", $events);
		$json = json_decode($events);
		foreach($json as $jsonArray){
			$position="";
			if($jsonArray->end==""){
				if($mes==date("m", strtotime($jsonArray->start))){
					$position = $letters[ intval(date("d", strtotime( $jsonArray->start )) )-1 ];
					$position = $position . (19+$jsonArray->activity);
					$jsonArray->title = str_replace(",", ".", $jsonArray->title);
					$phpExcel->getActiveSheet()->SetCellValue($position, $jsonArray->title);
				}
			}else{
				$dateStart = strtotime( $jsonArray->start );
				$dateEnd = strtotime($jsonArray->end);
				$jsonArray->title = str_replace(",", ".", $jsonArray->title);
				 
				for($i=$dateStart; $i<=$dateEnd; $i+=86400){//seg de un dia
					if($mes==date("m", $i)){
						$position = $letters[ intval(date("d", $i))-1 ];
						$position = $position . (19+$jsonArray->activity);
						$jsonArray->title = str_replace(",", ".", $jsonArray->title);
						$phpExcel->getActiveSheet()->SetCellValue($position, $jsonArray->title);
					}
				}
			}
		}
		
        return $phpExcel;
    }

}
