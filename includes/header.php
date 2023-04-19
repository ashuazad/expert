<?php
/*
if($_SERVER['REMOTE_ADDR'] == '14.141.149.50'){

echo "<script>location.href='../index.php?id=logout'</script>";
 
}
*/
?>  
<?php 
if(!$_SESSION['OTP_C']){
    
echo "<script>location.href='../index.php?id=logout'</script>";
}

?>  
      <div class="menu">                

            <div class="breadLine">            
                <div class="arrow"></div>
                <div class="adminControl active">
                    Hi, Admin
                </div>
            </div>

            <div class="admin">
                <div class="image">
                    <img src="../img/users/aqvatarius1.png" class="img-polaroid"/>                
                </div>
                <ul class="control">                
                    
<li><span class="icon-comment"></span> <a href="../superadmin/dashboard.php">Messages</a> <a href="../superadmin/" class="caption red">12</a></li>
                    <li><span class="icon-cog"></span> <a href="../superadmin/managebranch.php">Settings</a></li>
                    <li><span class="icon-share-alt"></span> <a href='../index.php?id=logout'>Logout</a></li>
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
                    <a href="dashboard.php">
                        <span class="isw-grid"></span><span class="text">Dashboard</span>
                    </a>
                                  
                <li class="openable">
                    <a href="dashboard.php">
                        <span class="isw-zoom"></span><span class="text">Leads</span>
                    </a>
                    <ul>
                       <li>
                            <a href="queryStatus.php">
                                <span class="icon-th"></span><span class="text">Dashboard Lead Status</span></a>                                                                                                                          
                        </li>     
                        <li>
                            <a href="accountsLeadStatus.php">
                                <span class="icon-th"></span><span class="text">Dashboard User Status</span></a>                                                                                                                          
                        </li>   
                        <li>
                            <a href="querydetail.php">
                                <span class="icon-search"></span><span class="text">All Lead Show</span></a>
                                 </li>                                       
                       <li>
                            <a href="querydetailIVR.php">
                                <span class="icon-search"></span><span class="text">IVR Calls Lead Show</span></a>
                                 </li> 
                                 <li>
                            <a href="https://www.advanceinstitute.co.in/ajax/addIVRLead.php">
                                <span class="icon-search"></span><span class="text">IVR Calls Add Data</span></a>
                                 </li> 
                        <li>
                            <a href="javascript:void(0);" id="move">
                                <span class="icon-share"></span><span class="text">Send Lead</span></a>                                  
                        </li>
                        
                        <li>
                            <a href="javascript:void(0);" id="addN">
                                <span class="icon-envelope"></span><span class="text">Add New Lead</span></a>                                  
                        </li>   
                        <li>
                            <a href="javascript:void(0);" id="del">
                                <span class="icon-trash"></span><span class="text">Delete</span></a>                                  
                        </li>
                                                        
                    </ul>                


                </li>
           <li class="openable">
                    <a href="">
                        <span class="isw-ok"></span><span class="text">Admissions</span>
                    </a>
                    <ul>
                        <li>
                            <a href="admissionDashboard.php">
                                <span class="icon-th"></span><span class="text">Dashboard Admissions</span></a>                                                                                                                          
                        </li> 
                        <li>
                            <a href="manageInsentive.php">
                                <span class="icon-th"></span><span class="text"> 
                                 Dashboard Incentive </span></a>                                                                                                                          
                        </li> 
                        <li>
                            <a href="manageSalary.php">
                                <span class="icon-th"></span><span class="text"> 
                                 Dashboard Salary </span></a>                                                                                                                          
                        </li> 
                        <li>
                            <a href="onlineadmission.php">
                                <span class="icon-search"></span><span class="text">PayU Admissions</span></a>                                  
                        </li>   
                        <li>
                            <a href="studentRegistration.php">
                                <span class="icon-pencil"></span><span class="text">Admission Form</span></a>                                                                                                                          
                        </li>
                        <li>
                            <a href="feepayment.php">
                                <span class="icon-eye-open"></span><span class="text">Bill Payment Receipt </span></a>                                                                                                                          
                        </li>
                        
                       <li>
                            <a href="feeHistory.php">
                                <span class="icon-eye-open"></span><span class="text"> Receipt History </span></a>                                                                                                                          
                        </li>    
                        
                        <li>
                            <a href="Duefee.php">
                                <span class="icon-eye-open"></span><span class="text">Due Fees Student</span></a>                                                                                                                          
                        </li>                                     
                                                                                
                    </ul>                


                </li>
                <li class="openable">
                    <a href="dashboard.php">
                        <span class="isw-cloud"></span><span class="text">Login IP Address</span>
                    </a>
                    <ul>
                        <li>
                            <a href="userlogs.php" id="move">
                                <span class="icon-th"></span><span class="text">Dashboard User ID Login</span></a>                                                                                                                          
                        </li>
                                             
                                 <li>
                            <a href="studentlog.php" id="move">
                                <span class="icon-th"></span><span class="text">Dashboard Student ID Login</span></a>                                                                                                                          
                        </li>      
                        <li>
                            <a href="visitIps.php">
                                <span class="icon-search"></span><span class="text">Google ads clicks</span></a>
                                 </li>                                       
                        
                         
                    </ul>                
                    <li class="openable">
                    <a href="dashboard.php">
                        <span class="isw-pin"></span><span class="text">Student Panel</span>
                    </a>
                    <ul>
                        <li>
                            <a href="jobCategory.php">
                                <span class="icon-th"></span><span class="text">Dashboard jobs Manage</span></a>                                                                                                                          
                        </li>
                        <li>
                            <a href="jobSeeker.php">
                                <span class="icon-th"></span><span class="text">Dashboard jobs Details</span></a>                                                                                                                          
                        </li>
                        <li>
                            <a href="software.php">
                                <span class="icon-search"></span><span class="text">Upload Software</span></a>
                                 </li>                                       
                        <li>
                            <a href="video.php">
                                <span class="icon-share"></span><span class="text">Upload Video</span></a>                                                                                                                          
                        </li>
                        <li>
                            <a href="jobs.php">
                                <span class="icon-share"></span><span class="text">Job placement</span></a>                                                                                                                          
                        </li>
                        
                        <li>
                            <a href="billing_data.php">
                                <span class="icon-share"></span><span class="text">GST Bill</span></a>                                                                                                                          
                        </li>
                                                         
                    </ul>                


                </li>
                
                   <li class="openable">
                    <a href="">
                        <span class="isw-target"></span><span class="text">Video Ordering</span>
                    </a>
                      <ul>
               <?php  $resultCatagory = mysql_query("select * from course_fee");  
                while( $dataCatagory = mysql_fetch_array($resultCatagory) ){   
                ?>   
                          <li>
                  <a href="videoShortCatagory.php?course=<?php echo $dataCatagory['course'];?>">
      <span class="icon-envelope"></span><span class="text"><?php echo str_replace("-"," ",$dataCatagory['course']);?></span></a>                                                                                                                          
                        </li>                                     
                 <?php } ?>
                     </ul>
                </li>
                <li class="openable">
                    <a href="">
                        <span class="isw-sync"></span><span class="text">Course Module</span>
                    </a>
                      <ul>
               <?php  $resultCatagory = mysql_query("select * from course_fee");  
                while( $dataCatagory = mysql_fetch_array($resultCatagory) ){   
                ?>   
                          <li>
                  <a href="coursemodules.php?course=<?php echo $dataCatagory['course'];?>">
      <span class="icon-envelope"></span><span class="text"><?php echo str_replace("-"," ",$dataCatagory['course']);?></span></a>                                                                                                                          
                        </li>                                     
                 <?php } ?>
                     </ul>
                </li>
               <li class="openable">
                    <a href="">
                        <span class="isw-settings"></span><span class="text">Software Module</span>
                    </a>
                      <ul>
               <?php  $resultCatagory = mysql_query("select * from course_fee");  
                while( $dataCatagory = mysql_fetch_array($resultCatagory) ){   
                ?>   
                          <li>
                  <a href="softwarmodules.php?course=<?php echo $dataCatagory['course'];?>">
      <span class="icon-envelope"></span><span class="text"><?php echo str_replace("-"," ",$dataCatagory['course']);?></span></a>                                                                                                                          
                        </li>                                     
                 <?php } ?>
                     </ul>
                </li>                     
      		
	  	         
