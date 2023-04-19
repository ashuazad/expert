/* Search Code */
var baseUrl = 'https://www.advanceinstitute.co.in';
var records = {};
var addInsentive = 0;
var currentTime = new Date().getTime();
let editData = {};
var recordRegno = null;
var params={};
var pageInfo={};
var admStatus = {};
var admRecords={};
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var isRemark = true;
let api = 'getSMSTemplates.php';
let isSuperAdmin = false;
let branchList = {};
let templates = {};
let currentOccasionType = $('.occasion-type').val();

import getCommon from "./common";

let commons = getCommon();

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

const updateAPIStatus = (data) => {
    $.ajax({
        url: baseUrl + '/ajax/editSMSAPIStatus.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(data),
        success : function( data ){
            if (data.success) {
                var alertMsg = {};
                alertMsg.heading = 'API Status';
                alertMsg.message = 'API Status has been update successfully';
                alertMessage(alertMsg);
                renderGrid('.apiList');
            }
        }
    });
}

const columnDefinition = () => {
                let columnDefinition = {};
                columnDefinition = [
                    { name: "type_text", title: "Type", type: "text", width: '10%', css:'text-center', cellRenderer: function(value){
                        var tdInner = '';
                        tdInner = '<td><strong>'+value+'</strong></td>';
                        return tdInner;
                    }},
                    { name: "sms_title", title: "Title", type: "text", width: '20%', css:'text-center'},
                    { name: "sms_content", title: "Content", type: "text", width: '70%', css:'text-center'},
                    { title: "ENABLE", type: "text", width: '10%', cellRenderer:function (value, item) {
                        //let innerHtml = (item.STATUS==='1')?'<span class="badge badge-success">Enabled</span>':'<span class="badge badge-warning">Disabled</span>'
                        let checkChecked = (item.default_sms==='1')?'checked':'';
                        let innerHtml = '<input data-id="'+item.ID+'" type="checkbox" '+checkChecked+' class="js-switch" data-size="small" data-color="#89e314" data-secondary-color="#f0f0f0" />';
                        return  '<td>'+innerHtml+'</td>';
                        },
                        css:'text-center'
                    },
                    {
                        type: "control", width: '10%', cellRenderer : function (value, item) {
                            let modalInfo = ' data-toggle="modal" data-target="#myModalAdd" ';
                            let editHtml = '<a '+modalInfo+'  data-id="'+item.sms_title+'" class="edit-sms-template" href="#"> <i class="fa fa-edit"></i></a>';
                            let removeHtml = '<a data-id="'+item.sms_title+'" style="margin-left: 20px" class="remove-sms-api" href="javascript:void(0)" ><i class="fa fa-trash"></i></a>';
                            var tdInner = '';
                            tdInner = '<td>'+editHtml+' '+removeHtml+'</td>';
                            return tdInner;
                        }
                    }
                ];
    return columnDefinition;
}

const renderGrid = (elementSel, filterOpt='') => {
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: true,
        sorting: true,
        paging: true,
        autoload: !0,
        pageSize: 15,
        pageButtonCount: 2,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){ return '';},
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    url: baseUrl + '/ajax/'+ api +'?_=' + currentTime + '&type=' + commons.comm_api_url_type + '&coType=' + currentOccasionType + '&' + filterOpt ,
                    dataType:'json',
                    data : filter,
                    success : function (data) {
                        templates = data;
                    }
                });
            }
        },
        fields: columnDefinition(),
        onDataLoaded: function(args) {
            $('.js-switch').each(function () {
                new Switchery($(this)[0], $(this).data());
            });
            $(".js-switch").on("change",function(){
                updateAPIStatus({id:$(this).attr('data-id')});
            });
        }
    });
}

/// Default Load ///
renderGrid('.smsList');
/// Default Load ///

// API Update //
var SwitcheryObj;
$('.js-switch-add').each(function () {
    SwitcheryObj = new Switchery($(this)[0], $(this).data());
});

const markChecked = () => {
    if (!SwitcheryObj.isChecked()) {
        $('.sms-template-status').trigger('click');
    }
}

const markUnchecked = () => {
    if (SwitcheryObj.isChecked()) {
        $('.sms-template-status').trigger('click');
    }
}

const getAPIEditData = () => {
    let data = {};
    data['api'] = $('.api-url').val();
    data['className'] = $('.api-class').val();
    data['status'] = (SwitcheryObj.isChecked())?'1':'0';
    data['id'] = editApiDetails.ID;
    return data;
}

const setEditForm = (data) => {
    $('.add-sms-title').val(data.sms_title);
    $('.add-sms-content').val(data.sms_content);
    $('.sms-type option').each(function () {
        if ($(this).val() === data.type) {
          //  $(this).attr("selected","selected");
            $(this).prop('selected', true)
        } else {
            //$(this).removeAttr("selected");
            $(this).prop('selected', false)
        }
    });
    if (data.default_sms === '1') {
        markChecked();
        $('.sms-template-status').attr('checked','checked');
    } else {
        markUnchecked();
        $('.sms-template-status').removeAttr('checked');
    }
}

$("body").on("click", ".edit-sms-template", function(){
    let id = $(this).attr('data-id');
    editData = templates.find((sms)=>{if(sms.sms_title===id) return sms});
    setEditForm(editData);
});

$("#updateAPI").click(function () {
    $.ajax({
        url: baseUrl + '/ajax/editSMSAPIs.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(getAPIEditData()),
        success : function( data ){
            if (data.success) {
                var alertMsg = {};
                alertMsg.heading = 'API Edit';
                alertMsg.message = 'API has been Updated successfully';
                alertMessage(alertMsg);
                editApiDetails = {};
                $("#updateAPICancelBtn").trigger('click');
                clearForm();
                renderGrid('.apiList');
            }
        }
    });
});
//  Update //

//  Add //
/*
$('.js-switch-add').each(function () {
    SwitcheryObj = new Switchery($(this)[0], $(this).data());
});*/
$("body").on("click", ".add-template-btn", function(){
    clearForm();
});
const getAddData = () => {
    let data = {};
    data['title'] = $('.add-sms-title').val();
    data['content'] = $('.add-sms-content').val();
    data['isDefault'] = SwitcheryObj.isChecked();
    data['type'] = $('.sms-type').val();
    data['sms_type'] = (commons.url_param_type)?commons.url_param_type:'due';
    return data;
}
$("#addAPI").click(function () {
    $.ajax({
        url: baseUrl + '/ajax/addSMSTemplate.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(getAddData()),
        success : function( data ){
            if (data.success) {
                var alertMsg = {};
                alertMsg.heading = 'SMS Add';
                alertMsg.message = 'Template has been Added successfully';
                alertMessage(alertMsg);
                $("#addAPICancelBtn").trigger('click');
                clearForm();
                renderGrid('.smsList');
            }
        }
    });
});
// Add //
// Delete //
$("body").on("click", ".remove-sms-api", function(){
    $(this).attr('data-id');
    $.ajax({
        url: baseUrl + '/ajax/deleteSMSTemplate.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify({id:$(this).attr('data-id')}),
        success : function( data ){
            if (data.success) {
                var alertMsg = {};
                alertMsg.heading = 'API Delete';
                alertMsg.message = 'API has been Deleted successfully';
                alertMessage(alertMsg);
                renderGrid('.smsList');
            }
        }
    });
});
$(".remove-sms-api").click(function () {

});
// Delete//
// Selecting Occasion Type //
$('.occasion-type').change(function(){
    currentOccasionType = $(this).find(":selected").val();
    renderGrid('.smsList');
});
// Selecting Occasion Type //