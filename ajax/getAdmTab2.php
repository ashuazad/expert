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
//print_r($_POST);
//echo $startLmt;
if(strlen($_POST['whereCnd'])){
    $where=$_POST['whereCnd'];
    $admsAry=$dbObj->getData(array("*" , "DATE_FORMAT( doj , '%d-%m-%y') admDate","DATE_FORMAT( next_due_date , '%d-%c-%y') dueDate"),"admission", $where." order by a_id desc limit $startLmt,$nofr");
}else{
    $admsAry=$dbObj->getData(array("*" , "DATE_FORMAT( doj , '%d-%m-%y') admDate","DATE_FORMAT( next_due_date , '%d-%c-%y') dueDate"),"admission", " status != '0' order by a_id desc limit $startLmt,$nofr");
 }
?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;"  >
                            <thead>
                                <tr>                                   
                                    <th width=""><input type='checkbox' name='check' id="chkAll"></th>
                                    <th width="4%">Roll No</th>
                                    <th width="12%">Name</th>
                                    <th width="9%">Phone No.</th>
                                    <th width="9%">Reg Date</th>
                                    <th width="8%">Next Due Date</th>
                                    <th width="10%">Location</th>
                                    <th width="7%">Total Fees</th>
                                    <th width="7%">Credit</th>
                                    <th width="7%">Due Fees</th>
                                    <?php if(strlen($_POST['whereCnd'])){ ?>
                                      <th width="10%">Remark</th>
                                    <?php }else{ ?>
                                      <th width="10%">Billing User ID</th>
                                    <?php } ?>
                                    <th width="10%">Admission ID</th>
                                    <th width="10%">Brand Name</th>
                                </tr>
                            </thead>
                     <tbody>                                    
<?php 
array_shift($admsAry);
//$admsAry = array_reverse($admsAry);
              foreach($admsAry  as $admData ){
      $lastRemark_id =  $dbObj->getData(array('max(id) lstId'),'admission_followups',"regno = '".$admData['regno']."'")[1]['lstId'];
      $lastRemark_Message =  $dbObj->getData(array('message'),'admission_followups',"id = '".$lastRemark_id."'")[1]['message'];
      $course = str_replace('+',', ',str_replace('-',' ',$admData['course']));
      ?>                             
                          <tr>
                   <td><input  type='checkbox'   name='check'  class="checkDel"  value="<?php echo $admData['regno'];?>"></td> 
                            <td class="fdp_roll_no-old">
<a href="javascript:void(0)" class="editAdmisLink" id="<?php echo $admData['regno'];?>" >
<?php echo $admData['roll_no'];?>
</a>
</td>
  <td><a href="feefollowups.php?id=<?php echo $admData['regno'];?>&d=d"  id="<?php echo $admData['regno'];?>" ><?php echo $admData['name'];?></a></td>
                            <td><?php echo $admData['phone'][0].$admData['phone'][1].'******'.$admData['phone'][strlen($admData['phone'])-2].$admData['phone'][strlen($admData['phone'])-1]; ?></td>
                            <td><?php echo $admData['admDate'];?></td>
                            <td><?php echo $admData['dueDate'];?></td>
                            <td>
                                <?php
                                if (empty($admData['phone_location']) || (strlen($admData['phone_location'])==0)) {
                                    $phone_location = ucfirst(strtolower(getMobileStateV2($admData['phone'])));
                                    if ($phone_location == 'None' ) {
                                        $phone_location = ucfirst(strtolower(str_replace(',',' ',getIpLoc($admData['ip']))));
                                    }
                                    if ($phone_location != 'None') {
                                        $dbObj->dataupdate(array('phone_location' => $phone_location), "admission", "a_id", $admData['a_id']);
                                    }
                                    echo $phone_location;
                                } else {
                                    echo ucfirst(strtolower(str_replace(',',' ',$admData['phone_location'])));
                                }
                                ?>
                            </td>
                            <td><?php echo $admData['total_fee'];?></td>
                            <?php $creditAmt=$admData['total_fee']-$admData['due_fee']; ?>                                
                            <td><?php echo $creditAmt;?></td> 
                            <td><?php echo $admData['due_fee'];?></td>
                            <?php if (strlen($_POST['whereCnd'])) { ?>
                              <td><?php echo $lastRemark_Message;?></td>
                            <?php }else{ ?>
       <td><?php $empNmArry=$userAccDb->getRecordById($admData['emp_id']);  echo $empNmArry['first_name']." ".$empNmArry['last_name'];?></td>
                            <?php } ?> 
                            <td><?php  $empLNmArry=$userAccDb->getRecordById($admData['lead_userId']);  echo $empLNmArry['first_name']." ".$empLNmArry['last_name'];?></td>
                            <td><?php echo $empLNmArry['branch_name'];?></td>
                          </tr>
<?php } ?>

         </tbody>                             
 </table>
<script type='text/javascript' src='../js/plugins.js?_=<?php echo time()?>'></script>
