<?php

require_once 'Classes/DbParm.php';
use Classes\DbParm;

$connOO = new mysqli(DbParm::DB_HOST, DbParm::DB_USERNAME, DbParm::DB_PASSWORD, DbParm::DB_DATABASE);

/* check connection */
if (mysqli_connect_errno()) {
  printf("資料庫連結失敗: %s\n", mysqli_connect_error());
  exit();
}

if (!$connOO->set_charset("utf8")) {
  printf("Error loading character set utf8: %s\n", $mysqli->error);
  exit();
}

?>

