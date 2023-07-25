var isRemark = true;
var currentTime = new Date().getTime();
var lastFollowUpId = null;
var searchDataSet = {};
function getFollowUpdata(aId)
{
    var data = {};
    data.remark = $(".followup-remarks").children("option:selected").val();
    data.message = (isRemark)?$(".followup-remarks").children("option:selected").val():$(".followup-message").val();
    data.next_followup = $(".followup-date").val();
    data.fee_status = $(".followup-status").val();
    data.a_id = aId;
    return data;
}

function displaySuccessMsg(msg, elementObj)
{
    $(elementObj).html('<div class="alert alert-success alert-rounded">'+msg+' '+closeButtonHtml+'</div>');
}

function displaySuccessMsgToast(msgHeading, msg)
{
    $.toast({
        heading: msgHeading,
        text: msg,
        position: 'top-right',
        loaderBg:'#ff6849',
        icon: 'info',
        hideAfter: 3000,
        stack: 6
    });
}

function closeFollowupBox(elementSelect)
{
    $(elementSelect).trigger("click");
}

function resetFollowupsHeading()
{
    $('.adm-dtl').html('');
}

function clearfollowupForm(){
    $('.form-followup input').each(function(){$(this).val('');});
}

function clearSuccessMsg(elementObj)
{
    $(elementObj).html('');
}

function clearErrors(elementObj)
{
    $(elementObj).html('');
}

function setFollowupsHeading(data)
{
    $('.adm-name').html(data.name);
    $('.adm-phone').html(data.phone_full);
    $('.adm-total-fees').html(data.total_fee);
    $('.adm-credit-fees').html(data.credit_amt);
    $('.adm-due-fees').html(data.due_fee);
    $('.adm-course').html(data.courses);
    $('.adm-reg-date').html(data.admDate);
    $('.adm-due-date').html(data.dueDate);
}

function getRemarkMsg(elementSelector = null){
    $.ajax({
        url: baseUrl + '/ajax/getRemTxtDueFeeV2.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            renderRemark(data, elementSelector);
        }
    });
}

function getRecieptTR(record)
{
    var TD = '<tr>';
    TD += '<td>'+record.f_id+'</td>';
    TD += '<td>'+record.recipt_date+'</td>';
    TD += '<td>'+record.amt+'</td>';
    TD += '<td>'+record.payment_mode+'</td>';
    TD += '<td>'+record.cheque+'</td>';
    TD += '<td>'+record.user+'</td>';
    TD += '<td><a href="javascript:void(0);" onclick="printReciept('+record.f_id+')"><i class="ti-printer"></i></a></td>';
    TD += '</tr>';
    return TD;
}

function renderReciept(records, element)
{
    var tableBody = '';
    for(var i = 0; i < records.length; i++) {
        tableBody += getRecieptTR(records[i]);
    }
    $(element).html(tableBody);
}

function getMessageLine(message){
    var messageLine = '';
    var nextCallingDateTxt = '<div class="row"><div class="col-md-6"><span class="badge badge-light text-right">Next Calling Date</span></div><div class="col-md-6 text-right"><span class="time">'+message.next_followup+'</span></div></div>'
    var messageTxt = (message.message.length>0)?'<h5>'+message.message+'</h5>':'';
    // messageTxt += (message.remark.length>0)?'<h5>'+message.remark+'</h5>':'';
    var callingDateTxt = '<span class="badge badge-light">Calling Date</span>';
    var colLeft = '<div class="col-md-6"><span class="badge badge-pill badge-success">'+message.status+'</span><br>'+callingDateTxt+'</div>';
    var colRight = '<div class="col-md-6 text-right"><span class="badge badge-primary">'+message.user+'</span><span class="time">'+message.followup+'</span></div>';

    messageLine = '<a href=""><div class="mail-contnet">'+nextCallingDateTxt+messageTxt+'<div class="row">'+colLeft+colRight+'</div></div></a>';

    return messageLine;
}

