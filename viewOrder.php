<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/BuyInfo.php';?>
<?php require_once 'Classes/OrderInfo.php';?>
<?php use Classes\Functions; ?>
<?php use Classes\BuyInfo; ?>
<?php use Classes\OrderInfo; ?>

<?php

function checkPaid($paid){
  return ($paid)? "paid-color": "unpaid-color";
}

function checkCancelable($orderer){
  // 訂購者本人可以取消訂購，回傳 ".cancelable"
  return ($orderer == $_SESSION['username'])? "cancelable": "";
}

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $i = 0;
  if(isset($_REQUEST['buy_id'])){
    $buyInfo = BuyInfo::getAll($_GET['buy_id']);
    $ordersByAmount = OrderInfo::getOrderInfoSortByAmount($_GET['buy_id']);// 按件統計
    $ordersByOrderer = OrderInfo::getOrderInfoSortByOrderer(); // 按人統計  }
  }
?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 訂單明細</title>
  <link href="css/style.css" rel="stylesheet">
  <link href="css/managerOrder.css" rel="stylesheet">
  <link href="css/viewOrder.css" rel="stylesheet">
  <script src="js/viewOrder.js"></script>


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
          <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
              訂單總覽：<?= $buyInfo['in_charge_name'];?>－<?= $buyInfo['store_name'];?>
              (<?= $buyInfo['store_tel'];?>)
            </h1>
          </div>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">
              總共 <?= count($ordersByAmount);?> 份，<?=$buyInfo['amount'];?> 人購買，共 <?=$buyInfo['sum'];?> 元。
            </h1>
          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
              <div id="accordion">

<!-- 按件統計 -->
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
                                  <td class="text-center cancel 
                                  sn-<?= $orderByAmount['order_sn'];?>
                                  <?= checkCancelable($orderByAmount['orderer']); ?>
                                  <?= checkPaid($orderByAmount['paid']); ?>" 
                                  data-paid="<?= $orderByAmount['paid'];?>"
                                  data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByAmount['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-price="<?= $orderByAmount['price'];?>"  
                                  >
                                    <div class="cell-content">
                                      <?= $orderByAmount['orderer'];?>
                                    </div>
                                  </td>
                                
                              <?php
                                }else{                                 
                              ?>
                                <td class="text-center cancel 
                                sn-<?= $orderByAmount['order_sn'];?>
                                <?= checkCancelable($orderByAmount['orderer']); ?>
                                <?= checkPaid($orderByAmount['paid']); ?>"
                                data-paid="<?= $orderByAmount['paid'];?>"
                                data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                data-order-id="<?= $orderByAmount['order_id'];?>" 
                                data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                data-sum="<?=$buyInfo['sum'];?>"
                                data-price="<?= $orderByAmount['price'];?>"
                                >
                                  <div class="cell-content">
                                    <?= $orderByAmount['orderer'];?>
                                  </div>
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
<!-- 按人統計 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse2">
                      按人統計
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

<!-- 明細列表 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse3">
                      明細列表
                    </a>
                  </div>
                  <div id="collapse3" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>

<!-- 我的訂購資訊 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse4">
                      我的訂購資訊
                    </a>
                  </div>
                  <div id="collapse4" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>
<!-- 出貨狀態 -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse5">
                      出貨狀態
                    </a>
                  </div>
                  <div id="collapse5" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>
              </div>
              <!-- end accordion -->
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
