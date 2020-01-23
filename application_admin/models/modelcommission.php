<?php

class ModelCommission extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getCatLevelOne(){
        $query=$this->db->query("select * from category_level_1 where is_active=1 order by name asc");
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    function getCatLevelTwo(){
        $cat_level_two_html='<option value="">Please Select</option>';
        $cat_level_one= $this->input->post('cat_level_one');
        $cat_level_two= $this->input->post('cat_level_two');

        $query=$this->db->query("select * from category_level_2 where level1=$cat_level_one and is_active=1 order by name asc");
        if($query->num_rows() > 0){
             foreach ($query->result_array() as $cat){
                 if($cat_level_two==$cat['id']){
                     $cat_level_two_html.='<option value="'.$cat['id'].'" selected>'.$cat['name'].'</option>';
                 }else{
                     $cat_level_two_html.='<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
                 }

             }
        }
        echo $cat_level_two_html;
    }

    function checkExist($cat1,$cat2){
        $query = $this->db->query("select * from merchant_commission where cat_level1=$cat1 and cat_level2=$cat2");
        if($query->num_rows() > 0){
            return true;
        }
        return false;
    }

    function saveCommission($sv_data){
        $this->db->insert('merchant_commission',$sv_data);
        if($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        return false;
    }

    function editedCommission($id,$updateData){
        $this->db->where('id', $id);
        $this->db->update('merchant_commission', $updateData);
        if($this->db->trans_status()) {
            return $id;
        }
        return false;
    }

    function getCommssionDataSingle($id){
        $query = $this->db->query("select * from merchant_commission where id=$id");
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    function getList(&$config,&$start,&$param)
    {
        // GET DATA FROM GET/POST  OR   SESSION ====================
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
            $sortType    	= $this->nsession->get_param('ADMIN_category','sortType','DESC');
            $sortField   	= $this->nsession->get_param('ADMIN_category','sortField','id');
            $searchField 	= $this->nsession->get_param('ADMIN_category','searchField','');
            $searchString 	= $this->nsession->get_param('ADMIN_category','searchString','');
            $searchText  	= $this->nsession->get_param('ADMIN_category','searchText','');
            $searchFromDate = $this->nsession->get_param('ADMIN_category','searchFromDate','');
            $searchToDate  	= $this->nsession->get_param('ADMIN_category','searchToDate','');
            $searchAlpha  	= $this->nsession->get_param('ADMIN_category','searchAlpha','');
            $searchMode  	= $this->nsession->get_param('ADMIN_category','searchMode','STRING');
            $searchDisplay  = $this->nsession->get_param('ADMIN_category','searchDisplay',20);
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

        $this->nsession->set_userdata('ADMIN_MANAGEPOSITION', $sessionDataArray);
        //==============================================================
        $this->db->select('COUNT(merchant_commission.id) as TotalrecordCount');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->select('merchant_commission.*');
        $this->db->join('category_level_1', 'category_level_1.id = merchant_commission.cat_level1', 'left');
        $this->db->join('category_level_2', 'category_level_2.id = merchant_commission.cat_level2', 'left');;
        $recordSet = $this->db->get('merchant_commission');
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
        $this->db->select('merchant_commission.*,category_level_1.name as cat_level1_name,category_level_2.name as cat_level2_name');
        $this->db->join('category_level_1', 'category_level_1.id = merchant_commission.cat_level1', 'left');
        $this->db->join('category_level_2', 'category_level_2.id = merchant_commission.cat_level2', 'left');;
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->order_by('cat_level1_name','asc');
        $this->db->limit($config['per_page'],$start);
        $recordSet = $this->db->get('merchant_commission');
        //echo $this->db->last_query();
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

    function deleteCommission($id){
        $this->db->delete('merchant_commission', array('id' => $id));
        return true;
    }


}