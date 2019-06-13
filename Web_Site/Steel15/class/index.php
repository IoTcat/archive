<html lang="en">
<head>
	<meta id="viewport" name="viewport" content="width=660,user-scalable=no,target-densitydpi=high-dpi">
<meta charset="utf-8">
<title>同学信息</title>
	
<style type="text/css">
			body {
				margin: 0;
				position: relative;
			}
			
			.raining {
				display: block;
			}
			
			.backimg {
				position: absolute;
				left: 0;
				top: 0;
				background: url(img/20.jpg);
				height: 100%;
				width: 100%;
				opacity: 0.3;
			}
			.audio{
				position: absolute;
				left: 0;
				top: 0;
			}
		</style>
</head>

<body style="background:#1B1B1B">
<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)--> 
<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
<canvas class="raining"></canvas>
<div id="mainMap" style="height:400px;width: 700px;padding:10px;background:#1B1B1B">
</div>
<!--Step:2 Import echarts.js--> 
<!--Step:2 引入echarts.js-->

<script type="text/javascript" src="js/jquery-1.8.0.js"></script> 
<script src="js/echarts.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('#document').ready(function(){
		 getEcharts();
	 });
</script>
<script type="text/javascript">
		
		var canvas = document.querySelector(".raining");
		var w, h;
		~~ function setSize() { //定义canvas的宽高，让他跟浏览器的窗口的宽高相同
			window.onresize = arguments.callee;
			w = window.innerWidth;
			h = window.innerHeight;
			canvas.width = w;
			canvas.height = h;
		}();

		var canCon = canvas.getContext("2d"); //建立2d画板
		var arain = []; //新建数组,储存雨滴
		//
		function random(min, max) { //返回最小值到最大值之间的值
			return Math.random() * (max - min) + min;
		}

		function rain() {};
		rain.prototype = {
			init: function() { //变量初始化
				this.x = random(0, w); //在0-w之间生成雨滴
				this.vx = 0.1;
				this.y = h; //起点在下面
				this.vy = random(4, 5);
				this.h = random(0.1 * h, 0.4 * h); //地板高度
				this.r = 1; //雨滴绽放的半径
				this.vr =1;
				this.colos = Math.floor(Math.random() * 1000);
			},
			draw: function() {

				if(this.y > this.h) {
					canCon.beginPath(); //拿起一支笔
					canCon.fillStyle = '#' + this.colos; //笔墨的颜色，随机变化的颜色
					canCon.fillRect(this.x, this.y, 3, 10); //想象画一个雨滴
				} else {
					canCon.beginPath(); //拿起一支笔
					canCon.strokeStyle = '#' + this.colos; //笔墨的颜色
					canCon.arc(this.x, this.y, this.r, 0, Math.PI * 2); //想象画一个圆
					canCon.stroke(); //下笔作画
				}
			},
			move: function() { //重点是判断和初始位置。其他无大变化
				if(this.y > this.h) { //位置判断
					this.y += -this.vy; //从下往上				

				} else {
					if(this.r < 100) { //绽放的大小
						this.r += this.vr;
					} else {
						this.init(); //放完后回归变量原点
					}

				}
				this.draw(); //开始作画

			}
		}

		function createrain(num) {
			for(var i = 0; i < num; i++) {
				setTimeout(function() {
					var raing = new rain(); //创建一滴雨
					raing.init();
					raing.draw();
					arain.push(raing);
				}, 800 * i) //每隔150ms下落一滴雨
			}
		}
		createrain(10); //雨滴数量
		setInterval(function() {
			canCon.fillStyle = "rgba(0,0,0,0.1)"; //拿起一支透明0.13的笔		
			canCon.fillRect(0, 0, w, h); //添加蒙版 控制雨滴长度
			for(var item of arain) {
				//item of arain指的是数组里的每一个元素
				//item in arain指的是数组里的每一个元素的下标（包括圆形连上的可遍历元素）
				item.move(); //运行整个move事件
			}
		}, 1000 / 60); //上升时间
	</script>

