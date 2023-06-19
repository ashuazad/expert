<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/student.php';
date_default_timezone_set("Asia/Kolkata");
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$cnt=null;  
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$managebranch = new managebranchDatabase();
$dbObj = new db();
$studentObj = new student();
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

if(isset($_POST['del'])){
  for($i=0;$i<count($_POST['chk']);$i++){
$dbObj->delOne('course_fee','course',$_POST['chk'][$i]);
   }
}
$datas=$dbObj->getData(array("*"),'course_fee');
?>
<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>CRM- Admin Manage Branch Panel</title>
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
require_once '../includes/header.php';
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
                        <h1>Course Fee</h1>  &nbsp;  
                        </div>                                  
                        <div class="clear"></div>
                    </div>
                    
                    <div  class="form">
                        <div class="row-fluid">
                
                <div class="span12">
<style type="text/css">
.inpTxt{color:#000; font-size:13px;}
</style>
            <form action="" method='post' id="studRegFrm">
                    <div class="block-fluid">                        
                    <table class="table" >
                        
                        <tr><th><input type="submit" class="btn btn-danger" name="del" value="Delete" /></th><th>Course</th><th>Full Name</th><th>Fee</th><th><a href="addcourse.php" class='btn btn-success'>Add New</a></th></tr>
                        <?php 
                          array_shift($datas);
                         foreach($datas as $data){ ?>
                            <tr><td><input type="checkbox" name="chk[]" value="<?php echo $data['course']; ?>" /></td><td><?php echo str_replace("-"," ",$data['course']); ?></td><td><?php echo $data['course']; ?></td><td><?php echo $data['fee']; ?></td><td><a class='btn btn-warning' href="addcourse.php?id=<?php echo $data['course']; ?>">Edit</a></td></tr>
                        <?php } ?>
                    </table>
           
                                 
                    </div>
           </form>
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
$("#fst_fee").blur(function(){ 
         $("#dueAmt").val( $("#total_fee").val()-$(this).val() );
    });  
$("#Cheque").click(function(){
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
//alert(cN);
       $.ajax({
               url : '../ajax/getCourseFee.php',
               type : 'POST',
               data : {course : cN },
               success : function(data){
  //                 alert(data);
                      $("#course_fee").val(data);
                       },
              error : function(err){
                           alert(err);
                        }
                 });
       
           } 
 
        });
$("#branch_name").change(function(){
         var bN = $(this).val();
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
 
        });
$("#phone").blur(function(){
              var phN = $(this).val();
//alert(phN);
if(phN.length > 1){
        
       $.ajax({
               url : '../ajax/getStudDetail.php',
               type : 'POST',
               data : {phone : phN },
               success : function(data){
                  //    alert(data);
                       var lDat=data.split("-");
                if(lDat[0].lenght>3){
                      $("#name").val(lDat[0]);
                   }
                      $("#email").val(lDat[2]);
                      $("#lead_id").val(lDat[4]);
                       }
                 });
       
           } 

   });
$(".row-form").css("background-color","#fff");
$(".row-form").css("border","0px");
});
</script>

