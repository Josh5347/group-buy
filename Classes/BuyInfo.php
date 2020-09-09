<?php
  namespace Classes; ?>

<?php


  class BuyInfo{

    
    public static function getOneByBuyOrderId($order_id){
    
      global $connOO;
  
      $query = sprintf("SELECT buy_info.*, store.store_name FROM buy_info INNER JOIN store ON buy_info.store_no = store.store_no
      WHERE `order_id` = %s", 
      GetSQLValue($order_id, "text"));
  
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
  }

?>