function getErrorLine(data)
{
    var errorLines = '';
    for(var i = 0; i < data.length; i++) {
        errorLines += '<div class="alert alert-danger alert-rounded">'+data[i]+closeButtonHtml+'</div>';
    }
    return errorLines;
}

function displayError(data, elementObj)
{
    $(elementObj).html(getErrorLine(data));
}

function renderFollowups( records, element ){
    var messageList = '';
    if (records.length) {
        for(var i = 0; i < records.length; i++) {
            messageList += getMessageLine(records[i]);
        }
    }
    $(element).html((messageList.length>0)?messageList:'<h4 class="text-warning">Followup History Not Found</h4>');
}

function clearFollowups(element ){
    $(element).html('<div class="spinner-grow" role="status">\n' +
        '                                                                              <span class="sr-only">Loading...</span>\n' +
        '                                                                            </div>\n' +
        '                                                                            <div class="spinner-grow text-primary" role="status">\n' +
        '                                                                              <span class="sr-only">Loading...</span>\n' +
        '                                                                            </div>\n' +
        '                                                                            <div class="spinner-grow text-secondary" role="status">\n' +
        '                                                                              <span class="sr-only">Loading...</span>\n' +
        '                                                                            </div>');
}

function renderRemark( records, elementSelector = null ){
    var options = '<option value="">Select Remark</option>';
    for(var i = 0; i < records.length; i++) {
        options += '<option value="'+records[i].remark+'">'+records[i].remark+'</option>';
    }
    var element = '';
    element = (elementSelector)?elementSelector:".followup-remarks";
    $(element).html(options);
}

function getFollowupHistory(regno, saveFlag = 0, elementId){
    var norows = '';
    norows = (saveFlag > 0)? '&r=3' : '';
    $.ajax({
        url: baseUrl + '/ajax/getAdmFollowups.php?_=' + currentTime + norows,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify({"regno":regno}),
        success : function( data ){
            console.log(data);
            renderFollowups(data, elementId);
        }
    });
}

function getFeeReciept(a_id)
{
    $.ajax({
        url: baseUrl + '/ajax/getFeeDetailsV2.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify({"a_id":a_id}),
        success : function( data ){
            console.log(data);
            renderReciept(data,"#feesRecieptTb");
        }
    });
}

function renderStatus( records, renderElement){
    var options = '<option value="">Select Status</option>';
    for(var i = 0; i < records.length; i++) {
        options += '<option value="'+records[i].status+'">'+records[i].status+'</option>';
    }
    $(renderElement).html(options);
    $(".search-fee-status").html(options);
}

function getFeeStatus(renderElement){
    $.ajax({
        url: baseUrl + '/ajax/getFeeStatus.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            renderStatus(data,renderElement);
        }
    });
}

/*$("#addMessage").click(function(){
    $("#rowRemark").hide('slow');
    $("#rowMessage").show('slow');
    isRemark = false;
});
$("#addRemark").click(function(){
    $("#rowMessage").hide('slow');
    $("#rowRemark").show('slow');
    isRemark = true;
});*/

function toggleRemarkMessage(remarkElement, messageElement, isMessage = false){
    if (isMessage) {
        $(remarkElement).hide('slow');
        $(messageElement).show('slow');
        isRemark = false;
    } else {
        $(messageElement).hide('slow');
        $(remarkElement).show('slow');
        isRemark = true;
    }
}

// Clear Remark Dropdown
const clearRemark = (elementSelector) => {
   //return  $(elementSelector).children('option[value=""]').each(function(){$(this).attr('selected','selected')});
    $(elementSelector + ' option').each(function(){
        if($(this).prop('selected')){
            $(this).prop('selected',false);
        }
    });
};

// Get Due Fees All Status
function getDueStatus(pgInfo = '')
{
    $.ajax({
        url: baseUrl + '/ajax/getAccDueFeesStatus.php?_=' + currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
            console.log(data);
            setDueStatus(data, pgInfo);
        }
    });
}

