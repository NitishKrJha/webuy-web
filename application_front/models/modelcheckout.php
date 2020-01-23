<?php
class ModelCheckout extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function checkCoupon($couponCode){
      $fl=1;
      $couponCode ="'".$couponCode."'";
    	$date = "'".date('Y-m-d')."'";
      $total_discount_price = 0;
      $total_product_price=0;
      $tot=0;
      $coupon_code_check_query = $this->db->query("select * from coupon where coupon_code=$couponCode and ( $date BETWEEN start_date AND end_date) and is_active=1");
    	if($coupon_code_check_query->num_rows() > 0){
         $coupon_details = $coupon_code_check_query->row();
         $coupon_apply_for = $coupon_details->discount_for;

         if($coupon_apply_for=='product'){          
              $cart_product_id_list = $this->_get_product_id_from_cart();
              $discount_product_ids = explode(",",$coupon_details->discount_select);
               foreach ($discount_product_ids as $prod_id) {
                 $total_product_price+= $this->_get_cart_product_price($prod_id);
               }           
         }elseif ($coupon_apply_for=='category') {
                $categories ="'".$coupon_details->discount_select."'"; 
                $discount_product_ids = $this->_get_product_id_using_categories_from_cart($categories);
                foreach ($discount_product_ids as $prod_id) {
                 $total_product_price+= $this->_get_cart_product_price($prod_id);
               }                
                
         }else{
          $discount_product_ids = $this->_get_product_id_from_cart();              
               foreach ($discount_product_ids as $prod_id) {
                 $total_product_price+= $this->_get_cart_product_price($prod_id);
                 $tot = $prod_id;
               }           
         }
         $total_payable_amount=0;
         $total_payable_amount = $this->get_total_payable_amount();
         if($coupon_details->discount_type=='percent'){
           $total_discount_price=$total_product_price * ($coupon_details->discount_value / 100);
         }elseif($total_payable_amount > $coupon_details->discount_value){
           $total_discount_price=$coupon_details->discount_value;
             $message='Coupon Code Applied Successfully';
         }else{
             $fl=0;
             $message='Given Coupon code is not applicable for your purchase item.';
         }

         $this->nsession->set_userdata('coupon',array('coupon_code'=>$couponCode,'discount_amount'=>$total_discount_price));          
        }else{
          $fl=0;
          $message='This coupon code is invalid or has expired.';
        }

        $cart_total_html = $this->get_checkout_cart_total_html($total_discount_price);

