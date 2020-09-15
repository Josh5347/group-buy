<?php
  if(!isset($_SESSION)){
    session_start();
  }

  if(!isset($_SESSION['username'])){
    $StringExplo=explode("/",$_SERVER['REQUEST_URI']);
    $HeadTo=$StringExplo[0].'/index.php';
    Header("Location: ". $HeadTo );
  }
?>