/* Search Code */
var baseUrl = 'https://www.advanceinstitute.co.in';
var records = new Object();
var addInsentive = 0;
var currentTime = new Date().getTime();
var recordId = null;
var recordRegno = null;
var params={};
var pageInfo={};
var admStatus = {};
var admRecords={};
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var isRemark = true;
var api_accDueFees = 'getAccDueFees.php';
var api_adminDueFees = 'getAdminDueFees.php';
var isSuperAdmin = false;
var currentTab = new Object();
var isFilterApplied = false;
var filterData = new Object();
function loadTab(){
    $(".nav-link").each(function (){
       if($(this).hasClass( "active" )){
           $(this).trigger("click");
       }
    });
}
isSuperAdmin = ($("#dueFee-Script").attr('data-l-type')=='SUPERADMIN');
//Get user permissions
if (!isSuperAdmin) {
    var userPermissions = JSON.parse($("#accDueFeesTag").attr('data-acc'));
    console.log(userPermissions);
}
//Get user permissions
function getFilterData()
{   
    filterData.fromDate = $('.from-date').val();
    filterData.toDate = $('.to-date').val();
    filterData.empId = $('.office-emp').val();
    filterData.phone = $('.amd-phone').val();
    isFilterApplied = true;
    return $.param(filterData);
}

function clearFilter()
{
    $('.from-date').val('');
    $('.to-date').val('');
    $('.office-emp').html('<option value="">Employee</option>');
    $('.office-branch').val('');
    $('.amd-phone').val();

    filterData.fromDate = null;
    filterData.toDate = null;
    filterData.empId = null;
    filterData.phone = null;

    isFilterApplied = false;
}

function adminColumnDefinition() {
    var columnDefinition = {};
    columnDefinition = [
        { name: "roll_no", title: "Roll No", type: "text", width: 60,css :"text-center"},
        { name: "name", title: "Name", type: "text", width: 100, css :"text-center", cellRenderer : function (value, item) {
                admRecords[item.a_id] =item;
                var tdInner = '';
                tdInner = '<td><span class="followupAdm" data-regno="'+item.regno+'" data-row="'+item.a_id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal">'+item.name+'</span></td>';
                return tdInner;
            } },
        { name: "phone", title: "Phone", type: "text", width: 100, css :"text-center"},
        { name: "total_fee", title: "Total Fee", type: "text", width: 80, css :"text-center"},
        { name: "credit_amt", title: "Credit Fee", type: "text", width: 80, css :"text-center"},
        { name: "due_fee", title: "Due Fee", type: "text", width: 70, css :"text-center"},
        { name: "message", title: "Remark", type: "text", width: 140, css :"text-center", cellRenderer:function (value, item) {
                var moretxtRemark = (value.length>22)?'...':'';
                var toltipAttr = 'data-toggle="tooltip" data-placement="top" title="'+item.message+'"';
                return  '<td><a class="followupHistry" data-regno="'+item.regno+'" data-row="'+item.regno+'" href="javascript:void(0)" data-toggle="modal" data-target="#grid-modal-followups"'+toltipAttr+'>'+checkEmptyData(value).substring(0,22)+moretxtRemark+'</a></td>';
            }
        },
        { name: "last_followup_date", title: "Followup Date", type: "text", width: 110, css :"text-center", cellRenderer:function (value, item) {
                var tdInner = '<td>';
                tdInner += checkEmptyData(value) + '</td>';
                return tdInner;
            } },
        { name: "dueDate", title: "Due Date", type: "text", width: 80, css :"text-center"},
        { name: "user_name", title: "Admission User", type: "text", width: 120, css :"text-center"},
        { name: "followup_user", title: "Calling User", type: "text", width: 120, css :"text-center"},
        {
            type: "control",cellRenderer : function (value, item) {
                var tdInner = '';
                tdInner = '<td></td>';
                return tdInner;
            }
        }
    ];
    return columnDefinition;
}

