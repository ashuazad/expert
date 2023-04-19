<?php
require_once 'configuration.php';
class userqueryDatabase {
       public function __construct() {
         $connection = @mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
        mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
    }
    
    public function getRecord($limit = null,$order = NULL,$branchid = null){
        if($branchid != null){
            $where = "and branch_id = $branchid";
        } else {
            $where = "";
        }
        $fetch = "select * from user_query where pid = 0 $where order by id $order limit $limit";
        $result = mysql_query($fetch);
        $data = array();
        while($row = mysql_fetch_assoc($result)){ 
            $data[] = $row;
            
        }
        
        return $data;
    }
     public function getRecordByBranch($branch){
        
        $fetch = "select * from user_query where status ='start' and branch_id = '$branch' order by id";
        $result = mysql_query($fetch);
        $data = array();
        while($row = mysql_fetch_assoc($result)){ 
            $data[] = $row;
            
        }
        
        return $data;
    }
    
    public function getRecordById($id){
        $sql_record = "select * from user_query where id =$id";
        $result_record = mysql_query($sql_record);
        $data = mysql_fetch_assoc($result_record);
        return $data;
    }
    
    public function checkParentId($id){
        $checkPId = "select * from user_query where pid = $id";
        $result_checkPId = mysql_query($checkPId);
        if(mysql_num_rows($result_checkPId) > 0){
            return 1;
        } else {
            return 0;
        }
    }
    public function getChildData($id){
        $datas= array();
       $checkPId = "select * from user_query where pid = $id";
        $result_checkPId = mysql_query($checkPId);
        $data = mysql_fetch_assoc($result_checkPId);
        if(mysql_num_rows($result_checkPId)){
            
            $datas[] = $data;
 }
        
        return $datas;
    }
    
    public function getRecordByEmployee($eId,$date = null){
        if($date == '1'){
            $date = "and DATE(assingment_data) ='".date('Y-m-d')."'";
        } else if($date == '2'){
              $date = "and DATE(assingment_data) <='".date('Y-m-d')."'";
               }else {
                  $date = '';
                }

  $sql = mysql_query("SELECT `id` ,  `name` ,  `email` ,  `category` ,  DATE_FORMAT( create_date , '%d-%m-%y | %r') create_date_fr ,  `phone` ,  `address` ,  `ip` ,  `status` ,  `branch_id` ,  `emp_id` ,  `r_status` ,`hits`,`frwId`,`message` FROM `leads` where emp_id=$eId $date and status != 'Dead'");
      $data = array();
$lid="";
        while ($row = mysql_fetch_assoc($sql)){ 
//print_r($row);
               $lid.=$row['id'].",";
            $sqlCheck = mysql_query("SELECT * FROM `user_query` where lead_id = ".$row['id']." and emp_id=$eId");
            if(mysql_num_rows($sqlCheck) == 0 || ($row['hits'] >= 1) || ($row['frwId'] > 0 && $row['message'] == '') ){
                   $data[] = $row;
             }                 
        }
//echo $lid;
        return $data;
    }

   public function followuptoday($empId){
        $datas = array();
        $sql = "select uq.lead_id,uq.id,l.name,l.category,l.phone,l.email,uq.message,uq.status,uq.followup_date from user_query uq ,leads l where uq.status != 'completed' and date(next_followup_date)='".date('Y-m-d')."' and uq.emp_id='$empId' and l.id=lead_id";
        $result = mysql_query($sql);
        while($row= mysql_fetch_row($result)){          
            $datas[] = $row;      
       }       
       return $datas;        
    }
   public function todayFollowUps($empId){
        $datas = array();
        $sql = "select uq.lead_id,uq.id,l.name,l.category,l.phone,l.email,uq.message,uq.status, DATE_FORMAT( uq.followup_date, '%d-%m-%y | %r') , DATE_FORMAT( uq.next_followup_date , '%d-%m-%y') from user_query uq ,leads l where uq.status != 'completed' and date(followup_date)='".date('Y-m-d')."' and uq.emp_id='$empId' and l.id=lead_id";
        $result = mysql_query($sql);
        while($row= mysql_fetch_row($result)){          
            $datas[] = $row;      
       }       
       return $datas;        
    }

