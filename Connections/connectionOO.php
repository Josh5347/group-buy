<?php



$connOO = new mysqli('localhost', '3598353_groupbuy', 'a531564531564', '3598353_groupbuy');
// $connOO = new mysqli('fdb27.runhosting.com', '3598353_groupbuy', 'a531564531564', '3598353_groupbuy');

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

