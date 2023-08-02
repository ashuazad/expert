<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/student.php';
require_once '../includes/userPermissions.php';
date_default_timezone_set("Asia/Kolkata");
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
$cnt=null;  
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$managebranch = new managebranchDatabase();
$userObj = new useraccountDatabase();
$dbObj = new db();
$studentObj = new student();
$permissions = new userPermissions($id);

$userDetailsArry = $userObj->getRecordById($user_id , true );
/*
if($id == '1'){
    $fetchrecord = 'admin';
}

if($fetchrecord != 'admin'){
    if($fetchrecord['role'] == 'employee'){ 
        header('Location: ' . constant('BASE_URL').'/account');
    exit;
    } else if($fetchrecord['role'] == 'branch'){
        header('Location: ' . constant('BASE_URL').'/branch');
    exit;
    } else {
        header('Location: ' . constant('BASE_URL'));
    exit;
    }
}
*/
if(count($_POST)>0){
$_POST['name']=$_POST['r_name'];
$_POST['doj']=date("Y-m-d H:i:s");
$_POST['emp_id']=$id;
$_POST['due_fee']=$_POST['total_fee']-$_POST['fst_fee'];
$_POST['credit_amt']=$_POST['fst_fee'];
$_POST['last_receipt_date'] = date('Y-m-d H:i:s');
$_POST['course']=implode("+",$_POST['course']);
$fst_fee=$_POST['fst_fee'];
$insertReptAry=array('amt'=>$fst_fee,'reg_no'=>$_POST['regno'],'recipt_date'=>$_POST['doj'],'payment_mode'=>$_POST['paymode'],'emp_id'=>$id,'dueamt'=>$_POST['due_fee'],'pid'=>0);
if(!empty($_POST['chequeno'])){
    $insertReptAry['cheque_no']=$_POST['chequeno'];
}
unset($_POST['r_name']);
unset($_POST['disAmt']);
unset($_POST['fst_fee']);
unset($_POST['register']);
unset($_POST['chequeno']);
unset($_POST['paymode']);
$_POST['roll_no']=$studentObj->nRolln;
extract($_POST);
if(empty($lead_id)){
$lead_id='NULL';
}
if(empty($lead_userId)){
$lead_userId=$user_id;
}

//Check Status
if($fst_fee > 2000){
	$amd_status = 'Admission';
}else{
	$amd_status = 'Registration';
}

$insertQuery="INSERT INTO admission( lead_id, father_name, email_id, phone, permanent_address, branch_name, regno, roll_no, course, course_fee, 
                      total_fee, next_due_date, credit_amt, last_receipt_date , name, doj, emp_id, due_fee ,lead_userId,status , password)
