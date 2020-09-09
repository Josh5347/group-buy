<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/BuyInfo.php';?>
<?php require_once 'Classes/StoreProduct.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\BuyInfo; ?>
<?php use Classes\StoreProduct; ?>

<?php
  function showOrder(){
    global $rowBuyInfo, $arrayProducts;

    $resultBuyInfo = getBuyInfo();
    $rowBuyInfo = $resultBuyInfo->fetch_assoc();

    $resultStoreProduct = getStoreProduct($rowBuyInfo['store_no']);
    $rowStoreProduct = $resultStoreProduct->fetch_assoc();

    $arrayProducts = preg_split('/\r\n/',$rowStoreProduct['product_list']);

  }

  function getBuyInfo(){
    global $connOO;

    $result = BuyInfo::getOneByBuyOrderId($_GET['order_id']);
    if (!$result){
      exit("查詢團購資訊失敗 :" .$connOO->error);
    }else{
      return $result;
    }

  }

  function getStoreProduct($store_no){
    global $connOO;

    $result = StoreProduct::getOneByStoreNo($store_no);
    if (!$result){
      exit("查詢產品清單失敗 :" .$connOO->error);
    }else{
      return $result;
    }
  }

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $i = 0;
  $rowBuyInfo = [];
  $arrayProducts = [];

  if(isset($_GET['order_id'])){
    showOrder();
  }

?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 下訂單</title>
  <link href="css/style.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php require_once 'common/sideBar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

<?php require_once 'common/topBar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">進行中的訂單</h1>
          </div>


          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-12 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">訂購&nbsp<?= $rowBuyInfo['store_name'];?></h6>
                </div>

                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>名稱</th>
                        <th>價錢</th>
                        <th>數量</th>
                        <th>說明</th>
                      </tr>
                    </thead>

                    <tbody>
<?php
  foreach( $arrayProducts as $product){
    /* 產品清單為類別 */
    if( '{' == substr($product, 0, 1)){
     
?>
                      <tr>
                        <td colspan="4">
                          <!-- 去除類別的{}字元 -->
                          <?= trim($product, "{}" );?>
                        </td>
                      </tr>
<?php
    }else{
      $others = preg_split("/[,]+/", $product);
    ?>
                      <tr>
                        <td><?= array_shift($others);?></td>
                        <td class="form-inline">
<?php
      foreach($others as $other){
        if(preg_match('/[0-9]+/',$other)){
          echo $other;
        }else{
          echo "<input type='radio' />&nbsp". $other;
        }
      }
?>
                        </td>
                        <td></td>
                        <td></td>
                      </tr>
<?php
    }/* end if-else */
  }/* end foreach */
?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>




        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


<!--  copy right 及登出模組 -->
<?php require_once 'common/htmlFooter.php'; ?>


</body>

</html>
