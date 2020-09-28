$(function () {

  $('.orderer').click(function () {
    var paid = $(this).data("paid");
    var buy_id = $(this).data("buy-id");
    var order_id = $(this).data("order-id");
    var order_sn = $(this).data("order-sn");
    var totalRowPaid = $(this).parent().find(".paid-row-sum").text();  
    var totalRowPaid_text = $(this).parent().find(".paid-row-sum");
    var totalPaid = parseInt($(this).data("total-paid"));
    var sum = parseInt($(this).data("sum"));
    var price = parseInt($(this).data('price'));
    var totalUnPaid = 0;
    var totalPaid_td = $('.total-paid');
    var totalUnPaid_td = $('.total-unpaid');
    var sum_td = $('.sum');
    

    totalRowPaid = parseInt(totalRowPaid);
    console.log("price:"+price);
    console.log(totalRowPaid);
    if(paid == '0'){
      // 付款
      console.log("付款");
      $(this).removeClass("unpaid-color");
      $(this).addClass("paid-color");
      // 更改DB之已付款flag
      updatePaidOfOrderInfo(buy_id, order_id, order_sn, 1);
      totalRowPaid_text.text(++totalRowPaid);
      $(this).data("paid","1");
      totalPaid += price;
      totalUnPaid = sum - totalPaid;

    }else{
      // 取消付款
      console.log("取消");
      $(this).removeClass("paid-color");
      $(this).addClass("unpaid-color");
      // 更改DB之已付款flag
      updatePaidOfOrderInfo(buy_id, order_id, order_sn, 0);
      totalRowPaid_text.text(--totalRowPaid);
      $(this).data("paid","0");
      totalPaid -= price;
      totalUnPaid = sum - totalPaid;
    }

    // 更新資料表buy_info的total_paid
    updateTotalPaidOfBuyInfo(buy_id, totalPaid);
    // 更新網頁 "金額計算" 欄中的已付及剩下金額
    totalPaid_td.text(totalPaid);
    totalUnPaid_td.text(totalUnPaid);
    sum_td.text(sum);
    // 更新網頁中的data-total-paid值
    $(".orderer").each(function(){
      $(this).data("total-paid", totalPaid);
    });

  });

  /* 修改訂單中，若選擇改訂項目，傳送資料至改訂Modal中 */
  $('#editOrderModal').on('show.bs.modal', function (event) {

    var titleVal = $(event.relatedTarget).data('orderer');
    // 欲修改項目的buy_id
    var buy_id =  $(event.relatedTarget).data('buy-id');
    // 欲修改項目的order_id
    var order_id = $(event.relatedTarget).data('order-id');
    // 欲修改項目的order_sn
    var order_sn =  $(event.relatedTarget).data('order-sn');
    // 欲修改項目的product_no
    var product_no =  $(event.relatedTarget).data('product-no');

    //console.log("product_no:"+product_no);
    $(this).find("#orderer-modal-title").text(titleVal);
    // input type=hidden之值
    $(this).find("#buy_id").val(buy_id);
    $(this).find("#order_id").val(order_id);
    $(this).find("#order_sn").val(order_sn);
    // 欲修改項目原本之產品所屬的radio設定為checked
    $(this).find('#'+product_no).prop("checked", true);

  });

  /* 刪除模式 */
  $('.btn-delete').click(function () {
    if(confirm("確定要刪除嗎?")){
      $(this).parent().submit();
    }
  });

  /* 出貨狀態 */
  // 當按下出貨狀態時，重新整理頁面，以獲得最新資料庫資料
  $('#collapse6').click(function () {
    $('#shipping').submit();
  })

  // 按下出貨日期，更新出貨日期
  $('.shipping').click(function () {
    var shippingDate = $(this).data("shipping-date");
    var buy_id = $(this).data("buy-id");
    var order_id = $(this).data("order-id");
    var order_sn = $(this).data("order-sn");
    var date = new Date();
    var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    var today = date.getFullYear() + '-' + months[date.getMonth()] + '-' + date.getDate();
    // console.log("shippingDate:"+shippingDate);
    // console.log("today:"+today);
    if(shippingDate == ''){
      // 更新出貨日為今日
      updateShippingDate(buy_id, order_id, order_sn, 1);
      $(this).data("shipping-date", today);
      $(this).find(".shipping-date").text(today);
    }else{
      // 更新出貨日為空白
      updateShippingDate(buy_id, order_id, order_sn, 0);
      $(this).data("shipping-date", "");
      $(this).find(".shipping-date").text("");
    }
  });

  /* 批次出貨 */
  $('.batch-shipping').click(function () {
    $(this).parent().find('.shipping').each(function () {
      $(this).click();
      // console.log( $(this).data("shipping-date"));
    })
  });

  function updatePaidOfOrderInfo(buy_id, order_id, order_sn, paid){
      var dataInput = {
        buyId : buy_id,
        orderId : order_id,
        orderSn : order_sn,
        paid: paid
    };

    $.ajax({
        url: 'ajax/updatePaid.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
            
        }// end of success


    });// end of ajax
  }

  function updateTotalPaidOfBuyInfo(buy_id,  total_paid){
    var dataInput = {
      buyId : buy_id,
      totalPaid: total_paid
    };

    $.ajax({
        url: 'ajax/updateTotalPaid.php',
        data: dataInput,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            console.log(data);
            
        }// end of success


    });// end of ajax
  }

  function updateShippingDate(buy_id, order_id, order_sn, flag){
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

function ScrollToBottom() {
  window.scrollTo(0,document.querySelector(".scrollingContainer").scrollHeight);  
}