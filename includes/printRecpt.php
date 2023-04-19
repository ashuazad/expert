<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/student.php';
date_default_timezone_set("Asia/Kolkata");
session_start();
if(isset($_GET['f_id']))
{
$f_id=$_GET['f_id'];
$adminAry = recptDtl( $f_id );
$studFeeArry =$adminAry [0];
$studRegDtl = $adminAry[1] ;
}
?>
<html>
<hea>
<style type="text/css">
.receptHead {
	color: #F00;
	font-size: 20px;
	margin:0px;
	padding:0px;
}
.addrHdOf {
	font-size: 18px;
}
</style>

</head>
<body>
<div style="width:64%; height:411px; border:#F00 2px solid; border-radius:10px; margin:auto;padding:4px;">
<table width="100%" height="370" align="center" cellspacing="0" cellpadding="0" border="0" style="">
  <tr>
      <td style="margin: 0px; padding: 0px; height: 173px;" colspan="6" align="center"><img src="http://www.expertlaptopinstitute.com/wp-content/uploads/2014/02/logo2.gif" alt=""  style="height: 72px;">
          <h2 class="receptHead">EXPERT INSTITUTE OF ADVANCE TECHNOLOGIES PVT. LTD.</h2>
            <p align="center" style=" margin:0px;"><b>Head Office:</b><br>
              <span class="addrHdOf">2453,Hudson Line, Kingsway Camp, Delhi-9 Ph : 011-47814776</span><br>
<b>Franchise Off. : </b>2439/40, Gali Munde Wali, Sadar Bazar, Delhi-6 
           </p>
           
     </td>
  </tr>
  <tr><td colspan="4" style="height:1px; width:100%; background-color:#F00" ></td></tr>
  <tr>
    <td width="27%"><strong>Recept No : </strong></td>
    <td width="23%"><?php echo $studFeeArry['f_id'];?></td>
    <td width="23%"><strong>Date: </strong></td>
    <td width="27%"><?php echo $studFeeArry['recipt_date'];?></td>
  </tr>
  <tr>
    <td><strong>Name : </strong></td>
    <td><?php echo $studRegDtl['name'];?></td>
    <td><strong>Course:</strong></td>
    <td><?php echo str_replace("+","<br>",str_replace("-"," ",$studRegDtl['course']) );?></td>
  </tr>
  <tr>
    <td><strong>Course Fee : </strong></td>
    <td> Rs. <?php echo $studRegDtl['total_fee'];?></td>
    <td><strong>Reg No : </strong></td>
    <td><?php echo $studFeeArry['reg_no'];?></td>
  </tr>
  <tr>
    <td><strong>Recived Amount :  </strong></td>
    <td>Rs. <?php echo $studFeeArry['amt'];?></td>
    <td><strong>Due Amount : </strong></td>
    <td>Rs. <?php echo $studRegDtl['due_fee'];?></td>
  </tr>
  <tr>
    <td><strong>Next Due Date : </strong></td>
    <td><?php echo $studRegDtl['next_due_date'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
</table>
</div>
<div style='margin:auto; width:100px; height:40px;'><input type="submit" name="button" id="button" value="Print" onClick="window.print();"></div>
</body>
</html>