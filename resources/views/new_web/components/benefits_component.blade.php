@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $benefits = $dynamic_page_data["benefits"] ?? null;

    $title = '' ;
    $body = '' ;
    if(isset($sections['benefits'])){
        $title = $sections['benefits']->first()->title ?? null;
        $body = $sections['benefits']->first()->description ?? null;
    }
@endphp

<div id="benefits" class="">
    <div class="course-benefits-text">
        <div class="container">
            <h2 class="text-align-center text-xs-left tab-title mt-5">{!!$title!!}</h2>
            <h3>{!!$body!!}</h3>
            <div class="benefits-list">
                <div class="row-flex row-flex-17">
                    @foreach($benefits as $key => $benefit)
                        @if($benefit['name'] != '')
                            <div class="col-3 col-sm-6 col-xs-12">
                                <div class="benefit-box">
                                    <div class="box-icon">
                                        <img class="replace-with-svg" src="{{cdn(get_image($benefit['medias']))}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefit['name'] }}</h3>
                                    {!! $benefit['description'] !!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>