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
        data: { method: 'sectionLoadMore',
            take: $("#appendNewContent").attr('data-dp-take'),
            total: $("#appendNewContent").attr('data-dp-total'),
            skip: $("#appendNewContent").attr('data-dp-skip'),
            category_id: $("#appendNewContent").attr('data-dp-section-id')
        },
        success: function(data) {
            var currTotal = Number(data.count) + Number($("#appendNewContent").attr('data-dp-skip'));
            $("#appendNewContent").attr('data-dp-skip', currTotal);
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

/*
function frontHelp(method, data) {
    switch (method) {
        case "pSlug":
            return pSlug(data);
            break;
        case "pField":
            return pField(data);
            break;
        case "pImg":
            return pImg(data);
            break;
        case "pTarget":
            return pTarget(data);
            break;
    }

    function pSlug(data) {
        var slug = '';
        if (!$.isEmptyObject(data)) {
            if ((typeof data.ext_url !== "undefined") && (data.ext_url.length)) {
                slug = data.ext_url;
            } else {
                if ((typeof data.content !== "undefined") && (!$.isEmptyObject(data.content)) && (data.content.ext_url.length)) {
                    slug = data.content.ext_url;
                } elseif
            }
        }

        return slug;
    }

    function pField(data) {

    }

    function pImg(data) {

    }
}
*/

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
