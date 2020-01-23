<?php
class ModelProduct extends CI_Model {

    function __construct()
    {
        parent::__construct();
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
		$cat 			= $param['para1']; 
		$cat_val 		= $param['para2']; 
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('PRODUCT_LIST','sortType','DESC');
			$sortField   	= $this->nsession->get_param('PRODUCT_LIST','sortField','id');
			$searchField 	= $this->nsession->get_param('PRODUCT_LIST','searchField','');
			$searchString 	= $this->nsession->get_param('PRODUCT_LIST','searchString','');
			$searchText  	= $this->nsession->get_param('PRODUCT_LIST','searchText','');
			$searchFromDate = $this->nsession->get_param('PRODUCT_LIST','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('PRODUCT_LIST','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('PRODUCT_LIST','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('PRODUCT_LIST','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('PRODUCT_LIST','searchDisplay',20);
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
		
		$this->nsession->set_userdata('PRODUCT_LIST', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(product_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->where('product.is_active',1);
		$this->db->where($cat,$cat_val);
		$this->db->select('product.*');

		$recordSet = $this->db->get('product'); 
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
			$this->db->select('product.*');
			$this->db->where('product.is_active',1);
			$this->db->where($cat,$cat_val);
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('product.product_id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('product');
		$rs = false;

		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
			{
				$picData=$this->db->select('path,path_sm')->get_where('product_images',array('product_id'=>$row['product_id'],'type'=>'main'))->row_array();
				if(isset($picData['path'])){
					if($picData['path_sm']!=''){
						$pic=file_upload_base_url().'product/'.$picData['path_sm'];
					}else{
						$pic=file_upload_base_url().'product/'.$picData['path'];
					}
				}else{
					//$pic=file_upload_base_url().'product/'
					$pic=css_images_js_base_url().'images/no_pr_img.jpg';
				}
				$row['pic']=$pic;
				$wishlist=0;
				$customer_id=0;
				if($this->nsession->userdata('member_session_id')){
					$customer_id=$this->nsession->userdata('member_session_id');
				}
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$row['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $row['wishlist']=$wishlist;
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

	function getRelatedProduct($product_id,$cat_level1='',$cat_level2='',$cat_level3=''){
		$this->db->limit(10);
		$where=array();
		if($cat_level1!=''){
			$where['cat_level1']=$cat_level1;
		}
		if($cat_level2!=''){
			$where['cat_level2']=$cat_level2;
		}
		if($cat_level3!=''){
			$where['cat_level3']=$cat_level3;
		}
		$where['is_active']=1;
        $where['is_combo !=']=1;
		$where['product_id !=']=$product_id;
		$result=$this->db->get_where('product',$where)->result_array();
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

		return $result;
	}

    function getComboProduct($product_id,$cat_level1='',$cat_level2='',$cat_level3=''){
        $this->db->limit(10);
        $where=array();
        if($cat_level1!=''){
            $where['cat_level1']=$cat_level1;
        }
        if($cat_level2!=''){
            $where['cat_level2']=$cat_level2;
        }
        if($cat_level3!=''){
            $where['cat_level3']=$cat_level3;
        }
        $where['is_active']=1;
        $where['is_combo']=1;
        $where['product_id !=']=$product_id;
        $result=$this->db->get_where('product',$where)->result_array();
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

        return $result;
    }

	public function getProductDetail($product_id,$customer_id=0){
        $result=$this->db->get_where('product',array('product_id'=>$product_id))->row_array();
        if(count($result) > 0){
            $product_id=$result['product_id'];
            $wishlist=0;
            if($customer_id > 0){
                $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$result['product_id']))->row_array();
                if(count($wishlist_status) > 0){
                    $wishlist=1;
                }
            }
            $result['wishlist']=$wishlist;
            $ratingData=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$result['product_id']))->row_array();
			if(isset($ratingData['avg_rate'])){
				$rating=round($ratingData['avg_rate']);
			}else{
				$rating=0;
			}
            $result['rating']=$rating;
            $result['seller']=$this->db->select('*')->get_where('merchants_business_details',array('merchants_id'=>$result['created_by']))->row_array();
            $result['comments']=$this->db->select('product_rating.*')->order_by('product_rating.rating_id','DESC')->get_where('product_rating',array('product_rating.product_id'=>$result['product_id'],'product_rating.is_active'=>1))->result_array();
            $result['image_path']=file_upload_base_url().'product/';
            $result['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$result['product_id'],'type'=>'main'))->result_array();
            
            $result['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$result['product_id']))->result_array();
            //pr($result['vara_list']);
            $namevaluelist=array();
            if(count($result['vara_list']) > 0){
                foreach($result['vara_list'] as $key => $value) {
                    $variation_name=(array)json_decode($value['variation_name'],true);
                    $variation_value=(array)json_decode($value['variation_value'],true);
                    $nvara=array();
                    $nvara['variation_id']=$value['id'];
                    $nvara['sku']=$value['sku'];
                    $nvara['start_price']=$value['start_price'];
                    $nvara['quantity']=$value['quantity'];
                    $nvara['upc']=$value['upc'];
                    $nvara['NameValue']=array();
                    $variation_attr=array();
                    
                    if(count($variation_name) > 0){
                        foreach ($variation_name as $key_variation => $value_variation) {
                            $variation_combination=array();
                            $variation_combination['Name']=$value_variation;
                            $variation_combination['Value']=$variation_value[$key_variation];
                            $nvara['NameValue'][]=$variation_combination;
                            /*if(!in_array($variation_value[$key_variation], $namevaluelist[$value_variation][])){
                                $namevaluelist[$value_variation][]=$variation_value[$key_variation];
                            }*/
                            $namevaluelist[$value_variation][]=$variation_value[$key_variation];

                        }
                    }
                    
                    $nvara['pic']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['id'],'type'=>'variation'))->result_array();
                    //unset($result['vara_list'][$key]['variation_name']);
                    //unset($result['vara_list'][$key]['variation_value']);
                    
                    $result['vara_list'][$key]['variations']=$nvara;
                }
            }
            $nnvarvalue=array();
            
            if(count($namevaluelist) > 0){
                foreach ($namevaluelist as $key => $value) {
                    $nnvarvalue[]=array('Name'=>$key,'Value'=>array_unique($value));
                    
                }
            }
            //pr($nnvarvalue);
            $result['namevalue']=$nnvarvalue;
        }

        return $result;

    }


  public function save_recent_view_product($customer_id,$product_id,$ip_address){
    if ($customer_id!='' && $customer_id>0) {
    	$check_exist = $this->db->query("select * from product_recent_view where customer=$customer_id and product_id=$product_id")->num_rows();
    }else{
    	$check_exist = $this->db->query("select * from product_recent_view where product_id=$product_id and ip_address='".$ip_address."'")->num_rows();
    }
    if($check_exist==0){
    	$this->db->insert('product_recent_view',array('product_id'=>$product_id,'customer'=>$customer_id,'ip_address'=>$ip_address));
    	return true;
    }

    return false;   

  }

  function get_level4_categories($level3_id)
  {
    $lavel4_categories_query = $this->db->query("select * from category_level_4 where level3=$level3_id and is_active=1 order by sort_num asc");
    if($lavel4_categories_query->num_rows() > 0){
      return $lavel4_categories_query->result();
    }
    return false;
  }

  function get_level_categories($levelname,$levelid)
  {
    if($levelname=='level2'){
      $lavel_categories_query = $this->db->query("select * from category_level_3 where level2=$levelid and is_active=1 order by sort_num asc");
    }

    if($levelname=='level1'){
      $lavel_categories_query = $this->db->query("select * from category_level_2 where level1=$levelid and is_active=1 order by sort_num asc");
    }
    
    if($lavel_categories_query->num_rows() > 0){
      return $lavel_categories_query->result();
    }
    return false;
  }

  function getMinMaxPrice($levelname,$levelid){
    /*$returnData=array();
    $purchaseminMaxSql ='select max(NULLIF(purchase_price,0)) as MaxPrice ,min(NULLIF(purchase_price,0)) as MinPrice from product where cat_'.$levelname.'='.$levelid.' and is_active=1 and admin_approval=1';
    $saleminMaxSql ='select max(NULLIF(sale_price,0)) as MaxPrice ,min(NULLIF(sale_price,0)) as MinPrice from product where cat_'.$levelname.'='.$levelid.' and is_active=1 and admin_approval=1';
    $purchaseminMaxSqlResult = $this->db->query($purchaseminMaxSql);
    $saleminMaxSqlResult = $this->db->query($saleminMaxSql);
    if($purchaseminMaxSqlResult->num_rows() > 0){
      $purchase_price = $purchaseminMaxSqlResult->row();
      $sale_price = $saleminMaxSqlResult->row();
      if($purchase_price->MaxPrice >= $sale_price->MaxPrice){
        $returnData['MaxPrice']=$purchase_price->MaxPrice;
      }else{
          $returnData['MaxPrice']=$sale_price->MaxPrice;
      }
      if($purchase_price->MinPrice >= $sale_price->MinPrice){
        $returnData['MinPrice']=$sale_price->MinPrice;
      }else{
         $returnData['MinPrice']=$purchase_price->MaxPrice;
      }

      return $returnData;


    }
    return false;*/
    $minMaxSql ='select max(NULLIF(sale_price,0)) as MaxPrice ,min(NULLIF(sale_price,0)) as MinPrice from product where cat_'.$levelname.'='.$levelid.' and is_active=1 and admin_approval=1';
    $productMainPriceMinMaxQuery = $this->db->query($minMaxSql);
    if($productMainPriceMinMaxQuery->num_rows() > 0){
      return $productMainPriceMinMaxQuery->row();
    }
    return false;
  }

  function get_brand_list($levelname,$levelid)
  {
      $result=array();
      $brand_sql='select brnd.id,brnd.name from product as prod right join brands as brnd on brnd.id=prod.brand where prod.cat_'.$levelname.'='.$levelid.' and prod.is_active=1 and prod.admin_approval=1 group by prod.brand order by brnd.name asc';
      $lavel4_brand_query = $this->db->query($brand_sql);
      if($lavel4_brand_query->num_rows() > 0){
          foreach ($lavel4_brand_query->result_array() as $row){
              $brand_id=$row['id'];
              if($brand_id!='' && $brand_id >0){
              $brand_product_count = $this->db->query("select count(*) as brand_product_count from product where cat_".$levelname."=".$levelid." and brand=$brand_id and admin_approval=1 and is_active=1")->row()->brand_product_count;
              $row['total_item']=$brand_product_count;
              array_push($result,$row);
          }
          }
      }
      return $result;
  }

  function get_variation_attribute_list($levelname,$levelid)
  {
    $result=array();
    $varient_sql='select * from variation_attribute where cat_'.$levelname.'='.$levelid.' and is_active=1 order by id asc';
    $variation_attribute_query = $this->db->query($varient_sql);
      if($variation_attribute_query->num_rows() > 0){
          foreach ($variation_attribute_query->result_array() as $row){
              $varient_id=$row['id'];
              $varient_values = $this->db->query("select * from variation_attribute_value where variation_id=$varient_id");
              if($varient_values->num_rows()>0){
                  $variation_tmp_val=array();
                  foreach ($varient_values->result_array() as $varient_val) {
                      $product_more_sql = 'select * from product_more where product_id in (select product_id from product where cat_' . $levelname . '=' . $levelid . ' and is_active=1 and admin_approval=1)';
                      $product_more_data = $this->db->query($product_more_sql)->result_array();
                      $varient_values_product_count = 0;
                      foreach ($product_more_data as $prdmore) {
                          if (strpos($prdmore['variation_value'], $varient_val['name'])) {
                              $varient_values_product_count++;
                          }
                      }
                      if($varient_values_product_count>0){
                          $varient_val['total_item']=$varient_values_product_count;
                          array_push($variation_tmp_val,$varient_val);
                      }
                  }
                  if(count($variation_tmp_val)>0){
                      $row['variation_attribute_value']=$variation_tmp_val;
                      array_push($result,$row);
                  }

              }


          }
      }

      return $result;


  }

  function _getFilterProductData(){
      if($this->input->post('page_number')!=''){
          $page_number = $this->input->post('page_number');
      }else{
          $page_number = 1;
      }
      $item_per_page  = 9;

    $discount='';
    $customer_review='';
    $cat_level_name=$this->input->post('cat_level_name');
    $cat_level_id=$this->input->post('cat_level_id');
  	$level4_id=$this->input->post('cat_level4');
  	$brand=$this->input->post('brand');
  	$varient_value=$this->input->post('varient_value');
  	$minprice=$this->input->post('minprice');
  	$maxprice=$this->input->post('maxprice');
  	$sortby=$this->input->post('sortby');
  	$is_combo = $this->input->post('is_combo');
    $discount = $this->input->post('discount');
    $customer_review = $this->input->post('customer_review');

  	$myFilterSql="select * from product where is_active=1 and admin_approval=1 and cat_".$cat_level_name."=".$cat_level_id;

  	if($level4_id){
  		$myFilterSql.=" and cat_level4=".$level4_id;
  	}

  	if ($brand) {
  		$brand = explode(',', $brand);
  		$brand = "'" . implode ( "', '", $brand) . "'";
  		$myFilterSql.=" and brand in ($brand)";
  	}
    $varient_value = explode(',', $varient_value);
  	if (count($varient_value) > 0) {  		
  		$varient_product_id_list = array();
  		$product_more_sql='select * from product_more where product_id in (select product_id from product where cat_'.$cat_level_name.'='.$cat_level_id.')';
      $product_more_data=$this->db->query($product_more_sql)->result();
      if($this->db->query($product_more_sql)->num_rows() > 0){      
	        foreach ($product_more_data as $prdmore) {
	        	 foreach (json_decode($prdmore->variation_value) as $var_value) {
	        	 	if (in_array($var_value, $varient_value)) 
	        	 	{	        	 		
                      array_push($varient_product_id_list,$prdmore->product_id);
	        	 	}	        	 	
	        	 }        

               
	        }
          }	        
	        if(count($varient_product_id_list)>0){
	        	$varient_product_id_list=implode(',', array_unique($varient_product_id_list));
	        	$myFilterSql.=" and product_id in ($varient_product_id_list)";
	        }
  	}

  	if($is_combo){
        $myFilterSql.=" and is_combo=1";
    }

  	if($minprice>0 && $maxprice>0){
  		$myFilterSql.=" and (purchase_price between $minprice and $maxprice or sale_price between $minprice and $maxprice)";
  	}

  if ($discount!='') {
      $myFilterSql.=" and ((purchase_price - sale_price) * 100 / purchase_price)>=$discount";
  }

      if ($customer_review!='' && $customer_review>0) {
          $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= product.product_id) >=$customer_review";
      }

  	if ($sortby=='newest_first') {
  		$myFilterSql.=" order by product_id desc";
  	}

  	if ($sortby=='price_low_to_heigh') {
  		$myFilterSql.=" order by purchase_price asc";
  	}

  	if ($sortby=='price_high_to_low') {
  		$myFilterSql.=" order by purchase_price desc";
  	}





      $get_total_rows =$this->db->query($myFilterSql)->num_rows();
      $total_pages = ceil($get_total_rows/$item_per_page);
      $page_position = (($page_number-1) * $item_per_page);
      $myFilterSql.=" LIMIT ".$page_position.",".$item_per_page;

  	

  	$myFilterSqlResult = $this->db->query($myFilterSql);

    $product_pagination='';
  	
  	if($myFilterSqlResult->num_rows() > 0){
      $product_html =' <ul>';
  		foreach ($myFilterSqlResult->result() as $product) {
  			$product_rating_query = $this->db->query("select count(*) as customer_count,sum(rate) as total_rating from product_rating where product_id=$product->product_id and is_active=1")->row();
  			$rating=0;
  			if($product_rating_query->customer_count>0 && $product_rating_query->total_rating>0){
  				$rating = $product_rating_query->total_rating / $product_rating_query->customer_count;
  			}  	

        $wishlist=0;
        $customer_id=0;
        if($this->nsession->userdata('member_session_id')){
          $customer_id=$this->nsession->userdata('member_session_id');
        }
                if($customer_id > 0){
                    $wishlist=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$product->product_id))->num_rows();
                    
                }

         $wlist = ($wishlist==0)?'-o':'';
  			$productImage = $this->db->query("select * from product_images where product_id=$product->product_id and type='main'")->row();
                                if($productImage->path!=''){
                                    $pic=file_upload_base_url().'product/'.$productImage->path;
                                }else{
                                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                                }
                                 $discount_price=(((float)$product->purchase_price-(float)$product->sale_price)/(float)$product->purchase_price)*100; 
                                  $rating_one = ($rating >=1)?'checked':'';
                                  $rating_two = ($rating >=2)?'checked':'';
                                  $rating_three = ($rating >=3)?'checked':'';
                                  $rating_four = ($rating >=4)?'checked':'';
                                  $rating_five = ($rating >=5)?'checked':'';                                 
  			                       $product_html.=' <li>
                                        <div class="pro-box-wrap">
                                        <div class="favourit-icon active"><i class="fa fa-heart'.$wlist.' dowishlist" data-id="'.$product->product_id.'"></i></div>
                                            <a href="'.base_url('product/details/'.$product->product_id.'/'.urlencode($product->title)).'">
                                            <figure class="pro-img">
                                                <img src="'.$pic.'" alt="" class="img-responsive"/>
                                            </figure><div class="pro-content">
                                                <h3 class="pro-title">'.$product->title.'</h3> 
                                                <ul class="pro-price">
                                                    <li><span class="old-price"><i class="fa fa-inr"></i>'.$product->purchase_price.'</span></li>
                                                    <li><span class="real-price"><i class="fa fa-inr"></i>'.$product->sale_price.'</span></li>
                                                    <li><span class="offer-price">'.number_format(abs($discount_price),2).'% off</span></li>
                                                </ul>                                               
                                                <div class="rv-rating">                                               
                                                    <i class="fa fa-star '.$rating_one.'"></i>
                                                    <i class="fa fa-star '.$rating_two.'"></i>
                                                    <i class="fa fa-star '.$rating_three.'"></i>
                                                    <i class="fa fa-star '.$rating_four.'"></i>
                                                    <i class="fa fa-star '.$rating_five.'" ></i>
                                                  </div>
                                                <p class="pro-size" style="display: none;">Size 4 UK, 5 UK, 6 UK, 7 UK, 8 UK</p>
                                            </div>
                                            <div class="spacer"></div>
                                            </a>
                                        </div>
                                    </li>';
  		}
      $product_html.='</ul>';
        $product_pagination=$this->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
  	}else{
      $product_html='No Product Found!';
    }

      header('Content-Type: application/json');
      echo json_encode( array('product_list'=>$product_html,'product_pagination'=>$product_pagination));
  	
  }


    function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
    {
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number

            $pagination .= '<div class="col-sm-12"> <ul class="pagination pull-right">';
            $right_links    = $current_page + 3;
            $previous       = $current_page - 1; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link
            if($current_page > 1){
                $previous_link = ($previous==0)? 1: $previous;
                $pagination .= '<li class="paginate_button previous"><a href="#" data-page="1" title="First">First</a></li>'; //first link

                $pagination .= '<li class="paginate_button previous"><a href="#" data-page="'.$previous_link.'" title="Previous">Previous</a></li>'; //previous link

                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }

                }
                $first_link = false; //set first link to false
            }

