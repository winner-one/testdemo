requirejs.config({
	paths: {
		// vue: "/assets/addons/shopro/libs/vue",
		g2: "/assets/addons/shopro/libs/antv"
	}
})
define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'g2'], function ($, undefined, Backend, Table, Form, G2) {

	var Controller = {
		index: function () {
			var vueChart = new Vue({
				el: "#antv-con",
				data() {
					return {
						orderList: [],
						chartsData: [],
						dataList: [{
							title: '支付订单',
							num: 0,
							checked: true,
							id: 1,
							color: '#F0A70A',
							back: '#FEF6DE',
							unit: '笔'
						}, {
							title: '支付金额',
							num: 0,
							checked: true,
							id: 5,
							color: '#4498FF',
							back: '#E4F0FF',
							unit: '元'
						}, {
							title: '待发货订单',
							num: 0,
							checked: false,
							id: 2,
							color: '#38C769',
							back: '#E2F7E9',
							unit: '笔'
						}, {
							title: '下单人数',
							num: 0,
							checked: false,
							id: 6,
							color: '#B052EA',
							back: '#F6E9FE',
							unit: '人'
						},
						{
							title: '售后维权',
							num: 0,
							checked: false,
							id: 3,
							color: '#627EFC',
							back: '#EBEEFE',
							unit: '笔'
						},
						{
							title: '退款订单',
							num: 0,
							checked: false,
							id: 4,
							color: '#FF826C',
							back: '#FFEDEB',
							unit: '笔'
						}],
						selectInputs: [],
						dropdownList: [{
							date: 'yesterday',
							name: '昨日'
						}, {
							date: 'today',
							name: '今日'
						}, {
							date: 'week',
							name: '近一周'
						}, {
							date: 'month',
							name: '近一月'
						}, {
							date: 'year',
							name: '近一年'
						}],
						dropdownName: '今日',
						dropdownDate: 'today',
						searchTime: [new Date(), new Date()],
						ringRightData: [
							{ item: '微信支付', count: 0, percent: 0 },
							{ item: '支付宝支付', count: 0, percent: 0 },
							{ item: '钱包支付', count: 0, percent: 0 },
							{ item: '其他', count: 0, percent: 0 }
						],
						tableData: [],
						value2: [new Date(), new Date()],
						//环图总数
						allOrderNum: 0,
						//成交比例
						tranPeople: 0,
						tranPeoplescale: 0,
						//支付比例					
						allOrderPayNum: 0,
						transcale: 0,
						orderFinish: {},
						payedFinish: {},
						loading: true



					}
				},
				mounted() {
					//请求数据
					this.changeTime();
				},
				methods: {
					//折线
					charts() {
						$("#main-chart").empty()
						const chart = new G2.Chart({
							container: 'main-chart',
							autoFit: true,
							height: 360,
							animate: true,
						});
						chart.data(this.chartsData);
						var dataBoxNum = 0
						this.selectInputs.forEach(e => {
							if (e.checked) {
								dataBoxNum++
							}
						})
						if (dataBoxNum == 1) {
							let title
							this.selectInputs.forEach(i => {
								if (i.checked) {
									title = i.title
								}
							})
							chart.scale({
								date: {
									alias: ' ',
								},
								y2: {
									alias: title,
									min: 0,
									sync: true,
									nice: true,
								},
							});
							chart
								.area()
								.position('date*y2')
								.color(this.selectInputs[0].color).tooltip(false).shape('smooth');
							chart
								.line()
								.position('date*y2')
								.color(this.selectInputs[0].color).shape('smooth');
						} else if (dataBoxNum == 2) {
							chart.scale({
								date: {
									alias: ' ',
								},
								y2: {
									alias: this.selectInputs[1].title,
									sync: true,
									nice: true,
									min: 0
								},
								y1: {
									alias: this.selectInputs[0].title,
									sync: true,
									nice: true,
									min: 0
								},
							});

							chart
								.area()
								.position('date*y1')
								.color(this.selectInputs[0].color).tooltip(false).shape('smooth');
							chart
								.line()
								.position('date*y1')
								.color(this.selectInputs[0].color).shape('smooth');
							chart
								.area()
								.position('date*y2')
								.color(this.selectInputs[1].color).tooltip(false).shape('smooth');
							chart
								.line()
								.position('date*y2')
								.color(this.selectInputs[1].color).shape('smooth');

						} else {
							return false
						}
						var margin = 1 / this.chartsData.length;
						chart.axis('date', {
							range: [margin / 4, 1 - margin / 4]

						});
						chart.axis('y1', {
							grid: null,
							title: {},
						});
						chart.axis('y2', {
							title: {},
						});

						chart.tooltip({
							showCrosshairs: true, //展示辅助线
							shared: true,
						});
						chart.render();
					},
					//选择显示数据
					selectLine(idx) {
						this.dataList[idx].checked = !this.dataList[idx].checked
						if (this.dataList[idx].checked == true) {
							this.selectInputs.push(this.dataList[idx]);
							if (this.selectInputs.length > 2) {
								this.selectInputs[0].checked = false;
								this.selectInputs.shift();
							}
						} else {
							this.selectInputs.forEach((item, index) => {
								if (this.dataList[idx].id == item.id)
									this.selectInputs.splice(index, 1);
							})
						}
						this.countOrderData()
						// this.charts()
					},
					//环图
					ringRight() {
						var that=this;
						$("#ring-right").empty()
						const chart = new G2.Chart({
							container: 'ring-right',
							autoFit: true,
							height: 300,
							width: 260,
							padding: [0, 0, 50, 0]
						});
						chart.data(this.ringRightData);
						chart.scale('percent', {
							formatter: (val) => {
								val = val * 100 + '%';
								return val;
							},
						});
						chart.coordinate('theta', {
							radius: 0.85,
							innerRadius: 0.8,
						});
						chart.tooltip({
							showTitle: false,
							showMarkers: false,
							itemTpl: '<li class="g2-tooltip-list-item"><span style="background-color:{color};" class="g2-tooltip-marker"></span>{name}: {value}({count})</li>',
						});
						// 辅助文本
						chart
							.annotation()
							.text({
								position: ['50%', '50%'],
								content: this.allOrderNum,
								style: {
									fontSize: 36,
									fill: '#753ECD',
									textAlign: 'center',
								},
								offsetX: -10,
								offsetY: -20,
							})
							.text({
								position: ['50%', '50%'],
								content: '笔',
								style: {
									fontSize: 16,
									fill: '#753ECD',
									textAlign: 'center',
								},
								offsetY: -20,
								offsetX: 30,
							})
							.text({
								position: ['50%', '50%'],
								content: '支付单数',
								style: {
									fontSize: 16,
									fill: '#753ECD',
									textAlign: 'center',
								},
								offsetY: 20,
							});
						chart
							.interval()
							.adjust('stack')
							.position('percent')
							.color('item', ['#38C769', '#627EFC', '#FF826C', '#F7B500'])
							.tooltip('item*percent', (item, percent) => {
								let counts=Math.round(that.allOrderNum*percent)
								percent = percent * 100 + '%';
								return {
									name: item,
									value: percent,
									count:counts
								};
							});

						chart.render();
					},
					//表格
					tableRowClassName({ row, rowIndex }) {
						if (rowIndex % 2 == 0) {
							return 'gray-row';
						}
						return '';
					},
					//选择时间段
					changeTime(index = 1) {
						this.dropdownDate = this.dropdownList[index].date;
						this.dropdownName = this.dropdownList[index].name;
						this.searchTime = this.getTimeSlot();
						this.getDataInfo();
					},
					//选择请求数据
					getDataInfo() {
						this.loading = true
						let that = this
						let timeSlot = moment(that.searchTime[0]).format("YYYY-MM-DD HH:mm:ss") + ' - ' + moment(that.searchTime[1]).format("YYYY-MM-DD HH:mm:ss");
						Fast.api.ajax({
							url: 'shopro/dashboard/index',
							loading: false,
							data: {
								datetimerange: timeSlot
							},
							success: function (res, ret) {
								that.tableData = res.data.goodsList;
								that.orderList = res.data.orderList;
								that.orderFinish = res.data.orderFinish
								that.payedFinish = res.data.payedFinish
								// 支付订单
								let payOrderNum = 0
								let payOrderArr = []
								//支付金额	
								let payAmountNum = 0
								let payAmountArr = []
								// 代发货
								let noSentNum = 0
								let noSentArr = []
								//下单人数
								let orderNum = that.orderList.length
								let orderArr = []
								//售后维权
								let aftersaleNum = 0
								let aftersaleArr = []
								//退款订单
								let refundNum = 0
								let refundArr = []
								//所有下单金额
								let totalAmount = 0
								//
								let tranPeople = 0

								//微信支付
								let wechatPay = 0
								//支付宝支付
								let alipayPay = 0
								//余额支付
								let walletPay = 0
								//所有的支付
								let allTypePay = 0
								that.orderList.forEach(e => {
									orderArr.push({ counter: 1, paytime: e.paytime * 1000, user_id: e.user_id })
									totalAmount += Number(e.pay_fee)
									if (e.status > 0) {
										payOrderNum++
										payOrderArr.push({ counter: 1, paytime: e.paytime * 1000 })

										payAmountNum += Number(e.pay_fee)
										payAmountArr.push({ counter: e.pay_fee, paytime: e.paytime * 1000 })

										tranPeople++

										e.item.forEach(i => {
											if (i.dispatch_status == 0) {
												noSentNum++
												noSentArr.push({ counter: 1, paytime: e.paytime * 1000 })
											}
											if (i.aftersale_status > 0) {
												aftersaleNum++
												aftersaleArr.push({ counter: 1, paytime: e.paytime * 1000 })
											}
											if (i.refund_status > 0) {
												refundNum++
												refundArr.push({ counter: 1, paytime: e.paytime * 1000 })
											}
										})
										allTypePay++
										if (e.pay_type == 'wechat') {
											wechatPay++
										}
										if (e.pay_type == 'alipay') {
											alipayPay++
										}
										if (e.pay_type == 'wallet') {
											walletPay++
										}
									}
								})

								that.dataList[0].num = payOrderNum
								that.dataList[0].item = payOrderArr
								that.dataList[1].num = payAmountNum.toFixed(2)
								that.dataList[1].item = payAmountArr
								that.dataList[2].num = noSentNum
								that.dataList[2].item = noSentArr
								const resorderArr = new Map();
								const neworderArr = orderArr.reverse().filter(item => !resorderArr.has(item.user_id) && resorderArr.set(item.user_id, 1));
								that.dataList[3].num = neworderArr.length
								that.dataList[3].item = neworderArr
								that.dataList[4].num = aftersaleNum
								that.dataList[4].item = aftersaleArr
								that.dataList[5].num = refundNum
								that.dataList[5].item = refundArr

								that.allOrderNum = allTypePay
								that.ringRightData[0].count = wechatPay
								that.ringRightData[0].percent = that.scaleFunc(wechatPay, allTypePay)
								that.ringRightData[1].count = alipayPay
								that.ringRightData[1].percent = that.scaleFunc(alipayPay, allTypePay)
								that.ringRightData[2].count = walletPay
								that.ringRightData[2].percent = that.scaleFunc(walletPay, allTypePay)
								that.ringRightData[3].count = allTypePay - wechatPay - alipayPay - walletPay
								that.ringRightData[3].percent = that.scaleFunc(that.ringRightData[3].count, allTypePay)
								that.ringRight()
								//成交比例
								that.tranPeople = tranPeople
								that.tranPeoplescale = that.scaleFunc(tranPeople, that.orderList.length) * 100
								//支付比例
								that.allOrderPayNum = payAmountNum
								that.transcale = that.scaleFunc(payAmountNum, totalAmount) * 100
								//判断是否选中
								that.selectInputs = []
								that.dataList.forEach((item, index) => {
									if (item.checked == true) that.selectInputs.push(item);
								})
								// 请求数据
								that.countOrderData();
							}
						})
					},

					countOrderData() {
						let that = this;
						let orderList = JSON.parse(JSON.stringify(that.orderList));
						let time = (
							new Date(moment(that.searchTime[1]).format("YYYY-MM-DD HH:mm:ss").replace(/-/g, "/")).getTime() - new Date(moment(that.searchTime[0]).format("YYYY-MM-DD HH:mm:ss").replace(/-/g, "/")).getTime()
						) / 1000 + 1;
						let kld = '';
						let interval = 0;
						if (time <= 60 * 60) {
							interval = parseInt(time / 60);

							kld = 'minutes';
						} else if (time <= 60 * 60 * 24) {
							interval = parseInt(time / (60 * 60));

							kld = 'hours';
						} else if (time <= 60 * 60 * 24 * 30 * 1.5) {
							interval = parseInt(time / (60 * 60 * 24));

							kld = 'days';

						} else if (time < 60 * 60 * 24 * 30 * 24) {
							interval = parseInt(time / (60 * 60 * 24 * 30));

							kld = 'months';

						} else if (time >= 60 * 60 * 24 * 30 * 24) {
							interval = parseInt(time / (60 * 60 * 24 * 30 * 12));

							kld = 'years';

						}
						this.drawX(interval, kld);

					},
					drawX(interval, kld) {
						let that = this
						let x = [];
						let selectInputLeng = 0
						this.selectInputs.forEach(e => {
							if (e.checked) {
								selectInputLeng++
							}
						})
						if (selectInputLeng == 1) {
							for (let i = 0; i <= interval; i++) {
								if (kld == 'minutes' || kld == 'hours') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("DD HH:mm"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0
									});
								} else if (kld == 'days') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY-MM-DD"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								} else if (kld == 'months') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY-MM"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								} else {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0
									});
								}
							}
							let flag3 = false
							for (var y = 0; y < x.length; y++) {
								let y2 = 0
								let selectItem = []
								let id = 0
								this.selectInputs.forEach(element => {
									if (element.checked == true) {
										selectItem = element.item
										id = element.id
									}
								})
								if (id == 6) {
									selectItem.forEach(se => {
										if (flag3) {
											if (se.paytime > x[0].timeStamp) {
												y2 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												flag3 = true
												y2 += Number(se.counter)
											}
										}
									})
								} else {
									selectItem.forEach(se => {
										if (y != x.length - 1) {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												y2 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp) {
												y2 += Number(se.counter)
											}
										}
										
									})
									if(id==5){
										if(y2>0){
											y2 =parseFloat(y2.toFixed(2))
										}
									}
								}

								x[y].y2 = y2
							}
						}
						if (selectInputLeng == 2) {
							for (let i = 0; i <= interval; i++) {
								if (kld == 'minutes' || kld == 'hours') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("DD HH:mm"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								} else if (kld == 'days') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY-MM-DD"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								} else if (kld == 'months') {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY-MM"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								} else {
									x.push({
										date: moment(that.searchTime[0]).add(i, kld).format("YYYY"),
										timeStamp: moment(that.searchTime[0]).add(i, kld).valueOf(),
										y2: 0,
										y1: 0
									});
								}
							}
							let flag1 = false
							let flag2 = false
							for (var y = 0; y < x.length; y++) {
								let y1 = 0
								let y2 = 0
								if (this.selectInputs[0].id == 6) {
									this.selectInputs[0].item.forEach(se => {
										if (flag1) {
											if (se.paytime > x[0].timeStamp) {
												y1 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												flag1 = true
												y1 += Number(se.counter)
											}
										}
									})

								} else {
									this.selectInputs[0].item.forEach(se => {
										if (y != x.length - 1) {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												y1 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp) {
												y1 += Number(se.counter)
											}
										}
									})
									if(this.selectInputs[0].id == 5){
										if(y1>0){
											y1 =parseFloat(y1.toFixed(2))
										}
									}
								}
								if (this.selectInputs[1].id == 6) {
									this.selectInputs[1].item.forEach(se => {
										if (flag2) {
											if (se.paytime > x[0].timeStamp) {
												y2 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												flag2 = true
												y2 += Number(se.counter)
											}
										}
									})
								} else {
									this.selectInputs[1].item.forEach(se => {
										if (y != x.length - 1) {
											if (se.paytime > x[y].timeStamp && se.paytime <= x[y + 1].timeStamp) {
												y2 += Number(se.counter)
											}
										} else {
											if (se.paytime > x[y].timeStamp) {
												y2 += Number(se.counter)
											}
										}
									})
									if(this.selectInputs[1].id == 5){
										if(y2>0){
											y2 =parseFloat(y2.toFixed(2))
										}
										
									}
								}
								x[y].y1 = y1
								x[y].y2 = y2
							}
						}
						that.chartsData = x
						that.loading = false
						that.charts()
					},
					//获取时间
					getTimeSlot() {

						let beginTime = '';
						let endTime = moment().format('YYYY-MM-DD');
						switch (this.dropdownDate) {
							case 'yesterday':
								endTime = moment().subtract(1, 'days').format('YYYY-MM-DD')
								beginTime = endTime
								break;
							case 'today':
								beginTime = endTime;
								break;
							case 'week':
								beginTime = moment().subtract(1, 'weeks').format('YYYY-MM-DD')
								break;
							case 'month':
								beginTime = moment().subtract(1, 'months').format('YYYY-MM-DD')
								break;
							case 'year':
								beginTime = moment().subtract(1, 'years').format('YYYY-MM-DD')
								break;
						}
						let timeSlot = [beginTime + ' 00:00:00', endTime + ' 23:59:59'];
						return timeSlot;
					},
					goDetail(id) {
						let that = this;
						let status = ''
						switch (id) {
							case 1:
								status = 'payed'
								break;
							case 2:
								status = 'nosend'
								break;
							case 3:
								status = 'aftersale'
								break;
							case 4:
								status = 'refund'
								break;
						}
						let times = encodeURI(that.searchTime.join(" - "))
						parent.Fast.api.open("shopro/order/order/index?status=" + status + "&datetimerange=" + times, "查看详情", { callback: function (data) { } });
						return false;
					},
					scaleFunc(a, b) {
						return (a <= 0 ? 0 : a / b).toFixed(2) - 0
					}
				},
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
				url: 'shopro/decorate/recyclebin' + location.search,
				pk: 'id',
				sortName: 'id',
				columns: [
					[
						{ checkbox: true },
						{ field: 'id', title: __('Id') },
						{ field: 'name', title: __('Name'), align: 'left' },
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
									url: 'shopro/decorate/restore',
									refresh: true
								},
								{
									name: 'Destroy',
									text: __('Destroy'),
									classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
									icon: 'fa fa-times',
									url: 'shopro/decorate/destroy',
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