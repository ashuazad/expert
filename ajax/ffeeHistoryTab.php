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

$arrColumns = array(
	'fd.f_id', 
	'fd.reg_no', 
	'adm.name', 
	'fd.payment_mode', 
	'fd.cheque_no', 
	'fd.recipt_date', 
	'fd.amt', 
	'fd.send_status',
	'fd.emp_id AS fd_emp_id',
	'adm.emp_id AS adm_emp_id'
);

$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;

if (strlen($_POST['whereCnd'])) {
$where=$_POST['whereCnd'];
 $recptList=$dbObj->getData($arrColumns,"fee_detail fd, admission adm", $where." order by fd.recipt_date desc limit $startLmt,$nofr");  	
} else {
 $recptList=$dbObj->getData($arrColumns,"fee_detail fd, admission adm", " 1=1 order by fd.recipt_date desc limit $startLmt,$nofr");    
}
 ?>
 <table cellpadding="0" cellspacing="0" width="100%" class="table">
 <tr>
            <th>Receipt No</th>
            <th>Registration No </th>
            <th>Name </th>
            <th>Payment Mode</th>
            <th>Date</th>
            <th>Amount </th>
            <th>Billing ID</th>
            <th>Admission ID</th>
            <th>Insentive</th>
            <th>Status</th>
            <th><input type="checkbox" id="chAll" class="recpt" name="" value=""></th>
        </tr>
		<?php
		array_shift($recptList);
	//	print_r($recptList);

		foreach( $recptList as $key  => $val ){
			//$studArry = $dbObj->getData(array("name", "emp_id"), "admission","regno='".$val['reg_no']."'");
        ?>
		<tr>  
		    
		<td><?php echo  $val['f_id'];?></td> 
		 <td><?php echo $val['reg_no'];?></td> 
		 <td><?php echo $val['name'];?></td>  
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
		 <td><?php echo $val['recipt_date'];?></td>
         <td><?php echo "Rs. ".$val['amt'];?></td>
            <?php
			$billingUser = 'Admin';
			if (!empty($val['fd_emp_id'])) {
				$userDtlArry = $dbObj->getData(array('CONCAT(first_name," ",last_name) AS NAME'), "login_accounts" , "id=".$val['fd_emp_id']);
				$billingUser = $userDtlArry[1]['NAME'];
			}
          $admissionUser = "Admin";
        //  if(isset($studArry[1]['emp_id'])){
              $admUserDtlArry = $dbObj->getData(array('CONCAT(first_name," ",last_name) AS NAME', 'insentive'), "login_accounts" , "id=".$val['adm_emp_id']);
              $admissionUser = $admUserDtlArry[1]['NAME'];
          //}
         ?>
		 <td><?php echo $billingUser;?></td>
         <td><?php echo $admissionUser;?></td>
         <td><?php
                 $insetAmt = ($val['amt']*$admUserDtlArry[1]['insentive'])/100;
                 echo "Rs. ".$insetAmt;
                // $totalIncentive += $insetAmt;
             ?>
         </td>
<?php $statusTxt = array('<span class="label label-danger">Pending</span>',
							'<span class="label label-warning">Wating</span>',
							'<span class="label label-success">Approved</span>')?>		 
		 <td><?php echo $statusTxt[$val['send_status']]; ?></td>
		 <td><input type="checkbox" class="recpt" name="send[]" value="<?php echo  $val['f_id']."-".$val['reg_no']."-".$val['amt'];?>" ></td>
		 </tr>	
		<?php
}			
?>
		</table>