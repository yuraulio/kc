$(document).ready(function () {

    $(".topic-header").click(function (e) {
        e.preventDefault();


        if ( !$(this).parent().hasClass("open") ) {
            resetAllTopics();
            $(this).parent().addClass("open");
            $(this).parent().find('.jsTopicOpen').hide();
            $(this).parent().find(".jsTopicClose").show();
            $(this).parent().find(".lessons-list").slideDown();
            var container = $('.sidebar-content'),
            scrollTo = $(this).parent();
            setTimeout(function(){
                container.animate({scrollTop: scrollTo.position().top}, 700);
            }, 750);
        } else {
            $(this).parent().removeClass("open");
            $(this).parent().find('.jsTopicClose').hide();
            $(this).parent().find(".jsTopicOpen").show();
            $(this).parent().find(".lessons-list").slideUp();
        }
    });

    // function myScroll() {
    // }

    // make toggle sidebar icon green on hover
    $(".jsGreenOnHover").hover(function (e) {
        e.preventDefault();
        $(this).attr("src","theme/assets/img/new/arrows-green.svg");
    }, function(e) {
        e.preventDefault();
        $(this).attr("src","theme/assets/img/new/arrows.svg");
    });

    // make lesson title green on lesson hover
    $(".lesson").hover(function (e) {
        e.preventDefault();
        $(this).find(".lesson-info_title").css("color","#d2ff00");
    }, function(e) {
        e.preventDefault();
        $(this).find(".lesson-info_title").css("color","#fff");
    });

    $(".jsHideSidebar").click(function (e) {
        e.preventDefault();
        $(".jsSidebar").removeClass("open");
        $(".jsLesson").addClass("full-width");

		if ($(document).width() > 991) {
            let headerHeight = $(".lesson-header").innerHeight();
            let windowHeight = $(window).height();
            let newHeight = windowHeight - headerHeight;
            $(".video-wrapper").css('height', newHeight);
        }
    });

    $(".jsShowSidebar").click(function (e) {
        e.preventDefault();
        $(".jsSidebar").addClass("open");
        $(".jsLesson").removeClass("full-width");

		if ($(document).width() > 991) {
            $(".video-wrapper").css('height', '0');
        }
    });

    $(".jsOpenResourcesTab").click(function (e) {
        e.preventDefault();
        $(".notes-button").removeClass("active");
        $(".notes").removeClass("active");
        $(this).addClass("active");
        $(".resources").addClass("active");
    });

    $(".jsOpenNotesTab").click(function (e) {
        e.preventDefault();
        $(".resources-button").removeClass("active");
        $(".resources").removeClass("active");
        $(this).addClass("active");
        $(".notes").addClass("active");
    });


    $(".jsSearchInput").on('keyup', function(e) {
        e.preventDefault();
        if ( $(this).val().length != 0 ) {
            $("#jsSearchButton").attr("src","theme/assets/img/new/close.svg");
            filterLessons($(this).val());
            filterTopics($(this).val());
        } else {
            $("#jsSearchButton").attr("src","theme/assets/img/new/search-icon.svg");
            resetAllLessons();
            resetAllTopics();
        }
    });

    $("#jsSearchButton").click(function (e) {
        e.preventDefault();
        $(".jsSearchInput").val('');
        $(this).attr("src","theme/assets/img/new/search-icon.svg");
        resetAllLessons();
        resetAllTopics();
    });

    function filterLessons(keyword) {
        // let hasMatch = false;

        $(".lesson").each(function() {
            let lessonTitle = $(this).find(".lesson-info_title").attr('data-title');
            let lessonTitleLowerCase = lessonTitle.toLowerCase();
            if (lessonTitleLowerCase.includes(keyword.toLowerCase())) {
                $(this).css('display', 'block');
                hasMatch = true;
                var regexp = new RegExp(keyword, "i")
                var result = lessonTitle.match(regexp);

                $(this).find(".lesson-info_title").html(lessonTitle.replace(result, '<span class="highlighted">'+ result +'</span>'));
                $(this).parent().css('display', 'block');
            } else {
                $(this).find(".lesson-info_title").html(lessonTitle);
                $(this).css('display', 'none');
            }

        })

        // if(hasMatch) {
        //     showAllTopics();
        // } else {
        //     resetAllTopics();
        // }

    }

    function filterTopics(keyword) {
        // let hasMatch = false;
        let hasChildrenMatch = false;

        $(".topic").each(function() {
            $(this).find('.lesson').each(function() {
                if ($(this).find(".lesson-info_title").html().includes("highlighted")) {
                    hasChildrenMatch = true;
                }
            })
            let topicTitle = $(this).find(".topic-info_title").attr('data-title');
            let topicTitleLowerCase = topicTitle.toLowerCase();
            if (topicTitleLowerCase.includes(keyword.toLowerCase())) {
                $(this).css('display', 'block');
                $(this).find('.lessons-list').css('display', 'block');
                var regexp = new RegExp(keyword, "i")
                var result = topicTitle.match(regexp);
                if (!hasChildrenMatch) {
                    $(this).find(".lesson").css('display', 'block');
                }

                $(this).find(".topic-info_title").html(topicTitle.replace(result, '<span class="highlighted">'+ result +'</span>'));
            } else if (hasChildrenMatch) {
                $(this).css('display', 'block');
                $(this).find('.lessons-list').css('display', 'block');
                $(this).find(".topic-info_title").html(topicTitle);
            }
            else {

                $(this).css('display', 'none');
            }


            hasChildrenMatch = false;
        })
    }

    function resetAllLessons() {
        $(".lesson").css('display', 'block');
        $(".lesson a").css('display', 'flex');
        $(".lesson-info_title").each(function() {
            $(this).html($(this).attr('data-title'));
        })
    }

    function resetAllTopics() {
        $(".lessons-list").slideUp();
        $(".topic").css('display', 'block');
        $(".topic").removeClass("open");
        $(".jsTopicClose").hide();
        $(".jsTopicOpen").show();
        $(".topic-info_title").each(function() {
            $(this).html($(this).attr('data-title'));
        })
    }

    // function showAllTopics() {
    //     $(".lessons-list").css('display', 'block');
    //     $(".jsTopicOpen").hide();
    //     $(".jsTopicClose").show();
    // }


    ///LIONCODE
    $('.lessons-list .lesson').click(function(){
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('.jsSidebar.open').removeClass('open')
        }
    })


});