<li class="active">
                    <a href="expenses.php">
                        <span class="isw-bookmark"></span><span class="text">Expert Expenses</span>
                    </a>
                                  
                </li>
<li class="openable">
                    <a href="dashboard.php">
                        <span class="isw-cloud"></span><span class="text">Manage Account</span>
                    </a>
                    <ul>
                     	<li>
                            <a href="managebranch.php">
                                <span class="icon-th"></span><span class="text">Dashboard Manage Branch </span></a>
                                 </li>   
                                 <li>
                            <a href="manageOfficeAddress.php">
                                <span class="icon-th"></span><span class="text"> Dashboard Logo Manage </span></a>
                                 </li>   
                                 
                                 <li>
                            <a href="discount.php">
                                <span class="icon-th"></span><span class="text">Dashboard Discount Rules </span></a>
                                 </li>
                                                            
                        <li>
                            <a href="course.php" id="move">
                                <span class="icon-th"></span><span class="text">Dashboard Course Fees</span></a>                                                                                                                          
                        </li>
                       <li>
                            <a href="remark.php" id="move">
                                <span class="icon-share"></span><span class="text">Manage Calling Remarks</span></a>                                                                                                                          
                        </li>
                    <li>
                            <a href="dueFeeFollowupRemark.php" id="move">
                                <span class="icon-share"></span><span class="text">Manage Due Fees Remarks</span></a>                                                                                                                          
                        </li>
                         <li>
                            <a href="manageSMSApi.php" id="move">
                                <span class="icon-th"></span><span class="text">Manage SMS API</span></a>                                                                                                                          
                        </li>
                      <li>
                            <a href="manageSMS.php" id="move">
                                <span class="icon-share"></span><span class="text">Manage SMS Temlate</span></a>                                                                                                                          
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/sendSMS.php" id="move">
                                <span class="icon-envelope"></span><span class="text">SMS Send</span></a>                                                                                                              
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/admissionSMS.php" id="move">
                                <span class="icon-envelope"></span><span class="text">SMS Send Admission </span></a>                                                                                                              
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/manageEmailSettings.php" id="move">
                                <span class="icon-th"></span><span class="text">Manage Email ID API</span></a>                                                                                                              
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/manageTemplate.php" id="move">
                                <span class="icon-envelope"></span><span class="text">Manage Email Temlate</span></a>                                                                                                              
                        </li>
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/sendEmail.php" id="move">
                                <span class="icon-envelope"></span><span class="text">Email Send</span></a>                                                                                                              
                        </li>
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/admissionSendEmail.php" id="move">
                                <span class="icon-envelope"></span><span class="text">Email Send Admission</span></a>                                                                                                              
                        </li>
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/manageSMSNewLead.php" id="move">
                                <span class="icon-th"></span><span class="text">Auto SMS Manage New Lead</span></a>                                                                                                              
                        </li>
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/manageLeadEmail.php" id="move">
                                <span class="icon-th"></span><span class="text">Auto Email Manage New Lead.php</span></a>                                                                                                              
                        </li>
                        
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/manageWhatsup.php" id="move">
                                <span class="icon-th"></span><span class="text">Auto Whatsapp Manage New Lead.php</span></a>                                                                                                              
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/editWhatsupRemark.php" id="move">
                                <span class="icon-envelope"></span><span class="text">Whatsapp edit Remark New Lead.php</span></a>                                                                                                              
                        </li>
                        <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/addWhatsupRemark.php" id="move">
                                <span class="icon-envelope"></span><span class="text">addWhatsupRemark New Lead.php</span></a>                                                                                                              
                        </li>
                         <li>
                            <a href="import_lead.php" id="move">
                                <span class="icon-share"></span><span class="text">Upload CSV Lead</span></a>                                                                                                                          
                        </li>
                         <li>
                            <a href="<?php echo constant("BASE_URL");  ?>/superadmin/managePaymentModel.php" id="move">
                                <span class="icon-th"></span><span class="text">Student Panel Manage</span></a>                                                                                                              
                        </li>
                                                         
                    </ul>                
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

            <div class="widget-fluid">

                
<div class="wBlock gray clearfix">
                    <div style="background:#333333" class="dSpace">
                        <h3>Total Leads</h3>
                        <span class="number"><?php $ldCunt=$dbObj->getData(array("count(*) as noflead ") , "leads"); echo $ldCunt[1]['noflead']; ?></span>                    
                     </div>
                    <div class="rSpace">
                        <?php
                            foreach($userquery->leadStat() as $lstD){
                        ?>
                         <span><?php echo $lstD[1];?> <b><?php echo $lstD[0];?></b></span>
                       <?php }?>
                    </div>
                </div>
            </div>

        </div>

