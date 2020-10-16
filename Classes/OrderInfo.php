<?php
  namespace Classes; ?>

<?php


  class OrderInfo{

  
    public static function getAllSortByProductNoByOrderer($buyId){
    
      global $connOO;

      $query = sprintf("SELECT * FROM order_info 
      WHERE `buy_id` = %d ORDER BY `product_no` ASC , `orderer` ASC", 
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

    public static function getAllByBuyIdByProductNo($buyId, $ProductNo){

      global $connOO;

      $query = sprintf("SELECT * FROM order_info 
      WHERE `buy_id` = %d AND `product_no` = %s ", 
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue( $ProductNo, "text")
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
  
      if (!$result){
        exit("查詢團購資訊失敗 :" .$connOO->error);
      }else{
        // 傳回查詢陣列
        $row = $result->fetch_assoc();
        return (isset($row['paid_sum']))? $row['paid_sum']: 0;
        
      }
    }

    public static function getPriceRowSumByOrderer($buyId, $orderer){

      global $connOO;

      $query = sprintf("SELECT orderer, count(*) AS `price_amount`, sum(`price`) AS `price_sum` FROM order_info 
      WHERE `buy_id` = %d AND `orderer` = %s GROUP BY `orderer`", 
      GetSQLValue((int)$buyId, "int"),
      GetSQLValue($orderer, "text")
      );

      $result = mysqli_query($connOO, $query);
      if (!$result){
        exit("查詢團購資訊失敗 :" .$connOO->error);
      }else{
        // 傳回查詢陣列
        return $result->fetch_assoc();
      }
    }




    public static function getOrderInfoSortByAmount($buyId){
      global $connOO;
      $arrayOrders = [];
      $prevProduct = '';

      $resultOrderByAmount = self::getAllSortByProductNoByOrderer($buyId);
      if (!$resultOrderByAmount){
        exit("查詢訂單資訊失敗 :" .$connOO->error);
      }
      
      // 將資料庫內容送進陣列中
      while( $row = $resultOrderByAmount->fetch_assoc()){
  
        // 取得每一產品的已付金額
        $result1 = self::getPaidByBuyIdByProduct($buyId, $row['product_no']);
        if (!$result1){
          exit("查詢團購資訊失敗 :" .$connOO->error);
        }
        $row['paid_row_sum']= $result1->num_rows;

        // 取得每一產品的總數量
        $result2 = self::getAllByBuyIdByProductNo($buyId, $row['product_no']);
        if (!$result2){
          exit("查詢團購資訊失敗 :" .$connOO->error);
        }
        $row['amount'] = $result2->num_rows;
  
        array_push($arrayOrders, $row);
        
      }
  
      return $arrayOrders;
    }

    public static function getOrderInfoSortByOrderer($buy_id){
      
      global $connOO;
      $prevOrderer = '';
      $arrayOrders = [];
     
      $resultOrderByOrderer = self::getAllSortByOrderer($buy_id);
      if (!$resultOrderByOrderer){
        exit("查詢訂單資訊失敗 :" .$connOO->error);
      }
  
      // 將資料庫內容送進陣列中
      while( $row = $resultOrderByOrderer->fetch_assoc()){

        // 取得每一訂購人的已付金額
        $row['paid_row_sum'] = 
          self::getPaidRowSumByOrderer($row['buy_id'], $row['orderer']);
        // 取得每一訂購人的總金額
        $result =
          self::getPriceRowSumByOrderer($row['buy_id'], $row['orderer']);
        $row['price_row_sum'] = $result['price_sum'];
        // 取得每一訂購人的未付金額
        $row['unpaid_row_sum'] = $row['price_row_sum'] - $row['paid_row_sum'];
        // 取得每一訂購人的數量
        $row['amount'] = $result['price_amount'];
        // 付清
        $row['paid_all'] = ($row['price_row_sum']==$row['paid_row_sum'])? "付清": "";

        array_push($arrayOrders, $row);
      }

    return $arrayOrders;

  }

  public static function countOrderIdNum($buyId){
    global $connOO;

    $query = sprintf("SELECT order_id FROM order_info 
    WHERE `buy_id` = %d GROUP BY `order_id`", 
    GetSQLValue((int)$buyId, "int")
    );

    $result = mysqli_query($connOO, $query);
    if (!$result){
      exit("查詢訂單數目失敗 :" .$connOO->error);
    }
    return $result;

  }

  public static function updateOrderInfo(){
    global $connOO;

    // 將輸入 以 ";" 分割
    $arrayInput = preg_split('/[;]+/', $_REQUEST['product_info']);       

    if(!self::updatePriceProduct(
      $_REQUEST['buy_id'],
      $_REQUEST['order_id'],
      $_REQUEST['order_sn'],
      $arrayInput[0],
      $arrayInput[1],
      $arrayInput[2],
      $_REQUEST['explanation']
      )){
      trigger_error(mysqli_error($connOO), E_USER_ERROR);
    }    

  }
  

}

?>