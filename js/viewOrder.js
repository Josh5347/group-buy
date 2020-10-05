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
    if(confirm("確定要取消此筆訂單")){

      $(this).parent().remove();
    }
  });

  function deleteOrderInfo( buy_id, order_id, order_sn ){
    var dataInput = {
      buyId : buy_id,
      orderId : order_id,
      orderSn : order_sn,
      updateFlag : flag
    };
    // console.log("flag:"+flag);
    $.ajax({
        url: 'ajax/updateShippingDate.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
          
            
        }// end of success


    });// end of ajax
  }

  function updateBuyInfo( buy_id ){
    var dataInput = {
      buyId : buy_id,
      orderId : order_id,
      orderSn : order_sn,
      updateFlag : flag
    };
    // console.log("flag:"+flag);
    $.ajax({
        url: 'ajax/updateShippingDate.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
          
            
        }// end of success


    });// end of ajax
  }

});