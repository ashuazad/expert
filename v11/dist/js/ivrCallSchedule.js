var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getQuotations.php';

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

/// Default Load ///
renderGrid('.apiList');
getBranch();
/// Default Load ///