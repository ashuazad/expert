<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/course_module_software.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$coursemoduleObj = new course_module_software();
$data = $coursemoduleObj->getModules($_POST['course']);
if($data[0]>0){
array_shift($data);
foreach( $data as $row ){
?>
 <option value="<?php echo $row['module_id'];?>"><?php echo $row['module_name'];?></option>  
<?php 
 }
}else{?>
<option value="0">-Not Available-</option>
<?php } ?>