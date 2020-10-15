<?php
  namespace Classes; ?>


<?php

class Collapse{
  public static $showAmount = '';
  public static $showUpdate = '';
  public static $showDelete = '';      
  public static $showShipping = '';

  public static function showCollapse($collapseName){
    switch ($collapseName) {
      case 'update':
        self::$showAmount = '';
        self::$showUpdate = 'show';
        self::$showDelete = '';      
        self::$showShipping = '';      
        break;
      case 'delete':
        self::$showAmount = '';
        self::$showUpdate = '';
        self::$showDelete = ' show';      
        self::$showShipping = '';      
        break;
      
      case 'shipping':
        self::$showAmount = '';
        self::$showUpdate = '';
        self::$showDelete = '';      
        self::$showShipping = ' show';      
        break;
      
      case 'default':
        self::$showAmount = ' show';
        self::$showUpdate = '';
        self::$showDelete = '';      
        self::$showShipping = '';      
        break;
      
      
      default:
        # code...
        break;
    }
  }
}
?>
