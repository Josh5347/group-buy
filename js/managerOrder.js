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
      totalPaid_td.text(totalPaid);
      totalUnPaid_td.text(totalUnPaid);
      sum_td.text(sum);
      $(this).parent().find(".orderer").data("total-paid", totalPaid);
      $(this).parent().find(".orderer").data("sum", sum);
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
      totalPaid_td.text(totalPaid);
      totalUnPaid_td.text(totalUnPaid);
      sum_td.text(sum);
      $(this).parent().find(".orderer").data("total-paid", totalPaid);
      $(this).parent().find(".orderer").data("sum", sum);
    }
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

});