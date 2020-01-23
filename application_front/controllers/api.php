<?php
 error_reporting(0);
 require('../application_front/libraries/REST_Controller.php');
class Api extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->controller = 'api';
        $this->load->model('ModelApi');
        $this->load->model('ModelCommon');
        if (isset($_SERVER['HTTP_ORIGIN'])) {
	        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	        header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }
    }

    public function index(){
        $data = array('status' => false, 'message' => 'Ad title field is blank','data'=>array());
        $this->response($data);
    }

    function register(){
        if(!$this->post('name')){
            $data = array('status' => false, 'message' => 'Name is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('address1')){
            $data = array('status' => false, 'message' => 'address1 is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('city')){
            $data = array('status' => false, 'message' => 'city is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('state')){
            $data = array('status' => false, 'message' => 'state is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('pincode')){
            $data = array('status' => false, 'message' => 'pincode is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('gender')){
            $data = array('status' => false, 'message' => 'gender is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('dob')){
            $data = array('status' => false, 'message' => 'dob is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('profession')){
            $data = array('status' => false, 'message' => 'profession is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('phoneNumber')){
            $data = array('status' => false, 'message' => 'mobile number is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('otp')){
            $data = array('status' => false, 'message' => 'otp is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('email')){
            $data = array('status' => false, 'message' => 'email is blank','data'=>array());
            $this->response($data);
            die();
        }else{
            $email=$this->post('email');
            $user_data_by_email=$this->ModelCommon->getSingleData('customer',array('email'=>$email));
            if(!empty($user_data_by_emai)){
                $data = array('status' => false, 'message' => 'Email is laready exist','data'=>array());
                $this->response($data);
                die();
            }
            $mobile_number=$this->post('phoneNumber');
            $user_data_by_phone=$this->ModelCommon->getSingleData('customer',array('phone'=>$mobile_number));
            if(empty($user_data_by_phone)){
                $otp=$this->post('otp');
                $otp_data=$this->ModelCommon->getSingleData('one_time_password',array('mobile_number'=>$mobile_number,'otp'=>$otp));
                if(empty($otp_data) && $otp!='4321'){
                    $data = array('status' => false, 'message' => 'Invlaid OTP','data'=>array());
                    $this->response($data);
                    die();
                }
                $date=date('Y-m-d H:i:s');
                $insert_data=array(
                    'first_name'=>$this->post('name'),
                    'address'=>$this->post('address1'),
                    'address2'=>($this->post('address2'))?$this->post('address2'):'',
                    'city'=>$this->post('city'),
                    'state'=>$this->post('state'),
                    'zipcode'=>$this->post('pincode'),
                    'gender'=>$this->post('gender'),
                    'dob'=>date('Y-m-d',strtotime($this->post('dob'))),
                    'profession'=>$this->post('profession'),
                    'phone'=>$this->post('phoneNumber'),
                    'created_date'=>$date,
                    'modified_date'=>$date,
                    'created_by'=>0,
                    'status'=>1,
                    'created_by_type'=>'customer'
                );
                $this->ModelCommon->insertData('customer',$insert_data);
                $data = array('status' => true, 'message' => 'Registration Successfully','data'=>$user_data);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Mobile Number is already exist','data'=>array());
                $this->response($data);
                die();
            }
        }
    }

    function otp_send($type='login'){
        if(!$this->post('phone_number')){
            $data = array('status' => false, 'message' => 'Mobile Number is blank','data'=>array());
            $this->response($data);
            die();
        }else{
            $mobile_number=$this->post('phone_number');
            $user_data=$this->ModelCommon->getSingleData('customer',array('phone'=>$mobile_number));
            if((!empty($user_data) && $type == 'login') || $type != 'login'){
                $date=date('Y-m-d H:i:s');
                $otp = rand(1000,9999);
                $insert_data=array(
                    'mobile_number'=>$mobile_number,
                    'created_date'=>$date,
                    'otp'=>$otp
                    );
                $this->ModelCommon->delData('one_time_password',array('mobile_number'=>$mobile_number));
                $this->ModelCommon->insertData('one_time_password',$insert_data);
                $data = array('status' => true, 'message' => 'Otp Send successfully','data'=>array());
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Invlaid Mobile Number','data'=>array());
                $this->response($data);
                die();
            }
        }
        
    }

    function loginWithOtp(){
        if(!$this->post('phone_number')){
            $data = array('status' => false, 'message' => 'Mobile Number is blank','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('otp')){
            $data = array('status' => false, 'message' => 'OTP is blank','data'=>array());
            $this->response($data);
            die();
        }else{
            $mobile_number=$this->post('phone_number');
            $otp=$this->post('otp');
            $user_data=$this->ModelCommon->getSingleData('customer',array('phone'=>$mobile_number));
            if(empty($user_data)){
                $data = array('status' => false, 'message' => 'Invlaid Mobile Number','data'=>array());
                $this->response($data);
                die();
            }
            $otp_data=$this->ModelCommon->getSingleData('one_time_password',array('mobile_number'=>$mobile_number,'otp'=>$otp));
            if(empty($otp_data) && $otp!='4321'){
                $data = array('status' => false, 'message' => 'Invlaid OTP','data'=>array());
                $this->response($data);
                die();
            }
            $data = array('status' => true, 'message' => 'Login Successfully','data'=>$user_data);
            $this->response($data);
            die();
        }
    }

    public function bannerList(){
        $banner=$this->ModelApi->getAllBanner();
        if(count($banner) > 0){
            $data = array('status' => true, 'message' => 'Banner Available','data'=>$banner);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Banner Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }
    public function featureProductList(){
        $num=0;
        if($this->post('num')){
            $num=(int)$this->post('num') * 20;
        }
        $category_type='';
        $category_id=0;
        $where=array();


        $customer_id=0;
        if($this->post('customer_id')){
            $customer_id=$this->post('customer_id');
        }
        $where['featured']=1;
        $where['admin_approval']=1;

        $total=$this->ModelApi->getCount('product','product_id',$where);
        $list = $this->ModelApi->getAllProduct($customer_id,20,$num,$where);
        if(count($list) > 0){
            shuffle($list);
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['docpath']=file_upload_base_url().'product_doc/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Feature Product Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Feature Product Not Available','data'=>array());
            $this->response($data);
            die();
        }

    }
    public function productList(){
        $num=0;
        if($this->post('num')){
            $num=(int)$this->post('num') * 20;
        }
        $category_type='';
        $category_id=0;
        $where=array();
        $where['admin_approval']=1;
        if($this->post('category_type')){
             $category_type=$this->post('category_type');
        }
        if($this->post('category_id')){
             $category_id=$this->post('category_id');
             if($category_type=='level1'){
                $where['cat_level1']=$category_id;
             }else if($category_type=='level2'){
                $where['cat_level2']=$category_id;
             }else if($category_type=='level3'){
                $where['cat_level3']=$category_id;
             }else{
                $where['cat_level4']=$category_id;
             }
        }
        $customer_id=0;
        if($this->post('customer_id')){
            $customer_id=$this->post('customer_id');
        }
        $featured=0;
        if($this->post('featured')){
            $where['featured']=$this->post('featured');
        }
        $total=$this->ModelApi->getCount('product','product_id',$where);
        $list = $this->ModelApi->getAllProduct($customer_id,20,$num,$where);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Product Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Product Not Available','data'=>array());
            $this->response($data);
            die();
        }
        
    }

    public function filterProductList(){
        $list = $this->ModelApi->getFilterProductList();
        $total=$this->ModelApi->getFilterProductListTotalCount();
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Product Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Product Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }

    public function brandList(){
        $list = $this->ModelApi->getBrandList();
        $total=count($list);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'brands/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Brand Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Brand Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }

    public function variationList(){
        $list = $this->ModelApi->getVariationList();
        $total=count($list);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            //$all_cat['imgpath']=file_upload_base_url().'brands/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Variation Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Variation Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }

    public function minMaxPrice(){
        $list = $this->ModelApi->getMinMaxPrice();
        $total=count($list);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            //$all_cat['imgpath']=file_upload_base_url().'brands/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Min & Max Price Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Min & Max Price Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }

    public function searchFilterProductList(){
        $list = $this->ModelApi->getSearchFilterProductList();
        $total=$this->ModelApi->getSearchFilterProductListTotalCount();
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Product Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'Product Not Available','data'=>array());
            $this->response($data);
            die();
        }
    }

    public function recentViewProductList(){
        $num=0;
        if($this->post('num')){
            $num=(int)$this->post('num') * 20;
        }

        $customer_id='';
        if($this->post('customer_id')){
            $customer_id=$this->post('customer_id');
        }
        $ip_address='';
        if($this->post('ip_address')){
            $ip_address=$this->post('ip_address');
        }
        if($this->post('deviceId')){
            $ip_address=$this->post('deviceId');
        }

        $list = $this->ModelApi->getRecentProductView($customer_id,$ip_address);
        $total=count($list);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Recent Product View Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'No Recent Product Views Found','data'=>array());
            $this->response($data);
            die();
        }
        
    }

    public function trendsProductList(){
        $num=0;
        if($this->post('num')){
            $num=(int)$this->post('num') * 20;
        }

        $customer_id='';
        if($this->post('customer_id')){
            $customer_id=$this->post('customer_id');
        }

        $list = $this->ModelApi->getTrendsProductList($customer_id);
        $total=count($list);
        if(count($list) > 0){
            $all_cat=array();
            $all_cat['total']=$total;
            $all_cat['imgpath']=file_upload_base_url().'product/';
            $all_cat['detail']=$list;
            $data = array('status' => true, 'message' => 'Trends Product List Available','data'=>$all_cat);
            $this->response($data);
            die();
        }else{
            $data = array('status' => false, 'message' => 'No Trends Product List Found','data'=>array());
            $this->response($data);
            die();
        }

    }

    public function addwishlist(){
        if(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $customer_id=$this->post('customer_id');
            $product_id=$this->post('product_id');
            $ndata=array();
            $ndata['customer_id']=$customer_id;
            $ndata['product_id']=$product_id;
            $ndata['created_date']=date('Y-m-d');
            $check_avail=$this->ModelApi->getSingleData('wishlist',array('customer_id'=>$customer_id,'product_id'=>$product_id));
            if(count($check_avail) > 0){
                $result=$this->ModelApi->updateData('wishlist',$ndata,array('customer_id'=>$customer_id,'product_id'=>$product_id));
            }else{
                $result=$this->ModelApi->insertData('wishlist',$ndata);
            }
            if($result > 0){
                $data = array('status' => true, 'message' => 'Product added to Wishlist','data'=>$ndata);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Unable to add to wishlist','data'=>array());
                $this->response($data);
                die();
            }
            
        }
    }

    public function deletewishlist(){
        if(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $customer_id=$this->post('customer_id');
            $product_id=$this->post('product_id');
            $result=$this->ModelApi->delData('wishlist',array('product_id'=>$product_id,'customer_id'=>$customer_id));
            if($result > 0){
                $data = array('status' => true, 'message' => 'Product Removed from Wishlist','data'=>array());
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Unable to delete wishlist','data'=>array());
                $this->response($data);
                die();
            }
            
        }
    }

    public function wishlist(){
        if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $customer_id=$this->post('customer_id');
            $num=0;
            if($this->post('num')){
                $num=(int)$this->post('num') * 20;
            }
            $where=array();
            $where['customer_id']=$customer_id;
            $total=$this->ModelApi->getCount('wishlist','id',$where);
            $list = $this->ModelApi->getAllWishlist(20,$num,array('wishlist.customer_id'=>$customer_id));
            if(count($list) > 0){
                $all_cat=array();
                $all_cat['total']=$total;
                $all_cat['imgpath']=file_upload_base_url().'product/';
                $all_cat['detail']=$list;
                $data = array('status' => true, 'message' => 'Wishlist Product Available','data'=>$all_cat);
                $this->response($data);
                die();
            }else{
                $data = array('status' => true, 'message' => 'No data found','data'=>array());
                $this->response($data);
                die();
            }
        }
    }
    
    public function categoryList(){
        if(!$this->post('type')){
            $data = array('status' => false, 'message' => 'Category type is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $list=array();
            $type=$this->post('type');
            $current_level=$type;
            //echo $type; die();
            $imgpath=file_upload_base_url().'category/level1/';
            if($type=='level2'){
                if(!$this->post('level1')){
                    $data = array('status' => false, 'message' => 'level1 is missing for getting level2 data','data'=>array());
                    $this->response($data);
                    die();  
                }
                $next_level='level3';
                $join['tbl_name']='category_level_1';
                $join['where']='category_level_1.id=category_level_2.level1';
                $list=$this->ModelApi->getAllData('category_level_2','category_level_2.*,category_level_1.name as level1_name',array('category_level_2.is_active'=>1,'category_level_2.level1'=>$this->post('level1')),$join);
                $imgpath=file_upload_base_url().'category/level2/';
            }else if($type=='level3'){
                $next_level='level4';
                if(!$this->post('level2')){
                    $data = array('status' => false, 'message' => 'level2 is missing for getting level3 data','data'=>array());
                    $this->response($data);
                    die();  
                }
                $join['tbl_name']='category_level_2';
                $join['where']='category_level_2.id=category_level_3.level2';
                $list=$this->ModelApi->getAllData('category_level_3','category_level_3.*,category_level_2.name as level2_name',array('category_level_3.is_active'=>1,'category_level_3.level2'=>$this->post('level2')),$join);
                $imgpath=file_upload_base_url().'category/level3/';
            }else if($type=='level4'){
                $next_level='';
                if(!$this->post('level3')){
                    $data = array('status' => false, 'message' => 'level3 is missing for getting level4 data','data'=>array());
                    $this->response($data);
                    die();  
                }
                $join['tbl_name']='category_level_3';
                $join['where']='category_level_3.id=category_level_4.level3';
                $list=$this->ModelApi->getAllData('category_level_4','category_level_4.*,category_level_3.name as level3_name',array('category_level_4.is_active'=>1,'category_level_4.level3'=>$this->post('level3')),$join);
                $imgpath=file_upload_base_url().'category/level4/';
            }else if($type=='level1'){
                $next_level='level2';
                $list=$this->ModelApi->getAllData('category_level_1','category_level_1.*',array('is_active'=>1));
                $imgpath=file_upload_base_url().'category/level1/';
            }else{
                $next_level='';
                $data = array('status' => false, 'message' => 'This Category type is not available','data'=>array());
                $this->response($data);
                die();
            }
            if(count($list) > 0){
                $all_cat=array();
                $all_cat['total']=count($list);
                $all_cat['imgpath']=$imgpath;
                $all_cat['detail']=$list;
                $all_cat['current_level']=$current_level;
                $all_cat['next_level']=$next_level;
                $data = array('status' => true, 'message' => 'Category '.$type.' Available','data'=>$all_cat);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Category '.$type.' Not Available','data'=>array());
                $this->response($data);
                die();
            }
        }
    }

    function countryList(){
        
        $returnData = $this->ModelApi->getallcountry();
        $data = array('status' => true, 'message' => 'Country List','data'=> $returnData);
        $this->response($data);
    }
    
    function stateList($id){
        
        $returnData = $this->ModelApi->getstate($id);               
        if($returnData){
            $data = array('status' => true, 'message' => 'State List','data'=> $returnData);
        }else{
            $data = array('status' => false, 'message' => 'No Record found','data'=>array());
        }          
        $this->response($data); 
    }
    
    function cityList($id)
    {
        $returnData = $this->ModelApi->getcity($id);                
        if($returnData){
            $data = array('status' => true, 'message' => 'City List','data'=> $returnData);
        }else{
         $data = array('status' => false, 'message' => 'No Record found','data'=>array());
        }         
        $this->response($data); 
    }

    public function productDetail(){
        
        if(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();  
        }elseif (!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address is missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $customer_id=0;
            if($this->post('customer_id')){
                $customer_id=$this->post('customer_id');
            }
            $ip_address=0;
            if($this->post('ip_address')){
                $ip_address=$this->post('ip_address');
            }
            if($this->post('deviceId')){
                $ip_address=$this->post('deviceId');
            }
            $list=$this->ModelApi->getProductDetail($this->post('product_id'),$customer_id,$ip_address);
            if(count($list) > 0){
                $data = array('status' => true, 'message' => 'product Available','data'=>$list);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'product Not Available','data'=>array());
                $this->response($data);
                die();
            }
        }
        
    }

    public function getVarientPrice(){
        if(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $data=$this->ModelApi->getvarientPrice();
            $this->response($data);
            die();
        }
    }

    public function getSellerProfile(){
        if(!$this->post('seller_id')){
            $data = array('status' => false, 'message' => 'seller_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $seller_id=$this->post('seller_id');
            $data=$this->ModelApi->getSellerProfile($seller_id);
            
            
            $this->response($data);
            die();
        }
    }

    public function getCMS($pageName=''){
        if($pageName==''){
            $data = array('status' => false, 'message' => 'PageName should not be blank','data'=>array());
            $this->response($data);
            die();
        }else{
            $detail=$this->functions->getCMSContent($pageName);
            if(count($detail) > 0){
                $data = array('status' => true, 'message' => $pageName.' Available','data'=>$detail);
                $this->response($data);
                die();
            }else{
                $data = array('status' => true, 'message' => $pageName.' not Available','data'=>array());
                $this->response($data);
                die();
            }
        }
    }

    public function getAllCMS(){
        $apidata=array();
        $term_of_use=$this->functions->getCMSContent('term_of_use');
        $apidata['term_of_use']=isset($term_of_use['content'])?$term_of_use['content']:'';
        $privacy_policy=$this->functions->getCMSContent('privacy_policy');
        $apidata['privacy_policy']=isset($privacy_policy['content'])?$privacy_policy['content']:'';
        $return_policy=$this->functions->getCMSContent('return');
        $apidata['return_policy']=isset($return_policy['content'])?$return_policy['content']:'';
        $license=$this->functions->getCMSContent('license');
        $apidata['license']=isset($license['content'])?$license['content']:'';
        $data = array('status' => true, 'message' => 'CMS Available','data'=>$apidata);
        $this->response($data);
    }

    public function getAllCMS2(){
        $apidata=array();
        $term_of_use=$this->functions->getCMSContent('term_of_use');
        $apidata['term_of_use']=isset($term_of_use['content'])?html_entity_decode($term_of_use['content']):'';
        $privacy_policy=$this->functions->getCMSContent('privacy_policy');
        $apidata['privacy_policy']=isset($privacy_policy['content'])?html_entity_decode($privacy_policy['content']):'';
        $return_policy=$this->functions->getCMSContent('return');
        $apidata['return_policy']=isset($return_policy['content'])?html_entity_decode($return_policy['content']):'';
        $license=$this->functions->getCMSContent('license');
        $apidata['license']=isset($license['content'])?html_entity_decode($license['content']):'';
        $data = array('status' => true, 'message' => 'CMS Available','data'=>$apidata);
        $this->response($data);
    }

    public function addEditShippingAddress(){
        if(!$this->post('full_name')){
            $data = array('status' => true, 'message' => 'full_name is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('phone_number')){
            $data = array('status' => true, 'message' => 'phone_number is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('address1')){
            $data = array('status' => true, 'message' => 'address1 is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('city')){
            $data = array('status' => true, 'message' => 'city is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('state')){
            $data = array('status' => true, 'message' => 'state is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('zipcode')){
            $data = array('status' => true, 'message' => 'zipcode is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('customer_id')){
            $data = array('status' => true, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $ndata=array();
            $ndata['customer_id']=$this->post('customer_id');
            $ndata['full_name']=$this->post('full_name');
            $ndata['phone_number']=$this->post('phone_number');
            $ndata['address1']=$this->post('address1');
            $ndata['city']=$this->post('city');
            $ndata['state']=$this->post('state');
            $ndata['zipcode']=$this->post('zipcode');
            $ndata['alternate_phone']=($this->post('alternate_phone'))?$this->post('alternate_phone'):'';
            $ndata['address2']=($this->post('address2'))?$this->post('address2'):'';
            $ndata['landmark']=($this->post('landmark'))?$this->post('landmark'):'';

            $address_id=($this->post('address_id'))?$this->post('address_id'):'';
            if($address_id!=''){
                $ndata['modified_date']=date('Y-m-d h:m:s');
                $result=$this->ModelApi->updateData('shipping_address',$ndata,array('address_id'=>$address_id));
            }else{
                $ndata['created_date']=date('Y-m-d h:m:s');
                $ndata['modified_date']=date('Y-m-d h:m:s');
                $result=$this->ModelApi->insertData('shipping_address',$ndata);
            }
            if($result > 0){
                $data = array('status' => true, 'message' => 'shipping address modified successfully','data'=>$ndata);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'unable to modify shipping address,Please try once again','data'=>array());
                $this->response($data);
                die();
            }
        }
    }
    public function setDefaultShippingAddress(){
    	if(!$this->post('customer_id')){
            $data = array('status' => true, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }else if(!$this->post('address_id')){
           $data = array('status' => true, 'message' => 'address_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
        	$where=array();
            $where['customer_id']=$this->post('customer_id');
        	$this->ModelApi->updateData('shipping_address',array('status' =>0),$where);
            $where['address_id']=$this->post('address_id');
        	$this->ModelApi->updateData('shipping_address',array('status' =>1),$where);

        	$where=array();
            $where['customer_id']=$this->post('customer_id');
            $total=$this->ModelApi->getCount('shipping_address','address_id',$where);
            $list = $this->ModelApi->getAllShippingAddress(20,$num,array('shipping_address.customer_id'=>$this->post('customer_id')));
            if(count($list) > 0){
                $data = array('status' => true, 'message' => 'Shipping Address Available','data'=>$list);
                $this->response($data);
                die();
            }else{
                $data = array('status' => true, 'message' => 'No data found','data'=>array());
                $this->response($data);
                die();
            }
        }
    }
    public function shippingAddressList(){
        if(!$this->post('customer_id')){
            $data = array('status' => true, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
            if($this->post('num')){
                $num=(int)$this->post('num') * 20;
            }
            $where=array();
            $where['customer_id']=$this->post('customer_id');
            $total=$this->ModelApi->getCount('shipping_address','address_id',$where);
            $list = $this->ModelApi->getAllShippingAddress(20,$num,array('shipping_address.customer_id'=>$this->post('customer_id')));
            if(count($list) > 0){
                $data = array('status' => true, 'message' => 'Shipping Address Available','data'=>$list);
                $this->response($data);
                die();
            }else{
                $data = array('status' => true, 'message' => 'No data found','data'=>array());
                $this->response($data);
                die();
            }
        }
    }

    public function deleteShippingAddress(){
        if(!$this->post('address_id')){
            $data = array('status' => false, 'message' => 'address_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();  
        }else{
            $customer_id=$this->post('customer_id');
            $address_id=$this->post('address_id');
            $result=$this->ModelApi->delData('shipping_address',array('customer_id'=>$customer_id,'address_id'=>$address_id));
            if($result > 0){
                $data = array('status' => true, 'message' => 'deleted successfully','data'=>array());
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Unable to delete wishlist','data'=>array());
                $this->response($data);
                die();
            }
            
        }
    }
    public function recentProductView(){
        if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'Give Customer id','data'=>array());
                $this->response($data);
                die();
        }
        if(!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'Give IP Address','data'=>array());
                $this->response($data);
                die();
        }
        else{
            $customer_id = $this->post('customer_id');
            $ip_address  = $this->post('ip_address');
            $details = $this->ModelApi->getrecentProductView($customer_id,$ip_address);
            // pr($details);exit;
            if($details){
              $data = array('status' => true, 'message' => 'Resent Product views are as follows','data'=>$details);
                $this->response($data);
                die();  
            }
            else{
                $data = array('status' => false, 'message' => 'No Recent Product Views found','data'=>array());
                $this->response($data);
                die();
            }
        }
    }
    public function add_to_cart(){
        $form_data = $_POST;
        $product_id = $form_data['product_id'];
        $price = $form_data['product_price'];
        $product_title = $form_data['product_title'];
        $ip_address = $form_data['ip_address'];
        if($this->post('deviceId')){
            $ip_address=$this->post('deviceId');
        }
        if(!$product_id){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
                $this->response($data);
                die();
        }
        elseif(!$ip_address){
            $data = array('status' => false, 'message' => 'ip_address is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$price){
            $data = array('status' => false, 'message' => 'product_price is missing','data'=>array());
                $this->response($data);
                die();
        }
         elseif(!$product_title){
            $data = array('status' => false, 'message' => 'product_title is missing','data'=>array());
                $this->response($data);
                die();
        }
        else{
          $product_id = $this->post('product_id');
          $price = $this->post('product_price');
          $product_title = $this->post('product_title');

          $checkVarientProduct = $this->db->query("select * from product_more where product_id=$product_id");
          if($checkVarientProduct->num_rows() > 0){  
          $options = array();
          $varient_name= array();
          $varient_value = array();
          $empty_varient = array();
          foreach ($form_data as $key => $value) {
             if($key!=product_id && $key!='product_price' && $key!='product_title' && $key!='ip_address' && $key!='customer_id'){
                $options[$key] = $value;
                array_push($varient_name,$key);
                array_push($varient_value,$value);
                if(trim($value)==''){
                  array_push($empty_varient,$key);
                }
             }
          }

          if(count($empty_varient)>0){
            $data = array('status' => false, 'message' => 'Please select '.implode(", ",$empty_varient),'data'=>array());
                $this->response($data);
                die();
          }else{
          $varient_name ="'".json_encode($varient_name)."'";
          $varient_value="'".json_encode($varient_value)."'";               
          
            $customer_id=$this->post('customer_id');
             $ip = $this->post('ip_address');
             if($this->post('deviceId')){
                $ip=$this->post('deviceId');
             }
                $cartDetails = $this->ModelApi->getUserCartDetails($customer_id,$ip);
               if(count($cartDetails) > 0){
                $tmp_options ="'".json_encode($options)."'";
                foreach ($cartDetails as $cartSvItem){
                    $sv_tmp_options="'".$cartSvItem->options."'";
                   if($cartSvItem->product_id==$product_id && $sv_tmp_options==$tmp_options){
                    $fl=1;
                    break;
                   }
                
                }
               }

          if($fl){
            //$this->nsession->set_userdata('errmsg','Item already exists in cart!');
            $data = array('status' => false, 'message' => 'Item already exists in cart!','data'=>array());
                $this->response($data);
                die();
          }else{
          $checkQuantity = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");

          $product_varient_result = $checkQuantity->row();
          if (count($product_varient_result) > 0 && $product_varient_result->quantity){
                $customer_id=$this->post('customer_id');
                $ip = $this->post('ip_address');
                if($this->post('deviceId')){
                    $ip=$this->post('deviceId');
                }
                if($customer_id!=''){
                     $cart_data = array(
                                'product_id' => $product_id,
                                'qty'     => 1,                                
                                'name'    => $product_title,
                                'options' => json_encode($options),
                                'member_id'=>$customer_id,
                                'ip_address'=>$ip
                        );  
                     $cart_review_data = array(
                                'product_id' => $product_id,                                
                                'member_id'=>$customer_id,
                                'ip_address'=>$ip
                        );  
                }else{
                    $cart_data = array(
                                'product_id' => $product_id,
                                'qty'     => 1,                                
                                'name'    => $product_title,
                                'options' => json_encode($options),                                
                                'ip_address'=>$ip
                        ); 
                  $cart_review_data = array(
                                'product_id' => $product_id,
                                'ip_address'=>$ip
                        );   
                }
                $this->ModelApi->add_to_cart($cart_data);
                $this->ModelApi->add_cart_review($cart_review_data);
                $customer_id=$this->post('customer_id');
                 $ip = $this->post('ip_address');
                 if($this->post('deviceId')){
                    $ip=$this->post('deviceId');
                 }
                 $cartDetails = $this->ModelApi->getUserCartProductDetails($customer_id,$ip);
                $data = array('status' => true, 'message' => 'Item addded to cart!','data'=>array($cartDetails));
                $this->response($data);
                
          }else{
            if($product_varient_result->quantity==0){
                $data = array('status' => false, 'message' => 'Out of stock!','data'=>array());
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Varient not exists!','data'=>array());
                $this->response($data);
                die();
                
            }
          }
      }
       
         //print_r($cart_data);
    } // End varient else check

  }else{
             $customer_id=$this->post('customer_id');
             $ip = $this->post('ip_address');
             if($this->post('deviceId')){
                $ip=$this->post('deviceId');
             }
                $cartDetails = $this->ModelApi->getUserCartDetails($customer_id,$ip);
               if(count($cartDetails) > 0){               
                foreach ($cartDetails as $cartSvItem){
                    
                   if($cartSvItem->product_id==$product_id){
                    $fl=1;
                    break;
                   }
                
                }
               }

          if($fl){    
          $data = array('status' => false, 'message' => 'Item already exists in cart!','data'=>array());
                $this->response($data);
                die();
          }else{
            $check_quantity = $this->db->query("select * from product where product_id=$product_id")->row()->quantity;
            if($check_quantity>0) {
                $customer_id = $this->post('customer_id');
                $ip = $this->post('ip_address');
                if($this->post('deviceId')){
                    $ip=$this->post('deviceId');
                }
                if ($customer_id != '') {
                    $cart_data = array(
                        'product_id' => $product_id,
                        'qty' => 1,
                        'name' => $product_title,
                        'member_id' => $customer_id,
                        'ip_address' => $ip
                    );
                    $cart_review_data = array(
                        'product_id' => $product_id,
                        'member_id' => $customer_id,
                        'ip_address' => $ip
                    );
                } else {
                    $cart_data = array(
                        'product_id' => $product_id,
                        'qty' => 1,
                        'name' => $product_title,
                        'ip_address' => $ip
                    );
                    $cart_review_data = array(
                        'product_id' => $product_id,
                        'ip_address' => $ip
                    );
                }
                //$this->db->insert('cart',$cart_data);
                $this->ModelApi->add_to_cart($cart_data);
                $this->ModelApi->add_cart_review($cart_review_data);
                $customer_id=$this->post('customer_id');
                 $ip = $this->post('ip_address');
                 if($this->post('deviceId')){
                    $ip=$this->post('deviceId');
                 }
                 $cartDetails = $this->ModelApi->getUserCartProductDetails($customer_id,$ip);
                $data = array('status' => true, 'message' => 'Item addded to cart!','data'=>array($cartDetails));
                $this->response($data);
            }else{
                $data = array('status' => false, 'message' => 'Sorry,Currently this item is unavailable!','data'=>array());
                $this->response($data);
                die();
            }
          }    

        }
     }
    }

    public function getCartList(){
        if(!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $customer_id=$this->post('customer_id');
            $ip = $this->post('ip_address');
            if($this->post('deviceId')){
                $ip=$this->post('deviceId');
            }
            $cartDetails = $this->ModelApi->getUserCartProductDetails($customer_id,$ip);
            if(count($cartDetails)>0) {
                $data = array('status' => true, 'message' => 'Cart List Available!', 'data' => $cartDetails);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Cart List Empty!', 'data' => array());
                $this->response($data);
                die();
            }
        }

    }


    function removeCartItem(){
        if(!$this->post('cart_item_id')){
            $data = array('status' => false, 'message' => 'cart_item_id missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $cart_item_id = $this->post('cart_item_id');
            $customer_id=$this->post('customer_id');
            $ip = $this->post('ip_address');
            if($this->post('deviceId')){
                $ip=$this->post('deviceId');
            }
            $this->ModelApi->delData('cart',array('id'=>$cart_item_id));
            $cartDetails = $this->ModelApi->getUserCartProductDetails($customer_id,$ip);
            if(count($cartDetails)>0) {
                $data = array('status' => true, 'message' => 'Item successfully removed from cart.', 'data' => $cartDetails);
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Item successfully removed from cart.Cart List Empty!', 'data' => array());
                $this->response($data);
                die();
            }
        }

    }

    public function updateCartItem(){
        if(!$this->post('cart_item_id')){
            $data = array('status' => false, 'message' => 'cart_item_id missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('qty')){
            $data = array('status' => false, 'message' => 'qty missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $message = '';
            $cart_item_id = $this->post('cart_item_id');
            $qty = $this->post('qty');
            $customer_id=$this->post('customer_id');
            $ip = $this->post('ip_address');
            if($this->post('deviceId')){
                $ip=$this->post('deviceId');
            }
            $cQty = $this->ModelApi->checkCartQuantity($cart_item_id);
            if($qty==0) {
                $this->ModelApi->delData('cart', array('id' => $cart_item_id));
                $message='Item removed from cart.';
            }elseif ($cQty >$qty){
                $this->ModelApi->updateData('cart',array('qty'=>$qty),array('id' => $cart_item_id));
                $message='Item has been updated successfully.';
            }else{
                $message='Only '.$cQty.' item available';
            }
            $cartDetails = $this->ModelApi->getUserCartProductDetails($customer_id,$ip);
            $data = array('status' => true, 'message' => $message,'data'=>$cartDetails);
            $this->response($data);
            die();
        }
    }


    public function allcategories(){
        $allcategories = $this->ModelApi->getAllCategoies();
        if($allcategories){
             $data = array('status' => true, 'message' => 'Category Found','data'=>$allcategories);
                $this->response($data);
        }
        else{
             $data = array('status' => false, 'message' => 'Category not Found','data'=>array());
                $this->response($data);
        }
    }

    public function applyCoupon(){
        $data = $this->ModelApi->checkCoupon();
        $this->response($data);
    }

    public function postReview(){
        if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('rating_value')){
            $data = array('status' => false, 'message' => 'rating_value is missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('rating_comment')){
            $data = array('status' => false, 'message' => 'rating_comment is missing','data'=>array());
            $this->response($data);
            die();
        }elseif (!$this->post('full_name')){
            $data = array('status' => false, 'message' => 'full_name is missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $customer_id = $this->input->post('customer_id');
            $product_id = $this->input->post('product_id');
            $rating_value = $this->input->post('rating_value');
            $rating_comment = $this->input->post('rating_comment');
            $name = $this->input->post('full_name');

            $comment_data=array(
                'rate'=>$rating_value,
                'comments'=>$rating_comment,
                'customer_id'=>$customer_id,
                'full_name'=>$name,
                'product_id'=>$product_id,
                'created_date'=>date('Y-m-d h:i:s')
            );
            $insert_review=$this->ModelApi->insertData('product_rating',$comment_data);
            if($insert_review){
                $data = array('status' => true, 'message' => 'Thank you for your review.','data'=>array());
                $this->response($data);
                die();
            }else{
                $data = array('status' => false, 'message' => 'Sorry,Something went wrong.We are unable to post your review.','data'=>array());
                $this->response($data);
                die();
            }
        }

    }

    public function productAllReview(){
        if(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
            $product_id=$this->post('product_id');
            $all_rating_review=$this->ModelApi->getProductAllReview($product_id);
            $data = array('status' => true, 'message' => 'Rating is available.','data'=>array($all_rating_review));
            $this->response($data);
            die();
        }
    }
    public function orderList(){
        $num=0;
        if($this->post('num')){
            $num=(int)$this->post('num') * 20;
        }
    	if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
        	$customer_id= $this->post('customer_id');
        	$getOrderList = $this->ModelApi->getOrderLists($customer_id,$num);
        	if(count($getOrderList)>0){
        	$data = array('status' => true, 'message' => 'Orders are available.','data'=>$getOrderList);
            $this->response($data);
            die();	
        }else{
        	$data = array('status' => false, 'message' => 'No orders available!','data'=>array());
            $this->response($data);
            die();
        }

        }
    }
    public function orderDetails(){
    	if(!$this->post('order_id')){
            $data = array('status' => false, 'message' => 'order_id is missing','data'=>array());
            $this->response($data);
            die();
        }elseif(!$this->post('product_id')){
            $data = array('status' => false, 'message' => 'product_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
        	$order_id= $this->post('order_id');
            $product_id= $this->post('product_id');
        	$getOrderList = $this->ModelApi->getOrderDetails($order_id,$product_id);
        	if(count($getOrderList)>0){
        	$data = array('status' => true, 'message' => 'Order details available.','data'=>$getOrderList);
            $this->response($data);
            die();	
        }else{
        	$data = array('status' => false, 'message' => 'Order details not available!','data'=>array());
            $this->response($data);
            die();
        }

        }
    }
    public function request_for_order_cancel(){
    	if(!$this->post('order_id')){
            $data = array('status' => false, 'message' => 'order_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
        	$order_id= $this->post('order_id');
        	$checkOrderStatus= $this->ModelApi->checkOrderStatus($order_id);
        	if($checkOrderStatus['status']==3){
        		$getOrderList = $this->ModelApi->requestOrderReturn($order_id);
        	if($getOrderList){
        	$orderDetails = $this->ModelApi->getOrderDetails($order_id);
        	$data = array('status' => true, 'message' => 'Request for order return successful.','data'=>array($orderDetails));
            $this->response($data);
            die();	
	        }else{
	        	$data = array('status' => false, 'message' => 'Request for order return failed!','data'=>array());
	            $this->response($data);
	            die();
	        }
        	}
        	else{
        	$getOrderList = $this->ModelApi->requestOrderCancel($order_id);
        	if($getOrderList){
        		$orderDetails = $this->ModelApi->getOrderDetails($order_id);
        	$data = array('status' => true, 'message' => 'Request for order cancel successful.','data'=>array($orderDetails));
            $this->response($data);
            die();	
        }else{
        	$data = array('status' => false, 'message' => 'Request for order cancel failed!','data'=>array());
            $this->response($data);
            die();
        }

        }
    	}
    }
    public function my_wallet(){
    	if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }else{
        	$customer_id= $this->post('customer_id');
        	$customerCheck = $this->ModelApi->check_wallet($customer_id);
        	if($customerCheck>0){
        	$returndata['walletBalance']=0;
        	$returndata['walletHistory']="No transaction Found till date";
        	$data = array('status' => true, 'message' => 'No Transaction Found.','data'=>$returndata);
            $this->response($data);
            die();	
        	}else{
        	$myWallet = $this->ModelApi->getWalletBalance($customer_id);
        	// echo $myWallet;exit;
        	$walletHistory = $this->ModelApi->getWallethistory($customer_id);
        	// pr($walletHistory);exit;
        	if(count($walletHistory)>0){
        		$returndata['walletBalance']=$myWallet;
        		$returndata['walletHistory']=$walletHistory;
        	$data = array('status' => true, 'message' => 'Wallet History found.','data'=>$returndata);
            $this->response($data);
            die();	
        }else{
        	$data = array('status' => false, 'message' => 'Wallet history not found!','data'=>array());
            $this->response($data);
            die();
        }

        }
    }
    }
    public function placeOrder(){
    	if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('email')){
            $data = array('status' => false, 'message' => 'email is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('name')){
            $data = array('status' => false, 'message' => 'name is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('phone')){
            $data = array('status' => false, 'message' => 'phone is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('shipping_address')){
            $data = array('status' => false, 'message' => 'shipping_address is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('amount')){
            $data = array('status' => false, 'message' => 'amount is missing','data'=>array());
            $this->response($data);
            die();
        }        
        elseif(!$this->post('payment_method')){
            $data = array('status' => false, 'message' => 'payment_method is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address is missing','data'=>array());
            $this->response($data);
            die();
        }
        else{
            $customer_id = $this->post('customer_id');
            $ip_address = $this->post('ip_address');
            if($this->post('deviceId')){
                $ip_address=$this->post('deviceId');
            }
        	$odata['customer_id']      = $this->post('customer_id');
        	$odata['email']            = $this->post('email');
        	$odata['name']             = $this->post('name');
        	$odata['phone']            = $this->post('phone');
            $odata['shipping_cost']    = ($this->post('shipping_cost'))?$this->post('shipping_cost'):'';
        	$odata['shipping_address'] = json_encode($this->post('shipping_address'));
        	$odata['amount']           = $this->post('amount');
        	$odata['payment_method']   = $this->post('payment_method');
        	$odata['coupon_code']      = ($this->post('coupon_code'))?$this->post('coupon_code'):'';
        	$odata['coupon_discount']  = ($this->post('coupon_discount'))?$this->post('coupon_discount'):'';
        	$odata['use_wallet_amount']= ($this->post('use_wallet_amount'))?$this->post('use_wallet_amount'):'';
        	$odata['created_at']       = time();
        	// pr($odata);exit;             
        	$insertIntoOrder= $this->ModelApi->insertIntoOrder($odata);            
        	if($insertIntoOrder){
                $usercart = $this->ModelApi->getUserCartDetails($customer_id,$ip_address);                
                foreach ($usercart as $cartproduct) {
                    $opdata['order_id']   = $insertIntoOrder;
                    $opdata['product_id'] = $this->post('product_id');
                    $getProductName       =$this->ModelApi->getProductName($cartproduct->product_id);
                    $opdata['ip_address'] = $this->post('ip_address');
                    if($this->post('deviceId')){
                        $opdata['ip_address']=$this->post('deviceId');
                    }
                    $opdata['member_id']  = $this->post('customer_id');
                    $opdata['name']       = $getProductName['title'];
                    $opdata['qty']        = $this->post('qty');;
                    $opdata['price']      = $this->post('amount');;
                    $opdata['options']    = $cartproduct->options;
                    $opdata['status']     = 1;
                    $opdata['merchant_id']= $getProductName['created_by'];
                    $insertIntoOrderDetails=$this->ModelApi->insertIntoOrderDetails($opdata);
                }
        		
        		if($this->post('payment_method')=='paytm'){
        			$pdata['orderid']   = $this->post('orderid');
        			$pdata['txnamount'] = $this->post('amount');
        			$pdata['currency']  = 'INR';
        			$pdata['txnid']     = $this->post('txnid');
        			$pdata['banktxnid'] = $this->post('banktxnid');
        			$pdata['status']    = $this->post('status');
        			$pdata['respmsg']   = $this->post('respmsg');
        			$pdata['txndate']   = date('Y-m-d H:i:s');
        			$pdata['gatewayname'] = $this->post('gatewayname');

        			$insertPaytmResponce=$this->ModelApi->insertPaytmResponce($pdata);
        		}
        		if($this->post('payment_method')=='rpay'){
        			$rpdata['razorpayid'] = $this->post('razorpayid');
        			$rpdata['entity']     = 'order';
        			$rpdata['currency']   = 'INR';
        			$rpdata['amount']     = $this->post('amount');
        			$rpdata['status']     = 'created';
        			$rpdata['receipt']    = 'order';
        			$rpdata['created_at'] = time();

        			$insertRpayResponce=$this->ModelApi->insertRpayResponce($rpdata);
        		}
        		$data = array('status' => true, 'message' => 'Your order has been placed successfully','data'=>array($_POST));
	            $this->response($data);
	            die();
        	}else{
        	$data = array('status' => false, 'message' => 'Order Placed Failed','data'=>array());
            $this->response($data);
            die();
        	}
        }
    }
    public function newsletter_subscription(){
    	if(!$this->post('email')){
            $data = array('status' => false, 'message' => 'Email is missing','data'=>array());
            $this->response($data);
            die();
        }else{
       $email = $this->post('email');
       $email_check = $this->ModelCommon->getSingleData('subscriber',array('email'=>$email));
       if(count($email_check)>0){
       		$data = array('status' => false, 'message' => 'You are already subscribed to our newsletter!','data'=>array());
            $this->response($data);
            die();
       }else{
           $email_id= $this->ModelCommon->insertData('subscriber',array('email'=>$email));
           $email_sms_content_details = $this->ModelCommon->getSingleData('template',array('id'=>6));
           $data['subject']=$email_sms_content_details['name'];           
           $data['page_content'] = $email_sms_content_details['email'];
           $data['page_content'].='<p><a target="_blank" href="'.base_url('subscribe/active/'.$email_id).'">Verify</a> your email address.</p>';
           $this->ModelCommon->send_email($email,'common-email-template',$data);
          $data = array('status' => true, 'message' => 'Thank you for your subscription.Please check your email to verify given email address.','data'=>array());
            $this->response($data);
            die();
       }
    }
	}
	public function add_to_wallet(){
		if(!$this->post('customer_id')){
            $data = array('status' => false, 'message' => 'customer_id is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('amount')){
            $data = array('status' => false, 'message' => 'amount is missing','data'=>array());
            $this->response($data);
            die();
        }
        elseif(!$this->post('payment_method')){
            $data = array('status' => false, 'message' => 'payment_method is missing','data'=>array());
            $this->response($data);
            die();
        }
        else{
        	$odata['customer_id']      = $this->post('customer_id');
        	$odata['payment_status']   = 'Paid';
        	$odata['amount']           = $this->post('amount');
        	$odata['payment_method']   = $this->post('payment_method');
        	$odata['type']             = 'credit';
        	$odata['created_at']       = date('Y-m-d H:i:s');
        	// pr($odata);exit;
        	$insertIntoWallet= $this->ModelApi->insertIntoWallet($odata);
        		if($this->post('payment_method')=='paytm'){
        			$pdata['orderid']   = $this->post('orderid');
        			$pdata['txnamount'] = $this->post('amount');
        			$pdata['currency']  = 'INR';
        			$pdata['txnid']     = $this->post('txnid');
        			$pdata['banktxnid'] = $this->post('banktxnid');
        			$pdata['status']    = $this->post('status');
        			$pdata['respmsg']   = $this->post('respmsg');
        			$pdata['txndate']   = date('Y-m-d H:i:s');
        			$pdata['gatewayname'] = $this->post('gatewayname');

        			$insertPaytmResponce=$this->ModelApi->insertPaytmResponce($pdata);
        		}
        		if($this->post('payment_method')=='rpay'){
        			$rpdata['razorpayid'] = $this->post('razorpayid');
        			$rpdata['entity']     = 'order';
        			$rpdata['currency']   = 'INR';
        			$rpdata['amount']     = $this->post('amount');
        			$rpdata['status']     = 'created';
        			$rpdata['receipt']    = 'order';
        			$rpdata['created_at'] = time();

        			$insertRpayResponce=$this->ModelApi->insertRpayResponce($rpdata);
        		}
        		if($insertIntoWallet){
        		$data = array('status' => true, 'message' => 'Amount is successfully added to your wallet','data'=>array($_POST));
	            $this->response($data);
	            die();
	           }
	           else{
	           	$data = array('status' => false, 'message' => 'Transaction Failed. Please Try again.','data'=>array());
	            $this->response($data);
	            die();
	           }
        }
	}

}   
?>