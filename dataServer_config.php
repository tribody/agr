<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
?>
<?php
	$table = $_GET['table'];
	/*
		greenhouse_id	1
		id	93
		oper	del
		sensor_id	1
		time	2015-05-04 21:03:09
	*/	
	if($table=="Alarm_new"||$table=="Alarm_old"){
	// 该处是为了能够删除报警信息中的数据，因为报警信息中的数据是两张表eg.history_message 和
	// alarm_setting共同查询出来的视图，因此无法输出某个特定的行。
	// 因此要想删除数据，本质上是删除history_message中的数据,通过下发的字段名可以精准定位到
	//history_message表中要删除的记录。 
		$greenhouse_id = $_POST['greenhouse_id'];
		$sensor_id = $_POST['sensor_id'];
		$time = "'".$_POST['time']."'";
		$table = "history_message";
	}
	$i=0; $j=0;
	$colum = array();
	$key = array();
	$query="select COLUMN_NAME from information_schema.COLUMNS where 	TABLE_SCHEMA='agriculture' and table_name = '" . $table. "'";
	// echo $query; exit(0);
	$result1 = mysql_query($query);
	while($row1 = mysql_fetch_array($result1)) {
	//echo $row[0];
		$colum[$i]=$row1[0];
		// echo $colum[$i].",";
		$i++;
	}
	foreach($colum as $value)
		{
			$key[$j++] = $_POST[$value];
		}
	$oper = $_POST['oper'];
	// echo "42----".$table."------".$oper; exit(0);
	if($oper=='edit'){
		for($i=1;$i<sizeof($key);$i++){
			$str .=  " $colum[$i] = '$key[$i]', ";
		}
		// 目的是去掉最后一个逗号：sensor_type = 空气温度（C）,  max_value = 32,  min_value = 2
		$str = substr($str, 0, -2);
		$SQL ="UPDATE $table SET $str WHERE id= $key[0]";  
		//echo $SQL;
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($oper=='del'){
		if($table=="Alarm_new"||$table=="Alarm_old"){
			$SQL ="DELETE FROM $table WHERE greenhouse_id=$greenhouse_id AND sensor_id = $sensor_id AND time=$time";
		}else{
			$SQL = "DELETE FROM $table WHERE id= $key[0]";
		}
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	if($oper=='add'){
		//INSERT INTO `alarm_setting` (`sensor_type`, `max_value`, `min_value`) VALUES ('光照强度', 60, 6)
		for($i=1;$i<sizeof($colum);$i++){
			$str1 .=  " `$colum[$i]`, ";
		}
		//去掉最后一个逗号
		$str1 = substr($str1, 0, -2);
		for($i=1;$i<sizeof($key);$i++){
			$str2 .=  " '$key[$i]', ";
		}
		//去掉最后一个逗号
		$str2 = substr($str2, 0, -2);
		$SQL ="INSERT INTO $table ($str1) VALUES ($str2)";
		//echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	}
	//用于处理用户管理中验证通过事件，当点击勾选checkbox则使得user_message中该记录
	//的checked字段值设置为1
	if($oper=='check'){
		// echo "helloworld"; exit(0);
		$SQL ="UPDATE $table SET checked = 1 WHERE id= $key[0]";  
		// echo $SQL; exit(0);
 		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
 		
	}
	//解除报警 
	if($oper=='remove'){
		// echo "helloworld"; exit(0);
		$table = "history_message";
		$SQL ="UPDATE $table SET alarmed = 1 WHERE id= $key[0]";  
		//echo $SQL; exit(0);
 		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
 		
	}
?>