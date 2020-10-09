<?php
  if(!isset($_SESSION)){
    session_start();
  }
  
  date_default_timezone_set("Asia/Taipei");
  
  if(!isset($_SESSION['username'])){
    $StringExplo=explode("/",$_SERVER['REQUEST_URI']);
    $HeadTo=$StringExplo[0].'/index.php';
    // Header("Location:". $HeadTo );
    echo '<script> location.replace($HeadTo); </script>';
    
  }
?>