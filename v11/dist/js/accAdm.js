var baseUrl = 'https://www.advanceinstitute.co.in';
var records = new Object();
var addInsentive = 0;
var currentTime = new Date().getTime();
var recordId = null;
var recordRegno = null;
var params={};
var pageInfo={};
var noOfrows = 0;
var noOfpgs=0;
var pageNo = 1;
var admStatus = {};
var admRecords={};
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
//Get user permissions
var userPermissions = JSON.parse($("#accDueFeesTag").attr('data-acc'));
console.log(userPermissions);
//Get user permissions
function setNumRecords(data, eleId){
    noOfrows = data;
    noOfpgs = Math.ceil(noOfrows/$("#nofrows").children("option:selected").val());
    $("#"+eleId).html(data);
    enableDisablePageLink();
}
if (userPermissions.view_emp_admissions || userPermissions.view_branch_admissions) {
    $('#amdTableHeadTr').append('<th>Branch Name</th><th>User</th>');
}
function setPageInfo(actions){
    switch(actions){
        case 'Next':
            pageNo = parseInt($("#pageNum").attr("data-page-no")) + 1;
            $("#pageNum").attr("data-page-no",pageNo);
            break;
        case 'Prev':
            pageNo = parseInt($("#pageNum").attr("data-page-no")) - 1;
            $("#pageNum").attr("data-page-no",pageNo);
            break;
        case 'Last':
            pageNo = noOfpgs;
            $("#pageNum").attr("data-page-no",pageNo);
            break;
        case 'First':
            pageNo = 1;
            $("#pageNum").attr("data-page-no",pageNo);        
            break;
    }
    $("#pageNum").text(pageNo);
}

function enableDisablePageLink()
{
    if (pageNo >= noOfpgs) {
        $('#liNext').addClass('disabled');
        $('#liLast').addClass('disabled');
    }
    
    if (pageNo == 1) {
        $('#liFirst').addClass('disabled');
        $('#liPrev').addClass('disabled');
        $('#liNext').removeClass('disabled');
        $('#liLast').removeClass('disabled');
    }
    
    if (pageNo < noOfpgs && pageNo > 1) {
        $('#liNext').removeClass('disabled');
        $('#liLast').removeClass('disabled');
        $('#liFirst').removeClass('disabled');
        $('#liPrev').removeClass('disabled');
    }
}

function getPageInfo()
{
    var data = {};
    data.page=$("#pageNum").attr("data-page-no");
    data.nofpg=$("#nofrows").children("option:selected").val();
    return data; 
}

function getParams()
{
    var data = {};
    data.date = {fromDate:$(".from-date").val(),toDate:$(".to-date").val()};
    data.phone = $(".phone").val();
    data.amount = $(".amount").val();
    data.course = $("#inputCourses").children("option:selected").val();
    data.fee_status = $(".search-fee-status").children("option:selected").val();
    return data;
}

/*function getFollowUpdata(aId)
{
    var data = {};
    data.remark = $(".followup-remarks").children("option:selected").val();
    data.message = (isRemark)?$(".followup-remarks").children("option:selected").val():$(".followup-message").val();
    data.next_followup = $(".followup-date").val();
    data.fee_status = $(".followup-status").val();
    data.a_id = aId;
    return data;
}*/

function getRequestData(){
    var requestData = {"params":getParams(),"pageInfo":getPageInfo()};
    return requestData;
}

function getList(){
    var list =  null;
    $.ajax({
        url: baseUrl + '/ajax/getAccAdmTab.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify(getRequestData()),
        success : function( data ){
            setNumRecords(data.nofrows, 'records');
            renderTable( data.rows , 'amdTable');
        }
    });
}

function getTRTD(record){
    var TD = '<tr>';
    TD += '<td>'+record.roll_no+'</td>';
    TD += '<td><span class="followupAdm" data-regno="'+record.regno+'" data-row="'+record.a_id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal">'+record.name+'</span></td>';
    TD += '<td>'+record.phone+'</td>';
    TD += '<td>'+record.admDate+'</td>';
    TD += '<td>'+record.dueDate+'</td>';
    var moretxt = '';
    moretxt = (record.courses.length>22)?'...':'';
    TD += '<td data-toggle="tooltip" data-placement="top" title="'+record.courses+'">'+record.courses.substring(0,22)+moretxt+'</td>';
    TD += '<td>'+record.total_fee+'</td>';
    TD += '<td>'+record.credit_amt+'</td>';
    TD += '<td>'+record.due_fee+'</td>';
    var moretxtRemark = '';
    moretxtRemark = (record.message.length>22)?'...':'';
    var toltipAttr = 'data-toggle="tooltip" data-placement="top" title="'+record.message+'"';
    TD += '<td><a class="followupHistry" data-regno="'+record.regno+'" data-row="'+record.regno+'" href="javascript:void(0)" data-toggle="modal" data-target="#grid-modal-followups"'+toltipAttr+'>'+record.message.substring(0,22)+moretxtRemark+'</a></td>';
    if (userPermissions.view_emp_admissions || userPermissions.view_branch_admissions) {
        TD += '<td>'+record.branch_name+'</td>';
        TD += '<td>'+record.emp_name+'</td>';
    }
    TD += '</tr>';
    return TD;
}

