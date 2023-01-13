<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
date_default_timezone_set('Asia/Kolkata');
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
if(empty($_POST['sort'])){
$dtlArray=$dbObj->getData(array("*"),"visitIps"," 1=1 order by hittime desc  limit $startLmt,$nofr" ); 
}else{
$dtlArray=$dbObj->getData(array("*"),"visitIps"," 1=1 order by hits desc  limit $startLmt,$nofr" ); 
}
?>
  <table cellpadding="0" cellspacing="0" width="100%" class="table"  >
                            <thead>
                                <tr>
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>                                 
                                 	<th width="45%">IP Address</th>
                                    <th width="45%">Time</th>
                                    <th width="10%">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php    

                               array_shift($dtlArray);
                                foreach($dtlArray as $data){
                                
                                    ?>
                                <tr>
                                   <td style="" id="c_b"><input onClick="" name="ipDel[]" class="check" type="checkbox"  value="<?php echo $data['ip'];?>">  </td>
                                    <td ><?php echo $data['ip']?></td>
                                    <td  ><?php echo date('d-M-y h:i:s:A', (int)$data['hittime']); ?></td>
                                        <td  ><?php echo $data['hits']; ?></td>
                          </tr>
                               <?php                                
                                }
?>                            </tbody>                         
                        </table>