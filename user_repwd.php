<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
	
	$type = $_GET['type'];
	$password = '"'.md5($_POST['password']).'"';
	$username = '"'.$_POST['username'].'"';
	// echo $username;
	// exit(0);
	$repassword = '"'.md5($_POST['newPwd']).'"';
	mysql_query("use agriculture");
	//检查原密码是否正确
	if($type=="check"){
		$SQL = "SELECT * FROM user_message WHERE user = $username AND password = $password";
		// echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		if(mysql_num_rows($result)==0){
			//echo "helloworld"; exit(0);
			echo "原密码错误";
		}
	}
	//修改用户的密码
	if($type=="modify"){
		$SQL = "UPDATE user_message SET password = $repassword WHERE user=$username";
		//echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		if(mysql_affected_rows()>0){
			echo "success";
		}else{
			echo "error";
		}

	}

	
 ?>