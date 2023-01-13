/* Search Code */
var baseUrl = 'https://www.advanceinstitute.co.in';
var records = new Object();
var addInsentive = 0;
var currentTime = new Date().getTime();
var recordId = null;
var recordRegno = null;
var params={};
var pageInfo={};
var admStatus = {};
var admRecords={};
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var isRemark = true;
let api = 'getUsersPermission.php';
let isSuperAdmin = false;
let branchList = {};
let empList = {};
let currentEmp;

import getAlertBox from "./alertBox";

const getPermissionsStrFromObject = (permissions, key=null) => {
    let permissionsStr = '';
    //return
}

const getRightsBoxHTML = (entityList, type='BRANCH', ids='', emp = {}) =>{
    let modalId = '';
    let editClass = '';
    switch (type) {
        case "BRANCH":
                modalId = '#modalIdBranch';
                editClass = 'editViewBranchPerm';
            break;
        case "EMPLOYEE":
                modalId = '#modalIdEmployee';
            editClass = 'editViewEmployeePerm';
            break;
    }
    let rightsBoxHTML = '';
    rightsBoxHTML = '<a href="javascript:void(0)" class="'+editClass+'" data-emp="'+emp.ID+'" data-toggle="modal" data-target="'+modalId+'" data-type="'+type+'" data-ids="'+ids+'">View Rights</a>';
    return rightsBoxHTML;
}

const columnDefinition = () => {
                let columnDefinition = {};
                columnDefinition = [
                    { name: "BRANCH_NAME", title: "BRANCH NAME", type: "text", width: 100, css:'text-center'},
                    { name: "EMP_NAME", title: "USER NAME", type: "text", width: 100, css:'text-center'},
                    { title: "RIGHTS", type: "text", width: 100, cellRenderer:function (value, item) {
                                let ids = (item.permissions.view_branch_admissions)?item.permissions.view_branch_admissions:'';
                                if (item.permissions.view_emp_admissions) {
                                    ids += ','+item.permissions.view_emp_admissions;
                                }
                                return  '<td>'+getRightsBoxHTML(ids,'BRANCH', ids, item)+'</td>';
                        },css:'text-center'
                    },
                    {
                        type: "control",cellRenderer : function (value, item) {
                            var tdInner = '';
                            tdInner = '<td></td>';
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
                    url: baseUrl + '/ajax/'+ api +'?_=' + currentTime + '&' + filterOpt,
                    dataType:'json',
                    data : filter,
                    success : function (data) {
                        empList = data;
                        getBranch('.office-branch');
                    }
                });
            }
        },
        fields: columnDefinition()
    });
}

// Render Branch Grid
const renderBranchList = (elementSel, ids = '') => {
    let idArray = ids.split(',');
    $(elementSel).jsGrid({
        height: "auto",
        width: "100%",
        sorting: false,
        paging: false,
        deleteConfirm: "Do you really want to delete client?",
        rowClass: function (item, itemIndex){ return '';},
        data:branchList,
        fields: [
            { type: "text", width: 10, css:'text-center', cellRenderer: function (value, item) {
                    let checked = '';
                    if (idArray.find(bId=>bId===item.id)) {
                        checked = 'checked="checked"';
                    }
                    return '<td><input type="checkbox" class="branch_id" value="'+item.id+'" '+checked+'/></td>';
                } },
            { name: "branch_name", title : "Branch Name",type: "text", width: 150, css:'text-center'},
            { name: "city", title : "City Name",type: "text", width: 150, css:'text-center'}
        ]
    });
}

// Branch Render
const getBranch = (renderElement) => {
    $.ajax({
        url: baseUrl + '/ajax/getBranch.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function( data ){
            branchList = data;
            renderBranch(data,renderElement);
        }
    });
}

