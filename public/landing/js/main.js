// JavaScript Document
var $ = jQuery.noConflict();


 jQuery(window).bind('scroll', function() {
  var navHeight = 100 - 70;
	   if (jQuery(window).scrollTop() > navHeight) {
		  jQuery('#myHeader').addClass('sticky');
	   }
	   else {
		  jQuery('#myHeader').removeClass('sticky');
	   }
 });

$(document).ready(function(){
$( "#clickme" ).click(function() {
  $( "#feature_more" ).toggle();
    // Animation complete.
  });
});

  $(document).ready(function() {
   $("#latest-update-carousel").owlCarousel({
        autoplay: true,
		loop: true,
        items : 4, 
		navText: false,
		dots: false,       
		nav : true,
		mouseDrag:false,
		lazyLoad : false,
		responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        900:{
            items:4
        }
    }
      });	
	  
$("#book-winskart-carousel").owlCarousel({
        autoplay: true,
		loop: true,
        items : 8, 
		navText: false,
		dots: false,       
		nav : true,
		mouseDrag:false,
		lazyLoad : false,
		responsive:{
        0:{
            items:1
        },
        321: {
            items:2
        },
        600:{
            items:4
        },
        1000:{
            items:8
        }
    }
      });	

   $("#best-offers-carousel").owlCarousel({
        autoplay: true,
		loop: true,
        items : 5, 
		navText: false,
		dots: false,       
		nav : true,
		mouseDrag:false,
		lazyLoad : false,
		responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        900:{
            items:4
        },
        1025:{
            items:5
        }
    }
      });	

});	

//*######################## more less btn toggle #########################*//
$(function(){
    
  $('.more_description').hide();
  $('.more_details').click(function(){
    if ($(this).find('span').html() == '+ More Details') {
    $(this).find('span').html('- Less Details');
    $('.more_description').slideDown('slow');
        $('.more_description').slideDown(function() {$(this).css('display', 'block');} );
     }else{
    $('.more_description').slideUp('slow');
    $(this).find('span').html('+ More Details');
    }
    
  })
})
//*######################## more less btn toggle #########################*//
