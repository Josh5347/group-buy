<?php
// 建立 MySQL 資料庫的連線
$connection = mysqli_connect('localhost', 'group_buy', 'group_buy') or 
	trigger_error(mysqli_error(), E_USER_ERROR);
// 設定在用戶端使用UTF-8的字元集
mysqli_set_charset($connection,'utf8');

mysqli_select_db($connection, 'group_buy') or die('資料庫group_buy不存在');	  

?>