<?php
require_once '../includes/userqueryDatabase.php';
require_once  '../includes/useraccountDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once  '../includes/db.php';

date_default_timezone_set('Asia/Kolkata');
session_start();
if (!$_SESSION['id']) {
	header('Location: ' . constant('BASE_URL'));
	exit;
}

$id = $_SESSION['id'];
if(!empty($_SESSION['uid'])){
	$user_id = $_SESSION['uid'];
}else{
	$user_id = $_SESSION['id'];
}
$dbObj = new db();
$userquery = new userqueryDatabase();
$useraccount = new useraccountDatabase();
$branchData = new managebranchDatabase();

$fetchrecord = $useraccount->getRecordById($user_id);

$_SESSION['user_details'] = $fetchrecord;

if($id == '1'){
	$fetchrecord = 'admin';
}
if($fetchrecord != 'admin'){
	if($fetchrecord['role'] == 'employee'){
		header('Location: ' . constant('BASE_URL').'/account');
		exit;
	} else if($fetchrecord['role'] == 'branch'){
		header('Location: ' . constant('BASE_URL').'/branch');
		exit;
	} else {
		header('Location: ' . constant('BASE_URL'));
		exit;
	}
}

$branch = $branchData->getBranch();

$today = date("Y-m-d");

$lastFollowsMins=5;
$lastMin = time()-($lastFollowsMins*60);
$lastDt = date("Y-m-d H:i:s",$lastMin);
$curDt = date("Y-m-d");
$data = $dbObj->getData(array('id', 'regno', 'remark', 'message', 'status', " DATE_FORMAT( followup  , '%D %M %y %r') flDt ", 'next_followup', 'user_id'),
		 "admission_followups",
		"followup = '$today' order by id desc" );
?>
Records : <?php echo $data[0];?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr>                                    
                                    <th width="15%">Name</th>
                            	    <th width="15%">Mobile</th> 
                                    <th width="25%">Remark</th>  
                                    <th width="7%">Status</th>
			                        <th width="18%">Date</th>
                                    <th width="15%">User Id</th>
                                </tr>
                            </thead>
  <tbody id="tdLeads">
<?php 
array_shift($data);	
 foreach($data as $curStatus){
      $admDetail= $dbObj->getData(array("name","phone","status"),"admission" , "regno = '".$curStatus['regno']."'");
      $empDetail = mysql_fetch_assoc( mysql_query("SELECT * FROM `login_accounts`  where id ='".$curStatus['user_id']."'"));
      
      $statusClr=array('completed'=>'#D0EA84' , 'pending'=>'#7CD6E9','declined'=>'#F3723A');
?>                                <tr>
                                                <td style="background-color:<?php echo $statusClr[$curStatus ['status']];?>" id="c_b">
  <a style="color:#000;" href="<?php echo constant('BASE_URL'); ?>/superadmin/feefollowups.php?id=<?php echo $curStatus ['regno']; ?>"> <?php echo $admDetail[1]['name'];?></a></td> 
                                                <td style="background-color:<?php echo $statusClr[$admDetail[1]['status']];?>"> <?php echo $admDetail[1]['phone'];?></td>
                                                <td style="background-color:<?php echo $statusClr[$admDetail[1]['status']];?>">  <?php echo $curStatus ['message'];?>  </td>
                                                <td style="background-color:<?php echo $statusClr[$admDetail[1]['status']];?>"><?php echo $admDetail[1]['status'];?></td>        
                                                <td style="background-color:<?php echo $statusClr[$admDetail[1]['status']];?>"><?php echo  $curStatus ['flDt'];?></td>
 <td style="background-color:<?php echo $statusClr[$admDetail[1]['status']];?>"><?php if($empDetail['id']!=1){?> <a href="redadacc.php?user_id=<?php echo $empDetail['id'];?>"><?php echo $empDetail['first_name']." ".$empDetail['last_name'];?></a><?php }else{ ?> 
								 <?php echo $empDetail['first_name']." ".$empDetail['last_name'];?>
								 <?php }?></td>
                                              
                                             	
                                            </tr>
                                         <?php } ?> 
                                
                            </tbody>
                        </table>
