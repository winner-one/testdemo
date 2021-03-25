<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"D:\testdemo\self-travel\public/../application/admin\view\shopro\goods\goods\edit.html";i:1606813170;s:66:"D:\testdemo\self-travel\application\admin\view\layout\default.html";i:1606813170;s:63:"D:\testdemo\self-travel\application\admin\view\common\meta.html";i:1606813170;s:65:"D:\testdemo\self-travel\application\admin\view\common\script.html";i:1606813170;}*/ ?>
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
        background: #fff;
        padding: 0 20px;
        overflow: auto;
        color: #666;

    }

    .btn-common {
        line-height: 32px;
        height: 32px;
        cursor: pointer;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    }

    .el-table,
    .el-table thead,
    .el-table th {
        color: #444;
        font-weight: 500 !important;
    }


    /* el-dialog */
    .el-dialog {
        width: 800px;
        height: 70vh;
        margin: 15vh auto;

    }

    .goods-dialog-big {
        width: 100vw;
        height: 100vh;
        margin: 0 auto;
        margin-top: 0 !important;
    }

    .goods-dialog-big .good-detail-body {
        height: calc(100vh - 200px);
        overflow: auto;
    }

    .el-dialog__header {
        padding: 16px 20px 10px;
    }

    .el-dialog__title {
        font-size: 14px;
        color: #444;
    }

    .el-dialog__headerbtn {
        font-size: 14px;
        color: #999;
    }

    .el-dialog__body {
        padding: 0;
    }

    .el-step.is-simple .el-step__title {
        font-size: 14px;
        font-weight: 600;
    }

    .el-step.is-simple .el-step__icon {
        display: none;
    }

    .el-step__title.is-finish {
        color: #7438D5;
    }

    .el-step__title.is-process {
        color: #666;
        font-weight: 500;
    }

    .el-step__title.is-wait {
        color: #999;
    }

    .el-form-item {
        margin-bottom: 20px;
    }

    .good-detail-body {
        padding: 20px 25px 10px 5px;
        height: calc(100vh - 120px);
        overflow: auto;
    }

    .good-detail-body::-webkit-scrollbar {
        width: 6px;
    }

    .good-detail-body::-webkit-scrollbar-thumb {
        width: 6px;
        background: #e6e6e6;
        height: 20px;
        border-radius: 3px;
    }

    .goods-type {
        width: 162px;
        height: 58px;
        border-radius: 4px;
        position: relative;
        margin-right: 20px;
    }

    .goods-type-img {
        border-radius: 4px;
    }

    .goods-type-selected {
        width: 16px;
        height: 16px;
        line-height: 16px;
        text-align: center;
        border-radius: 50%;
        background: #7438D5;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        display: none;
        position: absolute;
        top: -8px;
        right: -8px;
    }

    .el-input__inner,
    .el-input__icon {
        line-height: 34px;
        height: 34px;
    }

    .display-flex {
        display: flex;
        align-items: center;
    }

    .add-img {
        width: 60px;
        height: 60px;
        border: 1px dashed #E6E6E6;
        border-radius: 4px;
        justify-content: center;
    }

    label {
        margin-bottom: 0;
    }

    .msg-tip {
        margin-left: 30px;
        color: #999;
    }

    .dialog-footer {
        display: flex;
        justify-content: flex-end;
        padding: 0 30px;
    }

    .back-btn {
        width: 88px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        margin-right: 20px;
        color: #999;
        cursor: pointer;
    }

    .sub-btn {
        width: 88px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        background: #7438D5;
        font-size: 14px;
        color: #fff;
        cursor: pointer;
    }

    .goods-detail-table {
        border: 1px solid #E6E6E6;
        border-bottom: none;
        margin-bottom: 20px;
    }

    .goods-detail-item {
        border-bottom: 1px solid #E6E6E6;
    }

    .goods-detail-item>div {
        padding: 5px 10px;
    }

    .goods-detail-name {
        width: 120px;
    }

    .goods-detail-msg {
        width: 378px;
    }

    .goods-detail-del,
    .goods-detail-move {
        width: 50px;
        display: flex;
        justify-content: center;
    }

    .goods-detail-del-icon {
        color: #ff5959;
    }

    .del-image-btn {
        position: absolute;
        width: 14px;
        height: 14px;
        line-height: 14px;
        text-align: center;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
        background: #7438D5;
        color: #fff;
        top: -7px;
        right: -7px;
    }

    .goods-images {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        position: relative;
        border: 1px solid #7438D5;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .label-auto {
        width: 100%;
        height: 100%;
    }

    .el-radio__input.is-checked+.el-radio__label,
    .el-tabs__item.is-active,
    .el-tabs__item:hover,
    .el-pager li.active,
    .el-cascader-node.in-active-path,
    .el-cascader-node.is-active,
    .el-cascader-node.is-selectable.in-checked-path,
    .el-checkbox__input.is-checked+.el-checkbox__label,
    .el-select-dropdown.is-multiple .el-select-dropdown__item.selected {
        color: #7438D5;
    }

    .el-radio__input.is-checked .el-radio__inner,
    .el-tabs__active-bar,
    .el-checkbox__input.is-checked .el-checkbox__inner,
    .el-checkbox__input.is-indeterminate .el-checkbox__inner {
        background: #7438D5;
        border-color: #7438D5;
    }

    .add-sku-box {
        padding: 10px 8px;
        border: 1px solid #E6E6E6;
    }

    .sku-item {
        background: #F9F9F9;
        height: 50px;
        padding: 10px;
    }

    .sku-item-level {
        height: auto;
    }

    .sku-item-level2 {
        height: auto;
        padding: 0;
        width: 600px;
        background: #fff;
        display: flex;
        flex-wrap: wrap;
        line-height: 30px;
    }

    .sku-children {
        margin-right: 18px;
        position: relative;
        width: 120px;
        margin-bottom: 10px;
    }

    .sku-children-del {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        position: absolute;
        top: -8px;
        right: -8px;
        background: #7536D0;
        color: #fff;
        font-weight: 600;
        justify-content: center;
    }

    .sku-img {
        width: 34px;
        height: 34px;
        border-radius: 4px;
        position: relative;
    }

    .sku-img i {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        position: absolute;
        top: -7px;
        right: -7px;
        background: #7536D0;
        color: #fff;
        font-weight: 600;
        justify-content: center;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table-box {
        border: 1px solid #E6E6E6;
        margin: 20px 0;
    }

    .table-upload-img {
        width: 34px;
        height: 34px;
        color: #E6E6E6;
        border-radius: 2px;
        border: 1px solid #E6E6E6;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .all-edit-img {
        width: 14px;
        height: 14px;
        margin-left: 6px;
    }

    .sku-status {
        cursor: pointer;
    }

    .el-dialog__body .table {
        font-size: 12px;
        margin-bottom: 0;
    }

    .th-center {
        height: 34px;
        line-height: 34px;
    }

    .w-e-toolbar {
        display: flex;
        flex-wrap: wrap;
    }

    .el-cascader,
    .el-select {
        width: 100%;
    }

    .el-popover .el-input {
        margin-bottom: 10px;
    }

    .el-button--primary,
    .el-button--primary:hover {
        color: #FFF;
        background-color: #7536D0;
        border-color: #7536D0;
    }

    .el-popover .el-button--text {
        color: #7536D0;
    }

    .color-999 {
        color: #999;
    }

    .popover-container>p {
        margin-bottom: 10px;
    }

    .question-tip {
        font-size: 24px;
        color: #ccc;
        margin-left: 18px;
    }

    .el-radio {
        margin-right: 12px;
    }
    .flex-1{
        flex: 1;
    }
    .create-template{
        margin-left: 44px;
        cursor: pointer;
        color: #7536D0;
    }
    .category-inputs input,
    .category-inputs:focus input,
    .category-inputs:hover input {
        background: none;
        border: none;
        border-color: rgba(0, 0, 0, 0) !important;
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
<div id="goodsDetail" v-cloak style="height: calc(100vh - 63px);">
    <div class="preview-body">
        <el-steps :active="stepActive" simple>
            <el-step title="1、基础信息"></el-step>
            <el-step title="2、规格/价格"></el-step>
            <el-step title="3、商品详情"></el-step>
        </el-steps>
        <div class="good-detail-body">
            <el-form :model="goodsDetail" :rules="rules" ref="goodsDetail" label-width="100px" class="demo-goodsDetail">
                <div v-if="stepActive==1">

                    <el-form-item label="所属门店：" prop="store_id">
                        <div class="display-flex">
                            <div style="flex: 1;">
                                <el-select style="position: relative;" v-model="goodsDetail.store_id" filterable
                                    placeholder="" :filter-method="dataFilter">
                                    <el-option v-for="item in storeOptions" :key="item.name" :label="item.name" :value="item.id">
                                        <div class="display-flex" style="justify-content: space-around;">
                                            <span>{{ item.id }}</span>
                                            <div class="ellipsis-1" style="width: 80px;">{{ item.name }}</div>
                                            <div class="ellipsis-1" style="width: 280px;">
                                                {{item.province_name}}{{item.city_name}}{{item.area_name}}{{item.address}}
                                            </div>
                                        </div>
                                    </el-option>
                                    <div class="text-center display-flex"
                                        style="position: sticky;background: #fff;height:32px;top:0;z-index:1;justify-content: center;">
                                        <el-pagination class="pagination" :page-sizes="[6]" :current-page="currentPage"
                                            :total="totalPage" layout="total, sizes, prev, pager,next, jumper" pager-count="5"
                                            @size-change.stop="pageSizeChange" @current-change="pageCurrentChange" />
                                        </el-pagination>
                                        <div class="theme-color cursor-pointer" style="margin-left: 8px;" @click="getstoreOptions">
                                            跳转
                                        </div>
                                    </div>
                                </el-select>
                            </div>
                        </div>
                    </el-form-item>

                    <el-form-item label="商品形式：" prop="type">
                        <div class="display-flex">
                            <div v-if="goodsDetail.type=='normal'" class="goods-type" @click="changeGoodsType('normal')"
                                :style="{border:goodsDetail.type=='normal'?'1px solid #7438D5':'1px solid #E6E6E6'}">
                                <img class="label-auto goods-type-img" src="/assets/addons/shopro/img/goods/entity.png">
                                <div class="goods-type-selected"
                                    :style="{display:goodsDetail.type=='normal'?'block':''}">
                                    <img src="/assets/addons/shopro/img/goods/selected.png">
                                </div>
                            </div>
                            <div v-if="goodsDetail.type!='normal'" class="goods-type"
                                @click="changeGoodsType('virtual')"
                                :style="{border:goodsDetail.type=='virtual'?'1px solid #7438D5':'1px solid #E6E6E6'}">
                                <img class="label-auto goods-type-img"
                                    src="/assets/addons/shopro/img/goods/virtual.png">
                                <div class="goods-type-selected"
                                    :style="{display:goodsDetail.type=='virtual'?'block':''}">
                                    <img src="/assets/addons/shopro/img/goods/selected.png">
                                </div>
                            </div>
                        </div>
                    </el-form-item>

                    <el-form-item label="商品标题：" prop="title">
                        <el-input type="input" v-model="goodsDetail.title"></el-input>
                    </el-form-item>
                    <el-form-item label="副标题：" prop="subtitle">
                        <el-input type="input" v-model="goodsDetail.subtitle"></el-input>
                    </el-form-item>
                    <el-form-item label="商品状态：" prop="status">
                        <el-radio-group v-model="goodsDetail.status">
                            <el-radio label="up">上架</el-radio>
                            <el-radio label="hidden">隐藏</el-radio>
                            <el-radio label="down">下架</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="排序：" prop="weigh">
                        <div class="display-flex">
                            <el-input type="number" v-model="goodsDetail.weigh" style="width:300px;">
                            </el-input>
                            <div class="msg-tip">排序的大小默认按照从大到小排列</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="商品主图：" prop="image">
                        <div class="display-flex">
                            <div class="goods-image-box display-flex" v-if="goodsDetail.image">
                                <div class="goods-images" style="margin-right: 0;">
                                    <img class="label-auto" :src="Fast.api.cdnurl(goodsDetail.image)"
                                        style="border-radius: 4px;">
                                    <div class="del-image-btn" @click="delImg('image',null)">
                                        <img class="label-auto" src="/assets/addons/shopro/img/goods/close.png">
                                    </div>
                                </div>
                            </div>
                            <div class="add-img display-flex" @click="addImg('image',null,false)"
                                v-if="!goodsDetail.image">
                                <i class="el-icon-plus"></i>
                            </div>
                            <div class="msg-tip">作用于商城列表、分享头图；建议尺寸：750*750像素</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="轮播图：" prop="images">
                        <div class="display-flex" style="flex-wrap: wrap;">
                            <div class="goods-image-box display-flex" style="flex-wrap: wrap;"
                                v-if="timeData.images_arr">
                                <draggable class="display-flex" :list="timeData.images_arr" v-bind="$attrs"
                                    :options="{animation:500}">
                                    <div class="goods-images" v-for="(it,index) in timeData.images_arr">
                                        <img class="label-auto" :src="Fast.api.cdnurl(it)" style="border-radius: 4px;">
                                        <div class="del-image-btn" @click="delImg('images',index)">
                                            <img class="label-auto" src="/assets/addons/shopro/img/goods/close.png">
                                        </div>
                                    </div>
                                </draggable>
                            </div>
                            <div class="add-img display-flex" @click="addImg('images',null,true)"
                                v-if="timeData.images_arr.length<9" style="margin-right: 30px;">
                                <i class="el-icon-plus"></i>
                            </div>
                            <div class="msg-tip" style="margin-left: 0;">作用于商品详情顶部轮播，轮播图可以拖拽图片调整顺序</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="商品分类：" prop="category_ids">
                        <div class="display-flex">
                            <el-popover placement="bottom-start" width="600" v-model="visible">
                                <div>
                                    <el-tabs v-if="categoryOptions && categoryOptions.length>0" v-model="activeName" @tab-click="handleClickss">
                                        <el-tab-pane v-for="tab in categoryOptions" :label="tab.name" :name="tab.name">
                                            <el-cascader-panel v-model="timeData.category_ids_arr"
                                                :options="tab.children"
                                                :props="{ multiple: true, checkStrictly: true ,value:'id',label:'name',
                                                children:'children' }" clearable
                                                @change="panelChange"></el-cascader-panel>
                                        </el-tab-pane>
                                    </el-tabs>
                                    <div style="width: 100%;text-align: center;" v-if="categoryOptions && categoryOptions.length==0">没有分类,请选择去创建</div>
                                </div>
                                <div slot="reference">
                                    <div class="display-flex"
                                        style="flex-wrap: wrap;border: 1px solid #e6e6e6;border-radius: 4px;padding: 0 5px">
                                        <div style="    margin-right: 5px;
                                        bottom: 5px;
                                        height: 28px;
                                        line-height: 28px;
                                        padding: 0 5px;
                                        border: 1px solid #e6e6e6;
                                        border-radius: 4px;background: #f9f9f9;" v-for="(tag,index) in selectedcatArr"
                                            :key="tag" @close="closeTag(index)">
                                            {{tag.label}}
                                </div>
                                        <el-input class="category-inputs"  size="mini" style="background: none;border: none;width: 120px;height: 34px;"></el-input>
                                    </div>
                                </div>
                            </el-popover>
                            <div style="cursor: pointer;color: #7438D5;margin-left: 30px;" @click="createCategory">新建分类</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="虚增销量：">
                        <el-input type="number" v-model="goodsDetail.show_sales"></el-input>
                    </el-form-item>
                    <el-form-item label="浏览人数：">
                        <el-input type="number" v-model="goodsDetail.views"></el-input>
                    </el-form-item>


                    <div v-if="goodsDetail.type=='normal'">
                        <el-form-item label="配送方式：" prop="dispatch_type">
                            <div class="display-flex">
                                <el-checkbox-group v-model="timeData.dispatch_type_arr" @change="dispatchTypeChange">
                                    <el-checkbox :label="item.id" v-for="item in dispatchType"
                                        v-if="(goodsDetail.type=='normal' && item.id!='autosend')"  @change="getDispatchTemplateData(item.id)">{{item.name}}
                                    </el-checkbox>
                                </el-checkbox-group>
                                <el-popover placement="bottom" width="220" trigger="hover">
                                    <div class="popover-container">
                                        <p>1.选择上门自提配送方式，商品
                                            <br>
                                            购买之后会产生一个核销码。</p>
                                        <p>2.实体商品一个订单只可核销一次</p>
                                    </div>
                                    <i class="question-tip el-icon-question" slot="reference"></i>
                                </el-popover>
                            </div>
                        </el-form-item>
                        <el-form-item label="物流快递：" prop="express_ids"
                            v-if="goodsDetail.dispatch_type.indexOf('express')!=-1 && goodsDetail.type=='normal'">
                            <div class="display-flex">
                                <div class="flex-1">
                                    <el-select v-model="goodsDetail.express_ids" placeholder="请选择">
                                    <el-option v-for="item in dispatchOptions.express" :key="item.id" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                                </div>
                                <div class="create-template" @click="createTemplate('express')">
                                    新建模板
                                </div>
                            </div>
                        </el-form-item>
                        <el-form-item label="商家配送：" prop="store_ids"
                            v-if="goodsDetail.dispatch_type.indexOf('store')!=-1 && goodsDetail.type=='normal'">
                            <div class="display-flex">
                                <div class="flex-1">
                                <el-select v-model="goodsDetail.store_ids" placeholder="请选择">
                                    <el-option v-for="item in dispatchOptions.store" :key="item.id" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select></div>
                                <div class="create-template" @click="createTemplate('store')">
                                    新建模板
                                </div>
                            </div>
                        </el-form-item>
                        <el-form-item label="到店/自提：" prop="selfetch_ids"
                            v-if="goodsDetail.dispatch_type.indexOf('selfetch')!=-1">
                            <div class="display-flex">
                                <div class="flex-1">
                                <el-select v-model="goodsDetail.selfetch_ids" placeholder="请选择">
                                    <el-option v-for="item in dispatchOptions.selfetch" :key="item.id"
                                        :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select></div>
                                <div class="create-template" @click="createTemplate('selfetch')">
                                    新建模板
                                </div>
                            </div>
                        </el-form-item>
                    </div>

                    <el-form-item label="类型：" prop="is_ticket" v-if="goodsDetail.type=='virtual'">
                        <el-radio-group v-model="goodsDetail.is_ticket">
                            <!-- <el-radio :label="0" v-if="goodsDetail.is_ticket==0">否</el-radio>
                            <el-radio :label="1" v-if="goodsDetail.is_ticket==1">是</el-radio> -->
                            <el-radio :label="0">非预约型</el-radio>
                            <el-radio :label="1">门票预约</el-radio>
                            <el-radio :label="2">订房预约</el-radio>
                        </el-radio-group>
                        <!-- <span style="color: #999;">
                            （如果是订房、门票则属于预约类型，请选择是）
                        </span> -->
                    </el-form-item>
                    <el-form-item label="可预约天数：" prop="days" v-if="goodsDetail.type=='virtual' && goodsDetail.is_ticket!='0'">
                        <div class="display-flex">
                            <el-input type="number" v-model="goodsDetail.days" style="width:300px;">
                            </el-input>
                            <div class="msg-tip">今天至未来几天（默认为可预约为14天内）</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="周末溢价：" prop="rise_price" v-if="goodsDetail.type=='virtual' && goodsDetail.is_ticket!='0'">
                        <div class="display-flex">
                            <el-input type="" v-model="goodsDetail.rise_price" style="width:200px;">
                            </el-input>
                            <div class="msg-tip">周六、周日需涨价，则设置涨价金额（在原价格上进行累加）</div>
                        </div>
                    </el-form-item>

                    <el-form-item label="商品类型：" prop="dispatch_type" v-if="goodsDetail.type=='virtual'">
                        <el-radio-group v-model="goodsDetail.dispatch_type" @change="dispatchTypeChanger">
                            <el-radio label="selfetch"><span>核销券</span><span class="color-999"
                                    style="margin-left: 6px;">(商品需到店核销)</span>
                                <el-popover placement="bottom" width="220" trigger="hover">
                                    <div class="popover-container">
                                        <p>1.虚拟商品一个订单可核销多次</p>
                                    </div>
                                    <i class="question-tip el-icon-question" slot="reference"></i>
                                </el-popover>
                            </el-radio>
                            <el-radio label="autosend"><span>其他</span><span class="color-999"
                                    style="margin-left: 6px;">(商品可自动发货)</span></el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <div v-if="goodsDetail.type!='normal'">
                        <el-form-item label="到店/自提：" prop="selfetch_ids"
                            v-if="goodsDetail.dispatch_type.indexOf('selfetch')!=-1">
                            <div class="display-flex">
                                <div class="flex-1">
                                    <el-select v-model="goodsDetail.selfetch_ids" placeholder="请选择">
                                        <el-option v-for="item in dispatchOptions.selfetch" :key="item.id"
                                            :label="item.name" :value="item.id">
                                        </el-option>
                                    </el-select>
                                </div>
                                <div class="create-template" @click="createTemplate('selfetch')">
                                    新建模板
                                </div>
                            </div>
                        </el-form-item>
                        <el-form-item label="自动发货：" prop="autosend_ids"
                            v-if="goodsDetail.dispatch_type.indexOf('autosend')!=-1">
                            <div class="display-flex">
                                <div class="flex-1">
                                    <el-select v-model="goodsDetail.autosend_ids" placeholder="请选择">
                                        <el-option v-for="item in dispatchOptions.autosend" :key="item.id"
                                            :label="item.name" :value="item.id">
                                        </el-option>
                                    </el-select>
                                </div>
                                <div class="create-template" @click="createTemplate('autosend')">
                                    新建模板
                                </div>
                            </div>
                        </el-form-item>
                    </div>
                </div>
                <div v-if="stepActive==2">
                    <el-form-item label="商品规格：" prop="is_sku">
                        <div class="display-flex">
                            <el-radio-group v-model="goodsDetail.is_sku">
                                <el-radio :label="0" v-if="goodsDetail.is_sku==0">单规格</el-radio>
                                <el-radio :label="1" v-if="goodsDetail.is_sku==1">多规格</el-radio>
                            </el-radio-group>
                            <div class="msg-tip" style="margin-left: 20px;">如果商品参与了拼团,秒杀,积分商城等活动,编辑规格可能导致活动规格不可用</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="售卖价格：" prop="price" v-if="goodsDetail.is_sku==1">
                        <div class="display-flex">
                            <el-input type="text" v-model="goodsDetail.price" style="width:300px">
                            </el-input>
                            <div class="msg-tip">商品没有优惠的情况下售卖的价格</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="售卖价格：" prop="price" v-if="goodsDetail.is_sku==0">
                        <div class="display-flex">
                            <el-input type="number" v-model="goodsDetail.price" style="width:300px">
                            </el-input>
                            <div class="msg-tip">商品没有优惠的情况下售卖的价格</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="划线价格：" prop="original_price">
                        <div class="display-flex">
                            <el-input type="number" v-model="goodsDetail.original_price" style="width:300px">
                                <template slot="append">元</template>
                            </el-input>
                            <div class="msg-tip">划线价在商品列表及详情会以划线形式显示</div>
                        </div>
                    </el-form-item>
                    <div v-if="goodsDetail.is_sku==0">
                        <el-form-item label="商品库存：" prop="stock">
                            <div class="display-flex">
                                <el-input type="input" v-model="goodsDetail.stock" style="width:300px">
                                    <template slot="append">个</template>
                                </el-input>
                                <!-- <div class="msg-tip">商品没有优惠的情况下售卖的价格</div> -->
                            </div>
                        </el-form-item>
                        <el-form-item label="商品重量：">
                            <div class="display-flex">
                                <el-input type="input" v-model="goodsDetail.weight" style="width:300px">
                                </el-input>
                                <!-- <div class="msg-tip">划线价在商品列表及详情会以划线形式显示</div> -->
                            </div>
                        </el-form-item>
                        <el-form-item label="商品编号：">
                            <div class="display-flex">
                                <el-input type="input" v-model="goodsDetail.sn" style="width:300px">
                                </el-input>
                                <!-- <div class="msg-tip">划线价在商品列表及详情会以划线形式显示</div> -->
                            </div>
                        </el-form-item>
                    </div>
                    <div v-if="goodsDetail.is_sku==1">
                        <div class="add-sku-box">
                            <div class="" v-for="(s, k) in skuList">
                                <div class="display-flex sku-item" style="justify-content: space-between;">
                                    <div class="display-flex">
                                        <div>规格名称：</div>
                                        <div style="width: 120px;">
                                            <el-input type="input" v-model="skuList[k]['name']" maxlength="5"
                                                placeholder="请输入名称">
                                            </el-input>
                                        </div>
                                    </div>

                                    <div style="width: 20px;height: 20px;" @click="deleteMainSku(k)">
                                        <img class="label-auto" src="/assets/addons/shopro/img/goods/close.png">
                                    </div>
                                </div>
                                <div class="display-flex sku-item sku-item-level"
                                    style="background: #fff;padding: 10px 20px;">
                                    <div style="width: 60px;">规格值：</div>
                                    <div class="display-flex sku-item sku-item-level2">
                                        <div class="sku-children" v-for="(sc, c) in s.children">
                                            <el-input type="input" v-model="skuList[k]['children'][c]['name']"
                                                placeholder="请输入规格值" maxlength="22">
                                            </el-input>
                                            <div class="display-flex sku-children-del" @click="deleteChildrenSku(k,c)">
                                                <img class="label-auto" src="/assets/addons/shopro/img/goods/close.png">
                                            </div>
                                        </div>
                                        <span style="color: #7536D0;cursor: pointer;"
                                            @click="addChildrenSku(k)">添加</span>
                                    </div>

                                </div>
                            </div>
                            <div class="display-flex sku-item">
                                <div class="btn-common add-level1-sku" @click="addMainSku">
                                    <i class="el-icon-plus"></i>
                                    <span>添加规格</span>
                                </div>
                            </div>
                        </div>
                        <div class="table-box" v-show="skuPrice.length && skuList.length">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <template v-for="(item, i) in skuList" :key="i">
                                            <th v-if="item.children.length">
                                                <span class="th-center">{{item.name}}</span>
                                            </th>
                                        </template>
                                        <th width="60">图片</th>
                                        <th width="90">
                                            <div class="display-flex">
                                                <span>价格(元)</span>
                                                <el-popover placement="top" width="160" v-model="allEditPopover.price">
                                                    <el-input v-model="allEditDatas" placeholder="请输入内容"></el-input>
                                                    <div style="text-align: right; margin: 0">
                                                        <el-button size="mini" type="text"
                                                            @click="allEditData('price','cancel')">取消</el-button>
                                                        <el-button type="primary" size="mini"
                                                            @click="allEditData('price','define')">确定</el-button>
                                                    </div>
                                                    <div slot="reference">
                                                        <img class="all-edit-img"
                                                            src="/assets/addons/shopro/img/goods/batch.png"></div>
                                                </el-popover>
                                            </div>
                                        </th>
                                        <th width="90">
                                            <div class="display-flex">
                                                <span>库存(个)</span>
                                                <el-popover placement="top" width="160" v-model="allEditPopover.stock">
                                                    <el-input v-model="allEditDatas" placeholder="请输入内容"></el-input>
                                                    <div style="text-align: right; margin: 0">
                                                        <el-button size="mini" type="text"
                                                            @click="allEditData('stock','cancel')">取消</el-button>
                                                        <el-button type="primary" size="mini"
                                                            @click="allEditData('stock','define')">确定</el-button>
                                                    </div>
                                                    <div slot="reference">
                                                        <img class="all-edit-img"
                                                            src="/assets/addons/shopro/img/goods/batch.png"></div>
                                                </el-popover>
                                            </div>
                                        </th>
                                        <th width="90">
                                            <div class="display-flex">
                                                <span>重量</span>
                                                <el-popover placement="top" width="160" v-model="allEditPopover.weight">
                                                    <el-input v-model="allEditDatas" placeholder="请输入内容"></el-input>
                                                    <div style="text-align: right; margin: 0">
                                                        <el-button size="mini" type="text"
                                                            @click="allEditData('weight','cancel')">取消</el-button>
                                                        <el-button type="primary" size="mini"
                                                            @click="allEditData('weight','define')">确定</el-button>
                                                    </div>
                                                    <div slot="reference">
                                                        <img class="all-edit-img"
                                                            src="/assets/addons/shopro/img/goods/batch.png"></div>
                                                </el-popover>
                                            </div>
                                        </th>
                                        <th width="90">
                                            <div class="display-flex">
                                                <span>编码</span>
                                                <el-popover placement="top" width="160" v-model="allEditPopover.sn">
                                                    <el-input v-model="allEditDatas" placeholder="请输入内容"></el-input>
                                                    <div style="text-align: right; margin: 0">
                                                        <el-button size="mini" type="text"
                                                            @click="allEditData('sn','cancel')">取消</el-button>
                                                        <el-button type="primary" size="mini"
                                                            @click="allEditData('sn','define')">确定</el-button>
                                                    </div>
                                                    <div slot="reference">
                                                        <img class="all-edit-img"
                                                            src="/assets/addons/shopro/img/goods/batch.png"></div>
                                                </el-popover>
                                            </div>
                                        </th>
                                        <th width="70">当前状态</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in skuPrice" :key="i">
                                        <td v-for="(v, j) in item.goods_sku_text" :key="j">
                                            <span class="th-center">{{v}}</span>
                                        </td>
                                        <td width="60">
                                            <div class="display-flex table-upload-img">
                                                <div class="sku-img" v-if="item.image">
                                                    <img :src="Fast.api.cdnurl(item.image)" class="label-auto">
                                                    <i class="el-icon-close" @click="delImg('sku',i)"></i>
                                                </div>
                                                <div v-else @click="addImg('sku',i,false)">
                                                    <i class="el-icon-plus" style="font-size:18px;"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="90">
                                            <el-input type="input" v-model="item.price"></el-input>
                                        </td>
                                        <td width="90">
                                            <el-input type="input" v-model="item.stock"></el-input>
                                        </td>
                                        <td width="90">
                                            <el-input type="input" v-model="item.weight"></el-input>
                                        </td>
                                        <td width="90">
                                            <el-input type="input" v-model="item.sn"></el-input>
                                        </td>
                                        <td width="70"><span class="sku-status th-center"
                                                @click="editStatus(i)">{{item.status=='up'?'上架':'下架'}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-if="stepActive==3">
                    <el-form-item label="服务标签：">
                        <div class="display-flex">
                            <div class="flex-1">
                                <el-select v-model="timeData.service_ids_arr" placeholder="请选择" multiple
                                    @change="serviceChange" @focus="getServiceOptions">
                                    <el-option v-for="item in serviceOptions" :key="item.value" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </div>
                            <div class="create-template" @click="createTemplate('service')">
                                新建标签
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="参数详情：">
                        <div>
                            <div class="goods-detail-table">
                                <div class="display-flex goods-detail-item">
                                    <div class="goods-detail-name">
                                        参数名称
                                    </div>
                                    <div class="goods-detail-msg">
                                        内容
                                    </div>
                                    <div class="goods-detail-del">
                                        删除
                                    </div>
                                    <div class="goods-detail-move">
                                        移动
                                    </div>
                                </div>
                                <draggable :list="goodsDetail.params_arr" v-bind="$attrs" :options="{animation:500}">
                                    <div class="display-flex goods-detail-item"
                                        v-for="(it,index) in goodsDetail.params_arr">
                                        <div class="goods-detail-name">
                                            <el-input type="input" v-model="it.title" style="width:90px">
                                            </el-input>
                                        </div>
                                        <div class="goods-detail-msg">
                                            <el-input type="input" v-model="it.content" style="width:348px">
                                            </el-input>
                                        </div>
                                        <div class="goods-detail-del">
                                            <div class="goods-detail-del-icon" @click="delParams(index)">
                                                删除
                                            </div>
                                        </div>
                                        <div class="goods-detail-move">
                                            <img src="/assets/addons/shopro/img/goods/move.png">
                                        </div>
                                    </div>
                                </draggable>
                            </div>
                            <div class="btn-common add-params" @click="addParams">
                                <i class="el-icon-plus"></i>
                                <span>添加参数</span>
                            </div>
                        </div>
                    </el-form-item>

                </div>
                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action=""
                    v-show="stepActive==3">
                    <div class="display-flex" style="margin: 0;align-items: flex-start;">
                        <label class="control-label" style="width: 100px;
                        padding-right: 20px;
                        font-weight: 600;
                        font-size: 14px;
                        color: #606266;"><?php echo __('Content'); ?>:</label>
                        <div style="flex: 1;">
                            <textarea id="c-content" class="form-control editor" rows="5" name="row[content]"
                                cols="50"></textarea>
                        </div>
                    </div>
                </form>
            </el-form>
        </div>
    </div>
    <span slot="footer" class="dialog-footer">
        <div class="back-btn" v-if="stepActive>1" @click="gonextback">上一步</div>
        <div class="btn-common sub-btn" v-if="stepActive<3" @click="gotoback('goodsDetail')">下一步</div>
        <div class="btn-common sub-btn" v-if="stepActive==3" @click="submitForm('goodsDetail')">确定</div>
    </span>
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
