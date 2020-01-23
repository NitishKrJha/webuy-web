<?php

class ModelLanding extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function getLeftAdList(&$config,&$start,&$param)
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
            $sortType    	= $this->nsession->get_param('ADMIN_ADSECTION','sortType','DESC');
            $sortField   	= $this->nsession->get_param('ADMIN_ADSECTION','sortField','id');
            $searchField 	= $this->nsession->get_param('ADMIN_ADSECTION','searchField','');
            $searchString 	= $this->nsession->get_param('ADMIN_ADSECTION','searchString','');
            $searchText  	= $this->nsession->get_param('ADMIN_ADSECTION','searchText','');
            $searchFromDate = $this->nsession->get_param('ADMIN_ADSECTION','searchFromDate','');
            $searchToDate  	= $this->nsession->get_param('ADMIN_ADSECTION','searchToDate','');
            $searchAlpha  	= $this->nsession->get_param('ADMIN_ADSECTION','searchAlpha','');
            $searchMode  	= $this->nsession->get_param('ADMIN_ADSECTION','searchMode','STRING');
            $searchDisplay  = $this->nsession->get_param('ADMIN_ADSECTION','searchDisplay',20);
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
        $this->db->select('COUNT(id) as TotalrecordCount');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->select('	landing_left_adsection.*');

        $recordSet = $this->db->get('	landing_left_adsection');
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
        $this->db->select('landing_left_adsection.*');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }

        $this->db->order_by('sort_num','asc');
        $this->db->limit($config['per_page'],$start);
        $recordSet = $this->db->get('landing_left_adsection');
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

    function leftaddContent($data)
    {

        $max_num=$this->db->select('MAX(sort_num) as max_num')->get_where('landing_left_adsection')->row()->max_num;
        $data['sort_num']=(int)$max_num + 1;

        $insert_data= array(
            'title'=>$data['title'],
            'sort_num'=>$data['sort_num'],
            'icon'=>$data['icon'],
            'page_url'=>$data['page_url']
        );
        $this->db->insert('landing_left_adsection',$insert_data);
        $last_insert_id = $this->db->insert_id();

        if($last_insert_id)
        {
            return $last_insert_id;
        }
        else
        {
            return false;
        }
    }

    function lefteditContent($id,$data)
    {
        $update_data= array(
            'title'=>$data['title'],
            'page_url'=>$data['page_url'],
            'icon'=>$data['icon'],
            'icon_app'=>$data['icon_app']
        );
        $this->db->where('id', $id);
        $this->db->update('landing_left_adsection', $update_data);
        $affected_rows =$this->db->affected_rows();

        if($result)
        {
            return $affected_rows;
        }
        else
        {
            return false;
        }
    }

    function leftdelete($id)
    {
        $this->db->delete('landing_left_adsection', array('id' => $id));
        return true;
    }

    function getSingleLeft($id)
    {
        $sql = "SELECT * FROM landing_left_adsection WHERE id = ".$id;
        $recordSet = $this->db->query($sql);

        $rs = false;
        if ($recordSet->num_rows() > 0)
        {
            $rs = array();
            $isEscapeArr = array('icon');
            foreach ($recordSet->result_array() as $row)
            {
                foreach($row as $key=>$val){
                    if(!in_array($key,$isEscapeArr)){
                        $recordSet->fields[$key] = outputEscapeString($val);
                    }else{
                        $recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
                    }
                }
                $rs[] = $recordSet->fields;
            }
        }
        return $rs;
    }
    function activateleft($id)
    {
        $sql = "UPDATE landing_left_adsection SET is_active = '1' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }
    function inactiveleft($id)
    {
        $sql = "UPDATE landing_left_adsection SET is_active = '0' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }

    function changeorderLeft($val,$id){
        $result=array();
        $result['intrchng_id']='0';
        $result['intrchng_val']='0';
        $getFaq=$this->db->select('id,sort_num')->get_where('landing_left_adsection',array('id'=>$id))->row_array();
        if(count($getFaq) > 0){
            $prev_val=$getFaq['sort_num'];
            $result['intrchng_val']=$prev_val;
            $getFaqPre=$this->db->select('id,sort_num')->get_where('landing_left_adsection',array('sort_num'=>$val))->row_array();
            if(count($getFaqPre) > 0){
                $intrchng_id=$getFaqPre['id'];
                $result['intrchng_id']=$intrchng_id;
                $this->db->update('landing_left_adsection',array('sort_num'=>$prev_val),array('id'=>$intrchng_id));

            }


            $this->db->update('landing_left_adsection',array('sort_num'=>$val),array('id'=>$id));

        }

        return $result;

    }


    //Right Ad Section

    function getRightAdList(&$config,&$start,&$param)
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
            $sortType    	= $this->nsession->get_param('ADMIN_ADSECTION','sortType','DESC');
            $sortField   	= $this->nsession->get_param('ADMIN_ADSECTION','sortField','id');
            $searchField 	= $this->nsession->get_param('ADMIN_ADSECTION','searchField','');
            $searchString 	= $this->nsession->get_param('ADMIN_ADSECTION','searchString','');
            $searchText  	= $this->nsession->get_param('ADMIN_ADSECTION','searchText','');
            $searchFromDate = $this->nsession->get_param('ADMIN_ADSECTION','searchFromDate','');
            $searchToDate  	= $this->nsession->get_param('ADMIN_ADSECTION','searchToDate','');
            $searchAlpha  	= $this->nsession->get_param('ADMIN_ADSECTION','searchAlpha','');
            $searchMode  	= $this->nsession->get_param('ADMIN_ADSECTION','searchMode','STRING');
            $searchDisplay  = $this->nsession->get_param('ADMIN_ADSECTION','searchDisplay',20);
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
        $this->db->select('COUNT(id) as TotalrecordCount');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->select('landing_right_adsection.*');

        $recordSet = $this->db->get('landing_right_adsection');
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
        $this->db->select('landing_right_adsection.*');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }

        $this->db->order_by('sort_num','asc');
        $this->db->limit($config['per_page'],$start);
        $recordSet = $this->db->get('landing_right_adsection');
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

    function rightaddContent($data)
    {

        $max_num=$this->db->select('MAX(sort_num) as max_num')->get_where('landing_right_adsection')->row()->max_num;
        $data['sort_num']=(int)$max_num + 1;

        $insert_data= array(
            'title'=>$data['title'],
            'sort_num'=>$data['sort_num'],
            'icon'=>$data['icon'],
            'page_url'=>$data['page_url']
        );
        $this->db->insert('landing_right_adsection',$insert_data);
        $last_insert_id = $this->db->insert_id();

        if($last_insert_id)
        {
            return $last_insert_id;
        }
        else
        {
            return false;
        }
    }

    function righteditContent($id,$data)
    {
        $update_data= array(
            'title'=>$data['title'],
            'page_url'=>$data['page_url'],
            'icon'=>$data['icon'],
            'icon_app'=>$data['icon_app']
        );
        $this->db->where('id', $id);
        $this->db->update('landing_right_adsection', $update_data);
        $affected_rows =$this->db->affected_rows();

        if($result)
        {
            return $affected_rows;
        }
        else
        {
            return false;
        }
    }

    function rightdelete($id)
    {
        $this->db->delete('landing_right_adsection', array('id' => $id));
        return true;
    }

    function getSingleRight($id)
    {
        $sql = "SELECT * FROM landing_right_adsection WHERE id = ".$id;
        $recordSet = $this->db->query($sql);

        $rs = false;
        if ($recordSet->num_rows() > 0)
        {
            $rs = array();
            $isEscapeArr = array('icon');
            foreach ($recordSet->result_array() as $row)
            {
                foreach($row as $key=>$val){
                    if(!in_array($key,$isEscapeArr)){
                        $recordSet->fields[$key] = outputEscapeString($val);
                    }else{
                        $recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
                    }
                }
                $rs[] = $recordSet->fields;
            }
        }
        return $rs;
    }
    function activateright($id)
    {
        $sql = "UPDATE landing_right_adsection SET is_active = '1' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }
    function inactiveright($id)
    {
        $sql = "UPDATE landing_right_adsection SET is_active = '0' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }

    function changeorderRight($val,$id){
        $result=array();
        $result['intrchng_id']='0';
        $result['intrchng_val']='0';
        $getFaq=$this->db->select('id,sort_num')->get_where('landing_right_adsection',array('id'=>$id))->row_array();
        if(count($getFaq) > 0){
            $prev_val=$getFaq['sort_num'];
            $result['intrchng_val']=$prev_val;
            $getFaqPre=$this->db->select('id,sort_num')->get_where('landing_right_adsection',array('sort_num'=>$val))->row_array();
            if(count($getFaqPre) > 0){
                $intrchng_id=$getFaqPre['id'];
                $result['intrchng_id']=$intrchng_id;
                $this->db->update('landing_right_adsection',array('sort_num'=>$prev_val),array('id'=>$intrchng_id));

            }


            $this->db->update('landing_right_adsection',array('sort_num'=>$val),array('id'=>$id));

        }

        return $result;

    }


    function getCatLevel1(){
        $getFaq=$this->db->query("select * from category_level_1 where is_active=1 order by sort_num asc");
        if($getFaq->num_rows() > 0){
           return $getFaq->result_array();
        }
       return false;
    }

    function getCatLevel2(){
        $html='';
        $category_level_1=$this->input->post('category_level_1');
        $category_level_2=$this->input->post('category_level_2');
         $getFaq=$this->db->query("select * from category_level_2 where level1=$category_level_1 and is_active=1 order by sort_num asc");
        if($getFaq->num_rows() > 0){
          $html.='<option value="">Please Select</option>';           
               foreach ($getFaq->result_array() as $value) 
               {
                  if($category_level_2!='' && $category_level_2 >0 && $category_level_2==$value['id']){
                    $html.='<option selected value="'.$value['id'].'">'.$value['name'].'</option>';
                  }else{
                    $html.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
                  }
                  
                }
        }
       echo $html;
    }

    function saveCategoryWiseData($data){
      $this->db->insert('category_wise_product',$data);
      return $this->db->insert_id();
    }

    function updateCategoryWiseData($id,$data)
    {
        
        $this->db->where('id', $id);
        $this->db->update('category_wise_product', $data);
        $affected_rows =$this->db->affected_rows();

        if($result)
        {
            return $affected_rows;
        }
        else
        {
            return false;
        }
    }

    function getCategoryWiseList(&$config,&$start,&$param)
    {
        // GET DATA FROM GET/POST  OR   SESSION ====================
        $Count = 0;
        $page = $this->uri->segment(3,0); // page
        $isSession = $this->uri->segment(4,0); // read data from SESSION or POST     (1 == POST , 0 = SESSION )

        $start = 0;

        $sortType       = $param['sortType'];
        $sortField      = $param['sortField'];
        $searchField    = $param['searchField'];
        $searchString   = $param['searchString'];
        $searchText     = $param['searchText'];
        $searchFromDate = $param['searchFromDate'];
        $searchToDate   = $param['searchToDate'];
        $searchAlpha    = $param['searchAlpha'];
        $searchMode     = $param['searchMode'];
        $searchDisplay  = $param['searchDisplay'];

        if($isSession == 0)
        {
            $sortType       = $this->nsession->get_param('ADMIN_ADSECTION','sortType','DESC');
            $sortField      = $this->nsession->get_param('ADMIN_ADSECTION','sortField','id');
            $searchField    = $this->nsession->get_param('ADMIN_ADSECTION','searchField','');
            $searchString   = $this->nsession->get_param('ADMIN_ADSECTION','searchString','');
            $searchText     = $this->nsession->get_param('ADMIN_ADSECTION','searchText','');
            $searchFromDate = $this->nsession->get_param('ADMIN_ADSECTION','searchFromDate','');
            $searchToDate   = $this->nsession->get_param('ADMIN_ADSECTION','searchToDate','');
            $searchAlpha    = $this->nsession->get_param('ADMIN_ADSECTION','searchAlpha','');
            $searchMode     = $this->nsession->get_param('ADMIN_ADSECTION','searchMode','STRING');
            $searchDisplay  = $this->nsession->get_param('ADMIN_ADSECTION','searchDisplay',20);
        }

        //========= SET SESSION DATA FOR SEARCH / PAGE / SORT Condition etc =====================
        $sessionDataArray = array();
        $sessionDataArray['sortType']       = $sortType;
        $sessionDataArray['sortField']      = $sortField;
        if($searchField!=''){
            $sessionDataArray['searchField']    = $searchField;
            $sessionDataArray['searchString']   = $searchString ;
        }
        $sessionDataArray['searchText']     = $searchText;
        $sessionDataArray['searchFromDate'] = $searchFromDate;
        $sessionDataArray['searchToDate']   = $searchToDate;
        $sessionDataArray['searchAlpha']    = $searchAlpha;
        $sessionDataArray['searchMode']     = $searchMode;
        $sessionDataArray['searchDisplay']  = $searchDisplay;

        $this->nsession->set_userdata('ADMIN_MANAGEPOSITION', $sessionDataArray);
        //==============================================================
        $this->db->select('COUNT(id) as TotalrecordCount');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }
        $this->db->select('category_wise_product.*');

        $recordSet = $this->db->get('category_wise_product');
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
        $this->db->select('category_wise_product.*');
        if(isset($sessionDataArray['searchField'])){
            $this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
        }

        $this->db->order_by('sort_num','asc');
        $this->db->limit($config['per_page'],$start);
        $recordSet = $this->db->get('category_wise_product');
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

    function getSingleCategoryWiseProduct($id)
    {
        $sql = "SELECT * FROM category_wise_product WHERE id = ".$id;
        $recordSet = $this->db->query($sql);

        $rs = false;
        if ($recordSet->num_rows() > 0)
        {
            $rs = array();
            $isEscapeArr = array('icon');
            foreach ($recordSet->result_array() as $row)
            {
                foreach($row as $key=>$val){
                    if(!in_array($key,$isEscapeArr)){
                        $recordSet->fields[$key] = outputEscapeString($val);
                    }else{
                        $recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
                    }
                }
                $rs[] = $recordSet->fields;
            }
        }
        return $rs;
    }

    function activateCategory_wise_product($id)
    {
        $sql = "UPDATE category_wise_product SET is_active = '1' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }
    function inactiveCategory_wise_product($id)
    {
        $sql = "UPDATE category_wise_product SET is_active = '0' WHERE id = ".$id."";
        $recordSet = $this->db->query($sql);

        if (!$recordSet )
        {
            return false;
        }
    }

    function changeorderCategory_wise_product($val,$id){
        $result=array();
        $result['intrchng_id']='0';
        $result['intrchng_val']='0';
        $getFaq=$this->db->select('id,sort_num')->get_where('category_wise_product',array('id'=>$id))->row_array();
        if(count($getFaq) > 0){
            $prev_val=$getFaq['sort_num'];
            $result['intrchng_val']=$prev_val;
            $getFaqPre=$this->db->select('id,sort_num')->get_where('category_wise_product',array('sort_num'=>$val))->row_array();
            if(count($getFaqPre) > 0){
                $intrchng_id=$getFaqPre['id'];
                $result['intrchng_id']=$intrchng_id;
                $this->db->update('category_wise_product',array('sort_num'=>$prev_val),array('id'=>$intrchng_id));

            }


            $this->db->update('category_wise_product',array('sort_num'=>$val),array('id'=>$id));

        }

        return $result;

    }

    function deleteCategoryWiseProduct($id){
        $this->db->delete('category_wise_product', array('id' => $id));
        return true;
    }

    

}

?>