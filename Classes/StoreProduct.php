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

  }

?>