@php
    $homepage = [];
    foreach ($column->template->inputs as $input){
        $homepage[$input->key] = $input->value ?? "";
    }

    $inclassEvents = $dynamic_page_data["nonElearningEvents"] ?? [];
    $inclassFree = $dynamic_page_data["inclassFree"] ?? [];
    $elearningEvents = $dynamic_page_data["elearningEvents"] ?? [];
    $elearningFree = $dynamic_page_data["elearningFree"] ?? [];

    foreach ($inclassEvents as $key => $data) {
        $inclassEvents[$key]["free"] = false;
    }
    foreach ($inclassFree as $key => $data) {
        $inclassFree[$key]["free"] = true;
    }
    foreach ($elearningEvents as $key => $data) {
        $elearningEvents[$key]["free"] = false;
    }
    foreach ($elearningFree as $key => $data) {
        $elearningFree[$key]["free"] = true;
    }

    $sumStudentsByCategory = $dynamic_page_data['sumStudentsByCategories'];
    // $inclassEvents = array_merge($inclassEvents, $inclassFree);
    $elearningEvents = array_merge($elearningEvents, $elearningFree);
@endphp

@if($homepage["list_source"]->id == 6)
    <div class="row homepage-events mt-5">
        @foreach($inclassEvents as $data)
            @foreach($data['events'] as $event)
                <div class="col-sm-6 col-md-3 mb-5">
                    <div class="slide d-inline-block">
                        <?php
                        $string = $event['title'];
                        if( strpos($string, ',') !== false ) {
                            $until = substr($string, 0, strrpos($string, ","));
                        }
                        else {
                            $until = $string;
                        }
                        ?>

                        @if ( isset($event['mediable']) && isset($event['slugable']))
                        <a href="{{ $event['slugable']['slug'] }}">
                            <img loading="lazy" class="resp-img" src="{{ cdn(get_image($event['mediable'],'event-card')) }}" alt="{{ $until }}" width="{{ get_image_version_details('event-card')['w'] }}" height="{{ get_image_version_details('event-card')['h']}}" />
                        </a>
                        @endif

                        <div class="box-text">
                            @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                <h3><a href="{{ $event['slugable']['slug'] }}">{{ $until}}</a></h3>
                            @endif

                            <div class="box-footer">
                                @if(isset($event['city']) && count($event['city']) > 0)

                                <a href="{{ $event['city'][0]['slugable']['slug'] }}" class="location">{{ $event['city'][0]['name'] }}</a>
                                @endif
                                <?php $dateLaunch = !$event['launch_date'] ? date('F Y', strtotime($event['published_at'])) : date('F Y', strtotime($event['launch_date']));?>


                                <?php
                                    if($event['event_info1'] != null ){
                                        $sumStudents = 0;

                                        $hours_visible = isset($event['event_info1']['course_hours_visible']) ? json_decode($event['event_info1']['course_hours_visible'], true) : null;
                                        //$language_visible = isset($event['event_info1']['course_language_visible']) ? json_decode($event['event_info1']['course_language_visible'], true) : null;

                                        //$inclass_dates = isset($event['event_info1']['course_inclass_dates']) ? json_decode($event['event_info1']['course_inclass_dates'], true) : null;
                                        //$inclass_times = isset($event['event_info1']['course_inclass_times']) ? json_decode($event['event_info1']['course_inclass_times'], true) : null;
                                        //$inclass_days = isset($event['event_info1']['course_inclass_days']) ? json_decode($event['event_info1']['course_inclass_days'], true) : null;

                                        //$certificate_visible = isset($event['event_info1']['course_certification_visible']) ? json_decode($event['event_info1']['course_certification_visible'], true) : null;
                                        //$students_visible = isset($event['event_info1']['course_students_visible']) ? json_decode($event['event_info1']['course_students_visible'], true) : null;

                                    }

                                    if(isset($hours_visible['home']) && $hours_visible['home'] && isset($event['event_info1']['course_hours']) && $event['event_info1']['course_hours'] > 0){
                                        $dateLaunch .= ', ' . $event['event_info1']['course_hours'] . ' hours';
                                    }


                                ?>

                                <span class="date">{{$dateLaunch}}</span>
                                {{--<span class="hours">@if(isset($hours_visible['home']) && $hours_visible['home'] ) {{ (isset($event['event_info1']['course_hours']) && $event['event_info1']['course_hours']) ? $event['event_info1']['course_hours'] : '' }} {{ (isset($event['event_info1']['course_hours_text']) && $event['event_info1']['course_hours_text'] != null) ? $event['event_info1']['course_hours_text'] : '' }}@endif</span>
                                <span class="language">@if(isset($language_visible['home']) &&  $language_visible['home'] && isset($event['event_info1']['course_language']) && $event['event_info1']['course_language'] != null) {{ $event['event_info1']['course_language'] }} @endif</span>
                                <span class="certificate">@if(isset($certificate_visible['home']) && $certificate_visible['home'] && isset($event['event_info1']['course_certification_type']) && $event['event_info1']['course_certification_type'] != null) {{ $event['event_info1']['course_certification_type'] }} @endif</span>

                                <span class="dates">@if(isset($inclass_dates['visible']['home']) && $inclass_dates['visible']['home'] && isset($inclass_dates['text']) && $inclass_dates['text'] != null) {{ $inclass_dates['text'] }} @endif</span>
                                <span class="days">@if(isset($inclass_days['visible']['home']) && $inclass_days['visible']['home'] && isset($inclass_days['text']) && $inclass_days['text'] != null) {{ $inclass_days['text'] }} @endif</span>
                                <span class="times">@if(isset($inclass_times['visible']['home']) && $inclass_times['visible']['home'] && isset($inclass_times['text']) && $inclass_times['text'] != null) {{ $inclass_times['text'] }} @endif</span>--}}


                                <?php
                                    $sumStudents = $sumStudentsByCategory[$event['pivot']['category_id']];
                                ?>
                                <span class="students">@if(isset($students_visible['home']) && $students_visible['home'] && isset($event['event_info1']['course_students_number']) && $sumStudents > (int)$event['event_info1']['course_students_number']) {{ $sumStudents }} {{ ((isset($event['event_info1']['course_students_text']) && $event['event_info1']['course_students_text'] != null) ? $event['event_info1']['course_students_text'] : '')}} @endif</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if (isset($event['event_info1']['course_payment_method']) && $event['event_info1']['course_payment_method'] == 'free')
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                    @elseif($event['status'] != 0 && $event['status'] != App\Model\Event::STATUS_WAITING)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                                    @elseif($event['status'] == App\Model\Event::STATUS_WAITING)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">JOIN WAITING LIST</a>
                                    @else
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--primary">ENROLL FOR € {{ $event['ticket'][0]['price'] }} </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <script type="application/ld+json">
                    {!! json_encode($event->schemadata()) !!}
                </script>
            @endforeach
        @endforeach
    </div>