function accColumnDefinition() {
    var columnDefinition = {};
    columnDefinition = [
        { name: "roll_no", title: "Roll No", type: "text", width: 50,css :"text-center"},
        { name: "name", title: "Name", type: "text", width: 100, css :"text-center",cellRenderer : function (value, item) {
                admRecords[item.a_id] =item;
                var tdInner = '';
                tdInner = '<td><span class="followupAdm" data-regno="'+item.regno+'" data-row="'+item.a_id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal">'+item.name+'</span></td>';
                return tdInner;
            } },
        { name: "phone", title: "Phone", type: "text", width: 100,css :"text-center" },
        { name: "courses", title: "Courses", type: "text", width: 100, css :"text-center"},
        { name: "total_fee", title: "Total Fee", type: "text", width: 100, css :"text-center"},
        { name: "credit_amt", title: "Credit Fee", type: "text", width: 100, css :"text-center"},
        { name: "due_fee", title: "Due Fee", type: "text", width: 80, css :"text-center" },
        { name: "message", title: "Remark", type: "text", width: 100,css :"text-center", cellRenderer:function (value, item) {
                var moretxtRemark = (item.message.length>22)?'...':'';
                var toltipAttr = 'data-toggle="tooltip" data-placement="top" title="'+item.message+'"';
                return  '<td><a class="followupHistry" data-regno="'+item.regno+'" data-row="'+item.regno+'" href="javascript:void(0)" data-toggle="modal" data-target="#grid-modal-followups"'+toltipAttr+'>'+item.message.substring(0,22)+moretxtRemark+'</a></td>';
            }
        },
        { name: "last_followup_date", title: "Followup Date", type: "text", width: 120,css :"text-center" },
        { name: "dueDate", title: "Due Date", type: "text", width: 100, css :"text-center" }
    ];
    if (!isSuperAdmin && (userPermissions.view_emp_admissions || userPermissions.view_branch_admissions)) {
        columnDefinition[columnDefinition.length] = { name: "branch_name", title: "Branch Name", type: "text", width: 80,css :"text-center" };
        columnDefinition[columnDefinition.length] = { name: "emp_name", title: "User", type: "text", width: 80,css :"text-center" };
    }
    columnDefinition[columnDefinition.length] = {
        type: "control",cellRenderer : function (value, item) {
            var tdInner = '';
            tdInner = '<td></td>';
            return tdInner;
        }
    };
    return columnDefinition;
}

function getColumnDefinition()
{
    if (isSuperAdmin) {
        return adminColumnDefinition();
    } else {
        return accColumnDefinition();
    }
}
function renderGrid(elementSel, gridInfo, filterOpt=''){
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: true,
        sorting: true,
        paging: true,
        autoload: !0,
        pageSize: 10,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){ return '';},
        controller: {
            loadData: function(filter) {
                //var loadData = JSON.parse('{"data":[{"a_id":"4","regno":"210802AD1","roll_no":"3","name":"test","phone":"9999999999","courses":"Advance Mobile Course","total_fee":"20000","due_fee":"15000","admDate":"02-08-21","dueDate":"14-8-21","credit_amt":"5000","message":"He will come"},{"a_id":"2","regno":"210720AD1","roll_no":"2","name":"Mashkoor Ahmad malik","phone":"7051877247","courses":"Advance Mobile Course","total_fee":"25000","due_fee":"24700","admDate":"19-07-21","dueDate":"05-8-21","credit_amt":"300","message":"Fury"},{"a_id":"1","regno":"210718EX1","roll_no":"1","name":"Abhisek","phone":"9718888344","courses":"Desktop Course","total_fee":"15000","due_fee":"14700","admDate":"18-07-21","dueDate":"12-8-21","credit_amt":"300","message":"Call tomorrow for visit"}],"itemsCount":3}');
                return $.ajax({
                    url: baseUrl + '/ajax/'+ (isSuperAdmin?api_adminDueFees:api_accDueFees) +'?_=' + currentTime + '&param=' + gridInfo + '&' + filterOpt,
                    dataType:'json',
                    data : filter
                });
               /*//debugger;
               console.log(loadData.data);
               return loadData.data;*/
            }
        },
        fields: getColumnDefinition()
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
               // getFollowupHistory( data.regno, 3, '.last-followups')
                clearfollowupForm();
                displaySuccessMsg('Followup successfully done.','.display-success');
                displaySuccessMsgToast('Followup', 'Followup has been successfully done.')
                clearErrors('.display-errors');
                closeFollowupBox("#closeFollowupPopup");
                loadTab();
                getDueStatus();
            }
        }
    });
}
// Branch Render
function getBranch(renderElement){
    $.ajax({
        url: baseUrl + '/ajax/getBranch.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            renderBranch(data,renderElement);
        }
    });
}