            if($first_link){ //if current active page is first link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }elseif($current_page == $total_pages){ //if it's the last active link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }else{ //regular current link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }

            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'" >'.$i.'</a></li>';
                }
            }

            if($current_page < $total_pages){
                $next_link = ($next > $total_pages) ? $total_pages : $next;
                $pagination .= '<li class="paginate_button next"><a href="#" data-page="'.$next_link.'" title="Next">Next</a></li>'; //next link

                $pagination .= '<li class="paginate_button next"><a href="#" data-page="'.$total_pages.'" title="Last">Last</a></li>'; //last link

            }
            $pagination .= '</ul></div>';
        }
        return $pagination; //return pagination links
    }


    public function get_seller_profile($seller_id){
        $seller_details = $this->db->query("select mbd.*,cun.name as country,stat.name as state,city.name as city from merchants_business_details as mbd left join countries as cun on cun.id=mbd.business_country left join states as stat on stat.id=business_state left join cities as city on city.id=business_city where merchants_id=$seller_id")->row_array();
        $seller_details['feedback']=$this->db->query("select prd_r.* from product_rating as prd_r  join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1")->result_array();

        $seller_details['total_rating'] = $this->db->query("select sum(prd_r.rate) as total_rating from product_rating as prd_r join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1 and prd_r.is_active=1")->row()->total_rating;

        $seller_details['total_rating_user']=$this->db->query("select prd_r.* from product_rating as prd_r  join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1 and prd_r.is_active=1")->num_rows();
        return $seller_details;
    }

    public function get_seller_feedback(){
        if($this->input->post('page_number')!=''){
            $page_number = $this->input->post('page_number');
        }else{
            $page_number = 1;
        }
        $item_per_page  = 5;
        $seller_id= $this->input->post('seller_id');
        $feedback_query = "select prd_r.* from product_rating as prd_r  join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1 order by prd_r.rating_id desc";
        $get_total_rows =$this->db->query($feedback_query)->num_rows();
        $total_pages = ceil($get_total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);
        $feedback_query.=" LIMIT ".$page_position.",".$item_per_page;
        $feedback_result = $this->db->query($feedback_query);
        $html_content = '';
        if($feedback_result->num_rows() > 0){
            foreach ($feedback_result->result_array() as $feedback){
                $tot_rating=$feedback['rate'];

                $html_content.='<div class="col-md-3">
                    <div class="rv-rating">';

                        for($i=0;$i<$tot_rating;$i++){
                            $html_content.=' <i class="fa fa-star checked"></i>';
                        }

                        for($i=0;$i<5-$tot_rating;$i++){
                            $html_content.=' <i class="fa fa-star"></i>';
                        }

                   $html_content.='</div>
                </div>
                <div class="col-md-9"><em>"'.$feedback['comments'].'"</em>
                    <p>By '.$feedback['full_name'].' on '. date("d F Y h:i:s a", strtotime($feedback['created_date'])).'.</p>
                </div>';

            }
            $html_content.=$this->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
        }

        return $html_content;
    }

    public function get_seller_product(){
        if($this->input->post('page_number')!=''){
            $page_number = $this->input->post('page_number');
        }else{
            $page_number = 1;
        }
        $item_per_page  = 4;
        $seller_id= $this->input->post('seller_id');
        $search_tearm = $this->input->post('search_tearm');
        $product_sql="select * from product where is_active=1 and admin_approval=1 and created_by=$seller_id";
        if (trim($search_tearm)!=''){
            $product_sql.=" and title like   '%".$search_tearm."%'";
        }

        $get_total_rows =$this->db->query($product_sql)->num_rows();
        $total_pages = ceil($get_total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);
        $product_sql.=" LIMIT ".$page_position.",".$item_per_page;
        $product_result = $this->db->query($product_sql);
        $html_content = '';
        $product_pagination='';
        if($product_result->num_rows() > 0) {
            foreach ($product_result->result() as $product) {
                $product_rating_query = $this->db->query("select count(*) as customer_count,sum(rate) as total_rating from product_rating where product_id=$product->product_id and is_active=1")->row();
                $rating=0;
                if($product_rating_query->customer_count>0 && $product_rating_query->total_rating>0){
                    $rating = round($product_rating_query->total_rating / $product_rating_query->customer_count);
                }

                $wishlist=0;
                $customer_id=0;
                if($this->nsession->userdata('member_session_id')){
                    $customer_id=$this->nsession->userdata('member_session_id');
                }
                if($customer_id > 0){
                    $wishlist=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$product->product_id))->num_rows();

                }

                $wlist = ($wishlist==0)?'-o':'';
                $productImage = $this->db->query("select * from product_images where product_id=$product->product_id")->row();
                if($productImage->path!=''){
                    $pic=file_upload_base_url().'product/'.$productImage->path;
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $discount_price=(((float)$product->purchase_price-(float)$product->sale_price)/(float)$product->purchase_price)*100;
                $rating_one = ($rating >=1)?'checked':'';
                $rating_two = ($rating >=2)?'checked':'';
                $rating_three = ($rating >=3)?'checked':'';
                $rating_four = ($rating >=4)?'checked':'';
                $rating_five = ($rating >=5)?'checked':'';
                $html_content.=' <div class="col-md-3">
                                        <div class="product-bx"> <a href="'.base_url('product/details/'.$product->product_id.'/'.urlencode($product->title)).'">
                                                <figure> <img src="'.$pic.'" alt="" class="img-responsive"> </figure>
                                                <div class="rv-rating">
                                                 <i class="fa fa-star '.$rating_one.'"></i>
                                                    <i class="fa fa-star '.$rating_two.'"></i>
                                                    <i class="fa fa-star '.$rating_three.'"></i>
                                                    <i class="fa fa-star '.$rating_four.'"></i>
                                                    <i class="fa fa-star '.$rating_five.'" ></i>
                                                </div>
                                                <p class="pro-txt">'.$product->title.'</p>                                                
                                                <ul class="pro-price">
                                                    <li><span class="old-price"><i class="fa fa-inr"></i>'.$product->purchase_price.'</span></li>
                                                    <li><span class="real-price"><i class="fa fa-inr"></i>'.$product->sale_price.'</span></li>
                                                    <li><span class="offer-price">'.number_format(abs($discount_price),2).'% off</span></li>
                                                </ul>
                                            </a>
                                            <div class="spacer"></div>
                                        </div>
                                    </div>';

            }
            $html_content.=$this->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
        }else{
            $html_content.='<div class="col-md-9"><p>No product found!</p></div>';
        }

        echo $html_content;

    }


    public function getBreadcrumb($para1,$para2,$para3){
          $breadcrumb='';
          if($para1=='level3'){
            $result=$this->db->query("select catlevel3.id  as level3_id,catlevel3.name  as level3_name,catlevel2.id  as level2_id,catlevel2.name  as level2_name,catlevel1.id  as level1_id,catlevel1.name  as level1_name from category_level_3 as catlevel3 left join category_level_2 as catlevel2 on catlevel2.id=catlevel3.level2 left join category_level_1 as catlevel1 on catlevel1.id=catlevel2.level1 where catlevel3.id=$para2")->row_array();
             $breadcrumb.='<li><a href="'.base_url('product/index/level1/'.$result['level1_id'].'/'.$result['level1_name']).'" >'.urldecode($result['level1_name']).'</a></li>';
             $breadcrumb.='<li><a href="'.base_url('product/index/level2/'.$result['level2_id'].'/'.$result['level2_name']).'" >'.urldecode($result['level2_name']).'</a></li>';
             $breadcrumb.='<li><a href="'.base_url('product/index/level3/'.$result['level3_id'].'/'.$result['level3_name']).'" class="active">'.urldecode($result['level3_name']).'</a></li>';

          }elseif ($para1=='level2') {
            $result=$this->db->query("select catlevel2.id  as level2_id,catlevel2.name  as level2_name,catlevel1.id  as level1_id,catlevel1.name  as level1_name from  category_level_2 as catlevel2 left join category_level_1 as catlevel1 on catlevel1.id=catlevel2.level1 where catlevel2.id=$para2")->row_array();
            $breadcrumb.='<li><a href="'.base_url('product/index/level1/'.$result['level1_id'].'/'.$result['level1_name']).'" >'.urldecode($result['level1_name']).'</a></li>';
             $breadcrumb.='<li><a href="'.base_url('product/index/level2/'.$result['level2_id'].'/'.$result['level2_name']).'" class="active">'.urldecode($result['level2_name']).'</a></li>';
          }else{
             $result=$this->db->query("select catlevel1.id  as level1_id,catlevel1.name  as level1_name from  category_level_1 as catlevel1  where catlevel1.id=$para2")->row_array();
             $breadcrumb.='<li><a href="'.base_url('product/index/level1/'.$result['level1_id'].'/'.$result['level1_name']).'" class="active">'.urldecode($result['level1_name']).'</a></li>';
            
          }

          return $breadcrumb;

    }


    function getProductAllReview($product_id){
       $all_review=array();
       for ($i=1;$i<=5;$i++) {
               $all_review[$i] = $this->db->query("select count(*) as totaluser  from product_rating where product_id=$product_id and rate=$i and is_active=1")->row()->totaluser;       
       }

       return $all_review;
    }





	
}