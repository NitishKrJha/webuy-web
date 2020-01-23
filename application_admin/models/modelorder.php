<?php

class ModelOrder extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getList(&$config,&$start,&$param)
    {

        $Count = 0;
        $page = $this->uri->segment(3,0); // page
        $isSession = $this->uri->segment(4,0); // read data from SESSION or POST     (1 == POST , 0 = SESSION )

        $start = 0;

        $sortType 		= $param['sortType'];
        $sortField 		= $param['sortField'];
        $searchField 	= $param['searchField'];
        $searchString 	= $param['searchString'];
        $searchText 	= $param['searchText'];
        $searchFromDate	= $param['searchFromDate'];
        $searchToDate 	= $param['searchToDate'];
        $searchAlpha	= $param['searchAlpha'];
        $searchMode		= $param['searchMode'];
        $searchDisplay 	= $param['searchDisplay'];

        if($isSession == 0)
        {
            $sortType    	= $this->nsession->get_param('ADMIN_OWNER','sortType','DESC');
            $sortField   	= $this->nsession->get_param('ADMIN_OWNER','sortField','id');
            $searchField 	= $this->nsession->get_param('ADMIN_OWNER','searchField','');
            $searchString 	= $this->nsession->get_param('ADMIN_OWNER','searchString','');
            $searchText  	= $this->nsession->get_param('ADMIN_OWNER','searchText','');
            $searchFromDate = $this->nsession->get_param('ADMIN_OWNER','searchFromDate','');
            $searchToDate  	= $this->nsession->get_param('ADMIN_OWNER','searchToDate','');
            $searchAlpha  	= $this->nsession->get_param('ADMIN_OWNER','searchAlpha','');
            $searchMode  	= $this->nsession->get_param('ADMIN_OWNER','searchMode','STRING');
            $searchDisplay  = $this->nsession->get_param('ADMIN_OWNER','searchDisplay',10);
        }

        //========= SET SESSION DATA FOR SEARCH / PAGE / SORT Condition etc =====================
        $sessionDataArray = array();
        $sessionDataArray['sortType'] 		= $sortType;
        $sessionDataArray['sortField'] 		= $sortField;
        if($searchField!=''){
            $sessionDataArray['searchField'] 	= $searchField;
            $sessionDataArray['searchString'] 	= $searchString ;
        }
        $sessionDataArray['searchText'] 	= $searchText;
        $sessionDataArray['searchFromDate'] = $searchFromDate;
        $sessionDataArray['searchToDate'] 	= $searchToDate;
        $sessionDataArray['searchAlpha'] 	= $searchAlpha;
        $sessionDataArray['searchMode'] 	= $searchMode;
        $sessionDataArray['searchDisplay'] 	= $searchDisplay;

        $this->nsession->set_userdata('ADMIN_OWNER', $sessionDataArray);
        //==============================================================
        $this->db->select('COUNT(id) as TotalrecordCount');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->select('orders.*');

        $recordSet = $this->db->get('orders');
        $config['total_rows'] = 0;
        $config['per_page'] = $searchDisplay;
        if ($recordSet)
        {
            $row = $recordSet->row();
            $config['total_rows'] = $row->TotalrecordCount;
        }
        else
        {
            return false;
        }

        if($page > 0 && $page < $config['total_rows'] )
            $start = $page;
        $this->db->select('*');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }

        //$this->db->order_by($sortField,$sortType);
        $this->db->order_by('orders.id','desc');
        $this->db->limit($config['per_page'],$start);

        $recordSet = $this->db->get('orders');
        $rs = false;

        if ($recordSet->num_rows() > 0)
        {
            $rs = array();
            $isEscapeArr = array();
            foreach ($recordSet->result_array() as $row)
            {
                foreach($row as $key=>$val){
                    if(!in_array($key,$isEscapeArr)){
                        $recordSet->fields[$key] = outputEscapeString($val);
                    }
                }
                $rs[] = $recordSet->fields;
            }
        }
        else
        {
            return false;
        }
        return $rs;
    }


    public function getOrderDetails($order_id)
    {
        $order_data = array();

        $orders_query = $this->db->query("SELECT * FROM orders WHERE id =$order_id");
        if($orders_query->num_rows() > 0){
            $order_details = $orders_query->row_array();
            $order_id = $order_details['id'];
            $order_product_check_query = $this->db->query("SELECT * FROM order_product_details  WHERE order_id =$order_id");
            if($order_product_check_query->num_rows() > 0) {
                $product_details = array();
                foreach ($order_product_check_query->result_array() as $order_product){
                        $op_tmp = array();
                        $product_id = $order_product['product_id'];
                    $op_tmp['details'] = $this->db->query("select order_product_details.*,merchants.company_name,order_status.status as order_status from order_product_details left join merchants on merchants.merchants_id=order_product_details.merchant_id left join order_status on order_status.id=order_product_details.status  where order_product_details.product_id=$product_id and order_product_details.order_id=$order_id")->row_array();
                        $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                        if ($product_image['path_sm'] != '') {
                            $pic = file_upload_base_url() . 'product/' . $product_image['path_sm'];
                        } else {
                            $pic = css_images_js_base_url() . 'images/no_pr_img.jpg';
                        }
                    $op_tmp['pic_sm'] = $pic;
                    $op_tmp['total_rate'] = $this->db->query("select sum(rate) as total_rate from product_rating where product_id=$product_id and is_active=1")->row()->total_rate;
                    $op_tmp['total_customer'] = $this->db->query("select * from product_rating where product_id=$product_id and is_active=1")->num_rows();
                        array_push($product_details,$op_tmp);
                 }
                $order_details['product'] = $product_details;
            }

         return $order_details;
        }
        return false;
    }


}