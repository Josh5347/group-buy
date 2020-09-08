<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/BuyInfo.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\BuyInfo; ?>

<?php
  function showBuyInfo(){
    global $connOO;

    $result = BuyInfo::getAllByUsername($_SESSION['username']);
    if (!$result){
      exit("查詢可團購店家失敗 :" .$connOO->error);
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

  $buyInfoResult = showBuyInfo();
  $rowsCount = $buyInfoResult->num_rows;

?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 開始團購</title>
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

          <?php 
  if($rowsCount > 0){ 
    while($rowBuyInfo = $buyInfoResult->fetch_assoc()){
      $i++; 
      $x = ($i + 2) % 2;
      //每2個店家一行
      if((($i + 2) % 2) == 1){      
?>  
          <!-- Content Row -->
          <div class="row">
<?php
      }
?>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <button type="button" class="btn btn-info btn-sm float-left mr-2">4人<br />235元</button>
                      <div class="text-sm font-weight-bold text-gray-800 text-uppercase text-break"><?= $rowBuyInfo['in_charge_name'];?>發起的</div>
                      <a href="/processStore.php?store_no=<?= $rowBuyInfo['store_no'];?>"><div class="h5 font-weight-bold text-info text-break"><?=$rowBuyInfo['store_name']; ?></div></a>
                    </div>
                    <div class="col-auto">
                      <input type="button" class="btn btn-danger" data-toggle="modal" data-target="#buyInfoModal<?= $rowBuyInfo['store_no'];?>"
                      value="訂購" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <form class="user" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
              <!-- The Modal -->
              <div class="modal" id="buyInfoModal<?= $rowBuyInfo['store_no'];?>">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <div class="modal-title"><h4><?= $rowBuyInfo['store_name'];?>的團購</h4></div>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                      <div class="form-group">
                        <input type="text" name="in_charge_name" class="form-control form-control-user" 
                        placeholder="負責人(必輸)"  required/>
                      </div>
                      <div class="form-group">
                        <input type="text" name="expired_time" class="form-control form-control-user" 
                        placeholder="截止時間 格式 hh:mm:ss" />
                      </div>
                      <div class="form-group">
                        <textarea name="announce" rows="5" cols="35" class="form-control" 
                        placeholder="公告事項" ></textarea>   
                      </div>                 
                      <div class="form-group">
                        <label for="info1">訂單新增訂購人資訊欄:</label>
                        <div class="form-inline">
                          <input id="info1" type="text" name="info_1" class="form-control form-control-user col" 
                          placeholder="例如:電話分機" />
                          <input type="text" name="info_2" class="form-control form-control-user col m-2" 
                          placeholder="例如:面交地點" />
                        </div>
                      </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-danger btn-user" >開始團購</button>
                      <button type="button" class="btn btn-outline-danger btn-user" data-dismiss="modal">取消返回</button>
                      <input type="hidden" name="store_no" value="<?=$rowBuyInfo['store_no']; ?>" />
                    </div>

                  </div>
                </div>
              </div>
              <!-- Model end -->
            </form>


<?php
      //每2個店家一行
      if((($i + 2) % 2)== 0){
?>
          </div>
          <!-- Content Row End-->
<?php
      }
    }
          /* end of while */
  } ?>
          <!-- end of if -->


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


<!--  copy right 及登出模組 -->
<?php require_once 'common/htmlFooter.php'; ?>


</body>

</html>
