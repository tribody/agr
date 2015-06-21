1：修复手机端load慢的bug,在footer中加了document.ready()的判断
2: 新增js/data.js 尽量将chart 中的js代码放入其中，减少冗余
3: todolist, chart.php中有大量的jquery语句，应该放入js/data.js中。---还未完成

4. 重要修改，在userlist_server中修改了$row['id'] 而不是原先的$row[id]
原先遇到的问题是点击，只有最后一行才能修改，其他行无法修改。

5. 修改, 取消用户列表中的添加按钮
6. todolist, 当角色是用户时候，无法点击 申请管理界面
7. 修改,修改密码那个页面修改样式  
8. todolist, index.php 仍然有cdn方式的样式，需要删除
9.		            <li class="divider"></li>
		            <li><a href="#">Separated link</a></li> 
		            作用是干什么的不知道， 原位置在用户管理Ul下面


knowledge set.
系统日志：

刷新当前页面的方法：
window.location.reload();
刷新jqgrid的方法：
 $('#list2').trigger("reloadGrid");

mysql_num_rows() 函数用来表示查询出来的数据是否为空（值为0的时候）
mysql_affected_rows() 函数返回前一次 MySQL 操作所影响的记录行数。


2015-05-26
改动：
1、系统改为了中文显示模式，替换了中式英语部分。
2、用户列表错位，已修复。
3、添加了联系页面和关于页面。

问题：
1、嵌入iframe的英文替换还未解决。
2、登陆框表单填写完毕后焦点不能对焦到登录按钮上。

2015-05-27
改动：
1、更改数据库shipin_message为video_message，去掉视频类型。
2、在地图拓扑结构中添加了视频链接。

2015-5-30
改动：
1、将传感器类型和传感器编号级联列表位置颠倒过来了；
2、将chart.php中的绘图js代码抽取出来了；

新增：
1、在百度地图中新增了视频查看功能，并做了权限检查。
2、md5加密。

问题：
外网映射问题还未得到解决，公网访问演示做不了。

2015-05-31
改动：
将数据模拟发送器改为标准bootstrap样式。