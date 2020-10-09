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

  function deleteOrderInfo(){
    global $connOO;

    if(!OrderInfo::deleteOrderInfo(
      $_REQUEST['buy_id'],
      $_REQUEST['order_id'],
      $_REQUEST['order_sn']
      )){
      trigger_error(mysqli_error($connOO), E_USER_ERROR);
    }    


  }

  function showCollapse($collapseName){
    global $showAmount, $showUpdate, $showDelete, $showShipping;
    switch ($collapseName) {
      case 'update':
        $showAmount = '';
        $showUpdate = 'show';
        $showDelete = '';      
        $showShipping = '';      
        break;
      case 'delete':
        $showAmount = '';
        $showUpdate = '';
        $showDelete = ' show';      
        $showShipping = '';      
        break;
      
      case 'shipping':
        $showAmount = '';
        $showUpdate = '';
        $showDelete = '';      
        $showShipping = ' show';      
        break;
      
      case 'default':
        $showAmount = ' show';
        $showUpdate = '';
        $showDelete = '';      
        $showShipping = '';      
        break;
      
      
      default:
        # code...
        break;
    }
  }
  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $ordersByAmount = '';
  $numOfProduct = 0; // 份數
  $resultOrderByAmount; //全域變數
  $arrayProducts = [];
  $toShipping = false;
  $i = 0;
  /* showCollapse('default'); */

  if(isset($_REQUEST['update'])){
    updateOrderInfo();
    /* showCollapse('update'); */
  
  }

  if(isset($_REQUEST['shipping'])){
    /* showCollapse('shipping'); */
    $toShipping = true;

  }

  if(isset($_REQUEST['delete'])){
    deleteOrderInfo();
    /* showCollapse('delete'); */
  }

  if(isset($_REQUEST['buy_id'])){
    $buyInfo = BuyInfo::getAll($_GET['buy_id']);
    $ordersByAmount = OrderInfo::getOrderInfoSortByAmount($_GET['buy_id']);// 按件計算
    $ordersByOrderer = OrderInfo::getOrderInfoSortByOrderer(); // 按人計算
    $ordersExplan = OrderInfo::getOrderInfoSortByAmount($_GET['buy_id']);// 老闆我要訂
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
            <h1 class="h3 mb-0 text-gray-800">
              管理訂單：<?= $buyInfo['in_charge_name'];?>的 <?= $buyInfo['store_name'];?>
              (<?= $buyInfo['store_tel'];?>)
            </h1>
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
                    <span>共 <?= count($ordersByAmount);?> 份</span>
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
                  <h6 class="m-0 font-weight-bold text-primary"><div onclick="ScrollToBottom()"> 小筆記</div></h6>
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
                                  <td class="text-right paid-count-row-sum"><?= $orderByAmount['paid_row_sum'];?></td>
                                  <td class="text-center orderer 
                                  <?= checkPaid($orderByAmount['paid'])?> 
                                  id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?>
                                  "
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
                                <td class="text-center orderer 
                                <?= checkPaid($orderByAmount['paid'])?>
                                id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?>
                                "
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
<!-- 按人計算 -->
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
                            $rowNum = 0;
                            foreach($ordersByOrderer as $orderByOrderer  ){
                              if($prevOrderer != $orderByOrderer['orderer'] ){
                                $prevOrderer = $orderByOrderer['orderer'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByOrderer['orderer'];?></td>
                                  <td class="text-right"><?= $orderByOrderer['amount']?></td>
                                  <td class="text-right paid-row-sum"><?= $orderByOrderer['paid_row_sum'];?></td>
                                  <td class="text-right unpaid-row-sum"><?= $orderByOrderer['unpaid_row_sum'];?></td>
                                  <td class="text-right price-row-sum" ><?= $orderByOrderer['price_row_sum'];?></td>
                                  <td class="text-center orderer 
                                  <?= checkPaid($orderByOrderer['paid'])?> 
                                  id-<?= $orderByOrderer['order_id'];?>sn-<?= $orderByOrderer['order_sn'];?>
                                  "
                                  data-paid="<?= $orderByOrderer['paid'];?>"
                                  data-order-sn="<?= $orderByOrderer['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByOrderer['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-price="<?= $orderByOrderer['price'];?>"
                                  data-row-num="<?= ++$rowNum;?>"
                                  >
                                    <a href="javascript:void(0)"><?= $orderByOrderer['product'];?></a>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center orderer 
                                <?= checkPaid($orderByOrderer['paid'])?> 
                                id-<?= $orderByOrderer['order_id'];?>sn-<?= $orderByOrderer['order_sn'];?>
                                "
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
                                      $i = 0;
                                    }
                                    if($orderByAmount['explanation']!=null){ 
                                      echo $orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                                      $i++;
                                    } 
                                    // echo '<br />'.$orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                              }else{
                                /* 同一訂購人之同一產品，說明僅顯示一次 */
                                if ($prevOrderer != $orderByAmount['orderer']){
                                  $prevOrderer = $orderByAmount['orderer'];
                                  // echo '<br />'.$orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                                  if($orderByAmount['explanation']!=null){
                                    echo $orderByAmount['orderer'].':'. $orderByAmount['explanation'];
                                    $i++;
                                    // 有2筆以上的說明，第1筆後需跳行
                                    echo ( $i >= 1)? '<br />': '';
                                  }
                                }
                                
                            
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
                  <div id="collapse4" class="collapse " data-parent="#accordion">
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
                                  <td class="text-center 
                                  <?= checkPaid($orderByAmount['paid'])?>
                                  id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?>
                                  " >
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
                                <td class="text-center 
                                <?= checkPaid($orderByAmount['paid'])?> 
                                id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?> 
                                ">
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

<!-- 刪除模式 -->
                <div class="card">
                  <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapse5">
                      刪除模式
                    </a>
                  </div>
                  <div id="collapse5" class="collapse " data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">產品</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">單價</th>
                              <th class="text-center">選一個修改</th>
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
                                  <td class="text-center 
                                  <?= checkPaid($orderByAmount['paid'])?>
                                  id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?>" >
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
                                }else{                                 
                              ?>
                                <td class="text-center 
                                <?= checkPaid($orderByAmount['paid'])?> 
                                id-<?= $orderByAmount['order_id'];?>sn-<?= $orderByAmount['order_sn'];?>">
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

<!-- 出貨狀態 -->                
                <div class="card">
                  <div class="card-header">
                    <!-- 使超連結失效 -->
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse6">
                      出貨狀態
                    </a>
                  </div>
                  <div id="collapse6" class="collapse " data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th class="text-left">訂購人</th>
                              <th class="text-right">數量</th>
                              <th class="text-right">付款</th>
                              <th class="text-center">批次</th>
                              <th class="text-center">出貨日期</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $prevOrderer = '';
                            $rowNum = 0;
                            foreach($ordersByOrderer as $orderByOrderer  ){
                              if($prevOrderer != $orderByOrderer['orderer'] ){
                                $prevOrderer = $orderByOrderer['orderer'];
                            ?>
                                </tr>
                                <tr>
                                  <td class="text-left"><?= $orderByOrderer['orderer'];?></td>
                                  <td class="text-right"><?= $orderByOrderer['amount']?></td>
                                  <td class="text-right row<?=++$rowNum?>-paid-all"><?= $orderByOrderer['paid_all'];?></td>
                                  <td class="text-right batch-shipping">
                                    <a href="####">出貨 >></a>
                                  </td>
                                  <td class="text-center shipping <?= checkPaid($orderByOrderer['paid'])?>" 
                                  data-paid="<?= $orderByOrderer['paid'];?>"
                                  data-order-sn="<?= $orderByOrderer['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByOrderer['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-shipping-date="<?= $orderByOrderer['shipping_date'];?>"  
                                  >
                                    <a href="javascript:void(0)">
                                      <?= $orderByOrderer['product'];?><br />
                                      <span class="shipping-date">
                                        <?= $orderByOrderer['shipping_date'];?>
                                      </span>
                                    </a>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center shipping <?= checkPaid($orderByOrderer['paid'])?>"
                                data-paid="<?= $orderByOrderer['paid'];?>"
                                data-order-sn="<?= $orderByOrderer['order_sn'];?>"
                                data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                data-order-id="<?= $orderByOrderer['order_id'];?>" 
                                data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                data-sum="<?=$buyInfo['sum'];?>"
                                data-shipping-date="<?= $orderByOrderer['shipping_date'];?>"  
                                >
                                  <a href="javascript:void(0)">
                                    <?= $orderByOrderer['product'];?><br />
                                    <span class="shipping-date">
                                      <?= $orderByOrderer['shipping_date'];?>
                                    </span>
                                  </a>
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
<?php
