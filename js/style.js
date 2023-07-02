$(document).ready(function(){
   $('.add_message').click(function(){
       if($('.form').css('display') == 'none'){
       $('.form').show();    
       } else {
           $('.form').hide();
       }
       
   }) ;
   $('.edit_message').click(function(){ 
       if($('.edit_form').css('display') == 'none'){
       $('.edit_form').show();    
       } else {
           $('.edit_form').hide();
       }
       
   }) ;
   $('.submit_category').click(function(){
       var data = $('#category_name').val(); 
       $.post(
 '../ajax/add.php'  ,
{
'type':'add_category','data':data ,'action':'save'
},
function(data)
{
    alert('Category Added');
window.location.href='category.php'; 
}
)
   });
   
   
   $('.categoryshow').click(function(){
   var id= $(this).attr('id');
   if($('.show_'+id).css('display') == 'none'){
       $('.show_'+id).show();
   $('.real_'+id).hide();  
       } else {
   $('.show_'+id).hide();
   $('.real_'+id).show();
       }
    });

$('.update_category').click(function(){
   var id= $(this).attr('id');
   var value = $('.show_'+id+' input').val();
   $.post(
 '../ajax/add.php'  ,
{
'type':'add_category','data':value ,'action':'update','id':id
},
function(data)
{
    alert('Record has been updated');

window.location.reload(); 
}
)
});

$('.delete_record').click(function(){

var id = $(this).attr('id');
var splits = id.split('-');
var sure = confirm("Are you sure you want to delete");
if(sure){
$.post(
 '../ajax/add.php'  ,
{
'type':'delete_record','id':splits[1],'table':splits[0]
},
function(data)
{
    
    alert('Record has been deleted');

window.location.reload(); 
    
}
)
}
});
   
   $('.submit_query').click(function(){ 
       var firstname = $.trim($('#first_name').val());
       var lastname = $.trim($('#last_name').val());
       var email = $.trim($('#email').val());
       var phone = $.trim($('#phone').val());
       var category = $.trim($('#category').val());
       var follow_date = $.trim($('#datepicker').val());
       var message = $.trim($('#message').val());
       var branch = $.trim($('#branch').val());
       var type = 'add_query';
       //alert(follow_date);
         $.post(
 '../ajax/add.php'  ,
{
'type':type,'firstname':firstname,'lastname':lastname,'email':email,'phone':phone,'category':category,'follow_date':follow_date,'message':message,'branch':branch
},
function(data)
{ 
    if(data == '2'){
        alert("Email id already exist for this category");
         $('#email').focus();
        return false;
    } else {
        alert('Record saved!!!');
   window.location.href='querydetail.php';
    }
   
  
}
)
   });
   
   
   $('#add_message_query').click(function(){

var message = $.trim($('#followRemk').val());
//var followupD = $.trim($('#dateFollup').val());
//var timeFollup = $.trim($('#timeFollup').val());
var followupD = "NULL";
var timeFollup = "NULL";
//var followType = $.trim($('#followType').val());
var followType = "NULL";
var status = $.trim($('#status').val());
var nextFollowRemark = "NULL";
//var nextFollowRemark = $.trim($('#nextFollowRemark').val());
var dateFollupNxt = $.trim($('#dateFollupNxt').val());
///var timeFollupNxt = $.trim($('#timeFollupNxt').val());
var leadId = $.trim($('#leadId').val());
//alert(leadId);
var timeFollupNxt = "";
var id = $('.last_id_1:last').val();
var redTab = $("#tbDl").text();
if(id == undefined){ 
  id = $('.last_id').val();  
}
var type = 'message_follow';
if(message == ''){
    alert('Please enter the message');
    $('.messagedetail').focus();
    return false;
}
if(followupD == ''){
    alert('Please enter the followup date');
    $('#date').focus();
    return false;
}
if(status == ''){
    alert('Please select the status');
    $('.status').focus();
    return false;
}
if(timeFollup == '' ){
    alert('Please select the follow up time');
    $('#timeFollup').focus();
    return false;
}
 $.post(
 '../ajax/add.php'  ,
{
'type':type,'id':id,'message':message,'status':status,'followupD':followupD,'timeFollup':timeFollup,'followType':followType,'nextFollowRemark':nextFollowRemark,'dateFollupNxt':dateFollupNxt,'timeFollupNxt':timeFollupNxt,'leadId':leadId
},
function(data)
{
// alert(data); 
$("#msgBxx").fadeIn("fast");
$("#msgBxx").fadeOut("slow");
//  window.location.reload();  
  window.location.href="../account/index.php#"+redTab;
}
);

});

$('.submit_branch').click(function(){

var branch_name = $('#branch_name').val();
var role = $('.role').val();
var action = $('.action').val();

if(branch_name == '' && role == 'employee'){
    alert('Please enter Branch name.');
    $('#branch_name').focus();
    return false;
}
if(role == 'employee'){
    var branch_id = $('.branch_id').val();
    var category = $('#category').val();
} else {
    branch_id = '';
    category = '';
}

var first_name = $('#first_name').val();
if(first_name == ''){
    alert('Please enter First name.');
    $('#first_name').focus();
    return false;
}
var last_name = $('#last_name').val();
if(last_name == ''){
    alert('Please enter Last name.');
    $('#last_name').focus();
    return false;
}
var email = $('#email').val();
if(email == ''){
    alert('Please enter Email Id.');
    $('#email').focus();
    return false;
}
var phone = $('#phone').val();
if(phone == ''){
    alert('Please enter Phone Number.');
    $('#phone').focus();
    return false;
}
var address = $('#address').val();
if(address == ''){
    alert('Please enter Address.');
    $('#address').focus();
    return false;
}
var city = $('#city').val();
if(city == ''){
    alert('Please City.');
    $('#city').focus();
    return false;
}
var admission_frm_perm = $('#admission_frm_perm').val();
if(admission_frm_perm == ''){
    alert('Please Select Admission Form.');
    $('#admission_frm_perm').focus();
    return false;
}
var admission_dashboard_perm = $('#admission_dashboard_perm').val();
if(admission_dashboard_perm == ''){
    alert('Please Select Admission Form.');
    $('#admission_dashboard_perm').focus();
    return false;
} 
var fees_view_roll= $('#fees_view_roll').val();

var adm_from_details_phone= $('#adm_from_details_phone').val();
var office_address = $('#office_address').val();
var all_due_fee_pem = $('#all_due_fee_pem').val();
var all_admission_perm = $('#all_admission_perm').val();
var emp_set_discount = $('#emp_set_discount').val();
var all_fee_pay_pem = $('#all_fee_pay_pem').val();
var insentive = $('#insentive').val();
var username = $('#username').val();
var serach_leads_adm = $('#serach_leads_adm').val();
if(username == ''){
    alert('Please enter Username.');
    $('#username').focus();
    return false;
}
var password = $('#password').val();
if(password == ''){
    alert('Please enter Password.');
    $('#password').focus();
    return false;
}
var repassword = $('#re-password').val();
if(repassword == '' ){
    alert('Please enter re- password.');
    $('#re-password').focus();
    return false;
}
if(password != repassword && action == 'insert'){
    alert("Password does not match.");
    $('#re-password').focus();
    return false;
}
if(username == 'admin'){
    alert('Please choose different username.');
    $('#username').focus();
    return false;
}

if(action == 'update'){
    var id = $('.id').val();
} else  {
    id = '';
}

 $.post(
 '../ajax/add.php'  ,
{
'type':'add_branch','password':password,'email':email,'phone':phone,'username':username,
'city':city,'address':address,'first_name':first_name,'last_name':last_name,
'branch_name':branch_name,'action':action,'role':role,'admission_frm_perm':admission_frm_perm, 'admission_dashboard_perm':admission_dashboard_perm,'id':id,'branch_id':branch_id,'category':category,'fees_view_roll':fees_view_roll,'adm_from_details_phone': adm_from_details_phone,'emp_set_discount':emp_set_discount,'all_due_fee_pem':all_due_fee_pem,'all_admission_perm':all_admission_perm,'all_fee_pay_pem':all_fee_pay_pem,'insentive':insentive,
'status':$('#status').val(),'salary':$('#salary').val(),'office_address':office_address,'serach_leads_adm':serach_leads_adm
},
function(data)
{ 
  if(data==1){
     alert('Record saved!!!');     
   //alert("Already exist");
//        $('#category').focus();
		$("#bprc").addClass("alert-error");
		$("#bprc").children("h4").text("Already exist");
		$("#bprc").show("fast").delay(3000);
		$("#bprc").hide("fast");
		$('#category').addClass("errTxt");
	    window.location.reload();   
        return true;
    }else {
   
		$("#bprc").removeClass("alert-error");
		$("#bprc").removeClass("alert-info");
		$("#bprc").addClass("alert-success");
		$("#bprc").children("h4").text("Branch Has Been Successfully Added");
		$("#bprc").show("fast").delay(8000);
		$("#bprc").hide("fast").delay(8000);
	if(action == 'update'){
    		window.location.href="managebranch.php?prs=1";   
		} else  {
			window.location.reload();   
		}
  
    }
}
)

/* Paging Code*/
$("#nofr").change(function(){
	var slV=$(this).val();
	//alert("helloo");
	window.location.href='querydetail.php?nfr='+slV;
	});
/* Paging Code*/
    });

$("#signupForm").submit(function(){ 
                             //    alert("helloo")  ;
			//  $(this).children("input").each(function(index, element) {
                    //alert("hii");
                });


});

   
/* Reset Password  */
$(document).ready(function(){
	  $("#resetUserPassword").click(function(){
		 var countFldd = 0; 
		  $("input[type=password]").each(function(){
			  if($(this).val().length == 0){
				  $(this).css("border" , "1px solid red");
				  countFldd++;
			  }else{
				  $(this).css("border" , "1px solid green");
			  }
		  });
		  if( countFldd == 0){
			  if($("#newPassword").val() != $("#confirmNewPassword").val())
				  {		  
				     $("#newPassword").css("border" , "1px solid red");
				     $("#confirmNewPassword").css("border" , "1px solid red");		     
		             return false;
				  }else{
					 $("#newPassword").css("border" , "1px solid green");
					 $("#confirmNewPassword").css("border" , "1px solid green");		  	
		              }
		       $.post(
		               '../ajax/resetpass.php',
		               {'currentPassword' : $("#currentPassword").val(), 'newPassword' : $("#newPassword").val(), 'confirmNewPassword' : $("#confirmNewPassword").val()},
		               function(respData){
		            	   switch(respData){
		            	   case 'cpass':
		            		   $("#errDivPass").css("display" ,"block");
		            		   $("#errDivPassText").text("Current Password Is Incorrect");
		            		   break;
		            	   case 'pass':
                                           var passChnageText = 'Your Password Has Been Change. <a href="http://www.expertinstituteindia.in/"> Click Here </a> To Login Again';
		            		   $("#errDivPass").css("display" ,"block");
		            		   $("#errDivPassText").html(passChnageText);		            		   
		            		   break;
		            		default:
		            		   $("#errDivPass").css("display" ,"none");
		            		   $("#errDivPassText").text("");		            			
		            		   break;
		            	   }
		                    
		                 } 
		             );
		  } 
	  });
	});
