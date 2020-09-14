$(function () {
  //原本radio按鈕name屬性值
  var radioAttrName;

  //按下數量按鈕"+"
  $('.td_amount button.plus').click(function(){
    var tagInputAmount = $(this).parent().find('.amount');
    var tagInputProduct = $(this).parent().find('.product');
    var tagInputExpalin = $(this).parent().next().find('.explain');
    var tagRadioSubPrice = $(this).parent().prev().find('.subPrice');
    var tagInputPrice = $(this).parent().prev().find('.price');
    var tagInputProductNo = $(this).parent().find('.product-no');
    var productNo = $(this).parent().data("product-no");
    var amount = tagInputAmount.val();
    if(amount == ''){
      amount = 1;
      // 價錢欄不使用array
      var attrNamePrice = "price" + productNo;
      // 若數量欄有數字，將此欄name設成amount[]，即可透過表單傳送
      tagInputAmount.attr("name", "amount[]");
      tagInputProduct.attr("name", "product[]");
      tagInputExpalin.attr("name", "explaination[]");
      tagInputPrice.attr("name", attrNamePrice);
      tagInputProductNo.attr("name", "product_no[]")

      radioAttrName = tagRadioSubPrice.attr("name");
      tagRadioSubPrice.attr("name", attrNamePrice);
      //點擊價錢第一個radio
      // tagRadioSubPrice.first().click();

    }else{
      ++amount;
    }

    tagInputAmount.val(amount);
  });

  //按下數量按鈕"-"
  $('.td_amount button.minus').click(function(){
    var tagInputAmount = $(this).parent().find('.amount');
    var tagInputProduct = $(this).parent().find('.product');
    var tagInputExpalin = $(this).parent().next().find('.explain');
    var tagRadioSubPrice = $(this).parent().prev().find('.subPrice');
    var tagInputPrice = $(this).parent().prev().find('.price');
    var tagInputProductNo = $(this).parent().find('.product-no');
    var amount = tagInputAmount.val();
    //當數量=空 或 0 時 按下-扭會將數量清成空白
    if(amount == '' || amount == 1){
      amount = '';
      // 若數量欄無數字，將此欄name屬性移除，即不透過表單傳送
      tagInputAmount.removeAttr("name");
      tagInputProduct.removeAttr("name");  
      tagInputExpalin.removeAttr("name");  
      tagInputPrice.removeAttr("name"); 
      tagInputProductNo.removeAttr("name");
      //取消點擊價錢radio
      tagRadioSubPrice.prop("checked", "");
      //將radio的屬性值還原
      tagRadioSubPrice.attr("name", radioAttrName);
      //tagRadioSubPrice.removeAttr("name");

         
    }else{
      --amount;
    }

    tagInputAmount.val(amount);
  });

  // 按下價錢的radio按鈕，將radio商品名稱加在名稱之後 例:奶茶:S
  $('.subPrice').click(function () {
    var productNo = $(this).parent().data("product-no");
    var subProductNo = $(this).data("sub-product-no");
    var subProduct = $(this).data("sub-product");
    var tagProduct = $(this).parent().prev();
    var tagInputProduct = $(this).parent().next().find('.product');
    var tagInputProductNo = $(this).parent().next().find('.product-no');
    var product = tagProduct.text();
    var newProduct = product + ':' + subProduct;
    var newProductNo = productNo + '-' + subProductNo;
    tagInputProduct.val(newProduct);
    tagInputProductNo.val(newProductNo)
  });



});