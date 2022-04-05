@php
    $homepage = [];
    foreach ($column->template->inputs as $input){
        $homepage[$input->key] = $input->value ?? "";
    }

    $nonElearningEvents = $dynamic_page_data["nonElearningEvents"] ?? [];
    $elearningEvents = $dynamic_page_data["elearningEvents"] ?? [];
    $elearningFree = $dynamic_page_data["elearningFree"] ?? [];
    $inclassFree = $dynamic_page_data["inclassFree"] ?? [];
@endphp

<!-- nonElearningEvents -->
@if($homepage["event_types"]->id == 1)
    @foreach($nonElearningEvents as $bcatid => $category)
        <section class="section-text-carousel event-background">
            <div class="container container--md">
                <div class="row-text-carousel clearfix">
                    <div class="text-column">
                        <div class="text-area">
                            <h2>{{$category['name']}}</h2>
                            @if ($category['description'] != '')
                            @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{!!$category['hours']!!}</span>@endif
                            @endif
                            <p>{!!$category['description']!!}</p>
                        </div>
                    </div>
                    <div class="carousel-column">
                        <div class="carousel-wrapper">
                            <div class="boxes-carousel owl-carousel">
                                @foreach($category['events'] as $key => $row)
                                    <div class="slide">
                                        <?php
                                        $string = $row['title'];
                                        if( strpos($string, ',') !== false ) {
                                            $until = substr($string, 0, strrpos($string, ","));
                                        }
                                        else {
                                            $until = $string;
                                        }
                                        ?>

                                        @if ( isset($row['mediable']) && isset($row['slugable']))
                                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                                        @endif

                                        <?php //dd($row->slugable['slug']); ?>

                                        <div class="box-text">
                                        @if(isset($row['slugable']) && $row['slugable']['slug'] != '')
                                            <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>
                                        @endif
                                        @if(isset($row['city']) && count($row['city']) > 0)

                                        <a href="{{ $row['city'][0]['slugable']['slug'] }}" class="location">{{ $row['city'][0]['name'] }}</a>
                                        @endif
                                            <?php $dateLaunch = !$row['launch_date'] ? date('F Y', strtotime($row['published_at'])) : date('F Y', strtotime($row['launch_date']));?>
                                            <span class="date">{{$dateLaunch}} </span>
                                        @if(isset($row['slugable']) && $row['slugable']['slug'] != '')

                                            @if($row['status'] != 0)
                                            <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                                            @else
                                            <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                                            @endif
                                        @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
   @endforeach
@endif

<!-- elearningEvents -->
@if($homepage["event_types"]->id == 2)
    @foreach($elearningEvents as $bcatid => $category)
        <section class="section-text-carousel background section--blue-gradient">
            <div class="container container--md">
                <div class="row-text-carousel clearfix">
                    <div class="text-column">
                    <div class="text-area">

                        <h2>{{ $category['name'] }}</h2>

                            @if ($category['description'] != '')
                                @if($category['hours'])
                                    <span class="duration"><img src="{{ cdn('/theme/assets/images/icons/Start-Finish.svg')}}" class="replace-with-svg" alt=""/>{{ $category['hours'] }}</span>
                                @endif
                            @endif
                            <p>{!!$category['description']!!}</p>
                    </div>
                    </div>
                    <?php //dd($events[0]); ?>
                    <div class="carousel-column">
                    <div class="carousel-wrapper">
                        <div class="boxes-carousel owl-carousel">
                            <?php $lastmonth = '';

                            ?>
                            @foreach($category['events'] as $key => $row)


                            <div class="slide">
                                <?php
                                //dd($row);
                                    $string = $row['title'];
                                    if( strpos($string, ',') !== false ) {
                                        $until = substr($string, 0, strrpos($string, ","));
                                    }
                                    else {
                                        $until = $string;

                                    }
                                    //dd($until);
                                ?>
                                <?php //var_dump($until) ?>
                                @if ( isset($row['mediable']) && isset($row['slugable']))
                                <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                                @endif
                                <div class="box-text">
                                <?php
                                    $string = $row['title'];
                                    if( strpos($string, ',') !== false ) {
                                        $until = substr($string, 0, strrpos($string, ","));
                                    }
                                    else {
                                        $until = $string;
                                    }
                                    ?>


                                    <?php
                                    if(isset($row['slugable'])){
                                        $slug = $row['slugable']['slug'];
                                    }else{
                                        $slug = '';
                                    }

                                    $month = !$row['launch_date'] ? date('F Y', strtotime($row['published_at'])) : date('F Y', strtotime($row['launch_date']));

                                    ?>
                                <?php $url = url($slug); ?>

                                <h3><a href="{{$url}}">{{ $until }}</a></h3>
                                
                                @if(isset($header_menus['elearning_card']['data']['slugable']) )<a href="{{ $header_menus['elearning_card']['data']['slugable']['slug'] }}" class="location"> VIDEO E-LEARNING COURSES</a>@endif
                                <span class="date"> </span>
                                <a href="{{$url}}" class="btn btn--sm btn--secondary">course details</a>

                                </div>
                            </div>


                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
   @endforeach
