<?php
  namespace Classes; ?>

<?php


  class OrderInfo{

  
    public static function getAllSortByAmount($buyId){
    
      global $connOO;

      $query = sprintf("SELECT * FROM order_info 
      WHERE `buy_id` = %d ORDER BY `product_no` ASC", 
      GetSQLValue((int)$buyId, "int"));

      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function updatePaidByOrderSn($buyId, $orderId, $orderSn, $paid){
    
      global $connOO;

      $query = sprintf("UPDATE order_info SET `paid` = %d 
      WHERE `buy_id` = %d AND `order_id` = %d AND `order_sn` = %d",
      GetSQLValue((int)$paid, "int"),
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue((int)$orderId, "int"),
      GetSQLValue((int)$orderSn, "int")

      );

      $result = mysqli_query($connOO, $query);
      return $result;
    }
  }

?>