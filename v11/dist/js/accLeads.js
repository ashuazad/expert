/* Search Code */
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
var leadsRecords={};
var closeButtonHtml = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var isRemark = true;
var api_Allpending = baseUrl + '/ajax/getAccDueFees.php?_=' + currentTime;
//Due Fees
var admRecords={};

function setStatus(data)
{
    $(".todayPendingCount").text(data.TODAY_PENDING);
    $(".allPendingCount").text(data.ALL_PENDING);
    $(".todayDoneCount").text(data.TODAY_DONE);
    $(".todayNewCount").text(data.TODAY_NEW);
    $(".allStatusCount").text(data.ALL_STATUS);
}

function loadTab(){
    $(".nav-link").each(function (){
       if($(this).hasClass( "active" )){
           $(this).trigger("click");
       }
    });
}

function getColumnDefinition(gridInfo)
{
    var definition = {};
    if (gridInfo == 'todaydone') {
        definition = { name: "last_followup_date", title: "Followup Date", type: "text", width: 120 }
    } else {
        definition = { name: "last_fees_date", title: "Last Receipt", type: "text", width: 120 }
    }
    return definition;
}

function setIpLocation(data)
{
    $('.lead-Ip-City').html(data.city);
    $('.lead-Ip-Country').html(data.country);
}

function getIpLocation(leadData)
{
    $.ajax({
        url: baseUrl + '/ajax/getIPLocation.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify({"ip":leadData.ip}),
        success : function(data){
            console.log(data);
            if (data.city != 'NONE' && data.country != 'NONE') {
                setIpLocation(data);
            } else {
                if(leadData.phone_location.length) {
                    setIpLocation(JSON.stringify({"city":leadData.phone_location,"country":"India"}));
                } else {
                    getPhoneLocation(leadData);
                }
            }
        }
    });
}

function getPhoneLocation(data)
{
    $.ajax({
        url: baseUrl + '/ajax/getPhoneLocation.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify({"phone":data.phone,"id":data.id}),
        success : function(data){
            console.log(data);
            setIpLocation(data);
        }
    });
}

function renderGrid(elementSel, gridInfo){
    var testGrid = $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: true,
        sorting: true,
        paging: true,
        autoload: !0,
        pageSize: 50,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){ return '';},
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    url: baseUrl + '/ajax/getAccLeads.php?_=' + currentTime + '&param=' + gridInfo,
                    dataType:'json',
                    data : filter
                });
            }
        },
        fields:getGridColumns(gridInfo)
    });
}

function getFollowupData(aId)
{
    var data = {};
    data.remark = $(".followup-remarks").children("option:selected").val();
    data.message = (isRemark)?$(".followup-remarks").children("option:selected").val():$(".followup-message").val();
    data.next_followup_date = $(".followup-date").val();
    data.status = $(".followup-status").val();
    data.id = aId;
    return data;
}

function saveFollowup( data ){
    data['pid'] = lastFollowUpId;
    $.ajax({
        url: baseUrl + '/ajax/addLeadFollowup.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify(data),
        success : function( data ){
            if(!data.success){
                displayError(data.errors, '.display-errors');
            }
            if(data.success){
                clearfollowupForm();
                displaySuccessMsg('Followup successfully done.','.display-success');
                displaySuccessMsgToast('Followup', 'Followup has been successfully done.')
                clearErrors('.display-errors');
                closeFollowupBox("#closeFollowupPopup");
                loadTab();
                getLeadStatus();
            }
        }
    });
}

function getLeadStatus()
{
    $.ajax({
        url: baseUrl + '/ajax/getAccLeadsStatus.php?_=' + currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
           console.log(data);
           setStatus(data);
        }
    });
}

