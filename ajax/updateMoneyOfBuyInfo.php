<?php require_once '../common/ajaxHeader.php'; ?>
<?php require_once '../Connections/connectionOO.php'; ?>
<?php require_once '../Connections/function.php'; ?>
<?php require_once '../Classes/BuyInfo.php';?>
<?php require_once '../Classes/OrderInfo.php';?>

<?php use Classes\BuyInfo; ?>
<?php use Classes\OrderInfo; ?>

<?php

    function sqlQuery(){

      global $connOO, $result, $amountOrderer;

      $result = BuyInfo::getOneByBuyId($_REQUEST['buyId']);
      if (!$result){
        exit("查詢團購資訊失敗 :" .$connOO->error);
      }
      $buyInfo = $result->fetch_assoc();

      $result2 = OrderInfo::countOrderIdNum($_REQUEST['buyId']);

      $amountOrderer = $result2->num_rows;

      $sum = $buyInfo['sum'] - $_REQUEST['minusPrice'];

      // 若有付款:已付總金額 = 已付總金額 - 取消訂單的金額; 若無付款:已付總金額 = 已付總金額
      $total_paid = ($_REQUEST['paidFlg']=='true')? $buyInfo['total_paid'] - $_REQUEST['minusPrice']: $buyInfo['total_paid'];

      if(BuyInfo::updateCancelOrder(
        $_REQUEST['buyId'],
        $total_paid,
        $amountOrderer,
        $sum
        )){
        
        return $amountOrderer;
      }else{
        trigger_error(mysqli_error($connOO), E_USER_ERROR);
      }

    }    



    function outputJSON(){

    }
    /***************************************************************/
    /*                          主程式                              */
    /***************************************************************/
    


    $result = '';
    $amountOrderer = 0;
    $returnOBJ = new StdClass();
    

    if(sqlQuery()){
      $returnOBJ->status = true;
      $returnOBJ->amount = $amountOrderer;
      echo json_encode($returnOBJ);
    }else{
      $returnOBJ->status = false;
      echo json_encode($returnOBJ);
    }

?>