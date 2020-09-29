<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/BuyInfo.php';?>
<?php use Classes\Functions; ?>
<?php use Classes\BuyInfo; ?>

<?php

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

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $i = 0;
  if(isset($_REQUEST['buy_id'])){
    showInfo();
  }

?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 訂單明細</title>
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
            <h1 class="h3 mb-0 text-gray-800">
              訂單總覽：<?= $buyInfo['in_charge_name'];?>－<?= $buyInfo['store_name'];?>
              (<?= $buyInfo['store_tel'];?>)
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
                  <div id="collapse1" class="collapse " data-parent="#accordion">
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
                      Lorem ipsum..
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
