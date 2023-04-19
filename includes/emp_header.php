<?php 
if(!$_SESSION['OTP_C']){
    
echo "<script>location.href='../index.php?id=logout'</script>";
}
//var_dump($_SESSION['user_permission']);
?>  

<div class="menu">                

            <div class="breadLine">            
                <div class="arrow"></div>
                <div class="adminControl active">
                    Hi, <?php echo $fetchrecord['first_name'].' '.$fetchrecord['last_name'];   ?>
                </div>
            </div>

            <div class="admin">
                <div class="image">
                    <img src="../img/users/aqvatarius.jpg" class="img-polaroid"/>                
                </div>
                <ul class="control">                
                    <li><span class="icon-comment"></span> <a href="../accounts/querydetail.php">Messages</a></li>
                    <li><span class="icon-cog"></span> <a href="dashboard.php">Settings</a></li>
<?php 
if(!empty($_SESSION['uid'])){
?>
                   <li><span class="icon-share-alt"></span> <a href='<?php echo constant("BASE_URL");  ?>/superadmin/dashboard.php'>Admin Dashboard</a></li>
<?php }else{?>
             <li><span class="icon-share-alt"></span> <a href='<?php echo constant("BASE_URL");  ?>/account/resetpassword.php'>Reset Password</a></li>
                    <li><span class="icon-share-alt"></span> <a href='../index.php?id=logout'>Logout</a></li>
<?php 
}
?>
                </ul>
                <div class="info">
                    <span>Your last visit: <?php
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
echo date('H:i');
?> in <?php echo date ('d-M-Y') ?> </span>
                </div>
            </div>

            <ul class="navigation">            
                <li class="active">
                    <a href="<?php echo constant('BASE_URL'); ?>/account">
                        <span class="isw-grid"></span><span class="text">Dashboard</span>
                    </a>
                </li>
                
                <li class="openable">
                    <a href="#">
                        <span class="isw-ok"></span><span class="text">Admissions</span>
                    </a>
                    <ul>
          <?php if($_SESSION['user_details']['admission_dashboard_perm']==1){ ?>                          
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/admissionDashboard.php">
                                <span class="icon-th"></span><span class="text">Admission Dashboard</span></a>
                       </li>  
          <?php } ?>
          <?php if($_SESSION['user_details']['admission_frm_perm']==1){ ?>                          
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/studentRegistration.php">
                                <span class="icon-pencil"></span><span class="text">Admission Form</span></a>
                       </li>  
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/feepayment.php">
                                <span class="icon-eye-open"></span><span class="text">Bill Payment Receipt</span></a>
                       </li>      
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/feeHistory.php">
                                <span class="icon-comment"></span><span class="text">Payment Receipt History</span></a>
                       </li>
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/Duefee.php">
                                <span class="icon-comment"></span><span class="text">Due Fee </span></a>
                       </li>                                  
       <?php } ?>
            <?php if($_SESSION['user_details']['all_due_fee_pem'] == 1){ ?>
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/Duefee_All.php">
                                <span class="icon-comment"></span><span class="text">All Due Fee </span></a>
                       </li>
            <?php } ?>
            <?php if($_SESSION['user_permission']['all_fee_pay_pem'] == 1){ ?>
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/feepayment_All.php">
                                <span class="icon-comment"></span><span class="text">All Fee Payment</span></a>
                       </li>
            <?php } ?>
             <?php if($_SESSION['user_details']['all_admission_perm'] == 1){ ?>
                       <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/account/admissionDashboard_All.php">
                                <span class="icon-comment"></span><span class="text">All Admission </span></a>
                       </li>
            <?php } ?>  
                    </ul> 
                  </li>
          
                     <li class="openable">
                    <a href="#">
                        <span class="isw-mail"></span><span class="text">Incentive Dashboard</span>
                    </a>
                    <ul>
                        <li>
                            <a href="insentive.php">
                                <span class="icon-comment"></span><span class="text">Incentive Dashboard </span></a>
                       </li>            
                                          
                    </ul>        
                            
 </li>

      <li >
                    <a href="<?php echo constant("BASE_URL");  ?>/account/history.php?lftr=2&sn=Monthly">
                        <span class="isw-grid"></span><span class="text">New Lead Monthly History</span>
                    </a>
                </li>
                 <li >
                    <a id="addN" href="javascript:void(0);">
                        <span class="isw-plus"></span><span class="text">Add New Lead</span>
                    </a>
                </li>
                                   
          </ul>

            <div class="dr"><span></span></div>

            <div class="widget-fluid">
                <div id="menuDatepicker"></div>
            </div>

            <div class="dr"><span></span></div>

            <div class="widget">

                <div class="input-append">
                    <input id="appendedInputButton" style="width: 118px;" type="text"><button class="btn" type="button">Search</button>
                </div>            

            </div>

            <div class="dr"><span></span></div>

            

        </div>