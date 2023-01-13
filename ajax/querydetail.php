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
	$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%D %M %y %r') cdt"),"leads","  create_date >= '$strDate' And create_date <= '$endDate' order by create_date desc");
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
				$where.=" (create_date  >= '".$dateSrchArry[0]."' AND create_date <= '".$dateSrchArry[1] ."') AND";
			break;
			case 'srchbranch':
				$brnchSrchArry = explode("_",$fVal['srchbranch']);
				$where.=" (branch_id  = '".$brnchSrchArry[0]."' AND emp_id = '".$brnchSrchArry[1] ."') AND";
			break;
			case 'onlyBrnch':
				$where.=" (branch_id  = '".$fVal['onlyBrnch']."') AND";
			break;
			case 'srchStatus':
				$where.= "(status  = '".$fVal['srchStatus']."') AND";
			break;
			}
	}
$where = rtrim($where,"AND");
$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%D %M %y %r') cdt"),"leads",$where."  order by create_date desc limit 0,$nofr");
$getDataLeadsNuR=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%D %M %y %r') cdt"),"leads",$where."  order by create_date desc ");
	}else{
$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%D %M %y %r') cdt"),"leads"," 1=1 order by create_date desc limit 0,$nofr");
$getDataLeadsNuR=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%D %M %y %r') cdt"),"leads"," 1=1 order by create_date desc ");

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
?>
<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>CRM - Admin Query panel</title>

    <link rel="icon" type="image/ico" href="../favicon.ico"/>
    
    <link href="../css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link rel='stylesheet' type='text/css' href='../css/fullcalendar.print.css' media='print' />
    <script type="text/javascript" src='../js/jquery-1.4.2.min.js'></script>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js'></script>
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
                                    <li><a href="managebranch.php"><span class="isw-list"></span> Add User</a></li>                                   
<?php $statArray = $dbObj->getData(array("*"), "status");  array_shift($statArray); foreach( $statArray as $dtStat ){  ?><li><a href="querydetailFltSt.php?lftr=<?php echo $dtStat['status'];?>"><span class="isw-list"></span> <?php echo $dtStat['status'];?></a></li><?php  } ?>
                                </ul>
                            </li>
                        </ul>                        <div class="clear"></div>
                    </div>
                    <div id="cntrlMenu"> 
                    	<div id="cntrls" style=" display:none;float:left;margin:5px 0px 0px 10px;">
                        	<input type="button"  class="btn btn-warning" id="move" value="Move">&nbsp;&nbsp;&nbsp; 
                            <input id="del" type="button"  class="btn btn-danger" value="Delete">
                        </div>     
                    <div id="fltrCntrls" style="color:#000;font-family:Calibri;font-size:16px;font-weight:bold;float:left; margin:5px 0px 0px 10px;">
                        	<form action="" method="post">
                        		Leads From : <input type="text"  id="frmDat" name="ledsDat[]" style="width:80px; height:17px;" value="">&nbsp; 
                                To : &nbsp; <input id="toDat" type="text" name="ledsDat[]" style="width:80px; height:17px;" value=""> &nbsp; 
                                 <select name="srchbranch[]" id="srchB"  style="height: 26px; color: rgb(0, 0, 0); font-weight: bold; width: 154px;" >
                                    <option value="">Filtter By branch</option>
                                    <?php foreach ($branch as $fetch)  {  ?>
                                       <option value="<?php echo $fetch['id']  ?>"><?php echo $fetch['branch_name'];   ?></option>                                   
                                    <?php  } ?>
                                    
                                </select>
                                &nbsp; 
                                 <select name="srchbranch[]" id="srchE"  style="height: 26px; color:#000; font-weight: bold; width: 171px;" >
                                    <option value="" selected>Filtter By Employee</option>                                    
                                </select>
                                &nbsp; 
				 <select name="srchStatus" id="srchStatus" style="height: 26px; color:#000; font-weight: bold; width: 150px;" >
                                           <option value="">Filtter By Status</option>
                                            <?php 
                                                  $datStatus  = $dbObj->getData(array("*") ,"status");
                                                   array_shift($datStatus );
                                                   foreach( $datStatus as $statusOpt){  ?> <option value="<?php echo $statusOpt['status'];?>"><?php echo $statusOpt['status'];?></option><?php }
                                             ?>
                                  </select>
                                &nbsp; 
              <input type="submit" name="fltrBt" id="fltrBt" value="Go" style="height: 25px; width: 35px; margin-top: -11px;" class="button warning">
                
                          </form>
                        </div> 
					</div>
                 <div class="clear"></div>
                 <div id='nrf'>  Show <select name='nofr' id='nofr' style='padding: 0px; height: 20px; width: 47px;font-size:15px;'>
                                            <option value='20' <?php if($nofr==20)echo 'selected="selected";'?>>20</option>
                                           <option value='50'  <?php if($nofr==50)echo 'selected="selected";'?>>50</option>
                                           <option value='100'  <?php if($nofr==100)echo 'selected="selected";'?>>100</option>
                                 </select></div>
                  <div id='whereCnd' style="display:none;"><?php echo $where; ?></div>
                 <div id='nfp' style="display:none;" ><?php echo ceil($getDataLeadsNuR[0]/$nofr); ?></div>
                <div id='nextprev'><ul ><li class='f-page' id='1'>First</li><li class='prev-page' id='1'> < Prev</li><li class='curnt-page' id='1'>1</li><li class='next-page' id='2'>Next ></li><li class='l-page' id='<?php echo ceil($getDataLeadsNuR[0]/$nofr);?>'>Last</li></ul></div>

                    <div class="clear"></div>	
                 <div class="block-fluid table-sorting" id="dt-table" style="margin-top: 5px;">
                                            <table cellpadding="0" cellspacing="0" width="100%" class="table" >
                            <thead>
                                <tr>
                                    <th width=""><input type="checkbox" name="checkall" class="check" id="chAll" /> </th>                                 
                                 	<th width="15%">Name</th>
                                    <th width="15%">E-mail</th>
                                    <th width="10%">Phone</th>  
                                    <th width="25%">Course</th> 
                                    <th width="20%">Created</th><th width="20%">Follow Up Date</div></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php    
                                   array_shift($getDataLeads );
                                foreach($getDataLeads as $data){
                                   // print_r($data);
								   $styNew="background:#fff;color:#000;font-weight:bold;";
											if($data['r_status']==1){
												$styNew="background:#F8F8F8;color:#000;";
												}

                                    ?>
                                <tr>
                                   <td style="<?php  echo $styNew; ?>" id="c_b"><input onClick="dsiCntrl( this );" name="led[]" class="check" type="checkbox"  value="<?php echo $data['id']; ?>">  </td>
                                    <td style="<?php echo $styNew;?>" ><a href="<?php echo constant('BASE_URL'); ?>/superadmin/messagedetail.php?id=<?php echo $data['id']; ?>"><?php echo $data['name']?></a></td>
                                    <td style="<?php echo $styNew;?>" ><?php echo $data['email']; ?></td>
                                    <td style="<?php echo $styNew;?>" ><?php echo $data['phone']; ?></td>  
                                    
                                    <td style="<?php echo $styNew;?>" ><?php echo $data['category']; ?></td>
                                      <?php 
                                         $leadId=$data['id'];
                                         $sql= mysql_fetch_assoc(mysql_query("select max(id) as id from user_query where lead_id=$leadId"));  
                                    $followup_date = mysql_fetch_assoc(mysql_query("select DATE_FORMAT(next_followup_date,'%d-%m-%Y %r') nxtDt from user_query where id={$sql['id']}"));
                                    ?>
                                    <td style="<?php echo $styNew;?>" > <?php echo $data['cdt'];?></td>
                                    <td style="<?php echo $styNew;?>" ><?php if($followup_date['nxtDt']){ echo $followup_date['nxtDt'];}else { echo "No Follow Up";} ?></td>                               
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
            success: function(){
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
				 alert(data); 
   
					}
				);
		}
	});		
$(".prev-page").click(function(event){
	    event.preventDefault();
		var nfpg=$("#nfp").text();		
		var cPgP = parseInt($(".curnt-page").attr("id"));
		var nfrpp=$("#nofr").val();
		if(cPgP>1){	
		$(".curnt-page").attr("id",cPgP-1);
		$(".curnt-page").text(cPgP-1);
		}
	});	
	
/* Paging Code*/
	function dsiCntrl(  ){
		
		 var isChecked = $(this).val()?true:false;
		 	if(isChecked){
		  $("#cntrls").show("slow");
		 	}else{
		  $("#cntrls").hide("slow");		
				}
		 
		}
	
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
