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

$today = date("Y-m-d H:i:s");

$_POST['date_added'] = $today;
$_POST['status'] = 0;

$dbObj->dataInsert($_POST,'user_request');
$user_request_List = $dbObj->getData(array("*"), "user_request","user_id = '".$_POST['user_id']."' AND date_added like '%".$today."%'",true);
array_shift($user_request_List);

print_r($user_request_List)              ;
 foreach ($user_request_List as $requestItem) {?>
                               <div class="item user_request_list">                                    
                                    <div class="info clearfix" style="padding-left: 0px;">
<?php $requestUser = $dbObj->getData(array('first_name','last_name'), 'login_accounts' , "id = '".$requestItem['user_id']."'");?>
 <b><?php echo $requestUser[1]['first_name'].' '.$requestUser[1]['last_name'];?> :</b> <?php echo $requestItem['request_txt'];?>
<div style="float: right;">
<?php if($requestItem['status']==1){?>
	<span class="label label-success">Success</span>
<?php }else{?>
	<span class="label label-warning">Pending</span>
<?php } ?>
</div>
                                    </div>
                                </div>
                              <?php }?>  
		
