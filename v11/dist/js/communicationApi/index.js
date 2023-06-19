import {getCommon, getDefaultConfig} from "../common";

let commons = getCommon();
let api = 'getSMSAPIs.php';

let defaultConfig = getDefaultConfig();
let apiList = {};
let editApiDetails = {};
let baseUrl = defaultConfig.baseUrl;

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


const columnDefinition = () => {
    let columnDefinition = {};
    columnDefinition = [
        { name: "API", title: "API", type: "text", width: '70%', css:'text-center'},
        { name: "CLASS", title: "CLASS", type: "text", width: '10%', css:'text-center'},
        { title: "ENABLE", type: "text", width: '10%', cellRenderer:function (value, item) {
                    //let innerHtml = (item.STATUS==='1')?'<span class="badge badge-success">Enabled</span>':'<span class="badge badge-warning">Disabled</span>'
                let checkChecked = (item.STATUS==='1')?'checked':'';
                let innerHtml = '<input data-id="'+item.ID+'" type="checkbox" '+checkChecked+' class="js-switch" data-size="small" data-color="#89e314" data-secondary-color="#f0f0f0" />';
                return  '<td>'+innerHtml+'</td>';
            },css:'text-center'
        },
        {
            type: "control", width: '10%', cellRenderer : function (value, item) {
                let modalInfo = ' data-toggle="modal" data-target="#myModal" ';
                let editHtml = '<a '+modalInfo+'  data-id="'+item.ID+'" class="edit-sms-api" href="#"> <i class="fa fa-edit"></i></a>';
                let removeHtml = '<a data-id="'+item.ID+'" style="margin-left: 20px" class="remove-sms-api" href="javascript:void(0)" ><i class="fa fa-trash"></i></a>';
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
                                url: defaultConfig.baseUrl + '/ajax/'+ api +'?_=' + defaultConfig.currentTime + '&type=' + commons.url_param_type + '&' + filterOpt ,
                                dataType:'json',
                                data : filter,
                                success : function (data) {
                                    apiList = data;
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

// API Update //
var SwitcheryObj;
$('.js-switch-edit').each(function () {
    SwitcheryObj = new Switchery($(this)[0], $(this).data());
});

const markChecked = () => {
    if (!SwitcheryObj.isChecked()) {
        $('.api-status').trigger('click');
    }
}

const markUnchecked = () => {
    if (SwitcheryObj.isChecked()) {
        $('.api-status').trigger('click');
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

const setAPIEditForm = (data) => {
    $('.api-url').val(data.API);
    $('.api-class option').each(function () {
        if ($(this).val() === data.CLASS) {
          //  $(this).attr("selected","selected");
            $(this).prop('selected', true)
        } else {
            //$(this).removeAttr("selected");
            $(this).prop('selected', false)
        }
    });
    if (data.STATUS === '1') {
        markChecked();
        $('.api-status').attr('checked','checked');
    } else {
        markUnchecked();
        $('.api-status').removeAttr('checked');
    }
}

$("body").on("click", ".edit-sms-api", function(){
    let id = $(this).attr('data-id');
    editApiDetails = apiList.find((api)=>{if(api.ID===id) return api});
    setAPIEditForm(editApiDetails);
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
                Swal.fire('API Updated', 'API has been Updated successfully.', "success"); 
                editApiDetails = {};
                $("#updateAPICancelBtn").trigger('click');
                clearForm();
                renderGrid('.apiList');
            }
        }
    });
});
// API Update //

// API Add //
var SwitcheryObjAdd;
$('.js-switch-add').each(function () {
    SwitcheryObjAdd = new Switchery($(this)[0], $(this).data());
});
const getAPIAddData = () => {
    let data = {};
    data['api'] = $('.add-api-url').val();
    data['className'] = $('.add-api-class').val();
    data['status'] = (SwitcheryObjAdd.isChecked())?'1':'0';
    data['type'] = (commons.url_param_type)?commons.url_param_type:'LOGIN_OTP';
    return data;
}
$("#addAPI").click(function () {
    $.ajax({
        url: baseUrl + '/ajax/addSMSAPIs.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(getAPIAddData()),
        success : function( data ){
            if (data.success) {
                Swal.fire('API Added', 'API has been Added successfully.', "success"); 
                $("#addAPICancelBtn").trigger('click');
                clearForm();
                renderGrid('.apiList');
            }
        }
    });
});
// API Add //
// Delete API//
$("body").on("click", ".remove-sms-api", function(){
    $(this).attr('data-id');
    $.ajax({
        url: baseUrl + '/ajax/deleteSMSAPIs.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify({id:$(this).attr('data-id')}),
        success : function( data ){
            if (data.success) {
                Swal.fire('API Deleted', 'API has been Deleted successfully.', "success"); 
                renderGrid('.apiList');
            }
        }
    });
});
$(".remove-sms-api").click(function () {

});
// Delete API//

// Update Status
const updateAPIStatus = (data) => {
    $.ajax({
        url: baseUrl + '/ajax/editSMSAPIStatus.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data: JSON.stringify(data),
        success : function( data ){
            if (data.success) {
                Swal.fire('Status Updated', 'API has been Updated successfully.', "success"); 
                renderGrid('.apiList');
            }
        }
    });
}
// Update Status
export {renderGrid};