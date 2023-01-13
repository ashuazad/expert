<?php
require_once '../includes/userqueryDatabase.php';
require_once  '../includes/useraccountDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once  '../includes/db.php';

date_default_timezone_set('Asia/Kolkata');
session_start();
if (!$_SESSION['id']) {
	header('Location: ' . constant('BASE_URL'));
	exit;
}

$id = $_SESSION['id'];
if(!empty($_SESSION['uid'])){
	$user_id = $_SESSION['uid'];
}else{
	$user_id = $_SESSION['id'];
}
$dbObj = new db();
$userquery = new userqueryDatabase();
$useraccount = new useraccountDatabase();
$branchData = new managebranchDatabase();
$fetchrecord = $useraccount->getRecordById($user_id);

$_SESSION['user_details'] = $fetchrecord;


if($id == '1'){
	$fetchrecord = 'admin';
}
$adminRoleCheck = true;
if(isset($_SESSION['user_details']['all_due_fee_pem']) && !empty($_SESSION['user_details'])){
    if($_SESSION['user_details']['all_due_fee_pem'] == 1){
        $adminRoleCheck = false;        
    }    
}
if($adminRoleCheck){
    if($fetchrecord != 'admin'){
    	if($fetchrecord['role'] == 'employee'){
    		header('Location: ' . constant('BASE_URL').'/account');
    		exit;
    	} else if($fetchrecord['role'] == 'branch'){
    		header('Location: ' . constant('BASE_URL').'/branch');
    		exit;
    	} else {
    		header('Location: ' . constant('BASE_URL'));
    		exit;
    	}
    }
}
$branch = $branchData->getBranch();

$today = date("Y-m-d");
$nofr=$_POST['nfrPP'];
$where = $_POST['whereCnd'];
$pageN = $_POST['pageN'];
$startLmt=($pageN-1)*$nofr;
//echo $_POST['caseVal'];
switch ($_POST['caseVal']){
	case 'all-pending':
		$data = $dbObj->getData( array('a_id', 'roll_no', 'regno', 'course_fee',
				'total_fee', 'due_fee', "DATE_FORMAT(next_due_date,'%d-%b-%Y') due_date", 'doj',
				'name', 'email_id', 'phone', 'course','status','emp_id','followup_emp_id','next_due_date') ,
				"admission" , $where." due_fee > 0 and next_due_date <= '".$today."' order by next_due_date desc limit $startLmt , $nofr");
		break;
	case 'all-due':
		$data = $dbObj->getData( array('a_id', 'roll_no', 'regno', 'course_fee',
				'total_fee', 'due_fee', "DATE_FORMAT(next_due_date,'%d-%b-%Y') due_date", 'doj',
				'name', 'email_id', 'phone', 'course','status','emp_id','followup_emp_id') ,
				"admission" , $where." due_fee > 0  order by next_due_date desc limit $startLmt , $nofr" );
		break;	
}
?>
 	<table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;">
                            <thead>
                                <tr>
                                    <th width="2%"></th>                                    
                                     <th width="6%">Roll No</th>
                                    <th width="10%">Name</th>
                                    <th width="9%">Phone No.</th>
                                    <th width="8%">Total Fee</th>
                                    <th width="8%">Credit</th>
                                    <th width="8%">Due Fees</th>                                         
                                    <th width="10%">Last Payment</th>
                                    <th width="10%">Due Date</th>
                                    <th width="10%">Last Remark</th> 
                                    <th width="10%">Admin ID</th>
                                    <th width="10%">follow up ID</th>                                                                                                             
                                </tr>
                            </thead>
                           <?php  
                          
                           $i=0;
                           if($data[0]>0 ){  ?>
                                <tbody>                                    
                            <?php
                       array_shift($data);                      
                             foreach($data as $fdatas) {			                             	
									$followupDetails = $dbObj->getData(array("status" , 'followup' , 'next_followup','message' ), "admission_followups" , "regno = '".$fdatas['regno']."' order by id desc limit 0,1");
	 $feeDetails = $dbObj->getData(array("DATE_FORMAT(recipt_date,'%d-%b-%Y') reciptDate"), "fee_detail" , "reg_no = '".$fdatas['regno']."' order by f_id desc limit 0,1");
if($fdatas['followup_emp_id'] == 1){
	 	$followupUser['username'] = "Admin";
	 }else{
       
                 $followupUser = $useraccount->getRecordById($fdatas['followup_emp_id']);

          }
if($fdatas['emp_id'] == 1){
	 	$admissUser['username'] = "Admin";
	 }else{
$admissUser = $useraccount->getRecordById($fdatas['emp_id']);
               } 
if($followupDetails[0]==0){
  $followupDetails[1]["next_followup"] = $fdatas['due_date'];
}                   	?> 
                          <tr>
                          	<td class="sendChkBx"><input type="checkbox" name="sendDue[]" class="sendDueUser" value="<?php echo $fdatas['regno'];?>" ></td>
                            <td><?php echo $fdatas['roll_no']; ?></td>
                            <td><a href="<?php echo constant("BASE_URL"); ?>/account/feefollowups.php?id=<?php echo $fdatas['regno'];?>&tb=tabs-1"><?php echo $fdatas['name']; ?></a></td>
                            <td><?php echo $fdatas['phone']; ?></td>
                            <td><?php echo $fdatas['total_fee']; ?></td>                  
                            <td><?php echo $fdatas['total_fee'] - $fdatas['due_fee']; ?></td>
                            <td><?php echo $fdatas['due_fee']; ?></td>
                            <td><?php echo $feeDetails[1]["reciptDate"]; ?></td>
                            <td><?php echo $followupDetails[1]["next_followup"]; ?></td>
                            <td><?php echo $followupDetails[1]["message"]; ?></td>
                            <td><?php echo $admissUser['username']; ?></td>
                            <td><?php echo $followupUser['username']; ?></td>
                          </tr> 
                       <?php  			$i++;  
                             		
                             		
                             	}  ?>
                         </tbody>                             
                            <?php } else { 
                                    echo "No record(s) found";  
									}									
							 ?>
							
                        </table>
