<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/useraccountDatabase.php';
$dbObj = new db();
session_start();
date_default_timezone_set('Asia/Kolkata');
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$today = date("Y-m-d");
if ($id == '1') {
    $fetchrecord = 'admin';
}
if ($fetchrecord != 'admin') {
    if ($fetchrecord['role'] == 'employee') {
        header('Location: ' . constant('BASE_URL') . '/account');
        exit;
    } else if ($fetchrecord['role'] == 'branch') {
        header('Location: ' . constant('BASE_URL') . '/branch');
        exit;
    } else {
        header('Location: ' . constant('BASE_URL'));
        exit;
    }
}
####### All Admissions  #######
$branch = $branchData->getBranch();
$nofr=20;
$admsCount=0;
$nofr=20;
if(isset($_GET['nfr'])){
$nofr=$_GET['nfr'];
}

####### Where #######
$admsCount = array();
if(isset($_POST['fltrBt'])){
$frmDataAry = $_POST;
array_pop($frmDataAry);
$flterDetalArray =array();
foreach($frmDataAry as $key => $value ){
		if(is_array($value)){
				if(count($value)>0   ){
						if( ( strlen($value[0]>0) AND  strlen($value[1]>0) )){
						$flterDetalArray[] = array( $key => implode("_",$value ) );
						}else if( strlen($value[0]>0) ){
								$flterDetalArray[] = array( "onlyBrnch" => $value[0]  );
							}
					}
			}else if(strlen($value)>0){
								$flterDetalArray[] = array( $key => $value );
					}
	}
	$where = "";

$flagEmpSrch = false;

foreach( $flterDetalArray as $fKey => $fVal ){
	$caseVal=array_keys($fVal);
		switch($caseVal[0]){
			case 'ledsDat':
				$dateSrchArry = explode("_",$fVal['ledsDat']);
				$where.=" (doj  >= '".$dateSrchArry[0]."' AND doj <= '".$dateSrchArry[1] ."') AND";
			break;
			case 'srchbranch':
                                $flagEmpSrch = true;
				$brnchSrchArry = explode("_",$fVal['srchbranch']);
				$where.=" (branch_name  = '".$brnchSrchArry[0]."' AND emp_id = '".$brnchSrchArry[1] ."') AND";
			break;
			case 'onlyBrnch':
                                $flagEmpSrch = true;
				$where.=" (branch_name  = '".$fVal['onlyBrnch']."') AND";
			break;
			case 'phone':
				$where.= "(phone  = '".$fVal['phone']."') AND";
			break;
			case 'due_fee':
				$where.= "(due_fee <= ".$fVal['due_fee']."  AND due_fee != 0 ) AND";
			break;
			}
	 }
 #$where.= " status=1 ";
 $where.= " status != '0'";
 $where = rtrim($where,"AND");
   $admsCount=$dbObj->getData(array("count(*) admsCount"),"admission",$where );  
   $admsCount[0]=$admsCount[1]['admsCount'];
   //$admsAry=$dbObj->getData(array("*" , "DATE_FORMAT( doj , '%d-%c-%y') admDate"),"admission", $where ." order by a_id desc limit 0,$nofr");
   if($flagEmpSrch){
        $creditAmt = $dbObj->getData(array( 'SUM( total_fee - due_fee ) creditAmt' ),"admission", $where );
        $totalAmt = $dbObj->getData(array( 'SUM( total_fee ) totalAmt' ),"admission", $where );
        $dueTotalAmt = $dbObj->getData(array( 'SUM( due_fee ) dueTotalAmt' ),"admission", $where );        
     }
####### Where #######
	}else{
   #$admsCount=$dbObj->getData(array("*"),"admission" , " status=1");
   //$admsCount=$dbObj->getData(array("*"),"admission" , " status != '0'");        
   //$admsAry=$dbObj->getData(array("*" , "DATE_FORMAT( doj , '%d-%c-%y') admDate"),"admission", " status != '0' order by a_id desc limit 0,$nofr");
	}    
