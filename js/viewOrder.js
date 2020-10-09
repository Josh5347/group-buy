$(function () {

  var buy_id = $(this).data("buy-id");
  var order_id = $(this).data("order-id");
  var order_sn = $(this).data("order-sn");
  var cancelable;


  // 將表格有cancelable之class的，包覆一個<a></a>
  $(".cell-content").each(function(){
    var strClass = $(this).parent().attr("class");
    if(strClass.indexOf('cancelable') >= 0){
      $(this).wrap('<a class="cancel" href="javascript:void(0)"></a>');
    }
  });

  // 取消訂購
  $('.cancel').click(function () {

    var buy_id = $(this).parent().data("buy-id");
    var order_id = $(this).parent().data("order-id");
    var order_sn = $(this).parent().data("order-sn");
    var minus_price = $(this).parent().data("price");
    var classIdSn = ".id-" + order_id + "sn-" + order_sn;
    var strClass = $(this).attr('class');

    //檢查是否已經付款 
    var paidFlg = (strClass.indexOf('paid-color') != -1)? true: false;
  
    if(confirm("確定要取消此筆訂單")){
      // deleteOrderInfo( buy_id, order_id, order_sn );
      // updateBuyInfo( buy_id, minus_price, paidFlg );
      $(classIdSn).remove();
    }
    console.log("order_id:"+order_id + " order_sn:"+order_sn);
  });

  function deleteOrderInfo( buy_id, order_id, order_sn ){
    var dataInput = {
      buyId : buy_id,
      orderId : order_id,
      orderSn : order_sn
    };
    console.log("order_sn:"+order_sn);
    $.ajax({
        url: 'ajax/deleteOrderInfo.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
          
            
        }// end of success


    });// end of ajax
  }

  function updateBuyInfo( buy_id, minus_price, paid_flg ){
    var dataInput = {
      buyId : buy_id,
      minusPrice : minus_price,
      paidFlg : paid_flg
    };
    console.log("flag:"+paid_flg);
    $.ajax({
        url: 'ajax/updateMoneyOfBuyInfo.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
          
            
        }// end of success


    });// end of ajax
  }

});