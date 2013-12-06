<?
/*
 ===== LICENSE =====

GENPARTE by Roberto P�rez Fern�ndez is licensed under a Creative Commons Attribution-Noncommercial-Share Alike 3.0.
Permissions beyond the scope of this license may be available.
The author can be contacted here: http://disastercode.com.es


License details: http://creativecommons.org/licenses/by-nc-sa/3.0/


You are free:

    to Share � to copy, distribute and transmit the work
    to Remix � to adapt the work

Under the following conditions:

    Attribution � You must attribute the work in the manner specified by the author or licensor (but not in any way that suggests 
    			that they endorse you or your use of the work).

    Noncommercial � You may not use this work for commercial purposes.

    Share Alike � If you alter, transform, or build upon this work, you may distribute the resulting work only under the same or 
    			similar license to this one.

With the understanding that:

    Waiver � Any of the above conditions can be waived if you get permission from the copyright holder.
    Public Domain � Where the work or any of its elements is in the public domain under applicable law, that status is in no way 
    			affected by the license.
    Other Rights � In no way are any of the following rights affected by the license:
        Your fair dealing or fair use rights, or other applicable copyright exceptions and limitations;
        The author's moral rights;
        Rights other persons may have either in the work itself or in how the work is used, such as publicity or privacy rights.
    Notice � For any reuse or distribution, you must make clear to others the license terms of this work. The best way to do this 
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
		$foo = str_replace ( "�" , "&aacute;" , $foo );
		$foo = str_replace ( "�" , "&eacute;" , $foo );
		$foo = str_replace ( "�" , "&iacute;" , $foo );
		$foo = str_replace ( "�" , "&oacute;" , $foo );
		$foo = str_replace ( "�" , "&uacute;" , $foo );
		$foo = str_replace ( "�" , "&Aacute;" , $foo );
		$foo = str_replace ( "�" , "&Eacute;" , $foo );
		$foo = str_replace ( "�" , "&Iacute;" , $foo );
		$foo = str_replace ( "�" , "&Oacute;" , $foo );
		$foo = str_replace ( "�" , "&Uacute;" , $foo );
		$foo = str_replace ( "�" , "&ntilde;" , $foo );
		$foo = str_replace ( "�" , "&Ntilde;" , $foo );
		$foo = str_replace ( "�" , "&ordm;" , $foo );
		$foo = str_replace ( "�" , "&iexcl;" , $foo );
		
		$foo = str_replace ( "�" , "&iquest;" , $foo );
		$foo = str_replace ( "�" , "&ordf;" , $foo );
		$foo = str_replace ( "�" , "&frac12;" , $foo );
		$foo = str_replace ( "�" , "&frac34;" , $foo );
		$foo = str_replace ( "�" , "&frac14;" , $foo );
		
		$foo = str_replace ( "�" , "&agrave;" , $foo );
		$foo = str_replace ( "�" , "&egrave;" , $foo );
		$foo = str_replace ( "�" , "&igrave;" , $foo );
		$foo = str_replace ( "�" , "&ograve;" , $foo );
		$foo = str_replace ( "�" , "&ugrave;" , $foo );
		$foo = str_replace ( "�" , "&Agrave;" , $foo );
		$foo = str_replace ( "�" , "&Egrave;" , $foo );
		$foo = str_replace ( "�" , "&Igrave;" , $foo );
		$foo = str_replace ( "�" , "&Ograve;" , $foo );
		$foo = str_replace ( "�" , "&Ugrave;" , $foo );
		
		$foo = str_replace ( "�" , "&Uuml;" , $foo );
		$foo = str_replace ( "�" , "&uuml;" , $foo );
		
		
		return $foo;
	}
	
	
	
	$server->service($HTTP_RAW_POST_DATA);
?>