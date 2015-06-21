<?php 
// 将该页面封装成函数，在user.php中被调用
   function userList(){
?>

<!-- 利用jqgrid插件构建表格 -->
	<table id="list2"></table>
	<div id="pager2"></div>
<?php
  session_start();
	$num = $_GET['num'];
  // 设备配置 告警配置 作物种类 视频管理
	if(!$sidx) $sidx =1;
  $arrTable = array("序号","地区","地区编号","用户名","角色");
  $table="用户列表";
  $tableName="user_message";
  $width=120;
?>
  <script type="text/javascript">

  var lastsel;
  jQuery("#list2").jqGrid({
   	url:"userlist_server.php?type=userlist",
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
                  //权限控制的问题
                  if($_SESSION['agrrole']=="超级管理员"||$_SESSION['agrrole']=="管理员"){
                    $flag="true";
                  }else{
                    $flag="false";
                  }
                  //为了解决特定区域的管理员是不能够修改区号的问题
                  if($_SESSION['agrrole']=="管理员"&&($row1[0]=='zone'||$row1[0]=='area')){
                    $flag="false";    
                  }
                  //edittype:"select",editoptions:{value:"FE:FedEx;IN:InTime;TN:TNT;AR:ARAMEX"}
                  if($row1[0]=='role'){
                    $option = ',edittype:"select",editoptions:{value:"用户:用户;管理员:管理员;超级管理员:超级管理员"}';
                  }else{
                    $option = " ";
                  }                 
                  if($row1[0]=='id'){ $flag = "false"; }
                  echo $sep . "{name:'" . $row1[0] . "',index:'" . $row1[0] . "', width:" . $width . ", editable:" . $flag . ",required : true,search:true,align:'center' ".$option."}"; 
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
  jQuery("#list2").jqGrid('navGrid','#pager2',{edit:<?php echo $flag ?>,add:false,del:<?php echo $flag ?>},
    {reloadAfterSubmit:true}, // edit options
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
  }     //接最上方的函数userList()
?>
