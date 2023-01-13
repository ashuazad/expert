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
error_log("\n".date('Y-m-d H:i:s').':US|'.file_get_contents("php://input"),3,'upSal.log');
$postData = json_decode(file_get_contents("php://input"),true);
//print_r($postData);

$incentive = $postData['incentive'];
$target = $postData['target'];
$salary = $postData['salary'];
$totalSalary = $postData['totalSalary'];
$history_id = $postData['history_id'];
$empId = $postData['empId'];
$branch_id = $postData['branch_id'];
$effectiveDate = $postData['effectiveDate'];
$checkRow = $dbObj->getData(array('id'),'emp_payment_history', "DATE_FORMAT(payment_month,'%Y-%m') = DATE_FORMAT('" . $effectiveDate . "','%Y-%m') AND emp_id = '" . $empId . "'");
if ($history_id == 0) {
    $insertData = array(
                        'insentive'=>$incentive,
                        'target'=>$target,
                        'salary'=>$salary,
                        'total_salary'=>$totalSalary,
                        'emp_id'=>$empId,
                        'branch_id' => $branch_id,
                        'payment_month'=>$effectiveDate);
    //print_r($insertData);
    if ($checkRow['0']==0) {
        $dbObj->dataInsert($insertData,"emp_payment_history");
    }
} else {
    $updateData = array(
                        'insentive'=>$incentive,
                        'target'=>$target,
                        'salary'=>$salary,
                        'total_salary'=>$totalSalary
                        );
    //print_r($updateData);
    $dbObj->dataupdate($updateData, "emp_payment_history", 'id', $history_id);
}
$res = array("success"=>true);
echo json_encode($res);
?>
