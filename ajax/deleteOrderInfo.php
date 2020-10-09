<?php require_once '../common/ajaxHeader.php'; ?>
<?php require_once '../Connections/connectionOO.php'; ?>
<?php require_once '../Connections/function.php'; ?>
<?php require_once '../Classes/OrderInfo.php';?>

<?php use Classes\OrderInfo; ?>

<?php

    function sqlQuery(){

      global $connOO;

      if(!OrderInfo::deleteOrderInfo(
        $_REQUEST['buyId'],
        $_REQUEST['orderId'],
        $_REQUEST['orderSn']
        )){
        trigger_error(mysqli_error($connOO), E_USER_ERROR);
      }else{
        return true;
      } 
   

    }    


    function outputJSON(){

    }
    /***************************************************************/
    /*                          主程式                              */
    /***************************************************************/
    


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