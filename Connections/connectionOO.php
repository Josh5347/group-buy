<?php
  $mysqli = new mysqli('localhost', 'group_buy', 'group_buy', 'group_buy');

  /* check connection */
  if (mysqli_connect_errno()) {
    printf("資料庫連結失敗: %s\n", mysqli_connect_error());
    exit();
  }

  if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
    exit();
  }

?>

