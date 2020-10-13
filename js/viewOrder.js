$(function () {

  /* var buy_id = $(this).data("buy-id");
  var order_id = $(this).data("order-id");
  var order_sn = $(this).data("order-sn"); */

  // $("tr td.one:nth-child(1)").addClass("col1");
  
  $('#detailTable').DataTable(
    {
      "lengthMenu": [[-1], ["All"]]
  } 
  );

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
    var classIdSn = ".id-" + order_id + "sn-" + order_sn;
    var minus_price = $(this).parent().data("price");
    // 按件統計之資料
    var amountRowSum_text = $(classIdSn).parent().find('.amount-row-sum');
    var amountRowSum = parseInt(amountRowSum_text.text());
    // 按人統計之資料
    var amountRowSumByOrder_text = $(classIdSn).parent().find('.amount-row-sum-byOrder');
    var amountRowSumByOrder = parseInt(amountRowSumByOrder_text.text());
    var priceRowSumByOrder_text = $(classIdSn).parent().find('.price-row-sum-byOrder');
    var priceRowSumByOrder = parseInt($(priceRowSumByOrder_text).text());
    // 出貨狀態之資料
    var amountRowSumShipping_text = $(classIdSn).parent().find('.amount-row-sum-shipping');
    var amountRowSumShipping = parseInt(amountRowSumShipping_text.text());

    var strClass = $(this).attr('class');

    console.log("amountRowSum="+amountRowSum);
    console.log("amountRowSumByOrder="+amountRowSumByOrder);
    console.log("priceRowSumByOrder="+priceRowSumByOrder);
    console.log("amountRowSumShipping="+amountRowSumShipping);
    //檢查是否已經付款 
    var paidFlg = (strClass.indexOf('paid-color') != -1)? true: false;
    
    if(confirm("確定要取消此筆訂單")){
      // deleteOrderInfo( buy_id, order_id, order_sn );
      // updateBuyInfo( buy_id, minus_price, paidFlg );
      // 按件統計之產品數量加總
      amountRowSum_text.text(--amountRowSum);
      // 按人統計之訂購數量加總
      amountRowSumByOrder_text.text(--amountRowSumByOrder);
      // 按人統計之訂購金額加總
      priceRowSumByOrder_text.text(priceRowSumByOrder-minus_price);
      // 出貨狀態之每訂購人訂購總數
      amountRowSumShipping_text.text(--amountRowSumShipping);
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