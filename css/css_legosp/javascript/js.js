/*var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'http://kenwheeler.github.io/slick/slick/slick.js';
        document.head.appendChild(script);*/
$(document).ready(function() {

    if (device.android()) 
    {
        count_spec = $('.product_list>div').length;
        if (count_spec > 3)
            count_spec = 4;
	
	
        $('.product_list').slick({
            infinite: false,
            dots: true,
            slidesToShow: count_spec,
            slidesToScroll: count_spec,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    $(".menu-btn.adv_menu").on("click", function() {
        if ($('#wrapper').hasClass('open'))
        {
            $('#wrapper').removeClass();
        }
        else
        {
            $('#wrapper').addClass('open');
        }
    });

    $(".mob_category_list").on("click", function() {
        if ($('#wrapper').hasClass('category_open'))
        {
            $('#wrapper').removeClass();
        }
        else
        {
            $('#wrapper').addClass('category_open');
        }
    });
    $('#custinfo_form').on("focus", "input", function() {
        $('#custinfo_form li').removeClass('focused');
        $(this).closest('li').addClass('focused');
    });
    $('#cart-panel').on("click", "#module_cart", function() {
        location = "./cart/";
    });



});