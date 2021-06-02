<div id="mediaSubMenu">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-5">
            <div class="select-gallery <?php if ($media_type == "videos") { echo 'active'; } ?>">
                 <a href="/media/videos" id="gallery-videos-tab-toggle">
                    <i class="fa fa-line-chart"></i>
                    Video
                </a>
            </div>
            <div class="select-gallery <?php if ($media_type == "photos") { echo 'active'; } ?>">
                <a href="/media/photos" id="gallery-photos-tab-toggle">
                    <i class="fa fa-line-chart"></i>
                    Φωτογραφίες
                </a>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-7">
            <ul id="gallery-filter-categories">
                @if (!empty($mediaCategories))
                    @foreach ($mediaCategories as $key => $row)
                    <li class="<?php if ($category == $row['slug']) { echo 'active'; } ?>">
                        <a href="/media/{{ $media_type }}/{{ $row['slug'] }}" title="{{ $row['name'] }}">
                            {{ $row['name'] }}
                        </a>
                    </li>
                    @endforeach
                @endif
                <li class="<?php if ($category == 'all') { echo 'active'; } ?>">
                    @if ($media_type == "videos")
                    <a href="/media/{{ $media_type }}/all" title="Όλα τα βίντεο">
                        Όλα τα βίντεο
                    </a>
                    @else
                    <a href="/media/{{ $media_type }}/all" title="Όλες οι Γκαλερί">
                        Όλες οι Γκαλερί
                    </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