function renderBranch(data,element)
{
    var options = '<option value="">Branch</option>';
    var dataCount = data.length;
    for(var i = 0; i < dataCount; i++) {
        options += '<option value="'+data[i].id+'">'+data[i].branch_name+'</option>';
    }
    $(element).html(options);
}
// Employee Render
$(".office-branch").change(function(){
    var brnchId = $(this).val();
    $.ajax({
        url: baseUrl + "/ajax/getKeyPerson.php?_=" + currentTime,
        type:"POST",
        data:{id:brnchId},
        success: function( dataHtml ){
            $(".office-emp").html(dataHtml);
        }
    });
});

/// Default Load ///
if (isSuperAdmin) {
    getBranch('.office-branch');
}
getDueStatus();
getFeeStatus('.followup-status');

currentTab.elementSel = '.todayPendingGrid';
currentTab.gridInfo = 'todaypending';
renderGrid('.todayPendingGrid', 'todaypending');
/// Default Load ///

$("body").on('click', ".todayPendingNav", function (){
    currentTab.elementSel = '.todayPendingGrid';
    currentTab.gridInfo = 'todaypending';
    renderGrid('.todayPendingGrid', 'todaypending', (isFilterApplied)?getFilterData():'');
});

$("body").on('click', ".allPendingNav", function (){
    currentTab.elementSel = '.allPendingGrid';
    currentTab.gridInfo = 'allpending';
    renderGrid('.allPendingGrid', 'allpending',(isFilterApplied)?getFilterData():'');
});

$("body").on('click', ".todayDoneNav", function (){
    currentTab.elementSel = '.todayDoneGrid';
    currentTab.gridInfo = 'todaydone';
    renderGrid('.todayDoneGrid', 'todaydone',(isFilterApplied)?getFilterData():'');
});

$("body").on('click', ".allBookingNav", function (){
    currentTab.elementSel = '.allBookingGrid';
    currentTab.gridInfo = 'allbooking';
    renderGrid('.allBookingGrid', 'allbooking',(isFilterApplied)?getFilterData():'');
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

$(".filter").click(function (){
    renderGrid(currentTab.elementSel, currentTab.gridInfo, getFilterData());
});

$(".clear-filter").click(function (){
    clearFilter();
    renderGrid(currentTab.elementSel, currentTab.gridInfo, '');
});

$("#saveFollowup").click(function(){
    saveFollowup(getFollowUpdata(recordId));
});

function printReciept(fId){
    window.open(baseUrl + "/account/scriptrecpt/index.php?f_id="+fId, "Recept", "width=1100 height=900");
}

$("#addMessage").click(function() {
    toggleRemarkMessage("#rowRemark", "#rowMessage", true);
});

$("#addRemark").click(function(){
    toggleRemarkMessage("#rowRemark", "#rowMessage", false);
});

//Call Now Api
$('.call-now-btn').click(function(){
    $(this).hide('fast');
    $.ajax({
        url: baseUrl + '/ajax/callNowApi.php?_=' + currentTime + '&phone=' + admRecords[recordId].phone_full,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify({}),
        success : function( data ){
            Swal.fire('Call Now', 'Call has been successfully made.', "success"); 
            $('.call-now-btn').show('fast');
        }
    });
});