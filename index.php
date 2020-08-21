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
                  <form class="user">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="InputEmail" aria-describedby="emailHelp" placeholder="帳號">
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
                    <a href="index.html" class="btn btn-primary btn-user btn-block">
                      登入
                    </a>
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