function renderTable( record , elementId){
    var tableBody = '';
    
    var records = {};
    for(var i = 0; i < record.length; i++) {
       // name = '<td>' + record[i].name + '</td>';
        tableBody += getTRTD(record[i]);
        admRecords[record[i].a_id] = record[i];
    }
    console.log(admRecords);
    $("#"+elementId).html(tableBody);
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
            $("#inputCourses").html(courseSelectBox);
        }
    });
}

function setGuageValue(data){
    $(".today-pending").text(data.today_pending.value);
    $(".all-pending").text(data.all_pending.value);
    $(".all-booking").text(data.all_booking.value);
    $(".all-adm").text(data.all_admission.value);
}

function getAdmStatus(){
     $.ajax({
        url: baseUrl + '/ajax/getAccAdmStatus.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
         //   console.log(data);
            admStatus = data;
            setGuageValue(data);   
        }
    });
}

function printReciept(fId){
    window.open(baseUrl + "/account/scriptrecpt/index.php?f_id="+fId, "Recept", "width=1100 height=900");
}

function clearSearchForm(){
    $('.form-search input').each(function(){$(this).val('');});
    $('.form-search select > option').each(function(){
        if($(this).is(':selected')){
            $(this).removeAttr('selected');
        }
    });
}

function saveFollowup( data ){
     $.ajax({
        url: baseUrl + '/ajax/addFollowup.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify(data),
        success : function( data ){
            if(!data.success){
                displayError(data.errors, '.display-errors');
            }
            if(data.success){
                getFollowupHistory( data.regno, 3, '.last-followups')
                clearfollowupForm();
                displaySuccessMsg('Followup successfully done.','.display-success');
                displaySuccessMsgToast('Followup', 'Followup has been successfully done.')
                clearErrors('.display-errors');
                clearRemark('.followup-remarks');
                clearRemark('.followup-status');
                getAdmStatus();
                getList();
                closeFollowupBox('#closeFollowupPopup');
            }
        }
    });
}

function reloadList() {
    getList();
}
getAdmStatus();
getList();
loadCourses();
getFeeStatus('.followup-status');

$("#resetPage").click(function(){
    clearSearchForm();
    getList();    
});

$("#nofrows").click(function(){
    clearSearchForm();
    getList();    
});

$(".search").click(function(){
    getList();
});

$("#Next").click(function(){
    setPageInfo('Next');
    getList();
});
$("#Prev").click(function(){
    setPageInfo('Prev');
    getList();
});
$("#Last").click(function(){
    setPageInfo('Last');
    getList();
});
$("#First").click(function(){
    setPageInfo('First');
    getList();
});

$("body").on("click", ".followupAdm", function(){
   resetFollowupsHeading();
   clearfollowupForm();    
   clearSuccessMsg('.display-success');
   clearErrors('.display-errors');
   clearRemark('.followup-remarks');
   clearRemark('.followup-status');
   recordRegno = $(this).attr("data-regno");
   recordId = $(this).attr("data-row");
   setFollowupsHeading(admRecords[recordId]);
   getRemarkMsg();
   getFollowupHistory(recordRegno, 3, '.last-followups');
   getFeeReciept(recordId);
});

$("body").on("click", ".followupHistry", function(){
   recordRegno = $(this).attr("data-regno");
   getFollowupHistory(recordRegno, 0, '.all-followups');
});

$("#saveFollowup").click(function(){
    saveFollowup(getFollowUpdata(recordId));
});

//
$("#addMessage").click(function() {
    toggleRemarkMessage("#rowRemark", "#rowMessage", true);
});

$("#addRemark").click(function(){
    toggleRemarkMessage("#rowRemark", "#rowMessage", false);
});
