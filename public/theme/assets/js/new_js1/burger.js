

 $('.hamburger').click(function(){
    
    //$(this).toggleClass('is-active');

    $(".jsSidebar").removeClass("open");
    $(".jsLesson").addClass("full-width");

    if ($(document).width() > 991) {
        let headerHeight = $(".lesson-header").innerHeight();
        let windowHeight = $(window).height();
        let newHeight = windowHeight - headerHeight;
        $(".video-wrapper").css('height', newHeight);
    }

    return false;
});


$('.hamburger').not('.is-activee').click(function(){
    
    //$(this).toggleClass('is-active');

    //e.preventDefault();
    $(".jsSidebar").addClass("open");
    $(".jsLesson").removeClass("full-width");

    if ($(document).width() > 991) {
        $(".video-wrapper").css('height', '0');
    }
    return false;
});