@endif

<!-- elearningFree -->
@if($homepage["event_types"]->id == 3)
    @foreach($inclassFree as $bcatid => $category)
        <section class="section-text-carousel background event-background">
            <div class="container container--md">
                <div class="row-text-carousel clearfix">
                    <div class="text-column">
                        <div class="text-area">
                            <h2>{{$category['name']}}</h2>
                            @if ($category['description'] != '')
                            @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $category['hours'] }}</span>@endif
                            @endif
                            <p>{!!$category['description']!!}</p>
                        </div>
                    </div>
                    <div class="carousel-column">
                        <div class="carousel-wrapper">
                            <div class="boxes-carousel owl-carousel">
                                <?php $lastmonth = ''; ?>
                                @foreach($category['events'] as $key => $row)
                                    <div class="slide">
                                        <?php
                                            $string = $row['title'];
                                            if( strpos($string, ',') !== false ) {
                                                $until = substr($string, 0, strrpos($string, ","));
                                            }
                                            else {
                                                $until = $string;
                                            }
                                        ?>
                                        @if ( isset($row['mediable']) && isset($row['slugable']))
                                            <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                                        @endif
                                        <div class="box-text">
                                            <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>
                                            <span class="date"></span>
                                            @if($row['view_tpl'] == 'event_free')
                                                <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                            @elseif($row['view_tpl'] == 'event_free_coupon')
                                                <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
   @endforeach
@endif

<!-- inclassFree -->
@if($homepage["event_types"]->id == 4)
    @foreach($elearningFree as $bcatid => $category)
        <section class="section-text-carousel background event-background">
            <div class="container container--md">
                <div class="row-text-carousel clearfix">
                    <div class="text-column">
                        <div class="text-area">

                            <h2>{{$category['name']}}</h2>
                            <?php //dd($until); ?>
                            @if ($category['description'] != '')
                            @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $category['hours'] }}</span>@endif
                            @endif
                            <p>{!!$category['description']!!}</p>
                        </div>
                    </div>
                    <div class="carousel-column">
                        <div class="carousel-wrapper">
                            <div class="boxes-carousel owl-carousel">
                                <?php $lastmonth = ''; ?>
                                @foreach($category['events'] as $key => $row)


                                <div class="slide">
                                <?php

                                    $string = $row['title'];
                                        if( strpos($string, ',') !== false ) {
                                        $until = substr($string, 0, strrpos($string, ","));
                                        }
                                        else {
                                        $until = $string;
                                        }

                                        //dd($row);

                                        ?>
                                    @if ( isset($row['mediable']) && isset($row['slugable']))
                                    <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                                    @endif

                                    <div class="box-text">
                                    <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>

                                    @if(isset($header_menus['elearning_card']['data']['slugable']) )<a href="{{ $header_menus['elearning_card']['data']['slugable']['slug'] }}" class="location"> VIDEO E-LEARNING COURSES</a>@endif
                                    <span class="date"></span>
                                    <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                                    </div>
                                </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
   @endforeach
@endif