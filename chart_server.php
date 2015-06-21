<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
	session_start();
	if($_GET['flag']==1){
		if($_GET['timeChoose']==1) {
			//包装两个Jason数据
			//url:http://localhost/Agr_origin/dataPush_server.php?flag=1&&sensor_id=1&max=100&min=2
			$sensor_id = $_GET['sensor_id'];
			$SQL = "SELECT sensor_type FROM device_message WHERE sensor_id = $sensor_id";
			$result = mysql_query($SQL) or die("Couldn t execute query.".mysql_error()." 1");
			while ($row = mysql_fetch_array($result)) {
				echo $row[0];
				echo '&';
			}
			$SQL = "SELECT * FROM history_message WHERE sensor_id = $sensor_id order by time desc LIMIT 0,6";
			//device表   id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y time
			//history_message表  id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y data time
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()."2");
			$sep = '';
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	            echo $sep . '' . $row['time'] . '';             
	            $sep = ','; 
	     	}
	     	echo '&';
	     	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()."3");
	     	$sep = '';
	     	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	            echo $sep . '' . $row['data'] . '';             
	            $sep = ','; 
	     	}
	     } else{
	     	$date = $_GET['date'];
	     	$start_time = ''.$date.' 00:00:00';
	     	$end_time = ''.$date.' 23:59:59';
	     	//包装两个Jason数据
			//url:http://localhost/Agr_origin/dataPush_server.php?flag=1&&sensor_id=1&max=100&min=2
			$sensor_id = $_GET['sensor_id'];
			$SQL = "SELECT sensor_type FROM device_message WHERE sensor_id = $sensor_id";
			$result = mysql_query($SQL) or die("Couldn t execute query.".mysql_error()." 1");
			while ($row = mysql_fetch_array($result)) {
				echo $row[0];
				echo '&';
			}
			$SQL = "SELECT * FROM history_message WHERE sensor_id = $sensor_id AND (time > '$start_time' AND time < '$end_time') order by time desc ";
			//device表   id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y time
			//history_message表  id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y data time
			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()."2");
			$sep = '';
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	            echo $sep . '' . $row['time'] . '';             
	            $sep = ','; 
	     	}
	     	echo '&';
	     	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()."3");
	     	$sep = '';
	     	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	            echo $sep . '' . $row['data'] . '';             
	            $sep = ','; 
	     	}
	     }
	}else{
		$deviceArr = array();
		$SQL = "SELECT * FROM device_message  ORDER BY id";
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		$index=0;
		while($row = mysql_fetch_array($result,MYSQL_NUM)) {
			for($i=0;$i<count($row);$i++){
				$deviceArr[$index][$i] = $row[$i];
			}
			$index++;
		}
		$field = $_GET['field'];
		$fieldName1 = $_GET['fieldName1'];
		$fieldName2 = $_GET['fieldName2'];
		if($fieldName1=="zone") {
			$_SESSION['chart_zone'] = $field;
		}
		if($fieldName1=="sensor_type") {
			$field = '"'.$field.'"';
			$SQL = "SELECT DISTINCT $fieldName2 FROM device_message WHERE $fieldName1 =$field AND zone = '{$_SESSION['chart_zone']}'";
		} else {
			$SQL = "SELECT DISTINCT $fieldName2 FROM device_message WHERE $fieldName1 =$field";
		}
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		while($row = mysql_fetch_array($result)) {
			echo "<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	
 ?>