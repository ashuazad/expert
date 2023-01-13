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
error_log("\n".date('Y-m-d H:i:s').':USS|'.file_get_contents("php://input"),3,'upSal.log');
$postData = json_decode(file_get_contents("php://input"),true);
/*print_r($postData);
exit;*/
foreach ($postData as $key=>$value) {
    $checkRow = $dbObj->getData(array('id'),'emp_payment_history', "DATE_FORMAT(payment_month,'%Y-%m') = DATE_FORMAT('" . $value['effectiveDate'] . "','%Y-%m') AND emp_id = '" . $value['empId'] . "'");
    if ($value['history_id'] == 0) {
        $insertData = array(
            'insentive'=>$value['incentive'],
            'target'=>$value['target'],
            'salary'=>$value['salary'],
            'total_salary'=>$value['totalSalary'],
            'emp_id'=>$value['empId'],
            'branch_id' => $value['branch_id'],
            'payment_month'=>$value['effectiveDate'],
            'status' => 'Approve',
            'action_date'=>date('Y-m-d H:i:s'));
        //print_r($insertData);
        if ($checkRow['0']==0) {
            $dbObj->dataInsert($insertData,"emp_payment_history");
        }
    } else {
        $updateData = array(
            'insentive'=>$value['incentive'],
            'target'=>$value['target'],
            'salary'=>$value['salary'],
            'total_salary'=>$value['totalSalary'],
            'status' => 'Approve',
            'action_date'=>date('Y-m-d H:i:s')
        );
        //print_r($updateData);
        $dbObj->dataupdate($updateData, "emp_payment_history", 'id', $value['history_id']);
    }
}
?>
