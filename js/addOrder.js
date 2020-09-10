$(function () {
  var radioAttrName;

  //按下數量按鈕"+"
  $('.td_amount button.plus').click(function(){
    var tagInputAmount = $(this).parent().find('.amount');
    var tagInputProduct = $(this).parent().find('.product');
    var tagRadioSubPrice = $(this).parent().prev().find('.subPrice');
    var tagInputPrice = $(this).parent().prev().find('.price');
    var productNo = $(this).parent().data("product-no");
    var amount = tagInputAmount.val();
    if(amount == ''){
      amount = 1;
      var attrNamePrice = "price" + productNo;
      // 若數量欄有數字，將此欄name設成amount[]，即可透過表單傳送
      tagInputAmount.attr("name", "amount[]");
      tagInputProduct.attr("name", "product[]");
      tagInputPrice.attr("name", attrNamePrice);

      // radioAttrName = tagRadioSubPrice.attr("name");
      //tagRadioSubPrice.attr("name", "price[]");

    }else{
      ++amount;
    }

    tagInputAmount.val(amount);
  });

  //按下數量按鈕"-"
  $('.td_amount button.minus').click(function(){
    var tagInputAmount = $(this).parent().find('.amount');
    var tagInputProduct = $(this).parent().find('.product');
    var tagRadioSubPrice = $(this).parent().prev().find('.subPrice');
    var tagInputPrice = $(this).parent().prev().find('.price');
    var amount = tagInputAmount.val();
    //當數量=空 或 0 時 按下-扭會將數量清成空白
    if(amount == '' || amount == 1){
      amount = '';
      // 若數量欄無數字，將此欄name屬性移除，即不透過表單傳送
      tagInputAmount.removeAttr("name");
      tagInputProduct.removeAttr("name");  
      tagInputPrice.removeAttr("name"); 
      tagRadioSubPrice.prop("checked", "");
      // tagRadioSubPrice.removeAttr("name");
         
    }else{
      --amount;
    }

    tagInputAmount.val(amount);
  });

  
});