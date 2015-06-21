<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>数据模拟发送器</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="jqgrid/js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
				$(function(){
					var clock; //用来控制定时器
					var timeNum;//设置定时器时间
					$('#timeSubmit').click(function(){
						timeNum = $('#timeNum').val();
						if(timeNum){
							$('#confirm').html("<p>配置成功</p>").show(1000);
							$('#confirm').hide(2000);
						}else{
							alert("请输入发送时间间隔");
						}
					});
					//利用jQuery与Ajax实现菜单的自动刷新和过滤
					$('.device').click(function(){
						$(this).change(function(){
							//获取选择的值
							var field = $(this).find('option:selected').text();
							//获取选择的名称
							var fieldName1= $(this).attr("name");
							var fieldName2= $(this).next().attr("name");
							//var id = $(this).next().attr("id");
							//thisSel接收$(this)的引用
							var thisSel = $(this).next();  
							if(fieldName2!="max_value"){
								$.ajax({url: 'dataPush_server.php?field='+field+'&fieldName1='
												+fieldName1+'&fieldName2='+fieldName2,
			             			success: function(output) {
		             					var message = thisSel.children('option').eq(0).text();
		             					message = "<option>"+message+"</option>";
		             					//alert(message);
		             					thisSel.empty();
		             					output = message + output;
										thisSel.html(output);
									}
								});
							}

						});
					});
					

					//当点击开始button的时候，利用ajax开始向php发送请求
					$('.submit').click(function(){
						// alert(timeNum);
						var fieldValueArr = new Array("zone","greenhouse_id","sensor_id","sensor_type");
						var fieldName ="";
						for(var i=0;i<fieldValueArr.length;i++){
							fieldName += $(this).prevAll('.'+fieldValueArr[i]).first().find('option:selected').text()+"&";
						}
						//alert(fieldName);  打桩，fieldName输出为2&1&11&二氧化碳&
						var max = $(this).prevAll('.max').val();
						// alert(max);
						var min = $(this).prevAll('.min').val();  //获取input输入的值
						fieldName += max+"&"+min;    
						//alert(fieldName);  //打桩，fieldName输出为2&1&11&二氧化碳&100&2
						var strs= new Array();
						strs =fieldName.split("&");
						var flag=1;
						//for循环是为了检测是否完全输入了所有的数据
						for (i=0;i<strs.length ;i++ )    
					    {    
					        if(strs[i].indexOf("请选择")>=0||strs[i]==""){
					        	alert("请填写完数据！");
					        	flag = 0; 
					        	return;
					        }   
					    } 
					    fieldName = 'sensor_id='+strs[2]+'&max='+max+'&min='+min;
					    // alert(fieldName);
					   // alert(flag);
					    // if(flag){
					    	//注意setInterval 要采用下面的方式传递函数参数
					    var sendMsg = $(this).parent().next();
						clock = setInterval(function(){sendData(fieldName,sendMsg);},1000*timeNum);
						//将clock的值存入hidden隐藏域
						$(this).nextAll('.clock').val(clock);
							
					});
					//停止定时器
					$('.stop').click(function(){
						clock = $(this).nextAll('.clock').val();
						clearInterval(clock);
						$(this).parent().next().html('<div class="alert alert-warning" role="alert">停止插入数据</div>').show(1000);
						$(this).parent().next().hide(1000);
					});
					//重置整个页面
					$('.reset').click(function(){
						// $('#sendMsg').html("");
						clock = $(this).nextAll('.clock').val();
						clearInterval(clock);
						// history.go(0);  //重新刷新页面
					});


				});
				function sendData(fieldName,sendMsg){
					$.ajax({url: 'dataPush_server.php?flag=1&'+fieldName,
             			success: function(output) {
             				sendMsg.html('<div class="alert alert-success" role="alert">正在插入数据...</div>').show(1000);
						}
					});
				}
		</script>
	</head>
	<body>
		<?php 
			$deviceArr = array();
			$SQL = "SELECT distinct zone FROM device_message  ORDER BY id";
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
			$index=0;
			while($row = mysql_fetch_array($result,MYSQL_NUM)) {
				$deviceArr[$index]=$row[0];
				$index++;
			}
			function device($deviceArr){
				for($i=0;$i<count($deviceArr);$i++){
					echo "<option value=".$deviceArr[$i].">".$deviceArr[$i]."</option>";
				}	
			}
	 	?>
		<div style="margin:30px;">
			<h1 class="text-primary">数据模拟发送器</h1>
			<div class="form-inline">
				<input type="text" class="form-control" id="timeNum" placeholder="请输入发送间隔(s)">
				<button type="button" class="btn btn-default" id="timeSubmit">确认</button>
				<div class="alert alert-info" role="alert" id="confirm" style="display:none;"></div>
			</div>
			
			<form id="sel1" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
			<form id="sel2" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
			<form id="sel3" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
			<form id="sel4" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
			<form id="sel5" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
			<form id="sel6" class="form-inline" style="margin-top:10px;margin-bottom:10px">
				<select name="zone" class="btn btn-default device zone" >
					<option value="option">请选择区号</option>
					<?php 
						device($deviceArr);
					 ?>
				</select>
				<select name="greenhouse_id" class="btn btn-default device greenhouse_id" >
					<option value="option">请选择大棚编号</option>
				</select>
				<select name="sensor_id" class="btn btn-default device sensor_id" >
					<option value="option">请选择传感器编号</option>
				</select>
				<select name="sensor_type" class="btn btn-default device sensor_type" >
					<option value="option">请选择传感器种类</option>
				</select>
				<input type="text" class="min form-control" name="min_value" placeholder="请输入最小值">
				<input type="text" class="max form-control" name="max_value" placeholder="请输入最大值">
				<button type="button" class="submit btn btn-primary">开始</button>
				<button type="button" class="stop btn btn-danger">停止</button>
				<button type="reset" class="reset btn btn-warning">重置</button>
				<input type="hidden" class="clock">
			</form>
			<div class="sendMsg"></div>
		</div>
	</body>
</html>