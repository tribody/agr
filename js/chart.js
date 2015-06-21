
function chart_start(clock) {
	$('.device').click(function(){
	$(this).change(function(){
		//获取选择的值
		var field = $(this).find('option:selected').val();
		//获取选择的名称
		var fieldName1= $(this).attr("name");
		var fieldName2= $(this).next().attr("name");
		var id = $(this).next().attr("id");
		$.ajax({url: 'chart_server.php?field='+field+'&fieldName1='
						+fieldName1+'&fieldName2='+fieldName2,
 			success: function(output) {
					var message = $('#'+id).find("option").eq(0).html();
					// alert(message);
					message = "<option>" + message + "</option>";
					$('#'+id).empty();
					output = message + output;
				$('#'+id).html(output);
			}
		});
	});
	});
	$('.timeChoose').click(function() {
		// alert("hee");
		if($('.timeChoose').find('input:radio:checked').val()==2) {
			$('.dateChoose').show();
		} else{
			$('.dateChoose').hide();
		}
	});
	
	//当点击开始button的时候，查询数据库
	$('#submit').click(function(){
		var sensor_id = $('#sensor_id').find('option:selected').text();
		var timeChoose = $('.timeChoose').find('input:radio:checked').val();
		var date = $('#date').val();
		// alert(date);
		// clock = setInterval(function(){displayChart(sensor_id);},5000);
		// displayChart(sensor_id);
		//写两个绘图函数
		// Step:4 require echarts and use it in the callback.
		// Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
		
        initChart(DrawEChart);

        //创建ECharts图表方法
        function DrawEChart(ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('main')); 
            //图表显示提示信息
            myChart.showLoading({
                text: "图表数据正在努力加载..."
            });
            var option = initOption();
            //第一次通过Ajax获取数据
            onceGet(myChart,sensor_id,option,timeChoose,date);
            
			if(timeChoose==1) {
				//周期查询，动态显示
				var timeTicket = setInterval(function(){loopGet(myChart,sensor_id,option,timeChoose);}, 2000);
			}
        }
	});
}

function initChart(DrawEChart) {
    // 路径配置
        require.config({
            paths: {
                echarts: 'js/'
            }
        });
        
        // 使用
        require(
            [
                'echarts',
                'echarts/chart/bar',// 使用柱状图就加载bar模块，按需加载
                'echarts/chart/line'
            ],
            DrawEChart
        );
}
//初始化option
function initOption() {
    var option = {
                tooltip: {
                    show: true,
                    trigger : 'axis'
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                legend: {
                    data:[]
                },
                xAxis : [
                    {
                        name : '时间',
                        type : 'category',
                        axisLabel : {interval : 'auto'},
                        axisTick : {interval : 0},
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name : [],
                        type : 'line',
                        data : [],
                        smooth : true      
                    }         
                ]
            };
    return option;
}

//一次查询显示
function onceGet(myChart,sensor_id,option,timeChoose,date) {
    $.ajax({
            // type: "post",
            // async: false, //同步执行
            url: 'chart_server.php?flag=1&sensor_id=' + sensor_id + '&timeChoose=' + timeChoose +'&date=' + date,
            // dataType: "json", //返回数据形式为json
            success: function (result) {
                if (result) {                        
                    //将返回的category和series对象赋值给options对象内的category和series
                    //因为xAxis是一个数组 这里需要是xAxis[i]的形式
                    //第一次获取数据
                    // alert(result);
                    $('.interval').val(result);
                    // alert($('.interval').val());
                    var data = getData(result);
                    if(timeChoose==2) {
                        option.toolbox.feature.dataZoom = { show : true};
                        option.dataZoom = {
                                                show : true,
                                                realtime : true,
                                                start : 40,
                                                end : 60
                                            };
                        option.xAxis[0].axisTick = { interval : 'auto'};
                    }
                    option.legend.data = data.legend;
                    option.series[0].data = data.series;
                    option.series[0].name = data.legend;
                    // option.series[1].data = data.series;
                    option.xAxis[0].data = data.xAxis;
                    myChart.hideLoading();
                    myChart.setOption(option);
                }
            },
            error: function (errorMsg) {
                alert("不好意思，大爷，图表请求数据失败啦!");
            }
        });
}

//2015-08-02 04:43:09动态数据查询显示
function loopGet(myChart,sensor_id,option,timeChoose) {
	$.ajax({
            // type: "post",
            // async: false, //同步执行
            url: 'chart_server.php?flag=1&sensor_id=' + sensor_id + '&timeChoose=' + timeChoose,
            // dataType: "json", //返回数据形式为json
            success: function (result) {
            	var last_result = $('.interval').val();
            	$('.interval').val(result);
            	// alert(last_result!=result);
                if (result!=last_result) {                        
                    //将返回的category和series对象赋值给options对象内的category和series
                    //因为xAxis是一个数组 这里需要是xAxis[i]的形式
                    //第一次获取数据
                    var last_data = getData(last_result);
     				var new_data = getData(result);
     				// alert("istrue?");
     				// var update_data = new Array();
                    for (var i=0; i<new_data.xAxis.length; i++) {
                    	// alert(strToDate(new_data.xAxis[i]).getTime());
                    	// alert(strToDate(last_data.xAxis[last_data.xAxis.length-1]).getTime());
                    	if (strToDate(new_data.xAxis[i]).getTime()>strToDate(last_data.xAxis[last_data.xAxis.length-1]).getTime()) {
                    		// 动态数据接口 addData
                    		
						    myChart.addData([
						        [
						            0,        // 系列索引
						            new_data.series[i],
						            false,     // 新增数据是否从队列头部插入
						            false,    // 是否增加队列长度，false则自定删除原有数据，队头插入删队尾，队尾插入删队头
						            new_data.xAxis[i]
						        ]
						    ]);
                    	}
                    }
                    // myChart.hideLoading();
                    // alert("something?");
                    // myChart.setOption(option);
                }
            }
        });
}

//包装数据
function getData(result) {
	var data = result.split("&");
	var sensor_type = [data[0]];
	var xdata = data[1].split(",").reverse();
	// alert(xdata);
	var ydata = data[2].split(",").reverse();
	var data = {"legend" : sensor_type , "xAxis" : xdata , "series" : ydata};
	return data;
}

//将字符串转为时间对象
function strToDate(str) {
	var tempStrs = str.split(" ");
	var dateStrs = tempStrs[0].split("-");
	var year = parseInt(dateStrs[0], 10);
	var month = parseInt(dateStrs[1], 10);
	var day = parseInt(dateStrs[2], 10);
	var timeStrs = tempStrs[1].split(":");
	var hour = parseInt(timeStrs [0], 10);
	var minute = parseInt(timeStrs[1], 10);
	var second = parseInt(timeStrs[2], 10);
	var date = new Date(year, month, day, hour, minute, second);
	return date;
}