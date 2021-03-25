define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            function debounce(handle, delay) {
                let time = null;
                return function () {
                    let self = this,
                        arg = arguments;
                    clearTimeout(time);
                    time = setTimeout(function () {
                        handle.apply(self, arg);
                    }, delay)
                }
            }
            var orderIndex = new Vue({
                el: "#orderIndex",
                data() {
                    return {
                        screenType: false,
                        orderScreenList: {},
                        orderList: [],
                        multipleSelection: [],
                        express_dialog: false,
                        deliverForm: {
                            item_ids: '',
                            express_name: '',
                            express_code: '',
                            express_no: ''
                        },
                        deliverFormInit: {
                            item_ids: '',
                            express_name: '',
                            express_code: '',
                            express_no: ''
                        },
                        expressCompany: [],
                        disabledBtn: false,
                        //分页
                        currentPage: 1,
                        totalPage: 0,
                        offset: 0,
                        limit: 10,
                        dispatchList: [],
                        dispatchListItem: [],
                        totalStatus: '',
                        optRecordDialog: false,
                        optList: [],
                        focusi: false,

                        //快递公司分页
                        selectSearchKey: '',
                        selectOffset: 0,
                        selectLimit: 6,
                        selectCurrentPage: 1,
                        selectTotalPage: 0,

                        // form搜索
                        searchForm:{
                            status:"all",
                            createtime:[],
                            form_1_key:"order_sn",
                            form_1_value:"",
                            platform:"",
                            dispatch_type:"",
                            type:"",
                            pay_type:"",
                            activity_type:"",
                            goods_type:"",
                            goods_title:"",
                            form_2_key:"user_id",
                            form_2_value:""
                        },
                        searchFormInit:{
                            status:"all",
                            createtime:[],
                            form_1_key:"order_sn",
                            form_1_value:"",
                            platform:"",
                            dispatch_type:"",
                            type:"",
                            pay_type:"",
                            activity_type:"",
                            goods_type:"",
                            goods_title:"",
                            form_2_key:"user_id",
                            form_2_value:""
                        },
                        searchOp:{
                            status:"=",
                            createtime:"range",
                            order_sn:"=",
                            id :"=",
                            aftersale_sn:"=",  
                            transaction_id :"=",
                            platform :"=", 
                            dispatch_type :"=", 
                            type :"=", 
                            pay_type:"=",
                            activity_type:"like",
                            goods_type:"=",
                            goods_title:"like",
                            user_id :"=",
                            nickname :"like",
                            user_phone: "like",
                            consignee :"like",
                            phone :"like",
                        },
                    }
                },
                mounted() {
                    if(new URLSearchParams(location.search).get('status')){
                        this.searchForm.status=new URLSearchParams(location.search).get('status')
                    }
                    if(new URLSearchParams(location.search).get('datetimerange')){
                        this.searchForm.createtime=new URLSearchParams(location.search).get('datetimerange').split(' - ')
                    }
                    this.reqOrderList()
                    this.reqOrderScreenList()
                },
                methods: {
                    //筛选
                    changeSwitch() {
                        this.screenType=!this.screenType;
                    },
                    reqOrderScreenList() {
                        var that = this;
                        Fast.api.ajax({
                            url: 'shopro/order/order/getType',
                            loading: true,
                            type: 'GET',
                            data: {}
                        }, function (ret, res) {
                            that.orderScreenList = res.data
                            return false;
                        })
                    },
                    //导出
                    goExport() {
                        var that = this;
                        // if(offset==0 && limit==10){
                        //     that.offset=offset;
                        //     that.limit=limit;
                        // }
                        let filter={}
                        let op={}
                        for (key in that.searchForm) {
                            if(key!='status' && key!='createtime' && key!='form_1_key' && key!='form_1_value' && key!='form_2_key' && key!='form_2_value'){
                                if(that.searchForm[key]!='' && that.searchForm[key]!='all'){
                                    filter[key] = that.searchForm[key];  
                                }
                            }else if(key=='form_1_value'){
                                if(that.searchForm[key]!=''){
                                    filter[that.searchForm.form_1_key] = that.searchForm[key];  
                                }
                            }else if(key=='form_2_value'){
                                if(that.searchForm[key]!=''){
                                    filter[that.searchForm.form_2_key] = that.searchForm[key];  
                                }
                            }else if(key=='createtime'){
                                if(that.searchForm[key]){
                                    if(that.searchForm[key].length>0){
                                        filter[key] = that.searchForm[key].join(' - ');  
                                    }
                                }
                            }else if(key=='status'){
                                if(that.searchForm[key]!='' && that.searchForm[key]!='all'){
                                    filter[key] = that.searchForm[key];  
                                }
                            }
                        }
                        for(key in filter){
                            op[key]=that.searchOp[key]
                        }

                        window.location.href = "order/export?offset=" + that.offset + "&limit=" + that.limit + "&filter=" + JSON.stringify(filter) + "&op=" + JSON.stringify(op);
                    },
                    //请求
                    reqOrderList(offset,limit) {
                        var that = this;
                        if(offset==0 && limit==10){
                            that.offset=offset;
                            that.limit=limit;
                        }
                        let filter={}
                        let op={}
                        for (key in that.searchForm) {
                            if(key!='status' && key!='createtime' && key!='form_1_key' && key!='form_1_value' && key!='form_2_key' && key!='form_2_value'){
                                if(that.searchForm[key]!='' && that.searchForm[key]!='all'){
                                    filter[key] = that.searchForm[key];  
                                }
                            }else if(key=='form_1_value'){
                                if(that.searchForm[key]!=''){
                                    filter[that.searchForm.form_1_key] = that.searchForm[key];  
                                }
                            }else if(key=='form_2_value'){
                                if(that.searchForm[key]!=''){
                                    filter[that.searchForm.form_2_key] = that.searchForm[key];  
                                }
                            }else if(key=='createtime'){
                                if(that.searchForm[key]){
                                    if(that.searchForm[key].length>0){
                                        filter[key] = that.searchForm[key].join(' - ');  
                                    }
                                }
                            }else if(key=='status'){
                                if(that.searchForm[key]!='' && that.searchForm[key]!='all'){
                                    filter[key] = that.searchForm[key];  
                                }
                            }
                        }
                        for(key in filter){
                            op[key]=that.searchOp[key]
                        }
                        // return false;
                        Fast.api.ajax({
                            url: 'shopro/order/order/index',
                            loading: true,
                            type: 'GET',
                            data: {
                                filter: JSON.stringify(filter),
                                op: JSON.stringify(op),
                                offset: that.offset,
                                limit: that.limit,
                            }
                        }, function (ret, res) {
                            that.orderList = res.data.rows;
                            that.totalPage = res.data.total;
                            that.focusi = false;
                            return false;
                        })
                    },
                    screenEmpty() {
                        this.searchForm=JSON.parse(JSON.stringify(this.searchFormInit))
                    },
                    handleSelectionChange(val) {
                        this.multipleSelection = val;
                    },
                    //分页(更换页面显示条数)
                    handleSizeChange(val) {
                        this.offset = 0
                        this.limit = val
                        this.currentPage = 1;
                        this.reqOrderList()
                    },
                    //当前是第几页
                    handleCurrentChange(val) {
                        this.offset = (val - 1) * this.limit;
                        this.currentPage = 1;
                        this.reqOrderList()
                    },
                    viewAftersale(aftersale_id) {
                        let that=this;
                        Fast.api.open("shopro/order/aftersale/detail?id=" + aftersale_id, "售后详情",{
                            callback(){
                                that.reqOrderList()
                            }
                        });
                    },
                    goDetail(id) {
                        let that=this;
                        Fast.api.open('shopro/order/order/detail?id=' + id, "查看详情",{
                            callback(){
                                that.reqOrderList()
                            }
                        });

                    },
                    openDialog(item, index) {
                        this.dispatchList = item
                        this.dispatchListItem = []
                        this.dispatchListItem.push(item.item[index])
                        this.express_dialog = true
                        this.getExpressCompany()
                    },
                    handleClose() {
                        this.express_dialog = false;
                        this.deliverForm = JSON.parse(JSON.stringify(this.deliverFormInit));
                    },
                    expressFunc(type) {
                        var that = this;
                        if (type == 'yes') {
                            that.expressCompany.forEach(i => {
                                if (i.name == that.deliverForm.express_name) {
                                    that.deliverForm.express_code = i.code
                                }
                            })
                            Fast.api.ajax({
                                url: `shopro/order/order/send/id/${that.dispatchList.id}/item_id/${that.deliverForm.item_ids}`,
                                loading: true,
                                data: {
                                    express_name: that.deliverForm.express_name,
                                    express_no: that.deliverForm.express_no,
                                    express_code: that.deliverForm.express_code,
                                }
                            }, function (ret, res) {
                                that.expressCompany = [];
                                res.data.item.forEach(item => {
                                    that.expressCompany.push(item)
                                });
                                that.reqOrderList();
                                that.express_dialog = false
                                that.deliverForm = JSON.stringify(that.deliverFormInit);
                            })
                        } else {
                            that.express_dialog = false
                            that.deliverForm = JSON.stringify(that.deliverFormInit);
                        }

                    },
                    deliverSelectionChange(val) {
                        let item_ids = []
                        val.forEach(i => {
                            item_ids.push(i.id)
                        })
                        this.deliverForm.item_ids = item_ids.join(',')
                    },
                    goGroupon(type, id) {
                        let that = this;
                        if (id == 0) {
                            return false;
                        }
                        if (type == "groupon") {
                            parent.Fast.api.open(`shopro/activity/groupon/detail/id/${id}`, "查看详情", {
                                callback(data) {
                                    that.reqOrderList()
                                }
                            });
                        }
                    },
                    optRecord(id) {
                        let that = this;
                        Fast.api.ajax({
                            url: `shopro/order/order/actions/id/${id}`,
                            loading: true,
                            data: {}
                        }, function (ret, res) {
                            that.optList = res.data;
                            that.optRecordDialog = true
                            return false;
                        })
                    },
                    goOrderRefresh() {
                        this.focusi = true;
                        this.reqOrderList()
                    },

                    getExpressCompany() {
                        let that = this;
                        Fast.api.ajax({
                            url: 'shopro/express/select',
                            loading: false,
                            type: 'GET',
                            data: {
                                searchWhere: that.selectSearchKey,
                                limit: that.selectLimit,
                                offset: that.selectOffset
                            }
                        }, function (ret, res) {
                            that.expressCompany = res.data.rows;
                            that.selectTotalPage = res.data.total;
                            return false;
                        })
                    },
                    selectDebounceFilter: debounce(function () {
                        this.getExpressCompany()
                    }, 1000),
                    selectFilter(val) {
                        this.selectSearchKey = val;
                        this.selectLimit = 6;
                        this.selectOffset = 0;
                        this.selectCurrentPage = 1;
                        this.selectDebounceFilter();
                    },
                    selectSizeChange(val) {
                        this.selectOffset = 0;
                        this.selectLimit = val;
                        this.selectCurrentPage = 1;
                        this.getExpressCompany();
                    },
                    selectCurrentChange(val) {
                        this.selectOffset = (val - 1) * 6;
                        this.selectLimit = 6;
                        this.selectCurrentPage = 1;
                        this.getExpressCompany();
                    },
                    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
                        if (rowIndex >= 0) {
                          if (columnIndex === 0) {
                            return [, 0];
                          } else if (columnIndex === 1) {
                            return [1, 4];
                          }
                        }
                      },
                },
                watch: {
                    deliverForm: {
                        handler: function (newVal) {
                            // if (newVal.item_ids && newVal.express_name && newVal.express_no) {
                                this.disabledBtn = true
                            // } else {
                            //     this.disabledBtn = false
                            // }
                        },
                        deep: true
                    },
                }
            })
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
                url: 'shopro/order/order/recyclebin' + location.search,
                pk: 'type',
                sortName: 'type',
                columns: [
                    [{
                            checkbox: true
                        },
                        {
                            field: 'type',
                            title: __('type')
                        },
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            wtypeth: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [{
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'shopro/order/order/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'shopro/order/order/destroy',
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
        detail: function () {
            function debounce(handle, delay) {
                let time = null;
                return function () {
                    let self = this,
                        arg = arguments;
                    clearTimeout(time);
                    time = setTimeout(function () {
                        handle.apply(self, arg);
                    }, delay)
                }
            }
            var vue = new Vue({
                el: "#orderDetail",
                data() {
                    return {
                        orderId: null,
                        orderDetail: [],
                        orderDetailCopy: [],
                        goodsList: [], //所有商品
                        itemList: [], //物流快递
                        refundList: [], //去除退款的
                        nosendList: [], //未发货
                        stepActive: 1,
                        activeName: 1,
                        deliveryActive: 0,
                        deliverForm: {
                            item_ids: '',
                            express_name: '',
                            express_code: '',
                            express_no: ''
                        },
                        deliverFormInit: {
                            item_ids: '',
                            express_name: '',
                            express_code: '',
                            express_no: ''
                        },
                        expressCompany: [],
                        express_id: null, //包裹id
                        deliveryEditFlag: false,
                        disabledBtn: false,
                        express_dialog: false,
                        refund_money: '',
                        refund_dialog: false,
                        refund_orderId: '',
                        refund_itemId: '',
                        addMemoFlag: false,
                        receivingAddress: [],
                        receivingAddressName: [],
                        areaList: [],

                        packageList: [],
                        logistics: [],

                        //快递公司分页
                        selectSearchKey: '',
                        selectOffset: 0,
                        selectLimit: 6,
                        selectCurrentPage: 1,
                        selectTotalPage: 0,
                    }
                },
                mounted() {
                    window.location.search.replace('?', '').split("&").forEach(i => {
                        if (i.split('=')[0] == 'id') {
                            this.orderId = i.split('=')[1]
                        }
                    })
                    this.getDetail();
                    this.getExpressCompany();
                },
                methods: {
                    getDetail() {
                        let that = this;
                        Fast.api.ajax({
                            url: `shopro/order/order/detail?id=${that.orderId}`,
                            loading: true,
                            data: {}
                        }, function (ret, res) {
                            that.orderDetail = res.data.order;
                            that.goodsList = res.data.item;
                            //去除别的发货方式
                            let onlyExpress = []
                            res.data.item.forEach(i => {
                                if (i.dispatch_type == 'express') {
                                    onlyExpress.push(i)
                                }
                            })
                            that.itemList = onlyExpress;
                            let refundArr = []
                            onlyExpress.forEach(i => {
                                if (i.refund_status < 2) {
                                    refundArr.push(i);
                                }
                            })
                            that.refundList = refundArr;
                            that.packageList = res.data.express;
                            that.changeSteps();
                            that.nosendListFunc();
                            that.receivingAddress.push(that.orderDetail.province_id);
                            that.receivingAddress.push(that.orderDetail.city_id);
                            that.receivingAddress.push(that.orderDetail.area_id);
                            that.receivingAddressName.push(that.orderDetail.province_name);
                            that.receivingAddressName.push(that.orderDetail.city_name);
                            that.receivingAddressName.push(that.orderDetail.area_name);
                            return false;
                        })
                    },
                    nosendListFunc() {
                        let isExpressArr = []
                        this.refundList.forEach(i => {
                            if (i.dispatch_status == 0) {
                                isExpressArr.push(i)
                            }
                        })
                        this.nosendList = isExpressArr;
                    },
                    changeSteps() {
                        let that = this;
                        let status_code = that.orderDetail.status_code
                        if (that.orderDetail.status == 0) {
                            that.stepActive = 1
                        } else if (that.orderDetail.status == 1) {
                            that.stepActive = 2;
                            switch (status_code) {
                                case 'nosend':
                                    that.stepActive = 2
                                    break;
                                case 'noget':
                                    that.stepActive = 3
                                    break;
                                case 'nocomment':
                                    that.stepActive = 4
                                    break;
                                case 'commented':
                                    that.stepActive = 4
                                break;
                            }
                        } else if (that.orderDetail.status == 2) {
                            that.stepActive = 4;
                        }
                    },
                    openDialog(type, order_id, id) {
                        if (type == 'express') {
                            this.express_dialog = true;
                            this.getExpressCompany();
                            if (order_id) {
                                this.express_id = order_id;
                                let selectArr = [];
                                this.refundList.forEach(i => {
                                    this.packageList[this.activeName - 1].item.forEach(j => {
                                        if (i.id == j.id) {
                                            selectArr.push(i)
                                        }
                                    })
                                })
                                this.deliverForm.express_name = this.packageList[this.activeName - 1].express_name;
                                this.deliverForm.express_no = this.packageList[this.activeName - 1].express_no;
                                this.deliverForm.express_code = this.packageList[this.activeName - 1].express_code;
                                this.$nextTick(() => {
                                    this.toggleSelection(selectArr)
                                })
                            }
                        } else if (type = 'refund') {
                            this.refund_dialog = true
                            this.refund_orderId = order_id
                            this.refund_itemId = id
                        }
                    },
                    deliverSelectionChange(val) {
                        let item_ids = []
                        val.forEach(i => {
                            item_ids.push(i.id)
                        })
                        this.deliverForm.item_ids = item_ids.join(',')
                    },
                    expressFuc(type) {
                        var that = this;
                        if (type == 'yes') {
                            that.expressCompany.forEach(i => {
                                if (i.name == that.deliverForm.express_name) {
                                    that.deliverForm.express_code = i.code
                                }
                            })
                            let reqUrl = `shopro/order/order/send/id/${that.orderDetail.id}/item_id/${that.deliverForm.item_ids}/express_id/${that.express_id}`
                            if (that.express_id == null) {
                                reqUrl = `shopro/order/order/send/id/${that.orderDetail.id}/item_id/${that.deliverForm.item_ids}`
                            }
                            Fast.api.ajax({
                                url: reqUrl,
                                loading: true,
                                data: {
                                    express_name: that.deliverForm.express_name,
                                    express_code: that.deliverForm.express_code,
                                    express_no: that.deliverForm.express_no
                                }
                            }, function (ret, res) {
                                that.getDetail();
                                that.express_dialog = false;
                                that.deliverForm = that.deliverFormInit;
                                if (that.packageList[that.activeName - 1]) {
                                    that.activeName = that.packageList.length;
                                }
                                that.express_id = null;
                            })
                        } else {
                            that.express_dialog = false;
                            that.deliverForm = that.deliverFormInit;
                            that.express_id = null;
                        }
                    },
                    refundFunc(type) {
                        let that = this;
                        if (type == 'yes') {
                            let reqUrl = `shopro/order/order/refund/id/${that.refund_orderId}/item_id/${that.refund_itemId}`
                            if (that.refund_itemId == null) {
                                reqUrl = `shopro/order/order/refund/id/${that.refund_orderId}`
                            }
                            Fast.api.ajax({
                                url: reqUrl,
                                loading: true,
                                data: {
                                    refund_money: that.refund_money
                                }
                            }, function (ret, res) {
                                that.getDetail();
                                that.refund_dialog = false;
                                that.refund_money = "";
                            })
                        } else {
                            that.refund_dialog = false;
                            that.refund_money = "";
                        }
                    },
                    copyMsg() {
                        let clipboard = new Clipboard('.copy-msg')
                        clipboard.on('success', function () {
                            alert("复制成功")
                        });
                        clipboard.on('error', function () {
                            alert("复制失败")
                        });
                    },
                    deliveryEdit() {
                        var that = this;
                        Fast.api.ajax({
                            url: `shopro/area/select`,
                            loading: false,
                            type: 'GET',
                        }, function (ret, res) {
                            that.areaList = res.data;
                            that.deliveryEditFlag = true;
                            return false;
                        })
                    },
                    deliveryDefine() {
                        let that = this;
                        Fast.api.ajax({
                            url: `shopro/order/order/editConsignee/id/${that.orderDetail.id}`,
                            loading: true,
                            data: {
                                consignee: that.orderDetail.consignee,
                                phone: that.orderDetail.phone,
                                province_id: that.receivingAddress[0],
                                province_name: that.receivingAddressName[0],
                                city_id: that.receivingAddress[1],
                                city_name: that.receivingAddressName[1],
                                area_id: that.receivingAddress[2],
                                area_name: that.receivingAddressName[2],
                                address: that.orderDetail.address,
                            }
                        }, function (ret, res) {
                            that.orderDetail = res.data
                            that.deliveryEditFlag = false;
                        })
                    },
                    changeAddress(value) {
                        let that = this;
                        that.receivingAddress = value
                        let arr = []
                        that.areaList.forEach((i, index) => {
                            if (i.id == that.receivingAddress[0]) {
                                arr.push(i.label)
                                if (i.children.length > 0) {
                                    i.children.forEach((j, inde) => {
                                        if (j.id == that.receivingAddress[1]) {
                                            arr.push(j.label)
                                            if (j.children.length > 0) {
                                                j.children.forEach(k => {
                                                    if (k.id == that.receivingAddress[2]) {
                                                        arr.push(k.label)
                                                    }
                                                })
                                            }
                                        }
                                    })
                                }
                            }
                        })
                        that.receivingAddressName = arr
                    },
                    viewEvaluation(order_item_id) {
                        parent.Fast.api.open("shopro/goods/comment/index?order_item_id=" + order_item_id, "查看评价");
                    },
                    viewAftersale(aftersale_id) {
                        Fast.api.open("shopro/order/aftersale/detail?id=" + aftersale_id, "售后详情");
                    },
                    goUserDetail(user_id) {
                        Fast.api.open('shopro/user/user/profile?id=' + user_id, "用户详情");
                    },
                    addMemo(type) {
                        if (type == 'cancel') {
                            this.addMemoFlag = false;
                            this.orderDetail.memo = this.orderDetailCopy.memo;
                        } else {
                            this.addMemoFlag = true;
                        }
                    },
                    addMemoreq(id) {
                        var that = this;
                        Fast.api.ajax({
                            url: `shopro/order/order/editMemo/id/${id}`,
                            loading: true,
                            data: {
                                memo: that.orderDetail.memo,
                            }
                        }, function (ret, res) {
                            that.orderDetail = res.data;
                            that.addMemoFlag = false
                        })
                    },
                    toggleSelection(rows) {
                        if (rows) {
                            rows.forEach(row => {
                                this.$refs.multipleTable.toggleRowSelection(row);
                            });
                        } else {
                            this.$refs.multipleTable.clearSelection();
                        }
                    },
                    subscribe(id, type) {
                        let that = this;
                        Fast.api.ajax({
                            url: `shopro/order/order/getExpress/express_id/${id}`,
                            loading: false,
                            type: 'GET',
                            data: {
                                type: type
                            }
                        }, function (ret, res) {
                            that.getDetail();
                        })
                    },
                    getExpressCompany() {
                        let that = this;
                        Fast.api.ajax({
                            url: 'shopro/express/select',
                            loading: false,
                            type: 'GET',
                            data: {
                                searchWhere: that.selectSearchKey,
                                limit: that.selectLimit,
                                offset: that.selectOffset
                            }
                        }, function (ret, res) {
                            that.expressCompany = res.data.rows;
                            that.selectTotalPage = res.data.total;
                            return false;
                        })
                    },
                    selectDebounceFilter: debounce(function () {
                        this.getExpressCompany()
                    }, 1000),
                    selectFilter(val) {
                        this.selectSearchKey = val;
                        this.selectLimit = 6;
                        this.selectOffset = 0;
                        this.selectCurrentPage = 1;
                        this.selectDebounceFilter();
                    },
                    selectSizeChange(val) {
                        this.selectOffset = 0;
                        this.selectLimit = val;
                        this.selectCurrentPage = 1;
                        this.getExpressCompany();
                    },
                    selectCurrentChange(val) {
                        this.selectOffset = (val - 1) * 6;
                        this.selectLimit = 6;
                        this.selectCurrentPage = 1;
                        this.getExpressCompany();
                    },
                },
                watch: {
                    deliverForm: {
                        handler: function (newVal) {
                            if (newVal.item_ids && newVal.express_name && newVal.express_no) {
                                this.disabledBtn = true
                            } else {
                                this.disabledBtn = false
                            }
                        },
                        deep: true
                    },
                }
            })
            $('.more-ellipsis').each(function (i, obj) {
                var lineHeight = parseInt($(this).css("line-height"));
                var height = parseInt($(this).height());
                if ((height / lineHeight) > 2) {
                    $(this).addClass("more-ellipsis-after")
                    $(this).css("height", "36px");
                } else {
                    $(this).removeClass("more-ellipsis-after");
                }
            });
            Controller.api.bindevent();
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