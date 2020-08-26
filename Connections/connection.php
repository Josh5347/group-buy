<?php
// 建立 MySQL 資料庫的連線
$conn = mysqli_connect('localhost', 'group_buy', 'group_buy') or
  die("資料庫連結失敗: " . mysqli_connect_error());

// 設定在用戶端使用UTF-8的字元集
mysqli_set_charset($conn,'utf8');

mysqli_select_db($conn, 'group_buy') or 
  die(trigger_error(mysqli_error($conn), E_USER_ERROR));	  

?>