/* Reset Password  */
/* Due Fee Followups */
$(document).ready(function(){
	$("#add_message_due_fee").click(function(){
		
		var message = $.trim($('#followRemkDueFee').val());
		//var followupD = $.trim($('#dateFollup').val());
		//var timeFollup = $.trim($('#timeFollup').val());
		var followupD = "NULL";
		var timeFollup = "NULL";
		//var followType = $.trim($('#followType').val());
		var followType = "NULL";
		//var status = $.trim($('#statusDueFee').val());
		var nextFollowRemark = "NULL";
		//var nextFollowRemark = $.trim($('#nextFollowRemark').val());
		var dateFollupNxt = $.trim($('#dateFollupNxt_DueFee').val());
		///var timeFollupNxt = $.trim($('#timeFollupNxt').val());
		var regno = $.trim($('#regno').val());
		//alert(leadId);
		
		var pid = $('#pId').val();
		var redTab = $("#tbDl").text();
                
                 
		var accType =$(this).attr("account-type");

        var reqLoc =$(this).attr("req-loc"); 
        
        var reqdestination = $("#destination").val() != ''? $("#destination").val() : false; 
		var type = 'message_follow';
		if(message == ''){
		    alert('Please enter the message');
		    $('.messagedetail').focus();
		    return false;
		}
		if(followupD == ''){
		    alert('Please enter the followup date');
		    $('#date').focus();
		    return false;
		}		
		if(timeFollup == '' ){
		    alert('Please select the follow up time');
		    $('#timeFollup').focus();
		    return false;
		}
		 $.post(
		 '../ajax/add_Due_fee.php'  ,
		{
		'p_id':pid,'message':message,'remark':nextFollowRemark,'next_followup':dateFollupNxt,'regno':regno
		},
		function(data)
		{
		//alert(data); 
		$("#msgBxx").fadeIn("fast");
		$("#msgBxx").fadeOut("slow");
		          if(reqdestination == 'a'){
		            window.location.href="../account/Duefee_All.php#"+redTab;
                   exit();    
		          }  
                  if(reqLoc == 'd'){
                  window.location.href="../superadmin/admissionDashboard.php";
                   exit();
                   }		    
                  if(accType == 'user'){
		  window.location.href="../account/Duefee.php#"+redTab;
                    }else{
                  window.location.href="../superadmin/Duefee.php#"+redTab;
                   }
		}
		);
	});
});