function items(item)
{
    var itemsList = Array();
    itemsList['name'] = { name: "name", title: "Name", type: "text", width: 100, cellRenderer : function (value, item) {
            leadsRecords[item.id] =item;
            var tdInner = '';
            tdInner = '<td><span class="followupAdm" id="name-'+item.id+'" data-row="'+item.id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal">'+item.name+'</span></td>';
            return tdInner;
        }, css :"gird-cell-text-alignment" };
    itemsList['phone'] = { name: "phone", title: "Phone", type: "text", width: 100, cellRenderer:function (value, item) {
            var phoneText = value[0]+value[1]+'*****'+value[value.length-3]+value[value.length-2]+value[value.length-1];
            return '<td>' + phoneText + '</td>';
        }, css :"gird-cell-text-alignment"};
    itemsList['message'] = { name: "message", title: "Remark", type: "text", width: 140, cellRenderer:function (value, item) {
            var moretxtRemark = (item.message.length>22)?'...':'';
            var toltipAttr = 'data-toggle="tooltip" data-placement="top" title="'+item.message+'"';
            return  '<td><a class="followupHistry" data-row="'+item.id+'" href="javascript:void(0)" data-toggle="modal" data-target="#grid-modal-followups"'+toltipAttr+'>'+item.message.substring(0,22)+moretxtRemark+'</a></td>';
        }, css :"gird-cell-text-alignment"
    };
    itemsList['last_follow_up'] = { name: "last_follow_up", title: "Last Calling", type: "text", width: 100, css :"gird-cell-text-alignment" };
    itemsList['next_followup_date'] = { name: "next_followup_date", title: "Next Calling", type: "text", width: 60, css :"gird-cell-text-alignment" };
    itemsList['status'] = { name: "status", title: "Status", type: "text", width: 60, css :"gird-cell-text-alignment" };
    itemsList['email'] = { name: "email", title: "Email", type: "text", width: 100, cellRenderer : function (value, item) {
        return '<td id="email-'+item.id+'" >'+value+'</td>';
        }, css :"gird-cell-text-alignment"};
    itemsList['course'] = { name: "category", title: "Course", type: "text", width: 140, cellRenderer : function (value, item) {
            return '<td id="course-'+item.id+'" >'+value+'</td>';
        }, css :"gird-cell-text-alignment" };
    return itemsList[item];
}

function tabItems(tab)
{
    var tabItemsList = Array();
    tabItemsList['todaypending'] = ['name','phone','message','status','last_follow_up','next_followup_date'];
    tabItemsList['allpending'] = ['name','phone','course','message','status','last_follow_up','next_followup_date'];
    tabItemsList['todaynew'] = ['name','phone','course','last_follow_up','next_followup_date'];
    tabItemsList['todaydone'] = ['name','phone','course','message','status','last_follow_up','next_followup_date'];
    tabItemsList['allStatus'] = ['name','phone','course','message','status','last_follow_up','next_followup_date'];
    return tabItemsList[tab];
}

function getGridColumns(tabName){
    var itemList = tabItems(tabName);
    var itemLen = itemList.length;
    var objColumns = new Object();
    for(var i=0;i<itemLen;i++){
        objColumns[i] = items(itemList[i]);
    }
    //Control Column
    objColumns[i] = {
        type: "control",cellRenderer : function (value, item) {
            var tdInner = '<td></td>';
            return tdInner;
        },
        width: 10
    };
    return objColumns;
}

//Lead Edit
function enableEditField()
{
    $('.lead-edit .controls').show('fast');
    $('.lead-edit-row').hide('fast');
    $('.lead-save-button').show('fast');
    $('.lead-edit-button').hide('fast');
}

function setEditFields()
{
    $(".lead-edit-row").each(function (){
        $(this).siblings(".controls").children("input").val($(this).text())
    });
}

function getEditFieldData()
{
    var leadEditData = new Object();
    $(".lead-edit-row").each(function (){
        leadEditData[$(this).siblings(".controls").children("input").attr('name')] = $(this).siblings(".controls").children("input").val();
    });
    return leadEditData;
}

function updateLead(data, id)
{
    data['id'] = id;
    $.ajax({
        url: baseUrl + '/ajax/updateAccLead.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(data),
        success : function( data ){
            console.log(data);
            if (data.success) {
                updateLeadDataStore(data.data);
                updateRow(data.data);
                setLeadFollowupsHeading(leadsRecords[data.data.id]);
            }
        }
    });
}

function updateLeadDataStore(data)
{
    leadsRecords[data.id]['name'] = data.name;
    leadsRecords[data.id]['category'] = data.category;
    leadsRecords[data.id]['email'] = data.email;
    leadsRecords[data.id]['address'] = data.address;
}

function updateRow(data)
{
    $("#name-"+data.id).text(data.name);
    $("#email-"+data.id).text(data.email);
    $("#course-"+data.id).text(data.category);
    $("#address-"+data.id).text(data.address);
}

function clearEditFields()
{
    $(".lead-edit-row").each(function (){
        $(this).siblings(".controls").children("input").val('');
    });
}
//Lead Edit
// Set Income Status Start
function setIncomeStatus(data)
{
    $(".total-income").text(data.totalSalary);
}

