	 </div>
	</div>
   </div>
  </div>

</body>
	<!--实现nav-bar和side-bar的响应式布局  -->
	<script type="text/javascript">
	//当浏览器视口发生变化时触发
	$(window).resize(function() {
		if($(this).width() < 768) {
			$(".sidebar").hide();
			$(".mobile").show();
		} else {
			$(".sidebar").show();
			$(".mobile").hide();
		}
	});
	//当内容加载完成时触发
	$(window).load(function() {
		if($(this).width() < 768) {
			$(".sidebar").hide();
			$(".mobile").show();
		} else {
			$(".sidebar").show();
			$(".mobile").hide();
		}
	});
	//当Dom文件加载完成时触发
	$(document).ready(function() {
		if($(window).width() < 768) {
			$(".sidebar").hide();
			$(".mobile").show();
		} else {
			$(".sidebar").show();
			$(".mobile").hide();
		}
	});
	</script>
</html>
<?php 
	mysql_close(); //关闭
?>