####### All Admissions  #######
//SMS List
$smsArray = $dbObj -> getData( array("*") , "sms"); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title>CRM- Admin Dashboard Panel</title>
        <link rel="icon" type="image/ico" href="../images/favicon.png"/>
        <link href="../css/stylesheets.css" rel="stylesheet" type="text/css" />
        <link rel='stylesheet' type='text/css' href='../css/fullcalendar.print.css' media='print' />
        <script type="text/javascript" src='../js/jquery-1.4.2.min.js'></script>
        <script type='text/javascript' src='../js/jquery.min.js'></script>
        <script type='text/javascript' src='../js/jquery-ui.min.js'></script>
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
        <script type='text/javascript' src='../js/cookies.js'></script>
        <script type='text/javascript' src='../js/actions.js'></script>
        <script type='text/javascript' src='../js/charts.js'></script>
        <script type='text/javascript' src='../js/plugins.js'></script>
        <script type='text/javascript' src='../js/style.js'></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
<style type="text/css">
#editFromBox{
  width:100%;
  height:100%;
  background-color : rgba(200,180,100,0.7);
  position:absolute;
  z-index:1000;
 display:none;
}

#editFrmContainer{width:38%;height:25%;margin:auto;margin-top:7%;padding:10px;}
@media screen and (max-width: 480px) {
  #editFrmContainer{width:70%;height:25%;margin:auto;margin-top:10%;padding:10px;}
}
#editFrmHead{
float:left;
width:96%;
height:20px;
padding:10px;
  background: url("../img/backgrounds/box-head.jpg") repeat-x scroll left top padding-box transparent;
    border: 0px solid #212429;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
color:#fff;
}
.editFrmDiv{
float:left;
width:234px;
height:100%;
  background-color:#1F27F2;
padding:11px;
}
#admissionEditFrm{
margin:auto;

}

#admissionEditFrm .butn{
 width:20%;
}
#feeDetailsDiv{
 display:block; 
 width:975px;
 height:auto;
 margin:auto; 
margin-top:12%;
}
.fdp_roll_no{
cursor:pointer;
}
</style>
    </head>
    <body>

<!-- Edit From -->

<div id="editFromBox">
  <div id="editFrmContainer"> 
  <div id="editFrmHead">Update Admission</div>
  <form id="admissionEditFrm">
  
  <div class="editFrmDiv">
      <div class="row"><div class="span3"><p>Registration No </p> <input type="text" readOnly name="regno" id="regno" value="0"> </div></div>
        <div class="row"><div class="span3"><p>Name </p> <input type="text" name="name" id="editName" placeholder="Name"></div></div>
        <div class="row"><div class="span3"><p>Fathers Name </p><input type="text" name="father_name" id="editFName" placeholder="Father Name"></div></div>
        <div class="row"><div class="span3"><p>Phone No </p><input type="text" name="phone" id="editPhoneno" placeholder="Phone No"></div></div>
        <div class="row"><div class="span3"><p>Email Id </p><input type="text" name="email_id" id="editEmailA" placeholder="Email"></div></div>

        <div class="row"><div class="span3"><p>Branch Name</p><select name="branch_name" id="editBranch" placeholder="Branch">
           
    <?php 

