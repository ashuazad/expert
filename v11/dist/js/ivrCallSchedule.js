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
const getFilterData = (objType) => {
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

const resetFilterForm = (objType) => {
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

const getFilterSql = (searchData, objType) => {
    let api = '';
    switch(objType) {
        case 'leads':
            api = 'getLeadsFilter.php';
            break;
        case 'adm':
            api = 'getAdmFilter.php';
            break;    
    }
    $.ajax({
        url: baseUrl + '/ajax/'+api+'?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(searchData),
        success : function( data ){
            if (data.success) {
                setNoRecords(data.no_records, '.'+objType+'-nofrecords');
                isFilterResult=true;
                hideDisplayFilterResultBox(objType);
            }
        }
    });
}

$('.leads-offer-filter').click(function(){
    currentFilterObject = 'leads';
    //renderGrid('.quotationGrid', getFilterData());
    //console.log(getFilterData('leads'));
    getFilterSql(getFilterData('leads'),'leads');
});

$('.adm-offer-filter').click(function(){
    currentFilterObject = 'adm';
    //renderGrid('.quotationGrid', getFilterData());
    //console.log(getFilterData('adm'));
    getFilterSql(getFilterData('adm'),'adm');
});

$('.leads-reset-filter').click(function(){
    resetFilterForm('leads');
});
// Filter

//Job Status
if (jobStatus) {
    $('.job-status').show('fast');
} else {
    $('.job-status').hide('fast');
}
//Job Status

// Set Delayed Job
const getDelayedJobDetails = (objType) => {
    let jobData = new Object();
    jobData['object_type'] = objType;
    jobData['filter_query'] = currentFilter;
    jobData['delay_time'] = $('.'+objType+'-mins').val();
    return jobData;
}
const setDelayedJob = (objType) => {
    //let payloadData = getDelayedJobDetails(objType);
    $.ajax({
        url: baseUrl + '/ajax/setDelayedJob.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(getDelayedJobDetails(objType)),
        success : function( data ){
            if (data.success) {
               // runJob();
                getDelayedJobStatus();
                // Hide the Filter Result
                switch(objType){
                    case 'leads':
                        $('.' + objType + '-filter-result').hide('fast');
                        break;
                    case 'adm':
                        $('.' + objType + '-filter-result').hide('fast');
                        break;    
                }
            }
        }
    });
}

const runJob = () => {
    $.ajax({
        url: baseUrl + '/ajax/run_background_job.php?_=' + defaultConfig.currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
        }
    });
}

$('.leads-set-call-timing').click(function(){
    setDelayedJob('leads');
});

$('.adm-set-call-timing').click(function(){
    setDelayedJob('adm');
});

// Set Delayed Job

// Get Delayed Job Status
const stopDelayedJob = () => {
    $.ajax({
        url: baseUrl + '/ajax/stopDelayedJob.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            console.log(data);
            if (data.success) {
                getDelayedJobStatus();
            }
        }
    });
}

$('.stop-delayed-job').click(function(){
    stopDelayedJob(); 
});

const setDelayedJobDetails = (data) => {
    if (parseInt(data[0])>0) {
        $('.done-count').text(data[1].no_of_completed);
        $('.total-count').text(data[1].no_of_records);
        let object_type;
        switch(data[1].object_type){
            case 'leads':
                object_type = 'Leads,';
            break;    
            case 'adm':
                object_type = 'Admission,';
            break;    
        }
        $('.current-job-type').text(object_type);
        $('.job-status').show('fast');
        $('.leads-set-call-timing').prop('disabled', true);
        $('.adm-set-call-timing').prop('disabled', true);
        //$('.adm-filter-result').hide('fast');
    } else {
        $('.done-count').text(0);
        $('.total-count').text(0);
        $('.job-status').hide('fast');
        $('.leads-set-call-timing').prop('disabled', false);
        $('.adm-set-call-timing').prop('disabled', false);
       // $('.leads-filter-result').show('fast');
    }
}

const getDelayedJobStatus = () => {
    $.ajax({
        url: baseUrl + '/ajax/getDelayedJobStatus.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            console.log(data);
            if (data.success) {
                setDelayedJobDetails(data.data);
            }
        }
    });
}
// Get Delayed Job Status

/// Default Load ///
getDelayedJobStatus();
setInterval(getDelayedJobStatus, 30000);
renderGrid('.apiList');
getBranch();
//hideDisplayFilterResult();
/// Default Load ///