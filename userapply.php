<?php 
// 将该页面封装成函数，在user.php中被调用
   function userApply(){
?>

<!-- 利用jqgrid插件构建表格 -->
  <table id="list2"></table>
  <div id="pager2"></div>
<?php
  session_start();
  $num = $_GET['num'];
  // 设备配置 告警配置 作物种类 视频管理
  if(!$sidx) $sidx =1;
  $arrTable = array("序号","地区","地区编号","用户名","角色", "通过验证");
  $table="申请管理";
  $tableName="user_message";
  $width=120;
?>
  <script type="text/javascript">
  //getColumnIndexByName 该函数是为了下方绑定checkbox事件
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
      var cellvalue="通过";
      var new_formatted_cellvalue = '<button class="btn btn-primary">' + cellvalue + '</button>'; 
      return new_formatted_cellvalue; 
    } 

  var lastsel;
  jQuery("#list2").jqGrid({
    url:"userlist_server.php?type=check",
    datatype: "json",
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
              $result = mysql_query($query);
              while($row1 = mysql_fetch_array($result)) {
                //echo $row1[0];
                if($row1[0]!="password"&&$row1[0]!="country"&&$row1[0]!="district"&&$row1[0]!="checked"){
                  //edittype:"select",editoptions:{value:"FE:FedEx;IN:InTime;TN:TNT;AR:ARAMEX"}
                  $flag=true;
                  if($row1[0]=='id'){ $flag = "false"; }
                  echo $sep . "{name:'" . $row1[0] . "',index:'" . $row1[0] . "', width:" . $width . ", editable:$flag, required : true,search:true,align:'center'}"; 
                  $sep = ",";
                }
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
      viewrecords:true,
      pager: '#pager2',
      sortname: 'id',
      sortorder: "asc",
      regional:'cn',
      caption:"<?php echo $table ?>",
      editurl: "dataServer_config.php?table=<?php echo $tableName ?>",
      //checkbox event
      loadComplete: function () {
        var iCol = getColumnIndexByName ($(this), 'check'), rows = this.rows, i,
            c = rows.length;
        for (i = 1; i < c; i += 1) {
            $(rows[i].cells[iCol]).click(function (e) {
              //此处添加点击后的处理事件
                var id = $(e.target).closest('tr')[0].id;
                $.ajax({
                  url: 'dataServer_config.php?table=<?php echo $tableName ?>',
                  type:"post",
                  data:{"oper":"check","id":id},
                  success:function(){
                    alert("验证通过");
                    $('#list2').trigger("reloadGrid");
                  },
                  error:function(){
                    alert("系统错误，请稍后重试");
                  }
                });     
            });
        }
      }

  });
  jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:true},
    {}, // edit options
    {},
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
<?php 
  }     //接最上方的函数userApply()
?>