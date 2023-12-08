<?php
function getMobileState( $mobileNum ){
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://trace.bharatiyamobile.com/?numb=".$mobileNum);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		//file_put_contents('opTrMob.txt',$output);
		if(strpos($output , 'Could not connect') === false){
    		$findingText = "radio.gif' alt='FM Radio in Mobile Location'/> List of FM Radio Stations in ";		
    		$startPos = strpos($output , $findingText);
    		if (!$startPos) {
    		    curl_close($ch);     
    		    return 'None';    
    		}
    		$endPos = strpos($output , "</div>" , $startPos+strlen($findingText) );	
    		$stateLength = $endPos - ($startPos+strlen($findingText)); 
    		
    		// close curl resource to free up system resources
    		curl_close($ch);     
    		return substr($output , $startPos+strlen($findingText) , $stateLength);
		}else{
    		curl_close($ch);     
    		return 'None';
		}
	}
	
	function getIpLoc( $ip ){
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://traceip.bharatiyamobile.com/trace-ip-address.php?ip=".$ip);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		//Get Country
        $findingTextA = "Country:</td><td>";
		$startPos = strpos($output , $findingTextA);
		if (!$startPos) {
    		    curl_close($ch);     
    		    return 'None';    
    		}
		$endPos = strpos($output , "</td>" , $startPos+strlen($findingTextA) );
		$stateLength = $endPos - ($startPos+strlen($findingTextA));
		$address = substr($output , $startPos+strlen($findingTextA) , $stateLength);
		//Get City
		$findingTextB = "City:</td><td>";
		$startPos = strpos($output , $findingTextB);
		if (!$startPos) {
    		    curl_close($ch);     
    		    return 'None';    
    		}
		$endPos = strpos($output , "</td>" , $startPos+strlen($findingTextB) );
		$stateLength = $endPos - ($startPos+strlen($findingTextB));
		$cityName=substr($output , $startPos+strlen($findingTextB) , $stateLength);
		if(strlen($cityName)){
		    $address = $cityName . ',' . $address;    
		}
		
		// close curl resource to free up system resources
		curl_close($ch);
		return $address;
	}
	
	?>