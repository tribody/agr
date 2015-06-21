<?php 	include "header.php";	?>
		<table id="list2"></table>
		<div id="pager2"></div>
<?php
	$num = $_GET['num'];
  if(!isset($num)){
    $num=1;
  }
  $sensor_id = $_GET['sensor_id'];
	if(!$sidx) $sidx =1;
	if($num==1){
		$table="最新数据";
	}
	if($num==2){
		$table="历史数据";
	}
  if($sensor_id){
    $table =$sensor_id."号传感器数据";
  }
  $width=75;
  $tableName="history_message";
  $arr = array( 
          //性能数据
          array("区号","大棚编号","设备编号","mac地址","设备类型","经度","纬度","数据显示","时间"),
          //设备配置
          array("设备编号","大棚区号","区内编号","mac地址","作物种类","监控类型","经度","纬度","修改时间"),
          //告警配置
          array("编号","监控类型","数据上限","数据下限"),
          //作物种类
          array("编号","作物种类")  
          );
  echo "<input id='num'  type='hidden' value='" . $num . "'/>";
?>
  <script type="text/javascript">
  //	jQuery().ready(function (){
  jQuery("#list2").jqGrid({
     	url:"dataServer.php?num=<?php echo $num ?>&sensor_id=<?php echo $sensor_id ?>",
     	// url:"dataServer.php?num=<?php echo $num ?>",
  	datatype: "json",
    loadui:"disabled",
  	//`id`, `zone`, `zone_id`, `device_id`, `add_mac`, `device`, `sensor_type`, `cod_x`, `cod_y`, `data`, `time`, `cishu`
     	colNames:[ <?php 
                    $sep = "";
                    foreach($arr[0] as $col){
                        echo $sep . "'" . $col . "'";             
                      $sep = ","; 
                    }
                  ?>
      ],
     	colModel:[
                 <?php
                    $sep = "";
                    $query="select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA='agriculture' and   table_name = '" . $tableName. "'";
                    $result1 = mysql_query($query);
                    $i=0;
                    while($row1 = mysql_fetch_array($result1)) {
                       if($row1[0]!="id"&&$row1[0]!="alarmed"){
                          if($row1[0]=="time"){
                            $width=140;
                          }
                          echo $sep . "{name:'" . $row1[0] . "',index:'" . $row1[0] . "', width:" . $width . ", editable:true,required : true,search:true,align:'center'}"; 
                          $sep = ",";
                        }
                    }
                ?>
     	],		
     	rowNum:10,
     	height:350,
     	rowList:[10,20,30],
     	pager: '#pager2',
     	sortname: 'time',
      sortorder: "desc",
      loadtext:"",
      regional:'cn',
      caption:"<?php echo $table ?>"
  });
  jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false});
  //下面的函数是设置jqgrid ajax模式
  var refreshgrid = setInterval(function() {
      $('#list2').trigger("reloadGrid");
  }, 5000); //5000为5秒定时刷新
  // the following code will make jqgrid to be responsive  by Calvin
    $(window).on("resize", function () {
      var $grid = $("#list2"),
          newWidth = $grid.closest(".ui-jqgrid").parent().width();
      $grid.jqGrid("setGridWidth", newWidth, true);
    });
    $(window).on("load",function(){
      var $grid = $("#list2"),
          newWidth = $grid.closest(".ui-jqgrid").parent().width();
      $grid.jqGrid("setGridWidth", newWidth, true);
    });
  </script>
<?php	include "footer.php";	?>
