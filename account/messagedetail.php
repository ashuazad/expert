<?php
ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL);  
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/ip.php';

session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
if(!empty($_SESSION['uid'])){
   $id = $_SESSION['uid']; 
}else{
   $id = $_SESSION['id']; 
}
$_SESSION['ckid']=$id;
$hId = $id;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchObj = new managebranchDatabase();
$dbObj = new db();
if ($id == '1') {
    $fetchrecord = 'admin';
}
/*
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
*/
$leadDtl = $dbObj->getData(array("*"), "leads", "id='" . $_GET['id'] . "'");
$leadQuryDtl = $dbObj->getData(array("*"), "user_query", "lead_id='" . $_GET['id'] . "' AND pid=0");
if ($leadQuryDtl[0] > 0) {
    $getRecord = $userquery->getRecordById($leadQuryDtl[1]['id']);
}
$selectLead = "select * from leads where id='" . $_GET['id'] . "'";
$resultLead = mysql_fetch_assoc(mysql_query($selectLead));
?>

<!DOCTYPE html>

<html lang="en">

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">        

        

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />



       <title>EXPERT| Lead Management Software </title>

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
        <style type="text/css">

            #newMsgBx{position: relative;

                      height: 100%;

                      width: 100%;

                      z-index: 2000;

                      display: block;

            }

            .follwUpFrm{

                height: 32px;

                padding-bottom: 0;

                padding-top: 0;

            }
.subMsgBut{
    margin-left:23px;
    background-color:#000;
    color:#fff;
    width:130px;
   height:30px;
border:#000 1px solid;
  border-radius:5px;
}


        </style>





    </head>

    <body>



        <div class="header">

            <a class="logo" href="index.html"><img src="../img/logo.png" alt="Aquarius -  responsive admin panel" title="EXPERT"/></a>

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

                    <li><a href="#">Simple Admin</a> <span class="divider">></span></li>                

                    <li class="active">Followup</li>

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

                                    <div class="image"><a href="#"><img src="../img/users/aqvatarius.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Aqvatarius</a>                                    

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>



                                <div class="item">

                                    <div class="image"><a href="#"><img src="../img/users/olga.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Olga</a>                                

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                        



                                <div class="item">

                                    <div class="image"><a href="#"><img src="../img/users/alexey.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Alexey</a>  

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                              



                                <div class="item">

                                    <div class="image"><a href="#"><img src="../img/users/dmitry.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Dmitry</a>                                    

                                        <span>online</span>

                                    </div>

                                    <div class="clear"></div>

                                </div>                         



                                <div class="item">

                                    <div class="image"><a href="#"><img src="../img/users/helen.jpg" width="32"/></a></div>

                                    <div class="info">

                                        <a href="#" class="name">Helen</a>                                                                        

                                    </div>

                                    <div class="clear"></div>

                                </div>                                  



                                <div class="item">

                                    <div class="image"><a href="#"><img src="../img/users/alexander.jpg" width="32"/></a></div>

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



                           <div style="" class="form">

                                <div class="row-fluid">

                                    <div class="row-fluid">

                                        <div class="span6">

                                            <div class="head">

                                                <div class="isw-cloud"></div><span id="tbDl"><?php echo $_GET['tb']; ?></span>

          <h1>Lead Detail</h1> <h3>  <a href="#" class="save_details" style="color: #ffff00;display: none;">Save Details</a></h3> 
                                               <?php 
                                               if($resultLead['status'] == 'Complete' ){
                                               ?>
                                              <a href="#" class="btn btn-success" style="color: #ffff00;">Register</a>
                                               <?php 
                                                     }
                                                  ?>
                                                <ul class="buttons" style="z-index:2000;">                            
                                                 <li>
                                                      <a class="isw-settings" href="#"></a>
                                                      <ul class="dd-list">
                                                             <li><a href="javascript:void(0);" class="edit_details" ><span class="isw-edit"></span>Edit</a></li>                                    
                                 
                                                      </ul>
                                                  </li>
                                                </ul>
                                                <div class="clear"></div>

                                            </div>



                                            <div class="block-fluid">
                                                <input type="hidden" class="edit_id" value="<?php echo $resultLead['id']; ?>">                        
                                                <?php $getBrnchDtl = $branchObj->fetchById($getRecord['branch_id']); ?>

                                               
                                                <div class="row-form">

                                                    <div class="span3">Name :</div><div class="span5 show_name"><?php echo ucwords ( strtolower ($resultLead['name'])); ?></div>
                                                    <div class="span3 edit_name" style="display:none;"><input type="text" style="width:200px;"  value="<?php echo $resultLead['name']; ?>"></div>
                                                    <div class="clear"></div>

                                                </div>

                                                    <div class="row-form">

                                                    <div class="span3">Email Id :</div>

                                                    <div class="span3 show_email"><?php echo $resultLead['email']; ?></div>
                                                    <div class="span3 edit_email" style="display:none;"><input type="text" style="width:200px;"  value="<?php echo $resultLead['email']; ?>"></div>


                                                    <div class="clear"></div>   

                                                </div>

                                                <div class="row-form">

                                                    <div class="span3">Phone No :</div>

                                                    <div class="span3 show_phone"><?php echo $resultLead['phone']; ?></div>
                                                    <div class="span3 edit_phone" style="display:none;"><input type="text" style="width:200px;"  value="<?php echo $resultLead['phone']; ?>" readonly></div>

                                                    <div class="clear"></div>   

                                                </div>
 <div class="row-form">

                                                    <div class="span3">Course / Fees :</div>

                                                    <div class="span6 show_cat"> <?php echo str_replace("-"," ",$resultLead['category']); ?> </div>
                                                    <div class="span3 edit_cat" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['category']; ?>"></div>
                                                    
                                                    <div class="clear"></div>   

                                                </div>
                                                <div class="row-form">

                                                    <div class="span3">I.P. Address :</div>

                                                    <div class="span3 show_address"><?php echo $resultLead['ip']; ?></div>
                                                    <div class="span3 edit_ip" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['ip']; ?>"></div>
                                                    <div class="clear"></div>   

                                                </div>
                                                <div class="row-form">

                                                    <div class="span3">Mobile Location :</div>

                            <div class="span5 show_address mobLoc"><?php 