@endif

@if($homepage["list_source"]->id == 7)
    <div class="row homepage-events mt-5">
        @foreach($elearningEvents as $data)

            @foreach($data['events'] as $event)
                <script type="application/ld+json">
                    {!! json_encode($event->schemadata()) !!}
                </script>
                <div class="col-sm-6 col-md-3 mb-5">
                    <div class="slide d-inline-block">
                        <?php
                            $string = $event['title'];
                            if( strpos($string, ',') !== false ) {
                                $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                                $until = $string;
                            }
                        ?>
                        @if ( isset($event['mediable']) && isset($event['slugable']))
                        <a href="{{ $event['slugable']['slug'] }}">

                            <img loading="lazy" class="resp-img" src="{{ cdn(get_image($event['mediable'],'event-card')) }}" alt="{{ $until }}" width="{{ get_image_version_details('event-card')['w'] }}" height="{{ get_image_version_details('event-card')['h']}}" /></a>
                        @endif
                        <div class="box-text box-text-orange">
                            <?php
                                $string = $event['title'];
                                if( strpos($string, ',') !== false ) {
                                    $until = substr($string, 0, strrpos($string, ","));
                                }
                                else {
                                    $until = $string;
                                }

                                if(isset($event['slugable'])){
                                    $slug = $event['slugable']['slug'];
                                }else{
                                    $slug = '';
                                }

                                $month = !$event['launch_date'] ? date('F Y', strtotime($event['published_at'])) : date('F Y', strtotime($event['launch_date']));
                                $url = url($slug);
                            ?>

                            <?php

                                if($event['event_info1'] != null ){
                                    $sumStudents = 0;

                                    $hours_visible = isset($event['event_info1']['course_hours_visible']) ? json_decode($event['event_info1']['course_hours_visible'], true) : null;
                                    //$language_visible = isset($event['event_info1']['course_language_visible']) ? json_decode($event['event_info1']['course_language_visible'], true) : null;

                                    //$certificate_visible = isset($event['event_info1']['course_certification_visible']) ? json_decode($event['event_info1']['course_certification_visible'], true) : null;
                                    //$students_visible = isset($event['event_info1']['course_students_visible']) ? json_decode($event['event_info1']['course_students_visible'], true) : null;
                                    //$elearning_visible = isset($event['event_info1']['course_elearning_visible']) ? json_decode($event['event_info1']['course_elearning_visible'], true) : null;
                                    //if($elearning_visible != null){

                                    //}

                                    //dd($elearning_visible);

                                }
                            ?>
                            <h3><a href="{{$url}}">{{ $until }}</a></h3>
                            <div class="box-footer">
                                <a href="/video-on-demand-courses" class="location"> VIDEO E-LEARNING COURSES</a>
                                <span class="hours">@if(isset($hours_visible['home']) && $hours_visible['home'] && isset($event['event_info1']['course_hours']) && $event['event_info1']['course_hours']) {{ $event['event_info1']['course_hours'] . ' hours'}} @endif</span>
                                {{--<span class="language">@if(isset($language_visible['home']) && $language_visible['home'] && isset($event['event_info1']['course_language']) && $event['event_info1']['course_language'] != null) {{ $event['event_info1']['course_language'] }} @endif</span>
                                <span class="certificate">@if(isset($certificate_visible['home']) && $certificate_visible['home'] && isset($event['event_info1']['course_certification_type']) && $event['event_info1']['course_certification_type'] != null) {{ $event['event_info1']['course_certification_type'] }} @endif</span>

                                <span class="elearning">
                                    @if(isset($elearning_visible['home']) && $elearning_visible['home'] && isset($event['event_info1']['course_elearning_expiration']) && $event['event_info1']['course_elearning_expiration'] != null)
                                    {{ $event['event_info1']['course_elearning_expiration'] }} {{ (isset($event['event_info1']['course_elearning_text']) && $event['event_info1']['course_elearning_text'] != null) ? $event['event_info1']['course_elearning_text'] : '' }}
                                    @endif
                                </span>--}}

                                <?php
                                    $sumStudents = $sumStudentsByCategory[$event['pivot']['category_id']];
                                ?>

                                <span class="students">@if(isset($students_visible['home']) && $students_visible['home'] && isset($event['event_info1']['course_students_number']) && $sumStudents > (int)$event['event_info1']['course_students_number']) {{ $sumStudents }} {{ ((isset($event['event_info1']['course_students_text']) && $event['event_info1']['course_students_text'] != null) ? $event['event_info1']['course_students_text'] : '')}} @endif</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if (isset($event['event_info1']['course_payment_method']) && $event['event_info1']['course_payment_method'] == 'free')
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                    @elseif($event['status'] != App\Model\Event::STATUS_OPEN && $event['status'] != App\Model\Event::STATUS_WAITING)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                                    @elseif($event['status'] == App\Model\Event::STATUS_WAITING)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">JOIN WAITING LIST</a>
                                    @else
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--primary">ENROLL FOR € {{ $event['ticket'][0]['price'] }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@endif
