<?php
class ModelSearch extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
   	

  function get_search_categories($search_keyword)
  {
  	$lavel4_categories_query = $this->db->query("select * from category_level_4 where is_active=1 and level3 in (select prd.cat_level3 from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where prd.is_active=1 and prd.admin_approval=1 and cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like '%".$search_keyword."%' ) order by name asc");
  	if($lavel4_categories_query->num_rows() > 0){
  		return $lavel4_categories_query->result();
  	}
  	return false;
  }

  function getMinMaxPrice($search_keyword){
    $productMainPriceMinMaxQuery = $this->db->query("select max(NULLIF(prd.sale_price,0)) as MaxPrice ,min(NULLIF(prd.sale_price,0)) as MinPrice from product as prd where is_active=1 and ( product_id in (select product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where prd.is_active=1 and prd.admin_approval=1 and cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%')  or prd.title like   '%".$search_keyword."%' )");
    if($productMainPriceMinMaxQuery->num_rows() > 0){
    	return $productMainPriceMinMaxQuery->row();
    }
    return false;
  }

  function get_brand_list($search_keyword)
  {
      $result=array();
  	$lavel4_brand_query = $this->db->query("select brnd.id,brnd.name from product as prod left join brands as brnd on brnd.id=prod.brand where prod.is_active=1 and prod.product_id in (select product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where prd.is_active=1 and prd.admin_approval=1 and cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%')  or (title like   '%".$search_keyword."%' ) group by prod.brand order by brnd.name asc");
  	if($lavel4_brand_query->num_rows() > 0){
        foreach ($lavel4_brand_query->result_array() as $row){
            $brand_id=$row['id'];
            if($brand_id!='' && $brand_id>0){
            $brand_product_count = $this->db->query("select count(product_id) as brand_product_count from product  where is_active=1 and brand=$brand_id")->row()->brand_product_count;
            $row['total_item']=$brand_product_count;
            array_push($result,$row);
          }
        }
  	}
      return $result;
  }

  function get_variation_attribute_list($search_keyword)
  {
      $result = array();
  	$variation_attribute_query = $this->db->query("select * from variation_attribute where is_active=1 and  cat_level3 in (select prd.cat_level3 from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where prd.is_active=1 and prd.admin_approval=1 and cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like   '%".$search_keyword."%' )  order by id asc");

      if($variation_attribute_query->num_rows() > 0){
          foreach ($variation_attribute_query->result_array() as $row){
              $varient_id=$row['id'];
              $varient_values = $this->db->query("select * from variation_attribute_value where variation_id=$varient_id");
              if($varient_values->num_rows()>0){
                  $variation_tmp_val=array();
                  foreach ($varient_values->result_array() as $varient_val) {
                      $cat_level3=$row['cat_level3'];
                      $product_more_sql = "select * from product_more where product_id in (select prd.product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where prd.is_active=1 and prd.admin_approval=1 and cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like   '%".$search_keyword."%')";
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
  	$search_keyword =$this->input->post('search_keyword');
  	$level4_id=$this->input->post('cat_level4');
  	$brand=$this->input->post('brand');
  	$varient_value=$this->input->post('varient_value');
  	$minprice=$this->input->post('minprice');
  	$maxprice=$this->input->post('maxprice');
  	$sortby=$this->input->post('sortby');
    $is_combo = $this->input->post('is_combo');
    $discount = $this->input->post('discount');
    $customer_review = $this->input->post('customer_review');

  	$myFilterSql="select prd.* from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and (cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like   '%".$search_keyword."%')";

  	if($level4_id){
  		$myFilterSql.=" and prd.cat_level4=".$level4_id;
  	}

      if($is_combo){
          $myFilterSql.=" and prd.is_combo=1";
      }


      if ($brand) {
  		$brand = explode(',', $brand);
  		$brand = "'" . implode ( "', '", $brand) . "'";
  		$myFilterSql.=" and prd.brand in ($brand)";
  	}
    $varient_value = explode(',', $varient_value);
  	if (count($varient_value) > 0) {  		
  		$varient_product_id_list = array();
  		$product_more_data=$this->db->query(" select * from product_more where product_id in (select prd.product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and (cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like '%".$search_keyword."%'))")->result();
	        foreach ($product_more_data as $prdmore) {
	        	 foreach (json_decode($prdmore->variation_value) as $var_value) {
	        	 	if (in_array($var_value, $varient_value)) 
	        	 	{	        	 		
                      array_push($varient_product_id_list,$prdmore->product_id);
	        	 	}	        	 	
	        	 }             

               
	        }	        
	        if(count($varient_product_id_list)>0){
	        	$varient_product_id_list=implode(',', array_unique($varient_product_id_list));
	        	$myFilterSql.=" and product_id in ($varient_product_id_list)";
	        }
  	}

  	/*if($minprice>0 && $maxprice>0){
  		$myFilterSql.=" and prd.purchase_price between $minprice and $maxprice";
  	}*/

  	if($minprice>0 && $maxprice>0){
      $myFilterSql.=" and (prd.purchase_price between $minprice and $maxprice or prd.sale_price between $minprice and $maxprice)";
    }

      if ($discount!='') {
          $myFilterSql.=" and ((prd.purchase_price - prd.sale_price) * 100 / prd.purchase_price)>=$discount";
      }

      if ($customer_review!='' && $customer_review>0) {
          $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= prd.product_id) >=$customer_review";
      }

    if ($sortby=='newest_first') {
      $myFilterSql.=" order by prd.product_id desc";
    }

    if ($sortby=='price_low_to_heigh') {
      $myFilterSql.=" order by prd.purchase_price asc";
    }

    if ($sortby=='price_high_to_low') {
      $myFilterSql.=" order by prd.purchase_price desc";
    }

      $get_total_rows =$this->db->query($myFilterSql)->num_rows();
      $total_pages = ceil($get_total_rows/$item_per_page);
      $page_position = (($page_number-1) * $item_per_page);
      $myFilterSql.=" LIMIT ".$page_position.",".$item_per_page;

  	

  	$myFilterSqlResult = $this->db->query($myFilterSql);

  	
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
         	
  			$productImage = $this->db->query("select * from product_images where product_id=$product->product_id  and type='main'")->row();
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


      /*header('Content-Type: application/json');
      echo json_encode( array('product_list'=>$product_html,'product_pagination'=>$product_pagination));*/
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



	
}