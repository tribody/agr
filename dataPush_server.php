<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
	if($_GET['flag']==1){
		//url:http://localhost/Agr_origin/dataPush_server.php?flag=1&&sensor_id=1&max=100&min=2
		$max = $_GET['max'];
		$min = $_GET['min'];
		$sensor_id = $_GET['sensor_id'];
		//想要 5 到 15（包括 5 和 15）之间的随机数，用 rand(5, 15)。
		//$SQL ="INSERT INTO $table ($str1) VALUES ($str2)";
		function random_float ($min,$max) {
			return ($min+lcg_value()*(abs($max-$min)));
		}
		//上方函数是为了产生出浮点数
		//sprintf是为了产生2位小数
		$randData  = sprintf("%.2f",random_float($min,$max));
		// $randData = rand($min,$max);
		//
		$SQL = "SELECT * FROM device_message WHERE sensor_id = $sensor_id";
		// echo $SQL; exit(0);
		//device表   id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y time
		//history_message表  id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y data time
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$dataName = "`zone`,`greenhouse_id`,`sensor_id`,`add_mac`,`sensor_type`,`cod_x`,`cod_y`,`data`";
			 $sep = "'";
			$dataValue = "{$row['zone']},{$row['greenhouse_id']},$sensor_id, $sep{$row['add_mac']}$sep, $sep{$row['sensor_type']}$sep,$sep{$row['cod_x']}$sep,$sep{$row['cod_y']}$sep,$sep$randData$sep";
			//echo $dataValue; exit(0);	
   			//$dataValue = "{$row['zone']},{$row['greenhouse_id']}";
   			$SQL ="INSERT INTO `history_message` ($dataName) VALUES ($dataValue)"; 
   			$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	    }
	    // echo "hellowworld";
	    // echo $SQL; exit(0);
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
		$SQL = "SELECT DISTINCT $fieldName2 FROM device_message WHERE $fieldName1 =$field";
		// echo $SQL; exit(0);
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
		while($row = mysql_fetch_array($result)) {
			echo "<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	
 ?>