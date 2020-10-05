<?php

include 'Classes/DbParm.php';
use Classes\DbParm;

// 建立 MySQL 資料庫的連線
// $conn = mysqli_connect(DbParm::DB_HOST, DbParm::DB_USERNAME, DbParm::DB_PASSWORD) or
$conn = mysqli_connect('localhost', '3598353_groupbuy', 'a531564531564') or
// $conn = mysqli_connect('fdb27.runhosting.com', '3598353_groupbuy', 'a531564531564') or
  die("資料庫連結失敗: " . mysqli_connect_error());

// 設定在用戶端使用UTF-8的字元集
mysqli_set_charset($conn,'utf8');

mysqli_select_db($conn, '3598353_groupbuy') or 
  die(trigger_error(mysqli_error($conn), E_USER_ERROR));	  

?>