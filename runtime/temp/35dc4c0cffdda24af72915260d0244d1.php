<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"D:\testdemo\self-travel\public/../application/admin\view\shopro\goods\goods\index.html";i:1606813170;s:66:"D:\testdemo\self-travel\application\admin\view\layout\default.html";i:1606813170;s:63:"D:\testdemo\self-travel\application\admin\view\common\meta.html";i:1606813170;s:65:"D:\testdemo\self-travel\application\admin\view\common\script.html";i:1606813170;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo \think\Config::get('fastadmin.adminskin'); ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <link rel="stylesheet" href="/assets/addons/shopro/libs/element/element.css">
<style>
    #goodsIndex {
        color: #444;
    }

    .el-pagination,
    .el-pagination__total,
    .el-input__inner,
    .el-pagination__jump {
        color: #444;
    }

    .common-btn {
        height: 32px;
        cursor: pointer;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .el-input__inner,
    .el-input__icon {
        line-height: 32px;
        height: 32px;
    }

    .btn-box {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        justify-content: space-between;
    }

    .refresh-btn {
        width: 32px;
        border: 1px solid #E6E6E6;
        font-size: 14px;
        margin-right: 20px;
    }

    .create-goods,
    .add-params,
    .add-level1-sku {
        width: 98px;
        background: #7536D0;
        color: #fff;
    }

    .create-goods {
        margin-right: 40px;
    }

    .create-goods span,
    .add-params span,
    .add-level1-sku span {
        margin-left: 8px;
    }

    .goods-name {
        display: flex;
        align-items: center;
    }

    .goods-img {
        width: 34px;
        height: 34px;
        margin-right: 10px;
        border: 1px solid #e6e6e6;
    }

    .el-table,
    .el-table thead,
    .el-table th {
        color: #444;
        font-weight: 500 !important;
        font-size: 13px;
    }

    .display-flex {
        display: flex;
        align-items: center;
    }


    .label-auto {
        width: 100%;
        height: 100%;
    }

    .el-radio-button__inner,
    .el-radio-button__inner:hover {
        height: 32px;
        line-height: 32px;
        width: 80px;
        text-align: center;
        padding: 0;
        color: #666;
    }

    .el-radio__input.is-checked+.el-radio__label,
    .el-tabs__item.is-active,
    .el-tabs__item:hover,
    .el-pager li.active,
    .el-select-dropdown__item.selected {
        color: #7438D5;
    }

    .el-radio__input.is-checked .el-radio__inner,
    .el-tabs__active-bar,
    .el-checkbox__input.is-checked .el-checkbox__inner,
    .el-checkbox__input.is-indeterminate .el-checkbox__inner {
        background: #7438D5;
        border-color: #7438D5;
    }

    .one-ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .sort-order {
        margin-left: 6px;
        color: #C0C4CC;
        flex-direction: row-reverse;
    }

    .recycle-btn {
        width: 88px;
        color: #444;
        border: #444 1px solid;
    }

    .recycle-btn i {
        margin-right: 6px;
        font-size: 14px;
    }

    .all-btn-up,
    .all-btn-del,
    .all-btn-up1 {
        width: 60px;
        border: 1px solid #E5E5E5;
        margin-right: 20px;
    }

    .all-btn-up1:hover {
        color: #7438D5;
        border-color: #7438D5;
    }

    .all-btn-up:hover {
        color: #50E3C2;
        border-color: #50E3C2;
    }

    .all-btn-del:hover {
        color: #FF5959;
        border-color: #FF5959;
    }

    .status-box {
        width: 100%;
        justify-content: space-between;
    }

    .status-btn {
        width: 56px;
        height: 26px;
        border: 1px solid #7438D5;
        color: #7438D5;
        font-size: 12px;
    }

    .status-btn-2 {
        color: #FF5959;
        border-color: #FF5959;
    }

    .status-btn-3 {
        color: #999;
        border-color: #999;
    }

    .el-table th {
        background: #F9F9F9;
        padding: 7px 0;
    }

    .el-tabs__item {
        width: 80px;
        padding: 0;
        padding-right: 20px;
        text-align: center;
        color: #666;
    }

    .el-tabs__nav-wrap::after {
        height: 1px;
    }

    .icon-top {
        margin-left: -5px;
    }

    .edit-text,
    .copy-text,
    .yuyue,
    .del-text {
        cursor: pointer;
        color: #444;
    }

    .day-edit-text {
        margin-left: 14px;
    }

    .copy-text {
        margin: 0 14px;
    }

    .yuyue:hover {
        color: #409eff;
    }

    .edit-text:hover {
        color: #7438D5;
    }

    .copy-text:hover {
        color: #01CFA1;
    }

    .del-text:hover {
        color: #FF5959;
    }

    .el-button--primary,
    .el-button--primary:hover {
        background: #7438D5 !important;
        border-color: #7438D5 !important;
        color: #fff;
    }

    .el-popover {
        left: 46px;
        top: 10px;
    }

    .choose-container {
        margin-bottom: 10px;
    }

    .custom-choose,
    .custom-choose-type,
    .custom-choose-sub {
        height: 50px;
        border-radius: 4px;
        justify-content: space-between;
        padding: 0 20px;
        background: #fff;
    }

    .custom-choose-type,
    .custom-choose-sub {
        justify-content: flex-start;
    }

    .custom-choose-sub {
        height: 70px;
        padding: 0 10px 20px 20px;
    }

    .choose-status-tip {
        margin-right: 12px;
    }

    .custom-choose-type-tip {
        margin-right: 16px;
    }

    .choose-btn {
        width: 80px;
        border: 1px solid #E6E6E6;
        margin-right: 10px;
        height: 30px;
    }

    .choose-btn-active {
        background-color: #7536D0;
        border: none;
        color: #fff;
    }

    .choose-price {
        width: 140px;
    }

    .choose-price-line {
        margin: 0 14px;
    }

    .el-input-group__append,
    .el-input-group__prepend {
        width: 30px;
        text-align: center;
        padding: 0;
    }

    .custom-table {
        padding: 20px 20px 30px;
        background: #fff;
    }

    .custom-header {
        justify-content: space-between;
        margin-bottom: 14px;
    }

    label {
        margin-bottom: 0;
    }

    .custom-refresh {
        width: 34px;
        height: 32px;
        border: 1px solid #E6E6E6;
        font-size: 16px;
        justify-content: center;
        margin-right: 20px;
        border-radius: 4px;
    }

    .page-container {
        justify-content: space-between;
        margin-top: 30px;
    }

    .el-table td,
    .el-table th,
    .el-table th.is-leaf {
        border-color: #f7f7f7;
        padding: 0;
    }

    .el-table th .cell,
    .el-table td .cell {
        height: 44px;
        display: flex;
        align-items: center;
        line-height: 20px;
        justify-content: center;
    }

    .el-table th .cell {
        height: 40px;
    }

    .goods-title {
        margin-bottom: 4px;
        width: 154px;
        line-height: 14px;
    }

    .activity-type {
        justify-content: space-around;
    }

    .activity-type-btn {
        width: 40px;
        height: 20px;
        padding-top: 2px;
        border-radius: 4px;
        color: #fff;
        justify-content: center;
    }

    .seckill-btn {
        background: #FFF4F3;
        border: 1px solid #FECAC4;
        color: #FE9488;
    }

    .groupon-btn {
        background: #F1EDFA;
        border: 1px solid #CEBEEC;
        color: #9C7EDA;
    }

    .score-btn {
        background: #FDF5E8;
        border: 1px solid #F8DCAE;
        color: #F2BA5E;
    }

    .el-table .bg-color {
        background: #f9f9f9;
    }

    .cell-left .cell {
        justify-content: flex-start !important;
    }

    .el-table_1_column_11.is-leaf,
    .el-table_1_column_11 {
        border-color: #EBEEF5 !important;
    }

    .el-radio-button__inner {
        font-size: 12px;
    }

    .el-table__fixed-right::before,
    .el-table__fixed::before {
        height: 0 !important;
    }

    [v-cloak] {
        display: none
    }
</style>
<script src="/assets/addons/shopro/libs/vue.js"></script>
<script src="/assets/addons/shopro/libs/element/element.js"></script>
<script src="/assets/addons/shopro/libs/Sortable.min.js"></script>
<script src="/assets/addons/shopro/libs/vuedraggable.js"></script>
<script src="/assets/addons/shopro/libs/moment.js"></script>
<div id="goodsIndex" v-cloak style="height: calc(100vh - 63px);" @click.stop="hideup()">
    <div class="choose-container">
        <div class="custom-choose display-flex">
            <div class="choose-status">
                <span class="choose-status-tip">筛选条件</span>
                <el-switch @change="isShoose" active-value="1" inactive-value="0" v-model="chooseType"
                    active-color="#7536D0" inactive-color="#E9EBEF">
                </el-switch>
            </div>
            <div class="custom-search">
                <el-input placeholder="请输入标题" suffix-icon="el-icon-search" v-model="searchKey">
                </el-input>
            </div>
        </div>
        <el-collapse-transition>
            <div v-if="chooseType==1">
                <div class="custom-choose-type display-flex">
                    <div class="custom-choose-type-tip">
                        活动类别
                    </div>
                    <div class="common-btn choose-btn" :class="activityType=='all'?'choose-btn-active':''"
                        @click="chooseOpt('all')">
                        全部
                    </div>
                    <div class="common-btn choose-btn" :class="activityType=='groupon'?'choose-btn-active':''"
                        @click="chooseOpt('groupon')">
                        拼团
                    </div>
                    <div class="common-btn choose-btn" :class="activityType=='seckill'?'choose-btn-active':''"
                        @click="chooseOpt('seckill')">
                        秒杀
                    </div>
                    <div class="common-btn choose-btn" :class="activityType=='score'?'choose-btn-active':''"
                        @click="chooseOpt('score')">
                        积分
                    </div>
                </div>
                <div class="custom-choose-type display-flex">
                    <div class="custom-choose-type-tip">
                        价格区间
                    </div>
                    <div class="choose-price">
                        <el-input v-model="priceFrist">
                            <template slot="append">元</template>
                        </el-input>
                    </div>
                    <div class="choose-price-line">
                        -
                    </div>
                    <div class="choose-price">
                        <el-input v-model="priceLast">
                            <template slot="append">元</template>
                        </el-input>
                    </div>
                </div>
                <div class="custom-choose-sub display-flex">
                    <div class="common-btn choose-btn choose-btn-active" style="margin-right: 20px;"
                        @click="goodsOpt('filter')">
                        筛选
                    </div>
                    <div class="common-btn choose-btn" @click="goodsOpt('clear')">
                        清空
                    </div>
                </div>
            </div>
        </el-collapse-transition>
    </div>
    <div class="custom-table">
        <div class="custom-header display-flex">
            <div class="display-flex">
                <div class="custom-refresh display-flex" @click="getData">
                    <i class="el-icon-refresh"></i>
                </div>
                <div class="common-btn  create-goods" @click="goodsOpt('create')">
                    <i class="el-icon-plus"></i>
                    <span>新增商品</span>
                </div>
                <div>
                    <el-radio-group v-model="activeStatus" fill="#7536D0">
                        <el-radio-button label="all">全部</el-radio-button>
                        <el-radio-button label="up">已上架</el-radio-button>
                        <el-radio-button label="down">已下架</el-radio-button>
                        <el-radio-button label="hidden">已隐藏</el-radio-button>
                    </el-radio-group>
                </div>


            </div>
            <div class="common-btn  recycle-btn" @click="goodsOpt('recycle')">
                <i class="fa fa-recycle"></i>
                回收站
            </div>
        </div>
        <div>
            <el-table ref="multipleTable" :data="goodsData" tooltip-effect="dark" style="width: 100%" border
                @selection-change="handleSelectionChange" :row-class-name="tableRowClassName"
                :cell-class-name="tableCellClassName" :header-cell-class-name="tableCellClassName"
                @row-dblclick="goodsOpt">
                <el-table-column type="selection" min-width="36">
                </el-table-column>
                <el-table-column label="ID" prop="id" min-width="70">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>ID</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='id' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('id','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='id' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('id','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="商品" min-width="220">
                    <template slot-scope="scope">
                        <div class="goods-name">
                            <img class="goods-img" :src="Fast.api.cdnurl(scope.row.image)" alt="">
                            <div>
                                <div class="goods-title one-ellipsis">{{scope.row.title}}</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="类别" min-width="112">
                    <template slot-scope="scope">
                        <div style="width: 100%;">
                            <div class="activity-type display-flex"
                                v-if="scope.row.activity_type || scope.row.app_type">
                                <div class="activity-type-btn groupon-btn display-flex"
                                    v-if="scope.row.activity_type && scope.row.activity_type=='groupon'">
                                    {{scope.row.activity_type_text}}</div>
                                <div class="activity-type-btn seckill-btn display-flex"
                                    v-if="scope.row.activity_type && scope.row.activity_type=='seckill'">
                                    {{scope.row.activity_type_text}}</div>
                                <div class="activity-type-btn score-btn display-flex" v-if="scope.row.app_type">
                                    {{scope.row.app_type_text}}</div>
                            </div>
                            <div class="activity-type display-flex" v-else>-</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="price" label="价格" min-width="90">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>价格</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='price' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('price','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='price' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('price','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="sales" label="销量" min-width="100">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>销量</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='sales' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('sales','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='sales' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('sales','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="views" label="浏览量" min-width="90">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>浏览量</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='views' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('views','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='views' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('views','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="stock" label="库存" min-width="100">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>库存</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='stock' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('stock','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='stock' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('stock','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="更新时间" min-width="148">
                    <template slot-scope="scope">
                        <div>
                            {{moment(scope.row.updatetime*1000).format("YYYY-MM-DD HH:mm:ss")}}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="weigh" label="排序" min-width="80">
                    <template slot="header" slot-scope="scope">
                        <div class="display-flex">
                            <div>排序</div>
                            <div class="display-flex sort-order">
                                <i class="el-icon-sort-up icon-top"
                                    :style="{color:(sort=='weigh' && order=='asc')?'#7438d5':''}"
                                    @click="sortOrder('weigh','asc')"></i>
                                <i class="el-icon-sort-down icon-bottom"
                                    :style="{color:(sort=='weigh' && order=='desc')?'#7438d5':''}"
                                    @click="sortOrder('weigh','desc')"></i>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column fixed="right" label="操作" min-width="240">
                    <template slot-scope="scope">
                        <el-popover placement="right" width="120" v-model="upStatus" v-if="currentIndex==scope.$index">
                            <div class="display-flex status-box">
                                <div class="common-btn  status-btn status-btn-1" v-if="scope.row.status!='up'"
                                    @click="editStatus(scope.row.id,'up')">
                                    上架
                                </div>
                                <div class="common-btn  status-btn status-btn-2" v-if="scope.row.status!='down'"
                                    @click="editStatus(scope.row.id,'down')">
                                    下架
                                </div>
                                <div class="common-btn  status-btn status-btn-3" v-if="scope.row.status!='hidden'"
                                    @click="editStatus(scope.row.id,'hidden')">
                                    隐藏
                                </div>
                            </div>
                        </el-popover>
                        <span class="yuyue" style="margin-right: 12px;" @click="goodsOpt('yuyue',scope.row.id)"
                            v-if="scope.row.is_ticket != 0">预约统计</span>
                        <span style="cursor: pointer;margin-right: 12px;" @click.stop="popoverOpt(scope.$index)">
                            <span style="color:#7438D5" v-if="scope.row.status=='up'">{{scope.row.status_text}}</span>
                            <span style="color:#FF5959" v-if="scope.row.status=='down'">{{scope.row.status_text}}</span>
                            <span style="color:#999" v-if="scope.row.status=='hidden'">{{scope.row.status_text}}</span>
                        </span>
                        <span class="edit-text" @click="goodsOpt('edit',scope.row.id)">编辑</span>
                        <!-- <span class="day-edit-text" @click="goodsOpt('day-edit',scope.row.id)">节假日编辑
                        </span> -->
                        <span class="copy-text" @click="goodsOpt('copy',scope.row.id)">复制</span>
                        <span class="del-text" @click="goodsOpt('del',scope.row.id)">删除</span>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div class="page-container display-flex">
            <div class="display-flex">
                <div class="common-btn  all-btn-up1" @click="goodsOpt('up',null)" v-if="activeStatus!='up'">
                    上架
                </div>
                <div class="common-btn  all-btn-up" @click="goodsOpt('down',null)" v-if="activeStatus!='down'">
                    下架
                </div>
                <div class="common-btn  all-btn-del" @click="goodsOpt('del',null)">
                    删除
                </div>
            </div>
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange"
                :current-page="currentPage" :page-sizes="[10, 20, 30, 40]" :page-size="limit"
                layout="total, sizes, prev, pager, next, jumper" :total="totalPage">
            </el-pagination>
        </div>
    </div>

</div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