function setIncentiveStatus(data)
{
    $(".approve-incentive").text(data.APPROVED);
    $(".pending-incentive").text(data.PENDING);
}

function getIncomeStatus()
{
    $.ajax({
        url: baseUrl + '/ajax/getSalary.php?_=' + currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
            setIncomeStatus(data);
        }
    });
    $.ajax({
        url: baseUrl + '/ajax/getAccIncentiveStatus.php?_=' + currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
            setIncentiveStatus(data);
        }
    });
}
// Set Income Status End

// Create Lead Start
function getLeadData(currentObj)
{
   var returnData = {};
   var formData = $(currentObj).serializeArray();
   for(var i=0;i<formData.length;i++){
       returnData[formData[i].name] = formData[i].value;
   }
   return returnData;
}
function createLead(data)
{
    $.ajax({
        url: baseUrl + '/ajax/addLeadV2.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(data),
        success : function( data ){
            console.log(data);
            if (data.success) {
                getLeadStatus();
                getLeadFollowupStatus();
                renderGrid('.todayPendingGrid', 'todaypending');
                //displaySuccessMsg('Lead Created Successfully.','.display-success');
                //displaySuccessMsgToast('Lead', 'Lead Created Successfully.')
                Swal.fire("Lead Saved!", "New Lead has been created successfully.", "success"); 
            }
        }
    });
}
// Create Lead End

// Courses Start
function renderCourses(data, element)
{
    var selectOprions='<option value="">Select Course</option>';
    for(var i=0; i<data.length;i++){
        selectOprions+='<option value="'+data[i].course+'">'+data[i].course.replaceAll('-',' ')+'</option>';
    }
    $(element).html(selectOprions);
}

function getCourses()
{
    $.ajax({
        url: baseUrl + '/ajax/getCourses.php?_=' + currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
            renderCourses(data, '.lead-courses');
        }
    });
}
// Courses End
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
        var courseSelectBox = '';
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
$("#disAmt").change(function(){
    $("#total_fee").val( $("#course_fee").val() - $(this).val() );
    $('#quotation_isEdited').val('1');
});
// Get Lead Quotation
const getLeadQuotation = (lead_id, leadRecords) => {
    //var leadsRecords[recordId]
    $.ajax({
        url: baseUrl + '/ajax/getLeadQuotation.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify({lead_id:lead_id}),
        success : function( data ){
            //console.log(data);
            if (data.length) {
                leadRecords[lead_id]['quotation-history'] = data;
                renderQuatation(data, '#currentQuotationTable');
                $('.quotation-history').show('fast');
            } else {
                $('.quotation-history').hide('fast');
            }
            //clearForm();
        }
    });    
}

const renderQuatation = (quotation, elementSelector) => {
    let data = null;
    let tr = "<tr><td colspan='6'>NONE</td></tr>";
    data = quotation.length?quotation[0]:null;
    if (quotation.length) {
        let printTd = '<td><a target="_blank" href="quotationPdf.php?id='+data.quotation_id+'"><i class="ti-printer"></i></a></td>';
        let statusCellClass = '';
        if (data.status == 'APPROVED') {
            statusCellClass = 'badge-success';            
        } else {
            statusCellClass = 'badge-danger';
        }
        let statusTd = '<td><span class="badge '+statusCellClass+'">'+data.status+'</span></td>';
        tr = "<tr><td>"+data.created_date+"</td><td>"+data.courses+"</td><td>"+data.total_price+"</td><td>"+data.discount+"</td><td>"+data.offer_price+"</td><td>"+data.first_name+"</td>"+statusTd+printTd+"</tr>";
    }
    $(elementSelector).html(tr);
}
//Get Quotation Form data
const getQuotationFormData = () => {
    $('#quotation-pdf')
} 
//PFD Button
$('.quotation-button').click(function(){
    $(".quotation-tab").tab('show');
    $('.quotation-history').hide('fast');
    $('.quotation-form').show('fast');
});

$('.quotation-tab').click(function(){
    if (leadsRecords[recordId]['quotation-history']) {
        $('.quotation-history').show('fast');
        //renderQuatation(leadsRecords[recordId]['quotation-history'], '#currentQuotationTable');
        getLeadQuotation(recordId, leadsRecords)
    } 
    if (!leadsRecords[recordId]['quotation-history']) {
        getLeadQuotation(recordId, leadsRecords)
    }
    $('.quotation-form').show('fast');
});

