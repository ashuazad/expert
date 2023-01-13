<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../includes/userqueryDatabase.php';

require_once '../includes/categoryDatabase.php';

require_once '../includes/managebranchDatabase.php';

require_once '../includes/db.php';

require_once '../includes/common_api.php';

function getMobileState( $mobileNum ){
		// create curl resource
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "http://trace.bharatiyamobile.com/?numb=".$mobileNum);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$output = curl_exec($ch);		
		$startPos = strpos($output , "radio.gif' /> List of FM Radio Stations in ");
		$endPos = strpos($output , "</div>" , $startPos+strlen("radio.gif' /> List of FM Radio Stations in ") );	
		$stateLength = $endPos - ($startPos+strlen("radio.gif' /> List of FM Radio Stations in ")); 
		
		// close curl resource to free up system resources
		curl_close($ch);     
		return substr($output , $startPos+strlen("radio.gif' /> List of FM Radio Stations in ") , $stateLength);
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

//print_r($_POST);	die();

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

$where = "";

foreach( $flterDetalArray as $fKey => $fVal ){
	$caseVal=array_keys($fVal);
	//print_r($fVal);
		switch($caseVal[0]){

			case 'ledsDat':
				$dateSrchArry = explode("_",$fVal['ledsDat']);
				$where.=" (doj  >= '".$dateSrchArry[0]."' AND doj <= '".$dateSrchArry[1] ."') AND";
			break;
			case 'srchbranch':
				$brnchSrchArry = explode("_",$fVal['srchbranch']);
				$where.=" (branch_name  = '".$brnchSrchArry[0]."' AND emp_id = '".$brnchSrchArry[1] ."') AND";
			break;
			case 'onlyBrnch':
				$where.=" (branch_name  = '".$fVal['onlyBrnch']."') AND";
			break;
			case 'srchStatus':
				$where.= "(status  = '".$fVal['srchStatus']."') AND";
			break;
			case 'phone':
				$where.= "(phone  = '".$fVal['phone']."') AND";
			break;
			case 'due_fee':
				$where.= "( (total_fee - due_fee) <= ".$fVal['due_fee']." ) AND";
			break;

			}

	}
$where.= " status != '0'";
$where = rtrim($where,"AND");


$getDataLeadsNuR=$dbObj->getData(array('count(*) countNum') , "admission" , $where );

	}else{



}
 $getDataLeads= array();

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

//Email List
$emailArray = $dbObj -> getData( array("*") , "email_template_style" ); 


