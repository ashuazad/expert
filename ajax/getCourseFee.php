<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/userPermissions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$permissions = new userPermissions($id);

$disList = $dbObj->getData(array('name','no_of_course','persent'),'discount');
array_shift($disList);
foreach( $disList as $eachDis ){
	$disListDtl[$eachDis['no_of_course']] = $eachDis['persent'];	
}

$courseSrch='';
$courseName = rtrim(trim($_POST['course']) , "," );
 $courseName = explode( "," , $courseName );
foreach($courseName as $coureTxt){
$courseSrch=$courseSrch."'".$coureTxt."',";
}
//echo $courseSrch;
$courseSrch=rtrim($courseSrch , ",");
//$courseSrch=rtrim($courseSrch , ",");
$fee=0;
//echo $courseSrch;
//echo "SELECT * FROM `course_fee` where course in(".$courseSrch.")";
$res=mysql_query("SELECT * FROM `course_fee` where course in(".$courseSrch.")" ) or die(mysql_error());
while($dataArry=mysql_fetch_row($res)){
$fee+=$dataArry[2];
}

$discountAmt = 0;
$disPercent = 0;
if(isset($disListDtl[count($courseName)])){
 $disPercent = $disListDtl[count($courseName)]." ";
 }else{
 $disPercent = $disListDtl[3]." ";
 }
 $discountAmt = ($fee*$disPercent)/100;
 
$feeDetails = array();
$feeDetails['totalMainFee'] = $fee;
if($permissions->permission['emp_set_discount'] || $_POST['source'] == '1'){

}else{
	$feeDetails['disCountAmt'] = $discountAmt;
	$feeDetails['totalFee'] = $fee - $discountAmt;
	$feeDetails['disCountPercent'] = $disPercent; 
}
echo json_encode($feeDetails);
?>