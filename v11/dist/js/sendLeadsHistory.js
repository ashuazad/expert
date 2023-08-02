var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getSendLeadsHistory.php';

import {getCommon, getDefaultConfig} from "./common";

let defaultConfig = getDefaultConfig();

const columnDefinition = () => {
                let columnDefinition = {};
                columnDefinition = [
                    { name: "name", title: "Name", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "phone", title: "Phone", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "object_type", title: "Type", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "last_username", title: "From User", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "next_username", title: "To User", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "send_by", title: "Send By", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'},
                    { name: "send_date_time", title: "Date Time", type: "text", width: '100', css:'text-center p-10',headercss:'search-row text-center'}
                ];
    return columnDefinition;
}

const renderGrid = (elementSel, filterOpt='') => {
    getFilterData();
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: false,
        sorting: true,
        paging: false,
        autoload: !0,
        pageSize: 10,
        pageButtonCount: 1,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){ return '';},
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    url: defaultConfig.baseUrl + '/ajax/'+ api +'?_=' + defaultConfig.currentTime,
                    dataType:'json',
                    type : 'GET',
                    data : getFilterData(),
                    success : function (data) {
                        records = data;
                    }
                });
            }
        },
        fields: columnDefinition(),
        onDataLoaded: function(args) {
            $('.noff-rows-box').show('fast');
            $('.noff-rows-text').show('fast');
            $('.noff-rows-text').text(records.length+' Records Found');
        }
    });
}

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

const getFilterData = () => {
    let data = {};
    data['fromDate'] = $('.fromDate').val();
    data['toDate'] = $('.toDate').val();
    data['empId'] = $('.empList').val();
    return data;
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

$('.history-search').click(function(){
    renderGrid('.historyGrid');    
});

// Filter

/// Default Load ///
getBranch();
renderGrid('.historyGrid');
/// Default Load ///