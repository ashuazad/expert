<?php
require_once '../includes/userqueryDatabase.php';
require_once  '../includes/useraccountDatabase.php';
require_once  '../includes/db.php';
require_once '../includes/userPermissions.php';
date_default_timezone_set('Asia/Kolkata');
session_start();
if(!$_SESSION['OTP_C']){    
echo "<script>location.href='../index.php?id=logout'</script>";
}

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
$_SESSION['user_details']=$fetchrecord;
//$getData = $userquery->getRecordByEmployee($fetchrecord['category'], $fetchrecord['branch_id'],'1');
$getNewLeads = $userquery->getRecordByEmployee($user_id,'1');
//print_r($getData);
$followuptoday = $userquery->todayFollowUps($user_id);
//print_r($followuptoday);
$permissions = new userPermissions($user_id);
$_SESSION['user_permission'] = $permissions->userPermission;
if($fetchrecord['role'] != 'employee'){
    if($fetchrecord['role'] == 'admin'){
        header('Location: ' . constant('BASE_URL').'/superadmin');
    exit;
    } else if($fetchrecord['role'] == 'branch'){
        header('Location: ' . constant('BASE_URL').'/branch');
    exit;
    } else {
        header('Location: ' . constant('BASE_URL'));
    exit;
    }
}

$today = date("Y-m-d");
 //$selectLead = "select * from leads where DATE(create_date) = '$today' and branch_id = '$user_id'";
// $resultLead = mysql_query($selectLead) or die(mysql_error());
###### Pending Leads ###### 

if(empty($_GET['p-nfr'])){
  $pnrf = 50;
}else{
  $pnrf = $_GET['p-nfr'];
}

$userLeadsPendCount = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) num_rows  FROM leads 
          WHERE (status not in('Start','Complete','Dead')  and date(next_followup_date)<='".$today."' and emp_id=".$user_id." ) 
          OR (DATE(assingment_data) <= '".$today."' AND Message IS NULL AND EMP_id=".$user_id.")"))['num_rows'];

$userLeadsPend = mysql_query("SELECT id,name, phone, message, status,last_follow_up, DATE_FORMAT( last_follow_up, '%d-%m-%y | %r') fldDt ,DATE_FORMAT( next_followup_date, '%d-%m-%y')  nxtfldDt , hits FROM leads 
          WHERE (status not in('Start','Complete','Dead')  and date(next_followup_date)<='".$today."' and emp_id=".$user_id." ) 
          OR (DATE(assingment_data) <= '".$today."' AND Message IS NULL AND EMP_id=".$user_id." ) order by next_followup_date desc, hits desc
          LIMIT 0,$pnrf");

###### Pending Leads ######

###### Today Pending Leads ######
$todayPend = array();
$userLeadsTodayPendCount = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) num_rows  FROM leads 
          WHERE (status not in('Start','Complete','Dead')  and date(next_followup_date)='".$today."' and emp_id=".$user_id." ) 
          OR (DATE(assingment_data) = '".$today."' AND Message IS NULL AND EMP_id=".$user_id.")"));