foreach ($branch as $fetch)  {  ?>
    <option value="<?php echo $fetch['id']  ?>"><?php echo $fetch['branch_name'];   ?></option>
    <?php  } ?>
         
          </select></div>
        </div>
        <div class="row">
                    <div class="span3"> 

                           <p>Admission Employee</p>
                           <select name="emp_id"  id="editAdmissUser" placeholder="Admission User" class="editUserLst" >
                                      <option value="" selected>Employee</option>
                          </select>
                   </div>
        </div>
        <div class="row">
            <div class="span3">
               <p>Lead Employee</p> 
               <select id="editLedUsr" placeholder="Lead User" name="lead_userId" class="editUserLst">
                    <option value="" selected>Employee</option>
               </select>
            </div>
        </div>

         <div class="row"><div class="span3"><input class="butn" id="editSave" type="button" value="Save"></div></div>    
    
  </div>
  <div class="editFrmDiv"> 
        <div class="row"><div class="span3"><p>Course</p>
   <select name="course[]" id="editCourse"  multiple="multiple"> </select> 
        </div></div>
        <div class="row"><div class="span3"><p>Fee Package</p><input type="text" name="course_fee" id="editFeePakg" placeholder="Fees Package"></div></div>
        <div class="row"><div class="span3"><p>Discount</p><input type="text" name="discount" id="editDiscount" placeholder="Discount"></div></div>
        <div class="row"><div class="span3"><p>Total Fee</p><input type="text" name="total_fee" id="editTotalFee" placeholder="Total Fee"></div></div>
        <div class="row"><div class="span3"><p>Credit Amount</p><input type="text" name="creditAmt" id="editCrdAmt" placeholder="Credit Amount"></div></div>
        <div class="row"><div class="span3"><p>Due Amount</p><input type="text" name="due_fee" id="editDueAmt" placeholder="Due Amount"></div></div>
        <div class="row"><div class="span3"><p>Next Due Date</p><input type="text" name="next_due_date" id="editNxtDueDate" placeholder="Next Due Date"></div></div>
         <div class="row"><div class="span3"><input class="butn" id="closeEditFRm" type="button" value="Exit" Style="margin-left:60%;" ></div></div>    

  </div>
</form>
  </div>

<!-- Fees Details  -->
<div class="row-fluid" id="feeDetailsDiv">            
                <div class="span12">                    
                    <div class="head">
                        <div>  <div class="isw-grid"></div>
                        <h1>Fees History</h1>  
                        </div>                                  
                        <div class="clear"></div>
                    </div>
<div class="block-fluid table-sorting" id="fdp_PayHistory"><table width="100%" cellspacing="0" cellpadding="0" style="padding:5px;" class="table">
                            <thead>
                                <tr>                                   
                                    <th width="10%">Receipt No </th>
                                    <th width="15%">Date | Time</th>
                                    <th width="10%">Received Amount </th>
                                    <th width="10%">Payment Mode</th>
                                    <th width="15%">Cheque  No. </th>     
                                    <th width="15%">User Id</th>     
                                    <th width="10%">Print</th>     
                                </tr>
                            </thead>
                     <tbody>                                    
                         <tr>
                            <td></td> 
                            <td></td>
                            <td></td>                  
                            <td></td> 
                            <td></td>
                            <td></td>  
                            <td></td>                            
                          </tr> </tbody>  </table></div>

                 </div>
               </div>
<!-- Fees Details  -->
</div>

