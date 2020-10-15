<?php
  namespace Classes; ?>

<?php


  class StoreProduct{
  

    public static function getOneByStoreNo($store_no){
    
      global $connOO;
  
      $query = sprintf("SELECT * FROM store_product WHERE `store_no` = %s", 
      GetSQLValue($store_no, "text"));
  
      $result = mysqli_query($connOO, $query);
      return $result;
    }

    public static function getProductArray($store_no){
      global $connOO;
  
      $resultStoreProduct = self::getOneByStoreNo($store_no);
      if (!$resultStoreProduct){
        exit("查詢產品清單失敗 :" .$connOO->error);
      }
      $rowStoreProduct = $resultStoreProduct->fetch_assoc();
  
      // 將產品清單轉換成每一行每一行的陣列
      $arrayProducts = preg_split('/\r\n/',$rowStoreProduct['product_list']);
  
      return $arrayProducts;
    }

  }

?>