/// Default Load Start ///
//let multiSelectCoursesQuotation = null;
getLeadStatus();
getDueStatus('Lead');
getIncomeStatus();
getLeadFollowupStatus('.followup-status');
getLeadRemarkMsg();
renderGrid('.todayPendingGrid', 'todaypending');
getCourses();
getFeeStatus('.due-fees-followup-status');
$(function () {
    $(".select2").select2();
});
loadCourses();
/// Default Load End ///

$("#createLeadForm").submit(function(event) {
    console.log("createLeadForm");
    createLead(getLeadData(this));
    $("#grid-modal-newLead").modal('toggle');
    //return false;
    event.preventDefault();
});
/// Default Load ///

$("body").on('click', ".todayPendingNav", function (){
    renderGrid('.todayPendingGrid', 'todaypending');
});

$("body").on('click', ".allPendingNav", function (){
    renderGrid('.allPendingGrid', 'allpending');
});

$("body").on('click', ".todayNewNav", function (){
    renderGrid('.todayNewGrid', 'todaynew');
});

$("body").on('click', ".todayDoneNav", function (){
    renderGrid('.todayDoneGrid', 'todaydone');
});

$("body").on('click', ".allStatusNav", function (){
    renderGrid('.allStatusGrid', 'allStatus');
});

$("body").on('click', ".allPendingNavDueFee", function (){
    renderDueFeeGrid('.allPendingDueFeeGrid', 'allpending');
});

const clearQuotationForm = () => {
    $('#course_fee').val('');
    $('#total_fee').val('');
    $('#disAmt').val('');
    //$('#quotation_lead_name').val('');
    //$('#quotation_lead_phone').val('');
    $('#quotation_isEdited').val('0');
    //$('#quotation_lead_id').val('');
    //$('').val('');
   // multiSelectCoursesQuotation.empty();
    $('.select2').val(null).trigger('change');
}

$("body").on("click", ".followupAdm", function(){
    disableEditField();
    clearRemark('.followup-remarks');
    clearRemark('.followup-status');
    resetFollowupsHeading();
    clearfollowupForm();
    clearSuccessMsg('.display-success');
    clearErrors('.display-errors');
    clearFollowups('.last-followups');
    //recordRegno = $(this).attr("data-regno");
    recordId = $(this).attr("data-row");
    setLeadFollowupsHeading(leadsRecords[recordId]);
    getIpLocation(leadsRecords[recordId]);
    getLeadFollowupHistory(recordId, 3, '.last-followups');
   // getLeadQuotation(recordId, leadsRecords);
    clearQuotationForm();
});

$("body").on("click", ".followupHistry", function(){
    clearFollowups('.all-followups');
    var LeadId = $(this).attr("data-row");
    getLeadFollowupHistory(LeadId, 0, '.all-followups');
});

