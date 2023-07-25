var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getQuotations.php';
let jobStatus = false;
let isJobRunning = false;
let isFilterResult = false;
let currentFilterObject = 'leads';
let dataSet = {};

import {getCommon, getDefaultConfig} from "./common";
import {renderGrid} from "./communicationApi";

let defaultConfig = getDefaultConfig();

// Filter
const getBranch = (elementSelector = '.branchList') => {
    $.ajax({
        url: baseUrl + '/ajax/getBranch.php?_=' + defaultConfig.currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            console.log(data);
            renderBranch(elementSelector,data);
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
/*
const getFilterData = (objType) => {
    let strType = objType;
    objType = '#' + objType + '-';
    let filter = new Object();
    switch (strType) {
        case 'leads':
            filter['phone'] = $(objType+'phone').val();     
            filter['param'] = 'all';
            break;
        case 'adm':
            filter['phone'] = $(objType+'phone').val();
            filter['reg_no'] = $(objType+'regno').val();
            filter['roll_no'] = $(objType+'rollno').val();
            filter['param'] = 'alladm';
            break;
    }
    currentFilter = filter;
    return filter;
}
*/
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

// This will add select all checkbox functionality and display no of records as well
const selectAllRows = () => {
    let objSelector = '.'+searchDataSet.objectType+'-';
    $('.select-all').change(function(){
        if ($(this).prop('checked') == true) {
            $(objSelector+'id').prop('checked',true);
        } else {
            $(objSelector+'id').prop('checked',false);
        }
    });
    //Display no of records
    $(objSelector+'noff-rows-box').show('fast');
    $(objSelector+'noff-rows-text').show('fast');
    $(objSelector+'noff-rows-text').text(searchDataSet.data.length+' Records Found');
    //Un check all employee radio
    $('input:radio[name=move_emp_id]').prop('checked', false);
}
const getAdmGrid = () => {
    currentFilterObject = 'adm';
    let filterData = getSearchFilterData('adm');
    filterData['param']= 'alladm';
    let extraCol = [
            { name: "a_id", title: "", type: "text", width: '26', css:'text-center',cellRenderer : function (value, item) {
                return '<td><input type="checkbox" name="leadId[]" class="adm-id" value="'+item.a_id+'"</td>';
                                            },
                                    headerTemplate : function () { 
               return '<th><input type="checkbox" name="all-adm" class="select-all" value="all"/></th>';
                                            } 
                                        },
            { name: "roll_no", title: "Roll No", type: "text", width: '50', css:'text-center custom-jsgrid-cell'},
            { name: "name", title: "Name", type: "text", width: '100', css:'text-center',cellRenderer : function (value, item) {
                var tdInner = '';
                tdInner = '<td><span class="followupAdmDueFee" data-regno="'+item.regno+'" data-row="'+item.a_id+'" data-toggle="modal" data-target="#grid-modal-due-fee">'+item.name+'</span></td>';
                return tdInner;
            } },
            { name: "phone", title: "Phone", type: "text", width: '100', css:'text-center'},
            { name: "status", title: "Status", type: "text", width: '80', css:'text-center'},
            { name: "admDate", title: "Reg Date", type: "text", width: '80', css:'text-center'},
            { name: "dueDate", title: "Due Date", type: "text", width: '80', css:'text-center'},
            { name: "courses", title: "Courses", type: "text", width: '160', css:'text-center'},
            { name: "total_fee", title: "Total Fees", type: "text", width: '50', css:'text-center'},
            { name: "credit_amt", title: "Credit Amt", type: "text", width: '55', css:'text-center'},
            { name: "due_fee", title: "Due Fees", type: "text", width: '50', css:'text-center'},
            { name: "billing_emp_name", title: "Billing User ID", type: "text", width: '80', css:'text-center'},
            { name: "emp_name", title: "Admission ID", type: "text", width: '90', css:'text-center'},
            { name: "branch_name", title: "Brand Name", type: "text", width: '80', css:'text-center'}
        ];                            
    searchResult(filterData,'adm','.searchResultAdm', extraCol, dataSet,selectAllRows);
}

const getLeadsGrid = () => {
    currentFilterObject = 'leads';
    let filterData = getSearchFilterData('leads');
    filterData['param']= 'all';
    let extraCol = [
        { name: "id", title: "", type: "text", width: '20', css:'',cellRenderer : function (value, item) {
                return '<td class="'+rowBackground(item)+'"><input type="checkbox" name="leadId[]" class="leads-id" value="'+item.id+'" style=""/></td>';
            },
            headerTemplate : function () { 
                return '<input type="checkbox" name="all-leads" class="select-all" value="all">';
            }
        },
        { name: "name", title: "Name", type: "text", width: '130', css:'text-center custom-jsgrid-cell search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'"><span class="followupAdm" id="name-'+item.id+'" data-row="'+item.id+'" style="cursor:pointer;" data-toggle="modal" data-target="#grid-modal">'+value+'</span></td>';
        }},
        { name: "email", title: "E-mail", type: "text", width: '170', css:'text-center search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'">'+value+'</td>';
        }},
        { name: "phone", title: "Phone", type: "text", width: '105', css:'text-center search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'">'+value+'</td>';
        }},
        { name: "category", title: "Courses", type: "text", width: '128', css:'text-center search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'">'+value+'</td>';
        }},
        { name: "created_date", title: "Date", type: "text", width: '120', css:'text-center search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'">'+value+'</td>';
        }},
        { name: "lead_emp_name", title: "User ID", type: "text", width: '100', css:'text-center search-row',cellRenderer : function (value, item) {
            return '<td class="'+rowBackground(item)+'">'+value+'</td>';
        }}
    ];
    
    searchResult(filterData,'leads','.searchResultLeads', extraCol, dataSet, selectAllRows);
}
$('.leads-search').click(function(){
    getLeadsGrid();
});