//Set Due Fees All Status
function setDueStatus(data, pg = '')
{
    $(".todayPendingCount"+pg).text(data.TODAY_PENDING);
    $(".allPendingCount"+pg).text(data.ALL_PENDING);
    $(".todayDoneCount"+pg).text(data.TODAY_DONE);
    $(".allBookingCount"+pg).text(data.ALL_BOOKING);
}
//Check empty data if data is empty then return NONE string
const checkEmptyData = (data) => {
    var returnData = 'NONE';
    if (data && data.length>0) {
        returnData = data;
    }
    return returnData;
}

$.ajax({
    url: baseUrl + "/ajax/loadUserPermissions.php?_=" + currentTime,
    type:"GET",
    success: function( dataHtml ){
        
    }
});

// User Leads and Send Leads


function getLeadFollowupStatus(renderElement){
    $.ajax({
        url: baseUrl + '/ajax/getLeadStatus.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            renderStatus(data,renderElement);
        }
    });
}


function getLeadRemarkMsg()
{
    $.ajax({
        url: baseUrl + '/ajax/getLeadRemark.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            renderRemark(data);
        }
    });
}

function disableEditField()
{
    $('.lead-edit .controls').hide('fast');
    $('.lead-edit-row').show('fast');
    $('.lead-save-button').hide('fast');
    $('.lead-edit-button').show('fast');
}

function setLeadFollowupsHeading(data)
{
    $('.lead-name').html(data.name);
    $('.lead-phone').html(data.phone_full);
    $('.lead-emailId').html(data.email);
    $('.lead-Ip').html(data.ip);
    $('.lead-course').html(data.category);
    $('.lead-address').html(data.address);
    $('#quotation_lead_id').val(data.id);
    $('#quotation_lead_name').val(data.name);
    $('#quotation_lead_phone').val(data.phone);
}

function getLeadFollowupHistory(leadId, saveFlag = 0, elementId){
    var norows = '';
    norows = (saveFlag > 0)? '&r=3' : '';
    $.ajax({
        url: baseUrl + '/ajax/getLeadFollowups.php?_=' + currentTime + norows,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data : JSON.stringify({"leadId":leadId}),
        success : function( data ){
            if (data.length) {
                lastFollowUpId = data[0].id;
            }
            renderFollowups(data, elementId);
        }
    });
}


// Search [Leads Admissions / Send Leads Admissions]

const rowBackground = (data) => {
    let strCss = 'text-center '
    if (data.r_status == 1) {
        strCss += 'search-lead-assigned ';
    }
    if (data.NO_DEAD <= 1 && data.NO_DEAD >= 1 && data.status == 'Dead') {
        strCss += 'search-lead-once-dead ';
    }
    if (data.NO_DEAD >= 2 && data.status == 'Dead') {
        strCss += 'search-lead-multi-dead ';
    }
    if (data.hits >=1 && data.emp_id !=1) {
        strCss += "search-lead-exists ";
    }
    return strCss;
}

const searchResult = (searchData, objType, elementSelector, extraCol = {}, dataSet={}, callBackFuns = ()=>{} ) => {
    let api = '';
    searchDataSet['objectType'] = objType;
    switch(objType) {
        case 'leads':
            api = 'getAccLeads.php';
            break;
        case 'adm':
            api = 'getAccDueFees.php';
            break;    
    }
    var testGrid = $(elementSelector).jsGrid({
        height: "auto",
        width: "100%",
        filtering: false,
        sorting: false,
        paging: false,
        autoload: !0,
        pageSize: 50,
        pageButtonCount: 5,
        rowClass: function (item, itemIndex){ return '';},
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    url: baseUrl + '/ajax/'+api+'?_=' + currentTime,
                    dataType:'json',
                    data : searchData,
                    success: function(data){
                        searchDataSet['data']=data;
                    }
                });
            }
        },
        fields:getSearchGridColumns(objType, extraCol),
        onDataLoaded: function(args) {
            callBackFuns();
        }
    });
}

