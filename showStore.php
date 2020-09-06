<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php use Classes\Functions; ?>
<?php

  function createGroupStore(){

    global $connOO;

    //驗證通過
    if(validation()){
    
      if ( insert_store()){

        $result = get_stroe();
        
        //取得商店編號
        $row = $result->fetch_assoc();

        if(insert_store_product($row['store_no'])){
          Header("Location: ". Functions::redirect('/home.php') );
        }else{
          trigger_error(mysqli_error($connOO), E_USER_ERROR);
        }
        
      }else{
        trigger_error(mysqli_error($connOO), E_USER_ERROR);
      }



    }
  }

  function validation(){
    global $errors;

    //找到資料庫有一筆以上的資料
    $result = get_stroe();
    if( $result &&
      $result->num_rows > 0){
      array_push($errors, '已經有此商店名了喔!');
      $row = $result->fetch_assoc();
      return false;
    }else{
      return true;
    }

  }

  function get_stroe(){
    
    global $connOO;

    $query = sprintf("SELECT * FROM store WHERE `store_name` = %s", 
    GetSQLValue($_REQUEST['store_name'], "text"));

    $result = mysqli_query($connOO, $query);
    return $result;
  }

  function insert_store(){

    global $connOO;

    $query = sprintf("INSERT INTO store 
    (public_flag, store_name, Introduction, store_tel, store_address, detail, 
    store_fax, store_url, create_username, create_date, update_date) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )", 
    0, 
    GetSQLValue($_REQUEST['store_name'], "text"), 
    GetSQLValue($_REQUEST['introduction'], "text"), 
    GetSQLValue($_REQUEST['store_tel'], "text"), 
    GetSQLValue($_REQUEST['store_address'], "text"), 
    GetSQLValue($_REQUEST['detail'], "text"), 
    GetSQLValue($_REQUEST['store_fax'], "text"), 
    GetSQLValue($_REQUEST['store_url'], "text"), 
    GetSQLValue($_SESSION['username'], "text"), 
    GetSQLValue(date("Y-m-d H:i:s"), "date"), 
    GetSQLValue(date("Y-m-d H:i:s"), "date"));

  
    // 傳回結果集
    $result = mysqli_query($connOO, $query);
    
    return $result;
  }

  function insert_store_product($store_no){

    global $connOO;

    $query = sprintf("INSERT INTO store_product 
    ( store_no, product_list) 
    VALUES ( %s, %s)", 
    GetSQLValue($store_no, "int"),
    GetSQLValue($_REQUEST['product_list'], "text"));

  
    // 傳回結果集
    $result = mysqli_query($connOO, $query);

    return $result;
  }

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];

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

         
          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-sm font-weight-bold text-gray-800 text-uppercase text-break">簡介:123456789123456789</div>
                      <div class="h5 font-weight-bold text-primary text-break"><a href="#">店家:123456789123456789</a></div>
                    </div>
                    <div class="col-auto">
                      <input type="button" class="btn btn-danger btn-user" data-toggle="modal" data-target="#uploadSelectFilesModal"
                      value="團購" />
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <form class="user">
              <!-- The Modal -->
              <div class="modal" id="uploadSelectFilesModal">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <div class="modal-title"><h4>上傳圖片</h4></div>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                      <h6>支援&nbsp.jpg,&nbsp.png,&nbsp.gif,&nbsp.bmp&nbsp等四種圖片格式</h6>

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


            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-sm font-weight-bold text-gray-800 text-uppercase text-break">簡介:abcdefghijklmnopq</div>
                      <div class="h5 font-weight-bold text-primary text-break"><a href="#">店家:abcdefghijklmnopq</a></div>
                    </div>
                    <div class="col-auto">
                      <input type="button" class="btn btn-danger btn-user" data-toggle="modal" data-target="#uploadSelectFilesModal"
                      value="團購" />
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>
          <!-- Content Row End-->


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