<!-- Edit From -->

        <div class="header">
            <a class="logo" href="index.html"><img src="../img/logo.png" alt="Aquarius -  responsive admin panel" title="Aquarius -  responsive admin panel"/></a>
            <ul class="header_menu">
                <li class="list_icon"><a href="#">&nbsp;</a></li>

            </ul>    

        </div>

        <?php

        require_once '../includes/header.php';

        ?>

        <div class="content">
            <div class="breadLine">
                <ul class="breadcrumb">
                    <li><a href="#">Admin</a> <span class="divider">></span></li>                
                    <li class="active">Dashboard</li>
                </ul>
                <ul class="buttons">
                    <li>
                        <a href="#" class="link_bcPopupList"><span class="icon-user"></span><span class="text">Users list</span></a>



                        <div id="bcPopupList" class="popup">

                            <div class="head">

                                <div class="arrow"></div>

                                <span class="isw-users"></span>

                                <span class="name">List users</span>

                                <div class="clear"></div>

                            </div>

                            <div class="body-fluid users">



                                <div class="item">

                                    <div class="image"><a href="#"><img src="img/users/aqvatarius.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Aqvatarius</a>                                    

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>



                                <div class="item">

                                    <div class="image"><a href="#"><img src="img/users/olga.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Olga</a>                                

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                        



                                <div class="item">

                                    <div class="image"><a href="#"><img src="img/users/alexey.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Alexey</a>  

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                              



                                <div class="item">

                                    <div class="image"><a href="#"><img src="img/users/dmitry.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Dmitry</a>                                    

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                         



                                <div class="item">

                                    <div class="image"><a href="#"><img src="img/users/helen.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Helen</a>                                                                        

                                    </div>

                                    <div class="clear"></div>
                                </div>                                  
                                <div class="item">
                                    <div class="image"><a href="#"><img src="img/users/alexander.jpg" width="32"/></a></div>
                                    <div class="info">
                                        <a href="#" class="name">Alexander</a>                                                                        
                                    </div>
                                    <div class="clear"></div>
                                </div>                                  
                            </div>
                            <div class="footer">
                                <button class="btn" type="button">Add new</button>
                                <button class="btn btn-danger link_bcPopupList" type="button">Close</button>
                            </div>
                        </div>                 
                    </li>           
                    <li>
                        <a href="#" class="link_bcPopupSearch"><span class="icon-search"></span><span class="text">Search</span></a>
                        <div id="bcPopupSearch" class="popup">
                            <div class="head">
                                <div class="arrow"></div>
                                <span class="isw-zoom"></span>
                                <span class="name">Search</span>
                                <div class="clear"></div>
                            </div>
                            <div class="body search">
                                <input type="text" placeholder="Some text for search..." name="search"/>
                            </div>
                            <div class="footer">
                                <button class="btn" type="button">Search</button>
                                <button class="btn btn-danger link_bcPopupSearch" type="button">Close</button>
                            </div>
                        </div>                
                    </li>
                </ul>
            </div>
            <div class="workplace">
                <div class="row-fluid">
<div class="row-fluid">
<div class="span12">                    
 <div class="head">
                          <div class="isw-refresh" id="rfBt" style="cursor:pointer;" onClick="window.location.href='admissionSMS.php'"></div>
                        <h1>Admission </h1>                           
						<ul class="buttons">                            
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                <li><a href="http://www.expertinstituteindia.in/superadmin/feepayment.php"><span class="isw-mail"></span>Today Due Payment</a></li>
                                <li><a href="http://www.expertinstituteindia.in/superadmin/feepayment.php"><span class="isw-sound"></span>All Due Payment</a></li>
                             <li><a href="http://www.expertinstituteindia.in/superadmin/feepayment.php"><span class="isw-print"></span>Billing</a></li>
                  <li><a href="http://www.expertinstituteindia.in/superadmin/studentRegistration.php"><span class="isw-cloud"></span>Admission Form</a></li>

                                      <li><a href="javascript:void(0);" id="admisDelete"><span class="isw-delete"></span> Delete Admission</a></li>
                                </ul>
                            </li>
                        </ul>                        <div class="clear"></div>
                    </div>
<div id="tabs">
<ul>
<div id="tabs-1" class="block-fluid table-sorting"><br>
 <div id='nrf'> 
 <?php 
    if(isset($admsCount[0]))
        echo '&nbsp;'.$admsCount[0]." Records.";
 ?>
 <?php if($flagEmpSrch){?>
 <style>
  .mrgRight{ margin-right:10px;}
 </style> 
 <span class="label label-success mrgRight" id="pDspStata" ><?php echo "Rs. ".$creditAmt[1]['creditAmt'];?> Total Credit </span> 
 <span class="label label-warning mrgRight" id="aDspStata" ><?php echo "Rs. ".$dueTotalAmt[1]['dueTotalAmt'];?> Total Due </span>
 <span class="label label-danger mrgRight" id="wDspStata"><?php echo "Rs. ".$totalAmt[1]['totalAmt'];?> Total Fees Package </span> 
 <?php } ?>

