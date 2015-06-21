<?php 	
  include "header.php";	
  include "dataAnalysis_newalarm.php";
?>
<!-- 利用jqgrid插件构建表格 -->
		<table id="list2"></table>
		<div id="pager2"></div>
<?php
	$num = $_GET['num'];
	if(!$sidx) $sidx =1;
//数据分析
  	if($num==1){
  		alarm_new();
      exit(0);
  	}
  	if($num==2){
  		$table="历史告警";
      $tableName="Alarm_old";
  	}
    $width=65;
  $arr = array("序号","区号","大棚编号","作物种类","设备编号","MAC地址",
    "监控类型","数据值","报警时间");
  echo "<input id='num'  type='hidden' value='" . $num . "'/>";
?>
  <script type="text/javascript">
  jQuery("#list2").jqGrid({
   	url:"dataServer_alarm.php?num=<?php echo $num ?>",
  	datatype: "json",
    loadui:"disabled",
     	colNames:[ <?php 
                    $sep = "";
                    foreach($arr as $col){
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
                      while($row1 = mysql_fetch_array($result1)) {
                        
                          if($row1[0]=="time"){
                            $width=140;
                          }
                          echo $sep . "{name:'" . $row1[0] . "',index:'" . $row1[0] . "', width:" . $width . ", editable:true,required : true,search:true,align:'center'}"; 
                          $sep = ",";
                      
                      }
                  ?>
     	],		
     	rowNum:10,
     	height:350,
     	rowList:[10,20,30],
     	pager: '#pager2',
     	sortname: 'time',
      sortorder: "desc",
      regional:'cn',
      editurl: "dataServer_config.php?table=<?php echo $tableName ?>",
      caption:"<?php echo $table ?>"
  });
  jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:true},
        {reloadAfterSubmit:true}, // edit options
        {reloadAfterSubmit:true}, // add options
        // {reloadAfterSubmit:true}, // del options
        //下方函数的目的是删除的时候send additional post data
        {mtype:"POST", reloadAfterSubmit:true, serializeDelData: function (postdata) {
          var rowdata = jQuery('#list2').getRowData(postdata.id);
        // append postdata with any information 
          return {
            id: postdata.id, oper: postdata.oper, greenhouse_id: rowdata.greenhouse_id, sensor_id: rowdata.sensor_id,time:rowdata.time
          };
        }}, // del options
        {} // search options
   );
  //下面的函数是设置jqgrid ajax模式
  var refreshgrid = setInterval(function() {
      $('#list2').trigger("reloadGrid");
  }, 10000);
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
