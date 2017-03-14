/**
 * Created by ForAcer on 2016/12/14.
 */
function IndexChart()
{

}

IndexChart.prototype =
{
	init:function()
	{
		var _this = this;
		_this.loadWebViewTime();
		_this.loadUserGenderChart();
		_this.loadUserAgeChart();
		_this.loadUserEduChart();
		_this.loadUserAreaChart();
	},

	loadWebViewTime:function()
	{
		var chart = echarts.init(document.getElementById("viewWebTimeChart"));

		option = {
			grid: {
				x : '60px',
				y : '15px',
				x2 : '15px',
				y2 : '60px',
				containLabel: false
			},
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : ['周一','周二','周三','周四','周五','周六','周日']
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:'邮件营销',
					type:'line',
					stack: '总量',
					areaStyle: {normal: {}},
					data:[120, 132, 101, 134, 90, 230, 210]
				},
				{
					name:'联盟广告',
					type:'line',
					stack: '总量',
					areaStyle: {normal: {}},
					data:[220, 182, 191, 234, 290, 330, 310]
				},
				{
					name:'视频广告',
					type:'line',
					stack: '总量',
					areaStyle: {normal: {}},
					data:[150, 232, 201, 154, 190, 330, 410]
				},
				{
					name:'直接访问',
					type:'line',
					stack: '总量',
					areaStyle: {normal: {}},
					data:[320, 332, 301, 334, 390, 330, 320]
				},
				{
					name:'搜索引擎',
					type:'line',
					stack: '总量',
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					},
					areaStyle: {normal: {}},
					data:[820, 932, 901, 934, 1290, 1330, 1320]
				}
			]
		};

		chart.setOption(option);
	},

	loadUserGenderChart:function()
	{
		var chart = echarts.init(document.getElementById("userGender"));

		option = {
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			legend: {
				orient: 'vertical',
				left: 'left',
				data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
			},
			series : [
				{
					name: '访问来源',
					type: 'pie',
					radius : '55%',
					center: ['50%', '60%'],
					data:[
						{value:335, name:'直接访问'},
						{value:310, name:'邮件营销'},
						{value:234, name:'联盟广告'},
						{value:135, name:'视频广告'},
						{value:1548, name:'搜索引擎'}
					],
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: 'rgba(0, 0, 0, 0.5)'
						}
					}
				}
			]
		};

		chart.setOption(option);
	},

	loadUserGenderChart:function()
	{
		var chart = echarts.init(document.getElementById("userGender"));

		option = {
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			legend: {
				orient: 'vertical',
				left: 'left',
				data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
			},
			series : [
				{
					name: '访问来源',
					type: 'pie',
					radius : '55%',
					center: ['50%', '60%'],
					data:[
						{value:335, name:'直接访问'},
						{value:310, name:'邮件营销'},
						{value:234, name:'联盟广告'},
						{value:135, name:'视频广告'},
						{value:1548, name:'搜索引擎'}
					],
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: 'rgba(0, 0, 0, 0.5)'
						}
					}
				}
			]
		};

		chart.setOption(option);
	},

	loadUserAgeChart:function()
	{
		var chart = echarts.init(document.getElementById("userAge"));

		option = {
			tooltip : {
				trigger: 'axis',
				axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
				}
			},
			grid: {
				top:'3%',
				left: '10%',
				right: '4%',
				bottom: '8%',
				containLabel: false
			},
			xAxis : [
				{
					type : 'category',
					data : ['周一','周二','周三','周四','周五','周六','周日']
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:'直接访问',
					type:'bar',
					data:[320, 332, 301, 334, 390, 330, 320]
				},
				{
					name:'邮件营销',
					type:'bar',
					stack: '广告',
					data:[120, 132, 101, 134, 90, 230, 210]
				},
				{
					name:'联盟广告',
					type:'bar',
					stack: '广告',
					data:[220, 182, 191, 234, 290, 330, 310]
				},
				{
					name:'视频广告',
					type:'bar',
					stack: '广告',
					data:[150, 232, 201, 154, 190, 330, 410]
				},
				{
					name:'搜索引擎',
					type:'bar',
					data:[862, 1018, 964, 1026, 1679, 1600, 1570],
					markLine : {
						lineStyle: {
							normal: {
								type: 'dashed'
							}
						},
						data : [
							[{type : 'min'}, {type : 'max'}]
						]
					}
				},
				{
					name:'百度',
					type:'bar',
					barWidth : 5,
					stack: '搜索引擎',
					data:[620, 732, 701, 734, 1090, 1130, 1120]
				},
				{
					name:'谷歌',
					type:'bar',
					stack: '搜索引擎',
					data:[120, 132, 101, 134, 290, 230, 220]
				},
				{
					name:'必应',
					type:'bar',
					stack: '搜索引擎',
					data:[60, 72, 71, 74, 190, 130, 110]
				},
				{
					name:'其他',
					type:'bar',
					stack: '搜索引擎',
					data:[62, 82, 91, 84, 109, 110, 120]
				}
			]
		};

		chart.setOption(option);
	},


	loadUserEduChart:function()
	{
		var chart = echarts.init(document.getElementById("userEdu"));

		option = {
			tooltip : {
				trigger: 'axis',
				axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
				}
			},

			grid: {
				top: '3%',
				left: '8%',
				right: '4%',
				bottom: '8%',
				containLabel: false
			},
			xAxis : [
				{
					type : 'value'
				}
			],
			yAxis : [
				{
					type : 'category',
					axisTick : {show: false},
					data : ['周一','周二','周三','周四','周五','周六','周日']
				}
			],
			series : [
				{
					name:'收入',
					type:'bar',
					stack: '总量',
					label: {
						normal: {
							show: true
						}
					},
					data:[320, 302, 341, 374, 390, 450, 420]
				},
				{
					name:'收入',
					type:'bar',
					stack: '总量',
					label: {
						normal: {
							show: true,
						}
					},
					data:[120, 132, 101, 134, 190, 230, 210]
				}
			]
		};

		chart.setOption(option);
	},

	loadUserAreaChart:function()
	{
		var chart = echarts.init(document.getElementById("userArea"));

		option = {
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			legend: {
				orient: 'vertical',
				left: 'left',
				data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
			},
			series : [
				{
					name: '访问来源',
					type: 'pie',
					radius : '55%',
					center: ['50%', '60%'],
					data:[
						{value:335, name:'直接访问'},
						{value:310, name:'邮件营销'},
						{value:234, name:'联盟广告'},
						{value:135, name:'视频广告'},
						{value:1548, name:'搜索引擎'}
					],
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: 'rgba(0, 0, 0, 0.5)'
						}
					}
				}
			]
		};

		chart.setOption(option);
	},

}