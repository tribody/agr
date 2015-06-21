<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
?>
<?php
	session_start();
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction	
	if(!$sidx) $sidx =1;
	$table = 'greenhouse_message';
	if($_SESSION['agrrole']=="超级管理员"){
		$SQL = "SELECT COUNT(*) AS count FROM $table WHERE 1=1"; //1=1 语句的目的是为了方便后面if($sensor_id)的判断方便
	}else{
			//$SQL = "SELECT COUNT(*) AS count FROM $table WHERE zone = '{$_SESSION['agrzone']}'";
		$SQL = "SELECT COUNT(*) AS count FROM $table WHERE zone = '{$_SESSION['agrzone']}'";	
	}
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	// echo $count; exit(0);
	 $i=0;
	$colum = array();
	$query="select COLUMN_NAME from information_schema.COLUMNS where  	TABLE_SCHEMA='agriculture' and table_name = '" . $table. "'";
	// echo $query; exit(0);
	$result1 = mysql_query($query);
	while($row1 = mysql_fetch_array($result1)) {
	//echo $row[0];
		$colum[$i]=$row1[0];
		// echo $colum[$i].",";
		$i++;
	}
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($_SESSION['agrrole']=="超级管理员"){
		$SQL = "SELECT * FROM $table  WHERE 1=1 ";
	}else{
		$SQL = "SELECT * FROM $table  WHERE zone = '{$_SESSION['agrzone']}' ";
	}
	$SQL = $SQL." ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	    $responce->rows[$i]['id']=$row[id];
	    // $responce->rows[$i]['cell']=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
	    // $i++;
	    // 
	    foreach($colum as $col){


	    	if ($col != "id"){
					$responce->rows[$i]['cell'][] = $row[$col];
				}
		}
			
		$i++;
	}        
	echo json_encode($responce);
?>