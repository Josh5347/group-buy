$(function () {
  

  //無勾選我同意條款，不可註冊
  $('#btn-register-submit').click(function () {
    console.log($('#customCheck').prop('checked'));
    if($('#customCheck').prop('checked') != true){
      alert("無勾選我同意條款，不可註冊");
      return false
    }
  });

});