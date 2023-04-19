<?php
require_once 'configuration.php';
class managebranchDatabase {
       public function __construct() {
         $connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
        mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
    }
    
    public function fetchAll($id = ''){
        $data = array();
        if($id != ''){
            $where = 'where branch_id ='.$id;
        } else {
            $where = 'where role = "branch"';
        }
   $fetch = "select * from login_accounts $where order by id desc";
    $result_fetch = mysql_query($fetch);
    while($row = mysql_fetch_assoc($result_fetch)){
        $data[] = $row;
    }
    
    if($data != ''){
        
    return $data;
    }
}

public function fetchById($id){
$fetchRecord = "select * from login_accounts where id = $id";
$result= mysql_query($fetchRecord);
$data = mysql_fetch_assoc($result);
if($data != '')
return $data;
}

public function getBranch(){
    $sql_branch = "select * from login_accounts where role='branch'";
    $result_branch = mysql_query($sql_branch);
    while($row = mysql_fetch_assoc($result_branch)){
        $data[] = $row;
    }
    return $data;
}
}
?>
