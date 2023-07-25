var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getQuotations.php';
let jobStatus = false;
let isJobRunning = false;
let isFilterResult = false;
let currentFilterObject = 'leads';
let currentFilter;

import {getCommon, getDefaultConfig} from "./common";
import {renderGrid} from "./communicationApi";

let defaultConfig = getDefaultConfig();

// Filter
const getBranch = () => {
    $.ajax({
        url: baseUrl + '/ajax/getBranch.php?_=' + defaultConfig.currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            console.log(data);
            renderBranch('.branchList',data);
        }
    });
}

const renderBranch = (element, data) => {
    var options = '<option value="">Branch</option>';
    for ( var value of data) {
        options += '<option value="'+value.id+'">'+value.branch_name+"</option>";
    }
    $(element).html(options);
}

$(".branchList").change(function(){
    var brnchId = $(this).val();
    $.ajax({
        url: baseUrl + "/ajax/getKeyPerson.php?_=" + defaultConfig.currentTime,
        type:"POST",
        data:{id:brnchId},
        success: function( retuHt ){
                $(".empList").html(retuHt);
             }
        });
});

// Filter
const getSearchFilterData = (objType) => {
    let strType = objType;
    objType = '#' + objType + '-';
    let filter = new Object();
    switch (strType) {
        case 'leads':
            filter['phone'] = $(objType+'phone').val();     
            filter['param'] = 'all';
            filter['act']= 'SEARCH';
            break;
        case 'adm':
            filter['phone'] = $(objType+'phone').val();
            filter['reg_no'] = $(objType+'regno').val();
            filter['roll_no'] = $(objType+'rollno').val();
            filter['param'] = 'alladm';
            filter['act']= 'SEARCH';
            break;
    }
    currentFilter = filter;
    return filter;
}

const vaildateFilterData = (objType) => {
    let strType = objType;
    objType = '#' + objType + '-';
    let isValid = true;
    let errors = [];
    switch (strType) {
        case 'leads':
            if ($(objType+'phone').val() == '') {
                errors.push('Phone No is empty');
            }
            break;
        case 'adm':
            if (($(objType+'phone').val() == '') && ($(objType+'regno').val() == '') && ($(objType+'rollno').val() == '')) {
                errors.push('Please enter some value');
            }
            break;
    }
    return errors;
}

const resetFilterForm = (objType) => {
    objType = '#' + objType + '-';
    let filter = new Object();
    filter['phone'] = $(objType+'phone').val();
    filter['regno'] = $(objType+'regno').val();
    filter['rollno'] = $(objType+'rollno').val();
    return filter;
}

//Filter Info
const hideDisplayFilterResultBox = (objType) => {
    objType = '.' + objType + '-';
    if (isFilterResult) {
        $(objType + 'filter-result').show('fast');
    } else {
        $(objType + 'filter-result').hide('fast');
    }
}

const setNoRecords = (nofRecords, selector) => {
    $(selector).text(nofRecords);    
}
//Filter Info
/*
const searchResult = (searchData, objType, elementSelector) => {
    let api = '';
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
                    url: baseUrl + '/ajax/'+api+'?_=' + defaultConfig.currentTime,
                    dataType:'json',
                    data : searchData
                });
            }
        },
        fields:getSearchGridColumns(objType)
    });
}

const getSearchGridColumns = (objectType) => {
    let columnDefinition = {};
    switch(objectType) {
        case 'leads':
            columnDefinition = [
                { name: "name", title: "Name", type: "text", width: '70%', css:'text-center custom-jsgrid-cell'},
                { name: "email", title: "Reg Date", type: "text", width: '70%', css:'text-center'},
                { name: "phone", title: "Phone", type: "text", width: '70%', css:'text-center'},
                { name: "category", title: "Courses", type: "text", width: '70%', css:'text-center'},
                { name: "message", title: "Message", type: "text", width: '70%', css:'text-center'},
                { name: "lead_emp_name", title: "User ID", type: "text", width: '70%', css:'text-center'}
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
    return columnDefinition;
}
*/
$('.leads-search').click(function(){
    let validationErrors = vaildateFilterData('leads');
    if (validationErrors.length) {
        Swal.fire({
            title: "Error",
            text: validationErrors[0],
            type: "error",
            icon: 'error'
        });
        return false;
    }
    currentFilterObject = 'leads';
    //renderGrid('.quotationGrid', getFilterData());
    //console.log(getFilterData('leads'));
    searchResult(getSearchFilterData('leads'),'leads','.searchResultLeads');
});

$('.adm-offer-filter').click(function(){
    let validationErrors = vaildateFilterData('adm');
    if (validationErrors.length) {
        Swal.fire({
            title: "Error",
            text: validationErrors[0],
            type: "error",
            icon: 'error'
        });
        return false;
    }
    currentFilterObject = 'adm';
    //renderGrid('.quotationGrid', getFilterData());
    //console.log(getFilterData('adm'));
    searchResult(getSearchFilterData('adm'),'adm','.searchResultAdm');
});

$('.leads-reset-filter').click(function(){
    resetFilterForm('leads');
});
// Filter

// Display Due Fee Followup Modal
$("body").on("click", ".followupAdmDueFee", function(){
    clearErrors('#feesRecieptTb');
    recordId = $(this).attr("data-row");
    getFeeReciept(recordId);
});