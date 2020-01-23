(function($) {
    // "use strict";
    // /*---------------------------------------------------
    //     Main Menu
    // ----------------------------------------------------- */
    // $('#menu .nav > li > .dropdown-menu').each(function() {
    //     var menu = $('#menu').offset();
    //     var dropdown = $(this).parent().offset();

    //     var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

    //     if (i > 0) {
    //         $(this).css('margin-left', '-' + (i + 5) + 'px');
    //     }
    // });



    // var $screensize = $(window).width();
    // $('#menu .nav > li, #header .links > ul > li').on("mouseover", function() {

    //     if ($screensize > 991) {
    //         $(this).find('> .dropdown-menu').stop(true, true).slideDown('fast');
    //     }
    //     $(this).bind('mouseleave', function() {

    //         if ($screensize > 991) {
    //             $(this).find('> .dropdown-menu').stop(true, true).css('display', 'none');
    //         }
    //     });
    // });
    // $('#menu .nav > li div > ul > li').on("mouseover", function() {
    //     if ($screensize > 991) {
    //         $(this).find('> div').css('display', 'block');
    //     }
    //     $(this).bind('mouseleave', function() {
    //         if ($screensize > 991) {
    //             $(this).find('> div').css('display', 'none');
    //         }
    //     });
    // });
    // $('#menu .nav > li > .dropdown-menu').closest("li").addClass('sub');



    // // Clearfix for sub Menu column
    // $(document).ready(function() {
    //     $screensize = $(window).width();
    //     if ($screensize > 1199) {
    //         $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    //     }
    //     if ($screensize < 1199) {
    //         $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    //     }
    // });
    // $(window).resize(function() {
    //     $screensize = $(window).width();
    //     if ($screensize > 1199) {
    //         $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    //     }
    //     if ($screensize < 1199) {
    //         $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    //     }
    // });

    // // Clearfix for Brand Menu column
    // $(document).ready(function() {
    //     $screensize = $(window).width();
    //     if ($screensize > 1199) {
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    //     }
    //     if ($screensize < 1199) {
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    //     }
    //     if ($screensize < 991) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    //         $('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    //     }
    //     if ($screensize < 767) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(2n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    //         $('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    //     }
    // });
    // $(window).resize(function() {
    //     $screensize = $(window).width();
    //     if ($screensize > 1199) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    //     }
    //     if ($screensize < 1199) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    //     }
    //     if ($screensize < 991) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    //         $('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    //     }
    //     if ($screensize < 767) {
    //         $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
    //         $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    //         $('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    //     }
    // });


    // /*---------------------------------------------------
    //     Mobile Main Menu
    // ----------------------------------------------------- */
    // $('#menu .navbar-header > span').on("click", function() {
    //     $(this).toggleClass("active");
    //     $("#menu .navbar-collapse").slideToggle();
    //     return false;
    // });

    // //mobile sub menu plus/mines button
    // $('#menu .nav > li > div > .column > div, .submenu, #menu .nav > li .dropdown-menu').before('<span class="submore"></span>');

    // //mobile sub menu click function
    // $('span.submore').on("click", function() {
    //     $(this).next().slideToggle('fast');
    //     $(this).toggleClass('plus');
    //     return false;
    // });
    // //mobile top link click
    // $('.drop-icon').on("click", function() {
    //     $('#header .htop').find('.left-top').slideToggle('fast');
    //     return false;
    // });


    jQuery('#accordion .panel-title, #accordion2 .panel-title').click(function(e) {
        jQuery(this).toggleClass('active');
        jQuery(this).parent(".panel-heading").parent(".panel-default").siblings(".panel-default").find('.panel-title').removeClass('active');
    });

    /* ------------------------------------------------------------------------ */
    /* ACCORDIAN ICONS
    /* ------------------------------------------------------------------------ */
    jQuery('#accordion .panel-title a').click(function(e) {
        jQuery(this).parent().find('span i').toggleClass('fa-minus');
        jQuery(this).parent(".panel-title").parent(".panel-heading").parent(".panel-default").siblings(".panel-default").find('.panel-title a span i').removeClass('fa-minus');
    });



    // ion-range-slider
    $(document).ready(function() {
        $("#range_03").ionRangeSlider({
            type: "double",
            grid: true,
            min: 0,
            max: 1000,
            from: 200,
            to: 800,
            prefix: "$"
        });
    });

    // main slider
    $('#slider1').bxSlider({
        auto: true,
        controls: false,
        pager: true
    });

    //navigation 
    //function openNav() {
    //    document.getElementById("mySidenav").style.width = "70%";
    //    // document.getElementById("stak-navbar").style.width = "50%";
    //    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    //}
    //
    //function closeNav() {
    //    document.getElementById("mySidenav").style.width = "0";
    //    document.body.style.backgroundColor = "rgba(0,0,0,0)";
    //}

    //product slider
    $(document).ready(function() {
        $(".product-slider").owlCarousel({
            autoplay: false,
            items: 1,
            navText: false,
            dots: false,
            nav: true,
            mouseDrag: true,
            lazyLoad: false,
            responsive: {
                0: {
                    items: 1
                },
                500: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1024: {
                    items: 3
                },
                1199: {
                    items: 5
                }
            }
        });
    });


    //best product slider
    $(document).ready(function() {
        $(".best-product-slider").owlCarousel({
            autoplay: false,
            items: 1,
            navText: false,
            dots: false,
            nav: true,
            mouseDrag: true,
            lazyLoad: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    });

    //best product slider
    $(document).ready(function() {
        $("#category-nav").owlCarousel({
            autoplay: false,
            items: 1,
            navText: false,
            dots: false,
            nav: false,
            autoWidth: true,
            mouseDrag: true,
            lazyLoad: false,
            responsive: {
                0: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    });


    $("#zm-selectors").owlCarousel({
        loop: true,
        nav: true,
        responsiveClass: true,
        items: 4,

        responsive: {
            0: {
                items: 4,
                nav: true
            },
            600: {
                items: 4,
                nav: false
            },
            1000: {
                items: 6,
                nav: true,
                loop: false,
                margin: 5
            }
        }
    });


    // $("#menu .visible-xs").on('click', function(e) {
    //     e.stopPropagation();
    // });
    // //li width    
    // $(document).ready(function() {
    //     var n = $(".add-list li").length;
    //     var w = (100 / n);
    //     $(".add-list li").width(w + '%');
    // });

    //accordion
    $(function() {
        $(".expand").on("click", function() {
            $(this).next().slideToggle(200);
            $expand = $(this).find(">:first-child");

            if ($expand.text() == "+") {
                $expand.text("-");
            } else {
                $expand.text("+");
            }
        });
    });


    //li width    
    $(document).ready(function() {
        $('.btn-number').click(function(e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if (type == 'minus') {
                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }
                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }
                }
            } else {
                input.val(0);
            }
        });
    });


    $(document).ready(function() {
        if ($('#horizontalTab').length) {
            $('#horizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion           
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                closed: 'accordion', // Start closed if in accordion view
                activate: function(event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });
        }


    });
    // accoudion tab
    //$(document).ready(function () {
    //$('#horizontalTab').easyResponsiveTabs({
    //type: 'default', //Types: default, vertical, accordion           
    //width: 'auto', //auto or any width like 600px
    //fit: true,   // 100% fit in a container
    //closed: 'accordion', // Start closed if in accordion view
    //activate: function(event) { // Callback function if tab is switched
    //var $tab = $(this);
    //var $info = $('#tabInfo');
    //var $name = $('span', $info);
    //$name.text($tab.text());
    //$info.show();
    //}
    //});
    //$('#verticalTab').easyResponsiveTabs({
    //type: 'vertical',
    //width: 'auto',
    //fit: true
    //});
    //});



    $(document).ready(function() {
        $('[data-toggle="popover"]').popover();
    });


    $(document).ready(function() {
        $("#category-img-slide").owlCarousel({
            autoplay: false,
            items: 1,
            navText: false,
            dots: false,
            nav: true,
            mouseDrag: true,
            lazyLoad: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1024: {
                    items: 1
                },
                1199: {
                    items: 1
                }
            }
        });
    });



    function messagealert(title, text, type) {
    PNotify.removeAll();
    new PNotify({
        title: title,
        text: text,
        type: type,
        styling: 'bootstrap3'
    });
    }

})(jQuery);