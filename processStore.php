<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php require_once 'Classes/Functions.php';?>
<?php require_once 'Classes/Store.php';?>
<?php require_once 'Classes/StoreProduct.php';?>

<?php use Classes\Functions; ?>
<?php use Classes\Store; ?>
<?php use Classes\StoreProduct; ?>
<?php

  function createGroupStore(){

    global $connOO;

    //驗證通過
    if(validation()){
    
      if ( insert_store()){

        $result = Store::getOneByStoreName($_REQUEST['store_name']);
        
        //取得商店編號
        $row = $result->fetch_assoc();

        if(insert_store_product($row['store_no'])){
          echo  '<script> location.replace("/home.php"); </script>';
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
    $result = Store::getOneByStoreName($_REQUEST['store_name']);
    if( $result &&
      $result->num_rows > 0){
      array_push($errors, '已經有此商店名了喔!');
      $row = $result->fetch_assoc();
      return false;
    }else{
      return true;
    }

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

  function inquireStoreInfo(){
    global $title, $rowStore, $rowProduct, $connOO;
    
    $title = '修改';
    $result = Store::getOneByStoreNo($_GET['store_no']);

    //有查詢到1筆資料
    if( $result &&
      $result->num_rows > 0){

      $rowStore = $result->fetch_assoc();

      $result2 = StoreProduct::getOneByStoreNo($_GET['store_no']);
      if( $result2 &&
        $result2->num_rows > 0){
        $rowProduct = $result2->fetch_assoc();
      }else{
        exit("查詢產品失敗 :" .$connOO->error);
      }

    }else{
      exit("查詢店家失敗 :" .$connOO->error);
    }
  }

  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];
  $title = '新增';
  //新增時，輸入欄位無資料
  $rowStore = [
    'store_name'    =>  '', 
    'introduction'  =>  '', 
    'store_tel'     =>  '', 
    'store_address' =>  '', 
    'detail'        =>  '', 
    'store_fax'     =>  '', 
    'store_url'     =>  '',  
  ];
  $rowProduct = [
    'product_list'  =>  ''
  ];

  //取消
  if(isset($_REQUEST['cancel'])){
    echo  '<script> location.replace("/home.php"); </script>';
  }

  //儲存為群組專用店家
  if(isset($_REQUEST['create_group_store'])){
    createGroupStore();
  }

  //修改店家之查詢店家資料(資料由showStore.php傳送來)
  if(isset($_GET['store_no'])){
    inquireStoreInfo();
  }

?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - <?= $title;?>商店</title>
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
            <h1 class="h3 mb-0 text-gray-800"><?= $title;?>商店</h1>
          </div>

          <!-- 錯誤訊息 -->
          <?php require_once 'common/validationErrorMessage.php'?>

          <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="user">
            <!-- Content Row -->
            <div class="row">

              <!-- Content Column -->
              <div class="col-lg-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">基本資料</h6>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <input type="text" name="store_name" class="form-control form-control-user" 
                      placeholder="名稱:必填" value="<?= $rowStore['store_name']; ?>" required>
                    </div>
                    <div class="form-group">
                      <input type="text" name="introduction" class="form-control form-control-user" 
                      placeholder="簡介" value="<?= $rowStore['introduction']; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_tel" class="form-control form-control-user" 
                      placeholder="電話" value="<?= $rowStore['store_tel']; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_address" class="form-control form-control-user" 
                      placeholder="地址" value="<?= $rowStore['store_address']; ?>">
                    </div>
                  </div>
                </div>

                
              </div>


              <!-- Content Column -->
              <div class="col-lg-8 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">商品清單</h6>
                  </div>
                  <div class="card-body">
                    <div class="form-group float-left mr-1">
                      <textarea name="product_list" rows="10" cols="35" class="form-control" 
                      placeholder="商品:必填" required><?= $rowProduct['product_list'];?></textarea>   
                    </div>
                    <div class="form-group float-left">
                      <textarea rows="10" cols="35" class="form-control" placeholder="範例" readonly>魯肉飯, 40
叉燒飯, 60
{麵食}
牛肉麵, 意麵 80, 手工麵 90
陽春麵, 普通 40, 加蛋 50
{飲料冰品}
奶茶, S 20, M 30, L 40
冰咖啡, 中杯 40, 大杯 50
{下午茶系列}
甜甜圈, 大 40, 小 30, img:13</textarea>   
                    </div>

                  </div>
                </div>

                
              </div>

            </div>
            <!-- Content Row End-->

            <!-- Content Row -->
            <div class="row">


              <!-- Content Column -->
              <div class="col-lg-6 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">其他資訊</h6>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <textarea name="detail" rows="5" cols="50" class="form-control" placeholder="訂購說明"><?= $rowStore['detail']; ?></textarea>   
                    </div>
                    <div class="form-group">
                      <label>(這裡可以寫一些你希望在訂購時，讓訂購人看的說明，例如提醒註記加糖、加辣、不加冰等等細節。此欄位可以用&nbsphtml&nbsptag)</label>
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_fax" class="form-control form-control-user" 
                      placeholder="傳真" value="<?= $rowStore['store_fax']; ?>" >
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_url" class="form-control form-control-user" 
                      placeholder="店家網址" value="<?= $rowStore['store_url']; ?>" >
                    </div>
                    <div class="form-group">
                    </div>

                  </div>
                </div>

                
              </div>

              <!-- Content Column -->
              <div class="col-lg-6 mb-4">

                <!-- Content Row -->
                <div class="row">
                  <!-- Content Column -->
                  <div class="col-lg-12 mb-4">
                    <!-- Project Card Example -->
                    <div class="card shadow">
                      <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">商品圖片上傳與設定</h6>
                      </div>
                      <div class="card-body">
                        <div class="form-group">
                          <button type="button" class="btn btn-secondary btn-user" 
                            data-toggle="modal" data-target="#uploadSelectFilesModal">
                            上傳圖片
                          </button>
                        </div>
                        <br />
                        <br />
                        <br />
                        <br />
                        上傳的圖片如果是從別的網站引用來的， 請記得在 "訂購說明" 或是 "店家詳細說明" 裡註明出處 (例如寫來源的網址)，謝謝。
                      </div>
                    </div>
                  </div>
                  <!-- End of Column -->

                  <!-- Content Column -->
                  <div class="col-lg-12 mb-4">

                    <!-- Project Card Example -->
                    <div class="card shadow">
                      <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">儲存</h6>
                      </div>
                      <div class="card-body">
                        <div class="form-inline">
<?php //接收到$_GET['store_no']資料為修改店家
  if(isset($_GET['store_no'])){ 
?>
                          <div class="form-group mx-2">
                            <input type="submit" class="btn btn-danger btn-user" name="update_group_store"
                              value="修改群組專用店家" />
                            
                          </div>
<?php
  }else{
?>                          
                          <div class="form-group mx-2">
                            <input type="submit" class="btn btn-danger btn-user" name="create_group_store"
                              value="儲存為群組專用店家" />
                            
                          </div>
<?php
  }
?>
                
                          <div class="form-group mx-2">
                          <input type="hidden" class="btn btn-danger btn-user" name="create_public_store"
                              value="儲存為公用店家" />
                              
                          </div>
                          <div class="form-group mx-2">
                            <input type="submit" class="btn btn-outline-danger btn-user" name="cancel"
                              value="取消返回" />
                            
                          </div>
                        </div>

                      </div>
                    </div>



                    
                  </div>
                  <!-- End of Column -->

                </div>
                <!-- Content Row End-->
              </div>


            </div>
            <!-- Content Row End-->

          </form>
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
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<!--  copy right 及登出模組 -->
<?php require_once 'common/htmlFooter.php'; ?>




</body>

</html>
