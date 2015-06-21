<?php
	//连接数据库
	mysql_connect("localhost", "root", "") or die("连接失败!");

	//选择数据库
	mysql_select_db("agriculture") or die("数据库选择失败");
	mysql_query("set names 'UTF8'");

	