<!-- Fillter Start -->
  <form action="" method="post">
      <div>Select SMS : <select name="smsValue" id="smsValue">
                        <option value=""> Select SMS </option>
                        <?php if($smsArray[0] > 0){
                            array_shift($smsArray);
                            foreach($smsArray as $eachSMS){
                        ?>
                            <option value="<?php echo trim($eachSMS['sms_title']); ?>"> <?php echo trim($eachSMS['sms_title']); ?> </option>
                        <?php 
                            }
                        }else{ ?>
                            <option value=""> </option>
                        <?php } ?>
                    </select>  </div>
                     <i class="icon-calendar"></i>
                     <input type="text"  id="frmDat" name="ledsDat[]" style="width:70px; height:20px;" value="">
                     &nbsp; 
                     
                     To : &nbsp;
                     <input id="toDat" type="text" name="ledsDat[]" style="width:70px; height:20px;" value="">
  &nbsp;
  <select name="srchbranch[]" id="srchB"  style="height: 32px; color: rgb(0, 0, 0); font-weight: !important; width: 100px;" >
    <option value="">Branch</option>
    <?php foreach ($branch as $fetch)  {  ?>
    <option value="<?php echo $fetch['id']  ?>"><?php echo $fetch['branch_name'];   ?></option>
    <?php  } ?>
  </select>
  &nbsp;
  <select name="srchbranch[]" id="srchE"  style="height: 32px; color:#000; font-weight: !important; width: 100px;" >
    <option value="" selected>Employee</option>
  </select>
  &nbsp;
             <input id="" type="text" name="phone" style="width:100px; height:20px;" value="" placeholder="Phone Search">
             
&nbsp;
  &nbsp;
             <input id="" type="text" name="due_fee" style="width:100px; height:20px;" value="" placeholder="Due Fee">
             
&nbsp;
  <input type="submit" name="fltrBt" id="fltrBt" value="Go" style="height: 28px; width: 38px; margin-top: -11px;" class="button warning">
                   </form>


<!-- Fillter Start -->
           </div>
                  <div id='whereCnd' style="display:none;"><?php echo $where; ?></div>
<div id='nfp' style="display:none;" ><?php echo ceil($admsCount[0]/$nofr); ?></div>
 <div id='nextprev'></div>
<div class="clear"></div>


</div>
</div>
</div>





                    <div class="dr"><span></span></div>



                </div>



            </div>   



    </body>

</html>
<script>
$(document).ready(function(){
    $("#chkAll").click(function(){
         $(".checkDel").attr("checked",true);
     });
    $("#admisDelete").click(function(){ 
var x='';
       $(".checkDel:checked").each(function(){ 
            x=x+"+"+$(this).val();  
            });
         $.ajax({
              type: "POST",
              url: "../ajax/admission.php",
              data: { dataDel : x },
              success : function( data ){
                                 if(data.length){
                                    location.reload();
                                 }           
                    } 
            });
    }); 
 /* Paging Code*/


$("#nofr").change(function(){
	var slV=$(this).val();
	window.location.href='admissionDashboard.php?nfr='+slV;
	});

$(".next-page").click(function(event){
	    event.preventDefault();
		var whereCnd=$("#whereCnd").text();
		var nfpg=$("#nfp").text();
                var nfrpp=$("#nofr").val();
		var cPgN = parseInt($(".curnt-page").attr("id"));			

		if(cPgN < nfpg){
			$(".curnt-page").attr("id",cPgN+1);
			$(".curnt-page").text(cPgN+1);
				var nxtpg=cPgN+1;
			    $.post(
					'../ajax/getAdmTab.php'  ,
					{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},
						function(data)
							{
						// alert(data); 
							$("#dt-table").html(data);
							}
					);

			}

	});		

$(".prev-page").click(function(event){
	    event.preventDefault();
		var whereCnd=$("#whereCnd").text();
		var nfpg=$("#nfp").text();		
		var cPgP = parseInt($(".curnt-page").attr("id"));
		var nfrpp=$("#nofr").val();
		if(cPgP>1){	
		$(".curnt-page").attr("id",cPgP-1);
		$(".curnt-page").text(cPgP-1);
		var nxtpg=cPgP-1;
				    $.post(
							'../ajax/getAdmTab.php'  ,
							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},
						function(data)
							{
						 //alert(data); 
							$("#dt-table").html(data);
							}
					);
		}
	});	

$(".f-page").click(function(event){
		event.preventDefault();
		//alert("helloo"); 
		var whereCnd=$("#whereCnd").text();  
		var nfpg=$("#nfp").text();
		var cPgP = parseInt($(".curnt-page").attr("id"));
		var nfrpp=$("#nofr").val();
		$(".curnt-page").attr("id","1");
		$(".curnt-page").text("1");
		var nxtpg=1;
			   $.post(
							'../ajax/getAdmTab.php'  ,
							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},
						function(data)
							{
						//alert(data); 
							$("#dt-table").html(data);
							}
					);

	});	

