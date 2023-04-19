<?php
require_once 'configuration.php';
class categoryDatabase {
       public function __construct() {
         $connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
        mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
    }
    public function fetchAll($id = null){
        if($id != null){
            $where  = "where id= $id";
        } else {
            $where = "";
        }
        $data = array();
        $fetch_sql = "select * from category $where order by id asc";
        $fetch_result = mysql_query($fetch_sql);
        if(mysql_num_rows($fetch_result)> 0){
        while($row = mysql_fetch_assoc($fetch_result)){
            $data[] = $row;
        }
        }
        return $data;
    }
    
    public function insert($data){
         $insert_sql = "insert into category (id,category_name) values (null,'$data')";
        $result_sql = mysql_query($insert_sql);
        if($result_sql){
            return '1';
        } else {
            return '0';
        }
        
    }
    
    public function update($value, $id){
        $update_sql = "update category set category_name = '$value' where id =$id";
        $result_update = mysql_query($update_sql);
        if($result_update){
            echo  '1';
        } else {
            echo '0';
        }
    }
    
    public function delete($id){
        $delete_sql = "delete from category where id = $id";
        $result_sql = mysql_query($delete_sql);
        if($result_sql){
            echo "1";
        }else {
            echo '0';
        }
    }
 
}
?>