if( isset($_GET['txt']) && !empty($_GET['txt']) ){
  $editEmailArray = $dbObj -> getData( array("*") , "email_template_style" , "title='".trim($_GET['txt'])."'"); 
  array_shift($editEmailArray);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>        

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>EXPERT - Email</title>
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

        $("#fltrBt").submit(function(){
            if($("#emailValue").value() == 0){
                alert("Please select Email template");
                return false;
            }else{
                return true;
            }
        });
    
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

                        <h1>Email Template Formatt</h1>                           

						<ul class="buttons">                            

                            <li>

                                <a href="#" class="isw-settings"></a>

                                <ul class="dd-list">
                                    <li><a href="javascript:void(0);" id="selectSMSTemplate"><span class="isw-right_circle"></span>Send Email</a></li>
                                </ul>

                            </li>

                        </ul>                        <div class="clear"></div>

                    </div>

                    <div id="cntrlMenu">   

                    <div id="fltrCntrls" style="color:#000;font-family:Calibri;font-size:16px;font-weight:bold;float:left; margin:5px 0px 0px 10px;"></div> 

					</div>

                 <div class="clear"></div>

                 <div id='nrf'> 
                    <?php if($getDataLeadsNuR[0] > 0){ ?>
                    <span id='serchRowCount'><?php echo $getDataLeadsNuR[1]['countNum'];?> Record Found</span>
                    <?php } ?>
                    
                   <form action="" method="post" id="serchFrm">
                       <div>Select Email : <select name="emailValue" id="emailValue">
                        <option value=""> Select Email </option>
                        <?php if($emailArray[0] > 0){
                            array_shift($emailArray);
                            foreach($emailArray as $eachEmail){
                        ?>
                            <option value="<?php echo trim($eachEmail['title']); ?>"> <?php echo trim($eachEmail['title']); ?> </option>
                        <?php 
                            }
                        }else{ ?>
                            <option value=""> </option>
                        <?php } ?>
                    </select>  
                    <a href="#fModalAdd" role="button" class="btn btn-default" data-toggle="modal"> ADD EMAIL </a>
                <!-- Bootrstrap modal form -->
        <div class="modal fade" id="fModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Email Template</h4>
                    </div>
                    <div class="modal-body modal-body-np">
                        <div class="row" style="margin-left:-2px;">
                            <div class="block-fluid">
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Email Title:</div>
                                    <div class="col-md-9"><input id="add_email_title_inp" type="text" value=""/></div>
                                </div>
                                 <div class="row-form clearfix">
                                    <div class="col-md-3">Email Type:</div>
                                    <div class="col-md-9"><select id = "type"> 
                                                                <option value="">-Select-</option>
                                                                <option value="admission">Admission</option>
                                                                <option value="lead">Lead</option>
                                                         </select>
                                    </div>
                                </div> 
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Email Content:</div>
                                    <div class="col-md-9">
                                        <!--<div class="block-fluid" id="wysiwyg_container">-->

                            <textarea id="wysiwyg"  name="add_email_content_inp" style="height: 300px;"></textarea>

                        <!--</div>-->
                                        <!--<textarea id="add_email_content_inp"></textarea>--></div>
                                </div>                                                
                            </div>                
                            
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true" id="addEmailTemplate">Add</button> 
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>            
                    </div>
                </div>
            </div>
        </div>            
                    <a href="javascript:void(0);" role="button" class="btn btn-default"  id="editEmailBtnWrp" style="cursor:default;">EDIT EMAIL</a>
                    <a href="#fModal" role="button" class="btn btn-default" data-toggle="modal" id="editEmailBtn" style="display:none;cursor:default;">EDIT EMAIL</a>
                    <!-- Bootrstrap modal form -->
        <div class="modal fade" id="fModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Email Template</h4>
                    </div>
                    <div class="modal-body modal-body-np">
                        <div class="row" style="margin-left:-2px;">
                            <div class="block-fluid">
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Email Title:</div>
                                    <div class="col-md-9"><input id="email_title_inp" type="text" value="<?php echo $editEmailArray[0]['title'];?>"/></div>
                                </div>
                                       <div class="row-form clearfix">
                                    <div class="col-md-3">Email Type:</div>
                                    <div class="col-md-9"><select id = "edit_email_type"> 
                                                                <option value="">-Select-</option>
                                    <option <?php echo ($editEmailArray[0]['type'] == 'admission') ? "selected='selected'" : '';?>  value="admission">Admission</option>
                                    <option <?php echo ($editEmailArray[0]['type'] == 'lead') ? "selected='selected'" : '';?> value="lead">Lead</option>
                                                         </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Email Content:</div>
                                    <div class="col-md-9"><textarea id="wysiwyg2"  name="edit_email_content_inp" style="height: 300px;"><?php echo $editEmailArray[0]['content'];?></textarea></div>
                                </div>                                                
                            </div>                
                            
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <input id="email_title_pk" type="hidden" value="<?php if(isset($_GET['txt']) && !empty($_GET['txt'])){ echo trim($_GET['txt']);}?>"/>
                        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true" id="editEmailTemplate">Save updates</button> 
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" id="deleteEmailTemplate">Close</button>            
                    </div>
                </div>
            </div>
        </div>
                    <a href="javascript:void(0);" role="button" class="btn btn-default" data-toggle="modal" id="deleteEmailBtn" style="cursor:default;">DELETE EMAIL</a>
                    </div>
    </form>
                 </div>

                  <div id='whereCnd' style="display:none;"><?php echo $where; ?></div>

                 <div id='nfp' style="display:none;" ><?php echo ceil($getDataLeadsNuR[0]/$nofr); ?></div>

            <!--    <div id='nextprev'><ul ><li class='f-page' id='1'>Home</li><li class='prev-page' id='1'> < Previ</li><li class='curnt-page' id='1'>1</li><li class='next-page' id='2'>Next ></li><li class='l-page' id='<?php echo ceil($getDataLeadsNuR[0]/$nofr);?>'>Last</li></ul></div>-->



                    <div class="clear"></div>	

                 <div class="block-fluid table-sorting" id="dt-table" style="margin-top: 22px; display:none;">

           
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
<?php if(strlen($where) > 0){ ?>
//alert('hiii');

var currentdate = new Date();
    $.post(
            '../ajax/admissionSendEmail.php?_='+currentdate.getTime()  ,
            {"whereCnd":"<?php echo $where;?>","email":"<?php echo trim($_POST['emailValue']);?>"},
        	function(data)
						{
						}
					);

<?php } ?> 



$("#serchFrm").submit(function(){
   if($("#emailValue").val() == ''){
       alert("Please select Email template.");
       return false;
       
   }else{
       return true;
   } 
});

$("#emailValue").change(function(){
    /*console.log($(this).val());*/
    if($(this).val() == ''){
        $("#editEmailBtn").css("cursor","default");
        $("#editEmailBtn").attr('href','javascript:void(0)');
        
        $("#deleteEmailBtn").css("cursor","default");
    }else{
       
     $.ajax({
    type: "POST",
    url: "../ajax/getEmailFormatt.php",
    // The key needs to match your method's input parameter (case-sensitive).
    data: JSON.stringify({ title : $(this).val() }),
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function(responseGetEmail){
        $("#email_title_inp").val(responseGetEmail.title);
        $("#wysiwyg2").val(responseGetEmail.content);
        $("#email_title_pk").val(responseGetEmail.title);
            }
    });  
        $("#editEmailBtn").css("cursor","pointer");
        /*$("#editEmailBtn").attr('href','#fModal');*/
        
        $("#deleteEmailBtn").css("cursor","pointer");
        
        $("#editEmailBtnWrp").css("cursor","pointer");
        
    }
});

$("#addEmailTemplate").click(function(){
    console.log($("#add_email_title_inp").val());
    console.log($("#add_email_content_inp").val());
    $.ajax({
    type: "POST",
    url: "../ajax/addEmailFormatt.php",
    // The key needs to match your method's input parameter (case-sensitive).
    data: JSON.stringify({ title : $("#add_email_title_inp").val() , content : $("textarea[name='add_email_content_inp']").val() , type : $("#type").val() }),
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function(addGetEmail){
window.location.href = 'manageTemplate.php';
        location.reload(); 
            }
    });
});

$("#editEmailTemplate").click(function(){
    console.log($("#email_title_inp").val());
    console.log($("#wysiwyg2").val());
    console.log( $('#type').find(":selected").val());
    $.ajax({
    type: "POST",
    url: "../ajax/editEmailFormatt.php",
    // The key needs to match your method's input parameter (case-sensitive).
    data: JSON.stringify({ title : $("#email_title_inp").val() , content : $("#wysiwyg2").val() , email_title_pk : $("#email_title_pk").val() , type : $("#edit_email_type").val()}),
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function(editGetEmail){
        location.href = "manageTemplate.php"; 
            }
    });
});

$("#deleteEmailTemplate").click(function(){
    location.href = "manageTemplate.php";
});

$("#deleteEmailBtn").click(function(){
var emailTemplateTitle = '';
 emailTemplateTitle = $("#emailValue").val();
    if(emailTemplateTitle != ''){
        if(confirm('Are you sure to DELETE template?') == true){
            $.ajax({
            type: "POST",
            url: "../ajax/deleteEmailFormatt.php",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({ email_title : emailTemplateTitle }),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(editGetEmail){
                //console.log(editGetEmail);
                location.reload(); 
                    }
            });
        }
    }
});

$("#editEmailBtnWrp").click(function(){
    var emailTemplateTitle = '';
    emailTemplateTitle = $("#emailValue").val();
    if(emailTemplateTitle != ''){
        location.href = "manageTemplate.php?txt=" + emailTemplateTitle;        
    }
});

<?php if(isset($_GET['txt']) && !empty($_GET['txt'])){ ?>
$(document).ready(function(){
    $("#editEmailBtn").trigger( "click" );
});
<?php } ?>



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
     
     $("#selectSMSTemplate").click(function(){
         
     });

});

       </script>

       <!-- Date Picker -->

