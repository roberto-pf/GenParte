<?php

	##################################################################################
	##                                                                              ##
	##    DESARROLLADO POR: Edwin Fredy Mamani Calderon                             ##
	##    EMAIL: mcedwin@gmail.com                                                  ##
	##    URL: http://www.mcedwin.com                                               ##
	##    URL APP: http://www.mcedwin.com/excel/                                    ##
	##    URL ARTICULO: http://tecnato.com/generar-reportes-en-excel-con-php/       ##
	##    DESARROLLADO GRACIAS A: http://www.gruposistemas.com/                     ##
	##                                                                              ##
	##################################################################################
	
	class ExcelWriter{
			
		var $content = '';
		var $err = '';
		var $xs = 0;
		var $cls = array();
		var $col = 0;
		var $maxcol = 0;
		var $rows = 0;
		var $cols = array();
		
		function ExcelWriter(){
		
		}
		
		function GetXLS($download=true){
			header ("Expires: Mon, 15 Dec 2003 05:00:00 GMT");
			header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
			header ("Cache-Control: no-cache, must-revalidate");
			header ("Pragma: no-cache");
			if($download){
				header ("Content-type: application/x-msexcel");
				header ("Content-Disposition: attachment; filename=\"" . basename('file.xml') . "\"" );
				header ("Content-Description: PHP/INTERBASE Generated Data" );
			}
			echo $this->GetHeader();
			echo $this->content;
			echo $this->GetFooter();
		}
		
		function Close(){
			$this->$content='';
			$this->$err='';
			$this->$newRow=false;
			//$this->$xs = 0;
			$this->$cls = '';
			$this->$col = 0;
			$this->$maxcol = 0;
			$this->$cols = array();
		}
						
		function GetHeader()
		{
			$header = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>MCEDWIN</Author>
  <LastAuthor>MCEDWIN</LastAuthor>
  <Created>2013-07-20T02:44:40Z</Created>
  <LastSaved>2013-07-20T02:44:58Z</LastSaved>
  <Company>Windows</Company>
  <Version>7</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <AllowPNG/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>10740</WindowHeight>
  <WindowWidth>17595</WindowWidth>
  <WindowTopX>480</WindowTopX>
  <WindowTopY>90</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
  <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Arial" x:Family="Swiss"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
				'.implode($this->cls).'				

</Styles>
<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="'.$this->maxcol.'" ss:ExpandedRowCount="'.$this->rows.'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">'."\n";
			
			for($i=0;$i<$this->maxcol;$i++){
				if(empty($this->cols[$i])) $header .= "<Column ss:Index='".($i+1)."'/>\n";
				else $header .= "<Column ss:Index='".($i+1)."' ss:Width='".$this->cols[$i]."' />\n";
			}
			return $header;
		}
		
		function GetFooter(){
			return '</Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0"/>
    <Footer x:Margin="0"/>
    <PageMargins x:Bottom="0.98" x:Left="0.79" x:Right="0.79" x:Top="0.98"/>
   </PageSetup>
   <Selected/>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
';
		}
		
		function SetContent($content){
			$this->content .= $content;
		}
		
		function WriteLine($line_arr){
			$this->content .= "<Row>";
			foreach($line_arr as $col) $this->content .= "<Cell><Data ss:Type='String'>{$col}</Data></Cell>";
			$this->content .= "</Row>";
			if($this->maxcol<=count($line_arr))$this->maxcol = count($line_arr);
			$this->rows++;
		}
		
		function GetContent(){
			return $this->content;
		}
		
		function OpenRow(){
			$this->content .= "<Row>\n";
			$this->col = 0;
		}
		
		function CloseRow(){
			$this->content .= "</Row>\n";
			$this->col = 0;
			$this->rows++;
		}
		
		function NewCell($value,$autow=false,$style=array()){
			$rel = '';
			$cl = '';
			$class = '';
			$width = 80;
			$dnum = 0;
			$align = "";
			
			if(!isset($style['type']))$style['type']='';
			if(!isset($style['bold']))$style['bold']='';
			if(!isset($style['align']))$style['align']='';
			if(!isset($style['border']))$style['border']='';
			if(!isset($style['background']))$style['background']='';
			if(!isset($style['color']))$style['color']='';
			if(!isset($style['width']))$style['width']='';

			if($style['type']=='int'){
				$rel .= 'ss:Type="Number"';
			}else if($style['type']=='date'){
				$rel .= 'ss:Type="DateTime"';
				$cl .= '<NumberFormat ss:Format="Short Date"/>';
				$value = date("Y-m-d",$value)."T00:00:00.000";
			}else{
				$rel .= 'ss:Type="String"';
			}
			
			if(!empty($style['align'])){
				$cl .= '<Alignment ss:Horizontal="'.ucfirst(strtolower($style['align'])).'" ss:Vertical="Bottom"/>';
			}
			
			if($autow==true){
				$width = strlen($value)*50;
			}else{
				if(!empty($style['width'])){
					$width = $style['width'];
				}
			}
			$this->cols[$this->col] = $width;
			$this->col++;
			if($this->maxcol<=$this->col)$this->maxcol = $this->col;
			
			if(!empty($style['border'])){
				$cl .= '<Borders>
						<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"
						 ss:Color="#'.$style['border'].'"/>
						<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"
						 ss:Color="#'.$style['border'].'"/>
						<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"
						 ss:Color="#'.$style['border'].'"/>
						<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"
						 ss:Color="#'.$style['border'].'"/>
					   </Borders>';
			}
			
		
			if((!empty($style['color']))||$style['bold']==true){
				$cl .= '<Font ss:FontName="Arial" x:Family="Swiss" '.(empty($style['color'])?'':'ss:Color="#'.$style['color'].'"').' '.(empty($style['bold'])?'':'ss:Bold="1"').'/>';
			}
			
			if(!empty($style['background'])){
				$cl .= ' <Interior ss:Color="#'.$style['background'].'" ss:Pattern="Solid"/>';
			}
			
			if(!empty($sty)) $sty = "style='$sty'";
			
			if(!empty($cl)){
				$this->cls[md5($cl)] = '<Style ss:ID="xs'.md5($cl).'">'.$cl.'</Style>'."\n";
				$class = "ss:StyleID=\"xs".md5($cl)."\"";
				//$this->xs++;
			}
			
			$this->content .= "<Cell {$class}><Data {$rel}>{$value}</Data></Cell>\n";
		}
	}
?>