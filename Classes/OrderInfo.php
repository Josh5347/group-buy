<?php
  namespace Classes; ?>

<?php


  class OrderInfo{

  
    public static function getAllSortByAmount($buyId){
    
      global $connOO;

      $query = sprintf("SELECT * FROM order_info 
      WHERE `buy_id` = %d ORDER BY `product` DESC", 
      GetSQLValue((int)$buyId, "int"));

      $result = mysqli_query($connOO, $query);
      return $result;
    }
  }

?>