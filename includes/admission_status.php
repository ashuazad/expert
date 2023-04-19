<?php
class admission_status extends db {
	private $tableName = 'adm_status';
	public $allStatus = array();
	
	function __construct() {
	   $rows = $this->getData(array('*'), $this->tableName);
	   if( $rows[0]>0 ){
	   	array_shift($rows);
	   	foreach ($rows as $row){
	   			$this->allStatus[$row['status']] = str_replace("-", " ", $row['status']); 
	   		}
	   }
	}
	
	function addRemak($status) {
		$status = str_replace(" ", "-", $status);
		$result = $this->getData(array( 'status' ), $this->tableName ,"status = '".$status."'" );
		if($result[0] == 0 ){
			return  $this->dataInsert(array( 'status' => $status), $this->tableName );
		}else{
			return false;
		}
	}
	function editRemak($newstatus , $oldstatus) {
		
		$newstatus = str_replace(" ", "-", $newstatus);
		$oldstatus = str_replace(" ", "-", $oldstatus);
		return $this->dataupdate(array( 'status' => $newstatus), $this->tableName,'status', $oldstatus);			
	}

	function deletestatus( $status ){
		$status = str_replace("admission_statuss", "-", $status);
		return $this->delOne($this->tableName, 'status', $status);
	}
}