<script type="text/javascript">
$(function () {
    $("body").on("click", ".loadMoreContentBtn", function () {
        if (Number($("#appendNewContent").attr('data-dp-total')) > $(".dpContentEntry").length) {
            //console.log('Load more results');
            loadMoreContent();
        } else {
            //no more results
            //console.log('No more results');
            $("#loadMoreContent").remove();
        }
    });
});

function loadMoreContent() {
    var excluding_ids = [];
    var category_ids = [];
    $(".dpContentEntry").each(function (idx, val) {
        excluding_ids[idx] = $(this).attr('data-dp-content-id');
    });

    //category_ids = $.parseJSON($("#appendNewContent").attr('data-dp-section-ids'));
    //console.log(category_ids);

    $.ajax({ url: 'frontend-helper', type: "post",
        data: { method: 'videosLoadMore',
            take: $("#appendNewContent").attr('data-dp-take'),
            total: $("#appendNewContent").attr('data-dp-total'),
            excluding_ids: excluding_ids,
            category_ids: $.parseJSON($("#appendNewContent").attr('data-dp-section-ids')),
            type: $("#appendNewContent").attr('data-dp-media-type')
        },
        success: function(data) {
            $("#loadMoreContent").remove();
            appendContent(data);

            if ($(".dpContentEntry").length >= Number($("#appendNewContent").attr('data-dp-total'))) {
                //no more results
            } else {}
        }
    });
}

function appendContent(data) {
    $.each(data.list, function (idx, row) {
        $("#appendNewContent").append(contentTpl(row));
    });

    if ($(".dpContentEntry").length < Number($("#appendNewContent").attr('data-dp-total'))) {
        $("#appendNewContent").append(loadMoreContentTpl());
    } else {}
}

function contentTpl(row) {
    var html = '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nopadding dpContentEntry" data-dp-content-id="'+row.id+'">';
    html += '<div class="gallery-video-holder">';
    html += '<div class="video-container">';
    html += '<a href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += '<img style="width:100%;" class="img-responsive center-block" alt="'+$.trim(row.title)+'" src="'+row.src+'">';
    html += '</a>';
    html += '</div>';
    html += '<h1 class="gallery-video-post-title">';
    html += '<a href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += $.trim(row.title);
    html += '</a>';
    html += '</h1>';
    html += '</div>';
    html += '</div>';

    return html;
}

function noMoreContentTpl() {

}

function loadMoreContentTpl() {
    var html = '<div class="row" id="loadMoreContent">';
    html += '<div class="col-lg-4 col-md-4 col-sm-4"></div>';
    html += '<div class="col-lg-4 col-md-4 col-sm-4">';
    html += '<span class="btn btn-normal loadMoreContentBtn">';
    html += 'Δείτε περισσότερα';
    html += '</span>';
    html += '</div>';
    html += '<div class="col-lg-4 col-md-4 col-sm-4"></div>';
    html += '</div>';

    return html;
}
</script>
