$(function(){
    //alert(123);
    if($('#top_offers').length > 0){
      top_offers();
    }
    function top_offers(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/top_offers',
        success:function(msg){
          if(msg!=''){
            $('#top_offers').html('');
            $('#top_offers').html(msg);
            $(".product-slider").owlCarousel({
                autoplay: false,
                items : 1, 
                navText: false,
                dots: false,       
                nav : true,
                mouseDrag:true,
                lazyLoad : false,
                responsive:{
                  0:{
                      items:1
                  },
                  600:{
                      items:2
                  },
                  1024:{
                      items:3
                  },
                  1199:{
                      items:5
                  }
                }
              }); 
          }
        }
      });
    }

    if($('#advertise_section').length > 0){
      advertise_section();
    }
    function advertise_section(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/advertise_section',
        success:function(msg){
          if(msg!=''){
            $('#advertise_section').html('');
            $('#advertise_section').html(msg);
          }
        }
      });
    }

    if($('#trending_product').length > 0){
      trending_product();
    }
    function trending_product(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/trending_product',
        success:function(msg){
          if(msg!=''){
            $('#trending_product').html('');
            $('#trending_product').html(msg);
            $(".product-slider").owlCarousel({
                autoplay: false,
                items : 1, 
                navText: false,
                dots: false,       
                nav : true,
                mouseDrag:true,
                lazyLoad : false,
                responsive:{
                  0:{
                      items:1
                  },
                  600:{
                      items:2
                  },
                  1024:{
                      items:3
                  },
                  1199:{
                      items:5
                  }
                }
              }); 
          }
        }
      });
    }

    if($('#featured_brand').length > 0){
      featured_brand();
    }
    function featured_brand(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/featured_brand',
        success:function(msg){
          if(msg!=''){
            $('#featured_brand').html('');
            $('#featured_brand').html(msg);
          }
        }
      });
    }

    if($('#best_seller').length > 0){
      best_seller();
    }
    function best_seller(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/best_seller',
        success:function(msg){
          if(msg!=''){
            $('#best_seller').html('');
            $('#best_seller').html(msg);
            $(".best-product-slider").owlCarousel({
              autoplay: false,
              items : 1, 
              navText: false,
              dots: false,       
              nav : true,
              mouseDrag:true,
              lazyLoad : false,
              responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:3
                }
              }
            }); 
          }
        }
      });
    }
    
});
/**************************Home Dashboard End***************************/