<?php 
	include "header.php";	
	include "userlist.php";
	include "userapply.php";
?>

<?php
	if(isset($_GET['action']) && $_GET['action']=="mod") {
		//修改数据库中的数据
		$sql = "UPDATE user_message SET password='{$_POST['psw']}' WHERE user='{$_SESSION['agruser']}'";
		// echo $sql; exit(0);
		$result = mysql_query($sql);

		if($result && mysql_affected_rows() > 0) {
			echo "<script language='javascript'>alert('修改成功！');location='map.php';</script>";
		}else{
			echo "<script language='javascript'>alert('与原密码一致！请重新修改！');</script>";
			}
	}	
?>

<?php if($_GET['num']==1){ ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">修改密码</h3>
    </div>
    <div class="panel-body form-horizontal">
		<div class="form-group">
			<label for="user" class="control-label col-sm-4">用户名：</label>
			<div class="col-sm-4">
				<input id="username" type="username" class="form-control" placeholder="<?php echo $_SESSION['agruser'];?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="control-label col-sm-4">原密码：</label>
			<div class="col-sm-4">
				<input id="user_pwd" type="password" class="form-control">
			</div>
			<div id="pwd_msg" class="col-sm-4" style="color:red;"></div> 
		</div>
		<div class="form-group">
			<label for="new_password" class="control-label col-sm-4">新密码：</label>
			<div class="col-sm-4">
				<input id="repwd1" type="password" class="form-control" >
			</div>
			<span id="repwd1_msg" class="col-sm-4" style="color:red;"></span>
		</div>
		<div class="form-group">
			<label for="confirm_password" class="control-label col-sm-4">确认密码：</label>
			<div class="col-sm-4">
				<input id="repwd2" type="password" class="form-control">
			</div>
			<span id="repwd2_msg" class="col-sm-4" style="color:red;"></span> 
		</div>
		<div class="col-sm-8">
			<button id="user_submit" type="submit" class="btn btn-default pull-right">修改密码</button>
		</div>
    </div>
	
</div>
<?php } ?>


<?php 	
	if($_GET['num']==2){
		//调用userapply.php页面进行处理用户申请页面
		userApply();
	}	
	if($_GET['num']==3){
		//调用userlist.php页面进行处理用户列表页面
		userList();
	}	
?>
<?php	include "footer.php";	?>