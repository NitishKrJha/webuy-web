<?php
//error_reporting(E_ALL);
class Product extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'product';
		$this->load->model('ModelProduct');
		$this->load->model('ModelDashboard');
		$this->load->model('ModelCommon');
	}
	
	function index()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name='product';
		$for=0;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){
		$this->functions->checkAdmin($this->controller.'/',true);
		
		$config['base_url'] 			= base_url($this->controller."/index/");
		
		$config['uri_segment']  		= 3;
		$config['num_links'] 			= 10;
		$config['page_query_string'] 	= false;
		$config['extra_params'] 		= ""; 
		$config['extra_params'] 		= "";
		
		$this->pagination->setAdminPaginationStyle($config);
		$start = 0;
		
		$data['controller'] = $this->controller;
		
		$param['sortType'] 			= $this->input->request('sortType','DESC');
		$param['sortField'] 		= $this->input->request('sortField','product_id');
		$param['searchField'] 		= $this->input->request('searchField','');
		$param['searchString'] 		= $this->input->request('searchString','');
		$param['searchText'] 		= $this->input->request('searchText','');
		$param['searchFromDate'] 	= $this->input->request('searchFromDate','');
		$param['searchToDate'] 		= $this->input->request('searchToDate','');
		$param['searchDisplay'] 	= $this->input->request('searchDisplay','10');
		$param['searchAlpha'] 		= $this->input->request('txt_alpha','');
		$param['searchMode'] 		= $this->input->request('search_mode','STRING');

		$data['recordset'] 		= $this->ModelProduct->getList($config,$start,$param);
		
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_PRODUCT');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['pwd_link']        	= base_url($this->controller."/change_password/{{ID}}/0");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['activated_link']    	= base_url($this->controller."/activate/{{ID}}/0");
		$data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
		$data['feature_link']    	= base_url($this->controller."/feature/{{ID}}/0");
		$data['infeature_link']    	= base_url($this->controller."/infeature/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['view_link']    		= base_url($this->controller."/viewdetails/{{ID}}/0");
		$data['total_rows']			= $config['total_rows'];
		//echo $this->pagination->create_links(); die();
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'product/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
			redirect(base_url($this->user));
		}
	
	}

	function activate()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=product;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelProduct->activate($id);	
		/*$result = $this->ModelProduct->getsingle_empdata($id);
		$email = $result->email;
		$first_name = $result->first_name;
		
		$to = $email;
		$subject='Profile Activated';
		$body='<tr><td>Hi,</td></tr>
				<tr><td>Name : '.$first_name.'</td></tr>
				<tr style="background:#fff;"><td>Your profile has been activated successfully click on the link below to login.</td></tr> 
				<tr style="background:#fff;"><td><a href="'.front_base_url().'login'.'">Login</a></td></tr>';
		$return_check = $this->functions->mail_template($to,$subject,$body);*/
		$this->nsession->set_userdata('succmsg', 'Successfully product Activated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Active this Item.');
			redirect(base_url($this->controller));
		}
	}
	function inactive()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=product;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelProduct->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully product Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Inactive this Item.');
			redirect(base_url($this->controller));
		}
	}

	function feature()

	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=product;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelProduct->feature($id);	
		/*$result = $this->ModelProduct->getsingle_empdata($id);
		$email = $result->email;
		$first_name = $result->first_name;
		
		$to = $email;
		$subject='Profile Activated';
		$body='<tr><td>Hi,</td></tr>
				<tr><td>Name : '.$first_name.'</td></tr>
				<tr style="background:#fff;"><td>Your profile has been activated successfully click on the link below to login.</td></tr> 
				<tr style="background:#fff;"><td><a href="'.front_base_url().'login'.'">Login</a></td></tr>';
		$return_check = $this->functions->mail_template($to,$subject,$body);*/
		$this->nsession->set_userdata('succmsg', 'Successfully product Feature.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Featured this Item.');
			redirect(base_url($this->controller));
		}
	}
	function infeature()
	{


		$UserID = $this->nsession->userdata('admin_session_id');
		$name=product;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelProduct->infeature($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully product NonFeature.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Nonfeatured this Item.');
			redirect(base_url($this->controller));
		}
	}

	function viewdetails($id){
		if($id){
			$rs = $this->ModelProduct->getSingle($id);
			//$data['h']=$this->ModelProduct->multi_img($id);  
			//pr($rs);
			//$row = $rs->fields;
			if(is_array($rs))
			{
				foreach($rs as $key => $val)
				{
					if(!is_numeric($key))
					{
						$data[$key] = $val;
					}
				}
			}
			$data['imagelist'] = $this->ModelProduct->getAllimgage($id);
			//pr($data);

			//pr($data);
			//$data['business_details']=$this->ModelProduct->getBusinessList($id);
			//$data['bank_details']=$this->ModelProduct->getBankDetails($id);
			//pr($data['bank_details']);
			$data['succmsg'] = $this->nsession->userdata('succmsg');
			$data['errmsg'] = $this->nsession->userdata('errmsg');
			$this->nsession->set_userdata('succmsg', "");
			$this->nsession->set_userdata('errmsg', "");
			$elements = array();
			$elements['menu'] = 'menu/index';
			$elements['topmenu'] = 'menu/topmenu';
			$elements['main'] = 'product/view_details';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}

	function aadhar_card_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['aadhar_card_verified']=$status;
			$result=$this->ModelProduct->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));
			if($result !=false){
				$msg='Aadhar Card not verified Successfully';
				if($status==1){
					$msg='Aadhar Card verified Successfully';
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}

	function pan_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['pan_verified']=$status;
			$result=$this->ModelProduct->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));
			if($result !=false){
				$msg='Pan Card not verified Successfully';
				if($status==1){
					$msg='Pan Card verified Successfully';
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}

	function gstin_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['gstin_verified']=$status;
			$result=$this->ModelProduct->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));
			if($result !=false){
				$msg='GSTIN not verified Successfully';
				if($status==1){
					$msg='GSTIN verified Successfully';
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}
	
	//==========Initialize $data for Add =======================
	


	function addedit($id = 0)
	{
		error_reporting(E_ALL);
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
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['id']			= $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$product = $this->ModelProduct->getProductData($contentId);
			    $data['controller'] = $this->controller;
                $data['successresult'] = $this->nsession->userdata('successresult');
                $data['errorresult'] = $this->nsession->userdata('errorresult');

                $data['brands']    = $this->ModelProduct->getAllBrands();
                $data['succmsg']    = $this->nsession->userdata('succmsg');
                $data['errmsg']     = $this->nsession->userdata('errmsg');
                //echo $data['succmsg']; die();
                $this->nsession->set_userdata('succmsg', "");
                $this->nsession->set_userdata('errmsg', "");

                $merchants_id=$this->nsession->userdata('merchants_session_id');

                $data['all_cat_level_name']=$this->ModelDashboard->getCatLevelAllName($product['cat_level1'],$product['cat_level2'],$product['cat_level3'],$product['cat_level4']);
                if($data['all_cat_level_name']['level1']=='' || $data['all_cat_level_name']['level2']=='' || $data['all_cat_level_name']['level3']==''){
                    //$this->nsession->set_userdata('errmsg','You have selected invalid categories');
                    //redirect(base_url('product/seller_recent_category'));
                }
                
                //pr($check_member_business_status);
                $data['product']=$product;
                $data['variation_attribute'] =$this->ModelDashboard->getListOfTable('variation_attribute',array('is_active'=>1),'multiple');
                $data['category_level_1']=$this->ModelDashboard->getListOfTable('category_level_1',array('is_active'=>1),'multiple');
                $data['all_product_title']= $this->ModelDashboard->getAllProductTitle();
                
		}else{
			$data['action'] 	= "Add";
			$data['id']			= 0;
			$data['brands']    = $this->ModelProduct->getAllBrands();
			$data['variation_attribute'] =$this->ModelDashboard->getListOfTable('variation_attribute',array('is_active'=>1),'multiple');
            $data['category_level_1']=$this->ModelDashboard->getListOfTable('category_level_1',array('is_active'=>1),'multiple');
            $data['all_product_title']= $this->ModelDashboard->getAllProductTitle();
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'product/add_edit';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}

	function getCategory(){
        $return=array('error'=>1,'data'=>'','msg'=>'No Data Found');
        //pr($_POST);
        if($this->input->post('type') && $this->input->post('id')){
            $type=$this->input->post('type');
            if($type=='level2'){
                $nlevel='level1';
                $tbl='category_level_2';
            }else if($type=='level3'){
                $nlevel='level2';
                $tbl='category_level_3';
            }else if($type=='level4'){
                $nlevel='level3';
                $tbl='category_level_4';
            }else{
                $nlevel='level4';
                $tbl='category_level_1';
            }
            $id=$this->input->post('id');
            $result=$this->ModelDashboard->getListOfTable($tbl,array('is_active'=>1,$nlevel=>$id),'multiple');
            //echo $this->db->last_query();
            $html='';
            if(count($result) > 0){
                $html .='<option value="">Select</option>';
                foreach ($result as $key => $value) {
                    $html .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
            }
            if($html!=''){
                $return['error']=0;
                $return['data']=$html;
            }
        }
        echo json_encode($return);
    }

	public function douploadimages($name)
    {
		//echo "<pre>"; print_r($_FILES); die();
        $this->load->library('upload');
        $this->load->library('image_lib');
        
		$pr_image=array();
		if(empty($_FILES[$name]['name'])){
			return $pr_image;
		}
		$filesCount = count($_FILES[$name]['name']);
        if($filesCount>0){
            $propertKeys = array_keys($_FILES[$name]['name']);
            $i = 0;
            foreach($propertKeys as $propertKey){
                $_FILES['upload_img']['name']       = $_FILES[$name]['name'][$propertKey];
                $_FILES['upload_img']['type']       = $_FILES[$name]['type'][$propertKey];
                $_FILES['upload_img']['tmp_name']   = $_FILES[$name]['tmp_name'][$propertKey];
                $_FILES['upload_img']['error']      = $_FILES[$name]['error'][$propertKey];
                $_FILES['upload_img']['size']       = $_FILES[$name]['size'][$propertKey];

                $upload_img = time().$_FILES[$name]['name'][$propertKey];
                $uploadPath = file_upload_absolute_path().'product/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                $config['file_name']     = $upload_img;

                $this->upload->initialize($config);
                if($this->upload->do_upload('upload_img')){
                    $fileData = $this->upload->data();
                    $uploadData[$propertKey]['file_name'] = $fileData['file_name'];
                    $this->resize_image($fileData['full_path'], file_upload_absolute_path() . '/product/medium/','768','550');                    
                }else{
                    $error = array('error' => $this->upload->display_errors());
                    //  echo $this->upload->display_errors(); die();
                }
                //pr($uploadData);
                if(!empty($uploadData[$propertKey]['file_name'])) {
                    $pr_image['upload_img'][$i]['main_img'] = $uploadData[$propertKey]['file_name'];
                    $pr_image['upload_img'][$i]['thumb_img']='';
                    $thumb=$this->creatThumbImage($uploadData[$propertKey]['file_name']);
                    //echo $uploadData[$propertKey]['file_name']."<br/>";
                    //var_dump($thumb);
                    if($thumb!=false){
                        $pr_image['upload_img'][$i]['thumb_img']=$thumb;
                    }
                }else{
                    $pr_image['upload_img'][$i] =array('main_img'=>'','thumb_img'=>'');

                }

                $i++;
            }
        }
        //pr($pr_image);
        // $this->image_lib->clear();
        return $pr_image;
    }

    function resize_image($sourcePath, $desPath, $width = '500', $height = '500')
    {
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        //$config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = true;
        $config['thumb_marker'] = '';
        $config['width'] = $width;
        $config['height'] = $height;
        $this->image_lib->initialize($config);
 
        if ($this->image_lib->resize())
            return true;
        return false;
    }

    public function creatThumbImage($filename=''){
        $source_path = file_upload_absolute_path() . '/product/' . $filename;
        $target_path = file_upload_absolute_path() . '/product/';
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 150,
            'height' => 150
        );
        $this->image_lib->initialize($config_manip);
        if (!$this->image_lib->resize()) {
            //echo $this->image_lib->display_errors();
            $this->image_lib->clear();
            return false;
        }else{
            $imgDetailArray=explode('.',$filename);
            $thumbimgname=$imgDetailArray[0].'_thumb';
            // $this->image_lib->clear();
            return $thumbimgname.'.'.$imgDetailArray[1];
        }
    }

    function do_miltiupload_files($files)
    {
        $config = array(
            'upload_path'   => file_upload_absolute_path().'product_doc/',
            'allowed_types' => '*',
            'overwrite'     => 1,
        );

        $this->load->library('upload', $config);

        $images = array();

        foreach ($files['name'] as $key => $image) {
            $_FILES['attachfiles[]']['name']= $files['name'][$key];
            $_FILES['attachfiles[]']['type']= $files['type'][$key];
            $_FILES['attachfiles[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['attachfiles[]']['error']= $files['error'][$key];
            $_FILES['attachfiles[]']['size']= $files['size'][$key];

            $fileName = time() .'_'. $image;
            $images[] = $fileName;
            $config['file_name'] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('attachfiles[]')) { // upload file here
                /*echo "<pre>";
                print_r($this->upload->data());*/
            } else {
                return false;
            }
        }
        return $images;
    }

	function add_product($id=''){



        $imageArray=array();
        if($this->input->post('title')){
            if($this->nsession->userdata('upload_image')){
                if(is_array($this->nsession->userdata('upload_image'))){
                    $imageArray = $this->nsession->userdata('upload_image');
                }
            }

            $allimg=$this->douploadimages('images');

            $attachfiles = $this->do_miltiupload_files($_FILES['attachfiles']);


            $main_data=array();
            $main_data['title']=$this->input->post('title');
            $main_data['description']=$this->input->post('description');
            $main_data['specification']=$this->input->post('specification');
            $main_data['cat_level1']=$this->input->post('level1');
            $main_data['cat_level2']=$this->input->post('level2');
            $main_data['cat_level3']=$this->input->post('level3');
            $main_data['cat_level4']=$this->input->post('level4');
            $main_data['tag']=$this->input->post('tag');
            $main_data['brand']=$this->input->post('brand');
            $main_data['is_combo']=$this->input->post('is_combo');
            $main_data['sale_price']=$this->input->post('sale_price');
            $main_data['purchase_price']=$this->input->post('purchase_price');
            $main_data['gst']=$this->input->post('gst');
            $main_data['quantity']=$this->input->post('quantity');
            $main_data['shipping_cost']=$this->input->post('shipping_cost');
            //$main_data['sku']=$this->input->post('sku');
            $main_data['upc']=$this->input->post('upc');
            $main_data['discount']=$this->input->post('discount');
            $main_data['discount_type']=$this->input->post('discount_type');
            $main_data['item_id']=$this->ModelProduct->getUniqueID();
			
            $vara_sku=$this->input->post('vara_sku');
            $vara_qty=$this->input->post('vara_qty');
            $var_name=$this->input->post('var_name');
            $var_val=$this->input->post('var_val');
            $vara_upc=$this->input->post('vara_upc');
            $vara_start_price=$this->input->post('vara_start_price');

            if($id==''){
                $main_data['created_by']=$this->nsession->userdata('merchants_session_id');
                $main_data['created_date']=date('y-m-d h:m:s');
                $main_data['created_by_type']='merchants';

                $result=$this->ModelProduct->addData('product',$main_data);
                if($result!=false){
                    $product_id=$result;

                    if(count($attachfiles)>0){
                        for ($i=0;$i<count($attachfiles);$i++){
                            $doc_data=array();
                            $doc_data['product_id']=$product_id;
                            $doc_data['doc_name']=$attachfiles[$i];
                            $doc_data['created_dt']=date('Y-m-d h:i:s a');
                            $doc_data['modified_dt']=date('Y-m-d h:i:s a');
                            //$this->ModelProduct->addData('product_attach_document',$doc_data);
                            if($attachfiles[$i]!=''){
                                $this->ModelProduct->addData('product_attach_document',$doc_data);
                            }
                        }
                    }

                    $today = date('YmdHis');
                    $rand = rand(0, $today);
                    $newSKU=$rand.$product_id;
                    $this->ModelCommon->updateData('product',array('SKU'=>$newSKU),array('product_id'=>$product_id));
                    $this->ModelProduct->savePrImage($product_id,$allimg,'main');
                    //pr($allimg);
                    $this->ModelProduct->delData('product_more',array('product_id'=>$product_id));
                    if(count($vara_sku) > 0){
                        foreach ($vara_sku as $key => $value) {
                            $main_vara_data=array();
                            $nkey=((int)$key + 1);
                            //$main_vara_data['sku']=$value;
                            $main_vara_data['start_price']=$vara_start_price[$key];
                            $main_vara_data['quantity']=$vara_qty[$key];
                            $main_vara_data['upc']=$vara_upc[$key];
                            $main_vara_data['variation_name']=json_encode($var_name[$nkey]);
                            $main_vara_data['variation_value']=json_encode($var_val[$nkey]);
                            $main_vara_data['product_id']=$product_id;

                            $var_result=$this->ModelProduct->addData('product_more',$main_vara_data);
                            if($var_result!=false){
                                $today = date('YmdHis');
                                $rand = rand(0, $today);
                                $newSKU=$rand.$product_id.$var_result;
                                $this->ModelCommon->updateData('product_more',array('sku'=>$newSKU),array('id'=>$var_result));
                                if(isset($imageArray[$nkey])){
                                    if(is_array($imageArray[$nkey])){
                                        foreach ($imageArray[$nkey] as $key_v => $value_v) {
                                            $var_img_data=array();
                                            $var_img_data['product_id']=$var_result;
                                            $var_img_data['path']=$value_v;
                                            $var_img_data['type']='variation';
                                            $this->ModelProduct->addData('product_images',$var_img_data);
                                        }
                                    }
                                }
                            }


                        }
                    }
                    $this->nsession->set_userdata('succmsg', "Added Successfully");
                    redirect(base_url('product/index'));
                }else{
                    $this->nsession->set_userdata('errmsg','Technicle issue, Please try once again');
                    redirect(base_url('product/index'));
                }
            }else{
                if(!empty($attachfiles) && count($attachfiles)>0){
                    for ($i=0;$i<count($attachfiles);$i++){
                        if($attachfiles[$i]!=''){
                            $doc_data=array();
                            $doc_data['product_id']=$id;
                            $doc_data['doc_name']=$attachfiles[$i];
                            $doc_data['created_dt']=date('Y-m-d h:i:s a');
                            $doc_data['modified_dt']=date('Y-m-d h:i:s a');
                            if($attachfiles[$i]!=''){
                                $this->ModelProduct->addData('product_attach_document',$doc_data);
                            }

                        }

                    }
                }
                $main_data['modified_date']=date('y-m-d h:m:s');
                $main_data['status_modified_by']='merchants';
                $main_data['created_by']=$this->nsession->userdata('merchants_session_id');
				//echo "<pre>"; print_r($main_data); die();
				$result=$this->ModelProduct->addData('product',$main_data,array('product_id'=>$id));
                if($result!=false){
                    $product_id=$id;
                    $this->ModelProduct->savePrImage($product_id,$allimg,'main');
                    $this->ModelProduct->delData('product_more',array('product_id'=>$product_id));
                    if(count($vara_sku) > 0){
                        foreach ($vara_sku as $key => $value) {
                           
                            $main_vara_data=array();
                            $nkey=((int)$key + 1);
                            $main_vara_data['sku']=$value;
                            $main_vara_data['start_price']=$vara_start_price[$key];
                            $main_vara_data['quantity']=$vara_qty[$key];
                            $main_vara_data['upc']=$vara_upc[$key];
                            $main_vara_data['variation_name']=json_encode($var_name[$nkey]);
                            $main_vara_data['variation_value']=json_encode($var_val[$nkey]);
                            $main_vara_data['product_id']=$product_id;                           
                            $chekVarname=1;
                            foreach ($var_name[$nkey] as $vname) {                               
                                $chekVarname = $this->ModelProduct->checkData('variation_attribute',array('cat_level3'=>$main_data['cat_level3'],'name'=>$vname));
                                if($chekVarname>0){
                                     echo '<br>'.$vname;
                                    continue;
                                }else{
                                     $chekVarname=0;
                                    break;

                                }
                            }
                                                       
                           // $chekVarname = $this->ModelProduct->checkData('variation_attribute',array('cat_level3'=>$main_data['cat_level3'],'name'=>$var_name[$nkey]));
                            if($chekVarname>0){    
                                $var_result=$this->ModelProduct->addData('product_more',$main_vara_data);
                                if($var_result!=false){
                                    if(isset($imageArray[$nkey])){
                                        if(is_array($imageArray[$nkey])){
                                            $this->ModelProduct->delData('product_images',array('product_id'=>$var_result,'type'=>'variation'));
                                            foreach ($imageArray[$nkey] as $key_v => $value_v) {
                                                $var_img_data=array();
                                                $var_img_data['product_id']=$var_result;
                                                $var_img_data['path']=$value_v;
                                                $var_img_data['type']='variation';
                                                $this->ModelProduct->addData('product_images',$var_img_data);
                                            }
                                        }
                                    }
                                }
                           }


                        }
                    }
                    $this->nsession->set_userdata('succmsg', "Modify Successfully");
                    redirect(base_url('product/index'));
                }else{
                    $this->nsession->set_userdata('errmsg','Technicle issue, Please try once again');
                    redirect(base_url('product/index'));
                }
            }


        }
    }
	
	function do_addedit()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		
		if($this->input->post('hidData')!='JSENABLE'){
			$this->nsession->set_userdata('errmsg','unable to process because some extension is disable of this browser');
			redirect(base_url($this->controller));
			die();
		}	
		$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');
		$file_name = $_FILES['picture']['name'];
		$new_file_name = time().$file_name;
		$config['upload_path'] 	 = file_upload_absolute_path().'profile_pic/merchants/';
		$config['allowed_types'] = '*';
		$config['file_name']     = $new_file_name;  
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('picture')) {
			//echo $this->upload->display_errors(); die();
			//$error = array('error' => $this->upload->display_errors()); 
		}
		else{ 
			$upload_data = $this->upload->data();
			$data['picture'] 			= $upload_data['file_name'];
			$thumb=$this->creatThumbImage($data['picture']);
			if($thumb!=false){
				$data['picture_sm']=$thumb;
			}
		} 
		$data['first_name'] 		= $this->input->post('first_name');
		$data['last_name'] 			= $this->input->post('last_name');
		$data['phone'] 			    = $this->input->post('phone');
		$data['email'] 				= $this->input->post('email');
		$data['username'] 			= $this->input->post('username');
		$data['country'] 			= $this->input->post('country');
		$data['state'] 				= $this->input->post('state');
		$data['city'] 				= $this->input->post('city');
		$data['zipcode'] 			= $this->input->post('zipcode');
		$data['address'] 			= $this->input->post('address');
		$data['address2'] 			= $this->input->post('address2');

		$data['created_by_type']    = 'staff';
		$data['status_modified_by']         = $this->nsession->userdata('admin_session_id');
		if($contentId==0){
			
			$data['status'] 			= 1;
			$data['created_by']         = $this->nsession->userdata('admin_session_id');
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
		}else{
			
			$data['modified_date'] 			= date('Y-m-d h:m:s');
		}
		if($contentId==0){
			$rid=$this->ModelProduct->addContent($data);
			if($rid!=false){
				$ndata=array();
				$ndata['password']=md5($this->input->post('password'));
				$this->ModelProduct->updatePassword($ndata,$rid);
				/***************Email Content**********************/
				$home_page_url='<a href="'.front_base_url().'">'.front_base_url().'</a>';

				$login_link='<a href="'.front_base_url().'login'.'">'.front_base_url().'login'.'</a>';

				$email_template=$this->db->get_where('email_template',array('id'=>10))->row_array();
				if(count($email_template) > 0){
					$full_name=$data['first_name']." ".$data['last_name'];
					$subject=$email_template['subject'];
					$body=$email_template['content'];
					$body=str_replace('#full_name#', $full_name, $body);
					$body=str_replace('#property_link#', $property_link, $body);
					$body=str_replace('#home_page_url#', $home_page_url, $body);
					$body=str_replace('#login_link#', $login_link, $body);
					
				}else{
					$full_name=$data['first_name']." ".$data['last_name'];
					$subject		= "Registration";
					$body			= "<tr><td>Hi,".$full_name."</td></tr>
									<tr><td>Our team has been created an account for you. Please open this link to check is there correct or not.</td></tr><tr><td>Login Link : ".$login_link."</td><td>Email : ".$data['email']." Password: ".$this->input->post('password')."</td></tr>";
				}
				$to 			= $data['email'];
				
				$this->functions->mail_template($to,$subject,$body);
				/*************************************/
				$this->nsession->set_userdata('succmsg','merchants added successfully.');
				redirect(base_url($this->controller));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to add , please try once again..');
				redirect(base_url($this->controller));
			}
			
		}else{
			$this->ModelProduct->editContent($data,$contentId);
			$this->nsession->set_userdata('succmsg','merchants edited successfully.');
			redirect(base_url($this->controller));
		}
			
		
	}

	function seller_recent_category(){
    	$data = array();
		$data['controller'] = $this->controller;
		$data['successresult'] = $this->nsession->userdata('successresult');
		$data['errorresult'] = $this->nsession->userdata('errorresult');
		
        $config['base_url']             = base_url($this->controller."/seller_recent_category/");
        
        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = ""; 
        $config['extra_params']         = "";
        
        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;
        
        $data['controller'] = $this->controller;
        
        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','product.title');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','10');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        //print_r($param);
        $data['recordset']      = $this->ModelProduct->getAllProduct($config,$start,$param);
        //echo $this->db->last_query();
        //pr($data['recordset']);
        $data['startRecord']    = $start;
        
        $this->pagination->initialize($config);
        
        $data['params']             = $this->nsession->userdata('PRODUCT');
        $data['reload_link']        = base_url($this->controller."/index/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/addedit/0/0/");
        $data['edit_link']          = base_url("product/addedit/{{ID}}");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
        $data['showall_link']       = base_url($this->controller."/index/0/1");
        $data['total_rows']         = $config['total_rows'];
        

		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		//echo $data['succmsg']; die();
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		//pr($data['recordset']);
        $elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'product/list';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
    }
	
	

	function delete()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelProduct->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Deleted merchants Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	

	function change_password($id = 0)
	{
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
		$data['do_addedit_link']	= base_url($this->controller."/update_password/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelProduct->getSingle($contentId);
			//pr($rs);
			//$row = $rs->fields;
			if(is_array($rs))
			{
				foreach($rs as $key => $val)
				{
					if(!is_numeric($key))
					{
						$data[$key] = $val;
					}
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
		$elements['main'] = 'merchants/change_password';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}

	function update_password($id){
		if($this->input->post('password')){
			$ndata['password']=md5($this->input->post('password'));
			$this->ModelProduct->updatePassword($ndata,$id);
			$this->nsession->set_userdata('succmsg', 'Password has been changed');
			redirect(base_url('merchants/index/0/1'));
		}else{
			$this->nsession->set_userdata('errmsg', 'Unable to change password, please try once again');
			redirect(base_url('merchants/index/0/1'));
		}
	}

	function getState($id,$val=0){
		$data=$this->ModelProduct->getCountryCityStateList('states',array('country_id'=>$id));
		if(count($data) > 0){
			echo '<option value="">Select State</option>';
			foreach ($data as $key => $value) {
				$selected='';
				if($value['id']==$val){
					$selected="selected='selected'";
				}
				echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
			}
		}
	}

	function getCity($id,$val=0){
		$data=$this->ModelProduct->getCountryCityStateList('cities',array('state_id'=>$id));
		if(count($data) > 0){
			echo '<option value="">Select City</option>';
			foreach ($data as $key => $value) {
				$selected='';
				if($value['id']==$val){
					$selected="selected='selected'";
				}
				echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
			}
		}
	}

	public function checkuser($type,$id=0){
		if($type=='email'){
			$email_id = $this->input->post('email');
			$return = $this->ModelProduct->checkEmail($email_id,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelProduct->checkPhone($phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='compnay_phone'){
			$compnay_phone = $this->input->post('compnay_phone');
			$return = $this->ModelProduct->checkComapnyPhone($compnay_phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelProduct->checkUsername($username,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}
		
	}

	

	function notifyMerchant(){
	    $merchant_id=$this->input->post('merchant_id');
        $product_id=$this->input->post('product_id');
        $message=$this->input->post('message');
        $status=$this->input->post('status');
        $merchant_product_details=$this->ModelProduct->getMerchantProductDetails($product_id);
        $data=array();
        $data['subject']='Notify about '.$merchant_product_details['title'];
        $page_content='<p>Hi '.$merchant_product_details['business_name'].',</p>';
        $page_content.=$message;
        $page_content.='<p><a target="_blank" href="http://winskart.com/product/details/'.$product_id.'/'.$merchant_product_details['title'].'">'.$merchant_product_details['title'].'</a></p>';
        $data['page_content']=$page_content;

        $response=array();
        if(count($merchant_product_details)>0){
            $this->ModelProduct->updateData('product',array('admin_approval'=>$status),array('product_id'=>$product_id));
            $this->send_email($merchant_product_details['email'],'merchant-notify-template',$data);
            $response['status']=1;
            $response['message']='Message successfully send to merchant.';
        }else{
            $response['status']=0;
            $response['message']='Unable to send message,please try again later!';
        }
        header('Content-Type: application/json');
        echo json_encode( $response );

    }

    public function send_email($to,$template_name,$data){
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->to($to);
        $this->email->subject(sprintf($data['subject'], $this->config->item('website_name')));
        $this->email->message($this->load->view('email/'.$template_name,$data, TRUE));
        $this->email->send();
        return $this->email->print_debugger();
	}
	
	function delimg(){
        $result['error']=1;
        if($this->input->post('id')){
            $id=$this->input->post('id');
            $img_result=$this->ModelProduct->delImg($id);
            if($img_result!=false){
                $result['error']=0;
            }
        }
        echo json_encode($result);
    }

}
?>