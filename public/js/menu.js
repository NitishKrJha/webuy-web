(function ($) {
"use strict";
/*---------------------------------------------------
    Main Menu
----------------------------------------------------- */
$('#menu .nav > li > .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

var $screensize = $(window).width();
$('#menu .nav > li, #header .links > ul > li').on("mouseover", function() {
																		
			if ($screensize > 991) {
			$(this).find('> .dropdown-menu').stop(true, true).slideDown('fast');
			}			
			$(this).bind('mouseleave', function() {

			if ($screensize > 991) {
				$(this).find('> .dropdown-menu').stop(true, true).css('display', 'none');
			}
		});});
$('#menu .nav > li div > ul > li').on("mouseover", function() {
			if ($screensize > 991) {
			$(this).find('> div').css('display', 'block');
			}			
			$(this).bind('mouseleave', function() {
			if ($screensize > 991) {
				$(this).find('> div').css('display', 'none');
			}
		});});
$('#menu .nav > li > .dropdown-menu').closest("li").addClass('sub');

// Clearfix for sub Menu column
$( document ).ready(function() {
  $screensize = $(window).width();
    if ($screensize > 1199) {
        $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
  }
});
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
});

// Clearfix for Brand Menu column
$( document ).ready(function() {
$screensize = $(window).width();
    if ($screensize > 1199) {
        $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
    if ($screensize < 991) {
		$("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
		$("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(2n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});

/*---------------------------------------------------
    Mobile Main Menu
----------------------------------------------------- */
$('#menu .navbar-header > span').on("click", function() {
	  $(this).toggleClass("active");  
	  $("#menu .navbar-collapse").slideToggle();
	  return false;
	});

//mobile sub menu plus/mines button
$('#menu .nav > li > div > .column > div, .submenu, #menu .nav > li .dropdown-menu').before('<span class="submore"></span>');

//mobile sub menu click function
$('span.submore').on("click", function() {
	$(this).next().slideToggle('fast');
	$(this).toggleClass('plus');
	return false;
});
//mobile top link click
$('.drop-icon').on("click", function() {
	  $('#header .htop').find('.left-top').slideToggle('fast');
	  return false;
	});


jQuery('#accordion .panel-title, #accordion2 .panel-title').click(function(e) { 
      jQuery(this).toggleClass('active');
      jQuery(this).parent(".panel-heading").parent(".panel-default").siblings(".panel-default").find('.panel-title').removeClass('active');
  });
                        
                        


	
    
})(jQuery);
