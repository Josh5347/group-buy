<?php


  namespace Classes;


  class AccountPassword{

    //檢查帳密
    public static function check(){

      $query = sprintf("SELECT * form account WHERE username = %s",
        GetSQLValue($_REQUEST['account'], "text"));
      
      $result = mysqli_query($conn, $query);	

      if($result){
        mysqli_fetch
      }

    }
  }

?>