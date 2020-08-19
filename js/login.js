$(function () {
  //網頁根目錄
  var APP_URL = $('meta[name="_base_url"]').attr('content');

  var regBtn = $("#regBtn");
	var loginBtn = $("#loginBtn");
	var username = $("#username");
	var pwd = $("#pwd");
	var loginStatus = "";
	var showtime = $("#showtime");
  var S_validation_code = $("#I_validation_code");
  var S_change_validation_code = $("#I_change_validation_code");
	var S_verify_image = $("#I_verify_image");
  var S_return_code = $("#I_return_code");
  var error_msg_container = $("#error-msg-container");

  var verifyRoute = S_verify_image.attr('src');
  var chkVerifyImgRoute = S_verify_image.data("image-check");
  var chkUsernamePswRoute = loginBtn.data("username-psw-chk");
  var successLoginRoute = loginBtn.data("success-login");

  $('#I_change_validation_code').click(function () {
    console.log(verifyRoute);

    //換一張圖片驗證
    S_verify_image.attr("src", verifyRoute + '?'+Math.random() );
    //console.log($('#I_verify_image').attr("src"));



    /* $.ajax({
        url: APP_URL + "/ajax/validation.change", 
        data: $("form").serialize(),
        type: 'get',
        dataType: 'text',
        success: 
          function (data,status) {
          console.log(data);

          //$("[name='username']").val(data.username);
          //$("[name='pwd']").val(data.pwd);
          }

    }); *///end of ajax
      
  });// end of click

  /* 
  * 此登入按鈕在js無作用，點選此按鈕程式直接進到controller
  * 
   */
  loginBtn.bind("click", function(){

    //登入時移除錯誤訊息
    $("#error-msg").remove();
    //if(username.val()==""){ alert("請填入帳號"); username.focus(); return false; }
    //if(pwd.val()==""){ alert("請填入密碼"); pwd.focus(); return false; }
    //console.log("S_validation_code:"+S_validation_code.val());
    if(S_validation_code.val()==""){
      //alert("請填入驗證碼");
      //使用bootstrap的alert
      error_msg_container.append('<div id="error-msg" class="alert alert-danger"><strong>錯誤：</strong>請填入驗證碼</div>');      

      S_validation_code.focus(); 
      return false;
    }

    console.log(chkVerifyImgRoute);
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type:"POST",
        //url:"validation_processing.php",
        url: chkVerifyImgRoute,//檢查圖片驗證碼
        data:{
          I_validation_code: S_validation_code.val()
        },
        async:true,
        success:function(data,status){
            // var data = '';
            console.log("data:"+data+" status:"+status);
            if(data==1){
              abc(data);
            }else{
              error_msg_container.append('<div id="error-msg" class="alert alert-danger"><strong>錯誤：</strong>驗證碼有誤</div>');
            //換一張圖片驗證
            S_verify_image.attr("src", verifyRoute + '?'+Math.random() );
              return false;
            }
        }
    });//end of ajax

    function abc(data){
        console.log(data);
        var url_link = "";
        
        loginStatus = loginAjax(username.val(), pwd.val()); 
        console.log("loginStatus:"+loginStatus);
        switch(loginStatus){
            case "success":

                //if(loginStatus.is_client=="yes"){
                //    url_link = "procIgUserData.php"+loginStatus.cli_name;
                // alert(url_link);
                //}else if(loginStatus.is_client=="no"){
                    //if(username.val()=="penny2"){
                    //    url_link = "receipts.php";
                    //}
                    //驗證碼
                    console.log("data:"+data);
                    if(data ==1){
                        //alert(9);
                        //登入成功，轉跳網址
                        url_link = successLoginRoute;
                    }else{
                        //url_link="{{ URL::to('/') }}";
                    }
                //}

                //alert(url_link);
                window.location.href=url_link;
            break;
            case "fail":
                //alert("帳號或密碼錯誤!");
                //使用bootstrap的alert
                error_msg_container.append('<div id="error-msg" class="alert alert-danger"><strong>錯誤：</strong>帳號或密碼有誤</div>');
                //換一張圖片驗證
                S_verify_image.attr("src", verifyRoute + '?'+Math.random() );
                return false;
            break;
        }// end of switch
    }// end of function

    function loginAjax(_username, _pwd){
        var returnStatus="";
        var loginData = {
            username: _username,
            pwd: _pwd
        };

        //var urlAjax = getSiteRoot()+"ajax/chkUsernamePsw.php";
        //console.log("url:" + urlAjax);
        $.ajax(
            {
                //url:getSiteRoot()+"ajax/chkUsernamePsw.php",
                url: chkUsernamePswRoute,
                type:"POST",
                async:false,
                dataType:"json",
                data:loginData,
                success: function(data){
                    returnStatus = data.status;
                    console.log("returnStatus="+returnStatus);
                }//end of success
            }
        );//end of ajax
        console.log("returnStatus="+returnStatus);
        return returnStatus;
    }

    function callback1(status) {
        console.log("status:"+status);
        loginStatus = status;
    }

});

  //setInterval(function () {
  //    $.post(APP_URL, $("form").serialize());
  //}, 1000);
});