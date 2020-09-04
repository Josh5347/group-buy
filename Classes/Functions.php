<?php
  namespace Classes; ?>

<?php


  class Functions{

    
    public static function redirect($address){

      $StringExplo=explode("/",$_SERVER['REQUEST_URI']);
      $HeadTo=$StringExplo[0].$address;

      return $HeadTo;
    }
  }

?>