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

  if(isset($_POST['create_order'])){
    var_dump($_POST);
  }

?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 下訂單</title>
  <link href="css/style.css" rel="stylesheet">
  <script src="js/addOrder.js"></script>
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
            <h1 class="h3 mb-0 text-gray-800">新增訂單</h1>
          </div>


          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-12 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">訂購&nbsp<?= $rowBuyInfo['store_name'];?></h6>
                </div>

                <div class="card-body">
                  <form method="post" action="<?= $_SERVER['PHP_SELF'];?>" class="user" >
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>名稱</th>
                          <th>價錢</th>
                          <th>數量</th>
                          <th>說明一下</th>
                        </tr>
                      </thead>

                      <tbody>
<?php
  foreach( $arrayProducts as $rowProduct){
    /* 產品清單為類別 */
    if( '{' == substr($rowProduct, 0, 1)){
      
?>
                        <tr>
                          <td colspan="4" class="font-weight-bold text-primary">
                            <!-- 去除類別的{}字元 -->
                            <?= trim($rowProduct, "{}" );?>
                          </td>
                        </tr>
<?php
    }else{
      $rowOthers = preg_split("/[,]+/", $rowProduct);
        $i++; // 產品編號
    ?>
                        <tr>
                          <!-- 陣列中取出array[0]為產品名 -->
                          <td><?= $product = array_shift($rowOthers);?></td>
                          <td>
<?php
      foreach($rowOthers as $other){
        //比對如 " 中杯 40"，比對成功增加一個radio
        if(preg_match('/^(\s)*[(\x7f-\xff)a-zA-Z]+(\s)+[0-9]+/',$other)){
          $arrayOther = preg_split('/[\s]+/', $other);
          $arrayPrice = preg_grep('/[0-9]+/', $arrayOther );
          $price = array_shift($arrayPrice);
          printf("<input type='radio' name='price%d' class='subPrice' value=%d />&nbsp%s&nbsp&nbsp&nbsp",
          $i, $price, $other );
        }else{
          echo $other;
          printf("<input type='hidden' class='price' value='%s'", $other);
        }
      }
?>
                          </td>
                          <td class="td_amount form-inline" data-product-no="<?=$i;?>">
                            <input type="text" class="amount form-control" size="2" readonly />
                            <button type="button" class="plus btn btn-secondary btn-sm ml-2" onclick="plus(<?=$i;?>)">+</button>
                            <button type="button" class="minus btn btn-secondary btn-sm ml-2">-</button>
                            <input type="hidden" class="product" value="<?= $product;?>" />
                            
                          </td>
                          <td>
                            <input type="text" class="form-control" />
                          </td>
                        </tr>
<?php
    }/* end if-else */
  }/* end foreach */
?>
                      </tbody>                    
                    </table>
                    <div>
                        <button type="submit" name="create_order" class="btn btn-danger btn-user">確認訂購</button>
                    </div>

                  </form>
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
