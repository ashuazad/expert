<?php
class admission_remarks extends db {
	private $tableName = 'due_fee_remark';
	public $allRemarks = array();
	
	function __construct() {
	   $rows = $this->getData(array('*'), $this->tableName);
	   if( $rows[0]>0 ){
	   	array_shift($rows);
	   	foreach ($rows as $row){
	   			$this->allRemarks[$row['remark']] = str_replace("-", " ", $row['remark']); 
	   		}
	   }
	}
	
	function addRemak($remark) {
		$remark = str_replace(" ", "-", $remark);
		$result = $this->getData(array( 'remark' ), $this->tableName ,"admission_remarks = '".$remark."'" );
		if($result[0] == 0 ){
			return  $this->dataInsert(array( 'remark' => $remark), $this->tableName );
		}else{
			return false;
		}
	}
	function editRemak($newRemark , $oldRemark) {
		
		$newRemark = str_replace(" ", "-", $newRemark);
		$oldRemark = str_replace(" ", "-", $oldRemark);
		return $this->dataupdate(array( 'remark' => $newRemark), $this->tableName,'remark', $oldRemark);			
	}

	function deleteRemark( $remark ){
		$remark = str_replace("admission_remarks", "-", $remark);
		return $this->delOne($this->tableName, 'remark', $remark);
	}
}