<?php require_once '../Classes/DbParm.php'; ?>
<?php require_once '../Connections/connectionOO.php'; ?>
<?php require_once '../Connections/function.php'; ?>
<?php require_once '../Classes/BuyInfo.php';?>

<?php use Classes\BuyInfo; ?>

<?php

    function sqlQuery(){

      if (updateBuyInfo()){
        return true;
      }else{
        return false;
      }

    }    

    function updateBuyInfo(){

      global $connOO, $result;

      if(BuyInfo::updateTotalPaid(
        $_REQUEST['buyId'],
        $_REQUEST['totalPaid'])){
        
        return true;
      }else{
        trigger_error(mysqli_error($connOO), E_USER_ERROR);
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