$('.adm-offer-filter').click(function(){
    getAdmGrid();
});

$('.leads-reset-filter').click(function(){
    resetFilterForm('leads');
});
// Filter

// Move Lead & Admissions

$('.moveBranchList').change(function(){
    $.ajax({
        url: baseUrl + "/ajax/getBranchEmp.php?_=" + defaultConfig.currentTime,
        type:"POST",
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify({id:$(this).val()}),
        success: function(data){
                $(".move-emp-list").html(getEmpList(data));
                $(".send-objects-btn").show('fast');
             }
        });
});

const getEmpList = (employees) =>{
    let tBodyHtml='';
    employees.forEach(function(employee, key){
        let tBody = `<div class="btn btn-outline-secondary m-t-5 m-r-5">
                        <input type="radio" name="move_emp_id" value="${employee.id}">
                        <span class="m-l-5">${employee.name}</span>
                    </div>`;
        tBodyHtml += tBody;
    });
   return tBodyHtml;
}

const getSelectedObjectIds = (selector) => {
    let selectedObjectIds = '';
    $('input[class="'+selector+'"]:checked').each(function () {
        console.log($(this).val());
        selectedObjectIds += $(this).val()+',';
    });
    return selectedObjectIds;
}

$('.show-move-box').click(function(){
    if ((searchDataSet.hasOwnProperty('data')) && searchDataSet.data.length && (searchDataSet.objectType == $("ul.nav-tabs li a.active").attr('data-tab-id')) && (getSelectedObjectIds(searchDataSet.objectType+'-id').length>0)) {
        $('.move-box').show('fast');
    } else {
        Swal.fire({
            title: "Error",
            text: 'Please select IDs to send',
            type: "error",
            icon: 'error'
        });
        return false;
    }
});

$('.send-objects').click(function(){
    var emp= $('input:radio[name=move_emp_id]:checked').val();
    if(emp == undefined){
        Swal.fire({
            title: "Error",
            text: 'Please select an Employee',
            type: "error",
            icon: 'error'
        });
        return false;
    }
    switch(searchDataSet.objectType){
        case 'leads':
            $.ajax({
                type:'POST',
                url :baseUrl + '/ajax/add.php',
                data :{action:'updatelead',emp:emp,select:getSelectedObjectIds(searchDataSet.objectType+'-id')},
                success: function(result){
                    getLeadsGrid();
                    Swal.fire({
                        title: "Success",
                        text: 'Leads has been send successfully',
                        type: "success",
                        icon: 'success'
                    });
                    $('input:radio[name=move_emp_id]').prop('checked', false);
                    $('.move-box').hide('fast');
                }
             });
            break;
        case 'adm':
            $.ajax({
                type:'POST',
                url :baseUrl + '/ajax/moveAdmission.php',
                contentType : 'application/json',
                dataType:'json',
                data :JSON.stringify({emp_id:emp,select:getSelectedObjectIds(searchDataSet.objectType+'-id')}),
                success: function(result){
                    getAdmGrid();
                    Swal.fire({
                        title: "Success",
                        text: 'Admissions has been send successfully',
                        type: "success",
                        icon: 'success'
                    });
                    $('input:radio[name=move_emp_id]').prop('checked', false);
                    $('.move-box').hide('fast');
                }
             });
            break;
    }
});

$("ul.nav-tabs li a").click(function(){
    if (searchDataSet.objectType != $(this).attr('data-tab-id')) {
        $('.move-box').hide('fast');
    } else {
        $('.move-box').show('fast');
    }
});

// Move Lead & Admissions

// Leads Detail Modal

$("body").on("click", ".followupAdm", function(){
    disableEditField();
    clearRemark('.followup-remarks');
    clearRemark('.followup-status');
    resetFollowupsHeading();
    clearfollowupForm();
    //recordRegno = $(this).attr("data-regno");
    recordId = $(this).attr("data-row");
    let currentLeadClicked = searchDataSet.data.find(row => row.id == recordId);
    setLeadFollowupsHeading(currentLeadClicked);
    //getIpLocation(currentLeadClicked);
    getLeadFollowupHistory(recordId, 3, '.last-followups');
   // getLeadQuotation(recordId, leadsRecords);
   // clearQuotationForm();
});
// Leads Detail Modal

// Admission Details Modal
$("body").on("click", ".followupAdmDueFee", function(){
    resetFollowupsHeading();
    clearfollowupForm();
    clearRemark('.due-fees-followup-remarks');
    clearRemark('.due-fees-followup-status');
    clearSuccessMsg('.display-success');
    clearErrors('.display-errors');
    let recordRegno = $(this).attr("data-regno");
    recordId = $(this).attr("data-row");
    let currentAdmClicked = searchDataSet.data.find(row => row.a_id == recordId);
    setFollowupsHeading(currentAdmClicked);
    getRemarkMsg('.due-fees-followup-remarks');
    getFollowupHistory(recordRegno, 3, '.last-followups');
    getFeeReciept(recordId);
});
// Admission Details Modal

// Default
getBranch();
getBranch('.moveBranchList');
getLeadRemarkMsg();
getLeadFollowupStatus('.followup-status');