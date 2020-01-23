<?php
class ModelOrders extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getAllOrders($customer_id)
    {
        $order_data = array();
        $orders_query = $this->db->query("SELECT * FROM orders WHERE customer_id =$customer_id order by id desc");

        if($orders_query->num_rows() > 0){
           foreach ($orders_query->result_array() as $order_details){
               $order_id = $order_details['id'];
               $order_status_id = $order_details['order_status'];
               $order_details['products'] = $this->db->query("SELECT opd.*,ost.status as order_status  FROM order_product_details as opd left join order_status as ost on ost.id=opd.status  WHERE opd.order_id =$order_id")->result_array();

               array_push($order_data,$order_details);
           }
        }
        return $order_data;
    }

    public function getOrderDetails($order_id)
    {
        $order_data = array();
        $orders_query = $this->db->query("SELECT * FROM orders WHERE id =$order_id");

        if($orders_query->num_rows() > 0){
            $order_details = $orders_query->row_array();
                $order_id = $order_details['id'];

                $order_details['products'] = $this->db->query("SELECT opd.*,prod.gst FROM order_product_details as opd left join product as prod on prod.product_id=opd.product_id  WHERE opd.order_id =$order_id")->result_array();

            return $order_details;
        }
        return false;
    }

    public function getOrderShippingDetails($order_id,$product){
       
        $orders_query = $this->db->query("SELECT * FROM orders WHERE id =$order_id");

        if($orders_query->num_rows() > 0){
            $order_details = $orders_query->row_array();           
               $order_id = $order_details['id'];
               $order_status_id = $order_details['order_status'];
               $order_details['products'] = $this->db->query("SELECT opd.*,ost.status as order_status,mbd.business_name  FROM order_product_details as opd left join order_status as ost on ost.id=opd.status left join merchants_business_details as mbd on mbd.merchants_id=opd.merchant_id  WHERE opd.order_id =$order_id and opd.product_id=$product")->result_array();
                 $product_image = $this->db->query("select path_sm from product_images where product_id=$product and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                $pic=css_images_js_base_url().'images/no_pr_img.jpg';
               }

               $order_details['pic']=$pic;
               return $order_details;          
        }
        return false;
    }

    public function getProductReviewDetails($product_id){
        $product_details=array();
        $customer_id = $this->nsession->userdata('member_session_id');
        $order_product_check_query = $this->db->query("select * from order_product_details where product_id=$product_id and member_id=$customer_id");
        if($order_product_check_query->num_rows() > 0){
            $product_details['details'] = $this->db->query("select * from product where product_id=$product_id")->row_array();

            $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
            if($product_image['path_sm']!=''){
                $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
            }else{
                $pic=css_images_js_base_url().'images/no_pr_img.jpg';
            }
            $product_details['pic_sm']=$pic;
            $product_details['total_rate']= $this->db->query("select sum(rate) as total_rate from product_rating where product_id=$product_id and is_active=1")->row()->total_rate;
            $product_details['total_customer'] = $this->db->query("select * from product_rating where product_id=$product_id and is_active=1")->num_rows();
        }

        return $product_details;

    }

    public function get_order_merchant_details($order_id){
        $order_merchant_query = $this->db->query("select mcr.* from order_product_details as opd right join merchants as mcr on mcr.merchants_id=opd.merchant_id where opd.order_id=$order_id group by opd.merchant_id");
        if($order_merchant_query->num_rows() > 0){
            return $order_merchant_query->result_array();
        }
        return false;
    }

     public function getReviewDetails($product_id){        
        $customer_id = $this->nsession->userdata('member_session_id');
        $review_query = $this->db->query("select * from product_rating where product_id=$product_id and customer_id=$customer_id");
        if($review_query->num_rows() > 0){
           return $review_query->row_array();
        }

        return false;
     }


}