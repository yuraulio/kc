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
                                    if($event['eventInfo'] != null ){
                                        $sumStudents = 0;

                                        $hours_visible = $event['eventInfo']['course_hours_visible'] ?? null;
                                    }

                                    if(isset($hours_visible['home']) && $hours_visible['home'] && isset($event['eventInfo']['course_hours']) && $event['eventInfo']['course_hours'] > 0){
                                        $dateLaunch .= ', ' . $event['eventInfo']['course_hours'] . ' hours';
                                    }


                                ?>

                                <span class="date">{{$dateLaunch}}</span>


                                <?php
                                    $sumStudents = $sumStudentsByCategory[$event['pivot']['category_id']];
                                ?>
                                <span class="students">@if(isset($students_visible['home']) && $students_visible['home'] && isset($event['eventInfo']['course_students_number']) && $sumStudents > (int)$event['eventInfo']['course_students_number']) {{ $sumStudents }} {{ ((isset($event['eventInfo']['course_students_text']) && $event['eventInfo']['course_students_text'] != null) ? $event['eventInfo']['course_students_text'] : '')}} @endif</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if (isset($event['eventInfo']['course_payment_method']) && $event['eventInfo']['course_payment_method'] == 'free')
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

                            <img loading="lazy" class="resp-img" src="{{ cdn(get_image($event['mediable'],'event-card')) }}" alt="{{ $until}}" width="{{ get_image_version_details('event-card')['w'] }}" height="{{ get_image_version_details('event-card')['h']}}" /></a>
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

                                if($event['eventInfo'] != null ){
                                    $sumStudents = 0;

                                    $hours_visible = $event['eventInfo']['course_hours_visible'] ?? null;

                                }
                            ?>
                            <h3><a href="{{$url}}">{{ $until }}</a></h3>
                            <div class="box-footer">
                                <a href="/video-on-demand-courses" class="location"> VIDEO E-LEARNING COURSES</a>
                                <span class="hours">@if(isset($hours_visible['home']) && $hours_visible['home'] && isset($event['eventInfo']['course_hours']) && $event['eventInfo']['course_hours']) {{ $event['eventInfo']['course_hours'] . ' hours'}} @endif</span>

                                <?php
                                    $sumStudents = $sumStudentsByCategory[$event['pivot']['category_id']];
                                ?>

                                <span class="students">@if(isset($students_visible['home']) && $students_visible['home'] && isset($event['eventInfo']['course_students_number']) && $sumStudents > (int)$event['eventInfo']['course_students_number']) {{ $sumStudents }} {{ ((isset($event['eventInfo']['course_students_text']) && $event['eventInfo']['course_students_text'] != null) ? $event['eventInfo']['course_students_text'] : '')}} @endif</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if (isset($event['eventInfo']['course_payment_method']) && $event['eventInfo']['course_payment_method'] == 'free')
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
