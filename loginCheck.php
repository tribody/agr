<?php
//验证登陆信息
session_start();
//数据库链接文件
$host='localhost';//数据库服务器
$datauser='root';//数据库用户名
$psw='';//数据库密码
$database='agriculture';//数据库名
$conn=@mysql_connect($host,$datauser,$psw) or die('数据库连接失败！');
@mysql_select_db($database) or die('没有找到数据库！');
mysql_query("set names 'UTF8'");

//  -----------------------------------登录---------------------------------------------------
	$type =$_GET['type'];
	if($type=="login"){
		$user=$_POST['username'];
		$password= md5($_POST['password']);
		$validateCode = $_POST['validateCode'];
		if($validateCode!=$_SESSION['validateCode']){
			echo "code_error";
			exit(0);
		}
		$sql="SELECT * FROM user_message";
		$query=mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if ($row['user']==$user){
				if ($row['password']==$password){
					if($row['checked']==1){
						$_SESSION['agruser']=$user;
						$_SESSION['agrzone']=$row['zone'];
						$_SESSION['agrrole']=$row['role'];
						echo "success"; exit(0);
					}
					else echo "check"; exit(0);
				}else {
					echo "pwd_error";exit(0);
				}
			}
		}
		echo "name_error";	
	}
//  -----------------------------------注册---------------------------------------------------
if($type=="signup"){
	$user="'".$_POST['user']."'";
	$password="'".md5($_POST['password'])."'";
	$role="'".$_POST['role']."'";
	$area="'".$_POST['area']."'";
	//利用传过来的area的值，在user_message表中寻找到相应的编号
	$SQL="SELECT DISTINCT zone FROM greenhouse_message WHERE area = $area";
	$result=mysql_query($SQL) or die('查询失败51行！');
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$zone = $row['zone'];
	$SQL = "INSERT INTO user_message(area,zone,user,password,role) VALUES($area,$zone,$user,$password,$role)";
	$result=mysql_query($SQL);
	echo "success";
}
// 注册时，检查用户名是否已经注册
	if($type=="checkname"){
		$user='"'.$_POST['username'].'"';
		$sql="SELECT * FROM user_message WHERE user = $user";
		$result=mysql_query($sql);
		if(mysql_num_rows($result)>0){
			echo "name_error";
		}else{
			echo "pass";
		}
	}












mysql_close();
?>