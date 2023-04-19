<?php
//$con = mysql_connect("localhost" , "root" , "root");
//mysql_select_db("expertic_crmdb");

function getHistory_old( $id , $emp_id = NULL, $regNo = null ){
    echo "id ".$id."<br>";
	static   $admissionFollowupHistory = array();
	echo "select *,DATE_FORMAT(followup,'%d-%b-%Y') 'followup_date' ,DATE_FORMAT(next_followup,'%d-%b-%Y') 'next_followup_date'  from admission_followups where p_id = ".$id. " AND regno = '" . $regNo . "'" ;
	$result = mysql_query("select *,DATE_FORMAT(followup,'%d-%b-%Y') 'followup_date' ,DATE_FORMAT(next_followup,'%d-%b-%Y') 'next_followup_date'  from admission_followups where p_id = ".$id. " AND regno = '" . $regNo . "'" );
	if(mysql_num_rows($result) > 0){
		$childData = mysql_fetch_assoc($result);
		echo $childData['id']."<br>";
		if(empty($emp_id)){
			$admissionFollowupHistory[] = $childData;
		}else{
			if($emp_id == $childData['user_id']){
				$admissionFollowupHistory[] = $childData;
			}
		} 
		getHistory( $childData['id'],$id, $regNo);
	}
	return $admissionFollowupHistory;
}

function getHistory( $regNo = null , $emp_id = NULL ){
	$admissionFollowupHistory = array();
	$sql = "SELECT * FROM `admission_followups` WHERE regno = '" . trim($regNo) . "'";
	if(!empty($emp_id)){
	    $sql .= " AND user_id = " . $emp_id;     
	}
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		while($data = mysql_fetch_assoc($result)){
	    	$admissionFollowupHistory[] = $data;        
		}
	}
	return $admissionFollowupHistory;
}








