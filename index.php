<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
  error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
  require_once "config.inc.php";
  require_once "db.inc.php";
  session_start();
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="This project is designed for research">
    <meta name="author" content="Jason He">

    <title>首页展示</title>
    <script src="jqgrid/js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="js/agr.js" type="text/javascript"></script>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-select.min.css">

    <!-- Custom styles for this template -->    
    <link href="css/signin.css" rel="stylesheet">
    <link rel="stylesheet" href="css/carousel.css">

    <script>
      window.alert=function myalert(msg,msg_label){
        if(msg_label) {
          $('#msg_label').html('<strong style="color:green;">提示</strong>');
        } else {
          $('#msg_label').html('<strong style="color:red;">错误</strong>');
        }
        $('#msg').html('<p style="padding:15px;">'+msg+'</p>');
        $('#login').modal('hide');
        $('#signup').modal('hide');
        $('#tip').modal('show');
      }
      $(function(){
          signup();
          $("#getcode_math").click(function(){
            $(this).attr("src",'code_math.php?' + Math.random());
          });
          login();
      });
    
    </script>
  </head>
  <body>
    <!--  alert box   --> 
    <div class="modal fade" id="tip" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="alertLabel"><strong id="msg_label"></strong></h4>
            </div>
            <div class="modal-body">
              <p id="msg" style="font-size:16px;margin-left:10px;"></p>
            </div>
            <div class="modal-footer">
              <button  type="button" class="btn btn-primary" data-dismiss="modal">确认</button>
            </div>
          </div>   
        </div>
    </div>
    <!-- loginModal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="loginLabel">登录</h4>
          </div>
            <div class="modal-body">
              <label for="username" class="sr-only">Username</label>
              <input name="user" type="user" id="user" class="form-control" placeholder="用户名" required autofocus>
              <span id="user_msg"></span>
              <label for="inputPassword" class="sr-only">Password</label>
              <input name="password" type="password" id="password1" class="form-control" placeholder="密码" required>
              <span id="pwd_msg"></span>
              <input type="text" class="form-control" id="code_math" placeholder="验证码" maxlength="4" style="margin-bottom:5px;" /> <img class="img-rounded" src="code_math.php" id="getcode_math" title="看不清，点击换一张" align="absmiddle"/>
              <span id="code_msg"></span>
            </div>
            <div class="modal-footer">
              <input id="login_submit" type="submit" class="btn btn-primary btn-block" name="submit" value="登录">
            </div>
        </div>
      </div>
    </div>
    <!-- signupModal -->
    <div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="signupLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="signupLabel">注册</h4>
          </div>
            <div class="modal-body">
              <label for="username" class="sr-only">Username</label>
              <input name="user" type="user" id="user_register" class="form-control" placeholder="用户名" required autofocus>
              <span id="user_register_error"></span>
              <label for="inputPassword" class="sr-only">Password</label>
              <input name="password" type="password" id="password2" class="form-control" placeholder="密码" required>
              <label for="reinputPassword" class="sr-only">Repassword</label>
              <input name="repassword" type="password" id="repassword" class="form-control" placeholder="再次输入密码" required>
              <span id="repwd_register_error"></span>
              <label for="ID" class="sr-only">ID</label>
              <select id='role_selection' class="selectpicker" data-style="btn-primary" data-width="100%" name="role">
                <option selected="selected" style="display:none">----选择身份----</option>
                <option value ="用户">----用户----</option>
                <option value ="管理员">----管理员----</option>
              </select>
              <span id="role_error"></span>
              <label for="Zone" class="sr-only">Zone</label>
              <select  id='area_selection' class="selectpicker" data-style="btn-primary" data-width="100%" name="area">
                <option selected="selected" style="display:none">----选择区域----</option>
                <?php
                // 实现从数据库中查找现有的区域
                    $SQL = "SELECT DISTINCT area FROM greenhouse_message";
                    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
                    $sep='"';
                    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                      echo '<option value="'.$row[area].'">----'.$row[area].'区----</option>';
                    }
                ?>
              </select>
              <span id="area_error"></span>
            </div>
            <div class="modal-footer">
              <input type="submit" class="btn btn-primary btn-block" id="registbtn" name="signup" value="注册">
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
            <li class="active"><a href="#">主页</a></li>
            <li><a href="#about">关于</a></li>
            <li><a href="#contact">联系我们</a></li>
          </ul>
          <button type="button" class="btn btn-primary navbar-btn navbar-right" data-toggle="modal" data-target="#login">
              登录
          </button>
          <button type="button" class="btn btn-primary navbar-btn navbar-right" data-toggle="modal" data-target="#signup">
              注册
          </button>
        </div>
      </div>
    </nav>
    <!-- Carousel -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="images/area/1.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>拓扑浏览</h1>
              <p>用户可以通过地图拓扑结构查看区域大棚传感器信息，拓扑结构分为三级拓扑，从区域、大棚到传感器，切换轻松自如</p>
              <br><br>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="images/area/2.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>数据呈现</h1>
              <p>提供多样化数据呈现形式，用户可以通过表格查看传感器实时数据或者历史数据，利用百度Echarts图表引擎，为您提供图像化的动态数据，丰富的数据的展现能力</p>
              <br><br>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="images/area/3.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>云摄像监控</h1>
              <p>提供远程摄像头实时监控并录像，随时查看历史监控图像，并且支持市面上绝大多数摄像头接入</p>
              <br><br>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    <!-- FOOTER -->
    <footer>
      <p class="text-center">版权所有&copy; <a href="http://www.shu.edu.cn/">上海大学&nbsp;&nbsp;</a>地址：<a href="http://map.lehu.shu.edu.cn/">上海大学宝山区上大路99号（周边交通）&nbsp;&nbsp;</a>邮编：200444&nbsp;&nbsp;<a href="http://www.shu.edu.cn/Default.aspx?tabid=8558">电话查询</a></p>
    </footer>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
