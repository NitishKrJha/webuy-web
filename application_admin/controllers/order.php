<?php
//error_reporting(E_ALL);
class Order extends CI_Controller
{

    var $urlfix = "";

    function __construct()
    {
        parent::__construct();
        $this->controller = 'order';
        $this->load->model('ModelOrder');
    }

    function index()


    {

        $UserID = $this->nsession->userdata('admin_session_id');
        $name = product;
        $for = 0;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);

        if ($y == 1) {
            $this->functions->checkAdmin($this->controller . '/', true);

            $config['base_url'] = base_url($this->controller . "/index/");

            $config['uri_segment'] = 3;
            $config['num_links'] = 10;
            $config['page_query_string'] = false;
            $config['extra_params'] = "";
            $config['extra_params'] = "";

            $this->pagination->setAdminPaginationStyle($config);
            $start = 0;

            $data['controller'] = $this->controller;

            $param['sortType'] = $this->input->request('sortType', 'DESC');
            $param['sortField'] = $this->input->request('sortField', 'id');
            $param['searchField'] = $this->input->request('searchField', '');
            $param['searchString'] = $this->input->request('searchString', '');
            $param['searchText'] = $this->input->request('searchText', '');
            $param['searchFromDate'] = $this->input->request('searchFromDate', '');
            $param['searchToDate'] = $this->input->request('searchToDate', '');
            $param['searchDisplay'] = $this->input->request('searchDisplay', '10');
            $param['searchAlpha'] = $this->input->request('txt_alpha', '');
            $param['searchMode'] = $this->input->request('search_mode', 'STRING');

            $data['recordset'] = $this->ModelOrder->getList($config, $start, $param);

            $data['startRecord'] = $start;

            $this->pagination->initialize($config);

            $data['params'] = $this->nsession->userdata('ADMIN_merchants');
            $data['reload_link'] = base_url($this->controller . "/index/");
            $data['search_link'] = base_url($this->controller . "/index/0/1/");
            $data['add_link'] = base_url($this->controller . "/addedit/0/0/");
            $data['pwd_link'] = base_url($this->controller . "/change_password/{{ID}}/0");
            $data['edit_link'] = base_url($this->controller . "/addedit/{{ID}}/0");
            $data['activated_link'] = base_url($this->controller . "/activate/{{ID}}/0");
            $data['inacttived_Link'] = base_url($this->controller . "/inactive/{{ID}}/0");
            $data['feature_link'] = base_url($this->controller . "/feature/{{ID}}/0");
            $data['infeature_link'] = base_url($this->controller . "/infeature/{{ID}}/0");
            $data['showall_link'] = base_url($this->controller . "/index/0/1");
            $data['view_link'] = base_url($this->controller . "/viewdetails/{{ID}}");
            $data['total_rows'] = $config['total_rows'];

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'order/index';

            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }

    }

    function viewdetails($id){
        if($id){

            $data['order_details'] = $this->ModelOrder->getOrderDetails($id);
            //print_r($data['order_details']);
            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');
            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");
            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'order/view_details';
            $element_data['menu'] = $data;//array();
            $element_data['main'] = $data;
            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements,$element_data);
        }

    }






}