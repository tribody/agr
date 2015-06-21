<?php include "header.php";	?>
<?php include "dataAnalysis_gh.php"; ?>


<!-- 利用jqgrid插件构建表格 -->
		<table id="list2"></table>
		<div id="pager2"></div>
<?php
  session_start();
	$num = $_GET['num'];
  // 设备配置 告警配置 作物种类 视频管理
	if(!$sidx) $sidx =1;
  $arr = array( 
          //设备配置
          array("序号","区号","大棚编号","传感器编号","mac地址","监控类型","经度","纬度","投用时间"),
          //告警配置
          array("序号","区号","作物种类","传感器类型","数据上限","数据下限"),
          //作物种类
          array("序号","区号","大棚编号","作物种类"),
          //视频管理
          array("序号","名称","类型","经度","纬度")
          );

  if($num==1){
    $table="设备配置";
    $tableName="device_message";
    $arrTable=$arr[0];
    $width=65;
  }
  if($num==2){
    $table="告警配置";
    $tableName="alarm_setting";
    $arrTable=$arr[1];
    $width=160;
  }
  if($num==3){
    //利用封装好的函数,进行调用，便于管理和维护，实现页面一致————Calvin
    greenHouse();
    return;
  }
  if($num==4){
    $table="作物种类";
    $tableName="crops_message";
    $arrTable=$arr[2];
    $width = 160;
  }
  if($num==5){
    $table="视频管理";
    $tableName="video_message";
    $arrTable=$arr[3];
    $width=120;
  }

?>
  <script type="text/javascript">

  var lastsel;
  jQuery("#list2").jqGrid({
   	url:"dataServer_configure.php?num=<?php echo $num ?>",
     	// url:"dataServer.php?num=<?php echo $num ?>",
  	datatype: "json",
  	//`id`, `zone`, `zone_id`, `device_id`, `add_mac`, `device`, `sensor_type`, `cod_x`, `cod_y`, `data`, `time`, `cishu`
     	colNames:[ <?php 
                    $sep = "";
                    foreach($arrTable as $col){
                        echo $sep . "'" . $col . "'";             
                      $sep = ","; 
                    }
                  ?>
      ],
     	colModel:[
                  <?php
                      $sep = "";
                      $query="select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA='agriculture' and   table_name = '" . $tableName. "'";
                      //echo $query; exit(0);
                      $result1 = mysql_query($query);
                      while($row1 = mysql_fetch_array($result1)) {
                        if($row1[0]!="url"){
                          if($row1[0]=="time"){
                            $width=140;
                          }
                          //权限控制的问题
                          if($_SESSION['agrrole']=="超级管理员"||$_SESSION['agrrole']=="管理员"){
                            $flag="true";
                          }else{
                            $flag="false";
                          }
                          //为了解决特定区域的管理员是不能够修改区号的问题
                          if($_SESSION['agrrole']=="管理员"&&$row1[0]=='zone'){
                            $flag="false";    
                          }
                          if($row1[0]=='id'){ $flag = "false"; }
                          echo $sep . "{name:'" . $row1[0] . "',index:'" . $row1[0] . "', width:" . $width . ", editable:" . $flag . ",required : true,search:true,align:'center'}"; 
                          $sep = ",";
                        }
                      }
                  ?>
     	],		
     	rowNum:10,
     	height:350,
     	rowList:[10,20,30],
      viewrecords:true,
     	pager: '#pager2',
     	sortname: 'id',
      sortorder: "asc",
      regional:'cn',
      ondblClickRow: function(id){
        if(id && id!==lastsel){
          jQuery('#list2').jqGrid('restoreRow',lastsel);
          jQuery('#list2').jqGrid('editRow',id,true);
          lastsel=id;
        }
    },
      editurl: "dataServer_config.php?table=<?php echo $tableName ?>",
      // extraparam : {name:value},
      caption:"<?php echo $table ?>"
  });
  jQuery("#list2").jqGrid('navGrid','#pager2',{edit:<?php echo $flag ?>,add:<?php echo $flag ?>,del:<?php echo $flag ?>},
    {reloadAfterSubmit:true}, // edit options
    {reloadAfterSubmit:true}, // add options
    {reloadAfterSubmit:true}, // del options
    {} // search options
  );
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
