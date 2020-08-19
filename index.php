
<?php 
  $errors = [];
?>
<?php require_once('common/htmlHeader.php'); // html head部分 ?>
<script src="js/login.js"></script>
  <title>團購網</title>
</head>
<body>
<div class="container">
      <div class="row mt-4">
        <div class="col-md-7">
          <h4>管理員、客戶及專案人員登入後提供以下服務</h4>
          
          <ul>
            <li>管理員可瀏覽及執行所有功能</li>
            <li>客戶可瀏覽IG文章及專案</li>
            <li>專案人員可瀏覽IG文章及所獲得的酬金之管理</li>
            <li>專案人員註冊及修改基本資料請由登入下方之超連結進入</li>
            <li>客戶、管理員之註冊請洽本公司人員</li>
          </ul>
        </div>
        <div class="col-md-5">
          <div class="card ">
              <div class="card-header">
                <h5>登入IG管理系統</h5>
              </div>
              <div class="card-body">

                <form action="{{ route('verify.login.check') }}" method="post">
                  <div class="form-group">
                    <div class="container-err-msg">
<?php require_once ('common/validationErrorMessage.php') ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="username" class="col-lg-3">帳號</label>
                    <div class="col-lg-8">
                      <input type="text" class="form-control" id="username" name="username" maxlength="40" 
                      placeholder="訪客請輸入guest" value="" required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="pwd" class="col-lg-3">密碼</label>
                    <div class="col-lg-8">
                      <input type="password" class="form-control" id="pwd" name="pwd" maxlength="40" 
                      placeholder="訪客請輸入guest" value="" required />
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="I_validation_code" class="col-lg-3">驗證碼</label>
                    <div class="col-lg-9 form-inline">
                      <input type="text" class="form-control w-50 mr-2" id="I_validation_code" name="I_validation_code" type="text" required />
                      <img id="I_verify_image" class="mr-2" name="I_verify_image" src="{{route('verify.image.crate') }}" 
                      data-image-check="{{ route('ajax.image.check') }}" />
                      <a id="I_change_validation_code" name="I_change_validation_code" href="javascript: void(0)">
                        <img src="" alt="refresh validation" />
                      </a>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-9 form-inline">
                      <input type="submit" class="btn btn-primary mr-4" value="登入" data-username-psw-chk="{{ route('ajax.username_psw.check') }}" 
                      data-success-login=""/>
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" >
                        <label class="custom-control-label" for="remember">保持登入</label>
                      </div>
  
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <h6>專案人員</h6>
                <ul>
                  <li><button type="button" class="btn btn-link" onclick="javascript:location.href='{{ route("account.ig-users.formCreate") }}'">註冊申請加入</button></li>
                  <li>修改基本資料</li>
                </ul>
              </div>
          </div>
        </div>
      </div>

    </div>
</body>
</html>