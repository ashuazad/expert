<?php

require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/userPermissions.php';
require_once '../includes/db.php';

 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];

$managebranch = new managebranchDatabase();
$category = new categoryDatabase();
$db = new db();
$fetchid = $managebranch->fetchById($_GET['id']);
//var_dump($fetchid);
$fetchall = $category->fetchAll();
$permissions = new userPermissions($_GET['id']);
$officeList = $db->getData(array('id','name'),"office_details");
array_shift($officeList);
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
?>




<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>CRM- Admin Edit Branch Panel</title>

    <link rel="icon" type="image/ico" href="../favicon.ico"/>
    
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
    
    
    <script type='text/javascript' src='../js/cookies.js'></script>
    <script type='text/javascript' src='../js/actions.js'></script>
    <script type='text/javascript' src='../js/charts.js'></script>
    <script type='text/javascript' src='../js/plugins.js'></script>
    <script type='text/javascript' src='../js/style.js?<?php echo time();?>'></script>
    
</head>
<body>
    
    <div class="header">
        <a class="logo" href="index.html"><img src="../img/logo.png" alt="Aquarius -  responsive admin panel" title="Aquarius -  responsive admin panel"/></a>
        <ul class="header_menu">
            <li class="list_icon"><a href="#">&nbsp;</a></li>
        </ul>    
    </div>
    
    <div class="menu">                
        
        <div class="breadLine">            
            <div class="arrow"></div>
            <div class="adminControl active">
                Hi, Admin
            </div>
        </div>
        
        <div class="admin">
            <div class="image">
                <img src="../img/users/aqvatarius.jpg" class="img-polaroid"/>                
            </div>
            <ul class="control">                
                <li><span class="icon-comment"></span> <a href="../superadmin/querydetail.php">Messages</a></li>
                <li><span class="icon-cog"></span> <a href="forms.html">Settings</a></li>
                <li><span class="icon-share-alt"></span> <a href='../index.php?id=logout'>Logout</a></li>
            </ul>
            <div class="info">
                <span>Welcom back! Your last visit: 24.10.2012 in 19:55</span>
            </div>
        </div>
        
        <ul class="navigation">            
                <li>
                    <a href="dashboard.php">
                        <span class="isw-grid"></span><span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="category.php">
                        <span class="isw-grid"></span><span class="text">Category</span>
                    </a>
                </li>
               <li class="openable">
                    <a href="#">
                        <span class="isw-chat"></span><span class="text">Manage Branch & Employee</span>
                    </a>
                    <ul>
                                     <li>
                            <a href="managebranch.php">
                                <span class="icon-comment"></span><span class="text">Manage Branch</span></a>
