<?php 
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	require_once "config.inc.php";
	require_once "db.inc.php";
?>
<?php
	$zone = $_GET['zone'];
	$greenhouse_id = $_GET['greenhouse_id'];
  	$table="crops_message";

$result = mysql_query("SELECT COUNT(*) AS count FROM $table");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];
// echo $count; exit(0);
 $i=0;
$colum = array();
$query="SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='agriculture' AND table_name = '" . $table. "'";
// echo $query; exit(0);
$result1 = mysql_query($query);
while($row1 = mysql_fetch_array($result1)) {
//echo $row[0];
	$colum[$i]=$row1[0];
	// echo $colum[$i].",";
	$i++;
}
$SQL = "SELECT * FROM $table WHERE zone = $zone and greenhouse_id = $greenhouse_id order by id";
//echo $SQL; exit(0);


$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
$i=0; $j=1;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell'][] ="$j";
    foreach($colum as $col){
    	if ($col != "id"){
			$responce->rows[$i]['cell'][] = $row[$col];
		}
	}
		
	$i++; $j++;
}        
echo json_encode($responce);
?>