       header('Content-Type: application/json');
       echo json_encode( array('status'=>$fl,'message'=>$message,'cart_total_html'=>$cart_total_html) );    	
    	
    }

    public function get_total_payable_amount(){
      $coupon_discount_amount=0;
      $coupon_details=$this->nsession->userdata('coupon');
      $coupon_discount_amount=$coupon_details['discount_amount'];
      $cartDetails = $this->getUserCartDetails();
                          $cart_total_price_befor_discount=0;
                          $cart_total_price=0; 
                          $shipping_cost=0;                              
                          foreach ($cartDetails as $item) {
                            $product_id = $item->product_id;
                            $options = json_decode($item->options);                                
                            $productDetails = $this->db->query("select * from product where product_id=$product_id")->row();
                            $shipping_cost+=$item->shipping_cost*$item->qty;
                              $varient_name= array();
                              $varient_value = array();                              
                              foreach ($options as $key => $value) {                                     
                                    array_push($varient_name,$key);
                                    array_push($varient_value,$value);                                      
                              }

                              $varient_name ="'".json_encode($varient_name)."'";
                              $varient_value="'".json_encode($varient_value)."'";

                              $checkVarientPrice = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");

                              if($checkVarientPrice->num_rows()>0){
                                $price=$checkVarientPrice->row()->start_price;
                                $cart_total_price_befor_discount+=$checkVarientPrice->row()->start_price *$item->qty;
                              }else{
                                $cart_total_price_befor_discount+=$productDetails->purchase_price * $item->qty;
                                if ($productDetails->sale_price>0) {
                                    $price = $productDetails->sale_price;
                                }else{
                                    $price = $productDetails->purchase_price;
                                }
                              }

                              $cart_total_price+=$price*$item->qty;
                          }
                $cart_total_price = ($cart_total_price + $shipping_cost) - $coupon_discount_amount;

      return $cart_total_price;
    }


    public function get_checkout_cart_total_html($coupon_discount_amount){
                          $cartDetails = $this->getUserCartDetails();
                          $cart_total_price_befor_discount=0;
                          $cart_total_price=0; 
                          $shipping_cost=0;                                 
                          foreach ($cartDetails as $item) {
                            $product_id = $item->product_id;
                            $options = json_decode($item->options);                                
                            $productDetails = $this->db->query("select * from product where product_id=$product_id")->row();
                            $description = $productDetails->description;
                            $productImage = $this->db->query("select * from product_images where product_id=$product_id")->row();
                            if($productImage->path!=''){
                                $pic=file_upload_base_url().'product/'.$productImage->path;
                            }else{
                                $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                            }
                              $shipping_cost+=$item->shipping_cost*$item->qty;
                              $varient_name= array();
                              $varient_value = array();                              
                              foreach ($options as $key => $value) {                                     
                                    array_push($varient_name,$key);
                                    array_push($varient_value,$value);                                      
                              }

                              $varient_name ="'".json_encode($varient_name)."'";
                              $varient_value="'".json_encode($varient_value)."'";

                              $checkVarientPrice = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");

                              if($checkVarientPrice->num_rows()>0){
                                $price=$checkVarientPrice->row()->start_price;
                                $cart_total_price_befor_discount+=$checkVarientPrice->row()->start_price *$item->qty;
                              }else{
                                $cart_total_price_befor_discount+=$productDetails->purchase_price *$item->qty;
                                if ($productDetails->sale_price>0) {
                                    $price = $productDetails->sale_price;
                                }else{
                                    $price = $productDetails->purchase_price;
                                }
                              }

                              $cart_total_price+=$price*$item->qty;
                          }
          $html=' <h3>Price Details</h3>
                        <table class="table">
                            <tbody>
                            <tr>
                              <td class="text-left">Price ('.count($cartDetails).'items)</td>
                              <td class="text-right">₹'.number_format($cart_total_price,2).'</td>
                            </tr>
                            <tr>
                              <td class="text-left">Delivery Charges</td>';
                              $html.='<td class="text-right">';
                              if($shipping_cost>0){  $html.='<i class="fa fa-inr">'.number_format($shipping_cost,2);}else{
                                 $html.='Free';
                              }
                               $html.='</td>
                            </tr>';
                            if($coupon_discount_amount>0){
                                $html.='<tr>
                                          <td class="text-left">Coupon Discount</td>
                                          <td class="text-right">₹'.number_format($coupon_discount_amount,2).'</td>
                                        </tr>';
                              $cart_total_price = ($cart_total_price + $shipping_cost) - $coupon_discount_amount;          
                            }
                            $html.='<tr>
                              <td class="text-left">Amount Payable</td>
                              <td class="text-right">₹'.number_format($cart_total_price,2).'</td>
                            </tr>
                          </tbody>
                      </table>';

                      $save_price=0;
                       $save_price=$cart_total_price_befor_discount-$cart_total_price;
                       if($save_price>0){                        
                       $html.='<h4>Your Total Savings on this order ₹'.number_format($save_price,2).'</h4>';
                         }

                         return $html;

    }

    function getUserCartDetails(){
      $member_id=$this->nsession->userdata('member_session_id');
      $ip = $this->input->ip_address();
      if($member_id!='' && $member_id>0){
      $query = $this->db->query("select cart.*,product.shipping_cost from cart left join product on product.product_id=cart.product_id where cart.member_id=$member_id or cart.ip_address='".$ip."'");
      }else{
        $query = $this->db->query("select cart.*,product.shipping_cost from cart left join product on product.product_id=cart.product_id where cart.ip_address='".$ip."'");
      }

      $result=$query->result();
      return $result;
    }


    function _get_product_id_from_cart(){
      $ip = $this->input->ip_address();
      $customer_id=$this->nsession->userdata('member_session_id');
      if($customer_id!='' && $customer_id>0){
      $cart_query = $this->db->query("select * from cart where member_id=$customer_id");
      }else{
        $cart_query = $this->db->query("select * from cart where ip_address='".$ip."'");
      }

      $product_list = array();
      if($cart_query->num_rows() > 0){
        foreach ($cart_query->result() as $cart) {
          array_push($product_list,$cart->product_id);
        }
      }

      return array_unique($product_list);
    }

    function _get_product_id_using_categories_from_cart($categories){
      $ip = $this->input->ip_address();
      $customer_id=$this->nsession->userdata('member_session_id');
        if($customer_id!='' && $customer_id>0){
        $cart_query = $this->db->query("select product.product_id,product.cat_level1 from cart left join product on product.product_id=cart.product_id where cart.member_id=$customer_id and product.cat_level1 in ($categories)");
        }else{
          $cart_query = $this->db->query("select product.product_id,product.cat_level1 from cart left join product on product.product_id=cart.product_id where cart.ip_address='".$ip."' and product.cat_level1 in ($categories)");
        }

        $product_list = array();
        if($cart_query->num_rows() > 0){
          foreach ($cart_query->result() as $cart) {
            array_push($product_list,$cart->product_id);
          }
        }
      return array_unique($product_list);
    }

    

    function _get_cart_product_price($product_id){
      $total_price=0;
      $ip = $this->input->ip_address();
      $customer_id=$this->nsession->userdata('member_session_id');
        if($customer_id!='' && $customer_id>0){
        $cart_query = $this->db->query("select * from cart where member_id=$customer_id and product_id=$product_id");
        }else{
          $cart_query = $this->db->query("select * from cart where ip_address='".$ip."' and product_id=$product_id");
        }
      if($cart_query->num_rows()>0){
        foreach ($cart_query->result() as $cart) {          
          if($cart->options!=''){
            $options = array();
            $varient_name= array();
            $varient_value = array();
            $empty_varient = array();
            foreach (json_decode($cart->options) as $key => $value) {
                array_push($varient_name,$key);
                  array_push($varient_value,$value);   
               
            }
          $varient_name ="'".json_encode($varient_name)."'";
          $varient_value="'".json_encode($varient_value)."'";
          $checkQuantity = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");
          $product_varient_result = $checkQuantity->row();
          if(count($product_varient_result) > 0){
            $item_price=$product_varient_result->start_price;
          }
        }else{
          $checkQuantity = $this->db->query("select * from product where product_id=$product_id");
          $product_varient_result = $checkQuantity->row();
          if(count($product_varient_result) > 0){
            if($product_varient_result->sale_price>0){
             $item_price=$product_varient_result->sale_price;
            }else{
            $item_price=$product_varient_result->purchase_price;
              }
          }
        }
        $total_price+=$item_price*$cart->qty;
        }

      }
      return $total_price;
    }

    function _get_all_address(){
      $customer_id=$this->nsession->userdata('member_session_id');
      if($customer_id>0){
      $getAllAddressList = $this->db->query("select * from shipping_address where  customer_id=$customer_id");
      if($getAllAddressList->num_rows() > 0){
        return $getAllAddressList->result();
        }else{
          return false;
        }
      }else{
        return false;
      }

    }

    function save_rpay_details($rpay_details){
      $this->db->insert('razorpay_payment_details',$rpay_details);
      return $this->db->insert_id();
    }

    function save_order_details($order){
      $this->db->insert('orders',$order);
      if($this->db->insert_id()){
        return  $this->db->insert_id();
      }
      return false;
    }

    function _check_default_shipping_address(){
        $customer_id=$this->nsession->userdata('member_session_id');
            $getAllAddressList = $this->db->query("select * from shipping_address where  customer_id=$customer_id and status=1");
            if($getAllAddressList->num_rows() > 0){
                return true;
            }else{
                return false;
            }
    }




    

    
}