<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
 session_start();
if(empty($_SESSION['id'])){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$today=date('ymd');

$studDtlArry = $studentObj->getStudentPayDetail( $_POST['case'] , $_POST['val']);
$history= $studentObj->getFeeHistory($studDtlArry['regno'],$studDtlArry['a_id']);
//print_r($history);
$histryTable='<table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;" >
                            <thead>
                                <tr>                                   
                                    <th width="10%">Receipt No </th>
                                    <th width="15%">Date | Time</th>
                                    <th width="10%">Received Amount </th>
                                    <th width="10%">Payment Mode</th>
                                    <th width="15%">Cheque  No. </th>     
                                    <th width="15%">User Id</th>     
                                    <th width="10%">Print</th>     
                                </tr>
                            </thead>
                     <tbody>                                    
';
foreach($history as $histryRow )
{
  $sql_record = "select * from login_accounts where id ='".$histryRow['emp_id']."'";
        $result_record = mysql_query($sql_record);
        $userDtl= mysql_fetch_assoc($result_record); 
//$userDtl=useraccountDatabase::getRecordById ($histryRow['emp_id']);
 $histryTable.='   <tr>
                            <td>'.$histryRow['f_id'].'</td> 
                            <td>'. date( "d-M-Y H:i a", strtotime($histryRow['recipt_date']) ) .'</td>
                            <td>'.$histryRow['amt'].'</td>                  
                            <td>'.$histryRow['payment_mode'].'</td> 
                            <td>'.$histryRow['cheque_no'].'</td>
                            <td>'.$userDtl['first_name']." ".$userDtl['last_name'].'</td>  
                            <td><i class="ibb-print" style="cursor:pointer;"  onclick="abc('.$histryRow['f_id'].')"  ></i> </td>                            
                          </tr>';
}
 $histryTable.=' </tbody>  </table>';
$studDtlArry['histryTable']=$histryTable;
if(($_SESSION['id'] != 1) && ($_SESSION['user_permission']['all_fee_pay_pem'] != 1)){
$studDtlArry['phone'] = $studDtlArry['phone'][0].$studDtlArry['phone'][1]."********".$studDtlArry['phone'][8].$studDtlArry['phone'][9];
}
echo json_encode($studDtlArry);
?>