const renderBranch = (data,element) => {
    let options = '<option value="">Branch</option>';
    let dataCount = data.length;
    for(let i = 0; i < dataCount; i++) {
        options += '<option value="'+data[i].id+'">'+data[i].branch_name+'</option>';
    }
    $(element).html(options);
}
// Employee Render
$(".office-branch").change(function(){
    let brnchId = $(this).val();
    $.ajax({
        url: baseUrl + "/ajax/getKeyPerson.php?_=" + currentTime,
        type:"POST",
        data:{id:brnchId},
        success: function( dataHtml ){
            $(".office-emp").html(dataHtml);
        }
    });
});
// Render Branch wise Employee Grid
let employeeTreeObject;
const getTreeData = (ids) => {
    let idArray = ids.split(',');
    let treeData = [];
    for (let bIndx=0; bIndx < branchList.length; bIndx++) {
        const empListBr = empList.filter(({ BRANCH_ID }) => BRANCH_ID === branchList[bIndx].id);
        let childObject = [];
        for (let eIndx=0;eIndx<empListBr.length;eIndx++) {
            childObject[eIndx] = {id:empListBr[eIndx].ID,text:empListBr[eIndx].EMP_NAME, checkedFieldName:!!(idArray.find(ID => ID === empListBr[eIndx].ID))}
        }
        treeData[bIndx] = {id:branchList[bIndx].id,text:branchList[bIndx].branch_name,'children':childObject, checkedFieldName:!!(idArray.find(ID => ID === branchList[bIndx].id))};
    }
    return treeData;
}
/// Branch User Tree
const getTreeItem = (data) => {
    var checkedHtml = '';
    checkedHtml = data.checked?'checked="checked"':'';
    return ('<li class="dd-item"><div class="dd-handle"> <input class="userPermTreeItem parent-'+data.parent+'" type="checkbox" data-parent="'+data.parent+'" data-type="'+data.type+'" '+checkedHtml+' value="'+data.id+'">&nbsp;&nbsp;'+data.name+'</div></li>');
}
const getUserTree = (empPermissions) => {
    let empPermissionsArray = empPermissions.split(',');
    let branchEmpTree = '<ol class="dd-list">';
    var branchName = '';
    for (let bIndx=0; bIndx < branchList.length; bIndx++) {
        branchName = branchList[bIndx].first_name + ' ' + branchList[bIndx].last_name;
        if(branchList[bIndx].first_name.charAt(0) === '.'){
            branchName = branchList[bIndx].last_name;
        }
        branchEmpTree += getTreeItem({id:branchList[bIndx].id,name:branchName,checked:(empPermissionsArray.includes(branchList[bIndx].id)),type:"branch",parent:''});
        const empListBr = empList.filter(({ BRANCH_ID }) => BRANCH_ID === branchList[bIndx].id);
        let childObject = '<ol class="dd-list">';
        for (let eIndx=0;eIndx<empListBr.length;eIndx++) {
            //childObject[eIndx] = {id:empListBr[eIndx].ID,text:empListBr[eIndx].EMP_NAME, checkedFieldName:!!(idArray.find(ID => ID === empListBr[eIndx].ID))}
            childObject += getTreeItem({id:empListBr[eIndx].ID,name:empListBr[eIndx].EMP_NAME,checked:(empPermissionsArray.includes(branchList[bIndx].id) || empPermissionsArray.includes(empListBr[eIndx].ID)),type:"emp",parent:branchList[bIndx].id});
        }
        childObject += '</ol>';
        //treeData[bIndx] = {id:branchList[bIndx].id,text:branchList[bIndx].branch_name,'children':childObject, checkedFieldName:!!(idArray.find(ID => ID === branchList[bIndx].id))};
        branchEmpTree += childObject;
    }
    branchEmpTree += '</ol>';
    return branchEmpTree;
}

const getViewDueFees = () => {
    //$('.btnViewDueFeesSaveTree').
    var permissions = new Object();
    var arrViewBranchPerm = new Array();
    var arrViewEmpPerm = new Array();
    $('.userPermTreeItem:checked').each(function(){
        //console.log($(this).val());
        if ($(this).attr('data-parent')=='') {
            arrViewBranchPerm.push($(this).val());
        } else {
            if (!arrViewBranchPerm.includes($(this).attr('data-parent'))) {
                arrViewEmpPerm.push($(this).val());        
            }    
        }
    });
    permissions['view_branch_admissions'] = arrViewBranchPerm.toString();
    permissions['view_emp_admissions'] = arrViewEmpPerm.toString();
    return permissions;
}


$(".branchTree").on("click",".userPermTreeItem",function(){
    if ($(this).attr('data-parent')=='') {
        var isChecked = $(this).prop('checked');
        $('.parent-'+$(this).val()).each(function(){
            $(this).prop('checked',isChecked);
        });
    }
 })

