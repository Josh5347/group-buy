<?php
  namespace Classes; ?>

<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php


  class AccountPassword{

    //檢查帳密
    public static function check($connOO){

      $sql = sprintf("SELECT * from account WHERE username = %s",
        GetSQLValue($_REQUEST['account'], "text"));
      
      $result = $connOO->query($sql);	
      $rowCount = $result->num_rows;
      //資料庫有找到使用者帳號資料
      if( $result &&
          $rowCount > 0){
        $row = $result->fetch_assoc();
        if( password_verify( $_REQUEST['pwd'], $row['login_password'])){
          
          return true;
        }else{
          return "密碼不符";
        }
      }else{
        return "無此帳號";
      }

    }
  }

?>