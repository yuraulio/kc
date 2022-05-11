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
                                $img = get_image($syllabus[0]['mediable'],'instructors-small');
                            ?>
                            <a id="syllabus-link" href="{{env("NEW_PAGES_LINK") . "/" . $syllabus[0]['slugable']['slug']}}"><img src="{{cdn($img)}}" alt="{{$alt}}"></a>
                        </div>
                        <div class="ibox-text">
                            <p>Syllabus Manager<br></p>
                            <p>
                                <a href="{{env("NEW_PAGES_LINK") . "/" . $syllabus[0]['slugable']['slug']}}">{{ $syllabus[0]['title'] }} {!! $syllabus[0]['subtitle'] !!}</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="ibox">
                        @foreach($partners as $partner)
                            <?php
                                $alt=$partner->name;
                                $img = get_image($partner['mediable']);
                            ?>
                            <div class="ibox-img">
                                <img src="{{cdn($img)}}" alt="{{$alt}}">
                            </div>
                            <div class="ibox-text">
                                {!! $partner->name !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>