<?php require_once '../Classes/DbParm.php'; ?>
<?php require_once '../Connections/connectionOO.php'; ?>
<?php require_once '../Connections/function.php'; ?>
<?php require_once '../Classes/Functions.php';?>
<?php require_once '../Classes/OrderInfo.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\OrderInfo; ?>
<?php

    function sqlQuery(){
      global $connOO ,$result;
      
      if($_REQUEST['updateFlag']){
        // 日期更新為今日
        if(OrderInfo::updateShippingDate(
          $_REQUEST['buyId'],
          $_REQUEST['orderId'],
          $_REQUEST['orderSn'],
          date("Y-m-d"))){

          return true;
        }else{
          trigger_error(mysqli_error($connOO), E_USER_ERROR);
        }        
      }else{
        // 日期更新為空白
        if(OrderInfo::updateShippingDate(
          $_REQUEST['buyId'],
          $_REQUEST['orderId'],
          $_REQUEST['orderSn'],
          "")){

          return true;
        }else{
          trigger_error(mysqli_error($connOO), E_USER_ERROR);
        }        
      }
    }    

    function outputJSON(){

    }
    /***************************************************************/
    /*                          主程式                              */
    /***************************************************************/
    
    // 建立 session
    if (!isset($_SESSION)) {
        session_start();
    }

    $result = '';

    $returnOBJ = new StdClass();
    

    if(sqlQuery()){
      $returnOBJ->status = true;
      echo json_encode($returnOBJ);
    }else{
      $returnOBJ->status = false;
      echo json_encode($returnOBJ);
    }

?>