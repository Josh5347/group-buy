<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connectionOO.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php 
  use Classes\AccountPassword;

  function createAccountSuccessMsg(){
    if( isset($_SESSION['create_account']) &&
      $_SESSION['create_account'] == 'success'){
      echo "<script>alert('建立成功，請登入');</script>";
      unset($_SESSION['create_account']);
    }
  }

  function validation(){
    global $errors;

    //圖片驗證碼
    if( !isset($_REQUEST['verify_image'])){
      array_push($errors, '驗證碼為必輸');
    }

    if( isset($_REQUEST['verify_image']) &&
        $_SESSION['validation_image_number'] != $_REQUEST['verify_image']){
      array_push($errors, '驗證碼有誤');
    }

    if( !isset($_REQUEST['account']) ||
        $_REQUEST['account'] == '' ){
      array_push($errors, '帳號欄為必輸');
    }

    if( !isset($_REQUEST['pwd']) ||
        $_REQUEST['pwd'] == '' ){
      array_push($errors, '密碼欄為必輸');
    }

    //AccountPassword::check()

    //錯誤訊息超過1個
    if(count($errors) > 0){
      return false;
    }else{
      return true;
    }


  }


  /****************************************************/
  /*                    main                          */
  /****************************************************/
  
  //當建立群組帳號成功時，轉跳至登入頁面，顯示訊息
  createAccountSuccessMsg();

  //錯誤訊息
  $errors = [];

  //有按登入按鈕
  if( isset($_REQUEST['login']) &&
      $_REQUEST['login'] == 'submit'){

    //驗證通過
    if(validation()){

    }
  }
?>




<?php require_once 'common/htmlHeader.php'; ?>

  <title>團購網 - 登入</title>

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"> -->
              <div class="col-lg-6 d-none d-lg-block">
                <img src="https://picsum.photos/444/512" />
              </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">使用群組帳號或個人帳號登入</h1>
                  </div>

                  <!-- 錯誤訊息 -->
                  <?php require_once 'common/validationErrorMessage.php'?>

                  <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="user">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="InputUsername" placeholder="帳號">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="InputPassword" placeholder="密碼">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">記住我</label>
                      </div>
                    </div>
                    <div class="form-group form-inline">
                      <input type="text" class="form-control form-control-user w-50" id="I_validation_code" placeholder="請填驗證碼">
                      <a href="javascript:void(0)" onclick="$(function(){ $('#I_verify_image').attr('src', 'verify_image.php')});">
                        <img id="I_verify_image" class="ml-3 rounded-lg" name="I_verify_image" src="verify_image.php" />
                      </a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      登入
                    </button>
                    <input type="hidden" name="login" value="submit" />
<!--                     <hr>
                    <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">忘記個人帳號密碼?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">申請新的群組帳號!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>


</body>

</html>