if(strlen($resultLead['phone']) == 10 ){
echo $resultLead['']."". getMobileState( $resultLead['phone'] )."";}else{
echo $resultLead['phone'];
} 

?> </div>
                                                    <div class="span3 edit_ip" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['address']; ?>"></div>
                                                    <div class="clear"></div>   

                                                </div>
                                                <div class="row-form">

                                                    <div class="span3">IP Location City:</div>

                                                    <div class="span5 show_address ipCity"><?php 
$cityName = 'None';                                 
$countryName = 'None';
$ipDtl = array();                                 
if(strlen($resultLead['ip']) >= 7 ){
$ipDtlStr = getIpLoc( $resultLead['ip'] );
$ipDtl = explode(',',$ipDtlStr);
    if(count($ipDtl)==2){
        $cityName = $ipDtl[0];
        $countryName = $ipDtl[1];
    }else{
        $countryName = $ipDtl[0];
    }
} 
echo $cityName;
?> </div>
                                                    <div class="span3 edit_ip" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['ip']; ?>"></div>
                                                    <div class="clear"></div>   

                                                </div>
<div class="row-form">

                                                    <div class="span3">IP Location Country:</div>

                                                    <div class="span5 show_address ipCntry"><?php 
                                            echo $countryName;
                                        ?></div>
                                                    <div class="span3 edit_ip" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['ip']; ?>"></div>
                                                    <div class="clear"></div>   

                                                </div>
                                                
                                                <div class="row-form">

                                                    <div class="span3">UPDATE Other Details :</div>

                                                    <div class="span3 show_address"><?php echo $resultLead['address']; ?></div>
                                                    <div class="span3 edit_address" style="display:none;"><input type="text" style="width:200px;" value="<?php echo $resultLead['address']; ?>"></div>
                                                    <div class="clear"></div> 



                                                </div>
                                            </div>

                                        </div>        

                                        <div class="span6" style="alignment-adjust: central;">

                                            <div class="head">

                                                <div class="isw-mail"></div>

                                                <h1>Follow Up Detail</h1>

