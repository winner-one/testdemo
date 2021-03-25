define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shopro/goods/days_price/index' + location.search,
                    add_url: 'shopro/goods/days_price/add',
                    edit_url: 'shopro/goods/days_price/edit',
                    del_url: 'shopro/goods/days_price/del',
                    multi_url: 'shopro/goods/days_price/multi',
                    import_url: 'shopro/goods/days_price/import',
                    table: 'shopro_goods_sku_days_price',
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
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        // { field: 'goods_id', title: __('Goods_id') },
                        { field: 'shoprogoods.title', title: __('Goods_id') },
                        // { field: 'sku_price_ids', title: __('Sku_price_ids'), operate: 'LIKE' },
                        { field: 'shoprogoodsskuprice.goods_sku_text', title: __('Sku_price_ids'), operate: 'LIKE' },
                        { field: 'day', title: __('Day'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false },
                        { field: 'price', title: __('Price'), operate: 'BETWEEN' },
                        { field: 'stock', title: __('Stock'), operate: 'BETWEEN' },
                        { field: 'status', title: __('Status'), searchList: { "0": __('Status 0'), "1": __('Status 1') }, formatter: Table.api.formatter.status },
                        { field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'shoprogoods.title', title: __('Shoprogoods.title'), operate: 'LIKE' },
                        { field: 'shoprogoodsskuprice.goods_sku_text', title: __('Shoprogoodsskuprice.goods_sku_text'), operate: 'LIKE' },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
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
                url: 'shopro/goods/days_price/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
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
                                    url: 'shopro/goods/days_price/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'shopro/goods/days_price/destroy',
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
            //选择器的初始化
            initSelectPage();
            Controller.api.bindevent();
        },
        edit: function () {
            //选择器的初始化
            initSelectPage();
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    function initSelectPage() {
        //监听第一级的变化，清空第二级的值。
        $('#c-goods_id').on('change', function () {
            console.log('aaaaaaa')
            $("#c-sku_price_ids").selectPageClear();
        });

        //点击第二级给第二级赋 第一级选中的值params查询条件。
        $("#c-sku_price_ids").data("params", function () {
            const goods_id = $("input[name='row[goods_id]']").val();
            return { custom: { goods_id: goods_id } };
        });
    }
    return Controller;
});