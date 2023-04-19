import {getCommon, getDefaultConfig} from "./common";

let commons = getCommon();
let api = 'getSMSAPIs.php';

let defaultConfig = getDefaultConfig();

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
                                url: defaultConfig.baseUrl + '/ajax/'+ api +'?_=' + defaultConfig.currentTime + '&type=' + commons.comm_api_url_type + '&' + filterOpt ,
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

export {renderGrid};