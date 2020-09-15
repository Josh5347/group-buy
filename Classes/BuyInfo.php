<?php
  namespace Classes; ?>

<?php


  class BuyInfo{

    
    public static function getOneByBuyId($buy_id){
    
      global $connOO;
  
      $query = sprintf("SELECT buy_info.*, store.store_name FROM buy_info INNER JOIN store ON buy_info.store_no = store.store_no
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
  }

?>