$(".l-page").click(function(event){
		event.preventDefault();
		//alert("helloo"); 
		var whereCnd=$("#whereCnd").text();
		var nfpg=$("#nfp").text();		
		var cPgP = parseInt($(".curnt-page").attr("id"));
		var nfrpp=$("#nofr").val();
		$(".curnt-page").attr("id",nfpg);
		$(".curnt-page").text(nfpg);
		var nxtpg=nfpg;
				    $.post(
							'../ajax/getAdmTab.php'  ,
							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},
						function(data)
							{
						//alert(data); 
							$("#dt-table").html(data);
							}
					);

	});	

	

/* Paging Code*/

	 $("#srchB").change(function(){
		 		var brnchId = $(this).val();

				$.ajax({
					url:"../ajax/getKeyPerson.php",
					type:"POST",
					data:{id:brnchId},
					success: function( retuHt ){
							$("#srchE").html(retuHt);
						 }
				    });
		 });

      
	 $("#editBranch").change(function(){
		 		var brnchId = $(this).val();
				$.ajax({
					url:"../ajax/getKeyPerson.php",
					type:"POST",
					data:{id:brnchId},
					success: function( retuHt ){
							$(".editUserLst").html(retuHt);
						 }
				    });
		 });

$('select[name="smsValue"]').change(function(){
	var sms_title_txt = $(this).val();
	$.ajax({
        type:'POST',
        url :'../ajax/getSMSContent.php',
        data :{ 'sms_title' : sms_title_txt },
        success: function(smsContentTxt){            
        	//$("#smsContent").val($.trim(smsContentTxt));
        }

     });
});

	<?php if(strlen($where) > 0){ ?>

var currentdate = new Date();
    $.post(
            '../ajax/admissionSendSMS.php?_='+currentdate.getTime()  ,
            {"whereCnd":"<?php echo $where;?>","sms":"<?php echo trim($_POST['smsValue']);?>"},
        	function(data)
						{
						}
					);

<?php } ?>	 
       
});

</script>

<!-- Date Picker -->