</li>              

                    </ul>                


                </li>                        
                <li class="openable">
                    <a href="#">
                        <span class="isw-chat"></span><span class="text">Messages</span>
                    </a>
                    <ul>
                        <li>
                            <a href="querydetail.php">
                                <span class="icon-comment"></span><span class="text">Messages widgets</span></a>
                                                                                                                          
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
            
            <div class="wBlock">
                <div class="dSpace">
                    <h3>Last visits</h3>
                    <span class="number">6,302</span>                    
                    <span>5,774 <b>unique</b></span>
                    <span>3,512 <b>returning</b></span>
                </div>
                <div class="rSpace">
                    <h3>Today</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>                                                                                
                    <span>&nbsp;</span>
                    <span>65% <b>New</b></span>
                    <span>35% <b>Returning</b></span>
                </div>
            </div>
            
        </div>
        
    </div>
        
    <div class="content">
        
        
        <div class="breadLine">
            
            <ul class="breadcrumb">
                <li><a href="#">Admin</a> <span class="divider">></span></li>                
                <li class="active">Edit Branch Record</li>
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
                        <h1>Edit Branch Record</h1>  &nbsp;     
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                    <div style="display: show;" class="form">
                        <div class="row-fluid">
                
                <div class="span12">
                    
                    <div class="block-fluid">
                        <?php if($fetchid['role'] == 'branch'){    ?>
                        <div class="row-form">
                            <div class="span2">Branch Name:</div>
                            <div class="span3"><input type="text" id="branch_name" name="branch_name" value="<?php echo $fetchid['branch_name']; ?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <?php  }  ?>
                        <div class="row-form">
                            <div class="span2">First Name:</div>
                            <div class="span3"><input type="text" id="first_name" name="first_name" value="<?php echo $fetchid['first_name']; ?>"/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span2">Last Name:</div>
                            <div class="span3"><input type="text" id="last_name" name="last_name" value="<?php echo $fetchid['last_name']; ?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">E-mail:</div>
                            <div class="span3"><input value="<?php echo $fetchid['email_id']; ?>" class="validate[required,custom[email]]" type="text" name="email" id="email" />  <span>Example: someone@nowhere.com</span></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Phone number:</div>
                            <div class="span3"><input type="text" id="phone" name="phone" value="<?php echo $fetchid['phone_no']; ?>"/> <span>Example: 98 (765) 432-10-98</span></div>
                            <div class="clear"></div>
                        </div> 
                       <div class="row-form">
                            <div class="span2">Address:</div>
                            <div class="span6"><textarea name="textarea" id="address" name="address" ><?php echo $fetchid['address']; ?></textarea></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span2">City:</div>
                            <div class="span3"><input type="text" id="city" name="city" value="<?php echo $fetchid['city']; ?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Admission Form :</div>
                            <div class="span3">
                                   <select name="admission_frm_perm" id="admission_frm_perm">
                                          <option value="">-Select-</option>
 <option <?php if($fetchid['admission_frm_perm']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($fetchid['admission_frm_perm']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Admission Dashboard :</div>
                            <div class="span3">
                                   <select name="admission_dashboard_perm" id="admission_dashboard_perm">
                                          <option value="">-Select-</option>
 <option <?php if($fetchid['admission_dashboard_perm']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($fetchid['admission_dashboard_perm']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">All Admission :</div>
                            <div class="span3">
                                   <select name="all_admission_perm" id="all_admission_perm">
                                          <option value="">-Select-</option>
 <option <?php if($fetchid['all_admission_perm']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($fetchid['all_admission_perm']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Fees View Roll No On/Off:</div>
                            <div class="span3">                                        
                                <select name="fees_view_roll" id="fees_view_roll">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['fees_view_roll']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['fees_view_roll']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span2">Adm From Details On/Off:</div>
                            <div class="span3">                                        
                                <select name="adm_from_details_phone" id="adm_from_details_phone">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['adm_from_details_phone']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['adm_from_details_phone']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">All Due Fee On/Off:</div>
                            <div class="span3">                                        
                                <select name="all_due_fee_pem" id="all_due_fee_pem">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['all_due_fee_pem']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['all_due_fee_pem']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">All Fee Payment On/Off:</div>
                            <div class="span3">                                        
                                <select name="all_fee_pay_pem" id="all_fee_pay_pem">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['all_fee_pay_pem']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['all_fee_pay_pem']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Set Discount On/Off:</div>
                            <div class="span3">                                        
                                <select name="emp_set_discount" id="emp_set_discount">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['emp_set_discount']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['emp_set_discount']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Search Leads & Admissions:</div>
                            <div class="span3">                                        
                                <select name="serach_leads_adm" id="serach_leads_adm">
                                          <option value="">-Select-</option>
 <option <?php if($permissions->userPermission['search_leads_admissions']==="1")echo 'selected="selected"';?> value="1">Yes</option>
 <option <?php if($permissions->userPermission['search_leads_admissions']==="0")echo 'selected="selected"';?> value="0">No</option>
                                   </select>
                            </div>
                            <div class="clear"></div>
                        </div> 
                        <?php if($fetchid['category'] != ''){    ?>
                        <div class="row-form">
                            <div class="span2">Category:</div>
                            <div class="span3">        
                                <select name="category" id="category" class="validate[required]">
                                    <option value="">Choose a category</option>
                                    <?php                                   
                                    foreach ($fetchall as $fetch)  { 
                                        if($fetch['id'] == $fetchid['category']){
                                            $select = "selected";
                                        } else {
                                            $select = '';
                                        }
                                        ?>
                                       <option value="<?php echo $fetch['id']  ?>" <?php echo $select; ?>><?php echo $fetch['category_name'];   ?></option>                                   
                                    <?php  } ?>
                                    
                                </select>
                                <span>Required select field</span>
                            </div>
                            <div class="clear"></div>
                        </div> 
                        <?php   } ?>
                        <div class="row-form">
                            <div class="span2">Incentive:</div>
                            <div class="span3"><input type="text" id="insentive" name="insentive" value="<?php echo $fetchid['insentive'];?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Office Address:</div>
                            <div class="span3">
                                <select name="office_address" id="office_address">
                                    <option value="">-Select-</option>
                                    <?php foreach ($officeList as $eachOffice){
                                        $selectOpt = ($fetchid['office_address'] == $eachOffice['id']) ? 'selected="selected"' : '';
                                        echo '<option value="'.$eachOffice['id'].'" '.$selectOpt.'>'.$eachOffice['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Status:</div>
                            <div class="span3">
                                <select name="status" id="status">
                                    <option value="">-Select-</option>
                                    <option <?php if($fetchid['status']==="1")echo 'selected="selected"';?> value="1">Yes</option>
                                    <option <?php if($fetchid['status']==="0")echo 'selected="selected"';?> value="0">No</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Salary:</div>
                            <div class="span3"><input type="text" id="salary" name="salary" value="<?php echo $fetchid['salary'];?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Username:</div>
                            <div class="span3"><input type="text" id="username" name="username" value="<?php echo $fetchid['username']; ?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span2">Password:</div>
                            <div class="span3"><input type="password" id="password" name="password" value="<?php echo $fetchid['password']; ?>"/></div>
                            <div class="clear"></div>
                        </div>
                        <div><input type="hidden" class="action" value="update">
                            <input type="hidden" class="role" value="<?php echo $fetchid['role'];  ?>">
                            <input type="hidden" class="id" value="<?php echo $_GET['id'];  ?>"> 
                             <input type="hidden" class="branch_id" value="<?php echo $fetchid['branch_id'];  ?>">
                        </div>
                        <div style="margin-top: 5px;margin-bottom: 5px;margin-left: 300px;" class="submit_branch"><button>Update</button></div>                    
                        
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
