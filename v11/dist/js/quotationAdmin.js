var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getQuotations.php';

import {getCommon, getDefaultConfig} from "./common";

let defaultConfig = getDefaultConfig();

const columnDefinition = () => {
                let columnDefinition = {};
                columnDefinition = [
                    { name: "lead_name", title: "Lead Name", type: "text", width: '17%', css:'text-center p-10'},
                    { name: "lead_phone", title: "Lead Phone", type: "text", width: '10%', css:'text-center p-10'},
                    { name: "total_price", title: "Total Fee", type: "text", width: '8%', css:'text-center p-10'},
                    { name: "discount", title: "Discount", type: "text", width: '8%', css:'text-center p-10'},
                    { name: "offer_price", title: "Offer Fee", type: "text", width: '8%', css:'text-center p-10'},
                    { name: "created_date", title: "Date", type: "text", width: '15%', css:'text-center p-10'},
                    { name: "user_name", title: "User", type: "text", width: '10%', css:'text-center p-10'},
                    { name: "status", title: "Status", type: "text", width: '7%', css:'text-center p-10', cellRenderer : function (value, item) {
                            let statusCellClass = '';
                            if (item.status == 'APPROVED') {
                                statusCellClass = 'badge-success';            
                            } else {
                                statusCellClass = 'badge-danger';
                            }
                            return '<td><span class="badge '+statusCellClass+'">'+item.status+'</span></td>';
                        }
                    },
                    {
                        type: "control", width: '5%', cellRenderer : function (value, item) {
                            let checkboxHtml = '<input class="quotation-id cust-offer-checkbox" type="checkbox" name="quotationId" value="'+item.id+'">';
                            let tdInner = '';
                            tdInner = '<td>'+checkboxHtml+'</td>';
                            return tdInner;
                        },headerTemplate: function(){
                            return '<input class="selectAll cust-offer-checkbox" type="checkbox" name="selectAll">';
                        }
                    }
                ];
    return columnDefinition;
}

const renderGrid = (elementSel, filterOpt='') => {
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: false,
        sorting: true,
        paging: true,
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
                    data : filterOpt,
                    success : function (data) {
                        records = data;
                    }
                });
            }
        },
        fields: columnDefinition(),
        onDataLoaded: function(args) {
            $('.approve-quot').click(function(){
                let listData = [];
                $(".quotation-id:checked").each(function(){listData.push($(this).val());});
                const action = 'APPROVE';
                updateStatusApiCall({action:action,ids:listData});
            });
            
            $('.dis-approve-quot').click(function(){
                let listData = [];
                $(".quotation-id:checked").each(function(){listData.push($(this).val());});
                const action = 'DISAPPROVE';
                updateStatusApiCall({action:action,ids:listData});
            });
            $('.no-of-records').text( records.length + ' Records Found');
            $('.selectAll').click(function(){
                if ($(this).is(":checked")) {
                    $('.quotation-id').prop('checked', true);
                } else {
                    $('.quotation-id').prop('checked', false);
                }
            });
        },
        pagerContainer:".cust-pager"
    });
}


// Filter
const getBranch = () => {
    $.ajax({
        url: baseUrl + '/ajax/getBranch.php?_=' + defaultConfig.currentTime,
        type : 'GET',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            console.log(data);
            renderBranch('#branchList',data);
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

$("#branchList").change(function(){
    var brnchId = $(this).val();
    $.ajax({
        url: baseUrl + "/ajax/getKeyPerson.php?_=" + defaultConfig.currentTime,
        type:"POST",
        data:{id:brnchId},
        success: function( retuHt ){
                $("#empList").html(retuHt);
             }
        });
});

const getFilterData = () => {
    let filter = new Object();
    filter['from_date'] = $('#fromDate').val();
    filter['to_date'] = $('#toDate').val();
    filter['branch'] = $('#branchList').find(":selected").val();
    filter['emp'] = ($('#empList').find(":selected").val() == 'Filter By Employee')?'':$('#empList').find(":selected").val();
    filter['phone'] = $('#phone').val();
    return filter;
}

$('.offer-filter').click(function(){
    renderGrid('.quotationGrid', getFilterData());
});

$('.reset-filter').click(function(){
    $('#branchList option:eq(0)').prop('selected', true);
    $('#empList option:eq(0)').prop('selected', true);
    $('#fromDate').val('');
    $('#toDate').val('');
    $('#phone').val('');
    renderGrid('.quotationGrid');    
});
// Filter

// Delete Quotation
$('.delete-quot').click(function(){
    let listData = [];
    $(".quotation-id:checked").each(function(){listData.push($(this).val());});
    $.ajax({
        url: baseUrl + '/ajax/deleteQuotations.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify({ids:listData}),
        success : function( data ){
            console.log(data);
            if (data.success) {
                renderGrid('.quotationGrid');
                Swal.fire("Deleted", "Quotation deleted successufly.", "success"); 
            }
        }
    });
});
// Delete Quotation

/// Default Load ///
renderGrid('.quotationGrid');
getBranch();
/// Default Load ///

//Approve 
//let listData = [];
const updateStatusApiCall = (apiPayload) => {
    $.ajax({
        url: baseUrl + '/ajax/updateQuotationStatus.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(apiPayload),
        success : function( data ){
            //console.log(data);
            if (data.success) {
                renderGrid('.quotationGrid');
                Swal.fire("Status Updated", "Status of the offer is successufly updated.", "success"); 
            }
        }
    });
}
//Approve