<ul class="buttons">                            
                            <li>
                                <a class="isw-settings" href="#"></a>
                                <ul class="dd-list" style="z-index:2006;">
                                      <li><a href="index.php"><span class="isw-list"></span>All </a></li>
                                  <?php $statArray = $dbObj->getData(array("*"), "status");  array_shift($statArray); foreach( $statArray as $dtStat ){  ?><li><a href="querydetailFltSt.php?lftr=<?php echo $dtStat['status'];?>"><span class="isw-list"></span> <?php echo $dtStat['status'];?></a></li><?php  } ?>
                                 
                                </ul>
                            </li>
                        </ul>
                                                <div class="clear"></div>

                                            </div>



                                            <div  id="flowUpId" class="block messaging" style="height:400px; overflow:scroll;">

                                                <?php
#### Follow Up Check If Start ###
                                                if ($leadQuryDtl[0] > 0) {  
//echo $hId;
//print_r($getRecord);
                                                    ?> 
                                                   <?php  if($getRecord['emp_id']==$_SESSION['ckid']){ 
$queryFrwdHsty = mysql_query("SELECT *  FROM `leadfrwdhistory` WHERE `object_id` = ".$_GET['id']."  and object_type='LEAD' and nextId = ".$_SESSION['ckid']." order by frw_id desc limit 1 "); 
            $resultFrwdHsty = mysql_fetch_assoc($queryFrwdHsty);

              if(strtotime($resultFrwdHsty['frwDate']) < strtotime($getRecord['followup_date'])){ 
?>       

                                                    
<?php // echo $getRecord['firstname'] . ' ' . $getRecord['lastname']; ?>

        <div class="itemIn">
                           <a href="#" class="image"><img src="img/users/olga.jpg" class="img-polaroid"></a>     
                                <div class="text">
                                    
                                    <div class="info clearfix">
                                        <span class="name">Calling Date:-</span>
                                        <span class="date"><?php echo $getRecord['followup_date']; ?></span>
                                    </div>
                                    <div class="itemOut">
                                
                                <div class="text">
                                    <div class="info clearfix">
                                        <span class="name"></span>
                                        <span class="date"></span>
                                    </div>    <span class="label label-success"><?php echo $getRecord['message']; ?>.</span>                            
                                    
                                </div>
                                <span class="name">-</span>
                                 <div class="info clearfix">
                                        <span class="name">Next Calling Date  :-</span><span class="label label-success">#</span>
                                        <span class="date"><?php echo $getRecord['next_followup_date']; ?></span>
                                    </div>
                            </div>                                                
                                                                                                                                 
                                                        </div>

                                                    </div>
                                       <?php  } 

} ?>

                                                    <?php

                                                    function follow($followups = array()) {

                                                        $userquery = new userqueryDatabase();

                                                        foreach ($followups as $records) {
                                                  // echo "<br>".$_SESSION['id']."<br>";
                                                  //  print_r($records);
                                                      if($records['emp_id']==$_SESSION['ckid']){
            $queryFrwdHsty = mysql_query("SELECT *  FROM `leadfrwdhistory` WHERE `object_id` = ".$_GET['id']." and object_type='LEAD' and nextId = ".$_SESSION['ckid']." order by frw_id desc limit 1 "); 
            $resultFrwdHsty = mysql_fetch_assoc($queryFrwdHsty);

              if(strtotime($resultFrwdHsty['frwDate']) < strtotime($records['followup_date'])){                                        
                                                            ?>

                                                            <div class="info">
                                <address>
                                   
                                    <div class="itemIn">
                           <a href="#" class="image"><img src="img/users/olga.jpg" class="img-polaroid"></a>     
                                <div class="text">
                                    
                                    <div class="info clearfix">
                                        <span class="name">Calling Date:-</span>
                                        <span class="date"><?php echo $records['followup_date']; ?></span>
                                    </div>
                                    <div class="itemOut">
                                
                                <div class="text">
                                    <div class="info clearfix">
                                        <span class="name"></span>
                                        <span class="date"></span>
                                    </div>  
                                    <span class="label label-success"><?php echo $records['message']; ?>.</span>
                                    
                                </div>
                                <span class="name">-</span>
                                 <div class="info clearfix">
                                        <span class="name">Next Calling Date  :-</span><span class="label label-success">#</span>
                                        <span class="date"><?php echo $records['next_followup_date']; ?></span>
                                    </div>
                            </div> 
                                                                </div>
                                                            </div>
                                                        <?php 
                                                            }     
                                                          } ?>
                                                            <?php
                                                            $id = $records['id'];

                                                            echo "<input type=hidden class='last_id_1' value='" . $records['id'] . "' >";

                                                            if ($userquery->checkParentId($id) == '1') {

                                                                $data = $userquery->getChildData($records['id']);

                                                                if ($data) {

                                                                    follow($data);
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $checkChild = $userquery->checkParentId($getRecord['id']);

                                                    $followups = '';

                                                    if ($checkChild == '1') {

                                                        $followups = $userquery->getChildData($getRecord['id']);
                                                    }

                                                    $a = '';

                                                    if ($followups != '') {

                                                        follow($followups);
                                                    }









                                                    echo "<input type=hidden class='last_id' value='" . $getRecord['id'] . "' >";
                                                    ?>
                                                    <?php
                                                      
                                                }
                                                ###### Follow Up Check If End ######
                                                ?>     
                                             <!--   <button class="btn btn-warning" id="add_message_new">Add Message</button>  -->                                                                     

                                                <div id="newMsgBx" >

                                                    <div class="control">                                                        
                                                <label class="icon-chevron-down" id='getRemDrp'  style="display:none;" >  </label>  
                                                     <label class="isb-chat"  id='getRemTxt'> </label>       
                                             <b id="msgTxtBx">                                                                                                      
                                                     <?php 
                                                           $remarkArry =$dbObj->getData( array("*") , "remark" );
                                                           array_shift($remarkArry);
                                                       ?>
                                                       <select name="followRemk" id="followRemk" class="btn btn-inverse" placeholder="Remark" style="width: 220px; ">
                                                       <option value=''>Select Remark</option>
                                                       <?php foreach($remarkArry as $remk) {?>  
                                                                    <option value='<?php echo $remk['remark'];?>'><?php echo $remk['remark'];?></option>  
                                                       <?php } ?>
                                                      </select>
                                             </b>
                                                                         
                                                       <!-- <textarea name="followRemk" id="followRemk" class="messagedetail" rows='4' placeholder="Remark" style="width: 400px;"></textarea>-->
                                                      <!-- Status  -->
                                                            <input type="hidden" name="leadId" id="leadId" value="<?php echo $_GET['id']; ?>">    
                                                            <select name="status" id="status" class="btn btn-inverse" class="validate[required]" >

                                                                <option value="">Select status</option>
                                                                  <?php  
                                                                       $statusArry =$dbObj->getData( array("*") , "status" );
                                                           array_shift($statusArry);
                                                                        foreach( $statusArry as $statusDat){ ?><option value="<?php echo $statusDat['status']; ?>"><?php echo $statusDat['status']; ?></option><?php  }
                                                                   ?>
                         
                                                            </select>


                                                      <!-- Status  -->
                                                    </div>
                                                   <!--
                                                    <div class="row-form follwUpFrm">

                                                        <div class="span2" style="width:100px;">Status:</div>

                                                        <div class="span3">        

                                                            <input type="hidden" name="leadId" id="leadId" value="<?php echo $_GET['id']; ?>">    
                                                            <select name="status" id="status" class="status" class="validate[required]" >

                                                                <option value="">Choose a status</option>
                                                                  
                                                            </select>



                                                        </div>

                                                        <div class="clear"></div>

                                                    </div>
                                                 -->

 <tr> <tr>
                                              

                                                        <div class="span2" style="width:120px;">Next Calling Date:</div>

                                                        <div class="span4" style="margin-left:-5px;"><input value="" class="" type="text" name="dateNxt" id="dateFollupNxt" style="width:150px;"/> </div>
                                   <button class="subMsgBut" type="button" id="add_message_query" style="width:140px;">Submit</button>

                                                        <div class="clear"></div>            

                                                    </div>




                                                  <!--  <button class="btn btn-danger" id="add_message_cancle">Cancle</button>-->

                                                </div>



                                            </div>



                                        </div>

                                    </div>



                                </div>







                            </div>     





                        </div>





                        <div class="dr"><span></span></div>



                    </div>



                </div>   

<!-- Message Box-->

<!--Message Box-->
                </body>

                </html>

                <script>    
                    var sHgt = parseInt(document.getElementById("flowUpId").scrollHeight)-300;
                   console.log("Scrol height"+sHgt);
                    document.getElementById("flowUpId").scrollTop=sHgt;


                </script>

                <script>

                    //$(document).ready(function(){

                    $("#add_message_new").click(function(){

                        //alert("helooo");

                        $("#newMsgBx").css("display","block");

                        $(this).css("display","none");

                        var sHgt = document.getElementById("flowUpId").scrollHeight;

                        document.getElementById("flowUpId").scrollTop=sHgt;    

                    });        

                    $("#add_message_cancle").click(function(){

                        $("#newMsgBx").css("display","none");

                        $("#add_message_new").css("display","block");

                        var sHgt = document.getElementById("flowUpId").scrollHeight;

                        document.getElementById("flowUpId").scrollTop=sHgt;    

                    });

                    //});

                </script>

                <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

                <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

                <script>

                    $(function() {

                        $( "#dateFollup" ).datepicker();

                        $( "#dateFollup" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

    

                    });

                    $(function() {

                        $( "#dateFollupNxt" ).datepicker();

                        $( "#dateFollupNxt" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

    

                    });
$(document).ready(function(){

$("#getRemTxt").click(function(){
       $(this).css("display","none");   
       $("#msgTxtBx").load("../ajax/getRemTxt.php?opt=txt");    
       $("#getRemDrp").css("display","block");
    });
 $("#getRemDrp").click(function(){
       $("#msgTxtBx").load("../ajax/getRemTxt.php?opt=drp");    
       $(this).css("display","none");  
       $("#getRemTxt").css("display","block");
    });

});
                </script>
                <script>
           
                    $(document).ready(function(){
                        $('.edit_details').click(function(){
                            $('.show_name').hide();
                            $('.edit_name').show();
                            $('.show_cat').hide();
                            $('.edit_cat').show();
                            $('.show_email').hide();
                            $('.edit_email').show();
                            $('.show_phone').hide();
                            $('.edit_phone').show();
                            $('.show_address').hide();
                            $('.edit_address').show();
                            $('.edit_details').hide();
                            $('.save_details').show();
                        });
              
                        $('.save_details').live('click',function(){
                            var id = $('.edit_id').val();
                            var name = $.trim($('.edit_name input').val());
                            var cat = $.trim($('.edit_cat input').val());
                            var email = $.trim($('.edit_email input').val());
                            var phone = $.trim($('.edit_phone input').val());
                            var address = $.trim($('.edit_address input').val());
                            $.ajax({
                                type:"POST",
                                url:"../ajax/add.php",
                                data :{action:'update_lead',id:id,name:name,cat:cat,email:email,phone:phone,address:address},
                                success:function(){
                                    window.location.reload();
                                }
                            });
                 
                        });
                        var form = new FormData();
                        form.append("phone", "9311329865");
                        form.append("ip", "12.78.90.87");
                        form.append("id", "12345");

                        var settings = {
                            "url": "https://www.expertindia.in/ajax/getLocation.php",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "": "",
                                "Cookie": "PHPSESSID=af97nnrpfq9t1aajlp8s0vlpn5"
                            },
                            "processData": false,
                            "mimeType": "multipart/form-data",
                            "contentType": false,
                            "data": form
                        };

                        $.ajax(settings).done(function (response) {
                            console.log(response);
                        });
                    });
                </script>
