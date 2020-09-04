<?php
  /****************************************************/
  /*                    main                          */
  /****************************************************/

  //錯誤訊息
  $errors = [];


?>

<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 新增商店</title>
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
            <h1 class="h3 mb-0 text-gray-800">新增商店</h1>
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
                      <input type="text" name="store_name" class="form-control form-control-user" placeholder="名稱:必填">
                    </div>
                    <div class="form-group">
                      <input type="text" name="introduction" class="form-control form-control-user" placeholder="簡介">
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_tel" class="form-control form-control-user" placeholder="電話">
                    </div>
                    <div class="form-group">
                      <input type="text" name="store_address" class="form-control form-control-user" placeholder="地址">
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
                      <textarea name="store_name" rows="10" cols="35" class="form-control" placeholder="商品:必填"></textarea>   
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
                      <textarea name="store_name" rows="5" cols="50" class="form-control" placeholder="訂購說明"></textarea>   
                    </div>
                    <div class="form-group">
                      <label>(這裡可以寫一些你希望在訂購時，讓訂購人看的說明，例如提醒註記加糖、加辣、不加冰等等細節。此欄位可以用&nbsphtml&nbsptag)</label>
                    </div>
                    <div class="form-group">
                      <input type="text" name="" class="form-control form-control-user" placeholder="傳真">
                    </div>
                    <div class="form-group">
                      <input type="text" name="" class="form-control form-control-user" placeholder="店家網址">
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
                          <div class="form-group mx-2">
                            <input type="submit" class="btn btn-danger btn-user" name="create_group_store"
                              value="儲存為群組專用店家" />
                            
                          </div>

                          
                          <div class="form-group mx-2">
                          <input type="submit" class="btn btn-danger btn-user" name="create_public_store"
                              value="儲存為公用店家" />
                              儲存為公用店家
                            </button>
                          </div>
                          <div class="form-group mx-2">
                            <input type="submit" class="btn btn-outline-danger btn-user" name="cancel"
                              value="取消返回" />
                            <button type="button" class="btn btn-outline-danger btn-user">
                              取消返回 
                            </button>
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
