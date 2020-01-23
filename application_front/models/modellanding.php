<?php
class ModelLanding extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function getLeftAdSection(){
        $query = $this->db->query("select * from landing_left_adsection where is_active=1 order by sort_num asc limit 0,4");
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    function getRightAdSection(){
        $query = $this->db->query("select * from landing_right_adsection where is_active=1 order by sort_num asc limit 0,6");
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    function getMobileOperator(){
         $query = $this->db->query("select * from mobile_oparetor order by id asc");
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    function getCategoryWiseProduct(){
        $result_data=array();
         $query=$this->db->query("select * from category_wise_product where is_active=1 order by sort_num asc");
         if($query->num_rows()>0){
          foreach ($query->result_array() as $catwise_product) {
            $level1='';
            $level2='';
            if($catwise_product['level2']!=''){
                 $level1=$catwise_product['level1'];
                 $level2=$catwise_product['level2'];
                 $result=$this->db->query("SELECT *, (purchase_price - sale_price) * 100 / purchase_price AS discount FROM product WHERE cat_level1=$level1 and  cat_level2=$level2 and is_active=1 and admin_approval=1 and purchase_price > 0 and ((purchase_price - sale_price) * 100 / purchase_price) > 0 ORDER BY discount DESC limit 0,20")->result_array();
             }else{
                 $level1=$catwise_product['level1'];
                 $result=$this->db->query("SELECT *, (purchase_price - sale_price) * 100 / purchase_price AS discount FROM product WHERE cat_level1=$level1 and  is_active=1 and admin_approval=1 and purchase_price > 0 and ((purchase_price - sale_price) * 100 / purchase_price) > 0 ORDER BY discount DESC limit 0,20")->result_array();
             }
            
                if(count($result) > 0){
                    foreach ($result as $key => $value) {
                        $pic=$this->db->select('path,path_sm')->get_where('product_images',array('type'=>'main','product_id'=>$value['product_id']))->row_array();
                        if(isset($pic['path'])){
                            if($pic['path_sm']!=''){
                                $result[$key]['pic']=file_upload_base_url().'product/'.$pic['path_sm'];
                            }else{
                                $result[$key]['pic']=file_upload_base_url().'product/'.$pic['path'];
                            }
                        }else{
                            $result[$key]['pic']=css_images_js_base_url().'images/no_pr_img.jpg';
                        }

                        $rating=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$value['product_id']))->row_array();
                        if(isset($rating['avg_rate'])){
                            $result[$key]['rating']=round($rating['avg_rate']);
                        }else{
                            $result[$key]['rating']=0;
                        }
                    }
                }
                $catwise_product['product']=$result;

          array_push($result_data,$catwise_product);
         }

         return $result_data;
      }

      return false;
    }





}