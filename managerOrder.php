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
    global $connOO, $numOfProduct;
    $arrayOrders = [];
    $prevProduct = '';

    $resultOrderByAmount = OrderInfo::getAllSortByAmount($_GET['buy_id']);
    if (!$resultOrderByAmount){
      exit("查詢訂單資訊失敗 :" .$connOO->error);
    }else

    $i = 1;
    // 將資料庫內容送進陣列中
    while( $row = $resultOrderByAmount->fetch_assoc()){

      if($row['product'] != $prevProduct){
        $prevProduct = $row['product'];
        $i = 1;
        $row['amount'] = $i;
      }else{
        $i++;
        $row['amount'] = $i;
      }
      array_push($arrayOrders, $row);
      $numOfProduct++;
    }

    // 以 product_no為key1 升冪 ,amount為key2排序 降冪
    usort($arrayOrders, function($a, $b){
      if($a['product_no'] == $b['product_no']){
        if($a['amount'] == $b['amount']){
          return 0;
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
  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $buyInfo = [];
  $ordersByAmount = '';
  $numOfProduct = 0; // 份數

  //取消
  if(isset($_REQUEST['buy_id'])){
    showInfo();
    $ordersByAmount = getOrderInfoSortByAmount();

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
                                  <td class="text-right paid-row-sum"><?= $orderByAmount['paid'];?></td>
                                  <td class="text-center orderer <?= checkPaid($orderByAmount['paid'])?>" 
                                  data-paid="<?= $orderByAmount['paid'];?>"
                                  data-order-sn="<?= $orderByAmount['order_sn'];?>"
                                  data-buy-id="<?= $buyInfo['buy_id'];?>" 
                                  data-order-id="<?= $orderByAmount['order_id'];?>" 
                                  data-total-paid="<?= $buyInfo['total_paid'];?>" 
                                  data-sum="<?=$buyInfo['sum'];?>"
                                  data-price="<?= $orderByAmount['price'];?>"
                                  >
                                    <?= $orderByAmount['orderer'];?>
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
                                  <?= $orderByAmount['orderer'];?>
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
                      Lorem ipsum..
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse3">
                      老闆我要訂
                    </a>
                  </div>
                  <div id="collapse3" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>
                
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse4">
                      修改訂單
                    </a>
                  </div>
                  <div id="collapse4" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapse5">
                      刪除模式
                    </a>
                  </div>
                  <div id="collapse5" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      Lorem ipsum..
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

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<!--  copy right 及登出模組 -->
<?php require_once 'common/htmlFooter.php'; ?>




</body>

</html>