<!--    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">-->

    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

            <script>

       $(function() {

       $( "#frmDat" ).datepicker();     

    $( "#frmDat" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	

       });

	   $(function() {

       $( "#toDat" ).datepicker();     

    $( "#toDat" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	

       });

  $(function() {

       $( "#editNxtDueDate" ).datepicker();     

    $( "#editNxtDueDate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	

       });

$(document).ready(function(){

     $(".ui-state-highlight").css("color","#fff");
/* Fee Details Popup */
$(".fdp_roll_no").click(function(){
     $("#editFrmContainer").css("display","none"); 
     $("#feeDetailsDiv").show("fast");
     var rollNo=$(this).text();
      var myObj=null; 
     $.ajax({
            url   : "../ajax/getFeeDetails.php",
            type: 'POST',
	    data:{'val':rollNo,'case':'roll_no'},
	    success: function(data){
                            myObj = jQuery.parseJSON(data);
                         /*  
                           var crdamt = parseInt(myObj.total_fee) - parseInt(myObj.due_fee);
                          $("#fdp_crAmt").text('Rs. ' + crdamt);
                          $("#fdp_totlFee").text('Rs. '+myObj.total_fee);
                          $("#fdp_dueAmt").text('Rs. '+myObj.due_fee);
                          $("#fdp_name").text(myObj.name);
                          $("#fdp_phone").val(myObj.phone);
                          $("#fdp_roll_no").val(myObj.roll_no);
                          $("#fdp_regno").val(myObj.regno);             
                          $("#fdp_a_id").val(myObj.a_id);
                          $("#fdp_fname").text(myObj.father_name);
                          $("#fdp_userAddr").text(myObj.permanent_address); 
                         */
                          $("#fdp_PayHistory").html(myObj.histryTable);
				}  
      });
    $("#editFromBox").fadeIn("slow");
 });
/* Fee Details Popup */
});

/* Edit Admission Popup */

$(document).ready(function(){ 
  $(".editAdmisLink").click(function(){
  $("#feeDetailsDiv").css("display","none");
     var regno = $(this).attr("id");
     //alert(regno);
     var admisDtlObj = null; 
     $.ajax({
	url:"../ajax/getAdmissionDetails.php",
	type:"POST",
	data:{ regno : regno},   
	success: function( returnJSONData ){
                //alert(returnJSONData);
                admisDtlObj = jQuery.parseJSON(returnJSONData);  
                  $("#editName").val(admisDtlObj.name);
		  $("#editFName").val(admisDtlObj.father_name);
		  $("#editPhoneno").val(admisDtlObj.phone);
		  $("#editEmailA").val(admisDtlObj.email_id);
                  
		  $("#editCourse").html(admisDtlObj.course);
		  $("#editFeePakg").val(admisDtlObj.course_fee);
                  $("#editTotalFee").val(admisDtlObj.total_fee);
		  $("#editDiscount").val(parseInt(admisDtlObj.course_fee) - parseInt(admisDtlObj.total_fee));
                  $("#editCrdAmt").val(parseInt(admisDtlObj.total_fee) - parseInt(admisDtlObj.due_fee));
		  $("#editTotalFee").val(admisDtlObj.total_fee);
		  $("#editDueAmt").val(admisDtlObj.due_fee);
		  $("#editNxtDueDate").val(admisDtlObj.nextduedate);
		  $("#editBranch").val(admisDtlObj.branch_name);
		  $("#editAdmissUser").html(admisDtlObj.emp_id);
		  $("#editLedUsr").html(admisDtlObj.lead_userId);
                  $("#regno").val(admisDtlObj.regno);
		 }
      });
     $("#editFromBox").fadeIn("slow");
  });
 $("#closeEditFRm").click(function(){
      $("#editFromBox").fadeOut("slow");
  });
 $("#editSave").click(function(){
    var editFrmData = $("#admissionEditFrm").serialize();
//alert(editFrmData);
    $.ajax({
        url : "../ajax/editAdmission.php",
        type:"POST",
	data: editFrmData,
        success : function( returnData ){
                  location.reload();
           }   
      });
 });
 $("#editCourse").change(function(){
              var cN ='';
          $(this).children( "option:selected").each(function(index,element){                    
                   cN =cN+$(this).val()+",";
           });

if(cN.length > 1){  
var feeDetail = null;    
//alert(cN);
       $.ajax({
               url : '../ajax/getCourseFee.php',
               type : 'POST',
               data : {course : cN },
               success : function(data){
  //                 alert(data);
  			feeDetail = jQuery.parseJSON(data); 
  			$("#editFeePakg").val(feeDetail.totalMainFee);
  			$("#editTotalFee").val(feeDetail.totalFee);
  			/*
                      $("#editFeePakg").val(data);                      	 
                         var newAmt =  parseInt( data ) - ( parseInt( $("#editTotalFee").val() )+ parseInt( $("#editDiscount").val() ) );
                        $("#editTotalFee").val( parseInt($("#editTotalFee").val()) + newAmt);       
                        */
                       },
              error : function(err){
                           alert(err);
                        }
                 });
       
           } 
   });
});
/* Edit Admission Popup */
       </script>

       <!-- Date Picker -->