<?php 
	include "header.php"; 
	session_start();
?>
<script src="js/echarts.js"></script>
<script src="js/data.js"></script>
<script src="js/chart.js"></script>
<script>

	var _sensor_id = <?php echo '"'.$_GET['sensor_id'].'"'; ?>;
	if(_sensor_id) {
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
            onceGet(myChart,_sensor_id,option,1);
			//周期查询，动态显示
			var timeTicket = setInterval(function(){loopGet(myChart,_sensor_id,option,1);}, 2000);
        }
	}
	var sessionRole= <?php echo '"'.$_SESSION['agrrole'].'";'; ?>
	var sessionZone=<?php echo '"'.$_SESSION['agrzone'].'";'; ?>
	
	//利用jQuery与Ajax实现菜单的自动刷新和过滤
	var clock; //用来控制定时器
	$(function() {
		chart_start(clock);
		if(sessionRole!="超级管理员") {
		//-------------目的是将第一个selection直接选中特定区域，无需点击---------------------
			loadZone(); 
		}
	});
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
	<h3 class="panel-title">图表显示</h3>
	</div>
	<?php 
		$deviceArr = array();
		$zone = $_SESSION['agrzone'];
		if($_SESSION['agrrole']=="超级管理员"){
			$SQL = "SELECT area,zone FROM greenhouse_message  ORDER BY id";
		}else{
			$SQL = "SELECT area,zone FROM greenhouse_message  WHERE zone=$zone ORDER BY id";
		}
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$deviceArr["{$row['zone']}"]=$row['area'];
		}
		function device($deviceArr){
			foreach ($deviceArr as $key=>$value) { 
				echo "<option value=".$key.">".$value."</option>";
			}	
		}
	 ?>
	<div class="panel-body">
		<form class="form-inline">
			<div class="form-group" style="display:block;margin:10px">
				<label for="zone" class="device_id">设备选择：</label>
				<select name="zone" id="zone" class="btn btn-default device">
					<option value="option" style="display:none;">请选择区域</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" id="greenhouse_id" class="btn btn-default device">
					<option value="option" style="display:none;">请选择大棚编号</option>
				</select>
				<select name="sensor_type" id="sensor_type" class="btn btn-default device">
					<option value="option" style="display:none;">请选择传感器种类</option>
				</select>
				<select name="sensor_id" id="sensor_id" class="btn btn-default device">
					<option value="option" style="display:none;">请选择传感器编号</option>
				</select>
			</div>
			<div class="form-group timeChoose" style="display:block;margin:10px">
				<label for="checkbox-inline">
					<input type="radio" style="width:50px" name="timeChoose" value="1" checked />实时显示
					<input type="radio" style="width:50px" name="timeChoose" value="2" />历史查看
				</label>
			</div>
			<div class="form-group dateChoose" style="display:none;margin:10px">
				<label for="begin_date" class="date">选择日期：</label>
				<input type="date" class="form-control date" id="date" name="date" />
			</div>
			<hr>
		</form>
		<input type="submit" class="btn btn-default pull-right" style="margin:10px" name="chooseSubmit" id="submit" value="检索" />
		<input type="hidden" class="interval" />
		<div id="main" style="height:400px;margin-bottom:40px"></div>
	</div>
</div>


<?php	include "footer.php";	?>