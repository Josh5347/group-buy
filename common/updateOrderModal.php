<!-- The Modal -->
        <div class="modal" id="updateOrderModal">
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">改訂 <span id="orderer-modal-title"></span>  買的</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                <form method="post" action="<?= $_SERVER['PHP_SELF'].'?buy_id='.$_GET['buy_id'];?>" 
                class="user" >
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>名稱</th>
                        <th>價錢</th>
                      </tr>
                    </thead>

                    <tbody>
<?php
foreach( $arrayProducts as $rowProduct){
  /* 產品清單為類別 */
  if( '{' == substr($rowProduct, 0, 1)){
    
?>
                      <tr>
                        <td colspan="4" class="font-weight-bold text-primary">
                          <!-- 去除類別的{}字元 -->
                          <?= trim($rowProduct, "{}" );?>
                        </td>
                      </tr>
<?php
  }else{
    $rowOthers = preg_split("/[,]+/", $rowProduct);
      $i++; // 產品編號
  ?>
                      <tr>
                        <!-- 陣列中取出array[0]為產品名 -->
                        <td><?= $product = array_shift($rowOthers);?></td>
                        <td data-product-no="<?=$i;?>">
<?php
    $j = 0;
    foreach($rowOthers as $other){
      //比對如 " 中杯 40"，比對成功增加一個radio
      if(preg_match('/^(\s)*[(\x7f-\xff)a-zA-Z]+(\s)+[0-9]+/',$other)){
        $j++;
        $arrayOther = preg_split('/[\s]+/', $other);         
        $arrayPrice = preg_grep('/[0-9]+/', $arrayOther );
        $arraySubProduct = preg_grep('/[(\x7f-\xff)a-zA-Z]+/', $arrayOther );
        // 取得商品細項的價格
        $price = array_shift($arrayPrice);
        // 取得商品細項的名稱
        $subProduct = array_shift($arraySubProduct);
        // 輸入資料為 "產品名稱;產品編號;價格"
        printf("<input type='radio' id=%s name='product_info' value=%s />&nbsp%s&nbsp&nbsp&nbsp",
        $i.'-'.$j, $product.':'.$subProduct.';'.$i.'-'.$j.';'.$price, $other );
      }else{
        $other = str_replace(' ', '', $other);
        // 輸入資料為 "產品名稱;產品編號;價格"
        printf("<input type='radio' id=%s name='product_info' value=%s />&nbsp%s&nbsp&nbsp&nbsp",
        $i ,$product.';'.$i.';'.$other ,$other );
      }
    }

?>

                        </td>
                        
                      </tr>
<?php
  }/* end if-else */
}/* end foreach */
?>
                    </tbody>                    
                  </table>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <input type="text" name="explanation" class="form-control form-control-user" 
                      placeholder="額外訂購說明" />
                    </div>
                    <div class="col-md-8">
                        <button type="submit" name="create_order" class="btn btn-danger btn-user mr-3">改訂</button>
                        <button type="button" class="btn btn-outline-danger btn-user" data-dismiss="modal" >取消</button>
                    </div>
                    <input type='hidden' name='buy_id' id='buy_id' />
                    <input type='hidden' name='order_id' id='order_id' />
                    <input type='hidden' name='order_sn' id='order_sn' />
                    <input type='hidden' name='update' />
                  </div>
                </form>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
               
              </div>

            </div>
          </div>
        </div>
