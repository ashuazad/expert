<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
date_default_timezone_set('Asia/Kolkata');
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}

if(!empty($_SESSION['uid'])){
   $user_id = $_SESSION['uid']; 
}else{
   $user_id = $_SESSION['id']; 
}

$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$today = date("Y-m-d");
$searchText = $_POST['searchText'];
switch($_POST['tab']){
case 'plds-table-div':
$userLeadsPend = mysql_query("SELECT id,name, phone, message, status, DATE_FORMAT( last_follow_up, '%d-%m-%y | %r') fldDt ,DATE_FORMAT( next_followup_date, '%d-%m-%y')  nxtfldDt,hits FROM leads
                              WHERE (status not in('Start','Complete','Dead')  and emp_id=".$user_id." )                                
							 AND phone LIKE '".$searchText."%'");
?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr>                                    
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>
                                    <th width="20%">Name</th>
                                    <th width="10%">Phone</th>
                                    <!-- <th width="22%">Course</th> -->
                                    <th width="30%">Remark</th>
                                    <th width="8%">Status</th>                                    
                                    <th width="20%">Calling Date</th>
                                    <th width="15%">Next Calling Date</th>                                     
                                </tr>
                            </thead>                           
                              <tbody>                                    
                              <?php  
while($fdatasAL = mysql_fetch_assoc($userLeadsPend)) {
$styNew='';
if($datas['hits']>=1 ){

												$styNew="background:#a2d246;color:#000;";

												}	
							  ?> 
                                <tr>
                             <td style="<?php  echo $styNew; ?>"><input type="checkbox" name="checkall" class="check" id="chAll" /> </td>
                            <td style="<?php  echo $styNew; ?>"><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatasAL['id'];?>&tb=tabs-2"><?php echo $fdatasAL['name']; ?></a></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['phone']; ?></td>
                            <!--<td><?php // echo $leadDtl[1]['category']; ?></td>-->        
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['message']; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['status']; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['fldDt']; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['nxtfldDt']; ?></td>
                          </tr> 
					<?php	} ?>                             
                       </tbody>                             
                    </table>    

<?php
break;
case 'ls-tabl':
$sql = "select  id,email,name, phone, message, status, DATE_FORMAT( last_follow_up, '%d-%m-%y | %r') fldDt ,DATE_FORMAT( next_followup_date, '%d-%m-%y')  nxtfldDt from leads where status != 'Start' and emp_id=$user_id AND phone LIKE '".$searchText."%'";
$resultSearchPhone = mysql_query($sql);
?>
 <table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;">
                            <thead>
                                <tr>                                    
                                    <th width="15%">Name</th>
                                    <th width="10%">Phone</th>
                                    <th width="15%">E-mail</th>
                                    <th width="20%">Remark</th>
                                    <th width="8%">Status</th>
                                    <th width="20%">Calling Date</th>     
                                    <th width="15%">Next Calling Date</th>                                
                                </tr>
                            </thead>
<?php
while($dataSearchPhone = mysql_fetch_assoc($resultSearchPhone)){
?>
<tr>
                            <td><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $dataSearchPhone['id'];?>&tb=tabs-2"><?php echo $dataSearchPhone['name']; ?></a></td>
                            <td><?php echo $dataSearchPhone['phone']; ?></td>
                            <td><?php echo $dataSearchPhone['email']; ?></td>                  
                            <td><?php echo $dataSearchPhone['message']; ?></td>
                            <td><?php echo $dataSearchPhone['status']; ?></td>
                            <td><?php echo $dataSearchPhone['fldDt']; ?></td>
                            <td><?php echo $dataSearchPhone['nxtfldDt']; ?></td>
                          </tr> 
<?php
}?>
</table>
<?php
break;
 } ?>

