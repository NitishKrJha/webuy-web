<?php
//error_reporting(E_ALL);
class Reports extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->controller = 'reports';
        $this->load->model('ModelReports');
    }

    public function sales(){
        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'reports/sales';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }
    }

    public function get_sales_report_data(){
        $data = array();
        $order_data=$this->ModelReports->get_sales_report_data();
        if(count($order_data) > 0){
            foreach ($order_data as $row){
                $nestedData=array();
                $shipping_address = json_decode($row['shipping_address']);
                $sadd='';
                if($shipping_address->address){ $sadd.=$shipping_address->address.'<br>'; }
                if($shipping_address->address2){ $sadd.=','.$shipping_address->address2.'<br>'; }
                if($shipping_address->city){ $sadd.=','. $shipping_address->city; }
                $sadd.=$shipping_address->state.'-'.$shipping_address->zipcode;

                $nestedData[] = $row["order_id"];
                $nestedData[] = date("m/d/Y", $row['created_at']);
                $nestedData[] = $row["customer_name"];
                $nestedData[] = $row["phone"];
                $nestedData[] = $shipping_address->city;
                $nestedData[] = $sadd;
                $nestedData[] = $row["payment_method"];
                $nestedData[] = $row["payment_status"];
                $nestedData[] = $row["order_status"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["qty"];
                $nestedData[] = $row["price"];
                $nestedData[] = $row["business_name"];

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => 1,
            "recordsTotal"    => intval( count($order_data) ),
            "recordsFiltered" => intval(count($order_data)),
            "data"            => $data
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }


    public function customer(){
        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'reports/customer';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }
    }

    function get_customer_wise_report(){
        $data = array();
        $order_data=$this->ModelReports->get_customer_wise_report_data();
        // echo "<pre>";
        // print_r($order_data);
        if(count($order_data) > 0) {
            foreach ($order_data as $row) {
                $nestedData=array();
                $nestedData[] = $row["id"];
                $nestedData[] = $row["customer_id"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["amount"];
                $nestedData[] = date("m/d/Y", $row['created_at']);
                $nestedData[] = ucfirst($row["payment_method"]);
                $nestedData[] = 'Paid';
                $nestedData[] = $row["coupon_discount"]+$row["amount"];
                $nestedData[] = $row["coupon_discount"];
                $nestedData[] = $row["amount"];


                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => 1,
            "recordsTotal"    => intval( count($order_data) ),
            "recordsFiltered" => intval(count($order_data)),
            "data"            => $data
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function delivery(){
        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);
            $data['orderStatus']= $this->ModelReports->getOrderStatusValue();
            // pr($data['orderStatus']);
            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'reports/delivery';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }
    }

    function get_delivery_reports(){
        $data = array();
        $order_data=$this->ModelReports->get_sales_report_data();
        if(count($order_data) > 0){
            foreach ($order_data as $row){
                $nestedData=array();
                $shipping_address = json_decode($row['shipping_address']);
                $sadd='';
                if($shipping_address->address){ $sadd.=$shipping_address->address.'<br>'; }
                if($shipping_address->address2){ $sadd.=','.$shipping_address->address2.'<br>'; }
                if($shipping_address->city){ $sadd.=','. $shipping_address->city; }
                $sadd.=$shipping_address->state.'-'.$shipping_address->zipcode;

                $nestedData[] = $row["order_id"];
                $nestedData[] = date("m/d/Y", $row['created_at']);
                $nestedData[] = $row["order_status"];
                $nestedData[] = $row["customer_name"];
                $nestedData[] = $row["phone"];
                $nestedData[] = $shipping_address->city;
                $nestedData[] = $sadd;
                $nestedData[] = $row["payment_method"];
                $nestedData[] = $row["payment_status"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["qty"];
                $nestedData[] = $row["price"];
                $nestedData[] = $row["business_name"];

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => 1,
            "recordsTotal"    => intval( count($order_data) ),
            "recordsFiltered" => intval(count($order_data)),
            "data"            => $data
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }
    public function stock_report(){
        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'reports/stock_report';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }
    }

    public function get_stock_report_data(){
        $data=array();
        $merchants_id=$this->nsession->userdata('merchants_session_id');
        $inventory_stock_data=$this->ModelReports->get_stock_report_data();
        if(count($inventory_stock_data) > 0){
            foreach ($inventory_stock_data as $row){
                $nestedData=array();
                $nestedData[] = $row["product_id"];
                $nestedData[] = $row["sku"];
                $nestedData[] = $row["title"];
                $nestedData[] = $row["brand"];
                $variation_details='';
                if($row["variation_name"]!=''){
                    $variation_name= json_decode($row["variation_name"]);
                    $variation_value= json_decode($row["variation_value"]);
                    $i=0;
                    foreach ($variation_name as $vart){
                        $variation_details.=$vart.':'.$variation_value[$i].'<br>';
                        $i++;
                    }
                }
                $merchant_name= $this->ModelReports->getMerchantsName($row["created_by"]);
                
                $nestedData[] = $row["purchase_price"];
                $nestedData[] = $row["quantity"];
                $nestedData[] = $row["created_by"];
                $nestedData[] = $merchant_name["first_name"].' '.$merchant_name["last_name"];
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => 1,
            "recordsTotal"    => intval( count($data) ),
            "recordsFiltered" => intval(count($data)),
            "data"            => $data
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);

    }
    public function merchants(){
        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);
            $data['merchantsData']= $this->ModelReports->getAllMerchants();
            // pr($data['orderStatus']);
            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'reports/merchants';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }
    }

    function get_merchants_reports(){
        $data = array();
        $order_data=$this->ModelReports->get_merchants_reports();
        if(count($order_data) > 0){
            foreach ($order_data as $row){
                $nestedData=array();
                $shipping_address = json_decode($row['shipping_address']);
                $sadd='';
                if($shipping_address->address){ $sadd.=$shipping_address->address.'<br>'; }
                if($shipping_address->address2){ $sadd.=','.$shipping_address->address2.'<br>'; }
                if($shipping_address->city){ $sadd.=','. $shipping_address->city; }
                $sadd.=$shipping_address->state.'-'.$shipping_address->zipcode;

                $nestedData[] = $row["product_id"];
                $nestedData[] = $row["merchant_id"];
                $nestedData[] = date('m/d/Y', strtotime($row['status_change_date']));
                $nestedData[] = $row["name"];
                $nestedData[] = $row["price"];

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => 1,
            "recordsTotal"    => intval( count($order_data) ),
            "recordsFiltered" => intval(count($order_data)),
            "data"            => $data
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

}