$userLeadsTodayPend = mysql_query(  "SELECT id,name, phone, message, status,last_follow_up, DATE_FORMAT( last_follow_up, '%d-%m-%y | %r') fldDt ,DATE_FORMAT( next_followup_date, '%d-%m-%y')  nxtfldDt , hits FROM leads 
          WHERE (status not in('Start','Complete','Dead')  and date(next_followup_date)='".$today."' and emp_id=".$user_id." ) 
          OR (DATE(assingment_data) = '".$today."' AND Message IS NULL AND EMP_id=".$user_id." ) order by next_followup_date desc, hits desc");
while($each = mysql_fetch_assoc($userLeadsTodayPend)){
    $todayPend[] = $each;
}
###### Today Pending Leads ######
$nrof=50;
if(isset($_GET['nfr'])){
$nrof=$_GET['nfr'];
}
$getAllStatusLeads = $userquery->allStatusLead($user_id , 0 , $nrof );
//$getAllStatusLeadsNuR = count($userquery->allStatusLead($user_id )); countAllStatusLead
$getAllStatusLeadsNuR = $userquery->countAllStatusLead($user_id );

### Add New Lead ###
if(isset($_POST['submit'])){
array_pop($_POST);
$branch_idData = $dbObj->getData(array("branch_id") , "login_accounts" , "id = '".$user_id."'");
$_POST['create_date']=date("Y-m-d H:i:s");
$_POST['r_status']=1;
$_POST['emp_id']=$user_id;
$ip_address = $_SERVER['REMOTE_ADDR'];
$_POST['ip']=$ip_address;
$_POST['assingment_data']=date("Y-m-d H:i:s");
$_POST['domain_url']='incoming calls';
$_POST['status'] = 'Active';
if($branch_idData[0]>0){
	$_POST['branch_id'] = $branch_idData[1]['branch_id'];
}
$checkLead = $dbObj->getData(array("*") , "leads" , "phone = '".$_POST['phone']."'");
if($checkLead[0] == 0){
	$addedLeadid = $dbObj->dataInsert($_POST,"leads");
	$fwrLeadData = array();
    $fwrLeadData['object_id']     = $addedLeadid;
    $fwrLeadData['object_type'] ='LEAD';
    $fwrLeadData['currentId']   = $user_id;
    $fwrLeadData['nextId']      = $user_id;
    $fwrLeadData['frwDate']     = date("Y-m-d H:i:s");
    $fwrLeadData['modified_by'] = $user_id;
    $dbObj->dataInsert($fwrLeadData,"leadfrwdhistory");
 if($addedLeadid){
     header("location:index.php?n=l"); 
    }
 }else if($checkLead[1]['emp_id'] != $user_id){
   header("location:index.php?n=n"); 
 }
}


####### SMS List #######
$smsList = $dbObj->getData(array("*"), "sms");
array_shift($smsList);
####### SMS List ######


####### Request List #######
$user_request_List = $dbObj->getData(array("request_txt","DATE_FORMAT(date_added,'%h-%i %p') added_time","status") , "user_request","status=0 AND user_id=".$user_id ." AND DATE(date_added) = '".$today."' ORDER BY date_added DESC"  );
array_shift($user_request_List);
####### Request List ######
?>


<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

        <title>EXPERT| Lead Management Software Improve Customer Experience</title>

        <link rel="icon" type="image/ico" href="../favicon.ico"/>
        <link rel="icon" href="../images/favicon.png" type="image/x-icon" />
        <link href="../css/stylesheets.css" rel="stylesheet" type="text/css" />
        
        <link rel='stylesheet' type='text/css' href='../css/fullcalendar.print.css' media='print' />
        <script type="text/javascript" src='../js/jquery-1.4.2.min.js'></script>
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js'></script>
        <script type='text/javascript' src='../js/plugins/jquery/jquery.mousewheel.min.js'></script>
        <script type='text/javascript' src='../js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>
        <script type='text/javascript' src='../js/plugins/bootstrap.min.js'></script>
        <script type='text/javascript' src='../js/plugins/charts/excanvas.min.js'></script>
        <script type='text/javascript' src='../js/plugins/charts/jquery.flot.js'></script>    
        <script type='text/javascript' src='../js/plugins/charts/jquery.flot.stack.js'></script>    
        <script type='text/javascript' src='../js/plugins/charts/jquery.flot.pie.js'></script>
        <script type='text/javascript' src='../js/plugins/charts/jquery.flot.resize.js'></script>
        <script type='text/javascript' src='../js/plugins/sparklines/jquery.sparkline.min.js'></script>
        <script type='text/javascript' src='../js/plugins/fullcalendar/fullcalendar.min.js'></script>
        <script type='text/javascript' src='../js/plugins/select2/select2.min.js'></script>
        <script type='text/javascript' src='../js/plugins/uniform/uniform.js'></script>
        <script type='text/javascript' src='../js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>
     <script type='text/javascript' src='../js/plugins/validation/languages/jquery.validationEngine-en.js' charset='utf-8'></script>
        <script type='text/javascript' src='../js/plugins/validation/jquery.validationEngine.js' charset='utf-8'></script>
        <script type='text/javascript' src='../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
        <script type='text/javascript' src='../js/plugins/animatedprogressbar/animated_progressbar.js'></script>
        <script type='text/javascript' src='../js/plugins/qtip/jquery.qtip-1.0.0-rc3.min.js'></script>
        <script type='text/javascript' src='../js/plugins/cleditor/jquery.cleditor.js'></script>
        <script type='text/javascript' src='../js/plugins/dataTables/jquery.dataTables.min.js'></script>    
        <script type='text/javascript' src='../js/plugins/fancybox/jquery.fancybox.pack.js'></script>
        <script type='text/javascript' src='../js/plugins/scrollup/jquery.scrollUp.min.js'></script>      
        <script type='text/javascript' src='../js/cookies.js'></script>
        <script type='text/javascript' src='../js/actions.js?_=13123'></script>
        <script type='text/javascript' src='../js/charts.js'></script>
        <script type='text/javascript' src='../js/plugins.js'></script>
        <script type='text/javascript' src='../js/style.js'></script>
        <script type='text/javascript' src='../js/accjs.js'></script>
        <script type='text/javascript' >
     $(document).ready(function(){
/*             $("#tSortable_wrapper").css("background","#fff");
             $("#tSortable_wrapper").css("margin-top","-6px");
*/
            $(".dataTables_wrapper").css("background","#fff");
             $(".dataTables_wrapper").css("margin-top","-6px");
                

      $("#cnlLead").click(function(){

                     $("#addleadfrm").hide("fast");

         });

       $("#addN").click(function(){

                     $("#addleadfrm").show("fast");

         });	
            });
        </script>  

<!--tabcode-->

<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
<!--tabcode-->
    </head>
    <body>
<!-- Add New Lead -->


<div id="addleadfrm" style="position:fixed; width:100%; height:100%; background:rgba(63,55,57,0.6);z-index:3000; display:none;">

<div id="addFrm" style="width:30%; background-color:#6a5eaa;margin:auto;margin-top:100px; border-radius:10px;">
   
     <form action="" method="post" id="signupForm" style="margin:auto;padding:10px; width:75%;height:100%; margin-left:25%; ">
    <div class="fieldContainer">
        <div class="formRow">
            <div class="">
                <label for="name">Name:</label>
            </div>
            <div class="field">
                <input style="width:240px; height:28px;" type="text" required name="name" id="nameLd" placeholder="Name">
            </div>
        </div>
        <div class="formRow">
            <div class="">
                <label for="email">Email:</label>
            </div>
            <div class="field">
                <input style="width:240px; height:28px;" type="email" required name="email" id="emailLd" placeholder="Email ID">
            </div>
        </div>
        <div class="formRow">
            <div class="">
              <label for="email">Phone No:</label>
            </div>
            <div class="field">
              <input style="width:240px; height:28px;" type="text" required name="phone" id="phoneLd" placeholder="Phone">
            </div>
        </div>
<div class="formRow">
            <div class="">
                 <label for="pass">Courses:</label>

            </div>            

            <div class="field">

     <select style="width:250px; height:35px;" required="" class="" id="courseLd" name="category">Select Your Course</option>
     
   <option style="background-color:#000; font-size:15px; color:#FFF" value="Advance iPhone Repairing Course">Advance iPhone Repairing Course</option>
      <option style="background-color:#000; font-size:15px; color:#FFF" value="Advance Mobile Repairing course"> Advance Mobile Repairing course</option>
        <option style="background-color:#000; font-size:15px; color:#FFF" value="Advance Laptop Repairing Course">Advance Laptop Repairing Course</option>
              <option style="background-color:#000; font-size:15px; color:#FFF" value="MacBook Repairing Course">MacBook Repairing Course</option>
              <option style="background-color:#000; font-size:15px; color:#FFF" value="Printer Repairing Course">Printer Repairing Course</option>
              <option style="background-color:#000; font-size:15px; color:#FFF" value="CCTV Camera Repairing">CCTV Camera Repairing</option>
              <option style="background-color:#000; font-size:15px; color:#FFF" value="LED LCD Smart TV Repairing Course"> LED LCD Smart TV Repairing Course</option>
              
              <option style="background-color:#000; font-size:15px; color:#FFF" value="Franchises">Franchises</option>
              
                   
              
              
            
                                      </select>
     </div>
    </div>
    </div>
    <div class="signupButton">
         <input style="width:100px; height:40px;" type="button" name="cancle" id="cnlLead" class="btn btn-danger" value="Exit">
         <input style="width:100px; height:40px;" type="submit" name="submit" id="sLead" class="btn btn-success" value="Save">
    </div>
    </form>
</div>
</div>
<!--Add New Lead-->

 <div class="wrapper">
      <div class="header">
            <a class="logo" href="index.html"><img src="../img/logo.png" alt="EXPERT| Admin panel" title="EXPERT| Admin panel"/></a>
            <ul class="header_menu">
                <li class="list_icon"><a href="#">&nbsp;</a></li>
            </ul>    
      </div>
     <?php
     require_once '../includes/emp_header.php';
     ?>
        <div class="content">
            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="<?php echo constant('BASE_URL'); ?>/account">Admin</a> <span class="divider">></span></li>                
                    <li class="active">Dashboard</li>
                </ul>

                <ul class="buttons">
                    <li>
                        <a href="#" class="link_bcPopupList"><span class="icon-user"></span><span class="text">Send SMS</span></a>

                        <div id="bcPopupList" class="popup">
                            <div class="head">
                                <div class="arrow"></div>
                                <span class="isw-users"></span>
                                <span class="name">Send SMS</span>
                                <div class="clear"></div>
                            </div>
                            <div class="body-fluid users">
				<div class="row-form">
		                            <div class="span2">Phone No : </div>
		                            <div class="span3"><input type="text" name='smsPhoneNO'></div>
		                            <div class="clear"></div>
                        		</div> 
                            	<div class="row-form">
		                            <div class="span2">SMS Template : </div>
		                            <div class="span3"><select name="sms_title">
							<?php foreach ($smsList as $valueSMS) { ?>
<option value="<?php echo $valueSMS['sms_title'];?>" <?php if($valueSMS['default_sms'] == 1){echo 'Selected="Selected"'; }?> ><?php echo $valueSMS['sms_title'];?></option>
							<?php  } ?>
							</select>
									</div>
		                            <div class="clear"></div>
                        		</div>
                        		<div class="row-form">
		                            <div class="span2">SMS Content : </div>
		                            <div class="span3"><textarea name="" id='smsContent'>
							<?php foreach ($smsList as $valueSMS) {  if($valueSMS['default_sms']){ echo html_entity_decode(trim(urldecode($valueSMS['sms_content'])));} }?>
							</textarea>	
<input type="hidden" name="sms_content" id="sms_content" value="<?php foreach ($smsList as $valueSMS) {  if($valueSMS['default_sms']){ echo $valueSMS['sms_content'];} }?>" ></div>
		                            <div class="clear"></div>

                        		</div>
                              
                            </div>
                            <div class="footer">
                               <button class="btn" type="button" id = "sendSMS">Send</button>
                                <button class="btn btn-danger link_bcPopupList" type="button">Close</button>
                           </div>
                        </div>                    

                    </li>                
                    <li>
              <a href="#" class="link_bcPopupSearch"><span class="icon-search"></span><span class="text">24/7 Support Send Request</span></a>

                        <div id="bcPopupSearch" class="popup">
                            <div class="head">
                                <div class="arrow"></div>
                                <span class="isw-zoom"></span>
                                <span class="name">Send Request</span>
                                <div class="clear"></div>
                           </div>
                        		<div class="row-form">
		                            <div class="span2">Enter Request </div>
		                            <div class="span3">
						<textarea name="" id='requestData'>						
						</textarea>
					    </div>
		                            <div class="clear"></div>
                        		</div>
					<div class="row-form">
		                            <div class="span3">
		<button class="btn btn-success" type="button" request-user-id="<?php echo $user_id;?>" id = "send_request">Send Request</button>
                                <button class="btn btn-danger link_bcPopupSearch" type="button">Close</button>
                            </div>
		                            <div class="clear"></div>
                        		</div>
                <div style="padding:2px 5px;font-weight:bold;"  >Your Pending Request</div> 
		<div id="listOfRequest" >
                              <?php foreach ($user_request_List as $requestItem) {?>
                               <div class="item user_request_list">                                    
                                    <div class="info clearfix" style="padding-left: 0px;">
<b><?php echo $requestItem['added_time'];?></b>
 <?php echo $requestItem['request_txt'];?>
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
			</div>
                            <div class="footer">

                          </div>
                        </div>                
                    </li>
                    <li>
                        <a href="querydetail.php" class="link_bcPopupSearchNP"><span class="icon-search"></span><span class="text">Search Name / Mobile</span></a>
                        <div id="bcPopupSearchNP" class="popup">
                            <div class="head">
                                <div class="arrow"></div>
                                <span class="isw-zoom"></span>
                                <span class="name">Search</span>
                                <div class="clear"></div>
                            </div>
                            <form action="querydetail.php" method="post">
                                <div class="body search">
                                    <input type="text" placeholder="Some text for search..." name="search" id="searchLeadName"/>
                                </div>
                                <div class="footer">
                                    <button class="btn" type="submit" id = "searchLeadBtn">Search</button>
                                    <button class="btn btn-danger link_bcPopupSearchNP" type="button">Close</button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>

            </div>
            <div class="workplace">
<?php
if($_GET['n']=='l'){
?>
<div class="alert alert-success">                
                    <h4>Success!</h4>
                    Lead has been successfully added.
                </div>
<?php }
if($_GET['n']=='n'){
?>
<div class="alert alert-error">                
                    <h4>Error!</h4>
                    The lead is already on other user. 
                </div>
<?php } ?>
<div class="row-fluid">
     <div class="span12">
             <div class="">
                 <div class="wBlock auto clearfix">
                     <div class="dSpace">
                         <h3>Today Pending</h3>
                         <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!--2,5,3,5,6,7,10,8,5,6,2,5-->
                  </span> <span class="number"><?php echo $userLeadsTodayPendCount['num_rows'] ;?></span> </div>
                 </div>
               <div class="wBlock gray auto clearfix">
                 <div class="dSpace">
                 <h3>Pending All Calls</h3>
                 <span class="mChartBar" sparktype="bar" sparkbarcolor="white">
                   <!--2,5,3,5,6,7,10,8,5,6,2,5-->
                 </span> <span class="number" id="pndLds"> <?php echo $userLeadsPendCount; ?> </span> </div>
               </div>
               <div class="wBlock  green auto clearfix">
                        <div class="dSpace">
                            <h3>Today New Lead</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--2,5,3,5,6,7,10,8,5,6,2,5--></span>
                            <span class="number"><?php echo count($getNewLeads ); ?></span>                                                  
                        </div>
               </div>
               <div class="wBlock  yellow auto clearfix">
                 <div class="dSpace">
                   <h3>Today Done Calls</h3>
                   <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                     <!--2,5,3,5,6,7,10,8,5,6,2,5-->
                   </span> <span class="number"><?php echo count($followuptoday); ?></span> </div>
               </div>
               <div class="wBlock auto clearfix">
                <div class="dSpace">
                  <h3>All Live Status</h3>
                  <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!--2,5,3,5,6,7,10,8,5,6,2,5-->
                  </span> <span class="number"><?php echo $getAllStatusLeadsNuR ;?></span> </div>
                </div>
                 
             </div>
   </div>
</div>           
<div class="row-fluid">                
                <div class="span12">                    
                  <div class="head">
                          <div class="isw-refresh" id="rfBt" style="cursor:pointer;" onClick="window.location.href='index.php'"></div>
                        <h1>Dashboard</h1>                           
						<ul class="buttons">                            
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                   <?php $statArray = $dbObj->getData(array("*"), "status");  array_shift($statArray); foreach( $statArray as $dtStat ){  ?><li><a href="querydetailFltSt.php?lftr=<?php echo $dtStat['status'];?>"><span class="isw-list"></span> <?php echo $dtStat['status'];?></a></li><?php  } ?>                                   

                                </ul>
                            </li>
                        </ul>                        <div class="clear"></div>
                    </div>
                    <div class="block-fluid">
<div id="tabs">
<ul>
<li><a href="#tabs-5">Today Pending</a></li>
<li><a href="#tabs-2">Pending All Calls</a></li>
<li><a href="#tabs-4">Today New Lead</a></li>
<li><a href="#tabs-3">Today Done Calls</a></li>
<li><a href="#tabs-1">All Live Status</a></li>
</ul>
<div id="tabs-1">
<div id='ls-nrf'>Show <select name='nofr' id='ls-nofr' style='padding: 0px; height: 25px; width: 55px;font-size:12px;'>

                                            <option value='20' <?php if($nrof==20)echo 'selected="selected"';?>>20</option>

                                           <option value='50'  <?php if($nrof==50)echo 'selected="selected"';?>>50</option>

                                           <option value='100'  <?php if($nrof==100)echo 'selected="selected"';?>>100</option>

                                 </select></div>
<div style="float: left; margin-left: 10px;">
  <input type="text" style="width: 100%; height: 15px;" placeholder="Enter Phone No" search-type="ls-tabl" name="searchLeads" id="searchPhoneAllStatus"  >
    
  </div>
<div style="float: left; margin-left: 10px;">
<input textfieldId="searchPhoneAllStatus"  type="button" value="Search" style="float: left; margin-left: 15px;" name="searchPhone">
</div>
 <div id='ls-nfp' style="display:none;" ><?php echo ceil($getAllStatusLeadsNuR /$nrof); ?></div>

                <div id='ls-nextprev'><ul ><li class='ls-f-page' id='1'>Home</li><li class='ls-prev-page' id='1'> < Previous</li><li class='ls-curnt-page' id='1'>1</li><li class='ls-next-page' id='2'>Next ></li><li class='ls-l-page' id='<?php echo ceil($AllStatus/$nrof);?>'>Last</li></ul></div>



                    <div class="clear"></div>	
<div id="ls-tabl">
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
                           <?php  if($getAllStatusLeads!= ''){  ?>
                                <tbody>                                    
                            <?php 
//print_r($getAllStatusLeads);
                       $getAllStatusLeads=   array_reverse($getAllStatusLeads);
//print_r($getAllStatusLeads);
$i=1;
                             foreach($getAllStatusLeads as $fdatas) { 
                              ?> 
                          <tr>
                            <td><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatas[0];?>&tb=tabs-2"><?php echo $fdatas[1]; ?></a></td>
                            <td><?php echo $fdatas[4][0].$fdatas[4][1].'******'.$fdatas[4][strlen($fdatas[4])-2].$fdatas[4][strlen($fdatas[4])-1] ; ?></td>
                            <td><?php echo $fdatas[2]; ?></td>                  
                            <td><?php echo $fdatas[6]; ?></td>
                            <td><?php echo $fdatas[5]; ?></td>
                            <td><?php echo $fdatas[8]; ?></td>
                            <td><?php echo $fdatas[9]; ?></td>
                          </tr> 
                       <?php  $i++;  }  ?>
                         </tbody>                             
                            <?php }
                                else { 
                                    echo "No record(s) found";  } ?>
                        </table>
            </div>
</div>
<div id="tabs-2" class="block-fluid " >
<div id='ls-nrf'>Show <select name='nofr' id='pls-nofr' style='padding: 0px; height: 25px; width: 55px;font-size:12px;'>

                                            <option value='20' <?php if($pnrf==20)echo 'selected="selected"';?>>20</option>

                                           <option value='50'  <?php if($pnrf==50)echo 'selected="selected"';?>>50</option>

                                           <option value='100'  <?php if($pnrf==100)echo 'selected="selected"';?>>100</option>

                                 </select></div>
<div style="float: left; margin-left: 10px;">
   <input type="text" style="width: 100%; height: 15px;" placeholder="Enter Phone No" search-type="plds-table-div" name="searchLeads" id="searchPhonePendingLead">
  </div>
<div style="float: left; margin-left: 10px;">
<input  textfieldId="searchPhonePendingLead" type="button" value="Search" style="float: left; margin-left: 15px;" name="searchPhone">
</div> 
 <div id='pls-nfp' style="display:none;" ><?php echo ceil($userLeadsPendCount/$pnrf); ?></div>
  
 <div id='short-type' style="display:none;" ></div>
 <div id='class-name' style="display:none;" ></div>
 <div id='col-name' style="display:none;" ></div>

                <div id='pls-nextprev'>
                <ul>
                  <li class='pls-f-page' id='1'>Home</li>
                  <li class='pls-prev-page' id='1'> < Previous</li>
                  <li class='pls-curnt-page' id='1'>1</li>
                  <li class='pls-next-page' id='2'>Next ></li>
                  <li class='pls-l-page' id='<?php echo ceil($userLeadsPendCount/$pnrf);?>'>Last</li></ul>
                </div>
<div id="plds-table-div">                
<table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr id="tab2-table-heading">                                    
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>
                                    <th width="20%" clname="name" class=''>Name</th>
                                    <th width="10%"clname="phone" class=''>Phone</th>
                                    <!-- <th width="22%">Course</th> -->
                                    <th width="30%"clname="message" class=''>Remark</th>
                                    <th width="8%"clname="status" class=''>Status</th>                                    
                                    <th width="20%"clname="last_follow_up" class=''>Calling Date</th>
                                    <th width="15%"clname="next_followup_date" class=''>Next Calling Date</th>                                     
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
                            <td style="<?php  echo $styNew; ?>"><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatasAL['id'];?>&tb=tabs-2"><?php echo ucwords ( strtolower ($fdatasAL['name'])); ?></a></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['phone'][0].$fdatasAL['phone'][1].'******'.$fdatasAL['phone'][strlen($fdatasAL['phone'])-2].$fdatasAL['phone'][strlen($fdatasAL['phone'])-1]; ?></td>
                            <!--<td><?php // echo $leadDtl[1]['category']; ?></td>-->
                            <td style="<?php  echo $styNew; ?>"><?php  if($fdatasAL['message'] == ''){echo "NONE";}else{ echo $fdatasAL['message']; }?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $fdatasAL['status']; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php if($fdatasAL['last_follow_up'] == '0000-00-00 00:00:00'){echo "NONE";}else{ echo $fdatasAL['fldDt']; }?></td>
                            <td style="<?php  echo $styNew; ?>"><?php if($fdatasAL['nxtfldDt'] == '00-00-00'){echo "NONE";}else{ echo $fdatasAL['nxtfldDt'];} ?></td>
                          </tr> 
          <?php } ?>                             
                       </tbody>                             
                    </table>    
  </div>
</div>
<div id="tabs-3" class="block-fluid">
 <table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;"  >
                            <thead>
                                <tr>
                                     <th ><input type='checkbox' name='chk'></th>                                    
                                    <th width="13%">Name</th>
                                    <th width="8%">Phone</th>
                                   <th width="10%">E-mail</th>
                                    <th width="20%">Remark</th>
                                    <th width="8%">Status</th>
                                    <th width="26%">Calling Date</th>     
                                    <th width="15%">Next Calling Date</th>                                
                                </tr>
                            </thead>
                           <?php  if($followuptoday != ''){  
//print_r($followuptoday);
?>
                                <tbody>
                                    
                                  <?php  
                                  $followuptoday=array_reverse($followuptoday);
                                  foreach($followuptoday as $fdatas) { ?> 
                                <tr>
                            <td><input type='checkbox' name='chk'></td>
                            <td><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatas[0];?>&tb=tabs-3"><?php echo $fdatas[2]; ?></a></td>
                            <td><?php echo $fdatas[4][0].$fdatas[4][1].'******'.$fdatas[4][strlen($fdatas[4])-2].$fdatas[4][strlen($fdatas[4])-1]; ?></td>
                            <td><?php echo $fdatas[5]; ?></td>                  
                            <td><?php echo $fdatas[6]; ?></td>
                            <td><?php echo $fdatas[7]; ?></td>
                            <td><?php echo $fdatas[8]; ?></td>
                            <td><?php echo $fdatas[9]; ?></td>
                                </tr> <?php  } 
                                
?>
                            </tbody>                             
                            <?php }
                                else { 
                                    echo "No record(s) found";  } ?>
                        </table>
</div>
<div id="tabs-4" class="block-fluid">
 <table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr>
                                     <th ><input type='checkbox' name='chk'></th>                                    
                                    <th width="25%">Name</th>
                                    <th width="20%">Phone</th>
                                    <th width="25%">E-mail</th>
                                    <th width="30%">Course</th>                                  
                                </tr>
                            </thead>
                           <tbody>
                            <?php 
                            
                            if(count($getNewLeads ) > 0){   ?>
                            
                                  <?php foreach($getNewLeads as  $datas) {
//print_r($datas);
$styNew='';
if($datas['hits']>=1 ){

												$styNew="background:#a2d246;color:#000;";

												}	
  ?> 
                                <tr>
                            <td style="<?php  echo $styNew; ?>"><input type='checkbox' name='chk'></td>
                            <td style="<?php  echo $styNew; ?>"><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $datas['id'];?>&tb=tabs-4"><?php echo ucwords ( strtolower ($datas['name'])); ?></a></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $datas['phone'][0].$datas['phone'][1].'******'.$datas['phone'][strlen($datas['phone'])-2].$datas['phone'][strlen($datas['phone'])-1]; ?></td>
                            <td style="<?php  echo $styNew; ?>"><?php echo $datas['email']; ?></td>                            
                              <td style="<?php  echo $styNew; ?>"><?php echo str_replace("-"," ",$datas['category']); ?></td>
                              
                                </tr>
                                <?php  } ?>
                            
                            <?php  }?>
                           
                          </tbody> 
                        </table>
</div>
<div id="tabs-5" class="block-fluid">
    <table cellpadding="0" cellspacing="0" width="100%" class="table" >
        <thead>
        <tr>
            <th ><input type='checkbox' name='chk'></th>
            <th width="20%">Name</th>
            <th width="15%">Phone</th>
            <th width="20%">Remark</th>
            <th width="15%">Status</th>
            <th width="15%"clname="last_follow_up" class=''> Date / Time Last Calling</th>
            <th width="15%"clname="next_followup_date" class=''>Next Calling Date</th>   
        </tr>
        </thead>
        <tbody>
        <?php
        if(count($userLeadsTodayPendCount) > 0){
            foreach($todayPend as  $datas) {
                $styNew='';
                if($datas['hits']>=1 ){
                    $styNew="background:#a2d246;color:#000;";
                }
                ?>
                <tr>
                    <td style="<?php  echo $styNew; ?>"><input type='checkbox' name='chk'></td>
                    <td style="<?php  echo $styNew; ?>"><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $datas['id'];?>&tb=tabs-4"><?php echo ucwords ( strtolower ($datas['name'])); ?></a></td>
                    <td style="<?php  echo $styNew; ?>"><?php echo $datas['phone'][0].$datas['phone'][1].'******'.$datas['phone'][strlen($datas['phone'])-2].$datas['phone'][strlen($datas['phone'])-1]; ?></td>
                    <td style="<?php  echo $styNew; ?>"><?php echo $datas['message']; ?></td>
                    <td style="<?php  echo $styNew; ?>"><?php echo $datas['status']; ?></td>
                    <td style="<?php  echo $styNew; ?>"><?php echo $datas['fldDt']; ?></td>
                    <td style="<?php  echo $styNew; ?>"><?php echo $datas['nxtfldDt']; ?></td>
                </tr>
            <?php  }
        }?>
        </tbody>
    </table>
</div>
</div>


                     </div>
                </div> 
                
                <div class="row-fluid" >
               
                 
            </div> 
            </div> 
                                                    

                                                                

                                                    


                                                    <div class="dr"><span></span></div>

                                                    </div>

                                                    </div>   

                                                    </body>
                                                    </html>
<script>
// SMS Script //   
$("#bcPopupList textarea").val($.trim($("#bcPopupList textarea").val()));

$('select[name="sms_title"]').change(function(){
	var sms_title_txt = $(this).val();
	$.ajax({
        type:'POST',
        url :'../ajax/getSMSContent.php',
        data :{ 'sms_title' : sms_title_txt },
        success: function(smsContentTxt){            
        	$("#smsContent").val($.trim(smsContentTxt));
        }

     });
});

$("#sendSMS").click(function(){
	var smsPhoneNo = $('input[name="smsPhoneNO"]').val();
	var smsContent = $('#bcPopupList textarea').val();

	$.ajax({
	  url: '../ajax/sms.php',
	  type:'POST',
	  data:{'smsNo':smsPhoneNo,'smsContent':smsContent},
	  success: function(dataMM) {
			console.log(dataMM);				  
			$('#smsSuccess').css('display','block');
		    $('#bcPopupList').css('display','none');
	  			}

		});	
	$('#bcPopupList').css('display','none');	
});

$("#clearSMS").click(function(){
	$('#sms_content').val(' ');
});

// SMS Script //
//User Request Script//
$("#requestData").val($.trim($("#requestData").val()));
$("#send_request").click(function(){
	var requestText = $("#requestData").val();
	var requestUser = $(this).attr('request-user-id');
	$.ajax({
		url : '../ajax/addRequest.php',
		type:'POST',
		data : {'request_txt':requestText,'user_id':requestUser},
		success : function(data){
			if(data){
				$("#bcPopupSearch").hide('fast');
				$("#listOfRequest").hide(data);
                                 
			}		
		}	
	});
	console.log(requestUser+' '+requestText);
});

//user Request Script//
</script> 

