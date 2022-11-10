
jQuery(document).ready(function($) {


    var tabletWidth = 980;
    var mobileWidth = 680;

    //placeholders for IE
    $('input').placeholder();

    /* Check on blur if required fields is empty. Required fields must have class
     * "required" and a div wrapper with class "input-safe-wrapper". In this div
     * include only required field. */
    $(document).on('blur','input, textarea',function( event ){
        if( $(this).hasClass('required') ){
            if($(this).val() == ''){
                $(this).addClass('validate-error');
                $(this).parent().find('.error-message').remove();
                $(this).parent().append('<span class="error-message">'+ errorMessages.requiredError +'</span>');

            }else{
                $(this).removeClass('validate-error');
                $(this).parent().find('.error-message').remove();
            }
            if( $(this)[0].type == 'email' ){
                if( ( ! validateEmail($(this).val()) ) ){
                    if( ! $(this).hasClass('required-error') ){
                        $(this).addClass('validate-error');
                        if( ! $(this).parent().find('.error-message').length ){
                            $(this).parent().append('<span class="error-message">'+ errorMessages.emailError +'</span>');
                        }
                    }
                }
            }
            //console.log($(this).val());
        }
    });


    $(document).on('click','.btn',function( event ){

        var validForm = true;

        /* Check for required fields. Required fields must have class "required"
         * and a div wrapper with class "input-safe-wrapper". In this div include
         * only required field. */
        $(this).form('form').find('.required').each(function(){
            if($(this).val() == ''){
                validForm = false;
                $(this).addClass('validate-error');
                if( ! $(this).parent().find('.error-message').length ){
                    $(this).parent().append('<span class="error-message">'+ errorMessages.requiredError +'</span>');
                }
            }
        });





        // If form isn't valid, form don't submit and scroll to error area.
        if( ! validForm ){
            var elToScroll = $(this).find('.validate-error').offset().top - Math.round($('#header').outerHeight()) - 28;
            $('html, body').animate({
                scrollTop: elToScroll
            }, 1000);

            return false;
            event.preventDefault();
        }
    });

    $('.form-action-upper .edit-fields').click(function(){
        var $this = $(this);
        $this.parent().next('form').find('input.disabled').removeClass('disabled').removeAttr('disabled').addClass('editing');
        return false;
    });

    $('.go-to-href').click(function(){
        var target = $(this).attr('href');
        if( $(target).length ){
            var elToScroll = $(target).offset().top - $('#header').outerHeight();
            $('html, body').animate({
                scrollTop: elToScroll
            }, 1000);
        }
        return false;
    });

    $('.go-to-video-area').click(function(){
        if( $(window).width() <= tabletWidth ){
            if( $('.tab-controls').length ){
                var elementsHeight = $('.tab-controls').outerHeight() + Math.round($('#header').outerHeight());
            }else{
                var elementsHeight = Math.round($('#header').outerHeight());
            }
            var elToScroll = $('.video-point-scroll').offset().top - elementsHeight -1;
            $('html, body').animate({
                scrollTop: elToScroll
            }, 300);
            return false;
        }
    });

    if( $('.datepicker-jqui').length ){
        $('.datepicker-jqui').datepicker({
            dateFormat: 'd MM yy',
            beforeShow: function(input, inst) {
                var widget = $(inst).datepicker('widget');
                widget.css('margin-left', $(input).outerWidth() - widget.outerWidth());
            }
        });
    }

    $('.responsive-video').fitVids();
    $('.responsive-fb-video').fitVids({
        customSelector: "iframe[src^='https://www.facebook.com']"
    });

    $('.control-wrapper-filters .filters a').click(function(){
        var target = $(this).attr('href');
        $('.control-wrapper-filters .filters a').removeClass('active');
        $('.filters-wrapper').find('.filter-tab').removeClass('active-tab');
        $(this).addClass('active');
        $(target).addClass('active-tab');
        return false;
    });

    $('.tabs-ctrl ul li a').click(function(){
        var target = $(this).attr('href');
        var targetEl = $(this).parent().parent().parent('.tabs-ctrl').next('.inside-tabs-wrapper').find(target);
        var allTabs = $(this).parent().parent().parent('.tabs-ctrl').next('.inside-tabs-wrapper').find('.in-tab-wrapper');
        $(this).addClass('clicked');
        $(this).parent().parent('ul').find('li').each(function(){
            if( $(this).find('a').hasClass('clicked') ){
                $(this).addClass('active');
                $(this).find('a').removeClass('clicked');
            }else{
                $(this).removeClass('active');
            }
        });
        allTabs.hide();
        targetEl.show();
        return false;
    });

    $('.btn-add-participant').click(function(){
        var number = $(this).attr('data-participant-number');
        var maxticket = $(this).attr('data-ticket-max');
        maxticket = parseInt(maxticket);
        var newNumber = parseInt(number)+1;

        if(maxticket>number){
            var html = '<div class="extra-participant-area">' +
                            '<h2><span>Participant '+newNumber+'</span><a href="#" class="remove-participant"><svg version="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><!--Generated by IJSVG (https://github.com/iconjar/IJSVG)--><g fill="none"><path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5,7h14"></path><path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18,7v11c0,1.105 -0.895,2 -2,2h-8c-1.105,0 -2,-0.895 -2,-2v-11"></path><path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15,3.75h-6"></path><path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10,11v5"></path><path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14,11v5"></path></g></svg><span>Remove</span></a></h2>' +
                            '<div class="row">' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>First name <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="text" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>Last name <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="text" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>Email <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="email" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>Mobile phone <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="text" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>Company <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="text" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="col6 col-sm-12">' +
                                    '<label>Position title <span>*</span></label>' +
                                '<div class="input-safe-wrapper">' +
                                    '<input type="text" name="" class="required" />' +
                                '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
            $('.participant-form-group').append(html);
            $(this).attr('data-participant-number', newNumber);
        }
        return false;
    });

    $(document).on('click','.remove-participant',function() {
        var number = $(this).parent().parent().parent('.participant-form-group').next('.btn-add-participant').attr('data-participant-number');
        var newNumber = parseInt(number)-1;
        var smallestNumber = 2;
        $(this).parent().parent().parent('.participant-form-group').next('.btn-add-participant').attr('data-participant-number', newNumber);
        $(this).parent().parent('.extra-participant-area').addClass('to-remove')
        $(this).parent().parent().parent('.participant-form-group').find('.extra-participant-area').each(function(){
            if($(this).hasClass('to-remove')){
                $(this).remove();
            }else{
                $(this).find('h2').children('span').text('Participant ' + smallestNumber);
                smallestNumber++;
            }
        });
        return false;
    });


    $('.form-wrapper .crb-wrapper input').click(function(){
        var target = $(this).attr('data-fieldset-target');
        var $this = $(this);
        var $children = $this.parent().parent().parent().parent('.hidden-fields-actions');
        if( $children.length ){
           // console.log($children);
            $this.parentsUntil('.form-wrapper').find('.custom-radio-box.children-radio').removeClass('active');
            $this.parent().parent().addClass('active');
        }else{
            $this.parentsUntil('.form-wrapper').find('.custom-radio-box:not(.children-radio)').removeClass('active');
            $this.parentsUntil('.form-wrapper').find('.hidden-fields-actions').hide();
            $this.parent().parent().addClass('active');
            if( target != undefined ){
           //     console.log(target);
                $('.'+target).show();
            }
        }

        //return false;
    });

    $('.btn-ticket--dropdown').click(function(event){
        if( $(this).next('.tickets-dropdown').hasClass('active') ){
            $(this).next('.tickets-dropdown').removeClass('active');
        }else{
            $('.tickets-dropdown.active').removeClass('active');
            $(this).next('.tickets-dropdown').addClass('active');
        }
        return false;
    });

    $('.go-top-btn').click(function(event){
        var body = $("html, body");
        body.stop().animate({scrollTop:0}, 1000, 'swing');
        return false;
    });

    $('.search-toggle').click(function(event){
        $('#header').toggleClass('search-active');
       // event.preventDefault();
       setTimeout(function() { $('input#sat').focus() }, 300);

        //Bind click event to the document only when the search toggle is clicked.
        $(document).on('click.search' , function(){
            $('#header').toggleClass('search-active');

            //Unbind click event once search is closed for better performance
            $(document).off('click.search');
    });
    });

    $('.search-toggle , .header-search-wrapper').on('click' , function(event) {
        event.stopPropagation();
    });



    $('.boxes-carousel').owlCarousel({
        loop:false,
        mouseDrag: false,
        margin:30,
        nav:true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-green.svg'>"],
        dots:false,
        autoWidth: true,
        onInitialized: fixPosition,
        responsive:{
            0:{
                items:1,
                autoWidth: false,
                margin: 30
            },
            680:{
                items:2
            },
            1000:{
                items:2
            }
        }
    });

   $(window).scroll(function(){

        if( $(window).scrollTop() > 0 ){
            $('#header').addClass('scroll-down');
            if(hasTicker){
                $('#header').removeClass('has_ticker');

                if($(window).width() <= mobileWidth){
                    //console.log('remove has ticker tab-content')
                    $('.tab-controls').removeClass('has_ticker')
                }


            }

        }else{
            $('#header').removeClass('scroll-down')
            if(hasTicker){
                $('#header').addClass('has_ticker');

                if($(window).width() <= mobileWidth){
                    //console.log('add has ticker tab-content')
                    $('.tab-controls').addClass('has_ticker')
                }
            }

        }

        if( $(window).scrollTop() > 200 ){
            $('.go-top-btn').addClass('visible');
        }else{
            $('.go-top-btn').removeClass('visible');
        }

        if( $('.fixed-tab-controls').length ){
            var headerHeight = Math.round($('#header').outerHeight()) + 2;
            var distanceTop = $('.fixed-tab-controls').offset().top;
            if( $(window).scrollTop() > (distanceTop-headerHeight) ){
                if( ! $('.fixed-tab-controls .tab-controls').hasClass('sticky-sub') ){
                    $('.fixed-tab-controls .tab-controls').addClass('sticky-sub');
                }
            }else{
                if( $('.fixed-tab-controls .tab-controls').hasClass('sticky-sub') ){
                    $('.fixed-tab-controls .tab-controls').removeClass('sticky-sub');
                }
            }
        }

    });

    $('.mob-menu-btn').click(function(){
        $('.mobile-menu').fadeToggle(300);
        $('.cart-number').fadeToggle(300);
        $('body').toggleClass('mob-menu-active')
        $(this).toggleClass('is-active');
        return false;
    });

    $('.footer-title').click(function(){
        if( $(window).width() < tabletWidth ){
            var $this = $(this);

            // Get 2 layouts.
            var $footerRow = $(this).parent().parent('.footer-row');
            var $footerRowWithCol = $(this).parent().parent().parent().parent('.footer-row');

            // We have 2 layouts, without .row .col and without .row .col elements.
            if( $footerRow.length ){
                $(this).addClass('clicked');
                $footerRow.find('.footer-title').each(function(){
                    if( $(this).hasClass('clicked') ){
                        if( $(this).hasClass('active') ){
                            $(this).removeClass('active');
                            $(this).next().slideUp(300);
                        }else{
                            $(this).addClass('active');
                            $(this).next().slideDown(300);
                        }
                        $(this).removeClass('clicked');
                    }else{
                        $(this).removeClass('active');
                        $(this).next().slideUp(300);
                    }
                });
            }else if( $footerRowWithCol.length ){
                $(this).addClass('clicked');
                $footerRowWithCol.find('.footer-title').each(function(){
                    if( $(this).hasClass('clicked') ){
                        if( $(this).hasClass('active') ){
                            $(this).removeClass('active');
                            $(this).next().slideUp(300);
                        }else{
                            $(this).addClass('active');
                            $(this).next().slideDown(300);
                        }
                        $(this).removeClass('clicked');
                    }else{
                        $(this).removeClass('active');
                        $(this).next().slideUp(300);
        }
    });
            }
            setTimeout(function(){
                if( $('.tab-controls').length && ( $('.tabs-wrapper').hasClass('fixed-tab-controls') || $(window).width() < tabletWidth ) ){
                    var elementsHeight = $('.tab-controls').outerHeight() + Math.round($('#header').outerHeight());
                }else{
                    var elementsHeight = Math.round($('#header').outerHeight());
                }
               // console.log(elementsHeight);
                $('html, body').animate({
                    scrollTop: $this.offset().top - elementsHeight - 1
                }, 300);
            },400);
        }
    });

    $('.mobile-tabs-menu').click(function(){
        $(this).toggleClass('active');
        $(this).next('.tab-controls-list').slideToggle(300);
        return false;
    });

    $('.logos-carousel').owlCarousel({
        loop:true,
        margin:15,
        nav:true,
        dots:false,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-green.svg'>"],
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive:{
            0:{
                items:4,
                margin:15
            },
            600:{
                items:4,
                margin:90
            },
            1000:{
                items:6,
                margin:90
            }
        }
    });

    $('.video-carousel').owlCarousel({
        loop:true,
        margin:18,
        nav:true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-green.svg'>"],
        dots:false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:2
            }
        }
    });

    $('.user-testimonial').owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-light-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-light-green.svg'>"],
        dots:false,
        autoHeight:true,
        items:1
    });

    $('.video-carousel-big').owlCarousel({
        loop:true,
        margin:18,
        nav:true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-green.svg'>"],
        dots:false,
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

    $('.user-testimonial-big').owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-light-green.svg'>","<img src='/theme/assets/images/icons/icon-arrow-right-light-green.svg'>"],
        dots:false,
        autoHeight:true,
        slideBy:2,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:2
            }
        }
    });

    $('.user-testimonial-big-single').owlCarousel({
        loop:true,
        margin:0,
        items:1,
        nav:true,
        dots:false,
        autoplay: true,
        autoHeight:true,
        slideBy:2,
        autoplayTimeout: 8000,
        autoplayHoverPause: true,
        navText: ["<img src='/theme/assets/images/icons/icon-arrow-left-light-green.svg')>","<img src='/theme/assets/images/icons/icon-arrow-right-light-green.svg'>"]
    });

    $('.accordion-wrapper .accordion-item .accordion-title').click(function(){
        var $this = $(this);

    	$this.parent('.accordion-item').addClass('clicked');
    	$this.parent().parent().find('.accordion-item').each(function(){
    		if( $(this).hasClass('active-tab') && ! $(this).hasClass('clicked') ){
    			$(this).removeClass('active-tab');
    			$(this).find('.accordion-content').slideUp(300);
    		}
    	});
        if( $this.parent().parent('.accordion-wrapper').parent().parent().children('.multiple-accordions').length ){
            $this.parent().parent('.accordion-wrapper').parent().parent().children('.multiple-accordions').children('.accordion-wrapper').find('.accordion-item').each(function(){
                if( $(this).hasClass('active-tab') && ! $(this).hasClass('clicked') ){
                    $(this).removeClass('active-tab');
                    $(this).find('.accordion-content').slideUp(300);
                }
            });
        }

    	if( ! $this.parent('.accordion-item').hasClass('active-tab') ){
    		$this.parent('.accordion-item').addClass('active-tab');
    		$this.next('.accordion-content').slideDown(300);
            if( $this.hasClass('scroll-to-top') ){
                $('#header').addClass('scroll-down');

            }
            setTimeout(function(){
                if( $this.hasClass('scroll-to-top') ){

                    if( $('.tab-controls').length && ( $('.tabs-wrapper').hasClass('fixed-tab-controls') || $(window).width() < tabletWidth ) ){
                        var elementsHeight = $('.tab-controls').outerHeight() + Math.round($('#header').outerHeight());
                    }else{
                        var elementsHeight = Math.round($('#header').outerHeight());
                    }
                  //  console.log(elementsHeight);
                    $('html, body').animate({
                        scrollTop: $this.offset().top - elementsHeight - 1
                    }, 300);
                }
                if( $this.hasClass('scroll-to-top--inside') ){
                    if( $(window).width() > tabletWidth ){
                        $('.custom-scroll-area').mCustomScrollbar("scrollTo",$this.parent());
                    }else{
                        if( $('.tab-controls').length && ( $('.tabs-wrapper').hasClass('fixed-tab-controls') || $(window).width() < tabletWidth ) ){
                        var elementsHeight = $('.tab-controls').outerHeight() + Math.round($('#header').outerHeight());
                    }else{
                        var elementsHeight = Math.round($('#header').outerHeight());
                    }
                //    console.log(elementsHeight);
                    $('html, body').animate({
                        scrollTop: $this.offset().top - elementsHeight - 1
                    }, 300);
                }
                }
            },400);
    	}else{
    		$this.parent('.accordion-item').removeClass('active-tab');
    		$this.next('.accordion-content').slideUp(300);
    	}
    	$this.parent('.accordion-item').removeClass('clicked');
    });

    $('.tabs-wrapper .tab-controls ul li a').click(function(){

    	var $this = $(this);
    	var target = $this.attr('href');
        var text = $this.text();
        var childMenuEl = $this.parent().parent('.child-menu');

        //Check if clicked element is in child menu
        if( ! childMenuEl.length ){
            var $el = $this;
        }else{
            var $el = childMenuEl;
        }

        //Change text for mobile
        $el.parent().parent('ul').prev().text(text);

        //Remove active classes
        $el.parent().parent('ul').children('li').children('a').removeClass('active');
        if( $el.parent().parent('ul').children('li').children('.child-menu').length ){
            $el.parent().parent('ul').children('li').children('.child-menu').children('li').children('a').removeClass('active');
        }
        if( $el.parent().parent().parent('.tab-controls').next('.tabs-content').find('.tab-content-wrapper').length ){
            $el.parent().parent().parent('.tab-controls').next('.tabs-content').children('.tab-content-wrapper').removeClass('active-tab');
        }else if( $el.parent().parent().parent('.container').parent('.tab-controls').next('.tabs-content').find('.tab-content-wrapper').length ){
            $el.parent().parent().parent('.container').parent('.tab-controls').next('.tabs-content').children('.tab-content-wrapper').removeClass('active-tab');
        }
        $(".tabs-content>div").removeClass('active-tab');

    	$(target).addClass('active-tab');
    	$this.addClass('active');

        //Add active class to parent anchor element.
        if( $el.prev('a').length && $(window).width() > tabletWidth ){
            $el.prev('a').addClass('active');
        }

        if( $(window).width() < tabletWidth ){
            $el.parent().parent('ul').slideUp(300);
            $el.parent().parent('ul').prev().removeClass('active');
        }

        // If fixed tab manu, on click scroll to tab.
        if( ( $el.parent().parent().parent().parent().parent('.tabs-wrapper').hasClass('fixed-tab-controls') && $el.parent().parent().parent().parent('.tab-controls').hasClass('sticky-sub') ) && $(window).width() > mobileWidth ){
            var elToScroll = $el.parent().parent().parent().parent().parent('.tabs-wrapper').offset().top - Math.round($('#header').outerHeight()) - 1;
            $('html, body').animate({
                scrollTop: elToScroll
            }, 300);
        }

    	return false;
    });

    $('.tabs-forms .tab-controls ul li a').click(function(){
        var $this = $(this);
        var target = $this.attr('href');
        $this.parent().parent('ul').children('li').children('a').removeClass('active');
        $this.parent().parent().parent('.tab-controls').next('.tabs-content').children('.tab-content-wrapper').removeClass('active-tab');
        $(target).addClass('active-tab');
        $this.addClass('active');
        return false;
    });

    $(window).on('load resize', function(){
        if( $(window).width() > tabletWidth ){
            $('.mobile-menu').fadeOut(300);
            $('.tabs-wrapper .tab-controls .tab-controls-list').show();
            $('#footer .mobile-toggle').show();
            $('#footer .footer-title').removeClass('active');
            $('.custom-scroll-area').mCustomScrollbar();
        }else{
            $('.tabs-wrapper .tab-controls .tab-controls-list').hide();
            $('#footer .mobile-toggle').hide();
            $('#footer .footer-title').removeClass('active');
            $('.custom-scroll-area').mCustomScrollbar("destroy");
        }

    });

    Marquee3k.init()

    $(window).on('load', function(){

        if( $(window).scrollTop() > 0 ){
            $('#header').addClass('scroll-down');



        }else{
            $('#header').removeClass('scroll-down');

        }
        if( $(window).scrollTop() > 200 ){
            $('.go-top-btn').addClass('visible');
        }else{
            $('.go-top-btn').removeClass('visible');
        }


        // document.fonts.ready.then(function () {
        //     $(".profile-form-wrapper form .form-row .input-wrapper input").autoresize({padding:0,minWidth:10,maxWidth:300});
        // });


        // Wait for menu transition ends and get the final height of menu
        setTimeout(function(){
            if( $('.fixed-tab-controls').length ){
                var headerHeight = $('#header').outerHeight();
                var distanceTop = $('.fixed-tab-controls').offset().top;
                if( $(window).scrollTop() > (distanceTop-headerHeight) ){
                    if( ! $('.fixed-tab-controls .tab-controls').hasClass('sticky-sub') ){
                        $('.fixed-tab-controls .tab-controls').addClass('sticky-sub');
                    }
                }else{
                    if( $('.fixed-tab-controls .tab-controls').hasClass('sticky-sub') ){
                        $('.fixed-tab-controls .tab-controls').removeClass('sticky-sub');
                    }
                }
            }
        },300);
    });

    function fixPosition(event){
   //     console.log(event);
        var carouseCountClass = 'less-that-trigger';
        if( event.item.count >= event.isTrigger ){
            carouseCountClass = 'more-that-trigger';
        }
        $(event.currentTarget).addClass(carouseCountClass);
    }

    $('li.account-menu a').click(function(e) {
      //  e.preventDefault();

        $('.login-popup-wrapper').addClass('active');
    });

    $('.login-popup-wrapper .close-btn').click(function(e) {
        e.preventDefault();

        $('.login-popup-wrapper').removeClass('active');
    });

    /*
     * Replace all SVG images with inline SVG
     */
    jQuery('img.replace-with-svg').filter(function() {
        return this.src.match(/.*\.svg$/);
    }).each(function(){
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');
        var imgWidth = $img.attr('width');

        $.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = $(data).find('svg');

            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }
            // Add replaced image's width to the new SVG
            if(typeof imgWidth !== 'undefined') {
                $svg = $svg.attr('width', imgWidth);
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });

});

$.fn.textWidth = function(_text, _font){//get width of text with font.  usage: $("div").textWidth();
    var fakeEl = $('<span style="text-transform: uppercase;">').hide().appendTo(document.body).text(_text || this.val() || this.text()).css('font', _font || this.css('font')),
        width = fakeEl.width();
    fakeEl.remove();
    return width;
};

$.fn.autoresize = function(options){//resizes elements based on content size.  usage: $('input').autoresize({padding:10,minWidth:0,maxWidth:100});
    options = $.extend({padding:10,minWidth:0,maxWidth:10000}, options||{});

    $(this).on('input', function() {
        $(this).css('width', Math.min(options.maxWidth,Math.max(options.minWidth,$(this).textWidth() + options.padding)));
    }).trigger('input');
    return this;
}

// Function that validates email address through a regular expression.
function validateEmail(sEmail) {
    var filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}
