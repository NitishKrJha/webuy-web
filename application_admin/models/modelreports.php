<?php
class ModelReports extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_sales_report_data(){
        $order_product= array();
        $order_product_query = $this->db->query("select opd.*,odr.id as order_id,odr.name as customer_name,odr.phone,odr.shipping_address,odr.created_at,ods.status as order_status,odr.payment_method,odr.payment_status,mer.business_name from order_product_details as opd  left join orders as odr on odr.id=opd.order_id left join order_status as ods on ods.id=opd.status left join merchants_business_details as mer on mer.merchants_id=opd.merchant_id order by opd.id desc ");
        if($order_product_query->num_rows() > 0){
            foreach ($order_product_query->result_array() as $ordproduct){
                $product_id = $ordproduct['product_id'];
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $ordproduct['pic_sm']=$pic;

                array_push($order_product,$ordproduct);
            }

            return $order_product;
        }
        return false;
    }


    function  get_customer_wise_report_data(){
        $order_product= array();
        $customer_order_query = $this->db->query("select * from orders order by id desc");
        if($customer_order_query->num_rows()>0){
            return $customer_order_query->result_array();
        }
        return false;
    }

    function get_delivery_reports(){
        $order_product= array();
        $order_product_query = $this->db->query("select opd.*,odr.id as order_id,odr.name as customer_name,odr.phone,odr.shipping_address,odr.created_at,ods.status as order_status,odr.payment_method,odr.payment_status,mer.business_name from order_product_details as opd  left join orders as odr on odr.id=opd.order_id left join order_status as ods on ods.id=opd.status left join merchants_business_details as mer on mer.merchants_id=opd.merchant_id order by opd.id desc ");
        if($order_product_query->num_rows() > 0){
            foreach ($order_product_query->result_array() as $ordproduct){
                $product_id = $ordproduct['product_id'];
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $ordproduct['pic_sm']=$pic;

                array_push($order_product,$ordproduct);
            }

            return $order_product;
        }
        return false;
    }
    function getOrderStatusValue(){
        $this->db->select('*');
        $this->db->from('order_status');
        return $this->db->get()->result_array();
    }
    function get_stock_report_data(){
        $data=array();
        //$all_product=$this->db->query("select prod.*,prodm.start_price as pmprice,prodm.quantity as pmquantity,prodm.variation_name,prodm.variation_value,catl1.name as catl1_name,catl2.name as catl2_name,catl3.name as catl3_name,catl4.name as catl4_name from product as prod  left join product_more as prodm on prodm.product_id=prod.product_id left join category_level_1 as catl1 on catl1.id=prod.cat_level1 left join category_level_2 as catl2 on catl2.id=prod.cat_level2 left join category_level_3 as catl3 on catl3.id=prod.cat_level3 left join category_level_4 as catl4 on catl4.id=prod.cat_level4 where prod.is_active=1 and prod.created_by=$merchants_id");
        $all_product=$this->db->query("select prod.*,catl1.name as catl1_name,catl2.name as catl2_name,catl3.name as catl3_name,catl4.name as catl4_name from product as prod  left join category_level_1 as catl1 on catl1.id=prod.cat_level1 left join category_level_2 as catl2 on catl2.id=prod.cat_level2 left join category_level_3 as catl3 on catl3.id=prod.cat_level3 left join category_level_4 as catl4 on catl4.id=prod.cat_level4 where prod.is_active=1");
        if($all_product->num_rows() >0){
                foreach ($all_product->result_array() as $row) {
                    $pid=$row['product_id'];
                    if($row['brand']){
                        $brand_id=$row['brand'];
                        $row['brand'] = $this->db->query("select name from brands where id=$brand_id")->row()->name;
                    }
                    $product_more_query = $this->db->query("select * from product_more where product_id=$pid");
                    if($product_more_query->num_rows() > 0){
                        foreach ($product_more_query->result_array() as $row2){
                            $row['variation_name'] = $row2['variation_name'];
                            $row['variation_value'] = $row2['variation_value'];
                            $row['quantity'] = $row2['quantity'];
                            $row['start_price'] = $row2['start_price'];
                            array_push($data,$row);
                        }
                    }else{
                        $row['variation_name'] = '';
                        $row['variation_value'] = '';
                        $row['start_price'] = '';
                        array_push($data,$row);
                    }
                }
                // pr($data);exit;
                return $data;

         }
         return false;
    }
    function getMerchantsName($id){
        $this->db->select('*');
        $this->db->from('merchants');
        $this->db->where('merchants_id',$id);
        return $this->db->get()->row_array();
    
    }
    function getAllMerchants(){
        $this->db->select('*');
        $this->db->from('merchants');
        return $this->db->get()->result_array();
    }
    function get_merchants_reports(){
        $this->db->select('*');
        $this->db->from('order_product_details');
        return $this->db->get()->result_array();
    }


}