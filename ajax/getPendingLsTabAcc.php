<?php
require_once '../includes/userqueryDatabase.php';
require_once  '../includes/useraccountDatabase.php';
require_once  '../includes/db.php';
require_once  '../includes/functions.php';
date_default_timezone_set('Asia/Kolkata');
session_start();

if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
if(!empty($_SESSION['uid'])){
   $user_id = $_SESSION['uid']; 
}else{
   $user_id = $_SESSION['id']; 
}
$dbObj = new db();
$userquery = new userqueryDatabase();
$useraccount = new useraccountDatabase();
$fetchrecord = $useraccount->getRecordById($user_id);
$today = date("Y-m-d");
//echo "hello";
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
//print_r($_POST);
$shortClause='';
if(!empty($_POST['colname']) && !empty($_POST['shortType']) ){
$shortClause = " ORDER BY ".$_POST['colname']." ".$_POST['shortType'];
}
$userLeadsPend = mysql_query("SELECT id,name, phone, message, status, last_follow_up,DATE_FORMAT( last_follow_up, '%d-%m-%y | %r') fldDt ,next_followup_date,DATE_FORMAT( next_followup_date, '%d-%m-%y')  nxtfldDt, hits FROM leads
					WHERE (status not in('Start','Complete','Dead')  and date(next_followup_date)<='".$today."' and emp_id=".$user_id." )
					OR (DATE(assingment_data) <= '".$today."' AND Message IS NULL AND EMP_id=".$user_id.") $shortClause
		LIMIT $startLmt,$nofr");
$thClass = '';
if(!empty($_POST['className'])){
	$thClass = $_POST['className'];
}

?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr id="tab2-table-heading" >                                    
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>
                                    <th width="20%" clname="name" class='<?php if($_POST['colname'] =='name') echo  $thClass;?>' >Name</th>
                                    <th width="10%" clname="phone" class='<?php if($_POST['colname'] =='phone') echo  $thClass;?>'  >Phone</th>
                                    <!-- <th width="22%">Course</th> -->
                                    <th width="30%" clname="message" class='<?php if($_POST['colname'] =='message') echo  $thClass;?>'  >Remark</th>
                                    <th width="8%" clname="status" class='<?php if($_POST['colname'] =='status')  echo  $thClass;?>'  >Status</th>                                    
                                    <th width="20%" clname="last_follow_up" class='<?php if($_POST['colname'] =='last_follow_up')  echo  $thClass;?>'  >Calling Date</th>
                                    <th width="15%" clname="next_followup_date" class='<?php if($_POST['colname'] =='next_followup_date')  echo  $thClass;?>' >Next Calling Date</th>                                     
                                </tr>
                            </thead>                           
                              <tbody>                                    
                              <?php  

while($fdatasAL = mysql_fetch_assoc($userLeadsPend)) {
									  //$leadDtl=$dbObj->getData(array("*"),"leads","id='".$fdatasAL[0]['lead_id']."'");
//print_r($leadDtl);
$styNew='';
if($fdatasAL['hits']>=1 ){
	$styNew="background:#a2d246;color:#000;";
		}
								  ?> 
                                <tr>
                             <td style="<?php  echo $styNew; ?>"><input type="checkbox" name="checkall" class="check" id="chAll" /> </td>
                            <td style="<?php  echo $styNew; ?>"><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatasAL['id'];?>&tb=tabs-2"><?php echo $fdatasAL['name']; ?></a></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo hidePhone($fdatasAL['phone']); ?></td>
                            <!--<td><?php // echo $leadDtl[1]['category']; ?></td>-->        
                            <td style="<?php  echo $styNew; ?>"><?php if($fdatasAL['message'] == ''){echo "NONE";}else{echo $fdatasAL['message'];} ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['status']; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php if($fdatasAL['last_follow_up'] == '0000-00-00 00:00:00'){echo "NONE";}else{ echo $fdatasAL['fldDt'];} ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php if($fdatasAL['nxtfldDt'] == '00-00-00'){echo "NONE";}else{echo $fdatasAL['nxtfldDt'];} ?></td>
                          </tr> 
					<?php	} ?>                             
                       </tbody>                             
                    </table>    

                         
                            <script type="text/javascript" src='../js/jquery-1.4.2.min.js'></script>
                            <script type='text/javascript' >
                                /*Shorting Code*/
$("#tab2-table-heading > th").click(function(){

        var clsName = $(this).attr('Class');
        var shortType = '';
        var colname = $(this).attr('clname');

        $("#tab2-table-heading > th").attr("class","");
        if(clsName == ''){
                $(this).addClass("orderby-desc");
                shortType = "DESC";
        }else if(clsName == 'orderby-desc'){
                 $(this).removeClass("orderby-desc");
                 $(this).addClass("orderby-asc");
                shortType = "ASC";
        }else if(clsName == 'orderby-asc'){
                 $(this).removeClass("orderby-asc");
                 $(this).addClass("orderby-desc");
                shortType = "DESC";
        }

 $("#short-type").text(shortType);
         $("#class-name").text($(this).attr("class"));
          $("#col-name").text(colname);

var nfpg=$("#pls-nfp").text();
var cPgP = parseInt($(".pls-curnt-page").attr("id"));
var nfrpp=$("#pls-nofr").val();
$(".pls-curnt-page").attr("id","1");
$(".pls-curnt-page").text("1");
       var nxtpg=1;
                                    $.post(
                                                        '../ajax/getPendingLsTabAcc.php'  ,
                                                        {'nfrPP':nfrpp,'pageN':nxtpg,'colname':colname,'shortType':shortType,'className':$(this).attr("class")},
                                                function(data)
                                                        {

                                                        $("#plds-table-div").html(data);
                                                        }
                                        );

});
</script>
