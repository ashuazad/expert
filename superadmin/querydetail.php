<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../includes/userqueryDatabase.php';

require_once '../includes/categoryDatabase.php';

require_once '../includes/managebranchDatabase.php';

require_once '../includes/db.php';
function getMobileState( $mobileNum ){
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://trace.bharatiyamobile.com/?numb=".$mobileNum);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);
		//file_put_contents('opTrMob.txt',$output);
		if(strpos($output , 'Could not connect') === false){
    		$findingText = "radio.gif' alt='FM Radio in Mobile Location'/> List of FM Radio Stations in ";		
    		$startPos = strpos($output , $findingText);
    		if (!$startPos) {
    		    curl_close($ch);     
    		    return 'None';    
    		}
    		$endPos = strpos($output , "</div>" , $startPos+strlen($findingText) );	
    		$stateLength = $endPos - ($startPos+strlen($findingText)); 
    		
    		// close curl resource to free up system resources
    		curl_close($ch);     
    		return substr($output , $startPos+strlen($findingText) , $stateLength);
		}else{
    		curl_close($ch);     
    		return 'None';
		}
	}
  session_start();

if(!$_SESSION['id']){

   header('Location: ' . constant('BASE_URL'));

   exit;

}

$id = $_SESSION['id'];

$userquery = new userqueryDatabase();

$category = new categoryDatabase();

$branchData = new managebranchDatabase();

$dbObj = new db();

$nofr=20;

if(isset($_GET['nfr'])){

$nofr=$_GET['nfr'];

}