const getSearchGridColumns = (objectType,extraCol = {}) => {
    let columnDefinition = {};
    switch(objectType) {
        case 'leads':
            columnDefinition = [
                { name: "name", title: "Name", type: "text", width: '15%', css:'text-center custom-jsgrid-cell'},
                { name: "email", title: "Reg Date", type: "text", width: '25%', css:'text-center'},
                { name: "phone", title: "Phone", type: "text", width: '15%', css:'text-center'},
                { name: "category", title: "Courses", type: "text", width: '25%', css:'text-center'},
                { name: "lead_emp_name", title: "User ID", type: "text", width: '15%', css:'text-center'}
            ]; 
            break;
        case 'adm':
            columnDefinition = [
                { name: "roll_no", title: "Roll No", type: "text", width: '4%', css:'text-center custom-jsgrid-cell'},
                { name: "name", title: "Name", type: "text", width: '10%', css:'text-center',cellRenderer : function (value, item) {
                    var tdInner = '';
                    tdInner = '<td><span class="followupAdmDueFee" data-regno="'+item.regno+'" data-row="'+item.a_id+'" data-toggle="modal" data-target="#grid-modal-due-fee">'+item.name+'</span></td>';
                    return tdInner;
                } },
                { name: "phone", title: "Phone", type: "text", width: '9%', css:'text-center'},
                { name: "status", title: "Status", type: "text", width: '9%', css:'text-center'},
                { name: "admDate", title: "Reg Date", type: "text", width: '8%', css:'text-center'},
                { name: "dueDate", title: "Due Date", type: "text", width: '8%', css:'text-center'},
                { name: "courses", title: "Courses", type: "text", width: '14%', css:'text-center'},
                { name: "total_fee", title: "Total Fees", type: "text", width: '7%', css:'text-center'},
                { name: "credit_amt", title: "Credit Amt", type: "text", width: '7%', css:'text-center'},
                { name: "due_fee", title: "Due Fees", type: "text", width: '7%', css:'text-center'},
                { name: "billing_emp_name", title: "Billing User ID", type: "text", width: '8%', css:'text-center'},
                { name: "emp_name", title: "Admission ID", type: "text", width: '9%', css:'text-center'},
                { name: "branch_name", title: "Brand Name", type: "text", width: '8%', css:'text-center'}
            ];
            break;    
    }
    if (extraCol.length) {
        columnDefinition = extraCol;
    }
    return columnDefinition;
}

// Get Filter Data [IVR CAll / Send Leads Admissions  ]
const getSearchFilterData = (objType) => {
    let strType = objType;
    objType = '#' + objType + '-';
    let filter = new Object();
    filter['from_date'] = $(objType+'fromDate').val();
    filter['to_date'] = $(objType+'toDate').val();
    filter['branch'] = $(objType+'branchList').find(":selected").val();
    filter['emp'] = ($(objType+'empList').find(":selected").val() == 'Filter By Employee')?'':$(objType+'empList').find(":selected").val();
    filter['phone'] = $(objType+'phone').val();
    filter['status'] = $(objType+'status').find(":selected").val();
    if (strType == 'adm') {
        filter['credit_amt'] = $(objType+'credit-amt').val();
    }
    currentFilter = filter;
    return filter;
}

const resetSearchFilterForm = (objType) => {
    objType = '#' + objType + '-';
    let filter = new Object();
    filter['from_date'] = $(objType+'fromDate').val('');
    filter['to_date'] = $(objType+'toDate').val('');
    filter['branch'] = $(objType+'branchList option:eq(1)').prop('selected', true);
    //filter['emp'] = ($(objType+'empList').find(":selected").val() == 'Filter By Employee')?'':$(objType+'empList').find(":selected").val();
    filter['emp'] = $(objType+'branchList option:eq(0)').prop('selected', true);
    filter['phone'] = $(objType+'phone').val('');
    filter['status'] = $(objType+'empList option:eq(0)').prop('selected', true);
    return filter;
}

// Filter
