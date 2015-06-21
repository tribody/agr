<?php 	
  function alarm_new(){
  include "db.inc.php";
 ?>
<!-- 利用jqgrid插件构建表格 -->
	<table id="list2"></table>
	<div id="pager2"></div>
<?php
	 if(!$sidx) $sidx =1;
  	$num=1;
		$table="最新告警";
    $tableName="Alarm_new";
    $width=65;
  $arr = array("序号","区号","大棚编号","作物种类","传感器编号","MAC地址",
    "监控类型","数据值","报警时间","操作");
?>
  <script type="text/javascript">
   var getColumnIndexByName = function(grid, columnName) {
        var cm = grid.jqGrid('getGridParam', 'colModel'), i, l;
        for (i = 1, l = cm.length; i < l; i += 1) {
            if (cm[i].name === columnName) {
                return i; // return the index
            }
        }
        return -1;
    };
    // Custom formatter for a cell in a jqgrid row. 
    function pointercursor(cellvalue, options, rowObject)
    { 
      var cellvalue="解除警报";
      var new_formatted_cellvalue = '<button class="btn btn-primary">' + cellvalue + '</button>'; 
      return new_formatted_cellvalue; 
    } 
  // var lastsel;
  jQuery("#list2").jqGrid({
   	url:"/agr/dataServer_alarm.php?num=<?php echo $num ?>",
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
          ,{
             name: 'check', index: 'check', width: 80, align: 'center',
             formatter: pointercursor, editoptions: { value: '1:0' },
             formatoptions: { disabled: false }, 
          }
     	],		
     	rowNum:10,
     	height:350,
     	rowList:[10,20,30],
     	pager: '#pager2',
     	sortname: 'time',
      sortorder: "desc",
      regional:'cn',
      editurl: "/agr/dataServer_config.php?table=<?php echo $tableName ?>",
      loadComplete: function () {
        var iCol = getColumnIndexByName ($(this), 'check'), rows = this.rows, i,
            c = rows.length;
        for (i = 1; i < c; i += 1) {
            $(rows[i].cells[iCol]).click(function (e) {
              //此处添加点击后的处理事件
                var id = $(e.target).closest('tr')[0].id;
                $.ajax({
                  url: '/agr/dataServer_config.php?table=<?php echo $tableName ?>',
                  type:"post",
                  data:{"oper":"remove","id":id},
                  success:function(){
                    //alert("解除成功！");
                    $('#list2').trigger("reloadGrid");
                  },
                  error:function(){
                    alert("系统错误，请稍后重试");
                  }
                });     
            });
        }
      },
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
            id: postdata.id, oper: postdata.oper, sensor_id: rowdata.sensor_id,power_id:rowdata.power_id,device_id:rowdata.device_id,time:rowdata.time
          };
        }}, // del options
        {} // search options
   );
  //}
  // jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false});
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
<?php } ?>
