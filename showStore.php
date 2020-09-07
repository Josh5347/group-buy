<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/Store.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\Store; ?>

<?php

  function showStore(){
    global $connOO;

    $result = Store::getAllByUsername($_SESSION['username']);
    if (!$result){
      exit("查詢可團購店家失敗 :" .$result->error);
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

  $storeResult = showStore();
  $rowsCount = $storeResult->num_rows;

  //取消
  if(isset($_REQUEST['cancel'])){
    Header("Location: ". Functions::redirect('/home.php') );
  }

  //儲存為群組專用店家
  if(isset($_REQUEST['create_group_store'])){
    createGroupStore();
  }


?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 選一家開始團購</title>
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
            <h1 class="h3 mb-0 text-gray-800">選一家開始團購</h1>
          </div>

          <!-- 錯誤訊息 -->
          <?php require_once 'common/validationErrorMessage.php'?>
<?php 
  if($rowsCount > 0){ 
    while($rowStore = $storeResult->fetch_assoc()){
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
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-sm font-weight-bold text-gray-800 text-uppercase text-break">簡介:<?=$rowStore['introduction']; ?></div>
                      <div class="h5 font-weight-bold text-primary text-break"><a href="/processStore.php?store_no=<?= $rowStore['store_no'];?>">店家:<?= $rowStore['store_name'];?></a></div>
                    </div>
                    <div class="col-auto">
                      <input type="button" class="btn btn-danger btn-user" data-toggle="modal" data-target="#buyInfoModal<?= $rowStore['store_no'];?>"
                      value="團購" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <form class="user">
              <!-- The Modal -->
              <div class="modal" id="buyInfoModal<?= $rowStore['store_no'];?>">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <div class="modal-title"><h4>開啟<?= $rowStore['store_name'];?>的團購</h4></div>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                      

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger btn-user" data-dismiss="modal">確定上傳</button>
                      <button type="button" class="btn btn-outline-danger btn-user" data-dismiss="modal">取消上傳</button>
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

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>



</body>

</html>
