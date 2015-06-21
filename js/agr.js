var flag1 = true; var flag2= true; var flag3=true;
var username = $('#username').attr("placeholder");
function pwd_check(){
	$('#user_pwd').blur(function(){
    // alert("修改失败请稍后重试", false);
		var password = $('#user_pwd').val();
    var username = $('#username').attr("placeholder");
		//检测原密码是否正确
		if(password!=""){
			//alert("不会吧");
			$.ajax({
				url: "user_repwd.php?type=check",
				type:"post",
				data:{"username":username,"password":password},
				success:function(result){
					//如果原密码错误则显示出来
					$('#pwd_msg').html(result);
					if(result){
						flag1 = false; //使得提交按钮失效
					}else{
						flag1 = true;
						$('#pwd_msg').html("");
					}
				},
				error:function(){
					alert("网页出现错误，请稍后再试！", false);
				}
			});
		}else{
			$('#pwd_msg').html("此处不能为空！");
			flag1 = false;
		}
	});
}

function repwd_check(){
	$('#repwd1').blur(function(){
    var username = $('#username').attr("placeholder");
		var password = $('#user_pwd').val();
		var repassword1 = $('#repwd1').val();
		if(repassword1!=""){
			if(password==repassword1){
				flag2=false;
				$('#repwd1_msg').html("新密码与旧密码一致");
			}else{
				flag2=true;
				$('#repwd1_msg').html("");
			}
		}else{
			$('#repwd1_msg').html("此处不能为空!");
			flag2 = false;
		}
	});
}

function pwd_modify(){
	$('#user_submit').click(function(){
    var username = $('#username').attr("placeholder");
		var repassword1 = $('#repwd1').val();
		var repassword2= $('#repwd2').val();
		if(repassword1!=repassword2){
			flag3 = false;
			$('#repwd2_msg').html("新密码输入不一致");
		}else{
			flag3= true;
			$('#repwd2_msg').html("");
		}
		if(flag1&&flag2&&flag3){
			$.ajax({
				url: "user_repwd.php?type=modify",
				type:"post",
				data:{"newPwd":repassword2,"username":username},
				success:function(result){
					if("success" == result){
						alert("修改成功！", true);
						location='map.php';
					}else{
						alert("修改失败请稍后重试", false);
						location='user.php?num=1';
					}	
				},
				error:function(){
					alert("处理错误", false);
				}
			});
		}
	});
}

function signout(){
	$('#signout').click(function(){
		$.ajax({
			url: "logout.php",
			type:"post",
			success:function(result){
				if(result=="success"){
					//alert("登出成功！");
					location="index.php";
				}
			},
			error:function(){
				alert("出现错误,请稍后重试！", false);
			}
		});
	});
	//没有用到，目的是绑定alert的确定按钮
	$(document).on("click","#btn_out",function(){
		location="index.php";
	});
}

//----------------------------------注册登录-----------------------------------
	var regist_flag1 = true; 
	var regist_flag2 = true;
	var regist_flag3 = true;
function signup(){
	//用户名不能为空检测
  	$('#password2').focus(function(){ 
	  //alert($('#user_register').val());
	  var username = $('#user_register').val();
	  if(""==username){
	    $('#user_register_error').html("用户名不能为空");
	    regist_flag1 = false; 
	  }else{
	    regist_flag1 = true; 
	    $('#user_register_error').html("");
	  }
  	});
  //用户名查重模块
  $('#user_register').blur(function(){
      var username = $('#user_register').val();
      if(username!=""){
        //alert(username);
        $.ajax({
          url: "loginCheck.php?type=checkname",
          type:"post",
          data:{"username":username},
          success:function(result){
            if(result=="name_error"){
              $('#user_register_error').html("该用户名已注册");
              regist_flag3 = false;
            }else{
              $('#user_register_error').html("");
              regist_flag3 = true;
            }
          },
          error:function(){
            alert("处理错误" ,false);
          }
        });
      }
  });
  //注册提交按钮
  $('#registbtn').click(function(){
      var username = $('#user_register').val();
      var role = $('#role_selection').find('option:selected').attr("value");
      var area = $('#area_selection').find('option:selected').attr("value");
      var pwd = $('#password2').val();
      var repwd = $('#repassword').val();
      if(pwd!=repwd){
        regist_flag2 = false;
        $('#repwd_register_error').html("密码不一致，请重新输入！");
      }else{
        regist_flag2 = true;
        $('#repwd_register_error').html("");
      }
      if(!role){
          $('#role_error').html("需要选择角色");
      }else{
          $('#role_error').html("");
      }
      if(!area){
          $('#area_error').html("需要选择区域");
      }else{
           $('#area_error').html("");
      }
      if(regist_flag1&&regist_flag2&&regist_flag3&&role&&area){
          $.ajax({
            url: "loginCheck.php?type=signup",
            type:"post",
            data:{"user":username,"password":repwd,"role":role,"area":area},
            success:function(result){
              if(result=="success"){
                alert("注册请求发送成功，等待管理员验证", true);
              }else{
                alert("注册失败，请稍后重试", false);
              }
            },
            error:function(){
              alert("处理错误", false);
            }
          });
      }
  });
}

function login(){
  	$(document).on("click","#login_submit",function(){
    //alert("helloworld");
    var username = $('#user').val();
    var password = $('#password1').val();
    var validateCode = $('#code_math').val();
    if(username==""){
    	$('#code_msg').html('用户名不能为空！');
    	return false;
    }
    if(password==""){
    	$('#code_msg').html('密码不能为空！');
    	return false;
    }
    if(validateCode==""){
    	$('#code_msg').html('验证码不能为空！');
    	return false;
    }
    if(username&&password){
      $.ajax({
         url: "loginCheck.php?type=login",
         type:"post",
         data:{"username":username,"password":password,"validateCode":validateCode},
         success:function(result){
            switch(result){
              case "success": 
                {
                  location = "map.php"; break;
                }
              case "check": alert("等待管理员验证", true); break;
              case "name_error": 
                {
                  $('#pwd_msg').html("");
                  $('code_msg').html("");
                  $('#user_msg').html("用户名不存在"); break;
                }
              case "pwd_error": 
                {
                  $('user_msg').html("");
                  $('code_msg').html("");
                  $('#pwd_msg').html("输入密码错误"); break;
                }
                case "code_error": 
                {
                  $('user_msg').html("");
                  $('#pwd_msg').html("");
                  $('#code_msg').html("输入验证码错误"); 
           		  $("#getcode_math").click();
                  break;
                }
              default: alert("请稍候重试！", false);
            }
         },
         error:function(){
           alert("处理错误", false);
         }
      }); 
    }
  });
}