  public function allStatusLead( $empId , $limtSt = null ,$limtNro = null, $where = null) {
        $datas = array();
          $sql = "select * from leads where status != 'Start' and emp_id=$empId";
          if ($where != null) {
              $sql .= ' AND ' . $where;
          }
          if ($limtSt !== null) {
              $sql .= " limit $limtSt ,$limtNro";
          }
         //echo  $sql;
        $result = mysql_query($sql) ;
        while($row= mysql_fetch_row($result)){
         $isDead = $row[15];    
         $resReso= mysql_query("select message,DATE_FORMAT( followup_date, '%d-%m-%y | %r') ,DATE_FORMAT( next_followup_date, '%d-%m-%y ') from user_query where id = ( select max(id) from user_query where lead_id=$row[0])");
$ledDtl=mysql_fetch_row(mysql_query("select id,name,email,category,phone,status from leads where id=".$row[0] ));
$row=$ledDtl;
        $resAry=mysql_fetch_row($resReso);
           if( $isDead == 1 ){
                $row[9] = 'None';
                $row[6] = 'None';
                $row[8] = 'None';
           }else{
                $row[9] = ($resAry[2] == '') ? 'None' : $resAry[2];    
                $row[6] = ($resAry[0] == '') ? 'None' : $resAry[0];
                $row[8] = ($resAry[1] == '') ? 'None' : $resAry[1];
           }
            $datas[] = $row;
       }
       return $datas;
    }

    public function allStatusLeadSearch( $empId , $limtSt = null ,$limtNro = null, $where = null, $count = null) {
        $datas = array();
        $sql = "SELECT 
                    id, 
                    name, 
                    email,
                    phone, 
                    IF(status = 'Start','None',status) AS status,
                    IF((last_follow_up IS NULL OR (last_follow_up = '0000-00-00 00-00-00')), 'None', DATE_FORMAT( last_follow_up, '%d-%m-%y | %r')) AS calling_date,
                    IF((next_followup_date IS NULL OR (next_followup_date = '0000-00-00')), 'None', DATE_FORMAT( next_followup_date, '%d-%m-%y ')) AS next_calling_date,
                    IF(message IS NULL, 'None', message) AS message 
                FROM 
                    leads 
                WHERE 
                    emp_id = $empId";
        if ($where != null) {
            $sql .= ' AND ' . $where;
        }
        if (!is_null($count)) {
           $countDtl = mysql_fetch_array(mysql_query("SELECT count(*) AS count FROM leads WHERE emp_id = $empId AND " . $where));
            $datas['count'] = $countDtl['count'];
        }
        if ($limtSt !== null) {
            $sql .= " limit $limtSt ,$limtNro";
        }

        $result = mysql_query($sql) ;
        while($row= mysql_fetch_array($result)){
            $datas['rows'][] = $row;
        }
        return $datas;
    }

    public function countAllStatusLead($empId){
      $sql = "select count(*) num_rows from leads where status != 'Start' and emp_id=$empId";
      return mysql_fetch_assoc(mysql_query($sql))['num_rows'];
   } 
    
   public function followuptoday_old($category,$branch){
        $datas = array();
        $sql = "select * from user_query where status='start' and category='$category' and branch_id='$branch'";
        $result = mysql_query($sql);
        while($row= mysql_fetch_assoc($result)){
           $select_max =mysql_query("select * from user_query where id=(select max(id) from user_query where firstname='{$row['firstname']}' and lastname='{$row['lastname']}' and phone='{$row['phone']}' and email_id='{$row['email_id']}')");
        $data = mysql_fetch_assoc($select_max);
      // echo $data['followup_date'];echo "<br>";
        $curr_date = date('Y-m-d');
        if(strtotime($data['followup_date']) == strtotime($curr_date)){
            $datas[] = $row;
        }
       }
       
       return $datas;
        
    }
     
