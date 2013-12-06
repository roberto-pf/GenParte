<?
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

	require_once("lib/nusoap.php");
	
	$ns="http://genparte.disastercode.com.es/ws/nusoap";
	
	$server = new soap_server();
	$server->configureWSDL('GenParteWSDL',$ns);
	$server->wsdl->schemaTargetNamespace=$ns;
	$server->register('getCalendarFestive',
			array('strName' => 'xsd:string'), array('return' =>  'xsd:Array'),$ns);	
	
	
	function getCalendarFestive($strName){		
		$obras = json_decode(utf8_encode(getFileTxt($strName)));
		
		if(sizeof($obras->item) > 1){	
			for($i=0; $i < sizeof($obras->item) ; $i++){
				$resultado[$i]["type"] = $obras->item[$i]->strType;
				$resultado[$i]["date"] = $obras->item[$i]->strDate;
			}
		}
		return new soapval('return','xsd:Array', $resultado);
	}	
	
	
	
		
	function getFileTxt($name){
		$file = fopen("calendars/".$name.".txt", "r") or exit("Error abriendo fichero!");

		$resultado = "";
		while($linea = fgets($file)) {
			$resultado = $resultado.$linea;
			if (feof($file)) break;			
		}
		fclose($file);
		return $resultado;
	}	
	
	
		
	function changeSpecialsChar($foo){
		$foo = str_replace ( "á" , "&aacute;" , $foo );
		$foo = str_replace ( "é" , "&eacute;" , $foo );
		$foo = str_replace ( "í" , "&iacute;" , $foo );
		$foo = str_replace ( "ó" , "&oacute;" , $foo );
		$foo = str_replace ( "ú" , "&uacute;" , $foo );
		$foo = str_replace ( "Á" , "&Aacute;" , $foo );
		$foo = str_replace ( "É" , "&Eacute;" , $foo );
		$foo = str_replace ( "Í" , "&Iacute;" , $foo );
		$foo = str_replace ( "Ó" , "&Oacute;" , $foo );
		$foo = str_replace ( "Ú" , "&Uacute;" , $foo );
		$foo = str_replace ( "ñ" , "&ntilde;" , $foo );
		$foo = str_replace ( "Ñ" , "&Ntilde;" , $foo );
		$foo = str_replace ( "º" , "&ordm;" , $foo );
		$foo = str_replace ( "¡" , "&iexcl;" , $foo );
		
		$foo = str_replace ( "¿" , "&iquest;" , $foo );
		$foo = str_replace ( "ª" , "&ordf;" , $foo );
		$foo = str_replace ( "½" , "&frac12;" , $foo );
		$foo = str_replace ( "¾" , "&frac34;" , $foo );
		$foo = str_replace ( "¼" , "&frac14;" , $foo );
		
		$foo = str_replace ( "à" , "&agrave;" , $foo );
		$foo = str_replace ( "è" , "&egrave;" , $foo );
		$foo = str_replace ( "ì" , "&igrave;" , $foo );
		$foo = str_replace ( "ò" , "&ograve;" , $foo );
		$foo = str_replace ( "ù" , "&ugrave;" , $foo );
		$foo = str_replace ( "À" , "&Agrave;" , $foo );
		$foo = str_replace ( "È" , "&Egrave;" , $foo );
		$foo = str_replace ( "Ì" , "&Igrave;" , $foo );
		$foo = str_replace ( "Ò" , "&Ograve;" , $foo );
		$foo = str_replace ( "Ù" , "&Ugrave;" , $foo );
		
		$foo = str_replace ( "Ü" , "&Uuml;" , $foo );
		$foo = str_replace ( "ü" , "&uuml;" , $foo );
		
		
		return $foo;
	}
	
	
	
	$server->service($HTTP_RAW_POST_DATA);
?>