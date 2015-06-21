  <!-- 尝试封装成一个函数greenHounse()  ———Calvin -->
  <?php 
    function greenHouse(){
      $width=100;
      $tableName="greenhouse_message";
      $arr = array("国家","城市","实验区","大棚区号","大棚编号","经度","纬度");
      $table = "大棚信息表"; 
    //此处少了}号，在最下方 
  ?>
  <script type="text/javascript">
  //	jQuery().ready(function (){
  var lastsel;
  jQuery("#list2").jqGrid({
     	url:"dataServer_gh.php",
     	// url:"dataServer.php?num=<?php echo $num ?>",
  	datatype: "json",
  	//`id`, `zone`, `zone_id`, `device_id`, `add_mac`, `device`, `sensor_type`, `cod_x`, `cod_y`, `data`, `time`, `cishu`
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
                  $i=0;
                  while($row1 = mysql_fetch_array($result1)) {
                     if($row1[0]!="id"){
                        if($row1[0]=="time"){
                          $width=120;
                        }
                        if($_SESSION['agrrole']=="超级管理员"||$_SESSION['agrrole']=="管理员"){
                          $flag="true";
                        }else{
                          $flag="false";
                        }
                        //为了解决特定区域的管理员是不能够修改区号的问题
                        if($_SESSION['agrrole']=="管理员"&&($row1[0]=='zone'||$row1[0]=='area')){
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
      viewrecords: true,
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
      subGrid : true, subGridUrl: 'subgrid_gh.php?q=3', subGridModel: [{ name : ['序号','大棚区号','大棚编号','作物种类'], width : [150,150,150,200], align:['center','center','center','center'],params:['zone','greenhouse_id']} ],
      caption:"<?php echo $table ?>"
  });
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:<?php echo $flag ?>,add:<?php echo $flag ?>,del:<?php echo $flag ?>},
    {reloadAfterSubmit:true}, // edit options
    {reloadAfterSubmit:true}, // add options
    {reloadAfterSubmit:true}, // del options
    {} // search options
  );
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
<?php 
  }     //接最上方的函数greenHouse()
?>