    function leadStat( $empId=NULL){
                 $ldStArray=array();
                 if(empty($empId)){
                 $queryStat=mysql_query("SELECT status,count(*) FROM `leads` group by status ");
                 }else{
                 $queryStat=mysql_query("SELECT status,count(*) FROM `leads` where emp_id='".$empId."' group by status ");
                 }
                 while($datStat=mysql_fetch_row($queryStat)){
                  $ldStArray[]=$datStat;
                 }
             return $ldStArray;
         }
   
function daysbtwn ($start , $end )
{
	$days=array();
	$datediff = strtotime($end) - strtotime($start);
	$datediff = floor($datediff/(60*60*24));
	for($i = 0; $i < $datediff + 1; $i++){
	    $days[] = date("Y-m-d", strtotime($start . ' + ' . $i . 'day')) . "\n";
	}
       $days=array_reverse($days);
	return $days;
}

function statusHistory( $case = 1  , $empId ){
    $historyArry = array();     
    $today=date('Y-m-d');
     	switch ( $case ){
     		case 1 :
     			$monday = date('Y-m-d' , strtotime('last monday', strtotime('tomorrow')));
                        $listofdays = $this->daysbtwn($monday , $today);
                        foreach( $listofdays as $dy ){
   $res=mysql_query("SELECT status , count(*) from ( SELECT * FROM `user_query` where date( followup_date ) = '".$dy."' and emp_id=$empId ) tableStat group by status ") ;                              
                              $stArry=array();
                              $cuntArry=array();
                              while(  $dt = mysql_fetch_row($res) ){
                                    $stArry[] =  $dt[0];                
                                    $cuntArry[] =  $dt[1];
                                   }
                          $resArry =  array_combine( $stArry , $cuntArry ) ;
                          ksort($resArry);                                                        
                           $assingLead=mysql_query("SELECT * FROM `leads` where emp_id = $empId and date(assingment_data) = '$dy'"); 
                           $resArry['Total']=mysql_num_rows($assingLead);                                 
                            $historyArry[$dy]  =   $resArry;     
                          }   
     		break;
     		case 2 :
     		    $startdate=$today;
     			$startdate[8]=0;
     			$startdate[9]=1; 	    
                        $listofdays = $this->daysbtwn($startdate , $today);
                        foreach( $listofdays as $dy ){
   $res=mysql_query("SELECT status , count(*) from ( SELECT * FROM `user_query` where date( followup_date ) = '".$dy."' and emp_id=$empId ) tableStat group by status ") ;                              
                              $stArry=array();
                              $cuntArry=array();
                              while(  $dt=mysql_fetch_row($res) ){
                                    $stArry[] =  $dt[0];                
                                    $cuntArry[] =  $dt[1];
                                   }
                          $resArry =  array_combine( $stArry , $cuntArry ) ;
                          ksort($resArry);
                           $assingLead=mysql_query("SELECT * FROM `leads` where emp_id = $empId and date(assingment_data) = '$dy'"); 
                           $resArry['Total']=mysql_num_rows($assingLead);                                                          
                           $historyArry[$dy]  =   $resArry;     
                          }   
     		break;
     		case 3 :
     		    $startdate='2014-01-01';
                        $listofdays = $this->daysbtwn($startdate , $today);
                        foreach( $listofdays as $dy ){
   $res=mysql_query("SELECT status , count(*) from ( SELECT * FROM `user_query` where date( followup_date ) = '".$dy."' and emp_id=$empId ) tableStat group by status ") ;                              
                              $stArry=array();
                              $cuntArry=array();
                              while(  $dt=mysql_fetch_row($res) ){
                                    $stArry[] =  $dt[0];                
                                    $cuntArry[] =  $dt[1];
                                   }
                          $resArry =  array_combine( $stArry , $cuntArry ) ;
                          ksort($resArry);
                           $assingLead=mysql_query("SELECT * FROM `leads` where emp_id = $empId and date(assingment_data) = '$dy'"); 
                           $resArry['Total']=mysql_num_rows($assingLead);                                 
                           $historyArry[$dy]  =   $resArry;     
                          }   
     		break;
     		
     	}            
     return $historyArry;
  } 

  function noOfDead( $leadId ){
      $query = mysql_query("SELECT count(status) noStatus FROM `user_query` WHERE `lead_id` = ".$leadId." and status = 'Dead'");
      $row = mysql_fetch_assoc($query);
      return $row['noStatus']; 
    }
    
  function followupHistory( $lead_id , $userId){
     $history = array(); 
     // Get last frwd details
     $sql = "SELECT * FROM leadfrwdhistory WHERE lead_id = " . $lead_id . " ORDER BY frwDate DESC";
     $resultCheckFrw = mysql_query($sql);
     if(mysql_num_rows($resultCheckFrw) > 0){
        $dataCheckFrw = mysql_fetch_assoc($resultCheckFrw);
        $sql = "SELECT * FROM user_query WHERE lead_id = " . $lead_id . " AND emp_id = " . $userId . " AND followup_date > '" . $dataCheckFrw['frwDate'] . "' ORDER BY followup_date ASC";
        $queryResult = mysql_query($sql);
        while($eachRow = mysql_fetch_assoc($queryResult)){
            $history[] = $eachRow;
        }
     }
     return $history;
  }    

}



















?>
