<?php
/**
 * 菜单配置文件
 */

return [
	    [
	        "type" => "file",
	        "name" => "shopro",
	        "title" => "Shopro商城",
	        "icon" => "fa fa-home",
	        "condition" => "",
	        "remark" => "",
	        "ismenu" => 1,
	        "weigh" => 0,
	        "sublist" => [
	            [
	                "type" => "file",
	                "name" => "shopro/config",
	                "title" => "商城配置",
	                "icon" => "fa fa-sliders",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 90,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/config/platform",
	                        "title" => "平台配置",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/decorate",
	                "title" => "店铺装修",
	                "icon" => "fa fa-paint-brush",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 80,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/lists",
	                        "title" => "模版管理",
	                        "icon" => "fa fa-cubes",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/dodecorate",
	                        "title" => "装修",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/preview",
	                        "title" => "预览页面",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/designer",
	                        "title" => "设计师模板",
	                        "icon" => "fa fa-cube",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/decorate/use_designer_template",
	                                "title" => "使用",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/custom",
	                        "title" => "自定义页面",
	                        "icon" => "fa fa-clone",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/publish",
	                        "title" => "发布",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/dodecorate_save",
	                        "title" => "装修保存",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/copy",
	                        "title" => "复制模板",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/decorate/down",
	                        "title" => "模板下架",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/coupons",
	                "title" => "优惠券",
	                "icon" => "fa fa-money",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/coupons/select",
	                        "title" => "选择优惠券",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/dispatch",
	                "title" => "配送设置",
	                "icon" => "fa fa-gears",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 30,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dispatch/dispatch",
	                        "title" => "发货模板",
	                        "icon" => "fa fa-ambulance",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/dispatch/select",
	                                "title" => "选择模板",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dispatch/selfetch",
	                        "title" => "到店/自提",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/selfetch/destroy",
	                                "title" => "销毁",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dispatch/express",
	                        "title" => "物流快递",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/express/destroy",
	                                "title" => "销毁",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dispatch/store",
	                        "title" => "商家配送",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/store/destroy",
	                                "title" => "销毁",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dispatch/autosend",
	                        "title" => "自动发货",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/destroy",
	                                "title" => "销毁",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/dispatch/autosend/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/express",
	                        "title" => "快递公司",
	                        "icon" => "fa fa-automobile",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/express/select",
	                                "title" => "选择",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/product",
	                "title" => "商品管理",
	                "icon" => "fa fa-archive",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 60,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/category",
	                        "title" => "商品分类",
	                        "icon" => "fa fa-sitemap",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/select",
	                                "title" => "选择商品组",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/category/update",
	                                "title" => "保存更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods/goods",
	                        "title" => "商品管理",
	                        "icon" => "fa fa-shopping-bag",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/select",
	                                "title" => "选择商品",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/detail",
	                                "title" => "查看详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/goods/setstatus",
	                                "title" => "商品状态",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods/service",
	                        "title" => "服务标签",
	                        "icon" => "fa fa-tags",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/service/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods/comment",
	                        "title" => "商品评价",
	                        "icon" => "fa fa-pencil-square",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/goods/comment/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/material",
	                "title" => "资料管理",
	                "icon" => "fa fa-file-text",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/richtext",
	                        "title" => "富文本",
	                        "icon" => "fa fa-language",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/richtext/select",
	                                "title" => "选择富文本",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/link",
	                        "title" => "页面链接",
	                        "icon" => "fa fa-unlink",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/link/select",
	                                "title" => "选择链接",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/area",
	                        "title" => "省市区数据",
	                        "icon" => "fa fa-percent",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/area/select",
	                                "title" => "选择省市区",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/faq",
	                        "title" => "常见问题",
	                        "icon" => "fa fa-info-circle",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/faq/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_fake",
	                        "title" => "虚拟用户",
	                        "icon" => "fa fa-user-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user_fake/random_user",
	                                "title" => "添加虚拟用户",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/activity",
	                "title" => "活动中心",
	                "icon" => "fa fa-font-awesome",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 50,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/activity/activity",
	                        "title" => "营销活动",
	                        "icon" => "fa fa-star",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/createorupdatesku",
	                                "title" => "编辑活动规格",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/select",
	                                "title" => "选择活动",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/sku",
	                                "title" => "活动规格",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/detail",
	                                "title" => "活动详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity/all",
	                                "title" => "活动记录",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/activity/activity_sku_price",
	                        "title" => "商品规格",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/activity_sku_price/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/activity/groupon",
	                        "title" => "拼团列表",
	                        "icon" => "fa fa-modx",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/detail",
	                                "title" => "详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/activity/groupon/addfictitious",
	                                "title" => "确定拼团虚拟人",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/app",
	                "title" => "应用",
	                "icon" => "fa fa-suitcase",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 40,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/app/score_shop",
	                        "title" => "积分商城",
	                        "icon" => "fa fa-database",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/select",
	                                "title" => "选择积分产品",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/createorupdatesku",
	                                "title" => "更编辑积分规格",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/sku",
	                                "title" => "积分规格",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/score_shop/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/app/live",
	                        "title" => "小程序直播",
	                        "icon" => "fa fa-video-camera",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/select",
	                                "title" => "直播间选择",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/detail",
	                                "title" => "直播详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/synclive",
	                                "title" => "同步直播间",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/app/live/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/user_wallet_apply",
	                "title" => "用户提现",
	                "icon" => "fa fa-jpy",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user_wallet_apply/applyoper",
	                        "title" => "提现操作",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/order",
	                "title" => "订单中心",
	                "icon" => "fa fa-file",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 70,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/order/order",
	                        "title" => "订单管理",
	                        "icon" => "fa fa-file-text",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/detail",
	                                "title" => "详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/send",
	                                "title" => "发货",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/aftersalefinish",
	                                "title" => "售后",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/refund",
	                                "title" => "退款",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/editmemo",
	                                "title" => "备注",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/gettype",
	                                "title" => "订单筛选",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/refundrefuse",
	                                "title" => "拒绝退款",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/export",
	                                "title" => "订单导出",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/actions",
	                                "title" => "操作记录",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order/editconsignee",
	                                "title" => "修改收货信息",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/order/order_item",
	                        "title" => "订单商品明细",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/order_item/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/order/aftersale",
	                        "title" => "售后/退款",
	                        "icon" => "fa fa-handshake-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/recyclebin",
	                                "title" => "回收站",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/destroy",
	                                "title" => "真实删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/restore",
	                                "title" => "还原",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/multi",
	                                "title" => "批量更新",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/detail",
	                                "title" => "详情",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/order/aftersale/addlog",
	                                "title" => "售后反馈",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/feedback",
	                "title" => "意见反馈",
	                "icon" => "fa fa-question-circle-o",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/feedback/detail",
	                        "title" => "详情",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/goods/sku",
	                "title" => "商品规格",
	                "icon" => "fa fa-circle-o",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 0,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/goods_sku_price",
	                "title" => "商品规格",
	                "icon" => "fa fa-circle-o",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 0,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/goods_sku_price/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/dashboard",
	                "title" => "数据中心",
	                "icon" => "fa fa-dashboard",
	                "condition" => "",
	                "remark" => "用于展示当前系统中的统计数据、统计报表及重要实时数据",
	                "ismenu" => 1,
	                "weigh" => 100,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dashboard/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dashboard/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dashboard/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dashboard/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/dashboard/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/wechat",
	                "title" => "微信管理",
	                "icon" => "fa fa-th-large",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 20,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/fans/index",
	                        "title" => "粉丝管理",
	                        "icon" => "fa fa-users",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/fans/user",
	                                "title" => "查看用户",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/fans/syncfans",
	                                "title" => "同步粉丝",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/menu/index",
	                        "title" => "菜单管理",
	                        "icon" => "fa fa-server",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/menu/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/menu/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/menu/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/menu/publish",
	                                "title" => "发布",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/menu/copy",
	                                "title" => "复制",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/auto_reply/index",
	                        "title" => "自动回复",
	                        "icon" => "fa fa-send",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/auto_reply/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/auto_reply/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/auto_reply/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/wechat/material/index",
	                        "title" => "素材管理",
	                        "icon" => "fa fa-folder-open",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/material/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/material/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/material/detail",
	                                "title" => "音频播放",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/material/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/wechat/material/select",
	                                "title" => "选择",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/notification",
	                "title" => "消息通知",
	                "icon" => "fa fa-bullhorn",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 10,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/index",
	                        "title" => "查看列表",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/config",
	                        "title" => "消息配置",
	                        "icon" => "fa fa-cogs",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/set_template",
	                        "title" => "配置模板",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/notification/set_status",
	                        "title" => "切换状态",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/select",
	                "title" => "选择",
	                "icon" => "fa fa-th",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 0,
	                "weigh" => 0
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/upload",
	                "title" => "上传",
	                "icon" => "fa fa-upload",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 0,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/index",
	                        "title" => "查看",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/destroy",
	                        "title" => "真实删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/restore",
	                        "title" => "还原",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/upload/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/store/store",
	                "title" => "门店管理",
	                "icon" => "fa fa-bank",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/index",
	                        "title" => "门店列表",
	                        "icon" => "fa fa-building",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/edit",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/setstatus",
	                        "title" => "是否禁用",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/store/store/select",
	                        "title" => "选择",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "shopro/user/user",
	                "title" => "用户管理",
	                "icon" => "fa fa-user",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "weigh" => 0,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/index",
	                        "title" => "用户列表",
	                        "icon" => "fa fa-list-ul",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/select",
	                        "title" => "选择",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/profile",
	                        "title" => "编辑",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/order_log",
	                        "title" => "订单记录",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/login_log",
	                        "title" => "登录记录",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/money_log",
	                        "title" => "余额明细",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/score_log",
	                        "title" => "积分明细",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/share_log",
	                        "title" => "分享记录",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/goods_favorite",
	                        "title" => "收藏商品",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/coupon_log",
	                        "title" => "优惠券",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/goods_view",
	                        "title" => "浏览足迹",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/recyclebin",
	                        "title" => "回收站",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/update",
	                        "title" => "更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/money_recharge",
	                        "title" => "余额充值",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/user/score_recharge",
	                        "title" => "积分充值",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0,
	                        "weigh" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "shopro/user/group",
	                        "title" => "用户分组",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 1,
	                        "weigh" => 0,
	                        "sublist" => [
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user/group/index",
	                                "title" => "查看",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user/group/add",
	                                "title" => "添加",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user/group/edit",
	                                "title" => "编辑",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ],
	                            [
	                                "type" => "file",
	                                "name" => "shopro/user/group/del",
	                                "title" => "删除",
	                                "icon" => "fa fa-circle-o",
	                                "condition" => "",
	                                "remark" => "",
	                                "ismenu" => 0,
	                                "weigh" => 0
	                            ]
	                        ]
	                    ]
	                ]
	            ]
	        ]
	    ]
	];