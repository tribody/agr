<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
?>
<?php
	session_start();
	$type = $_GET['type']; //用于处理userlist.php 和 userapply.php两个页面发出的请求
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$table="user_message";
	if($_SESSION['agrrole']=="超级管理员"){
		$SQL = "SELECT COUNT(*) AS count FROM $table WHERE 1=1"; //1=1 语句的目的是为了方便后面if($sensor_id)的判断方便
	}else{
		//$SQL = "SELECT COUNT(*) AS count FROM $table WHERE zone = '{$_SESSION['agrzone']}'";
		$SQL = "SELECT COUNT(*) AS count FROM $table WHERE zone = '{$_SESSION['agrzone']}'";	
	}
	if($type=="check"){
		$SQL .= " AND checked = 0 ";
	} 
	//echo $SQL; exit(0);
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
// echo $count; exit(0);
	$i=0;
	$colum = array();
	$query="SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='agriculture' AND 	table_name = '" . $table. "'";
	// echo $query; exit(0);
	$result1 = mysql_query($query);
	while($row1 = mysql_fetch_array($result1)) {
		$colum[$i]=$row1[0]; 
		$i++;
	}
	// exit(0);
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($_SESSION['agrrole']=="超级管理员"){
		$SQL = "SELECT id,area,zone,user,role FROM $table  WHERE 1=1 ";
	}else{
		$SQL = "SELECT id,area,zone,user,role FROM $table  WHERE zone = '{$_SESSION['agrzone']}' ";
	}
	if($type=="check"){
		$SQL .= " AND checked = 0 ";
	}
	if( $count >0 ){
		$SQL = $SQL." ORDER BY $sidx $sord LIMIT $start , $limit";
	}
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0; $j=1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	    $responce->rows[$i]['id']=$row['id'];
	    $responce->rows[$i]['cell'][] ="$j";
	    foreach($colum as $col){
	      if ($col != "id"&&$col!="password"&&$col!="country"&&$col!="district"&&$col!="checked"){
	      	$responce->rows[$i]['cell'][] = $row[$col];
	    }
	  }
	  $i++; $j++;
	}         
	echo json_encode($responce);
?>