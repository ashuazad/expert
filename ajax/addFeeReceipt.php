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

$postData = json_decode(file_get_contents("php://input"),true);

foreach($postData as $data) {
   $_POST[$data['name']] = $data['value'];
}
if(!empty($_POST)){
//$updStudFeeDtlArry=array('due_fee'=>  , 'next_due_date'=>$_POST['next_due_date'] );
//print_r($_POST);
    if($_POST['payment_mode']=='Cash'){
        unset($_POST['cheque_no']);
    }
    $next_due_date =  $_POST['next_due_date'];
    unset($_POST['roll_no']);
    unset($_POST['phone']);
    unset($_POST['makepayment']);
    unset($_POST['next_due_date']);
    $_POST['emp_id']=$id;
    $_POST['recipt_date']=date("Y-m-d H:i:s");

    $currentCredit = 0;
    $feeDetailSql = "SELECT SUM(amt) AS CREDITAMTFEE FROM `fee_detail` WHERE a_id =  '".$_POST['a_id']."' and reg_no='".$_POST['reg_no']."'";
    $feeDetailResult = mysql_query($feeDetailSql);
    if(mysql_num_rows($feeDetailResult)>0){
        $feeDetailRow = mysql_fetch_assoc($feeDetailResult);
        $currentCredit = $feeDetailRow['CREDITAMTFEE'];
    }

    $currentCredit += $_POST['amt'];
    if($currentCredit > 200){
        $amd_status = 'Admission';
    }else{
        $amd_status = 'Registration';
    }

    $amd_total_fee = $dbObj->getData(array('total_fee'), 'admission' , "regno='".$_POST['reg_no']."'");
    $dueFeeAdm = $amd_total_fee[1]['total_fee'] - $currentCredit;
    $_POST['dueamt'] = $dueFeeAdm;
    if( $dbObj->dataInsert( $_POST , "fee_detail" ) )
    {
        $lstf_id=mysql_insert_id();
        // Check Status

        mysql_query( "update admission set next_due_date =  '$next_due_date' , due_fee = '".$dueFeeAdm."',status='".$amd_status."', last_receipt_date='".$_POST['recipt_date']."'  where a_id =  '".$_POST['a_id']."' and regno='".$_POST['reg_no']."'" ) ;
        // Check Status
        //mysql_query( "update admission set next_due_date =  '$next_due_date' , due_fee = '".$_POST['dueamt']."' where a_id =  '".$_POST['a_id']."' and regno='".$_POST['reg_no']."'" ) ;
        echo 1;
    }
}

?>
