<?php
require_once 'configuration.php';
class userqueryDatabase {
       public function __construct() {
         $connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
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
    
    public function getRecordByEmployee($category,$branch,$date = null){
        if($date == '1'){
            $date = 'and date ="'.date('Y-m-d').'"';
        } else {
            $date = '';
        }
        //echo "select * from user_query where category= '$category' and branch_id = '$branch' and status='start' $date order by id desc";
        $sql = mysql_query("select * from user_query where category= '$category' and branch_id = '$branch' and status='start' $date order by id desc");
      $data = array();
        while ($row = mysql_fetch_assoc($sql)){ 
            $data[] = $row;
        }
        return $data;
    }
    
    public function followuptoday($category,$branch){
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
     
    
   
}

?>
