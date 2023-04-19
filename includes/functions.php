<?php
define('DATE_TIME_FORMAT', "'%d-%m-%y | %r'");
define('DATE_FORMAT', "'%d-%m-%y'");
function getMobileState( $mobileNum ){
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "http://trace.bharatiyamobile.com/?numb=".$mobileNum);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);		
		$startPos = strpos($output , "radio.gif' /> List of FM Radio Stations in ");
		$endPos = strpos($output , "</div>" , $startPos+strlen("radio.gif' /> List of FM Radio Stations in ") );	
		$stateLength = $endPos - ($startPos+strlen("radio.gif' /> List of FM Radio Stations in ")); 
		
		// close curl resource to free up system resources
		curl_close($ch);     
		return substr($output , $startPos+strlen("radio.gif' /> List of FM Radio Stations in ") , $stateLength);
	}

function hidePhone( $phoneNo ){
   return $phoneNo[0].$phoneNo[1].'******'.$phoneNo[strlen($phoneNo)-2].$phoneNo[strlen($phoneNo)-1];
}

function date_sort($a, $b) {
	return strtotime($a) - strtotime($b);
}
function getMonthRange($startDate,$endDate) {
	$monthRange = array();
	$start    = new DateTime($startDate);
	$start->modify('first day of this month');
	$end      = new DateTime($endDate);
	$end->modify('first day of next month');
	$interval = DateInterval::createFromDateString('1 month');
	$period   = new DatePeriod($start, $interval, $end);

	foreach ($period as $dt) {
		$monthRange[] = $dt->format("Y-m");
	}
	return $monthRange;
}

function getMobileStateV2( $mobileNum ){
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
