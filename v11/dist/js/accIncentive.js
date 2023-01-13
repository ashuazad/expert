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
var incentiveRecords={};
var closeButtonHtml = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var isRemark = true;

function setStatus(data)
{
    $(".incentive").text(data.incentive);
    $(".target").text(data.target);
    $(".salary").text(data.salary);
    $(".totalSalary").text(data.totalSalary);
}

function loadTab(){
    $(".nav-link").each(function (){
       if($(this).hasClass( "active" )){
           $(this).trigger("click");
       }
    });
}

function renderGrid(elementSel, gridInfo){
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        filtering: true,
        sorting: true,
        paging: true,
        autoload: !0,
        pageSize: 50,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){
            var rowClass = "";
            if(item.insentive_status=='Approved'){
                rowClass += "approved";
            }
            return rowClass;
            },
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    url: baseUrl + '/ajax/getAccInsentiveV2.php?_=' + currentTime + '&param=' + gridInfo,
                    dataType:'json',
                    data : filter
                });
            }
        },
        fields:getGridColumns(gridInfo),
        onDataLoaded:function (grid){
            displayTotals(grid.data);
        }
    });
}

function getIncomeStatus()
{
    $.ajax({
        url: baseUrl + '/ajax/getSalary.php?_=' + currentTime,
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
    var approveIncetiveClass = "";
    var itemsList = Array();
    itemsList['roll_no'] = { name: "roll_no", title: "Roll No", type: "text", width: 50, css :"gird-cell-text-alignment", cellRenderer : function (value, item) {
            incentiveRecords[item.id] =item;
            var tdInner = '';
            tdInner = '<td data-status="'+item.insentive_status+'">'+item.roll_no+'</td>';
            return tdInner;
        }, css :"gird-cell-text-alignment"};
    itemsList['recipt_date'] = { name: "recipt_date", title: "Receipt Date", type: "text", width: 130, css :"gird-cell-text-alignment" };
    itemsList['name'] = { name: "name", title: "Name", type: "text", width: 120,css :"gird-cell-text-alignment"};
    itemsList['doj'] = { name: "doj", title: "Reg Date", type: "text", width: 100, css :"gird-cell-text-alignment" };
    itemsList['insentive_amt'] = { name: "insentive_amt", title: "Incentive Amt", type: "text", width: 100, css :"gird-cell-text-alignment" };
    itemsList['insentive_date'] = { name: "insentive_date", title: "Incentive Date", type: "text", width: 100, css :"gird-cell-text-alignment"};
    itemsList['insentive_status'] = { name: "insentive_status", title: "Status", type: "text", width: 100, css :"gird-cell-text-alignment" };
    return itemsList[item];
}

function tabItems(tab)
{
    var tabItemsList = Array();
    tabItemsList['incentive'] = ['roll_no','name','doj','recipt_date','insentive_amt','insentive_date','insentive_status'];
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

function displayTotals(data) {
    let dataLength = 0;
    dataLength = data.length;
    let approveIncentive = 0;
    let pendingIncentive = 0;
    for (var i=0;i<dataLength;i++) {
        if(data[i].insentive_status == 'Approved'){
            approveIncentive += parseInt(data[i].insentive_amt);
        } else {
            pendingIncentive += parseInt(data[i].insentive_amt);
        }
    }
    $(".noOfRecords").text(dataLength);
    $(".approveTotalAmt").text(approveIncentive);
    $(".pendingTotalAmt").text(pendingIncentive);
}
/// Default Load ///
getIncomeStatus();
renderGrid('.incentiveGrid', 'incentive');
//highlightRow();
/// Default Load ///