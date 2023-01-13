<?php
require_once '../includes/managebranchDatabase.php';
$brnObj = new managebranchDatabase();
$bid = $_POST['id'];
$kpAry=$brnObj -> fetchAll($bid); 
?>

    <option>Filter By Employee</option>
<?php
if(count($kpAry)>0){
 foreach($kpAry as $data){
 ?>
    <option value="<?php echo $data['id'];?>"><?php echo $data['first_name'];?></option>
<?php  } 
}else{
?>
    <option value="">Not Avaliable </option>
<?php } ?>   
