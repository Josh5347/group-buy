<?php
  namespace Classes; ?>

<?php


  class BuyInfo{

    
    public static function getOneByBuyId($buy_id){
    
      global $connOO;
  
      $query = sprintf("SELECT buy_info.*, store.store_name, store.store_tel FROM buy_info INNER JOIN store ON buy_info.store_no = store.store_no
      WHERE `buy_id` = %s", 
      GetSQLValue($buy_id, "text"));
  
      $result = mysqli_query($connOO, $query);
      return $result;
    }
    

    public static function getAllByUsername($username){
    
      global $connOO;

      $query = sprintf("SELECT buy_info.*, store.store_name FROM buy_info INNER JOIN store ON buy_info.store_no = store.store_no
      WHERE `username` = %s ORDER BY `create_date` DESC", 
      GetSQLValue($username, "text"));

      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function updateTotalPaid($buyId, $totalPaid){

      global $connOO;

      $query = sprintf("UPDATE buy_info SET `total_paid` = %d  WHERE `buy_id` = %d",
      GetSQLValue((int)$totalPaid, "int"),
      GetSQLValue((int)$buyId, "int")
      );
      
      $result = mysqli_query($connOO, $query);
      return $result;
    }
    
    public static function updateCancelOrder($buyId, $total_paid, $amountOrderer, $sum){

      global $connOO;
      $datetime = date("Y-m-d H:i:s");

      $query = sprintf("UPDATE buy_info SET 
      `total_paid` = %d , `amount` = %d, `sum` = %d, `update_date` = %s
      WHERE `buy_id` = %d",
      GetSQLValue((int)$total_paid, "int"),
      GetSQLValue((int)$amountOrderer, "int"),
      GetSQLValue((int)$sum, "int"),
      GetSQLValue(date("Y-m-d H:i:s"), "text"),
      GetSQLValue((int)$buyId, "int")
      );
      
      $result = mysqli_query($connOO, $query);
      return $result;
      
    }

    public static function getAll($buyId){
      global $connOO;

      $resultBuyInfo = self::getOneByBuyId($buyId);
      if (!$resultBuyInfo){
        exit("查詢團購資訊失敗 :" .$connOO->error);
      }
      return $resultBuyInfo->fetch_assoc();

    }
  }
?>