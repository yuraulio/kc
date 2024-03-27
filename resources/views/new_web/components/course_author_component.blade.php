@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $syllabus = $dynamic_page_data["syllabus"] ?? null;
    $partners = $dynamic_page_data['partners'] ?? null;
@endphp

<div class="course-tab-text">
    <div class="author-infos mt-5 mb-5">
        <div class="row">
            @if(count($syllabus) > 0)
                <div class="col-sm-6 col-xs-12">
                    <div id="syll" class="ibox">
                        <div class="author-img">
                            <?php
                                $alt='';
                                $width = 0;
                                $height = 0;
                                $img = get_image($syllabus[0]['mediable'],'users');

                                $imageDetails = get_image_version_details('users');
                                $width = $imageDetails['w'];
                                $height = $imageDetails['h'];
                            ?>
                            <a id="syllabus-link" href="{{config("app.NEW_PAGES_LINK") . "/" . $syllabus[0]['slugable']['slug']}}"><img loading="lazy" src="{{cdn($img)}}" alt="{{$alt}}" width="{{ $width }}" height="{{ $height }}" title="{{$alt}}"></a>
                        </div>
                        <div class="ibox-text">
                            <p>Syllabus Manager<br></p>
                            <p>
                                <a href="{{config("app.NEW_PAGES_LINK") . "/" . $syllabus[0]['slugable']['slug']}}">{{ $syllabus[0]['title'] }} {!! $syllabus[0]['subtitle'] !!}</a>
                            </p>
                        </div>
                    </div>
                </div>
                {{--<div class="col-sm-6 col-xs-12">
                    <div class="ibox">
                    @foreach($partners as $partner)
                            <?php
                                $alt=$partner->name;
                                $img = get_image($partner['mediable']);
                            ?>
                            <div class="ibox-img partner">
                                <img class="resp-img" loading="lazy" src="{{cdn($img)}}" width="{{isset($partner['mediable']) ? $partner['mediable']['width'] : ''}}" height="{{isset($partner['mediable']) ? $partner['mediable']['height'] : ''}}" alt="{{$alt}}">
                            </div>
                            <div class="ibox-text">
                                <div class="ibox-text">
                                <p>Supported By<br></p>
                                    @if($partner->url != null)
                                    <p><a target="_blank" href="{{$partner->url}}">{!! $partner->name !!}</a><p>
                                    @else
                                    <p>{!! $partner->name !!}</p>
                                    @endif
                                </div>


                            </div>

                        @endforeach
                    </div>
                </div>--}}
            @endif
        </div>
    </div>
</div>
