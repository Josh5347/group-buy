<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/Store.php';?>
<?php require_once 'Classes/StoreProduct.php';?>
<?php require_once 'Classes/BuyInfo.php';?>
<?php require_once 'Classes/OrderInfo.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\Store; ?>
<?php use Classes\StoreProduct; ?>
<?php use Classes\BuyInfo; ?>
<?php use Classes\OrderInfo; ?>

<?php
  function checkPaid($paid){
    return ($paid)? "paid-color": "unpaid-color";
  }

  function showInfo(){
    global $buyInfo;

    $resultBuyInfo = getBuyInfo();
    $buyInfo = $resultBuyInfo->fetch_assoc();


  }

  function getBuyInfo(){
    global $connOO;

    $result = BuyInfo::getOneByBuyId($_GET['buy_id']);
    if (!$result){
      exit("查詢團購資訊失敗 :" .$connOO->error);
    }else{
      return $result;
    }

  }

  function getOrderInfoSortByAmount(){
    global $connOO, $numOfProduct, $resultOrderByAmount;
    $arrayOrders = [];
    $prevProduct = '';

    $resultOrderByAmount = OrderInfo::getAllSortByAmount($_GET['buy_id']);
    if (!$resultOrderByAmount){
      exit("查詢訂單資訊失敗 :" .$connOO->error);
    }

    $i = 1;
    // 將資料庫內容送進陣列中
    while( $row = $resultOrderByAmount->fetch_assoc()){

      if($row['product'] != $prevProduct){
        $prevProduct = $row['product'];
        $i = 1;
        $row['amount'] = $i;
        // 取得每一產品的已付數

        $paidRowSum = 
          $row['paid_row_sum'] = 
          getPaidRowSumByProductNo($row['buy_id'], $row['product_no']);
      }else{
        $i++;
        $row['amount'] = $i;
        $row['paid_row_sum'] = $paidRowSum;
      }
      array_push($arrayOrders, $row);
      $numOfProduct++;
    }

    // 以 product_no為key1 升冪 ,amount為key2排序 降冪
    usort($arrayOrders, function($a, $b){
      if($a['product_no'] == $b['product_no']){
        if($a['amount'] == $b['amount']){
          if($a['orderer'] == $b['orderer']){
            return 0;
          }elseif($a['orderer'] > $b['orderer']){
            return 1;
          }else{
            return -1;
          }
        }elseif($a['amount'] > $b['amount']){
          return -1;
        }else{
          return 1;
        }
      }else if($a['product_no'] > $b['product_no']){
        return 1;
      }else{
        return -1;
      }
    });

    return $arrayOrders;

  }

  function getPaidRowSumByProductNo($buyId, $ProductNo){
    global $connOO;

    $result = OrderInfo::getPaidByBuyIdByProduct($buyId, $ProductNo);
    if (!$result){
      exit("查詢團購資訊失敗 :" .$connOO->error);
    }else{
      // 傳回查詢列數
      return $result->num_rows;
    }

  }

  function getOrderInfoSortByOrderer(){
    global $connOO;
    $prevOrderer = '';
    $arrayOrders = [];
   
    $resultOrderByOrderer = OrderInfo::getAllSortByOrderer($_GET['buy_id']);
    if (!$resultOrderByOrderer){
      exit("查詢訂單資訊失敗 :" .$connOO->error);
    }
    
    // 將資料庫內容送進陣列中
    while( $row = $resultOrderByOrderer->fetch_assoc()){

      // 取得每一訂購人的已付金額
      $row['paid_row_sum'] = 
        getPaidRowSumByOrderer($row['buy_id'], $row['orderer']);
      // 取得每一訂購人的總金額
      $result =
        getPriceRowSumByOrderer($row['buy_id'], $row['orderer']);
      $row['price_row_sum'] = $result['price_sum'];
      // 取得每一訂購人的未付金額
      $row['unpaid_row_sum'] = $row['price_row_sum'] - $row['paid_row_sum'];
      // 取得每一訂購人的數量
      $row['amount'] = $result['price_amount'];
      
      array_push($arrayOrders, $row);
    }

    return $arrayOrders;

  }

  function getPaidRowSumByOrderer($buyId, $orderer){
    global $connOO;

    $result = OrderInfo::getPaidRowSumByOrderer($buyId, $orderer);
    if (!$result){
      exit("查詢團購資訊失敗 :" .$connOO->error);
    }else{
      // 傳回查詢陣列
      $row = $result->fetch_assoc();
      return (isset($row['paid_sum']))? $row['paid_sum']: 0;
      
    }
  }

  function getPriceRowSumByOrderer($buyId, $orderer){
    global $connOO;

    $result = OrderInfo::getPriceRowSumByOrderer($buyId, $orderer);
    if (!$result){
      exit("查詢團購資訊失敗 :" .$connOO->error);
    }else{
      // 傳回查詢陣列
      return $result->fetch_assoc();
    }
  }

  function getOrderInfoShowExplanation(){
    global $resultOrderByAmount;
    $arrayOrders = [];
    $prevProduct = '';

    $resultOrderByAmount->data_seek(0);
        // 將資料庫內容送進陣列中
    while( $row = $resultOrderByAmount->fetch_assoc()){
      array_push($arrayOrders, $row);
    }

    // 以 product_no為key1 升冪 ,orderer為key2排序 升冪
    usort($arrayOrders, function($a, $b){
      if($a['product_no'] == $b['product_no']){
        if($a['orderer'] == $b['orderer']){
          return 0;
        }elseif($a['orderer'] > $b['orderer']){
          return 1;
        }else{
          return -1;
        }
      }else if($a['product_no'] > $b['product_no']){
        return 1;
      }else{
        return -1;
      }
    });

    return $arrayOrders;

  }

  function showOrder(){
    global $buyInfo;

    $resultStoreProduct = getStoreProduct($buyInfo['store_no']);
    $rowStoreProduct = $resultStoreProduct->fetch_assoc();

    // 將產品清單轉換成每一行每一行的陣列
    $arrayProducts = preg_split('/\r\n/',$rowStoreProduct['product_list']);

    return $arrayProducts;
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

  function updateOrderInfo(){
    global $connOO;

    // 將輸入 以 ";" 分割
    $arrayInput = preg_split('/[;]+/', $_REQUEST['product_info']);       

    if(!OrderInfo::updatePriceProduct(
      $_REQUEST['buy_id'],
      $_REQUEST['order_id'],
      $_REQUEST['order_sn'],
      $arrayInput[0],
      $arrayInput[1],
      $arrayInput[2],
      $_REQUEST['explanation']
      )){
      trigger_error(mysqli_error($connOO), E_USER_ERROR);
    }    

  }

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $buyInfo = [];
  $ordersByAmount = '';
  $numOfProduct = 0; // 份數
  $resultOrderByAmount; //全域變數
  $arrayProducts = [];
  $i = 0;

  if(isset($_REQUEST['update'])){
    updateOrderInfo();
  }

  if(isset($_REQUEST['buy_id'])){
    showInfo();
    $ordersByAmount = getOrderInfoSortByAmount();// 按件計算
    $ordersByOrderer = getOrderInfoSortByOrderer(); // 按人計算
    $ordersExplan = getOrderInfoShowExplanation();// 老闆我要訂
    $arrayProducts = showOrder();// 修改訂單
  }


?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>管理訂單</title>
  <link href="css/style.css" rel="stylesheet">
  <link href="css/managerOrder.css" rel="stylesheet">
  <script src="js/managerOrder.js"></script>
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
            <h1 class="h3 mb-0 text-gray-800">管理訂單：<?= $buyInfo['in_charge_name'];?>的 <?= $buyInfo['store_name'];?></h1>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->
            <div class="col-lg-3">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">金額計算</h6>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <span>共 <?= $numOfProduct;?> 份</span>
                    <hr>
                    <table class="table table-borderless text-right">
                      <tbody>
                        <tr>
                          <td class="pr-5">已付</td>
                          <td class="pr-5 total-paid"><?= $buyInfo['total_paid']; ?></td>
                        </tr>
                        <tr>
                          <td class="pr-5">剩下</td>
                          <td class="pr-5 total-unpaid"><?= $buyInfo['sum'] - $buyInfo['total_paid']; ?></td>
                        </tr>
                        <tr>
                          <td class="pr-5 text-danger"><h5>總價</h5></td>
                          <td class="pr-5 text-danger sum"><h5><?= $buyInfo['sum']; ?></h5></td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
      
                </div>
              </div>

              
            </div>


            <!-- Content Column -->
            <div class="col-lg-3">

              <!-- Project Card Example -->
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">進度設定</h6>
                </div>
                <div class="card-body">
                  <ul class="ml-n3">
                    <li class="mb-2">截止時間: 
                    <?= ($buyInfo['expired_time'] == '')? '沒有': substr($buyInfo['expired_time'], 0, 5); ?>
                    </li>
                    <li class="mb-2">截止數量: 沒有</li>
                    <li class="mb-2">截止金額: 沒有</li>
                    <li class="mb-2">訂單
                    <?= ($buyInfo['enable'] == 1)? 
                      '<span class="text-danger">進行中</span>':
                      '<span class="text-success">已結束</span>'; ?>
                      <button type="button" class="btn btn-sm btn-light">停止加訂</button>
                    </li>
                    <li class="mb-2">
                      截止後隱藏: 
                      <button type="button" class="btn btn-sm btn-light">留在首頁</button>
                    </li>
                    <li class="mb-2">
                      <button type="button" class="btn btn-sm btn-danger">下載訂購單</button>
                    </li>
                  </ul>
                </div>
              </div>

              
            </div>

            <!-- Content Column -->
            <div class="col-lg-3">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">公告事項</h6>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <textarea rows="8" cols="35" class="form-control" placeholder="沒有"></textarea>

                  </div>
      
                </div>
              </div>
            </div>

            <!-- Content Column -->
            <div class="col-lg-3">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">小筆記</h6>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <textarea rows="8" class="form-control" placeholder="memo"></textarea>

                  </div>
      
                </div>
              </div>
              
            </div>


          </div>
          <!-- Content Row End-->

          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
              <div id="accordion">
<!-- 按件計算 -->
                <div class="card">
                  <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapse1">
                      按件計算
                    </a>
                  </div>
                  <div id="collapse1" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">產品</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">單價</th>
                              <th class="text-right">已付數</th>
                              <th class="text-center">顯示說明</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $prevProduct = '';
                            foreach($ordersByAmount as $orderByAmount  ){
                              if($prevProduct != $orderByAmount['product'] ){
                                $prevProduct = $orderByAmount['product'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByAmount['product'];?></td>
                                  <td class="text-right"><?= $orderByAmount['amount']?></td>
                                  <td class="text-right"><?= $orderByAmount['price'];?></td>
                                  <td class="text-right paid-row-sum"><?= $orderByAmount['paid_row_sum'];?></td>
                                  <td class="text-center orderer <?= checkPaid($orderByAmount['paid'])?>" 
                                  data-paid="<?= $orderByAmount['paid'];?>"
                                  data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByAmount['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-price="<?= $orderByAmount['price'];?>"  
                                  >
                                    <a href="javascript:void(0)"><?= $orderByAmount['orderer'];?></a>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center orderer <?= checkPaid($orderByAmount['paid'])?>"
                                data-paid="<?= $orderByAmount['paid'];?>"
                                data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                data-order-id="<?= $orderByAmount['order_id'];?>" 
                                data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                data-sum="<?=$buyInfo['sum'];?>"
                                data-price="<?= $orderByAmount['price'];?>"
                                >
                                  <a href="javascript:void(0)"><?= $orderByAmount['orderer'];?></a>
                                </td>
                              <?php
                                }
                              ?>
                              
                            <?php
                            }
                            ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse2">
                      按人計算
                    </a>
                  </div>
                  <div id="collapse2" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">訂購人</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">已付</th>
                              <th class="text-right">還剩</th>
                              <th class="text-right">總共</th>
                              <th class="text-center">顯示說明</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $prevOrderer = '';
                            foreach($ordersByOrderer as $orderByOrderer  ){
                              if($prevOrderer != $orderByOrderer['orderer'] ){
                                $prevOrderer = $orderByOrderer['orderer'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByOrderer['orderer'];?></td>
                                  <td class="text-right"><?= $orderByOrderer['amount']?></td>
                                  <td class="text-right paid-row-sum"><?= $orderByOrderer['paid_row_sum'];?></td>
                                  <td class="text-right"><?= $orderByOrderer['unpaid_row_sum'];?></td>
                                  <td class="text-right"><?= $orderByOrderer['price_row_sum'];?></td>
                                  <td class="text-center orderer <?= checkPaid($orderByOrderer['paid'])?>" 
                                  data-paid="<?= $orderByOrderer['paid'];?>"
                                  data-order-sn="<?= $orderByOrderer['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByOrderer['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-price="<?= $orderByOrderer['price'];?>"  
                                  >
                                    <a href="javascript:void(0)"><?= $orderByOrderer['product'];?></a>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center orderer <?= checkPaid($orderByOrderer['paid'])?>"
                                data-paid="<?= $orderByOrderer['paid'];?>"
                                data-order-sn="<?= $orderByOrderer['order_sn'];?>"
                                data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                data-order-id="<?= $orderByOrderer['order_id'];?>" 
                                data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                data-sum="<?=$buyInfo['sum'];?>"
                                data-price="<?= $orderByOrderer['price'];?>"
                                >
                                  <a href="javascript:void(0)"><?= $orderByOrderer['product'];?></a>
                                </td>
                              <?php
                                }
                              ?>
                              
                            <?php
                            }
                            ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      
                    </div>
                  </div>
                </div>

<!-- 老闆我要訂 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse3">
                      老闆我要訂
                    </a>
                  </div>
                  <div id="collapse3" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">產品</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">單價</th>
                              <th class="text-left">說明</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                            $prevProduct = '';
                            $prevOrderer = '';
                            foreach($ordersByAmount as $orderByAmount  ){

                              if($prevProduct != $orderByAmount['product'] ){
                                $prevProduct = $orderByAmount['product'];
                            ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByAmount['product'];?></td>
                                  <td class="text-right"><?= $orderByAmount['amount']?></td>
                                  <td class="text-right"><?= $orderByAmount['price'];?></td>
                                  <td class="text-left">
                                  <?php
                                    if ($prevOrderer != $orderByAmount['orderer']){
                                      $prevOrderer = $orderByAmount['orderer'];
                                    }
                                    echo ($orderByAmount['explanation']!=null)? $orderByAmount['orderer'].':'. $orderByAmount['explanation']: null; 
                                    // echo '<br />'.$orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                              }else{
                                /* 同一訂購人之同一產品，說明僅顯示一次 */
                                /* if ($prevOrderer != $orderByAmount['orderer']){ */
                                  $prevOrderer = $orderByAmount['orderer'];
                                  // echo '<br />'.$orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                                  echo ($orderByAmount['explanation']!=null)? '<br />'. $orderByAmount['orderer'].':'. $orderByAmount['explanation']:'';
                                /* } */
                                
                            
                              }
                            }
                            ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

<!-- 修改訂單 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse4">
                      修改訂單
                    </a>
                  </div>
                  <div id="collapse4" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">產品</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">單價</th>
                              <th class="text-center">選一個刪除</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $prevProduct = '';
                            foreach($ordersByAmount as $orderByAmount  ){
                              if($prevProduct != $orderByAmount['product'] ){
                                $prevProduct = $orderByAmount['product'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByAmount['product'];?></td>
                                  <td class="text-right"><?= $orderByAmount['amount']?></td>
                                  <td class="text-right"><?= $orderByAmount['price'];?></td>
                                  <td class="text-center <?= checkPaid($orderByAmount['paid'])?>" >
                                    <button type="button" class="btn btn-link btn-sm"
                                    data-orderer="<?= $orderByAmount['orderer'];?>"
                                    data-buy-id="<?= $orderByAmount['buy_id'];?>"
                                    data-order-id="<?= $orderByAmount['order_id'];?>"
                                    data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                    data-product-no="<?= $orderByAmount['product_no'];?>"
                                    data-toggle="modal" data-target="#editOrderModal">
                                      <?= $orderByAmount['orderer'];?>
                                    </button>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center <?= checkPaid($orderByAmount['paid'])?>">
                                  <button type="button" class="btn btn-link btn-sm" 
                                  data-orderer="<?= $orderByAmount['orderer'];?>" 
                                  data-buy-id="<?= $orderByAmount['buy_id'];?>"
                                  data-order-id="<?= $orderByAmount['order_id'];?>"
                                  data-order-sn="<?= $orderByAmount['order_sn'];?>"  
                                  data-product-no="<?= $orderByAmount['product_no'];?>"
                                  data-toggle="modal" data-target="#editOrderModal">
                                    <?= $orderByAmount['orderer'];?>
                                  </button>

                                </td>
                              <?php
                                }
                              ?>
                              
                            <?php
                            }
                            ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapse5">
                      刪除模式
                    </a>
                  </div>
                  <div id="collapse5" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">產品</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">單價</th>
                              <th class="text-center">選一個刪除</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $prevProduct = '';
                            foreach($ordersByAmount as $orderByAmount  ){
                              if($prevProduct != $orderByAmount['product'] ){
                                $prevProduct = $orderByAmount['product'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByAmount['product'];?></td>
                                  <td class="text-right"><?= $orderByAmount['amount']?></td>
                                  <td class="text-right"><?= $orderByAmount['price'];?></td>
                                  <td class="text-center <?= checkPaid($orderByAmount['paid'])?>" >
                                    <form method="post" action="<?= $_SERVER['PHP_SELF'];?>" >
                                      <button type="button" class="btn btn-link btn-sm btn-delete" >
                                        <?= $orderByAmount['orderer'];?>
                                      </button>
                                      <input type="hidden" name="delete" />
                                      <input type="hidden" name="buy_id" value="<?= $orderByAmount['buy_id'];?>" />
                                      <input type="hidden" name="order_id" value="<?= $orderByAmount['order_id'];?>" />
                                      <input type="hidden" name="order_sn" value="<?= $orderByAmount['order_sn'];?>" />

                                    </form>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center <?= checkPaid($orderByAmount['paid'])?>">
                                  <form method="post" action="<?= $_SERVER['PHP_SELF'].'?buy_id='.$_GET['buy_id'];?>" >
                                    <button type="button" class="btn btn-link btn-sm btn-delete" >
                                      <?= $orderByAmount['orderer'];?>
                                    </button>
                                    <input type="hidden" name="delete" />
                                    <input type="hidden" name="buy_id" value="<?= $orderByAmount['buy_id'];?>" />
                                    <input type="hidden" name="order_id" value="<?= $orderByAmount['order_id'];?>" />
                                    <input type="hidden" name="order_sn" value="<?= $orderByAmount['order_sn'];?>" />

                                  </form>
                                </td>
                              <?php
                                }
                              ?>
                              
                            <?php
                            }
                            ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse6">
                      出貨狀態
                    </a>
                  </div>
                  <div id="collapse6" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse7">
                      其他設定
                    </a>
                  </div>
                  <div id="collapse7" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          
<!-- 改訂Modal -->
          <!-- The Modal -->
          <div class="modal" id="editOrderModal">
            <div class="modal-dialog">
              <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">改訂 <span id="orderer-modal-title"></span>  買的</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form method="post" action="<?= $_SERVER['PHP_SELF'].'?buy_id='.$_GET['buy_id'];?>" 
                  class="user" >
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>名稱</th>
                          <th>價錢</th>
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
                          <td data-product-no="<?=$i;?>">
<?php
      $j = 0;
      foreach($rowOthers as $other){
        //比對如 " 中杯 40"，比對成功增加一個radio
        if(preg_match('/^(\s)*[(\x7f-\xff)a-zA-Z]+(\s)+[0-9]+/',$other)){
          $j++;
          $arrayOther = preg_split('/[\s]+/', $other);         
          $arrayPrice = preg_grep('/[0-9]+/', $arrayOther );
          $arraySubProduct = preg_grep('/[(\x7f-\xff)a-zA-Z]+/', $arrayOther );
          // 取得商品細項的價格
          $price = array_shift($arrayPrice);
          // 取得商品細項的名稱
          $subProduct = array_shift($arraySubProduct);
          // 輸入資料為 "產品名稱;產品編號;價格"
          printf("<input type='radio' id=%s name='product_info' value=%s />&nbsp%s&nbsp&nbsp&nbsp",
          $i.'-'.$j, $product.':'.$subProduct.';'.$i.'-'.$j.';'.$price, $other );
        }else{
          $other = str_replace(' ', '', $other);
          // 輸入資料為 "產品名稱;產品編號;價格"
          printf("<input type='radio' id=%s name='product_info' value=%s />&nbsp%s&nbsp&nbsp&nbsp",
          $i ,$product.';'.$i.';'.$other ,$other );
        }
      }

?>

                          </td>
                          
                        </tr>
<?php
    }/* end if-else */
  }/* end foreach */
?>
                      </tbody>                    
                    </table>
                    <div class="row">
                      <div class="form-group col-md-4">
                        <input type="text" name="explanation" class="form-control form-control-user" 
                        placeholder="額外訂購說明" />
                      </div>
                      <div class="col-md-8">
                          <button type="submit" name="create_order" class="btn btn-danger btn-user mr-3">改訂</button>
                          <button type="button" class="btn btn-outline-danger btn-user" data-dismiss="modal" >取消</button>
                      </div>
                      <input type='hidden' name='buy_id' id='buy_id' />
                      <input type='hidden' name='order_id' id='order_id' />
                      <input type='hidden' name='order_sn' id='order_sn' />
                      <input type='hidden' name='update' />
                    </div>
                  </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                 
                </div>

              </div>
            </div>
          </div>

<!-- 刪除modal -->

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<!--  copy right 及登出模組 -->
<?php require_once 'common/htmlFooter.php'; ?>




</body>

</html>
