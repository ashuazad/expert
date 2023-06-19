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

/**
 *  Get Filter SQL for Leads 
 * 
*/
function getLeadsFilterSQl($flterInput) 
{
	$filterSql = '';
	 // Date Filter
	 if ((isset($flterInput['from_date']) && !empty($flterInput['from_date'])) && (isset($flterInput['to_date']) && !empty($flterInput['to_date']))) {
		$filterSql .= "(create_date  >= '".$flterInput['from_date']." 00:00:00' AND create_date <= '" . $flterInput['to_date'] ."  23:59:59') AND";   
	 }
	 $isOnlyBranch = true;
	 if ((isset($flterInput['emp']) && !empty($flterInput['emp'])) && (isset($flterInput['branch']) && !empty($flterInput['branch']))) {
		$isOnlyBranch = false;
	 }
	 //Branch Filter
	 if ((isset($flterInput['branch']) && !empty($flterInput['branch'])) && $isOnlyBranch) {
		$filterSql .= " (branch_id  = '".$flterInput['branch']."') AND";   
	 }
	 //Employee Filter
	 if ((isset($flterInput['emp']) && !empty($flterInput['emp'])) && !$isOnlyBranch) {
		$filterSql .= " (branch_id  = '".$flterInput['branch']."' AND emp_id  = '".$flterInput['emp']."') AND";   
	 }
	 //Phone Filter
	 if (isset($flterInput['phone']) && !empty($flterInput['phone'])) {
		$filterSql .= " (phone  = '".$flterInput['phone']."') AND";   
	 }
	 
	 //Status Filter
	 if (isset($flterInput['status']) && !empty($flterInput['status'])) {
		$filterSql .= " (status  = '".$flterInput['status']."') AND";   
	 }

	 if (strlen($filterSql)>0) {
		$filterSql = rtrim($filterSql, "AND");
	 }

	 return $filterSql;
}

/**
 *  Get Filter SQL for Leads 
 * 
*/
function getAdmissionFilterSQl($flterInput) 
{
	$filterSql = '';

	if ((isset($flterInput['from_date']) && !empty($flterInput['from_date'])) && (isset($flterInput['to_date']) && !empty($flterInput['to_date']))) {
		$filterSql .= "(doj  >= '".$flterInput['from_date']." 00:00:00' AND doj <= '" . $flterInput['to_date'] ."  23:59:59') AND";   
	 }

	 $isOnlyBranch = true;
	 if ((isset($flterInput['emp']) && !empty($flterInput['emp'])) && (isset($flterInput['branch']) && !empty($flterInput['branch']))) {
		$isOnlyBranch = false;
	 }
	 //Branch Filter
	 if ((isset($flterInput['branch']) && !empty($flterInput['branch']))  && $isOnlyBranch) {
		$filterSql .= " (branch_name  = '".$flterInput['branch']."') AND";   
	 }
	 //Employee Filter
	 if ((isset($flterInput['emp']) && !empty($flterInput['emp'])) && !$isOnlyBranch) {
		$filterSql .= " (branch_name  = '".$flterInput['branch']."' AND emp_id  = '".$flterInput['emp']."') AND";   
	 }
	 //Phone Filter
	 if (isset($flterInput['phone']) && !empty($flterInput['phone'])) {
		$filterSql .= " (phone  = '".$flterInput['phone']."') AND";   
	 }
	 
	 //Status Filter
	 if (isset($flterInput['status']) && !empty($flterInput['status'])) {
		$filterSql .= " (status  = '".$flterInput['status']."') AND";   
	 }

	 //Credit Amount Filter
	 if (isset($flterInput['credit_amt']) && !empty($flterInput['credit_amt'])) {
		$filterSql .= " (credit_amt  = '".$flterInput['credit_amt']."') AND";   
	 }

	 if (strlen($filterSql)>0) {
		$filterSql = rtrim($filterSql, "AND");
	 }

	 return $filterSql;
}

function getCountFromObject($objectName) {
	if ($objectName == '') {
		return false;
	}
	$sql = 'SELECT count(*) FROM ' . $objectName;
	$row =  mysql_fetch_row(mysql_query($sql));
	return $row[0];
}
?>
