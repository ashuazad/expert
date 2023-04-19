<?php
class  userPermissions{ 
     var $userPermission = array();
// Get Emp Permission
    public function __construct($id){
   
      $query = mysql_query("SELECT * FROM `emp_permissions` WHERE emp_id = ".$id);
      if(mysql_num_rows($query) > 0){
        while($row = mysql_fetch_assoc($query)){
            $this->userPermission[$row['permission']] = $row['value']; 
          }
       }        
    }
    
    public function setPermission( $data = array() , $emp_id ) 
	{
		$result = false;
    	if(count($data)){
    		foreach( $data as $eachPermission => $eachPermissionValue ){
				if	(isset($eachPermissionValue)) {
					$resultRow = mysql_query("SELECT *  FROM `emp_permissions` WHERE `emp_id` = ".$emp_id." AND permission = '".$eachPermission ."'" );
					if (mysql_num_rows($resultRow)>0) {
						$sqlPermUpdate = "UPDATE `emp_permissions` SET `value` = '".$eachPermissionValue."' WHERE `emp_permissions`.`permission`='".$eachPermission."' AND `emp_permissions`.`emp_id`='".$emp_id ."'";
						$result = mysql_query($sqlPermUpdate) ;			
					} else {
						$sqlPermInsert = "INSERT INTO `emp_permissions` (`id`, `emp_id`, `permission`, `value`) VALUES (NULL, '".$emp_id ."', '".$eachPermission."', '".$eachPermissionValue."')";
						$result = mysql_query($sqlPermInsert ) ;	
					} 
				}
    		}
    	}
		return (bool)$result;
    }
    
}