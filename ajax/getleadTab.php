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
$dbObj = new db();
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
//print_r($_POST);
//echo $startLmt;
if(strlen($_POST['whereCnd'])){
$where=$_POST['whereCnd'];

$resultCountLead = mysql_fetch_assoc(mysql_query("SELECT COUNT(id) as leadCount FROM leads WHERE ".$where));

$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','status' ),"leads",$where."  order by create_date desc limit $startLmt,$nofr" );
}else{
$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','status' ),"leads"," 1=1 order by create_date desc limit $startLmt,$nofr");
}
?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" id='queryDetailTabId' row-count = "<?php echo $resultCountLead['leadCount']; ?>" >
                            <thead>

                                <tr>
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>                                 
                                 	<th width="15%">Name</th>
                                    <th width="15%">E-mail</th>
                                    <th width="10%">Phone</th>  
                                   <?php if(strlen($_POST['whereCnd'])){ ?>
                                    <th width="20%">Remark</th>                                    
                                   <?php }else{ ?>
                                    <th width="20%">Course</th> 
                                   <?php } ?>
                                    <th width="22%">Date</th>
                                    <th width="10%">User ID</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php    
                                   array_shift($getDataLeads );
                                foreach($getDataLeads as $data){
                                   // print_r($data);
								  $styNew="background:#fff;color:#000;font-weight:bold;";

                                    ?>
                                <tr>
   <?php 
                                         $leadId=$data['id'];
                                         $sql= mysql_fetch_assoc(mysql_query("select max(id) as id from user_query where lead_id=$leadId"));  
                                    $followup_date = mysql_fetch_assoc(mysql_query("select DATE_FORMAT(next_followup_date,'%d-%m-%Y %r') nxtDt,message from user_query where id={$sql['id']}"));
                                    
                                    if($data['r_status']==1){
												$styNew="background:#F8F8F8;color:#000;";
												}
  $nofDead = $userquery->noOfDead($leadId);
                                    if($nofDead <= 1 && $nofDead >= 1 && $data['status'] == 'Dead'){
                                            $styNew="background:#ffb612;color:#000;";
                                          }  
                                       if($nofDead >= 2 && $data['status'] == 'Dead' ){
                                            $styNew="background:#ff1c37;color:#000;";
                                          }  
											
											if($data['hits']>=1 && $data['emp_id']!=1){
												$styNew="background:#a2d246;color:#000;";
												}
                                    ?>
                                   <td style="<?php  echo $styNew; ?>" id="c_b"><input onClick="dsiCntrl( this );" name="led[]" class="check" type="checkbox"  value="<?php echo $data['id']; ?>">  </td>
                                    <td style="<?php echo $styNew;?>" ><a href="<?php echo constant('BASE_URL'); ?>/superadmin/messagedetail.php?id=<?php echo $data['id']; ?>"><?php echo ucwords ( strtolower ($data['name'])); ?></a></td>
                                    <td style="<?php echo $styNew;?>" ><?php echo $data['email']; ?></td>
                                    <td style="<?php echo $styNew;?>" ><?php echo $data['phone']; ?></td>  
                                    <?php if(strlen($_POST['whereCnd'])){ ?>
                                     <td style="<?php echo $styNew;?>" ><?php echo $followup_date['message']; ?></td>
                                    <?php }else{?>
                                     <td style="<?php echo $styNew;?>" ><?php echo str_replace("-"," ",$data['category']); ?></td>
                                    <?php } ?>
                                    <td style="<?php echo $styNew;?>" > <?php echo $data['cdt'];?></td>
                                    <td style="<?php echo $styNew;?>" ><?php  if($data['emp_id'] == 1){echo 'Admin'; }else{  
          $userDat = $dbObj->getData(array('first_name','last_name') ,"login_accounts", "id = ".$data['emp_id']); 
 ?>
         <a href="redadacc.php?user_id=<?php echo $data['emp_id'];?>">
                <?php echo $userDat[1]['first_name']." ".$userDat[1]['last_name'] ; ?>
        </a>
<?php  } ?>
</td>                               
                          </tr>
                                <?php
                                
                                }
?>
                                
                                
                                                                
                            </tbody>
                            
                                
                        </table>

<script>
$("#chAll").click(function(){
     $('input:checkbox').not(this).prop('checked', this.checked);
});

</script>