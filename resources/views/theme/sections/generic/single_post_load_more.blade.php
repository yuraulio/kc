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
    $(".dpContentEntry").each(function (idx, val) {
        excluding_ids[idx] = $(this).attr('data-dp-content-id');
    });

    $.ajax({ url: 'frontend-helper', type: "post",
        data: { method: 'postLoadMore',
            take: $("#appendNewContent").attr('data-dp-take'),
            total: $("#appendNewContent").attr('data-dp-total'),
            excluding_ids: excluding_ids,
            category_id: $("#appendNewContent").attr('data-dp-section-id')
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

    var html = '<div class="other-post-list-item dpContentEntry" data-dp-content-id="'+row.id+'">';
    html += '<div class="row">';

    html += '<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 other-post-preview-img">';
    html += '<a href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += '<img class="img-responsive top-banner-img" alt="'+$.trim(row.title)+'" src="'+row.src+'" />';
    html += '</a>';
    html += '</div>';

    html += '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 other-post-preview">';
    html += '<span class="small-date">';
    html += row.published_at;
    html += '</span>';
    html += '<h1>';
    html += '<a href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += $.trim(row.title);
    html += '</a>';
    html += '</h1>';

    html += '<a class="category_lead_text" href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += $.trim(row.truncated_body);
    html += '</a>'

    html += '</div>';

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
