<?php 	include "header.php";	?>
<style type="text/css">
    .iw_poi_title {color:#CC5522;font-size:14px;font-weight:bold;overflow:hidden;padding-right:13px;white-space:nowrap}
    .iw_poi_content {font:12px arial,sans-serif;overflow:visible;padding-top:4px;white-space:-moz-pre-wrap;word-wrap:break-word}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
<?php
	if($_SESSION['agrzone']!=0&&$_SESSION['agrzone']!=$_GET['zone']){
		echo "<script language='javascript'>alert('非法操作！','map.php');</script>";
	}
	else{ ?>
		<div class="panel panel-primary">
	    	<div class="panel-heading">
	        	<h3 class="panel-title"><a href="map.php">拓扑管理</a>&gt&gt节点管理</h3>
	    	</div>
	    <!--百度地图容器-->
	      	<div class="container-fluid"style="width:100%;height:500px;" id="dituContent"></div>
		</div>
<?php } ?>  

<script type="text/javascript">
    //创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
        addMarker();//向地图中添加marker
    }
    <?php
   		$greenhouse_id =$_GET['greenhouse_id'];
   		$zone =$_GET['zone'];
   		$SQL = "SELECT cod_x, cod_y FROM greenhouse_message WHERE zone = $zone and greenhouse_id = $greenhouse_id";
		// echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$cod_x=0; 
		$cod_y=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    		$cod_x=$row[cod_x];
    		$cod_y=$row[cod_y];
		} 
		// device_message id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y time
		$SQL = "SELECT count(sensor_id) FROM device_message WHERE zone = $zone and greenhouse_id = $greenhouse_id";
		// echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		//将对应的$power_id的光伏发电站所有设备放入数组$deviceArr中
		$number = mysql_fetch_array($result,MYSQL_NUM);
		$length = $number[0];
		
    		
   ?>
    //创建地图函数：
    function createMap(){
    	// 121.400532,31.322212
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point(<?php echo $cod_x.",".$cod_y ?>);//定义一个中心点坐标
        map.centerAndZoom(point,17);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局
    }
    
    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    }
    
    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
	var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
	map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
    }
    
    //标注点数组
	var markerArr = [
		 <?php 
			 	$sql = "select sensor_id,cod_x,cod_y from device_message where zone = $zone and greenhouse_id = $greenhouse_id order by id";
			 	// echo $sql; exit(0);				
				$result = mysql_query($sql);
				while(list($sensor_id,$cod_x,$cod_y) = mysql_fetch_row($result)) {
					echo '{title:"'.$sensor_id.'号传感器",content:"<a href=\'dataAnalysis.php?num=1&sensor_id='.$sensor_id.'\'>查看表格</a>'."&nbsp;&nbsp;<a href='chart.php?sensor_id=".$sensor_id."'".'>查看图表</a>",point:"'.$cod_x.'|'.$cod_y.'",isOpen:0,icon:';
					$sql1 = "select * from alarm_new where sensor_id = $sensor_id";
					$result1 = mysql_query($sql1);
					if(mysql_num_rows($result1)==0) echo '{w:23,h:25,l:0,t:21,x:9,lb:12}},';
					else echo '{w:23,h:25,l:46,t:21,x:6,lb:12}},';
				}
			?>
		 ];
    //创建marker
    function addMarker(){
        for(var i=0;i<markerArr.length;i++){
            var json = markerArr[i];
            var p0 = json.point.split("|")[0];
            var p1 = json.point.split("|")[1];
            var point = new BMap.Point(p0,p1);
			var iconImg = createIcon(json.icon);
            var marker = new BMap.Marker(point,{icon:iconImg});
			var iw = createInfoWindow(i);
			var label = new BMap.Label(json.title,{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
			marker.setLabel(label);
            map.addOverlay(marker);
            label.setStyle({
                        borderColor:"#808080",
                        color:"#333",
                        cursor:"pointer"
            });
			(function(){
				var index = i;
				var _iw = createInfoWindow(i);
				var _marker = marker;
				_marker.addEventListener("click",function(){
				    this.openInfoWindow(_iw);
			    });
			    _iw.addEventListener("open",function(){
				    _marker.getLabel().hide();
			    })
			    _iw.addEventListener("close",function(){
				    _marker.getLabel().show();
			    })
				label.addEventListener("click",function(){
				    _marker.openInfoWindow(_iw);
			    })
				if(!!json.isOpen){
					label.hide();
					_marker.openInfoWindow(_iw);
				}
			})()
        }
    }
    //创建InfoWindow
    function createInfoWindow(i){
        var json = markerArr[i];
        var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
        return iw;
    }
    //创建一个Icon
    function createIcon(json){
        var icon = new BMap.Icon("http://app.baidu.com/map/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
        return icon;
    }
    initMap();//创建和初始化地图
</script>
<?php	include "footer.php";	?>