//Due Fees Followup
//Render Due Fees Grid
function renderDueFeeGrid(elementSel, gridInfo){
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
                    url: baseUrl + '/ajax/getAccDueFees.php?_=' + currentTime + '&param=' + gridInfo,
                    dataType:'json',
                    data : filter
                });
                /*//debugger;
                console.log(loadData.data);
                return loadData.data;*/
            }
        },
        fields: [
            { name: "roll_no", title: "Roll No", type: "text", width: '4%', css :"gird-cell-text-alignment" },
            { name: "name", title: "Name", type: "text", width: '10%', cellRenderer : function (value, item) {
                    admRecords[item.a_id] =item;
                    var tdInner = '';
                    tdInner = '<td><span class="followupAdmDueFee" data-regno="'+item.regno+'" data-row="'+item.a_id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal-due-fee">'+item.name+'</span></td>';
                    return tdInner;
                }, css :"gird-cell-text-alignment"  },
            { name: "phone", title: "Phone", type: "text", width: '10%',cellRenderer:function (value, item) {
                var phoneText = value[0]+value[1]+'*****'+value[value.length-3]+value[value.length-2]+value[value.length-1];
                return '<td>' + phoneText + '</td>';
            }, css :"gird-cell-text-alignment" },
            { name: "courses", title: "Courses", type: "text", width: '14%', css :"gird-cell-text-alignment" },
            { name: "total_fee", title: "Total Fee", type: "text", width: '9%', css :"gird-cell-text-alignment"  },
            { name: "credit_amt", title: "Credit Fee", type: "text", width: '9%', css :"gird-cell-text-alignment"  },
            { name: "due_fee", title: "Due Fee", type: "text", width: '9%', css :"gird-cell-text-alignment"  },
            { name: "message", title: "Remark", type: "text", width: '10%', cellRenderer:function (value, item) {
                    var moretxtRemark = (item.message.length>22)?'...':'';
                    var toltipAttr = 'data-toggle="tooltip" data-placement="top" title="'+item.message+'"';
                    return  '<td><a class="dueFeeFollowupHistory" data-regno="'+item.regno+'" data-row="'+item.regno+'" href="javascript:void(0)" data-toggle="modal" data-target="#grid-modal-due-fee-followups"'+toltipAttr+'>'+item.message.substring(0,22)+moretxtRemark+'</a></td>';
                }, css :"gird-cell-text-alignment" 
            },
            { name: "last_followup_date", title: "Followup Date", type: "text", width: 120, css :"gird-cell-text-alignment"  },
            { name: "dueDate", title: "Due Date", type: "text", width: '9%' , css :"gird-cell-text-alignment" },
            {
                type: "control",cellRenderer : function (value, item) {
                    var tdInner = '';
                    tdInner = '<td></td>';
                    return tdInner;
                }
            }
        ]
    });

}
// Display Due Fee Followup Modal
$("body").on("click", ".followupAdmDueFee", function(){
    resetFollowupsHeading();
    clearfollowupForm();
    clearRemark('.due-fees-followup-remarks');
    clearRemark('.due-fees-followup-status');
    clearSuccessMsg('.display-success');
    clearErrors('.display-errors');
    recordRegno = $(this).attr("data-regno");
    recordId = $(this).attr("data-row");
    setFollowupsHeading(admRecords[recordId]);
    getRemarkMsg('.due-fees-followup-remarks');
    getFollowupHistory(recordRegno, 3, '.last-followups');
    getFeeReciept(recordId);
});

// Get Due Fees FollowUp Data
function getDueFollowUpdata(aId)
{
    var data = {};
    data.remark = $(".due-fees-followup-remarks").children("option:selected").val();
    data.message = (isRemark)?$(".due-fees-followup-remarks").children("option:selected").val():$(".due-fees-followup-message").val();
    data.next_followup = $(".due-fees-followup-date").val();
    data.fee_status = $(".due-fees-followup-status").val();
    data.a_id = aId;
    return data;
}

// Save Due Fees FollowUp Data
function saveAdmDueFollowup( data ){
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
                clearfollowupForm();
                displaySuccessMsg('Followup successfully done.','.display-success');
                displaySuccessMsgToast('Followup', 'Followup has been successfully done.')
                clearErrors('.display-errors');
                closeFollowupBox("#closeAdmDueFollowupPopup");
                loadTab();
                getDueStatus('Lead');
               // getDueStatus();
            }
        }
    });
}

//Trigger Save Due Fee Followup Data
$("#saveAdmDueFollowup").click(function(){
    saveAdmDueFollowup(getDueFollowUpdata(recordId));
});

//Get All Followups of Due Fees
$("body").on("click", ".dueFeeFollowupHistory", function(){
    clearFollowups('.due-fee-all-followups');
    recordRegno = $(this).attr("data-regno");
    getFollowupHistory(recordRegno, 0, '.due-fee-all-followups');
});
//Due Fees Followup

$("#saveFollowup").click(function(){
    saveFollowup(getFollowupData(recordId));
});

$('.lead-edit-button').click(function (){
    setEditFields();
    enableEditField();
});

$('.lead-save-button').click(function (){
    disableEditField();
    updateLead(getEditFieldData(),recordId);
});

function printReciept(fId){
    window.open(baseUrl + "/account/scriptrecpt/index.php?f_id="+fId, "Recept", "width=1100 height=900");
}
//Due Fees Remark & Text Message Box toggle
$("#addMessageDueFees").click(function() {
    toggleRemarkMessage("#rowRemarkDueFee", "#rowMessageDueFee", true);
});

$("#addRemarkDueFees").click(function(){
    toggleRemarkMessage("#rowRemarkDueFee", "#rowMessageDueFee", false);
});

//Lead Remark & Text Message Box toggle
$("#addMessage").click(function() {
    toggleRemarkMessage("#rowRemark", "#rowMessage", true);
});

$("#addRemark").click(function(){
    toggleRemarkMessage("#rowRemark", "#rowMessage", false);
});