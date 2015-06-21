<?php 	
	include "header.php";	
	session_start();
	$agrzone = $_SESSION['agrzone'];
	$src = null;
	//处理GET方法跳转到该页面
	$srcs = array(
		"http://www.ispyconnect.com/watch_pop.aspx?id=IkpHcuEsibNadY0oFaIpdoWePfr5bNqpsJyXaQVTb9rUfC0fG44ORwJiqeYBeJp2wtBtBl5t1rERj2IiHVV4wSFPYX6sdP9%2bgOju%2faGJoRsBJcU0HlcsvYagPQAAvbhO",
		"http://www.ispyconnect.com/external_viewer3.aspx?id=IkpHcuEsibNadY0oFaIpdoWePfr5bNqpsJyXaQVTb9qjR%2bbh9hiXgOzo85KHXxt%2fEtNz7tYeLvC0PbG7803Ow4haM0o43IOrONjyOaI9x0PT8eL9HhT5%2flXJLJFl4NeeIQMepEj4GvHLaK2nTKLX1f%2byA3IpoK8bcRCXk4atEN2gauY69PAXer%2bJUK9%2fDuQA",
		"http://www.ispyconnect.com/external_viewer3.aspx?id=IkpHcuEsibNadY0oFaIpdoWePfr5bNqpsJyXaQVTb9qjR%2bbh9hiXgOzo85KHXxt%2fTFhcyQoxbPlGxa4dhQmDBuFj87PAWzBJ9chKiCYcPoCS4Uw1NVSdde18U8dfjwJdAC6q6iX0j5cjab0Mc12TzoQM9ckf3paMmYGd%2bHRVdG3tPqyONeYkmIaK98Kdtfy4",
		"http://www.ispyconnect.com/external_viewer3.aspx?id=IkpHcuEsibNadY0oFaIpdoWePfr5bNqpsJyXaQVTb9qjR%2bbh9hiXgOzo85KHXxt%2f%2b00o%2frhS4NDkXuENc5HWsz9sFYj7gMPC9FyokwzK5bIuGYw5L5wZw%2f6UPuwr7OK0z%2boiP9K%2fiWEvOg77hVPT8vCV%2b4K7q01t9g%2bQzg6Uu8dTCCxFuGwKFyvPzywpOCsk");
	if($_GET['zone']!=0) {
		$agrzone = $_GET['zone'];
	} 
	if($agrzone==1) {
		$src = $srcs[1];
	} elseif ($agrzone==2) {
		$src = $srcs[2];
	} elseif ($agrzone==3) {
		$src = $srcs[3];
	} else {
		$src = $srcs[0];
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">视频</h3>
	</div>
	<div class="panel-body" style="padding:0px">
		<iframe id="myiframe" src="<?php echo $src; ?>" width="100%" height="600px" frameborder="0"></iframe>
    </div>
 </div>		
<?php	include "footer.php";	?>