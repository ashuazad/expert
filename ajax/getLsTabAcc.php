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
//echo "hello";
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
//print_r($_POST);
$getAllStatusLeads = $userquery->allStatusLead($user_id , $startLmt , $nofr);
?>
<table cellpadding="0" cellspacing="0" width="100%" class="table" style="padding:5px;">
                            <thead>
                                <tr>                                    
                                    <th width="15%">Name</th>
                                    <th width="10%">Phone</th>
                                    <th width="12%">E-mail</th>
                                    <th width="20%">Remark</th>
                                    <th width="8%">Status</th>
                                    <th width="20%">Calling Date</th>     
                                    <th width="15%">Next Calling Date</th>                                
                                </tr>
                            </thead>
                           
                                <tbody>                                    
                            <?php 

                             foreach($getAllStatusLeads as $fdatas) { 
                              ?> 
                          <tr>
                            <td><a href="<?php echo constant("BASE_URL"); ?>/account/messagedetail.php?id=<?php echo $fdatas[0];?>"><?php echo $fdatas[1]; ?></a></td>
                            <td><?php echo hidePhone($fdatas[4]); ?></td>
                            <td><?php echo $fdatas[2]; ?></td>                  
                            <td><?php echo $fdatas[6]; ?></td>
                            <td><?php echo $fdatas[5]; ?></td>
                            <td><?php echo $fdatas[8]; ?></td>
                            <td><?php echo $fdatas[9]; ?></td>
                          </tr> 
                       <?php  }  ?>
                         </tbody>                             
                        </table>
