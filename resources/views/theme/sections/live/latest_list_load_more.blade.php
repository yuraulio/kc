<script type="text/javascript">
$(function () {
    $("body").on("click", ".loadMoreContentBtn", function () {
        if (Number($("#appendNewContent").attr('data-dp-total')) > $("#appendNewContent .dpContentEntry").length) {
            //console.log('Load more results');
            loadMoreContent();
        } else {
            //no more results
            //console.log('No more results');
        }
    });
});
function loadMoreContent() {
    $.ajax({ url: 'frontend-helper', type: "post",
        data: { method: 'liveLoadMore',
            take: $("#appendNewContent").attr('data-dp-take'),
            total: $("#appendNewContent").attr('data-dp-total'),
            skip: $("#appendNewContent").attr('data-dp-skip'),
            updated_pivot_time: $("#appendNewContent").attr('data-dp-updated-pivot-time')
        },
        success: function(data) {
            var currTotal = Number(data.count) + Number($("#appendNewContent").attr('data-dp-skip'));
            $("#appendNewContent").attr('data-dp-skip', currTotal);
            $("#appendNewContent").attr('data-dp-total',data.total);
            $("#loadMoreContent").remove();
            appendContent(data);

            if (currTotal >= Number($("#appendNewContent").attr('data-dp-total'))) {
                //no more results
            } else {}
        }
    });
}

function appendContent(data) {
    $.each(data.list, function (idx, row) {
        $("#appendNewContent").append(contentTpl(row));
    });

    if (Number($("#appendNewContent").attr('data-dp-skip')) < Number($("#appendNewContent").attr('data-dp-total'))) {
        $("#appendNewContent").append(loadMoreContentTpl());
    }
}

function contentTpl(row) {
    //routesObj.baseUrl();
    var html = '<div class="col-lg-4 col-md-6 col-sm-12 dpContentEntry" data-dp-content-id="'+row.id+'">';
    html += '<div class="home-small-photo-tile">';

    html += '<div class="home-small-photo">';
    html += '<a href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += '<img style="width:100%;" class="img-responsive center-block" alt="'+$.trim(row.title)+'" src="'+row.src+'" />';
    html += '</a>';
    html += '</div>';

    html += '<span class="home-small-post-date">';
    html += row.published_at
    html += '</span>';

    html += '<h2 class="home-small-post-sub-title">';
    html += '<a class="section-heading-'+$("#appendNewContent").attr('data-dp-section-id')+'>" href="'+row.slug+'" title="'+$.trim(row.title)+'" target="'+row.target+'">';
    html += $.trim(row.header);
    html += '</a>';
    html += '</h2>';

    html += '<h1 class="home-small-post-title">';
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
