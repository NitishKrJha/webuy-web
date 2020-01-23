<?php
class Commission extends CI_Controller
{



    function __construct()
    {
        parent::__construct();
        $this->controller = 'commission';
        $this->load->model(array('ModelCommission','ModelCategory'));
    }

    function index()

    {
        $UserID = $this->nsession->userdata('admin_session_id');
        $for = 0;
        $name = category;
        $y = $this->functions->acc_permission_r($UserID, $name, $for);
        //echo $y;exit;
        if ($y == 1) {


            $this->functions->checkAdmin($this->controller . '/');
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
            $param['searchDisplay'] = $this->input->request('searchDisplay', '20');
            $param['searchAlpha'] = $this->input->request('txt_alpha', '');
            $param['searchMode'] = $this->input->request('search_mode', 'STRING');

            $data['recordset'] = $this->ModelCommission->getList($config, $start, $param);
            $data['startRecord'] = $start;

            $this->pagination->initialize($config);

            $data['params'] = $this->nsession->userdata('ADMIN_category');
            $data['reload_link'] = base_url($this->controller . "/index/");
            $data['search_link'] = base_url($this->controller . "/index/0/1/");
            $data['add_link'] = base_url($this->controller . "/addedit/0/0/");
            $data['edit_link'] = base_url($this->controller . "/addedit/{{ID}}/0");
            $data['activated_link'] = base_url($this->controller . "/activate/{{ID}}/0");
            $data['inacttived_Link'] = base_url($this->controller . "/inactive/{{ID}}/0");
            $data['delete_link'] = base_url($this->controller . "/delete/{{ID}}/0");
            $data['showall_link'] = base_url($this->controller . "/index/0/1");
            $data['total_rows'] = $config['total_rows'];

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');

            $data['module'] = 'Merchant Commission Management';

            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'merchant_commission/index';
            $element_data['menu'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements, $element_data);
        } else {
            $this->nsession->set_userdata('errmsg', 'You are Not Authorized to Access this Option.');
            redirect(base_url($this->user));
        }

    }



    function addedit($id = 0)
    {

        $UserID = $this->nsession->userdata('admin_session_id');
        $for=1;
        $name=category;
        $y=$this->functions->acc_permission_r($UserID,$name,$for);
        //echo $y;exit;
        if($y==1){


            $this->functions->checkAdmin($this->controller.'/');
            //if add or edit
            $startRecord  	= 0;
            $contentId 		= $this->uri->segment(3, 0);
            $page 			= $this->uri->segment(4, 0);

            if($page > 0)
                $startRecord = $page;

            $page = $startRecord;

            $data['controller'] 		= $this->controller;
            $data['action'] 			= "Add";
            $data['params']['page'] 	= $page;
            $data['do_addedit_link']	= base_url($this->controller."/do_addedit/".$contentId."/".$page."/");
            $data['back_link']			= base_url($this->controller."/index/");
            $data['cat_level_one'] = $this->ModelCommission->getCatLevelOne();
            if($contentId > 0)
            {
                $data['adminpage_id'] = $contentId;
                $data['action'] = "Edit";
                //=================prepare DATA ==================
                $rs = $this->ModelCommission->getCommssionDataSingle($contentId);
                //$row = $rs->fields;

                    foreach($rs as $key => $val)
                    {
                        if(!is_numeric($key))
                        {
                            $data[$key] = $val;
                        }
                    }

            }else{
                $data['action'] 	= "Add";
                $data['id']			= 0;

            }

            $data['succmsg'] = $this->nsession->userdata('succmsg');
            $data['errmsg'] = $this->nsession->userdata('errmsg');
            $this->nsession->set_userdata('succmsg', "");
            $this->nsession->set_userdata('errmsg', "");

            $elements = array();
            $elements['menu'] = 'menu/index';
            $elements['topmenu'] = 'menu/topmenu';
            $elements['main'] = 'merchant_commission/add_edit';
            $element_data['main'] = $data;
            $element_data['main'] = $data;

            $this->layout->setLayout('layout_main_view');
            $this->layout->multiple_view($elements,$element_data);
        }
        else{
            $this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
            redirect(base_url($this->controller));
        }


    }


    function get_cat_level_two(){
        $this->ModelCommission->getCatLevelTwo();
    }

    function do_addedit()
    {
        $this->functions->checkAdmin($this->controller.'/');
        $contentId = $this->uri->segment(3, 0);
        $page = $this->uri->segment(4, 0);
        $comission_data = $_POST;
        if($contentId > 0)   //edit
        {
            $affected_row = $this->ModelCommission->editedCommission($contentId,$comission_data);
            $this->nsession->set_userdata('succmsg', 'Successfully commission Edited.');
            redirect(base_url($this->controller."/index/"));
            return true;
        }
        else    //add
        {
          $checkExist=$this->ModelCommission->checkExist($comission_data['cat_level1'],$comission_data['cat_level2']);
            if(!$checkExist){
              $insert_id = $this->ModelCommission->saveCommission($comission_data);
                if($insert_id)
                {
                    $this->nsession->set_userdata('succmsg', 'Successfully commission Added.');
                    redirect(base_url($this->controller."/index/"));
                    return true;
                }
            }else{
                $this->nsession->set_userdata('succmsg', 'Selected category is already in commission.');
                redirect(base_url($this->controller."/index/"));
                return true;
            }


        }
    }


    function delete()
    {

        $UserID = $this->nsession->userdata('admin_session_id');
        $for=2;
        $name=brands;
        $y=$this->functions->acc_permission_r($UserID,$name,$for);
        //echo $y;exit;
        if($y==1){

            $this->functions->checkAdmin($this->controller.'/');
            $id = $this->uri->segment(3, 0);
            $this->ModelCommission->deleteCommission($id);
            $this->nsession->set_userdata('succmsg', 'Commission Deleted.');

            redirect(base_url($this->controller."/index/"));
            return true;
        }
        else{
            $this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this brand.');
            redirect(base_url($this->controller));
        }
    }







}