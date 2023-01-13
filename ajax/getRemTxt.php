<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();

if($_GET['opt']=='drp')
{
        $remarkArry =$dbObj->getData( array("*") , "remark" );
        array_shift($remarkArry);
?>
<select name="followRemk" id="followRemk" class="messagedetail-drp" placeholder="Remark" style="width: 250px; ">
<option value=''>Choose a Remark</option>
 <?php foreach($remarkArry as $remk) {?>
       <option value='<?php echo $remk['remark'];?>'><?php echo $remk['remark'];?></option>  
            <?php } ?>
</select>
         <?php

}else{
?>
<input type="text" name="followRemk" id="followRemk" class="messagedetail-txt" >
<?php
}
?>
