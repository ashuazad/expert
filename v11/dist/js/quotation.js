/* Search Code */
var baseUrl = 'https://www.advanceinstitute.co.in';
var currentTime = new Date().getTime();
let editData = {};
let api = 'getSMSTemplates.php';
let templates = {};

const alertMessage = (data) => {
    $.toast({
        heading: data.heading,
        text: data.message,
        position: 'top-right',
        loaderBg:'#ff6849',
        icon: 'info',
        hideAfter: 3000,
        stack: 6
    });
}

const clearForm = () => {
    $('.form-control').each(function () {
        if ($(this).prop("tagName") === 'select') {
            $(this).children('option').each(function () {
                $(this).prop('selected', false)
            })
        }
        $(this).val('');
    })
}

function loadCourses(){
    var settings = {
        "async": false,
        "crossDomain": true,
        "url": baseUrl + "/api_v1/index.php/Registration/get_courses" + "?&_=" + currentTime,
        "method": "GET",
        "headers": {
            "cache-control": "no-cache"
        }
    }

    $.ajax(settings).done(function (response) {
        var courses = JSON.parse( response );
        var courseSelectBox = '<option value = "">Select Course</option>';
        if(courses.success == true){
            $.each( courses.data, function( key, value ) {
                courseSelectBox += '<option value = "'+ value.course +'" >' + value.course.replace( /-/g ,' ' ) + '</option>';
            });
            $(".courses-quotation").html(courseSelectBox);
        }
    });
}

$(".select2").change(function(){
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
            console.log(data);
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
//Change Discount
$("#disAmt").blur(function(){
    $("#total_fee").val( $("#course_fee").val() - $(this).val() );
});
//
/// Default Load ///
$(function () {
    $(".select2").select2();
});
loadCourses();
/// Default Load ///