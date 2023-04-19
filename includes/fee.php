<?php
class fee extends db{
   function getReceiptEmp($empId , $period = NULL , $startRw = NULL , $nofr = NULL) {
   	$data=array();   	
   	$res=mysql_query("select `f_id`, `reg_no`, admission.a_id, DATE_FORMAT( recipt_date , '%d-%c-%y | %r') recipt_date_frmt, `payment_mode`, `cheque_no`, `amt`, `dueamt`, `send_status`,`name` 
   	                  from fee_detail , admission where regno = reg_no and fee_detail.emp_id = $empId order by `recipt_date` desc") or die(mysql_error());
   	while($row=mysql_fetch_assoc($res)){
    		$data[]=$row;	
  	 	}
    	return $data;	
   }
function getReceiptAll( $period = NULL , $startRw = NULL , $nofr = NULL ) {
   	$data=array();   	
   	$res=mysql_query("select `f_id`, `reg_no`, admission.a_id, `recipt_date`, `payment_mode`, `cheque_no`, `amt`, 
   					`dueamt`, `send_status`,`name`,fee_detail.emp_id
   	 	             from fee_detail , admission where ( regno = reg_no) and date(`recipt_date`)= '".$period."'  
   	 	             order by  `recipt_date` desc limit $startRw , $nofr") or die(mysql_error());
   	while($row=mysql_fetch_assoc($res)){
   		    $empArry=$this->getData(array("first_name","last_name"), "login_account","id='".$row['emp_id']."'");
   		    $row['empName']=$empArry[1]["first_name"]." ".$empArry[1]["last_name"];  
    		$data[]=$row;    			
  	 	}
    	return $data;	
   }
}
?>