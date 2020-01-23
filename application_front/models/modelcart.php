<?php
class ModelCart extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function getUserCartDetails($member_id,$ip){
    	
    	if($member_id!='' && $member_id>0){
			$query = $this->db->query("select cart.*,product.shipping_cost from cart left join product on product.product_id=cart.product_id where cart.member_id=$member_id or cart.ip_address='".$ip."'");
			}else{
				$query = $this->db->query("select cart.*,product.shipping_cost from cart left join product on product.product_id=cart.product_id where cart.ip_address='".$ip."'");
			}
    	$result=$query->result();
    	return $result;
    }

    function remove_cart($cart_id){
    	$this->db->where('id',$cart_id);
        $this->db->delete('cart');
    }

    function update_cart($cart_id,$data){         
    	 $this->db->where('id', $cart_id);
         $this->db->update('cart', $data);        
      }

    function checkCartQuantity($cart_id){
    	$cartDetails = $this->db->query("select * from cart where id=$cart_id")->row();
    	$product_id = $cartDetails->product_id;
      $checkVarientProduct = $this->db->query("select * from product_more where product_id=$product_id");
          if($checkVarientProduct->num_rows() > 0){ 
    			$ex_cart_varient_name = array();
                $ex_cart_varient_value=array();
                foreach(json_decode($cartDetails->options) as $key=>$value){
                        array_push($ex_cart_varient_name,$key);
                        array_push($ex_cart_varient_value,$value);
                }

          $ex_cart_varient_name ="'".json_encode($ex_cart_varient_name)."'";
          $ex_cart_varient_value="'".json_encode($ex_cart_varient_value)."'";      
          $checkQuantity=0;
          $checkQuantity = $this->db->query("select quantity from product_more where variation_name=$ex_cart_varient_name and variation_value=$ex_cart_varient_value and product_id=$product_id")->row()->quantity;
          return $checkQuantity;

        }else{
          $checkQuantity = $this->db->query("select quantity from product where product_id=$product_id")->row()->quantity;
          return $checkQuantity;
        }



    }

    function add_to_cart($data){
      $this->db->insert('cart',$data);
      if($this->db->insert_id() > 0){
        return true;
      }
      return false;
    }

    function add_cart_review($data){
      $this->db->insert('cart_review',$data);
      if($this->db->insert_id() > 0){
        return true;
      }
      return false;
    }

    
}