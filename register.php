<?php require_once 'common/phpHeader.php'; ?>
<?php require_once 'Connections/connection.php'; ?>
<?php require_once 'Connections/function.php'; ?>
<?php 
   //驗證
  function validation(){
    global $errors;

    //圖片驗證碼
    if( isset($_REQUEST['verify_image']) &&
        $_SESSION['validation_image_number'] != $_REQUEST['verify_image']){
      array_push($errors, '驗證碼有誤');
    }

    if( !isset($_REQUEST['account']) ||
        $_REQUEST['account'] == '' ){
      array_push($errors, '帳號欄為必輸');
      
    }

    if(isset($_REQUEST['account'] )&&
       !preg_match("/^\w{3,20}$/",$_REQUEST['account'])){
        array_push($errors, '帳號格式有誤');
    }

    if( !isset($_REQUEST['pwd']) ||
        $_REQUEST['pwd'] == '' ){
      array_push($errors, '密碼欄為必輸');
    }

    if( !isset($_REQUEST['confirm_pwd']) ||
        $_REQUEST['confirm_pwd'] == '' ){
      array_push($errors, '確認密碼欄為必輸');
    }

    if(isset($_REQUEST['pwd']) &&
      !preg_match("/^[\s\S]{6,12}$/",$_REQUEST['pwd'])){
      array_push($errors, '密碼需為6~12個字');
    }

    if( isset($_REQUEST['pwd']) &&
        isset($_REQUEST['confirm_pwd']) &&
        $_REQUEST['pwd'] != $_REQUEST['confirm_pwd']){
      array_push($errors, '密碼與確認密碼不符');
    }
    
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
  


  //錯誤訊息
  $errors = [];
  $todayDateTime = date("Y-m-d H:i:s");
  $result = false;
  
  $StringExplo=explode("/",$_SERVER['REQUEST_URI']);
  // $HeadTo=$StringExplo[0]."/index.php";

  //有按建立群組按鈕
  if( isset($_REQUEST['insert']) &&
      $_REQUEST['insert'] == 'submit'){

    //驗證通過
    if(validation()){
	
      $passwordEncrypt = password_hash( $_REQUEST['pwd'], PASSWORD_BCRYPT);
      
      $query = sprintf("INSERT INTO account (username, login_password, register_date) 
        VALUES (%s, %s, %s)", 
		    GetSQLValue($_REQUEST['account'], "text"), 
		    GetSQLValue($passwordEncrypt, "text"), 
		    GetSQLValue($todayDateTime, "text")); 
      
      // 傳回結果集
      $result = mysqli_query($conn, $query);	
    
      if ($result){
        //echo "<script>alert('建立成功，請登入');</script>";
        $_SESSION['create_account'] = 'success';
        // Header("Location:".$HeadTo);
        echo '<script> location.replace("/index.php"); </script>';
      }else{
        trigger_error(mysqli_error($conn), E_USER_ERROR);
      }
      
    }
  }


?>

<?php require_once 'common/htmlHeader.php'; ?>
<script src="js/register.js" defer></script>

  <title>團購網 - 建立群組</title>
</head>

<!-- <body class="bg-gradient-primary"> -->
<body class="">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="user">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              
              

              <!-- 使用條款 -->
              <div class="col-lg-6">
                <div class="p-5">

                  <div class="card  ">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">使用條款</h6>
                    </div>
                    <div class="card-body m-1">
                      <ol>
                        <li><b>本網站不提供任何保證。任何因本服務所造成的傷害或損失，您願意自行負責。</b></li>
                        <li>您承諾絕不為任何非法目的、以任何非法方式使用本站，並承諾遵守中華民國相關法規及一切使用網際網路之國際慣例。您同意並保證不得利用本服務從事侵害他人權益或違法之行為</li>
                        <li>您同意本站得基於維護交易安全之考量，或違反本使用條款的明文規定及精神，終止您的密碼、帳號（或其任何部分）或本服務之使用</li>
                      </ol>
                      <div class="custom-control custom-checkbox">
                        <input name="chkbox_terms_agree" type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck"><b>我同意使用條款</b></label>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <!-- 建立群組帳號密碼 -->
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">建立群組</h1>
                  </div>

                  <!-- 錯誤訊息 -->
                  <?php require_once 'common/validationErrorMessage.php'?>

                  <div class="form-group">
                    <input type="text" name="account" class="form-control form-control-user" id="InputAccount" placeholder="帳號:3~20個英文或數字">
                  </div>
                  <div class="form-group">
                    <input type="password" name="pwd" class="form-control form-control-user" id="InputPassword" placeholder="密碼:6~12個字">
                  </div>
                  <div class="form-group">
                    <input type="password" name="confirm_pwd" class="form-control form-control-user" id="InputPasswordConfirm"
                      placeholder="確認密碼">
                  </div>
                  <div class="form-group form-inline">
                    <input type="text" name="verify_image" class="form-control form-control-user w-50" id="I_validation_code"
                      placeholder="請填驗證碼">
                    <a href="javascript:void(0)"
                      onclick="$(function(){ $('#I_verify_image').attr('src', 'verify_image.php')});">
                      <img id="I_verify_image" class="ml-2 rounded-lg" src="verify_image.php" />
                    </a>
                  </div>
                  <div class="form-group">
                    <button id="btn-register-submit" type="submit" class="btn btn-danger btn-user btn-block">
                      建立群組
                    </button>
                  </div>

                  <input type="hidden" name="insert" value="submit" />
                    <!--                     <hr>
                    <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">忘記個人帳號密碼?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="index.php">已經有帳號了嗎?登入!</a>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </from>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>


</html>