if(isset($_POST['fltrBt'])){

	

	/*$strDate = mysql_real_escape_string($_POST['frmDat']);

	$endDate = mysql_real_escape_string($_POST['toDat']);

	$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt"),"leads","  create_date >= '$strDate' And create_date <= '$endDate' order by create_date desc");

*/

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



//print_r($flterDetalArray);

$where = " source != 'IVR' AND ";

foreach( $flterDetalArray as $fKey => $fVal ){

	$caseVal=array_keys($fVal);

	//print_r($fVal);

		switch($caseVal[0]){

			case 'ledsDat':

				$dateSrchArry = explode("_",$fVal['ledsDat']);

				$where.=" (create_date  >= '".$dateSrchArry[0]." 00:00:00' AND create_date <= '".$dateSrchArry[1] ."  23:59:59') AND";

			break;

			case 'srchbranch':

				$brnchSrchArry = explode("_",$fVal['srchbranch']);

				$where.=" (branch_id  = '".$brnchSrchArry[0]."' OR emp_id = '".$brnchSrchArry[1] ."') AND";

			break;

			case 'onlyBrnch':

				$where.=" (branch_id  = '".$fVal['onlyBrnch']."') AND";

			break;

			case 'srchStatus':

				$where.= "(status  = '".$fVal['srchStatus']."') AND";

			break;
			case 'phone':

				$where.= "(phone  = '".$fVal['phone']."') AND";

			break;

			}

	}

$where = rtrim($where,"AND");

$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status','phone_location',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt",'emp_id','hits','status'),"leads",$where."  order by create_date desc limit 0,$nofr");

$getDataLeadsNuR=$dbObj->getData(array('id','name','email','phone','category','r_status','phone_location',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt",'emp_id','hits','status'),"leads",$where."  order by create_date desc ");

	}else{

$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status','phone_location',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','hits','status'),"leads"," source != 'IVR' order by create_date desc limit 0,$nofr");

$getDataLeadsNuR=$dbObj->getData(array('id','name','email','phone','category','r_status','phone_location',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','hits','status' ),"leads"," source != 'IVR' order by create_date desc ");
}
 

//print_r($getDataLeads);

//$getData = $userquery->getRecord('50','desc');

$fetchall = $category->fetchAll();

$branch = $branchData->getBranch();

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

function array_to_csv_download($array,$heading, $filename = "export.csv", $delimiter=",") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    //fputcsv($f, $heading, $delimiter); 
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}

if(isset($_GET['dwn'])){
$whereCondtion = base64_decode ($_GET['dwn']);
if(strlen($whereCondtion)> 2){
$getDataDownload =$dbObj->getData(array($_GET['typ']),"leads",$whereCondtion."  order by create_date desc ");
array_shift($getDataDownload);
$downloadFileName="export-".strtoupper($_GET['typ'])."-".date("dSMy")." ".date('H:i:s').".csv";
$headingFields = array(strtoupper($_GET['typ']));
array_to_csv_download($getDataDownload,$headingFields ,$downloadFileName);
die();
}
}
?>

<!DOCTYPE html>

<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">        

    

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />



    <title>EXPERT - Admin panel</title>


      <link rel="icon" href="../images/favicon.png" type="image/x-icon" />
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
    <script type='text/javascript' src='../js/plugins/scrollup/jquery.scrollUp.min.js'></script>

    

    

    <script type='text/javascript' src='../js/cookies.js'></script>

    <script type='text/javascript' src='../js/actions.js'></script>

    <script type='text/javascript' src='../js/charts.js'></script>

    <script type='text/javascript' src='../js/plugins.js'></script>

    <script type='text/javascript' src='../js/style.js'></script>

           <script >

       $(document).ready(function(e) {

        $("#c_b").children("input:checkbox").click(function(){

		alert("helloo")	;	

		var flg=0;

			$("#c_b").children("input:checkbox").each(function(index, element) {

        			var isChe=$(this).val()?true:false;        

					if(isChecked){

		  					$("#cntrls").show("slow");

							flg++;

		 				}	

	            });

		 	if(flg>0){

				  $("#cntrls").show("slow");

		 	}else{

		 		 $("#cntrls").hide("slow");		

				}

		

		});	



    });

       </script>

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

                <li><a href="#">Simple Admin</a> <span class="divider">></span></li>                

                <li class="active">Quries</li>

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

                    <a href="#" class="link_bcPopupSearch"><span class="icon-search"></span><span class="text">Search Name / Mobile</span></a>

                    

                    <div id="bcPopupSearch" class="popup">

                        <div class="head">

                            <div class="arrow"></div>

                            <span class="isw-zoom"></span>

                            <span class="name">Search</span>

                            <div class="clear"></div>

                        </div>

                        <div class="body search">

                            <input type="text" placeholder="Some text for search..." name="search" id="searchLeadName"/>

                        </div>

                        <div class="footer">

                            <button class="btn" type="button" id = "searchLeadBtn">Search</button>

                            <button class="btn btn-danger link_bcPopupSearch" type="button">Close</button>

                        </div>

                    </div>                

                </li>

            </ul>

            

        </div>

        

        <div class="workplace">

                                    

            

            

             <div class="row-fluid branch" style="display:none;">

                     <div class="head">

                                <div class="isw-refresh" id="rfBt" style="cursor:pointer;"></div>

                                <h1>Move</h1>   

                                

                                <div class="clear"></div>

                            </div>

                        <div class="block-fluid">

                            <br>

                            <?php $selectbranch = "select * from login_accounts where branch_id is null"; 

                            $selectresult = mysql_query($selectbranch); ?>

                            <div style="margin-left:15px;">Select Branch : &nbsp; <select id="getemp"><option>-----Select-----</option>

                                <?php while($row = mysql_fetch_assoc($selectresult)){

                                    echo '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';

                                } ?>

                                </select></div>

                            <div class="emp" style="margin-left:15px;margin-bottom:20px;"></div>

                            <div><input type="hidden" value="" class="checked"></div>

                            <div style="margin-left:40px;margin-bottom:20px;"><input type="button" class="move_to" value="Move To" style="display:none;"></div>

                        </div>



                    </div>         

            

             

            

            <div class="row-fluid">

          

     

            <div class="row-fluid">

                

                <div class="span12">                    

                    <div class="head">

                          <div class="isw-refresh" id="rfBt" style="cursor:pointer;" onClick="window.location.href='querydetail.php'"></div>

                        <h1>All Leads</h1>                           

						<ul class="buttons">                            

                            <li>

                                <a href="#" class="isw-settings"></a>

                                <ul class="dd-list">

                           <li><a href="javascript:void(0);" id="move"><span class="isw-right_circle"></span>Send Lead</a></li>

                             <li><a href="javascript:void(0);" id="del"><span class="isw-delete"></span>Delete Lead</a></li>
                             
                             <li><a href="managebranch.php"><span class="isw-list"></span>Manage User</a></li>
                             <li><a href="javascript:void(0);" class ="downloadQuery" id="phone"><span class="isw-list"></span>Download Phone</a></li>
                             <li><a href="javascript:void(0);" class ="downloadQuery" id="email"><span class="isw-list"></span>Download Email</a></li>                                                                                            

<?php $statArray = $dbObj->getData(array("*"), "status");  array_shift($statArray); foreach( $statArray as $dtStat ){  ?><li><a href="querydetailFltSt.php?lftr=<?php echo $dtStat['status'];?>"><span class="isw-list"></span> <?php echo $dtStat['status'];?></a></li><?php  } ?>

                                </ul>

                            </li>

                        </ul>                        <div class="clear"></div>

                    </div>

                    <div id="cntrlMenu">   

                    <div id="fltrCntrls" style="color:#000;font-family:Calibri;font-size:16px;font-weight:bold;float:left; margin:5px 0px 0px 10px;"></div> 

					</div>

                 <div class="clear"></div>

                 <div id='nrf'> Show <select name='nofr' id='nofr' style='padding: 0px; height: 25px; width: 55px;font-size:14px;'>

                                            <option value='20' <?php if($nofr==20)echo 'selected="selected";'?>>20</option>

                                           <option value='50'  <?php if($nofr==50)echo 'selected="selected";'?>>50</option>

                                           <option value='100'  <?php if($nofr==100)echo 'selected="selected";'?>>100</option>
                                                                                                            </select>
                    <span id='serchRowCount'><?php echo $getDataLeadsNuR[0];?> Record Found</span>
                   <form action="" method="post">
                     <i class="icon-calendar"></i>
                     <input type="text"  id="frmDat" name="ledsDat[]" style="width:70px; height:20px;" value="" placeholder=" Date">
                     &nbsp; 
                     
                     To : &nbsp;
                     <input id="toDat" type="text" name="ledsDat[]" style="width:70px; height:20px;" value="" placeholder=" End Date">
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
  <select name="srchStatus" id="srchStatus" style="height: 32px; color:#000; font-weight: !important; width: 100px;" >
    <option value="">Status</option>
    <?php 

                                                  $datStatus  = $dbObj->getData(array("*") ,"status");

                                                   array_shift($datStatus );

                                                   foreach( $datStatus as $statusOpt){  ?>
    <option value="<?php echo $statusOpt['status'];?>"><?php echo $statusOpt['status'];?></option>
    <?php }

                                             ?>
  </select>
  &nbsp;
             <input id="" type="text" name="phone" style="width:100px; height:20px;" value="" placeholder="Phone Search">
             
&nbsp;
  <input type="submit" name="fltrBt" id="fltrBt" value="Go" style="height: 28px; width: 38px; margin-top: -11px;" class="button warning">
  
                   </form>
                 </div>

                  <div id='whereCnd' style="display:none;"><?php echo $where; ?></div>

                 <div id='nfp' style="display:none;" ><?php echo ceil($getDataLeadsNuR[0]/$nofr); ?></div>

                <div id='nextprev'><ul ><li class='f-page' id='1'>Home</li><li class='prev-page' id='1'> < Previ</li><li class='curnt-page' id='1'>1</li><li class='next-page' id='2'>Next ></li><li class='l-page' id='<?php echo ceil($getDataLeadsNuR[0]/$nofr);?>'>Last</li></ul></div>



                    <div class="clear"></div>	

                 <div class="block-fluid table-sorting" id="dt-table" style="margin-top: 22px;">

                                            <table cellpadding="0" cellspacing="0" width="100%" class="table" >

                            <thead>

                                <tr>

                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>                                 

                                 	<th width="15%">Name</th>

                                    <th width="15%">E-mail</th>

                                    <th width="8%">Phone</th>                                
                                   <?php if(isset($_POST['fltrBt'])){?>
                                   <th width="25%">Remark</th>
                                   <?php }else{ ?>
                                    <th width="25%">Course</th> 
                                   <?php } ?>        
                                    <th width="22%">Date</th>
                                   <th width="10%">User ID</th>



                                </tr>

                            </thead>

                            <tbody>

                                <?php    

                                   array_shift($getDataLeads );

                                foreach($getDataLeads as $data){

                                   // print_r($data);

								   $styNew="background:#fff;color:#000;font-weight:bold;";

                                  	

                                    ?>

                                <tr>
                               <?php 

                                         $leadId=$data['id'];

                                         $sql= mysql_fetch_assoc(mysql_query("select max(id) as id from user_query where lead_id=$leadId"));  

                                    $followup_date = mysql_fetch_assoc(mysql_query("select DATE_FORMAT(next_followup_date,'%d-%m-%Y %r') nxtDt , message from user_query where id={$sql['id']}"));                                       
if($data['r_status']==1){
												$styNew="background:#F8F8F8;color:#000;";
												}
                                        $nofDead = $userquery->noOfDead($leadId);
  
                                        if($nofDead <= 1 && $nofDead >= 1 && $data['status'] == 'Dead'){
                                            $styNew="background:#ffb612;color:#000;";
                                            }  
                                        if($nofDead >= 2 && $data['status'] == 'Dead'){
                                            $styNew="background:#ff1c37;color:#000;";
                                            }  
										if($data['hits']>=1 && $data['emp_id']!=1){
											$styNew="background:#a2d246;color:#000;";
										    }
										
                                    ?>


                                   <td style="<?php  echo $styNew; ?>" id="c_b"><input onClick="dsiCntrl( this );" name="led[]" class="check" type="checkbox"  value="<?php echo $data['id']; ?>">  </td>

                                    <td style="<?php echo $styNew;?>" ><a href="<?php echo constant('BASE_URL'); ?>/superadmin/messagedetail.php?id=<?php echo $data['id']; ?>"><?php echo ucwords ( strtolower ($data['name'])); ?></a></td>

                                    <td style="<?php echo $styNew;?>" ><?php echo $data['email']; ?></td>


                                    <td style="<?php echo $styNew;?>" ><?php echo $data['phone']; ?></td>  

                                     <?php if(isset($_POST['fltrBt'])){?>                                           
<td style="<?php echo $styNew;?>" ><?php echo $followup_date['message']; ?></td>                                    
                                    <?php }else{?>
<td style="<?php echo $styNew;?>" ><?php echo str_replace("-"," ",$data['category']); ?></td>
                                    <?php } ?>
        
                                    <td style="<?php echo $styNew;?>" > <?php echo $data['cdt'];?></td>

                                   <!-- <td style="<?php echo $styNew;?>" ><?php // if($followup_date['nxtDt']){ echo $followup_date['nxtDt'];}else { echo "No Follow Up";} ?></td> -->                              
                                   <td style="<?php echo $styNew;?>" >

<?php  if($data['emp_id'] == 1){echo 'Admin'; }else{  
          $userDat = $dbObj->getData(array('first_name','last_name') ,"login_accounts", "id = ".$data['emp_id']); 
 ?>
         <a href="redadacc.php?user_id=<?php echo $data['emp_id'];?>">
                <?php echo $userDat[1]['first_name']." ".$userDat[1]['last_name'] ; ?>
        </a>
<?php  } ?>

</td>
                          </tr>

                                <?php

                                

                                }

?>

                                

                                

                                                                

                            </tbody>

                            

                                

                        </table>

                        

                    </div>

                   

                </div>     

               

                 

            </div>

            

             

            <div class="dr"><span></span></div>

        

        </div>

        

    </div>   

    

</body>

</html>

<script type="text/javascript">

    $(document).ready(function(){

       $('#move').click(function(){

           var select = '';

          $('#c_b input[type="checkbox"]:checked').each(function(){

            select += $(this).val()+', ';

          });

          

          if(select == ''){

              alert('Please select the checkbox and then perform the action !');

              return false;

          } else {

              $('.branch').toggle("slow");

              $('.checked').val(select);

          }

       }); 

       

     $('#getemp').change(function(){

         var id = $(this).val();

         $.ajax({

            type:'POST',

            url : '../ajax/add.php',

            data :{action:'getemp',id:id},

            success:function(data){

                $('.emp').html(data);

                $('.move_to').show();

            }

         });

     });

     

         $('.move_to').click(function(){

         var emp= $('input:radio[name=emp]:checked').val();

         if(emp == undefined){

             alert('Please select an employee !');

             return false;

         }

         var select = $('.checked').val();

         //alert(emp);

         //alert(select);

         $.ajax({

            type:'POST',

            url :'../ajax/add.php',

            data :{action:'updatelead',emp:emp,select:select},

            success: function(dddt){
//alert(dddt);
                window.location.reload();

            }

         });

         

     });

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

	 $(".checker").click(function(){

		// alert("hhh");

		 var isChecked = $('.check:checked').val()?true:false;

		 	if(isChecked){

		  $("#cntrls").show("slow");

		 	}else{

		  $("#cntrls").hide("slow");		

				}

		 });

	$("#del").click(function(){

		 var delLds = '';

          $('#c_b input[type="checkbox"]:checked').each(function(){

            delLds += $(this).val()+', ';

          });

		 $.ajax({

            type:'POST',

            url :'../ajax/delLeads.php',

            data :{deletLds:delLds},

            success: function(respTxt){

				//alert(respTxt);

                window.location.reload();

				//$("#tdLeads").html(respTxt);

            }

         });

		

		});	

    });



/* Paging Code*/

$("#nofr").change(function(){

	var slV=$(this).val();

	window.location.href='querydetail.php?nfr='+slV;

	});

$(".next-page").click(function(event){

	    event.preventDefault();

		var whereCnd=$("#whereCnd").text();

		var nfpg=$("#nfp").text();

		var cPgN = parseInt($(".curnt-page").attr("id"));

		var nfrpp=$("#nofr").val();	

		if(cPgN < nfpg){

			$(".curnt-page").attr("id",cPgN+1);

			$(".curnt-page").text(cPgN+1);

				var nxtpg=cPgN+1;

			    $.post(

							'../ajax/getleadTab.php'  ,

							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},

						function(data)

							{

						 //alert(data); 

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

							'../ajax/getleadTab.php'  ,

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

							'../ajax/getleadTab.php'  ,

							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},

						function(data)

							{

						// alert(data); 

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

							'../ajax/getleadTab.php'  ,

							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},

						function(data)

							{

						// alert(data); 

							$("#dt-table").html(data);

							}

					);



	});	

	

/* Paging Code*/
/*Search Code*/
$("#searchLeadBtn").click(function(event){

	    event.preventDefault();

		var whereCnd=" `name` like '%"+$("#searchLeadName").val()+"%' OR `phone` like '%"+$("#searchLeadName").val()+"%' OR `message` like '%"+$("#searchLeadName").val()+"%' ";
console.log(whereCnd);

                $("#whereCnd").text(whereCnd);

		var nfpg=$("#nfp").text();		

		var cPgP = 1;

		var nfrpp=100;

		
		var nxtpg=1;
var currentdate = new Date();

				    $.post(

							'../ajax/getleadTab.php?_='+currentdate.getTime()  ,

							{'nfrPP':nfrpp,'pageN':nxtpg,'whereCnd':whereCnd},

						function(data)

							{

						 //alert(data); 

							$("#dt-table").html(data);
                                                         $("#serchRowCount").text($("#queryDetailTabId").attr("row-count"));
							}

					);

		

	});	

/*Search Code*/


	function dsiCntrl(  ){

		

		 var isChecked = $(this).val()?true:false;

		 	if(isChecked){

		  $("#cntrls").show("slow");

		 	}else{

		  $("#cntrls").hide("slow");		

				}

		 

		}
$(".downloadQuery").click(function(){
	
	var encodedString = btoa($("#whereCnd").text());
	var newUrl = window.location.href+'?dwn='+encodedString+'&typ='+ $(this).attr('id');
	console.log(newUrl);
	window.location = newUrl;
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

$(document).ready(function(){

     $(".ui-state-highlight").css("color","#fff");

});

       </script>

       <!-- Date Picker -->

