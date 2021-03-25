define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shopro/user_wallet_apply/index' + location.search,
                    add_url: 'shopro/user_wallet_apply/add',
                    edit_url: 'shopro/user_wallet_apply/edit',
                    del_url: 'shopro/user_wallet_apply/del',
                    multi_url: 'shopro/user_wallet_apply/multi',
                    table: 'shopro_user_wallet_apply',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'money', title: __('Money'), operate:'BETWEEN'},
                        {field: 'charge_money', title: __('Charge_money'), operate:'BETWEEN'},
                        {field: 'service_fee', title: __('Service_fee'), operate:'BETWEEN'},
                        // {field: 'get_type', title: __('Get_type'), 
                        // searchList: {"bank":__('Bank')},
                        //  formatter: function (value, row) {
                        //     return `<div>
                        //         <div>${row.get_type_text}</div>
                        //     </div>`
                        // }                       
                        // },
                        {field: 'real_name', title: __('Real_name')},
                        {field: 'bank_info', title: __('Bank_info'),
                            formatter: function (value, row) {
                            let bankInfo = JSON.parse(row.bank_info);
                                return `<div>
                                    <div>${bankInfo.bank_name}</div>
                                    <div>${bankInfo.card_no}</div>
                                </div>`
                            }
                        },
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"-1":__('Status -1')}, formatter: Table.api.formatter.status},
                        {field: 'status_msg', title: __('Status_msg')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: '提现操作',
                            table: table,
                            operate: false,
                            formatter: function (value, row) {
                                if(row.status==0){
                                    return `<div>
                                <button class="btn btn-xs btn-success withdrawreal" data-id="${row.id}" data-sta="1" >同意</button>
                                <button class="btn btn-xs btn-danger withdrawrefuse" data-id="${row.id}"  data-sta="-1">拒绝</button>
                                </div>`
                                }else{
                                    return `<div>已完成</div>`
                                }
                                
                            },                           
                        },
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                    ]
                ]
            });
            $(document).on("click", ".withdrawrefuse", function () {
                let id = $(this).attr("data-id")
                let sta=$(this).attr("data-sta")
                Layer.prompt({title: __('请输入驳回理由'), formType: 0}, function (value, index) {
                    Backend.api.ajax({
                        url: "shopro/user_wallet_apply/applyOper/id/" + id,
                        data: {
                         status:sta,
                        status_msg:value
                        },

                    }, function () {
                        table.bootstrapTable('refresh');
                        Layer.close(index);
                    });

                });
            });
            $(document).on("click", ".withdrawreal", function () {
                let id = $(this).attr("data-id")
                let sta=$(this).attr("data-sta")
                    Backend.api.ajax({
                        url: "shopro/user_wallet_apply/applyOper/id/" + id,
                        data: {
                         status:sta,
                        status_msg:''
                        },

                    }, function () {
                        table.bootstrapTable('refresh');
                    });

            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'shopro/user_wallet_apply/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'shopro/user_wallet_apply/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'shopro/user_wallet_apply/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});