<script type="text/javascript">
function getEcharts(){
    // Step:3 conifg ECharts's path, link to echarts.js from current page.
    // Step:3 为模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径
    require.config({
        paths: {
            echarts: './js'
        }
    });
    
    // Step:4 require echarts and use it in the callback.
    // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
    require(
        [
            'echarts',
            'echarts/chart/map'
        ],
        function (ec) {
            // --- 地图 ---
            var myChart2 = ec.init(document.getElementById('mainMap'));
            myChart2.setOption({
				dataRange: {
					min : 0,
					max : 10,
					calculable : true,
					color: ['#ff3333', 'orange', 'yellow','lime','aqua'],
					textStyle:{
						color:'#fff'
					}
				},
				series : [
					{
						name: '全国',
						type: 'map',
						roam: true,
						hoverable: false,
						mapType: 'china',
						itemStyle:{
							normal:{
								borderColor:'rgba(100,149,237,1)',
								borderWidth:0.5,
								areaStyle:{
									color: '#1b1b1b'
								}
							}
						},
						data:[],
						markLine : {
							smooth:true,
							symbol: ['none', 'circle'],  
							symbolSize : 1,
							itemStyle : {
								normal: {
									color:'#fff',
									borderWidth:1,
									borderColor:'rgba(30,144,255,0.5)'
								}
							},
							data : [
							],
						},
						geoCoord: {
							'上海': [121.4648,31.2891],
							'东莞': [113.8953,22.901],
							'东营': [118.7073,37.5513],
							'中山': [113.4229,22.478],
							'临汾': [111.4783,36.1615],
							'临沂': [118.3118,35.2936],
							'丹东': [124.541,40.4242],
							'丽水': [119.5642,28.1854],
							'新疆': [87.9236,43.5883],
							'佛山': [112.8955,23.1097],
							'保定': [115.0488,39.0948],
							'甘肃': [103.5901,36.3043],
							'包头': [110.3467,41.4899],
							'北京': [116.4551,40.2539],
							'北海': [109.314,21.6211],
							'江苏': [118.8062,31.9208],
							'广西': [108.479,23.1152],
							'南昌': [116.0046,28.6633],
							'南通': [121.1023,32.1625],
							'厦门': [118.1689,24.6478],
							'台州': [121.1353,28.6688],
							'安徽': [117.29,32.0581],
							'内蒙古': [111.4124,40.4901],
							'咸阳': [108.4131,34.8706],
							'黑龙江': [127.9688,45.368],
							'唐山': [118.4766,39.6826],
							'嘉兴': [120.9155,30.6354],
							'大同': [113.7854,39.8035],
							'大连': [122.2229,39.4409],
							'天津': [117.4219,39.4189],
							'山西': [112.3352,37.9413],
							'威海': [121.9482,37.1393],
							'宁波': [121.5967,29.6466],
							'宝鸡': [107.1826,34.3433],
							'宿迁': [118.5535,33.7775],
							'常州': [119.4543,31.5582],
							'广东': [113.5107,23.2196],
							'廊坊': [116.521,39.0509],
							'延安': [109.1052,36.4252],
							'张家口': [115.1477,40.8527],
							'徐州': [117.5208,34.3268],
							'德州': [116.6858,37.2107],
							'惠州': [114.6204,23.1647],
							'四川': [103.9526,30.7617],
							'扬州': [119.4653,32.8162],
							'承德': [117.5757,41.4075],
							'西藏': [91.1865,30.1465],
							'无锡': [120.3442,31.5527],
							'日照': [119.2786,35.5023],
							'昆明': [102.9199,25.4663],
							'浙江': [119.5313,29.8773],
							'枣庄': [117.323,34.8926],
							'柳州': [109.3799,24.9774],
							'株洲': [113.5327,27.0319],
							'湖北': [114.3896,30.6628],
							'汕头': [117.1692,23.3405],
							'江门': [112.6318,22.1484],
							'辽宁': [123.1238,42.1216],
							'沧州': [116.8286,38.2104],
							'河源': [114.917,23.9722],
							'泉州': [118.3228,25.1147],
							
							'泰州': [120.0586,32.5525],
							'山东': [117.1582,36.8701],
							'济宁': [116.8286,35.3375],
							'海南': [110.3893,19.8516],
							'淄博': [118.0371,36.6064],
							'淮安': [118.927,33.4039],
							'深圳': [114.5435,22.5439],
							'清远': [112.9175,24.3292],
							'温州': [120.498,27.8119],
							'渭南': [109.7864,35.0299],
							'湖州': [119.8608,30.7782],
							'湘潭': [112.5439,27.7075],
							'滨州': [117.8174,37.4963],
							'潍坊': [119.0918,36.524],
							'烟台': [120.7397,37.5128],
							'玉溪': [101.9312,23.8898],
							'珠海': [113.7305,22.1155],
							'盐城': [120.2234,33.5577],
							'盘锦': [121.9482,41.0449],
							'河北': [114.4995,38.1006],
							'福州': [119.4543,25.9222],
							'秦皇岛': [119.2126,40.0232],
							'绍兴': [120.564,29.7565],
							'聊城': [115.9167,36.4032],
							'肇庆': [112.1265,23.5822],
							'舟山': [122.2559,30.2234],
							'苏州': [120.6519,31.3989],
							'莱芜': [117.6526,36.2714],
							'菏泽': [115.6201,35.2057],
							'营口': [122.4316,40.4297],
							'葫芦岛': [120.1575,40.578],
							'衡水': [115.8838,37.7161],
							'衢州': [118.6853,28.8666],
							'西宁': [101.4038,36.8207],
							'陕西': [109.1162,34.2004],
							'贵州': [106.6992,26.7682],
							'连云港': [119.1248,34.552],
							'邢台': [114.8071,37.2821],
							'邯郸': [114.4775,36.535],
							'河南': [113.4668,34.6234],
							'鄂尔多斯': [108.9734,39.2487],
							'重庆': [107.7539,30.1904],
							'金华': [120.0037,29.1028],
							'铜川': [109.0393,35.1947],
							'宁夏': [106.3586,38.1775],
							'镇江': [119.4763,31.9702],
							'吉林': [125.8154,44.2584],
							'湖南': [113.0823,28.2568],
							'长治': [112.8625,36.4746],
							'阳泉': [113.4778,38.0951],
							'青岛': [120.4651,36.3373],
							'韶关': [113.7964,24.7028],
							'泰安': [117.126398,36.195616],
							'运城': [111,35],
							'荆州': [111.150,30]

						},
						markPoint : {
							symbol:'emptyCircle',
							symbolSize : function (v){
								return 10 + v/10
							},
							effect : {
								show: true,
								shadowBlur : 0
							},
							itemStyle:{
								normal:{
									label:{show:false}
								},
								emphasis: {
									label:{position:'top'}
								}
							},
							data : [
{name:'上海',value:2},
								{name:'黑龙江',value:2},
								{name:'辽宁',value:4},
			
								 {name:'天津',value:2},
								 {name:'山东',value:24},


							{name:'江苏',value:5},
							{name:'浙江',value:2},
							{name:'安徽',value:1},
							 {name:'广东',value:2},

								 {name:'北京',value:5},
							 {name:'山西',value:1},
							 {name:'甘肃',value:1},
								 {name:'陕西',value:1},
								 {name:'湖北',value:5},
								 {name:'重庆',value:1},
							]
						}
					},
					{
						name: '北京 Top10',
						type: 'map',
						mapType: 'china',
						data:[],
						markLine : {
							smooth:true,
							effect : {
								show: true,
								scaleSize: 1,
								period: 30,
								color: '#fff',
								shadowBlur: 10
							},
							itemStyle : {
								normal: {
									label:{show:false},
									borderWidth:1,
									lineStyle: {
										type: 'solid',
										shadowBlur: 10
									}
								}
							},
							data : [
								[{name:'泰安'}, {name:'上海',value:2}],
								[{name:'泰安'}, {name:'黑龙江',value:2}],
								[{name:'泰安'}, {name:'辽宁',value:4}],
								[{name:'泰安'}, {name:'天津',value:2}],
								[{name:'泰安'}, {name:'江苏',value:5}],
								[{name:'泰安'}, {name:'浙江',value:2}],
								[{name:'泰安'}, {name:'安徽',value:1}],
								[{name:'泰安'}, {name:'广东',value:2}],
								[{name:'泰安'}, {name:'北京',value:5}],
								[{name:'泰安'}, {name:'山西',value:1}],
								[{name:'泰安'}, {name:'甘肃',value:1}],
								[{name:'泰安'}, {name:'陕西',value:1}],
								[{name:'泰安'}, {name:'湖北',value:5}],
								[{name:'泰安'}, {name:'重庆',value:1}],
								

							]
						},
						markPoint : {
							symbol:'emptyCircle',
							symbolSize : function (v){
								return 0.1
							},
							effect : {
								show: false,
								shadowBlur : 0
							},
							itemStyle:{
								normal:{
									label:{show:true,
										  position:'top',
										  textStyle: {
													fontSize: 14
												}
										  }
								},
								emphasis: {
									label:{show:false}
								}
							},
							data : [
							{name:'上海',value:2},
								{name:'黑龙江',value:2},
								{name:'辽宁',value:4},
			
								 {name:'天津',value:2},
								 {name:'山东',value:24},


							{name:'江苏',value:5},
							{name:'浙江',value:2},
							{name:'安徽',value:1},
							 {name:'广东',value:2},

								 {name:'北京',value:5},
							 {name:'山西',value:1},
							 {name:'甘肃',value:1},
								 {name:'陕西',value:1},
								 {name:'湖北',value:5},
								 {name:'重庆',value:1},

							]
						}
					}
				]
        });
	});
}
    </script>
</body>
</html>