$('#btnViewDueFeesSaveTree').click(function(){
    //getViewDueFees();
    $('#btnViewDueFeesSaveTree').prop('disabled',true);
    let params = {};
    params['rights']=getViewDueFees();
    params['emp_id']=currentEmp.ID;
    $.ajax({
        url: baseUrl + "/ajax/saveUserPermission.php?_=" + currentTime,
        type:"POST",
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(params),
        success: function( data ) {
            if (data.success) {
                currentEmp.permissions.view_emp_admissions = data.result.rights.view_emp_admissions;
                currentEmp.permissions.view_branch_admissions = data.result.rights.view_branch_admissions;
                renderGrid('.userList');
                $('.exp-alert').alert('close');
                $('.container-branchTree').prepend(getAlertBox('success',{title:'Pesmission Updated Successfuly.',text:''}));
            }
            $('#btnViewDueFeesSaveTree').prop('disabled',false);        
        }
    });
});

$('#btnViewDueFeesResetTree').click(function(){
    $(this).prop('disabled',true);
    let params = {};
    params['rights']={view_branch_admissions:"",view_emp_admissions:""};
    params['emp_id']=currentEmp.ID;
    $.ajax({
        url: baseUrl + "/ajax/saveUserPermission.php?_=" + currentTime,
        type:"POST",
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(params),
        success: function( data ){
            if (data.success) {
                currentEmp.permissions.view_emp_admissions = data.result.rights.view_emp_admissions;
                currentEmp.permissions.view_branch_admissions = data.result.rights.view_branch_admissions;
                renderGrid('.userList');
                let idList = (currentEmp.permissions.view_branch_admissions)?currentEmp.permissions.view_branch_admissions:'';
                idList += (currentEmp.permissions.view_emp_admissions)?',' + currentEmp.permissions.view_emp_admissions:'';
                renderEmployeeList('.branchTree', idList,3);
                $('.exp-alert').alert('close');
                $('.container-branchTree').prepend(getAlertBox('success',{title:'Pesmission Reset Successfuly.',text:''}));
            }
            $('#btnViewDueFeesResetTree').prop('disabled',false);
        }
    });
});

function renderEmployeeList(elementSel, dataArray, empId=1)
{
    $(elementSel).html(getUserTree(dataArray));
}
/// Branch User Trees

/// Default Load ///
renderGrid('.userList');
getBranch('.perm_branch');
/// Default Load ///

$("body").on('click', ".editViewBranchPerm", function (){
    let empId = $(this).attr('data-emp');
    currentEmp = empList.find(({ ID }) => ID === empId);
    $(".userNamePerm").text(currentEmp.EMP_NAME);
});

$("body").on('click', ".viewDueFeesPermission", function (){
    let idList = (currentEmp.permissions.view_branch_admissions)?currentEmp.permissions.view_branch_admissions:'';
    idList += (currentEmp.permissions.view_emp_admissions)?',' + currentEmp.permissions.view_emp_admissions:'';
    renderEmployeeList('.branchTree', idList,3);
});

/*let selectEmpUsersList = '';
let currentEmpId = '';
$("body").on('click', ".editViewEmployeePerm", function (){
    selectEmpUsersList = $(this).attr('data-ids');
    currentEmpId = $(this).attr('data-emp');
    $(".userNamePerm").text(empList.find(({ ID }) => ID === currentEmpId).EMP_NAME);
});*/

$(".perm_branch").change(function (){
    const empListBr = empList.filter(({ BRANCH_ID }) => BRANCH_ID === $(this).val());
    renderEmployeeList('.empList', empListBr, selectEmpUsersList);
    selectEmpUsersList = '';
});

//Save Emp Permission
const clearList = (element) =>{
    $(element).html('');
}
$("body").on('click', ".save-emp-permission", function (){
    let userIds = [] ;
    $('.user_id:checked').each(function(indx,val){
        userIds.push($(val).val());
    });
    saveViewUserRights(userIds);
});

const saveViewUserRights = (userIds) => {
    $.ajax({url: baseUrl + "/ajax/saveUserRights.php?_=" + currentTime,
        type:"POST",
        data:JSON.stringify({user_ids:userIds,right:'view_emp_admissions',emp_id:currentEmp.ID}),
        contentType : 'application/json',
        dataType:'json',
        success: function( dataHtml ){
          console.log(dataHtml);
            $(".close-emp-permission").trigger("click");
            currentEmpId = '';
            clearList('.empList');
            renderGrid('.userList');
            $(".userNamePerm").text('NONE');
        }
    });
};

$("#btnSaveTree").click(function () {
    let checkedIds = employeeTreeObject.getCheckedNodes();
    console.log(checkedIds);
});