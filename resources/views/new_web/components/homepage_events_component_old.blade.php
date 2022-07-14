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
        if($data['view_tpl'] == 'elearning_free'){
            $elearningEvents[$key]["free"] = true;

        }else{
            $elearningEvents[$key]["free"] = false;

        }
    }
    foreach ($elearningFree as $key => $data) {
        $elearningFree[$key]["free"] = true;
    }

    // $inclassEvents = array_merge($inclassEvents, $inclassFree);
    $elearningEvents = array_merge($elearningEvents, $elearningFree);
@endphp

@if($homepage["event_types"]->id == 1)
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
                        <a href="{{ $event['slugable']['slug'] }}"><img src="{{ cdn(get_image($event['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
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
                                <span class="date">{{$dateLaunch}}, {{ $event['hours'] }} hours</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if ($data["free"])
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                    @elseif($event['status'] != 0 && $event['status'] != 5)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                                    @elseif($event['status'] == 5)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">JOIN WAITING LIST</a>
                                    @else
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
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

@if($homepage["event_types"]->id == 2)
    <div class="row homepage-events mt-5">
        @foreach($elearningEvents as $data)
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
                        <a href="{{ $event['slugable']['slug'] }}"><img src="{{ cdn(get_image($event['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
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

                            <h3><a href="{{$url}}">{{ $until }}</a></h3>
                            <div class="box-footer">
                                <a href="/video-on-demand-courses" class="location"> VIDEO E-LEARNING COURSES</a>
                                <span class="date">{{ $event['hours'] }} hours</span>
                                @if(isset($event['slugable']) && $event['slugable']['slug'] != '')
                                    @if ($data["free"])
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                    @elseif($event['status'] != 0 && $event['status'] != 5)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                                    @elseif($event['status'] == 5)
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">JOIN WAITING LIST</a>
                                    @else
                                        <a href="{{ $event['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
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
