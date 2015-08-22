<?php
/* -----------------------------------------------------
 * File name 	: additional.lib.php							
 * Created by	: aryonp@gmail.com	
 * Modify date 	: 15.11.2014
 * -----------------------------------------------------				            
 * Purpose	 	: Contain additional functions for the QMS																	                 			
 * ----------------------------------------------------- 
 */

function iterateMD5($times,$input) {
	for($i=0;$i<$times;$i++){
		$input = md5($input);
	}
	return $input;
}

//------------------------------------------------------------------------------

function chkLicense(){
	$licenseKey = file_get_contents('license.key.txt');
	$actualSettings = substr(iterateMD5(PASS_ITERATE,date('Y-m-d H:i:s')),0,7).iterateMD5(PASS_ITERATE,MAX_ADMIN).iterateMD5(PASS_ITERATE,PRODUCT_SN);
	
	if(!$licenseKey){
		header("Location:".LIC_INVALID_PAGE."");
	}	
	
	if(substr($licenseKey,7) != substr($actualSettings,7)) {
		header("Location:".LIC_INVALID_PAGE."");
	}

}

?>