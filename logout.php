<?php
//注销登录
session_start();
$_SESSION['agruser']="";
$_SESSION['agrzone']="";
$_SESSION['agrrole']="";
echo "success";
?>