<?php
require_once 'configuration.php';
class useraccountDatabase {
    var $userPermission = array();
    public function __construct() {
         $connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
        mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
    }
     
     function login($username,$password){
       
      $login = 'select username, password,role from login_accounts where username ="'.mysql_real_escape_string($username).'" and password="'.mysql_real_escape_string($password).'"';
       $result = mysql_query($login);
       $data_result = mysql_fetch_assoc($result);
       if($data_result['role'] !=''){
           return $data_result['role'];
       } else{
           return 0;
       }
       
   }
     function userid($username,$password){
       
     $login = 'select id from login_accounts where username ="'.mysql_real_escape_string($username).'" and password="'.mysql_real_escape_string($password).'"';
       $result = mysql_fetch_assoc(mysql_query($login));
       return $result['id'];
       
   }
     public function getRecordById($id , $parent = false ){
        $sql_record = "select * from login_accounts where id =$id";     
        $result_record = mysql_query($sql_record);
        $data = mysql_fetch_assoc($result_record); 
        $brchDtlAry=mysql_fetch_array(mysql_query("select branch_name from login_accounts where id = ".$data['branch_id']));       
        $data['branch_name']=$brchDtlAry['branch_name'];     
        return $data;
    }

    public function getBranchEmp($branch_id){
$data = array();
$sql_record = "select id from login_accounts where branch_id ='".$branch_id."'";     
        $result_record = mysql_query($sql_record);
        while($data[] = mysql_fetch_assoc($result_record)['id']){
            
         } 
             
        return $data;
    }


   // Get Emp Permission
    public function getPermissions($id){
      $query = mysql_query("SELECT * FROM `emp_permissions` WHERE emp_id = ".$id);
      if(mysql_num_rows($query) > 0){
        while($row = mysql_fetch_assoc($query)){
            $this->userPermission[$row['permission']] = $row['value']; 
          }
       }        
    }
}
?>