VALUES (
$lead_id , '$father_name', '$email_id', $phone, '$permanent_address', '$branch_name', '$regno', getNewRollNo(
), '$course', $course_fee, $total_fee, '$next_due_date','$credit_amt','$last_receipt_date', '$name', '$doj', '$emp_id', '$due_fee',$lead_userId,'$amd_status' , md5('$regno'))";
//echo $insertQuery;
$queryResult = mysql_query($insertQuery) or die(mysql_error());
if($queryResult){  //if($dbObj->dataInsert($_POST,'admission')){
  $insertReptAry['a_id'] = mysql_insert_id();
        if( $dbObj->dataInsert($insertReptAry,'fee_detail')){
                  $cnt=1;  
        header('Location: viewRegistration.php?aId=' . $insertReptAry['a_id'] . '&regno=' . $_POST['regno'] );  
                  exit;
               }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>EXPERT| Admission Form Dashboard Panel</title>
    <link rel="icon" href="../images/favicon.png" type="image/x-icon" />   
    <link href="../css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link href="../css/form.css" rel="stylesheet" type="text/css" />
     <link href="../css/style.css" rel="stylesheet" type="text/css" />
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
    <script type='text/javascript' src='../js/cookies.js'></script>
    <script type='text/javascript' src='../js/actions.js'></script>
    <script type='text/javascript' src='../js/charts.js'></script>
    <script type='text/javascript' src='../js/plugins.js'></script>
    <script type='text/javascript' src='../js/style.js'></script>
</head>
<body>
    <div class="header">
        <a class="logo" href="index.html"><img src="../img/logo.png" alt="Aquarius -  responsive admin panel" title="Aquarius -  responsive admin panel"/></a>
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
                <li><a href="#">Admin</a> <span class="divider">></span></li>                
                <li class="active">Manage Branch</li>
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
                        <div >  <div class="isw-grid"></div>
                        <h1>Admission Form</h1>  &nbsp;  
                        </div>                                  
                        <div class="clear"></div>
                    </div>
                    
                    <div  class="form">
                        <div class="row-fluid">
                
                <div class="span12">
<script src="jquery.easyWizard.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
   $('#myWizard').easyWizard();
});
</script>
<style type="text/css">

    .easyWizardSteps {list-style:none;width:100%;overflow:hidden;margin:0;padding:0;border-bottom:1px solid #ccc;margin-bottom:20px}
    .easyWizardSteps li {font-size:18px;display:inline-block;padding:10px;color:#B0B1B3;margin-right:20px;}
    .easyWizardSteps li span {font-size:24px}
    .easyWizardSteps li.current {color:#000}
    .easyWizardButtons {overflow:hidden;padding:10px;}
    .easyWizardButtons button, .easyWizardButtons .submit {cursor:pointer}
    .easyWizardButtons .prev {float:left}
    .easyWizardButtons .next, .easyWizardButtons .submit {float:right}
   .span6{padding:10px 30px; }
   .span4{padding:10px 30px;  font-size:17px;}
  .rowDivN{width:100%;height:40px;float:left;}
   .cols{width:10%;height:40px;float:left;padding:5px 30px; font-size:16px;}
   .colsTxt{width:200px;height:40px;float:left;padding:5px 25px;}
   #thrdBx span{background:none;}
   #thrdBx span input{ background:#FFF;opacity:1;}
</style>
                    <div class="block-fluid">                        
<!-- Form Start -->
<form id="myWizard" method="post" action="" class="form-horizontal" style="height:500px; background-color:rgb(22, 119, 173);">
	<section class="step" data-step-title="Student Infarmation">
<div style="width:60%">
 <div class="row-from clearFix"> 
    <div class="span6" >
          <input type="text" id="r_name" name="r_name" Placeholder="Name" required>
          <input type="hidden" id="lead_id" name="lead_id" value="" class='inpTxt'/>
          <input type="hidden" id="lead_userId" name="lead_userId" value="" class='inpTxt'/>
    </div>
    <div class="span6">
             <input type="text" id="father_name" placeholder="Father  Name" name="father_name" value="" class='inpTxt' required/>
    </div>
</div>
 <div class="row-from clearFix"> 
    <div class="span6">
    <input value="" class="validate[required,custom[email]]" type="text" name="email_id" id="email_id"  class='inpTxt' placeholder="Email ID" required/> </div>
<div class="span6"><input type="text" id="phone" name="phone" class='inpTxt' placeholder="Mobile No." required /></div>
</div>
 <div class="row-from clearFix"> 
   <div class="span6"><input type="text"  name="permanent_address" id="permanent_address"   class='inpTxt' placeholder="Address" required></div>
</div> 
</div>
	</section>
	<section class="step" data-step-title="Course Infarmation">
<div style="width:60%">
<!--
<div class="row-from clearFix"> 
        <div class="span6">
           <select ><option>-Employee-</option></select>
        </div>  
        <div class="span6"></div>  
</div>
-->
<div class="row-from clearFix"> 
    <div class="span6">     
    <select name='branch_name' id='branch_name' style="width:220px; height:30px; background-color:#fff; font-size:12px; font-weight:bold;" required class='inpTxt' placeholder="Branch">
   <option value='<?php echo $userDetailsArry['branch_id'];?>' select='selected'><?php echo $userDetailsArry['branch_name'];?></option>
                                               </select>
<input type="hidden" id="regno" name="regno" readonly value="" class='inpTxt' />
</div><div class="span6"><input type="text" id="roll_no" name="roll_no" value="<?php echo $studentObj->nRolln;?>"  placeholder="Roll No." readonly /></div>
  <div class="row-from clearFix"> 
    <div class="span6">
    <input type="text" id="course_fee" name="course_fee" readonly  value="" class='inpTxt' Placeholder="Course Packege"/>
    </div><div class="span6">  <input type='text'  id="disAmt" name="disAmt" Placeholder="Discount" readonly  > </div>
    </div> 
    <div class="row-from clearFix"> 
    <div class="span6">    
                    <select name='course[]' id="s2_2" style="width:220px; height:30px; background-color:#fff;font-size:12px; font-weight:bold;" class='inpTxt' multiple="multiple" Placeholder="Select Course" required>
                                                          
                                                          <?php 
                                                          $courseArry= $dbObj->getData(array('*') , "course_fee");
                                                          array_shift($courseArry);
                                                          foreach($courseArry as $courseDtl){
                                                          ?>               
                                                          <option value='<?php echo $courseDtl['course'];?>' ><?php echo str_replace("-"," ",$courseDtl['course']);?></option>
                                                          <?php } ?>
                   </select>                        
</div>
<div class="span6"><input type="text" id="total_fee" name="total_fee" value="" class='inpTxt' Placeholder="Total Fee" readonly  required/></div>
</div>

</div>
	</section>
	<section class="step" data-step-title="Payment Infarmation">
		<div class="row-from clearFix"> 
                       <div class="span4" id='dis-Name'>Name : Name</div><div class="span4" id='dis-Addr'>Address : Name</div><div class="span4" id='dis-email'>E Mail : Name</div>
		</div>
	<div class="row-from clearFix"> 
                       <div class="span4"><input type="text" id="fst_fee" name="fst_fee" placeholder="Receive Amount " value="" required />   
   <input type="hidden" id="dueAmt" name="dueAmt" disabled="disabled" value="" class='inpTxt'/>
                       </div>
                       <div class="span4" id='thrdBx'>Payment Mode : 
                            <input type="radio" id="Cash" name="paymode" value="Cash" required/>Cash 
                           <input type="radio" id="Cheque" name="paymode" value="Cheque" required  onclick="document.getElementById('chequeno').style.display='block'" />Cheque
                           <input type="text" id="chequeno" name="chequeno" value="" placeholder='Cheque No.'   style="width:260px;display:none;"  />                 
                      </div>
                      <div class="span4">
                            <input type="text" name="next_due_date"  id="next_due_date"  Placeholder="Next Due Date" required>
                      </div>
		</div>
	</section>
</form>
<!-- Form End  -->
                   </div>
                </div>
            </div>
                    </div>                 
                </div>                   
            </div>
<div class="dr"><span></span></div>        
        </div>       
    </div>       
</body>
</html>
<script>
                  $(function() {
                        $( "#next_due_date" ).datepicker();
                       $( "#next_due_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
                   });
$(document).ready(function(){
$("#Cash").css({"opacity":"1" , "margin":"1px"});
$("#Cheque").css({"opacity":"1" , "margin":"1px"});

$("#r_name").blur(function(){
$("#dis-Name").text("Name :  "+$("#r_name").val());
});
$("#permanent_address").blur(function(){
$("#dis-Addr").text("Address :  "+$("#permanent_address").val());
});
$("#email_id").blur(function(){
$("#dis-email").text("E-Mail :  "+$("#email_id").val());
});

$("#fst_fee").blur(function(){ 
         $("#dueAmt").val( $("#total_fee").val()-$(this).val() );
    });  
$("#disAmt").blur(function(){
     $("#total_fee").val( $("#course_fee").val() - $(this).val() );
});
$("input:radio").click(function(){

        $("#chequeno").css("display","block");
});
$("#Cash").click(function(){

        $("#chequeno").css("display","none");
});

   $("#s2_2").change(function(){
       var cN ='';
          $(this).children( "option:selected").each(function(index,element){                    
                   cN =cN+$(this).val()+",";
           });

if(cN.length > 1){        
var myObj = null;
//alert(cN);
       $.ajax({
               url : '../ajax/getCourseFee.php',
               type : 'POST',
               data : {course : cN },
               success : function(data){
               //console.log(data);
                  //alert(data);
  			myObj = jQuery.parseJSON(data);
                      $("#course_fee").val(myObj.totalMainFee);
                      $("#total_fee").val(myObj.totalFee);
             
                      $("#disAmt").val(myObj.disCountAmt);
                      
                       },
              error : function(err){
                           alert(err);
                        }
                 });
       
           } 
 
        });
// $("#branch_name").change(function(){
         var bN = $("#branch_name").val();
if(bN.length > 1){
        
       $.ajax({
               url : '../ajax/getRegNo.php',
               type : 'POST',
               data : {branch : bN },
               success : function(data){
                      $("#regno").val(data);
                       }
                 });
       
           } 
 
//        });
$("#phone").blur(function(){
              var phN = $(this).val();
//alert(phN);
if(phN.length > 1){
        
       $.ajax({
               url : '../ajax/getStudDetail.php',
               type : 'POST',
               data : {phone : phN },
               success : function(data){
                      var ledObj = jQuery.parseJSON(data);
                      $("#r_name").val(ledObj.name);                   
                      $("#email_id").val(ledObj.email);
                      $("#lead_id").val(ledObj.id);
                      $("#lead_userId").val(ledObj.emp_id);  
                      $("#dis-Name").text("Name :  " + ledObj.name);
                      $("#dis-email").text("E-Mail :  " + ledObj.email);
                       }
                 });
       
           } 

   });
$(".row-form").css("background-color","#fff");
$(".row-form").css("border","0px");
<?php if($permissions->userPermission['emp_set_discount']){?>
$("#disAmt").removeAttr('readonly');
$("#total_fee").removeAttr('readonly');
<?php }?>
});

</script>
