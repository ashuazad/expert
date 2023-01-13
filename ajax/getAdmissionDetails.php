<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$brnObj = new managebranchDatabase();
$dbObj=new db();
$regno = $_POST['regno'];
$admissionArry = $dbObj->getData(array("*","DATE_FORMAT(doj ,'%d-%m-%Y') frmDoj" , "DATE_FORMAT(next_due_date ,'%d-%m-%Y') nextduedate" ) , 'admission' , " regno = '".$regno."'" );
array_shift($admissionArry);
$admissionArry=$admissionArry[0];

// Branch Name
//$branchDtl=$dbObj->getData(array("first_name" , "last_name" ) , 'login_accounts' , " id = '".$admissionArry['branch_name']."'");
//array_shift($branchDtl);

$branchId = $admissionArry['branch_name'];

$optTagBrch="<option value='0'>Branch</option>";

  $brnchArry= $dbObj->getData(array('id','city','branch_name') , "login_accounts" , "branch_id IS NULL");
  array_shift($brnchArry);
  foreach($brnchArry as $brnchName){
    if($brnchName['id']==$admissionArry['branch_name']){
       $optTagBrch.="<option value='".$brnchName['id']."' selected='selected' >".$brnchName['branch_name']."</option>";    
       }else{
       $optTagBrch.="<option value='".$brnchName['id']."' >".$brnchName['branch_name']."</option>";
           }
   }
$admissionArry['branch_name'] = $optTagBrch;

// Admiss Emp Name

$AdmsEmpDtl=$dbObj->getData(array("first_name" , "last_name" ) , 'login_accounts' , " id = '".$admissionArry['emp_id']."'");
array_shift($AdmsEmpDtl);

$kpAry=$brnObj -> fetchAll($branchId); 

$optTagBrnchEmp = '<option>Filter By Employee</option>';
//print_r($kpAry);
if(count($kpAry)>0){
 foreach($kpAry as $data){
   if($admissionArry['emp_id']==$data['id']){
    $optTagBrnchEmp.="<option value=".$data['id']." selected='selected' >".$data['first_name']."</option>";   
    }else{
    $optTagBrnchEmp.="<option value=".$data['id'].">".$data['first_name']."</option>";
   }
 }    
}

$admissionArry['emp_id'] = $optTagBrnchEmp ;

//$admissionArry['emp_id'] = $AdmsEmpDtl[0]["first_name"] ." ". $AdmsEmpDtl[0]["last_name" ];

// Lead Emp Name
$leadEmpDtl=$dbObj->getData(array("first_name" , "last_name" ) , 'login_accounts' , " id = '".$admissionArry['lead_userId']."'");
array_shift($leadEmpDtl);



$optTagBrncLead = '<option>Filter By Employee</option>';

if(count($kpAry)>0){
 foreach($kpAry as $data){
   if($admissionArry['lead_userId']==$data['id']){
    $optTagBrnchLead.="<option value=".$data['id']." selected='selected' >".$data['first_name']."</option>";   
    }else{
    $optTagBrnchLead.="<option value=".$data['id'].">".$data['first_name']."</option>";
   }
 }   
}
$admissionArry['lead_userId'] = $optTagBrnchLead ;
//$admissionArry['lead_userId'] = $leadEmpDtl[0]["first_name"] ." ". $leadEmpDtl[0]["last_name" ];

// Course Name
$courseArry= $dbObj->getData(array('*') , "course_fee");
 array_shift($courseArry);
$optionTag="<option value='0'>-Select-</option>";
 foreach($courseArry as $courseDtl){
     $avaliableCourse = explode("+",$admissionArry['course']);
      if(in_array( $courseDtl['course'] , $avaliableCourse )){
        $optionTag.="<option value='".$courseDtl['course']."' selected='selected' >".str_replace("-"," ",$courseDtl['course'])."    </option>";              
       }else{
         $optionTag.="<option value='".$courseDtl['course']."' >".str_replace("-"," ",$courseDtl['course'])."</option>";              
      }
  }
//$admissionArry['course'] = str_replace("-" , " ", str_replace("+",",",$admissionArry['course']) );
$admissionArry['course'] = $optionTag;
$admissionArry['doj'] = strtotime($admissionArry['doj']);
//print_r($admissionArry);
echo json_encode($admissionArry);
?>
