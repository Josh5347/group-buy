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
  
    public static function getAllSortByOrderer($buyId){
    
      global $connOO;

      $query = sprintf("SELECT * FROM order_info WHERE `buy_id` = %d ORDER BY `orderer` ASC, `product_no` ASC", 
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
    
    public static function updatePriceProduct( $buyId, $orderId, $orderSn, $product, $productNo, $price, $explanation){
    
      global $connOO;

      $query = sprintf("UPDATE order_info 
      SET `price` = %d , `product`= %s , `product_no`= %s , `explanation` = %s
      WHERE `buy_id` = %d AND `order_id` = %d AND `order_sn` = %d",
      GetSQLValue((int)$price, "int"),
      GetSQLValue($product, "text"),
      GetSQLValue($productNo, "text"),
      GetSQLValue($explanation, "text"),
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue((int)$orderId, "int"),
      GetSQLValue((int)$orderSn, "int")
      );

      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function updateShippingDate( $buyId, $orderId, $orderSn, $shippingDate){
    
      global $connOO;

      $query = sprintf("UPDATE order_info 
      SET `shipping_date` = %s 
      WHERE `buy_id` = %d AND `order_id` = %d AND `order_sn` = %d",
      GetSQLValue($shippingDate, "text"),
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue((int)$orderId, "int"),
      GetSQLValue((int)$orderSn, "int")
      );

      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function deleteOrderInfo($buyId, $orderId, $orderSn){
    
      global $connOO;

      $query = sprintf("DELETE FROM order_info 
      WHERE `buy_id` = %d AND `order_id` = %d AND `order_sn` = %d",
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue((int)$orderId, "int"),
      GetSQLValue((int)$orderSn, "int")
      );  

      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function getPaidByBuyIdByProduct($buyId, $ProductNo){

      global $connOO;

      $query = sprintf("SELECT * FROM order_info 
      WHERE `buy_id` = %d AND `product_no` = %s AND `paid` = 1", 
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue($ProductNo, "text")
      );

      $result = mysqli_query($connOO, $query);
      return $result;

    }

    public static function getPaidRowSumByOrderer($buyId, $orderer){

      global $connOO;

      $query = sprintf("SELECT orderer, count(*) AS `paid_amount`, sum(`price`) AS `paid_sum` FROM order_info 
      WHERE `buy_id` = %d AND `orderer` = %s AND `paid` = 1 GROUP BY `orderer`", 
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue($orderer, "text")
      );

      $result = mysqli_query($connOO, $query);
      return $result;

    }

    public static function getPriceRowSumByOrderer($buyId, $orderer){

      global $connOO;

      $query = sprintf("SELECT orderer, count(*) AS `price_amount`, sum(`price`) AS `price_sum` FROM order_info 
      WHERE `buy_id` = %d AND `orderer` = %s GROUP BY `orderer`", 
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue($orderer, "text")
      );

      $result = mysqli_query($connOO, $query);
      return $result;

    }
  }

?>