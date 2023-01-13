<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
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
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;

if(strlen($_POST['whereCnd'])){
$where=$_POST['whereCnd'];
$recptList=$dbObj->getData(array("*"),"fee_detail", $where." order by `recipt_date` desc limit $startLmt,$nofr");  	
}else{
 $recptList=$dbObj->getData(array("*"),"fee_detail", " 1=1 order by `recipt_date` desc limit $startLmt,$nofr");    
 }
 ?>
 <table cellpadding="0" cellspacing="0" width="100%" class="table">
		<tr>   <th>Receipt No</th>  <th>Registration No </th>  <th>Name </th> <th>Payment Mode</th> <th>Amount </th> <th>Date</th><th>User</th> <th>Status</th> <th>
<input type="checkbox" id="chAll" class="recpt" name="" value="">
</th>  </tr>
		<?php
$pendingAmt=0;
			$watingAmt=0;
			$aprvAmt=0;
		
		array_shift($recptList);		
		foreach( $recptList as $key  => $val ){
			$studArry = $dbObj->getData(array("name"), "admission","regno='".$val['reg_no']."'");		 
		?>
		<tr>  
		    
		<td><?php echo  $val['f_id'];?></td> 
		 <td><?php echo  $val['reg_no'];?></td> 
		 <td><?php echo $studArry[1]['name'];?></td>  
         <td><?php /* if(($val['payment_mode'] == 'Cash') || (strpos($val['payment_mode'],'PayU') >= 0)){ echo $val['payment_mode']; }else{ echo $val['cheque_no']; }*/
            switch($val['payment_mode']){
                case 'Cash':
                        echo $val['payment_mode'];
                    break;
                case 'Cheque':
                        echo $val['cheque_no'];
                    break;
                default:
                        echo $val['payment_mode'];
                    break;
            }
         ?></td>
		 <td><?php echo "Rs. ".$val['amt'];?></td> 
		 <td><?php echo $val['recipt_date'];?></td>
		 <?php $userDtlArry = $dbObj->getData(array('first_name','last_name'), "login_accounts" , "id=".$val['emp_id']);?>
		 <td><?php echo $userDtlArry[1]['first_name']." ".$userDtlArry[1]['last_name'];?></td>
<?php $statusTxt = array('<span class="label label-danger">Pending</span>',
							'<span class="label label-warning">Wating</span>',
							'<span class="label label-success">Approved</span>')?>		 
		 <td><?php echo $statusTxt[$val['send_status']]; ?></td>
		 <td><input type="checkbox" class="recpt" name="send[]" value="<?php echo  $val['f_id']."-".$val['reg_no']."-".$val['amt'];?>" ></td>
		 </tr>		
		<?php
}
$aprvAmt= 0;$watingAmt =0;$pendingAmt =0;
if($where != ''){
 $aprvAmt= mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where ".$where." and send_status =2"))['sumAmt'];
$watingAmt = mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where ".$where." and send_status =1"))['sumAmt'];
$pendingAmt = mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where ".$where." and send_status =0"))['sumAmt'];
}else{
 $aprvAmt= mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where  send_status =2"))['sumAmt'];
$watingAmt = mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where  send_status =1"))['sumAmt'];
$pendingAmt = mysql_fetch_assoc(mysql_query("select sum(amt) sumAmt from fee_detail where send_status =0"))['sumAmt'];

}
					
?>
		</table>