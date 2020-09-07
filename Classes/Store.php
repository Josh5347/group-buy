<?php
  namespace Classes; ?>

<?php


  class Store{

    
    public static function getOneByStoreName($store_name){
    
      global $connOO;
  
      $query = sprintf("SELECT * FROM store WHERE `store_name` = %s", 
      GetSQLValue($store_name, "text"));
  
      $result = mysqli_query($connOO, $query);
      return $result;
    }
    
    public static function getOneByStoreNo($store_no){
    
      global $connOO;
  
      $query = sprintf("SELECT * FROM store WHERE `store_no` = %s", 
      GetSQLValue($store_no, "text"));
  
      $result = mysqli_query($connOO, $query);
      return $result;
    }
    public static function getAllByUsername($create_username){
    
      global $connOO;

      $query = sprintf("SELECT * FROM store WHERE `create_username` = %s ORDER BY `create_date` DESC", 
      GetSQLValue($create_username, "text"));

      $result = mysqli_query($connOO, $query);
      return $result;
    }
  }

?>