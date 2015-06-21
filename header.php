<?php
	include "config.inc.php";
	include "db.inc.php";
?>
<?php
//在需要验证管理员身份的地方引用
//在引用本文件之前不能有任何形式的输出，建议在文档最开始出引用
	session_start();
	if ($_SESSION['agruser']==""){
		echo "<script language='javascript'>alert('非法操作！');location='index.php';</script>";
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    <meta name="description" content="This project is designed for research.">
	    <meta name="author" content="Jason He">
	    <title>农业物联网管理平台</title>
	    <!-- Bootstrap core CSS -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">
	    <!-- Custom styles -->
	    <link rel="stylesheet" href="css/dashboard.css">
	    <link rel="stylesheet" href="css/carousel.css">
	    <link rel="stylesheet" href="css/bootstrap-select.min.css">
		 <!-- 下面是jqgrid的样式表 -->
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/ui-lightness/jquery-ui-1.8.2.custom.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/ui.jqgrid.css" />
		<!-- jQuery的js文件 -->
		<script src="jqgrid/js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<!-- jqGrid的js文件 -->
		<script src="jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="jqgrid/js/i18n/grid.locale-cn.js" type="text/javascript"></script>
		<!-- 下面是bootstrap的js文件 -->
		<script src="js/bootstrap-select.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- 自定义js文件 -->
		<script src="js/nav.js"></script>	
		<script src="js/agr.js" type="text/javascript"> </script>
		<script>
			window.alert=function myalert(msg,url){
				$('#error_msg').html('<p style="padding:15px;">'+msg+'</p>');
		        $('#error_box').modal('show');
		        if(url){
			        $('#alert_confirm').bind('click',function(){
			        	location='/agr/'+url;
			        });
		    	}
			}

			$(function(){
				//原密码检查
				pwd_check();
				//新旧密码检查
				repwd_check();
				//密码修改
				pwd_modify();
				//登出操作
				signout();
			});
		</script>	
	</head>

	<body>
	<!-- alert box -->
	<div class="modal fade" id="error_box" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="loginLabel" style="color:red;"><strong>错误</strong></h4>
          </div>
          <div class="modal-body">
          	<p id="error_msg" style="font-size:16px;margin-left:10px;"></p>
          </div>
          <div class="modal-footer">
            <button  type="button" class="btn btn-primary" id="alert_confirm" data-dismiss="modal">确认</button>
          </div>
        </div>   
      </div>
	</div>
    <!-- nav-bar -->
    <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand">农业物联网管理平台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="mobile" style="display:none"><a href="map.php">拓扑管理</a></li>
            <li class="dropdown mobile" style="display:none">
	          <a href="chart.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">数据分析 <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="dataAnalysis.php?num=1">最新数据</a></li>
	            <li><a href="dataAnalysis.php?num=2">历史数据</a></li>
	            <li><a href="chart.php">图表显示</a></li>
	          </ul>
	        </li>
	        <li class="dropdown mobile" style="display:none">
	          <a href="dataAnalysis_alarm.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">告警管理 <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="dataAnalysis_alarm.php?num=1">最新告警</a></li>
	            <li><a href="dataAnalysis_alarm.php?num=2">历史告警</a></li>
	          </ul>
	        </li>
	        <li class="dropdown mobile" style="display:none">
	          <a href="configure.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">配置管理 <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="configure.php?num=1">设备配置</a></li>
	            <li><a href="configure.php?num=2">告警配置</a></li>
	            <li><a href="configure.php?num=3">大棚配置</a></li>
	            <li><a href="configure.php?num=4">作物种类</a></li>
	            <li><a href="configure.php?num=5">视频管理</a></li>
	          </ul>
	        </li>
	        <li class="dropdown mobile" style="display:none">
	          <a href="yonghu.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">用户管理 <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="user.php?num=1">修改密码</a></li>
	            <?php 
	            	if($_SESSION['agrrole']!="用户"){
	            		echo '<li><a href="user.php?num=2">申请管理</a></li><li><a href="user.php?num=3">用户列表</a></li>';
	            	}
              	?>
	          </ul>
	        </li>
	       <li class="mobile" style="display:none"><a href="video.php">视频管理</a></li>
	        <li><a href="map.php">主页</a></li>
            <li><a href="#about">关于</a></li>
            <li><a href="#contact">联系我们</a></li>
          </ul>
          <button id="signout" class="btn btn-primary navbar-btn navbar-right">登出</button>
          <p class="navbar-text text-info navbar-right"><?php echo $_SESSION['agruser'] ?>，&nbsp欢迎</p>
        </div>
      </div>
    </nav>
    <!-- side-bar -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-2 sidebar" style="display:none">
          <ul class="nav nav-sidebar">
            <li><a href="map.php"  class="map">拓扑管理</a></li>
            <li><a href="chart.php">数据分析</a>
				<ul>
					<li><a href="dataAnalysis.php?num=1">最新数据</a></li>
		            <li><a href="dataAnalysis.php?num=2">历史数据</a></li>
		            <li><a href="chart.php">图表显示</a></li>
				</ul>
            </li>
            <li><a href="dataAnalysis_alarm.php">告警管理</a>
				<ul>
					<li><a href="dataAnalysis_alarm.php?num=1">最新告警</a></li>
            		<li><a href="dataAnalysis_alarm.php?num=2">历史告警</a></li>
				</ul>
            </li>
            <li><a href="configure.php">配置管理</a>
				<ul>
					<li><a href="configure.php?num=1">设备配置</a></li>
		            <li><a href="configure.php?num=2">告警配置</a></li>
		            <li><a href="configure.php?num=3">大棚配置</a></li>
		            <li><a href="configure.php?num=4">作物种类</a></li>
		            <li><a href="configure.php?num=5">视频管理</a></li>
				</ul>
            </li>
            <li><a href="user.php">用户管理</a>
				<ul>
		            <li><a href="user.php?num=1">修改密码</a></li>
		            <?php 
		            	if($_SESSION['agrrole']!="用户"){
		            		echo '<li><a href="user.php?num=2">申请管理</a></li><li><a href="user.php?num=3">用户列表</a></li>';
		            	}
                  	?>
	          	</ul>
            </li>
            <li><a href="video.php" class="map">视频管理</a></li>
          </ul>
        </div>
        <!-- main内容显示容器 -->
        <div class="col-xs-12 col-sm-9 col-sm-offset-3 col-md-10 main">
        	<div class="container-fluid" id="cont">
        
							
					
		