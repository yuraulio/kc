<script type="text/javascript">
$(function() {
    var vc = $('#videoCarousel');
    var pc = $('#photoCarousel');

    if ($("#carousel-custom").length > 0) {
        scrollToSection("#carousel-custom");
        setPhotoGalleryHeight();
    } else {
        scrollToSection();
    }
});

$('#gallery-videos-tab-toggle').click(function(){
    var vd = $('#gallery-videos-tab');
    var ph = $('#gallery-photos-tab');
    var vc = $('#videoCarousel');
    var pc = $('#photoCarousel');

    var $thevid = $(this).closest('div[class^="select-gallery"]');

    $('.select-gallery').removeClass('active');
    $thevid.addClass('active');

    ph.removeClass('active');
    vd.addClass('active');

    pc.hide();
    vc.show();
});

$('#gallery-photos-tab-toggle').click(function(){
    var vd = $('#gallery-videos-tab');
    var ph = $('#gallery-photos-tab');
    var vc = $('#videoCarousel');
    var pc = $('#photoCarousel');
    var $thevid = $(this).closest('div[class^="select-gallery"]');

    $('.select-gallery').removeClass('active');
    $thevid.addClass('active');

    vd.removeClass('active');
    ph.addClass('active');

    vc.hide();
    pc.show();
});

function scrollToSection(toElement) {
    if (typeof toElement !== "undefined") {
        $('html, body').animate({
            scrollTop: $(toElement).offset().top
        }, 800);
    } else {
        $('html, body').animate({
            scrollTop: $("#mediaSubMenu").offset().top
        }, 800);
    }
}

function setPhotoGalleryHeight() {
    $("#carousel-custom").css({
        height: $(window).height()
    });

    $("#carousel-custom .carousel-outer img").css({
        "max-height": $(window).height() - 160
    });
}
</script>
