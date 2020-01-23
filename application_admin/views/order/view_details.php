<?php
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Order</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h4 class="m-t-0 m-b-30">View</h4>
                            <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
                            <form id="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data">
                                <h4 class="m-t-0 m-b-30">Order Details</h4>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Order ID: </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo $order_details['order_id']; ?> </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Order Date: </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"> <?php echo date("F j, Y, g:i a",$order_details['created_at']); ?> </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Shipping Details: </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php $shipping_address = json_decode($order_details['shipping_address']); ?>
                                        <?php  echo $shipping_address->full_name; ?><br><?php  echo $shipping_address->phone_number; ?>
                                        <?php if($shipping_address->address){ echo $shipping_address->address; } ?>
                                        <?php if($shipping_address->address2){ echo','.$shipping_address->address2; } ?><br>
                                        <?php if($shipping_address->city){ echo','. $shipping_address->city; } ?>
                                        <?php echo $shipping_address->state; ?> - <strong><?php echo $shipping_address->zipcode; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Product Details</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Details</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Merchant</th>
                                                <th >Status</th>
                                                <th >Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($order_details['product'] as $product){
                                            $pid=$product['details']['product_id'];
                                            $pic=$product['pic_sm'];

                                            ?>
                                            <tr>
                                                <td><img editable="" style="display:block;" src="<?php echo $pic; ?>" width="100"></td>
                                                <td><?php echo $product['details']['name'];  ?></td>
                                                <td><?php echo $product['details']['quantity'];  ?></td>
                                                <td>Rs.<?php echo $product['details']['price'];  ?></td>
                                                <td><?php echo $product['details']['company_name'];  ?></td>
                                                <td><?php echo $product['details']['order_status'];  ?></td>
                                                <td>
                                                    <?php echo date("d F Y h:i:s a", strtotime($product['details']['status_change_date'])); ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Payment Details: </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <table class="table table-bordered">
                                            <tr><th>Payment Method</th><td><?php if($order_details['payment_method']=='rpay'){ echo'Razorpay'; } ?><?php if($order_details['payment_method']=='wallet'){ echo'Wallet'; } ?><?php if($order_details['payment_method']=='paytm'){ echo'Paytm'; } ?></td></tr>
                                            <tr><th>Subtotal</th><td>Rs.<?php echo number_format($order_details['amount'] - $order_details['shipping_cost'],2); ?></td></tr>
                                             <?php if($order_details['shipping_cost']!='' && $order_details['shipping_cost'] > 0){ ?>
                                            <tr><th>Shipping Cost</th><td>Rs.<?php echo number_format($order_details['shipping_cost'],2); ?></td></tr>
                                            <?php } ?>
                                            <?php if($order_details['coupon_discount']!='' && $order_details['coupon_discount'] > 0){ ?>
                                            <tr><th>Coupon Discount</th><td>Rs.<?php echo number_format($order_details['coupon_discount'],2); ?></td></tr>
                                            <?php } ?>
                                            <tr><th>Total</th><td>Rs.<?php echo number_format($order_details['amount'],2); ?></td></tr>
                                        </table>
                                    </div>
                                </div>





                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a class="btn btn-primary" href="javascript:window.history.back();">Back</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>